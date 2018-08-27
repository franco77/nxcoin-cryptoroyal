<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Walletmodel extends CI_Model {

	public $variable;

	public function __construct()
	{
		parent::__construct();
		
	}

	public function cek_balance($type='A',$user = '')
	{
		$user = ($user == '')? userid() : $user;
		$this->db->select_sum('wallet_amount');
		$this->db->where('wallet_userid', $user);
		$this->db->where('wallet_type', $type);
		$a = $this->db->get('tb_wallet')->result();

		return array_shift($a)->wallet_amount;
	}

	public function pengurangan($type='A', $amount ='0',$user='')
	{
		$user = ($user == '')? userid() : $user;
		$object = array(
			array(
				'wallet_userid' => 1,
				'wallet_type'	=> $type,
				'wallet_amount' => $amount,
				'wallet_desc'	=> 'Pembelian Rollover'
			),
			array(
				'wallet_userid' => $user,
				'wallet_type'	=> $type,
				'wallet_amount' => ($amount * -1),
				'wallet_desc'	=> 'Pembelian Rollover'
			)
		);
		$this->db->insert_batch('tb_wallet', $object);
	}

	public function getPriceNx()
	{
		$url = "https://nxcoin.io/account/apis/get_price";
		$ch = curl_init();
		curl_setopt ($ch, CURLOPT_URL, $url);
		curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, 5);
		curl_setopt ($ch, CURLOPT_RETURNTRANSFER, true);
		$contents = curl_exec($ch);
		if (curl_errno($ch)) {
		  echo curl_error($ch);
		  echo "\n<br />";
		  $contents = '';
		} else {
		  curl_close($ch);
		}

		if (!is_string($contents) || !strlen($contents)) {
		echo "Failed to get contents.";
		$contents = '';
		}

		return $contents;
	}

	public function get_wallet($type = 'A', $userid=null) {
		$userid = ($userid == null)? userid() : $userid;

		$this->db->select('*');
		$this->db->where('wallet_userid', $userid);
		$this->db->where('wallet_type', $type);
		$this->db->where('wallet_address is NOT NULL', NULL, FALSE);

		return $this->db->get('tb_wallet')->row();
	}

}

/* End of file  */
/* Location: ./application/models/ */