<?php

require_once dirname(__FILE__) . '/narinconfig.php';

require_once $NarinConfig->path . '/lib/securimage/securimage.php';

$captcha = new Securimage();

$captcha->charset      = '0123456789';
$captcha->image_width  = 200;
$captcha->image_height = 70;
$captcha->num_lines    = 3;
$captcha->perturbation = .6;

$captcha->expiry_time  = $NarinConfig->expiry_time;
$captcha->namespace    = $_GET['form'];

$captcha->show();