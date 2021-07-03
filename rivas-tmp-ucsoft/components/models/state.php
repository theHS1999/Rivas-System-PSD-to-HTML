<?php

/**
 *
 * state.php
 * state model class file
 *
 * @copyright	Copyright (C) 2012 Rivas Systems Inc. All rights reserved.
 * @versin	1.00 2011-02-05
 * @author	Behnam Salili
 *
 */

require_once dirname(dirname(__file__)) . '/database/database.php';

class State {
	private static $name;

	public static function setName($name) {
		self::$name = $name;
	}

	public static function getName() {
		return self::$name;
	}

	public static function viewTowns() {
		$htmlOut = '';
		$htmlOut .= '<p style="text-align: right; font-weight: bold; font-size: 15px; margin-right: 20px"><a href="#" style="color: green;" id="slideLink" ><img src="../illustrator/images/icons/plus.png" />&nbsp;افزودن شهرستان</a></p>';
		$htmlOut .= '<form action="' . $_SERVER['PHP_SELF'] . '" method="post" id="slideForm" class="narin">';
		$htmlOut .= '<input type="hidden" name="narin:token" value="' . Narin::create_token() . '" />';
		$htmlOut .= '<fieldset><legend>افزودن شهرستان</legend><br /><label for="input_town_name" class="required">نام شهرستان :</label>';
		$htmlOut .= '<input type="text" name="input_town_name" id="input_town_name" maxlength="256" ';
		if (isset($_POST['input_town_name']) && $_POST['input_town_name'] !== '')
			$htmlOut .= ' value="' . htmlspecialchars($_POST['input_town_name']) . '"';
		$htmlOut .= ' class="validate required ';
		if (isset($r['invalid']['input_town_name']))
			$htmlOut .= 'invalid';
		$htmlOut .= '" />';
		$htmlOut .= '<span class="description" style="display:none;">فقط حروف فارسی</span>';
		$htmlOut .= '&nbsp;&nbsp;&nbsp;<input type="submit" value="ثبت شهرستان" name="submitAddTown" /></fieldset></form>';
		$res = Database::execute_query("SELECT * FROM  `towns` ORDER BY `name` ASC;");
		$counter = 1;
		if (Database::num_of_rows($res) > 0) {
			$htmlOut .= '<p style="margin-right: 20px;">برای ورود به هر شهرستان، بر روی نام آن کلیک نمایید.</p>';
			$htmlOut .= '<fieldset><legend>فهرست شهرستان های استان ' . State::getName() . '</legend>';
			$htmlOut .= '<br /><table align="right" class="contentTable" border="0" cellpadding="0" cellspacing="1">';
			$htmlOut .= '<thead><th width="40">ردیف</th><th>نام</th><th width="70">تعداد مراکز</th><th width="50">حذف</th><th width="50">ویرایش</th></thead><tbody>';
			while ($row = Database::get_assoc_array($res)) {
				$resTmp = Database::execute_query("SELECT * FROM `centers` WHERE `town-id` = '" . $row['id'] . "';");
				$htmlOut .= '<tr><td>' . $counter . '</td><td><a href="?cmd=manageCenters&townId=' . $row['id'] . '">' . $row['name'] . '</a></td>';
				$htmlOut .= '<td>' . Database::num_of_rows($resTmp) . '</td>';
				$htmlOut .= '<td><a href="?cmd=removeTown&townId=' . $row['id'] . '"><img src="../illustrator/images/icons/remove.png" title="حذف شهرستان ' . $row['name'] . '" /></a></td>';
				$htmlOut .= '<td><a href="?cmd=editTown&townId=' . $row['id'] . '"><img src="../illustrator/images/icons/edit.png" title="ویرایش شهرستان ' . $row['name'] . '" /></a></td></tr>';
				$counter++;
			}

			$htmlOut .= '</tbody></table></fielset><br />';
		} else
			$htmlOut .= NO_TOWN_EXISTS;

		return $htmlOut;
	}

	public static function removeTown($id) {
		$res = Database::execute_query("SELECT `name` FROM `towns` WHERE `id` = '$id';");
		$row = Database::get_assoc_array($res);
		$_logTXT = 'حذف شهرستان "' . $row['name'] . '" از سیستم';
		
		try {
			Database::execute_query("DELETE FROM `towns` WHERE `id` = '" . $id . "';");
		} catch ( exception $e ) {
			return $e -> getMessage();
		}
		FormProccessor::_makeLog($_logTXT);
		
		header("Location: " . $_SERVER['HTTP_REFERER']);
		exit;
	}

	public static function editTown($id) {
		$htmlOut = '';
		$dbRes = Database::execute_query("SELECT * FROM `towns` WHERE `id` = '" . $id . "' LIMIT 1;");
		$row = Database::get_assoc_array($dbRes);
		$htmlOut .= '<form action="' . substr(strrchr($_SERVER['PHP_SELF'], '/'), 1) . '" method="post" class="narin">';
		$htmlOut .= '<input type="hidden" name="narin:token" value="' . Narin::create_token() . '" />';
		$htmlOut .= '<input type="hidden" name="old_town" value="' . $id . '" />';
		$htmlOut .= '<fieldset><legend>ویرایش شهرستان</legend><br /><label for="input_town_name" class="required">نام شهرستان :</label>';
		$htmlOut .= '<input type="text" name="input_town_name" id="input_town_name" maxlength="256" ';
		$htmlOut .= ' class="validate required ';
		if (isset($r['invalid']['input_town_name']))
			$htmlOut .= 'invalid';
		$htmlOut .= '" value="' . $row['name'] . '" />';
		$htmlOut .= '<span class="description" style="display:none;">فقط حروف فارسی</span>';
		$htmlOut .= '&nbsp;&nbsp;&nbsp;<input type="submit" value="ویرایش شهرستان" name="submitEditTown" /></fieldset></form>';

		return $htmlOut;

	}

	public static function viewActionsMenu() {

		$htmlOut = '';
		$htmlOut .= '<a href="' . $_SERVER['PHP_SELF'] . '"><img src="../illustrator/images/icons/home.png" />&nbsp;صفحه‌اصلی</a>';
		$htmlOut .= '<a href="?cmd=manageTowns"><img src="../illustrator/images/icons/towns.png" />&nbsp;شهرستان‌ها</a>';
		$htmlOut .= '<a href="?cmd=manageAvailableServices"><img src="../illustrator/images/icons/service.png" />&nbsp;خدمات موجود</a>';
        $htmlOut .= '<a href="?cmd=manageSibJobsAndPeriods"><img src="../illustrator/images/icons/sib.png">&nbsp;مدیریت جزئیات سیب</a>';
		$htmlOut .= '<a href="?cmd=manageAvailableJobTitles"><img src="../illustrator/images/icons/crm.png" />&nbsp;رده‌های پرسنلی</a>';
		$htmlOut .= '<a href="?cmd=manageAvailableDrugs"><img src="../illustrator/images/icons/drug.png" />&nbsp;داروها</a>';
		$htmlOut .= '<a href="?cmd=manageAvailableConsumingEquipments"><img src="../illustrator/images/icons/enjektor.png" />&nbsp;تجهیزات مصرفی</a>';
		$htmlOut .= '<a href="?cmd=manageAvailableNonConsumingEquipments"><img src="../illustrator/images/icons/non_consuming.png" />&nbsp;تجهیزات غیرمصرفی</a>';
		$htmlOut .= '<a href="?cmd=manageGeneralChargesTypes"><img src="../illustrator/images/icons/charge.png" />&nbsp;هزینه‌های عمومی</a>';
		$htmlOut .= '<a href="?cmd=reports"><img src="../illustrator/images/icons/chart-1.png" />&nbsp;گزارشات و نمودارها</a>';
		$htmlOut .= '<a href="?cmd=searchPersonnel"><img src="../illustrator/images/icons/search.png" />&nbsp;جستجوی پرسنل</a>';
		$htmlOut .= '<a href="?cmd=manageUsers"><img src="../illustrator/images/icons/users.png" />&nbsp;مدیریت کاربران</a>';
		$htmlOut .= '<a href="?cmd=manageEditFrequencyRequests"><img src="../illustrator/images/icons/editReq.png" />&nbsp;مدیریت اسناداصلاحی</a>';
		$htmlOut .= '<a href="?cmd=_LOGS"><img src="../illustrator/images/icons/log.png" />&nbsp;وقایع سیستم</a>';
		$htmlOut .= '<a href="?cmd=logOut"><img src="../illustrator/images/icons/exit.png" />&nbsp;خروج از سامانه</a>';
		return $htmlOut;
	}

