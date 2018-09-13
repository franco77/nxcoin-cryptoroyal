<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Este helper gera os elementos do bootstrap 3 usando PHP
| ao invés de HTML puro.
|
| Foi desenvolvido para ser usado no FrameWork CodeIgniter em conjunto
| com o helper HTML e URL.
| 
| @author Eliel de Paula <elieldepaula@gmail.com>
| @since 20/10/2014
|--------------------------------------------------------------------------
*/
if ( ! function_exists( 'sekarang' ) ) {

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author Ayatulloh Ahad r
	 **/
	function sekarang()
	{
		return date('Y-m-d H:i:s');
	}

}

if ( ! function_exists( 'generateWallet' ) ) {

	/**
	* undocumented function
	*
	* @return void
	* @author
	**/
	function generateWallet()
	{

		//format: afb8032a-4b09-41ab-be96-cf4f50b9f202
		$random_1 	= strtolower(
			substr( hash('sha256', random_string( 'alnum', 64 ) ) , 0, 8 )
		);

		$random_2 	= strtolower(
			substr( hash('sha256', random_string( 'alnum', 64 ) ) , 0, 4 )
		);

		$random_3 	= strtolower(
			substr( hash('sha256', random_string( 'alnum', 64 ) ) , 0, 4 )
		);

		$random_4 	= strtolower(
			substr( hash('sha256', random_string( 'alnum', 64 ) ) , 0, 4 )
		);

		$random_5 	= strtolower(
			substr( hash('sha256', random_string( 'alnum', 64 ) ) , 0, 12 )
		);


		return $random_1 . '-' . $random_2 . '-' . $random_3 . '-' . $random_4 . '-' .$random_5;

	}

}

if ( ! function_exists( 'generateTxid' ) ) {

	/**
	* undocumented function
	*
	* @return void
	* @author
	**/
	function generateTxid($nomor)
	{
		$CI 	=& get_instance();
		$get 	= $CI->db->get('tb_rollover');
		$nomor = $get->num_rows(); 

		$nomor = $nomor++;
		$random_3 = str_pad($nomor, 8, "0", STR_PAD_LEFT);


		return $random_3;

	}

}

if ( ! function_exists( 'post' ) ) {
	/**
	 * undocumented function
	 *
	 * @return void
	  * @author Ayatulloh Ahad R [ayatulloh@idprogrammer.com]
	 **/
	function post( $key = null )
	{
	    $return     = null;
	    if( $key != null ){

	        $CI =& get_instance();
	        $return     = $CI->input->post( $key );
	    
	    }

	    return $return;
	    
	}
}

if ( ! function_exists( 'get' ) ) {
	/**
	 * undocumented function
	 *
	 * @return void
	  * @author Ayatulloh Ahad R [ayatulloh@idprogrammer.com]
	 **/
	function get( $key = null )
	{
	    $return     = null;
	    if( $key != null ){

	        $CI =& get_instance();
	        $return     = $CI->input->get( $key );
	    
	    }

	    return $return;
	    
	}
}

if ( ! function_exists( 'userid' ) ) {
	
	/**
	 * undocumented function
	 *
	 * @return void
	 * @author Ayatulloh Ahad r
	 **/
	function userid()
	{
		$CI =& get_instance();
		return $CI->session->userdata('user_id');
	}

}


if ( ! function_exists( 'balance' ) ) {
	
	/**
	 * undocumented function
	 *
	 * @return void
	 * @author Ayatulloh Ahad r
	 **/
	function balance($id = '', $type='in', $amount='', $desc = '')
	{
		$CI =& get_instance();
		
		$data = array(
			'balance_userid'	=> $id,
			'balance_amount'	=> $amount,
			'balance_type'		=> $type,
			'balance_desc'		=> $desc
		);
		$CI->db->insert('tb_balance', $data);
		
	}

}

if ( ! function_exists( 'balance_eth' ) ) {
	
	/**
	 * undocumented function
	 *
	 * @return void
	 * @author Ayatulloh Ahad r
	 **/
	function balance_eth($id = '', $type='in', $amount='', $desc = '')
	{
		$CI =& get_instance();
		
		$data = array(
			'balance_userid'	=> $id,
			'balance_amount'	=> $amount,
			'balance_type'		=> $type,
			'balance_desc'		=> $desc
		);
		$CI->db->insert('tb_balance', $data);
		
	}

}

if ( ! function_exists( 'report' ) ) {
	
	/**
	 * undocumented function
	 *
	 * @return void
	 * @author Ayatulloh Ahad r
	 **/
	function report($from = null, $amount = null, $desc = null)
	{
		$CI =& get_instance();
		$object = array(
			'report_from' 	=> $from,
			'report_to' 	=> '1',
			'report_amount' => $amount,
			'report_desc' 	=> $desc
		);
		$CI->db->insert('tb_report', $object);

		return true;
	}

}

if ( ! function_exists( 'getreport' ) ) {
	
	/**
	 * undocumented function
	 *
	 * @return void
	 * @author Ayatulloh Ahad r
	 **/
	function getreport( $type = null, $by = null, $add=null)
	{
		$CI =& get_instance();
		if ($type = 'day'){
			if (($by != '') && ($add != '')){ 
				$CI->db->where('report_date BETWEEN `'.$by.'` and `'.$add.'`'); 
			}else if($add == ''){
				$CI->db->like('report_date', $by, 'BOTH'); 
			}else{
				$CI->db->like('report_date', date('Y-m-d'), 'BOTH');
			}
		}else if($type = 'id'){
			if ($add != ''){ 
				$CI->db->where('report_from', $by);
				$CI->db->where('report_to', $add);
			}else{
				$CI->db->where('report_from', $by);
			}
		}
		
		return $CI->db->get('tb_report')->result();
	}

}
 


