<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Wallet extends CI_Controller {


    protected $address = [
        'BTC' => null,
        'NXCC' => null
    ];
    private $blockchainConfig = [
        'guid'				=> '431a22e0-e708-40de-b7cd-fb67d8d4290c',
        'main_password' 	=> 'Bismillah123',
        'second_password'	=> '252525',
        'api_code'			=> "0d39755a-7844-4f56-9c3d-83594417b912",
        'base_url'			=> 'http://127.0.0.1/',
        'port'				=> '3000',
    ];

    public function __construct()
	{
		
        parent::__construct();

        $has_btc_wallet = $this->marketmodel->hasBtcWallet();
        $this->address['BTC'] = ($has_btc_wallet) ? $has_btc_wallet->wallet_address : $this->marketmodel->create_user_btc_wallet();
    }

    public function balance($wallet = 'btc') {
        
        $this->benchmark->mark('s');

        $balance = 0;

        if( method_exists($this,$wallet) ) {
            $method = strtolower($wallet);
            $balance = $this->{$method}();
        }

        $this->benchmark->mark('e');
        return response([
            'data' => [
                'address' => $this->address[ strtoupper($wallet) ],
                'balance' => $balance,
            ],
            'execution_time' => $this->benchmark->elapsed_time('s', 'e')
        ])->json();
    }

    private function btc() {
        $btc = $this->marketmodel->blockchain->address_balance(
            $this->address['BTC']
        );
        if(array_key_exists('balance',$btc)) {

            return convertToBTCFromSatoshi($btc['balance']);

        }
        return 0;

    }
    private function nxcc() {
        return currency($this->walletmodel->cek_balance('A'),'2','');
    }

    public function listing() {

        if( !$this->ion_auth->is_login() && !$this->ion_auth->is_admin() ) {

            return response([
                'status' => 0,
                'message' => 'unAuthorized Access!'
            ], 401)->json();

        }
        $blockchain = new Blockchain($this->blockchainConfig);
        $addresses = $blockchain->list_addresses();

        return response([
            'status' => 1,
            'data' => $addresses
        ], 200)->json();
    }

}