	public static function manageAvailableServices() {
		$htmlOut = '';
		$htmlOut .= '<p style="margin-right: 20px;">در این قسمت می توانید خدمات موردنیاز برای کل سیستم را تعریف نمایید.</p>';
		$htmlOut .= '<p style="text-align: right; font-weight: bold; font-size: 15px; margin-right: 20px"><a href="#" style="color: green;" id="slideLink" ><img src="../illustrator/images/icons/plus.png" />&nbsp;تعریف خدمت</a></p>';
		$htmlOut .= '<form action="' . substr(strrchr($_SERVER['PHP_SELF'], '/'), 1) . '" id="slideForm" method="post" class="narin">';
		$htmlOut .= '<input type="hidden" name="narin:token" value="' . Narin::create_token() . '" /><fieldset><legend>افزودن خدمت</legend>';
		$htmlOut .= '<label class="required">نوع خدمت:</label>';
		$htmlOut .= '<input type="radio" name="input_service_type" id="service_type_0" value="0"';
		if(isset($_POST['input_service_type']) && $_POST['input_service_type'] == "0")
		    $htmlOut .= 'checked';
		$htmlOut .= '>';
		$htmlOut .= '<label for="service_type_0">عمومی</label>';
        $htmlOut .= '<input type="radio" name="input_service_type" id="service_type_1" value="1" style="margin-right: 10px;"';
        if(isset($_POST['input_service_type']) && $_POST['input_service_type'] == "1")
            $htmlOut .= 'checked';
        $htmlOut .= '>';
        $htmlOut .= '<label for="service_type_1">سیب</label>';
        $htmlOut .= '<script>';
        $htmlOut .= '$(\'input[name="input_service_type"]\').change(function() {';
        $htmlOut .= 'if(this.value === "0") { $("#type-0-form0").show();';
        $htmlOut .= '$("#type-1-form0").hide(); $("#jobTitle1").removeClass("validate");';
        $htmlOut .= '$("#period1").removeClass("validate"); $("#input_service_name1").removeClass("validate");';
        $htmlOut .= '$("#input_service_description1").removeClass("validate"); $("#input_service_code1").removeClass("validate");';
        $htmlOut .= '$("#input_service_cost1").removeClass("validate"); $("#input_average_time1").removeClass("validate");';
        $htmlOut .= '$("#input_service_name0").addClass("validate");';
        $htmlOut .= '$("#input_service_cost0").addClass("validate"); $("#input_average_time0").addClass("validate"); }';
        $htmlOut .= 'else { $("#type-1-form0").show(); $("#type-0-form0").hide(); $("#jobTitle1").addClass("validate");';
        $htmlOut .= '$("#period1").addClass("validate"); $("#input_service_name1").addClass("validate");';
        $htmlOut .= '$("#input_service_description1").addClass("validate"); $("#input_service_code1").addClass("validate");';
        $htmlOut .= '$("#input_service_cost1").addClass("validate"); $("#input_average_time1").addClass("validate");';
        $htmlOut .= '$("#input_service_name0").removeClass("validate");';
        $htmlOut .= '$("#input_service_cost0").removeClass("validate"); $("#input_average_time0").removeClass("validate"); } });';
        $htmlOut .= '</script>';

        //form 0 type 0
        $htmlOut .= '<div id="type-0-form0" style="display: none">';
		$htmlOut .= '<label for="input_service_name0" class="required">نام خدمت:</label>';
		$htmlOut .= '<input type="text" name="input_service_name0" id="input_service_name0" maxlength="255"';
		if (isset($_POST['input_service_name0']) && $_POST['input_service_name0'] !== '')
			$htmlOut .= ' value="' . htmlspecialchars($_POST['input_service_name0']) . '"';
		$htmlOut .= ' class="validate required ';
		if (isset($r['invalid']['input_service_name0']))
			$htmlOut .= ' invalid';
		$htmlOut .= '" />';
		$htmlOut .= '<span class="description"';
		if (!isset($r['invalid']['input_service_name0']))
			$htmlOut .= ' style="display:none;"';
		$htmlOut .= '>فارسی بنویسید</span><br />';
		$htmlOut .= '<br><label for="input_service_cost0" class="required">هزینه ی دریافتی از مشتریان(به ریال):</label>';
		$htmlOut .= '<input type="text" name="input_service_cost0" id="input_service_cost0" dir="ltr"';
		if (isset($_POST['input_service_cost0']) && $_POST['input_service_cost0'] !== '')
			$htmlOut .= ' value="' . htmlspecialchars($_POST['input_service_cost0']) . '"';
		$htmlOut .= ' class="validate required integer';
		if (isset($r['invalid']['input_service_cost0']))
			echo ' invalid';
		$htmlOut .= '" />';
		$htmlOut .= '<span class="description"';
		if (!isset($r['invalid']['input_service_cost0']))
			$htmlOut .= ' style="display:none;"';
		$htmlOut .= '>عدد صحیح، حداقل بزرگتر از 0</span>';
		$htmlOut .= '<script type="text/javascript">$(\'#input_service_cost0\').data(\'num_min\',0).data(\'num_min_inc\',false);</script><br />';
		$htmlOut .= '<label for="input_average_time0" class="required">میانگین زمان صرف شده برای خدمت (به دقیقه):</label>';
		$htmlOut .= '<input type="text" name="input_average_time0" id="input_average_time0" dir="ltr"';
		if (isset($_POST['input_average_time0']) && $_POST['input_average_time0'] !== '')
			$htmlOut .= ' value="' . htmlspecialchars($_POST['input_average_time0']) . '"';
		$htmlOut .= ' class="validate required integer';
		if (isset($r['invalid']['input_average_time0']))
			$htmlOut .= ' invalid';
		$htmlOut .= '" /></div>';

        //form 0 type 1
        $htmlOut .= '<div id="type-1-form0" style="display: none">';
        $htmlOut .= '<label for="jobTitle1" class="required">عنوان شغلی:</label>';
        $jobTitles = Database::execute_query("SELECT * FROM `sib_job_titles` ORDER BY `id` ASC;");

        $htmlOut .= '<select name="jobTitle1" id="jobTitle1"  class="validate">';
        $htmlOut .= '<option>انتخاب کنید...</option>';
        while ($jobTitle = Database::get_assoc_array($jobTitles)) {
            $htmlOut .= '<option value="' . $jobTitle['title'] . '">' . $jobTitle['title'] . '</option>' ;
        }
        $htmlOut .= '</select>';

        $htmlOut .= '<br><label for="period1" class="required">دوره زندگی:</label>';
        $periods = Database::execute_query("SELECT * FROM `sib_periods` ORDER BY `id` ASC");

        $htmlOut .= '<select name="period1" id="period1"  class="validate">';
        $htmlOut .= '<option>انتخاب کنید...</option>';
        while($period = Database::get_assoc_array($periods)) {
            $htmlOut .= '<option value="' . $period['name'] . '">' . $period['name'] . '</option>' ;
        }
        $htmlOut .= '</select>';

        $htmlOut .= '<br><label for="input_service_name1" class="required">نام خدمت:</label>';
        $htmlOut .= '<input type="text" name="input_service_name1" id="input_service_name1" maxlength="255"';
        if (isset($_POST['input_service_name1']) && $_POST['input_service_name1'] !== '')
            $htmlOut .= ' value="' . htmlspecialchars($_POST['input_service_name1']) . '"';
        $htmlOut .= ' class="validate required ';
        if (isset($r['invalid']['input_service_name1']))
            $htmlOut .= ' invalid';
        $htmlOut .= '" />';
        $htmlOut .= '<span class="description"';
        if (!isset($r['invalid']['input_service_name1']))
            $htmlOut .= ' style="display:none;"';
        $htmlOut .= '>فارسی بنویسید</span><br />';
        $htmlOut .= '<label for="input_service_description1" class="required">توضیحات:</label>';
        $htmlOut .= '<input type="text" name="input_service_description1" id="input_service_description1" maxlength="255"';
        if (isset($_POST['input_service_description1']) && $_POST['input_service_description1'] !== '')
            $htmlOut .= ' value="' . htmlspecialchars($_POST['input_service_description1']) . '"';
        $htmlOut .= ' class="validate required ';
        if (isset($r['invalid']['input_service_description1']))
            $htmlOut .= ' invalid';
        $htmlOut .= '" />';
        $htmlOut .= '<span class="description"';
        if (!isset($r['invalid']['input_service_description1']))
            $htmlOut .= ' style="display:none;"';
        $htmlOut .= '>فارسی بنویسید</span><br />';
        $htmlOut .= '<label for="input_service_code1" class="required">کد خدمت:</label>';
        $htmlOut .= '<input type="text" name="input_service_code1" id="input_service_code1" maxlength="255"';
        if (isset($_POST['input_service_code1']) && $_POST['input_service_code1'] !== '')
            $htmlOut .= ' value="' . htmlspecialchars($_POST['input_service_code1']) . '"';
        $htmlOut .= ' class="validate required integer';
        if (isset($r['invalid']['input_service_code1']))
            echo ' invalid';
        $htmlOut .= '" />';
        $htmlOut .= '<span class="description"';
        if (!isset($r['invalid']['input_service_code1']))
            $htmlOut .= ' style="display:none;"';
        $htmlOut .= '>عدد صحیح، حداقل بزرگتر از 0</span>';
        $htmlOut .= '<br><label for="input_service_cost1" class="required">هزینه ی دریافتی از مشتریان(به ریال):</label>';
        $htmlOut .= '<input type="text" name="input_service_cost1" id="input_service_cost1" dir="ltr"';
        if (isset($_POST['input_service_cost1']) && $_POST['input_service_cost1'] !== '')
            $htmlOut .= ' value="' . htmlspecialchars($_POST['input_service_cost1']) . '"';
        $htmlOut .= ' class="validate required integer';
        if (isset($r['invalid']['input_service_cost1']))
            echo ' invalid';
        $htmlOut .= '" />';
        $htmlOut .= '<span class="description"';
        if (!isset($r['invalid']['input_service_cost1']))
            $htmlOut .= ' style="display:none;"';
        $htmlOut .= '>عدد صحیح، حداقل بزرگتر از 0</span>';
        $htmlOut .= '<script type="text/javascript">$(\'#input_service_cost1\').data(\'num_min\',0).data(\'num_min_inc\',false);</script><br />';
        $htmlOut .= '<label for="input_average_time1" class="required">میانگین زمان صرف شده برای خدمت (به دقیقه):</label>';
        $htmlOut .= '<input type="text" name="input_average_time1" id="input_average_time1" dir="ltr"';
        if (isset($_POST['input_average_time1']) && $_POST['input_average_time1'] !== '')
            $htmlOut .= ' value="' . htmlspecialchars($_POST['input_average_time1']) . '"';
        $htmlOut .= ' class="validate required integer';
        if (isset($r['invalid']['input_average_time1']))
            $htmlOut .= ' invalid';
        $htmlOut .= '" /></div>';

		$htmlOut .= '<br /><input type="submit" value="ثبت خدمت" name="submitAddService" /></fieldset></form>';

		$htmlOut .= '<p style="text-align: right; font-weight: bold; font-size: 15px; margin-right: 20px"><a href="#" style="color: #008000;" id="slideLink1" ><img src="../illustrator/images/icons/plus.png" />&nbsp;تعریف و افزودن دسته‌ای خدمت به واحدها</a></p>';
		$htmlOut .= '<form action="' . substr(strrchr($_SERVER['PHP_SELF'], '/'), 1) . '" id="slideForm1" method="post" class="narin">';
		$htmlOut .= '<input type="hidden" name="narin:token" value="' . Narin::create_token() . '" /><fieldset><legend>افزودن خدمت</legend>';
        $htmlOut .= '<label class="required">نوع خدمت:</label>';
        $htmlOut .= '<input type="radio" name="input_service_type1" id="service_type_0" value="0"';
        if(isset($_POST['input_service_type']) && $_POST['input_service_type'] == "0")
            $htmlOut .= 'checked';
        $htmlOut .= '>';
        $htmlOut .= '<label for="service_type_0">عمومی</label>';
        $htmlOut .= '<input type="radio" name="input_service_type1" id="service_type_1" value="1" style="margin-right: 10px;"';
        if(isset($_POST['input_service_type']) && $_POST['input_service_type'] == "1")
            $htmlOut .= 'checked';
        $htmlOut .= '>';
        $htmlOut .= '<label for="service_type_1">سیب</label>';
        $htmlOut .= '<script type="text/javascript">';
        $htmlOut .= '$("input[name=\'input_service_type1\']").change(function() {console.log(this.value);';
        $htmlOut .= 'var ajaxPost1 = $.post("../application/ea-sh-ajax.php", {"unitTypeForAddingService" : this.value});';
        $htmlOut .= 'ajaxPost1.done(function(data) {$("#unitToAddTo0").empty().append(data);});';
        $htmlOut .= 'if(this.value === "0") { $("#type-0-form1").show();';
        $htmlOut .= '$("#type-1-form1").hide(); $("#jobTitle3").removeClass("validate");';
        $htmlOut .= '$("#period3").removeClass("validate"); $("#input_service_name3").removeClass("validate");';
        $htmlOut .= '$("#input_service_description3").removeClass("validate"); $("#input_service_code3").removeClass("validate");';
        $htmlOut .= '$("#input_service_cost3").removeClass("validate"); $("#input_average_time3").removeClass("validate");';
        $htmlOut .= '$("#input_service_name2").addClass("validate");';
        $htmlOut .= '$("#input_service_cost2").addClass("validate"); $("#input_average_time2").addClass("validate"); }';
        $htmlOut .= 'else { $("#type-1-form1").show(); $("#type-0-form1").hide(); $("#jobTitle3").addClass("validate");';
        $htmlOut .= '$("#period3").addClass("validate"); $("#input_service_name3").addClass("validate");';
        $htmlOut .= '$("#input_service_description3").addClass("validate"); $("#input_service_code3").addClass("validate");';
        $htmlOut .= '$("#input_service_cost3").addClass("validate"); $("#input_average_time3").addClass("validate");';
        $htmlOut .= '$("#input_service_name2").removeClass("validate");';
        $htmlOut .= '$("#input_service_cost2").removeClass("validate"); $("#input_average_time2").removeClass("validate"); } });';
        $htmlOut .= '</script>';


        //form 1 type 0
        $htmlOut .= '<div id="type-0-form1" style="display: none">';
        $htmlOut .= '<label for="input_service_name2" class="required">نام خدمت:</label>';
        $htmlOut .= '<input type="text" name="input_service_name2" id="input_service_name2" maxlength="255"';
        if (isset($_POST['input_service_name3']) && $_POST['input_service_name2'] !== '')
            $htmlOut .= ' value="' . htmlspecialchars($_POST['input_service_name2']) . '"';
        $htmlOut .= ' class="validate required ';
        if (isset($r['invalid']['input_service_name2']))
            $htmlOut .= ' invalid';
        $htmlOut .= '" />';
        $htmlOut .= '<span class="description"';
        if (!isset($r['invalid']['input_service_name2']))
            $htmlOut .= ' style="display:none;"';
        $htmlOut .= '>فارسی بنویسید</span><br />';
        $htmlOut .= '<br><label for="input_service_cost2" class="required">هزینه ی دریافتی از مشتریان(به ریال):</label>';
        $htmlOut .= '<input type="text" name="input_service_cost2" id="input_service_cost2" dir="ltr"';
        if (isset($_POST['input_service_cost2']) && $_POST['input_service_cost2'] !== '')
            $htmlOut .= ' value="' . htmlspecialchars($_POST['input_service_cost2']) . '"';
        $htmlOut .= ' class="validate required integer';
        if (isset($r['invalid']['input_service_cost2']))
            echo ' invalid';
        $htmlOut .= '" />';
        $htmlOut .= '<span class="description"';
        if (!isset($r['invalid']['input_service_cost0']))
            $htmlOut .= ' style="display:none;"';
        $htmlOut .= '>عدد صحیح، حداقل بزرگتر از 0</span>';
        $htmlOut .= '<script type="text/javascript">$(\'#input_service_cost0\').data(\'num_min\',0).data(\'num_min_inc\',false);</script><br />';
        $htmlOut .= '<label for="input_average_time2" class="required">میانگین زمان صرف شده برای خدمت (به دقیقه):</label>';
        $htmlOut .= '<input type="text" name="input_average_time2" id="input_average_time2" dir="ltr"';
        if (isset($_POST['input_average_time2']) && $_POST['input_average_time2'] !== '')
            $htmlOut .= ' value="' . htmlspecialchars($_POST['input_average_time2']) . '"';
        $htmlOut .= ' class="validate required integer';
        if (isset($r['invalid']['input_average_time2']))
            $htmlOut .= ' invalid';
        $htmlOut .= '" /></div>';

        //form 1 type 1
        $htmlOut .= '<div id="type-1-form1" style="display: none">';
        $htmlOut .= '<label for="jobTitle3" class="required">عنوان شغلی:</label>';
        $jobTitles1 = Database::execute_query("SELECT * FROM `sib_job_titles` ORDER BY `id` ASC;");

        $htmlOut .= '<select name="jobTitle3" id="jobTitle3"  class="validate">';
        $htmlOut .= '<option>انتخاب کنید...</option>';
        while ($jobTitle1 = Database::get_assoc_array($jobTitles1)) {
            $htmlOut .= '<option value="' . $jobTitle1['title'] . '">' . $jobTitle1['title'] . '</option>' ;
        }
        $htmlOut .= '</select>';

        $htmlOut .= '<br><label for="period3" class="required">دوره زندگی:</label>';
        $periods1 = Database::execute_query("SELECT * FROM `sib_periods` ORDER BY `id` ASC");

        $htmlOut .= '<select name="period3" id="period3"  class="validate">';
        $htmlOut .= '<option>انتخاب کنید...</option>';
        while($period1 = Database::get_assoc_array($periods1)) {
            $htmlOut .= '<option value="' . $period1['name'] . '">' . $period1['name'] . '</option>' ;
        }
        $htmlOut .= '</select>';

        $htmlOut .= '<br><label for="input_service_name3" class="required">نام خدمت:</label>';
        $htmlOut .= '<input type="text" name="input_service_name3" id="input_service_name3" maxlength="255"';
        if (isset($_POST['input_service_name3']) && $_POST['input_service_name3'] !== '')
            $htmlOut .= ' value="' . htmlspecialchars($_POST['input_service_name3']) . '"';
        $htmlOut .= ' class="validate required ';
        if (isset($r['invalid']['input_service_name3']))
            $htmlOut .= ' invalid';
        $htmlOut .= '" />';
        $htmlOut .= '<span class="description"';
        if (!isset($r['invalid']['input_service_name3']))
            $htmlOut .= ' style="display:none;"';
        $htmlOut .= '>فارسی بنویسید</span><br />';
        $htmlOut .= '<label for="input_service_description3" class="required">توضیحات:</label>';
        $htmlOut .= '<input type="text" name="input_service_description3" id="input_service_description3" maxlength="255"';
        if (isset($_POST['input_service_description3']) && $_POST['input_service_description3'] !== '')
            $htmlOut .= ' value="' . htmlspecialchars($_POST['input_service_description3']) . '"';
        $htmlOut .= ' class="validate required ';
        if (isset($r['invalid']['input_service_description3']))
            $htmlOut .= ' invalid';
        $htmlOut .= '" />';
        $htmlOut .= '<span class="description"';
        if (!isset($r['invalid']['input_service_description3']))
            $htmlOut .= ' style="display:none;"';
        $htmlOut .= '>فارسی بنویسید</span><br />';
        $htmlOut .= '<label for="input_service_code3" class="required">کد خدمت:</label>';
        $htmlOut .= '<input type="text" name="input_service_code3" id="input_service_code3" maxlength="255"';
        if (isset($_POST['input_service_code3']) && $_POST['input_service_code3'] !== '')
            $htmlOut .= ' value="' . htmlspecialchars($_POST['input_service_code3']) . '"';
        $htmlOut .= ' class="validate required integer';
        if (isset($r['invalid']['input_service_code3']))
            echo ' invalid';
        $htmlOut .= '" />';
        $htmlOut .= '<span class="description"';
        if (!isset($r['invalid']['input_service_code3']))
            $htmlOut .= ' style="display:none;"';
        $htmlOut .= '>عدد صحیح، حداقل بزرگتر از 0</span>';
        $htmlOut .= '<br><label for="input_service_cost3" class="required">هزینه ی دریافتی از مشتریان(به ریال):</label>';
        $htmlOut .= '<input type="text" name="input_service_cost3" id="input_service_cost3" dir="ltr"';
        if (isset($_POST['input_service_cost1']) && $_POST['input_service_cost3'] !== '')
            $htmlOut .= ' value="' . htmlspecialchars($_POST['input_service_cost3']) . '"';
        $htmlOut .= ' class="validate required integer';
        if (isset($r['invalid']['input_service_cost3']))
            echo ' invalid';
        $htmlOut .= '" />';
        $htmlOut .= '<span class="description"';
        if (!isset($r['invalid']['input_service_cost3']))
            $htmlOut .= ' style="display:none;"';
        $htmlOut .= '>عدد صحیح، حداقل بزرگتر از 0</span>';
        $htmlOut .= '<script type="text/javascript">$(\'#input_service_cost3\').data(\'num_min\',0).data(\'num_min_inc\',false);</script><br />';
        $htmlOut .= '<label for="input_average_time3" class="required">میانگین زمان صرف شده برای خدمت (به دقیقه):</label>';
        $htmlOut .= '<input type="text" name="input_average_time3" id="input_average_time3" dir="ltr"';
        if (isset($_POST['input_average_time3']) && $_POST['input_average_time3'] !== '')
            $htmlOut .= ' value="' . htmlspecialchars($_POST['input_average_time3']) . '"';
        $htmlOut .= ' class="validate required integer';
        if (isset($r['invalid']['input_average_time3']))
            $htmlOut .= ' invalid';
        $htmlOut .= '" /></div>';

		$htmlOut .= '<div><label for="unitToAddTo0" class="required">افزودن به تمام واحدهای:</label>';
		$htmlOut .= '<select name="unitToAddTo" id="unitToAddTo0" class="validate">';
        $htmlOut .= '<option>نوع خدمات خود را انتخاب کنید</option>';
		$htmlOut .= '</select></div>';
		$htmlOut .= '<br /><input type="submit" value="ثبت دسته ای خدمت" name="submitBatchAddService" /></fieldset></form><br>';

        $htmlOut .= '<div><form action="' . substr(strrchr($_SERVER['PHP_SELF'], '/'), 1) . '"method="post" class="narin">';
        $htmlOut .= '<input type="hidden" name="narin:token" value="' . Narin::create_token() . '" />';
        $htmlOut .= '<label for="input_search_name">جستجو: </label>';
        $htmlOut .= '<input type="text" name="input_search_name" id="input_search_name"';
        if(isset($_GET['s']) && strlen($_GET['s']) > 0) $htmlOut .= ' value="' . htmlspecialchars($_GET['s']) . '"';
        $htmlOut .= '><input type="submit" value="جستجو" name="submitSearchServices">';
        $htmlOut .= '</form></div><br>';



        $startPoint = isset($_GET['p']) && $_GET['p'] > 1 ? (20 * ($_GET['p'] - 1)) - 1 : 0;

        if(isset($_GET['s']) && strlen($_GET['s']) > 0) {
            $res = Database::execute_query("SELECT `service_type`, `id`, `name`, `service_code` FROM `available-services` WHERE `name` LIKE '%"
                . $_GET['s'] . "%' ORDER BY `name` ASC LIMIT 20 OFFSET " . $startPoint . ";");
        } else {
            $res = Database::execute_query("SELECT `service_type`, `id`, `name`, `service_code` FROM `available-services` ORDER BY `name` ASC LIMIT 20 OFFSET "
                . $startPoint . ";");
        }
		$counter = 1;
		if (Database::num_of_rows($res) > 0) {
			$htmlOut .= '<div><fieldset><legend>فهرست خدمات موجود</legend>';

			$htmlOut .= '<br /><table align="right" class="contentTable" border="0" cellpadding="0" cellspacing="1">';
			$htmlOut .= '<thead><tr><th width="40">ردیف</th><th width="60">نوع خدمت</th><th>نام</th><th width="50">کد خدمت</th><th width="50">حذف</th><th width="50">ویرایش</th></thead><tbody>';
			while ($row = Database::get_assoc_array($res)) {

			    $type = '';
			    switch($row['service_type']) {
                    case '1':
                        $type = 'سیب';
                        break;
                    default:
                        $type = 'عمومی';
                        break;
                }
                $rowCounter = (20 * ($_GET['p'] - 1)) + $counter;
                $htmlOut .= '<tr><td>' . $rowCounter . '</td><td>' . $type . '</td><td>' . $row['name'] . '</td>';
			    $serviceCode = $row['service_code'] ? $row['service_code'] : "-";
                $htmlOut .= '<td>' . $serviceCode . '</td>';
				$htmlOut .= '<td><a href="?cmd=removeService&serviceId=' . $row['id'] . '"><img src="../illustrator/images/icons/remove.png" title="حذف خدمت ' . $row['name'] . '" /></a></td>';
				$htmlOut .= '<td><a href="?cmd=editService&serviceId=' . $row['id'] . '"><img src="../illustrator/images/icons/edit.png" title="ویریش خدمت ' . $row['name'] . '" /></a></td></tr>';
				$counter++;
			}

			$htmlOut .= '</tbody></table></fielset></div>';


            $currentPage = $_GET['p'];
            $range = 2;




            if(isset($_GET['s']) && strlen($_GET['s']) > 0) {
                $list = Database::execute_query("SELECT `service_type`, `id`, `name`, `service_code` FROM `available-services` WHERE `name` LIKE '%" . $_GET['s'] . "%' ORDER BY `name` ASC;");
                $totalPages = ceil(Database::num_of_rows($list) / 20);

                if ($currentPage > 1 && $totalPages != 1) {
                    // show << link to go back to page 1
                    $htmlOut .= " <a href='{$_SERVER['PHP_SELF']}?cmd=manageAvailableServices&s={$_GET['s']}&p=1' class='pagination'><<</a> ";
                    // get previous page num
                    $prevPage = $currentPage - 1;
                    // show < link to go back to 1 page
                    $htmlOut .= " <a href='{$_SERVER['PHP_SELF']}?cmd=manageAvailableServices&s={$_GET['s']}&p=$prevPage' class='pagination'><</a> ";
                } // end if

                // loop to show links to range of pages around current page
                for ($x = ($currentPage - $range); $x < (($currentPage + $range) + 1); $x++) {
                    // if it's a valid page number...
                    if (($x > 0) && ($x <= $totalPages)) {
                        // if we're on current page...
                        if ($x == $currentPage) {
                            // 'highlight' it but don't make a link
                            $htmlOut .= " [<b>$x</b>] ";
                            // if not current page...
                        } else {
                            // make it a link
                            $htmlOut .= " <a href='{$_SERVER['PHP_SELF']}?cmd=manageAvailableServices&p=$x' class='pagination'>$x</a> ";
                        } // end else
                    } // end if
                } // end for

                // if not on last page, show forward and last page links
                if ($currentPage != $totalPages && $totalPages != 1) {
                    // get next page
                    $nextPage = $currentPage + 1;
                    // echo forward link for next page
                    $htmlOut .= " <a href='{$_SERVER['PHP_SELF']}?cmd=manageAvailableServices&s={$_GET['s']}&p=$nextPage' class='pagination'>></a> ";
                    // echo forward link for last page
                    $htmlOut .= " <a href='{$_SERVER['PHP_SELF']}?cmd=manageAvailableServices&s={$_GET['s']}&p=$totalPages' class='pagination'>>></a> ";
                } // end if
            } else {
                $list = Database::execute_query("SELECT `service_type`, `id`, `name`, `service_code` FROM `available-services` ORDER BY `name` ASC ;");
                $totalPages = ceil(Database::num_of_rows($list) / 20);

                if ($currentPage > 1 && $totalPages != 1) {
                    // show << link to go back to page 1
                    $htmlOut .= " <a href='{$_SERVER['PHP_SELF']}?cmd=manageAvailableServices&p=1' class='pagination'><<</a> ";
                    // get previous page num
                    $prevPage = $currentPage - 1;
                    // show < link to go back to 1 page
                    $htmlOut .= " <a href='{$_SERVER['PHP_SELF']}?cmd=manageAvailableServices&p=$prevPage' class='pagination'><</a> ";
                } // end if

                // loop to show links to range of pages around current page
                for ($x = ($currentPage - $range); $x < (($currentPage + $range) + 1); $x++) {
                    // if it's a valid page number...
                    if (($x > 0) && ($x <= $totalPages)) {
                        // if we're on current page...
                        if ($x == $currentPage) {
                            // 'highlight' it but don't make a link
                            $htmlOut .= " [<b>$x</b>] ";
                            // if not current page...
                        } else {
                            // make it a link
                            $htmlOut .= " <a href='{$_SERVER['PHP_SELF']}?cmd=manageAvailableServices&p=$x' class='pagination'>$x</a> ";
                        } // end else
                    } // end if
                } // end for

                // if not on last page, show forward and last page links
                if ($currentPage != $totalPages && $totalPages != 1) {
                    // get next page
                    $nextPage = $currentPage + 1;
                    // echo forward link for next page
                    $htmlOut .= " <a href='{$_SERVER['PHP_SELF']}?cmd=manageAvailableServices&p=$nextPage' class='pagination'>></a> ";
                    // echo forward link for last page
                    $htmlOut .= " <a href='{$_SERVER['PHP_SELF']}?cmd=manageAvailableServices&p=$totalPages' class='pagination'>>></a> ";
                } // end if
            }



		} else
			$htmlOut .= NO_AVAILABLE_SERVICES;

		return $htmlOut;
	}

