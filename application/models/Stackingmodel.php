<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Stackingmodel extends CI_Model {

	public $variable;

	public function __construct()
	{
		parent::__construct();
		
	}

	public function get_stacking($id='')
	{
		$id = ($id == '')? userid() : $id;
		$this->db->join('tb_package', 'package_id = stc_package', 'left');
		$this->db->where('stc_userid', $id);
		$this->db->where('stc_date_end >', date('Y-m-d H:i:s'));
		$this->db->order_by('stc_amount', 'desc');
		$cek = $this->db->get('tb_stacking');
		if ($cek->num_rows() > 0){
			$data = $cek->row();
		}else{
			$data = false;
		}
		return $data;
	}

	public function get_amount($id='')
	{
		$id = ($id == '')? userid() : $id;
		$amount = 0;
		$this->db->where('stc_userid', $id);
		$this->db->where('stc_date_end >', date('Y-m-d H:i:s'));
		$this->db->order_by('stc_amount', 'desc');
		$cek = $this->db->get('tb_stacking');
		foreach ($cek->result() as $key) {
			$amount += $key->stc_amount;
		}
		return $amount;
	}

}

/* End of file  */
/* Location: ./application/models/ */