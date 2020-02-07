<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| Email
| -------------------------------------------------------------------------
| This file lets you define parameters for sending emails.
| Please see the user guide for info:
|
|	http://codeigniter.com/user_guide/libraries/email.html
|
*/
$config = array(
    'protocol' => "smtp",
    'smtp_host' => "ssl://smtp.gmail.com",
    'smtp_port' => "465",//"587", // 465 나 587 중 하나를 사용
    'smtp_user' => "dev101@baek.co",
    'smtp_pass' => "Lng1slndICT~",
    'charset' => "utf-8",
    'newline' => "\r\n",
    'mailtype' => "html",
    'smtp_timeout' => 10,
);
/* End of file email.php */
/* Location: ./application/config/email.php */