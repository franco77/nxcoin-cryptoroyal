<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$route['register'] 					= 'auth/register';

$route['default_controller'] 		= 'member/view';

$route['(:any)'] 					= 'member/view/$1';
$route['welcome'] 					= 'welcome/index';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

$route['wallet/balance/(:any)'] = 'wallet/balance/$1';

$route['admin/order/history'] = 'adminarea/order/history/$1';
$route['order/history'] = 'order/history/$1';
$route['admin/user-bonuses/(:any)'] = 'adminarea/bonuses/get_bonus/$1';
$route['walletbtc/confirm-withdraw'] = 'walletbtc/confirm_request_wd';