	public static function removeService($id) {
		$res = Database::execute_query("SELECT `name` FROM `available-services` WHERE `id` = '$id';");
		$row = Database::get_assoc_array($res);
		$_logTXT = 'حذف تعریف خدمت "' . $row['name'] . '" از سیستم';
		
		try {
			Database::execute_query("DELETE FROM `available-services` WHERE `id` = '" . $id . "';");
		} catch ( exception $e ) {
			return $e -> getMessage();
		}
		//FormProccessor::_makeLog($_logTXT);
		
		header("Location: " . substr(strrchr($_SERVER['PHP_SELF'], '/'), 1) . "?cmd=manageAvailableServices");
		exit;
		return true;
	}

	public static function editService($id) {
		$htmlOut = '';
		$dbRes = Database::execute_query("SELECT * FROM `available-services` WHERE `id` = '" . $id . "';");
		while ($row = Database::get_assoc_array($dbRes))
			$tmp = $row;
		if($tmp['sib_service_id'] > 0) {
		    $res2 = Database::execute_query("SELECT * FROM `sib_services` WHERE `id` = '" . $tmp['sib_service_id'] . "';");
		    while($row = Database::get_assoc_array($res2))
                $sib = $row;
        }
		$htmlOut .= '<form action="' . substr(strrchr($_SERVER['PHP_SELF'], '/'), 1) . '" method="post" class="narin">';
		$htmlOut .= '<input type="hidden" name="old_service" value="' . $id . '" />';
		$htmlOut .= '<input type="hidden" name="narin:token" value="' . Narin::create_token() . '" /><fieldset><legend>ویرایش خدمت</legend>';
        $htmlOut .= '<label class="required">نوع خدمت:</label>';
        $htmlOut .= '<input type="radio" name="input_service_type" id="service_type_0" value="0"';
        if((isset($_POST['input_service_type']) && $_POST['input_service_type'] == "0") || $tmp['service_type'] == "0")
            $htmlOut .= 'checked';
        $htmlOut .= '>';
        $htmlOut .= '<label for="service_type_0">عمومی</label>';
        $htmlOut .= '<input type="radio" name="input_service_type" id="service_type_1" value="1" style="margin-right: 10px;"';
        if((isset($_POST['input_service_type']) && $_POST['input_service_type'] == "1") || $tmp['service_type'] == "1")
            $htmlOut .= 'checked';
        $htmlOut .= '>';
        $htmlOut .= '<label for="service_type_1">سیب</label>';

        $htmlOut .= '<script type="text/javascript">';
        $htmlOut .= '$(\'input[name="input_service_type"]\').change(function() {';
        $htmlOut .= 'if(this.value === "0") { $("#type-0").show();';
        $htmlOut .= '$("#type-1").hide(); $("#jobTitle1").removeClass("validate");';
        $htmlOut .= '$("#period1").removeClass("validate"); $("#input_service_name1").removeClass("validate");';
        $htmlOut .= '$("#input_service_description1").removeClass("validate"); $("#input_service_code1").removeClass("validate");';
        $htmlOut .= '$("#input_service_cost1").removeClass("validate"); $("#input_average_time1").removeClass("validate");';
        $htmlOut .= '$("#input_service_name0").addClass("validate");';
        $htmlOut .= '$("#input_service_cost0").addClass("validate"); $("#input_average_time0").addClass("validate"); }';
        $htmlOut .= 'else { $("#type-1").show(); $("#type-0").hide(); $("#jobTitle1").addClass("validate");';
        $htmlOut .= '$("#period1").addClass("validate"); $("#input_service_name1").addClass("validate");';
        $htmlOut .= '$("#input_service_description1").addClass("validate"); $("#input_service_code1").addClass("validate");';
        $htmlOut .= '$("#input_service_cost1").addClass("validate"); $("#input_average_time1").addClass("validate");';
        $htmlOut .= '$("#input_service_name0").removeClass("validate");';
        $htmlOut .= '$("#input_service_cost0").removeClass("validate"); $("#input_average_time0").removeClass("validate"); } });';
        $htmlOut .= '</script>';

        //type 0
        $type0display = $tmp['service_type'] == "1" ? 'none' : 'block';
        $validations0 = $tmp['service_type'] == "0" ? "validate" : "";
        $htmlOut .= '<div id="type-0" style="display: ' . $type0display . '">';
        $htmlOut .= '<label for="input_service_name0" class="required">نام خدمت:</label>';
        $htmlOut .= '<input type="text" name="input_service_name0" id="input_service_name0" maxlength="255" value="' . $tmp['name'] . '"';
        $htmlOut .= ' class="' . $validations0 . ' required ';
        if (isset($r['invalid']['input_service_name0']))
            $htmlOut .= ' invalid';
        $htmlOut .= '" />';
        $htmlOut .= '<span class="description"';
        if (!isset($r['invalid']['input_service_name0']))
            $htmlOut .= ' style="display:none;"';
        $htmlOut .= '>فارسی بنویسید</span><br />';
        $htmlOut .= '<label for="input_service_cost0" class="required">هزینه ی خدمت(به ریال):</label>';
        $htmlOut .= '<input type="text" name="input_service_cost0" id="input_service_cost0" maxlength="8" dir="ltr" value="' . $tmp['cost'] . '"';
        $htmlOut .= ' class="' . $validations0 . ' required integer';
        if (isset($r['invalid']['input_service_cost0']))
            echo ' invalid';
        $htmlOut .= '" />';
        $htmlOut .= '<span class="description"';
        if (!isset($r['invalid']['input_service_cost0']))
            $htmlOut .= ' style="display:none;"';
        $htmlOut .= '>عدد صحیح، حداقل بزرگتر از 0 و حداکثر 99999999</span>';
        $htmlOut .= '<script type="text/javascript">$(\'#input_service_cost0\').data(\'num_min\',0).data(\'num_min_inc\',false).data(\'num_max\',99999999).data(\'num_max_inc\',true);</script><br />';
        $htmlOut .= '<label for="input_average_time0" class="required">میانگین زمان صرف شده برای خدمت (به دقیقه):</label>';
        $htmlOut .= '<input type="text" name="input_average_time0" id="input_average_time0" maxlength="4" dir="ltr" value="' . $tmp['average-time'] . '"';
        $htmlOut .= ' class="' . $validations0 . ' required integer';
        if (isset($r['invalid']['input_average_time0']))
            $htmlOut .= ' invalid';
        $htmlOut .= '" />';
        $htmlOut .= '<span class="description"';
        if (!isset($r['invalid']['input_average_time0']))
            $htmlOut .= ' style="display:none;"';

        $htmlOut .= '>عدد صحیح، حداقل بزرگتر از 0 و حداکثر 2000</span>';
        $htmlOut .= '<script type="text/javascript">$(\'#input_average_time0\').data(\'num_min\',0).data(\'num_min_inc\',false).data(\'num_max\',2000).data(\'num_max_inc\',true);</script><br /></div>';


        //type 1
        $type1display = $tmp['service_type'] == "1" ? 'block' : 'none';
        $validations1 = $tmp['service_type'] == "1" ? 'validate' : '';
        $htmlOut .= '<div id="type-1" style="display: ' . $type1display . '">';
        $htmlOut .= '<label for="jobTitle1" class="required">عنوان شغلی:</label>';
        $jobTitles = Database::execute_query("SELECT * FROM `sib_job_titles` ORDER BY `id` ASC");

        $htmlOut .= '<select name="jobTitle1" id="jobTitle1"  class="' . $validations1 . '">';
        $htmlOut .= '<option>انتخاب کنید...</option>';
        while($jobTitle = Database::get_assoc_array($jobTitles)) {
            $htmlOut .= '<option value="' . $jobTitle['title'] . '"';
            if($tmp['sib_service_id'] > 0 && $sib['job_title'] == $jobTitle['title']) $htmlOut .= 'selected';
            $htmlOut .= '>' . $jobTitle['title'] . '</option>';
        }
        $htmlOut .= '</select>';

        $htmlOut .= '<br><label for="period1" class="required">دوره زندگی:</label>';
        $periods = Database::execute_query("SELECT * FROM `sib_periods` ORDER BY `id` ASC");

        $htmlOut .= '<select name="period1" id="period1"  class="' . $validations1 . '">';
        $htmlOut .= '<option>انتخاب کنید...</option>';
        while($period = Database::get_assoc_array($periods)) {
            $htmlOut .= '<option value="' . $period['name'] . '"';
            if($tmp['sib_service_id'] > 0 && $sib['period'] == $period['name']) $htmlOut .= 'selected';
            $htmlOut .= '>' . $period['name'] . '</option>';
        }
        $htmlOut .= '</select>';
        $htmlOut .= '<br><label for="input_service_name1" class="required">نام خدمت:</label>';
        $htmlOut .= '<input type="text" name="input_service_name1" id="input_service_name1" maxlength="255" value="' . $tmp['name'] . '"';
        $htmlOut .= ' class="' . $validations1 . ' required ';
        if (isset($r['invalid']['input_service_name1']))
            $htmlOut .= ' invalid';
        $htmlOut .= '" />';
        $htmlOut .= '<span class="description"';
        if (!isset($r['invalid']['input_service_name1']))
            $htmlOut .= ' style="display:none;"';
        $htmlOut .= '>فارسی بنویسید</span><br />';
        $htmlOut .= '<label for="input_service_description1" class="required">توضیحات:</label>';
        $description = $tmp['sib_service_id'] > 0 ? $sib['description'] : '';
        $htmlOut .= '<input type="text" name="input_service_description1" id="input_service_description1" maxlength="255" value="' . $description . '"';
        if (isset($_POST['input_service_description1']) && $_POST['input_service_description1'] !== '')
            $htmlOut .= ' value="' . htmlspecialchars($_POST['input_service_description3']) . '"';
        $htmlOut .= ' class="' . $validations1 . ' required ';
        if (isset($r['invalid']['input_service_description1']))
            $htmlOut .= ' invalid';
        $htmlOut .= '" />';
        $htmlOut .= '<span class="description"';
        if (!isset($r['invalid']['input_service_description1']))
            $htmlOut .= ' style="display:none;"';
        $htmlOut .= '>فارسی بنویسید</span><br />';
        $htmlOut .= '<label for="input_service_code1" class="required">کد خدمت:</label>';
        $htmlOut .= '<input type="text" name="input_service_code1" id="input_service_code1" maxlength="255" value="' . $tmp['service_code'] . '"';
        if (isset($_POST['input_service_code1']) && $_POST['input_service_code1'] !== '')
            $htmlOut .= ' value="' . htmlspecialchars($_POST['input_service_code1']) . '"';
        $htmlOut .= ' class="' . $validations1 . ' required integer';
        if (isset($r['invalid']['input_service_code1']))
            echo ' invalid';
        $htmlOut .= '" />';
        $htmlOut .= '<span class="description"';
        if (!isset($r['invalid']['input_service_code1']))
            $htmlOut .= ' style="display:none;"';
        $htmlOut .= '>عدد صحیح، حداقل بزرگتر از 0</span>';
        $htmlOut .= '<label for="input_service_cost1" class="required">هزینه ی خدمت(به ریال):</label>';
        $htmlOut .= '<input type="text" name="input_service_cost1" id="input_service_cost1" maxlength="8" dir="ltr" value="' . $tmp['cost'] . '"';
        $htmlOut .= ' class="' . $validations1 . ' required integer';
        if (isset($r['invalid']['input_service_cost1']))
            echo ' invalid';
        $htmlOut .= '" />';
        $htmlOut .= '<span class="description"';
        if (!isset($r['invalid']['input_service_cost1']))
            $htmlOut .= ' style="display:none;"';
        $htmlOut .= '>عدد صحیح، حداقل بزرگتر از 0 و حداکثر 99999999</span>';
        $htmlOut .= '<script type="text/javascript">$(\'#input_service_cost1\').data(\'num_min\',0).data(\'num_min_inc\',false).data(\'num_max\',99999999).data(\'num_max_inc\',true);</script><br />';
        $htmlOut .= '<label for="input_average_time1" class="required">میانگین زمان صرف شده برای خدمت (به دقیقه):</label>';
        $htmlOut .= '<input type="text" name="input_average_time1" id="input_average_time1" maxlength="4" dir="ltr" value="' . $tmp['average-time'] . '"';
        $htmlOut .= ' class="' . $validations1 . ' required integer';
        if (isset($r['invalid']['input_average_time1']))
            $htmlOut .= ' invalid';
        $htmlOut .= '" />';
        $htmlOut .= '<span class="description"';
        if (!isset($r['invalid']['input_average_time1']))
            $htmlOut .= ' style="display:none;"';
        $htmlOut .= '>عدد صحیح، حداقل بزرگتر از 0 و حداکثر 2000</span>';
        $htmlOut .= '<script type="text/javascript">$(\'#input_average_time1\').data(\'num_min\',0).data(\'num_min_inc\',false).data(\'num_max\',2000).data(\'num_max_inc\',true);</script><br /></div>';

		$htmlOut .= '<br><input type="submit" value="ویرایش خدمت" name="submitEditService" /></fieldset></form>';

		return $htmlOut;
	}

