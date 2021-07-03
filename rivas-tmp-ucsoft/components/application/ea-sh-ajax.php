<?php

/**
 *
 * ea-sh-ajax.php
 *
 * @copyright	Copyright (C) 2012 Rivas Systems Inc. All rights reserved.
 * @versin	1.00 2011-02-05
 * @author	Behnam Salili
 *
 */

require_once dirname(dirname(dirname(__file__))) . '/defines.php';
require_once dirname(dirname(__file__)) . '/database/database.php';
require_once dirname(dirname(__file__)) . '/illustrator/pdate.php';

date_default_timezone_set('Asia/Tehran');

//for center management
if (isset($_POST['townId_1'])) {
	$htmlOut = '';
	$res = Database::execute_query("SELECT `name` FROM `towns` WHERE `id` = '" . Database::filter_str($_POST['townId_1']) . "';");
	$row = Database::get_assoc_array($res);
	$townName = $row['name'];
	$res = Database::execute_query("SELECT `id`, `name` FROM `centers` WHERE `town-id` = '" . Database::filter_str($_POST['townId_1']) . "' ORDER BY `name` ASC;");
	$counter = 1;
	if (Database::num_of_rows($res) > 0) {
		$htmlOut .= '<fieldset><legend>فهرست مراکز شهرستان "' . $townName . '"</legend>';
		$htmlOut .= '<br /><table align="right" class="contentTable" border="0" cellpadding="0" cellspacing="1">';
		$htmlOut .= '<thead><th width="40">ردیف</th><th>نام</th><th width="100">تعداد واحدها</th><th width="50">حذف</th><th width="50">ویرایش</th></thead><tbody>';
		while ($row = Database::get_assoc_array($res)) {
			$resTmp = Database::execute_query("SELECT * FROM `units` WHERE `center-id` = '" . $row['id'] . "';");
			$num = intval(Database::num_of_rows($resTmp));
			$htmlOut .= '<tr><td>' . $counter . '</td><td>' . $row['name'] . '</td>';
			$htmlOut .= '<td>' . $num . '</td>';
			$htmlOut .= '<td><a href="?cmd=removeCenter&centerId=' . $row['id'] . '"><img src="../illustrator/images/icons/remove.png" title="حذف مرکز ' . $row['name'] . '" /></a></td>';
			$htmlOut .= '<td><a href="?cmd=editCenter&centerId=' . $row['id'] . '&ref=t"><img src="../illustrator/images/icons/edit.png" title="ویرایش مرکز ' . $row['name'] . '" /></a></td></tr>';
			$counter++;
		}

		$htmlOut .= '</tbody></table></fielset>';
	} else
		$htmlOut .= NO_CENTER_EXISTS;

	echo $htmlOut;
}

//for hygiene unit management
if (isset($_POST['centerIdForHygieneUnitList'])) {
	$htmlOut = '';
	$res = Database::execute_query("SELECT `name` FROM `centers` WHERE `id` = '" . $_POST['centerIdForHygieneUnitList'] . "';");
	$row = Database::get_assoc_array($res);
	$centerName = $row['name'];
	$res = Database::execute_query("SELECT `id`, `name` FROM  `hygiene-units` WHERE `center-id` = '" . $_POST['centerIdForHygieneUnitList'] . "' ORDER BY `name` ASC;");
	$counter = 1;
	if (Database::num_of_rows($res) > 0) {
		$htmlOut .= '<fieldset><legend>فهرست خانه های بهداشت مرکز "' . $centerName . '"</legend>';
		$htmlOut .= '<br /><table align="right" class="contentTable" border="0" width="400" cellpadding="0" cellspacing="1">';
		$htmlOut .= '<thead><tr><th width="40">ردیف</th><th>نام</th><th width="80">تعداد پرسنل</th><th width="50">حذف</th><th width="50">ویرایش</th></thead><tbody>';
		while ($row = Database::get_assoc_array($res)) {
			$resTmp = Database::execute_query("SELECT `id` FROM `hygiene-unit-personnels` WHERE `hygiene-unit-id` = '" . $row['id'] . "';");
			$num = intval(Database::num_of_rows($resTmp));
			$htmlOut .= '<tr><td>' . $counter . '</td><td>' . $row['name'] . '</td>';
			$htmlOut .= '<td>' . $num . '</td>';
			$htmlOut .= '<td><a href="?cmd=removeHygieneUnit&hygieneUnitId=' . $row['id'] . '"><img src="../illustrator/images/icons/remove.png" title="حذف خانه بهداشت ' . $row['name'] . '" /></a></td>';
			$htmlOut .= '<td><a href="?cmd=editHygieneUnit&hygieneUnitId=' . $row['id'] . '&ref=t"><img src="../illustrator/images/icons/edit.png" title="ویرایش خانه بهداشت ' . $row['name'] . '" /></a></td></tr>';
			$counter++;
		}

		$htmlOut .= '</tbody></table></fielset>';
	} else
		$htmlOut .= NO_HYGIENE_UNIT_EXISTS;

	echo $htmlOut;
}

//for unit management
if (isset($_POST['centerId'])) {
	$htmlOut = '';
	$res = Database::execute_query("SELECT `name` FROM `centers` WHERE `id` = '" . Database::filter_str($_POST['centerId']) . "';");
	$row = Database::get_assoc_array($res);
	$centerName = $row['name'];
	$res = Database::execute_query("SELECT `id`, `name` FROM  `units` WHERE `center-id` = '" . Database::filter_str($_POST['centerId']) . "' ORDER BY `name` ASC;");
	$counter = 1;
	if (Database::num_of_rows($res) > 0) {
		$htmlOut .= '<br /><fieldset><legend>واحدهای مرکز "' . $centerName . '"</legend>';
		$htmlOut .= '<br /><table align="right" class="contentTable" border="0" cellpadding="0" cellspacing="1">';
		$htmlOut .= '<thead><tr><th width="40">ردیف</th><th>نام</th><th width="80">تعداد پرسنل</th><th width="50">حذف</th><th width="50">ویرایش</th></thead><tbody>';
		while ($row = Database::get_assoc_array($res)) {
			$resTmp = Database::execute_query("SELECT `id` FROM `unit-personnels` WHERE `unit-id` = '" . Database::filter_str($row['id']) . "';");
			$num = intval(Database::num_of_rows($resTmp));
			$htmlOut .= '<tr><td>' . $counter . '</td><td>' . $row['name'] . '</td>';
			$htmlOut .= '<td>' . $num . '</td>';
			$htmlOut .= '<td><a href="?cmd=removeUnit&unitId=' . $row['id'] . '"><img src="../illustrator/images/icons/remove.png" title="حذف واحد ' . $row['name'] . '" /></a></td>';
			$htmlOut .= '<td><a href="?cmd=editUnit&unitId=' . $row['id'] . '&ref=t"><img src="../illustrator/images/icons/edit.png" title="ویرایش واحد ' . $row['name'] . '" /></a></td></tr>';
			$counter++;
		}

		$htmlOut .= '</tbody></table></fielset>';
	} else
		$htmlOut .= NO_UNIT_EXISTS;

	echo $htmlOut;
}

//for center's drugs management
if (isset($_POST['centerIdForDrugs'])) {
	$htmlOut = '';
	$res = Database::execute_query("SELECT `name` FROM `centers` WHERE `id` = '" . Database::filter_str($_POST['centerIdForDrugs']) . "';");
	$row = Database::get_assoc_array($res);
	$centerName = $row['name'];
	$res = Database::execute_query("SELECT * FROM `v_centers_drugs_full` WHERE `deleted` = '0' AND `is_drug` = 'yes' AND `center-id` = '" . $_POST['centerIdForDrugs'] . "' ORDER BY `name` ASC;");
	$counter = 1;
	if (Database::num_of_rows($res) > 0) {
		$htmlOut .= '<br /><fieldset><legend>فهرست داروهای مرکز "' . $centerName . '"</legend>';
		$htmlOut .= '<br /><table align="right" class="contentTable" border="0" cellpadding="0" cellspacing="1">';
		$htmlOut .= '<thead><th width="40">ردیف</th><th>نام</th><th width="40">تعداد</th><th>قیمت واحد(ريال)</th><th>قیمت کل(ريال)</th><th width="50">حذف</th></thead><tbody>';
		while ($row = Database::get_assoc_array($res)) {
			$htmlOut .= '<tr><td>' . $counter . '</td><td>' . $row['name'] . '</a></td>';
			$htmlOut .= '<td>' . $row['num-of-drugs'] . '</td>';
			$htmlOut .= '<td>' . $row['cost'] . '</td>';
			$htmlOut .= '<td>' . $row['num-of-drugs'] * $row['cost'] . '</td>';
			$htmlOut .= '<td><a href="?cmd=removeCenterDrug&drugId=' . $row['center-drug-id'] . '"><img src="../illustrator/images/icons/remove.png" title="حذف ' . $row['name'] . '" /></a></td></tr>';
			$counter++;
		}

		$htmlOut .= '</tbody></table></fielset><br />';
	} else
		$htmlOut .= '<p class="warning">هیچ دارویی برای مرکز "' . $centerName . '" ثبت نشده است.</p>';

	echo $htmlOut;
}

