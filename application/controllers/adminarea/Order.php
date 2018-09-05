<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Order extends MY_Controller {


    public function __construct() {

        parent::__construct();
        $this->load->model('ordermodel');

    }


    public function history( $type = NULL ) {

        //return response($this->input->get('start'));
        $start = $this->input->get('start');
        $limit = $this->input->get('length');
        $order = $this->input->get('order');
        $columns = $this->input->get('columns');

        $order_column   = $columns[ $order[0]['column'] ]['data'];
        $order_sort     = $order[0]['dir'];
        $search = $this->input->get('search')['value'];

        $orderHistory = $this->ordermodel->history(
            $start,
            $limit,
            $order_column,
            $order_sort,
            $search
        );
        $recordsTotal = $recordsFiltered = $orderHistory['totalRows'];
        $data = $orderHistory['data'];

        return response([

            'data' => $data,
            'draw' => $this->input->get('draw'),
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered

        ])->json();
        
    }



}