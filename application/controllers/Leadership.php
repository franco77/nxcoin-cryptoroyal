<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Leadership extends CI_Controller {

	public function __construct()
	{
        parent::__construct();
        if( $this->ion_auth->logged_in() ) {

            $this->load->model('leadershipmodel');

        } else {
            return response(['status' => 0, 'message' => 'unAuthorized Access.'],401)->json();
        }
    }

    public function detail() {
        $userid = get('userid');
        $leadership_detail = $this->leadershipmodel->get_leader_detail($userid);

        return response($leadership_detail)->json();
    }

    public function update_star() {
        $userdata = userdata(['id' => userid()]);
        $userid = $userdata->id;
        $leadership_detail = $this->leadershipmodel->get_leader_detail($userid);
        $have_update = 0;
        $updated = 0;
        $newest_star = $leadership_detail['achivements']['star'];
        if( $userdata->user_stars != $newest_star ) {
            $updated = $this->leadershipmodel->update_star($userid, $newest_star);
            $have_update = 1;
        }

        return response([
            'status' => $updated,
            'have_update' => $have_update
        ])->json();
    }
    
}