//for center's consumings management
if (isset($_POST['centerIdForConsumings'])) {
	$htmlOut = '';
	$res = Database::execute_query("SELECT `name` FROM `centers` WHERE `id` = '" . Database::filter_str($_POST['centerIdForConsumings']) . "';");
	$row = Database::get_assoc_array($res);
	$centerName = $row['name'];
	$res = Database::execute_query("SELECT * FROM `v_centers_drugs_full` WHERE `deleted` = '0' AND `is_drug` = 'no' AND `center-id` = '" . $_POST['centerIdForConsumings'] . "' ORDER BY `name` ASC;");
	$counter = 1;
	if (Database::num_of_rows($res) > 0) {
		$htmlOut .= '<br /><fieldset><legend>فهرست تجهیزات مصرفی مرکز "' . $centerName . '"</legend>';
		$htmlOut .= '<br /><table align="right" class="contentTable" border="0" cellpadding="0" cellspacing="1">';
		$htmlOut .= '<thead><th width="40">ردیف</th><th>نام</th><th width="40">تعداد</th><th>قیمت واحد(ريال)</th><th>قیمت کل(ريال)</th><th width="50">حذف</th></thead><tbody>';
		while ($row = Database::get_assoc_array($res)) {
			$htmlOut .= '<tr><td>' . $counter . '</td><td>' . $row['name'] . '</a></td>';
			$htmlOut .= '<td>' . $row['num-of-drugs'] . '</td>';
			$htmlOut .= '<td>' . $row['cost'] . '</td>';
			$htmlOut .= '<td>' . $row['num-of-drugs'] * $row['cost'] . '</td>';
			$htmlOut .= '<td><a href="?cmd=removeCenterDrug&drugId=' . $row['center-drug-id'] . '"><img src="../illustrator/images/icons/remove.png" title="حذف ' . $row['name'] . '" /></a></td></tr>';
			$counter++;
		}

		$htmlOut .= '</tbody></table></fielset><br />';
	} else
		$htmlOut .= '<p class="warning">هیچ تجهیزات مصرفی برای مرکز "' . $centerName . '" ثبت نشده است.</p>';

	echo $htmlOut;
}

//for hygiene unit's drugs management
if (isset($_POST['hygieneUnitIdForDrugs'])) {
	$htmlOut = '';
	$res = Database::execute_query("SELECT `name` FROM `hygiene-units` WHERE `id` = '" . Database::filter_str($_POST['hygieneUnitIdForDrugs']) . "';");
	$row = Database::get_assoc_array($res);
	$hygieneUnitName = $row['name'];
	$res = Database::execute_query("SELECT * FROM `v_hygiene-units_drugs_full` WHERE `deleted` = '0' AND `is_drug` = 'yes' AND `hygiene-unit-id` = '" . $_POST['hygieneUnitIdForDrugs'] . "' ORDER BY `name` ASC;");
	$counter = 1;
	if (Database::num_of_rows($res) > 0) {
		$htmlOut .= '<br /><fieldset><legend>فهرست داروهای خانه‌بهداشت "' . $hygieneUnitName . '"</legend>';
		$htmlOut .= '<br /><table align="right" class="contentTable" border="0" cellpadding="0" cellspacing="1">';
		$htmlOut .= '<thead><th width="40">ردیف</th><th>نام</th><th width="40">تعداد</th><th>قیمت واحد(ريال)</th><th>قیمت کل(ريال)</th><th width="50">حذف</th></thead><tbody>';
		while ($row = Database::get_assoc_array($res)) {
			$htmlOut .= '<tr><td>' . $counter . '</td><td>' . $row['name'] . '</a></td>';
			$htmlOut .= '<td>' . $row['num-of-drugs'] . '</td>';
			$htmlOut .= '<td>' . $row['cost'] . '</td>';
			$htmlOut .= '<td>' . $row['num-of-drugs'] * $row['cost'] . '</td>';
			$htmlOut .= '<td><a href="?cmd=removeHygieneUnitDrug&drugId=' . $row['hg-drug-id'] . '"><img src="../illustrator/images/icons/remove.png" title="حذف ' . $row['name'] . '" /></a></td></tr>';
			$counter++;
		}

		$htmlOut .= '</tbody></table></fielset><br />';
	} else
		$htmlOut .= '<br /><p class="warning">هیچ دارویی برای خانه‌بهداشت "' . $hygieneUnitName . '" ثبت نشده است.</p>';

	echo $htmlOut;
}

//for hygiene unit's consumings management
if (isset($_POST['hygieneUnitIdForConsumings'])) {
	$htmlOut = '';
	$res = Database::execute_query("SELECT `name` FROM `hygiene-units` WHERE `id` = '" . Database::filter_str($_POST['hygieneUnitIdForConsumings']) . "';");
	$row = Database::get_assoc_array($res);
	$hygieneUnitName = $row['name'];
	$res = Database::execute_query("SELECT * FROM `v_hygiene-units_drugs_full` WHERE `deleted` = '0' AND `is_drug` = 'no' AND `hygiene-unit-id` = '" . $_POST['hygieneUnitIdForConsumings'] . "' ORDER BY `name` ASC;");
	$counter = 1;
	if (Database::num_of_rows($res) > 0) {
		$htmlOut .= '<br /><fieldset><legend>فهرست تجهیزات مصرفی خانه‌بهداشت "' . $hygieneUnitName . '"</legend>';
		$htmlOut .= '<br /><table align="right" class="contentTable" border="0" cellpadding="0" cellspacing="1">';
		$htmlOut .= '<thead><th width="40">ردیف</th><th>نام</th><th width="40">تعداد</th><th>قیمت واحد(ريال)</th><th>قیمت کل(ريال)</th><th width="50">حذف</th></thead><tbody>';
		while ($row = Database::get_assoc_array($res)) {
			$htmlOut .= '<tr><td>' . $counter . '</td><td>' . $row['name'] . '</a></td>';
			$htmlOut .= '<td>' . $row['num-of-drugs'] . '</td>';
			$htmlOut .= '<td>' . $row['cost'] . '</td>';
			$htmlOut .= '<td>' . $row['num-of-drugs'] * $row['cost'] . '</td>';
			$htmlOut .= '<td><a href="?cmd=removeHygieneUnitDrug&drugId=' . $row['hg-drug-id'] . '"><img src="../illustrator/images/icons/remove.png" title="حذف ' . $row['name'] . '" /></a></td></tr>';
			$counter++;
		}

		$htmlOut .= '</tbody></table></fielset><br />';
	} else
		$htmlOut .= '<br /><p class="warning">هیچ تجهیزات مصرفی برای خانه‌بهداشت "' . $hygieneUnitName . '" ثبت نشده است.</p>';

	echo $htmlOut;
}

//for center's non consumings management
if (isset($_POST['centerIdForNonConsumings'])) {
	$htmlOut = '';
	$res = Database::execute_query("SELECT `name` FROM `centers` WHERE `id` = '" . Database::filter_str($_POST['centerIdForNonConsumings']) . "';");
	$row = Database::get_assoc_array($res);
	$centerName = $row['name'];
	$res = Database::execute_query("SELECT * FROM `v_centers_nonconsumings_full` WHERE `deleted` = '0' AND `center-id` = '" . $_POST['centerIdForNonConsumings'] . "' ORDER BY `name` ASC;");
	$counter = 1;
	if (Database::num_of_rows($res) > 0) {
		$htmlOut .= '<br /><fieldset><legend>فهرست تجهیزات غیرمصرفی مرکز "' . $centerName . '"</legend>';
		$htmlOut .= '<br /><table align="right" class="contentTable" border="0" cellpadding="0" cellspacing="1">';
		$htmlOut .= '<thead><th width="40">ردیف</th><th>نام</th><th width="40">تعداد</th><th>قیمت واحد(ريال)</th><th>قیمت کل(ريال)</th><th width="50">حذف</th></thead><tbody>';
		while ($row = Database::get_assoc_array($res)) {
			$htmlOut .= '<tr><td>' . $counter . '</td><td>' . $row['name'] . ' - (' . $row['mark'] . ')</td>';
			$htmlOut .= '<td>' . $row['num-of-equips'] . '</td>';
			$htmlOut .= '<td>' . $row['cost'] . '</td>';
			$htmlOut .= '<td>' . $row['num-of-equips'] * $row['cost'] . '</td>';
			$htmlOut .= '<td><a href="?cmd=removeCenterNonConsumingEquip&id=' . $row['center-equip-id'] . '"><img src="../illustrator/images/icons/remove.png" title="حذف ' . $row['name'] . '" /></a></td></tr>';
			$counter++;
		}

		$htmlOut .= '</tbody></table></fielset><br />';
	} else
		$htmlOut .= '<p class="warning">هیچ تجهیزات غیرمصرفی برای مرکز "' . $centerName . '" ثبت نشده است.</p>';

	echo $htmlOut;
}

