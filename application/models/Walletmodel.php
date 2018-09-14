<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Walletmodel extends CI_Model {

	public $variable;

	private $blockchainConfig = [
        'guid'				=> '431a22e0-e708-40de-b7cd-fb67d8d4290c',
        'main_password' 	=> 'Bismillah123',
        'second_password'	=> '252525',
        'api_code'			=> "0d39755a-7844-4f56-9c3d-83594417b912",
        'base_url'			=> 'http://127.0.0.1/',
        'port'				=> '3000',
	];
	
	private $blockchain_fee = '1.4';
	private $cryptoroyal_fee = '1.1';

	private $admin_wallet = [
		'BTC' => [
			'ID' => 47,
			'ADDRESS' => '1EiY1JHBLvRvFFCbE6hit52Bxvz9VwdEFC'
		],
	];

	public function __construct()
	{
		parent::__construct();
		
	}

	public function cek_balance($type='A',$user = '')
	{
		$user = ($user == '')? userid() : $user;
		$this->db->select_sum('wallet_amount');
		$this->db->where('wallet_userid', $user);
		$this->db->where('wallet_type', $type);
		$a = $this->db->get('tb_wallet')->result();

		return array_shift($a)->wallet_amount;
	}

// fungsi sementara untuk development internal market
	public function cek_btc_balance($userid = 1)
	{
		$userid = ($userid == '')? userid() : $userid;
		$a = $this->db->select('wallet_amount')->from('tb_wallet')
			->where('wallet_userid', $userid)
			->where('wallet_type','BTC')->row()->wallet_amount;
        
        if($userid == 43){
		    return array("balance" => $a);
        }else{
            return null;
        }
	}
// end fungsi
	public function pengurangan($type='A', $amount ='0',$user='', $desc='Pembelian Rollover')
	{
		$user = ($user == '')? userid() : $user;
		$object = array(
			array(
				'wallet_userid' => 1,
				'wallet_type'	=> $type,
				'wallet_amount' => $amount,
				'wallet_desc'	=> $desc
			),
			array(
				'wallet_userid' => $user,
				'wallet_type'	=> $type,
				'wallet_amount' => ($amount * -1),
				'wallet_desc'	=> $desc
			)
		);
		$this->db->insert_batch('tb_wallet', $object);
	}

	public function getPriceNx()
	{
		// tak ganti di https://nxcoin.io/account/apis/get_price jadi harganya 0.5
		$url = "https://nxcoin.io/account/apis/get_price";
		$ch = curl_init();
		curl_setopt ($ch, CURLOPT_URL, $url);
		curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, 5);
		curl_setopt ($ch, CURLOPT_RETURNTRANSFER, true);
		$contents = curl_exec($ch);
		if (curl_errno($ch)) {
		  echo curl_error($ch);
		  echo "\n<br />";
		  $contents = '';
		} else {
		  curl_close($ch);
		}

		if (!is_string($contents) || !strlen($contents)) {
		echo "Failed to get contents.";
		$contents = '';
		}

		return $contents;
	}
	
	// function tambahan
	public function get_wallet($type='A', $userid = NULL){
	    
		$userid = ( $userid == NULL ) ? userid() : $userid;
        
        $wallet = $this->db->select('*')->from('tb_wallet')
			->where('wallet_userid', $userid)
			->where('wallet_address is NOT NULL', NULL, FALSE)
            ->where('wallet_type',$type)->get();
            
		if( $wallet->num_rows() < 1 ) {
            return false;
        }
        return $wallet->row();
	}

	public function withdraw_btc( $userid, $amount, $wallet, $receiver_address ) {
		
		$amount = str_replace( ',' ,'' , $amount );

		$blockchain_fee		= bcdiv( bcmul( $amount, $this->blockchain_fee, 8 ), "100", 8);
		$cryptoroyal_fee 	= bcdiv( bcmul( $amount, $this->cryptoroyal_fee, 8 ), "100", 8 );
		$cr_fee_send = bcdiv( bcmul( $cryptoroyal_fee, "30", 8 ), "100", 8 );
		$cr_total_fee_received = bcsub( $cryptoroyal_fee, $cr_fee_send, 8 );
		
		$total_sent = bcsub( $amount, $blockchain_fee,8 );
		$total_sent = bcsub( $total_sent, $cryptoroyal_fee, 8 );
		$grand_total = bcadd( bcadd($total_sent, $blockchain_fee,8), $cryptoroyal_fee, 8);

		


		$sender_address = $wallet->wallet_address;
		
		$this->db->trans_begin();

		$data = [
			
			[ //increment cryptoroyal fee ke wallet admin
				'wallet_userid' => $this->admin_wallet['BTC']['ID'],
				'wallet_type' => 'BTC',
				'wallet_amount' => $cryptoroyal_fee,
				'wallet_desc' => 'WITHDRAW_FEE',
				'wallet_date' => sekarang()
			],
			[ //decrement amount ke wallet user
				'wallet_userid' => $wallet->wallet_id,
				'wallet_type' => 'BTC',
				'wallet_amount' => '-'.$amount,
				'wallet_desc' => 'WITHDRAW_TO : '. $receiver_address,
				'wallet_date' => sekarang()
			]
		];
		
		$this->db->insert_batch('tb_wallet', $data);

		if( $this->db->trans_status() === FALSE ) {
			
			$this->db->trans_rollback();
			return false;

		}

		$blockchain = new Blockchain($this->blockchainConfig);

		$total_sent			= convertToSatoshi($total_sent);
		$blockchain_fee 	= convertToSatoshi($blockchain_fee);
		$cryptoroyal_fee	= convertToSatoshi($cryptoroyal_fee);
		$cr_fee_send		= convertToSatoshi($cr_fee_send);
		$cr_total_fee_received = convertToSatoshi($cr_total_fee_received);

		$processed_data =[
			'total_sent' => $total_sent,
			'blockchain_fee' => $blockchain_fee,
			'cryptoroyal_fee' => $cryptoroyal_fee,
			'cr_fee_send' => $cr_fee_send,
			'cr_total_fee_received' => $cr_total_fee_received,
			'sender_address' => $sender_address
		];

		$btcSend = $blockchain->send( $receiver_address, $total_sent, $sender_address, $blockchain_fee );
		$feeSend = $blockchain->send( $this->admin_wallet['BTC']['ADDRESS'], $cr_total_fee_received, $sender_address, $cr_fee_send);
		
		if( array_key_exists('success', $btcSend ) && array_key_exists('success', $feeSend ) ) {

            if( !$btcSend['success'] && !$feeSend['success']) {

				$this->db->trans_rollback();
				$response = [
					'status' => FALSE,
					'btcSend' => $btcSend,
					'feeSend' => $feeSend,
					'grand_total' => $grand_total,
					'processed_data' => $processed_data
				];
				return $response;

            }

		} else {
			$this->db->trans_rollback();
			$response = [
				'status' => FALSE,
				'btcSend' => $btcSend,
				'feeSend' => $feeSend,
				'grand_total' => $grand_total,
				'processed_data' => $processed_data
			];
			return $response;
		}

		$this->db->trans_commit();
		$response = [
			'status' => TRUE,
			'btcSend' => $btcSend,
			'feeSend' => $feeSend,
			'grand_total' => $grand_total,
			'processed_data' => $processed_data
		];
		return $response;



	}

}

/* End of file  */
/* Location: ./application/models/ */