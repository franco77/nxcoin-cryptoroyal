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

	public function get_stacking_batch($userid, $amount = NULL) {


		$query = $this->db->from('tb_stacking')
		->where_in('stc_userid', $userid);

		if($amount) {
			$query->where('stc_amount >=', $amount);
		}
		return $query->get();
		

	}

	public function get_new_stacking($userid, $select = NULL) {
		$select = ($select) ? $select : '*';
		
		
		$query = $this->db->select($select)
		->from('tb_stacking')
		->where( 'date_format(stc_date_start, "%Y-%m-%d") =', kemarin() ); //karena cron jalan pada pukul 00:00:00
		
		if(is_array($userid)) {
			$ids = [];
			
			foreach($userid as $user) {
				$ids[] = $user->id;
			}

			
			$query->where_in('stc_userid', $ids);

		} else {

			$query->where('stc_userid', $userid);

		}
		return $query->get();

	}

	public function get_all() {

		return $this->db->select('*')->from('tb_stacking')->get()->result();

	}

	public function update($id, $fields) {

		
		$updated = $this->db->where('stc_id', $id);
		if(count($fields) < 1) {
			return true;
		}
		
		foreach( $fields as $k => $v ) {
			$updated->set($k, $v);
		}
		
		return $updated->update('tb_stacking');

		
	}

}

/* End of file  */
/* Location: ./application/models/ */