<?php

/**
 *
 * town.php
 * town model class file
 *
 * @copyright	Copyright (C) 2012 Rivas Systems Inc. All rights reserved.
 * @versin	1.00 2011-02-05
 * @author	Behnam Salili
 *
 */

require_once dirname(dirname(__file__)) . '/database/database.php';

class Town {
	private static $id;

	public static function setId($id) {
		self::$id = $id;
	}

	public static function getName() {
		$res = Database::execute_query("SELECT `name` FROM `towns` WHERE `id` = " . self::$id . ";");
		$tmp = Database::get_assoc_array($res);
		return $tmp['name'];
	}

	/*public static function viewCenters() {
		$htmlOut = '<p style="font-weight: bold; text-align: center;">فهرست مراکز و خانه‌های بهداشت شهرستان ' . self::getName() . '</p>';
		$htmlOut .= '<div id="tabs"><ul><li><a href="#tabs-1">مراکز</a></li><li><a href="#tabs-2">خانه های بهداشت</a></li></ul>';
		$htmlOut .= '<div id="tabs-1"><p style="text-align: right; font-weight: bold; font-size: 15px;"><a href="#" style="color: green;" id="slideLink" ><img src="../illustrator/images/icons/plus.png" />&nbsp;افزودن مرکز</a></p>';
		$htmlOut .= '<form action="' . substr(strrchr($_SERVER['PHP_SELF'], '/'), 1) . '" method="post" id="slideForm" class="narin">';
		$htmlOut .= '<input type="hidden" name="narin:token" value="' . Narin::create_token() . '" />';
		$htmlOut .= '<input type="hidden" name="townId" value="' . self::$id . '" />';
		$htmlOut .= '<fieldset><legend>افزودن مرکز</legend>';
		$htmlOut .= '<label for="input_center_name" class="required">نام مرکز:</label>';
		$htmlOut .= '<input type="text" name="input_center_name" id="input_center_name" maxlength="256"';
		if (isset($_POST['input_center_name']) && $_POST['input_center_name'] !== '')
			$htmlOut .= ' value="' . htmlspecialchars($_POST['input_center_name']) . '"';
		$htmlOut .= 'class="validate required ';
		if (isset($r['invalid']['input_center_name']))
			$htmlOut .= ' invalid';
		$htmlOut .= '" />';
		$htmlOut .= '<span class="description"';
		if (!isset($r['invalid']['input_center_name']))
			$htmlOut .= ' style="display:none;" >فارسی بنویسید</span><br />';
		$htmlOut .= '<label for="input_center_phone" class="required">تلفن مرکز:</label>';
		$htmlOut .= '<input type="text" name="input_center_phone" id="input_center_phone" maxlength="14" dir="ltr"';
		if (isset($_POST['input_center_phone']) && $_POST['input_center_phone'] !== '')
			$htmlOut .= ' value="' . htmlspecialchars($_POST['input_center_phone']) . '"';
		$htmlOut .= ' class="validate required tel';
		if (isset($r['invalid']['input_center_phone']))
			$htmlOut[] = ' invalid';
		$htmlOut .= '" />';
		$htmlOut .= '<span class="description">مثلاً 0123‎-‎1234567</span><br />';
		$htmlOut .= '<label for="input_center_fax" class="required">شماره نمابر (فکس) مرکز:</label>';
		$htmlOut .= '<input type="text" name="input_center_fax" id="input_center_fax" maxlength="14" dir="ltr"';
		if (isset($_POST['input_center_fax']) && $_POST['input_center_fax'] !== '')
			$htmlOut .= ' value="' . htmlspecialchars($_POST['input_center_fax']) . '"';
		$htmlOut .= ' class="validate required tel';
		if (isset($r['invalid']['input_center_fax']))
			$htmlOut[] = ' invalid';
		$htmlOut .= '" />';
		$htmlOut .= '<span class="description">مثلاً 0123‎-‎1234567</span><br />';
		$htmlOut .= '<label for="input_center_address" class="labelbr required">آدرس مرکز:</label>';
		$htmlOut .= '<span class="description">فارسی بنویسید</span><br />';
		$htmlOut .= '<textarea name="input_center_address" id="input_center_address" rows="4" cols="48" class="validate required ';
		if (isset($r['invalid']['input_center_address']))
			$htmlOut .= ' invalid';
		$htmlOut .= '">';
		if (isset($_POST['input_center_address']))
			$htmlOut .= htmlspecialchars($_POST['input_center_address']);
		$htmlOut .= '</textarea><br />';
		$htmlOut .= '<input type="submit" value="ثبت مرکز" name="submitAddCenter" /></fieldset></form>';

		$res = Database::execute_query("SELECT `id`, `name` FROM `centers` WHERE `town-id` = '" . Database::filter_str(self::$id) . "' ORDER BY `name` ASC;");
		$counter = 1;
		if (Database::num_of_rows($res) > 0) {
			$htmlOut .= '<p style="margin-right: 20px;">برای ورود به هر مرکز، بر روی نام آن کلیک نمایید.</p>';
			$htmlOut .= '<fieldset><legend>مراکز شهرستان ' . self::getName() . '</legend>';
			$htmlOut .= '<br /><table align="right" class="contentTable" border="0" cellpadding="0" cellspacing="1">';
			$htmlOut .= '<thead><th width="40">ردیف</th><th>نام</th><th width="100">تعداد واحدها</th><th width="50">حذف</th><th width="50">ویرایش</th></thead><tbody>';
			while ($row = Database::get_assoc_array($res)) {
				$resTmp = Database::execute_query("SELECT * FROM `units` WHERE `center-id` = '" . $row['id'] . "';");
				$num = intval(Database::num_of_rows($resTmp));
				$htmlOut .= '<tr><td>' . $counter . '</td><td><a href="?cmd=manageUnits&centerId=' . $row['id'] . '">' . $row['name'] . '</a></td>';
				$htmlOut .= '<td>' . $num . '</td>';
				$htmlOut .= '<td><a href="?cmd=removeCenter&centerId=' . $row['id'] . '"><img src="../illustrator/images/icons/remove.png" title="حذف مرکز ' . $row['name'] . '" /></a></td>';
				$htmlOut .= '<td><a href="?cmd=editCenter&centerId=' . $row['id'] . '"><img src="../illustrator/images/icons/edit.png" title="ویریش مرکز ' . $row['name'] . '" /></a></td></tr>';
				$counter++;
			}

			$htmlOut .= '</tbody></table></fielset>';
		} else
			$htmlOut .= NO_CENTER_EXISTS;
		$htmlOut .= '</div>';

		/*****************************************************************************************************************************/
		/*$htmlOut .= '<div id="tabs-2">';
		$htmlOut .= '<p style="text-align: right; font-weight: bold; font-size: 15px;"><a href="#" id="slideLink1" style="color: green;" ><img src="../illustrator/images/icons/plus.png" />&nbsp;افزودن خانه بهداشت</a></p>';
		$htmlOut .= '<form action="' . substr(strrchr($_SERVER['PHP_SELF'], '/'), 1) . '" method="post" id="slideForm1" class="narin">';
		$htmlOut .= '<input type="hidden" name="narin:token" value="' . Narin::create_token() . '" />';
		$htmlOut .= '<input type="hidden" name="townId" value="' . self::$id . '" />';
		$htmlOut .= '<fieldset><legend>افزودن خانه بهداشت</legend><label for="input_hygiene_name" class="required">نام خانه بهداشت:</label>';
		$htmlOut .= '<input type="text" name="input_hygiene_name" id="input_hygiene_name" maxlength="255"';
		if (isset($_POST['input_hygiene_name']) && $_POST['input_hygiene_name'] !== '')
			$htmlOut .= ' value="' . htmlspecialchars($_POST['input_hygiene_name']) . '"';
		$htmlOut .= ' class="validate required ';
		if (isset($r['invalid']['input_hygiene_name']))
			$htmlOut .= ' invalid';
		$htmlOut .= '" />';
		$htmlOut .= '<span class="description"';
		if (!isset($r['invalid']['input_hygiene_name']))
			$htmlOut .= ' style="display:none;"';
		$htmlOut .= '>فارسی بنویسید</span><br />';
		$htmlOut .= '<label for="input_hygiene_phone" class="required">تلفن خانه بهداشت:</label>';
		$htmlOut .= '<input type="text" name="input_hygiene_phone" id="input_hygiene_phone" maxlength="14" dir="ltr"';
		if (isset($_POST['input_hygiene_phone']) && $_POST['input_hygiene_phone'] !== '')
			$htmlOut .= ' value="' . htmlspecialchars($_POST['input_hygiene_phone']) . '"';
		$htmlOut .= ' class="validate required tel';
		if (isset($r['invalid']['input_hygiene_phone']))
			$htmlOut .= ' invalid';
		$htmlOut .= '" />';
		$htmlOut .= '<span class="description">مثلاً 0123‎-‎1234567</span><br />';
		$htmlOut .= '<label for="input_hygiene_fax" class="required">شماره نمابر (فکس):</label>';
		$htmlOut .= '<input type="text" name="input_hygiene_fax" id="input_hygiene_fax" maxlength="14" dir="ltr"';
		if (isset($_POST['input_hygiene_fax']) && $_POST['input_hygiene_fax'] !== '')
			$htmlOut .= ' value="' . htmlspecialchars($_POST['input_hygiene_fax']) . '"';
		$htmlOut .= ' class="validate required tel';
		if (isset($r['invalid']['input_hygiene_fax']))
			$htmlOut .= ' invalid';
		$htmlOut .= '" />';
		$htmlOut .= '<span class="description">مثلاً 0123‎-‎1234567</span><br />';
		$htmlOut .= '<label for="input_hygiene_address" class="labelbr required">آدرس خانه بهداشت:</label>';
		$htmlOut .= '<span class="description">فارسی بنویسید</span><br />';
		$htmlOut .= '<textarea name="input_hygiene_address" id="input_hygiene_address" rows="4" cols="43" class="validate required ';
		if (isset($r['invalid']['input_hygiene_address']))
			echo ' invalid';
		$htmlOut .= '">';
		if (isset($_POST['input_hygiene_address']))
			$htmlOut .= htmlspecialchars($_POST['input_hygiene_address']);
		$htmlOut .= '</textarea><br />';
		$htmlOut .= '<input type="submit" value="ثبت خانه بهداشت" name="submitAddHygieneUnit" /></fieldset></form>';

		$res = Database::execute_query("SELECT `id`, `name` FROM `hygiene-units` WHERE `town-id` = '" . Database::filter_str(self::$id) . "' ORDER BY `name` ASC;");
		$counter = 1;
		if (Database::num_of_rows($res) > 0) {
			$htmlOut .= '<p style="margin-right: 20px;">برای مدیریت هر خانه بهداشت، بر روی نام آن کلیک نمایید.</p>';
			$htmlOut .= '<fieldset><legend>خانه های بهداشت شهرستان ' . self::getName() . '</legend>';
			$htmlOut .= '<br /><table align="right" class="contentTable" border="0" width="400" cellpadding="0" cellspacing="1">';
			$htmlOut .= '<thead><tr><th width="40">ردیف</th><th>نام</th><th width="80">تعداد پرسنل</th><th width="50">حذف</th><th width="50">ویرایش</th></thead><tbody>';
			while ($row = Database::get_assoc_array($res)) {
				$resTmp = Database::execute_query("SELECT `id` FROM `hygiene-unit-personnels` WHERE `hygiene-unit-id` = '" . Database::filter_str($row['id']) . "';");
				$num = intval(Database::num_of_rows($resTmp));
				$htmlOut .= '<tr><td>' . $counter . '</td><td><a href="?cmd=modifyHygieneUnits&hygieneUnitId=' . $row['id'] . '">' . $row['name'] . '</a></td>';
				$htmlOut .= '<td>' . $num . '</td>';
				$htmlOut .= '<td><a href="?cmd=removeHygieneUnit&hygieneUnitId=' . $row['id'] . '"><img src="../illustrator/images/icons/remove.png" title="حذف خانه بهداشت ' . $row['name'] . '" /></a></td>';
				$htmlOut .= '<td><a href="?cmd=editHygieneUnit&hygieneUnitId=' . $row['id'] . '"><img src="../illustrator/images/icons/edit.png" title="ویریش خانه بهداشت ' . $row['name'] . '" /></a></td></tr>';
				$counter++;
			}

			$htmlOut .= '</tbody></table></fielset>';
		} else
			$htmlOut .= NO_HYGIENE_UNIT_EXISTS;
		$htmlOut .= '</div>';
		//for tab2
		$htmlOut .= '</div>';
		//for whole tabs
		return $htmlOut;
	}*/

