<?php

/**
 *
 * hygiene-unit.php
 * hygiene unit model class file
 *
 * @copyright	Copyright (C) 2012 Rivas Systems Inc. All rights reserved.
 * @versin	1.00 2011-02-05
 * @author	Behnam Salili
 *
 */

require_once dirname(dirname(__file__)) . '/database/database.php';
require_once dirname(dirname(__file__)) . '/form/form-proccessor.php';

class HygieneUnit {

	private static $id;

	public static function setId($id) {
		self::$id = $id;
	}

	public static function getName() {
		$res = Database::execute_query("SELECT `name` FROM `hygiene-units` WHERE `id` = '" . self::$id . "';");
		$tmp = Database::get_assoc_array($res);
		return $tmp['name'];
	}

	public static function viewActionsMenu() {
		$htmlOut = '';
		switch ( $_SESSION['user']['acl'] ) {
			case 'State Manager' :
				$htmlOut .= '<a href="' . $_SERVER['PHP_SELF'] . '"><img src="../illustrator/images/icons/home.png" />&nbsp;صفحه‌اصلی</a>';
				break;
			case 'Town Manager' :
				$htmlOut .= '<a href="' . substr(strrchr($_SERVER['PHP_SELF'], '/'), 1) . '?cmd=manageCenters&townId=' . $_SESSION['user']['acl-id'] . '"><img src="../illustrator/images/icons/home.png" />&nbsp;صفحه‌اصلی</a>';
				break;
			case 'Hygiene Unit Manager':
				$htmlOut .= '<a href="' . substr(strrchr($_SERVER['PHP_SELF'], '/'), 1) . '?cmd=modifyHygieneUnits&hygieneUnitId=' . $_SESSION['user']['acl-id'] . '"><img src="../illustrator/images/icons/home.png" />&nbsp;صفحه‌اصلی</a>';
				break;
		}
		$htmlOut .= '<a href="?cmd=addHygieneUnitDrugs"><img src="../illustrator/images/icons/drug.png" />&nbsp;داروها</a>';
		$htmlOut .= '<a href="?cmd=addHygieneUnitConsumingEquipments"><img src="../illustrator/images/icons/enjektor.png" />&nbsp;تجهیزات مصرفی</a>';
		$htmlOut .= '<a href="?cmd=addHygieneUnitNonConsumingEquipments"><img src="../illustrator/images/icons/non_consuming.png" />&nbsp;تجهیزات غیرمصرفی</a>';
		$htmlOut .= '<a href="?cmd=manageHygieneUnitPersonnel"><img src="../illustrator/images/icons/personnel.png" />&nbsp;مدیریت بهورزان</a>';
		$htmlOut .= '<a href="?cmd=hygieneUnitPersonnelFinancialInfo"><img src="../illustrator/images/icons/charge.png" />&nbsp;اطلاعات مالی بهورزان</a>';
		$htmlOut .= '<a href="?cmd=manageHygieneUnitBuildings"><img src="../illustrator/images/icons/office-building.png" />&nbsp;مدیریت ساختمان‌ها</a>';
		$htmlOut .= '<a href="?cmd=addHygieneUnitBuildingCharge"><img src="../illustrator/images/icons/charge.png" />&nbsp;هزینه نگهداری ساختمان</a>';
		$htmlOut .= '<a href="?cmd=manageHygieneUnitVehicles"><img src="../illustrator/images/icons/CarRepairIcon.png" />&nbsp;مدیریت وسایل نقلیه</a>';
		$htmlOut .= '<a href="?cmd=addHygieneUnitVehicleCharge"><img src="../illustrator/images/icons/charge.png" />&nbsp;ثبت هزینه وسیله نقلیه</a>';
		$htmlOut .= '<a href="?cmd=addHygieneUnitGeneralCharge"><img src="../illustrator/images/icons/charge.png" />&nbsp;ثبت هزینه عمومی</a>';
		$htmlOut .= '<a href="?cmd=manageHygieneUnitServices"><img src="../illustrator/images/icons/service.png" />&nbsp;مدیریت خدمات</a>';
		if ($_SESSION['user']['acl'] == 'State Manager' or $_SESSION['user']['acl'] == 'Town Manager')
			$htmlOut .= '<a href="?cmd=manageUnits&centerId=' . $_SESSION['centerId'] . '"><img src="../illustrator/images/icons/back.png" />&nbsp;بازگشت</a>';
		$htmlOut .= '<a href="?cmd=logOut"><img src="../illustrator/images/icons/exit.png" />&nbsp;خروج از سامانه</a>';

		return $htmlOut;
	}

	public static function manageHygieneUnitBehvarz() {
		$htmlOut = '';
		$htmlOut .= '<p>در این قسمت می توانید بهورزان خانه بهداشت "' . self::getName() . '" را مدیریت نمایید.</p>';
		$htmlOut .= '<p style="text-align: right; font-weight: bold; font-size: 15px; margin-right: 20px"><a href="#" style="color: green;" id="slideLink" ><img src="../illustrator/images/icons/plus.png" />&nbsp;افزودن بهورز</a></p>';
		$htmlOut .= '<form action="' . substr(strrchr($_SERVER['PHP_SELF'], '/'), 1) . '" method="post" id="slideForm" class="narin">';
		$htmlOut .= '<input type="hidden" name="narin:token" value="' . Narin::create_token() . '" /><fieldset><legend>افزودن بهورز</legend>';
		$htmlOut .= '<label for="input_name" class="required">نام:</label>';
		$htmlOut .= '<input type="text" name="input_name" id="input_name" maxlength="255"';
		if (isset($_POST['input_name']) && $_POST['input_name'] !== '')
			$htmlOut .= ' value="' . htmlspecialchars($_POST['input_name']) . '"';
		$htmlOut .= ' class="validate required ';
		if (isset($r['invalid']['input_name']))
			$htmlOut .= ' invalid';
		$htmlOut .= '" />';
		$htmlOut .= '<span class="description"';
		if (!isset($r['invalid']['input_name']))
			$htmlOut .= ' style="display:none;"';
		$htmlOut .= '>فقط حروف فارسی</span><br />';

		$htmlOut .= '<label for="input_lastname" class="required">نام خانوادگی:</label>';
		$htmlOut .= '<input type="text" name="input_lastname" id="input_lastname" maxlength="255"';
		if (isset($_POST['input_lastname']) && $_POST['input_lastname'] !== '')
			$htmlOut .= ' value="' . htmlspecialchars($_POST['input_lastname']) . '"';
		$htmlOut .= ' class="validate required ';
		if (isset($r['invalid']['input_lastname']))
			$htmlOut .= ' invalid';
		$htmlOut .= '" />';
		$htmlOut .= '<span class="description"';
		if (!isset($r['invalid']['input_lastname']))
			$htmlOut .= ' style="display:none;"';
		$htmlOut .= '>فقط حروف فارسی</span><br />';
		$htmlOut .= '<label for="input_last_ed_deg" class="required">آخرین مدرک تحصیلی:</label>';
		$htmlOut .= '<input type="text" name="input_last_ed_deg" id="input_last_ed_deg" maxlength="255"';
		if (isset($_POST['input_last_ed_deg']) && $_POST['input_last_ed_deg'] !== '')
			$htmlOut .= ' value="' . htmlspecialchars($_POST['input_last_ed_deg']) . '"';
		$htmlOut .= ' class="validate required ';
		if (isset($r['invalid']['input_last_ed_deg']))
			$htmlOut .= ' invalid';
		$htmlOut .= '" />';
		$htmlOut .= '<span class="description"';
		if (!isset($r['invalid']['input_last_ed_deg']))
			$htmlOut .= ' style="display:none;"';
		$htmlOut .= '>فقط حروف فارسی</span><br />';
		$htmlOut .= '<div id="date_picker_div" class="date_picker';
        if ( isset( $r['invalid']['input_age'] ) )
            $htmlOut .= ' invalid';
        $htmlOut .= '">';
        $htmlOut .= '<label id="date_picker_label" for="input_age">سال تولد:</label>';
        $htmlOut .= '<input type="text" id="input_age" name="input_age" size="4" maxlength="4" class="date_y"';
        if ( isset( $_POST['input_age'] ) && $_POST['input_age'] !== '' )
            $htmlOut .= ' value="' . htmlspecialchars( $_POST['input_age'] ) . '"';
        $htmlOut .= ' /></div><br />';
		$htmlOut .= '<label for="input_base_salary" class="required">حقوق کل:</label>';
		$htmlOut .= '<input type="text" name="input_base_salary" id="input_base_salary" dir="ltr"';
		if (isset($_POST['input_base_salary']) && $_POST['input_base_salary'] !== '')
			$htmlOut .= ' value="' . htmlspecialchars($_POST['input_base_salary']) . '"';
		$htmlOut .= ' class="validate required integer';
		if (isset($r['invalid']['input_base_salary']))
			$htmlOut .= ' invalid';
		$htmlOut .= '" />';
		$htmlOut .= '<span class="description"';
		if (!isset($r['invalid']['input_base_salary']))
			$htmlOut .= ' style="display:none;"';
		$htmlOut .= '>عدد صحیح، حداقل بزرگتر از 0</span>';
		$htmlOut .= '<script type="text/javascript">$(\'#input_base_salary\').data(\'num_min\',0).data(\'num_min_inc\',false);</script><br />';
		$htmlOut .= '<label for="input_work-bg">سابقه کار (به ماه):</label>';
		$htmlOut .= '<input type="text" name="input_work-bg" id="input_work-bg" maxlength="3" dir="ltr"';
		if (isset($_POST['input_work-bg']) && $_POST['input_work-bg'] !== '')
			$htmlOut .= ' value="' . htmlspecialchars($_POST['input_work-bg']) . '"';
		$htmlOut .= ' class="validate integer';
		if (isset($r['invalid']['input_work-bg']))
			$htmlOut .= ' invalid';
		$htmlOut .= '" />';
		$htmlOut .= '<span class="description"';
		if (!isset($r['invalid']['input_work-bg']))
			$htmlOut .= ' style="display:none;"';
		$htmlOut .= '>عدد قابل قبول نیست</span>';
		$htmlOut .= '<script type="text/javascript">$(\'#input_work-bg\').data(\'num_min\',0).data(\'num_min_inc\',false).data(\'num_max\',360).data(\'num_max_inc\',true);</script><br />';
		$htmlOut .= '<label for="input_sex">جنسیت:</label>';
		$htmlOut .= '<select name="input_sex" id="input_sex" class="';
		if (isset($r['invalid']['input_sex']))
			$htmlOut .= 'invalid';
		$htmlOut .= '">';
		$htmlOut .= "\n<option value=\"female\"";
		if (isset($_POST['input_sex']) && $_POST['input_sex'] == '0')
			$htmlOut .= ' selected="selected"';
		$htmlOut .= '>زن</option>';
		$htmlOut .= "\n<option value=\"male\"";
		if (isset($_POST['input_sex']) && $_POST['input_sex'] == '1')
			$htmlOut .= ' selected="selected"';
		$htmlOut .= '>مرد</option>';
		$htmlOut .= '</select><br />';
		$htmlOut .= '<label for="input_jobtitle">عنوان شغلی:</label>';
		$htmlOut .= '<select name="input_jobtitle" id="input_jobtitle" class="';
		if (isset($r['invalid']['input_jobtitle']))
			$htmlOut .= 'invalid';
		$htmlOut .= '">';
		$htmlOut .= '<option value="بهورز">بهورز</option>';
		$htmlOut .= '</select><br />';
		$htmlOut .= '<br /><input type="submit" value="ثبت بهورز" name="submitAddHygieneUnitPersonnel" /></fieldset></form>';

		$res = Database::execute_query("SELECT `id`, `name`, `lastname`, `job-title` FROM  `hygiene-unit-personnels` WHERE `deleted` = '0' AND `hygiene-unit-id` = '" . Database::filter_str($_SESSION['hygieneUnitId']) . "' AND `job-title` = 'بهورز' ORDER BY `lastname` ASC;");
		$counter = 1;
		if (Database::num_of_rows($res) > 0) {
			$htmlOut .= '<div><fieldset><legend>فهرست بهورزان "' . self::getName() . '"</legend>';
			$htmlOut .= '<br /><table align="right" class="contentTable" border="0" cellpadding="0" cellspacing="1">';
			$htmlOut .= '<thead><tr><th width="40">ردیف</th><th>نام</th><th>نام خانوادگی</th><th width="100">رده پرسنلی</th><th width="50">حذف</th><th width="50">ویرایش</th></thead><tbody>';
			while ($row = Database::get_assoc_array($res)) {
				$htmlOut .= '<tr><td>' . $counter . '</td><td>' . $row['name'] . '</td>';
				$htmlOut .= '<td>' . $row['lastname'] . '</td>';
				$htmlOut .= '<td>' . $row['job-title'] . '</td>';
				$htmlOut .= '<td><a href="?cmd=removeHygieneUnitPersonnel&hygieneUnitPersonnelId=' . $row['id'] . '"><img src="../illustrator/images/icons/remove.png" title="حذف ' . $row['name'] . '" /></a></td>';
				$htmlOut .= '<td><a href="?cmd=editHygieneUnitPersonnel&hygieneUnitPersonnelId=' . $row['id'] . '"><img src="../illustrator/images/icons/edit.png" title="ویرایش ' . $row['name'] . '" /></a></td></tr>';
				$counter++;
			}

			$htmlOut .= '</tbody></table></fielset></div>';
		} else
			$htmlOut .= NO_UNIT_PERSONNEL;

		return $htmlOut;
	}

