<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Withdrawmodel extends CI_Model {


    public function __construct() {

        parent::__construct();
    }

    public function create_request($userid = NULL, $amount, $wallet_sender, $receiver, $request_type = 'BTC') {

        

        $userid = ($userid) ? $userid : userid();
        
        $userdata = userdata(['id' => $userid]);

        if(!$userdata) { return ['status' => FALSE]; }

        define('unique_code',base64_encode(time().time().$userid));

        $user_email = $userdata->email;

        $this->db->trans_begin();
        
		$this->db->insert('tb_request_wd', [
            'req_user_id' => $userid,
            'req_amount' => $amount,
            'req_wallet_sender' => $wallet_sender->wallet_address,
            'req_wallet_receiver' => $receiver,
            'req_unique_code' => unique_code,
            'req_type' => $request_type,
            'req_status' => 'waiting',
            'req_created_at' => sekarang()
        ]);
        
        $insert_id = $this->db->insert_id();
        $request_object = $this->find($insert_id);

        $email_template = $this->load->view('library/email-request-wd',[
            'request' => $request_object
        ], TRUE);
        
        $subject = 'CRYPTOROYAL - Request Withdrawal';

        $mail_sent = $this->mailermodel->send($user_email, $subject, $email_template);

        if( !$mail_sent ) {

            $this->db->trans_rollback();
            return ['status' => false];

        } else {

            $this->db->trans_commit();

        }

        return ['status' => true];
    }

    public function get_request( $userid = NULL, $request_type = 'BTC', $status = 'waiting' ) {


        $userid = ($userid) ? $userid : userid();

        return $this->db
        ->select('*')
        ->from('tb_request_wd')
        ->where('req_user_id', $userid)
        ->where('req_type', $request_type)
        ->where('req_status', $status)
        ->get();


    }
    public function is_request_expired( $date ) {
        $date = new DateTime($date);
        $expired_at = $date->add(new DateInterval('PT3M'));
        $current = new DateTime();

        if( $current->getTimestamp() >= $expired_at->getTimestamp() ) {

            return true;

        }
        return false;

    }

    public function cancel_request( $req_id ) {
        
        $this->db
            ->where('id_request', $req_id)
            ->update('tb_request_wd', [
                'req_status' => 'canceled'
            ]);
        return true;

    }
    public function approve_request($req_id) {
        $this->db
            ->where('id_request', $req_id)
            ->update('tb_request_wd', [
                'req_status' => 'approved'
            ]);
        return true;
    }

    public function find( $id_request) {
        return $this->db->from('tb_request_wd')
        ->where('id_request', $id_request)
        ->get()->row();
    }

    public function find_code( $code ) {
        $req = $this->db->from('tb_request_wd')
        ->where('req_unique_code', $code)
        ->where('req_status', 'waiting')
        ->get()->row();

        if(!$req) {
            return false;
        }

        if( $this->is_request_expired( $req->req_created_at ) ) {
            $this->cancel_request( $req->id_request );
            return false;
        }
        return $req;

    }


}