	public static function manageAvailableJobTitles() {
		$htmlOut = '';
		$htmlOut .= '<p style="margin-right: 20px;">در این قسمت می توانید رده های پرسنلی موردنیاز برای کل سیستم را تعریف نمایید.</p>';
		$htmlOut .= '<p style="text-align: right; font-weight: bold; font-size: 15px; margin-right: 20px"><a href="#" style="color: green;" id="slideLink" ><img src="../illustrator/images/icons/plus.png" />&nbsp;افزودن رده‌پرسنلی</a></p>';
		$htmlOut .= '<form action="' . substr(strrchr($_SERVER['PHP_SELF'], '/'), 1) . '" id="slideForm" method="post" class="narin">';
		$htmlOut .= '<input type="hidden" name="narin:token" value="' . Narin::create_token() . '" /><fieldset><legend>افزودن رده پرسنلی</legend>';
		$htmlOut .= '<label for="input_jobtitle" class="required">عنوان رده پرسنلی:</label>';
		$htmlOut .= '<input type="text" name="input_jobtitle" id="input_jobtitle" maxlength="255"';
		if (isset($_POST['input_jobtitle']) && $_POST['input_jobtitle'] !== '')
			$htmlOut .= ' value="' . htmlspecialchars($_POST['input_jobtitle']) . '"';
		$htmlOut .= ' class="validate required ';
		if (isset($r['invalid']['input_jobtitle']))
			$htmlOut .= ' invalid';
		$htmlOut .= '" />';
		$htmlOut .= '<span class="description"';
		if (!isset($r['invalid']['input_jobtitle']))
			$htmlOut .= ' style="display:none;"';
		$htmlOut .= '>فارسی بنویسید</span>';
		$htmlOut .= '<input type="submit" value="ثبت رده پرسنلی" name="submitAddJobTitle" /></fieldset></form>';

		$res = Database::execute_query("SELECT `id`, `title` FROM `available-jobtitles`;");
		$counter = 1;
		if (Database::num_of_rows($res) > 0) {
			$htmlOut .= '<div><fieldset><legend>فهرست خدمات موجود</legend>';
			$htmlOut .= '<br /><table align="right" class="contentTable" border="0" cellpadding="0" cellspacing="1">';
			$htmlOut .= '<thead><tr><th width="40">ردیف</th><th>عنوان</th><th width="50">حذف</th><th width="50">ویرایش</th></thead><tbody>';
			while ($row = Database::get_assoc_array($res)) {
				$htmlOut .= '<tr><td>' . $counter . '</td><td>' . $row['title'] . '</td>';
				$htmlOut .= '<td><a href="?cmd=removeJobtitle&jobtitleId=' . $row['id'] . '"><img src="../illustrator/images/icons/remove.png" title="حذف رده پرسنلی ' . $row['title'] . '" /></a></td>';
				$htmlOut .= '<td><a href="?cmd=editJobtitle&jobtitleId=' . $row['id'] . '"><img src="../illustrator/images/icons/edit.png" title="ویریش رده پرسنلی ' . $row['title'] . '" /></a></td></tr>';
				$counter++;
			}

			$htmlOut .= '</tbody></table></fielset></div>';
		} else
			$htmlOut .= NO_AVAILABLE_JOBTITLES;

		return $htmlOut;
	}

