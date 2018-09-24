<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Leadership extends CI_Controller {

	public function __construct()
	{
        parent::__construct();
        if( $this->ion_auth->logged_in() ) {

            $this->load->model('leadershipmodel');
            
        } else {
            exit;
        }
    }

    public function detail() {
        $userid = get('userid');
        $leadership_detail = $this->leadershipmodel->get_leader_detail($userid);

        return response($leadership_detail)->json();
    }

    public function update() {
        
    }
    
}