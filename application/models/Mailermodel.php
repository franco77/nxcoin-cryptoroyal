<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mailermodel extends CI_Model {


	public function __construct()
	{
		parent::__construct();
		//Do your magic here
		$config['protocol'] 		= 'smtp';

		$config['smtp_host'] 		= 'mail.smtp2go.com';
		$config['smtp_user'] 		= 'admin@cryptoroyal.co';
		$config['smtp_pass'] 		= 'Bismillah123!@#';
		$config['smtp_port']		= '25';
		// $config['smtp_crypto']		= 'SSL';

		$config['charset'] 			= 'iso-8859-1';
		$config['wordwrap'] 		= TRUE;
		$config['mailtype'] 		= 'html';
		$config['newline'] 			= '\r\n';
		$this->load->library('email');
		$this->email->initialize( $config );

	}


	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function send( $email_to = 'null', $subject = 'null', $message = null )
	{ 
		$this->email->from('admin@cryptoroyal.co', 'Crypto Royal');
		$this->email->to( $email_to );
		
		$this->email->subject( $subject );
		$this->email->message( $message );
		
		return $this->email->send();

	}
	

}

/* End of file Mailermodel.php */
/* Location: ./application/models/Mailermodel.php */