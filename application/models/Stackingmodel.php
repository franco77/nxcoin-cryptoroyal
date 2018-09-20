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

	public function get_omset_jaringan( $userid = NULL ) {

		$userid = ($userid) ? $userid : userid();

		$sql = 'select sum(s.stc_amount) as omset from (
					SELECT id
					from    ( select * from tb_users where position="right" order by `upline_id`, `id`) tb_users_sorted,
							( select @pv := '.$userid.' ) initialisation
					where find_in_set(`upline_id`, @pv) > 0
					and @pv := concat(@pv, ",", `id`)
				
				) as u
				join tb_stacking s on s.stc_userid = u.id';
		$query = $this->db->query($sql);
		$result = $query->row();
		if(!$result) {
			return 0;
		}
		return $result->omset;
		
	}

}

/* End of file  */
/* Location: ./application/models/ */