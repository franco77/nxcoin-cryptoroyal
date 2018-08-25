<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Usermodel extends CI_Model {

	public $variable;

	public function __construct()
	{
		parent::__construct();
		
	}

	public function modalajax( $filename = 'null' )
	{

		if ( file_exists( VIEWPATH . 'modal/' . $filename . '.php' ) ) {
			echo $this->load->view('modal/' . $filename, [], true);
		}

	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function is_active( $userid = null)
	{

		$status_active 				= false;
		$userid = ( $userid == null ) ? userid() : $userid ;

		$get 	= $this->lendingmodel->get(array(
			'lending_userid' 		=> $userid,
			'lending_dateend >=' 	=> date('Y-m-d H:i:s')
		));

		if ( $get != false ) {
			$status_active 				= true;
		}

		return $status_active;
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function getUserPaket($userid='')
	{
		$userid = ($userid == '')? userid() : $userid;
		$this->db->where('id', $userid);
		$this->db->join('tb_users', 'user_package = package_id', 'left');
		$a = $this->db->get('tb_packages');

		return $a->row()->package_name;
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function getRandomUplineIDRight( $userid = 1, $position = 'right' )
	{

		$data 	= $this->get_jaringan( $userid, 'WHERE `position`="'.$position.'"' );

		// return $data;
		$daftar_upline 	= array();
		foreach ($data as $value) {
			
			if( $value->position == $position ){

				$daftar_upline[] 	= $value->upline_id;
			
			}

		}

		foreach ($data as $data) {
			
			if ( $data->position == $position ) {
				
				if ( ! in_array( $data->id , $daftar_upline )  ) {
					
					// return $data->id;
					$return 	= $data->id;

				}

			}

		}

		if( empty( $return ) ){
			$return 	= $userid;
		}


		return $return;
		
	}


	public function getFoot($userid='1', $position = 'right')
	{
		$user 		= $this->get_jaringan( $userid , ' WHERE upline_id="'.$userid.'" and position = "'.$position.'" ');
		$totalkaki  = 0;

		foreach ($user as $value) {	
			$get = $this->get_jaringan( $value->id );
			foreach ($get as $value2 ){
				$totalkaki++;
			}
			$totalkaki++;
		}
		return $totalkaki;
	}

 
	public function totalFoot( $userid = '1', $position = 'left' )
	{
		$user 		= $this->get_jaringan( $userid , ' WHERE upline_id="'.$userid.'" and position = "'.$position.'" ');
		$totalomset  = 0;

		foreach ($user as $value) {	
			$get 			= $this->get_jaringan( $value->id );
			$this->db->join('tb_users', 'tb_users.id = tb_lending.lending_userid', 'left');
			$this->db->where('tb_users.lock_profit', 'false');
			$this->db->where('lending_userid', $value->id);
			$getlending 	= $this->db->get('tb_lending');

			if ($getlending->num_rows() > 0){
				foreach ($getlending->result() as $key) {
					$totalomset = $totalomset + $key->lending_amount;
				}
			}
			foreach ($get as $value2 ){

				$this->db->join('tb_users', 'tb_users.id = tb_lending.lending_userid', 'left');
				$this->db->where('tb_users.lock_profit', 'false');
				$this->db->where('lending_userid', $value2->id);
				$getlending2 	= $this->db->get('tb_lending');

				if ($getlending2->num_rows() > 0){
					foreach ($getlending2->result() as $key) {
						$totalomset = $totalomset + $key->lending_amount;
					}
				}
			}
		}
		return $totalomset;

	}



	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function get_jaringan( $user_id = null, $where = null )
	{

		$userid = ( $user_id == null ) ? userid() : $user_id ;

		$get = $this->db->query('
			SELECT  `id`,
			        `username`,
			        `upline_id`,
			        `position`
			from    (select * from tb_users '.$where.' 
			         order by `upline_id`, `id`) tb_users_sorted,
			        (select @pv := '.$userid.') initialisation
			where   find_in_set(`upline_id`, @pv) > 0
			and     @pv := concat(@pv, ",", `id`)
		');
		
		// JARINGAN DENGAN REFERRAL
		/*$get = $this->db->query('
			SELECT  `id`,
			        `username`,
			        `referral_id` 
			from    (select * from tb_users
			         order by `referral_id`, `id`) tb_users_sorted,
			        (select @pv := '.$userid.') initialisation
			where   find_in_set(`referral_id`, @pv) > 0
			and     @pv := concat(@pv, ",", `id`)
		');*/

		return $get->result();

	}


	/**
	 * undocumented function
	 *
	 * @return void
	 * @author Ayatulloh Ahad R
	 **/
	public function cek_jaringan( $current_user = 'null', $userid_banding = 'null' )
	{
		
		$this->db->order_by('id', 'desc');
		$all_member 	= $this->ion_auth->users();
		$no 			= 1;
		foreach ($all_member->result() as $member) {
			
			if ( $member->id <= $userid_banding ) {

				if ( $userid_banding != $member->idreferensi ) {
					
					echo  $no++. '' .$member->username. '<br>';

				}
				
				// echo 'lalal <br>';

			}

		}
	}


	/**
	 * undocumented function
	 *
	 * @return void
	 * @author Ayatulloh Ahad R
	 **/
	public function validasi_telp()
	{
		if ( $this->input->post('phone_number') ) {
			
			$user 		= $this->ion_auth->user()->row();
			$p 			= $this->input->post();
			$last_digit	= substr( $user->tlf, -4);

			if ( $p['phone_number'] == $last_digit ) {
				return TRUE;
			} else {
				return FALSE;
			}

		}	
	}


	/**
	 * undocumented function
	 *
	 * @return void
	 * @author Ayatulloh Ahad R
	 **/
	public function get_user_position($refid = 'null' , $position = 'null')
	{
		

		$userdata 			= new stdClass;
		/*$this->db->join('tb_lending', 'lending_userid = id', 'left');
		$this->db->join('tb_packages', 'package_id = lending_package_id', 'left');*/
		$query = $this->db->get_where('tb_users',array('upline_id' => $refid, 'position' => $position));
		if ($query->num_rows() == 1 ) {

			$userdata 			= $query->row();
			$userdata->result 	= 1;

		} else {

			$userdata->result 	= 0;

			//tidak ada data sehingga tombol register disable
			$fields 		= $this->db->list_fields('tb_users');
			foreach ($fields as $field) {
				
				$userdata->$field 	= null;

			}


			/* CHECKING REFERENSI DATA */
			/*$this->db->join('tb_lending', 'lending_userid = id', 'left');
			$this->db->join('tb_packages', 'package_id = lending_package_id', 'left');*/
			$this->db->select('id, upline_id, user_code');
			$this->db->where( 'id', $refid );
			$ref_data 		= $this->db->get('tb_users');
			if ( $ref_data->num_rows() == 1 ) {
				
				// ada data referensi sehingga membuat tombol link registrasi member baru
				$user_data				= $ref_data->row();
				$userdata->referral_id 	= $user_data->id;
				$userdata->user_code	= $user_data->user_code;
				$userdata->position 	= $position;
				$userdata->result 		= 2;

			} 
			

		}

		return $userdata;


	}




	/**
	 * undocumented function
	 *
	 * @return void
	 * @author Ayatulloh Ahad R
	 **/
	public function do_login()
	{

		$global_status 	= true;
		// $global_status 	= false;
		// $this->session->set_flashdata('auth_flash', alerts( 'System under maintenance a few hours. Please come back later !', 'danger' ) );

		// VALIDASI GOOGLE CAPTCHA Catch the user's answer
		/*$captcha_answer = $this->input->post('g-recaptcha-response');

		// Verify user's answer
		$response = $this->recaptcha->verifyResponse($captcha_answer);

		// Processing ...
		if ( ! $response['success']) {
			$global_status 	= false;
			$this->session->set_flashdata('auth_flash', alerts( 'Please verify Recaptcha !', 'danger' ) );
		}*/
		
		if ( $global_status == TRUE ) {
			
			$do_login 		= $this->ion_auth->login( post('username'), post('password'), TRUE );
			if ( $do_login == false ) {
				$this->session->set_flashdata('auth_flash', alerts( $this->ion_auth->errors(), 'danger' ) );
			}

		}

		/*$do_login 		= $this->ion_auth->login( post('form_username'), post('form_password'), TRUE );
		if ( $do_login == false ) {
			$this->session->set_flashdata('auth_flash', alerts( $this->ion_auth->errors(), 'danger' ) );
		}*/
		

	}


	/**
	 * undocumented function
	 *
	 * @return void
	 * @author Ayatulloh Ahad R
	 **/
	public function forgot_password()
	{
		
		$this->load->model('mailermodel');

		$post 			= $this->input->post();
		$global_status 	= TRUE;

		//VALIDASI USERNAME DAN EMAIL
		$this->db->where('email', $post['forgot_email']);
		$this->db->where('username', $post['forgot_username']);
		$cek_data 		= $this->db->get('tb_users');
		if ( $cek_data->num_rows() == 0 ) {
			
			$global_status  	= FALSE;
			$this->session->set_flashdata('forgot_flash', alerts( 'Data Salah!', 'danger' ) );

		}


		if ( $global_status == TRUE ) {

			//generting new password
			$new_password 		= random_string( 'numeric', 6 );
			$userdata 			= $cek_data->row();

			//UPDATE PASSWORD 
			$this->ion_auth->update( $userdata->id , array('password' => $new_password ) );


			//KIRIM EMAIL KE MEMBER
			$forgot_subject 	= 'Forgot Password '.APP_NAME;
			$forgot_message 	= $this->load->view('library/email-forgot-password', array( 'username' => $userdata->username, 'password' => $new_password ) , TRUE);

			$this->mailermodel->send( $post['forgot_email'], $forgot_subject, $forgot_message );
			$this->session->set_flashdata('forgot_flash', alerts( 'Your new password has been sent to your email !', 'info' ) );

		}

	}


	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function change_password()
	{

		$post 					= $this->input->post();
		$userdata 				= userdata( array('id' => $post['change_id']) );
		$global_status 			= TRUE;

		if( ! $this->ion_auth->is_admin() ){
			
			$this->form_validation->set_rules('old_pass', 'Old Password', 'trim|required');
			
		}

		$this->form_validation->set_rules('new_pass', 'New Password', 'trim|required');

		if ($this->form_validation->run() == FALSE) {
			$global_status 		= FALSE;
			$message 			= alerts( validation_errors(), 'danger' );
		}

		

		if( ! $this->ion_auth->is_admin() ){

			if( ! $this->ion_auth->hash_password_db($userdata->id, $post['old_pass']) ){
				$global_status 		= FALSE;
				$message 			= alerts('Your old password did not match !', 'danger');
			}

		}


		if ( $post['new_pass'] != $post['confirm_pass'] ) {
			$global_status 		= FALSE;
			$message 			= alerts('The New Password field does not match the Confirm Password field.', 'danger');
		}


		if ( $global_status == TRUE ) {
			//do update password
			$this->ion_auth->update( $userdata->id, array('password' => $post['new_pass']) );
			$message 			= alerts('Your password has updated !', 'info');
		}

		$this->session->set_flashdata('profile_flash', $message);

	}



	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function save_info_general( $userid, $data = null )
	{

		$user_id = ($userid==null) ? userid() : $userid ;
		$this->db->update('tb_anggota',  $data, array( 'id' => $user_id ) );
		
		$this->session->set_flashdata('profile_flash', alerts('Your information has been updated !', 'info'));

	}


	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function reset2FA()
	{

		$post 		= $this->input->post();
		$status 	= true;
		$message 	= null;

		//validate userdata
		$userdata 	= userdata( array(
			'username'	=> $post['username'],
			'email'		=> $post['email'],
			'user_phone'=> $post['phone']
		) );
		if ( ! $userdata ) {
			$status 	= false;
			$message 	= alerts( 'Your have entered wrong user information !', 'danger' );
		}

		if ( $status ) {
			
			//update gauth setting
			$this->ion_auth->update( $userdata->id, array('gauth_status' => 'off', 'gauth_secret' => null ));
			$message 	= alerts( 'Two Factor Authentication was removed !', 'success' ); 
		}

		$this->session->set_flashdata('auth_flash', $message);
		return $status;

	}


}

/* End of file Usermodel.php */
/* Location: ./application/models/Usermodel.php */