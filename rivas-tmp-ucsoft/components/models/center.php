<?php

/**
 *
 * center.php
 * center model class file
 *
 * @copyright	Copyright (C) 2012 Rivas Systems Inc. All rights reserved.
 * @versin	1.00 2011-02-05
 * @author	Behnam Salili
 *
 */

require_once dirname(dirname(__file__)) . '/database/database.php';
require_once dirname(dirname(__file__)) . '/form/form-proccessor.php';


class Center {
	private static $id;

	public static function setId($id) {
		self::$id = $id;
	}

	public static function getName() {
		$res = Database::execute_query("SELECT `name` FROM `centers` WHERE `id` = " . self::$id . ";");
		$tmp = Database::get_assoc_array($res);
		return $tmp['name'];
	}

	public static function viewUnitsAndHygieneUnits() {
		$htmlOut = '<p style="font-weight: bold; text-align: center; font-size: large;">مدیریت مرکز "' . self::getName() . '"</p>';
		$htmlOut .= '<div id="tabs"><ul><li><a href="#tabs-1">واحدها</a></li><li><a href="#tabs-2">خانه های بهداشت</a></li></ul>';
		$htmlOut .= '<div id="tabs-1"><p style="font-weight: bold; text-align: center;">فهرست واحدهای مرکز "' . self::getName() . '"</p>';
		$htmlOut .= '<p style="text-align: right; font-weight: bold; font-size: 15px; margin-right: 20px"><a href="#" style="color: green;" id="slideLink" ><img src="../illustrator/images/icons/plus.png" />&nbsp;افزودن واحد</a></p>';
		$htmlOut .= '<form action="' . substr(strrchr($_SERVER['PHP_SELF'], '/'), 1) . '" method="post" id="slideForm" class="narin">';
		$htmlOut .= '<input type="hidden" name="narin:token" value="' . Narin::create_token() . '" />';
		$htmlOut .= '<input type="hidden" name="centerId" value="' . self::$id . '" />';
		$htmlOut .= '<fieldset><legend>افزودن واحد</legend>';
        $htmlOut .= '<label class="required">نوع واحد:</label>';
        $htmlOut .= '<input type="radio" name="input_unit_type" id="unit_type_0" value="0"';
        if(isset($_POST['input_unit_type']) && $_POST['input_unit_type'] == "0")
            $htmlOut .= 'checked';
        $htmlOut .= '>';
        $htmlOut .= '<label for="unit_type_0">عمومی</label>';
        $htmlOut .= '<input type="radio" name="input_unit_type" id="unit_type_1" value="1" style="margin-right: 10px;"';
        if(isset($_POST['input_unit_type']) && $_POST['input_unit_type'] == "1")
            $htmlOut .= 'checked';
        $htmlOut .= '>';
        $htmlOut .= '<label for="unit_type_1">سیب</label><br>';
        $htmlOut .= '<label for="input_unit_name" class="required">نام واحد:</label>';
		$htmlOut .= '<input type="text" name="input_unit_name" id="input_unit_name" maxlength="256"';
		if (isset($_POST['input_unit_name']) && $_POST['input_unit_name'] !== '')
			$htmlOut .= ' value="' . htmlspecialchars($_POST['input_unit_name']) . '"';
		$htmlOut .= ' class="validate required ';
		if (isset($r['invalid']['input_unit_name']))
			$htmlOut .= ' invalid';
		$htmlOut .= '" />';
		$htmlOut .= '<span class="description"';
		if (!isset($r['invalid']['input_unit_name']))
			$htmlOut .= ' style="display:none;"';
		$htmlOut .= '>فارسی بنویسید</span>';
		$htmlOut .= '<input type="submit" value="ثبت واحد" name="submitAddUnit" /></fieldset></form>';

		$res = Database::execute_query("SELECT `id`, `unit_type`, `name` FROM  `units` WHERE `center-id` = '" . Database::filter_str(self::$id) . "' ORDER BY `name` ASC;");
		$counter = 1;
		if (Database::num_of_rows($res) > 0) {
			$htmlOut .= '<p style="margin-right: 20px;">برای مدیریت هر واحد، بر روی نام آن کلیک نمایید.</p>';
			$htmlOut .= '<fieldset><legend>واحدهای مرکز ' . self::getName() . '</legend>';
			$htmlOut .= '<br /><table align="right" class="contentTable" border="0" cellpadding="0" cellspacing="1">';
			$htmlOut .= '<thead><tr><th width="40">ردیف</th><th width="60">نوع واحد</th><th>نام</th><th width="80">تعداد پرسنل</th><th width="50">حذف</th><th width="50">ویرایش</th></thead><tbody>';
			while ($row = Database::get_assoc_array($res)) {
				$resTmp = Database::execute_query("SELECT `id` FROM `unit-personnels` WHERE `unit-id` = '" . Database::filter_str($row['id']) . "';");
				$num = intval(Database::num_of_rows($resTmp));
				$type = '';
                switch($row['unit_type']) {
                    case '1':
                        $type = 'سیب';
                        break;
                    default:
                        $type = 'عمومی';
                        break;
                }
				$htmlOut .= '<tr><td>' . $counter . '</td><td>' . $type . '</td><td><a href="?cmd=modifyUnits&unitId=' . $row['id'] . '">' . $row['name'] . '</a></td>';
				$htmlOut .= '<td>' . $num . '</td>';
				$htmlOut .= '<td><a href="?cmd=removeUnit&unitId=' . $row['id'] . '"><img src="../illustrator/images/icons/remove.png" title="حذف واحد ' . $row['name'] . '" /></a></td>';
				$htmlOut .= '<td><a href="?cmd=editUnit&unitId=' . $row['id'] . '"><img src="../illustrator/images/icons/edit.png" title="ویریش واحد ' . $row['name'] . '" /></a></td></tr>';
				$counter++;
			}

			$htmlOut .= '</tbody></table></fielset>';
		} else
			$htmlOut .= NO_UNIT_EXISTS;
		$htmlOut .= '</div>';

		/*****************************************************************************************************************************/

		$htmlOut .= '<div id="tabs-2"><p style="font-weight: bold; text-align: center;">فهرست خانه های بهداشت مرکز "' . self::getName() . '"</p>';
		$htmlOut .= '<p style="text-align: right; font-weight: bold; font-size: 15px;"><a href="#" id="slideLink1" style="color: green;" ><img src="../illustrator/images/icons/plus.png" />&nbsp;افزودن خانه بهداشت</a></p>';
		$htmlOut .= '<form action="' . substr(strrchr($_SERVER['PHP_SELF'], '/'), 1) . '" method="post" id="slideForm1" class="narin">';
		$htmlOut .= '<input type="hidden" name="narin:token" value="' . Narin::create_token() . '" />';
		$htmlOut .= '<input type="hidden" name="centerId" value="' . self::$id . '" />';
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

		$res = Database::execute_query("SELECT `id`, `name` FROM `hygiene-units` WHERE `center-id` = '" . Database::filter_str(self::$id) . "' ORDER BY `name` ASC;");
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
	}

	public static function removeUnit($id) {
		//_log process
		try {
			$resU = Database::execute_query("SELECT `name`, `center-id` FROM `units` WHERE `id` = '$id';");
			$rowU = Database::get_assoc_array($resU);
			$resC = Database::execute_query("SELECT `name`, `town-id` FROM `centers` WHERE `id` = '" . $rowU['center-id'] . "';");
			$rowC = Database::get_assoc_array($resC);
			$res = Database::execute_query("SELECT `name` FROM `towns` WHERE `id` = '" . $rowC['town-id'] . "';");
			$row = Database::get_assoc_array($res);
		} catch ( exception $e ) {
			return $e -> getMessage();
		}
		$_logTXT = 'حذف واحد "' . $rowU['name'] . '" از مرکز "' . $rowC['name'] . '" در شهرستان "' . $row['name'] . '"';
		
		try {
			Database::execute_query("DELETE FROM `units` WHERE `id` = '" . $id . "';");
		} catch ( exception $e ) {
			return $e -> getMessage();
		}
		FormProccessor::_makeLog($_logTXT);
		
		header("Location: " . $_SERVER['HTTP_REFERER']);
		exit;
	}

	public static function editUnit($id) {
		$htmlOut = '';
		$res = Database::execute_query("SELECT * FROM `units` WHERE `id` = '" . $id . "';");
		while ($row = Database::get_assoc_array($res))
			$tmp = $row;
		$htmlOut .= '<form action="' . substr(strrchr($_SERVER['PHP_SELF'], '/'), 1) . '" method="post"  class="narin">';
		$htmlOut .= '<input type="hidden" name="narin:token" value="' . Narin::create_token() . '" />';
		$htmlOut .= '<input type="hidden" name="unit_old" value="' . $id . '" />';
		$htmlOut .= '<input type="hidden" name="centerId" value="' . self::$id . '" />';
		$htmlOut .= '<fieldset><legend>ویرایش واحد</legend>';
        $htmlOut .= '<label class="required">نوع واحد:</label>';
        $htmlOut .= '<input type="radio" name="input_unit_type" id="unit_type_0" value="0"';
        if((isset($_POST['input_unit_type']) && $_POST['input_unit_type'] == "0") || $tmp['unit_type'] == "0")
            $htmlOut .= 'checked';
        $htmlOut .= '>';
        $htmlOut .= '<label for="unit_type_0">عمومی</label>';
        $htmlOut .= '<input type="radio" name="input_unit_type" id="unit_type_1" value="1" style="margin-right: 10px;"';
        if((isset($_POST['input_unit_type']) && $_POST['input_unit_type'] == "1") || $tmp['unit_type'] == "1")
            $htmlOut .= 'checked';
        $htmlOut .= '>';
        $htmlOut .= '<label for="unit_type_1">سیب</label><br>';
        $htmlOut .= '<label for="input_unit_name" class="required">نام واحد:</label>';
		$htmlOut .= '<input type="text" name="input_unit_name" id="input_unit_name" maxlength="256" value="' . $tmp['name'] . '"';
		$htmlOut .= ' class="validate required ';
		if (isset($r['invalid']['input_unit_name']))
			$htmlOut .= ' invalid';
		$htmlOut .= '" />';
		$htmlOut .= '<span class="description"';
		if (!isset($r['invalid']['input_unit_name']))
			$htmlOut .= ' style="display:none;"';
		$htmlOut .= '>فارسی بنویسید</span>';
		$htmlOut .= '<input type="submit" value="ویرایش واحد" name="submitEditUnit" /></fieldset></form>';

		return $htmlOut;
	}

	public static function editHygieneUnit($id) {
		$htmlOut = '';
		$dbRes = Database::execute_query("SELECT * FROM `hygiene-units` WHERE `id` = '$id';");
		while ($row = Database::get_assoc_array($dbRes))
			$tmp = $row;
		$htmlOut .= '<form action="' . substr(strrchr($_SERVER['PHP_SELF'], '/'), 1) . '" method="post" class="narin">';
		$htmlOut .= '<input type="hidden" name="narin:token" value="' . Narin::create_token() . '" />';
		$htmlOut .= '<input type="hidden" name="hygiene_old_id" value="' . $id . '" />';
		$htmlOut .= '<input type="hidden" name="centerId" value="' . self::$id . '" />';
		$htmlOut .= '<fieldset><legend>افزودن خانه بهداشت</legend><label for="input_hygiene_name" class="required">نام خانه بهداشت:</label>';
		$htmlOut .= '<input type="text" name="input_hygiene_name" id="input_hygiene_name" maxlength="255" value="' . $tmp['name'] . '"';
		$htmlOut .= ' class="validate required ';
		if (isset($r['invalid']['input_hygiene_name']))
			$htmlOut .= ' invalid';
		$htmlOut .= '" />';
		$htmlOut .= '<span class="description"';
		if (!isset($r['invalid']['input_hygiene_name']))
			$htmlOut .= ' style="display:none;"';
		$htmlOut .= '>فارسی بنویسید</span><br />';
		$htmlOut .= '<label for="input_hygiene_phone" class="required">تلفن خانه بهداشت:</label>';
		$htmlOut .= '<input type="text" name="input_hygiene_phone" id="input_hygiene_phone" maxlength="14" dir="ltr" value="' . $tmp['phone'] . '"';
		$htmlOut .= ' class="validate required tel';
		if (isset($r['invalid']['input_hygiene_phone']))
			$htmlOut .= ' invalid';
		$htmlOut .= '" />';
		$htmlOut .= '<span class="description">مثلاً 0123‎-‎1234567</span><br />';
		$htmlOut .= '<label for="input_hygiene_fax" class="required">شماره نمابر (فکس):</label>';
		$htmlOut .= '<input type="text" name="input_hygiene_fax" id="input_hygiene_fax" maxlength="14" dir="ltr" value="' . $tmp['fax'] . '"';
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
		$htmlOut .= $tmp['address'] . '</textarea><br />';
		$htmlOut .= '<input type="submit" value="ویرایش خانه بهداشت" name="submitEditHygieneUnit" /></fieldset></form>';

		return $htmlOut;
	}