//for hygiene unit's non consumings management
if (isset($_POST['hygieneUnitIdForNonConsumings'])) {
	$htmlOut = '';
	$res = Database::execute_query("SELECT `name` FROM `hygiene-units` WHERE `id` = '" . Database::filter_str($_POST['hygieneUnitIdForNonConsumings']) . "';");
	$row = Database::get_assoc_array($res);
	$hygieneUnitName = $row['name'];
	$res = Database::execute_query("SELECT * FROM `v_hygiene-units_nonconsumings_full` WHERE `deleted` = '0' AND `hygiene-unit-id` = '" . $_POST['hygieneUnitIdForNonConsumings'] . "' ORDER BY `name` ASC;");
	$counter = 1;
	if (Database::num_of_rows($res) > 0) {
		$htmlOut .= '<br /><fieldset><legend>فهرست تجهیزات غیرمصرفی خانه بهداشت "' . $hygieneUnitName . '"</legend>';
		$htmlOut .= '<br /><table align="right" class="contentTable" border="0" cellpadding="0" cellspacing="1">';
		$htmlOut .= '<thead><th width="40">ردیف</th><th>نام</th><th width="40">تعداد</th><th>قیمت واحد(ريال)</th><th>قیمت کل(ريال)</th><th width="50">حذف</th></thead><tbody>';
		while ($row = Database::get_assoc_array($res)) {
			$htmlOut .= '<tr><td>' . $counter . '</td><td>' . $row['name'] . ' - (' . $row['mark'] . ')</td>';
			$htmlOut .= '<td>' . $row['num-of-equips'] . '</td>';
			$htmlOut .= '<td>' . $row['cost'] . '</td>';
			$htmlOut .= '<td>' . $row['num-of-equips'] * $row['cost'] . '</td>';
			$htmlOut .= '<td><a href="?cmd=removeHygieneUnitNonConsumingEquip&id=' . $row['hg-equip-id'] . '"><img src="../illustrator/images/icons/remove.png" title="حذف ' . $row['name'] . '" /></a></td></tr>';
			$counter++;
		}

		$htmlOut .= '</tbody></table></fielset><br />';
	} else
		$htmlOut .= '<p class="warning">هیچ تجهیزات غیرمصرفی برای خانه بهداشت "' . $hygieneUnitName . '" ثبت نشده است.</p>';

	echo $htmlOut;
}

//for center's buildings
if (isset($_POST['centerIdForBuildings'])) {
	$htmlOut = '';
	$res = Database::execute_query("SELECT `name` FROM `centers` WHERE `id` = '" . Database::filter_str($_POST['centerIdForBuildings']) . "';");
	$row = Database::get_assoc_array($res);
	$centerName = $row['name'];
	$res = Database::execute_query("SELECT `id`, `title` FROM  `center-buildings` WHERE `center-id` = '" . Database::filter_str($_POST['centerIdForBuildings']) . "' ORDER BY `title` ASC;");
	$counter = 1;
	if (Database::num_of_rows($res) > 0) {
		$htmlOut .= '<br /><fieldset><legend>فهرست ساختمان های مرکز "' . $centerName . '"</legend>';
		$htmlOut .= '<br /><table align="right" class="contentTable" border="0" cellpadding="0" cellspacing="1">';
		$htmlOut .= '<thead><tr><th width="40">ردیف</th><th>نام</th><th width="50">حذف</th><th width="50">ویرایش</th></thead><tbody>';
		while ($row = Database::get_assoc_array($res)) {
			$htmlOut .= '<tr><td>' . $counter . '</td><td>' . $row['title'] . '</td>';
			$htmlOut .= '<td><a href="?cmd=removeCenterBuilding&buildingId=' . $row['id'] . '"><img src="../illustrator/images/icons/remove.png" title="حذف ' . $row['title'] . '" /></a></td>';
			$htmlOut .= '<td><a href="?cmd=editCenterBuilding&buildingId=' . $row['id'] . '&ref"><img src="../illustrator/images/icons/edit.png" title="ویرایش ' . $row['title'] . '" /></a></td></tr>';
			$counter++;
		}

		$htmlOut .= '</tbody></table></fielset>';
	} else
		$htmlOut .= NO_CENTER_BUILDINGS;

	echo $htmlOut;
}

//for center's building's List
if (isset($_POST['centerIdForBuildingsList'])) {
	$htmlOut = '';
	$res = Database::execute_query("SELECT `a`.`id`, `a`.`title` FROM `center-buildings` AS `a` INNER JOIN `centers` AS `b` ON `a`.`center-id` = `b`.`id` WHERE `a`.`center-id` = '" . $_POST['centerIdForBuildingsList'] . "' ORDER BY `title` ASC;");
	if (Database::num_of_rows($res) > 0) {
		$htmlOut .= '<option>انتخاب کنید...</option>';
		while ($row = Database::get_assoc_array($res)) {
			$htmlOut .= '<option value="' . $row['id'] . '">' . $row['title'] . '</option>';
		}
	} else
		$htmlOut .= '<option>هیچ ساختمانی وجود ندارد!</option>';

	echo $htmlOut;
}

//for center's building's charge edit
if (isset($_POST['buildingIdForBuildingCharge'])) {
	$htmlOut = '';
	$res = Database::execute_query("SELECT `title` FROM `center-buildings` WHERE `id` = '" . Database::filter_str($_POST['buildingIdForBuildingCharge']) . "';");
	$row = Database::get_assoc_array($res);
	$buildingName = $row['title'];
	$res = Database::execute_query("SELECT `a`.`title`, `b`.`id`, `b`.`charge-name`, `b`.`charge-amount` FROM `center-buildings` AS `a` INNER JOIN `center-building-maintain-charges` AS `b` ON `a`.`id` = `b`.`building-id` WHERE `a`.`id` = '" . $_POST['buildingIdForBuildingCharge'] . "' ORDER BY `date` ASC;");
	$counter = 1;
	if (Database::num_of_rows($res) > 0) {
		$htmlOut .= '<br /><fieldset><legend>فهرست هزینه های ساختمان ' . $buildingName . '</legend>';
		$htmlOut .= '<br /><table align="right" class="contentTable" border="0" cellpadding="0" cellspacing="1">';
		$htmlOut .= '<thead><tr><th width="40">ردیف</th><th>نام هزینه</th><th>مبلغ هزینه</th><th width="50">حذف</th><th width="50">ویرایش</th></thead><tbody>';
		while ($row = Database::get_assoc_array($res)) {
			$htmlOut .= '<tr><td>' . $counter . '</td><td>' . $row['charge-name'] . '</td><td>' . $row['charge-amount'] . '</td>';
			$htmlOut .= '<td><a href="?cmd=removeCenterBuildingCharge&chargeId=' . $row['id'] . '"><img src="../illustrator/images/icons/remove.png" title="حذف ' . $row['charge-name'] . '" /></a></td>';
			$htmlOut .= '<td><a href="?cmd=editCenterBuildingCharge&chargeId=' . $row['id'] . '"><img src="../illustrator/images/icons/edit.png" title="ویرایش ' . $row['charge-name'] . '" /></a></td></tr>';
			$counter++;
		}

		$htmlOut .= '</tbody></table></fielset>';
	} else
		$htmlOut .= '<p class="warning">هیچ هزینه ای ثبت نشده است.</p>';

	echo $htmlOut;
}

//for hygiene unit's buildings
if (isset($_POST['hygieneUnitIdForBuildings'])) {
	$htmlOut = '';
	$res = Database::execute_query("SELECT `name` FROM `hygiene-units` WHERE `id` = '" . Database::filter_str($_POST['hygieneUnitIdForBuildings']) . "';");
	$row = Database::get_assoc_array($res);
	$hygieneUnitName = $row['name'];
	$res = Database::execute_query("SELECT `id`, `title` FROM  `hygiene-unit-buildings` WHERE `hygiene-unit-id` = '" . Database::filter_str($_POST['hygieneUnitIdForBuildings']) . "' ORDER BY `title` ASC;");
	$counter = 1;
	if (Database::num_of_rows($res) > 0) {
		$htmlOut .= '<br /><fieldset><legend>فهرست ساختمان های خانه بهداشت "' . $hygieneUnitName . '"</legend>';
		$htmlOut .= '<br /><table align="right" class="contentTable" border="0" cellpadding="0" cellspacing="1">';
		$htmlOut .= '<thead><tr><th width="40">ردیف</th><th>نام</th><th width="50">حذف</th><th width="50">ویرایش</th></thead><tbody>';
		while ($row = Database::get_assoc_array($res)) {
			$htmlOut .= '<tr><td>' . $counter . '</td><td>' . $row['title'] . '</td>';
			$htmlOut .= '<td><a href="?cmd=removeHygieneUnitBuilding&buildingId=' . $row['id'] . '"><img src="../illustrator/images/icons/remove.png" title="حذف ' . $row['title'] . '" /></a></td>';
			$htmlOut .= '<td><a href="?cmd=editHygieneUnitBuilding&buildingId=' . $row['id'] . '"><img src="../illustrator/images/icons/edit.png" title="ویرایش ' . $row['title'] . '" /></a></td></tr>';
			$counter++;
		}

		$htmlOut .= '</tbody></table></fielset>';
	} else
		$htmlOut .= NO_CENTER_BUILDINGS;

	echo $htmlOut;
}

