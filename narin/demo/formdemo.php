<?php

/* Copyright (C) Rivas System, Inc. All rights reserved. */

require_once dirname(dirname(__FILE__)) . '/narinconfig.php';

$form_name = $_GET['form'];
$form_class_name = $form_name . '_form';

include_once $NarinConfig->path . "/forms/$form_name/form.php";

$form = class_exists($form_class_name, false) ? new $form_class_name() : null;

?>
<!DOCTYPE html>
<html dir="rtl">
<head>
	<meta charset="UTF-8" />
	<title><?= is_null($form) ? 'فرم وجود ندارد' : htmlspecialchars($form->get_title()) ?></title>
	<link rel="stylesheet" type="text/css" href="../static/style/narinform.css" />
	<script type="text/javascript" src="../static/script/jquery.js"></script>
	<script type="text/javascript" src="../static/script/jquery.printthis.js"></script>
	<script type="text/javascript" src="../static/script/narinform.js"></script>
</head>
<body>
<?= is_null($form) ? '<h1>فرم وجود ندارد</h1>' : $form->get_view() ?>
</body>
</html>