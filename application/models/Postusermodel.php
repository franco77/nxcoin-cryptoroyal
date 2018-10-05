<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Postusermodel extends CI_Model {


	/**
	 * undocumented function
	 *
	 * @return void
	 * @author Ayatulloh Ahad Robanie [ayatulloh@idprogrammer.com]
	 **/

	private $ticket_price = [
		'USD' => 11,
		'NXCC' => NULL
	];
	

	public function __construct() {

		$nx_price = $this->marketmodel->get_latest_price('USD');
		$this->ticket_price['NXCC'] = $ticket_price_nxcc = bcdiv("11", $nx_price, 8);
		
	}

	

	public function doRegister()
	{

		$data['status'] 	= true;
		$data['message'] 	= null;
		$data['heading'] 	= 'Failed';
		$data['type'] 		= 'error';
		$data['csrf_data']	= $this->security->get_csrf_hash();


		/* validate referra; */
		$referral_id 			= 1;

		if( $this->input->post('user_referral') ){

			///validate referral id
			$user_referral 		= userdata( ['username' => post('user_referral') ] );
			if ( ! $user_referral ) {
				$data['status'] 	= false;
				$data['message'] 	= 'Referral username invalid or not available !';
			}else{
				$referral_id 	= $user_referral->id;
			}

		}

		//jika ada session referral yang digunakan seswion referral
		if ( $this->session->userdata('referralID') ) {
			$referral_id 		= userdata( ['username' => $this->session->userdata('referralID') ] )->id;
		}
		

		/* validate form */
		$this->form_validation->set_rules('username', 'username', 'trim|required|min_length[6]|max_length[20]|is_unique[tb_users.username]');
		$this->form_validation->set_rules('password', 'password', 'required|min_length[6]|max_length[20]');
		$this->form_validation->set_rules('password', 'confirm password', 'trim|required|matches[password]');
		$this->form_validation->set_rules('email', 'email address', 'required|valid_email|is_unique[tb_users.email]');
		$this->form_validation->set_rules('check', 'Term and Conditions', 'required');
		if ($this->form_validation->run() == false) {
			$data['status'] 	= false;
			$data['message'] 	= validation_errors(' ', '<br/>');
		}


		if( $data['status'] ){



			//register berhasil
			$generate_pin		= random_string('numeric', 6 );

			//create new user here
			$additional_data 	= array(
				'referral_id' 	=> $referral_id,
				'upline_id' 	=> $referral_id,
				'user_code'		=> $generate_pin,

				'user_fullname'	=> post( 'user_fullname' )

			);
			$this->ion_auth->register( post('username'), post('password'), post('email'), $additional_data, array(2) );

			$get_id_last = $this->db->get_where('tb_users', array('username' => post('username')) )->row()->id;

			//set session for resend email activation
			$this->session->set_userdata('activation_mail',[
				'user_id' => $get_id_last,
				'email' => post('email'),
				'activation' => $generate_pin
			]);

			$object = array(
				array(
					'wallet_userid' => $get_id_last,
					'wallet_type'	=> 'A',
					'wallet_address'=> generateWallet(),
					'wallet_amount'	=> '0'
				),
				array(
					'wallet_userid' => $get_id_last,
					'wallet_type'	=> 'B',
					'wallet_address'=> generateWallet(),
					'wallet_amount'	=> '0'
				),
				array(
					'wallet_userid' => $get_id_last,
					'wallet_type'	=> 'C',
					'wallet_address'=> generateWallet(),
					'wallet_amount'	=> '0'
				),
			);
			$this->db->insert_batch('tb_wallet', $object);

			//unset referral session
			$this->session->unset_userdata('referralID');

			$data['message'] 	= 'Registration success';
			$data['heading'] 	= 'Successfull';
			$data['type'] 		= 'success';

		}		

		return $data;

	}

	public function buy_by_ticket()
	{ 
		$this->mainmodel->Always_Load();
		$data['status'] 	= true;
		$data['message'] 	= null;
		$data['heading'] 	= 'Failed';
		$data['type'] 		= 'error';
		$userdata 			= userdata();
		$data['csrf_data']	= $this->security->get_csrf_hash();

		$ticket_price = $this->ticket_price['NXCC'];

		$walleta = $this->walletmodel->cek_balance('A', $userdata->id);
		if(!$ticket_price) {
			$data['status'] 	= false; 
			$data['message'] 	= 'Error 500';
		}
		if ( bcsub((string)$walleta, $ticket_price, 8) < 0 ){
			$data['status'] 	= false; 
			$data['message'] 	= 'Your Balance is Insuficient';
		}

		$this->db->order_by('rollover_id', 'desc');
		$this->db->where('rollover_userid', userid());
		$a = $this->db->get('tb_rollover');
		if ($a->num_rows() > 0){
			$datetime = new DateTime($a->row()->rollover_date);
			$datetime->modify('+1 hour');
			$jam = $datetime->format('Y-m-d H:i:s');
			if ( date('Y-m-d H:i:s') <= $jam){
				$data['status'] 	= false; 
				$data['message'] 	= 'In 1 hour you only buy 1 ticket';
			}
		}

		if ($this->stackingmodel->get_stacking() == false){
			$data['status'] 	= false; 
			$data['message'] 	= 'You need to have an active package';
		}

		$data['kelas'] = '0';
		$this->db->where('rollover_class', '1'); 
		$this->db->where('rollover_userid', userid());
		if ($this->db->get('tb_rollover')->num_rows() > 0){  
			$data['kelas'] = '0';
		}else{
			$data['kelas'] = '1';
		} 
		$data['data'] = $ticket_price;
		if ($data['status']){ 

			$jumlah_rollover = $this->db->get('tb_rollover')->num_rows();
			$walleta = $this->walletmodel->cek_balance('A', $userdata->id); 
			
			if ($walleta >= 11){
				$object = array(
					'rollover_userid'	=> $userdata->id,
					'rollover_class' 	=> $data['kelas'],
					'rollover_amount' 	=> $ticket_price,
					'rollover_txid'		=> generateTxid(),
					'rollover_date'		=> date('Y-m-d H:i:s')
				);
				$this->db->insert('tb_rollover', $object);
				$this->walletmodel->pengurangan('A', $ticket_price);
				if ($data['kelas'] != '0'){
					if ($this->mainmodel->updateUrutan('3','1') == true){
						$this->mainmodel->updateUrutan('5','2');
					}
				}
			} 
			
			$data['heading'] 	= 'Success';
			$data['type'] 		= 'success';
			$data['status'] 	= true;  
			$data['message'] 	= 'Success Buy One Ticket';
			
		}
		$this->mainmodel->Always_Load();
		////////////////////////////////////////////////////////////////////////////////////////////
		return $data;
	}

	public function cek_price_nx()
	{
		$data = file_get_contents('https://api.nxcoin.io/api/example/price?X-API-KEY=123456789');
		return json_decode($data)->value;
	}

	public function getcoin($user = '')
	{
		$userid = ($user == '')? userid() : $user;
		$data = file_get_contents('https://api.nxcoin.io/api/example/walletamount?X-API-KEY=123456789&userid='.$userid);
		return json_decode($data)->value;
	}

	public function cek_for_stacking($amount = '')
	{
		$status 	= false;
		$amount = ($amount == '')? get('amount') : $amount;
		
		$balanceA = $this->walletmodel->cek_balance('A');
		$balanceB = $this->walletmodel->cek_balance('B');
		
		if ( ($amount - ($amount*0.1)) <= $balanceA ){
			if (($amount*0.1) <= $balanceB){
				$status 	= true;
			}else{
				$status 	= false;
			}
		}

		return $status;
	}


	public function cek_data_before_send(){
		$data['status'] 	= true;
		$data['message'] 	= null;
		$data['heading'] 	= 'Failed';
		$data['type'] 		= 'error';
		$data['csrf_data']	= $this->security->get_csrf_hash();
		$userdata = userdata(); 

		if (post('trfMode') == 'A'){
			$wallet_type = 'A';
		}else{
			$wallet_type = 'B';
		}  

		$balance = $this->walletmodel->cek_balance($wallet_type);
		if (post('amount') > $balance){
			$data['status'] 	= false;
			$data['message'] 	= 'Wallet Amount Insuficient';
		}

		if (!empty(post('oneCode'))) {
			$checkResult = $this->googleauthenticator->verifyCode( $userdata->gauth_secret , post('oneCode'), 2);
			if ( ! $checkResult) {
			    $data['status'] 	= false;
				$data['message'] 	= 'Code Authenticator invalid'; 
			}
		}

		$this->form_validation->set_rules('amount', 'Amount', 'trim|required|greater_than[0]');
		$this->form_validation->set_rules('address', 'Wallet Address', 'trim|required');

		if ($this->form_validation->run() == FALSE) {
			$data['status'] 	= false;
			$data['message'] 	= validation_errors(' ','<br>');
		}

		$cek_length = strlen(post('address'));
		if ( $cek_length == 35 ){
		    $data['id'] = post('address');
		    $data['method'] = false; 
		}else{ 
    		$this->db->where('wallet_address', post('address') );
			$get_wallet = $this->db->get('tb_wallet');
			if ($get_wallet->num_rows() > 0){
				$get_wallet2 = $get_wallet->row();
				$data['id'] = $get_wallet2->wallet_userid; 
    			$data['method'] = true; 
			}else{
				$data['status'] 	= false;
				$data['message'] 	= 'Invalid wallet Address'; 
			}
    		
		}

		if ($data['status']){
			$data['message'] 	= 'Success';
			$data['heading'] 	= 'Success';
			$data['type'] 		= 'success';
		}

		return $data;
	}
	public function sendWallet($value='')
	{
		$data['status'] 	= true;
		$data['message'] 	= null;
		$data['heading'] 	= 'Failed';
		$data['type'] 		= 'error';
		$data['csrf_data']	= $this->security->get_csrf_hash();
		$userdata = userdata();

		$cek_length = strlen($this->input->get_post('address'));
		if ( $cek_length == 35 ){
		    $data['id'] = $this->input->get_post('address');
		    $data['method'] = false;
		}else{ 
    		$this->db->where('wallet_address', $this->input->get_post('address') );
			$get_wallet = $this->db->get('tb_wallet')->row();
    		$data['id'] = $get_wallet->wallet_userid; 
    		$data['method'] = true;
		}

		if ($this->input->get_post('trfMode') == 'A'){
			$wallet_type = 'A';
		}else{
			$wallet_type = 'B';
		} 

		if ($data['method']){

			$this->db->where('wallet_address', $this->input->get_post('address') );
			$get_wallet = $this->db->get('tb_wallet');
			if ($get_wallet->num_rows() == 0){
				$data['status'] 	= false;
				$data['message'] 	= 'Wallet not found';
			}else{
				$get_w = $get_wallet->row();
				if ($get_w->wallet_type != $this->input->get_post('trfMode')){
					$data['status'] 	= false;
					$data['message'] 	= 'This wallet is in different class';
				}
				if ($get_w->wallet_userid == userid()){
					$data['status'] 	= false;
					$data['message'] 	= 'Cannot send amount to yourself';
				}
			} 
		}
		if ($data['status']){ 

			$data['message'] 	= 'Success';
			$data['heading'] 	= 'Success';
			$data['type'] 		= 'success';
		}

		return $data;
	}



	public function success_send()
	{
		$data['status'] 	= true;
		$data['message'] 	= null;
		$data['heading'] 	= 'Failed';
		$data['type'] 		= 'error';
		$data['csrf_data']	= $this->security->get_csrf_hash();
		$userdata = userdata(); 

		if ($this->input->get_post('trfMode') == 'A'){
			$wallet_type = 'A';
		}else{
			$wallet_type = 'B';
		}  

		$cek_length = strlen($this->input->get_post('address'));
		if ( $cek_length == 35 ){
		    $data['id'] = $this->input->get_post('address');
		    $data['method'] = false;
		}else{
    		$this->db->where('wallet_address', $this->input->get_post('address') );
    		$this->db->order_by('wallet_id', 'asc');
			$get_wallet = $this->db->get('tb_wallet')->row();
    		$data['id'] = $get_wallet->wallet_userid; 
    		$data['method'] = true;
		}

		

		if ($data['method']){
			$object = array(
				'wallet_userid' => $data['id'],
				'wallet_type'	=> $wallet_type,
				'wallet_amount' => $this->input->get_post('amount'),
				'wallet_desc'	=> 'Wallet Transfer'
			);
			$this->db->insert('tb_wallet', $object); 
		}	
		$object2 = array(
			'wallet_userid' => userid(),
			'wallet_type'	=> $wallet_type,
			'wallet_amount' => ($this->input->get_post('amount') * -1),
			'wallet_desc'	=> 'Transfer Amount Wallet To '.$this->input->get_post('address')
		);
		$this->db->insert('tb_wallet', $object2);
		
		

		$data['message'] 	= 'Success';
		$data['heading'] 	= 'Success';
		$data['type'] 		= 'success'; 
		return $data;
	}

	public function cek_package($value = '')
	{
		$status = true;
		if ($value == ''){
			$amount = $this->input->get_post('amount');
		}else{
			$amount = $value;
		}
		if (($amount * 1) != $amount){
			$status 	= false;
		}else{
			$this->db->where(' "'. $amount .'" BETWEEN package_price_low AND package_price_high ');
			$get = $this->db->get('tb_package');
			if ($get->num_rows() < 1){
				$status 	= false;
			}else{
				$status = $get->row();
			} 
		}
		return $status;
	}

	public function startStacking()
	{
		$data['status'] 	= true;
		$data['message'] 	= null;
		$data['heading'] 	= 'Failed';
		$data['type'] 		= 'error';
		$data['csrf_data']	= $this->security->get_csrf_hash();
		$userdata = userdata();

		if (! $this->input->post()){
			$data['status'] 	= false;
			$data['message'] 	= 'Method Disabled';
		}
 
		if (post('use10')){
			if ( ($this->cek_for_stacking(post('amount'))) == false ){
				$data['status'] 	= false;
				$data['message'] 	= 'Amount to staking is not in accordance with the rule';
			}
		}else{
			$balance = $this->walletmodel->cek_balance('A');
			if (post('amount') > $balance){
				$data['status'] 	= false;
				$data['message'] 	= 'Wallet Amount Insuficient, try to use some amount form wallet B ';
			}
		}  

		if (! empty( post('oneCode') ) ) {
			$checkResult = $this->googleauthenticator->verifyCode( $userdata->gauth_secret , post('oneCode'), 2);
			if ( ! $checkResult) {
			    $data['status'] 	= false;
				$data['message'] 	= 'Code Authenticator invalid'; 
			}
		}

		if (empty(post('amount'))){
			$data['status'] 	= false;
			$data['message'] 	= 'Amount to staking is to low';
		}

		$this->form_validation->set_rules('amount', 'Amount', 'trim|required|greater_than[0]');
		if ($this->form_validation->run() == FALSE) {
			$data['status'] 	= false;
			$data['message'] 	= validation_errors(' ','<br>');
		}else{
			//cek package  
			if ($this->cek_package(post('amount')) == false){
				$data['status'] 	= false;
				$data['message'] 	= 'Package Invalid';
			}
		}

		$this->db->where('stc_userid', userid());
		$cek_available = $this->db->get('tb_stacking')->num_rows();
		if ($cek_available > 0){
			$data['status'] 	= false;
			$data['message'] 	= 'You already staking';
		}

		$cek_isi = explode(',', post('amount'));
		if (isset($cek_isi[1])){
			$data['status'] 	= false;
			$data['message'] 	= 'Comma (,) is disabled, try using dot (.)';
		}

		// cek if send -1 or more
		if (post('amount') < 0){
			$data['status'] 	= false;
			$data['message'] 	= 'Amount to staking is to low';
		} 

		if ($data['status']){
			$amount = post('amount');
			$amountB = 0;
			$amountA = $amount;
			if (post('use10')){
				$amountB = ($amount*0.1);
				$amountA = ($amount - $amountB);
			}
			$end_date 	= date('Y-m-d', strtotime('+168 days', strtotime( sekarang() )));
			$object = array(
				'stc_userid'		=> userid(),
				'stc_amount'		=> $amount,
				'stc_package'		=> $this->cek_package(post('amount'))->package_id,
				'stc_date_start' 	=> sekarang(),
				'stc_date_end' 		=> $end_date,

			);
			$this->db->insert('tb_stacking', $object);
			$next_profit_date 	=  date('Y-m-d', strtotime('+7 days', strtotime( sekarang() )));
			$this->db->update('tb_users', [
						'next_profit' 	=> $next_profit_date
					], [
						'id'			=> userid()
					]);
			$object2 = array(
				array(
					'wallet_userid' => 1,
					'wallet_type'	=> 'A',
					'wallet_amount' => $amountA,
					'wallet_desc'	=> 'Staking User '.userdata()->username
				),
				array(
					'wallet_userid' => userid(),
					'wallet_type'	=> 'A',
					'wallet_amount' => ($amountA * -1),
					'wallet_desc'	=> 'Staking'
				) 
			);
			$this->db->insert_batch('tb_wallet', $object2);
			if ($amountB > 0){
				$object3 = array( 
					array(
						'wallet_userid' => 1,
						'wallet_type'	=> 'B',
						'wallet_amount' => $amountB,
						'wallet_desc'	=> 'Staking User '.userdata()->username
					),
					array(
						'wallet_userid' => userid(),
						'wallet_type'	=> 'B',
						'wallet_amount' => ($amountB * -1),
						'wallet_desc'	=> 'Staking'
					)
				);
				$this->db->insert_batch('tb_wallet', $object3);
			}
			// run bonus active generasi
			$this->pembagian_bonus($amount, userdata(array('id' => userid()))->referral_id );


			$data['message'] 	= 'Success';
			$data['heading'] 	= 'Success';
			$data['type'] 		= 'success';
		}
		$this->mainmodel->Always_Load();

		return $data;
	}  


	private function pembagian_bonus($amount='',$upline='')
	{
		$query = $this->db->query('SELECT `id`, `username`, `referral_id`, `position`
		FROM ( SELECT * FROM tb_users ORDER BY `id`, `referral_id` ) 
			tb_users_sorted, (SELECT @pv := '.$upline.' ) initialisation
		WHERE find_in_set(`id`, @pv) > 0
		AND @pv := concat(@pv, ",", `referral_id`)
		ORDER BY id DESC');
		$generasi = 0;
		foreach ($query->result() as $key) { 
			if ($generasi <= 9){
				if ($this->stackingmodel->get_stacking($key->id) != false){
					$bonus = json_decode($this->stackingmodel->get_stacking($key->id)->package_sponsor);
					if ( isset($bonus[$generasi]) ){
						$get = $amount * ($bonus[$generasi]/100);
						$this->bonusmodel->insert($key->id,$get,$generasi);
					} 
				}
				$generasi++;
			}else{
				break;
			}
		}
		$this->mainmodel->Always_Load();
	}

	/*==============================================
	=            FUNCTION UNTUK PROFILE            =
	==============================================*/
	

	public function save_profile()
	{
		$post 	= $this->input->post();
		$data['status'] 	= true;
		$data['message'] 	= null;
		$data['heading'] 	= 'Failed';
		$data['type'] 		= 'error';
		$data['csrf_data']	= $this->security->get_csrf_hash();

		$change_password 	= false;

		if( ( post('old_password') != null ) && (post('new_password') != null ) ){

			//checking old password
			$this->form_validation->set_rules('old_password', 'current password', 'trim|required');
			$this->form_validation->set_rules('new_password', 'new password', 'trim|required|min_length[6]');
			$this->form_validation->set_rules('confirm_password', 'confirm password', 'trim|required|matches[new_password]');

			if( ! $this->ion_auth->hash_password_db( userid(), post( 'old_password' ) ) ){
				$data['status'] 	= false;
				$data['message'] 	= 'Your current password not valid !'; 
			}else{

				$post['password'] 	= post('new_password');
				
			}

		}

		$this->load->library('AddressValidator');
		$validate_address 	= AddressValidator::isValid( post( 'user_btc' ) );
		if( ! $validate_address ){
			$data['status'] 	= false;
			$data['message'] 	= 'Invalid blockchain address !';
		}


		$array_field 		= array('user_fullname', 'email');
		foreach ($array_field as $field) {
			$this->form_validation->set_rules( $field , $field, 'required');
		}
		if ($this->form_validation->run() == FALSE ) {
			$data['status'] 	= false;
			$data['message'] 	= validation_errors('', '<br/>');
		}


		if ( $data['status'] ) {




			$this->ion_auth->update( userid(), $post ); 

			$data['status'] 	= true;
			$data['message'] 	= 'Profile successfully saved !';
			$data['heading'] 	= 'Success';
			$data['type'] 		= 'success';
		}

		return $data;

	}



	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function two_factor_activation() 
	{
		
		$data['status'] 	= true;
		$data['message'] 	= null;
		$data['csrf_data']	= $this->security->get_csrf_hash();

		$checkResult = $this->googleauthenticator->verifyCode( post('secret') , post('oneCodeAuth'), 2); // 2 = 2*30sec clock tolerance
		if ( ! $checkResult) {
		    $data['status'] 	= false;
			$data['message'] 	= 'Code Authenticator invalid';
			$data['heading'] 	= 'Failed';
			$data['type'] 		= 'error';
		}

		if ( empty( post('oneCodeAuth') ) ) {
			$data['status'] 	= false;
			$data['message'] 	= 'One Code Authenticator is Required';
			$data['heading'] 	= 'Failed';
			$data['type'] 		= 'error';
		}

		if ( $data['status'] ) {

			//save to userdata
			$this->ion_auth->update( userid(), array( 'gauth_status' => 'on', 'gauth_secret' => post('secret') ) ); 
			$data['status'] 	= true;
			$data['message'] 	= 'Two Factor Authentication Activated !';
			$data['heading'] 	= 'Success';
			$data['type'] 		= 'success';
		}

		return $data;

	}	


	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function two_factor_remove()
	{
		
		$data['status'] 	= true;
		$data['message'] 	= 'Two Factor Authentication Removed !';
		$data['heading'] 	= 'Success';
		$data['type'] 		= 'success';
		$data['csrf_data']	= $this->security->get_csrf_hash();

		//validate code
		$userdata 	= userdata();
		$checkResult = $this->googleauthenticator->verifyCode($userdata->gauth_secret, post('oneCodeAuth'), 2); // 2 = 2*30sec clock tolerance
		if ( ! $checkResult) {
		   	$data['status'] 	= false;
			$data['message'] 	= 'Code Authenticator invalid';
			$data['heading'] 	= 'Failed';
			$data['type'] 		= 'error';
		}

		if( empty( post('oneCodeAuth') ) ){
			$data['status'] 	= false;
			$data['message'] 	= 'One Code Authenticator is Required';
			$data['heading'] 	= 'Failed';
			$data['type'] 		= 'error';
		}

		if ( $data['status'] ) {
			//remove it
			$this->ion_auth->update( userid(), array( 'gauth_status' => 'off', 'gauth_secret' => null ) ); 
		}

		return $data;

	}

	public function validate2FA()
	{
		
		$post 	= $this->input->post();
		$data['status'] 	= true;
		$data['message'] 	= null;
		$data['csrf_data']	= $this->security->get_csrf_hash();
		$secret = userdata()->gauth_secret;

		$checkResult = $this->googleauthenticator->verifyCode($secret, $post['oneCodeAuth'], 2); // 2 = 2*30sec clock tolerance
		if ( ! $checkResult) {
		    $data['status'] 	= false;
			$data['message'] 	= 'Code Authenticator not valid';
			$data['heading'] 	= 'Failed';
			$data['type'] 		= 'error';
		}

		if ( empty( $post['oneCodeAuth'] ) ) {
			$data['status'] 	= false;
			$data['message'] 	= 'One Code Authenticator is Required';
			$data['heading'] 	= 'Failed';
			$data['type'] 		= 'error';
		}

		if ( $data['status'] ) {
			$array = array(
				'gauth_status' => 'valid'
			);
			$this->session->set_userdata( $array );
			$data['status'] 	= true;
			$data['message'] 	= 'Welcome Back !';
			$data['heading'] 	= 'Success';
			$data['type'] 		= 'success';
		}

		return $data;

	}
	
	
	/*=====  End of FUNCTION UNTUK PROFILE  ======*/ 


	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function doLogin()
	{

		$data['status'] 	= true;
		$data['message'] 	= null;
		$data['csrf_data']	= $this->security->get_csrf_hash();

		/*//validate recaptcha
		$captcha_answer = $this->input->post('g-recaptcha-response');
		$response = $this->recaptcha->verifyResponse($captcha_answer);
		if ( $response['success'] != true) {
			
			$data['status'] 	= false;
			$data['message'] 	= 'Please verify Captcha security !';
		
		}else{*/
			
			$do_login 			= $this->ion_auth->login( post('username'), post('password'), true );
			if ( ! $do_login ) {
				$data['status'] 	= false;
				$data['message'] 	= $this->ion_auth->errors();	
			}

		// }


		

		if ( $data['status'] ) {
			$data['heading'] 	= 'Login Successful';
			$data['type'] 		= 'success';
			$data['message'] 	= 'Click to proceed !';
		}else {
			$data['heading'] 	= 'Login unsuccessful'; 
			$data['type'] 		= 'error';
		}

		return $data;

	}


 


}

/* End of file Postusermodel.php */
/* Location: ./application/models/Postusermodel.php */