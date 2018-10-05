<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mailermodel extends CI_Model {
	
	public function __construct()
	{
		parent::__construct();
		$this->load->config('email');
		$this->load->library('mailgun');
		//$api_key = $this->config->item('mailgun_api_key');
		//$this->mailgun = Mailgun::create($api_key);
		// $config = $this->config->item($used_mail);
		// $this->load->library('email');
		// $this->email->initialize( $config );

	}


	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function send( $email_to = 'null', $subject = 'null', $message = null )
	{ 
		
		// $this->email->from('admin@cryptoroyal.co', 'Crypto Royal');
		// $this->email->to( $email_to );
		
		// $this->email->subject( $subject );
		// $this->email->message( $message );
		
		// return $this->email->send();

		// return $this->mailgun->messages()->send('mg.cryptoroyal.co', [
		// 	'from'    => 'admin@cryptoroyal.co',
		// 	'to'      => $email_to,
		// 	'subject' => $subject,
		// 	'html'    => $message
		// ]);
		return $this->mailgun->to($email_to)
		->from('admin@cryptoroyal.co')
		->subject($subject)
		->message($message)
		->send();

	}
	

}

/* End of file Mailermodel.php */
/* Location: ./application/models/Mailermodel.php */