//for hygiene unit's building's charge
if (isset($_POST['hygieneUnitIdForBuildingsList'])) {
	$htmlOut = '';
	$res = Database::execute_query("SELECT `a`.`id`, `a`.`title` FROM `hygiene-unit-buildings` AS `a` INNER JOIN `hygiene-units` AS `b` ON `a`.`hygiene-unit-id` = `b`.`id` WHERE `a`.`hygiene-unit-id` = '" . $_POST['hygieneUnitIdForBuildingsList'] . "' ORDER BY `title` ASC;");
	if (Database::num_of_rows($res) > 0) {
		$htmlOut .= '<option>انتخاب کنید...</option>';
		while ($row = Database::get_assoc_array($res)) {
			$htmlOut .= '<option value="' . $row['id'] . '">' . $row['title'] . '</option>';
		}
	} else
		$htmlOut .= '<option>هیچ ساختمانی وجود ندارد!</option>';

	echo $htmlOut;
}

//for hygiene unit's building's charge edit
if (isset($_POST['buildingIdForHGBuildingCharge'])) {
	$htmlOut = '';
	$res = Database::execute_query("SELECT `title` FROM `hygiene-unit-buildings` WHERE `id` = '" . Database::filter_str($_POST['buildingIdForHGBuildingCharge']) . "';");
	$row = Database::get_assoc_array($res);
	$buildingName = $row['title'];
	$res = Database::execute_query("SELECT `a`.`title`, `b`.`id`, `b`.`charge-name`, `b`.`charge-amount` FROM `hygiene-unit-buildings` AS `a` INNER JOIN `hygiene-unit-building-maintain-charges` AS `b` ON `a`.`id` = `b`.`building-id` WHERE `a`.`id` = '" . $_POST['buildingIdForHGBuildingCharge'] . "' ORDER BY `date` ASC;");
	$counter = 1;
	if (Database::num_of_rows($res) > 0) {
		$htmlOut .= '<br /><fieldset><legend>فهرست هزینه های ساختمان "' . $buildingName . '"</legend>';
		$htmlOut .= '<br /><table align="right" class="contentTable" border="0" cellpadding="0" cellspacing="1">';
		$htmlOut .= '<thead><tr><th width="40">ردیف</th><th>نام هزینه</th><th>مبلغ هزینه</th><th width="50">حذف</th><th width="50">ویرایش</th></thead><tbody>';
		while ($row = Database::get_assoc_array($res)) {
			$htmlOut .= '<tr><td>' . $counter . '</td><td>' . $row['charge-name'] . '</td><td>' . $row['charge-amount'] . '</td>';
			$htmlOut .= '<td><a href="?cmd=removeHGBuildingCharge&chargeId=' . $row['id'] . '"><img src="../illustrator/images/icons/remove.png" title="حذف ' . $row['charge-name'] . '" /></a></td>';
			$htmlOut .= '<td><a href="?cmd=editHGBuildingCharge&chargeId=' . $row['id'] . '"><img src="../illustrator/images/icons/edit.png" title="ویرایش ' . $row['charge-name'] . '" /></a></td></tr>';
			$counter++;
		}

		$htmlOut .= '</tbody></table></fielset>';
	} else
		$htmlOut .= '<p class="warning">هیچ هزینه ای ثبت نشده است.</p>';

	echo $htmlOut;
}

//for center's vehicles
if (isset($_POST['centerIdForVehicles'])) {
	$htmlOut = '';
	$res = Database::execute_query("SELECT `name` FROM `centers` WHERE `id` = '" . Database::filter_str($_POST['centerIdForVehicles']) . "';");
	$row = Database::get_assoc_array($res);
	$centerName = $row['name'];
	$res = Database::execute_query("SELECT `id`, `title` FROM `center-vehicles` WHERE `center-id` = '" . Database::filter_str($_POST['centerIdForVehicles']) . "' ORDER BY `title` ASC;");
	$counter = 1;
	if (Database::num_of_rows($res) > 0) {
		$htmlOut .= '<br /><fieldset><legend>فهرست وسایل نقلیه مرکز ' . $centerName . '</legend>';
		$htmlOut .= '<br /><table align="right" class="contentTable" border="0" cellpadding="0" cellspacing="1">';
		$htmlOut .= '<thead><tr><th width="40">ردیف</th><th>نام</th><th width="50">حذف</th><th width="50">ویرایش</th></thead><tbody>';
		while ($row = Database::get_assoc_array($res)) {
			$htmlOut .= '<tr><td>' . $counter . '</td><td>' . $row['title'] . '</td>';
			$htmlOut .= '<td><a href="?cmd=removeCenterVehicle&vehicleId=' . $row['id'] . '"><img src="../illustrator/images/icons/remove.png" title="حذف ' . $row['title'] . '" /></a></td>';
			$htmlOut .= '<td><a href="?cmd=editCenterVehicle&vehicleId=' . $row['id'] . '&ref"><img src="../illustrator/images/icons/edit.png" title="ویرایش ' . $row['title'] . '" /></a></td></tr>';
			$counter++;
		}

		$htmlOut .= '</tbody></table></fielset>';
	} else
		$htmlOut .= NO_CENTER_VEHICLE;

	echo $htmlOut;
}

//for center's vehicle's charge
if (isset($_POST['centerIdForVehiclesList'])) {
	$htmlOut = '';
	$res = Database::execute_query("SELECT `a`.`id`, `a`.`title` FROM `center-vehicles` AS `a` INNER JOIN `centers` AS `b` ON `a`.`center-id` = `b`.`id` WHERE `a`.`center-id` = '" . $_POST['centerIdForVehiclesList'] . "' ORDER BY `title` ASC;");
	if (Database::num_of_rows($res) > 0) {
		$htmlOut .= '<option>انتخاب کنید...</option>';
		while ($row = Database::get_assoc_array($res)) {
			$htmlOut .= '<option value="' . $row['id'] . '">' . $row['title'] . '</option>';
		}
	} else
		$htmlOut .= '<option>هیچ وسیله نقلیه ای وجود ندارد!</option>';

	echo $htmlOut;
}

//for center's vehicle's charge edit
if (isset($_POST['vehicleIdForVehicleCharge'])) {
	$htmlOut = '';
	$res = Database::execute_query("SELECT `title` FROM `center-vehicles` WHERE `id` = '" . Database::filter_str($_POST['vehicleIdForVehicleCharge']) . "';");
	$row = Database::get_assoc_array($res);
	$vehicleName = $row['title'];
	$res = Database::execute_query("SELECT `a`.`title`, `b`.`id`, `b`.`charge-name`, `b`.`charge-amount` FROM `center-vehicles` AS `a` INNER JOIN `center-vehicle-maintain-charges` AS `b` ON `a`.`id` = `b`.`vehicle-id` WHERE `a`.`id` = '" . $_POST['vehicleIdForVehicleCharge'] . "' ORDER BY `date` ASC;");
	$counter = 1;
	if (Database::num_of_rows($res) > 0) {
		$htmlOut .= '<br /><fieldset><legend>فهرست هزینه های وسیله نقلیه  ' . $vehicleName . '</legend>';
		$htmlOut .= '<br /><table align="right" class="contentTable" border="0" cellpadding="0" cellspacing="1">';
		$htmlOut .= '<thead><tr><th width="40">ردیف</th><th>نام هزینه</th><th>مبلغ هزینه</th><th width="50">حذف</th><th width="50">ویرایش</th></thead><tbody>';
		while ($row = Database::get_assoc_array($res)) {
			$htmlOut .= '<tr><td>' . $counter . '</td><td>' . $row['charge-name'] . '</td><td>' . $row['charge-amount'] . '</td>';
			$htmlOut .= '<td><a href="?cmd=removeCenterVehicleCharge&chargeId=' . $row['id'] . '"><img src="../illustrator/images/icons/remove.png" title="حذف ' . $row['title'] . '" /></a></td>';
			$htmlOut .= '<td><a href="?cmd=editCenterVehicleCharge&chargeId=' . $row['id'] . '"><img src="../illustrator/images/icons/edit.png" title="ویرایش ' . $row['title'] . '" /></a></td></tr>';
			$counter++;
		}

		$htmlOut .= '</tbody></table></fielset>';
	} else
		$htmlOut .= '<p class="warning">هیچ هزینه ای ثبت نشده است.</p>';

	echo $htmlOut;
}

