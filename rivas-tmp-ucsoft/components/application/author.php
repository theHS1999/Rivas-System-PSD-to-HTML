<?php

/**
 *
 * author.php
 * author user file
 *
 * @copyright	Copyright (C) 2012 Rivas Systems Inc. All rights reserved.
 * @versin	1.00 2011-02-05
 * @author	Behnam Salili
 *
 */
 
require_once dirname(dirname(dirname(__file__))) . '/defines.php';
require_once dirname(dirname(__file__)) . '/users/author.php';

$aut = new Author("سامانه مدیریت مراکز بهداشتی-درمانی استان گیلان :: نویسنده");

if ($aut->loggedIn())
    $aut->render();
else
    echo ACCESS_FORBIDDEN;

?>