	public static function removeJobTitle($id) {
		$res = Database::execute_query("SELECT `title` FROM `available-jobtitles` WHERE `id` = '$id';");
		$row = Database::get_assoc_array($res);
		$_logTXT = 'حذف تعریف رده‌پرسنلی "' . $row['title'] . '" از سیستم';
		
		try {
			Database::execute_query("DELETE FROM `available-jobtitles` WHERE `id` = '" . $id . "';");
		} catch ( exception $e ) {
			return $e -> getMessage();
		}
		//FormProccessor::_makeLog($_logTXT);
		
		header("Location: " . substr(strrchr($_SERVER['PHP_SELF'], '/'), 1) . "?cmd=manageAvailableJobTitles");
		exit;
		return true;
	}

	public static function editJobTitle($id) {
		$htmlOut = '';
		$dbRes = Database::execute_query("SELECT `title` FROM `available-jobtitles` WHERE `id` = '" . $id . "' LIMIT 1;");
		while ($row = Database::get_assoc_array($dbRes))
			$title = $row['title'];
		$htmlOut .= '<form action="' . substr(strrchr($_SERVER['PHP_SELF'], '/'), 1) . '" method="post" class="narin">';
		$htmlOut .= '<input type="hidden" name="old_jobtitle" value="' . $id . '" />';
		$htmlOut .= '<input type="hidden" name="narin:token" value="' . Narin::create_token() . '" /><fieldset><legend>ویرایش رده پرسنلی</legend>';
		$htmlOut .= '<label for="input_jobtitle" class="required">عنوان رده پرسنلی:</label>';
		$htmlOut .= '<input type="text" name="input_jobtitle" id="input_jobtitle" maxlength="255" value="' . $title . '"';
		$htmlOut .= ' class="validate required ';
		if (isset($r['invalid']['input_jobtitle']))
			$htmlOut .= ' invalid';
		$htmlOut .= '" />';
		$htmlOut .= '<span class="description"';
		if (!isset($r['invalid']['input_jobtitle']))
			$htmlOut .= ' style="display:none;"';
		$htmlOut .= '>فارسی بنویسید</span>';
		$htmlOut .= '<input type="submit" value="ویرایش رده پرسنلی" name="submitEditJobTitle" /></fieldset></form>';

		return $htmlOut;
	}

