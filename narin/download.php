<?php

/* Copyright (C) Rivas System, Inc. All rights reserved. */

error_reporting(0);

require_once dirname(__FILE__) . '/narinconfig.php';

if (!isset($_GET['form']) || $_GET['form'] === '' || !isset($_GET['file']) || $_GET['file'] === '') {
	header('HTTP/1.0 400 Bad Request');
	exit;
}

$form_name = $_GET['form'];
$file_name = $_GET['file'];

if (!preg_match('/^[a-z0-9_]+$/i', $form_name) || !preg_match('/^[a-z0-9]+\.[a-z0-9]+$/i', $file_name)) {
	header('HTTP/1.0 404 Not Found');
	exit;
}

$file_path = $NarinConfig->path . '/forms/' . $form_name . '/uploads/' . str_replace('.', '_', $file_name);

if (!is_file($file_path)) {
	header('HTTP/1.0 404 Not Found');
	exit;
}

/*
if (!has_access($form_name)) {
	header('HTTP/1.0 403 Forbidden');
	exit;
}
*/

$file = fopen($file_path, 'rb');

if (!$file) {
	header('HTTP/1.0 500 Internal Server Error');
	exit;
}

$file_size = filesize($file_path);

$start = 0;
$end = $file_size - 1;
$length = $file_size;

if (isset($_SERVER['HTTP_RANGE'])) {

	if (!preg_match('/^bytes=(\d*)-(\d*)/', $_SERVER['HTTP_RANGE'], $matches) || ($matches[1] === '' && $matches[2] === '')) {
		header('HTTP/1.1 416 Requested Range Not Satisfiable');
		fclose($file);
		exit;
	}
	
	$start = (int)$matches[1];
	$end = $matches[2] === '' ? $file_size - 1 : (int)$matches[2];
	
	if ($start >= $end || $end >= $file_size) {
		header('HTTP/1.1 416 Requested Range Not Satisfiable');
		fclose($file);
		exit;
	}
	
	$length = $end - $start + 1;
}

if ($length != $file_size) {
	header('HTTP/1.1 206 Partial Content');
	header('Content-Range: bytes ' . $start . '-' . $end . '/' . $file_size);
}
else {
	header('HTTP/1.1 200 OK');
}

header('Pragma: public');
header('Expires: -1');
header('Cache-Control: public, must-revalidate, post-check=0, pre-check=0');
header('Accept-Ranges: bytes');
header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename="' . $file_name . '"');
header('Content-Length: ' . $length);

set_time_limit(0);

$chunk_size = 1024 * 8;

fseek($file, $start);
$left = $length;

while ($left > 0) {

	$size = $chunk_size > $left ? $left : $chunk_size;
	
	echo fread($file, $size);
	ob_flush();
	flush();
	
	if (connection_status()) {
		fclose($file);
		exit;
	}
	
	$left -= $size;
}

fclose($file);