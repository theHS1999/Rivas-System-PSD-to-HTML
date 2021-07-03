<?php
/**
 * * index.php, default application's page *
 * @copyright    Copyright (C) 2012 Rivas Systems Inc. All rights reserved.
 * @versin    1.00 2011-02-05 * @author    Behnam Salili *
 */


error_reporting(E_ALL); 
ini_set('display_errors', TRUE); 
ini_set('display_startup_errors', TRUE);


require_once 'components/application/application.php';
$app = new Application("نرم افزار مدیریت مراکز بهداشتی-درمانی استان گیلان");
if ($app->initialise()) $app->render();

?>