//for hygiene unit's vehicles
if (isset($_POST['hygieneUnitIdForVehicles'])) {
	$htmlOut = '';
	$res = Database::execute_query("SELECT `name` FROM `hygiene-units` WHERE `id` = '" . Database::filter_str($_POST['hygieneUnitIdForVehicles']) . "';");
	$row = Database::get_assoc_array($res);
	$hygieneUnitName = $row['name'];
	$res = Database::execute_query("SELECT `id`, `title` FROM `hygiene-unit-vehicles` WHERE `hygiene-unit-id` = '" . Database::filter_str($_POST['hygieneUnitIdForVehicles']) . "' ORDER BY `title` ASC;");
	$counter = 1;
	if (Database::num_of_rows($res) > 0) {
		$htmlOut .= '<br /><fieldset><legend>فهرست وسایل نقلیه خانه بهداشت "' . $hygieneUnitName . '"</legend>';
		$htmlOut .= '<br /><table align="right" class="contentTable" border="0" cellpadding="0" cellspacing="1">';
		$htmlOut .= '<thead><tr><th width="40">ردیف</th><th>نام</th><th width="50">حذف</th><th width="50">ویرایش</th></thead><tbody>';
		while ($row = Database::get_assoc_array($res)) {
			$htmlOut .= '<tr><td>' . $counter . '</td><td>' . $row['title'] . '</td>';
			$htmlOut .= '<td><a href="?cmd=removeHygieneUnitVehicle&vehicleId=' . $row['id'] . '"><img src="../illustrator/images/icons/remove.png" title="حذف ' . $row['title'] . '" /></a></td>';
			$htmlOut .= '<td><a href="?cmd=editHygieneUnitVehicle&vehicleId=' . $row['id'] . '"><img src="../illustrator/images/icons/edit.png" title="ویرایش ' . $row['title'] . '" /></a></td></tr>';
			$counter++;
		}

		$htmlOut .= '</tbody></table></fielset>';
	} else
		$htmlOut .= NO_CENTER_VEHICLE;

	echo $htmlOut;
}

//for hygiene unit's vehicle's charge
if (isset($_POST['hygieneUnitIdForVehiclesList'])) {
	$htmlOut = '';
	$res = Database::execute_query("SELECT `a`.`id`, `a`.`title` FROM `hygiene-unit-vehicles` AS `a` INNER JOIN `hygiene-units` AS `b` ON `a`.`hygiene-unit-id` = `b`.`id` WHERE `a`.`hygiene-unit-id` = '" . $_POST['hygieneUnitIdForVehiclesList'] . "' ORDER BY `title` ASC;");
	if (Database::num_of_rows($res) > 0) {
		$htmlOut .= '<option>انتخاب کنید...</option>';
		while ($row = Database::get_assoc_array($res)) {
			$htmlOut .= '<option value="' . $row['id'] . '">' . $row['title'] . '</option>';
		}
	} else
		$htmlOut .= '<option>هیچ وسیله نقلیه ای وجود ندارد!</option>';

	echo $htmlOut;
}

//for hygiene unit's vehicle's charge edit
if (isset($_POST['vehicleIdForHGVehicleCharge'])) {
	$htmlOut = '';
	$res = Database::execute_query("SELECT `title` FROM `hygiene-unit-vehicles` WHERE `id` = '" . Database::filter_str($_POST['vehicleIdForHGVehicleCharge']) . "';");
	$row = Database::get_assoc_array($res);
	$vehicleName = $row['title'];
	$res = Database::execute_query("SELECT `a`.`title`, `b`.`id`, `b`.`charge-name`, `b`.`charge-amount` FROM `hygiene-unit-vehicles` AS `a` INNER JOIN `hygiene-unit-vehicle-maintain-charges` AS `b` ON `a`.`id` = `b`.`vehicle-id` WHERE `a`.`id` = '" . $_POST['vehicleIdForHGVehicleCharge'] . "' ORDER BY `date` ASC;");
	$counter = 1;
	if (Database::num_of_rows($res) > 0) {
		$htmlOut .= '<br /><fieldset><legend>فهرست هزینه های وسیله نقلیه  ' . $vehicleName . '</legend>';
		$htmlOut .= '<br /><table align="right" class="contentTable" border="0" cellpadding="0" cellspacing="1">';
		$htmlOut .= '<thead><tr><th width="40">ردیف</th><th>نام هزینه</th><th>مبلغ هزینه</th><th width="50">حذف</th><th width="50">ویرایش</th></thead><tbody>';
		while ($row = Database::get_assoc_array($res)) {
			$htmlOut .= '<tr><td>' . $counter . '</td><td>' . $row['charge-name'] . '</td><td>' . $row['charge-amount'] . '</td>';
			$htmlOut .= '<td><a href="?cmd=removeHGVehicleCharge&chargeId=' . $row['id'] . '"><img src="../illustrator/images/icons/remove.png" title="حذف ' . $row['title'] . '" /></a></td>';
			$htmlOut .= '<td><a href="?cmd=editHGVehicleCharge&chargeId=' . $row['id'] . '"><img src="../illustrator/images/icons/edit.png" title="ویرایش ' . $row['title'] . '" /></a></td></tr>';
			$counter++;
		}

		$htmlOut .= '</tbody></table></fielset>';
	} else
		$htmlOut .= '<p class="warning">هیچ هزینه ای ثبت نشده است.</p>';

	echo $htmlOut;
}

//for center's general charges
if (isset($_POST['centerIdForGeneralCharges'])) {
	$htmlOut = '';
	$res = Database::execute_query("SELECT `name` FROM `centers` WHERE `id` = '" . Database::filter_str($_POST['centerIdForGeneralCharges']) . "';");
	$row = Database::get_assoc_array($res);
	$centerName = $row['name'];
	$res = Database::execute_query("SELECT `a`.`type`, `b`.`id`, `b`.`charge-amount`, `b`.`date` FROM `general-charge-types` AS `a` INNER JOIN `center-general-charges` AS `b` ON `a`.`id` = `b`.`charge-id` WHERE `center-id` = '" . $_POST['centerIdForGeneralCharges'] . "' ORDER BY `date` ASC;");
	$counter = 1;
	if (Database::num_of_rows($res) > 0) {
		$htmlOut .= '<br /><fieldset><legend>فهرست هزینه های عمومی ثبت شده مرکز "' . $centerName . '"</legend>';
		$htmlOut .= '<br /><table align="right" class="contentTable" border="0" cellpadding="0" cellspacing="1">';
		$htmlOut .= '<thead><th width="40">ردیف</th><th>نام هزینه</th><th>مبلغ</th><th>تاریخ</th><th width="50">حذف</th><th width="50">ویرایش</th></thead><tbody>';
		while ($row = Database::get_assoc_array($res)) {
			$htmlOut .= '<tr><td>' . $counter . '</td><td>' . $row['type'] . '</a></td>';
			$htmlOut .= '<td>' . $row['charge-amount'] . '</td>';
			$htmlOut .= '<td>' . $row['date'] . '</td>';
			$htmlOut .= '<td><a href="?cmd=removeCenterGeneralCharge&chargeId=' . $row['id'] . '"><img src="../illustrator/images/icons/remove.png" title="حذف ' . $row['type'] . '" /></a></td>';
			$htmlOut .= '<td><a href="?cmd=editCenterGeneralCharge&chargeId=' . $row['id'] . '"><img src="../illustrator/images/icons/edit.png" title="ویرایش ' . $row['type'] . '" /></a></td></tr>';
			$counter++;
		}

		$htmlOut .= '</tbody></table></fielset><br />';
	} else
		$htmlOut .= '<p class="warning">هیچ هزینه ای برای مرکز ثبت نشده است.</p>';

	echo $htmlOut;
}

//for hygiene unit's general charges
if (isset($_POST['hygieneUnitIdForGeneralCharges'])) {
	$htmlOut = '';
	$res = Database::execute_query("SELECT `name` FROM `hygiene-units` WHERE `id` = '" . Database::filter_str($_POST['hygieneUnitIdForGeneralCharges']) . "';");
	$row = Database::get_assoc_array($res);
	$centerName = $row['name'];
	$res = Database::execute_query("SELECT `a`.`type`, `b`.`id`, `b`.`charge-amount`, `b`.`date` FROM `general-charge-types` AS `a` INNER JOIN `hygiene-unit-general-charges` AS `b` ON `a`.`id` = `b`.`charge-id` WHERE `hygiene-unit-id` = '" . $_POST['hygieneUnitIdForGeneralCharges'] . "' ORDER BY `date` ASC;");
	$counter = 1;
	if (Database::num_of_rows($res) > 0) {
		$htmlOut .= '<br /><fieldset><legend>فهرست هزینه های عمومی ثبت شده خانه بهداشت "' . $centerName . '"</legend>';
		$htmlOut .= '<br /><table align="right" class="contentTable" border="0" cellpadding="0" cellspacing="1">';
		$htmlOut .= '<thead><th width="40">ردیف</th><th>نام هزینه</th><th>مبلغ</th><th>تاریخ</th><th width="50">حذف</th><th width="50">ویرایش</th></thead><tbody>';
		while ($row = Database::get_assoc_array($res)) {
			$htmlOut .= '<tr><td>' . $counter . '</td><td>' . $row['type'] . '</a></td>';
			$htmlOut .= '<td>' . $row['charge-amount'] . '</td>';
			$htmlOut .= '<td>' . $row['date'] . '</td>';
			$htmlOut .= '<td><a href="?cmd=removeHygieneUnitGeneralCharge&chargeId=' . $row['id'] . '"><img src="../illustrator/images/icons/remove.png" title="حذف ' . $row['type'] . '" /></a></td>';
			$htmlOut .= '<td><a href="?cmd=editHygieneUnitGeneralCharge&chargeId=' . $row['id'] . '"><img src="../illustrator/images/icons/edit.png" title="ویرایش ' . $row['type'] . '" /></a></td></tr>';
			$counter++;
		}

		$htmlOut .= '</tbody></table></fielset><br />';
	} else
		$htmlOut .= '<p class="warning">هیچ هزینه ای برای خانه بهداشت ثبت نشده است.</p>';

	echo $htmlOut;
}