	public static function removeHygieneUnitBehvarz($id) {
		//_log process
		try {
			$resP = Database::execute_query("SELECT `hygiene-unit-id`, `name`, `lastname` FROM `hygiene-unit-personnels` WHERE `id` = '$id';");
			$rowP = Database::get_assoc_array($resP);
			$resU = Database::execute_query("SELECT `name`, `center-id` FROM `hygiene-units` WHERE `id` = '" . $rowP['hygiene-unit-id'] . "';");
			$rowU = Database::get_assoc_array($resU);
			$resC = Database::execute_query("SELECT `name`, `town-id` FROM `centers` WHERE `id` = '" . $rowU['center-id'] . "';");
			$rowC = Database::get_assoc_array($resC);
			$res = Database::execute_query("SELECT `name` FROM `towns` WHERE `id` = '" . $rowC['town-id'] . "';");
			$row = Database::get_assoc_array($res);
		} catch ( exception $e ) {
			return array('err_msg' => $e -> getMessage());
		}
		$_logTXT = 'حذف بهورز "' . $rowP['name'] . ' ' . $rowP['lastname'] . '" از خانه‌بهداشت "' . $rowU['name'] . '" در مرکز "' . $rowC['name'] . '" در شهرستان "' . $row['name'] . '"';
		
		$dateTimeObj = new DateTime();
		$dateTimeZone = new DateTimeZone('Asia/Tehran');
		$dateTimeObj -> setTimezone($dateTimeZone);
		$shamsiDate = gregorian_to_jalali($dateTimeObj -> format('Y'), $dateTimeObj -> format('m'), $dateTimeObj -> format('d'));
		$date = $shamsiDate[0] . "-" . $shamsiDate[1] . "-" . $shamsiDate[2] . " " . $dateTimeObj -> format('H:i:s');
		try {
			Database::execute_query("UPDATE `hygiene-unit-personnels` SET `deleted` = '1', `date-of-delete` = '$date' WHERE `id` = '" . $id . "';");
			unset($dateTimeObj, $dateTimeZone, $date, $shamsiDate);
		} catch ( exception $e ) {
			return $e -> getMessage();
		}
		FormProccessor::_makeLog($_logTXT);
		
		header("Location: " . $_SERVER['HTTP_REFERER']);
		exit;
		return true;
	}

	public static function editHygieneUnitBehvarz($id) {
		$htmlOut = '';
		if (strpos($_SERVER['HTTP_REFERER'], 'es'))
			$_SESSION['ref'] = 1;
		else
			$_SESSION['ref'] = 0;
		$res = Database::execute_query("SELECT * FROM `hygiene-unit-personnels` WHERE `id` = '$id';");
		$row = Database::get_assoc_array($res);
		$htmlOut .= '<form action="' . substr(strrchr($_SERVER['PHP_SELF'], '/'), 1) . '" method="post" class="narin">';
		$htmlOut .= '<input type="hidden" name="id" value="' . $id . '" />';
		$htmlOut .= '<input type="hidden" name="narin:token" value="' . Narin::create_token() . '" /><fieldset><legend>ویرایش بهورز</legend>';
		$htmlOut .= '<label for="input_name" class="required">نام:</label>';
		$htmlOut .= '<input type="text" name="input_name" id="input_name" maxlength="255" value="' . $row['name'] . '"';
		if (isset($_POST['input_name']) && $_POST['input_name'] !== '')
			$htmlOut .= ' value="' . htmlspecialchars($_POST['input_name']) . '"';
		$htmlOut .= ' class="validate required ';
		if (isset($r['invalid']['input_name']))
			$htmlOut .= ' invalid';
		$htmlOut .= '" />';
		$htmlOut .= '<span class="description"';
		if (!isset($r['invalid']['input_name']))
			$htmlOut .= ' style="display:none;"';
		$htmlOut .= '>فقط حروف فارسی</span><br />';

		$htmlOut .= '<label for="input_lastname" class="required">نام خانوادگی:</label>';
		$htmlOut .= '<input type="text" name="input_lastname" id="input_lastname" maxlength="255" value="' . $row['lastname'] . '"';
		if (isset($_POST['input_lastname']) && $_POST['input_lastname'] !== '')
			$htmlOut .= ' value="' . htmlspecialchars($_POST['input_lastname']) . '"';
		$htmlOut .= ' class="validate required ';
		if (isset($r['invalid']['input_lastname']))
			$htmlOut .= ' invalid';
		$htmlOut .= '" />';
		$htmlOut .= '<span class="description"';
		if (!isset($r['invalid']['input_lastname']))
			$htmlOut .= ' style="display:none;"';
		$htmlOut .= '>فقط حروف فارسی</span><br />';
		$htmlOut .= '<label for="input_last_ed_deg" class="required">آخرین مدرک تحصیلی:</label>';
		$htmlOut .= '<input type="text" name="input_last_ed_deg" id="input_last_ed_deg" maxlength="255" value="' . $row['last-educational-degree'] . '"';
		if (isset($_POST['input_last_ed_deg']) && $_POST['input_last_ed_deg'] !== '')
			$htmlOut .= ' value="' . htmlspecialchars($_POST['input_last_ed_deg']) . '"';
		$htmlOut .= ' class="validate required ';
		if (isset($r['invalid']['input_last_ed_deg']))
			$htmlOut .= ' invalid';
		$htmlOut .= '" />';
		$htmlOut .= '<span class="description"';
		if (!isset($r['invalid']['input_last_ed_deg']))
			$htmlOut .= ' style="display:none;"';
		$htmlOut .= '>فقط حروف فارسی</span><br />';
		$htmlOut .= '<div id="date_picker_div" class="date_picker';
        if ( isset( $r['invalid']['input_age'] ) )
            $htmlOut .= ' invalid';
        $htmlOut .= '">';
        $htmlOut .= '<label id="date_picker_label" for="input_age">سال تولد:</label>';
        $htmlOut .= '<input type="text" id="input_age" name="input_age" size="4" maxlength="4" class="date_y" value="' . $row['age'] . '"';
        if ( isset( $_POST['input_age'] ) && $_POST['input_age'] !== '' )
            $htmlOut .= ' value="' . htmlspecialchars( $_POST['input_age'] ) . '"';
        $htmlOut .= ' /></div><br />';
		$htmlOut .= '<label for="input_base_salary" class="required">حقوق کل:</label>';
		$htmlOut .= '<input type="text" name="input_base_salary" id="input_base_salary" dir="ltr" value="' . $row['base-salary'] . '"';
		if (isset($_POST['input_base_salary']) && $_POST['input_base_salary'] !== '')
			$htmlOut .= ' value="' . htmlspecialchars($_POST['input_base_salary']) . '"';
		$htmlOut .= ' class="validate required integer';
		if (isset($r['invalid']['input_base_salary']))
			$htmlOut .= ' invalid';
		$htmlOut .= '" />';
		$htmlOut .= '<span class="description"';
		if (!isset($r['invalid']['input_base_salary']))
			$htmlOut .= ' style="display:none;"';
		$htmlOut .= '>عدد صحیح، حداقل بزرگتر از 0</span>';
		$htmlOut .= '<script type="text/javascript">$(\'#input_base_salary\').data(\'num_min\',0).data(\'num_min_inc\',false);</script><br />';
		$htmlOut .= '<label for="input_work-bg">سابقه کار (به ماه):</label>';
		$htmlOut .= '<input type="text" name="input_work-bg" id="input_work-bg" maxlength="3" dir="ltr" value="' . $row['work-background'] . '"';
		if (isset($_POST['input_work-bg']) && $_POST['input_work-bg'] !== '')
			$htmlOut .= ' value="' . htmlspecialchars($_POST['input_work-bg']) . '"';
		$htmlOut .= ' class="validate integer';
		if (isset($r['invalid']['input_work-bg']))
			$htmlOut .= ' invalid';
		$htmlOut .= '" />';
		$htmlOut .= '<span class="description"';
		if (!isset($r['invalid']['input_work-bg']))
			$htmlOut .= ' style="display:none;"';
		$htmlOut .= '>عدد قابل قبول نیست</span>';
		$htmlOut .= '<script type="text/javascript">$(\'#input_work-bg\').data(\'num_min\',0).data(\'num_min_inc\',false).data(\'num_max\',360).data(\'num_max_inc\',true);</script><br />';
		$htmlOut .= '<label for="input_sex">جنسیت:</label>';
		$htmlOut .= '<select name="input_sex" id="input_sex" class="';
		if (isset($r['invalid']['input_sex']))
			$htmlOut .= 'invalid';
		$htmlOut .= '">';
		$htmlOut .= "<option value=\"female\"";
		if ($row['sex'] == 'female')
			$htmlOut .= ' selected="selected"';
		$htmlOut .= '>زن</option>';
		$htmlOut .= "\n<option value=\"male\"";
		if ($row['sex'] == 'male')
			$htmlOut .= ' selected="selected"';
		$htmlOut .= '>مرد</option>';
		$htmlOut .= '</select><br />';
		$htmlOut .= '<br /><input type="submit" value="ثبت بهورز" name="submitEditHygieneUnitPersonnel" /></fieldset></form>';
		
		return $htmlOut;
	}

