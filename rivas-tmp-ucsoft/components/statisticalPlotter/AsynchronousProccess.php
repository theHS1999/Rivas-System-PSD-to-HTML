<?php

/**
 *
 * AsynchronousProcess.php
 * asynchronousproccess file
 *
 * @copyright	Copyright (C) 2012 Rivas Systems Inc. All rights reserved.
 * @versin	1.00 2012-02-05
 * @author	Behnam Salili
 *
 */

require_once 'AsynchronousProcessClass.php';

if (isset($_POST['reportingSource'])) {
	echo AsynchronousProcess::sourceSelection($_POST['reportingSource']);
}

if (isset($_POST['townId'])) {
	echo AsynchronousProcess::getCenters($_POST['townId']);
}

if (isset($_POST['townIdForUnit'])) {
	echo AsynchronousProcess::getUnitsByTownId($_POST['townIdForUnit']);
}

if (isset($_POST['centerId']) && isset($_POST['unitType'])) {
	echo AsynchronousProcess::getUnits($_POST['centerId'], $_POST['unitType']);
}

if (isset($_POST['centerId']) && !isset($_POST['unitType'])) {
    echo AsynchronousProcess::getUnits($_POST['centerId'], null);
}

if (isset($_POST['centerIdForHygieneUnitSelection'])) {
	echo AsynchronousProcess::getHygieneUnitsByCenterId($_POST['centerIdForHygieneUnitSelection']);
}

if (isset($_POST['centersId'])) {
	echo AsynchronousProcess::drawServicesChart($_POST['centersId'], $_POST['serviceId'], $_POST['date_period_from_day'], $_POST['date_period_from_month'], $_POST['date_period_from_year'], $_POST['date_period_to_day'], $_POST['date_period_to_month'], $_POST['date_period_to_year']);
}

if (isset($_POST['distinctUnitNames'])) {
	echo AsynchronousProcess::getDistinctUnits();
}
	
if (isset($_POST['distinctServiceNames'])) {
	echo AsynchronousProcess::getDistinctServices();
}

if (isset($_POST['pName'])) {
	echo $_POST['pName'];
}
?>