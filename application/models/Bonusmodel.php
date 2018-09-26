<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Bonusmodel extends CI_Model {

	public $variable;

	protected $leadership = [
		1 => 1,
		2 => 3,
		3 => 4,
		4 => 5
	];
	protected $leadership_alocation = [
		'A' => 50,
		'B' => 30,
		'C' => 20
	];

	public function __construct()
	{
		parent::__construct();
		
	}

	public function insert($id = '' ,$bonus = '', $generasi = '')
	{
		$generasi = $generasi +1;
		//50 30 20
		$object = array(
			array(
				'wallet_userid' 	=> $id,
				'wallet_type' 		=> 'A',
				'wallet_amount'		=> 0.5*$bonus,
				'wallet_desc'		=> 'Staking Bonus User Referal Generasi '.$generasi,
				'wallet_date'		=> date('Y-m-d H:i:s')
			),
			array(
				'wallet_userid' 	=> $id,
				'wallet_type' 		=> 'B',
				'wallet_amount'		=> 0.3*$bonus,
				'wallet_desc'		=> 'Staking Bonus User Referal Generasi '.$generasi,
				'wallet_date'		=> date('Y-m-d H:i:s')
			),
			array(
				'wallet_userid' 	=> $id,
				'wallet_type' 		=> 'C',
				'wallet_amount'		=> 0.2*$bonus,
				'wallet_desc'		=> 'Staking Bonus User Referal Generasi '.$generasi,
				'wallet_date'		=> date('Y-m-d H:i:s')
			),
		);
		$this->db->insert_batch('tb_wallet', $object);
		$dayta = array(
			'bonus_userid' 	=> $id,
			'bonus_name'	=> 'Referal Bonus Generations',
			'bonus_desc'	=> 'Staking Bonus User Referal Generasi '.$generasi,
			'bonus_amount'	=> $bonus,
			'bonus_type'	=> 'active',
			'bonus_date'	=> date('Y-m-d H:i:s')
		);
		$this->db->insert('tb_bonus', $dayta);
	}

	public function insert_pasif($id = '' ,$bonus = '', $judul = 'Profit Bonus')
	{ 
		//80 10 10
		$object = array(
			array(
				'wallet_userid' 	=> $id,
				'wallet_type' 		=> 'A',
				'wallet_amount'		=> 0.8*$bonus,
				'wallet_desc'		=> $judul,
				'wallet_date'		=> date('Y-m-d H:i:s')
			),
			array(
				'wallet_userid' 	=> $id,
				'wallet_type' 		=> 'B',
				'wallet_amount'		=> 0.1*$bonus,
				'wallet_desc'		=> $judul,
				'wallet_date'		=> date('Y-m-d H:i:s')
			),
			array(
				'wallet_userid' 	=> $id,
				'wallet_type' 		=> 'C',
				'wallet_amount'		=> 0.1*$bonus,
				'wallet_desc'		=> $judul,
				'wallet_date'		=> date('Y-m-d H:i:s')
			),
		);
		$this->db->insert_batch('tb_wallet', $object);
		$dayta = array(
			'bonus_userid' 	=> $id,
			'bonus_name'	=> $judul,
			'bonus_desc'	=> $judul,
			'bonus_amount'	=> $bonus,
			'bonus_type'	=> 'pasive',
			'bonus_date'	=> date('Y-m-d H:i:s')
		);
		$this->db->insert('tb_bonus', $dayta);
	}

	public function insert_pasif_mode2($id = '' ,$bonus = '', $judul = 'Profit Bonus')
	{ 
		//80 10 10
		$object = array(
			array(
				'wallet_userid' 	=> $id,
				'wallet_type' 		=> 'A',
				'wallet_amount'		=> 0.8*$bonus,
				'wallet_desc'		=> $judul,
				'wallet_date'		=> date('Y-m-d H:i:s')
			),
			array(
				'wallet_userid' 	=> $id,
				'wallet_type' 		=> 'B',
				'wallet_amount'		=> 0.1*$bonus,
				'wallet_desc'		=> $judul,
				'wallet_date'		=> date('Y-m-d H:i:s')
			), 
		);
		$this->db->insert_batch('tb_wallet', $object);
		$dayta = array(
			'bonus_userid' 	=> $id,
			'bonus_name'	=> $judul,
			'bonus_desc'	=> $judul,
			'bonus_amount'	=> $bonus,
			'bonus_type'	=> 'pasive',
			'bonus_date'	=> date('Y-m-d H:i:s')
		);
		$this->db->insert('tb_bonus', $dayta);
	}
	public function deactivate($id) {
		return 
		$this->db
			->set('bonus_status', 'transfer')
			->where('bonus_id', $id)
			->update('tb_bonus');

	}

	public function get_bonuses($userid, $type = null) {
		$data = $this->db
			->select('*')
			->from('tb_bonus')
			->where('bonus_userid', $userid);
		if($type) {
			$data->where('bonus_type',$type);
		}
			
		return $data->get()->result();
	}

	public function calc_leadership($amount, $star) {
		
		$percentage = $this->leadership[$star];
		$bonus = bcmul((string) $amount, (string)$percentage,8);
		$bonus = bcdiv($bonus, '100', 8);

		$alocations = [];
		
		foreach( $this->leadership_alocation as $key => $alocation ) {

			$alocated = bcdiv(bcmul( (string) $bonus, "$alocation", 8),'100',8);
			$alocations[$key] = $alocated;

		}
		return $alocations;

	}

	public function insert_leadership($userid, $bonus_alocations) {

		$bonuses = [];
		$total_bonus = 0;
		
		foreach($bonus_alocations as $key => $alocated) {

			$bonuses[] = [
				'wallet_userid' 	=> $userid,
				'wallet_type' 		=> $key,
				'wallet_amount'		=> $alocated,
				'wallet_desc'		=> 'Leadership Bonus',
				'wallet_date'		=> date('Y-m-d H:i:s')
			];
			$total_bonus = bcadd((string) $total_bonus, (string) $alocated, 8);
			
		}
		
		if(!empty($bonuses)) {

			$bonus_transaction = array(
				'bonus_userid' 	=> $userid,
				'bonus_name'	=> 'Leadership Bonus',
				'bonus_desc'	=> 'Leadership Bonus',
				'bonus_amount'	=> $total_bonus,
				'bonus_type'	=> 'pasive',
				'bonus_date'	=> date('Y-m-d H:i:s')
			);
			$this->db->trans_begin();

			$this->db->insert_batch('tb_wallet', $bonuses);
			$this->db->insert('tb_bonus', $bonus_transaction);

			if($this->db->trans_status() === FALSE) {

				$this->db->trans_rollback();
				$this->mailermodel->send('sugamirza2@gmail.com','LEADERSHIP BONUS FAILED','FAILED DATA: '. json_encode($bonuses) );
			} else {

				$this->db->trans_commit();

			}
		}

	}
}

/* End of file  */
/* Location: ./application/models/ */