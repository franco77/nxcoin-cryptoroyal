<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


// $config['useragent']		= CNF_EMAIL_USERAGENT;
/*$config['protocol'] 		= 'smtp';

$config['smtp_host'] 		= 'mail.smtp2go.com';
$config['smtp_user'] 		= 'admin@cryptoroyal.co';
$config['smtp_pass'] 		= 'Bismillah123!@#';
$config['smtp_port']		= '25';
// $config['smtp_crypto']		= 'SSL';

$config['charset'] 			= 'iso-8859-1';
$config['wordwrap'] 		= TRUE;
$config['mailtype'] 		= 'html';
$config['newline'] 			= '\r\n';*/
$config['mail_1'] = [
    'protocol'      => 'smtp',
    'smtp_host'     => 'mail.smtp2go.com',
    'smtp_user'     => 'admin@cryptoroyal.co',
    'smtp_pass'     => 'Bismillah123!@#',
    'smtp_port'     => '8025',
    'charset'       => 'iso-8859-1',
    'wordwrap'      => TRUE,
    'mailtype'      => 'html',
    'newline'       => '\r\n',
];
$config['mail_2'] = [
    'protocol'      => 'smtp',
    'smtp_host'     => 'mail.cryptoroyal.co',
    'smtp_user'     => 'info@cryptoroyal.co',
    'smtp_pass'     => 'c1?%9$8*}^wV',
    'smtp_port'     => '465',
    'smtp_crypto'   => 'SSL',
    'charset'       => 'iso-8859-1',
    'wordwrap'      => TRUE,
    'mailtype'      => 'html',
    'newline'       => '\r\n',
];
//$config['used_mail'] = 'mail_1';

$config['mailgun_api_key'] = '49541e26f026393a909e30daaf22c6ec-c8e745ec-a4b9870a';