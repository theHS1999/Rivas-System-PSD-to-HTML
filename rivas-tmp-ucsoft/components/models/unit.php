<?php

/**
 *
 * unit.php
 * unit model class file
 *
 * @copyright	Copyright (C) 2012 Rivas Systems Inc. All rights reserved.
 * @versin	1.00 2011-02-05
 * @author	Behnam Salili
 *
 */

require_once dirname(dirname(__file__)) . '/database/database.php';

class Unit {
	private static $id;

	public static function setId($id) {
		self::$id = $id;
	}

	public static function getName() {
		$res = Database::execute_query("SELECT `name` FROM `units` WHERE `id` = '" . self::$id . "';");
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
			case 'Center Manager' :
				$htmlOut .= '<a href="' . substr(strrchr($_SERVER['PHP_SELF'], '/'), 1) . '?cmd=manageUnits&centerId=' . $_SESSION['user']['acl-id'] . '"><img src="../illustrator/images/icons/home.png" />&nbsp;صفحه‌اصلی</a>';
				break;
			case 'Unit Manager' :
				$htmlOut .= '<a href="' . substr(strrchr($_SERVER['PHP_SELF'], '/'), 1) . '?cmd=modifyUnits&unitId=' . $_SESSION['user']['acl-id'] . '"><img src="../illustrator/images/icons/home.png" />&nbsp;صفحه‌اصلی</a>';
				break;
		}

		$htmlOut .= '<a href="?cmd=manageUnitPersonnel"><img src="../illustrator/images/icons/personnel.png" />&nbsp;مدیریت پرسنل واحد</a>';
		$htmlOut .= '<a href="?cmd=unitPersonnelFinancialInfo"><img src="../illustrator/images/icons/charge.png" />&nbsp;اطلاعات مالی پرسنل</a>';
		$htmlOut .= '<a href="?cmd=manageUnitServices"><img src="../illustrator/images/icons/service.png" />&nbsp;مدیریت خدمات واحد</a>';
		if ($_SESSION['user']['acl'] == 'State Manager' or $_SESSION['user']['acl'] == 'Town Manager' or $_SESSION['user']['acl'] == 'Center Manager')
			$htmlOut .= '<a href="?cmd=manageUnits&centerId=' . $_SESSION['centerId'] . '"><img src="../illustrator/images/icons/back.png" />&nbsp;بازگشت</a>';
		$htmlOut .= '<a href="?cmd=logOut"><img src="../illustrator/images/icons/exit.png" />&nbsp;خروج از سامانه</a>';