//for unit's services
if (isset($_POST['unitIdForServices'])) {
	$htmlOut = '';
	$res = Database::execute_query("SELECT `name` FROM `units` WHERE `id` = '" . Database::filter_str($_POST['unitIdForServices']) . "';");
	$row = Database::get_assoc_array($res);
	$unitName = $row['name'];
	$res = Database::execute_query("SELECT `unit-services`.`id`, `available-services`.`name` FROM `available-services` INNER JOIN `unit-services` ON `available-services`.`id` = `unit-services`.`service-id` WHERE `unit-id` = '" . Database::filter_str($_POST['unitIdForServices']) . "' ORDER BY `name` ASC;");
	$counter = 1;
	if (Database::num_of_rows($res) > 0) {
		$htmlOut .= '<br /><fieldset><legend>فهرست خدمات واحد "' . $unitName . '"</legend>';
		$htmlOut .= '<br /><table align="right" class="contentTable" border="0" cellpadding="0" cellspacing="1">';
		$htmlOut .= '<thead><tr><th width="40">ردیف</th><th>نام</th><th width="50">حذف</th></thead><tbody>';
		while ($row = Database::get_assoc_array($res)) {
			$htmlOut .= '<tr><td>' . $counter . '</td><td>' . $row['name'] . '</td>';
			$htmlOut .= '<td><a href="?cmd=removeUnitService&unitServiceId=' . $row['id'] . '"><img src="../illustrator/images/icons/remove.png" title="حذف ' . $row['name'] . '" /></a></td>';
			$counter++;
		}

		$htmlOut .= '</tbody></table></fielset>';
	} else
		$htmlOut .= NO_UNIT_SERVICE;

	echo $htmlOut;
}

if (isset($_POST['unitIdForServiceSelection'])) {
	$htmlOut = '';
	$res = Database::execute_query("SELECT `available-services`.`id`, `available-services`.`name` FROM `available-services` INNER JOIN `unit-services` ON `available-services`.`id` = `unit-services`.`service-id` WHERE `unit-id` = '" . Database::filter_str($_POST['unitIdForServiceSelection']) . "' ORDER BY `name` ASC;");
	if (Database::num_of_rows($res) > 0) {
		$htmlOut .= '<option>انتخاب کنید...</option>';
		while ($row = Database::get_assoc_array($res)) {
			$htmlOut .= '<option value="' . $row['id'] . '">' . $row['name'] . '</option>';
		}
	} else
		$htmlOut .= '<option>هیچ خدمتی وجود ندارد!</option>';

	echo $htmlOut;
}

//for hygiene unit's services
if (isset($_POST['hygieneUnitIdForServices'])) {
	$htmlOut = '';
	$res = Database::execute_query("SELECT `name` FROM `hygiene-units` WHERE `id` = '" . Database::filter_str($_POST['hygieneUnitIdForServices']) . "';");
	$row = Database::get_assoc_array($res);
	$hygieneUnitName = $row['name'];
	$res = Database::execute_query("SELECT `hygiene-unit-services`.`id`, `available-services`.`name` FROM `available-services` INNER JOIN `hygiene-unit-services` ON `available-services`.`id` = `hygiene-unit-services`.`service-id` WHERE `hygiene-unit-id` = '" . Database::filter_str($_POST['hygieneUnitIdForServices']) . "' ORDER BY `name` ASC;");
	$counter = 1;
	if (Database::num_of_rows($res) > 0) {
		$htmlOut .= '<br /><fieldset><legend>فهرست خدمات خانه بهداشت "' . $hygieneUnitName . '"</legend>';
		$htmlOut .= '<br /><table align="right" class="contentTable" border="0" cellpadding="0" cellspacing="1">';
		$htmlOut .= '<thead><tr><th width="40">ردیف</th><th>نام</th><th width="50">حذف</th></thead><tbody>';
		while ($row = Database::get_assoc_array($res)) {
			$htmlOut .= '<tr><td>' . $counter . '</td><td>' . $row['name'] . '</td>';
			$htmlOut .= '<td><a href="?cmd=removeHygieneUnitService&hygieneUnitServiceId=' . $row['id'] . '"><img src="../illustrator/images/icons/remove.png" title="حذف ' . $row['name'] . '" /></a></td>';
			$counter++;
		}

		$htmlOut .= '</tbody></table></fielset>';
	} else
		$htmlOut .= NO_UNIT_SERVICE;

	echo $htmlOut;
}

if (isset($_POST['hygieneUnitIdForServiceSelection'])) {
	$htmlOut = '';
	$res = Database::execute_query("SELECT `available-services`.`id`, `available-services`.`name` FROM `available-services` INNER JOIN `hygiene-unit-services` ON `available-services`.`id` = `hygiene-unit-services`.`service-id` WHERE `hygiene-unit-id` = '" . Database::filter_str($_POST['hygieneUnitIdForServiceSelection']) . "' ORDER BY `name` ASC;");
	if (Database::num_of_rows($res) > 0) {
		$htmlOut .= '<option>انتخاب کنید...</option>';
		while ($row = Database::get_assoc_array($res)) {
			$htmlOut .= '<option value="' . $row['id'] . '">' . $row['name'] . '</option>';
		}
	} else
		$htmlOut .= '<option>هیچ خدمتی وجود ندارد!</option>';

	echo $htmlOut;
}

//for unit's personnels
if (isset($_POST['unitIdForPersonnel'])) {
	$htmlOut = '';
	$res = Database::execute_query("SELECT `name` FROM `units` WHERE `id` = '" . Database::filter_str($_POST['unitIdForPersonnel']) . "';");
	$row = Database::get_assoc_array($res);
	$unitName = $row['name'];
	$res = Database::execute_query("SELECT `id`, `name`, `lastname`, `job-title` FROM  `unit-personnels` WHERE `deleted` = '0' AND  `unit-id` = '" . Database::filter_str($_POST['unitIdForPersonnel']) . "'  ORDER BY `job-title` ASC;");
	$counter = 1;
	if (Database::num_of_rows($res) > 0) {
		$htmlOut .= '<br /><fieldset><legend>فهرست پرسنل واحد ' . $unitName . '</legend>';
		$htmlOut .= '<br /><table align="right" class="contentTable" border="0" cellpadding="0" cellspacing="1">';
		$htmlOut .= '<thead><tr><th width="40">ردیف</th><th>نام</th><th>نام خانوادگی</th><th>رده پرسنلی</th><th width="50">حذف</th><th width="50">ویرایش</th></thead><tbody>';
		while ($row = Database::get_assoc_array($res)) {
			$htmlOut .= '<tr><td>' . $counter . '</td><td>' . $row['name'] . '</td>';
			$htmlOut .= '<td>' . $row['lastname'] . '</td>';
			$htmlOut .= '<td>' . $row['job-title'] . '</td>';
			$htmlOut .= '<td><a href="?cmd=removeUnitPersonnel&unitPersonnelId=' . $row['id'] . '"><img src="../illustrator/images/icons/remove.png" title="حذف ' . $row['name'] . '" /></a></td>';
			$htmlOut .= '<td><a href="?cmd=editUnitPersonnel&unitPersonnelId=' . $row['id'] . '"><img src="../illustrator/images/icons/edit.png" title="ویرایش ' . $row['name'] . '" /></a></td></tr>';
			$counter++;
		}

		$htmlOut .= '</tbody></table></fielset>';
	} else
		$htmlOut .= NO_UNIT_PERSONNEL;

	echo $htmlOut;
}

//for unit's personnels financial info
if (isset($_POST['unitIdForPersonnelFinancialInfo'])) {
	$htmlOut = '';
	if (isset($_POST['searchValue']) && trim($_POST['searchValue']) !== '') {
		$res = Database::execute_query("SELECT `id`, `name`, `lastname`, `job-title` FROM  `unit-personnels` WHERE `unit-id` = '" . $_POST['unitIdForPersonnelFinancialInfo'] . "' AND `" . $_POST['searchBy'] . "` LIKE '%" . Database::filter_str($_POST['searchValue']) . "%' ORDER BY `lastname` ASC;");
		$counter = 1;
		if (Database::num_of_rows($res) > 0) {
			$htmlOut .= '<p><br /><span style="font-weight: bold; text-align: right;">گام دوم</span>: برای انتخاب، روی نام پرسنل موردنظر کلیک کنید.</p>';
			$htmlOut .= '<fieldset><legend>اسامی یافت شده</legend>';
			$htmlOut .= '<br /><table align="center" class="contentTable" border="0" cellpadding="0" cellspacing="1">';
			$htmlOut .= '<thead><tr><th>ردیف</th><th>نام</th><th>نام خانوادگی</th><th>رده ی پرسنلی</th></tr></thead><tbody>';
			while ($row = Database::get_assoc_array($res)) {
				$htmlOut .= '<tr><td>' . $counter . '</td><td><a href="?es=unitPersonnelFinancialInfo&id=' . $row['id'] . '">' . $row['name'] . '</a></td>';
				$htmlOut .= '<td>' . $row['lastname'] . '</td>';
				$htmlOut .= '<td>' . $row['job-title'] . '</td></tr>';
				$counter++;
			}

			$htmlOut .= '<tbody></table></fielset><br />';
		} else
			$htmlOut .= '<p class="warning">هیچ نتیجه ای برای "' . $_POST['searchValue'] . '" وجود ندارد. در درست و کامل وارد کردن مشخصه ی پرسنل دقت نمایید.</p>';
	} else
		$htmlOut = '<p class="warning">کلمه ای برای جستجو وارد نکرده اید، لطفا دوباره امتحان کنید.</p>';

	echo $htmlOut;
}

