<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Controller extends CI_Controller {


    public function __counstruct() {

        parent::__construct();
        if ( ! $this->ion_auth->logged_in() ) {
			exit;
		}
		if ( ! $this->ion_auth->is_admin() ) {
			exit;
        }
    }

}