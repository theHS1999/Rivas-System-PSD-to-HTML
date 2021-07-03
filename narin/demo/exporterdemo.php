<?php

/* Copyright (C) Rivas System, Inc. All rights reserved. */

error_reporting(0);

require_once dirname(dirname(__FILE__)) . '/narinconfig.php';

$form_name = $_GET['form'];
$dataviewer_class_name = $form_name . '_dataviewer';

include_once $NarinConfig->path . "/forms/$form_name/dataviewer.php";

$dataviewer = class_exists($dataviewer_class_name, false) ? new $dataviewer_class_name() : null;

if (is_null($dataviewer)) {
	header('HTTP/1.0 404 Not Found');
	exit;
}

$dataviewer->export();