	public static function manageAvailableDrugs() {
		$htmlOut = '';
		$htmlOut .= '<p style="margin-right: 20px;">در این قسمت می توانید داروهای موردنیاز برای کل سیستم را تعریف نمایید.</p>';
		$htmlOut .= '<p style="text-align: right; font-weight: bold; font-size: 15px; margin-right: 20px"><a href="#" style="color: green;" id="slideLink" ><img src="../illustrator/images/icons/plus.png" />&nbsp;افزودن دارو</a></p>';
		$htmlOut .= '<form action="' . substr(strrchr($_SERVER['PHP_SELF'], '/'), 1) . '" method="post" id="slideForm" class="narin">';
		$htmlOut .= '<input type="hidden" name="narin:token" value="' . Narin::create_token() . '" /><fieldset><legend>افزودن دارو</legend>';
		$htmlOut .= '<br /><label for="input_drug_name" class="required">نام:</label>';
		$htmlOut .= '<input type="text" name="input_drug_name" id="input_drug_name" maxlength="255"';
		if (isset($_POST['input_drug_name']) && $_POST['input_drug_name'] !== '')
			$htmlOut .= ' value="' . htmlspecialchars($_POST['input_drug_name']) . '"';
		$htmlOut .= ' class="validate required ';
		if (isset($r['invalid']['input_drug_name']))
			$htmlOut .= ' invalid';
		$htmlOut .= '" />';
		$htmlOut .= '<span class="description"';
		if (!isset($r['invalid']['input_drug_name']))
			$htmlOut .= ' style="display:none;"';
		$htmlOut .= '>فارسی بنویسید</span><br />';
		$htmlOut .= '<label for="input_drug_shape" class="required">شکل:</label>';
		$htmlOut .= '<input type="text" name="input_drug_shape" id="input_drug_shape" maxlength="255"';
		if (isset($_POST['input_drug_shape']) && $_POST['input_drug_shape'] !== '')
			$htmlOut .= ' value="' . htmlspecialchars($_POST['input_drug_shape']) . '"';
		$htmlOut .= ' class="validate required ';
		if (isset($r['invalid']['input_drug_shape']))
			$htmlOut .= ' invalid';
		$htmlOut .= '" />';
		$htmlOut .= '<span class="description"';
		if (!isset($r['invalid']['input_drug_shape']))
			$htmlOut .= ' style="display:none;"';
		$htmlOut .= '>فارسی بنویسید</span><br />';
		$htmlOut .= '<label for="input_drug_cost">قیمت واحد (به ریال):</label>';
		$htmlOut .= '<input type="text" name="input_drug_cost" id="input_drug_cost" dir="ltr"';
		if (isset($_POST['input_drug_cost']) && $_POST['input_drug_cost'] !== '')
			$htmlOut .= ' value="' . htmlspecialchars($_POST['input_drug_cost']) . '"';
		$htmlOut .= ' class="validate integer';
		if (isset($r['invalid']['input_drug_cost']))
			$htmlOut .= ' invalid';
		$htmlOut .= '" />';
		$htmlOut .= '<span class="description"';
		if (!isset($r['invalid']['input_drug_cost']))
			$htmlOut .= ' style="display:none;"';
		$htmlOut .= '>عدد صحیح، حداقل بزرگتر از 0 و حداکثر 99999999</span>';
		$htmlOut .= '<script type="text/javascript">$(\'#input_drug_cos\').data(\'num_min\',0).data(\'num_min_inc\',false);</script><br />';
		$htmlOut .= '<input type="checkbox" name="isFree" id="isFree" />';
		$htmlOut .= '<label for="isFree">رایگان</label><br /><br />';
		$htmlOut .= '<input type="submit" value="ثبت دارو" name="submitAddDrug" /></fieldset></form>';

		$res = Database::execute_query("SELECT `id`, `name` FROM `available-drugs-and-consuming-equipments` WHERE `is_drug` = 'yes' ORDER BY `name` ASC;");
		$counter = 1;
		if (Database::num_of_rows($res) > 0) {
			$htmlOut .= '<br /><div><fieldset><legend>فهرست داروهای موجود</legend>';
			$htmlOut .= '<br /><table align="right" class="contentTable" border="0" cellpadding="0" cellspacing="1">';
			$htmlOut .= '<thead><tr><th width="40">ردیف</th><th>عنوان</th><th width="50">حذف</th><th width="50">ویرایش</th></thead><tbody>';
			while ($row = Database::get_assoc_array($res)) {
				$htmlOut .= '<tr><td>' . $counter . '</td><td>' . $row['name'] . '</td>';
				$htmlOut .= '<td><a href="?cmd=removeDrug&drugId=' . $row['id'] . '"><img src="../illustrator/images/icons/remove.png" title="حذف  ' . $row['name'] . '" /></a></td>';
				$htmlOut .= '<td><a href="?cmd=editDrug&drugId=' . $row['id'] . '"><img src="../illustrator/images/icons/edit.png" title="ویرایش ' . $row['name'] . '" /></a></td></tr>';
				$counter++;
			}
			$htmlOut .= '</tbody></table></fielset></div>';
		} else
			$htmlOut .= NO_AVAILABLE_DRUGS;

		return $htmlOut;
	}

	public static function removeDrug($id) {
		$res = Database::execute_query("SELECT `name` FROM `available-drugs-and-consuming-equipments` WHERE `id` = '$id';");
		$row = Database::get_assoc_array($res);
		$_logTXT = 'حذف تعریف دارو یا تجهیزات مصرفی "' . $row['name'] . '" از سیستم';
		
		try {
			Database::execute_query("DELETE FROM `available-drugs-and-consuming-equipments` WHERE `id` = '" . $id . "';");
		} catch ( exception $e ) {
			return $e -> getMessage();
		}
		//FormProccessor::_makeLog($_logTXT);
		
		header("Location: " . $_SERVER['HTTP_REFERER']);
		exit;
		return true;
	}

	public static function editDrug($id) {
		$htmlOut = '';
		
		$res = Database::execute_query("SELECT MAX(`id`) FROM `available-drugs-cost` WHERE `drug-id` = '" . $id . "';");
		$row = Database::get_assoc_array($res);
		$res = Database::execute_query("SELECT `cost` FROM `available-drugs-cost` WHERE `id` = '" . $row['MAX(`id`)'] . "';");
		$row = Database::get_assoc_array($res);
		$dbRes = Database::execute_query("SELECT * FROM `available-drugs-and-consuming-equipments` WHERE `id` = '" . $id . "' LIMIT 1;");
		$tmp = Database::get_assoc_array($dbRes);
		$htmlOut .= '<form action="' . substr(strrchr($_SERVER['PHP_SELF'], '/'), 1) . '" method="post" class="narin">';
		$htmlOut .= '<input type="hidden" name="old_drug" value="' . $id . '" />';
		$htmlOut .= '<input type="hidden" name="narin:token" value="' . Narin::create_token() . '" /><fieldset><legend>ویرایش دارو</legend>';
		$htmlOut .= '<label for="input_drug_name" class="required">نام:</label>';
		$htmlOut .= '<input type="text" name="input_drug_name" id="input_drug_name" maxlength="255" value="' . $tmp['name'] . '"';
		$htmlOut .= ' class="validate required ';
		if (isset($r['invalid']['input_drug_name']))
			$htmlOut .= ' invalid';
		$htmlOut .= '" />';
		$htmlOut .= '<span class="description"';
		if (!isset($r['invalid']['input_drug_name']))
			$htmlOut .= ' style="display:none;"';
		$htmlOut .= '>فارسی بنویسید</span><br />';
		$htmlOut .= '<label for="input_drug_shape" class="required">شکل:</label>';
		$htmlOut .= '<input type="text" name="input_drug_shape" id="input_drug_shape" maxlength="255" value="' . $tmp['shape'] . '"';
		$htmlOut .= ' class="validate required ';
		if (isset($r['invalid']['input_drug_shape']))
			$htmlOut .= ' invalid';
		$htmlOut .= '" />';
		$htmlOut .= '<span class="description"';
		if (!isset($r['invalid']['input_drug_shape']))
			$htmlOut .= ' style="display:none;"';
		$htmlOut .= '>فارسی بنویسید</span><br />';
		$htmlOut .= '<label for="input_drug_cost">قیمت واحد معیار(به ریال):</label>';
		$htmlOut .= '<input type="text" name="input_drug_cost" id="input_drug_cost" maxlength="8" dir="ltr" value="' . $row['cost'] . '"';
		$htmlOut .= ' class="validate integer';
		if (isset($r['invalid']['input_drug_cost']))
			$htmlOut .= ' invalid';
		$htmlOut .= '" />';
		$htmlOut .= '<span class="description"';
		if (!isset($r['invalid']['input_drug_cost']))
			$htmlOut .= ' style="display:none;"';
		$htmlOut .= '>عدد صحیح، حداقل بزرگتر از 0 و حداکثر 99999999</span>';
		$htmlOut .= '<script type="text/javascript">$(\'#input_drug_cos\').data(\'num_min\',0).data(\'num_min_inc\',false).data(\'num_max\',99999999).data(\'num_max_inc\',true);</script><br />';
		/*$htmlOut .= '<input type="checkbox" name="isFree" id="isFree"';
		if ($tmp['is_free'] == 'yes')
			$htmlOut .= ' checked="checked"';
		$htmlOut .= ' />';
		$htmlOut .= '<label for="isFree">رایگان</label><br /><br />';*/
		$htmlOut .= '<br /><fieldset style="width: 250px;" class="required"><legend>نحوه اصلاح قیمت</legend>';
		$htmlOut .= '<input type="radio" name="editType" id="editType_0" value="0" checked="checked" /><label for="editType_0">اصلاح آخرین قیمت</label><br />';
		$htmlOut .= '<input type="radio" name="editType" id="editType_1" value="1" /><label for="editType_1">تغییر قیمت معیار</label></fieldset><br /><br />';
		$htmlOut .= '<input type="submit" value="ویرایش دارو" name="submitEditDrug" /></fieldset></form>';

		return $htmlOut;
	}

	public static function manageAvailableConsumingEquipments() {
		$htmlOut = '';
		$htmlOut .= '<p style="margin-right: 20px;">در این قسمت می توانید تجهیزات مصرفی موردنیاز برای کل سیستم را تعریف نمایید.</p>';
		$htmlOut .= '<p style="text-align: right; font-weight: bold; font-size: 15px; margin-right: 20px"><a href="#" style="color: green;" id="slideLink" ><img src="../illustrator/images/icons/plus.png" />&nbsp;افزودن تجهیزات مصرفی</a></p>';
		$htmlOut .= '<form action="' . substr(strrchr($_SERVER['PHP_SELF'], '/'), 1) . '" method="post" id="slideForm" class="narin">';
		$htmlOut .= '<input type="hidden" name="narin:token" value="' . Narin::create_token() . '" /><fieldset><legend>افزودن تجهیزات مصرفی</legend>';
		$htmlOut .= '<br /><label for="input_drug_name" class="required">نام:</label>';
		$htmlOut .= '<input type="text" name="input_drug_name" id="input_drug_name" maxlength="255"';
		if (isset($_POST['input_drug_name']) && $_POST['input_drug_name'] !== '')
			$htmlOut .= ' value="' . htmlspecialchars($_POST['input_drug_name']) . '"';
		$htmlOut .= ' class="validate required ';
		if (isset($r['invalid']['input_drug_name']))
			$htmlOut .= ' invalid';
		$htmlOut .= '" />';
		$htmlOut .= '<span class="description"';
		if (!isset($r['invalid']['input_drug_name']))
			$htmlOut .= ' style="display:none;"';
		$htmlOut .= '>فارسی بنویسید</span><br />';
		$htmlOut .= '<label for="input_drug_shape" class="required">شکل:</label>';
		$htmlOut .= '<input type="text" name="input_drug_shape" id="input_drug_shape" maxlength="255"';
		if (isset($_POST['input_drug_shape']) && $_POST['input_drug_shape'] !== '')
			$htmlOut .= ' value="' . htmlspecialchars($_POST['input_drug_shape']) . '"';
		$htmlOut .= ' class="validate required ';
		if (isset($r['invalid']['input_drug_shape']))
			$htmlOut .= ' invalid';
		$htmlOut .= '" />';
		$htmlOut .= '<span class="description"';
		if (!isset($r['invalid']['input_drug_shape']))
			$htmlOut .= ' style="display:none;"';
		$htmlOut .= '>فارسی بنویسید</span><br />';
		$htmlOut .= '<label for="input_drug_cost" class="required">قیمت واحد (به ریال):</label>';
		$htmlOut .= '<input type="text" name="input_drug_cost" id="input_drug_cost" dir="ltr"';
		if (isset($_POST['input_drug_cost']) && $_POST['input_drug_cost'] !== '')
			$htmlOut .= ' value="' . htmlspecialchars($_POST['input_drug_cost']) . '"';
		$htmlOut .= ' class="validate required integer';
		if (isset($r['invalid']['input_drug_cost']))
			$htmlOut .= ' invalid';
		$htmlOut .= '" />';
		$htmlOut .= '<span class="description"';
		if (!isset($r['invalid']['input_drug_cost']))
			$htmlOut .= ' style="display:none;"';
		$htmlOut .= '>عدد صحیح، حداقل بزرگتر از 0 و حداکثر 99999999</span>';
		$htmlOut .= '<script type="text/javascript">$(\'#input_drug_cos\').data(\'num_min\',0).data(\'num_min_inc\',false);</script><br />';
		$htmlOut .= '<br /><input type="submit" value="ثبت تجهیزات مصرفی" name="submitAddConsumingEquipment" /></fieldset></form>';

		$res = Database::execute_query("SELECT `id`, `name` FROM `available-drugs-and-consuming-equipments` WHERE `is_drug` = 'no' ORDER BY `name` ASC;");
		$counter = 1;
		if (Database::num_of_rows($res) > 0) {
			$htmlOut .= '<br /><div><fieldset><legend>فهرست تجهیزات مصرفی موجود</legend>';
			$htmlOut .= '<br /><table align="right" class="contentTable" border="0" cellpadding="0" cellspacing="1">';
			$htmlOut .= '<thead><tr><th width="40">ردیف</th><th>عنوان</th><th width="50">حذف</th><th width="50">ویرایش</th></thead><tbody>';
			while ($row = Database::get_assoc_array($res)) {
				$htmlOut .= '<tr><td>' . $counter . '</td><td>' . $row['name'] . '</td>';
				$htmlOut .= '<td><a href="?cmd=removeDrug&drugId=' . $row['id'] . '"><img src="../illustrator/images/icons/remove.png" title="حذف  ' . $row['name'] . '" /></a></td>';
				$htmlOut .= '<td><a href="?cmd=editConsumingEquip&id=' . $row['id'] . '"><img src="../illustrator/images/icons/edit.png" title="ویریش ' . $row['name'] . '" /></a></td></tr>';
				$counter++;
			}
			$htmlOut .= '</tbody></table></fielset></div>';
		} else
			$htmlOut .= NO_AVAILABLE_DRUGS;

		return $htmlOut;
	}

