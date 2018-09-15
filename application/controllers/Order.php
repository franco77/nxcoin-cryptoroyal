<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Order extends CI_Controller {
 	
    protected $defaultWalletId = 1;
    protected $defaultWalletBtcId = 47;
    protected $adminBtcAddress = '1EiY1JHBLvRvFFCbE6hit52Bxvz9VwdEFC';
    protected $minprice = [
        'BTC' => 0,
        'USD' => 0.5
    ];
	public function __construct()
	{
		
		parent::__construct();

		$this->output->set_header("Pragma: no-cache");
        $this->output->set_header("Cache-Control: no-store, no-cache");
        $this->output->set_content_type('application/json');
        
        $this->minprice['BTC'] =blockchain_to_btc($this->minprice['USD'],'USD');
    }

    public function cancel(){
        $this->load->model('marketmodel');
        $this->load->model('walletmodel');
        $booking = $this->marketmodel->get_booking(post('id'));
        $amount = $booking->amount;
        $booking_user_id = $booking->user_id;
        if ($booking_user_id == userid()){
            $this->walletmodel->pengurangan('A', ($amount * -1), userid(), 'BOOKING CANCEL');
            $this->marketmodel->deactivateBooking(post('id'));
        }
        //redirect($_SERVER['HTTP_REFERER']);

        if( $booking->type == 'B' && $booking->status == 'A') {
            $price = $booking->price;
            $amount = $booking->amount;

            $total = bcmul($price, $amount, 8);
            $fee = bcdiv( bcmul($total, "1.4", 8), 100, 8);
            $totalSend = bcsub($total, $fee, 8);
            $blockchain = $this->marketmodel->blockchain2;
            $wallet = $this->walletmodel->get_wallet('BTC', $booking->user_id);
        }
        $data = [
            'message' => 'Your order has been canceled.',
            'heading' => 'Success',
            'type' => 'success',
            'status' => 1,
            'csrf_data' => $this->security->get_csrf_hash(),
        ];
        return $this->output->set_output(json_encode($data));
    }
    public function sell() {
        $this->load->model('marketmodel');

        $blockchain = $this->marketmodel->blockchain;
        $btcAddress = $this->marketmodel->getBtcAddress();
        $nxccWallet = $this->walletmodel->get_wallet('A');
        $price = post('price');
        $amount = str_replace(',','',post('amount'));

        $minprice = $this->minprice['BTC'];

        if( $price < $minprice ) {
            return $this->output->set_output(json_encode([
                'message' => 'Minimum price is '.$minprice,
                'heading' => 'failed',
                'type' => 'warning',
                'status' => 0,
                'csrf_data' => $this->security->get_csrf_hash()
            
            ]));
        }
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
            'data' => [
                'pair' => 'nxcc-btc',
                'price' => $price,
                'amount' => $amount,
                'total' => $price * $amount,
                'type' => 'S',
                'time' => sekarang(),
                'bookingId' => $bookingId
            ]
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

        $minprice = $this->minprice['BTC'];

        if( $price < $minprice ) {
            $bestAsk = $this->marketmodel->bestAsk();

            if( $price < $bestAsk ) {
                
            
                return $this->output->set_output(json_encode([
                    'message' => 'Minimum price is '.$bestAsk,
                    'heading' => 'failed',
                    'type' => 'warning',
                    'status' => 0,
                    'csrf_data' => $this->security->get_csrf_hash()
                
                ]));

            }
            
        }

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
            'data' => [
                'pair' => 'nxcc-btc',
                'price' => $price,
                'amount' => $amount,
                'total' => $price * $amount,
                'type' => 'B',
                'time' => sekarang()
            ]
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

    public function lastprice($timeseries = 1800) {
        $prices = $this->marketmodel->lastprice($timeseries);
        return response($prices)->json();
    }

    public function history($type = NULL) {

        $this->load->model('ordermodel');
        
        $start = $this->input->get('start');
        $limit = $this->input->get('length');
        $order = $this->input->get('order');
        $columns = $this->input->get('columns');

        $order_column   = $columns[ $order[0]['column'] ]['data'];
        $order_sort     = $order[0]['dir'];
        $search = $this->input->get('search')['value'];

        $orderHistory = $this->ordermodel->history(
            $start,
            $limit,
            $order_column,
            $order_sort,
            $search
        );
        $recordsTotal = $recordsFiltered = $orderHistory['totalRows'];
        $data = $orderHistory['data'];

        return response([

            'data' => $data,
            'draw' => $this->input->get('draw'),
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered

        ])->json();

    }
    
}