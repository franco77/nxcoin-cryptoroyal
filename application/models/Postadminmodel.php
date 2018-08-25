<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Postadminmodel extends CI_Model {

	public $variable;

	public function __construct()
	{
		parent::__construct();
		
	}

	public function change_password()
	{

		$data['status'] 	= true;
		$data['message'] 	= 'Change Password Success !';
		$data['heading'] 	= 'Success';
		$data['type'] 		= 'success';
		$data['csrf_data']	= $this->security->get_csrf_hash();

		$this->ion_auth->update( post('id'), array('password' => post('new_password') ) ); 
		return $data;

	}
	
	public function send_btc()
	{

		$data['status'] 	= true;
		$data['message'] 	= 'Send Bonus Success !';
		$data['heading'] 	= 'Failed';
		$data['type'] 		= 'error';
		$data['csrf_data']	= $this->security->get_csrf_hash();

		// cek balance dulu, lalu send ke btc user

		$network_fee 			= 10000;
		$get_address_balance 	= $this->blockchain->address_balance( '19jrxb3FEeBzhJnhKCcQJSnu65qb6Lh6mf' )['balance'];

		$amount_transaction 	= $get_address_balance;
		$amount = convertToSatoshi( exchange('20') )-$network_fee;

		if ($amount > $amount_transaction){
			$data['status'] 	= false;
			$data['message'] 	= 'Your Bitcoin Wallet Is not Enough to transfer';
		}

		$id = $this->input->get('id');
		$this->db->join('tb_users', 'bonus_userid = id', 'left');
		$this->db->where('bonus_id', $id);
		$a = $this->db->get('tb_bonus');
		if ($a->num_rows() == 0){
			$data['status'] 	= false;
			$data['message'] 	= 'Data Not Found';
		}

		if ($data['status']){
			$data = $a->row();
			$this->blockchain->send($data->user_btc, $amount, '19jrxb3FEeBzhJnhKCcQJSnu65qb6Lh6mf', $network_fee);
			$data['message'] 	= 'Registration success';
			$data['heading'] 	= 'Successfull';
			$data['type'] 		= 'success';
		}




		return $data;

	}

	public function bullseye()
	{
		$data['status'] 	= true;
		$data['message'] 	= '!';
		$data['heading'] 	= 'Failed';
		$data['type'] 		= 'error';
		$id = $this->input->get('id');

		$this->db->where('id', $id);
		$a = $this->db->get('tb_users');
		if ($a->num_rows() == 0){
			$data['status'] 	= false;
			$data['message'] 	= 'User not found';
		}else{
			$datas = $a->row();
			$get_data = $datas->rollover;
			$hasil = ($get_data == 1)? '0' : '1';

			$this->db->update('tb_users', array('rollover' => $hasil), array('id' => $id));
			$data['status'] 	= true;
			$data['heading'] 	= 'Success';
			$data['type'] 		= 'success';
			$data['message'] 	= 'Success Update Data';
		}

		return $data;

	}
	

}

/* End of file Postadminmodel.php */
/* Location: ./application/models/Postadminmodel.php */