//for hygiene unit's personnels
if (isset($_POST['hygieneUnitIdForPersonnel'])) {
	$htmlOut = '';
	$res = Database::execute_query("SELECT `name` FROM `hygiene-units` WHERE `id` = '" . Database::filter_str($_POST['hygieneUnitIdForPersonnel']) . "';");
	$row = Database::get_assoc_array($res);
	$hygieneUnitName = $row['name'];
	$res = Database::execute_query("SELECT `id`, `name`, `lastname`, `job-title` FROM  `hygiene-unit-personnels` WHERE `deleted` = '0' AND `hygiene-unit-id` = '" . Database::filter_str($_POST['hygieneUnitIdForPersonnel']) . "'  ORDER BY `job-title` ASC;");
	$counter = 1;
	if (Database::num_of_rows($res) > 0) {
		$htmlOut .= '<br /><fieldset><legend>فهرست پرسنل خانه بهداشت ' . $hygieneUnitName . '</legend>';
		$htmlOut .= '<br /><table align="right" class="contentTable" border="0" cellpadding="0" cellspacing="1">';
		$htmlOut .= '<thead><tr><th width="40">ردیف</th><th>نام</th><th>نام خانوادگی</th><th>رده پرسنلی</th><th width="50">حذف</th><th width="50">ویرایش</th></thead><tbody>';
		while ($row = Database::get_assoc_array($res)) {
			$htmlOut .= '<tr><td>' . $counter . '</td><td>' . $row['name'] . '</td>';
			$htmlOut .= '<td>' . $row['lastname'] . '</td>';
			$htmlOut .= '<td>' . $row['job-title'] . '</td>';
			$htmlOut .= '<td><a href="?cmd=removeHygieneUnitPersonnel&hygieneUnitPersonnelId=' . $row['id'] . '"><img src="../illustrator/images/icons/remove.png" title="حذف ' . $row['name'] . '" /></a></td>';
			$htmlOut .= '<td><a href="?cmd=editHygieneUnitPersonnel&hygieneUnitPersonnelId=' . $row['id'] . '"><img src="../illustrator/images/icons/edit.png" title="ویرایش ' . $row['name'] . '" /></a></td></tr>';
			$counter++;
		}

		$htmlOut .= '</tbody></table></fielset>';
	} else
		$htmlOut .= NO_UNIT_PERSONNEL;

	echo $htmlOut;
}

//for unit's personnels financial info
if (isset($_POST['hygieneUnitIdForPersonnelFinancialInfo'])) {
	$htmlOut = '';
	if (isset($_POST['searchValue']) && trim($_POST['searchValue']) !== '') {
		$res = Database::execute_query("SELECT `id`, `name`, `lastname`, `job-title` FROM  `hygiene-unit-personnels` WHERE `hygiene-unit-id` = '" . $_POST['hygieneUnitIdForPersonnelFinancialInfo'] . "' AND `" . $_POST['searchBy'] . "` LIKE '%" . Database::filter_str($_POST['searchValue']) . "%' AND `job-title` = 'بهورز' ORDER BY `lastname` ASC;");
		$counter = 1;
		if (Database::num_of_rows($res) > 0) {
			$htmlOut .= '<p><br /><span style="font-weight: bold; text-align: right;">گام دوم</span>: برای انتخاب، روی نام پرسنل موردنظر کلیک کنید.</p>';
			$htmlOut .= '<fieldset><legend>اسامی یافت شده</legend>';
			$htmlOut .= '<br /><table align="center" class="contentTable" border="0" cellpadding="0" cellspacing="1">';
			$htmlOut .= '<thead><tr><th>ردیف</th><th>نام</th><th>نام خانوادگی</th><th>رده ی پرسنلی</th></tr></thead><tbody>';
			while ($row = Database::get_assoc_array($res)) {
				$htmlOut .= '<tr><td>' . $counter . '</td><td><a href="?es=hygieneUnitPersonnelFinancialInfo&id=' . $row['id'] . '">' . $row['name'] . '</a></td>';
				$htmlOut .= '<td>' . $row['lastname'] . '</td>';
				$htmlOut .= '<td>' . $row['job-title'] . '</td></tr>';
				$counter++;
			}

			$htmlOut .= '<tbody></table></fielset><br />';
		} else
			$htmlOut .= '<p class="warning">هیچ نتیجه ای برای "' . $_POST['searchValue'] . '" وجود ندارد. در درست و کامل وارد کردن مشخصه ی پرسنل دقت نمایید.</p>';
	} else
		$htmlOut = '<p class="warning">کلمه ای برای جستجو وارد نکرده اید، لطفا دوباره امتحان کنید.</p>';

	echo $htmlOut;
}

//for num of unit services
if (isset($_POST['data'])) {//0 => unit id, 1 => service id
	$htmlOut = '';
	$postedData = $_POST['data'];
	$res = Database::execute_query("SELECT `name` FROM `units` WHERE `id` = '" . Database::filter_str($postedData['0']) . "';");
	$row = Database::get_assoc_array($res);
	$unitName = $row['name'];
	$res = Database::execute_query("SELECT `name` FROM `available-services` WHERE `id` = '" . Database::filter_str($postedData['1']) . "';");
	$row = Database::get_assoc_array($res);
	$serviceName = $row['name'];
	$res = Database::execute_query("SELECT `b`.`id`, `b`.`service-frequency`, `b`.`period-start-date`, `b`.`period-finish-date` FROM `units` AS `a` INNER JOIN `unit-done-services` AS `b` ON `a`.`id` = `b`.`unit-id` WHERE `a`.`id` = '" . Database::filter_str($postedData['0']) . "' AND `b`.`service-id` = '" . Database::filter_str($postedData['1']) . "' ORDER BY `b`.`date` ASC;");
	$counter = 1;
	if (Database::num_of_rows($res) > 0) {
		$htmlOut .= '<br /><fieldset><legend>فهرست ثبت شده خدمت "' . $serviceName . '" در واحد "' . $unitName . '"</legend>';
		$htmlOut .= '<br /><table align="center" class="contentTable" border="0" cellpadding="0" cellspacing="1">';
		$htmlOut .= '<thead><tr><th width="40">ردیف</th><th>بار خدمت</th><th>از تاریخ</th><th>تا تاریخ</th><th width="50">حذف</th><th width="50">ویرایش</th></thead><tbody>';
		while ($row = Database::get_assoc_array($res)) {
			$htmlOut .= '<tr><td>' . $counter . '</td><td>' . $row['service-frequency'] . '</td>';
			$htmlOut .= '<td>' . $row['period-start-date'] . '</td>';
			$htmlOut .= '<td>' . $row['period-finish-date'] . '</td>';
			$htmlOut .= '<td><a href="?cmd=removeUnitServiceFreq&id=' . $row['id'] . '"><img src="../illustrator/images/icons/remove.png" /></a></td>';
			$htmlOut .= '<td><a href="?cmd=editUnitServiceFreq&id=' . $row['id'] . '"><img src="../illustrator/images/icons/edit.png" /></a></td></tr>';
			$counter++;
		}

		$htmlOut .= '</tbody></table></fielset>';
	} else
		$htmlOut .= '<p class="warning">هیچ بار خدمتی ثبت نشده است.</p>';

	echo $htmlOut;
}

