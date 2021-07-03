<?php

/* Copyright (C) Rivas System, Inc. All rights reserved. */

require_once dirname(dirname(__FILE__)) . '/narinconfig.php';

$form_name = $_GET['form'];
$dataviewer_class_name = $form_name . '_dataviewer';

include_once $NarinConfig->path . "/forms/$form_name/dataviewer.php";

$dataviewer = class_exists($dataviewer_class_name, false) ? new $dataviewer_class_name() : null;

?>
<!DOCTYPE html>
<html dir="rtl">
<head>
	<meta charset="UTF-8" />
	<title><?= is_null($dataviewer) ? 'فرم وجود ندارد' : htmlspecialchars($dataviewer->get_title()) ?></title>
	<link rel="stylesheet" type="text/css" href="../static/style/narindataviewer.css" />
	<script type="text/javascript" src="../static/script/jquery.js"></script>
	<script type="text/javascript" src="../static/script/narindataviewer.js"></script>
</head>
<?php flush(); ?>
<body>
<?= is_null($dataviewer) ? '<h1>فرم وجود ندارد</h1>' : $dataviewer->get_view_using_post() ?>
</body>
</html>