<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Walletmodel extends CI_Model {

	public $variable;

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

}

/* End of file  */
/* Location: ./application/models/ */