//for num of hygiene unit services
if (isset($_POST['HGdata'])) {//0 => hygiene unit id, 1 => service id
	$htmlOut = '';
	$postedData = $_POST['HGdata'];
	$res = Database::execute_query("SELECT `name` FROM `hygiene-units` WHERE `id` = '" . Database::filter_str($postedData['0']) . "';");
	$row = Database::get_assoc_array($res);
	$hygieneUnitName = $row['name'];
	$res = Database::execute_query("SELECT `name` FROM `available-services` WHERE `id` = '" . Database::filter_str($postedData['1']) . "';");
	$row = Database::get_assoc_array($res);
	$serviceName = $row['name'];
	$res = Database::execute_query("SELECT `b`.`id`, `b`.`service-frequency`, `b`.`period-start-date`, `b`.`period-finish-date` FROM `hygiene-units` AS `a` INNER JOIN `hygiene-unit-done-services` AS `b` ON `a`.`id` = `b`.`hygiene-unit-id` WHERE `a`.`id` = '" . Database::filter_str($postedData['0']) . "' AND `b`.`service-id` = '" . Database::filter_str($postedData['1']) . "' ORDER BY `b`.`date` ASC;");
	$counter = 1;
	if (Database::num_of_rows($res) > 0) {
		$htmlOut .= '<br /><fieldset><legend>فهرست ثبت شده خدمت "' . $serviceName . '" در خانه بهداشت "' . $hygieneUnitName . '"</legend>';
		$htmlOut .= '<br /><table align="center" class="contentTable" border="0" cellpadding="0" cellspacing="1">';
		$htmlOut .= '<thead><tr><th width="40">ردیف</th><th>بار خدمت</th><th>از تاریخ</th><th>تا تاریخ</th><th width="50">حذف</th><th width="50">ویرایش</th></thead><tbody>';
		while ($row = Database::get_assoc_array($res)) {
			$htmlOut .= '<tr><td>' . $counter . '</td><td>' . $row['service-frequency'] . '</td>';
			$htmlOut .= '<td>' . $row['period-start-date'] . '</td>';
			$htmlOut .= '<td>' . $row['period-finish-date'] . '</td>';
			$htmlOut .= '<td><a href="?cmd=removeHGUnitServiceFreq&id=' . $row['id'] . '"><img src="../illustrator/images/icons/remove.png" /></a></td>';
			$htmlOut .= '<td><a href="?cmd=editHGUnitServiceFreq&id=' . $row['id'] . '"><img src="../illustrator/images/icons/edit.png" /></a></td></tr>';
			$counter++;
		}

		$htmlOut .= '</tbody></table></fielset>';
	} else
		$htmlOut .= '<p class="warning">هیچ بار خدمتی ثبت نشده است.</p>';

	echo $htmlOut;
}

//for total unit services in current year
if (isset($_POST['unitId4CurrentYearServices'])) {
	$htmlOut = '';
	$unitId = $_POST['unitId4CurrentYearServices'];
	$res = Database::execute_query("SELECT `name` FROM `units` WHERE `id` = '" . Database::filter_str($unitId) . "';");
	$row = Database::get_assoc_array($res);
	$unitName = $row['name'];
	$res = Database::execute_query("SELECT `b`.`id`, `b`.`service-frequency`, `b`.`period-start-date`, `b`.`period-finish-date`, `b`.`service-id` FROM `units` AS `a` INNER JOIN `unit-done-services` AS `b` ON `a`.`id` = `b`.`unit-id` WHERE `a`.`id` = '" . Database::filter_str($unitId) . "' ORDER BY `b`.`date` ASC;");
	$counter = 1;
	if (Database::num_of_rows($res) > 0) {
		$htmlOut .= '<br /><fieldset><legend>فهرست خدمات ثبت شده در واحد "' . $unitName . '" در سال جاری</legend>';
		$htmlOut .= '<br /><table align="center" class="contentTable" border="0" cellpadding="0" cellspacing="1">';
		$htmlOut .= '<thead><tr><th width="40">ردیف</th><th>نام خدمت</th><th>بار خدمت</th><th>از تاریخ</th><th>تا تاریخ</th><th width="50">حذف</th><th width="50">ویرایش</th></thead><tbody>';
		while ($row = Database::get_assoc_array($res)) {
			$serviceRes = Database::execute_query("SELECT `name` FROM `available-services` WHERE `id` = '" . $row['service-id'] . "';");
			$serviceRow = Database::get_assoc_array($serviceRes);
			$serviceName = $serviceRow['name'];
			$tmp = date_parse($row['period-start-date']);
			$startYear = $tmp['year'];
			$tmp = date_parse($row['period-finish-date']);
			$finishYear = $tmp['year'];
			$dateTimeObj = new DateTime();
			$dateTimeZone = new DateTimeZone('Asia/Tehran');
			$dateTimeObj -> setTimezone($dateTimeZone);
			$shamsiDate = gregorian_to_jalali($dateTimeObj -> format('Y'), $dateTimeObj -> format('m'), $dateTimeObj -> format('d'));
			//if ($shamsiDate['0'] == $startYear || $shamsiDate['0'] == $finishYear) {
				$htmlOut .= '<tr><td>' . $counter . '</td><td>' . $serviceName . '</td><td>' . $row['service-frequency'] . '</td>';
				$htmlOut .= '<td>' . $row['period-start-date'] . '</td>';
				$htmlOut .= '<td>' . $row['period-finish-date'] . '</td>';
				$htmlOut .= '<td><a href="?cmd=removeUnitServiceFreq&id=' . $row['id'] . '"><img src="../illustrator/images/icons/remove.png" /></a></td>';
				$htmlOut .= '<td><a href="?cmd=editUnitServiceFreq&id=' . $row['id'] . '"><img src="../illustrator/images/icons/edit.png" /></a></td></tr>';
				$counter++;
			//}
		}

		$htmlOut .= '</tbody></table></fielset>';
	} else
		$htmlOut .= '<p class="warning">هیچ بار خدمتی ثبت نشده است.</p>';

	echo $htmlOut;
}

if (isset($_POST['HGunitId4CurrentYearServices'])) {
	$htmlOut = '';
	$HGunitId = $_POST['HGunitId4CurrentYearServices'];
	$res = Database::execute_query("SELECT `name` FROM `hygiene-units` WHERE `id` = '" . Database::filter_str($HGunitId) . "';");
	$row = Database::get_assoc_array($res);
	$HGunitName = $row['name'];
	$res = Database::execute_query("SELECT `b`.`id`, `b`.`service-frequency`, `b`.`period-start-date`, `b`.`period-finish-date`, `b`.`service-id` FROM `hygiene-units` AS `a` INNER JOIN `hygiene-unit-done-services` AS `b` ON `a`.`id` = `b`.`hygiene-unit-id` WHERE `a`.`id` = '" . Database::filter_str($HGunitId) . "' ORDER BY `b`.`date` ASC;");
	$counter = 1;
	if (Database::num_of_rows($res) > 0) {
		$htmlOut .= '<br /><fieldset><legend>فهرست خدمات ثبت شده در خانه بهداشت "' . $HGunitName . '" در سال جاری</legend>';
		$htmlOut .= '<br /><table align="center" class="contentTable" border="0" cellpadding="0" cellspacing="1">';
		$htmlOut .= '<thead><tr><th width="40">ردیف</th><th>نام خدمت</th><th>بار خدمت</th><th>از تاریخ</th><th>تا تاریخ</th><th width="50">حذف</th><th width="50">ویرایش</th></thead><tbody>';
		while ($row = Database::get_assoc_array($res)) {
			$serviceRes = Database::execute_query("SELECT `name` FROM `available-services` WHERE `id` = '" . $row['service-id'] . "';");
			$serviceRow = Database::get_assoc_array($serviceRes);
			$serviceName = $serviceRow['name'];
			$tmp = date_parse($row['period-start-date']);
			$startYear = $tmp['year'];
			$tmp = date_parse($row['period-finish-date']);
			$finishYear = $tmp['year'];
			$dateTimeObj = new DateTime();
			$dateTimeZone = new DateTimeZone('Asia/Tehran');
			$dateTimeObj -> setTimezone($dateTimeZone);
			$shamsiDate = gregorian_to_jalali($dateTimeObj -> format('Y'), $dateTimeObj -> format('m'), $dateTimeObj -> format('d'));
			//if ($shamsiDate['0'] == $startYear || $shamsiDate['0'] == $finishYear) {
				$htmlOut .= '<tr><td>' . $counter . '</td><td>' . $serviceName . '</td><td>' . $row['service-frequency'] . '</td>';
				$htmlOut .= '<td>' . $row['period-start-date'] . '</td>';
				$htmlOut .= '<td>' . $row['period-finish-date'] . '</td>';
				$htmlOut .= '<td><a href="?cmd=removeHGUnitServiceFreq&id=' . $row['id'] . '"><img src="../illustrator/images/icons/remove.png" /></a></td>';
				$htmlOut .= '<td><a href="?cmd=editHGUnitServiceFreq&id=' . $row['id'] . '"><img src="../illustrator/images/icons/edit.png" /></a></td></tr>';
				$counter++;
			//}
		}

		$htmlOut .= '</tbody></table></fielset>';
	} else
		$htmlOut .= '<p class="warning">هیچ بار خدمتی ثبت نشده است.</p>';

	echo $htmlOut;
}

if(isset($_POST['unitTypeForAddingService'])) {
    $htmlOut = '';
    $serviceType = $_POST['unitTypeForAddingService'];
    $res = Database::execute_query("SELECT DISTINCT `name` FROM `units` WHERE `unit_type` = '" . Database::filter_str($serviceType) . "';");
    if(Database::num_of_rows($res) > 0) {
        while($row = Database::get_assoc_array($res)) {
            $htmlOut .= '<option value="' . $row['name'] . '">' . $row['name'] . '</option>';
        }
    } else {
        $htmlOut .= '<option>واحدی در این دسته موجود نیست</option>';
    }

    echo $htmlOut;
}


?>