	public static function modifyHygieneUnitsContent() {
		$htmlOut = '';
		$htmlOut .= '<p>در این قسمت شما می توانید ویژگی های موردنظر خود را برای خانه بهداشت "' . self::getName() . '" تعریف نمایید.</p>';

		return $htmlOut;
	}

	public static function hygieneUnitServices() {
		$htmlOut = '';
		$htmlOut .= '<p>در این قسمت شما می توانید خدمات موردنظر خود را برای خانه بهداشت "' . self::getName() . '" تعریف نمایید.</p>';
		$htmlOut .= '<p style="text-align: right; font-weight: bold; font-size: 15px; margin-right: 20px"><a href="#" style="color: green;" id="slideLink" ><img src="../illustrator/images/icons/plus.png" />&nbsp;افزودن خدمت</a></p>';
		$htmlOut .= '<form action="' . substr(strrchr($_SERVER['PHP_SELF'], '/'), 1) . '" method="post" id="slideForm" class="narin"><fieldset><legend>افزودن خدمت</legend>';
		$htmlOut .= 'نام خدمت: ' . '<select name="input_service_name" class="validate first_opt_not_allowed">';
		$dbRes = Database::execute_query("SELECT `id`, `name` FROM `available-services`;");
		if(Database::num_of_rows($dbRes) > 0) {
        	$htmlOut .= '<option>انتخاب کنید...</option>';
        	while ( $dbRow = Database::get_assoc_array( $dbRes ) )
            	$htmlOut .= '<option value="' . $dbRow['id'] . '">' . $dbRow['name'] . '</option>';
		} else
			$htmlOut .= '<option>' . NO_AVAILABLE_SERVICES . '</option>';
		$htmlOut .= '</select>';
		$htmlOut .= '<input type="submit" name="submitAddHygieneUnitService" value="ثبت خدمت برای خانه بهداشت" />';
		$htmlOut .= '</fieldset></form>';

		$res = Database::execute_query("SELECT `hygiene-unit-services`.`id`, `available-services`.`name` FROM `available-services` INNER JOIN `hygiene-unit-services` ON `available-services`.`id` = `hygiene-unit-services`.`service-id` WHERE `hygiene-unit-id` = '" . Database::filter_str($_SESSION['hygieneUnitId']) . "' ORDER BY `name` ASC;");
		$counter = 1;
		if (Database::num_of_rows($res) > 0) {
			$htmlOut .= '<div><fieldset><legend>فهرست خدمات خانه بهداشت "' . self::getName() . '"</legend>';
			$htmlOut .= '<br /><table align="right" class="contentTable" border="0" cellpadding="0" cellspacing="1">';
			$htmlOut .= '<thead><tr><th width="40">ردیف</th><th>نام</th><th width="50">حذف</th></thead><tbody>';
			while ($row = Database::get_assoc_array($res)) {
				$htmlOut .= '<tr><td>' . $counter . '</td><td>' . $row['name'] . '</td>';
				$htmlOut .= '<td><a href="?cmd=removeHygieneUnitService&hygieneUnitServiceId=' . $row['id'] . '"><img src="../illustrator/images/icons/remove.png" title="حذف ' . $row['name'] . '" /></a></td>';
				$counter++;
			}

			$htmlOut .= '</tbody></table></fielset></div>';
		} else
			$htmlOut .= NO_UNIT_SERVICE;

		return $htmlOut;
	}

	public static function removeHygieneUnitService($id) {
		//_log process
		$resH = Database::execute_query("SELECT * FROM `hygiene-unit-services` WHERE `id` = '$id';");
		$rowH = Database::get_assoc_array($resH);
		$resS = Database::execute_query("SELECT `name` FROM `available-services` WHERE `id` = '" . $rowH['service-id'] . "';");
		$rowS = Database::get_assoc_array($resS);
		$resU = Database::execute_query("SELECT `name`, `center-id` FROM `hygiene-units` WHERE `id` = '" . $rowH['hygiene-unit-id'] . "';");
		$rowU = Database::get_assoc_array($resU);
		$resC = Database::execute_query("SELECT `name`, `town-id` FROM `centers` WHERE `id` = '" . $rowU['center-id'] . "';");
		$rowC = Database::get_assoc_array($resC);
		$res = Database::execute_query("SELECT `name` FROM `towns` WHERE `id` = '" . $rowC['town-id'] . "';");
		$row = Database::get_assoc_array($res);
		$_logTXT = 'حذف خدمت "' . $rowS['name'] . '" از خانه‌بهداشت "' . $rowU['name'] . '" در مرکز "' . $rowC['name'] . '" در شهرستان "' . $row['name'] . '"';
		
		try {
			Database::execute_query("DELETE FROM `hygiene-unit-services` WHERE `id` = '" . $id . "';");
		} catch ( exception $e ) {
			return $e -> getMessage();
		}
		FormProccessor::_makeLog($_logTXT);
		
		header("Location: " . $_SERVER['HTTP_REFERER']);
		exit;
		return true;
	}

	public static function behvarzFinancialInfo() {
		$htmlOut = '';
		$htmlOut .= '<p><span style="font-weight: bold;">گام اول</span>: برای مشاهده و تغییر اطلاعات مالی پرسنل موردنظر، ابتدا وی را بیابید.</p>';
		$htmlOut .= '<form action="' . substr(strrchr($_SERVER['PHP_SELF'], '/'), 1) . '?cmd=hygieneUnitPersonnelFinancialInfo" method="post" class="narin">';
		$htmlOut .= '<input type="hidden" name="narin:token" value="' . Narin::create_token() . '" /><fieldset><legend>یافتن پرسنل</legend>';
		$htmlOut .= '<label for="searchBy">یافتن بر اساس</label>';
		$htmlOut .= '<select name="searchBy" id="searchBy" class="';
		if (isset($r['invalid']['searchBy']))
			$htmlOut .= 'invalid';
		$htmlOut .= '">';
		$htmlOut .= '<option value="name"';
		if (isset($_POST['searchBy']) and $_POST['searchBy'] == 'name')
			$htmlOut .= ' selected="selected"';
		$htmlOut .= '>نام</option>';
		$htmlOut .= '<option value="lastname"';
		if (isset($_POST['searchBy']) and $_POST['searchBy'] == 'lastname')
			$htmlOut .= ' selected="selected"';
		$htmlOut .= '>نام خانوادگی</option>';
		$htmlOut .= '<option value="job-title"';
		if (isset($_POST['searchBy']) and $_POST['searchBy'] == 'job-title')
			$htmlOut .= ' selected="selected"';
		$htmlOut .= '>رده پرسنلی</option>';
		$htmlOut .= '</select>';
		$htmlOut .= '&nbsp;::<input type="text" name="searchValue" id="searchValue" maxlength="255"';
		if (isset($_POST['searchValue']) && $_POST['searchValue'] !== '')
			$htmlOut .= ' value="' . htmlspecialchars($_POST['searchValue']) . '"';
		$htmlOut .= ' class="validate required ';
		if (isset($r['invalid']['searchValue']))
			$htmlOut .= ' invalid';
		$htmlOut .= '" />';
		$htmlOut .= '<span class="description"';
		if (!isset($r['invalid']['searchValue']))
			$htmlOut .= ' style="display:none;"';
		$htmlOut .= '>فارسی بنویسید</span>';
		$htmlOut .= '   <input type="submit" value="بیاب" name="submitSearchBehvarz" /></fieldset></form>';

		return $htmlOut;
	}

