<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Walletbtc extends CI_Controller {

    private $blockchainConfig = [
        'guid'				=> '431a22e0-e708-40de-b7cd-fb67d8d4290c',
        'main_password' 	=> 'Bismillah123',
        'second_password'	=> '252525',
        'api_code'			=> "0d39755a-7844-4f56-9c3d-83594417b912",
        'base_url'			=> 'http://127.0.0.1/',
        'port'				=> '3000',
    ];
    public $blockchain = NULL;

    public function __construct() {

        parent::__construct();
        $this->blockchain = new Blockchain($this->blockchainConfig);

    }

    public function withdraw() {

        $receiver = $this->input->post('receiver');
        $wallet_sender = $this->walletmodel->get_wallet( 'BTC', userid() );
        $amount = $this->input->post('amount');

        $success = TRUE;
        $message = '';

        if(!$receiver) {

            $message = 'Please fill receiver address';
            $success = FALSE;

        }
        if(!$amount) {
            
            $message = 'Please fill amount';
            $success = FALSE;

        }
        $amount = str_replace(',','', $amount);

        if(!is_numeric($amount)) {
            $message = 'Please fill amount with numeric only';
            $success = FALSE;
        }
        
        if(!$wallet_sender) {

            $message = 'Your wallet btc not created yet, please contact web admin';
            $success = FALSE;

        }

        


        if(!$success) {

            return response([

                'status' => 0,
                'message' => $message,
                'heading' => 'Failed',
                'type'  => 'error',
                'csrf_data' => $this->security->get_csrf_hash()

            ], 500)->json();
        }



        $sent = $this->walletmodel->withdraw_btc( userid(), $amount, $wallet_sender, $receiver );

        if( !$sent['status'] ) {

            return response([
                
                'status' => 0,
                'message' => 'Sorry we cannot process your request now.',
                'heading' => 'Failed',
                'type'  => 'error',
                'csrf_data' => $this->security->get_csrf_hash(),
                'btcSend' => $sent['btcSend'],
                'feeSend' => $sent['feeSend'],
                'grand_total' => $sent['grand_total']
            ], 500)->json();

        }

        return response([

            'status' => 1,
            'message' => 'Withdraw Btc Success',
            'heading' => 'Success',
            'type'  => 'success',
            'csrf_data' => $this->security->get_csrf_hash(),
            'btcSend' => $sent['btcSend'],
            'feeSend' => $sent['feeSend'],
            'grand_total' => $grand_total

        ], 200)->json();
    }
}