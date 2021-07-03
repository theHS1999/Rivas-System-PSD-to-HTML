<?php

$NarinConfig = (object)array(
	
	'db_name'      => 'narin',
	'db_user'      => 'root',
	'db_pass'      => '',
	'db_host'      => 'localhost',
	'table_prefix' => '', // only characters A-Z a-z 0-9 _ -
	'expiry_time'  => 1800, // in seconds
	'url'          => 'http://narin.localhost.com',
	'path'         => dirname(__FILE__)
	
);