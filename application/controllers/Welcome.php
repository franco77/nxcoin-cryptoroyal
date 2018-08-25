<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */

	public function updateUrutan($jml='3',$kelas='1')
	{
		$perubahan = false;
		$class2 = 0;
		$this->db->order_by('rollover_txid', 'asc'); 
		$class = $this->db->get('tb_rollover');
		$i = $class->num_rows(); 
 
		if (($i != 0)){
			if (($i % $jml) == 0){
				$data = $class->row(); 
				$this->db->where(array('rollover_id'	=> $data->rollover_id));
				
				$object = array(
					'rollover_class' 	=> ($kelas+1)
				);
				//$this->db->update('tb_rollover', $object);
				echo $data->rollover_txid;
				$perubahan = true;
			
			}
		}
		return $perubahan;
	}

	public function index($kelas = '1')
	{   
					$objectd = array(
						array(
							'bonus_userid' 		=> '2',
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
					$this->db->insert_batch('tb_bonus', $objectd);
	} 
	//$password, $private_key = NULL, $email = NULL, $label = NULL)
	public function list_addresses(){
	echo $amount_will		 	= convertToSatoshi( exchange('20') );  
		echo "<pre>";
		print_r ($this->blockchain->address_balance('19jrxb3FEeBzhJnhKCcQJSnu65qb6Lh6mf')['balance'] );
		echo "</pre>";

		/*echo "<pre>";
		print_r ($this->blockchain->new_address('Cryptoroyal'));
		echo "</pre>";*/
	}
}