	public static function manageCenterDrugs() {
		$htmlOut = '';
		$htmlOut .= '<p style="margin-right: 20px;">در این قسمت می توانید داروهای مرکز "' . self::getName() . '" را تعیین نمایید.</p>';
		$htmlOut .= '<p style="text-align: right; font-weight: bold; font-size: 15px; margin-right: 20px"><a href="#" style="color: green;" id="slideLink" ><img src="../illustrator/images/icons/plus.png" />&nbsp;افزودن</a></p>';
		$htmlOut .= '<form action="' . substr(strrchr($_SERVER['PHP_SELF'], '/'), 1) . '" method="post" id="slideForm" class="narin">';
		$htmlOut .= '<input type="hidden" name="narin:token" value="' . Narin::create_token() . '" /><fieldset><legend>ثبت دارو</legend>';
		$htmlOut .= '<label for="input_drug_name">نام:</label>';
		/*$htmlOut .= '<input id="input_drug_name" name="input_drug_name" list="drugs-list" title="انتخاب کنید..." class="validate required ';
        if (isset($r['invalid']['input_drug_name']))
            $htmlOut .= 'invalid';
        $htmlOut .= '">';
		$htmlOut .= '<datalist id="drugs-list">';
        $dbRes = Database::execute_query("SELECT `id`, `name` FROM `available-drugs-and-consuming-equipments` WHERE `is_drug` = 'yes' ORDER BY `name` ASC;");
        if (Database::num_of_rows($dbRes) > 0) {
            while ($dbRow = Database::get_assoc_array($dbRes))
                $htmlOut .= '<option value="' . $dbRow['id'] . '">' . $dbRow['name'] . '</option>';
        } else
            $htmlOut .= '<option>' . NO_AVAILABLE_DRUGS . '</option>';
		$htmlOut .= '</datalist><br>';*/
		$htmlOut .= '<select name="input_drug_name" id="input_drug_name" class="validate first_opt_not_allowed ';
		if (isset($r['invalid']['input_drug_name']))
			$htmlOut .= 'invalid';
		$htmlOut .= '">';
		$dbRes = Database::execute_query("SELECT `id`, `name` FROM `available-drugs-and-consuming-equipments` WHERE `is_drug` = 'yes' ORDER BY `name` ASC;");
		if (Database::num_of_rows($dbRes) > 0) {
			$htmlOut .= '<option>انتخاب کنید...</option>';
			while ($dbRow = Database::get_assoc_array($dbRes))
				$htmlOut .= '<option value="' . $dbRow['id'] . '">' . $dbRow['name'] . '</option>';
		} else
			$htmlOut .= '<option>' . NO_AVAILABLE_DRUGS . '</option>';
		$htmlOut .= '</select><br />';
		$htmlOut .= '<label for="input_drug_num" class="required">تعداد:</label>';
		$htmlOut .= '<input type="text" name="input_drug_num" id="input_drug_num" maxlength="6" dir="ltr"';
		if (isset($_POST['input_drug_num']) && $_POST['input_drug_num'] !== '')
			$htmlOut .= ' value="' . htmlspecialchars($_POST['input_drug_num']) . '"';
		$htmlOut .= ' class="validate required integer';
		if (isset($r['invalid']['input_drug_num']))
			$htmlOut .= ' invalid';
		$htmlOut .= '" />';
		$htmlOut .= '<span class="description"';
		if (!isset($r['invalid']['input_drug_num']))
			$htmlOut .= ' style="display:none;"';
		$htmlOut .= '>عدد صحیح، حداقل بزرگتر از 0</span>';
		$htmlOut .= '<script type="text/javascript">$(\'#input_drug_num\').data(\'num_min\',0).data(\'num_min_inc\',false);</script><br />';
		$htmlOut .= '<label for="input_drug_cm" class="labelbr">ملاحظات:</label>';
		$htmlOut .= '<span class="description">فارسی بنویسید</span><br />';
		$htmlOut .= '<textarea name="input_drug_cm" id="input_drug_cm" rows="4" cols="40" class="validate ';
		if (isset($r['invalid']['input_drug_cm']))
			$htmlOut .= ' invalid';
		$htmlOut .= '">';
		if (isset($_POST['input_drug_cm']))
			$htmlOut .= htmlspecialchars($_POST['input_drug_cm']);
		$htmlOut .= '</textarea><br /><input type="submit" name="submitAddCenterDrugsAndConsumingEquipments" value="ثبت خرید" /></fieldset></form>';

		/************************************************************************************************************************************/
        //$res = Database::execute_query("SELECT * FROM `v_centers_drugs_full` WHERE `deleted` = '0' AND `is_drug` = 'yes' AND `center-id` = '" . $_SESSION['centerId'] . "' ORDER BY `name` ASC;");
        $res = Database::execute_query("SELECT `center-drugs`.`id`, `center-drugs`.`num-of-drugs`, `available-drugs-cost`.`cost`, `available-drugs-and-consuming-equipments`.`name` FROM `center-drugs` 
                                    LEFT JOIN `available-drugs-cost` ON `center-drugs`.`drug-cost-id` = `available-drugs-cost`.`id`
                                    LEFT JOIN `available-drugs-and-consuming-equipments` ON `available-drugs-cost`.`drug-id` = `available-drugs-and-consuming-equipments`.`id`
                                    WHERE `center-drugs`.`deleted` = 0 AND `center-drugs`.`center-id` = '" . $_SESSION['centerId'] . "' AND `available-drugs-and-consuming-equipments`.`is_drug` = 'yes' 
                                    ORDER BY `available-drugs-and-consuming-equipments`.`name` ASC;");
		$counter = 1;
		if (Database::num_of_rows($res) > 0) {
			$htmlOut .= '<fieldset><legend>فهرست داروهای مرکز "' . self::getName() . '"</legend>';
			$htmlOut .= '<br /><table align="right" class="contentTable" border="0" cellpadding="0" cellspacing="1">';
			$htmlOut .= '<thead><th width="40">ردیف</th><th>نام</th><th width="40">تعداد</th><th>قیمت واحد(ريال)</th><th>قیمت کل(ريال)</th><th width="50">حذف</th></thead><tbody>';
			while ($row = Database::get_assoc_array($res)) {
				$htmlOut .= '<tr><td>' . $counter . '</td><td>' . $row['name'] . '</a></td>';
				$htmlOut .= '<td>' . $row['num-of-drugs'] . '</td>';
				$htmlOut .= '<td>' . $row['cost'] . '</td>';
				$htmlOut .= '<td>' . $row['num-of-drugs'] * $row['cost'] . '</td>';
				$htmlOut .= '<td><a href="?cmd=removeCenterDrug&drugId=' . $row['id'] . '"><img src="../illustrator/images/icons/remove.png" title="حذف ' . $row['name'] . '" /></a></td></tr>';
				$counter++;
			}

			$htmlOut .= '</tbody></table></fielset><br />';
		} else
			$htmlOut .= '<p class="warning">هیچ دارویی برای مرکز "' . self::getName() . '" ثبت نشده است.</p>';

		return $htmlOut;
	}

	public static function manageCenterConsumingEquipments() {
		$htmlOut = '';
		$htmlOut .= '<p style="margin-right: 20px;">در این قسمت می توانید تجهیزات مصرفی مرکز "' . self::getName() . '" را تعیین نمایید.</p>';
		$htmlOut .= '<p style="text-align: right; font-weight: bold; font-size: 15px; margin-right: 20px"><a href="#" style="color: green;" id="slideLink" ><img src="../illustrator/images/icons/plus.png" />&nbsp;افزودن</a></p>';
		$htmlOut .= '<form action="' . substr(strrchr($_SERVER['PHP_SELF'], '/'), 1) . '" method="post" id="slideForm" class="narin">';
		$htmlOut .= '<input type="hidden" name="narin:token" value="' . Narin::create_token() . '" /><fieldset><legend>ثبت تجهیزات مصرفی</legend>';
		$htmlOut .= '<label for="input_drug_name">نام:</label>';
        /*$htmlOut .= '<input id="input_drug_name" name="input_drug_name" list="drugs-list" title="انتخاب کنید..." class="validate required ';
        if (isset($r['invalid']['input_drug_name']))
            $htmlOut .= 'invalid';
        $htmlOut .= '">';
        $htmlOut .= '<datalist id="drugs-list">';
        $dbRes = Database::execute_query("SELECT `id`, `name` FROM `available-drugs-and-consuming-equipments` WHERE `is_drug` = 'no' ORDER BY `name` ASC;");
        if (Database::num_of_rows($dbRes) > 0) {
            while ($dbRow = Database::get_assoc_array($dbRes))
                $htmlOut .= '<option value="' . $dbRow['id'] . '">' . $dbRow['name'] . '</option>';
        } else
            $htmlOut .= '<option>' . NO_AVAILABLE_DRUGS . '</option>';
        $htmlOut .= '</datalist><br>';*/
		$htmlOut .= '<select name="input_drug_name" id="input_drug_name" class="validate first_opt_not_allowed ';
		if (isset($r['invalid']['input_drug_name']))
			$htmlOut .= 'invalid';
		$htmlOut .= '">';
		$dbRes = Database::execute_query("SELECT `id`, `name` FROM `available-drugs-and-consuming-equipments` WHERE `is_drug` = 'no' ORDER BY `name` ASC;");
		if (Database::num_of_rows($dbRes) > 0) {
			$htmlOut .= '<option>انتخاب کنید...</option>';
			while ($dbRow = Database::get_assoc_array($dbRes))
				$htmlOut .= '<option value="' . $dbRow['id'] . '">' . $dbRow['name'] . '</option>';
		} else
			$htmlOut .= '<option>' . NO_AVAILABLE_DRUGS . '</option>';
		$htmlOut .= '</select><br />';
		$htmlOut .= '<label for="input_drug_num" class="required">تعداد:</label>';
		$htmlOut .= '<input type="text" name="input_drug_num" id="input_drug_num" maxlength="6" dir="ltr"';
		if (isset($_POST['input_drug_num']) && $_POST['input_drug_num'] !== '')
			$htmlOut .= ' value="' . htmlspecialchars($_POST['input_drug_num']) . '"';
		$htmlOut .= ' class="validate required integer';
		if (isset($r['invalid']['input_drug_num']))
			$htmlOut .= ' invalid';
		$htmlOut .= '" />';
		$htmlOut .= '<span class="description"';
		if (!isset($r['invalid']['input_drug_num']))
			$htmlOut .= ' style="display:none;"';
		$htmlOut .= '>عدد صحیح، حداقل بزرگتر از 0</span>';
		$htmlOut .= '<script type="text/javascript">$(\'#input_drug_num\').data(\'num_min\',0).data(\'num_min_inc\',false);</script><br />';
		$htmlOut .= '<label for="input_drug_cm" class="labelbr">ملاحظات:</label>';
		$htmlOut .= '<span class="description">فارسی بنویسید</span><br />';
		$htmlOut .= '<textarea name="input_drug_cm" id="input_drug_cm" rows="4" cols="40" class="validate ';
		if (isset($r['invalid']['input_drug_cm']))
			$htmlOut .= ' invalid';
		$htmlOut .= '">';
		if (isset($_POST['input_drug_cm']))
			$htmlOut .= htmlspecialchars($_POST['input_drug_cm']);
		$htmlOut .= '</textarea><br /><input type="submit" name="submitAddCenterDrugsAndConsumingEquipments" value="ثبت خرید" /></fieldset></form>';

		/************************************************************************************************************************************/
		//$res = Database::execute_query("SELECT * FROM `v_centers_drugs_full` WHERE `deleted` = '0' AND `is_drug` = 'no' AND `center-id` = '" . $_SESSION['centerId'] . "' ORDER BY `name` ASC;");
        $res = Database::execute_query("SELECT `center-drugs`.`id`, `center-drugs`.`num-of-drugs`, `available-drugs-cost`.`cost`, `available-drugs-and-consuming-equipments`.`name` FROM `center-drugs` 
                                    LEFT JOIN `available-drugs-cost` ON `center-drugs`.`drug-cost-id` = `available-drugs-cost`.`id`
                                    LEFT JOIN `available-drugs-and-consuming-equipments` ON `available-drugs-cost`.`drug-id` = `available-drugs-and-consuming-equipments`.`id`
                                    WHERE `center-drugs`.`deleted` = 0 AND `center-drugs`.`center-id` = '" . $_SESSION['centerId'] . "' AND `available-drugs-and-consuming-equipments`.`is_drug` = 'no' 
                                    ORDER BY `available-drugs-and-consuming-equipments`.`name` ASC;");

		$counter = 1;
		if (Database::num_of_rows($res) > 0) {
			$htmlOut .= '<fieldset><legend>فهرست تجهیزات مصرفی مرکز "' . self::getName() . '"</legend>';
			$htmlOut .= '<br /><table align="right" class="contentTable" border="0" cellpadding="0" cellspacing="1">';
			$htmlOut .= '<thead><th width="40">ردیف</th><th>نام</th><th width="40">تعداد</th><th>قیمت واحد(ريال)</th><th>قیمت کل(ريال)</th><th width="50">حذف</th></thead><tbody>';
			while ($row = Database::get_assoc_array($res)) {
				$htmlOut .= '<tr><td>' . $counter . '</td><td>' . $row['name'] . '</a></td>';
				$htmlOut .= '<td>' . $row['num-of-drugs'] . '</td>';
				$htmlOut .= '<td>' . $row['cost'] . '</td>';
				$htmlOut .= '<td>' . $row['num-of-drugs'] * $row['cost'] . '</td>';
				$htmlOut .= '<td><a href="?cmd=removeCenterDrug&drugId=' . $row['id'] . '"><img src="../illustrator/images/icons/remove.png" title="حذف ' . $row['name'] . '" /></a></td></tr>';
				$counter++;
			}

			$htmlOut .= '</tbody></table></fielset><br />';
		} else
			$htmlOut .= '<p class="warning">هیچ تجهیزات مصرفی برای مرکز "' . self::getName() . '" ثبت نشده است.</p>';

		return $htmlOut;
	}

	public static function removeCenterDrugsAndConsumingEquipments($id) {
		
		//_log process
		try {
			//$resU = Database::execute_query("SELECT * FROM `v_centers_drugs_full` WHERE `center-drug-id` = '$id';");
            $resU = Database::execute_query("SELECT `center-drugs`.`id`, `center-drugs`.`center-id`, `available-drugs-cost`.`cost`, `available-drugs-and-consuming-equipments`.`name` FROM `center-drugs` 
                                    LEFT JOIN `available-drugs-cost` ON `center-drugs`.`drug-cost-id` = `available-drugs-cost`.`id`
                                    LEFT JOIN `available-drugs-and-consuming-equipments` ON `available-drugs-cost`.`drug-id` = `available-drugs-and-consuming-equipments`.`id`
                                    WHERE `center-drugs`.`id` = " . $id . ";");
			$rowU = Database::get_assoc_array($resU);
			$resC = Database::execute_query("SELECT `name`, `town-id` FROM `centers` WHERE `id` = '" . $rowU['center-id'] . "';");
			$rowC = Database::get_assoc_array($resC);
			$res = Database::execute_query("SELECT `name` FROM `towns` WHERE `id` = '" . $rowC['town-id'] . "';");
			$row = Database::get_assoc_array($res);
		} catch ( exception $e ) {
			return $e -> getMessage();
		}
		$_logTXT = 'حذف دارو یا تجهیزات مصرفی به‌نام "' . $rowU['name'] . '" از مرکز "' . $rowC['name'] . '" در شهرستان "' . $row['name'] . '"';
		
		$dateTimeObj = new DateTime();
		$dateTimeZone = new DateTimeZone('Asia/Tehran');
		$dateTimeObj -> setTimezone($dateTimeZone);
		$shamsiDate = gregorian_to_jalali($dateTimeObj -> format('Y'), $dateTimeObj -> format('m'), $dateTimeObj -> format('d'));
		$date = $shamsiDate[0] . "-" . $shamsiDate[1] . "-" . $shamsiDate[2];
		try {
			Database::execute_query("UPDATE `center-drugs` SET `deleted` = '1', `date-of-delete` = '$date' WHERE `id` = '" . $id . "';");
			unset($dateTimeObj, $dateTimeZone, $date, $shamsiDate);
		} catch ( exception $e ) {
			return $e -> getMessage();
		}
		FormProccessor::_makeLog($_logTXT);
		
		header("Location: " . $_SERVER['HTTP_REFERER']);
		exit;
	}

	public static function addCenterNonConsumingEquipments() {
		$htmlOut = '';
		$htmlOut .= '<p style="margin-right: 20px;">در این قسمت می توانید تجهیزات غیرمصرفی مرکز "' . self::getName() . '" را تعیین نمایید.</p>';
		$htmlOut .= '<p style="text-align: right; font-weight: bold; font-size: 15px; margin-right: 20px"><a href="#" style="color: green;" id="slideLink" ><img src="../illustrator/images/icons/plus.png" />&nbsp;افزودن</a></p>';
		$htmlOut .= '<form action="' . substr(strrchr($_SERVER['PHP_SELF'], '/'), 1) . '" method="post" id="slideForm" class="narin">';
		$htmlOut .= '<input type="hidden" name="narin:token" value="' . Narin::create_token() . '" /><fieldset><legend>ثبت خرید تجهیزات مصرفی</legend>';
		$htmlOut .= '<label for="input_equip_id">نام:</label>';
        /*$htmlOut .= '<input id="input_equip_id" name="input_equip_id" list="equipments-list" title="انتخاب کنید..." class="validate required ';
        if (isset($r['invalid']['input_equip_id']))
            $htmlOut .= 'invalid';
        $htmlOut .= '">';
        $htmlOut .= '<datalist id="equipments-list">';
        $dbRes = Database::execute_query("SELECT `id`, `name`, `mark` FROM `available-non-consuming-equipments` ORDER BY `name` ASC;");
        if (Database::num_of_rows($dbRes) > 0) {
            while ($dbRow = Database::get_assoc_array($dbRes))
                $htmlOut .= '<option value="' . $dbRow['id'] . '">' . $dbRow['name'] . ' - (' . $dbRow['mark'] . ')</option>';
        } else
            $htmlOut .= '<option>' . NO_AVAILABLE_NONCONSUMINGEQUIPMENT . '</option>';
        $htmlOut .= '</datalist><br>';*/
		$htmlOut .= '<select name="input_equip_id" id="input_equip_id" class="validate first_opt_not_allowed ';
		if (isset($r['invalid']['input_equip_id']))
			$htmlOut .= 'invalid';
		$htmlOut .= '">';
		$dbRes = Database::execute_query("SELECT `id`, `name`, `mark` FROM `available-non-consuming-equipments` ORDER BY `name` ASC;");
		if (Database::num_of_rows($dbRes) > 0) {
			$htmlOut .= '<option>انتخاب کنید...</option>';
			while ($dbRow = Database::get_assoc_array($dbRes))
				$htmlOut .= '<option value="' . $dbRow['id'] . '">' . $dbRow['name'] . ' - (' . $dbRow['mark'] . ')</option>';
		} else
			$htmlOut .= '<option>' . NO_AVAILABLE_NONCONSUMINGEQUIPMENT . '</option>';
		$htmlOut .= '</select><br />';
		$htmlOut .= '<div id="date_picker_div" class="date_picker required';
		if (isset($r['invalid']['input_built_date']))
			$htmlOut .= ' invalid';
		$htmlOut .= '">';
		$htmlOut .= '<label id="date_picker_label" for="input_buy_date:y" class="required">سال خریداری:</label>';
		$htmlOut .= '<input type="hidden" name="input_buy_date:d" value="1" />';
		$htmlOut .= '<input type="hidden" name="input_buy_date:m" value="1" />';
		$htmlOut .= '<input type="text" id="build_year" name="input_buy_date:y" size="4" maxlength="4" class="date_y"';
		if (isset($_POST['input_buy_date:y']) && $_POST['input_buy_date:y'] !== '')
			$htmlOut .= ' value="' . htmlspecialchars($_POST['input_buy_date:y']) . '"';
		$htmlOut .= ' /></div><br />';
		$htmlOut .= '<label for="input_equip_nums" class="required">تعداد:</label>';
		$htmlOut .= '<input type="text" name="input_equip_nums" id="input_equip_nums" maxlength="6" dir="ltr"';
		if (isset($_POST['input_equip_nums']) && $_POST['input_equip_nums'] !== '')
			$htmlOut .= ' value="' . htmlspecialchars($_POST['input_equip_nums']) . '"';
		$htmlOut .= ' class="validate required integer';
		if (isset($r['invalid']['input_equip_nums']))
			$htmlOut .= ' invalid';
		$htmlOut .= '" />';
		$htmlOut .= '<span class="description"';
		if (!isset($r['invalid']['input_equip_nums']))
			$htmlOut .= ' style="display:none;"';
		$htmlOut .= '>عدد صحیح، حداقل بزرگتر از 0</span>';
		$htmlOut .= '<script type="text/javascript">$(\'#input_equip_nums\').data(\'num_min\',0).data(\'num_min_inc\',false);</script><br />';
		$htmlOut .= '<label for="input_equip_cm" class="labelbr">ملاحظات:</label>';
		$htmlOut .= '<span class="description">فارسی بنویسید</span><br />';
		$htmlOut .= '<textarea name="input_equip_cm" id="input_equip_cm" rows="4" cols="40" class="validate ';
		if (isset($r['invalid']['input_equip_cm']))
			$htmlOut .= ' invalid';
		$htmlOut .= '">';
		if (isset($_POST['input_equip_cm']))
			$htmlOut .= htmlspecialchars($_POST['input_equip_cm']);
		$htmlOut .= '</textarea><br />';
		$htmlOut .= '<input type="submit" name="submitAddCenterNonConsumingEquipments" value="ثبت" /></fieldset></form>';

		/************************************************************************************************************************************/

		//$res = Database::execute_query("SELECT * FROM `v_centers_nonconsumings_full` WHERE `deleted` = '0' AND `center-id` = '" . $_SESSION['centerId'] . "' ORDER BY `name` ASC;");
		$res = Database::execute_query("SELECT `center-non-consuming-equipments`.`id`, `center-non-consuming-equipments`.`num-of-equips`, `available-non-consuming-cost`.`cost`, `available-non-consuming-equipments`.`name`, `available-non-consuming-equipments`.`mark` FROM `center-non-consuming-equipments` 
                                    LEFT JOIN `available-non-consuming-cost` ON `center-non-consuming-equipments`.`equip-cost-id` = `available-non-consuming-cost`.`id` 
                                    LEFT JOIN `available-non-consuming-equipments` ON `available-non-consuming-cost`.`equip-id` = `available-non-consuming-equipments`.`id` 
                                    WHERE `center-non-consuming-equipments`.`center-id` = " . $_SESSION['centerId'] . " AND `center-non-consuming-equipments`.`deleted` = '0' 
                                    ORDER BY `available-non-consuming-equipments`.`name` ASC;");
        $counter = 1;
		if (Database::num_of_rows($res) > 0) {
			$htmlOut .= '<fieldset><legend>فهرست تجهیزات غیرمصرفی مرکز "' . self::getName() . '"</legend>';
			$htmlOut .= '<br /><table align="right" class="contentTable" border="0" cellpadding="0" cellspacing="1">';
			$htmlOut .= '<thead><th width="40">ردیف</th><th>نام</th><th width="40">تعداد</th><th>قیمت واحد(ريال)</th><th>قیمت کل(ريال)</th><th width="50">حذف</th></thead><tbody>';
			while ($row = Database::get_assoc_array($res)) {
				$htmlOut .= '<tr><td>' . $counter . '</td><td>' . $row['name'] . ' - (' . $row['mark'] . ')</a></td>';
				$htmlOut .= '<td>' . $row['num-of-equips'] . '</td>';
				$htmlOut .= '<td>' . $row['cost'] . '</td>';
				$htmlOut .= '<td>' . $row['num-of-equips'] * $row['cost'] . '</td>';
				$htmlOut .= '<td><a href="?cmd=removeCenterNonConsumingEquip&id=' . $row['id'] . '"><img src="../illustrator/images/icons/remove.png" title="حذف ' . $row['name'] . '" /></a></td></tr>';
				$counter++;
			}

			$htmlOut .= '</tbody></table></fielset><br />';
		} else
			$htmlOut .= '<p class="warning">هیچ تجهیزات غیرمصرفی برای مرکز "' . self::getName() . '" ثبت نشده است.</p>';

		return $htmlOut;
	}

	public static function removeCenterNonConsumingEquipment($id) {
		//_log process
		try {
			//$resU = Database::execute_query("SELECT * FROM `v_centers_nonconsumings_full` WHERE `center-equip-id` = '$id';");
            $resU = Database::execute_query("SELECT `center-non-consuming-equipments`.`center-id`, `available-non-consuming-equipments`.`name`, `available-non-consuming-equipments`.`mark` FROM `center-non-consuming-equipments` 
                                    LEFT JOIN `available-non-consuming-cost` ON `center-non-consuming-equipments`.`equip-cost-id` = `available-non-consuming-cost`.`id` 
                                    LEFT JOIN `available-non-consuming-equipments` ON `available-non-consuming-cost`.`equip-id` = `available-non-consuming-equipments`.`id` 
                                    WHERE `center-non-consuming-equipments`.`id` = " . $id . ";");
			$rowU = Database::get_assoc_array($resU);
			$resC = Database::execute_query("SELECT `name`, `town-id` FROM `centers` WHERE `id` = '" . $rowU['center-id'] . "';");
			$rowC = Database::get_assoc_array($resC);
			$res = Database::execute_query("SELECT `name` FROM `towns` WHERE `id` = '" . $rowC['town-id'] . "';");
			$row = Database::get_assoc_array($res);
		} catch ( exception $e ) {
			return $e -> getMessage();
		}
		$_logTXT = 'حذف تجهیزات غیرمصرفی به‌نام "' . $rowU['name'] . '(' .$rowU['mark'] . ')" از مرکز "' . $rowC['name'] . '" در شهرستان "' . $row['name'] . '"';
		
		$dateTimeObj = new DateTime();
		$dateTimeZone = new DateTimeZone('Asia/Tehran');
		$dateTimeObj -> setTimezone($dateTimeZone);
		$shamsiDate = gregorian_to_jalali($dateTimeObj -> format('Y'), $dateTimeObj -> format('m'), $dateTimeObj -> format('d'));
		$date = $shamsiDate[0] . "-" . $shamsiDate[1] . "-" . $shamsiDate[2];
		try {
			Database::execute_query("UPDATE `center-non-consuming-equipments` SET `deleted` = '1', `date-of-delete` = '$date' WHERE `id` = '" . $id . "';");
			unset($dateTimeObj, $dateTimeZone, $date, $shamsiDate);
		} catch ( exception $e ) {
			return $e -> getMessage();
		}
		FormProccessor::_makeLog($_logTXT);
		
		header("Location: " . $_SERVER['HTTP_REFERER']);
		exit;
	}

	public static function manageCenterCharges() {
		$htmlOut = '';
		$htmlOut .= '<p>در این قسمت شما می توانید هزینه های استهلاک و نگهداری مرکز "' . self::getName() . '" را مدیریت نمایید.<br />از منوی سمت راست گزینه ی موردنظر را انتخاب نمایید.</p>';

		return $htmlOut;
	}

	public static function manageCenterBuildings() {
		$htmlOut = '';
		$htmlOut .= '<p>در این قسمت شما می توانید ساختمان های مورد استفاده ی مرکز "' . self::getName() . '"  را مدیریت نمایید.</p>';
		$htmlOut .= '<p style="text-align: right; font-weight: bold; font-size: 15px; margin-right: 20px"><a href="#" style="color: green;" id="slideLink" ><img src="../illustrator/images/icons/plus.png" />&nbsp;افزودن ساختمان</a></p>';
		$htmlOut .= '<form action="' . substr(strrchr($_SERVER['PHP_SELF'], '/'), 1) . '" method="post" id="slideForm" class="narin">';
		$htmlOut .= '<input type="hidden" name="narin:token" value="' . Narin::create_token() . '" /><fieldset><legend>افزودن ساختمان</legend>';
		$htmlOut .= '<label for="input_building_title" class="required">عنوان ساختمان:</label>';
		$htmlOut .= '<input type="text" name="input_building_title" id="input_building_title" maxlength="255"';
		if (isset($_POST['input_building_title']) && $_POST['input_building_title'] !== '')
			$htmlOut .= ' value="' . htmlspecialchars($_POST['input_building_title']) . '"';
		$htmlOut .= ' class="validate required ';
		if (isset($r['invalid']['input_building_title']))
			$htmlOut .= ' invalid';
		$htmlOut .= '" />';
		$htmlOut .= '<span class="description"';
		if (!isset($r['invalid']['input_building_title']))
			$htmlOut .= ' style="display:none;"';
		$htmlOut .= '>فارسی بنویسید</span><br />';
		$htmlOut .= '<label for="input_ground_area" class="required">مساحت کل زمین:</label>';
		$htmlOut .= '<input type="text" name="input_ground_area" id="input_ground_area" dir="ltr"';
		if (isset($_POST['input_ground_area']) && $_POST['input_ground_area'] !== '')
			$htmlOut .= ' value="' . htmlspecialchars($_POST['input_ground_area']) . '"';
		$htmlOut .= ' class="validate required integer';
		if (isset($r['invalid']['input_ground_area']))
			$htmlOut .= ' invalid';
		$htmlOut .= '" />';
		$htmlOut .= '<span class="description"';
		if (!isset($r['invalid']['input_ground_area']))
			$htmlOut .= ' style="display:none;"';
		$htmlOut .= '>عدد صحیح، حداقل بزرگتر از 0</span>';
		$htmlOut .= '<script type="text/javascript">$(\'#input_ground_area\').data(\'num_min\',0).data(\'num_min_inc\',false);</script><br />';
		$htmlOut .= '<label for="input_building_area" class="required">متراژ ساختمان:</label>';
		$htmlOut .= '<input type="text" name="input_building_area" id="input_building_area" dir="ltr"';
		if (isset($_POST['input_building_area']) && $_POST['input_building_area'] !== '')
			$htmlOut .= ' value="' . htmlspecialchars($_POST['input_building_area']) . '"';
		$htmlOut .= ' class="validate required integer';
		if (isset($r['invalid']['input_building_area']))
			$htmlOut .= ' invalid';
		$htmlOut .= '" />';
		$htmlOut .= '<span class="description"';
		if (!isset($r['invalid']['input_building_area']))
			$htmlOut .= ' style="display:none;"';
		$htmlOut .= '>عدد صحیح، حداقل بزرگتر از 0</span>';
		$htmlOut .= '<script type="text/javascript">$(\'#input_building_area\').data(\'num_min\',0).data(\'num_min_inc\',false);</script><br />';
		$htmlOut .= '<div class="optgroup';
		if (isset($r['invalid']['input_ownership_type']))
			$htmlOut .= ' invalid';
		$htmlOut .= '">';
		$htmlOut .= '<h3>نوع مالکیت:</h3>';
		$htmlOut .= '<input type="radio" name="input_ownership_type" id="input_ownership_type_0" value="0"';
		if (!isset($_POST['input_ownership_type']) || $_POST['input_ownership_type'] == '0')
			$htmlOut .= ' checked="checked"';
		$htmlOut .= ' /><label for="input_ownership_type_0">ملکی</label><br />';
		$htmlOut .= '<input type="radio" name="input_ownership_type" id="input_ownership_type_1" value="1"';
		if (isset($_POST['input_ownership_type']) && $_POST['input_ownership_type'] == '1')
			$htmlOut .= ' checked="checked"';
		$htmlOut .= ' /><label for="input_ownership_type_1">استیجاری</label><br />';
		$htmlOut .= '<input type="radio" name="input_ownership_type" id="input_ownership_type_2" value="2"';
		if (isset($_POST['input_ownership_type']) && $_POST['input_ownership_type'] == '2')
			$htmlOut .= ' checked="checked"';
		$htmlOut .= ' /><label for="input_ownership_type_2">خیریه</label></div>';
		$htmlOut .= '<br /><label for="input_rent_cost" id="estijari" class="required">مبلغ ماهیانه اجاره بها (به ریال):</label>';
		$htmlOut .= '<input type="text" name="input_rent_cost" id="input_rent_cost" dir="ltr"';
		if (isset($_POST['input_rent_cost']) && $_POST['input_rent_cost'] !== '')
			$htmlOut .= ' value="' . htmlspecialchars($_POST['input_rent_cost']) . '"';
		$htmlOut .= ' class="validate required integer';
		if (isset($r['invalid']['input_rent_cost']))
			$htmlOut .= ' invalid';
		$htmlOut .= '" />';
		$htmlOut .= '<span class="description"';
		if (!isset($r['invalid']['input_rent_cost']))
			$htmlOut .= ' style="display:none;"';
		$htmlOut .= '>عدد صحیح، حداقل بزرگتر از 0</span>';
		$htmlOut .= '<script type="text/javascript">$(\'#input_rent_cost\').data(\'num_min\',0).data(\'num_min_inc\',false);</script>';
		$htmlOut .= '<div id="date_picker_div" class="date_picker required';
		if (isset($r['invalid']['input_built_date']))
			$htmlOut .= ' invalid';
		$htmlOut .= '">';
		$htmlOut .= '<label id="date_picker_label" for="input_built_date:y" class="required">سال ساخت:</label>';
		$htmlOut .= '<input type="hidden" name="input_built_date:d" value="1" />';
		$htmlOut .= '<input type="hidden" name="input_built_date:m" value="1" />';
		$htmlOut .= '<input type="text" id="build_year" name="input_built_date:y" size="4" maxlength="4" class="date_y"';
		if (isset($_POST['input_built_date:y']) && $_POST['input_built_date:y'] !== '')
			$htmlOut .= ' value="' . htmlspecialchars($_POST['input_built_date:y']) . '"';
		$htmlOut .= ' /></div><br />';
		$htmlOut .= '<label id="melki" for="input_building_worth" class="required">ارزش ریالی ساختمان:</label>';
		$htmlOut .= '<input type="text" name="input_building_worth" id="input_building_worth" dir="ltr"';
		if (isset($_POST['input_building_worth']) && $_POST['input_building_worth'] !== '')
			$htmlOut .= ' value="' . htmlspecialchars($_POST['input_building_worth']) . '"';
		$htmlOut .= ' class="validate required integer';
		if (isset($r['invalid']['input_building_worth']))
			$htmlOut .= ' invalid';
		$htmlOut .= '" />';
		$htmlOut .= '<span class="description"';
		if (!isset($r['invalid']['input_building_worth']))
			$htmlOut .= ' style="display:none;"';
		$htmlOut .= '>عدد صحیح، حداقل بزرگتر از 0</span><br />';
		$htmlOut .= '<input type="submit" value="ثبت ساختمان" name="submitAddCenterBuilding" /></fieldset></form>';

		$res = Database::execute_query("SELECT `id`, `title` FROM  `center-buildings` WHERE `center-id` = '" . Database::filter_str(self::$id) . "' ORDER BY `title` ASC;");
		$counter = 1;
		if (Database::num_of_rows($res) > 0) {
			$htmlOut .= '<div><fieldset><legend>فهرست ساختمان های مرکز ' . self::getName() . '</legend>';
			$htmlOut .= '<br /><table align="right" class="contentTable" border="0" cellpadding="0" cellspacing="1">';
			$htmlOut .= '<thead><tr><th width="40">ردیف</th><th>نام</th><th width="50">حذف</th><th width="50">ویرایش</th></thead><tbody>';
			while ($row = Database::get_assoc_array($res)) {
				$htmlOut .= '<tr><td>' . $counter . '</td><td>' . $row['title'] . '</td>';
				$htmlOut .= '<td><a href="?cmd=removeCenterBuilding&buildingId=' . $row['id'] . '"><img src="../illustrator/images/icons/remove.png" title="حذف ' . $row['title'] . '" /></a></td>';
				$htmlOut .= '<td><a href="?cmd=editCenterBuilding&buildingId=' . $row['id'] . '"><img src="../illustrator/images/icons/edit.png" title="ویرایش ' . $row['title'] . '" /></a></td></tr>';
				$counter++;
			}

			$htmlOut .= '</tbody></table></fielset></div>';
		} else
			$htmlOut .= NO_CENTER_BUILDINGS;

		return $htmlOut;
	}

	public static function removeCenterBuilding($id) {
		//_log process
		try {
			$resU = Database::execute_query("SELECT * FROM `center-buildings` WHERE `id` = '$id';");
			$rowU = Database::get_assoc_array($resU);
			$resC = Database::execute_query("SELECT `name`, `town-id` FROM `centers` WHERE `id` = '" . $rowU['center-id'] . "';");
			$rowC = Database::get_assoc_array($resC);
			$res = Database::execute_query("SELECT `name` FROM `towns` WHERE `id` = '" . $rowC['town-id'] . "';");
			$row = Database::get_assoc_array($res);
		} catch ( exception $e ) {
			return $e -> getMessage();
		}
		$_logTXT = 'حذف ساختمان باعنوان "' . $rowU['title'] . '" از مرکز "' . $rowC['name'] . '" در شهرستان "' . $row['name'] . '"';
		
		try {
			Database::execute_query("DELETE FROM `center-buildings` WHERE `id` = '" . $id . "';");
		} catch ( exception $e ) {
			return $e -> getMessage();
		}
		FormProccessor::_makeLog($_logTXT);
		
		header("Location: " . $_SERVER['HTTP_REFERER']);
		exit;
		return true;
	}

	public static function editCenterBuilding($id) {
		$htmlOut = '';
		$res = Database::execute_query("SELECT * FROM `center-buildings` WHERE `id` = '$id';");
		$row = Database::get_assoc_array($res);
		$htmlOut .= '<form action="' . substr(strrchr($_SERVER['PHP_SELF'], '/'), 1) . '" method="post" class="narin">';
		$htmlOut .= '<input type="hidden" name="buildingId" value="' . $id . '" />';
		$htmlOut .= '<input type="hidden" name="narin:token" value="' . Narin::create_token() . '" /><fieldset><legend>اصلاح ساختمان</legend>';
		$htmlOut .= '<label for="input_building_title" class="required">عنوان ساختمان:</label>';
		$htmlOut .= '<input type="text" name="input_building_title" value="' . $row['title'] . '" id="input_building_title" maxlength="255"';
		if (isset($_POST['input_building_title']) && $_POST['input_building_title'] !== '')
			$htmlOut .= ' value="' . htmlspecialchars($_POST['input_building_title']) . '"';
		$htmlOut .= ' class="validate required ';
		if (isset($r['invalid']['input_building_title']))
			$htmlOut .= ' invalid';
		$htmlOut .= '" />';
		$htmlOut .= '<span class="description"';
		if (!isset($r['invalid']['input_building_title']))
			$htmlOut .= ' style="display:none;"';
		$htmlOut .= '>فارسی بنویسید</span><br />';
		$htmlOut .= '<label for="input_ground_area" class="required">مساحت کل زمین:</label>';
		$htmlOut .= '<input type="text" name="input_ground_area" value="' . $row['ground-area'] . '" id="input_ground_area" dir="ltr"';
		if (isset($_POST['input_ground_area']) && $_POST['input_ground_area'] !== '')
			$htmlOut .= ' value="' . htmlspecialchars($_POST['input_ground_area']) . '"';
		$htmlOut .= ' class="validate required integer';
		if (isset($r['invalid']['input_ground_area']))
			$htmlOut .= ' invalid';
		$htmlOut .= '" />';
		$htmlOut .= '<span class="description"';
		if (!isset($r['invalid']['input_ground_area']))
			$htmlOut .= ' style="display:none;"';
		$htmlOut .= '>عدد صحیح، حداقل بزرگتر از 0</span>';
		$htmlOut .= '<script type="text/javascript">$(\'#input_ground_area\').data(\'num_min\',0).data(\'num_min_inc\',false);</script><br />';
		$htmlOut .= '<label for="input_building_area" class="required">متراژ ساختمان:</label>';
		$htmlOut .= '<input type="text" name="input_building_area" value="' . $row['building-area'] . '" id="input_building_area" dir="ltr"';
		if (isset($_POST['input_building_area']) && $_POST['input_building_area'] !== '')
			$htmlOut .= ' value="' . htmlspecialchars($_POST['input_building_area']) . '"';
		$htmlOut .= ' class="validate required integer';
		if (isset($r['invalid']['input_building_area']))
			$htmlOut .= ' invalid';
		$htmlOut .= '" />';
		$htmlOut .= '<span class="description"';
		if (!isset($r['invalid']['input_building_area']))
			$htmlOut .= ' style="display:none;"';
		$htmlOut .= '>عدد صحیح، حداقل بزرگتر از 0</span>';
		$htmlOut .= '<script type="text/javascript">$(\'#input_building_area\').data(\'num_min\',0).data(\'num_min_inc\',false);</script><br />';
		switch ($row['ownership-type']) {
			case '0' :
				//owned
				$htmlOut .= '<div id="date_picker_div" class="date_picker required';
				if (isset($r['invalid']['input_built_date']))
					$htmlOut .= ' invalid';
				$htmlOut .= '">';
				$htmlOut .= '<label id="date_picker_label" for="input_built_date:y" class="required">سال ساخت:</label>';
				$htmlOut .= '<input type="hidden" name="input_built_date:d" value="1" />';
				$htmlOut .= '<input type="hidden" name="input_built_date:m" value="1" />';
				$buildYear = date_parse($row['built-date']);
				$htmlOut .= '<input type="text" id="build_year" name="input_built_date:y" size="4" maxlength="4" class="date_y" value="' . $buildYear['year'] . '" /></div><br />';
				$htmlOut .= '<label id="melki" for="input_building_worth" class="required">ارزش ریالی ساختمان:</label>';
				$htmlOut .= '<input type="text" name="input_building_worth" id="input_building_worth" dir="ltr" class="validate required integer" value="' . $row['building-worth'] . '" /><br />';
				break;
			case '1' :
				//rent
				$htmlOut .= '<label for="input_rent_cost1" class="required">مبلغ ماهیانه اجاره بها (به ریال):</label>';
				$htmlOut .= '<input type="text" name="input_rent_cost" id="input_rent_cost1" dir="ltr" class="validate required integer" value="' . $row['rent-cost'] . '" /><br />';
				$htmlOut .= '<script type="text/javascript">$(\'#input_rent_cost1\').data(\'num_min\',0).data(\'num_min_inc\',false);</script>';
				break;
		}
		$htmlOut .= '<input type="submit" value="اصلاح ساختمان" name="submitEditCenterBuilding" /></fieldset></form>';

		return $htmlOut;
	}

	public static function addCenterBuildingCharge() {
		$htmlOut = '';
		$htmlOut .= '<p style="text-align: right; font-weight: bold; font-size: 15px; margin-right: 20px"><a href="#" style="color: green;" id="slideLink" ><img src="../illustrator/images/icons/plus.png" />&nbsp;ثبت</a></p>';
		$htmlOut .= '<form action="' . substr(strrchr($_SERVER['PHP_SELF'], '/'), 1) . '" id="slideForm" method="post" class="narin">';
		$htmlOut .= '<input type="hidden" name="narin:token" value="' . Narin::create_token() . '" /><fieldset><legend>افزودن هزینه تعمیر ساختمان</legend>';
		$htmlOut .= '<label for="input_charge_name" class="required">نام هزینه:</label>';
		$htmlOut .= '<input type="text" name="input_charge_name" id="input_charge_name" maxlength="255"';
		if (isset($_POST['input_charge_name']) && $_POST['input_charge_name'] !== '')
			$htmlOut .= ' value="' . htmlspecialchars($_POST['input_charge_name']) . '"';
		$htmlOut .= ' class="validate required ';
		if (isset($r['invalid']['input_charge_name']))
			$htmlOut .= ' invalid';
		$htmlOut .= '" />';
		$htmlOut .= '<span class="description"';
		if (!isset($r['invalid']['input_charge_name']))
			$htmlOut .= ' style="display:none;"';
		$htmlOut .= '>فارسی بنویسید</span><br />';
		$htmlOut .= '<label for="input_charge_amount" class="required">مبلغ هزینه (به ریال):</label>';
		$htmlOut .= '<input type="text" name="input_charge_amount" id="input_charge_amount" dir="ltr"';
		if (isset($_POST['input_charge_amount']) && $_POST['input_charge_amount'] !== '')
			$htmlOut .= ' value="' . htmlspecialchars($_POST['input_charge_amount']) . '"';
		$htmlOut .= ' class="validate required integer';
		if (isset($r['invalid']['input_charge_amount']))
			$htmlOut .= ' invalid';
		$htmlOut .= '" />';
		$htmlOut .= '<span class="description"';
		if (!isset($r['invalid']['input_charge_amount']))
			$htmlOut .= ' style="display:none;"';
		$htmlOut .= '>عدد صحیح، حداقل بزرگتر از 0</span>';
		$htmlOut .= '<script type="text/javascript">$(\'#input_charge_amount\').data(\'num_min\',0).data(\'num_min_inc\',false);</script><br />';
		$htmlOut .= '<label for="input_building_id">عنوان ساختمان:</label>';
		$htmlOut .= '<select name="input_building_id" id="input_building_id" class="validate first_opt_not_allowed ';
		if (isset($r['invalid']['input_building_id']))
			$htmlOut .= 'invalid';
		$htmlOut .= '">';
		$dbRes = Database::execute_query("SELECT `a`.`id`, `a`.`title` FROM `center-buildings` AS `a` INNER JOIN `centers` AS `b` ON `a`.`center-id` = `b`.`id` WHERE `a`.`center-id` = '" . $_SESSION['centerId'] . "' ORDER BY `title` ASC;");
		if (Database::num_of_rows($dbRes) > 0) {
			$htmlOut .= '<option>انتخاب کنید...</option>';
			while ($dbRow = Database::get_assoc_array($dbRes))
				$htmlOut .= '<option value="' . $dbRow['id'] . '">' . $dbRow['title'] . '</option>';
		} else
			$htmlOut .= '<option>' . NO_CENTER_BUILDINGS . '</option>';
		$htmlOut .= '</select><br /><input type="submit" name="submitAddCenterBuildingCharge" value="ثبت هزینه" /></fieldset></form>';

		$htmlOut .= '<div class="loading"><img src="../statisticalPlotter/loading.gif" />&nbsp;&nbsp;در حال بارگذاری؛ لطفا صبر کنید...</div><br />';
		$htmlOut .= '<div id="mngDiv"><fieldset><legend>مشاهده ی فهرست هزینه های ساختمان های مرکز "' . self::getName() . '"</legend>';
		$res = Database::execute_query("SELECT `id`, `title` FROM `center-buildings` WHERE `center-id` = '" . self::$id . "';");
		$htmlOut .= '<br /><label for="input_building_id_1" class="required">عنوان ساختمان:</label>';
		$htmlOut .= '<select name="input_building_id_1" id="input_building_id_1" class="validate first_opt_not_allowed">';
		if (Database::num_of_rows($res) > 0) {
			$htmlOut .= '<option>انتخاب کنید...</option>';
			while ($row = Database::get_assoc_array($res))
				$htmlOut .= '<option value="' . $row['id'] . '">' . $row['title'] . '</option>';
		} else
			$htmlOut .= '<option>' . NO_CENTER_BUILDINGS . '</option>';
		$htmlOut .= '</select><br />';
		$htmlOut .= '<div id="list"></div><br /></fieldset>';
		$htmlOut .= '</div>';
		$htmlOut .= '<script type="text/javascript">';
		$htmlOut .= '$(document).ajaxStart(function() {$(\'.loading\').show();$(\'#mngDiv\').css(\'opacity\', \'0.3\');});' . '$(document).ajaxSuccess(function() {$(\'.loading\').hide();$(\'#mngDiv\').css(\'opacity\', \'1\');});';
		$htmlOut .= '$(\'#list\').load(\'./ea-sh-ajax.php\', {\'buildingIdForBuildingCharge\' : $(\'#input_building_id_1 option:selected\').val()});';
		$htmlOut .= '$(\'#input_building_id_1\').change(function() {$(\'#list\').load(\'./ea-sh-ajax.php\', {\'buildingIdForBuildingCharge\' : $(\'#input_building_id_1 option:selected\').val()});});';
		$htmlOut .= '</script>';

		return $htmlOut;
	}

	public static function removeCenterBuildingCharge($id) {
		//_log process
		try {
			$resU = Database::execute_query("SELECT * FROM `center-building-maintain-charges` WHERE `id` = '$id';");
			$rowU = Database::get_assoc_array($resU);
			$resI = Database::execute_query("SELECT `title`, `center-id` FROM `center-buildings` WHERE `id` = '" . $rowU['building-id'] . "';");
			$rowI = Database::get_assoc_array($resI);
			$resC = Database::execute_query("SELECT `name`, `town-id` FROM `centers` WHERE `id` = '" . $rowI['center-id'] . "';");
			$rowC = Database::get_assoc_array($resC);
			$res = Database::execute_query("SELECT `name` FROM `towns` WHERE `id` = '" . $rowC['town-id'] . "';");
			$row = Database::get_assoc_array($res);
		} catch ( exception $e ) {
			return $e -> getMessage();
		}
		$_logTXT = 'حذف هزینه "' . $rowU['charge-name'] . '" از ساختمان "' . $rowI['title'] . '" در مرکز "' . $rowC['name'] . '" در شهرستان "' . $row['name'] . '"';
		
		try {
			Database::execute_query("DELETE FROM `center-building-maintain-charges` WHERE `id` = '" . $id . "';");
		} catch ( exception $e ) {
			return $e -> getMessage();
		}
		FormProccessor::_makeLog($_logTXT);
		
		header("Location: " . $_SERVER['HTTP_REFERER']);
		exit;
		return true;
	}

	public static function editCenterBuildingCharge($id) {
		$htmlOut = '';
		if (strpos($_SERVER['HTTP_REFERER'], 'es'))
			$_SESSION['ref'] = 1;
		else
			$_SESSION['ref'] = 0;
		$res = Database::execute_query("SELECT `charge-name`, `charge-amount` FROM `center-building-maintain-charges` WHERE `id` = '$id';");
		$row = Database::get_assoc_array($res);
		$htmlOut .= '<form action="' . substr(strrchr($_SERVER['PHP_SELF'], '/'), 1) . '" method="post" class="narin">';
		$htmlOut .= '<input type="hidden" name="id" value="' . $id . '" />';
		$htmlOut .= '<input type="hidden" name="narin:token" value="' . Narin::create_token() . '" /><fieldset><legend>اصلاح هزینه تعمیر ساختمان</legend>';
		$htmlOut .= '<label for="input_charge_name" class="required">نام هزینه:</label>';
		$htmlOut .= '<input type="text" name="input_charge_name" id="input_charge_name" maxlength="255" value="' . $row['charge-name'] . '"';
		if (isset($_POST['input_charge_name']) && $_POST['input_charge_name'] !== '')
			$htmlOut .= ' value="' . htmlspecialchars($_POST['input_charge_name']) . '"';
		$htmlOut .= ' class="validate required ';
		if (isset($r['invalid']['input_charge_name']))
			$htmlOut .= ' invalid';
		$htmlOut .= '" />';
		$htmlOut .= '<span class="description"';
		if (!isset($r['invalid']['input_charge_name']))
			$htmlOut .= ' style="display:none;"';
		$htmlOut .= '>فارسی بنویسید</span><br />';
		$htmlOut .= '<label for="input_charge_amount" class="required">مبلغ هزینه (به ریال):</label>';
		$htmlOut .= '<input type="text" name="input_charge_amount" id="input_charge_amount" dir="ltr" value="' . $row['charge-amount'] . '"';
		if (isset($_POST['input_charge_amount']) && $_POST['input_charge_amount'] !== '')
			$htmlOut .= ' value="' . htmlspecialchars($_POST['input_charge_amount']) . '"';
		$htmlOut .= ' class="validate required integer';
		if (isset($r['invalid']['input_charge_amount']))
			$htmlOut .= ' invalid';
		$htmlOut .= '" />';
		$htmlOut .= '<span class="description"';
		if (!isset($r['invalid']['input_charge_amount']))
			$htmlOut .= ' style="display:none;"';
		$htmlOut .= '>عدد صحیح، حداقل بزرگتر از 0</span>';
		$htmlOut .= '<script type="text/javascript">$(\'#input_charge_amount\').data(\'num_min\',0).data(\'num_min_inc\',false);</script><br />';
		$htmlOut .= '<input type="submit" name="submitEditCenterBuildingCharge" value="اصلاح هزینه" /></fieldset></form>';

		return $htmlOut;
	}

	public static function manageCenterVehicles() {
		$htmlOut = '';
		$htmlOut .= '<p>در این قسمت شما می توانید وسایل نقلیه ی مورد استفاده ی مرکز "' . self::getName() . '" را مدیریت نمایید.</p>';
		$htmlOut .= '<p style="text-align: right; font-weight: bold; font-size: 15px; margin-right: 20px"><a href="#" style="color: green;" id="slideLink" ><img src="../illustrator/images/icons/plus.png" />&nbsp;افزودن وسیله نقلیه</a></p>';
		$htmlOut .= '<form action="' . substr(strrchr($_SERVER['PHP_SELF'], '/'), 1) . '" method="post" id="slideForm" class="narin">';
		$htmlOut .= '<input type="hidden" name="narin:token" value="' . Narin::create_token() . '" /><fieldset><legend>افزودن وسیله نقلیه</legend>';
		$htmlOut .= '<label for="input_vehicle_title" class="required">عنوان وسیله:</label>';
		$htmlOut .= '<input type="text" name="input_vehicle_title" id="input_vehicle_title" maxlength="255"';
		if (isset($_POST['input_vehicle_title']) && $_POST['input_vehicle_title'] !== '')
			$htmlOut .= ' value="' . htmlspecialchars($_POST['input_vehicle_title']) . '"';
		$htmlOut .= ' class="validate required ';
		if (isset($r['invalid']['input_vehicle_title']))
			$htmlOut .= ' invalid';
		$htmlOut .= '" />';
		$htmlOut .= '<span class="description"';
		if (!isset($r['invalid']['input_vehicle_title']))
			$htmlOut .= ' style="display:none;"';
		$htmlOut .= '>فارسی بنویسید</span><br />';
		$htmlOut .= '<label for="input_vehicle_type" class="required">نوع وسیله:</label>';
		$htmlOut .= '<input type="text" name="input_vehicle_type" id="input_vehicle_type" maxlength="255"';
		if (isset($_POST['input_vehicle_type']) && $_POST['input_vehicle_type'] !== '')
			$htmlOut .= ' value="' . htmlspecialchars($_POST['input_vehicle_type']) . '"';
		$htmlOut .= ' class="validate required ';
		if (isset($r['invalid']['input_vehicle_type']))
			$htmlOut .= ' invalid';
		$htmlOut .= '" />';
		$htmlOut .= '<span class="description"';
		if (!isset($r['invalid']['input_vehicle_type']))
			$htmlOut .= ' style="display:none;"';
		$htmlOut .= '>فارسی بنویسید</span><br />';
		$htmlOut .= '<div class="optgroup';
		if (isset($r['invalid']['input_vehicle_ownership_type']))
			$htmlOut .= ' invalid';
		$htmlOut .= '">';
		$htmlOut .= '<h3>نوع مالکیت:</h3>';
		$htmlOut .= '<input type="radio" name="input_vehicle_ownership_type" id="input_vehicle_ownership_type_0" value="0"';
		if (!isset($_POST['input_vehicle_ownership_type']) || $_POST['input_vehicle_ownership_type'] == '0')
			$htmlOut .= ' checked="checked"';
		$htmlOut .= ' /><label for="input_vehicle_ownership_type_0">دولتی</label><br />';
		$htmlOut .= '<input type="radio" name="input_vehicle_ownership_type" id="input_vehicle_ownership_type_1" value="1"';
		if (isset($_POST['input_vehicle_ownership_type']) && $_POST['input_vehicle_ownership_type'] == '1')
			$htmlOut .= ' checked="checked"';
		$htmlOut .= ' /><label for="input_vehicle_ownership_type_1">استیجاری</label></div>';
		$htmlOut .= '<br /><label for="input_rent_cost" id="estijari" class="required">مبلغ ماهیانه اجاره بها:</label>';
		$htmlOut .= '<input type="text" name="input_rent_cost" id="input_rent_cost" dir="ltr"';
		if (isset($_POST['input_rent_cost']) && $_POST['input_rent_cost'] !== '')
			$htmlOut .= ' value="' . htmlspecialchars($_POST['input_rent_cost']) . '"';
		$htmlOut .= ' class="validate required integer';
		if (isset($r['invalid']['input_rent_cost']))
			$htmlOut .= ' invalid';
		$htmlOut .= '" />';
		$htmlOut .= '<span class="description"';
		if (!isset($r['invalid']['input_rent_cost']))
			$htmlOut .= ' style="display:none;"';
		$htmlOut .= '>عدد صحیح، حداقل بزرگتر از 0</span>';
		$htmlOut .= '<script type="text/javascript">$(\'#input_rent_cost\').data(\'num_min\',0).data(\'num_min_inc\',false);</script>';
		$htmlOut .= '<div id="date_picker_div" class="date_picker required';
		if (isset($r['invalid']['input_buy_date']))
			$htmlOut .= ' invalid';
		$htmlOut .= '">';
		$htmlOut .= '<label id="date_picker_label" for="input_buy_date:d" class="required">سال خرید:</label>';
		$htmlOut .= '<input type="hidden" name="input_buy_date:d" value="1" />';
		$htmlOut .= '<input type="hidden" name="input_buy_date:m" value="1" />';
		$htmlOut .= '<input type="text" id="buy_year" name="input_buy_date:y" size="4" maxlength="4" class="date_y"';
		if (isset($_POST['input_buy_date:y']) && $_POST['input_buy_date:y'] !== '')
			$htmlOut .= ' value="' . htmlspecialchars($_POST['input_buy_date:y']) . '"';
		$htmlOut .= ' /></div><br />';
		$htmlOut .= '<label for="input_vehicle_worth" class="required" id="melki">ارزش ریالی وسیله:</label>';
		$htmlOut .= '<input type="text" name="input_vehicle_worth" id="input_vehicle_worth" dir="ltr"';
		if (isset($_POST['input_vehicle_worth']) && $_POST['input_vehicle_worth'] !== '')
			$htmlOut .= ' value="' . htmlspecialchars($_POST['input_vehicle_worth']) . '"';
		$htmlOut .= ' class="validate required integer';
		if (isset($r['invalid']['input_vehicle_worth']))
			$htmlOut .= ' invalid';
		$htmlOut .= '" />';
		$htmlOut .= '<span class="description"';
		if (!isset($r['invalid']['input_vehicle_worth']))
			$htmlOut .= ' style="display:none;"';
		$htmlOut .= '>عدد صحیح، حداقل بزرگتر از 0</span>';
		$htmlOut .= '<script type="text/javascript">$(\'#input_vehicle_worth\').data(\'num_min\',0).data(\'num_min_inc\',false);</script><br />';
		$htmlOut .= '<input type="submit" name="submitAddCenterVehicle" value="ثبت وسیله نقلیه" /></fieldset></form>';

		$res = Database::execute_query("SELECT `id`, `title` FROM `center-vehicles` WHERE `center-id` = '" . Database::filter_str(self::$id) . "' ORDER BY `title` ASC;");
		$counter = 1;
		if (Database::num_of_rows($res) > 0) {
			$htmlOut .= '<div><fieldset><legend>فهرست وسایل نقلیه مرکز ' . self::getName() . '</legend>';
			$htmlOut .= '<br /><table align="right" class="contentTable" border="0" cellpadding="0" cellspacing="1">';
			$htmlOut .= '<thead><tr><th width="40">ردیف</th><th>نام</th><th width="50">حذف</th><th width="50">ویرایش</th></thead><tbody>';
			while ($row = Database::get_assoc_array($res)) {
				$htmlOut .= '<tr><td>' . $counter . '</td><td>' . $row['title'] . '</td>';
				$htmlOut .= '<td><a href="?cmd=removeCenterVehicle&vehicleId=' . $row['id'] . '"><img src="../illustrator/images/icons/remove.png" title="حذف ' . $row['title'] . '" /></a></td>';
				$htmlOut .= '<td><a href="?cmd=editCenterVehicle&vehicleId=' . $row['id'] . '"><img src="../illustrator/images/icons/edit.png" title="ویرایش ' . $row['title'] . '" /></a></td></tr>';
				$counter++;
			}

			$htmlOut .= '</tbody></table></fielset></div>';
		} else
			$htmlOut .= NO_CENTER_VEHICLE;

		return $htmlOut;
	}

	public static function removeCenterVehicle($id) {
		//_log process
		try {
			$resU = Database::execute_query("SELECT * FROM `center-vehicles` WHERE `id` = '$id';");
			$rowU = Database::get_assoc_array($resU);
			$resC = Database::execute_query("SELECT `name`, `town-id` FROM `centers` WHERE `id` = '" . $rowU['center-id'] . "';");
			$rowC = Database::get_assoc_array($resC);
			$res = Database::execute_query("SELECT `name` FROM `towns` WHERE `id` = '" . $rowC['town-id'] . "';");
			$row = Database::get_assoc_array($res);
		} catch ( exception $e ) {
			return $e -> getMessage();
		}
		$_logTXT = 'حذف وسیله نقلیه باعنوان "' . $rowU['title'] . '" از مرکز "' . $rowC['name'] . '" در شهرستان "' . $row['name'] . '"';
		
		try {
			Database::execute_query("DELETE FROM `center-vehicles` WHERE `id` = '" . $id . "';");
		} catch ( exception $e ) {
			return $e -> getMessage();
		}
		FormProccessor::_makeLog($_logTXT);
		
		header("Location: " . $_SERVER['HTTP_REFERER']);
		exit;
		return true;
	}

	public static function editCenterVehicle($id) {
		$htmlOut = '';
		if (strpos($_SERVER['HTTP_REFERER'], 'es'))
			$_SESSION['ref'] = 1;
		else
			$_SESSION['ref'] = 0;
		$res = Database::execute_query("SELECT * FROM `center-vehicles` WHERE `id` = '$id';");
		$row = Database::get_assoc_array($res);
		$htmlOut .= '<form action="' . substr(strrchr($_SERVER['PHP_SELF'], '/'), 1) . '" method="post" class="narin">';
		$htmlOut .= '<input type="hidden" name="vehicleId" value="' . $id . '" />';
		$htmlOut .= '<input type="hidden" name="narin:token" value="' . Narin::create_token() . '" /><fieldset><legend>اصلاح وسیله نقلیه</legend>';
		$htmlOut .= '<label for="input_vehicle_title" class="required">عنوان وسیله:</label>';
		$htmlOut .= '<input type="text" name="input_vehicle_title" id="input_vehicle_title" value="' . $row['title'] . '" maxlength="255"';
		if (isset($_POST['input_vehicle_title']) && $_POST['input_vehicle_title'] !== '')
			$htmlOut .= ' value="' . htmlspecialchars($_POST['input_vehicle_title']) . '"';
		$htmlOut .= ' class="validate required ';
		if (isset($r['invalid']['input_vehicle_title']))
			$htmlOut .= ' invalid';
		$htmlOut .= '" />';
		$htmlOut .= '<span class="description"';
		if (!isset($r['invalid']['input_vehicle_title']))
			$htmlOut .= ' style="display:none;"';
		$htmlOut .= '>فارسی بنویسید</span><br />';
		$htmlOut .= '<label for="input_vehicle_type" class="required">نوع وسیله:</label>';
		$htmlOut .= '<input type="text" name="input_vehicle_type" value="' . $row['vehicle-type'] . '" id="input_vehicle_type" maxlength="255"';
		if (isset($_POST['input_vehicle_type']) && $_POST['input_vehicle_type'] !== '')
			$htmlOut .= ' value="' . htmlspecialchars($_POST['input_vehicle_type']) . '"';
		$htmlOut .= ' class="validate required ';
		if (isset($r['invalid']['input_vehicle_type']))
			$htmlOut .= ' invalid';
		$htmlOut .= '" />';
		$htmlOut .= '<span class="description"';
		if (!isset($r['invalid']['input_vehicle_type']))
			$htmlOut .= ' style="display:none;"';
		$htmlOut .= '>فارسی بنویسید</span><br />';

		switch ($row['ownership-type']) {
			case '0' :
				//gov
				$htmlOut .= '<div id="date_picker_div" class="date_picker required';
				if (isset($r['invalid']['input_buy_date']))
					$htmlOut .= ' invalid';
				$htmlOut .= '">';
				$htmlOut .= '<label id="date_picker_label" for="input_buy_date:d" class="required">سال خرید:</label>';
				$htmlOut .= '<input type="hidden" name="input_buy_date:d" value="1" />';
				$htmlOut .= '<input type="hidden" name="input_buy_date:m" value="1" />';
				$buyYear = date_parse($row['buy-date']);
				$buyYear = $buyYear['year'];
				$htmlOut .= '<input type="text" id="buy_year" name="input_buy_date:y" size="4" maxlength="4" class="date_y" value="' . $buyYear . '" /></div><br />';
				$htmlOut .= '<label for="input_vehicle_worth" class="required" id="melki">ارزش ریالی وسیله:</label>';
				$htmlOut .= '<input type="text" name="input_vehicle_worth" id="input_vehicle_worth" dir="ltr" value="' . $row['vehicle-worth'] . '"';
				$htmlOut .= ' class="validate required integer';
				if (isset($r['invalid']['input_vehicle_worth']))
					$htmlOut .= ' invalid';
				$htmlOut .= '" />';
				$htmlOut .= '<span class="description"';
				if (!isset($r['invalid']['input_vehicle_worth']))
					$htmlOut .= ' style="display:none;"';
				$htmlOut .= '>عدد صحیح، حداقل بزرگتر از 0</span>';
				$htmlOut .= '<script type="text/javascript">$(\'#input_vehicle_worth\').data(\'num_min\',0).data(\'num_min_inc\',false);</script><br />';
				break;
			case '1' :
				//rent
				$htmlOut .= '<label for="input_rent_cost1" class="required">مبلغ ماهیانه اجاره بها:</label>';
				$htmlOut .= '<input type="text" name="input_rent_cost" id="input_rent_cost1" dir="ltr" value="' . $row['rent-cost'] . '"';
				$htmlOut .= ' class="validate required integer';
				if (isset($r['invalid']['input_rent_cost']))
					$htmlOut .= ' invalid';
				$htmlOut .= '" />';
				$htmlOut .= '<span class="description"';
				if (!isset($r['invalid']['input_rent_cost']))
					$htmlOut .= ' style="display:none;"';
				$htmlOut .= '>عدد صحیح، حداقل بزرگتر از 0</span>';
				$htmlOut .= '<script type="text/javascript">$(\'#input_rent_cost1\').data(\'num_min\',0).data(\'num_min_inc\',false);</script><br />';
				break;
		}

		$htmlOut .= '<input type="submit" name="submitEditCenterVehicle" value="اصلاح وسیله نقلیه" /></fieldset></form>';

		return $htmlOut;
	}

	public static function addCenterVehicleCharge() {
		$htmlOut = '';
		$htmlOut .= '<p style="text-align: right; font-weight: bold; font-size: 15px; margin-right: 20px"><a href="#" style="color: green;" id="slideLink" ><img src="../illustrator/images/icons/plus.png" />&nbsp;ثبت</a></p>';
		$htmlOut .= '<form action="' . substr(strrchr($_SERVER['PHP_SELF'], '/'), 1) . '" method="post" id="slideForm" class="narin">';
		$htmlOut .= '<input type="hidden" name="narin:token" value="' . Narin::create_token() . '" /><fieldset><legend>افزودن هزینه وسیله نقلیه</legend>';
		$htmlOut .= '<label for="input_charge_name" class="required">نام هزینه:</label>';
		$htmlOut .= '<input type="text" name="input_charge_name" id="input_charge_name" maxlength="255"';
		if (isset($_POST['input_charge_name']) && $_POST['input_charge_name'] !== '')
			$htmlOut .= ' value="' . htmlspecialchars($_POST['input_charge_name']) . '"';
		$htmlOut .= ' class="validate required ';
		if (isset($r['invalid']['input_charge_name']))
			$htmlOut .= ' invalid';
		$htmlOut .= '" />';
		$htmlOut .= '<span class="description"';
		if (!isset($r['invalid']['input_charge_name']))
			$htmlOut .= ' style="display:none;"';
		$htmlOut .= '>فارسی بنویسید</span><br />';
		$htmlOut .= '<label for="input_charge_amount" class="required">مبلغ هزینه (به ریال):</label>';
		$htmlOut .= '<input type="text" name="input_charge_amount" id="input_charge_amount" dir="ltr"';
		if (isset($_POST['input_charge_amount']) && $_POST['input_charge_amount'] !== '')
			$htmlOut .= ' value="' . htmlspecialchars($_POST['input_charge_amount']) . '"';
		$htmlOut .= ' class="validate required integer';
		if (isset($r['invalid']['input_charge_amount']))
			$htmlOut .= ' invalid';
		$htmlOut .= '" />';
		$htmlOut .= '<span class="description"';
		if (!isset($r['invalid']['input_charge_amount']))
			$htmlOut .= ' style="display:none;"';
		$htmlOut .= '>عدد صحیح، حداقل بزرگتر از 0</span>';
		$htmlOut .= '<script type="text/javascript">$(\'#input_charge_amount\').data(\'num_min\',0).data(\'num_min_inc\',false);</script><br />';
		$htmlOut .= '<label for="input_vehicle_id">عنوان وسیله نقلیه:</label>';
		$htmlOut .= '<select name="input_vehicle_id" id="input_vehicle_id" class="validate first_opt_not_allowed ';
		if (isset($r['invalid']['input_vehicle_id']))
			$htmlOut .= 'invalid';
		$htmlOut .= '">';
		$dbRes = Database::execute_query("SELECT `a`.`id`, `a`.`title` FROM `center-vehicles` AS `a` INNER JOIN `centers` AS `b` ON `a`.`center-id` = `b`.`id` WHERE `a`.`center-id` = '" . $_SESSION['centerId'] . "' ORDER BY `title` ASC;");
		if (Database::num_of_rows($dbRes) > 0) {
			$htmlOut .= '<option>انتخاب کنید...</option>';
			while ($dbRow = Database::get_assoc_array($dbRes))
				$htmlOut .= '<option value="' . $dbRow['id'] . '">' . $dbRow['title'] . '</option>';
		} else
			$htmlOut .= '<option>' . NO_CENTER_VEHICLE . '</option>';
		$htmlOut .= '</select><br /><input type="submit" name="submitAddCenterVehicleCharge" value="ثبت هزینه" /></fieldset></form>';
		
		$htmlOut .= '<div class="loading"><img src="../statisticalPlotter/loading.gif" />&nbsp;&nbsp;در حال بارگذاری؛ لطفا صبر کنید...</div><br />';
		$htmlOut .= '<div id="mngDiv"><fieldset><legend>مشاهده ی فهرست هزینه های وسایل نقلیه مرکز "' . self::getName() . '"</legend>';
		$res = Database::execute_query("SELECT `id`, `title` FROM `center-vehicles` WHERE `center-id` = '" . self::$id . "';");
		$htmlOut .= '<br /><label for="input_vehicle_id_1" class="required">عنوان وسیله نقلیه:</label>';
		$htmlOut .= '<select name="input_vehicle_id_1" id="input_vehicle_id_1" class="validate first_opt_not_allowed">';
		if (Database::num_of_rows($res) > 0) {
			$htmlOut .= '<option>انتخاب کنید...</option>';
			while ($row = Database::get_assoc_array($res))
				$htmlOut .= '<option value="' . $row['id'] . '">' . $row['title'] . '</option>';
		} else
			$htmlOut .= '<option>' . NO_CENTER_VEHICLE . '</option>';
		$htmlOut .= '</select><br />';
		$htmlOut .= '<div id="list"></div><br /></fieldset>';
		$htmlOut .= '</div>';
		$htmlOut .= '<script type="text/javascript">';
		$htmlOut .= '$(document).ajaxStart(function() {$(\'.loading\').show();$(\'#mngDiv\').css(\'opacity\', \'0.3\');});' . '$(document).ajaxSuccess(function() {$(\'.loading\').hide();$(\'#mngDiv\').css(\'opacity\', \'1\');});';
		$htmlOut .= '$(\'#list\').load(\'./ea-sh-ajax.php\', {\'vehicleIdForVehicleCharge\' : $(\'#input_vehicle_id_1 option:selected\').val()});';
		$htmlOut .= '$(\'#input_vehicle_id_1\').change(function() {$(\'#list\').load(\'./ea-sh-ajax.php\', {\'vehicleIdForVehicleCharge\' : $(\'#input_vehicle_id_1 option:selected\').val()});});';
		$htmlOut .= '</script>';

		return $htmlOut;
	}

	public static function removeCenterVehicleCharge($id) {
		//_log process
		try {
			$resU = Database::execute_query("SELECT * FROM `center-vehicle-maintain-charges` WHERE `id` = '$id';");
			$rowU = Database::get_assoc_array($resU);
			$resI = Database::execute_query("SELECT `title`, `center-id` FROM `center-vehicles` WHERE `id` = '" . $rowU['vehicle-id'] . "';");
			$rowI = Database::get_assoc_array($resI);
			$resC = Database::execute_query("SELECT `name`, `town-id` FROM `centers` WHERE `id` = '" . $rowI['center-id'] . "';");
			$rowC = Database::get_assoc_array($resC);
			$res = Database::execute_query("SELECT `name` FROM `towns` WHERE `id` = '" . $rowC['town-id'] . "';");
			$row = Database::get_assoc_array($res);
		} catch ( exception $e ) {
			return $e -> getMessage();
		}
		$_logTXT = 'حذف هزینه "' . $rowU['charge-name'] . '" از وسیله نقلیه "' . $rowI['title'] . '" در مرکز "' . $rowC['name'] . '" در شهرستان "' . $row['name'] . '"';
		
		try {
			Database::execute_query("DELETE FROM `center-vehicle-maintain-charges` WHERE `id` = '" . $id . "';");
		} catch ( exception $e ) {
			return $e -> getMessage();
		}
		FormProccessor::_makeLog($_logTXT);
		
		header("Location: " . $_SERVER['HTTP_REFERER']);
		exit;
		return true;
	}

	public static function editCenterVehicleCharge($id) {
		$htmlOut = '';
		if (strpos($_SERVER['HTTP_REFERER'], 'es'))
			$_SESSION['ref'] = 1;
		else
			$_SESSION['ref'] = 0;
		$res = Database::execute_query("SELECT `charge-name`, `charge-amount` FROM `center-vehicle-maintain-charges` WHERE `id` = '$id';");
		$row = Database::get_assoc_array($res);
		$htmlOut .= '<form action="' . substr(strrchr($_SERVER['PHP_SELF'], '/'), 1) . '" method="post" class="narin">';
		$htmlOut .= '<input type="hidden" name="id" value="' . $id . '" />';
		$htmlOut .= '<input type="hidden" name="narin:token" value="' . Narin::create_token() . '" /><fieldset><legend>اصلاح هزینه وسیله نقلیه</legend>';
		$htmlOut .= '<label for="input_charge_name" class="required">نام هزینه:</label>';
		$htmlOut .= '<input type="text" name="input_charge_name" id="input_charge_name" maxlength="255" value="' . $row['charge-name'] . '"';
		if (isset($_POST['input_charge_name']) && $_POST['input_charge_name'] !== '')
			$htmlOut .= ' value="' . htmlspecialchars($_POST['input_charge_name']) . '"';
		$htmlOut .= ' class="validate required ';
		if (isset($r['invalid']['input_charge_name']))
			$htmlOut .= ' invalid';
		$htmlOut .= '" />';
		$htmlOut .= '<span class="description"';
		if (!isset($r['invalid']['input_charge_name']))
			$htmlOut .= ' style="display:none;"';
		$htmlOut .= '>فارسی بنویسید</span><br />';
		$htmlOut .= '<label for="input_charge_amount" class="required">مبلغ هزینه (به ریال):</label>';
		$htmlOut .= '<input type="text" name="input_charge_amount" id="input_charge_amount" dir="ltr" value="' . $row['charge-amount'] . '"';
		if (isset($_POST['input_charge_amount']) && $_POST['input_charge_amount'] !== '')
			$htmlOut .= ' value="' . htmlspecialchars($_POST['input_charge_amount']) . '"';
		$htmlOut .= ' class="validate required integer';
		if (isset($r['invalid']['input_charge_amount']))
			$htmlOut .= ' invalid';
		$htmlOut .= '" />';
		$htmlOut .= '<span class="description"';
		if (!isset($r['invalid']['input_charge_amount']))
			$htmlOut .= ' style="display:none;"';
		$htmlOut .= '>عدد صحیح، حداقل بزرگتر از 0</span>';
		$htmlOut .= '<script type="text/javascript">$(\'#input_charge_amount\').data(\'num_min\',0).data(\'num_min_inc\',false);</script><br />';
		$htmlOut .= '<input type="submit" name="submitEditCenterVehicleCharge" value="اصلاح هزینه" /></fieldset></form>';

		return $htmlOut;
	}

	public static function addCenterGeneralCharge() {
		$htmlOut = '';
		$htmlOut .= '<p style="text-align: right; font-weight: bold; font-size: 15px; margin-right: 20px"><a href="#" style="color: green;" id="slideLink" ><img src="../illustrator/images/icons/plus.png" />&nbsp;ثبت</a></p>';
		$htmlOut .= '<form action="' . substr(strrchr($_SERVER['PHP_SELF'], '/'), 1) . '" method="post" id="slideForm" class="narin">';
		$htmlOut .= '<input type="hidden" name="narin:token" value="' . Narin::create_token() . '" /><fieldset><legend>ثبت هزینه برای مرکز</legend>';
		$htmlOut .= '<label for="input_charge_name">نام هزینه:</label>';
		$htmlOut .= '<select name="input_charge_name" id="input_charge_name" class="validate first_opt_not_allowed ';
		if (isset($r['invalid']['input_charge_name']))
			$htmlOut .= 'invalid';
		$htmlOut .= '">';
		$dbRes = Database::execute_query("SELECT `id`, `type` FROM `general-charge-types` ORDER BY `type` ASC;");
		if (Database::num_of_rows($dbRes) > 0) {
			$htmlOut .= '<option>انتخاب کنید...</option>';
			while ($dbRow = Database::get_assoc_array($dbRes))
				$htmlOut .= '<option value="' . $dbRow['id'] . '">' . $dbRow['type'] . '</option>';
		} else
			$htmlOut .= '<option>' . NO_AVAILABLE_GENERALCHARGETYPE . '</option>';
		$htmlOut .= '</select><br />';
		$htmlOut .= '<label for="input_charge_amount" class="required">مبلغ هزینه:</label>';
		$htmlOut .= '<input type="text" name="input_charge_amount" id="input_charge_amount" dir="ltr"';
		if (isset($_POST['input_charge_amount']) && $_POST['input_charge_amount'] !== '')
			$htmlOut .= ' value="' . htmlspecialchars($_POST['input_charge_amount']) . '"';
		$htmlOut .= ' class="validate required integer';
		if (isset($r['invalid']['input_charge_amount']))
			$htmlOut .= ' invalid';
		$htmlOut .= '" />';
		$htmlOut .= '<span class="description"';
		if (!isset($r['invalid']['input_charge_amount']))
			$htmlOut .= ' style="display:none;"';
		$htmlOut .= '>عدد صحیح، حداقل بزرگتر از 0</span>';
		$htmlOut .= '<script type="text/javascript">$(\'#input_charge_amount\').data(\'num_min\',0).data(\'num_min_inc\',false);</script><br />';
		$htmlOut .= '<input value="تاریخ" onclick="displayDatePicker(\'input_date\');" type="button"><input name="input_date" type="text" style="direction: ltr;" readonly="readonly" /><br />';
		$htmlOut .= '<label for="input_charge_cm" class="labelbr">توضیحات درمورد پرداخت و طول دوره:</label>';
		$htmlOut .= '<span class="description">فارسی بنویسید</span><br />';
		$htmlOut .= '<textarea name="input_charge_cm" id="input_charge_cm" rows="4" cols="40" class="validate ';
		if (isset($r['invalid']['input_charge_cm']))
			$htmlOut .= ' invalid';
		$htmlOut .= '">';
		if (isset($_POST['input_charge_cm']))
			$htmlOut .= htmlspecialchars($_POST['input_charge_cm']);
		$htmlOut .= '</textarea><br /><input type="submit" name="submitAddCenterGeneralCharge" value="ثبت هزینه" /></fieldset></form>';

		/************************************************************************************************************************************/

		$res = Database::execute_query("SELECT `a`.`type`, `b`.`id`, `b`.`charge-amount`, `b`.`date` FROM `general-charge-types` AS `a` INNER JOIN `center-general-charges` AS `b` ON `a`.`id` = `b`.`charge-id` WHERE `center-id` = '" . $_SESSION['centerId'] . "' ORDER BY `date` ASC;");
		$counter = 1;
		if (Database::num_of_rows($res) > 0) {
			$htmlOut .= '<br /><fieldset><legend>فهرست هزینه های عمومی ثبت شده مرکز "' . self::getName() . '"</legend>';
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
			$htmlOut .= '<p class="warning">هیچ هزینه ای برای مرکز "' . self::getName() . '" ثبت نشده است.</p>';

		return $htmlOut;
	}

	public static function removeCenterGeneralCharge($id) {
		//_log process
		try {
			$resU = Database::execute_query("SELECT * FROM `center-general-charges` WHERE `id` = '$id';");
			$rowU = Database::get_assoc_array($resU);
			$resI = Database::execute_query("SELECT `type` FROM `general-charge-types` WHERE `id` = '" . $rowU['charge-id'] . "';");
			$rowI = Database::get_assoc_array($resI);
			$resC = Database::execute_query("SELECT `name`, `town-id` FROM `centers` WHERE `id` = '" . $rowU['center-id'] . "';");
			$rowC = Database::get_assoc_array($resC);
			$res = Database::execute_query("SELECT `name` FROM `towns` WHERE `id` = '" . $rowC['town-id'] . "';");
			$row = Database::get_assoc_array($res);
		} catch ( exception $e ) {
			return $e -> getMessage();
		}
		$_logTXT = 'حذف هزینه عمومی "' . $rowI['type'] . '" به تاریخ "' . $rowU['date'] .  '" از مرکز "' . $rowC['name'] . '" در شهرستان "' . $row['name'] . '"';
		
		try {
			Database::execute_query("DELETE FROM `center-general-charges` WHERE `id` = '" . $id . "';");
		} catch ( exception $e ) {
			return $e -> getMessage();
		}
		FormProccessor::_makeLog($_logTXT);
		
		header("Location: " . $_SERVER['HTTP_REFERER']);
		exit;
		return true;
	}

	public static function editCenterGeneralCharge($id) {
		$htmlOut = '';
		if (strpos($_SERVER['HTTP_REFERER'], 'es'))
			$_SESSION['ref'] = 1;
		else
			$_SESSION['ref'] = 0;
		$res = Database::execute_query("SELECT `a`.`type`, `b`.`charge-amount`, `b`.`comment`, `b`.`date` FROM `general-charge-types` AS `a` INNER JOIN `center-general-charges` AS `b` ON `a`.`id` = `b`.`charge-id` WHERE `b`.`id` = '$id' ORDER BY `date` ASC;");
		$row = Database::get_assoc_array($res);
		$htmlOut .= '<form action="' . substr(strrchr($_SERVER['PHP_SELF'], '/'), 1) . '" method="post" class="narin">';
		$htmlOut .= '<input type="hidden" name="chargeId" value="' . $id . '" />';
		$htmlOut .= '<input type="hidden" name="narin:token" value="' . Narin::create_token() . '" /><fieldset><legend>اصلاح هزینه برای مرکز</legend>';
		$htmlOut .= '<input value="تاریخ" onclick="displayDatePicker(\'input_date\');" type="button"><input name="input_date" type="text" value="' . $row['date'] . '" style="direction: ltr;" readonly="readonly" /><br />';
		$htmlOut .= '<label for="input_charge_amount" class="required">مبلغ هزینه:</label>';
		$htmlOut .= '<input type="text" name="input_charge_amount" id="input_charge_amount" dir="ltr" value="' . $row['charge-amount'] . '"';
		if (isset($_POST['input_charge_amount']) && $_POST['input_charge_amount'] !== '')
			$htmlOut .= ' value="' . htmlspecialchars($_POST['input_charge_amount']) . '"';
		$htmlOut .= ' class="validate required integer';
		if (isset($r['invalid']['input_charge_amount']))
			$htmlOut .= ' invalid';
		$htmlOut .= '" />';
		$htmlOut .= '<span class="description"';
		if (!isset($r['invalid']['input_charge_amount']))
			$htmlOut .= ' style="display:none;"';
		$htmlOut .= '>عدد صحیح، حداقل بزرگتر از 0</span>';
		$htmlOut .= '<script type="text/javascript">$(\'#input_charge_amount\').data(\'num_min\',0).data(\'num_min_inc\',false);</script><br />';
		$htmlOut .= '<label for="input_charge_cm" class="labelbr">توضیحات درمورد پرداخت و طول دوره:</label>';
		$htmlOut .= '<span class="description">فارسی بنویسید</span><br />';
		$htmlOut .= '<textarea name="input_charge_cm" id="input_charge_cm" rows="4" cols="40" class="validate ';
		if (isset($r['invalid']['input_charge_cm']))
			$htmlOut .= ' invalid';
		$htmlOut .= '">';
		if (isset($_POST['input_charge_cm']))
			$htmlOut .= htmlspecialchars($_POST['input_charge_cm']);
		$htmlOut .= $row['comment'] . '</textarea><br /><input type="submit" name="submitEditCenterGeneralCharge" value="اصلاح هزینه" /></fieldset></form>';

		return $htmlOut;
	}

	public static function viewActionsMenu() {
		$htmlOut = '';
		if ($_SESSION['user']['acl'] == 'State Manager')
			$htmlOut .= '<a href="' . $_SERVER['PHP_SELF'] . '"><img src="../illustrator/images/icons/home.png" />&nbsp;صفحه‌اصلی</a>';
		if ($_SESSION['user']['acl'] == 'Town Manager')
			$htmlOut .= '<a href="' . substr(strrchr($_SERVER['PHP_SELF'], '/'), 1) . '?cmd=manageCenters&townId=' . $_SESSION['user']['acl-id'] . '"><img src="../illustrator/images/icons/home.png" />&nbsp;صفحه‌اصلی</a>';
		if ($_SESSION['user']['acl'] == 'Center Manager')
			$htmlOut .= '<a href="' . substr(strrchr($_SERVER['PHP_SELF'], '/'), 1) . '?cmd=manageUnits&centerId=' . $_SESSION['user']['acl-id'] . '"><img src="../illustrator/images/icons/home.png" />&nbsp;صفحه‌اصلی</a>';
		$htmlOut .= '<a href="?cmd=manageCenterDrugs"><img src="../illustrator/images/icons/drug.png" />&nbsp;داروهای مرکز</a>';
		$htmlOut .= '<a href="?cmd=manageCenterConsumingEquipments"><img src="../illustrator/images/icons/enjektor.png" />&nbsp;تجهیزات مصرفی مرکز</a>';
		$htmlOut .= '<a href="?cmd=addCenterNonConsumingEquipments"><img src="../illustrator/images/icons/non_consuming.png" />&nbsp;تجهیزات غیرمصرفی مرکز</a>';
		$htmlOut .= '<a href="?cmd=manageCenterBuildings"><img src="../illustrator/images/icons/office-building.png" />&nbsp;مدیریت ساختمان‌ها</a>';
		$htmlOut .= '<a href="?cmd=addCenterBuildingCharge"><img src="../illustrator/images/icons/office-building.png" />&nbsp;هزینه نگهداری ساختمان</a>';
		$htmlOut .= '<a href="?cmd=manageCenterVehicles"><img src="../illustrator/images/icons/CarRepairIcon.png" />&nbsp;مدیریت وسایل نقلیه</a>';
		$htmlOut .= '<a href="?cmd=addCenterVehicleCharge"><img src="../illustrator/images/icons/CarRepairIcon.png" />&nbsp;ثبت هزینه وسیله نقلیه</a>';
		$htmlOut .= '<a href="?cmd=addCenterGeneralCharge"><img src="../illustrator/images/icons/charge.png" />&nbsp;ثبت هزینه عمومی</a>';
		if ($_SESSION['user']['acl'] == 'State Manager' or $_SESSION['user']['acl'] == 'Town Manager')
			$htmlOut .= '<a href="?cmd=manageCenters&townId=' . $_SESSION['townId'] . '"><img src="../illustrator/images/icons/back.png" />&nbsp;بازگشت</a>';
		$htmlOut .= '<a href="?cmd=logOut"><img src="../illustrator/images/icons/exit.png" />&nbsp;خروج از سامانه</a>';

		return $htmlOut;
	}

}
?>