	public static function editConsumingEquip($id) {
		$htmlOut = '';
		$res = Database::execute_query("SELECT MAX(`id`) FROM `available-drugs-cost` WHERE `drug-id` = '" . $id . "';");
		$row = Database::get_assoc_array($res);
		$res = Database::execute_query("SELECT `cost` FROM `available-drugs-cost` WHERE `id` = '" . $row['MAX(`id`)'] . "';");
		$row = Database::get_assoc_array($res);
		$dbRes = Database::execute_query("SELECT * FROM `available-drugs-and-consuming-equipments` WHERE `id` = '" . $id . "' LIMIT 1;");
		$tmp = Database::get_assoc_array($dbRes);
		$htmlOut .= '<form action="' . substr(strrchr($_SERVER['PHP_SELF'], '/'), 1) . '" method="post" class="narin">';
		$htmlOut .= '<input type="hidden" name="old_drug" value="' . $id . '" />';
		$htmlOut .= '<input type="hidden" name="narin:token" value="' . Narin::create_token() . '" /><fieldset><legend>ویرایش تجهیزات مصرفی</legend>';
		$htmlOut .= '<label for="input_drug_name" class="required">نام:</label>';
		$htmlOut .= '<input type="text" name="input_drug_name" id="input_drug_name" maxlength="255" value="' . $tmp['name'] . '"';
		$htmlOut .= ' class="validate required ';
		if (isset($r['invalid']['input_drug_name']))
			$htmlOut .= ' invalid';
		$htmlOut .= '" />';
		$htmlOut .= '<span class="description"';
		if (!isset($r['invalid']['input_drug_name']))
			$htmlOut .= ' style="display:none;"';
		$htmlOut .= '>فارسی بنویسید</span><br />';
		$htmlOut .= '<label for="input_drug_shape" class="required">شکل:</label>';
		$htmlOut .= '<input type="text" name="input_drug_shape" id="input_drug_shape" maxlength="255" value="' . $tmp['shape'] . '"';
		$htmlOut .= ' class="validate required ';
		if (isset($r['invalid']['input_drug_shape']))
			$htmlOut .= ' invalid';
		$htmlOut .= '" />';
		$htmlOut .= '<span class="description"';
		if (!isset($r['invalid']['input_drug_shape']))
			$htmlOut .= ' style="display:none;"';
		$htmlOut .= '>فارسی بنویسید</span><br />';
		$htmlOut .= '<label for="input_drug_cost" class="required">قیمت واحد (به ریال):</label>';
		$htmlOut .= '<input type="text" name="input_drug_cost" id="input_drug_cost" maxlength="8" dir="ltr" value="' . $row['cost'] . '"';
		$htmlOut .= ' class="validate required integer';
		if (isset($r['invalid']['input_drug_cost']))
			$htmlOut .= ' invalid';
		$htmlOut .= '" />';
		$htmlOut .= '<span class="description"';
		if (!isset($r['invalid']['input_drug_cost']))
			$htmlOut .= ' style="display:none;"';
		$htmlOut .= '>عدد صحیح، حداقل بزرگتر از 0 و حداکثر 99999999</span>';
		$htmlOut .= '<script type="text/javascript">$(\'#input_drug_cos\').data(\'num_min\',0).data(\'num_min_inc\',false).data(\'num_max\',99999999).data(\'num_max_inc\',true);</script><br />';
		$htmlOut .= '<br /><fieldset style="width: 250px;" class="required"><legend>نحوه اصلاح قیمت</legend>';
		$htmlOut .= '<input type="radio" name="editType" id="editType_0" value="0" checked="checked" /><label for="editType_0">اصلاح آخرین قیمت</label><br />';
		$htmlOut .= '<input type="radio" name="editType" id="editType_1" value="1" /><label for="editType_1">تغییر قیمت معیار</label></fieldset><br /><br />';
		$htmlOut .= '<br /><input type="submit" value="ویرایش تجهیزات مصرفی" name="submitEditConsuminEquipment" /></fieldset></form>';

		return $htmlOut;
	}

	public static function manageAvailableNonConsumingEquipments() {
		$htmlOut = '';
		$htmlOut .= '<p style="margin-right: 20px;">در این قسمت شما می توانید انواع تجهیزات غیرمصرفی را مدیریت نمایید.</p>';
		$htmlOut .= '<p style="text-align: right; font-weight: bold; font-size: 15px; margin-right: 20px"><a href="#" style="color: green;" id="slideLink"><img src="../illustrator/images/icons/plus.png" />&nbsp;افزودن تجهیزات غیرمصرفی</a></p>';
		$htmlOut .= '<form action="' . substr(strrchr($_SERVER['PHP_SELF'], '/'), 1) . '" method="post" id="slideForm" class="narin">';
		$htmlOut .= '<input type="hidden" name="narin:token" value="' . Narin::create_token() . '" /><fieldset><legend>افزودن وسیله غیرمصرفی</legend>';
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
		$htmlOut .= '>فارسی بنویسید</span><br />';

		$htmlOut .= '<label for="input_type" class="required">نوع:</label>';
		$htmlOut .= '<input type="text" name="input_type" id="input_type" maxlength="255"';
		if (isset($_POST['input_type']) && $_POST['input_type'] !== '')
			$htmlOut .= ' value="' . htmlspecialchars($_POST['input_type']) . '"';
		$htmlOut .= ' class="validate required ';
		if (isset($r['invalid']['input_type']))
			$htmlOut .= ' invalid';
		$htmlOut .= '" />';
		$htmlOut .= '<span class="description"';
		if (!isset($r['invalid']['input_type']))
			$htmlOut .= ' style="display:none;"';
		$htmlOut .= '>فارسی بنویسید</span><br />';
		
		$htmlOut .= '<label for="input_mark" class="required">مارک:</label>';
		$htmlOut .= '<input type="text" name="input_mark" id="input_mark" maxlength="255"';
		if (isset($_POST['input_mark']) && $_POST['input_mark'] !== '')
			$htmlOut .= ' value="' . htmlspecialchars($_POST['input_mark']) . '"';
		$htmlOut .= ' class="validate required';
		if (isset($r['invalid']['input_mark']))
			$htmlOut .= ' invalid';
		$htmlOut .= '" />';
		$htmlOut .= '<span class="description"';
		if (!isset($r['invalid']['input_mark']))
			$htmlOut .= ' style="display:none;"';
		$htmlOut .= '>فارسی بنویسید</span><br />';

		$htmlOut .= '<label for="input_price" class="required">قیمت واحد (به  ریال):</label>';
		$htmlOut .= '<input type="text" name="input_price" id="input_price" dir="ltr"';
		if (isset($_POST['input_price']) && $_POST['input_price'] !== '')
			$htmlOut .= ' value="' . htmlspecialchars($_POST['input_price']) . '"';
		$htmlOut .= ' class="validate required integer';
		if (isset($r['invalid']['input_price']))
			$htmlOut .= ' invalid';
		$htmlOut .= '" />';
		$htmlOut .= '<span class="description"';
		if (!isset($r['invalid']['input_price']))
			$htmlOut .= ' style="display:none;"';
		$htmlOut .= '>عدد صحیح، حداقل بزرگتر از 0</span>';
		$htmlOut .= '<script type="text/javascript">$(\'#input_price\').data(\'num_min\',0).data(\'num_min_inc\',false);</script><br />';
		$htmlOut .= '<input type="submit" name="submitaddAvailableNonConsumingEquipment" value="ثبت" /></fieldset></form>';

		$res = Database::execute_query("SELECT * FROM `available-non-consuming-equipments` ORDER BY `name` ASC;");
		$counter = 1;
		if (Database::num_of_rows($res) > 0) {
			$htmlOut .= '<div><fieldset><legend>فهرست داروها و تجهیزات مصرفی موجود</legend>';
			$htmlOut .= '<br /><table align="right" class="contentTable" border="0" cellpadding="0" cellspacing="1">';
			$htmlOut .= '<thead><tr><th width="40">ردیف</th><th>عنوان</th><th>نوع</th><th width="50">حذف</th><th width="50">ویرایش</th></thead><tbody>';
			while ($row = Database::get_assoc_array($res)) {
				$htmlOut .= '<tr><td>' . $counter . '</td><td>' . $row['name'] . ' - (' . $row['mark'] .  ')</td><td>' . $row['type'] . '</td>';
				$htmlOut .= '<td><a href="?cmd=removeNonConsumingEquipment&nonConsumingEquipmentId=' . $row['id'] . '"><img src="../illustrator/images/icons/remove.png" title="حذف ' . $row['name'] . '" /></a></td>';
				$htmlOut .= '<td><a href="?cmd=editNonConsumingEquipment&nonConsumingEquipmentId=' . $row['id'] . '"><img src="../illustrator/images/icons/edit.png" title="ویریش ' . $row['name'] . '" /></a></td></tr>';
				$counter++;
			}

			$htmlOut .= '</tbody></table></fielset></div>';
		} else
			$htmlOut .= NO_AVAILABLE_NONCONSUMINGEQUIPMENT;

		return $htmlOut;
	}

	public static function removeAvailableNonConsumingEquipment($id) {
		$res = Database::execute_query("SELECT `name`, `mark` FROM `available-non-consuming-equipments` WHERE `id` = '$id';");
		$row = Database::get_assoc_array($res);
		$_logTXT = 'حذف تعریف تجهیزات غیرمصرفی "' . $row['name'] . '(' . $row['mark'] . ')" از سیستم';
		
		try {
			Database::execute_query("DELETE FROM `available-non-consuming-equipments` WHERE `id` = '" . $id . "';");
		} catch ( exception $e ) {
			return $e -> getMessage();
		}
		//FormProccessor::_makeLog($_logTXT);

		header("Location: " . $_SERVER['HTTP_REFERER']);
		exit;
		return true;
	}