if ( ! function_exists( 'userdata' ) ) {
    
    /**
     * undocumented function
     *
     * @return void
     * @author Ayatulloh Ahad R [ayatulloh@idprogrammer.com]
     **/
    function userdata( $where_data = null )
    {
        $CI =& get_instance();
        $return = false;
        if ( $where_data != null ):
            foreach ($where_data as $key => $value) :

                $CI->db->where( $key , $value);

            endforeach;
        else:

            $CI->db->where('id', userid() );

        endif;

        // $CI->db->join('tb_users_meta', 'tb_users_meta.um_user_id = tb_users.id', 'left');
        $get    = $CI->db->get('tb_users');
        if ( $get->num_rows() == 1 ) {
            
            $return = $get->row();
            
        } else if ( $get->num_rows() > 1 ) {

            $return = $get->result();

        }
    
        return $return;
    }

}



if ( ! function_exists( 'howdy' ) ) {
	
	/**
	 * undocumented function
	 *
	 * @return void
	 * @author Ayatulloh Ahad r
	 **/
	function howdy( $string = 'Guest' )
	{
		$get_hour 		= date('H');
		if ( ($get_hour >= 0) && ($get_hour < 10) ) {
			
			$output_string 		= 'Morning';

		} elseif ( ($get_hour >= 10) && ($get_hour < 21) ) {

			$output_string 		= 'Afternoon';

		} elseif ( $get_hour >= 21 ) {

			$output_string 		= 'Evening';

		}

		return ucwords('Good ' .$output_string. ', ' .$string );

	}

}


if ( ! function_exists( 'user_picture' ) ) {
	
	/**
	 * undocumented function
	 *
	 * @return void
	 * @author Ayatulloh Ahad r
	 **/
	function user_picture( $gender 	= 'man' )
	{
		if ( $gender == 'man' ) {
			$image 	= 'default-man';
		} else {
			$image 	= 'default-woman';
		}

		$image_path = base_url('assets/images/users/'.$image.'.png');

		return $image_path;
	}

}



if ( ! function_exists('currency'))
{
	/**
	 * undocumented function
	 *
	 * @return void
	 * @author Ayatulloh Ahad r
	 **/
	function currency( $string, $count = 2, $curency = '$' )
	{
		return $curency . number_format($string, $count, ',', '.');
		/*setlocale(LC_MONETARY,"en_US");
		return money_format("%i", $string);*/

		// return $string . $curency;

		/*$fmt = new NumberFormatter('en_US', NumberFormatter::CURRENCY);
		return $fmt->formatCurrency($string, $curency); */

	}

}



if ( ! function_exists('option'))
{
	/**
	 * undocumented function
	 *
	 * @return void
	 * @author Ayatulloh Ahad r
	 **/
	function option($option_name ='null')
	{
		$CI 	=& get_instance();
		$get 	= $CI->db->get_where('tb_options', array('opt_name' => $option_name) );
		if ( $get->num_rows() == 1 ) {
			return $get->row()->opt_value;
		}
	}

}


if ( ! function_exists('user_by_token') ) {
	
	/**
	 * undocumented function
	 *
	 * @return void
	 * @author Ayatulloh Ahad r
	 **/
	function user_by_token( $token = null )
	{
		$CI =& get_instance();
		$CI->db->where('token', $token);
		$get 	= $CI->db->get('tb_anggota');
		if ( $get->num_rows() == 1 ) {
			
			return $get->row();

		}
	}

}


if ( ! function_exists('script_tag'))
{	

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author Ayatulloh Ahad r
	 **/
	function script_tag($src = '', $type = 'text/javascript', $index_page = FALSE)
    {
        $CI =& get_instance();

        $link = '';
        if (is_array($src))
        {
            foreach ($src as $v)
            {
                $link .= script_tag($v,$type,$index_page);
            }

        }
        else
        {
            $link = '<script ';
            if ( strpos($src, '://') !== FALSE)
            {
                $link .= 'src="'.$src.'" ';
            }
            elseif ($index_page === TRUE)
            {
                $link .= 'src="'.$CI->config->site_url($src).'" ';
            }
            else
            {
                $link .= 'src="'.$CI->config->slash_item('base_url').$src.'" ';
            }

            $link .= " type='{$type}'></script>";
        }
        return $link;
    }
}


if ( ! function_exists( 'time_span' ) ) {
    
    /**
     * undocumented function
     *
     * @return void
     * @author 
     **/
    function time_span( $post_date = null, $distance = 2 )
    {
        
    	$post_date 	= ( is_numeric( $post_date ) )? date('Y-m-d H:i:s', $post_date) : $post_date;

        $date1 = new DateTime( $post_date );
        $date2 = new DateTime( date('Y-m-d H:i:s') );
        $interval = $date1->diff($date2);
        

        if( $interval->days >= 5 ){
            $show_date  = date('d F Y H:i', strtotime( $post_date ));
        } else {
            $show_date  = timespan( strtotime( $post_date ), time(), $distance ). ' ago';
        }

        return $show_date;

    }

}

if( ! function_exists( 'response' ) ) {


	function response($data, $code = 200) {

		$ci =& get_instance();
		$ci->load->library('response');
		return $ci->response->create($data,$code);

	}

}

if(!function_exists('csrf_field') ) {
	function csrf_field() {
		$ci =& get_instance();
		$token = $ci->security->get_csrf_hash();
		return "<input type='hidden' name='csrf_nx' value='$token'>";
	}
}