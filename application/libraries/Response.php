<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Response {

    protected $data;
    protected $CI;
    public function __construct() {
        $CI =& get_instance();
        $CI->output->set_header("Pragma: no-cache");
        $CI->output->set_header("Cache-Control: no-store, no-cache");
        $this->CI = $CI;

    }

    public function create($data, $code) {
        
        $this->CI->output->set_status_header($code);
        $this->data = $data;
        return $this;
    }

    public function json() {
        $this->CI->output->set_content_type('application/json');
        return $this->CI->output->set_output(json_encode($this->data));
    }


}