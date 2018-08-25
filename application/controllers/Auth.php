<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Auth extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->load->library(array('ion_auth', 'form_validation'));
		$this->load->helper(array('url', 'language'));

		$this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));

		$this->lang->load('auth');
	}



	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function Qpass_gen(){
    	$pass = $this->ion_auth_model->hash_password('password',FALSE,FALSE);
    	echo $pass;
    }

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function forgot_password()
	{
		
		if ( $this->input->post('do_forgot_password') ) {
			$this->usermodel->forgot_password();
		}

		$this->template->content->view('auth/forgot-password');
		$this->template->publish('app_template_auth');

	}


	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function register($refid = '',$nama='')
	{

		$data 					= array();
		$message 				= null;
		$status 				= true;

		if ($nama != ''){
			if ( userdata( array('username' => $nama) ) != false ){
				$inni = $nama;
			}else{
				$inni = userdata( array('id' => 1) )->username;
			}
		}else{
			if ( $this->session->userdata('referral_session') == null ) {
				$inni = userdata( array('id' => 1) )->username;
			}else{ 
				$inni = $this->session->userdata('referral_session'); 
			}
		} 

		$data['username_referral'] 		= $inni;

		$this->template->content->view( 'auth/register', $data );
		$this->template->publish( 'app_template_auth' );

	}

	
	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function logout()
	{
		$this->ion_auth->logout();
		$this->session->unset_userdata('view_profile', 'view_wd');

		redirect('','refresh');
	}


	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function reset2FA()
	{

		if( $this->input->post('doReset2FA') ){
			
			$doReset 	= $this->usermodel->reset2FA();
			if( $doReset ){
				redirect( site_url() , 301);
			}

		}

		$this->template->content->view( 'auth/reset-2FA-form' );
		$this->template->publish( 'app_template_auth' );

	}


	/**
	 * Activate the user
	 *
	 * @param int         $id   The user ID
	 * @param string|bool $code The activation code
	 */
	public function activate($id, $code = FALSE)
	{
		if ($code !== FALSE)
		{
			$activation = $this->ion_auth->activate($id, $code);
		}
		else if ($this->ion_auth->is_admin())
		{
			$activation = $this->ion_auth->activate($id);
		}

		if ($activation)
		{
			// redirect them to the auth page
			$this->session->set_flashdata('message', $this->ion_auth->messages());
			redirect("", 'refresh');
		}
		else
		{
			// redirect them to the forgot password page
			$this->session->set_flashdata('message', $this->ion_auth->errors());
			redirect("auth/forgot_password", 'refresh');
		}
	}


}

/* End of file Auth.php */
/* Location: ./application/controllers/Auth.php */