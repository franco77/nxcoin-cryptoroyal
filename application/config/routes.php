<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$route['register'] 					= 'auth/register';

$route['default_controller'] 		= 'member/view';

$route['(:any)'] 					= 'member/view/$1';
$route['welcome'] 					= 'welcome/index';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;
