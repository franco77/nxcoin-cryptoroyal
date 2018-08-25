<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Apis extends CI_Controller {
 	

	public function __construct()
	{
		
		parent::__construct();

		$this->output->set_header("Pragma: no-cache");
        $this->output->set_header("Cache-Control: no-store, no-cache");
		$this->output->set_content_type('application/json');

	} 

	public function getLast_price($value='')
	{
		echo $this->walletmodel->getPriceNx();
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function get_bitcoin_payment(){
		// bitcoin payment
		include_once(APPPATH.'libraries/cryptobox/cryptobox.class.php');

		// get amount
		$amount = $this->input->get_post("amount");
		if(isset($amount)){
			$this->session->set_userdata(array("amount"=>$amount));
		}
			
		$amount = $this->session->userdata("amount");

		// $this->db->where('jenis_id', $amount);
		// $data_amount = $this->db->get('tb_jenis_daftar')->row_array();

	    /**** CONFIGURATION VARIABLES ****/ 
		
		$userID 		= $this->config->item('userID');		// you don't need to use userID for unregistered website visitors
		$userFormat		= $this->config->item('userFormat');	// save userID in cookies (or you can use IPADDRESS, SESSION)
		$orderID 		= $this->config->item('orderID');		// Registration Page   
		// $amountUSD		= exchange("BTC", "USD", $amount);	// price per registration - 1 BTC
		$amountUSD		= $amount;	// price per registration - 1 BTC
		$period			= $this->config->item('period');		// one time payment for each new user, not expiry
		$public_key		= $this->config->item('public_key'); 	// from gourl.io
		$private_key	= $this->config->item('private_key');	// from gourl.io
		$def_language	= $this->config->item('def_language');	// default Payment Box Language

		// IMPORTANT: Please read description of options here - https://gourl.io/api-php.html#options  
		
		/********************************/
		
		/** PAYMENT BOX **/
		$options = array(
				"public_key"  => $public_key, 	// your public key from gourl.io
				"private_key" => $private_key, 	// your private key from gourl.io
				"webdev_key"  => "", 		// optional, gourl affiliate key
				"orderID"     => $orderID, 		// order id
				"userID"      => $userID, 		// unique identifier for every user
				"userFormat"  => $userFormat, 	// save userID in COOKIE, IPADDRESS or SESSION
				// "amount"   	  => 0,				// price in coins OR in USD below
				"amountUSD"   => $amountUSD,	// we use price in USD
				"period"      => $period, 		// payment valid period
				"language"	  => $def_language  // text on EN - english, FR - french, etc
		);

		// Initialise Payment Class
		$box = new Cryptobox ($options);
		
		// coin name
		$coinName = $box->coin_name(); 
		
		
		// Optional - Language selection list for payment box (html code)
		$languages_list = display_language_box($def_language);

		// One-time Process Received Payment
		/*if ($box->is_paid( true ) && !$box->is_processed()) 
		{
		// Your code here to handle a successful cryptocoin payment/captcha verification
		// For example, update user membership 
			$box->set_status_processed();
			$status_paid = true;
		} else {
			$status_paid = false;
		}*/



		// deploy
		$data['coinName'] 		= $coinName;
		$data['amountUSD'] 		= $amountUSD;
		$data['is_paid'] 		= $box->is_paid( true ); 
		$data['languages_list'] = $languages_list;
		$data['box'] 			= $box->display_cryptobox(true, 490, 230, "border-radius:15px;");

		echo json_encode($data);
		// $this->output->set_content_type('application/json')->set_output(json_encode($data));
	}


	public function confirm_data()
	{
		$status 	= true;
		$uploaded['file_name'] 	= '';
		if (post('buy_method') == 'btc'){ 
			// bitcoin payment
			include_once(APPPATH.'libraries/cryptobox/cryptobox.class.php');
			
			// deploy
			$def_language	= $this->config->item('def_language');				// default Payment Box Language
			// Optional - Language selection list for payment box (html code)
			$languages_list = display_language_box($def_language);
			$data['languages_list'] = $languages_list;
			$is_payment_failed = 0;



			/**** CONFIGURATION VARIABLES ****/ 
			// $amount = $this->session->userdata("amount");
			$amount 		= post('amount');
			/*$this->db->where('jenis_id', $amount);
			$data_amount = $this->db->get('tb_jenis_daftar')->row_array();*/

			$userID 		= $this->config->item('userID');		// you don't need to use userID for unregistered website visitors
			$userFormat		= $this->config->item('userFormat');	// save userID in cookies (or you can use IPADDRESS, SESSION)
			$orderID 		= $this->config->item('orderID');		// Registration Page   
			$amountUSD		= $amount;	// price per registration - 1 BTC
			$period			= $this->config->item('period');		// one time payment for each new user, not expiry
			$public_key		= $this->config->item('public_key'); 	// from gourl.io
			$private_key	= $this->config->item('private_key');	// from gourl.io

			
			/********************************/
			
			/** PAYMENT BOX **/
			$options = array(
					"public_key"  => $public_key, 	// your public key from gourl.io
					"private_key" => $private_key, 	// your private key from gourl.io
					"webdev_key"  => "", 			// optional, gourl affiliate key
					"orderID"     => $orderID, 		// order id
					"userID"      => $userID, 		// unique identifier for every user
					"userFormat"  => $userFormat, 	// save userID in COOKIE, IPADDRESS or SESSION
					"amount"   	  => 0,				// price in coins OR in USD below
					"amountUSD"   => $amountUSD,	// we use price in USD
					"period"      => $period, 		// payment valid period
					"language"	  => $def_language  // text on EN - english, FR - french, etc
			);


			// Initialise Payment Class
			$box = new Cryptobox ($options);

			if ( ! $box->is_paid(true) ){
				$status		= false;  
				$message 	= alerts('<h4>Bitcoin belum diterima. </ H4> <p> Jika Anda telah mengirim Bitcoin (jumlah Bitcoin yang tepat dalam satu pembayaran seperti yang ditunjukkan pada kotak di bawah), mohon tunggu beberapa menit untuk menerimanya melalui Sistem Pembayaran Bitcoin. Jika Anda mengirim jumlah lain, Sistem Pembayaran akan mengabaikan transaksi dan Anda harus mengirim jumlah yang benar lagi, atau menghubungi pemilik situs untuk meminta bantuan.</p> ', 'danger');
			} 
		}else{

			$config['upload_path'] = './uploads/confirm/';
			$config['allowed_types'] = 'gif|jpg|png|jpeg';
			$config['max_size']  = '3500';
			$config['max_width']  = '2048';
			$config['max_height']  = '1000';
			
			$this->load->library('upload', $config);
			
			if ( $this->upload->do_upload() ){
				$uploaded 	= $this->upload->data();
			}else {
				$uploaded['file_name'] 	= '';
				$status = false;
				$message=alerts($this->upload->display_errors(), 'danger');
			}
		}


		if ( $status  ) {
			
			$insert_data = array(
				'confirm_userid' 	=> userid(),
				'confirm_amount' 	=> post('amount'),
				'confirm_invoice' 	=> post('invoice'),  
				'confirm_image' 	=> $uploaded['file_name'], 
				'confirm_status' 	=> 'pending', 
			); 


			if (post('buy_method') == 'btc'){ 

				$insert_data['confirm_method'] = 'BTC';

				$box->cryptobox_reset();
				$this->session->unset_userdata('amount');
			}

			$this->db->insert('tb_confirm', $insert_data);
			$message = alerts('Berhasil dikirim','success');

		}

		report(userid(),'','User melakukan konfirmasi pembayaran untuk invoice '.post('invoice'));
		$this->session->set_flashdata('konfirm',$message); 
		// marstekind@gmail.com
		$usernamenya = userdata(array('id' => userid() ))->username;
		$isi = 'Berikut data User yang melakukan konfirmasi melalui Bank Transfer <br>
			<table border="0">
			<tr>
				<td>Username : </td><td>'.$usernamenya.'</td>
			</tr><tr>
				<td>Nomor Invoice : </td><td>'.post('invoice').'</td>
			</tr><tr>
				<td>Jumlah Transfer : </td><td>'.post('amount').'</td>
			</tr>
			</table>
		';
		$this->mailermodel->send('marstekind@gmail.com', 'Konfirmasi Pembayaran '.$usernamenya.' ', $isi);

		redirect('','refresh'); 
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function postdata( $functionName = null )
	{
		
		$this->load->model('postusermodel');
		echo json_encode( $this->postusermodel->$functionName() );
	}


	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function get_package_from_range( $amount = 0 )
	{
		
		$result 	= alerts('Package not available for this amount !', 'danger') . '<script>$("#buy_gets_wallet").prop("disabled", true)</script>';

		if( is_numeric( $amount ) ){
			$this->db->where( $amount . ' BETWEEN package_range_start AND package_range_end');
			$get 		= $this->db->get('tb_packages');
			if ( $get->num_rows() > 0) {

				$result_data 	= $get->row();
				$result 		= $this->load->view('partials/package_view', $result_data, true);
				$result 		.= '<script>$("#buy_gets_wallet").prop("disabled", false)</script>';

			}
		}

		echo $result;

	}





	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	/*public function get_package_from_range()
	{
		
		$data['status'] 	= false;
		$data['message'] 	= null;
		$data['heading'] 	= 'Failed';
		$data['type'] 		= 'error';
		$data['csrf_data']	= $this->security->get_csrf_hash();

		$this->form_validation->set_rules('amount_wallet', 'amount activation', 'trim|required|numeric|greater_than_equal_to[100]');
		$this->form_validation->set_rules('account_password', 'current password', 'trim|required');
		if ($this->form_validation->run() == FALSE) {
			$data['status'] 	= false;
			$data['message'] 	= validation_errors(' ', '<br/>');			
		}

		if ( ! $this->input->post() ) {
			$data['status'] 	= false;
			$data['message'] 	= 'This method not allowd !';
		}

		$this->db->where( post('amount') . ' BETWEEN package_range_start AND package_range_end');
		$get 		= $this->db->get('tb_packages');
		if ( $get->num_rows() == 0) {
			$data['status'] 	= false;
			$data['message'] 	= 'Package not available for this amount !';
		}


		if ( $data['status'] ) {
			$result_data 			= $get->row();
			$data['message'] 		= $this->load->view('partials/package_view', $result_data, true);
		}

		return $result;

	}*/


	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function validate2FAAuth()
	{
		$post 	= $this->input->post();
		$data['status'] 	= true;
		$data['message'] 	= null;
		$data['heading'] 	= null;
		$data['type'] 		= null;

		//validate 2FA
		// get the user's phone code and the secret code that was generated, and verify
		$checkResult = $this->googleauthenticator->verifyCode( userdata()->gauth_secret , $this->input->post('oneCode'), 2); // 2 = 2*30sec clock tolerance
		if ( ! $checkResult) {
			$data['status'] 	= false;
			$data['message'] 	= 'One Code authentication not valid';
			$data['heading'] 	= 'Failed';
			$data['type'] 		= 'error';
		}

		if( empty($post['oneCode']) || ( ! isset( $post['oneCode'] ) ) ){
			$data['status'] 	= false;
			$data['message'] 	= 'The secret one code required';
			$data['heading'] 	= 'Failed';
			$data['type'] 		= 'error';
		}

		if ( $data['status'] ) {
			//create session valid auth
			$array = array(
				'gauth_status' => 'valid'
			);
			$this->session->set_userdata( $array );

			$data['status'] 	= true;
			$data['message'] 	= 'Two Factor authentication verified';
			$data['heading'] 	= 'Success';
			$data['type'] 		= 'success';

		}

		echo json_encode( $data );
	}
	
	/*=====  End of FUNCTION UNTUK AUTHENTICATION  ======*/

}

/* End of file Apis.php */
/* Location: ./application/controllers/Apis.php */