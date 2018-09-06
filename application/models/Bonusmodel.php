<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Bonusmodel extends CI_Model {

	public $variable;

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
}

/* End of file  */
/* Location: ./application/models/ */