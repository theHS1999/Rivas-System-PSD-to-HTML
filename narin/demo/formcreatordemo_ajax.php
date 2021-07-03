<?php

/* Copyright (C) Rivas System, Inc. All rights reserved. */

require_once dirname(dirname(__FILE__)) . '/narinconfig.php';

require_once $NarinConfig->path . '/lib/narin/narinformcreator.php';

if (!isset($_POST['form_source']) || !isset($_POST['action'])) {
	die('error:bad request');
}

try {
	$form_creator = new NarinFormCreator($_POST['form_source']);
	switch ($_POST['action']) {
		case 'create':
			$form_creator->create_form();
			break;
		case 'update':
			$form_creator->update_form();
			break;
		default:
			die('error:bad request');
	}
}
catch (Exception $e) {
	die('error:' . $e->getMessage());
}

die('success:' . $form_creator->get_form_name());