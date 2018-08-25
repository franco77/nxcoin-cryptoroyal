<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Userpost extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		if ( ! $this->ion_auth->logged_in() ) {
			exit;
		}
	}  

	

	public function postdata( $function_name = 'null' )
	{
		
		$this->output->set_content_type('application/json');

		$this->load->model('postusermodel');
		$get_data 	= $this->postusermodel->$function_name();
		echo json_encode( $get_data );

	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function modalajax( $filename = null )
	{

		if ( file_exists( VIEWPATH . 'modal/' . $filename . '.php' ) ) {
			echo $this->load->view('modal/' . $filename , array(), true);
		}
	}

	public function changeUserPicture()
	{
		$this->output->set_content_type('application/json');
		$data['status'] 	 = true;
		$data['message'] 	 = alerts('User picture was updates !', 'success');
		$data['heading'] 	= 'Success';

		$config['upload_path'] = './uploads/image/';
		$config['allowed_types'] = 'gif|jpg|png';
		$config['max_size']  = '8000';
		$config['max_width']  = '10240';
		$config['max_height']  = '10240';
		
		if( ! $this->ion_auth->logged_in() ){
			$data['status'] 	 = false;
			$data['message'] 	 = alerts('failed', 'danger');
		}

		$this->load->library('upload', $config);
		if (get('pic') != 'default'){
			if ( ! $this->upload->do_upload('file_name')){
				$data['status'] 	 = false;
				$data['message'] 	 = alerts( $this->upload->display_errors(), 'danger' );
			}
			if( ! $this->ion_auth->logged_in() ){
				$data['status'] 	 = false;
				$data['message'] 	 = alerts('failed', 'danger');
			}


			if ( $data['status'] ) { 
				$uploaded_data 		= $this->upload->data();
				$this->ion_auth->update( userid(), array( 'user_picture' => $uploaded_data['file_name']) ); 
			}
			
		}else{
			$data['message'] = alerts('Picture Set To dafault', 'success');
			$this->ion_auth->update( userid(), array( 'user_picture' => 'no-images.jpg') ); 
		}
 

		$this->session->set_flashdata('profile_flash', $data['message'] );
		redirect('profile', 301);
		
	}


}

/* End of file Userpost.php */
/* Location: ./application/controllers/Userpost.php */