<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Order extends CI_Controller {
 	
    protected $defaultWalletId = 1;
    protected $defaultWalletBtcId = 40;
    protected $adminBtcAddress = '1EiY1JHBLvRvFFCbE6hit52Bxvz9VwdEFC';

	public function __construct()
	{
		
		parent::__construct();

		$this->output->set_header("Pragma: no-cache");
        $this->output->set_header("Cache-Control: no-store, no-cache");
		$this->output->set_content_type('application/json');

    }

    public function sell() {
        $this->load->model('marketmodel');

        $blockchain = $this->marketmodel->blockchain;
        $btcAddress = $this->marketmodel->getBtcAddress();
        $nxccWallet = $this->walletmodel->get_wallet('A');

        $price = post('price');
        $amount = str_replace(',','',post('amount'));

        if(!$btcAddress) {

            return $this->output->set_output(json_encode([
                'message' => 'Your btc wallet not created yet, please contact web admin.',
                'heading' => 'failed',
                'type' => 'warning',
                'status' => 0,
                'csrf_data' => $this->security->get_csrf_hash()
            
            ]));

        }

        if( !$nxccWallet ) {

            return $this->output->set_output(json_encode([
                'message' => 'Your nxcc wallet not created yet, please contact web admin.',
                'heading' => 'failed',
                'type' => 'warning',
                'status' => 0,
                'csrf_data' => $this->security->get_csrf_hash()
            
            ]));

        }
        $availBalance = $this->walletmodel->cek_balance('A');
        if( $availBalance < $amount ) {

            return $this->output->set_output(json_encode([
                'message' => 'Your nxcc balance is insuficient.',
                'heading' => 'failed',
                'type' => 'warning',
                'status' => 0,
                'csrf_data' => $this->security->get_csrf_hash()
            ]));

        }

        $bookingId = $this->marketmodel->create_booking(
            $nxccWallet->wallet_id,
            $this->defaultWalletId,
            $amount,
            'nxcc-btc',
            $price,
            'S'
        );

        $data = [
            'message' => 'Your order has been created.',
            'heading' => 'failed',
            'type' => 'success',
            'status' => 1,
            'csrf_data' => $this->security->get_csrf_hash(),
        ];
        $matches = $this->marketmodel->findMatch($price, userid(), 'B');

        if( !empty($matches) ) {

            $this->marketmodel->proccessMatch($bookingId, $matches);

        }

        return $this->output->set_output(json_encode($data));
    }

    public function buy() {
        $this->load->model('marketmodel');

        $blockchain = $this->marketmodel->blockchain;
        $btcWallet = $this->walletmodel->get_wallet('BTC');
        $nxccWallet = $this->walletmodel->get_wallet('A');

        $price = post('price');
        $amount = str_replace(',','',post('amount'));

        if(!$btcWallet) {

            return $this->output->set_output(json_encode([
                'message' => 'Your btc wallet not created yet, please contact web admin.',
                'heading' => 'failed',
                'type' => 'warning',
                'status' => 0,
                'csrf_data' => $this->security->get_csrf_hash()
            
            ]));

        }

        if( !$nxccWallet ) {

            return $this->output->set_output(json_encode([
                'message' => 'Your nxcc wallet not created yet, please contact web admin.',
                'heading' => 'failed',
                'type' => 'warning',
                'status' => 0,
                'csrf_data' => $this->security->get_csrf_hash()
            
            ]));

        }
        $btcBalance = $this->marketmodel->blockchain->address_balance($btcWallet->wallet_address);

        if(!array_key_exists('balance', $btcBalance)) {

            return $this->output->set_output(json_encode([
                'message' => 'Sorry, we cannot proccess your request now.',
                'heading' => 'failed',
                'type' => 'warning',
                'status' => 0,
                'csrf_data' => $this->security->get_csrf_hash()
            ]));

        }
        $btcBalance = convertToBTCFromSatoshi($btcBalance['balance']);

        $total = $amount * $price;
        $fee = ($total * 1.4) / 100;
        $totalPay = $total + $fee;

        if( $btcBalance < $totalPay ) {

            return $this->output->set_output(json_encode([
                'message' => 'Your BTC balance is insuficient.',
                'heading' => 'failed',
                'type' => 'warning',
                'status' => 0,
                'csrf_data' => $this->security->get_csrf_hash()
            ]));

        }

        $btcSend = $this->marketmodel->blockchain->send(
            $this->adminBtcAddress,
            convertToSatoshi($total),
            $btcWallet->wallet_address,
            $fee
        );
        if( array_key_exists('success', $btcSend ) ) {

            if( !$btcSend['success'] ) {

                return $this->output->set_output(json_encode([
                    'message' => 'Sorry, we cannot proccess your request now.',
                    'heading' => 'failed',
                    'type' => 'warning',
                    'status' => 0,
                    'csrf_data' => $this->security->get_csrf_hash()
                ]));

            }

        }

        $bookingId = $this->marketmodel->create_booking(
            $btcWallet->wallet_id,
            $this->defaultWalletBtcId,
            $amount,
            'nxcc-btc',
            $price,
            'B'
        );

        $data = [
            'message' => 'Your order has been created.',
            'heading' => 'failed',
            'type' => 'success',
            'status' => 1,
            'csrf_data' => $this->security->get_csrf_hash(),
        ];

        $matches = $this->marketmodel->findMatch($price, userid(), 'S');

        if( !empty($matches) ) {

            $this->marketmodel->proccessMatch($bookingId, $matches);

        }

        return $this->output->set_output(json_encode($data));
    }

    public function pending() {

        $pendings = $this->marketmodel->pending_orders();

        return $this->output->set_output(json_encode([
            'success' => 1,
            'data' => $pendings
        ]));


    }

    public function lastprice() {
        $prices = $this->marketmodel->lastprice();
        return $this->output->set_output(json_encode($prices));
    }
    
}