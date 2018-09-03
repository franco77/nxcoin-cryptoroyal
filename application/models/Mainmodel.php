<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Mainmodel extends CI_Model {

	public $variable;

	public function __construct()
	{
		parent::__construct();
		$this->init();
	} 


	/**
	 * undocumented function
	 *
	 * @return void
	 * @author Ayatulloh Ahad Robanie [ayatulloh@idprogrammer.com]
	 **/
	private function init()
	{

		/*========================================================
		=            CREATE REFERRAL SYSTEM DETECTION            =
		========================================================*/

		if( $this->input->get('referralID') ){
			
			$userdata 	= userdata([
				'username'	=> $this->input->get('referralID')
			]);
			if ( $userdata != false ) {

				$array = array(
					'referralID' => $userdata->username
				);				
				$this->session->set_userdata( $array );
			}

		}
		
		/*=====  End of CREATE REFERRAL SYSTEM DETECTION  ======*/

	}



	public function Always_Load($value='')
	{
		//
		// cek di bagian antrian dulu
		$this->db->order_by('rollover_id', 'asc');
		$this->db->where('rollover_userid != ', '1');
		$this->db->where('rollover_class', '0');
		$class = $this->db->get('tb_rollover');
		foreach ($class->result() as $datas) {
			$this->db->order_by('rollover_id', 'desc');
			$this->db->where('rollover_userid', $datas->rollover_userid);
			$a = $this->db->get('tb_rollover');
			if ($a->num_rows() > 0){
				$datetime = new DateTime($a->row()->rollover_date);
				$datetime->modify('+1 hour');
				$jam = $datetime->format('Y-m-d H:i:s');
				if ( date('Y-m-d H:i:s') <= $jam){
					/*$data['status'] 	= false; 
					$data['message'] 	= 'In 1 hour you only buy 1 ticket'; */
				}else{
					$this->db->where('rollover_class', '1');
					$this->db->where('rollover_userid', $datas->rollover_userid);
					$class2 = $this->db->get('tb_rollover');
					if ($class2->num_rows() == 0){
						// jika tidak ditemukan nama yang sama masukkan ke dalam urutan
						$object = array(
							'rollover_userid'	=> $datas->rollover_userid,
							'rollover_class' 	=> 1,
							'rollover_txid'		=> $datas->rollover_txid,
							'rollover_amount' 	=> $datas->rollover_amount,
							'rollover_date'		=> $datas->rollover_date
						);
						$this->db->where('rollover_id', $datas->rollover_id);
						$this->db->update('tb_rollover', $object);

						if ($this->updateUrutan('3','1') == true){
							$this->updateUrutan('5','2');
						}
					}
				}
			}
			if ($this->updateUrutan('3','1') == true){
				$this->updateUrutan('5','2');
			}
		} 
		if ($this->updateUrutan('3','1') == true){
			$this->updateUrutan('5','2');
		}

		// cek wallet users
		$this->db->order_by('id', 'asc');
		$this->db->where('id != ', '1');
		$a = $this->db->get('tb_users');
		//$priceNx = $this->walletmodel->getPriceNx();
		$priceNx = $this->marketmodel->get_latest_price('USD');
		foreach ($a->result() as $key) {
			$jml = 0;
			$walletc = $this->walletmodel->cek_balance('C', $key->id) * $priceNx;
			if ($walletc >= 10){
				$this->db->where('rollover_class', '1'); 
				$this->db->where('rollover_userid', $key->id);
				$jml_row = $this->db->get('tb_rollover')->num_rows();
				if ($jml_row > 0){  
					$data['kelas'] = '0';
				}else{
					$data['kelas'] = '1';
				} 
				// cek apakah user tsb ada dalam board
				$this->db->where('rollover_userid', $key->id);
				$this->db->where('rollover_class', '1'); 
				$jml = $this->db->get('tb_rollover')->num_rows();
				if ($jml == 0){  
					$object = array(
						'rollover_userid'	=> $key->id,
						'rollover_class' 	=> $data['kelas'],
						'rollover_amount' 	=> '10',
						'rollover_txid'		=> generateTxid(),
						'rollover_date'		=> date('Y-m-d H:i:s')
					);
					$this->db->insert('tb_rollover', $object);
					$jml = 10 / $priceNx;
					$this->walletmodel->pengurangan('C', $jml,$key->id);
					if ($data['kelas'] != 0){
						if ($this->updateUrutan('3','1') == true){
							$this->updateUrutan('5','2');
						}
					}
				}
			}
			if ($this->updateUrutan('3','1') == true){
				$this->updateUrutan('5','2');
			}
		}
		if ($this->updateUrutan('3','1') == true){
			$this->updateUrutan('5','2');
		}
		return '';
	}

	public function updateUrutans($jml='3',$kelas='1')
	{
		$perubahan = false;
		$class2 = 0; 
		
		$this->db->order_by('rollover_id', 'asc');
		$this->db->where('rollover_class', $kelas);
		$class = $this->db->get('tb_rollover');
		$i = $class->num_rows(); 
		if ($i > $jml){
			$data = $class->row(); 
			$this->db->where(array('rollover_id'	=> $data->rollover_id));
			$objectsss = array(
				'rollover_class' 	=> ($kelas+1)
			);
			$this->db->update('tb_rollover', $objectsss);
			$objectd = array(
				array(
					'bonus_userid' 		=> $data->rollover_userid,
					'bonus_type' 		=> 'A',
					'bonus_amount'		=> '20',
					'bonus_name'		=> 'Bonus Rollover',
					'bonus_desc'		=> 'Bonus Rollover',
					'bonus_type'		=> 'btc',
				),
				array(
					'bonus_userid' 		=> 1,
					'bonus_type' 		=> 'A',
					'bonus_amount'		=> '5',
					'bonus_name'		=> 'Bonus Admin Rollover',
					'bonus_desc'		=> 'Bonus Admin Rollover',
					'bonus_type'		=> 'btc',
				),
			);
			$object = array(
				'rollover_userid'	=> $data->rollover_userid,
				'rollover_class' 	=> 'Got Bonus From Board '.$kelas, 
				'rollover_txid'		=> $data->rollover_txid,
				'rollover_date'		=> date('Y-m-d H:i:s')
			);
			$this->db->insert('tb_rollover_history', $object);
			// cek apakah dia dapat bonus_rollover
			if ( userdata(array('id' => $data->rollover_userid))->rollover == 1 ){
				$this->db->insert_batch('tb_bonus', $objectd);
			}

			$perubahan = true;
		
		}
		return $perubahan;

	}

	public function updateUrutan($jml='3',$kelas='1')
	{
		$perubahan = false;

		$this->db->order_by('rollover_id', 'asc'); 
		$this->db->where('rollover_class', $kelas);
		$class = $this->db->get('tb_rollover');
		$i = $class->num_rows(); 
		
		$this->db->order_by('rollover_id', 'asc'); 
		$class2 = $this->db->get('tb_rollover');
		$jumlah = $class2->num_rows(); 
 
		if (($i != 0)){
			if (($jumlah % $jml) == 0){
				if (option('board'.$kelas) == $jumlah){
				
					$data = $class->row();
					$this->db->where(array('rollover_id'	=> $data->rollover_id));

					$object = array(
						'rollover_class' 	=> ($kelas+1)
					);
					$this->db->update('tb_rollover', $object);
					$objectd = array(
						array(
							'bonus_userid' 		=> $data->rollover_userid,
							'bonus_type' 		=> 'A',
							'bonus_amount'		=> '20',
							'bonus_name'		=> 'Bonus Rollover',
							'bonus_desc'		=> 'Bonus Rollover',
							'bonus_type'		=> 'btc',
						),
						array(
							'bonus_userid' 		=> 1,
							'bonus_type' 		=> 'A',
							'bonus_amount'		=> '5',
							'bonus_name'		=> 'Bonus Admin Rollover',
							'bonus_desc'		=> 'Bonus Admin Rollover',
							'bonus_type'		=> 'btc',
						),
					);
					$object = array(
						'rollover_userid'	=> $data->rollover_userid,
						'rollover_class' 	=> 'Got Bonus From Board '.$kelas, 
						'rollover_txid'		=> $data->rollover_txid,
						'rollover_date'		=> date('Y-m-d H:i:s')
					);
					$this->db->insert('tb_rollover_history', $object);
					// cek apakah dia dapat bonus_rollover
					if ( userdata(array('id' => $data->rollover_userid))->rollover == 1 ){
						$this->db->insert_batch('tb_bonus', $objectd);
					} 
					$perubahan = true; 


					$iki = array(
							'opt_value' =>  (option('board'.$kelas) + $jml)
					);
					$this->db->update('tb_options', $iki, array('opt_id' => $kelas));
				}
			}
		}
		return $perubahan;
	}

}

/* End of file  */

/* Location: ./application/models/ */