<?php

/* Copyright (C) Rivas System, Inc. All rights reserved. */

require_once dirname(dirname(__FILE__)) . '/narinconfig.php';

if (!(isset($_POST['form']) && isset($_POST['id']) && (isset($_POST['status']) || isset($_POST['msg'])))) {
	header('HTTP/1.0 400 Bad Request');
	exit;
}

$form = $_POST['form'];
$id = $_POST['id'];
$status = isset($_POST['status']) ? strtoupper($_POST['status']) : null;
$msg = isset($_POST['msg']) ? $_POST['msg'] : null;

if (!(preg_match('/^[a-z][a-z0-9_]*$/i', $form) && preg_match('/^[0-9]+$/', $id) && in_array($status, array(null, 'P', 'A', 'D'), true))) {
	header('HTTP/1.0 400 Bad Request');
	exit;
}

try {
	
	if (is_null($msg)) {
		$query = "UPDATE `{$form}` SET `_approval_` = ? WHERE `_id_` = ?";
		$vals = array($status, $id);
	}
	elseif (is_null($status)) {
		$query = "UPDATE `{$form}` SET `_approvalmsg_` = ? WHERE `_id_` = ?";
		$vals = array($msg === '' ? null : $msg, $id);
	}
	else {
		$query = "UPDATE `{$form}` SET `_approval_` = ?, `_approvalmsg_` = ? WHERE `_id_` = ?";
		$vals = array($status, $msg === '' ? null : $msg, $id);
	}

	$db = new PDO("mysql:host={$NarinConfig->db_host};dbname={$NarinConfig->db_name};charset=utf8", $NarinConfig->db_user, $NarinConfig->db_pass, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
	$db->exec('SET NAMES utf8');
	$st = $db->prepare($query);
	$st->execute($vals);

	if (!$st->rowCount()) {
		header('HTTP/1.0 404 Not Found');
		exit;
	}
}
catch (Exception $e) {
	header('HTTP/1.0 500 Internal Server Error');
	exit;
}
