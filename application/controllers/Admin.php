<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		if ( ! $this->ion_auth->logged_in() ) {
			exit;
		}
		if ( ! $this->ion_auth->is_admin() ) {
			exit;
		}
	}

	public function view( $filename = 'index' )
	{
		
		
		if ( $this->input->post('do_login') ) {
			$this->usermodel->do_login();
		}


		if ( $this->ion_auth->logged_in() ) {

			if( ! file_exists( VIEWPATH . 'admin/page_' . $filename . '.php' ) ){
				show_404();
				exit;
			}
 
			if( (userdata()->gauth_status == 'on') && ( $this->session->userdata('gauth_status') != 'valid' ) ):

				//show form validate 2FA
				$this->template->content->view('auth/validate-2FA-form');
				$this->template->publish('app_template_auth');

			else:   
				# HALAMAN DASHBOARD DAN MEMBER AREA
				
				$this->template->content->view('admin/page_' .$filename, TRUE );
				$this->template->publish('app_template_dashboard');

			endif;

		} else {

			#HALAMAN AUTHTENTICATION

			/* LOAD CAPTCHA DATA */
			$data['captcha'] 	= null;

			$this->template->content->view('auth/login', $data);
			$this->template->publish('app_template_auth');

		}

	}

	public function postdata( $function_name = 'null' )
	{ 
		$this->output->set_content_type('application/json');

		$this->load->model('postadminmodel');
		$get_data 	= $this->postadminmodel->$function_name();
		echo json_encode( $get_data ); 
	}

	public function modalajax( $filename = '' )
	{

		if ( file_exists( VIEWPATH . 'admin/modal/' . $filename . '.php' ) ) {
			echo $this->load->view('admin/modal/' . $filename, [], true);
		}else{
			show_404();
		}

	}

}

/* End of file Admin.php */
/* Location: ./application/controllers/Admin.php */