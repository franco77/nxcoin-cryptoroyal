<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Member extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
	}


	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function activation()
	{

		if ( $this->usermodel->is_active() ) {
			redirect('','refresh');
		}

		//load form activation
		$this->template->content->view('page/page_activation' );
		$this->template->publish('app_template_dashboard');

	}


	/**
	 * undocumented function
	 *
	 * @return void
	 * @author Ayatulloh Ahad R
	 **/
	public function view( $filename = 'index' )
	{
		
		
		if ( $this->input->post('do_login') ) {
			$this->usermodel->do_login();
		}


		if ( $this->ion_auth->logged_in() ) {

			if( ! file_exists( VIEWPATH . 'page/page_' . $filename . '.php' ) ){
				show_404();
				exit;
			}

			if( (userdata()->gauth_status == 'on') && ( $this->session->userdata('gauth_status') != 'valid' ) ):

				//show form validate 2FA
				$this->template->content->view('auth/validate-2FA-form');
				$this->template->publish('app_template_auth');

			else:   
				# HALAMAN DASHBOARD DAN MEMBER AREA
				
				$this->template->content->view('page/page_' .$filename, TRUE );
				$this->template->publish('app_template_dashboard');
				$this->mainmodel->Always_Load();

			endif;

		} else {

			#HALAMAN AUTHTENTICATION

			/* LOAD CAPTCHA DATA */
			$data['captcha'] 	= null;

			$this->template->content->view('auth/login', $data);
			$this->template->publish('app_template_auth');

		}

	}


	/**
	 * undocumented function
	 *
	 * @return void
	 * @author Ayatulloh Ahad R
	 **/
	public function networktree( $user_token = 'null' )
	{

		if ( $this->input->post('do_login') ) {
			$this->usermodel->do_login();
		}


		if ( $this->ion_auth->logged_in() ) {

			$get_user_token 	= userdata( array( 'user_code' => $user_token ) );
			if ( ! empty( $get_user_token ) ) {
				$data['userid'] 		= $get_user_token->id;
			} else {
				$data['userid'] 		= userid();
			}

			
			# HALAMAN DASHBOARD DAN MEMBER AREA
			$this->template->content->view('page/network-program', $data);
			$this->template->publish('app_template_dashboard');

		} else {

			#HALAMAN AUTHTENTICATION
			// $data['captcha'] 	= $this->mainmodel->create_captcha();
			
			$this->template->content->view('auth/login');
			$this->template->publish('app_template_auth');

		}

	}



	/**
	 * undocumented function
	 *
	 * @return void
	 * @author Ayatulloh Ahad R
	 **/
	public function bonus(  $bonus_name = null, $user_id = null )
	{	

		$global_status 		= TRUE;
		$message 			= null;
		$now 				= date('Y-m-d');
		$data 				= new stdClass;
		$post 				= $this->input->post();

		if ( ! $this->ion_auth->logged_in() ) {
			$global_status 	= FALSE;
			redirect('','refresh');
		}

		if ( ($bonus_name != 'sponsor') && ( $bonus_name != 'pairing' ) && ( $bonus_name != 'withdrawal' ) && ( $bonus_name != 'profit' ) ) {
			$global_status 	= FALSE;
			show_404();
		}


		if ( $this->input->post('advanced_search') ) {
			// $this->db->where('tgl BETWEEN '.$post['date_start'].' AND '.$post['date_end'].'');
			$this->db->where('tgl >=', $post['date_start']);
			$this->db->where('tgl <=', $post['date_end']);
		} else {
			$this->db->where('tgl', $now );
		}

		if ( $global_status == TRUE ) {
			
			$userid 			= ( $user_id == null )? $this->session->userdata('user_id') : $user_id ;

			
			if ( $bonus_name != null ) {
				$this->db->like('nama', $bonus_name);
			}

			$this->db->where('id', $userid);
			$get_bonus 			= $this->db->get('tb_bonus');


			$data->result 		= $get_bonus->result();
			$data->bonus_name	= $bonus_name;
			$this->template->content->view( 'page/page_bonus', $data );
			$this->template->publish();

		}

	}


	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function edit_profile( $usertoken = 'default' )
	{

		if( $this->ion_auth->logged_in() ):

			$data 				= new stdClass;

			if ( $this->input->post('nama') ) {
		        $this->usermodel->save_info_general( userid(), $this->input->post() );
		    }

		    if ( $this->input->post('namabank') ) {
		    	$this->usermodel->save_info_general( userid(), $this->input->post() );	
		    }

		    if ( $this->input->post('change_password') ) {
		        $this->usermodel->change_password();
		    } 


			if( $this->ion_auth->is_admin() ):

				$get_user_token 	= userdata( array('token' => $usertoken) );

				if ( ! empty( $get_user_token ) ) {
					$data->user_data 		= $get_user_token;
				} else {
					$data->user_data 		= userdata();
				}

			else:

				$data->user_data 		= userdata();

			endif;
			


			$this->template->content->view('page/profile_view', $data);
			$this->template->publish();

		else:

			redirect('','refresh');

		endif;

	}
}

/* End of file Member.php */
/* Location: ./application/controllers/Member.php */