<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Update extends CI_Controller {

	public function __construct()
	{
		parent::__construct(); 
		//wget https://cryptoroyal.co/account/market/update_price/latest_price/
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function latest_price()
	{
		$res = $this->db->select("MAX(price) as high_price, 
		    MIN(price) as low_price, 
		    SUBSTRING_INDEX(GROUP_CONCAT(CAST(price AS CHAR)  ORDER BY created_at SEPARATOR ','), ',', 1 ) as close_price,
		    SUBSTRING_INDEX(GROUP_CONCAT(CAST(price AS CHAR)  ORDER BY created_at DESC SEPARATOR ','), ',', 1 ) as open_price,
		    FLOOR(UNIX_TIMESTAMP(created_at)/(10 * 60)) AS timekey")
		    ->from('tb_booking_orders')
		    ->where('type', 's')
		    ->where('created_at >', 'DATE_SUB(CURDATE(), INTERVAL 1 DAY)')
		    ->order_by('booking_id','desc')
		    ->group_by('timekey')
		    ->get()->result();
		foreach($res as $row){
    		echo 'HIGH :' . $row->high_price . '<br>';
    		echo 'LOW :' .  $row->low_price . '<br>';
    		echo 'CLOSE :' .  $row->close_price . '<br>';
    		echo 'OPEN :' .  $row->open_price . '<br>';
		}
	}
}