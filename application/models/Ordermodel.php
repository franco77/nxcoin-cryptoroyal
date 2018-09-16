<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ordermodel extends CI_Model {

    protected $table = 'tb_orders';

    public function __construct() {

        parent::__construct();

    }


    public function history($start, $limit, $order_column, $order_sort, $search) {

        //$totalRows = $this->db->select('COUNT(order_id) as total_row')->from($this->table)->get()->row()->total_row;
        $fields = [
            "order_id",
            "sell_id",
            "buy_id",
            "o.pair",
            "o.price",
            "o.amount",
            "o.created_at",
            "o.updated_at",
            "u1.username",
            "u2.username",
        ];
        $data = $this->db
            ->select('
                o.*,
                u1.id as seller_id,
                u2.id as buyer_id,
                u1.username as seller_username,
                u2.username as buyer_username
            ')
            ->from( $this->table .' as o')
            ->join('tb_booking_orders b1','o.sell_id = b1.booking_id')
            ->join('tb_booking_orders b2', 'o.buy_id = b2.booking_id')
            ->join('tb_users u1','b1.user_id = u1.id')
            ->join('tb_users u2','b2.user_id = u2.id');
        if(!empty($search)) {
            foreach($fields as $field) {
                $data->or_like($field, $search);
            }
        }
        $tmp = clone $this->db;

        $totalRows = $tmp->get()->num_rows();
        $result = $data->limit($limit)
            ->offset($start)
            ->order_by($order_column,$order_sort)
            ->get()->result();
        
        return [
            'totalRows' => $totalRows,
            'data' => $result
        ];


    }

    public function user_order_history($userid = NULL) {
        $userid = ($userid == NULL) ? userid() : $userid;

        $sell = $this->db->select('o.*,b.type')-> from('tb_booking_orders b')
        ->join('tb_orders o',' b.booking_id = o.sell_id ')
        ->where('b.user_id', $userid)
        ->get()->result();


        $buy = $this->db->select('o.*, b.type')
        ->from('tb_booking_orders b')
        ->join('tb_orders o', 'b.booking_id = o.buy_id')
        ->where('b.user_id', $userid)
        ->get()->result();

        // $this->db->select('o.*,b.type')->from('tb_booking_orders b, tb_orders o')
        // ->where('b.user_id', $userid)
        // ->where('b.booking_id = o.buy_id', NULL, FALSE)
        // ->or_where('b.booking_id = o.sell_id', NULL, FALSE)
        // ->get()->result();
        $data = [];
        if(!empty($sell)) {
            $data += $sell;
        }
        if(!empty($buy)) {
            $data += $buy;
        }

        return $data;

    }

}