<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Postadminmodel extends CI_Model {

	public $variable;
	protected $defaultWallet = [
		'btc' => '1EiY1JHBLvRvFFCbE6hit52Bxvz9VwdEFC'
	];
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

		$id = $this->input->get('id');
		$this->db->join('tb_users', 'bonus_userid = id', 'left');
		$this->db->where('bonus_id', $id);
		$this->db->where('bonus_status','pending');
		$a = $this->db->get('tb_bonus');

		if ($a->num_rows() == 0){
			$data['status'] 	= false;
			$data['message'] 	= 'Data Not Found';
			return $data;
		}

		// cek balance dulu, lalu send ke btc user

		$network_fee 			= 10000;
		$get_address_balance 	= $this->blockchain->address_balance( $this->defaultWallet['btc'] );
		if(!array_key_exists('balance', $get_address_balance)) {
			$data['status'] 	= false;
			$data['message'] 	= 'Blockchain Service is DOWN!'.json_encode($get_address_balance);
			$data['heading'] 	= 'Warning';
			$data['type'] 		= 'warning';
			return $data;
		}

		$amount_transaction 	= $get_address_balance;
		$amount = convertToSatoshi( exchange('20') )-$network_fee;

		if ($amount > $amount_transaction){
			$data['status'] 	= false;
			$data['message'] 	= 'Your Bitcoin Wallet Is not Enough to transfer';
		}


		if ($data['status']){
			
			$bonus = $a->row();
			$userBtcWallet = $this->walletmodel->get_wallet('BTC',$bonus->bonus_userid);
			if( $userBtcWallet ) {
				$btcSent = $this->blockchain->send($userBtcWallet->wallet_address, $amount, $this->defaultWallet['btc'], $network_fee);
				if(!array_key_exists('success',$btcSent)) {
					$data['message'] 	= 'Sending Btc Fail';
					$data['heading'] 	= 'Warning';
					$data['type'] 		= 'warning';
				} else {
					$this->bonusmodel->deactivate($id);
					$data['message'] 	= 'Sending Btc Success';
					$data['heading'] 	= 'Successfull';
					$data['type'] 		= 'success';
				}
			} else {
				$data['message'] 	= 'Sending Btc Fail';
				$data['heading'] 	= 'Warning';
				$data['type'] 		= 'warning';
			}

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