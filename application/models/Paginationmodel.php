<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Paginationmodel extends CI_Model {

	public $variable;

	public function __construct()
	{
		parent::__construct();
		
	}


	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function paginate( $uri_string = null, $num_rows = 0, $limit_per_page = 20 )
	{

		// $this->load->library('pagination');
		
		$config['base_url'] 		= $uri_string;
		$config['total_rows'] 		= $num_rows;
		$config['per_page'] 		= $limit_per_page;
		$config['page_query_string']= false;  
		$config['uri_segment'] 		= 3;
		$config['num_links'] 		= 9;
		$config['page_query_string']= TRUE;

		$config['query_string_segment'] = 'page';
		$config['full_tag_open'] 	= '<ul class="pagination ">';
		$config['full_tag_close'] 	= '</ul>';
		$config['first_link'] 		= '&laquo; First';
		$config['first_tag_open'] 	= '<li class="previous page-item page-link">';
		$config['first_tag_close'] 	= '</li>';
		$config['last_link'] 		= 'Last &raquo;';
		$config['last_tag_open'] 	= '<li class="next page-item page-link">';
		$config['last_tag_close'] 	= '</li>';
		$config['next_link'] 		= 'Next &rarr;';
		$config['next_tag_open'] 	= '<li class="next page-item page-link">';
		$config['next_tag_close'] 	= '</li>';
		$config['prev_link'] 		= '&larr; Previous';
		$config['prev_tag_open'] 	= '<li class="previous page-item page-link">';
		$config['prev_tag_close'] 	= '</li>';
		$config['cur_tag_open'] 	= '<li class="page-item active"><a href="" class="page-link">';
		$config['cur_tag_close'] 	= '</a></li>';
		$config['num_tag_open']		= '<li class="page-item page-link">';
		$config['num_tag_close'] 	= '</li>';
		// $config['display_pages'] = FALSE;

		$config['anchor_class'] 		= 'follow_link';
		//$this->load->library('pagination', $config);
		$this->pagination->initialize($config);
		
		return $this->pagination->create_links();

	}

}

/* End of file Paginationmodel.php */
/* Location: ./application/models/Paginationmodel.php */