<?php

/* Copyright (C) Rivas System, Inc. All rights reserved. */

require_once dirname(dirname(__FILE__)) . '/narinconfig.php';

$form_source = isset($_GET['form']) && $_GET['form'] !== '' ? file_get_contents($NarinConfig->path . '/forms/' . $_GET['form'] . '/source.json') : false;

?>
<!DOCTYPE html>
<html lang="fa-IR" dir="rtl">
<head>
	<meta charset="UTF-8" />
	<title>فرم‌ساز نارین</title>
	<link rel="stylesheet" type="text/css" href="../static/style/narinformcreator.css" />
	<script type="text/javascript" src="../static/script/jquery.js"></script>
	<script type="text/javascript" src="../static/script/json2.js"></script>
	<script type="text/javascript" src="../static/script/narinformcreator.js"></script>
</head>
<body>
<?php
include dirname(__FILE__) . '/narinformcreator.html';
if ($form_source !== false) {
	echo "\r\n" . '<script type="text/javascript"> NarinFormCreator.load(' . $form_source . '); </script>';
}
?>
</body>