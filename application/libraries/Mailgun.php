<?php
defined('BASEPATH') OR exit('No direct script access allowed');
 
class Mailgun {
 
    var $_to = "";
    var $_cc = "";
    var $_bcc = "";
    var $_from = "";
    var $_subject = "";
    var $_message = "";
    var $_attachments = array();
    protected $apikey = '49541e26f026393a909e30daaf22c6ec-c8e745ec-a4b9870a';
    protected $mailgun_domain = 'mg.cryptoroyal.co';
 
    public function to($to){
        $this->_to = $to;
        return $this;
    }
 
    public function cc($cc){
        $this->_cc = $cc;
        return $this;
    }
 
    public function bcc($bcc){
        $this->_bcc = $bcc;
        return $this;
    }
 
    public function from($from){
        $this->_from = $from;
        return $this;
    }
 
    public function subject($subject){
        $this->_subject = $subject;
        return $this;
    }
 
    public function message($message){
        $this->_message = $message;
        return $this;
    }
 
    public function attachments($attachments){
        $this->_attachments[] = $attachments;
        return $this;
    }
 
    public function attach($attachment){
      return $this->attachments($attachment);
    }
 
    public function send(){
      date_default_timezone_set('Asia/Jakarta');
      $url = "https://api.mailgun.net/v3/".$this->mailgun_domain."/messages";
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: multipart/form-data',
      ));  
      curl_setopt($ch, CURLOPT_URL, $url);
      curl_setopt($ch, CURLOPT_USERPWD, "api:" . $this->apikey);  
      curl_setopt($ch, CURLOPT_POST, 1);
      $data = array(
        'to' => $this->_to,
        'from' => $this->_from,
        'subject' => $this->_subject,
        'html' => $this->_message,
      );
      if($this->_cc){
        $data["cc"] = $this->_cc;
      }
      if($this->_bcc){
        $data["bcc"] = $this->_bcc;
      }
      for($i = 0; $i < count($this->_attachments); $i++){
        $data["attachment[" . ($i+1) . "]"] = "@" . $this->_attachments[$i];
      }
      curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      $server_output = curl_exec ($ch);
      return TRUE;
    }
}
?>