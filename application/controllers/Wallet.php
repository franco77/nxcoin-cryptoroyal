<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Wallet extends CI_Controller {


    protected $address = [
        'BTC' => null,
        'NXCC' => null
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

        sleep(3); //remove this
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
        return 0.0000549; // remove this
        $btc = $this->marketmodel->blockchain->address_balance(
            $this->address['BTC']
        );
        if(array_key_exists('balance',$btc)) {

            return convertToBTCFromSatoshi($btc['balance']);

        }
        return 0;

    }
    private function nxcc() {
        //return 1000; //remove this
        return (float) number_format($this->walletmodel->cek_balance('A'),8);
    }

}