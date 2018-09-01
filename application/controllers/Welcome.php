<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

	public function index($id='')
	{
		echo $walleta = $this->walletmodel->cek_balance('A', $id);
	}
}
