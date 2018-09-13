<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Bonuses extends MY_Controller {

    public function __construct() {

        parent::__construct();

        if ( ! $this->ion_auth->logged_in() ) {
			exit;
		}
		if ( ! $this->ion_auth->is_admin() ) {
			exit;
		}

    }

    public function get_bonus($userid) {

        $type = $this->input->get('type');
        $data = $this->bonusmodel->get_bonuses($userid, $type);

        return response([
            
            'status' => 1,
            'data' => $data,
            'user_id' => $userid,
            'type' => $type

        ],200)->json();

    }
    public function force_pasive_bonus() {
        $userid = post('userid');
        
        $stacking = $this->usermodel->stacking($userid);
        $bonus = $stacking->stc_amount * ($stacking->package_profit/100);
        $judul = 'Profit Bonus (forced)';
        if ($stacking->rollover == '0'){
            $this->bonusmodel->insert_pasif_mode2($stacking->stc_userid,$bonus, $judul);
        }else{
            $this->bonusmodel->insert_pasif($stacking->stc_userid,$bonus, $judul);
        }

        redirect(site_url('admin/view/user-detail/').$userid,'GET');
    }
}