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
	public function bonus_profit_7days($from_date = null, $to_date = null)
	{
		$next_profit_date 	=  date('Y-m-d', strtotime('+7 days', strtotime( sekarang() )));

		$current = date('Y-m-d');
		//cek db stacking
		$jml = 0	;
		$this->db->join('tb_users', 'id = stc_userid', 'left');
		$this->db->join('tb_package', 'package_id = stc_package', 'left');
		$this->db->where('stc_date_end >= ', sekarang());
		if(!$from_date && !$to_date) {
			$this->db->where('date_format(next_profit,"%Y-%m-%d")', $current);
		} else {
			$this->db->where('date_format(next_profit,"%Y-%m-%d") >', $from_date);
			$this->db->where('date_format(next_profit,"%Y-%m-%d") <', $to_date);
		}
		$ge = $this->db->get('tb_stacking');
		$bonuses_sent = [];
		if ($ge->num_rows() > 0){ 
			foreach ($ge->result() as $get_stc) { 
				
				if( !$this->last_three_weeks_bonus($get_stc->stc_date_start) ) {
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

			}
			$this->mailermodel->send('sugamirza2@gmail.com','Cryptoroyal - PROFIT 7 DAYS',json_encode($bonuses_sent));
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

	private function last_three_weeks_bonus($start_stacking) {
		
		$at_week	= [ 25,26,27,28 ];
		$stc_date	= date('Y-m-d', strtotime($start_stacking));
		$weeks		= count_weeks($stc_date,date('Y-m-d'));

		return in_array($weeks, $at_week);
	}

	public function extend_stc_date($pass=null) {
		
		if($pass !== 'cr123runkerak') {

			return response(['success' => 0], 401)->json();

		}

		$stackings = $this->stackingmodel->get_all();
		if(!$stackings) {
			return response([
				'status' =>0
			])->json();
		}
		$success = [];
		foreach($stackings as $stack) {

			$extends_date = date('Y-m-d H:i:s', strtotime('+196 days', strtotime($stack->stc_date_start) ) );

			$date_before = $stack->stc_date_end;

			$updated = $this->stackingmodel->update($stack->stc_id, [
				'stc_date_end' => $extends_date
			]);

			if( $updated ) {

				$success[] = [
					'stc_id' => $stack->stc_id,
					'then' => $date_before,
					'now' => $extends_date
				];

			}
			
		}

		return response($success)->json();

	}
}