	public static function viewCenters() {
		$htmlOut = '<p style="font-weight: bold; text-align: center;">فهرست مراکز شهرستان ' . self::getName() . '</p>';
		$htmlOut .= '<p style="text-align: right; font-weight: bold; font-size: 15px;"><a href="#" style="color: green;" id="slideLink" ><img src="../illustrator/images/icons/plus.png" />&nbsp;افزودن مرکز</a></p>';
		$htmlOut .= '<form action="' . substr(strrchr($_SERVER['PHP_SELF'], '/'), 1) . '" method="post" id="slideForm" class="narin">';
		$htmlOut .= '<input type="hidden" name="narin:token" value="' . Narin::create_token() . '" />';
		$htmlOut .= '<input type="hidden" name="townId" value="' . self::$id . '" />';
		$htmlOut .= '<fieldset><legend>افزودن مرکز</legend>';
		$htmlOut .= '<label for="input_center_name" class="required">نام مرکز:</label>';
		$htmlOut .= '<input type="text" name="input_center_name" id="input_center_name" maxlength="256"';
		if (isset($_POST['input_center_name']) && $_POST['input_center_name'] !== '')
			$htmlOut .= ' value="' . htmlspecialchars($_POST['input_center_name']) . '"';
		$htmlOut .= 'class="validate required ';
		if (isset($r['invalid']['input_center_name']))
			$htmlOut .= ' invalid';
		$htmlOut .= '" />';
		$htmlOut .= '<span class="description"';
		if (!isset($r['invalid']['input_center_name']))
			$htmlOut .= ' style="display:none;" >فارسی بنویسید</span><br />';
		$htmlOut .= '<label for="input_center_phone" class="required">تلفن مرکز:</label>';
		$htmlOut .= '<input type="text" name="input_center_phone" id="input_center_phone" maxlength="14" dir="ltr"';
		if (isset($_POST['input_center_phone']) && $_POST['input_center_phone'] !== '')
			$htmlOut .= ' value="' . htmlspecialchars($_POST['input_center_phone']) . '"';
		$htmlOut .= ' class="validate required tel';
		if (isset($r['invalid']['input_center_phone']))
			$htmlOut[] = ' invalid';
		$htmlOut .= '" />';
		$htmlOut .= '<span class="description">مثلاً 0123‎-‎1234567</span><br />';
		$htmlOut .= '<label for="input_center_fax" class="required">شماره نمابر (فکس) مرکز:</label>';
		$htmlOut .= '<input type="text" name="input_center_fax" id="input_center_fax" maxlength="14" dir="ltr"';
		if (isset($_POST['input_center_fax']) && $_POST['input_center_fax'] !== '')
			$htmlOut .= ' value="' . htmlspecialchars($_POST['input_center_fax']) . '"';
		$htmlOut .= ' class="validate required tel';
		if (isset($r['invalid']['input_center_fax']))
			$htmlOut[] = ' invalid';
		$htmlOut .= '" />';
		$htmlOut .= '<span class="description">مثلاً 0123‎-‎1234567</span><br />';
		$htmlOut .= '<label for="input_center_address" class="labelbr required">آدرس مرکز:</label>';
		$htmlOut .= '<span class="description">فارسی بنویسید</span><br />';
		$htmlOut .= '<textarea name="input_center_address" id="input_center_address" rows="4" cols="48" class="validate required ';
		if (isset($r['invalid']['input_center_address']))
			$htmlOut .= ' invalid';
		$htmlOut .= '">';
		if (isset($_POST['input_center_address']))
			$htmlOut .= htmlspecialchars($_POST['input_center_address']);
		$htmlOut .= '</textarea><br />';
		$htmlOut .= '<input type="submit" value="ثبت مرکز" name="submitAddCenter" /></fieldset></form>';

		$res = Database::execute_query("SELECT `id`, `name` FROM `centers` WHERE `town-id` = '" . Database::filter_str(self::$id) . "' ORDER BY `name` ASC;");
		$counter = 1;
		if (Database::num_of_rows($res) > 0) {
			$htmlOut .= '<p style="margin-right: 20px;">برای ورود به هر مرکز، بر روی نام آن کلیک نمایید.</p>';
			$htmlOut .= '<fieldset><legend>مراکز شهرستان ' . self::getName() . '</legend>';
			$htmlOut .= '<br /><table align="right" class="contentTable" border="0" cellpadding="0" cellspacing="1">';
			$htmlOut .= '<thead><th width="40">ردیف</th><th>نام</th><th width="100">تعداد واحدها</th><th width="50">حذف</th><th width="50">ویرایش</th></thead><tbody>';
			while ($row = Database::get_assoc_array($res)) {
				$resTmp = Database::execute_query("SELECT * FROM `units` WHERE `center-id` = '" . $row['id'] . "';");
				$num = intval(Database::num_of_rows($resTmp));
				$htmlOut .= '<tr><td>' . $counter . '</td><td><a href="?cmd=manageUnits&centerId=' . $row['id'] . '">' . $row['name'] . '</a></td>';
				$htmlOut .= '<td>' . $num . '</td>';
				$htmlOut .= '<td><a href="?cmd=removeCenter&centerId=' . $row['id'] . '"><img src="../illustrator/images/icons/remove.png" title="حذف مرکز ' . $row['name'] . '" /></a></td>';
				$htmlOut .= '<td><a href="?cmd=editCenter&centerId=' . $row['id'] . '"><img src="../illustrator/images/icons/edit.png" title="ویریش مرکز ' . $row['name'] . '" /></a></td></tr>';
				$counter++;
			}

			$htmlOut .= '</tbody></table></fielset>';
		} else
			$htmlOut .= NO_CENTER_EXISTS;

		return $htmlOut;
	}

