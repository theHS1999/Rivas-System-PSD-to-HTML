<?php

/**
 *
 * administrator.php
 * administrator user file
 *
 * @copyright	Copyright (C) 2012 Rivas Systems Inc. All rights reserved.
 * @versin	1.00 2011-02-05
 * @author	Behnam Salili
 *
 */
 
require_once dirname(dirname(dirname(__file__))) . '/defines.php';
require_once dirname(dirname(__file__)) . '/users/administrator.php';

$admin = new administrator("سامانه مدیریت مراکز بهداشتی-درمانی استان گیلان :: مدیریت");

if ($admin->loggedIn())
    $admin->render();
else
    echo ACCESS_FORBIDDEN;

?>