	public static function behvarzSearchResult() {
		$htmlOut = '';
		if (isset($_POST['searchValue']) && trim($_POST['searchValue']) !== '') {
			$res = Database::execute_query("SELECT `id`, `name`, `lastname`, `job-title` FROM  `hygiene-unit-personnels` WHERE `hygiene-unit-id` = '" . $_SESSION['hygieneUnitId'] . "' AND `" . $_POST['searchBy'] . "` LIKE '%" . Database::filter_str($_POST['searchValue']) . "%' AND `job-title` = 'بهورز' ORDER BY `lastname` ASC;");
			$counter = 1;
			if (Database::num_of_rows($res) > 0) {
				$htmlOut .= '<p><br /><span style="font-weight: bold; text-align: right;">گام دوم</span>: برای انتخاب، روی نام پرسنل موردنظر کلیک کنید.</p>';
				$htmlOut .= '<fieldset><legend>اسامی یافت شده</legend>';
				$htmlOut .= '<br /><table align="center" class="contentTable" border="0" cellpadding="0" cellspacing="1">';
				$htmlOut .= '<thead><tr><th>ردیف</th><th>نام</th><th>نام خانوادگی</th><th>رده ی پرسنلی</th></tr></thead><tbody>';
				while ($row = Database::get_assoc_array($res)) {
					$htmlOut .= '<tr><td>' . $counter . '</td><td><a href="?cmd=hygieneUnitPersonnelFinancialInfo&id=' . $row['id'] . '">' . $row['name'] . '</a></td>';
					$htmlOut .= '<td>' . $row['lastname'] . '</td>';
					$htmlOut .= '<td>' . $row['job-title'] . '</td></tr>';
					$counter++;
				}

				$htmlOut .= '<tbody></table></fielset><br />';
			} else
				$htmlOut .= '<p class="warning">هیچ نتیجه ای برای "' . $_POST['searchValue'] . '" وجود ندارد. در درست و کامل وارد کردن مشخصه ی پرسنل دقت نمایید.</p>';
		} else
			$htmlOut = '<p id="err">کلمه ای برای جستجو وارد نکرده اید، لطفا دوباره امتحان کنید.</p>';

		return $htmlOut;
	}

	public static function editBehvarzFinancialInfo($id) {
		$htmlOut = '';
		$htmlOut .= '<p><br /><span style="font-weight: bold; text-align: right;">گام سوم</span>: هزینه ی موردنظر را انتخاب کرده و مبلغ آن را وارد نمایید.</p>';
		$htmlOut .= '<form action="' . substr(strrchr($_SERVER['PHP_SELF'], '/'), 1) . '" method="post" class="narin">';
		$htmlOut .= '<input type="hidden" name="narin:token" value="' . Narin::create_token() . '" />';
		$htmlOut .= '<input type="hidden" name="personnelId" value="' . $id . '" /><fieldset><legend>ثبت هزینه ی مالی پرسنل</legend>';
		$htmlOut .= '<label for="input_title">عنوان هزینه:</label>';
		$htmlOut .= '<select name="input_title" id="input_title" class="';
		if (isset($r['invalid']['input_title']))
			$htmlOut .= 'invalid';
		$htmlOut .= '">';
		$htmlOut .= '<option value="additional-charge"';
		if (isset($_POST['input_title']) && $_POST['input_title'] == 'additional-charge')
			$htmlOut .= ' selected="selected"';
		$htmlOut .= '>مزایا</option>';
		$htmlOut .= '<option value="extra-work-salary"';
		if (isset($_POST['input_title']) && $_POST['input_title'] == 'extra-work-salary')
			$htmlOut .= ' selected="selected"';
		$htmlOut .= '>اضافه کاری</option>';
		$htmlOut .= '<option value="gift-cost"';
		if (isset($_POST['input_title']) && $_POST['input_title'] == 'gift-cost')
			$htmlOut .= ' selected="selected"';
		$htmlOut .= '>عیدی و پاداش</option>';
		$htmlOut .= '<option value="mission-income"';
		if (isset($_POST['input_title']) && $_POST['input_title'] == 'mission-income')
			$htmlOut .= ' selected="selected"';
		$htmlOut .= '>ماموریت</option>';
		$htmlOut .= '<option value="clothes-cost"';
		if (isset($_POST['input_title']) && $_POST['input_title'] == 'clothes-cost')
			$htmlOut .= ' selected="selected"';
		$htmlOut .= '>لباس</option>';
		$htmlOut .= '<option value="insurance-cost"';
		if (isset($_POST['input_title']) && $_POST['input_title'] == 'insurance-cost')
			$htmlOut .= ' selected="selected"';
		$htmlOut .= '>بیمه</option>';
		$htmlOut .= '<option value="vacation-left"';
		if (isset($_POST['input_title']) && $_POST['input_title'] == 'vacation-left')
			$htmlOut .= ' selected="selected"';
		$htmlOut .= '>ذخیره ی مرخصی و پاداش بازنشستگی</option>';
		$htmlOut .= '<option value="other"';
		if (isset($_POST['input_title']) && $_POST['input_title'] == 'other')
			$htmlOut .= ' selected="selected"';
		$htmlOut .= '>سایر</option>';
		$htmlOut .= '</select><br />';
		$htmlOut .= '<label for="input_cost" class="required">مبلغ هزینه:</label>';
		$htmlOut .= '<input type="text" name="input_cost" id="input_cost" dir="ltr"';
		if (isset($_POST['input_cost']) && $_POST['input_cost'] !== '')
			$htmlOut .= ' value="' . htmlspecialchars($_POST['input_cost']) . '"';
		$htmlOut .= ' class="validate required integer';
		if (isset($r['invalid']['input_cost']))
			$htmlOut .= ' invalid';
		$htmlOut .= '" />';
		$htmlOut .= '<span class="description"';
		if (!isset($r['invalid']['input_cost']))
			$htmlOut .= ' style="display:none;"';
		$htmlOut .= '>عدد صحیح، حداقل بزرگتر از 0</span>';
		$htmlOut .= '<script type="text/javascript">$(\'#input_cost\').data(\'num_min\',0).data(\'num_min_inc\',false);</script><br />';
		$htmlOut .= '<input type="submit" value="ثبت هزینه" name="submitRegisterBehvarzCost" /></fieldset></form>';

		return $htmlOut;
	}