	public static function removeCenter($id) {
		$resC = Database::execute_query("SELECT `name`, `town-id` FROM `centers` WHERE `id` = '$id';");
		$rowC = Database::get_assoc_array($resC);
		$resT = Database::execute_query("SELECT `name` FROM `towns` WHERE `id` = '" . $rowC['town-id'] . "';");
		$rowT = Database::get_assoc_array($resT);
		$_logTXT = 'حذف مرکز "' . $rowC['name'] . '" از شهرستان "' . $rowT['name'] . '"';
		
		try {
			Database::execute_query("DELETE FROM `centers` WHERE `id` = '" . $id . "';");
		} catch ( exception $e ) {
			return $e -> getMessage();
		}
		FormProccessor::_makeLog($_logTXT);
		
		header("Location: " . $_SERVER['HTTP_REFERER']);
		exit;
		return true;
	}

	public static function editCenter($id) {
		$htmlOut = '';
		$dbRes = Database::execute_query("SELECT * FROM `centers` WHERE `id` = '" . $id . "';");
		$tmp = Database::get_assoc_array($dbRes);
		$htmlOut .= '<form action="' . substr(strrchr($_SERVER['PHP_SELF'], '/'), 1) . '" method="post" class="narin">';
		$htmlOut .= '<input type="hidden" name="narin:token" value="' . Narin::create_token() . '" />';
		$htmlOut .= '<input type="hidden" name="townId" value="' . self::$id . '" />';
		$htmlOut .= '<input type="hidden" name="centerId" value="' . $id . '" />';
		$htmlOut .= '<fieldset><legend>ویرایش مرکز</legend>';
		$htmlOut .= '<label for="input_center_name" class="required">نام مرکز:</label>';
		$htmlOut .= '<input type="text" name="input_center_name" id="input_center_name" maxlength="255" value="' . $tmp['name'] . '"';
		$htmlOut .= 'class="validate required ';
		if (isset($r['invalid']['input_center_name']))
			$htmlOut .= ' invalid';
		$htmlOut .= '" />';
		$htmlOut .= '<span class="description"';
		if (!isset($r['invalid']['input_center_name']))
			$htmlOut .= ' style="display:none;" >فارسی بنویسید</span><br />';
		$htmlOut .= '<label for="input_center_phone" class="required">تلفن مرکز:</label>';
		$htmlOut .= '<input type="text" name="input_center_phone" id="input_center_phone" maxlength="14" dir="ltr" value="' . $tmp['phone'] . '"';
		$htmlOut .= ' class="validate required tel';
		if (isset($r['invalid']['input_center_phone']))
			$htmlOut[] = ' invalid';
		$htmlOut .= '" />';
		$htmlOut .= '<span class="description">مثلاً 0123‎-‎1234567</span><br />';
		$htmlOut .= '<label for="input_center_fax" class="required">شماره نمابر (فکس) مرکز:</label>';
		$htmlOut .= '<input type="text" name="input_center_fax" id="input_center_fax" maxlength="14" dir="ltr" value="' . $tmp['fax'] . '"';
		$htmlOut .= ' class="validate required tel';
		if (isset($r['invalid']['input_center_fax']))
			$htmlOut[] = ' invalid';
		$htmlOut .= '" />';
		$htmlOut .= '<span class="description">مثلاً 0123‎-‎1234567</span><br />';
		$htmlOut .= '<label for="input_center_address" class="labelbr required">آدرس مرکز:</label>';
		$htmlOut .= '<span class="description">فارسی بنویسید</span><br />';
		$htmlOut .= '<textarea name="input_center_address" id="input_center_address" rows="4" cols="48" class="validate required ';
		if (isset($r['invalid']['input_center_address']))
			$htmlOut .= ' invalid';
		$htmlOut .= '">';
		$htmlOut .= $tmp['address'] . '</textarea><br />';
		$htmlOut .= '<input type="submit" value="ویرایش مرکز" name="submitEditCenter" /></fieldset></form>';

		return $htmlOut;
	}