		return $htmlOut;
	}

	public static function unitPersonnel() {
		$htmlOut = '';
		$htmlOut .= '<p style="margin-right: 20px;">در این قسمت می توانید پرسنل واحد "' . self::getName() . '" را مدیریت نمایید.</p>';
		$htmlOut .= '<p style="text-align: right; font-weight: bold; font-size: 15px; margin-right: 20px"><a href="#" style="color: green;" id="slideLink" ><img src="../illustrator/images/icons/plus.png" />&nbsp;افزودن پرسنل به واحد</a></p>';
		$htmlOut .= '<form action="' . substr(strrchr($_SERVER['PHP_SELF'], '/'), 1) . '" method="post" id="slideForm" class="narin">';
		$htmlOut .= '<input type="hidden" name="narin:token" value="' . Narin::create_token() . '" /><fieldset><legend>افزودن پرسنل</legend>';
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
		if (isset($r['invalid']['input_age']))
			$htmlOut .= ' invalid';
		$htmlOut .= '">';
		$htmlOut .= '<label id="date_picker_label" for="input_age">سال تولد:</label>';
		$htmlOut .= '<input type="text" id="input_age" name="input_age" size="4" maxlength="4" class="date_y"';
		if (isset($_POST['input_age']) && $_POST['input_age'] !== '')
			$htmlOut .= ' value="' . htmlspecialchars($_POST['input_age']) . '"';
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
		$dbRes = Database::execute_query("SELECT `title` FROM `available-jobtitles`;");
		if (Database::num_of_rows($dbRes) > 0) {
			while ($dbRow = Database::get_assoc_array($dbRes))
				$htmlOut .= '<option value="' . $dbRow['title'] . '">' . $dbRow['title'] . '</option>';
		}
		$htmlOut .= '</select><br />';
		$htmlOut .= '<br /><input type="submit" value="ثبت پرسنل" name="submitAddUnitPersonnel" /></fieldset></form>';

		$res = Database::execute_query("SELECT `id`, `name`, `lastname`, `job-title` FROM  `unit-personnels` WHERE `deleted` = '0' AND `unit-id` = '" . Database::filter_str($_SESSION['unitId']) . "'  ORDER BY `job-title` ASC;");
		$counter = 1;
		if (Database::num_of_rows($res) > 0) {
			$htmlOut .= '<div><fieldset><legend>فهرست پرسنل واحد ' . self::getName() . '</legend>';
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

			$htmlOut .= '</tbody></table></fielset></div>';
		} else
			$htmlOut .= NO_UNIT_PERSONNEL;

		return $htmlOut;
	}

	public static function removeUnitPersonnel($id) {
		//_log process
		try {
			$resP = Database::execute_query("SELECT `unit-id`, `name`, `lastname` FROM `unit-personnels` WHERE `id` = '$id';");
			$rowP = Database::get_assoc_array($resP);
			$resU = Database::execute_query("SELECT `name`, `center-id` FROM `units` WHERE `id` = '" . $rowP['unit-id'] . "';");
			$rowU = Database::get_assoc_array($resU);
			$resC = Database::execute_query("SELECT `name`, `town-id` FROM `centers` WHERE `id` = '" . $rowU['center-id'] . "';");
			$rowC = Database::get_assoc_array($resC);
			$res = Database::execute_query("SELECT `name` FROM `towns` WHERE `id` = '" . $rowC['town-id'] . "';");
			$row = Database::get_assoc_array($res);
		} catch ( exception $e ) {
			return array('err_msg' => $e -> getMessage());
		}
		$_logTXT = 'حذف پرسنل "' . $rowP['name'] . ' ' . $rowP['lastname'] . '" از واحد "' . $rowU['name'] . '" در مرکز "' . $rowC['name'] . '" در شهرستان "' . $row['name'] . '"';
		
		$dateTimeObj = new DateTime();
		$dateTimeZone = new DateTimeZone('Asia/Tehran');
		$dateTimeObj -> setTimezone($dateTimeZone);
		$shamsiDate = gregorian_to_jalali($dateTimeObj -> format('Y'), $dateTimeObj -> format('m'), $dateTimeObj -> format('d'));
		$date = $shamsiDate[0] . "-" . $shamsiDate[1] . "-" . $shamsiDate[2] . " " . $dateTimeObj -> format('H:i:s');
		try {
			Database::execute_query("UPDATE `unit-personnels` SET `deleted` = '1', `date-of-delete` = '$date' WHERE `id` = '" . $id . "';");
			unset($dateTimeObj, $dateTimeZone, $date, $shamsiDate);
		} catch ( exception $e ) {
			return $e -> getMessage();
		}
		FormProccessor::_makeLog($_logTXT);
		
		header("Location: " . $_SERVER['HTTP_REFERER']);
		exit;
		return true;
	}

	public static function editUnitPersonnel($id) {
		$htmlOut = '';
		if (strpos($_SERVER['HTTP_REFERER'], 'es'))
			$_SESSION['ref'] = 1;
		else
			$_SESSION['ref'] = 0;
		$res = Database::execute_query("SELECT * FROM `unit-personnels` WHERE `id` = '$id';");
		$row = Database::get_assoc_array($res);
		$htmlOut .= '<form action="' . substr(strrchr($_SERVER['PHP_SELF'], '/'), 1) . '" method="post" class="narin">';
		$htmlOut .= '<input type="hidden" name="id" value="' . $id . '" />';
		$htmlOut .= '<input type="hidden" name="narin:token" value="' . Narin::create_token() . '" /><fieldset><legend>ویرایش پرسنل</legend>';
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
		if (isset($r['invalid']['input_age']))
			$htmlOut .= ' invalid';
		$htmlOut .= '">';
		$htmlOut .= '<label id="date_picker_label" for="input_age">سال تولد:</label>';
		$htmlOut .= '<input type="text" id="input_age" name="input_age" size="4" maxlength="4" class="date_y" value="' . $row['age'] . '"';
		if (isset($_POST['input_age']) && $_POST['input_age'] !== '')
			$htmlOut .= ' value="' . htmlspecialchars($_POST['input_age']) . '"';
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
		$htmlOut .= "\n<option value=\"female\"";
		if ($row['sex'] == 'female')
			$htmlOut .= ' selected="selected"';
		$htmlOut .= '>زن</option>';
		$htmlOut .= "<option value=\"male\"";
		if ($row['sex'] == 'male')
			$htmlOut .= ' selected="selected"';
		$htmlOut .= '>مرد</option>';
		$htmlOut .= '</select><br />';
		$htmlOut .= '<label for="input_jobtitle">عنوان شغلی:</label>';
		$htmlOut .= '<select name="input_jobtitle" id="input_jobtitle" class="';
		if (isset($r['invalid']['input_jobtitle']))
			$htmlOut .= 'invalid';
		$htmlOut .= '">';
		$dbRes = Database::execute_query("SELECT `title` FROM `available-jobtitles`;");
		if (Database::num_of_rows($dbRes) > 0) {
			while ($dbRow = Database::get_assoc_array($dbRes))
				$htmlOut .= '<option value="' . $dbRow['title'] . '">' . $dbRow['title'] . '</option>';
		}
		$htmlOut .= '</select><br />';
		$htmlOut .= '<br /><input type="submit" value="ثبت پرسنل" name="submitEditUnitPersonnel" /></fieldset></form>';

		return $htmlOut;
	}

	public static function personnelFinancialInfo() {
		$htmlOut = '';
		$htmlOut .= '<p style="margin-right: 20px;"><span style="font-weight: bold;">گام اول</span>: برای مشاهده و تغییر اطلاعات مالی پرسنل موردنظر، ابتدا وی را بیابید.</p>';
		$htmlOut .= '<form action="' . substr(strrchr($_SERVER['PHP_SELF'], '/'), 1) . '?cmd=unitPersonnelFinancialInfo" method="post" class="narin">';
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
		$htmlOut .= '   <input type="submit" value="بیاب" name="submitSearchPersonnel" /></fieldset></form>';

		return $htmlOut;
	}

	public static function personnelSearchResult() {
		$htmlOut = '';
		if (isset($_POST['searchValue']) && trim($_POST['searchValue']) !== '') {
			$res = Database::execute_query("SELECT `id`, `name`, `lastname`, `job-title` FROM  `unit-personnels` WHERE `unit-id` = '" . $_SESSION['unitId'] . "' AND `" . $_POST['searchBy'] . "` LIKE '%" . Database::filter_str($_POST['searchValue']) . "%' ORDER BY `lastname` ASC;");
			$counter = 1;
			if (Database::num_of_rows($res) > 0) {
				$htmlOut .= '<p><br /><span style="font-weight: bold; text-align: right;">گام دوم</span>: برای انتخاب، روی نام پرسنل موردنظر کلیک کنید.</p>';
				$htmlOut .= '<fieldset><legend>اسامی یافت شده</legend>';
				$htmlOut .= '<br /><table align="center" class="contentTable" border="0" cellpadding="0" cellspacing="1">';
				$htmlOut .= '<thead><tr><th>ردیف</th><th>نام</th><th>نام خانوادگی</th><th>رده ی پرسنلی</th></tr></thead><tbody>';
				while ($row = Database::get_assoc_array($res)) {
					$htmlOut .= '<tr><td>' . $counter . '</td><td><a href="?cmd=unitPersonnelFinancialInfo&id=' . $row['id'] . '">' . $row['name'] . '</a></td>';
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

	public static function editUnitPersonnelFinancialInfo($id) {
		$htmlOut = '';
		$htmlOut .= '<p><br /><span style="font-weight: bold; text-align: right;">گام آخر</span>: هزینه ی موردنظر را انتخاب کرده و مبلغ آن را وارد نمایید.</p>';
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
		$htmlOut .= '<input type="submit" value="ثبت هزینه" name="submitRegisterUnitPersonnelCost" /></fieldset></form>';

		return $htmlOut;
	}

	public static function unitServices() {
		$htmlOut = '';
		$htmlOut .= '<p>در این قسمت شما می توانید خدمات موردنظر خود برای واحد "' . self::getName() . '" را تعریف نمایید.</p>';
		$htmlOut .= '<p style="text-align: right; font-weight: bold; font-size: 15px; margin-right: 20px"><a href="#" style="color: green;" id="slideLink" ><img src="../illustrator/images/icons/plus.png" />&nbsp;افزودن خدمت</a></p>';
		$htmlOut .= '<form action="' . substr(strrchr($_SERVER['PHP_SELF'], '/'), 1) . '" method="post" id="slideForm" class="narin"><fieldset><legend>افزودن خدمت به واحد</legend>';
		$htmlOut .= 'نام خدمت: ' . '<select name="input_service_name" class="validate first_opt_not_allowed">';
		$dbRes = Database::execute_query("SELECT `id`, `name` FROM `available-services`;");
		if (Database::num_of_rows($dbRes) > 0) {
			$htmlOut .= '<option>انتخاب کنید...</option>';
			while ($dbRow = Database::get_assoc_array($dbRes))
				$htmlOut .= '<option value="' . $dbRow['id'] . '">' . $dbRow['name'] . '</option>';
		} else
			$htmlOut .= '<option>' . NO_AVAILABLE_SERVICES . '</option>';
		$htmlOut .= '</select>';
		$htmlOut .= '<input type="submit" name="submitAddUnitService" value="ثبت خدمت برای واحد" />';
		$htmlOut .= '</fieldset></form>';
		$res = Database::execute_query("SELECT `unit-services`.`id`, `available-services`.`name` FROM `available-services` INNER JOIN `unit-services` ON `available-services`.`id` = `unit-services`.`service-id` WHERE `unit-id` = '" . Database::filter_str($_SESSION['unitId']) . "' ORDER BY `name` ASC;");
		$counter = 1;
		if (Database::num_of_rows($res) > 0) {
			$htmlOut .= '<div><fieldset><legend>فهرست خدمات واحد ' . self::getName() . '</legend>';
			$htmlOut .= '<br /><table align="right" class="contentTable" border="0" cellpadding="0" cellspacing="1">';
			$htmlOut .= '<thead><tr><th width="40">ردیف</th><th>نام</th><th width="50">حذف</th></thead><tbody>';
			while ($row = Database::get_assoc_array($res)) {
				$htmlOut .= '<tr><td>' . $counter . '</td><td>' . $row['name'] . '</td>';
				$htmlOut .= '<td><a href="?cmd=removeUnitService&unitServiceId=' . $row['id'] . '"><img src="../illustrator/images/icons/remove.png" title="حذف ' . $row['name'] . '" /></a></td>';
				$counter++;
			}

			$htmlOut .= '</tbody></table></fielset></div>';
		} else
			$htmlOut .= NO_UNIT_SERVICE;

		return $htmlOut;
	}

	public static function removeUnitService($id) {
		//_log process
		$resH = Database::execute_query("SELECT * FROM `unit-services` WHERE `id` = '$id';");
		$rowH = Database::get_assoc_array($resH);
		$resS = Database::execute_query("SELECT `name` FROM `available-services` WHERE `id` = '" . $rowH['service-id'] . "';");
		$rowS = Database::get_assoc_array($resS);
		$resU = Database::execute_query("SELECT `name`, `center-id` FROM `units` WHERE `id` = '" . $rowH['unit-id'] . "';");
		$rowU = Database::get_assoc_array($resU);
		$resC = Database::execute_query("SELECT `name`, `town-id` FROM `centers` WHERE `id` = '" . $rowU['center-id'] . "';");
		$rowC = Database::get_assoc_array($resC);
		$res = Database::execute_query("SELECT `name` FROM `towns` WHERE `id` = '" . $rowC['town-id'] . "';");
		$row = Database::get_assoc_array($res);
		$_logTXT = 'حذف خدمت "' . $rowS['name'] . '" از واحد "' . $rowU['name'] . '" در مرکز "' . $rowC['name'] . '" در شهرستان "' . $row['name'] . '"';
		
		try {
			Database::execute_query("DELETE FROM `unit-services` WHERE `id` = '" . $id . "';");
		} catch ( exception $e ) {
			return $e -> getMessage();
		}
		FormProccessor::_makeLog($_logTXT);
		
		header("Location: " . $_SERVER['HTTP_REFERER']);
		exit;
		return true;
	}

	public static function modifyUnitsContent() {
		$htmlOut = '';
		$htmlOut .= '<p>برای اعمال تغییرات مورد نیاز خود روی واحد"' . self::getName() . '"، از منوی سمت راست گزینه موردنظر را انتخاب کنید.</p>';

		return $htmlOut;
	}

}
?>