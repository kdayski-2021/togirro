<?php

defined("BASEPATH") OR exit("No direct script access allowed");

$config["mailtype"]	 = "html";
$config["charset"]	 = "utf-8";
$config["wordwrap"]	 = TRUE;

$config["protocol"]		 = "smtp";
$config["smtp_host"]	 = "ssl://smtp.mail.ru";
$config["smtp_user"]	 = "Forum@togirro.ru"; // E-mail
$config["smtp_pass"]	 = "IRpyyP3rtU8"; // Пароль
$config["smtp_port"]	 = "465";
$config["smtp_timeout"]	 = "7";

//alternative

// $config['smtp_host'] = 'smtp.gmail.com';
// $config['smtp_port'] = '587';
// $config['smtp_user'] = 'K.togirro@togirro.ru';
// $config['_smtp_auth'] = TRUE;
// $config['smtp_pass'] = 'AKUetoYuu87';
// $config['smtp_crypto'] = 'tls';
// $config['protocol'] = 'smtp';
// $config['mailtype'] = 'html';
// $config['send_multipart'] = FALSE;
// $config['charset'] = 'utf-8';
// $config['wordwrap'] = TRUE;
// $config["smtp_timeout"]	 = "7";