	public static function removeHygieneUnit($id) {
		$resH = Database::execute_query("SELECT `name`, `center-id` FROM `hygiene-units` WHERE `id` = '$id';");
		$rowH = Database::get_assoc_array($resH);
		$resC = Database::execute_query("SELECT `name`, `town-id` FROM `centers` WHERE `id` = '" . $rowH['center-id'] . "';");
		$rowC = Database::get_assoc_array($resC);
		$resT = Database::execute_query("SELECT `name` FROM `towns` WHERE `id` = '" . $rowC['town-id'] . "';");
		$rowT = Database::get_assoc_array($resT);
		$_logTXT = 'حذف خانه‌بهداشت "' . $rowH['name'] . '" از مرکز "' . $rowC['name'] . '" در شهرستان "' . $rowT['name'] . '"';
		
		try {
			Database::execute_query("DELETE FROM `hygiene-units` WHERE `id` = '" . $id . "';");
		} catch ( exception $e ) {
			return $e -> getMessage();
		}
		FormProccessor::_makeLog($_logTXT);
		
		header("Location: " . $_SERVER['HTTP_REFERER']);
		exit;
		return true;
	}

	public static function viewActionsMenu() {
		$htmlOut = '';
		if ($_SESSION['user']['acl'] == 'State Manager') {
			$htmlOut .= '<a href="' . $_SERVER['PHP_SELF'] . '"><img src="../illustrator/images/icons/home.png" />&nbsp;صفحه‌اصلی</a>';
			$htmlOut .= '<a href="?cmd=manageTowns"><img src="../illustrator/images/icons/towns.png" />&nbsp;شهرستان‌ها</a>';
			$htmlOut .= '<a href="?cmd=manageAvailableServices"><img src="../illustrator/images/icons/service.png" />&nbsp;خدمات موجود</a>';
			$htmlOut .= '<a href="?cmd=manageAvailableJobTitles"><img src="../illustrator/images/icons/crm.png" />&nbsp;رده‌های پرسنلی</a>';
			$htmlOut .= '<a href="?cmd=manageAvailableDrugs"><img src="../illustrator/images/icons/drug.png" />&nbsp;داروها و تجهیزات مصرفی</a>';
			$htmlOut .= '<a href="?cmd=manageAvailableNonConsumingEquipments"><img src="../illustrator/images/icons/non_consuming.png" />&nbsp;تجهیزات غیرمصرفی</a>';
			$htmlOut .= '<a href="?cmd=manageGeneralChargesTypes"><img src="../illustrator/images/icons/charge.png" />&nbsp;نوع هزینه‌های عمومی</a>';
			$htmlOut .= '<a href="?cmd=reports"><img src="../illustrator/images/icons/chart-1.png" />&nbsp;گزارشات و نمودارها</a>';
			$htmlOut .= '<a href="' . substr(strrchr($_SERVER['PHP_SELF'], '/'), 1) . '?cmd=manageTowns"><img src="../illustrator/images/icons/back.png" />&nbsp;بازگشت</a>';
		}
		$htmlOut .= '<a href="?cmd=logOut"><img src="../illustrator/images/icons/exit.png" />&nbsp;خروج از سامانه</a>';
		return $htmlOut;
	}

}
?>