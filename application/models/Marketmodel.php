<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Marketmodel extends CI_Model {

	public $variable;
    public $blockchain2;
    protected $defaultWalletId = 1;
    protected $adminBtcAddress = '1EiY1JHBLvRvFFCbE6hit52Bxvz9VwdEFC';
    protected $defaultBtcWalletId = 40;
    protected $adminUserId = 1;
    protected $btcPrice = 0.5;
	public function __construct()
	{
        parent::__construct();
        
        $this->load->library('blockchain');
        
        $this->blockchain2 = new Blockchain([
            'guid'				=> '431a22e0-e708-40de-b7cd-fb67d8d4290c',
            'main_password' 	=> 'Bismillah123',
            'second_password'	=> '252525',
            'api_code'			=> "0d39755a-7844-4f56-9c3d-83594417b912",
            'base_url'			=> 'http://127.0.0.1/',
            'port'				=> '3000',
        ]);

        //$this->defaultWallet = $this->walletmodel->get_wallet('A',1);
		
    }

    public function hasBtcWallet() {
		$wallet = $this->db->select('*')->from('tb_wallet')
            ->where('wallet_userid', userid())
            ->where('wallet_address is not NULL', NULL, FALSE)
			->where('wallet_type','BTC')->get();
		if( $wallet->num_rows() < 1 ) {
            return false;
        }
        return $wallet->row();
    }

    public function getBtcAddress($userid = null) {

        $userid = ($userid == null) ? userid() : $userid;
        
        $wallet = $this->db->select('wallet_address')->from('tb_wallet')
            ->where('wallet_userid', $userid)
            ->where('wallet_address is not NULL', NULL, FALSE)
            ->where('wallet_type','BTC')->get();
            
		if( $wallet->num_rows() < 1 ) {
            return false;
        }
        return $wallet->row()->wallet_address;
    }
    
    public function create_user_btc_wallet() {


        $label = 'cr_'.userdata()->username; // tambahkan prefix cr untuk label wallet btc;
        $newAddress = $this->blockchain2->new_address($label);

        if( !array_key_exists('address', $newAddress) ) {
            return false;
        }

        $btcAddress = $newAddress['address'];

        $inserted = $this->db->insert('tb_wallet', [
            'wallet_userid' => userid(),
            'wallet_type'   => 'BTC',
            'wallet_amount' => '0',
            'wallet_desc'   => '',
            'wallet_date'   => date('Y-m-d H:i:s'),
            'wallet_address'=> $btcAddress
        ]);
        if( $inserted ) {
            return $btcAddress;
        }
        return false;

    }

    //get balance wallet by id shit!
    public function get_balance_by_id($walletId) {
        $res = $this->db->select('wallet_amount')
        ->from('tb_wallet')
        ->where('wallet_id', $walletId)
        ->get()->row();
        return $res->wallet_amount;
    }


    public function create_booking( $from_wallet, $to_wallet, $amount, $pairs, $price, $type, $userid = null ) {

        $userid = ($userid == null) ? userid() : $userid;
        $from_wallet = $this->db->select('*')->where('wallet_id', $from_wallet)->get('tb_wallet')->row();
        $to_wallet = $this->db->select('*')->where('wallet_id', $to_wallet)->get('tb_wallet')->row();
        $this->db->trans_begin();

        $transactions = [
            [
                'wallet_id' => $from_wallet->wallet_id,
                'type' => 'D',
                'notes' => 'BOOKING ORDER',
                'amount' => $amount,
                'created_at' => sekarang()
            ],
            [
                'wallet_id' => $to_wallet->wallet_id,
                'type' => 'C',
                'notes' => 'BOOKING ORDER',
                'amount' => $amount,
                'created_at' => sekarang()
            ]
        ];
        $incr_decr_amount = ($type == 'S') ? $amount : ($amount * $price);
        $this->db->insert_batch('tb_transactions',$transactions);
        $insWallet = [
            [
                'wallet_userid' => $from_wallet->wallet_userid,
                'wallet_type' => $from_wallet->wallet_type,
                'wallet_amount' => '-'.$incr_decr_amount,
                'wallet_desc' => 'BOOKING ORDER',
            ],
            [
                'wallet_userid' => $to_wallet->wallet_userid,
                'wallet_type' => $to_wallet->wallet_type,
                'wallet_amount' => $incr_decr_amount,
                'wallet_desc' => 'BOOKING ORDER',
            ]
        ];
        $this->db->insert_batch('tb_wallet', $insWallet);
        $this->db
        ->insert('tb_booking_orders', [
            'user_id' => $userid,
            'pairs' => $pairs,
            'price' => $price,
            'amount' => $amount,
            'status' => 'A',
            'type' => $type,
            'created_at' => sekarang()
        ]);
        $insertId = $this->db->insert_id();
        $this->db->trans_complete();
        return $insertId;
    }

    public function pending_orders($byUser = TRUE, $type = NULL) {

        $this->db->select('*')->where('status', 'A');

        if($byUser) {

            $this->db->where('user_id',userid());

        }
        if($type !== NULL) {
            $this->db->where('type',$type);
        }
        $res = $this->db->get('tb_booking_orders')->result();

        if( !$res ) {
            return [];
        }

        return $res;
    }

    public function findMatch($price, $userid, $type) {

        return $this->db->select('*')
        ->from('tb_booking_orders')
        ->where('price', $price)
        ->where('user_id !=', $userid)
        ->where('type', $type)
        ->where('deleted_at', null)
        ->where('status', 'A')
        ->order_by('booking_id','asc')
        ->get()->result();

    }

    public function proccessMatch($bookingId, $matches) {

        $booking = $this->db->where('booking_id', $bookingId)->get('tb_booking_orders')->row();

        foreach( $matches as $match ) {
            
            if( $booking->amount <= 0 ) {
                return;
            }

            $matchAmount = 0;
            $totalBtc = 0;
            $pairs = 'nxcc-btc';
            if( $booking->amount == $match->amount ) {
                
                $this->deactivateBooking($match->booking_id);
                $this->deactivateBooking($booking->booking_id);
                $matchAmount = $match->amount;
                $booking->amount = 0;
            } else if( $booking->amount > $match->amount ) {

                $this->deactivateBooking($match->booking_id);
                $matchAmount = $match->amount;
                $booking->amount = $booking->amount - $match->amount;
                $this->decrementBooking($booking->booking_id, $booking->amount);
            }
            else {

                $this->deactivateBooking($booking->booking_id);
                $matchAmount = $booking->amount;
                $this->decrementBooking($match->booking_id, $match->amount - $booking->amount);
                $booking->amount = 0;

            }

            $buyerId = ($match->type == 'B') ? $match->user_id : $booking->user_id;
            $sellerId = ($match->type == 'S') ? $match->user_id : $booking->user_id;

            $sellId = ($match->type == 'S') ? $match->booking_id : $booking->booking_id;
            $buyId = ($match->type == 'B') ? $match->booking_id : $booking->booking_id;

            $totalBtc =  bcmul( (string) $match->price, (string) $matchAmount, 8);
            $fee = bcdiv( bcmul($totalBtc, "1.4", 8), "100", 8);
            $totalSendBtc = bcsub( (string) $totalBtc, (string) $fee, 8);

            $this->incrNxccBalance($buyerId, $matchAmount);
            $this->incrBtcBalance($sellerId, $totalSendBtc, $fee);
            $this->create_order($buyId, $sellId, $match->price, $matchAmount);

        }

    }

    public function deactivateBooking($bookingId) {
        $this->db
            ->set('status', 'I')
            ->where('booking_id', $bookingId)
            ->update('tb_booking_orders');
    }

    public function decrementBooking( $bookingId, $amount ) {

        $this->db
            ->set('amount', $amount)
            ->where('booking_id', $bookingId)
            ->update('tb_booking_orders');
    }

    public function incrNxccBalance( $userid, $amount ) {

        $wallet = $this->walletmodel->get_wallet('A',$userid);

        $this->db->trans_begin();

        $transactions = [
            [
                'wallet_id' => $this->defaultWalletId,
                'type' => 'D',
                'notes' => 'INTERNAL MARKET',
                'amount' => $amount,
            ],
            [
                'wallet_id' => $wallet->wallet_id,
                'type' => 'C',
                'notes' => 'INTERNAL MARKET',
                'amount' => $amount,
            ]
        ];

        $this->db->insert_batch('tb_transactions',$transactions);

        // $updateAdminAmount = $this->get_balance_by_id($this->defaultWalletId) - $amount;
        // $updateTargetAmount = $wallet->wallet_amount + $amount;

        $insertTrans = [
            [
                'wallet_userid' => $this->adminUserId,
                'wallet_type' => 'A',
                'wallet_amount' => '-'.$amount,
                'wallet_desc' => 'INTERNAL MARKET-NXCC',
                'wallet_date' => date('Y-m-d H:i:s')
            ],
            [
                'wallet_userid' => $userid,
                'wallet_type' => 'A',
                'wallet_amount' => $amount,
                'wallet_desc' => 'INTERNAL MARKET-NXCC',
                'wallet_date' => date('Y-m-d H:i:s')
            ]
        ];
        $this->db->insert_batch('tb_wallet',$insertTrans);

        // $this->db->where('wallet_id', $this->defaultWalletId)
        // ->set('wallet_amount', $updateAdminAmount)
        // ->update('tb_wallet');

        // $this->db->where('wallet_id', $wallet->wallet_id)
        // ->set('wallet_amount', $updateTargetAmount)
        // ->update('tb_wallet');

        $this->db->trans_complete();


    }

    public function incrBtcBalance( $userId, $amount, $fee ) {


        $btcWallet = $this->walletmodel->get_wallet('BTC', $userId);
        $transactions = [
            [
                'wallet_id' => $this->defaultBtcWalletId,
                'type' => 'D',
                'notes' => 'INTERNAL MARKET-BTC',
                'amount' => $amount,
            ],
            [
                'wallet_id' => $btcWallet->wallet_id,
                'type' => 'C',
                'notes' => 'INTERNAL MARKET-BTC',
                'amount' => $amount,
            ]
        ];
        $this->db->insert_batch('tb_transactions',$transactions);
        $sent = $this->blockchain2->send(
            $btcWallet->wallet_address,
            convertToSatoshi($amount),
            $this->adminBtcAddress,
            convertToSatoshi($fee)
        );
    }

    public function create_order( $buyId, $sellId, $price, $amount, $pairs = 'nxcc-btc') {
        
        $data = [
            'price'         => $price,
            'amount'        => $amount,
            'buy_id'        => $buyId,
            'sell_id'       => $sellId,
            'pair'          => $pairs,
            'created_at'    => date('Y-m-d H:i:s'),
            'unix_timestamp'=> time(),
        ];
        // $data = [
            //     [
            //         'user_id' => $buyerId,
            //         'price' => $price,
            //         'amount' => $amount,
            //         'pair' =>$pairs,
            //         'created_at' => date('Y-m-d H:i:s'),
            //         'unix_timestamp' => time(),
            //     ],
            //     [
            //         'user_id' => $sellerId,
            //         'price' => $price,
            //         'amount' => $amount,
            //         'pair' =>$pairs,
            //         'created_at' => date('Y-m-d H:i:s'),
            //         'unix_timestamp' => time(),
            //     ]
        // ];
        $this->db->insert('tb_orders', $data);

    }
    
    public function get_latest_price($currency = 'BTC'){
        
        $result = $this->db->query("SELECT * FROM tb_orders order by order_id desc")->row();
        if( !$result ) {

            $price = blockchain_to_btc($this->btcPrice, 'USD');

        } else {

            $price = $result->price;

        }
        switch ($currency) {
            case 'USD':
                $price = bcmul("$price",(string) blockchain_exchange('USD'),8);
                break;
            
            default:
                break;
        }
        //return $this->db->query("SELECT * FROM tb_orders order by order_id desc")->row()->price;
        return $price;
    }
        
    public function get_booking($id=''){
        return $this->db->select('*')
        ->from('tb_booking_orders')
        ->where('booking_id', $id)
        ->get()->row();
    } 
    
	public function lastprice( $div = 1800 )
	{
        return $this->db->query("
        SELECT *, TRUNCATE ( ( ( ( d.close_price - d.open_price ) / d.close_price ) * 100), 2 ) as changes FROM (
            SELECT
                MAX(price) as high_price, 
                SUM(amount) as volume,
                COUNT(order_id) as count_booking,
                MIN(price) as low_price, 
                MAX(created_at) as created_at,
                SUBSTRING_INDEX(GROUP_CONCAT(CAST(price AS CHAR)  ORDER BY created_at DESC SEPARATOR ','), ',', 1 ) as close_price,
                SUBSTRING_INDEX(GROUP_CONCAT(CAST(price AS CHAR)  ORDER BY created_at SEPARATOR ','), ',', 1 ) as open_price,
                UNIX_TIMESTAMP(created_at) DIV $div AS timekey

            FROM tb_orders
            WHERE created_at > DATE_SUB(CURDATE(), INTERVAL 30 DAY)
            GROUP BY timekey
            ORDER BY timekey DESC
        ) as d
        ")->result();
		// return $this->db->select("MAX(price) as high_price, 
        //     SUM(amount) as volume,
        //     COUNT(booking_id) as count_booking,
		//     MIN(price) as low_price, 
        //     MAX(created_at) as created_at,
		//     SUBSTRING_INDEX(GROUP_CONCAT(CAST(price AS CHAR)  ORDER BY created_at DESC SEPARATOR ','), ',', 1 ) as close_price,
		//     SUBSTRING_INDEX(GROUP_CONCAT(CAST(price AS CHAR)  ORDER BY created_at SEPARATOR ','), ',', 1 ) as open_price,
		//     UNIX_TIMESTAMP(created_at) DIV 1800 AS timekey")
		//     ->from('tb_booking_orders')
		//     ->where('type', 's')
		//     ->where('created_at <', 'DATE_SUB(CURDATE(), INTERVAL 1 DAY)')
		//     ->group_by('timekey')
		//     ->get()->result();
    }
    public function bestAsk($pairs = 'nxcc-btc') {
        return $this->db
            ->select('price')
            ->from('tb_booking_orders')
            ->where('pairs',$pairs)
            ->where('type','S')
            ->where('status','A')
            ->order_by('price','asc')
            ->limit(1)
            ->get()->row()->price;
    }



    

}