	public static function editAvailableNonConsumingEquipment($id) {
		$htmlOut = '';
		$res = Database::execute_query("SELECT MAX(`id`) FROM `available-non-consuming-cost` WHERE `equip-id` = '" . $id . "';");
		$row = Database::get_assoc_array($res);
		$res = Database::execute_query("SELECT `cost` FROM `available-non-consuming-cost` WHERE `id` = '" . $row['MAX(`id`)'] . "';");
		$row = Database::get_assoc_array($res);
		$dbRes = Database::execute_query("SELECT * FROM `available-non-consuming-equipments` WHERE `id` = '" . $id . "' LIMIT 1;");
		$tmp = Database::get_assoc_array($dbRes);
		$htmlOut .= '<form action="' . substr(strrchr($_SERVER['PHP_SELF'], '/'), 1) . '" method="post" class="narin">';
		$htmlOut .= '<input type="hidden" name="old_non_consuming" value="' . $id . '" />';
		$htmlOut .= '<input type="hidden" name="narin:token" value="' . Narin::create_token() . '" /><fieldset><legend>ویرایش وسیله غیرمصرفی</legend>';
		$htmlOut .= '<label for="input_name" class="required">نام:</label>';
		$htmlOut .= '<input type="text" name="input_name" id="input_name" maxlength="255" value="' . $tmp['name'] . '"';
		$htmlOut .= ' class="validate required ';
		if (isset($r['invalid']['input_name']))
			$htmlOut .= ' invalid';
		$htmlOut .= '" />';
		$htmlOut .= '<span class="description"';
		if (!isset($r['invalid']['input_name']))
			$htmlOut .= ' style="display:none;"';
		$htmlOut .= '>فارسی بنویسید</span><br />';

		$htmlOut .= '<label for="input_type" class="required">نوع:</label>';
		$htmlOut .= '<input type="text" name="input_type" id="input_type" maxlength="255" value="' . $tmp['type'] . '"';
		if (isset($_POST['input_type']) && $_POST['input_type'] !== '')
			$htmlOut .= ' value="' . htmlspecialchars($_POST['input_type']) . '"';
		$htmlOut .= ' class="validate required ';
		if (isset($r['invalid']['input_type']))
			$htmlOut .= ' invalid';
		$htmlOut .= '" />';
		$htmlOut .= '<span class="description"';
		if (!isset($r['invalid']['input_type']))
			$htmlOut .= ' style="display:none;"';
		$htmlOut .= '>فارسی بنویسید</span><br />';
		$htmlOut .= '<label for="input_mark" class="required">مارک:</label>';
		$htmlOut .= '<input type="text" name="input_mark" id="input_mark" maxlength="255" value="' . $tmp['mark'] . '"';
		if (isset($_POST['input_mark']) && $_POST['input_mark'] !== '')
			$htmlOut .= ' value="' . htmlspecialchars($_POST['input_mark']) . '"';
		$htmlOut .= ' class="validate required ';
		if (isset($r['invalid']['input_mark']))
			$htmlOut .= ' invalid';
		$htmlOut .= '" />';
		$htmlOut .= '<span class="description"';
		if (!isset($r['invalid']['input_mark']))
			$htmlOut .= ' style="display:none;"';
		$htmlOut .= '>فارسی بنویسید</span><br />';
		$htmlOut .= '<label for="input_price" class="required">قیمت واحد (به  ریال):</label>';
		$htmlOut .= '<input type="text" name="input_price" id="input_price" dir="ltr" value="' . $row['cost'] . '"';
		$htmlOut .= ' class="validate required integer';
		if (isset($r['invalid']['input_price']))
			$htmlOut .= ' invalid';
		$htmlOut .= '" />';
		$htmlOut .= '<span class="description"';
		if (!isset($r['invalid']['input_price']))
			$htmlOut .= ' style="display:none;"';
		$htmlOut .= '>عدد صحیح، حداقل بزرگتر از 0</span>';
		$htmlOut .= '<script type="text/javascript">$(\'#input_price\').data(\'num_min\',0).data(\'num_min_inc\',false);</script><br />';
		$htmlOut .= '<br /><fieldset style="width: 250px;" class="required"><legend>نحوه اصلاح قیمت</legend>';
		$htmlOut .= '<input type="radio" name="editType" id="editType_0" value="0" checked="checked" /><label for="editType_0">اصلاح آخرین قیمت</label><br />';
		$htmlOut .= '<input type="radio" name="editType" id="editType_1" value="1" /><label for="editType_1">تغییر قیمت معیار</label></fieldset><br /><br />';
		$htmlOut .= '<input type="submit" name="submitEditAvailableNonConsumingEquipment" value="ویرایش" /></fieldset></form>';

		return $htmlOut;
	}

	public static function manageGeneralChargesTypes() {
		$htmlOut = '';
		$htmlOut .= '<p style="margin-right: 20px;">در این قسمت شما می توانید انواع هزینه های عمومی را مدیریت نمایید.</p>';
		$htmlOut .= '<p style="text-align: right; font-weight: bold; font-size: 15px; margin-right: 20px"><a href="#" style="color: green;" id="slideLink"><img src="../illustrator/images/icons/plus.png" />&nbsp;افزودن نوع</a></p>';
		$htmlOut .= '<form action="' . substr(strrchr($_SERVER['PHP_SELF'], '/'), 1) . '" method="post" id="slideForm" class="narin">';
		$htmlOut .= '<input type="hidden" name="narin:token" value="' . Narin::create_token() . '" /><fieldset><legend>افزودن نوع هزینه ای</legend>';
		$htmlOut .= '<label for="input_charge_type" class="required">نام نوع هزینه:</label>';
		$htmlOut .= '<input type="text" name="input_charge_type" id="input_charge_type" maxlength="255"';
		if (isset($_POST['input_charge_type']) && $_POST['input_charge_type'] !== '')
			$htmlOut .= ' value="' . htmlspecialchars($_POST['input_charge_type']) . '"';
		$htmlOut .= ' class="validate required ';
		if (isset($r['invalid']['input_charge_type']))
			$htmlOut .= ' invalid';
		$htmlOut .= '" />';
		$htmlOut .= '<span class="description"';
		if (!isset($r['invalid']['input_charge_type']))
			$htmlOut .= ' style="display:none;"';
		$htmlOut .= '>فارسی بنویسید</span><br />';
		$htmlOut .= '<label for="input_charge_type_cm" class="labelbr">توضیحات:</label>';
		$htmlOut .= '<span class="description">فارسی بنویسید</span><br />';
		$htmlOut .= '<textarea name="input_charge_type_cm" id="input_charge_type_cm" rows="4" cols="40" class="validate ';
		if (isset($r['invalid']['input_charge_type_cm']))
			$htmlOut .= ' invalid';
		$htmlOut .= '">';
		if (isset($_POST['input_charge_type_cm']))
			$htmlOut .= htmlspecialchars($_POST['input_charge_type_cm']);
		$htmlOut .= '</textarea><br />';
		$htmlOut .= '<input type="submit" name="submitAddGeneralChargeType" value="ثبت نوع هزینه" /></fieldset></form>';

		$res = Database::execute_query("SELECT `id`, `type` FROM `general-charge-types`;");
		$counter = 1;
		if (Database::num_of_rows($res) > 0) {
			$htmlOut .= '<div><fieldset><legend>فهرست نوع هزینه های موجود</legend>';
			$htmlOut .= '<br /><table align="right" class="contentTable" border="0" cellpadding="0" cellspacing="1">';
			$htmlOut .= '<thead><tr><th width="40">ردیف</th><th>عنوان</th><th width="50">حذف</th><th width="50">ویرایش</th></thead><tbody>';
			while ($row = Database::get_assoc_array($res)) {
				$htmlOut .= '<tr><td>' . $counter . '</td><td>' . $row['type'] . '</td>';
				$htmlOut .= '<td><a href="?cmd=removeGeneralChargeType&generalChargeTypeId=' . $row['id'] . '"><img src="../illustrator/images/icons/remove.png" title="حذف ' . $row['type'] . '" /></a></td>';
				$htmlOut .= '<td><a href="?cmd=editGeneralChargeType&generalChargeTypeId=' . $row['id'] . '"><img src="../illustrator/images/icons/edit.png" title="ویرایش ' . $row['type'] . '" /></a></td></tr>';
				$counter++;
			}

			$htmlOut .= '</tbody></table></fielset></div>';
		} else
			$htmlOut .= NO_AVAILABLE_GENERALCHARGETYPE;

		return $htmlOut;
	}

	public static function addGeneralChargeType() {
		$htmlOut = '';
		$htmlOut .= '<form action="' . substr(strrchr($_SERVER['PHP_SELF'], '/'), 1) . '" method="post" class="narin">';
		$htmlOut .= '<input type="hidden" name="narin:token" value="' . Narin::create_token() . '" /><fieldset><legend>افزودن نوع هزینه ای</legend>';
		$htmlOut .= '<label for="input_charge_type" class="required">نام نوع هزینه:</label>';
		$htmlOut .= '<input type="text" name="input_charge_type" id="input_charge_type" maxlength="255"';
		if (isset($_POST['input_charge_type']) && $_POST['input_charge_type'] !== '')
			$htmlOut .= ' value="' . htmlspecialchars($_POST['input_charge_type']) . '"';
		$htmlOut .= ' class="validate required ';
		if (isset($r['invalid']['input_charge_type']))
			$htmlOut .= ' invalid';
		$htmlOut .= '" />';
		$htmlOut .= '<span class="description"';
		if (!isset($r['invalid']['input_charge_type']))
			$htmlOut .= ' style="display:none;"';
		$htmlOut .= '>فارسی بنویسید</span><br />';
		$htmlOut .= '<label for="input_charge_type_cm" class="labelbr">توضیحات:</label>';
		$htmlOut .= '<span class="description">فارسی بنویسید</span><br />';
		$htmlOut .= '<textarea name="input_charge_type_cm" id="input_charge_type_cm" rows="4" cols="40" class="validate ';
		if (isset($r['invalid']['input_charge_type_cm']))
			$htmlOut .= ' invalid';
		$htmlOut .= '">';
		if (isset($_POST['input_charge_type_cm']))
			$htmlOut .= htmlspecialchars($_POST['input_charge_type_cm']);
		$htmlOut .= '</textarea><br />';
		$htmlOut .= '<input type="submit" name="submitAddGeneralChargeType" value="ثبت نوع هزینه" /></fieldset></form>';

		return $htmlOut;
	}

	public static function removeGeneralChargeType($id) {
		$res = Database::execute_query("SELECT `type` FROM `general-charge-types` WHERE `id` = '$id';");
		$row = Database::get_assoc_array($res);
		$_logTXT = 'حذف تعریف هزینه عمومی "' . $row['type'] . '" از سیستم';
		
		try {
			Database::execute_query("DELETE FROM `general-charge-types` WHERE `id` = '" . $id . "';");
		} catch ( exception $e ) {
			return $e -> getMessage();
		}
		//FormProccessor::_makeLog($_logTXT);
		
		header("Location: " . $_SERVER['HTTP_REFERER']);
		exit;
		return true;
	}

	public static function editGeneralChargeType($id) {
		$htmlOut = '';
		$res = Database::execute_query("SELECT * FROM `general-charge-types` WHERE `id` = '$id';");
		$row = Database::get_assoc_array($res);
		$htmlOut .= '<form action="' . substr(strrchr($_SERVER['PHP_SELF'], '/'), 1) . '" method="post" class="narin">';
		$htmlOut .= '<input type="hidden" name="chargeId" value="' . $id . '" />';
		$htmlOut .= '<input type="hidden" name="narin:token" value="' . Narin::create_token() . '" /><fieldset><legend>اصلاح نوع هزینه</legend>';
		$htmlOut .= '<label for="input_charge_type" class="required">نام نوع هزینه:</label>';
		$htmlOut .= '<input type="text" name="input_charge_type" id="input_charge_type" maxlength="255" value="' . $row['type'] . '"';
		if (isset($_POST['input_charge_type']) && $_POST['input_charge_type'] !== '')
			$htmlOut .= ' value="' . htmlspecialchars($_POST['input_charge_type']) . '"';
		$htmlOut .= ' class="validate required ';
		if (isset($r['invalid']['input_charge_type']))
			$htmlOut .= ' invalid';
		$htmlOut .= '" />';
		$htmlOut .= '<span class="description"';
		if (!isset($r['invalid']['input_charge_type']))
			$htmlOut .= ' style="display:none;"';
		$htmlOut .= '>فارسی بنویسید</span><br />';
		$htmlOut .= '<label for="input_charge_type_cm" class="labelbr">توضیحات:</label>';
		$htmlOut .= '<span class="description">فارسی بنویسید</span><br />';
		$htmlOut .= '<textarea name="input_charge_type_cm" id="input_charge_type_cm" rows="4" cols="40" class="validate ';
		if (isset($r['invalid']['input_charge_type_cm']))
			$htmlOut .= ' invalid';
		$htmlOut .= '">';
		if (isset($_POST['input_charge_type_cm']))
			$htmlOut .= htmlspecialchars($_POST['input_charge_type_cm']);
		$htmlOut .= $row['comment'] . '</textarea><br />';
		$htmlOut .= '<input type="submit" name="submitEditGeneralChargeType" value="اصلاح نوع هزینه" /></fieldset></form>';

		return $htmlOut;
	}

}
?>