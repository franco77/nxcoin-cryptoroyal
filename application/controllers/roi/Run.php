<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Run extends CI_Controller {

	public function __construct()
	{
		parent::__construct(); 
		//wget https://cryptoroyal.co/account/roi/run/bonus_profit_7days

	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function bonus_profit_7days()
	{
		$next_profit_date 	=  date('Y-m-d', strtotime('+7 days', strtotime( sekarang() )));

		$current = date('Y-m-d');
		//cek db stacking
		$jml = 0	;
		$this->db->join('tb_users', 'id = stc_userid', 'left');
		$this->db->join('tb_package', 'package_id = stc_package', 'left');
		$this->db->where('stc_date_end >= ', sekarang());
		$this->db->where('date_format(next_profit,"%Y-%m-%d")', $current);
		$ge = $this->db->get('tb_stacking');
		return response($ge->result())->json();
		$bonuses_sent = [];
		if ($ge->num_rows() > 0){ 
			foreach ($ge->result() as $get_stc) { 
				$userdata = userdata(array('id' => $get_stc->stc_userid));
				//if ($userdata->next_profit ==  $next_profit_date){
					$bonus = $get_stc->stc_amount * ($get_stc->package_profit/100);
					$bonuses_sent[] = [
						'user_id' => $userdata->id,
						'username' => $userdata->username,
						'amount' => $bonus,
					];
					if ($get_stc->rollover == '0'){
						$this->bonusmodel->insert_pasif_mode2($get_stc->stc_userid,$bonus);
					}else{
						$this->bonusmodel->insert_pasif($get_stc->stc_userid,$bonus);
					}
					//update next Profit
					$this->db->update('tb_users', [ 'next_profit' => $next_profit_date ], [ 'id' => $userdata->id ]);
				//}
			}
			echo json_encode($bonuses_sent);
		}

		//ini untuk kembalian 
		$jml2 = 0; 
		$this->db->where('stc_date_end <= ', $next_profit_date);
		$ge = $this->db->get('tb_stacking');
		if ($ge->num_rows() > 0){
			foreach ($ge->result() as $key) {
				$bonus = $key->stc_amount * (25/100); 
				$this->bonusmodel->insert_pasif($key->stc_userid,$bonus,'Capital Back After Done Staking'); 
			}
		} 
		
	}

	public function rollover()
	{
		$this->load->model('mainmodel');
		$this->mainmodel->Always_Load();
	}
}