	public static function addHygieneUnitDrugs() {
		$htmlOut = '';
		$htmlOut .= '<p style="margin-right: 20px;">در این قسمت می توانید داروهای خانه‌بهداشت "' . self::getName() . '" را تعیین نمایید.</p>';
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
		if(Database::num_of_rows($dbRes) > 0) {
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
		$htmlOut .= '</textarea><br /><input type="submit" name="submitAddHygieneUnitDrugsAndConsumingEquipments" value="ثبت خرید" /></fieldset></form>';

		/************************************************************************************************************************************/

		//$res = Database::execute_query("SELECT * FROM `v_hygiene-units_drugs_full` WHERE `deleted` = '0' AND `is_drug` = 'yes' AND `hygiene-unit-id` = '" . $_SESSION['hygieneUnitId'] . "' ORDER BY `name` ASC;");
        $res = Database::execute_query("SELECT `hygiene-unit-drugs`.`id`, `hygiene-unit-drugs`.`num-of-drugs`, `available-drugs-cost`.`cost`, `available-drugs-and-consuming-equipments`.`name` FROM `hygiene-unit-drugs` 
                                    LEFT JOIN `available-drugs-cost` ON `hygiene-unit-drugs`.`drug-cost-id` = `available-drugs-cost`.`id`
                                    LEFT JOIN `available-drugs-and-consuming-equipments` ON `available-drugs-cost`.`drug-id` = `available-drugs-and-consuming-equipments`.`id`
                                    WHERE `hygiene-unit-drugs`.`deleted` = 0 AND `hygiene-unit-drugs`.`hygiene-unit-id` = '" . $_SESSION['hygieneUnitId'] . "' AND `available-drugs-and-consuming-equipments`.`is_drug` = 'yes' 
                                    ORDER BY `available-drugs-and-consuming-equipments`.`name` ASC;");
        $counter = 1;
		if (Database::num_of_rows($res) > 0) {
			$htmlOut .= '<fieldset><legend>فهرست داروهای خانه‌بهداشت "' . self::getName() . '"</legend>';
			$htmlOut .= '<br /><table align="right" class="contentTable" border="0" cellpadding="0" cellspacing="1">';
			$htmlOut .= '<thead><th width="40">ردیف</th><th>نام</th><th width="40">تعداد</th><th>قیمت واحد(ريال)</th><th>قیمت کل(ريال)</th><th width="50">حذف</th></thead><tbody>';
			while ($row = Database::get_assoc_array($res)) {
				$htmlOut .= '<tr><td>' . $counter . '</td><td>' . $row['name'] . '</a></td>';
				$htmlOut .= '<td>' . $row['num-of-drugs'] . '</td>';
				$htmlOut .= '<td>' . $row['cost'] . '</td>';
				$htmlOut .= '<td>' . $row['num-of-drugs'] * $row['cost'] . '</td>';
				$htmlOut .= '<td><a href="?cmd=removeHygieneUnitDrug&drugId=' . $row['id'] . '"><img src="../illustrator/images/icons/remove.png" title="حذف ' . $row['name'] . '" /></a></td></tr>';
				$counter++;
			}

			$htmlOut .= '</tbody></table></fielset><br />';
		} else
			$htmlOut .= '<p class="warning">هیچ دارویی برای خانه‌بهداشت "' . self::getName() . '" ثبت نشده است.</p>';

		return $htmlOut;
	}

	public static function addHygieneUnitConsumingEquipments() {
		$htmlOut = '';
		$htmlOut .= '<p style="margin-right: 20px;">در این قسمت می توانید تجهیزات مصرفی خانه‌بهداشت "' . self::getName() . '" را تعیین نمایید.</p>';
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
		if(Database::num_of_rows($dbRes) > 0) {
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
		$htmlOut .= '</textarea><br /><input type="submit" name="submitAddHygieneUnitDrugsAndConsumingEquipments" value="ثبت خرید" /></fieldset></form>';

		/************************************************************************************************************************************/

        //$res = Database::execute_query("SELECT * FROM `v_hygiene-units_drugs_full` WHERE `deleted` = '0' AND `is_drug` = 'no' AND `hygiene-unit-id` = '" . $_SESSION['hygieneUnitId'] . "' ORDER BY `name` ASC;");
        $res = Database::execute_query("SELECT `hygiene-unit-drugs`.`id`, `hygiene-unit-drugs`.`num-of-drugs`, `available-drugs-cost`.`cost`, `available-drugs-and-consuming-equipments`.`name` FROM `hygiene-unit-drugs` 
                                    LEFT JOIN `available-drugs-cost` ON `hygiene-unit-drugs`.`drug-cost-id` = `available-drugs-cost`.`id`
                                    LEFT JOIN `available-drugs-and-consuming-equipments` ON `available-drugs-cost`.`drug-id` = `available-drugs-and-consuming-equipments`.`id`
                                    WHERE `hygiene-unit-drugs`.`deleted` = 0 AND `hygiene-unit-drugs`.`hygiene-unit-id` = '" . $_SESSION['hygieneUnitId'] . "' AND `available-drugs-and-consuming-equipments`.`is_drug` = 'no' 
                                    ORDER BY `available-drugs-and-consuming-equipments`.`name` ASC;");
        $counter = 1;
		if (Database::num_of_rows($res) > 0) {
			$htmlOut .= '<fieldset><legend>فهرست تجهیزات مصرفی خانه‌بهداشت "' . self::getName() . '"</legend>';
			$htmlOut .= '<br /><table align="right" class="contentTable" border="0" cellpadding="0" cellspacing="1">';
			$htmlOut .= '<thead><th width="40">ردیف</th><th>نام</th><th width="40">تعداد</th><th>قیمت واحد(ريال)</th><th>قیمت کل(ريال)</th><th width="50">حذف</th></thead><tbody>';
			while ($row = Database::get_assoc_array($res)) {
				$htmlOut .= '<tr><td>' . $counter . '</td><td>' . $row['name'] . '</a></td>';
				$htmlOut .= '<td>' . $row['num-of-drugs'] . '</td>';
				$htmlOut .= '<td>' . $row['cost'] . '</td>';
				$htmlOut .= '<td>' . $row['num-of-drugs'] * $row['cost'] . '</td>';
				$htmlOut .= '<td><a href="?cmd=removeHygieneUnitDrug&drugId=' . $row['id'] . '"><img src="../illustrator/images/icons/remove.png" title="حذف ' . $row['name'] . '" /></a></td></tr>';
				$counter++;
			}

			$htmlOut .= '</tbody></table></fielset><br />';
		} else
			$htmlOut .= '<p class="warning">هیچ تجهیزات مصرفی برای خانه‌بهداشت "' . self::getName() . '" ثبت نشده است.</p>';

		return $htmlOut;
	}

	public static function removeHygieneUnitDrugsAndConsumingEquipments($id) {
		//_log process
		try {
			//$resU = Database::execute_query("SELECT * FROM `v_hygiene-units_drugs_full` WHERE `hg-drug-id` = '$id';");
            $resU = Database::execute_query("SELECT `hygiene-unit-drugs`.`hygiene-unit-id`, `available-drugs-and-consuming-equipments`.`name` FROM `hygiene-unit-drugs` 
                                    LEFT JOIN `available-drugs-cost` ON `hygiene-unit-drugs`.`drug-cost-id` = `available-drugs-cost`.`id`
                                    LEFT JOIN `available-drugs-and-consuming-equipments` ON `available-drugs-cost`.`drug-id` = `available-drugs-and-consuming-equipments`.`id`
                                    WHERE `hygiene-unit-drugs`.`hygiene-unit-id` = " . $id . ";");
			$rowU = Database::get_assoc_array($resU);
			$resH = Database::execute_query("SELECT `name`, `center-id` FROM `hygiene-units` WHERE `id` = '" . $rowU['hygiene-unit-id'] . "';");
			$rowH = Database::get_assoc_array($resH);
			$resC = Database::execute_query("SELECT `name`, `town-id` FROM `centers` WHERE `id` = '" . $rowH['center-id'] . "';");
			$rowC = Database::get_assoc_array($resC);
			$res = Database::execute_query("SELECT `name` FROM `towns` WHERE `id` = '" . $rowC['town-id'] . "';");
			$row = Database::get_assoc_array($res);
		} catch ( exception $e ) {
			return $e -> getMessage();
		}
		$_logTXT = 'حذف دارو یا تجهیزات مصرفی به‌نام "' . $rowU['name'] . '" از خانه‌بهداشت "' . $rowH['name'] . '" در مرکز "' . $rowC['name'] . '" در شهرستان "' . $row['name'] . '"';
		
		$dateTimeObj = new DateTime();
		$dateTimeZone = new DateTimeZone('Asia/Tehran');
		$dateTimeObj -> setTimezone($dateTimeZone);
		$shamsiDate = gregorian_to_jalali($dateTimeObj -> format('Y'), $dateTimeObj -> format('m'), $dateTimeObj -> format('d'));
		$date = $shamsiDate[0] . "-" . $shamsiDate[1] . "-" . $shamsiDate[2];

		try {
			Database::execute_query("UPDATE `hygiene-unit-drugs` SET `deleted` = '1', `date-of-delete` = '$date' WHERE `id` = '" . $id . "';");
			unset($dateTimeObj, $dateTimeZone, $date, $shamsiDate);
		} catch ( exception $e ) {
			return $e -> getMessage();
		}
		FormProccessor::_makeLog($_logTXT);
		
		header("Location: " . $_SERVER['HTTP_REFERER']);
		exit;
		return true;
	}

	public static function addHygieneUnitNonConsumingEquipments() {
		$htmlOut = '';
		$htmlOut .= '<p style="margin-right: 20px;">در این قسمت می توانید تجهیزات غیرمصرفی خانه بهداشت "' . self::getName() . '" را تعیین نمایید.</p>';
		$htmlOut .= '<p style="text-align: right; font-weight: bold; font-size: 15px; margin-right: 20px"><a href="#" style="color: green;" id="slideLink" ><img src="../illustrator/images/icons/plus.png" />&nbsp;افزودن</a></p>';
		$htmlOut .= '<form action="' . substr(strrchr($_SERVER['PHP_SELF'], '/'), 1) . '" method="post" id="slideForm" class="narin">';
		$htmlOut .= '<input type="hidden" name="narin:token" value="' . Narin::create_token() . '" /><fieldset><legend>ثبت خرید تجهیزات مصرفی</legend>';
		$htmlOut .= '<label for="input_equip_id">نام:</label>';
        /*$htmlOut .= '<input id="input_drug_name" name="input_drug_name" list="drugs-list" title="انتخاب کنید..." class="validate required ';
        if (isset($r['invalid']['input_drug_name']))
            $htmlOut .= 'invalid';
        $htmlOut .= '">';
        $htmlOut .= '<datalist id="drugs-list">';
        $dbRes = Database::execute_query("SELECT `id`, `name`, `mark` FROM `available-non-consuming-equipments` ORDER BY `name` ASC;");
        if (Database::num_of_rows($dbRes) > 0) {
            while ($dbRow = Database::get_assoc_array($dbRes))
                $htmlOut .= '<option value="' . $dbRow['id'] . '">' . $dbRow['name'] . ' - (' . $dbRow['mark'] . ')</option>';
        } else
            $htmlOut .= '<option>' . NO_AVAILABLE_DRUGS . '</option>';
        $htmlOut .= '</datalist><br>';*/
		$htmlOut .= '<select name="input_equip_id" id="input_equip_id" class="validate first_opt_not_allowed ';
		if (isset($r['invalid']['input_equip_id']))
			$htmlOut .= 'invalid';
		$htmlOut .= '">';
		$dbRes = Database::execute_query("SELECT `id`, `name`, `mark` FROM `available-non-consuming-equipments` ORDER BY `name` ASC;");
		if(Database::num_of_rows($dbRes) > 0) {
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
		$htmlOut .= '<input type="submit" name="submitAddHygieneUnitNonConsumingEquipments" value="ثبت" /></fieldset></form>';

		/************************************************************************************************************************************/

		//$res = Database::execute_query("SELECT * FROM `v_hygiene-units_nonconsumings_full` WHERE `deleted` = '0' AND `hygiene-unit-id` = '" . $_SESSION['hygieneUnitId'] . "' ORDER BY `name` ASC;");
        $res = Database::execute_query("SELECT `hygiene-unit-non-consuming-equipments`.`id`, `hygiene-unit-non-consuming-equipments`.`num-of-equips`, `available-non-consuming-cost`.`cost`, `available-non-consuming-equipments`.`name`, `available-non-consuming-equipments`.`mark` FROM `hygiene-unit-non-consuming-equipments` 
                                    LEFT JOIN `available-non-consuming-cost` ON `hygiene-unit-non-consuming-equipments`.`equip-cost-id` = `available-non-consuming-cost`.`id` 
                                    LEFT JOIN `available-non-consuming-equipments` ON `available-non-consuming-cost`.`equip-id` = `available-non-consuming-equipments`.`id` 
                                    WHERE `hygiene-unit-non-consuming-equipments`.`hygiene-unit-id` = " . $_SESSION['hygieneUnitId'] . " AND `hygiene-unit-non-consuming-equipments`.`deleted` = '0' 
                                    ORDER BY `available-non-consuming-equipments`.`name` ASC;");
        $counter = 1;
		if (Database::num_of_rows($res) > 0) {
			$htmlOut .= '<fieldset><legend>فهرست تجهیزات غیرمصرفی خانه بهداشت "' . self::getName() . '"</legend>';
			$htmlOut .= '<br /><table align="right" class="contentTable" border="0" cellpadding="0" cellspacing="1">';
			$htmlOut .= '<thead><th width="40">ردیف</th><th>نام</th><th width="40">تعداد</th><th>قیمت واحد(ريال)</th><th>قیمت کل(ريال)</th><th width="50">حذف</th></thead><tbody>';
			while ($row = Database::get_assoc_array($res)) {
				$htmlOut .= '<tr><td>' . $counter . '</td><td>' . $row['name'] . ' - (' . $row['mark'] . ')</td>';
				$htmlOut .= '<td>' . $row['num-of-equips'] . '</td>';
				$htmlOut .= '<td>' . $row['cost'] . '</td>';
				$htmlOut .= '<td>' . $row['num-of-equips'] * $row['cost'] . '</td>';
				$htmlOut .= '<td><a href="?cmd=removeHygieneUnitNonConsumingEquip&id=' . $row['id'] . '"><img src="../illustrator/images/icons/remove.png" title="حذف ' . $row['name'] . '" /></a></td></tr>';
				$counter++;
			}

			$htmlOut .= '</tbody></table></fielset><br />';
		} else
			$htmlOut .= '<p class="warning">هیچ تجهیزات غیرمصرفی برای خانه بهداشت "' . self::getName() . '" ثبت نشده است.</p>';

		return $htmlOut;
	}

	public static function removeHygieneUnitNonConsumingEquipment($id) {
		//_log process
		try {
			//$resU = Database::execute_query("SELECT * FROM `v_hygiene-units_nonconsumings_full` WHERE `hg-equip-id` = '$id';");
            $resU = Database::execute_query("SELECT `hygiene-unit-non-consuming-equipments`.`hygiene-unit-id`, `available-non-consuming-equipments`.`name`, `available-non-consuming-equipments`.`mark` FROM `hygiene-unit-non-consuming-equipments` 
                                    LEFT JOIN `available-non-consuming-cost` ON `hygiene-unit-non-consuming-equipments`.`equip-cost-id` = `available-non-consuming-cost`.`id` 
                                    LEFT JOIN `available-non-consuming-equipments` ON `available-non-consuming-cost`.`equip-id` = `available-non-consuming-equipments`.`id` 
                                    WHERE `hygiene-unit-non-consuming-equipments`.`id` = " . $id . ";");
			$rowU = Database::get_assoc_array($resU);
			$resH = Database::execute_query("SELECT `name`, `center-id` FROM `hygiene-units` WHERE `id` = '" . $rowU['hygiene-unit-id'] . "';");
			$rowH = Database::get_assoc_array($resH);
			$resC = Database::execute_query("SELECT `name`, `town-id` FROM `centers` WHERE `id` = '" . $rowH['center-id'] . "';");
			$rowC = Database::get_assoc_array($resC);
			$res = Database::execute_query("SELECT `name` FROM `towns` WHERE `id` = '" . $rowC['town-id'] . "';");
			$row = Database::get_assoc_array($res);
		} catch ( exception $e ) {
			return $e -> getMessage();
		}
		$_logTXT = 'حذف تجهیزات غیرمصرفی به‌نام "' . $rowU['name'] . '(' . $rowU['mark'] . ')" از خانه‌بهداشت "' . $rowH['name'] . '" در مرکز "' . $rowC['name'] . '" در شهرستان "' . $row['name'] . '"';
		
		$dateTimeObj = new DateTime();
		$dateTimeZone = new DateTimeZone('Asia/Tehran');
		$dateTimeObj -> setTimezone($dateTimeZone);
		$shamsiDate = gregorian_to_jalali($dateTimeObj -> format('Y'), $dateTimeObj -> format('m'), $dateTimeObj -> format('d'));
		$date = $shamsiDate[0] . "-" . $shamsiDate[1] . "-" . $shamsiDate[2];
		try {
			Database::execute_query("UPDATE `hygiene-unit-non-consuming-equipments` SET `deleted` = '1', `date-of-delete` = '$date' WHERE `id` = '" . $id . "';");
			unset($dateTimeObj, $dateTimeZone, $date, $shamsiDate);
		} catch ( exception $e ) {
			return $e -> getMessage();
		}
		FormProccessor::_makeLog($_logTXT);
		
		header("Location: " . $_SERVER['HTTP_REFERER']);
		exit;
		return true;
	}

	public static function manageHygieneUnitCharges() {
		$htmlOut = '';
		$htmlOut .= '<p>در این قسمت شما می توانید هزینه های استهلاک و نگهداری خانه بهداشت "' . self::getName() . '" را مدیریت نمایید.<br />از منوی سمت راست گزینه ی موردنظر را انتخاب نمایید.</p>';

		return $htmlOut;
	}

	public static function manageHygieneUnitBuildings() {
		$htmlOut = '';
		$htmlOut .= '<p>در این قسمت شما می توانید ساختمان های مورد استفاده خانه بهداشت "' . self::getName() . '" را مدیریت نمایید.</p>';
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
		$htmlOut .= '<input type="submit" value="ثبت ساختمان" name="submitAddHygieneUnitBuilding" /></fieldset></form>';

		$res = Database::execute_query("SELECT `id`, `title` FROM  `hygiene-unit-buildings` WHERE `hygiene-unit-id` = '" . Database::filter_str(self::$id) . "' ORDER BY `title` ASC;");
		$counter = 1;
		if (Database::num_of_rows($res) > 0) {
			$htmlOut .= '<div><fieldset><legend>فهرست ساختمان های خانه بهداشت ' . self::getName() . '</legend>';
			$htmlOut .= '<br /><table align="right" class="contentTable" border="0" cellpadding="0" cellspacing="1">';
			$htmlOut .= '<thead><tr><th width="40">ردیف</th><th>نام</th><th width="50">حذف</th><th width="50">ویرایش</th></thead><tbody>';
			while ($row = Database::get_assoc_array($res)) {
				$htmlOut .= '<tr><td>' . $counter . '</td><td>' . $row['title'] . '</td>';
				$htmlOut .= '<td><a href="?cmd=removeHygieneUnitBuilding&buildingId=' . $row['id'] . '"><img src="../illustrator/images/icons/remove.png" title="حذف ' . $row['title'] . '" /></a></td>';
				$htmlOut .= '<td><a href="?cmd=editHygieneUnitBuilding&buildingId=' . $row['id'] . '"><img src="../illustrator/images/icons/edit.png" title="ویرایش ' . $row['title'] . '" /></a></td></tr>';
				$counter++;
			}

			$htmlOut .= '</tbody></table></fielset></div>';
		} else
			$htmlOut .= NO_CENTER_BUILDINGS;

		return $htmlOut;
	}

	public static function editHygieneUnitBuilding($id) {
		$htmlOut = '';
		if (strpos($_SERVER['HTTP_REFERER'], 'es'))
			$_SESSION['ref'] = 1;
		else
			$_SESSION['ref'] = 0;
		$res = Database::execute_query("SELECT * FROM `hygiene-unit-buildings` WHERE `id` = '$id';");
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
		$htmlOut .= '<input type="submit" value="اصلاح ساختمان" name="submitEditHygieneUnitBuilding" /></fieldset></form>';

		return $htmlOut;
	}

	public static function removeHygieneUnitBuilding($id) {
		//_log process
		try {
			$resU = Database::execute_query("SELECT * FROM `hygiene-unit-buildings` WHERE `id` = '$id';");
			$rowU = Database::get_assoc_array($resU);
			$resH = Database::execute_query("SELECT `name`, `center-id` FROM `hygiene-units` WHERE `id` = '" . $rowU['hygiene-unit-id'] . "';");
			$rowH = Database::get_assoc_array($resH);
			$resC = Database::execute_query("SELECT `name`, `town-id` FROM `centers` WHERE `id` = '" . $rowH['center-id'] . "';");
			$rowC = Database::get_assoc_array($resC);
			$res = Database::execute_query("SELECT `name` FROM `towns` WHERE `id` = '" . $rowC['town-id'] . "';");
			$row = Database::get_assoc_array($res);
		} catch ( exception $e ) {
			return $e -> getMessage();
		}
		$_logTXT = 'حذف ساختمان باعنوان "' . $rowU['title'] . '" از خانه‌بهداشت "' . $rowH['name'] . '" در مرکز "' . $rowC['name'] . '" در شهرستان "' . $row['name'] . '"';
		
		try {
			Database::execute_query("DELETE FROM `hygiene-unit-buildings` WHERE `id` = '" . $id . "';");
		} catch ( exception $e ) {
			return $e -> getMessage();
		}
		FormProccessor::_makeLog($_logTXT);
		
		header("Location: " . $_SERVER['HTTP_REFERER']);
		exit;
		return true;
	}

	public static function addHygieneUnitBuildingCharge() {
		$htmlOut = '';
		$htmlOut .= '<p style="text-align: right; font-weight: bold; font-size: 15px; margin-right: 20px"><a href="#" style="color: green;" id="slideLink" ><img src="../illustrator/images/icons/plus.png" />&nbsp;ثبت</a></p>';
		$htmlOut .= '<form action="' . substr(strrchr($_SERVER['PHP_SELF'], '/'), 1) . '" method="post" id="slideForm" class="narin">';
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
		$dbRes = Database::execute_query("SELECT `a`.`id`, `a`.`title` FROM `hygiene-unit-buildings` AS `a` INNER JOIN `hygiene-units` AS `b` ON `a`.`hygiene-unit-id` = `b`.`id` WHERE `a`.`hygiene-unit-id` = '" . $_SESSION['hygieneUnitId'] . "' ORDER BY `title` ASC;");
		if(Database::num_of_rows($dbRes) > 0) {
			$htmlOut .= '<option>انتخاب کنید...</option>';
			while ($dbRow = Database::get_assoc_array($dbRes))
				$htmlOut .= '<option value="' . $dbRow['id'] . '">' . $dbRow['title'] . '</option>';
		} else
			$htmlOut .= '<option>' . NO_CENTER_BUILDINGS . '</option>';
		$htmlOut .= '</select><br /><input type="submit" name="submitAddHygieneUnitBuildingCharge" value="ثبت هزینه" /></fieldset></form>';
		
		$htmlOut .= '<div class="loading"><img src="../statisticalPlotter/loading.gif" />&nbsp;&nbsp;در حال بارگذاری؛ لطفا صبر کنید...</div><br />';
		$htmlOut .= '<div id="mngDiv"><fieldset><legend>مشاهده ی فهرست هزینه های ساختمان های خانه بهداشت "' . self::getName() . '"</legend>';
		$res = Database::execute_query("SELECT `id`, `title` FROM `hygiene-unit-buildings` WHERE `hygiene-unit-id` = '" . self::$id . "';");
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
		$htmlOut .= '$(\'#list\').load(\'./ea-sh-ajax.php\', {\'buildingIdForHGBuildingCharge\' : $(\'#input_building_id_1 option:selected\').val()});';
		$htmlOut .= '$(\'#input_building_id_1\').change(function() {$(\'#list\').load(\'./ea-sh-ajax.php\', {\'buildingIdForHGBuildingCharge\' : $(\'#input_building_id_1 option:selected\').val()});});';
		$htmlOut .= '</script>';

		return $htmlOut;
	}

	public static function removeHygieneUnitBuildingCharge($id) {
		//_log process
		try {
			$resB = Database::execute_query("SELECT * FROM `hygiene-unit-building-maintain-charges` WHERE `id` = '$id';");
			$rowB = Database::get_assoc_array($resB);
			$resU = Database::execute_query("SELECT * FROM `hygiene-unit-buildings` WHERE `id` = '" . $rowB['building-id'] . "';");
			$rowU = Database::get_assoc_array($resU);
			$resH = Database::execute_query("SELECT `name`, `center-id` FROM `hygiene-units` WHERE `id` = '" . $rowU['hygiene-unit-id'] . "';");
			$rowH = Database::get_assoc_array($resH);
			$resC = Database::execute_query("SELECT `name`, `town-id` FROM `centers` WHERE `id` = '" . $rowH['center-id'] . "';");
			$rowC = Database::get_assoc_array($resC);
			$res = Database::execute_query("SELECT `name` FROM `towns` WHERE `id` = '" . $rowC['town-id'] . "';");
			$row = Database::get_assoc_array($res);
		} catch ( exception $e ) {
			return $e -> getMessage();
		}
		$_logTXT = 'حذف هزینه "' . $rowB['charge-name'] . '" از ساختمان "' . $rowU['title'] . '" در خانه‌بهداشت "' . $rowH['name'] . '" در مرکز "' . $rowC['name'] . '" در شهرستان "' . $row['name'] . '"';
		
		try {
			Database::execute_query("DELETE FROM `hygiene-unit-building-maintain-charges` WHERE `id` = '" . $id . "';");
		} catch ( exception $e ) {
			return $e -> getMessage();
		}
		FormProccessor::_makeLog($_logTXT);
		
		header("Location: " . $_SERVER['HTTP_REFERER']);
		exit;
		return true;
	}
	
	public static function editHygieneUnitBuildingCharge($id) {
		$htmlOut = '';
		if (strpos($_SERVER['HTTP_REFERER'], 'es'))
			$_SESSION['ref'] = 1;
		else
			$_SESSION['ref'] = 0;
		$res = Database::execute_query("SELECT `charge-name`, `charge-amount` FROM `hygiene-unit-building-maintain-charges` WHERE `id` = '$id';");
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
		$htmlOut .= '<input type="text" name="input_charge_amount" id="input_charge_amount" dir="ltr"  value="' . $row['charge-amount'] . '"';
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
		$htmlOut .= '<input type="submit" name="submitEditHygieneUnitBuildingCharge" value="اصلاح هزینه" /></fieldset></form>';

		return $htmlOut;
	}
	
	public static function manageHygieneUnitVehicles() {
		$htmlOut = '';
		$htmlOut .= '<p>در این قسمت شما می توانید وسایل نقلیه ی مورد استفاده خانه بهداشت "' . self::getName() . '" را مدیریت نمایید.</p>';
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
		$htmlOut .= '<input type="submit" name="submitAddHygieneUnitVehicle" value="ثبت وسیله نقلیه" /></fieldset></form>';

		$res = Database::execute_query("SELECT `id`, `title` FROM `hygiene-unit-vehicles` WHERE `hygiene-unit-id` = '" . Database::filter_str(self::$id) . "' ORDER BY `title` ASC;");
		$counter = 1;
		if (Database::num_of_rows($res) > 0) {
			$htmlOut .= '<div><fieldset><legend>فهرست وسایل نقلیه خانه بهداشت "' . self::getName() . '"</legend>';
			$htmlOut .= '<br /><table align="right" class="contentTable" border="0" cellpadding="0" cellspacing="1">';
			$htmlOut .= '<thead><tr><th width="40">ردیف</th><th>نام</th><th width="50">حذف</th><th width="50">ویرایش</th></thead><tbody>';
			while ($row = Database::get_assoc_array($res)) {
				$htmlOut .= '<tr><td>' . $counter . '</td><td>' . $row['title'] . '</td>';
				$htmlOut .= '<td><a href="?cmd=removeHygieneUnitVehicle&vehicleId=' . $row['id'] . '"><img src="../illustrator/images/icons/remove.png" title="حذف ' . $row['title'] . '" /></a></td>';
				$htmlOut .= '<td><a href="?cmd=editHygieneUnitVehicle&vehicleId=' . $row['id'] . '"><img src="../illustrator/images/icons/edit.png" title="ویرایش ' . $row['title'] . '" /></a></td></tr>';
				$counter++;
			}

			$htmlOut .= '</tbody></table></fielset></div>';
		} else
			$htmlOut .= NO_CENTER_VEHICLE;

		return $htmlOut;
	}

	public static function removeHygieneUnitVehicle($id) {
		//_log process
		try {
			$resU = Database::execute_query("SELECT * FROM `hygiene-unit-vehicles` WHERE `id` = '$id';");
			$rowU = Database::get_assoc_array($resU);
			$resH = Database::execute_query("SELECT `name`, `center-id` FROM `hygiene-units` WHERE `id` = '" . $rowU['hygiene-unit-id'] . "';");
			$rowH = Database::get_assoc_array($resH);
			$resC = Database::execute_query("SELECT `name`, `town-id` FROM `centers` WHERE `id` = '" . $rowH['center-id'] . "';");
			$rowC = Database::get_assoc_array($resC);
			$res = Database::execute_query("SELECT `name` FROM `towns` WHERE `id` = '" . $rowC['town-id'] . "';");
			$row = Database::get_assoc_array($res);
		} catch ( exception $e ) {
			return $e -> getMessage();
		}
		$_logTXT = 'حذف وسیله نقلیه باعنوان "' . $rowU['title'] . '" از خانه‌بهداشت "' . $rowH['name'] . '" در مرکز "' . $rowC['name'] . '" در شهرستان "' . $row['name'] . '"';
		
		try {
			Database::execute_query("DELETE FROM `hygiene-unit-vehicles` WHERE `id` = '" . $id . "';");
		} catch ( exception $e ) {
			return $e -> getMessage();
		}
		FormProccessor::_makeLog($_logTXT);
		
		header("Location: " . $_SERVER['HTTP_REFERER']);
		exit;
		return true;
	}

	public static function editHygieneUnitVehicle($id) {
		$htmlOut = '';
		if (strpos($_SERVER['HTTP_REFERER'], 'cmd') == true)
			$_SESSION['ref'] = 0;
		else
			$_SESSION['ref'] = 1;
		$res = Database::execute_query("SELECT * FROM `hygiene-unit-vehicles` WHERE `id` = '$id';");
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

		$htmlOut .= '<input type="submit" name="submitEditHygieneUnitVehicle" value="اصلاح وسیله نقلیه" /></fieldset></form>';

		return $htmlOut;
	}

	public static function addHygieneUnitVehicleCharge() {
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
		$dbRes = Database::execute_query("SELECT `a`.`id`, `a`.`title` FROM `hygiene-unit-vehicles` AS `a` INNER JOIN `hygiene-units` AS `b` ON `a`.`hygiene-unit-id` = `b`.`id` WHERE `a`.`hygiene-unit-id` = '" . $_SESSION['hygieneUnitId'] . "' ORDER BY `title` ASC;");
		if(Database::num_of_rows($dbRes) > 0) {
			$htmlOut .= '<option>انتخاب کنید...</option>';
			while ($dbRow = Database::get_assoc_array($dbRes))
				$htmlOut .= '<option value="' . $dbRow['id'] . '">' . $dbRow['title'] . '</option>';
		} else
			$htmlOut .= '<option>' . NO_CENTER_VEHICLE . '</option>';
		$htmlOut .= '</select><br /><input type="submit" name="submitAddHygieneUnitVehicleCharge" value="ثبت هزینه" /></fieldset></form>';
		
		$htmlOut .= '<div class="loading"><img src="../statisticalPlotter/loading.gif" />&nbsp;&nbsp;در حال بارگذاری؛ لطفا صبر کنید...</div><br />';
		$htmlOut .= '<div id="mngDiv"><fieldset><legend>مشاهده ی فهرست هزینه های وسایل نقلیه خانه بهداشت "' . self::getName() . '"</legend>';
		$res = Database::execute_query("SELECT `id`, `title` FROM `hygiene-unit-vehicles` WHERE `hygiene-unit-id` = '" . self::$id . "';");
		$htmlOut .= '<br /><label for="input_vehicle_id_1" class="required">عنوان وسیله نقلیه:</label>';
		$htmlOut .= '<select name="input_vehicle_id_1" id="input_vehicle_id_1" class="validate first_opt_not_allowed">';
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
		$htmlOut .= '$(\'#list\').load(\'./ea-sh-ajax.php\', {\'vehicleIdForHGVehicleCharge\' : $(\'#input_vehicle_id_1 option:selected\').val()});';
		$htmlOut .= '$(\'#input_vehicle_id_1\').change(function() {$(\'#list\').load(\'./ea-sh-ajax.php\', {\'vehicleIdForHGVehicleCharge\' : $(\'#input_vehicle_id_1 option:selected\').val()});});';
		$htmlOut .= '</script>';

		return $htmlOut;
	}

	public static function removeHygieneUnitVehicleCharge($id) {
		//_log process
		try {
			$resB = Database::execute_query("SELECT * FROM `hygiene-unit-vehicle-maintain-charges` WHERE `id` = '$id';");
			$rowB = Database::get_assoc_array($resB);
			$resU = Database::execute_query("SELECT * FROM `hygiene-unit-vehicles` WHERE `id` = '" . $rowB['vehicle-id'] . "';");
			$rowU = Database::get_assoc_array($resU);
			$resH = Database::execute_query("SELECT `name`, `center-id` FROM `hygiene-units` WHERE `id` = '" . $rowU['hygiene-unit-id'] . "';");
			$rowH = Database::get_assoc_array($resH);
			$resC = Database::execute_query("SELECT `name`, `town-id` FROM `centers` WHERE `id` = '" . $rowH['center-id'] . "';");
			$rowC = Database::get_assoc_array($resC);
			$res = Database::execute_query("SELECT `name` FROM `towns` WHERE `id` = '" . $rowC['town-id'] . "';");
			$row = Database::get_assoc_array($res);
		} catch ( exception $e ) {
			return $e -> getMessage();
		}
		$_logTXT = 'حذف هزینه "' . $rowB['charge-name'] . '" از وسیله نقلیه "' . $rowU['title'] . '" در خانه‌بهداشت "' . $rowH['name'] . '" در مرکز "' . $rowC['name'] . '" در شهرستان "' . $row['name'] . '"';
		
		try {
			Database::execute_query("DELETE FROM `hygiene-unit-vehicle-maintain-charges` WHERE `id` = '" . $id . "';");
		} catch ( exception $e ) {
			return $e -> getMessage();
		}
		FormProccessor::_makeLog($_logTXT);
		
		header("Location: " . $_SERVER['HTTP_REFERER']);
		exit;
		return true;
	}

	public static function editHygieneUnitVehicleCharge($id) {
		$htmlOut = '';
		if (strpos($_SERVER['HTTP_REFERER'], 'es'))
			$_SESSION['ref'] = 1;
		else
			$_SESSION['ref'] = 0;
		$res = Database::execute_query("SELECT `charge-name`, `charge-amount` FROM `hygiene-unit-vehicle-maintain-charges` WHERE `id` = '$id';");
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
		$htmlOut .= '<input type="submit" name="submitEditHygieneUnitVehicleCharge" value="اصلاح هزینه" /></fieldset></form>';

		return $htmlOut;
	}

	public static function addHygieneUnitGeneralCharge() {
		$htmlOut = '';
		$htmlOut .= '<p style="text-align: right; font-weight: bold; font-size: 15px; margin-right: 20px"><a href="#" style="color: green;" id="slideLink" ><img src="../illustrator/images/icons/plus.png" />&nbsp;ثبت</a></p>';
		$htmlOut .= '<form action="' . substr(strrchr($_SERVER['PHP_SELF'], '/'), 1) . '" method="post" id="slideForm" class="narin">';
		$htmlOut .= '<input type="hidden" name="narin:token" value="' . Narin::create_token() . '" /><fieldset><legend>ثبت هزینه برای خانه بهداشت</legend>';
		$htmlOut .= '<label for="input_charge_name">نام هزینه:</label>';
		$htmlOut .= '<select name="input_charge_name" id="input_charge_name" class="validate first_opt_not_allowed ';
		if (isset($r['invalid']['input_charge_name']))
			$htmlOut .= 'invalid';
		$htmlOut .= '">';
		$dbRes = Database::execute_query("SELECT `id`, `type` FROM `general-charge-types` ORDER BY `type` ASC;");
		if(Database::num_of_rows($dbRes) > 0) {
        	$htmlOut .= '<option>انتخاب کنید...</option>';
        	while ( $dbRow = Database::get_assoc_array( $dbRes ) )
            	$htmlOut .= '<option value="' . $dbRow['id'] . '">' . $dbRow['type'] . '</option>';
		}
		else
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
		$htmlOut .= '</textarea><br /><input type="submit" name="submitAddHygieneUnitGeneralCharge" value="ثبت هزینه" /></fieldset></form>';
		
		/************************************************************************************************************************************/

		$res = Database::execute_query("SELECT `a`.`type`, `b`.`id`, `b`.`charge-amount`, `b`.`date` FROM `general-charge-types` AS `a` INNER JOIN `hygiene-unit-general-charges` AS `b` ON `a`.`id` = `b`.`charge-id` WHERE `hygiene-unit-id` = '" . $_SESSION['hygieneUnitId'] . "' ORDER BY `date` ASC;");
		$counter = 1;
		if (Database::num_of_rows($res) > 0) {
			$htmlOut .= '<br /><fieldset><legend>فهرست هزینه های عمومی ثبت شده خانه بهداشت "' . self::getName() . '"</legend>';
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
			$htmlOut .= '<p class="warning">هیچ هزینه ای برای خانه بهداشت "' . self::getName() . '" ثبت نشده است.</p>';
		
		return $htmlOut;
	}

	public static function editHygieneUnitGeneralCharge($id) {
		$htmlOut = '';
		if (strpos($_SERVER['HTTP_REFERER'], 'es'))
			$_SESSION['ref'] = 1;
		else
			$_SESSION['ref'] = 0;
		$res = Database::execute_query("SELECT `a`.`type`, `b`.`charge-amount`, `b`.`comment`, `b`.`date` FROM `general-charge-types` AS `a` INNER JOIN `hygiene-unit-general-charges` AS `b` ON `a`.`id` = `b`.`charge-id` WHERE `b`.`id` = '$id' ORDER BY `date` ASC;");
		$row = Database::get_assoc_array($res);
		$htmlOut .= '<form action="' . substr(strrchr($_SERVER['PHP_SELF'], '/'), 1) . '" method="post" class="narin">';
		$htmlOut .= '<input type="hidden" name="chargeId" value="' . $id . '" />';
		$htmlOut .= '<input type="hidden" name="narin:token" value="' . Narin::create_token() . '" /><fieldset><legend>اصلاح هزینه برای خانه بهداشت</legend>';
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
		$htmlOut .= $row['comment'] . '</textarea><br /><input type="submit" name="submitEditHygieneUnitGeneralCharge" value="اصلاح هزینه" /></fieldset></form>';

		return $htmlOut;
	}
	
	public static function removeHygieneUnitGeneralCharge($id) {
		//_log process
		try {
			$resU = Database::execute_query("SELECT * FROM `hygiene-unit-general-charges` WHERE `id` = '$id';");
			$rowU = Database::get_assoc_array($resU);
			$resI = Database::execute_query("SELECT `type` FROM `general-charge-types` WHERE `id` = '" . $rowU['charge-id'] . "';");
			$rowI = Database::get_assoc_array($resI);
			$resH = Database::execute_query("SELECT `name`, `center-id` FROM `hygiene-units` WHERE `id` = '" . $rowU['hygiene-unit-id'] . "';");
			$rowH = Database::get_assoc_array($resH);
			$resC = Database::execute_query("SELECT `name`, `town-id` FROM `centers` WHERE `id` = '" . $rowH['center-id'] . "';");
			$rowC = Database::get_assoc_array($resC);
			$res = Database::execute_query("SELECT `name` FROM `towns` WHERE `id` = '" . $rowC['town-id'] . "';");
			$row = Database::get_assoc_array($res);
		} catch ( exception $e ) {
			return $e -> getMessage();
		}
		$_logTXT = 'حذف هزینه عمومی "' . $rowI['type'] . '" به تاریخ "' . $rowU['date'] . '" از خانه‌بهداشت "' . $rowH['name'] . '" در مرکز "' . $rowC['name'] . '" در شهرستان "' . $row['name'] . '"';
		
		try {
			Database::execute_query("DELETE FROM `hygiene-unit-general-charges` WHERE `id` = '" . $id . "';");
		} catch ( exception $e ) {
			return $e -> getMessage();
		}
		FormProccessor::_makeLog($_logTXT);
		
		header("Location: " . $_SERVER['HTTP_REFERER']);
		exit;
		return true;
	}

}//end of class
?>