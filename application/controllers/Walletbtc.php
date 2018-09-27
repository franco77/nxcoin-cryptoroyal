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

        // $btcBalance = $this->marketmodel->blockchain->address_balance($wallet_sender->wallet_address);
        // if(!array_key_exists('balance', $btcBalance)) {

            
        //     $message = 'Sorry, we cannot proccess your request now.';
        //     $success = FALSE;

        // } else if( $btcBalance['balance'] < $amount ) {
        //     $message = 'Your BTC Balance is insuficient';
        //     $success = FALSE;
        // }

        


        if(!$success) {

            return response([

                'status' => 0,
                'message' => $message,
                'heading' => 'Failed',
                'type'  => 'error',
                'csrf_data' => $this->security->get_csrf_hash()

            ], 500)->json();
        }



        //$sent = $this->walletmodel->withdraw_btc( userid(), $amount, $wallet_sender, $receiver );
        $this->load->model('withdrawmodel');

        $request = $this->withdrawmodel->get_request(userid(), 'BTC', 'waiting');

        if( $request->num_rows() > 0 ) {

            $request = $request->row();

            if( $this->withdrawmodel->is_request_expired( $request->req_created_at ) ) {

                $this->withdrawmodel->cancel_request($request->id_request);
                

            } else {
                
                return response([
                    'status' => 0,
                    'message' => 'You already request withdraw, please check your email',
                    'heading' => 'Failed',
                    'type' => 'error',
                    'csrf_data' => $this->security->get_csrf_hash(),
                ])->json();

            }

        }


        $sent = $this->withdrawmodel->create_request( userid(), $amount, $wallet_sender, $receiver );
        if( !$sent['status'] ) {

            return response([
                
                'status' => 0,
                'message' => 'Sorry we cannot process your request now.',
                'heading' => 'Failed',
                'type'  => 'error',
                'csrf_data' => $this->security->get_csrf_hash(),
                'wd_res' => $sent
            ], 500)->json();

        }

        return response([

            'status' => 1,
            'message' => 'Request Withdraw Btc Success, Please check your email to validate request',
            'heading' => 'Success',
            'type'  => 'success',
            'csrf_data' => $this->security->get_csrf_hash(),
            'wd_res' => $sent

        ], 200)->json();
    }
    
    public function confirm_request_wd() {

        $code = get('code');
        $this->load->model('withdrawmodel');
        $request = $this->withdrawmodel->find_code($code);
        if(!$request) {
            $message = 'Request not found!';
        } else {
            $userid         = $request->req_user_id;
            $amount         = $request->req_amount;
            $wallet_sender  = $this->walletmodel->get_wallet('BTC', $request->req_user_id);
            $receiver       = $request->req_wallet_receiver;

            $sent = $this->walletmodel->withdraw_btc( $userid, $amount, $wallet_sender, $receiver );
            if( !$sent['status'] ) {
                $message = 'Confirmation Failed';
            } else {
                $this->withdrawmodel->approve_request( $request->id_request );
                $message = 'Your Withdraw request have been confirmed';
            }
        }

        $this->load->view('static/wd-confirm', [
            'message' => $message
        ]);

    }
}