<?php

/**
 *
 * ea-sh.php
 *
 * @copyright	Copyright (C) 2012 Rivas Systems Inc. All rights reserved.
 * @versin	1.00 2011-02-05
 * @author	Behnam Salili
 *
 */

require_once dirname(dirname(dirname(__file__))) . '/defines.php';
require_once dirname(dirname(__file__)) . '/database/database.php';
require_once dirname(dirname(__file__)) . '/models/state.php';
require_once dirname(dirname(__file__)) . '/models/town.php';
require_once dirname(dirname(__file__)) . '/models/center.php';
require_once dirname(dirname(__file__)) . '/models/unit.php';
require_once dirname(dirname(__file__)) . '/models/hygiene-unit.php';

class ShortcutsView {

	public static function processES($es) {
		$htmlOut = '';
		switch ($es) {
			case 'manageTowns' :
				$htmlOut = self::manageTownsForm();
				break;
			case 'manageCenters' :
				$htmlOut = self::manageCentersForm();
				break;
			case 'manageHygieneUnits' :
				$htmlOut = self::manageHygieneUnitsForm();
				break;
			case 'manageUnits' :
				$htmlOut = self::manageUnitsForm();
				break;
			case 'manageCentersDrugs' :
				$htmlOut = self::manageCentersDrugsForm();
				break;
			case 'manageCentersConsumings' :
				$htmlOut = self::manageCentersConsumingsForm();
				break;
			case 'manageHygieneUnitsDrugs' :
				$htmlOut = self::manageHygieneUnitsDrugsForm();
				break;
			case 'manageHygieneUnitsConsumings' :
				$htmlOut = self::manageHygieneUnitsConsumingsForm();
				break;
			case 'manageCentersNonConsumings' :
				$htmlOut = self::manageCentersNonConsumingsForm();
				break;
			case 'manageHygieneUnitsNonConsumings' :
				$htmlOut = self::manageHygieneUnitsNonConsumingsForm();
				break;
			case 'manageCentersBuildings' :
				$htmlOut = self::manageCentersBuildingsForm();
				break;
			case 'manageCentersBuildingsCharge' :
				$htmlOut = self::manageCentersBuildingsChargeForm();
				break;
			case 'manageHygieneUnitsBuildings' :
				$htmlOut = self::manageHygieneUnitsBuildingsForm();
				break;
			case 'manageHygieneUnitsBuildingsCharge' :
				$htmlOut = self::manageHygieneUnitsBuildingsChargeForm();
				break;
			case 'manageCentersVehicles' :
				$htmlOut = self::manageCentersVehiclesForm();
				break;
			case 'manageCentersVehiclesCharge' :
				$htmlOut = self::manageCentersVehiclesChargeForm();
				break;
			case 'manageHygieneUnitsVehicles' :
				$htmlOut = self::manageHygieneUnitsVehiclesForm();
				break;
			case 'manageHygieneUnitsVehiclesCharge' :
				$htmlOut = self::manageHygieneUnitsVehiclesChargeForm();
				break;
			case 'manageCentersGeneralCharges' :
				$htmlOut = self::manageCentersGeneralChargesForm();
				break;
			case 'manageHygieneUnitsGeneralCharges' :
				$htmlOut = self::manageHygieneUnitsGeneralChargesForm();
				break;
			case 'manageUnitsPersonnels' :
				$htmlOut = self::manageUnitsPersonnelsForm();
				break;
			case 'manageUnitPersonnelFinancialInfo' :
				$htmlOut = self::manageUnitPersonnelFinancialInfoForm();
				break;
			case 'unitPersonnelFinancialInfo' :
				$htmlOut = self::editUnitPersonnelFinancialInfo($_GET['id']);
				break;
			case 'manageHygieneUnitsPersonnel' :
				$htmlOut = self::manageHygieneUnitsPersonnelForm();
				break;
			case 'manageHygieneUnitPersonnelFinancialInfo' :
				$htmlOut = self::manageHygieneUnitPersonnelFinancialInfoForm();
				break;
			case 'hygieneUnitPersonnelFinancialInfo' :
				$htmlOut = self::editBehvarzFinancialInfo($_GET['id']);
				break;
			case 'manageUnitsServices' :
				$htmlOut = self::manageUnitsServices();
				break;
			case 'manageHygieneUnitsServices' :
				$htmlOut = self::manageHygieneUnitsServices();
				break;
		}

		return $htmlOut;
	}

	private static function manageTownsForm() {
		$htmlOut = '';
		$htmlOut .= '<p style="margin-right: 20px;">در این قسمت می توانید شهرستان ها را تعیین نمایید.</p>';
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
			$htmlOut .= '<fieldset><legend>فهرست شهرستان های استان ' . State::getName() . '</legend>';
			$htmlOut .= '<br /><table align="right" class="contentTable" border="0" cellpadding="0" cellspacing="1">';
			$htmlOut .= '<thead><th width="40">ردیف</th><th>نام</th><th width="70">تعداد مراکز</th><th width="50">حذف</th><th width="50">ویرایش</th></thead><tbody>';
			while ($row = Database::get_assoc_array($res)) {
				$resTmp = Database::execute_query("SELECT * FROM `centers` WHERE `town-id` = '" . $row['id'] . "';");
				$htmlOut .= '<tr><td>' . $counter . '</td><td><a href="?cmd=manageCenters&townId=' . $row['id'] . '">' . $row['name'] . '</a></td>';
				$htmlOut .= '<td>' . Database::num_of_rows($resTmp) . '</td>';
				$htmlOut .= '<td><a href="?cmd=removeTown&townId=' . $row['id'] . '"><img src="../illustrator/images/icons/remove.png" title="حذف شهرستان ' . $row['name'] . '" /></a></td>';
				$htmlOut .= '<td><a href="?cmd=editTown&townId=' . $row['id'] . '&ref=t"><img src="../illustrator/images/icons/edit.png" title="ویرایش شهرستان ' . $row['name'] . '" /></a></td></tr>';
				$counter++;
			}
			$htmlOut .= '</tbody></table></fielset><br />';
		} else
			$htmlOut .= NO_TOWN_EXISTS;

		return $htmlOut;
	}

	private static function manageCentersForm() {
		$htmlOut = '';
		$htmlOut .= '<p style="margin-right: 20px;">در این قسمت می توانید خانه های مراکز شهرستان ها را تعیین نمایید.</p>';
		$htmlOut .= '<p style="text-align: right; font-weight: bold; font-size: 15px; margin-right: 20px;"><a href="#" style="color: green;" id="slideLink" ><img src="../illustrator/images/icons/plus.png" />&nbsp;افزودن مرکز</a></p>';
		$htmlOut .= '<form action="' . substr(strrchr($_SERVER['PHP_SELF'], '/'), 1) . '" method="post" id="slideForm" class="narin">';
		$htmlOut .= '<input type="hidden" name="narin:token" value="' . Narin::create_token() . '" />';
		$htmlOut .= '<fieldset><legend>افزودن مرکز</legend>';
		$htmlOut .= '<br /><label for="townId">نام شهرستان:</label>';
		$htmlOut .= '<select name="townId" id="townId" class="validate first_opt_not_allowed">';
		$res = Database::execute_query("SELECT * FROM `towns` ORDER BY `name` ASC;");
		if (Database::num_of_rows($res) > 0) {
			$htmlOut .= '<option>انتخاب کنید...</option>';
			while ($row = Database::get_assoc_array($res))
				$htmlOut .= '<option value="' . $row['id'] . '">' . $row['name'] . '</option>';
		} else
			$htmlOut .= '<option>' . NO_TOWN_EXISTS . '</option>';
		$htmlOut .= '</select><br />';
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
		$htmlOut .= '<script type="text/javascript">';
		$htmlOut .= '$(\'#list\').ready(function() {$(document).ajaxStart(function() {$(\'#list\').css(\'opacity\', \'0.3\');$(\'.loading\').show();});$(document).ajaxSuccess(function() {$(\'#list\').css(\'opacity\', \'1\');$(\'.loading\').hide();});$(\'#list\').load(\'./ea-sh-ajax.php\', {\'townId_1\' : $(\'#townId_1 option:selected\').val()});});';
		$htmlOut .= '</script>';

		$htmlOut .= '<fieldset style="width: 580px;"><legend>مشاهده ی مراکز شهرستان ها</legend>';
		$htmlOut .= '<br /><label for="townId_1">نام شهرستان:</label>';
		$htmlOut .= '<select name="townId_1" id="townId_1" onchange="$(\'#list\').load(\'./ea-sh-ajax.php\', {\'townId_1\' : $(\'#townId_1 option:selected\').val()});">';
		$res = Database::execute_query("SELECT * FROM `towns` ORDER BY `name` ASC;");
		if (Database::num_of_rows($res) > 0) {
			while ($row = Database::get_assoc_array($res))
				$htmlOut .= '<option value="' . $row['id'] . '">' . $row['name'] . '</option>';
		} else
			$htmlOut .= '<option>' . NO_TOWN_EXISTS . '</option>';
		$htmlOut .= '</select><br /><br />';
		$htmlOut .= '<div class="loading">در حال بارگذاری؛ لطفا صبر کنید... <br /><br /><img src="../statisticalPlotter/loading.gif" /></div>';
		$htmlOut .= '<div id="list"></div><br /></fieldset>';

		return $htmlOut;
	}

	private static function manageHygieneUnitsForm() {
		$htmlOut = '';
		$htmlOut .= '<p style="margin-right: 20px;">در این قسمت می توانید خانه های بهداشت مراکز را تعیین نمایید.</p>';
		$htmlOut .= '<p style="text-align: right; font-weight: bold; font-size: 15px;"><a href="#" id="slideLink1" style="color: green;" ><img src="../illustrator/images/icons/plus.png" />&nbsp;افزودن خانه بهداشت</a></p>';
		$htmlOut .= '<div class="loading">در حال بارگذاری؛ لطفا صبر کنید... <br /><br /><img src="../statisticalPlotter/loading.gif" /></div>';
		$htmlOut .= '<div id="mngDiv"><form action="' . substr(strrchr($_SERVER['PHP_SELF'], '/'), 1) . '" method="post" id="slideForm1" class="narin">';
		$htmlOut .= '<input type="hidden" name="narin:token" value="' . Narin::create_token() . '" />';
		$htmlOut .= '<fieldset><legend>افزودن خانه بهداشت</legend>';
		$htmlOut .= '<br /><label for="townId">نام شهرستان:</label>';
		$htmlOut .= '<select name="townId" id="townId" class="validate first_opt_not_allowed">';
		$res = Database::execute_query("SELECT * FROM `towns` ORDER BY `name` ASC;");
		if (Database::num_of_rows($res) > 0) {
			$htmlOut .= '<option>انتخاب کنید...</option>';
			while ($row = Database::get_assoc_array($res))
				$htmlOut .= '<option value="' . $row['id'] . '">' . $row['name'] . '</option>';
		} else
			$htmlOut .= '<option>' . NO_TOWN_EXISTS . '</option>';
		$htmlOut .= '</select><br />';
		$htmlOut .= '<label for="centerId" class="required">نام مرکز:</label>';
		$htmlOut .= '<select name="centerId" id="centerId" class="validate first_opt_not_allowed"></select><br />';
		$htmlOut .= '<script type="text/javascript">';
		$htmlOut .= '$(document).ajaxStart(function() {$(\'.loading\').show();$(\'#mngDiv\').css(\'opacity\', \'0.3\');});$(document).ajaxSuccess(function() {$(\'.loading\').hide();$(\'#mngDiv\').css(\'opacity\', \'1\');});';
		$htmlOut .= '$(\'#centerId\').load(\'../statisticalPlotter/AsynchronousProccess.php\', {\'townId\' : $(\'#townId option:selected\').val()});';
		$htmlOut .= '$(\'#townId\').change(function() {$(\'#centerId\').load(\'../statisticalPlotter/AsynchronousProccess.php\', {\'townId\' : $(\'#townId option:selected\').val()});});';
		$htmlOut .= '</script>';
		$htmlOut .= '<label for="input_hygiene_name" class="required">نام خانه بهداشت:</label>';
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

		$htmlOut .= '<fieldset style="width: 580px;"><legend>مشاهده ی خانه های بهداشت مراکز</legend>';
		$htmlOut .= '<br /><label for="townId_1">نام شهرستان:</label>';
		$htmlOut .= '<select name="townId_1" id="townId_1">';
		$res = Database::execute_query("SELECT * FROM `towns` ORDER BY `name` ASC;");
		if (Database::num_of_rows($res) > 0) {
			while ($row = Database::get_assoc_array($res))
				$htmlOut .= '<option value="' . $row['id'] . '">' . $row['name'] . '</option>';
		} else
			$htmlOut .= '<option>' . NO_TOWN_EXISTS . '</option>';
		$htmlOut .= '</select>&nbsp;&nbsp;-->&nbsp;&nbsp;';
		$htmlOut .= '<label for="centerId_1" class="required">نام مرکز:</label>';
		$htmlOut .= '<select name="centerId_1" id="centerId_1" class="validate"></select><br /><br /><br />';
		$htmlOut .= '<script type="text/javascript">';
		$htmlOut .= 'var ajaxPost = $.post(\'../statisticalPlotter/AsynchronousProccess.php\', {\'townId\' : $(\'#townId_1 option:selected\').val()});';
		$htmlOut .= 'ajaxPost.done(function(data) {$(\'#centerId_1\').empty().append(data);$(\'#list\').load(\'./ea-sh-ajax.php\', {\'centerIdForHygieneUnitList\' : $(\'#centerId_1 option:selected\').val()});});';
		$htmlOut .= '$(\'#townId_1\').change(function() {var ajaxPost = $.post(\'../statisticalPlotter/AsynchronousProccess.php\', {\'townId\' : $(\'#townId_1 option:selected\').val()});ajaxPost.done(function(data) {$(\'#centerId_1\').empty().append(data);$(\'#list\').load(\'./ea-sh-ajax.php\', {\'centerIdForHygieneUnitList\' : $(\'#centerId_1 option:selected\').val()});});});';
		$htmlOut .= '$(\'#centerId_1\').change(function() {$(\'#list\').load(\'./ea-sh-ajax.php\', {\'centerIdForHygieneUnitList\' : $(\'#centerId_1 option:selected\').val()});});';
		$htmlOut .= '</script>';
		$htmlOut .= '<div id="list"></div><br /></fieldset>';
		$htmlOut .= '</div>';

		return $htmlOut;
	}

	private static function manageUnitsForm() {
		$htmlOut = '';
		$htmlOut .= '<p style="margin-right: 20px;">در این قسمت می توانید واحدهای مراکز را تعیین نمایید.</p>';
		$htmlOut .= '<p style="text-align: right; font-weight: bold; font-size: 15px; margin-right: 20px"><a href="#" style="color: green;" id="slideLink" ><img src="../illustrator/images/icons/plus.png" />&nbsp;افزودن واحد</a></p>';
		$htmlOut .= '<div class="loading" id="loadingForUnitSelection"><img src="../statisticalPlotter/loading.gif" />&nbsp;&nbsp;در حال بارگذاری؛ لطفا صبر کنید...</div><br />';
		$htmlOut .= '<div id="mngDiv"><form action="' . substr(strrchr($_SERVER['PHP_SELF'], '/'), 1) . '" method="post" id="slideForm" class="narin">';
		$htmlOut .= '<input type="hidden" name="narin:token" value="' . Narin::create_token() . '" />';
		$htmlOut .= '<fieldset><legend>افزودن واحد</legend>';
		$htmlOut .= '<label for="townNameForCenter" class="required">نام شهرستان:</label>';
		$htmlOut .= '<select name="townNameForCenter" id="townNameForCenter" class="validate first_opt_not_allowed">';
		$res = Database::execute_query("SELECT * FROM `towns` ORDER BY `name` ASC;");
		if (Database::num_of_rows($res) > 0) {
			$htmlOut .= '<option>انتخاب کنید...</option>';
			while ($row = Database::get_assoc_array($res))
				$htmlOut .= '<option value="' . $row['id'] . '">' . $row['name'] . '</option>';
		} else
			$htmlOut .= '<option>' . NO_TOWN_EXISTS . '</option>';
		$htmlOut .= '</select><br />';
		$htmlOut .= '<label for="centerId" class="required">نام مرکز:</label>';
		$htmlOut .= '<select name="centerId" id="centerId" class="validate first_opt_not_allowed"></select><br />';
		$htmlOut .= '<script type="text/javascript">';
		$htmlOut .= '$(document).ajaxStart(function() {$(\'#loadingForUnitSelection\').show();$(\'#mngDiv\').css(\'opacity\', \'0.3\');});' . '$(document).ajaxSuccess(function() {$(\'#loadingForUnitSelection\').hide();$(\'#mngDiv\').css(\'opacity\', \'1\');});';
		$htmlOut .= '$(\'#centerId\').load(\'../statisticalPlotter/AsynchronousProccess.php\', {\'townId\' : $(\'#townNameForCenter option:selected\').val()});';
		$htmlOut .= '$(\'#townNameForCenter\').change(function() {$(\'#centerId\').load(\'../statisticalPlotter/AsynchronousProccess.php\', {\'townId\' : $(\'#townNameForCenter option:selected\').val()});});';
		$htmlOut .= '</script>';
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

		$htmlOut .= '<fieldset style="width: 580px;"><legend>مشاهده ی واحدهای مراکز</legend>';
		$htmlOut .= '<br /><label for="townId_3" class="required">نام شهرستان:</label>';
		$htmlOut .= '<select name="townId_3" id="townId_3" class="validate">';
		$res = Database::execute_query("SELECT * FROM `towns` ORDER BY `name` ASC;");
		if (Database::num_of_rows($res) > 0) {
			while ($row = Database::get_assoc_array($res))
				$htmlOut .= '<option value="' . $row['id'] . '">' . $row['name'] . '</option>';
		} else
			$htmlOut .= '<option>' . NO_TOWN_EXISTS . '</option>';
		$htmlOut .= '</select>&nbsp; --> &nbsp;';
		$htmlOut .= '<label for="centerId_1" class="required">نام مرکز:</label>';
		$htmlOut .= '<select name="centerId_1" id="centerId_1" class="validate"></select>';
		$htmlOut .= '<script type="text/javascript">';
		$htmlOut .= 'var ajaxPost = $.post(\'../statisticalPlotter/AsynchronousProccess.php\', {\'townId\' : $(\'#townId_3 option:selected\').val()});';
		$htmlOut .= 'ajaxPost.done(function(data) {$(\'#centerId_1\').empty().append(data);$(\'#list\').load(\'./ea-sh-ajax.php\', {\'centerId\' : $(\'#centerId_1 option:selected\').val()});});';
		$htmlOut .= '$(\'#townId_3\').change(function() {var ajaxPost = $.post(\'../statisticalPlotter/AsynchronousProccess.php\', {\'townId\' : $(\'#townId_3 option:selected\').val()});' . 'ajaxPost.done(function(data) {$(\'#centerId_1\').empty().append(data);$(\'#list\').load(\'./ea-sh-ajax.php\', {\'centerId\' : $(\'#centerId_1 option:selected\').val()});});});';
		$htmlOut .= '$(\'#centerId_1\').change(function() {$(\'#list\').load(\'./ea-sh-ajax.php\', {\'centerId\' : $(\'#centerId_1 option:selected\').val()});});';
		$htmlOut .= '</script>';
		$htmlOut .= '<div id="list"></div><br /></fieldset>';
		$htmlOut .= '</div>';

		return $htmlOut;
	}

	private static function manageCentersDrugsForm() {
		$htmlOut = '';
		$htmlOut .= '<p style="margin-right: 20px;">در این قسمت می توانید داروهای مراکز را تعیین نمایید.</p>';
		$htmlOut .= '<p style="text-align: right; font-weight: bold; font-size: 15px; margin-right: 20px"><a href="#" style="color: green;" id="slideLink" ><img src="../illustrator/images/icons/plus.png" />&nbsp;افزودن</a></p>';
		$htmlOut .= '<div class="loading"><img src="../statisticalPlotter/loading.gif" />&nbsp;&nbsp;در حال بارگذاری؛ لطفا صبر کنید...</div><br />';
		$htmlOut .= '<div id="mngDiv"><form action="' . substr(strrchr($_SERVER['PHP_SELF'], '/'), 1) . '" method="post" id="slideForm" class="narin">';
		$htmlOut .= '<input type="hidden" name="narin:token" value="' . Narin::create_token() . '" /><fieldset><legend>ثبت دارو</legend>';
		$htmlOut .= '<label for="townNameForCenter" class="required">نام شهرستان:</label>';
		$htmlOut .= '<select name="townNameForCenter" id="townNameForCenter" class="validate first_opt_not_allowed">';
		$res = Database::execute_query("SELECT * FROM `towns` ORDER BY `name` ASC;");
		if (Database::num_of_rows($res) > 0) {
			$htmlOut .= '<option>انتخاب کنید...</option>';
			while ($row = Database::get_assoc_array($res))
				$htmlOut .= '<option value="' . $row['id'] . '">' . $row['name'] . '</option>';
		} else
			$htmlOut .= '<option>' . NO_TOWN_EXISTS . '</option>';
		$htmlOut .= '</select><br />';
		$htmlOut .= '<label for="centerId" class="required">نام مرکز:</label>';
		$htmlOut .= '<select name="centerId" id="centerId" class="validate first_opt_not_allowed"></select><br />';
		$htmlOut .= '<script type="text/javascript">';
		$htmlOut .= '$(document).ajaxStart(function() {$(\'.loading\').show();$(\'#mngDiv\').css(\'opacity\', \'0.3\');});' . '$(document).ajaxSuccess(function() {$(\'.loading\').hide();$(\'#mngDiv\').css(\'opacity\', \'1\');});';
		$htmlOut .= '$(\'#centerId\').load(\'../statisticalPlotter/AsynchronousProccess.php\', {\'townId\' : $(\'#townNameForCenter option:selected\').val()});';
		$htmlOut .= '$(\'#townNameForCenter\').change(function() {$(\'#centerId\').load(\'../statisticalPlotter/AsynchronousProccess.php\', {\'townId\' : $(\'#townNameForCenter option:selected\').val()});});';
		$htmlOut .= '</script>';
		$htmlOut .= '<label for="input_drug_name" class="required">نام دارو:</label>';
		$htmlOut .= '<select name="input_drug_name" id="input_drug_name" class="validate first_opt_not_allowed ';
		if (isset($r['invalid']['input_drug_name']))
			$htmlOut .= 'invalid';
		$htmlOut .= ' ">';
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
		$htmlOut .= '</textarea><br /><input type="submit" name="submit_eash_AddCenterDrugsAndConsumingEquipments" value="ثبت خرید" /></fieldset></form>';

		$htmlOut .= '<fieldset style="width: 580px;"><legend>مشاهده ی داروهای مراکز</legend>';
		$htmlOut .= '<br /><label for="townId" class="required">نام شهرستان:</label>';
		$htmlOut .= '<select name="townId" id="townId" class="validate">';
		$res = Database::execute_query("SELECT * FROM `towns` ORDER BY `name` ASC;");
		if (Database::num_of_rows($res) > 0) {
			while ($row = Database::get_assoc_array($res))
				$htmlOut .= '<option value="' . $row['id'] . '">' . $row['name'] . '</option>';
		} else
			$htmlOut .= '<option>' . NO_TOWN_EXISTS . '</option>';
		$htmlOut .= '</select>&nbsp; --> &nbsp;';
		$htmlOut .= '<label for="centerId_1" class="required">نام مرکز:</label>';
		$htmlOut .= '<select name="centerId_1" id="centerId_1" class="validate"></select>';
		$htmlOut .= '<script type="text/javascript">';
		$htmlOut .= 'var ajaxPost = $.post(\'../statisticalPlotter/AsynchronousProccess.php\', {\'townId\' : $(\'#townId option:selected\').val()});';
		$htmlOut .= 'ajaxPost.done(function(data) {$(\'#centerId_1\').empty().append(data);$(\'#list\').load(\'./ea-sh-ajax.php\', {\'centerIdForDrugs\' : $(\'#centerId_1 option:selected\').val()});});';
		$htmlOut .= '$(\'#townId\').change(function() {var ajaxPost = $.post(\'../statisticalPlotter/AsynchronousProccess.php\', {\'townId\' : $(\'#townId option:selected\').val()});' . 'ajaxPost.done(function(data) {$(\'#centerId_1\').empty().append(data);$(\'#list\').load(\'./ea-sh-ajax.php\', {\'centerIdForDrugs\' : $(\'#centerId_1 option:selected\').val()});});});';
		$htmlOut .= '$(\'#centerId_1\').change(function() {$(\'#list\').load(\'./ea-sh-ajax.php\', {\'centerIdForDrugs\' : $(\'#centerId_1 option:selected\').val()});});';
		$htmlOut .= '</script>';
		$htmlOut .= '<div id="list"></div><br /></fieldset>';
		$htmlOut .= '</div>';

		return $htmlOut;
	}

	private static function manageCentersConsumingsForm() {
		$htmlOut = '';
		$htmlOut .= '<p style="margin-right: 20px;">در این قسمت می توانید تجهیزات مصرفی مراکز را تعیین نمایید.</p>';
		$htmlOut .= '<p style="text-align: right; font-weight: bold; font-size: 15px; margin-right: 20px"><a href="#" style="color: green;" id="slideLink" ><img src="../illustrator/images/icons/plus.png" />&nbsp;افزودن</a></p>';
		$htmlOut .= '<div class="loading"><img src="../statisticalPlotter/loading.gif" />&nbsp;&nbsp;در حال بارگذاری؛ لطفا صبر کنید...</div><br />';
		$htmlOut .= '<div id="mngDiv"><form action="' . substr(strrchr($_SERVER['PHP_SELF'], '/'), 1) . '" method="post" id="slideForm" class="narin">';
		$htmlOut .= '<input type="hidden" name="narin:token" value="' . Narin::create_token() . '" /><fieldset><legend>ثبت تجهیزات مصرفی</legend>';
		$htmlOut .= '<label for="townNameForCenter" class="required">نام شهرستان:</label>';
		$htmlOut .= '<select name="townNameForCenter" id="townNameForCenter" class="validate first_opt_not_allowed">';
		$res = Database::execute_query("SELECT * FROM `towns` ORDER BY `name` ASC;");
		if (Database::num_of_rows($res) > 0) {
			$htmlOut .= '<option>انتخاب کنید...</option>';
			while ($row = Database::get_assoc_array($res))
				$htmlOut .= '<option value="' . $row['id'] . '">' . $row['name'] . '</option>';
		} else
			$htmlOut .= '<option>' . NO_TOWN_EXISTS . '</option>';
		$htmlOut .= '</select><br />';
		$htmlOut .= '<label for="centerId" class="required">نام مرکز:</label>';
		$htmlOut .= '<select name="centerId" id="centerId" class="validate first_opt_not_allowed"></select><br />';
		$htmlOut .= '<script type="text/javascript">';
		$htmlOut .= '$(document).ajaxStart(function() {$(\'.loading\').show();$(\'#mngDiv\').css(\'opacity\', \'0.3\');});' . '$(document).ajaxSuccess(function() {$(\'.loading\').hide();$(\'#mngDiv\').css(\'opacity\', \'1\');});';
		$htmlOut .= '$(\'#centerId\').load(\'../statisticalPlotter/AsynchronousProccess.php\', {\'townId\' : $(\'#townNameForCenter option:selected\').val()});';
		$htmlOut .= '$(\'#townNameForCenter\').change(function() {$(\'#centerId\').load(\'../statisticalPlotter/AsynchronousProccess.php\', {\'townId\' : $(\'#townNameForCenter option:selected\').val()});});';
		$htmlOut .= '</script>';
		$htmlOut .= '<label for="input_drug_name" class="required">نام وسیله:</label>';
		$htmlOut .= '<select name="input_drug_name" id="input_drug_name" class="validate first_opt_not_allowed ';
		if (isset($r['invalid']['input_drug_name']))
			$htmlOut .= 'invalid';
		$htmlOut .= ' ">';
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
		$htmlOut .= '</textarea><br /><input type="submit" name="submit_eash_AddCenterDrugsAndConsumingEquipments" value="ثبت خرید" /></fieldset></form>';

		$htmlOut .= '<fieldset style="width: 580px;"><legend>مشاهده ی تجهیزات مصرفی مراکز</legend>';
		$htmlOut .= '<br /><label for="townId" class="required">نام شهرستان:</label>';
		$htmlOut .= '<select name="townId" id="townId" class="validate">';
		$res = Database::execute_query("SELECT * FROM `towns` ORDER BY `name` ASC;");
		if (Database::num_of_rows($res) > 0) {
			while ($row = Database::get_assoc_array($res))
				$htmlOut .= '<option value="' . $row['id'] . '">' . $row['name'] . '</option>';
		} else
			$htmlOut .= '<option>' . NO_TOWN_EXISTS . '</option>';
		$htmlOut .= '</select>&nbsp; --> &nbsp;';
		$htmlOut .= '<label for="centerId_1" class="required">نام مرکز:</label>';
		$htmlOut .= '<select name="centerId_1" id="centerId_1" class="validate"></select>';
		$htmlOut .= '<script type="text/javascript">';
		$htmlOut .= 'var ajaxPost = $.post(\'../statisticalPlotter/AsynchronousProccess.php\', {\'townId\' : $(\'#townId option:selected\').val()});';
		$htmlOut .= 'ajaxPost.done(function(data) {$(\'#centerId_1\').empty().append(data);$(\'#list\').load(\'./ea-sh-ajax.php\', {\'centerIdForConsumings\' : $(\'#centerId_1 option:selected\').val()});});';
		$htmlOut .= '$(\'#townId\').change(function() {var ajaxPost = $.post(\'../statisticalPlotter/AsynchronousProccess.php\', {\'townId\' : $(\'#townId option:selected\').val()});' . 'ajaxPost.done(function(data) {$(\'#centerId_1\').empty().append(data);$(\'#list\').load(\'./ea-sh-ajax.php\', {\'centerIdForConsumings\' : $(\'#centerId_1 option:selected\').val()});});});';
		$htmlOut .= '$(\'#centerId_1\').change(function() {$(\'#list\').load(\'./ea-sh-ajax.php\', {\'centerIdForConsumings\' : $(\'#centerId_1 option:selected\').val()});});';
		$htmlOut .= '</script>';
		$htmlOut .= '<div id="list"></div><br /></fieldset>';
		$htmlOut .= '</div>';

		return $htmlOut;
	}

	private static function manageHygieneUnitsDrugsForm() {
		$htmlOut = '';
		$htmlOut .= '<p style="margin-right: 20px;">در این قسمت می توانید داروهای خانه‌های بهداشت را تعیین نمایید.</p>';
		$htmlOut .= '<p style="text-align: right; font-weight: bold; font-size: 15px; margin-right: 20px"><a href="#" style="color: green;" id="slideLink" ><img src="../illustrator/images/icons/plus.png" />&nbsp;افزودن</a></p>';
		$htmlOut .= '<div class="loading"><img src="../statisticalPlotter/loading.gif" />&nbsp;&nbsp;در حال بارگذاری؛ لطفا صبر کنید...</div><br />';
		$htmlOut .= '<div id="mngDiv"><form action="' . substr(strrchr($_SERVER['PHP_SELF'], '/'), 1) . '" method="post" id="slideForm" class="narin">';
		$htmlOut .= '<input type="hidden" name="narin:token" value="' . Narin::create_token() . '" /><fieldset><legend>ثبت دارو</legend>';
		$htmlOut .= '<label for="townId" class="required">نام شهرستان:</label>';
		$htmlOut .= '<select name="townId" id="townId" class="validate first_opt_not_allowed">';
		$res = Database::execute_query("SELECT * FROM `towns` ORDER BY `name` ASC;");
		if (Database::num_of_rows($res) > 0) {
			$htmlOut .= '<option>انتخاب کنید...</option>';
			while ($row = Database::get_assoc_array($res))
				$htmlOut .= '<option value="' . $row['id'] . '">' . $row['name'] . '</option>';
		} else
			$htmlOut .= '<option>' . NO_TOWN_EXISTS . '</option>';
		$htmlOut .= '</select><br />';
		$htmlOut .= '<label for="centerId" class="required">نام مرکز:</label>';
		$htmlOut .= '<select name="centerId" id="centerId" class="validate first_opt_not_allowed"></select><br />';
		$htmlOut .= '<label for="hygieneUnitId" class="required">نام خانه بهداشت:</label>';
		$htmlOut .= '<select name="hygieneUnitId" id="hygieneUnitId" class="validate first_opt_not_allowed"></select><br />';
		$htmlOut .= '<script type="text/javascript">';
		$htmlOut .= '$(document).ajaxStart(function() {$(\'.loading\').show();$(\'#mngDiv\').css(\'opacity\', \'0.3\');});' . '$(document).ajaxSuccess(function() {$(\'.loading\').hide();$(\'#mngDiv\').css(\'opacity\', \'1\');});';
		$htmlOut .= 'var ajaxPost = $.post(\'../statisticalPlotter/AsynchronousProccess.php\', {\'townId\' : $(\'#townId option:selected\').val()});ajaxPost.done(function(data) {$(\'#centerId\').empty().append(data);$(\'#hygieneUnitId\').load(\'../statisticalPlotter/AsynchronousProccess.php\', {\'centerIdForHygieneUnitSelection\' : $(\'#centerId option:selected\').val()});});';
		$htmlOut .= '$(\'#townId\').change(function() {var ajaxPost1 = $.post(\'../statisticalPlotter/AsynchronousProccess.php\', {\'townId\' : $(\'#townId option:selected\').val()});ajaxPost1.done(function(data) {$(\'#centerId\').empty().append(data);$(\'#hygieneUnitId\').load(\'../statisticalPlotter/AsynchronousProccess.php\', {\'centerIdForHygieneUnitSelection\' : $(\'#centerId option:selected\').val()});});});';
		$htmlOut .= '$(\'#centerId\').change(function() {$(\'#hygieneUnitId\').load(\'../statisticalPlotter/AsynchronousProccess.php\', {\'centerIdForHygieneUnitSelection\' : $(\'#centerId option:selected\').val()});});';
		$htmlOut .= '</script>';
		$htmlOut .= '<label for="input_drug_name" class="required">نام دارو:</label>';
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
		$htmlOut .= '</textarea><br /><input type="submit" name="submit_eash_AddHygieneUnitDrugsAndConsumingEquipments" value="ثبت خرید" /></fieldset></form>';

		$htmlOut .= '<fieldset style="width: 580px;"><legend>مشاهده ی داروهای خانه‌های بهداشت</legend>';
		$htmlOut .= '<br /><label for="townId_1" class="required">نام شهرستان:</label>';
		$htmlOut .= '<select name="townId_1" id="townId_1" class="validate">';
		$res = Database::execute_query("SELECT * FROM `towns` ORDER BY `name` ASC;");
		if (Database::num_of_rows($res) > 0) {
			while ($row = Database::get_assoc_array($res))
				$htmlOut .= '<option value="' . $row['id'] . '">' . $row['name'] . '</option>';
		} else
			$htmlOut .= '<option>' . NO_TOWN_EXISTS . '</option>';
		$htmlOut .= '</select>&nbsp; --> &nbsp;';
		$htmlOut .= '<label for="centerId_1" class="required">نام مرکز:</label>';
		$htmlOut .= '<select name="centerId_1" id="centerId_1" class="validate"></select><br /><br />';
		$htmlOut .= '<label for="hygieneUnitId_1" class="required">نام خانه بهداشت:</label>';
		$htmlOut .= '<select name="hygieneUnitId_1" id="hygieneUnitId_1" class="validate"></select><br />';
		$htmlOut .= '<script type="text/javascript">';
		$htmlOut .= 'var ajaxPost = $.post(\'../statisticalPlotter/AsynchronousProccess.php\', {\'townId\' : $(\'#townId_1 option:selected\').val()});ajaxPost.done(function(data) {$(\'#centerId_1\').empty().append(data);var ajaxPost1 = $.post(\'../statisticalPlotter/AsynchronousProccess.php\', {\'centerIdForHygieneUnitSelection\' : $(\'#centerId_1 option:selected\').val()});ajaxPost1.done(function(data) {$(\'#hygieneUnitId_1\').empty().append(data);$(\'#list\').load(\'./ea-sh-ajax.php\', {\'hygieneUnitIdForDrugs\' : $(\'#hygieneUnitId_1 option:selected\').val()});});});';
		$htmlOut .= '$(\'#townId_1\').change(function() {var ajaxPost = $.post(\'../statisticalPlotter/AsynchronousProccess.php\', {\'townId\' : $(\'#townId_1 option:selected\').val()});ajaxPost.done(function(data) {$(\'#centerId_1\').empty().append(data);var ajaxPost1 = $.post(\'../statisticalPlotter/AsynchronousProccess.php\', {\'centerIdForHygieneUnitSelection\' : $(\'#centerId_1 option:selected\').val()});ajaxPost1.done(function(data) {$(\'#hygieneUnitId_1\').empty().append(data);$(\'#list\').load(\'./ea-sh-ajax.php\', {\'hygieneUnitIdForDrugs\' : $(\'#hygieneUnitId_1 option:selected\').val()});});});});';
		$htmlOut .= '$(\'#centerId_1\').change(function() {var ajaxPost = $.post(\'../statisticalPlotter/AsynchronousProccess.php\', {\'centerIdForHygieneUnitSelection\' : $(\'#centerId_1 option:selected\').val()});ajaxPost.done(function(data) {$(\'#hygieneUnitId_1\').empty().append(data);$(\'#list\').load(\'./ea-sh-ajax.php\', {\'hygieneUnitIdForDrugs\' : $(\'#hygieneUnitId_1 option:selected\').val()});});});';
		$htmlOut .= '$(\'#hygieneUnitId_1\').change(function() {$(\'#list\').load(\'./ea-sh-ajax.php\', {\'hygieneUnitIdForDrugs\' : $(\'#hygieneUnitId_1 option:selected\').val()});});';
		$htmlOut .= '</script>';
		$htmlOut .= '<div id="list"></div><br /></fieldset>';
		$htmlOut .= '</div>';

		return $htmlOut;
	}

	private static function manageHygieneUnitsConsumingsForm() {
		$htmlOut = '';
		$htmlOut .= '<p style="margin-right: 20px;">در این قسمت می توانید تجهیزات مصرفی خانه های بهداشت را تعیین نمایید.</p>';
		$htmlOut .= '<p style="text-align: right; font-weight: bold; font-size: 15px; margin-right: 20px"><a href="#" style="color: green;" id="slideLink" ><img src="../illustrator/images/icons/plus.png" />&nbsp;افزودن</a></p>';
		$htmlOut .= '<div class="loading"><img src="../statisticalPlotter/loading.gif" />&nbsp;&nbsp;در حال بارگذاری؛ لطفا صبر کنید...</div><br />';
		$htmlOut .= '<div id="mngDiv"><form action="' . substr(strrchr($_SERVER['PHP_SELF'], '/'), 1) . '" method="post" id="slideForm" class="narin">';
		$htmlOut .= '<input type="hidden" name="narin:token" value="' . Narin::create_token() . '" /><fieldset><legend>ثبت تجهیزات مصرفی</legend>';
		$htmlOut .= '<label for="townId" class="required">نام شهرستان:</label>';
		$htmlOut .= '<select name="townId" id="townId" class="validate first_opt_not_allowed">';
		$res = Database::execute_query("SELECT * FROM `towns` ORDER BY `name` ASC;");
		if (Database::num_of_rows($res) > 0) {
			$htmlOut .= '<option>انتخاب کنید...</option>';
			while ($row = Database::get_assoc_array($res))
				$htmlOut .= '<option value="' . $row['id'] . '">' . $row['name'] . '</option>';
		} else
			$htmlOut .= '<option>' . NO_TOWN_EXISTS . '</option>';
		$htmlOut .= '</select><br />';
		$htmlOut .= '<label for="centerId" class="required">نام مرکز:</label>';
		$htmlOut .= '<select name="centerId" id="centerId" class="validate first_opt_not_allowed"></select><br />';
		$htmlOut .= '<label for="hygieneUnitId" class="required">نام خانه‌بهداشت:</label>';
		$htmlOut .= '<select name="hygieneUnitId" id="hygieneUnitId" class="validate first_opt_not_allowed"></select><br />';
		$htmlOut .= '<script type="text/javascript">';
		$htmlOut .= '$(document).ajaxStart(function() {$(\'.loading\').show();$(\'#mngDiv\').css(\'opacity\', \'0.3\');});' . '$(document).ajaxSuccess(function() {$(\'.loading\').hide();$(\'#mngDiv\').css(\'opacity\', \'1\');});';
		$htmlOut .= 'var ajaxPost = $.post(\'../statisticalPlotter/AsynchronousProccess.php\', {\'townId\' : $(\'#townId option:selected\').val()});ajaxPost.done(function(data) {$(\'#centerId\').empty().append(data);$(\'#hygieneUnitId\').load(\'../statisticalPlotter/AsynchronousProccess.php\', {\'centerIdForHygieneUnitSelection\' : $(\'#centerId option:selected\').val()});});';
		$htmlOut .= '$(\'#townId\').change(function() {var ajaxPost1 = $.post(\'../statisticalPlotter/AsynchronousProccess.php\', {\'townId\' : $(\'#townId option:selected\').val()});ajaxPost1.done(function(data) {$(\'#centerId\').empty().append(data);$(\'#hygieneUnitId\').load(\'../statisticalPlotter/AsynchronousProccess.php\', {\'centerIdForHygieneUnitSelection\' : $(\'#centerId option:selected\').val()});});});';
		$htmlOut .= '$(\'#centerId\').change(function() {$(\'#hygieneUnitId\').load(\'../statisticalPlotter/AsynchronousProccess.php\', {\'centerIdForHygieneUnitSelection\' : $(\'#centerId option:selected\').val()});});';
		$htmlOut .= '</script>';
		$htmlOut .= '<label for="input_drug_name" class="required">نام دارو:</label>';
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
		$htmlOut .= '</textarea><br /><input type="submit" name="submit_eash_AddHygieneUnitDrugsAndConsumingEquipments" value="ثبت خرید" /></fieldset></form>';

		$htmlOut .= '<fieldset style="width: 580px;"><legend>مشاهده ی تجهیزات مصرفی خانه‌های بهداشت</legend>';
		$htmlOut .= '<br /><label for="townId_1" class="required">نام شهرستان:</label>';
		$htmlOut .= '<select name="townId_1" id="townId_1" class="validate">';
		$res = Database::execute_query("SELECT * FROM `towns` ORDER BY `name` ASC;");
		if (Database::num_of_rows($res) > 0) {
			while ($row = Database::get_assoc_array($res))
				$htmlOut .= '<option value="' . $row['id'] . '">' . $row['name'] . '</option>';
		} else
			$htmlOut .= '<option>' . NO_TOWN_EXISTS . '</option>';
		$htmlOut .= '</select>&nbsp; --> &nbsp;';
		$htmlOut .= '<label for="centerId_1" class="required">نام مرکز:</label>';
		$htmlOut .= '<select name="centerId_1" id="centerId_1" class="validate"></select><br /><br />';
		$htmlOut .= '<label for="hygieneUnitId_1" class="required">نام خانه‌بهداشت:</label>';
		$htmlOut .= '<select name="hygieneUnitId_1" id="hygieneUnitId_1" class="validate"></select><br />';
		$htmlOut .= '<script type="text/javascript">';
		$htmlOut .= 'var ajaxPost = $.post(\'../statisticalPlotter/AsynchronousProccess.php\', {\'townId\' : $(\'#townId_1 option:selected\').val()});ajaxPost.done(function(data) {$(\'#centerId_1\').empty().append(data);var ajaxPost1 = $.post(\'../statisticalPlotter/AsynchronousProccess.php\', {\'centerIdForHygieneUnitSelection\' : $(\'#centerId_1 option:selected\').val()});ajaxPost1.done(function(data) {$(\'#hygieneUnitId_1\').empty().append(data);$(\'#list\').load(\'./ea-sh-ajax.php\', {\'hygieneUnitIdForConsumings\' : $(\'#hygieneUnitId_1 option:selected\').val()});});});';
		$htmlOut .= '$(\'#townId_1\').change(function() {var ajaxPost = $.post(\'../statisticalPlotter/AsynchronousProccess.php\', {\'townId\' : $(\'#townId_1 option:selected\').val()});ajaxPost.done(function(data) {$(\'#centerId_1\').empty().append(data);var ajaxPost1 = $.post(\'../statisticalPlotter/AsynchronousProccess.php\', {\'centerIdForHygieneUnitSelection\' : $(\'#centerId_1 option:selected\').val()});ajaxPost1.done(function(data) {$(\'#hygieneUnitId_1\').empty().append(data);$(\'#list\').load(\'./ea-sh-ajax.php\', {\'hygieneUnitIdForConsumings\' : $(\'#hygieneUnitId_1 option:selected\').val()});});});});';
		$htmlOut .= '$(\'#centerId_1\').change(function() {var ajaxPost = $.post(\'../statisticalPlotter/AsynchronousProccess.php\', {\'centerIdForHygieneUnitSelection\' : $(\'#centerId_1 option:selected\').val()});ajaxPost.done(function(data) {$(\'#hygieneUnitId_1\').empty().append(data);$(\'#list\').load(\'./ea-sh-ajax.php\', {\'hygieneUnitIdForConsumings\' : $(\'#hygieneUnitId_1 option:selected\').val()});});});';
		$htmlOut .= '$(\'#hygieneUnitId_1\').change(function() {$(\'#list\').load(\'./ea-sh-ajax.php\', {\'hygieneUnitIdForConsumings\' : $(\'#hygieneUnitId_1 option:selected\').val()});});';
		$htmlOut .= '</script>';
		$htmlOut .= '<div id="list"></div><br /></fieldset>';
		$htmlOut .= '</div>';

		return $htmlOut;
	}

	private static function manageCentersNonConsumingsForm() {
		$htmlOut = '';
		$htmlOut .= '<p style="margin-right: 20px;">در این قسمت می توانید تجهیزات غیرمصرفی مراکز را تعیین نمایید.</p>';
		$htmlOut .= '<p style="text-align: right; font-weight: bold; font-size: 15px; margin-right: 20px"><a href="#" style="color: green;" id="slideLink" ><img src="../illustrator/images/icons/plus.png" />&nbsp;افزودن</a></p>';
		$htmlOut .= '<div class="loading"><img src="../statisticalPlotter/loading.gif" />&nbsp;&nbsp;در حال بارگذاری؛ لطفا صبر کنید...</div><br />';
		$htmlOut .= '<div id="mngDiv"><form action="' . substr(strrchr($_SERVER['PHP_SELF'], '/'), 1) . '" method="post" id="slideForm" class="narin">';
		$htmlOut .= '<input type="hidden" name="narin:token" value="' . Narin::create_token() . '" /><fieldset><legend>ثبت خرید تجهیزات مصرفی</legend>';
		$htmlOut .= '<label for="townNameForCenter" class="required">نام شهرستان:</label>';
		$htmlOut .= '<select name="townNameForCenter" id="townNameForCenter" class="validate first_opt_not_allowed">';
		$res = Database::execute_query("SELECT * FROM `towns` ORDER BY `name` ASC;");
		if (Database::num_of_rows($res) > 0) {
			$htmlOut .= '<option>انتخاب کنید...</option>';
			while ($row = Database::get_assoc_array($res))
				$htmlOut .= '<option value="' . $row['id'] . '">' . $row['name'] . '</option>';
		} else
			$htmlOut .= '<option>' . NO_TOWN_EXISTS . '</option>';
		$htmlOut .= '</select><br />';
		$htmlOut .= '<label for="centerId" class="required">نام مرکز:</label>';
		$htmlOut .= '<select name="centerId" id="centerId" class="validate first_opt_not_allowed"></select><br />';
		$htmlOut .= '<script type="text/javascript">';
		$htmlOut .= '$(document).ajaxStart(function() {$(\'.loading\').show();$(\'#mngDiv\').css(\'opacity\', \'0.3\');});' . '$(document).ajaxSuccess(function() {$(\'.loading\').hide();$(\'#mngDiv\').css(\'opacity\', \'1\');});';
		$htmlOut .= '$(\'#centerId\').load(\'../statisticalPlotter/AsynchronousProccess.php\', {\'townId\' : $(\'#townNameForCenter option:selected\').val()});';
		$htmlOut .= '$(\'#townNameForCenter\').change(function() {$(\'#centerId\').load(\'../statisticalPlotter/AsynchronousProccess.php\', {\'townId\' : $(\'#townNameForCenter option:selected\').val()});});';
		$htmlOut .= '</script>';
		$htmlOut .= '<label for="input_equip_id">نام وسیله:</label>';
		$htmlOut .= '<select name="input_equip_id" id="input_equip_id" class="validate first_opt_not_allowed';
		if (isset($r['invalid']['input_equip_id']))
			$htmlOut .= ' invalid';
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
		$htmlOut .= '<input type="submit" name="submit_eash_AddCenterNonConsumingEquipments" value="ثبت" /></fieldset></form>';

		$htmlOut .= '<fieldset style="width: 580px;"><legend>مشاهده ی تجهیزات غیرمصرفی مراکز</legend>';
		$htmlOut .= '<br /><label for="townId" class="required">نام شهرستان:</label>';
		$htmlOut .= '<select name="townId" id="townId" class="validate">';
		$res = Database::execute_query("SELECT * FROM `towns` ORDER BY `name` ASC;");
		if (Database::num_of_rows($res) > 0) {
			while ($row = Database::get_assoc_array($res))
				$htmlOut .= '<option value="' . $row['id'] . '">' . $row['name'] . '</option>';
		} else
			$htmlOut .= '<option>' . NO_TOWN_EXISTS . '</option>';
		$htmlOut .= '</select>&nbsp; --> &nbsp;';
		$htmlOut .= '<label for="centerId_1" class="required">نام مرکز:</label>';
		$htmlOut .= '<select name="centerId_1" id="centerId_1" class="validate"></select>';
		$htmlOut .= '<script type="text/javascript">';
		$htmlOut .= 'var ajaxPost = $.post(\'../statisticalPlotter/AsynchronousProccess.php\', {\'townId\' : $(\'#townId option:selected\').val()});';
		$htmlOut .= 'ajaxPost.done(function(data) {$(\'#centerId_1\').empty().append(data);$(\'#list\').load(\'./ea-sh-ajax.php\', {\'centerIdForNonConsumings\' : $(\'#centerId_1 option:selected\').val()});});';
		$htmlOut .= '$(\'#townId\').change(function() {var ajaxPost = $.post(\'../statisticalPlotter/AsynchronousProccess.php\', {\'townId\' : $(\'#townId option:selected\').val()});' . 'ajaxPost.done(function(data) {$(\'#centerId_1\').empty().append(data);$(\'#list\').load(\'./ea-sh-ajax.php\', {\'centerIdForNonConsumings\' : $(\'#centerId_1 option:selected\').val()});});});';
		$htmlOut .= '$(\'#centerId_1\').change(function() {$(\'#list\').load(\'./ea-sh-ajax.php\', {\'centerIdForNonConsumings\' : $(\'#centerId_1 option:selected\').val()});});';
		$htmlOut .= '</script>';
		$htmlOut .= '<div id="list"></div><br /></fieldset>';
		$htmlOut .= '</div>';

		return $htmlOut;
	}

	private static function manageHygieneUnitsNonConsumingsForm() {
		$htmlOut = '';
		$htmlOut .= '<p style="margin-right: 20px;">در این قسمت می توانید تجهیزات غیرمصرفی خانه های بهداشت  را تعیین نمایید.</p>';
		$htmlOut .= '<p style="text-align: right; font-weight: bold; font-size: 15px; margin-right: 20px"><a href="#" style="color: green;" id="slideLink" ><img src="../illustrator/images/icons/plus.png" />&nbsp;افزودن</a></p>';
		$htmlOut .= '<div class="loading"><img src="../statisticalPlotter/loading.gif" />&nbsp;&nbsp;در حال بارگذاری؛ لطفا صبر کنید...</div><br />';
		$htmlOut .= '<div id="mngDiv"><form action="' . substr(strrchr($_SERVER['PHP_SELF'], '/'), 1) . '" method="post" id="slideForm" class="narin">';
		$htmlOut .= '<input type="hidden" name="narin:token" value="' . Narin::create_token() . '" /><fieldset><legend>ثبت خرید تجهیزات مصرفی</legend>';
		$htmlOut .= '<label for="townId" class="required">نام شهرستان:</label>';
		$htmlOut .= '<select name="townId" id="townId" class="validate first_opt_not_allowed">';
		$res = Database::execute_query("SELECT * FROM `towns` ORDER BY `name` ASC;");
		if (Database::num_of_rows($res) > 0) {
			$htmlOut .= '<option>انتخاب کنید...</option>';
			while ($row = Database::get_assoc_array($res))
				$htmlOut .= '<option value="' . $row['id'] . '">' . $row['name'] . '</option>';
		} else
			$htmlOut .= '<option>' . NO_TOWN_EXISTS . '</option>';
		$htmlOut .= '</select><br />';
		$htmlOut .= '<label for="centerId" class="required">نام مرکز:</label>';
		$htmlOut .= '<select name="centerId" id="centerId" class="validate first_opt_not_allowed"></select><br />';
		$htmlOut .= '<label for="hygieneUnitId" class="required">نام خانه بهداشت:</label>';
		$htmlOut .= '<select name="hygieneUnitId" id="hygieneUnitId" class="validate first_opt_not_allowed"></select><br />';
		$htmlOut .= '<script type="text/javascript">';
		$htmlOut .= '$(document).ajaxStart(function() {$(\'.loading\').show();$(\'#mngDiv\').css(\'opacity\', \'0.3\');});' . '$(document).ajaxSuccess(function() {$(\'.loading\').hide();$(\'#mngDiv\').css(\'opacity\', \'1\');});';
		$htmlOut .= 'var ajaxPost = $.post(\'../statisticalPlotter/AsynchronousProccess.php\', {\'townId\' : $(\'#townId option:selected\').val()});ajaxPost.done(function(data) {$(\'#centerId\').empty().append(data);$(\'#hygieneUnitId\').load(\'../statisticalPlotter/AsynchronousProccess.php\', {\'centerIdForHygieneUnitSelection\' : $(\'#centerId option:selected\').val()});});';
		$htmlOut .= '$(\'#townId\').change(function() {var ajaxPost1 = $.post(\'../statisticalPlotter/AsynchronousProccess.php\', {\'townId\' : $(\'#townId option:selected\').val()});ajaxPost1.done(function(data) {$(\'#centerId\').empty().append(data);$(\'#hygieneUnitId\').load(\'../statisticalPlotter/AsynchronousProccess.php\', {\'centerIdForHygieneUnitSelection\' : $(\'#centerId option:selected\').val()});});});';
		$htmlOut .= '$(\'#centerId\').change(function() {$(\'#hygieneUnitId\').load(\'../statisticalPlotter/AsynchronousProccess.php\', {\'centerIdForHygieneUnitSelection\' : $(\'#centerId option:selected\').val()});});';
		$htmlOut .= '</script>';
		$htmlOut .= '<label for="input_equip_id">نام وسیله:</label>';
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
		$htmlOut .= '<input type="submit" name="submit_eash_AddHygieneUnitNonConsumingEquipments" value="ثبت" /></fieldset></form>';

		$htmlOut .= '<fieldset style="width: 580px;"><legend>مشاهده ی داروها و تجهیزات غیرمصرفی خانه های بهداشت</legend>';
		$htmlOut .= '<br /><label for="townId_1" class="required">نام شهرستان:</label>';
		$htmlOut .= '<select name="townId_1" id="townId_1" class="validate">';
		$res = Database::execute_query("SELECT * FROM `towns` ORDER BY `name` ASC;");
		if (Database::num_of_rows($res) > 0) {
			while ($row = Database::get_assoc_array($res))
				$htmlOut .= '<option value="' . $row['id'] . '">' . $row['name'] . '</option>';
		} else
			$htmlOut .= '<option>' . NO_TOWN_EXISTS . '</option>';
		$htmlOut .= '</select>&nbsp; --> &nbsp;';
		$htmlOut .= '<label for="centerId_1" class="required">نام مرکز:</label>';
		$htmlOut .= '<select name="centerId_1" id="centerId_1" class="validate"></select><br /><br />';
		$htmlOut .= '<label for="hygieneUnitId_1" class="required">نام خانه بهداشت:</label>';
		$htmlOut .= '<select name="hygieneUnitId_1" id="hygieneUnitId_1" class="validate"></select><br />';
		$htmlOut .= '<script type="text/javascript">';
		$htmlOut .= 'var ajaxPost = $.post(\'../statisticalPlotter/AsynchronousProccess.php\', {\'townId\' : $(\'#townId_1 option:selected\').val()});ajaxPost.done(function(data) {$(\'#centerId_1\').empty().append(data);var ajaxPost1 = $.post(\'../statisticalPlotter/AsynchronousProccess.php\', {\'centerIdForHygieneUnitSelection\' : $(\'#centerId_1 option:selected\').val()});ajaxPost1.done(function(data) {$(\'#hygieneUnitId_1\').empty().append(data);$(\'#list\').load(\'./ea-sh-ajax.php\', {\'hygieneUnitIdForNonConsumings\' : $(\'#hygieneUnitId_1 option:selected\').val()});});});';
		$htmlOut .= '$(\'#townId_1\').change(function() {var ajaxPost = $.post(\'../statisticalPlotter/AsynchronousProccess.php\', {\'townId\' : $(\'#townId_1 option:selected\').val()});ajaxPost.done(function(data) {$(\'#centerId_1\').empty().append(data);var ajaxPost1 = $.post(\'../statisticalPlotter/AsynchronousProccess.php\', {\'centerIdForHygieneUnitSelection\' : $(\'#centerId_1 option:selected\').val()});ajaxPost1.done(function(data) {$(\'#hygieneUnitId_1\').empty().append(data);$(\'#list\').load(\'./ea-sh-ajax.php\', {\'hygieneUnitIdForNonConsumings\' : $(\'#hygieneUnitId_1 option:selected\').val()});});});});';
		$htmlOut .= '$(\'#centerId_1\').change(function() {var ajaxPost = $.post(\'../statisticalPlotter/AsynchronousProccess.php\', {\'centerIdForHygieneUnitSelection\' : $(\'#centerId_1 option:selected\').val()});ajaxPost.done(function(data) {$(\'#hygieneUnitId_1\').empty().append(data);$(\'#list\').load(\'./ea-sh-ajax.php\', {\'hygieneUnitIdForNonConsumings\' : $(\'#hygieneUnitId_1 option:selected\').val()});});});';
		$htmlOut .= '$(\'#hygieneUnitId_1\').change(function() {$(\'#list\').load(\'./ea-sh-ajax.php\', {\'hygieneUnitIdForNonConsumings\' : $(\'#hygieneUnitId_1 option:selected\').val()});});';
		$htmlOut .= '</script>';
		$htmlOut .= '<div id="list"></div><br /></fieldset>';
		$htmlOut .= '</div>';

		return $htmlOut;
	}

	private static function manageCentersBuildingsForm() {
		$htmlOut = '';
		$htmlOut .= '<p style="text-align: right;">در این قسمت شما می توانید ساختمان های مورد استفاده ی مراکز را مدیریت نمایید.</p>';
		$htmlOut .= '<p style="text-align: right; font-weight: bold; font-size: 15px; margin-right: 20px"><a href="#" style="color: green;" id="slideLink" ><img src="../illustrator/images/icons/plus.png" />&nbsp;افزودن ساختمان</a></p>';
		$htmlOut .= '<div class="loading"><img src="../statisticalPlotter/loading.gif" />&nbsp;&nbsp;در حال بارگذاری؛ لطفا صبر کنید...</div><br />';
		$htmlOut .= '<div id="mngDiv"><form action="' . substr(strrchr($_SERVER['PHP_SELF'], '/'), 1) . '" method="post" id="slideForm" class="narin">';
		$htmlOut .= '<input type="hidden" name="narin:token" value="' . Narin::create_token() . '" /><fieldset><legend>افزودن ساختمان</legend>';
		$htmlOut .= '<label for="townNameForCenter" class="required">نام شهرستان:</label>';
		$htmlOut .= '<select name="townNameForCenter" id="townNameForCenter" class="validate first_opt_not_allowed">';
		$res = Database::execute_query("SELECT * FROM `towns` ORDER BY `name` ASC;");
		if (Database::num_of_rows($res) > 0) {
			$htmlOut .= '<option>انتخاب کنید...</option>';
			while ($row = Database::get_assoc_array($res))
				$htmlOut .= '<option value="' . $row['id'] . '">' . $row['name'] . '</option>';
		} else
			$htmlOut .= '<option>' . NO_TOWN_EXISTS . '</option>';
		$htmlOut .= '</select><br />';
		$htmlOut .= '<label for="centerId" class="required">نام مرکز:</label>';
		$htmlOut .= '<select name="centerId" id="centerId" class="validate first_opt_not_allowed"></select><br />';
		$htmlOut .= '<script type="text/javascript">';
		$htmlOut .= '$(document).ajaxStart(function() {$(\'.loading\').show();$(\'#mngDiv\').css(\'opacity\', \'0.3\');});' . '$(document).ajaxSuccess(function() {$(\'.loading\').hide();$(\'#mngDiv\').css(\'opacity\', \'1\');});';
		$htmlOut .= '$(\'#centerId\').load(\'../statisticalPlotter/AsynchronousProccess.php\', {\'townId\' : $(\'#townNameForCenter option:selected\').val()});';
		$htmlOut .= '$(\'#townNameForCenter\').change(function() {$(\'#centerId\').load(\'../statisticalPlotter/AsynchronousProccess.php\', {\'townId\' : $(\'#townNameForCenter option:selected\').val()});});';
		$htmlOut .= '</script>';
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
		$htmlOut .= '<input type="submit" value="ثبت ساختمان" name="submit_eash_AddCenterBuilding" /></fieldset></form>';

		$htmlOut .= '<fieldset style="width: 580px;"><legend>مشاهده ی ساختمان های مراکز</legend>';
		$htmlOut .= '<br /><label for="townId" class="required">نام شهرستان:</label>';
		$htmlOut .= '<select name="townId" id="townId" class="validate">';
		$res = Database::execute_query("SELECT * FROM `towns` ORDER BY `name` ASC;");
		if (Database::num_of_rows($res) > 0) {
			while ($row = Database::get_assoc_array($res))
				$htmlOut .= '<option value="' . $row['id'] . '">' . $row['name'] . '</option>';
		} else
			$htmlOut .= '<option>' . NO_TOWN_EXISTS . '</option>';
		$htmlOut .= '</select>&nbsp; --> &nbsp;';
		$htmlOut .= '<label for="centerId_1" class="required">نام مرکز:</label>';
		$htmlOut .= '<select name="centerId_1" id="centerId_1" class="validate"></select>';
		$htmlOut .= '<script type="text/javascript">';
		$htmlOut .= 'var ajaxPost = $.post(\'../statisticalPlotter/AsynchronousProccess.php\', {\'townId\' : $(\'#townId option:selected\').val()});';
		$htmlOut .= 'ajaxPost.done(function(data) {$(\'#centerId_1\').empty().append(data);$(\'#list\').load(\'./ea-sh-ajax.php\', {\'centerIdForBuildings\' : $(\'#centerId_1 option:selected\').val()});});';
		$htmlOut .= '$(\'#townId\').change(function() {var ajaxPost = $.post(\'../statisticalPlotter/AsynchronousProccess.php\', {\'townId\' : $(\'#townId option:selected\').val()});' . 'ajaxPost.done(function(data) {$(\'#centerId_1\').empty().append(data);$(\'#list\').load(\'./ea-sh-ajax.php\', {\'centerIdForBuildings\' : $(\'#centerId_1 option:selected\').val()});});});';
		$htmlOut .= '$(\'#centerId_1\').change(function() {$(\'#list\').load(\'./ea-sh-ajax.php\', {\'centerIdForBuildings\' : $(\'#centerId_1 option:selected\').val()});});';
		$htmlOut .= '</script>';
		$htmlOut .= '<div id="list"></div><br /></fieldset>';
		$htmlOut .= '</div>';

		return $htmlOut;
	}

	private static function manageCentersBuildingsChargeForm() {
		$htmlOut = '';
		$htmlOut .= '<div class="loading"><img src="../statisticalPlotter/loading.gif" />&nbsp;&nbsp;در حال بارگذاری؛ لطفا صبر کنید...</div><br />';
		$htmlOut .= '<p style="text-align: right; font-weight: bold; font-size: 15px; margin-right: 20px"><a href="#" style="color: green;" id="slideLink" ><img src="../illustrator/images/icons/plus.png" />&nbsp;ثبت</a></p>';
		$htmlOut .= '<div id="mngDiv"><form action="' . substr(strrchr($_SERVER['PHP_SELF'], '/'), 1) . '" id="slideForm" method="post" class="narin">';
		$htmlOut .= '<input type="hidden" name="narin:token" value="' . Narin::create_token() . '" /><fieldset><legend>افزودن هزینه تعمیر یا نگهداری ساختمان برای مراکز</legend>';
		$htmlOut .= '<br /><label for="townNameForCenter" class="required">نام شهرستان:</label>';
		$htmlOut .= '<select name="townNameForCenter" id="townNameForCenter" class="validate first_opt_not_allowed">';
		$res = Database::execute_query("SELECT * FROM `towns` ORDER BY `name` ASC;");
		if (Database::num_of_rows($res) > 0) {
			$htmlOut .= '<option>انتخاب کنید...</option>';
			while ($row = Database::get_assoc_array($res))
				$htmlOut .= '<option value="' . $row['id'] . '">' . $row['name'] . '</option>';
		} else
			$htmlOut .= '<option>' . NO_TOWN_EXISTS . '</option>';
		$htmlOut .= '</select><br />';
		$htmlOut .= '<label for="centerId" class="required">نام مرکز:</label>';
		$htmlOut .= '<select name="centerId" id="centerId" class="validate first_opt_not_allowed"></select><br />';
		$htmlOut .= '<label for="input_building_id" class="required">عنوان ساختمان:</label>';
		$htmlOut .= '<select name="input_building_id" id="input_building_id" class="validate first_opt_not_allowed"></select><br />';
		$htmlOut .= '<script type="text/javascript">';
		$htmlOut .= '$(document).ajaxStart(function() {$(\'.loading\').show();$(\'#mngDiv\').css(\'opacity\', \'0.3\');});' . '$(document).ajaxSuccess(function() {$(\'.loading\').hide();$(\'#mngDiv\').css(\'opacity\', \'1\');});';
		$htmlOut .= 'var ajaxPost = $.post(\'../statisticalPlotter/AsynchronousProccess.php\', {\'townId\' : $(\'#townNameForCenter option:selected\').val()});';
		$htmlOut .= 'ajaxPost.done(function(data) {$(\'#centerId\').empty().append(data);var ajaxPost1 = $.post(\'./ea-sh-ajax.php\', {\'centerIdForBuildingsList\' : $(\'#centerId option:selected\').val()});ajaxPost1.done(function(data) {$(\'#input_building_id\').empty().append(data);});});';
		$htmlOut .= '$(\'#townNameForCenter\').change(function() {var ajaxPost1 = $.post(\'../statisticalPlotter/AsynchronousProccess.php\', {\'townId\' : $(\'#townNameForCenter option:selected\').val()});ajaxPost1.done(function(data) {$(\'#centerId\').empty().append(data);$(\'#input_building_id\').load(\'./ea-sh-ajax.php\', {\'centerIdForBuildingsList\' : $(\'#centerId option:selected\').val()});});});';
		$htmlOut .= '$(\'#centerId\').change(function() {$(\'#input_building_id\').load(\'./ea-sh-ajax.php\', {\'centerIdForBuildingsList\' : $(\'#centerId option:selected\').val()});});';
		$htmlOut .= '</script>';
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
		$htmlOut .= '<input type="submit" name="submitAddCenterBuildingCharge" value="ثبت هزینه" /></fieldset></form>';
		
		$htmlOut .= '<fieldset style="width: 580px;"><legend>مشاهده ی هزینه های ساختمان های مراکز</legend>';
		$htmlOut .= '<br /><label for="townId" class="required">نام شهرستان:</label>';
		$htmlOut .= '<select name="townId" id="townId" class="validate">';
		$res = Database::execute_query("SELECT * FROM `towns` ORDER BY `name` ASC;");
		if (Database::num_of_rows($res) > 0) {
			while ($row = Database::get_assoc_array($res))
				$htmlOut .= '<option value="' . $row['id'] . '">' . $row['name'] . '</option>';
		} else
			$htmlOut .= '<option>' . NO_TOWN_EXISTS . '</option>';
		$htmlOut .= '</select>&nbsp; --> &nbsp;';
		$htmlOut .= '<label for="centerId_1" class="required">نام مرکز:</label>';
		$htmlOut .= '<select name="centerId_1" id="centerId_1" class="validate"></select><br /><br />&nbsp; --> &nbsp;';
		$htmlOut .= '<label for="input_building_id_1" class="required">عنوان ساختمان:</label>';
		$htmlOut .= '<select name="input_building_id_1" id="input_building_id_1" class="validate first_opt_not_allowed"></select><br />';
		$htmlOut .= '<script type="text/javascript">';
		$htmlOut .= 'var ajaxPost = $.post(\'../statisticalPlotter/AsynchronousProccess.php\', {\'townId\' : $(\'#townId option:selected\').val()});ajaxPost.done(function(data) {$(\'#centerId_1\').empty().append(data);var ajaxPost1 = $.post(\'./ea-sh-ajax.php\', {\'centerIdForBuildingsList\' : $(\'#centerId_1 option:selected\').val()});ajaxPost1.done(function(data) {$(\'#input_building_id_1\').empty().append(data);$(\'#list\').load(\'./ea-sh-ajax.php\', {\'buildingIdForBuildingCharge\' : $(\'#input_building_id_1 option:selected\').val()});});});';
		$htmlOut .= '$(\'#townId\').change(function() {var ajaxPost = $.post(\'../statisticalPlotter/AsynchronousProccess.php\', {\'townId\' : $(\'#townId option:selected\').val()});ajaxPost.done(function(data) {$(\'#centerId_1\').empty().append(data);var ajaxPost1 = $.post(\'./ea-sh-ajax.php\', {\'centerIdForBuildingsList\' : $(\'#centerId_1 option:selected\').val()});ajaxPost1.done(function(data) {$(\'#input_building_id_1\').empty().append(data);$(\'#list\').load(\'./ea-sh-ajax.php\', {\'buildingIdForBuildingCharge\' : $(\'#input_building_id_1 option:selected\').val()});});});});';
		$htmlOut .= '$(\'#centerId_1\').change(function() {var ajaxPost = $.post(\'./ea-sh-ajax.php\', {\'centerIdForBuildingsList\' : $(\'#centerId_1 option:selected\').val()});ajaxPost.done(function(data) {$(\'#input_building_id_1\').empty().append(data);$(\'#list\').load(\'./ea-sh-ajax.php\', {\'buildingIdForBuildingCharge\' : $(\'#input_building_id_1 option:selected\').val()});});});';
		$htmlOut .= '$(\'#input_building_id_1\').change(function() {$(\'#list\').load(\'./ea-sh-ajax.php\', {\'buildingIdForBuildingCharge\' : $(\'#input_building_id_1 option:selected\').val()});});';
		$htmlOut .= '</script>';
		$htmlOut .= '<div id="list"></div><br /></fieldset>';
		
		$htmlOut .= '</div>';

		return $htmlOut;
	}

	private static function manageHygieneUnitsBuildingsForm() {
		$htmlOut = '';
		$htmlOut .= '<p style="text-align: right;">در این قسمت شما می توانید ساختمان های مورد استفاده خانه های بهداشت را مدیریت نمایید.</p>';
		$htmlOut .= '<p style="text-align: right; font-weight: bold; font-size: 15px; margin-right: 20px"><a href="#" style="color: green;" id="slideLink" ><img src="../illustrator/images/icons/plus.png" />&nbsp;افزودن ساختمان</a></p>';
		$htmlOut .= '<div class="loading"><img src="../statisticalPlotter/loading.gif" />&nbsp;&nbsp;در حال بارگذاری؛ لطفا صبر کنید...</div><br />';
		$htmlOut .= '<div id="mngDiv"><form action="' . substr(strrchr($_SERVER['PHP_SELF'], '/'), 1) . '" method="post" id="slideForm" class="narin">';
		$htmlOut .= '<input type="hidden" name="narin:token" value="' . Narin::create_token() . '" /><fieldset><legend>افزودن ساختمان</legend>';
		$htmlOut .= '<label for="townId" class="required">نام شهرستان:</label>';
		$htmlOut .= '<select name="townId" id="townId" class="validate first_opt_not_allowed">';
		$res = Database::execute_query("SELECT * FROM `towns` ORDER BY `name` ASC;");
		if (Database::num_of_rows($res) > 0) {
			$htmlOut .= '<option>انتخاب کنید...</option>';
			while ($row = Database::get_assoc_array($res))
				$htmlOut .= '<option value="' . $row['id'] . '">' . $row['name'] . '</option>';
		} else
			$htmlOut .= '<option>' . NO_TOWN_EXISTS . '</option>';
		$htmlOut .= '</select><br />';
		$htmlOut .= '<label for="centerId" class="required">نام مرکز:</label>';
		$htmlOut .= '<select name="centerId" id="centerId" class="validate first_opt_not_allowed"></select><br />';
		$htmlOut .= '<label for="hygieneUnitId" class="required">نام خانه بهداشت:</label>';
		$htmlOut .= '<select name="hygieneUnitId" id="hygieneUnitId" class="validate first_opt_not_allowed"></select><br />';
		$htmlOut .= '<script type="text/javascript">';
		$htmlOut .= '$(document).ajaxStart(function() {$(\'.loading\').show();$(\'#mngDiv\').css(\'opacity\', \'0.3\');});' . '$(document).ajaxSuccess(function() {$(\'.loading\').hide();$(\'#mngDiv\').css(\'opacity\', \'1\');});';
		$htmlOut .= 'var ajaxPost = $.post(\'../statisticalPlotter/AsynchronousProccess.php\', {\'townId\' : $(\'#townId option:selected\').val()});ajaxPost.done(function(data) {$(\'#centerId\').empty().append(data);$(\'#hygieneUnitId\').load(\'../statisticalPlotter/AsynchronousProccess.php\', {\'centerIdForHygieneUnitSelection\' : $(\'#centerId option:selected\').val()});});';
		$htmlOut .= '$(\'#townId\').change(function() {var ajaxPost1 = $.post(\'../statisticalPlotter/AsynchronousProccess.php\', {\'townId\' : $(\'#townId option:selected\').val()});ajaxPost1.done(function(data) {$(\'#centerId\').empty().append(data);$(\'#hygieneUnitId\').load(\'../statisticalPlotter/AsynchronousProccess.php\', {\'centerIdForHygieneUnitSelection\' : $(\'#centerId option:selected\').val()});});});';
		$htmlOut .= '$(\'#centerId\').change(function() {$(\'#hygieneUnitId\').load(\'../statisticalPlotter/AsynchronousProccess.php\', {\'centerIdForHygieneUnitSelection\' : $(\'#centerId option:selected\').val()});});';
		$htmlOut .= '</script>';
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
		$htmlOut .= '<input type="submit" value="ثبت ساختمان" name="submit_eash_AddHygieneUnitBuilding" /></fieldset></form>';

		$htmlOut .= '<fieldset style="width: 580px;"><legend>مشاهده ی ساختمان های خانه های بهداشت</legend>';
		$htmlOut .= '<br /><label for="townId_1" class="required">نام شهرستان:</label>';
		$htmlOut .= '<select name="townId_1" id="townId_1" class="validate">';
		$res = Database::execute_query("SELECT * FROM `towns` ORDER BY `name` ASC;");
		if (Database::num_of_rows($res) > 0) {
			while ($row = Database::get_assoc_array($res))
				$htmlOut .= '<option value="' . $row['id'] . '">' . $row['name'] . '</option>';
		} else
			$htmlOut .= '<option>' . NO_TOWN_EXISTS . '</option>';
		$htmlOut .= '</select>&nbsp; --> &nbsp;';
		$htmlOut .= '<label for="centerId_1" class="required">نام مرکز:</label>';
		$htmlOut .= '<select name="centerId_1" id="centerId_1" class="validate"></select><br /><br />';
		$htmlOut .= '<label for="hygieneUnitId_1" class="required">نام خانه بهداشت:</label>';
		$htmlOut .= '<select name="hygieneUnitId_1" id="hygieneUnitId_1" class="validate"></select><br />';
		$htmlOut .= '<script type="text/javascript">';
		$htmlOut .= 'var ajaxPost = $.post(\'../statisticalPlotter/AsynchronousProccess.php\', {\'townId\' : $(\'#townId_1 option:selected\').val()});ajaxPost.done(function(data) {$(\'#centerId_1\').empty().append(data);var ajaxPost1 = $.post(\'../statisticalPlotter/AsynchronousProccess.php\', {\'centerIdForHygieneUnitSelection\' : $(\'#centerId_1 option:selected\').val()});ajaxPost1.done(function(data) {$(\'#hygieneUnitId_1\').empty().append(data);$(\'#list\').load(\'./ea-sh-ajax.php\', {\'hygieneUnitIdForBuildings\' : $(\'#hygieneUnitId_1 option:selected\').val()});});});';
		$htmlOut .= '$(\'#townId_1\').change(function() {var ajaxPost = $.post(\'../statisticalPlotter/AsynchronousProccess.php\', {\'townId\' : $(\'#townId_1 option:selected\').val()});ajaxPost.done(function(data) {$(\'#centerId_1\').empty().append(data);var ajaxPost1 = $.post(\'../statisticalPlotter/AsynchronousProccess.php\', {\'centerIdForHygieneUnitSelection\' : $(\'#centerId_1 option:selected\').val()});ajaxPost1.done(function(data) {$(\'#hygieneUnitId_1\').empty().append(data);$(\'#list\').load(\'./ea-sh-ajax.php\', {\'hygieneUnitIdForBuildings\' : $(\'#hygieneUnitId_1 option:selected\').val()});});});});';
		$htmlOut .= '$(\'#centerId_1\').change(function() {var ajaxPost = $.post(\'../statisticalPlotter/AsynchronousProccess.php\', {\'centerIdForHygieneUnitSelection\' : $(\'#centerId_1 option:selected\').val()});ajaxPost.done(function(data) {$(\'#hygieneUnitId_1\').empty().append(data);$(\'#list\').load(\'./ea-sh-ajax.php\', {\'hygieneUnitIdForBuildings\' : $(\'#hygieneUnitId_1 option:selected\').val()});});});';
		$htmlOut .= '$(\'#hygieneUnitId_1\').change(function() {$(\'#list\').load(\'./ea-sh-ajax.php\', {\'hygieneUnitIdForBuildings\' : $(\'#hygieneUnitId_1 option:selected\').val()});});';
		$htmlOut .= '</script>';
		$htmlOut .= '<div id="list"></div><br /></fieldset>';
		$htmlOut .= '</div>';

		return $htmlOut;
	}

	private static function manageHygieneUnitsBuildingsChargeForm() {
		$htmlOut = '';
		$htmlOut .= '<p style="text-align: right; font-weight: bold; font-size: 15px; margin-right: 20px"><a href="#" style="color: green;" id="slideLink" ><img src="../illustrator/images/icons/plus.png" />&nbsp;ثبت</a></p>';
		$htmlOut .= '<div class="loading"><img src="../statisticalPlotter/loading.gif" />&nbsp;&nbsp;در حال بارگذاری؛ لطفا صبر کنید...</div><br />';
		$htmlOut .= '<div id="mngDiv"><form action="' . substr(strrchr($_SERVER['PHP_SELF'], '/'), 1) . '" method="post" id="slideForm" class="narin">';
		$htmlOut .= '<input type="hidden" name="narin:token" value="' . Narin::create_token() . '" /><fieldset><legend>افزودن هزینه تعمیر یا نگهداری ساختمان برای خانه های بهداشت</legend>';
		$htmlOut .= '<label for="townId" class="required">نام شهرستان:</label>';
		$htmlOut .= '<select name="townId" id="townId" class="validate first_opt_not_allowed">';
		$res = Database::execute_query("SELECT * FROM `towns` ORDER BY `name` ASC;");
		if (Database::num_of_rows($res) > 0) {
			$htmlOut .= '<option>انتخاب کنید...</option>';
			while ($row = Database::get_assoc_array($res))
				$htmlOut .= '<option value="' . $row['id'] . '">' . $row['name'] . '</option>';
		} else
			$htmlOut .= '<option>' . NO_TOWN_EXISTS . '</option>';
		$htmlOut .= '</select><br />';
		$htmlOut .= '<label for="centerId" class="required">نام مرکز:</label>';
		$htmlOut .= '<select name="centerId" id="centerId" class="validate first_opt_not_allowed"></select><br />';
		$htmlOut .= '<label for="hygieneUnitId" class="required">نام خانه بهداشت:</label>';
		$htmlOut .= '<select name="hygieneUnitId" id="hygieneUnitId" class="validate first_opt_not_allowed"></select><br />';
		$htmlOut .= '<script type="text/javascript">';
		$htmlOut .= '$(document).ajaxStart(function() {$(\'.loading\').show();$(\'#mngDiv\').css(\'opacity\', \'0.3\');});' . '$(document).ajaxSuccess(function() {$(\'.loading\').hide();$(\'#mngDiv\').css(\'opacity\', \'1\');});';
		$htmlOut .= 'var ajaxPost = $.post(\'../statisticalPlotter/AsynchronousProccess.php\', {\'townId\' : $(\'#townId option:selected\').val()});
		ajaxPost.done(function(data) {
			$(\'#centerId\').empty().append(data);
			var ajaxPost_1 = $.post(\'../statisticalPlotter/AsynchronousProccess.php\', {\'centerIdForHygieneUnitSelection\' : $(\'#centerId option:selected\').val()});
			ajaxPost_1.done(function(data) {
				$(\'#hygieneUnitId\').empty().append(data);
				$(\'#input_building_id\').load(\'./ea-sh-ajax.php\', {\'hygieneUnitIdForBuildingsList\' : $(\'#hygieneUnitId option:selected\').val()});
			});
		});';
		$htmlOut .= '$(\'#townId\').change(function() {
			var ajaxPost = $.post(\'../statisticalPlotter/AsynchronousProccess.php\', {\'townId\' : $(\'#townId option:selected\').val()});
		ajaxPost.done(function(data) {
			$(\'#centerId\').empty().append(data);
			var ajaxPost_1 = $.post(\'../statisticalPlotter/AsynchronousProccess.php\', {\'centerIdForHygieneUnitSelection\' : $(\'#centerId option:selected\').val()});
			ajaxPost_1.done(function(data) {
				$(\'#hygieneUnitId\').empty().append(data);
				$(\'#input_building_id\').load(\'./ea-sh-ajax.php\', {\'hygieneUnitIdForBuildingsList\' : $(\'#hygieneUnitId option:selected\').val()});
			});
		});
		});';
		$htmlOut .= '$(\'#centerId\').change(function() {
			var ajaxPost_1 = $.post(\'../statisticalPlotter/AsynchronousProccess.php\', {\'centerIdForHygieneUnitSelection\' : $(\'#centerId option:selected\').val()});
			ajaxPost_1.done(function(data) {
				$(\'#hygieneUnitId\').empty().append(data);
				$(\'#input_building_id\').load(\'./ea-sh-ajax.php\', {\'hygieneUnitIdForBuildingsList\' : $(\'#hygieneUnitId option:selected\').val()});
			});
		});';
		$htmlOut .= '$(\'#hygieneUnitId\').change(function() {
				$(\'#input_building_id\').load(\'./ea-sh-ajax.php\', {\'hygieneUnitIdForBuildingsList\' : $(\'#hygieneUnitId option:selected\').val()});
		});';
		$htmlOut .= '</script>';
		$htmlOut .= '<label for="input_building_id">عنوان ساختمان:</label>';
		$htmlOut .= '<select name="input_building_id" id="input_building_id" class="validate first_opt_not_allowed ';
		if (isset($r['invalid']['input_building_id']))
			$htmlOut .= 'invalid';
		$htmlOut .= '"></select><br />';
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
		$htmlOut .= '<input type="submit" name="submitAddHygieneUnitBuildingCharge" value="ثبت هزینه" /></fieldset></form>';
		
		$htmlOut .= '<fieldset style="width: 580px;"><legend>مشاهده ی هزینه های ساختمان های خانه های بهداشت</legend>';
		$htmlOut .= '<br /><label for="townId_1" class="required">نام شهرستان:</label>';
		$htmlOut .= '<select name="townId_1" id="townId_1" class="validate">';
		$res = Database::execute_query("SELECT * FROM `towns` ORDER BY `name` ASC;");
		if (Database::num_of_rows($res) > 0) {
			while ($row = Database::get_assoc_array($res))
				$htmlOut .= '<option value="' . $row['id'] . '">' . $row['name'] . '</option>';
		} else
			$htmlOut .= '<option>' . NO_TOWN_EXISTS . '</option>';
		$htmlOut .= '</select>&nbsp; --> &nbsp;';
		$htmlOut .= '<label for="centerId_1" class="required">نام مرکز:</label>';
		$htmlOut .= '<select name="centerId_1" id="centerId_1" class="validate"></select><br /><br />&nbsp; --> &nbsp;';
		$htmlOut .= '<label for="hygieneUnitId_1" class="required">نام خانه بهداشت:</label>';
		$htmlOut .= '<select name="hygieneUnitId_1" id="hygieneUnitId_1" class="validate first_opt_not_allowed"></select>';
		$htmlOut .= '<br /><br />&nbsp; --> &nbsp;<label for="input_building_id_1" class="required">عنوان ساختمان:</label>';
		$htmlOut .= '<select name="input_building_id_1" id="input_building_id_1" class="validate first_opt_not_allowed"></select><br />';
		$htmlOut .= '<script type="text/javascript">';
		$htmlOut .= '$(document).ajaxStart(function() {$(\'.loading\').show();$(\'#mngDiv\').css(\'opacity\', \'0.3\');});' . '$(document).ajaxSuccess(function() {$(\'.loading\').hide();$(\'#mngDiv\').css(\'opacity\', \'1\');});';
		$htmlOut .= 'var ajaxPost = $.post(\'../statisticalPlotter/AsynchronousProccess.php\', {\'townId\' : $(\'#townId_1 option:selected\').val()});
		ajaxPost.done(function(data) {
			$(\'#centerId_1\').empty().append(data);
			var ajaxPost_1 = $.post(\'../statisticalPlotter/AsynchronousProccess.php\', {\'centerIdForHygieneUnitSelection\' : $(\'#centerId_1 option:selected\').val()});
			ajaxPost_1.done(function(data) {
				$(\'#hygieneUnitId_1\').empty().append(data);
				var ajaxPost_2 = $.post(\'./ea-sh-ajax.php\', {\'hygieneUnitIdForBuildingsList\' : $(\'#hygieneUnitId_1 option:selected\').val()});
				ajaxPost_2.done(function(data) {
					$(\'#input_building_id_1\').empty().append(data);
					$(\'#list\').load(\'./ea-sh-ajax.php\', {\'buildingIdForHGBuildingCharge\' : $(\'#input_building_id_1 option:selected\').val()});
				});
			});
		});';
		$htmlOut .= '$(\'#townId_1\').change(function() {
			var ajaxPost = $.post(\'../statisticalPlotter/AsynchronousProccess.php\', {\'townId\' : $(\'#townId_1 option:selected\').val()});
			ajaxPost.done(function(data) {
				$(\'#centerId_1\').empty().append(data);
				var ajaxPost_1 = $.post(\'../statisticalPlotter/AsynchronousProccess.php\', {\'centerIdForHygieneUnitSelection\' : $(\'#centerId_1 option:selected\').val()});
				ajaxPost_1.done(function(data) {
					$(\'#hygieneUnitId_1\').empty().append(data);
					var ajaxPost_2 = $.post(\'./ea-sh-ajax.php\', {\'hygieneUnitIdForBuildingsList\' : $(\'#hygieneUnitId_1 option:selected\').val()});
					ajaxPost_2.done(function(data) {
						$(\'#input_building_id_1\').empty().append(data);
						$(\'#list\').load(\'./ea-sh-ajax.php\', {\'buildingIdForHGBuildingCharge\' : $(\'#input_building_id_1 option:selected\').val()});
					});
				});
			});
		});';
		$htmlOut .= '$(\'#centerId_1\').change(function() {
			var ajaxPost_1 = $.post(\'../statisticalPlotter/AsynchronousProccess.php\', {\'centerIdForHygieneUnitSelection\' : $(\'#centerId_1 option:selected\').val()});
			ajaxPost_1.done(function(data) {
			$(\'#hygieneUnitId_1\').empty().append(data);
			var ajaxPost_2 = $.post(\'./ea-sh-ajax.php\', {\'hygieneUnitIdForBuildingsList\' : $(\'#hygieneUnitId_1 option:selected\').val()});
			ajaxPost_2.done(function(data) {
				$(\'#input_building_id_1\').empty().append(data);
				$(\'#list\').load(\'./ea-sh-ajax.php\', {\'buildingIdForHGBuildingCharge\' : $(\'#input_building_id_1 option:selected\').val()});
			});
		});
		});';
		$htmlOut .= '$(\'#hygieneUnitId_1\').change(function() {
				var ajaxPost_2 = $.post(\'./ea-sh-ajax.php\', {\'hygieneUnitIdForBuildingsList\' : $(\'#hygieneUnitId_1 option:selected\').val()});
				ajaxPost_2.done(function(data) {
					$(\'#input_building_id_1\').empty().append(data);
					$(\'#list\').load(\'./ea-sh-ajax.php\', {\'buildingIdForHGBuildingCharge\' : $(\'#input_building_id_1 option:selected\').val()});
				});
		});';
		$htmlOut .= '$(\'#input_building_id_1\').change(function() {$(\'#list\').load(\'./ea-sh-ajax.php\', {\'buildingIdForHGBuildingCharge\' : $(\'#input_building_id_1 option:selected\').val()});});';
		$htmlOut .= '</script>';
		$htmlOut .= '<div id="list"></div><br /></fieldset>';
		
		$htmlOut .= '</div>';

		return $htmlOut;
	}

	private static function manageCentersVehiclesForm() {
		$htmlOut = '';
		$htmlOut .= '<p style="text-align: right;">در این قسمت شما می توانید وسایل نقلیه ی مورد استفاده ی مراکز را مدیریت نمایید.</p>';
		$htmlOut .= '<p style="text-align: right; font-weight: bold; font-size: 15px; margin-right: 20px"><a href="#" style="color: green;" id="slideLink" ><img src="../illustrator/images/icons/plus.png" />&nbsp;افزودن وسیله نقلیه</a></p>';
		$htmlOut .= '<div class="loading"><img src="../statisticalPlotter/loading.gif" />&nbsp;&nbsp;در حال بارگذاری؛ لطفا صبر کنید...</div><br />';
		$htmlOut .= '<div id="mngDiv"><form action="' . substr(strrchr($_SERVER['PHP_SELF'], '/'), 1) . '" method="post" id="slideForm" class="narin">';
		$htmlOut .= '<input type="hidden" name="narin:token" value="' . Narin::create_token() . '" /><fieldset><legend>افزودن وسیله نقلیه</legend>';
		$htmlOut .= '<label for="townNameForCenter" class="required">نام شهرستان:</label>';
		$htmlOut .= '<select name="townNameForCenter" id="townNameForCenter" class="validate first_opt_not_allowed">';
		$res = Database::execute_query("SELECT * FROM `towns` ORDER BY `name` ASC;");
		if (Database::num_of_rows($res) > 0) {
			$htmlOut .= '<option>انتخاب کنید...</option>';
			while ($row = Database::get_assoc_array($res))
				$htmlOut .= '<option value="' . $row['id'] . '">' . $row['name'] . '</option>';
		} else
			$htmlOut .= '<option>' . NO_TOWN_EXISTS . '</option>';
		$htmlOut .= '</select><br />';
		$htmlOut .= '<label for="centerId" class="required">نام مرکز:</label>';
		$htmlOut .= '<select name="centerId" id="centerId" class="validate first_opt_not_allowed"></select><br />';
		$htmlOut .= '<script type="text/javascript">';
		$htmlOut .= '$(document).ajaxStart(function() {$(\'.loading\').show();$(\'#mngDiv\').css(\'opacity\', \'0.3\');});' . '$(document).ajaxSuccess(function() {$(\'.loading\').hide();$(\'#mngDiv\').css(\'opacity\', \'1\');});';
		$htmlOut .= '$(\'#centerId\').load(\'../statisticalPlotter/AsynchronousProccess.php\', {\'townId\' : $(\'#townNameForCenter option:selected\').val()});';
		$htmlOut .= '$(\'#townNameForCenter\').change(function() {$(\'#centerId\').load(\'../statisticalPlotter/AsynchronousProccess.php\', {\'townId\' : $(\'#townNameForCenter option:selected\').val()});});';
		$htmlOut .= '</script>';
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
		$htmlOut .= '<input type="submit" name="submit_eash_AddCenterVehicle" value="ثبت وسیله نقلیه" /></fieldset></form>';

		$htmlOut .= '<fieldset style="width: 580px;"><legend>مشاهده ی وسایل نقلیه ی مراکز</legend>';
		$htmlOut .= '<br /><label for="townId" class="required">نام شهرستان:</label>';
		$htmlOut .= '<select name="townId" id="townId" class="validate">';
		$res = Database::execute_query("SELECT * FROM `towns` ORDER BY `name` ASC;");
		if (Database::num_of_rows($res) > 0) {
			while ($row = Database::get_assoc_array($res))
				$htmlOut .= '<option value="' . $row['id'] . '">' . $row['name'] . '</option>';
		} else
			$htmlOut .= '<option>' . NO_TOWN_EXISTS . '</option>';
		$htmlOut .= '</select>&nbsp; --> &nbsp;';
		$htmlOut .= '<label for="centerId_1" class="required">نام مرکز:</label>';
		$htmlOut .= '<select name="centerId_1" id="centerId_1" class="validate"></select>';
		$htmlOut .= '<script type="text/javascript">';
		$htmlOut .= 'var ajaxPost = $.post(\'../statisticalPlotter/AsynchronousProccess.php\', {\'townId\' : $(\'#townId option:selected\').val()});';
		$htmlOut .= 'ajaxPost.done(function(data) {$(\'#centerId_1\').empty().append(data);$(\'#list\').load(\'./ea-sh-ajax.php\', {\'centerIdForVehicles\' : $(\'#centerId_1 option:selected\').val()});});';
		$htmlOut .= '$(\'#townId\').change(function() {var ajaxPost = $.post(\'../statisticalPlotter/AsynchronousProccess.php\', {\'townId\' : $(\'#townId option:selected\').val()});' . 'ajaxPost.done(function(data) {$(\'#centerId_1\').empty().append(data);$(\'#list\').load(\'./ea-sh-ajax.php\', {\'centerIdForVehicles\' : $(\'#centerId_1 option:selected\').val()});});});';
		$htmlOut .= '$(\'#centerId_1\').change(function() {$(\'#list\').load(\'./ea-sh-ajax.php\', {\'centerIdForVehicles\' : $(\'#centerId_1 option:selected\').val()});});';
		$htmlOut .= '</script>';
		$htmlOut .= '<div id="list"></div><br /></fieldset>';
		$htmlOut .= '</div>';

		return $htmlOut;
	}

	private static function manageCentersVehiclesChargeForm() {
		$htmlOut = '';
		$htmlOut .= '<div class="loading"><img src="../statisticalPlotter/loading.gif" />&nbsp;&nbsp;در حال بارگذاری؛ لطفا صبر کنید...</div><br />';
		$htmlOut .= '<p style="text-align: right; font-weight: bold; font-size: 15px; margin-right: 20px"><a href="#" style="color: green;" id="slideLink" ><img src="../illustrator/images/icons/plus.png" />&nbsp;ثبت</a></p>';
		$htmlOut .= '<div id="mngDiv"><form action="' . substr(strrchr($_SERVER['PHP_SELF'], '/'), 1) . '" id="slideForm" method="post" class="narin">';
		$htmlOut .= '<input type="hidden" name="narin:token" value="' . Narin::create_token() . '" /><fieldset><legend>افزودن هزینه وسیله نقلیه برای مراکز</legend>';
		$htmlOut .= '<br /><label for="townNameForCenter" class="required">نام شهرستان:</label>';
		$htmlOut .= '<select name="townNameForCenter" id="townNameForCenter" class="validate first_opt_not_allowed">';
		$res = Database::execute_query("SELECT * FROM `towns` ORDER BY `name` ASC;");
		if (Database::num_of_rows($res) > 0) {
			$htmlOut .= '<option>انتخاب کنید...</option>';
			while ($row = Database::get_assoc_array($res))
				$htmlOut .= '<option value="' . $row['id'] . '">' . $row['name'] . '</option>';
		} else
			$htmlOut .= '<option>' . NO_TOWN_EXISTS . '</option>';
		$htmlOut .= '</select><br />';
		$htmlOut .= '<label for="centerId" class="required">نام مرکز:</label>';
		$htmlOut .= '<select name="centerId" id="centerId" class="validate first_opt_not_allowed"></select><br />';
		$htmlOut .= '<label for="input_vehicle_id" class="required">عنوان وسیله نقلیه:</label>';
		$htmlOut .= '<select name="input_vehicle_id" id="input_vehicle_id" class="validate first_opt_not_allowed"></select><br />';
		$htmlOut .= '<script type="text/javascript">';
		$htmlOut .= '$(document).ajaxStart(function() {$(\'.loading\').show();$(\'#mngDiv\').css(\'opacity\', \'0.3\');});' . '$(document).ajaxSuccess(function() {$(\'.loading\').hide();$(\'#mngDiv\').css(\'opacity\', \'1\');});';
		$htmlOut .= 'var ajaxPost = $.post(\'../statisticalPlotter/AsynchronousProccess.php\', {\'townId\' : $(\'#townNameForCenter option:selected\').val()});';
		$htmlOut .= 'ajaxPost.done(function(data) {$(\'#centerId\').empty().append(data);var ajaxPost1 = $.post(\'./ea-sh-ajax.php\', {\'centerIdForVehiclesList\' : $(\'#centerId option:selected\').val()});ajaxPost1.done(function(data) {$(\'#input_vehicle_id\').empty().append(data);});});';
		$htmlOut .= '$(\'#townNameForCenter\').change(function() {var ajaxPost1 = $.post(\'../statisticalPlotter/AsynchronousProccess.php\', {\'townId\' : $(\'#townNameForCenter option:selected\').val()});ajaxPost1.done(function(data) {$(\'#centerId\').empty().append(data);$(\'#input_vehicle_id\').load(\'./ea-sh-ajax.php\', {\'centerIdForVehiclesList\' : $(\'#centerId option:selected\').val()});});});';
		$htmlOut .= '$(\'#centerId\').change(function() {$(\'#input_vehicle_id\').load(\'./ea-sh-ajax.php\', {\'centerIdForVehiclesList\' : $(\'#centerId option:selected\').val()});});';
		$htmlOut .= '</script>';
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
		$htmlOut .= '<input type="submit" name="submitAddCenterVehicleCharge" value="ثبت هزینه" /></fieldset></form>';
		
		$htmlOut .= '<fieldset style="width: 580px;"><legend>مشاهده ی هزینه های وسایل نقلیه مراکز</legend>';
		$htmlOut .= '<br /><label for="townId" class="required">نام شهرستان:</label>';
		$htmlOut .= '<select name="townId" id="townId" class="validate">';
		$res = Database::execute_query("SELECT * FROM `towns` ORDER BY `name` ASC;");
		if (Database::num_of_rows($res) > 0) {
			while ($row = Database::get_assoc_array($res))
				$htmlOut .= '<option value="' . $row['id'] . '">' . $row['name'] . '</option>';
		} else
			$htmlOut .= '<option>' . NO_TOWN_EXISTS . '</option>';
		$htmlOut .= '</select>&nbsp; --> &nbsp;';
		$htmlOut .= '<label for="centerId_1" class="required">نام مرکز:</label>';
		$htmlOut .= '<select name="centerId_1" id="centerId_1" class="validate"></select><br /><br />&nbsp; --> &nbsp;';
		$htmlOut .= '<label for="input_vehicle_id_1" class="required">عنوان وسیله نقلیه:</label>';
		$htmlOut .= '<select name="input_vehicle_id_1" id="input_vehicle_id_1" class="validate first_opt_not_allowed"></select><br />';
		$htmlOut .= '<script type="text/javascript">';
		$htmlOut .= 'var ajaxPost = $.post(\'../statisticalPlotter/AsynchronousProccess.php\', {\'townId\' : $(\'#townId option:selected\').val()});ajaxPost.done(function(data) {$(\'#centerId_1\').empty().append(data);var ajaxPost1 = $.post(\'./ea-sh-ajax.php\', {\'centerIdForVehiclesList\' : $(\'#centerId_1 option:selected\').val()});ajaxPost1.done(function(data) {$(\'#input_vehicle_id_1\').empty().append(data);$(\'#list\').load(\'./ea-sh-ajax.php\', {\'vehicleIdForVehicleCharge\' : $(\'#input_vehicle_id_1 option:selected\').val()});});});';
		$htmlOut .= '$(\'#townId\').change(function() {var ajaxPost = $.post(\'../statisticalPlotter/AsynchronousProccess.php\', {\'townId\' : $(\'#townId option:selected\').val()});ajaxPost.done(function(data) {$(\'#centerId_1\').empty().append(data);var ajaxPost1 = $.post(\'./ea-sh-ajax.php\', {\'centerIdForVehiclesList\' : $(\'#centerId_1 option:selected\').val()});ajaxPost1.done(function(data) {$(\'#input_vehicle_id_1\').empty().append(data);$(\'#list\').load(\'./ea-sh-ajax.php\', {\'vehicleIdForVehicleCharge\' : $(\'#input_vehicle_id_1 option:selected\').val()});});});});';
		$htmlOut .= '$(\'#centerId_1\').change(function() {var ajaxPost = $.post(\'./ea-sh-ajax.php\', {\'centerIdForVehiclesList\' : $(\'#centerId_1 option:selected\').val()});ajaxPost.done(function(data) {$(\'#input_vehicle_id_1\').empty().append(data);$(\'#list\').load(\'./ea-sh-ajax.php\', {\'vehicleIdForVehicleCharge\' : $(\'#input_vehicle_id_1 option:selected\').val()});});});';
		$htmlOut .= '$(\'#input_vehicle_id_1\').change(function() {$(\'#list\').load(\'./ea-sh-ajax.php\', {\'vehicleIdForVehicleCharge\' : $(\'#input_vehicle_id_1 option:selected\').val()});});';
		$htmlOut .= '</script>';
		$htmlOut .= '<div id="list"></div><br /></fieldset>';
		
		$htmlOut .= '</div>';

		return $htmlOut;
	}

	private static function manageHygieneUnitsVehiclesForm() {
		$htmlOut = '';
		$htmlOut .= '<p style="text-align: right;">در این قسمت شما می توانید وسایل نقلیه ی مورد استفاده خانه های بهداشت را مدیریت نمایید.</p>';
		$htmlOut .= '<p style="text-align: right; font-weight: bold; font-size: 15px; margin-right: 20px"><a href="#" style="color: green;" id="slideLink" ><img src="../illustrator/images/icons/plus.png" />&nbsp;افزودن وسیله نقلیه</a></p>';
		$htmlOut .= '<div class="loading"><img src="../statisticalPlotter/loading.gif" />&nbsp;&nbsp;در حال بارگذاری؛ لطفا صبر کنید...</div><br />';
		$htmlOut .= '<div id="mngDiv"><form action="' . substr(strrchr($_SERVER['PHP_SELF'], '/'), 1) . '" method="post" id="slideForm" class="narin">';
		$htmlOut .= '<input type="hidden" name="narin:token" value="' . Narin::create_token() . '" /><fieldset><legend>افزودن وسیله نقلیه</legend>';
		$htmlOut .= '<label for="townId" class="required">نام شهرستان:</label>';
		$htmlOut .= '<select name="townId" id="townId" class="validate first_opt_not_allowed">';
		$res = Database::execute_query("SELECT * FROM `towns` ORDER BY `name` ASC;");
		if (Database::num_of_rows($res) > 0) {
			$htmlOut .= '<option>انتخاب کنید...</option>';
			while ($row = Database::get_assoc_array($res))
				$htmlOut .= '<option value="' . $row['id'] . '">' . $row['name'] . '</option>';
		} else
			$htmlOut .= '<option>' . NO_TOWN_EXISTS . '</option>';
		$htmlOut .= '</select><br />';
		$htmlOut .= '<label for="centerId" class="required">نام مرکز:</label>';
		$htmlOut .= '<select name="centerId" id="centerId" class="validate first_opt_not_allowed"></select><br />';
		$htmlOut .= '<label for="hygieneUnitId" class="required">نام خانه بهداشت:</label>';
		$htmlOut .= '<select name="hygieneUnitId" id="hygieneUnitId" class="validate first_opt_not_allowed"></select><br />';
		$htmlOut .= '<script type="text/javascript">';
		$htmlOut .= '$(document).ajaxStart(function() {$(\'.loading\').show();$(\'#mngDiv\').css(\'opacity\', \'0.3\');});' . '$(document).ajaxSuccess(function() {$(\'.loading\').hide();$(\'#mngDiv\').css(\'opacity\', \'1\');});';
		$htmlOut .= 'var ajaxPost = $.post(\'../statisticalPlotter/AsynchronousProccess.php\', {\'townId\' : $(\'#townId option:selected\').val()});ajaxPost.done(function(data) {$(\'#centerId\').empty().append(data);$(\'#hygieneUnitId\').load(\'../statisticalPlotter/AsynchronousProccess.php\', {\'centerIdForHygieneUnitSelection\' : $(\'#centerId option:selected\').val()});});';
		$htmlOut .= '$(\'#townId\').change(function() {var ajaxPost1 = $.post(\'../statisticalPlotter/AsynchronousProccess.php\', {\'townId\' : $(\'#townId option:selected\').val()});ajaxPost1.done(function(data) {$(\'#centerId\').empty().append(data);$(\'#hygieneUnitId\').load(\'../statisticalPlotter/AsynchronousProccess.php\', {\'centerIdForHygieneUnitSelection\' : $(\'#centerId option:selected\').val()});});});';
		$htmlOut .= '$(\'#centerId\').change(function() {$(\'#hygieneUnitId\').load(\'../statisticalPlotter/AsynchronousProccess.php\', {\'centerIdForHygieneUnitSelection\' : $(\'#centerId option:selected\').val()});});';
		$htmlOut .= '</script>';
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
		$htmlOut .= '<input type="submit" name="submit_eash_AddHygieneUnitVehicle" value="ثبت وسیله نقلیه" /></fieldset></form>';

		$htmlOut .= '<br /><fieldset style="width: 580px;"><legend>مشاهده ی وسایل نقلیه خانه های بهداشت</legend>';
		$htmlOut .= '<br /><label for="townId_1" class="required">نام شهرستان:</label>';
		$htmlOut .= '<select name="townId_1" id="townId_1" class="validate">';
		$res = Database::execute_query("SELECT * FROM `towns` ORDER BY `name` ASC;");
		if (Database::num_of_rows($res) > 0) {
			while ($row = Database::get_assoc_array($res))
				$htmlOut .= '<option value="' . $row['id'] . '">' . $row['name'] . '</option>';
		} else
			$htmlOut .= '<option>' . NO_TOWN_EXISTS . '</option>';
		$htmlOut .= '</select>&nbsp; --> &nbsp;';
		$htmlOut .= '<label for="centerId_1" class="required">نام مرکز:</label>';
		$htmlOut .= '<select name="centerId_1" id="centerId_1" class="validate"></select><br /><br />';
		$htmlOut .= '<label for="hygieneUnitId_1" class="required">نام خانه بهداشت:</label>';
		$htmlOut .= '<select name="hygieneUnitId_1" id="hygieneUnitId_1" class="validate"></select><br />';
		$htmlOut .= '<script type="text/javascript">';
		$htmlOut .= 'var ajaxPost = $.post(\'../statisticalPlotter/AsynchronousProccess.php\', {\'townId\' : $(\'#townId_1 option:selected\').val()});ajaxPost.done(function(data) {$(\'#centerId_1\').empty().append(data);var ajaxPost1 = $.post(\'../statisticalPlotter/AsynchronousProccess.php\', {\'centerIdForHygieneUnitSelection\' : $(\'#centerId_1 option:selected\').val()});ajaxPost1.done(function(data) {$(\'#hygieneUnitId_1\').empty().append(data);$(\'#list\').load(\'./ea-sh-ajax.php\', {\'hygieneUnitIdForVehicles\' : $(\'#hygieneUnitId_1 option:selected\').val()});});});';
		$htmlOut .= '$(\'#townId_1\').change(function() {var ajaxPost = $.post(\'../statisticalPlotter/AsynchronousProccess.php\', {\'townId\' : $(\'#townId_1 option:selected\').val()});ajaxPost.done(function(data) {$(\'#centerId_1\').empty().append(data);var ajaxPost1 = $.post(\'../statisticalPlotter/AsynchronousProccess.php\', {\'centerIdForHygieneUnitSelection\' : $(\'#centerId_1 option:selected\').val()});ajaxPost1.done(function(data) {$(\'#hygieneUnitId_1\').empty().append(data);$(\'#list\').load(\'./ea-sh-ajax.php\', {\'hygieneUnitIdForVehicles\' : $(\'#hygieneUnitId_1 option:selected\').val()});});});});';
		$htmlOut .= '$(\'#centerId_1\').change(function() {var ajaxPost = $.post(\'../statisticalPlotter/AsynchronousProccess.php\', {\'centerIdForHygieneUnitSelection\' : $(\'#centerId_1 option:selected\').val()});ajaxPost.done(function(data) {$(\'#hygieneUnitId_1\').empty().append(data);$(\'#list\').load(\'./ea-sh-ajax.php\', {\'hygieneUnitIdForVehicles\' : $(\'#hygieneUnitId_1 option:selected\').val()});});});';
		$htmlOut .= '$(\'#hygieneUnitId_1\').change(function() {$(\'#list\').load(\'./ea-sh-ajax.php\', {\'hygieneUnitIdForVehicles\' : $(\'#hygieneUnitId_1 option:selected\').val()});});';
		$htmlOut .= '</script>';
		$htmlOut .= '<div id="list"></div><br /></fieldset>';
		$htmlOut .= '</div>';

		return $htmlOut;
	}

	private static function manageHygieneUnitsVehiclesChargeForm() {
		$htmlOut = '';
		$htmlOut .= '<p style="text-align: right; font-weight: bold; font-size: 15px; margin-right: 20px"><a href="#" style="color: green;" id="slideLink" ><img src="../illustrator/images/icons/plus.png" />&nbsp;ثبت</a></p>';
		$htmlOut .= '<div class="loading"><img src="../statisticalPlotter/loading.gif" />&nbsp;&nbsp;در حال بارگذاری؛ لطفا صبر کنید...</div><br />';
		$htmlOut .= '<div id="mngDiv"><form action="' . substr(strrchr($_SERVER['PHP_SELF'], '/'), 1) . '" method="post" id="slideForm" class="narin">';
		$htmlOut .= '<input type="hidden" name="narin:token" value="' . Narin::create_token() . '" /><fieldset><legend>افزودن هزینه وسیله نقلیه</legend>';
		$htmlOut .= '<label for="townId" class="required">نام شهرستان:</label>';
		$htmlOut .= '<select name="townId" id="townId" class="validate first_opt_not_allowed">';
		$res = Database::execute_query("SELECT * FROM `towns` ORDER BY `name` ASC;");
		if (Database::num_of_rows($res) > 0) {
			$htmlOut .= '<option>انتخاب کنید...</option>';
			while ($row = Database::get_assoc_array($res))
				$htmlOut .= '<option value="' . $row['id'] . '">' . $row['name'] . '</option>';
		} else
			$htmlOut .= '<option>' . NO_TOWN_EXISTS . '</option>';
		$htmlOut .= '</select><br />';
		$htmlOut .= '<label for="centerId" class="required">نام مرکز:</label>';
		$htmlOut .= '<select name="centerId" id="centerId" class="validate first_opt_not_allowed"></select><br />';
		$htmlOut .= '<label for="hygieneUnitId" class="required">نام خانه بهداشت:</label>';
		$htmlOut .= '<select name="hygieneUnitId" id="hygieneUnitId" class="validate first_opt_not_allowed"></select><br />';
		$htmlOut .= '<script type="text/javascript">';
		$htmlOut .= '$(document).ajaxStart(function() {$(\'.loading\').show();$(\'#mngDiv\').css(\'opacity\', \'0.3\');});' . '$(document).ajaxSuccess(function() {$(\'.loading\').hide();$(\'#mngDiv\').css(\'opacity\', \'1\');});';
		$htmlOut .= 'var ajaxPost = $.post(\'../statisticalPlotter/AsynchronousProccess.php\', {\'townId\' : $(\'#townId option:selected\').val()});
		ajaxPost.done(function(data) {
			$(\'#centerId\').empty().append(data);
			var ajaxPost_1 = $.post(\'../statisticalPlotter/AsynchronousProccess.php\', {\'centerIdForHygieneUnitSelection\' : $(\'#centerId option:selected\').val()});
			ajaxPost_1.done(function(data) {
				$(\'#hygieneUnitId\').empty().append(data);
				$(\'#input_vehicle_id\').load(\'./ea-sh-ajax.php\', {\'hygieneUnitIdForVehiclesList\' : $(\'#hygieneUnitId option:selected\').val()});
			});
		});';
		$htmlOut .= '$(\'#townId\').change(function() {
			var ajaxPost = $.post(\'../statisticalPlotter/AsynchronousProccess.php\', {\'townId\' : $(\'#townId option:selected\').val()});
		ajaxPost.done(function(data) {
			$(\'#centerId\').empty().append(data);
			var ajaxPost_1 = $.post(\'../statisticalPlotter/AsynchronousProccess.php\', {\'centerIdForHygieneUnitSelection\' : $(\'#centerId option:selected\').val()});
			ajaxPost_1.done(function(data) {
				$(\'#hygieneUnitId\').empty().append(data);
				$(\'#input_vehicle_id\').load(\'./ea-sh-ajax.php\', {\'hygieneUnitIdForVehiclesList\' : $(\'#hygieneUnitId option:selected\').val()});
			});
		});
		});';
		$htmlOut .= '$(\'#centerId\').change(function() {
			var ajaxPost_1 = $.post(\'../statisticalPlotter/AsynchronousProccess.php\', {\'centerIdForHygieneUnitSelection\' : $(\'#centerId option:selected\').val()});
			ajaxPost_1.done(function(data) {
				$(\'#hygieneUnitId\').empty().append(data);
				$(\'#input_vehicle_id\').load(\'./ea-sh-ajax.php\', {\'hygieneUnitIdForVehiclesList\' : $(\'#hygieneUnitId option:selected\').val()});
			});
		});';
		$htmlOut .= '$(\'#hygieneUnitId\').change(function() {
				$(\'#input_vehicle_id\').load(\'./ea-sh-ajax.php\', {\'hygieneUnitIdForVehiclesList\' : $(\'#hygieneUnitId option:selected\').val()});
		});';
		$htmlOut .= '</script>';
		$htmlOut .= '<label for="input_vehicle_id" class="required">عنوان وسیله نقلیه:</label>';
		$htmlOut .= '<select name="input_vehicle_id" id="input_vehicle_id" class="validate first_opt_not_allowed"></select><br />';
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
		$htmlOut .= '<input type="submit" name="submitAddHygieneUnitVehicleCharge" value="ثبت هزینه" /></fieldset></form>';
		
		$htmlOut .= '<fieldset style="width: 580px;"><legend>مشاهده ی هزینه های وسایل نقلیه خانه های بهداشت</legend>';
		$htmlOut .= '<br /><label for="townId_1" class="required">نام شهرستان:</label>';
		$htmlOut .= '<select name="townId_1" id="townId_1" class="validate">';
		$res = Database::execute_query("SELECT * FROM `towns` ORDER BY `name` ASC;");
		if (Database::num_of_rows($res) > 0) {
			while ($row = Database::get_assoc_array($res))
				$htmlOut .= '<option value="' . $row['id'] . '">' . $row['name'] . '</option>';
		} else
			$htmlOut .= '<option>' . NO_TOWN_EXISTS . '</option>';
		$htmlOut .= '</select>&nbsp; --> &nbsp;';
		$htmlOut .= '<label for="centerId_1" class="required">نام مرکز:</label>';
		$htmlOut .= '<select name="centerId_1" id="centerId_1" class="validate"></select><br /><br />&nbsp; --> &nbsp;';
		$htmlOut .= '<label for="hygieneUnitId_1" class="required">نام خانه بهداشت:</label>';
		$htmlOut .= '<select name="hygieneUnitId_1" id="hygieneUnitId_1" class="validate first_opt_not_allowed"></select>';
		$htmlOut .= '<br /><br />&nbsp; --> &nbsp;<label for="input_vehicle_id_1" class="required">عنوان وسیله نقلیه:</label>';
		$htmlOut .= '<select name="input_vehicle_id_1" id="input_vehicle_id_1" class="validate first_opt_not_allowed"></select><br />';
		$htmlOut .= '<script type="text/javascript">';
		$htmlOut .= '$(document).ajaxStart(function() {$(\'.loading\').show();$(\'#mngDiv\').css(\'opacity\', \'0.3\');});' . '$(document).ajaxSuccess(function() {$(\'.loading\').hide();$(\'#mngDiv\').css(\'opacity\', \'1\');});';
		$htmlOut .= 'var ajaxPost = $.post(\'../statisticalPlotter/AsynchronousProccess.php\', {\'townId\' : $(\'#townId_1 option:selected\').val()});
		ajaxPost.done(function(data) {
			$(\'#centerId_1\').empty().append(data);
			var ajaxPost_1 = $.post(\'../statisticalPlotter/AsynchronousProccess.php\', {\'centerIdForHygieneUnitSelection\' : $(\'#centerId_1 option:selected\').val()});
			ajaxPost_1.done(function(data) {
				$(\'#hygieneUnitId_1\').empty().append(data);
				var ajaxPost_2 = $.post(\'./ea-sh-ajax.php\', {\'hygieneUnitIdForVehiclesList\' : $(\'#hygieneUnitId_1 option:selected\').val()});
				ajaxPost_2.done(function(data) {
					$(\'#input_vehicle_id_1\').empty().append(data);
					$(\'#list\').load(\'./ea-sh-ajax.php\', {\'vehicleIdForHGVehicleCharge\' : $(\'#input_vehicle_id_1 option:selected\').val()});
				});
			});
		});';
		$htmlOut .= '$(\'#townId_1\').change(function() {
			var ajaxPost = $.post(\'../statisticalPlotter/AsynchronousProccess.php\', {\'townId\' : $(\'#townId_1 option:selected\').val()});
			ajaxPost.done(function(data) {
			$(\'#centerId_1\').empty().append(data);
			var ajaxPost_1 = $.post(\'../statisticalPlotter/AsynchronousProccess.php\', {\'centerIdForHygieneUnitSelection\' : $(\'#centerId_1 option:selected\').val()});
			ajaxPost_1.done(function(data) {
				$(\'#hygieneUnitId_1\').empty().append(data);
				var ajaxPost_2 = $.post(\'./ea-sh-ajax.php\', {\'hygieneUnitIdForVehiclesList\' : $(\'#hygieneUnitId_1 option:selected\').val()});
				ajaxPost_2.done(function(data) {
					$(\'#input_vehicle_id_1\').empty().append(data);
					$(\'#list\').load(\'./ea-sh-ajax.php\', {\'vehicleIdForHGVehicleCharge\' : $(\'#input_vehicle_id_1 option:selected\').val()});
				});
			});
		});
		});';
		$htmlOut .= '$(\'#centerId_1\').change(function() {
			var ajaxPost_1 = $.post(\'../statisticalPlotter/AsynchronousProccess.php\', {\'centerIdForHygieneUnitSelection\' : $(\'#centerId_1 option:selected\').val()});
			ajaxPost_1.done(function(data) {
				$(\'#hygieneUnitId_1\').empty().append(data);
				var ajaxPost_2 = $.post(\'./ea-sh-ajax.php\', {\'hygieneUnitIdForVehiclesList\' : $(\'#hygieneUnitId_1 option:selected\').val()});
				ajaxPost_2.done(function(data) {
					$(\'#input_vehicle_id_1\').empty().append(data);
					$(\'#list\').load(\'./ea-sh-ajax.php\', {\'vehicleIdForHGVehicleCharge\' : $(\'#input_vehicle_id_1 option:selected\').val()});
				});
			});
		});';
		$htmlOut .= '$(\'#hygieneUnitId_1\').change(function() {
				var ajaxPost_2 = $.post(\'./ea-sh-ajax.php\', {\'hygieneUnitIdForVehiclesList\' : $(\'#hygieneUnitId_1 option:selected\').val()});
				ajaxPost_2.done(function(data) {
					$(\'#input_vehicle_id_1\').empty().append(data);
					$(\'#list\').load(\'./ea-sh-ajax.php\', {\'vehicleIdForHGVehicleCharge\' : $(\'#input_vehicle_id_1 option:selected\').val()});
				});
		});';
		$htmlOut .= '$(\'#input_vehicle_id_1\').change(function() {$(\'#list\').load(\'./ea-sh-ajax.php\', {\'vehicleIdForHGVehicleCharge\' : $(\'#input_vehicle_id_1 option:selected\').val()});});';
		$htmlOut .= '</script>';
		$htmlOut .= '<div id="list"></div><br /></fieldset>';
		
		$htmlOut .= '</div>';

		return $htmlOut;
	}

	private static function manageCentersGeneralChargesForm() {
		$htmlOut = '';
		$htmlOut .= '<div class="loading"><img src="../statisticalPlotter/loading.gif" />&nbsp;&nbsp;در حال بارگذاری؛ لطفا صبر کنید...</div><br />';
		$htmlOut .= '<p style="text-align: right; font-weight: bold; font-size: 15px; margin-right: 20px"><a href="#" style="color: green;" id="slideLink" ><img src="../illustrator/images/icons/plus.png" />&nbsp;ثبت</a></p>';
		$htmlOut .= '<div id="mngDiv"><form action="' . substr(strrchr($_SERVER['PHP_SELF'], '/'), 1) . '" method="post" id="slideForm" class="narin">';
		$htmlOut .= '<input type="hidden" name="narin:token" value="' . Narin::create_token() . '" /><fieldset><legend>ثبت هزینه برای مرکز</legend>';
		$htmlOut .= '<br /><label for="townNameForCenter" class="required">نام شهرستان:</label>';
		$htmlOut .= '<select name="townNameForCenter" id="townNameForCenter" class="validate first_opt_not_allowed">';
		$res = Database::execute_query("SELECT * FROM `towns` ORDER BY `name` ASC;");
		if (Database::num_of_rows($res) > 0) {
			$htmlOut .= '<option>انتخاب کنید...</option>';
			while ($row = Database::get_assoc_array($res))
				$htmlOut .= '<option value="' . $row['id'] . '">' . $row['name'] . '</option>';
		} else
			$htmlOut .= '<option>' . NO_TOWN_EXISTS . '</option>';
		$htmlOut .= '</select><br />';
		$htmlOut .= '<label for="centerId" class="required">نام مرکز:</label>';
		$htmlOut .= '<select name="centerId" id="centerId" class="validate first_opt_not_allowed"></select><br />';
		$htmlOut .= '<script type="text/javascript">';
		$htmlOut .= '$(document).ajaxStart(function() {$(\'.loading\').show();$(\'#mngDiv\').css(\'opacity\', \'0.3\');});' . '$(document).ajaxSuccess(function() {$(\'.loading\').hide();$(\'#mngDiv\').css(\'opacity\', \'1\');});';
		$htmlOut .= '$(\'#centerId\').load(\'../statisticalPlotter/AsynchronousProccess.php\', {\'townId\' : $(\'#townNameForCenter option:selected\').val()});';
		$htmlOut .= '$(\'#townNameForCenter\').change(function() {$(\'#centerId\').load(\'../statisticalPlotter/AsynchronousProccess.php\', {\'townId\' : $(\'#townNameForCenter option:selected\').val()});});';
		$htmlOut .= '</script>';
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
		$htmlOut .= '</textarea><br /><input type="submit" name="submit_eash_AddCenterGeneralCharge" value="ثبت هزینه" /></fieldset></form>';
		
		$htmlOut .= '<fieldset style="width: 580px;"><legend>مشاهده ی هزینه های عمومی مراکز</legend>';
		$htmlOut .= '<br /><label for="townId" class="required">نام شهرستان:</label>';
		$htmlOut .= '<select name="townId" id="townId" class="validate">';
		$res = Database::execute_query("SELECT * FROM `towns` ORDER BY `name` ASC;");
		if (Database::num_of_rows($res) > 0) {
			while ($row = Database::get_assoc_array($res))
				$htmlOut .= '<option value="' . $row['id'] . '">' . $row['name'] . '</option>';
		} else
			$htmlOut .= '<option>' . NO_TOWN_EXISTS . '</option>';
		$htmlOut .= '</select>&nbsp; --> &nbsp;';
		$htmlOut .= '<label for="centerId_1" class="required">نام مرکز:</label>';
		$htmlOut .= '<select name="centerId_1" id="centerId_1" class="validate"></select>';
		$htmlOut .= '<script type="text/javascript">';
		$htmlOut .= 'var ajaxPost = $.post(\'../statisticalPlotter/AsynchronousProccess.php\', {\'townId\' : $(\'#townId option:selected\').val()});';
		$htmlOut .= 'ajaxPost.done(function(data) {$(\'#centerId_1\').empty().append(data);$(\'#list\').load(\'./ea-sh-ajax.php\', {\'centerIdForGeneralCharges\' : $(\'#centerId_1 option:selected\').val()});});';
		$htmlOut .= '$(\'#townId\').change(function() {var ajaxPost = $.post(\'../statisticalPlotter/AsynchronousProccess.php\', {\'townId\' : $(\'#townId option:selected\').val()});' . 'ajaxPost.done(function(data) {$(\'#centerId_1\').empty().append(data);$(\'#list\').load(\'./ea-sh-ajax.php\', {\'centerIdForGeneralCharges\' : $(\'#centerId_1 option:selected\').val()});});});';
		$htmlOut .= '$(\'#centerId_1\').change(function() {$(\'#list\').load(\'./ea-sh-ajax.php\', {\'centerIdForGeneralCharges\' : $(\'#centerId_1 option:selected\').val()});});';
		$htmlOut .= '</script>';
		$htmlOut .= '<div id="list"></div><br /></fieldset>';
		
		$htmlOut .= '</div>';

		return $htmlOut;
	}

	private static function manageHygieneUnitsGeneralChargesForm() {
		$htmlOut = '';
		$htmlOut .= '<div class="loading"><img src="../statisticalPlotter/loading.gif" />&nbsp;&nbsp;در حال بارگذاری؛ لطفا صبر کنید...</div><br />';
		$htmlOut .= '<p style="text-align: right; font-weight: bold; font-size: 15px; margin-right: 20px"><a href="#" style="color: green;" id="slideLink" ><img src="../illustrator/images/icons/plus.png" />&nbsp;ثبت</a></p>';
		$htmlOut .= '<div id="mngDiv"><form action="' . substr(strrchr($_SERVER['PHP_SELF'], '/'), 1) . '" method="post" id="slideForm" class="narin">';
		$htmlOut .= '<input type="hidden" name="narin:token" value="' . Narin::create_token() . '" /><fieldset><legend>ثبت هزینه برای خانه بهداشت</legend>';
		$htmlOut .= '<label for="townId" class="required">نام شهرستان:</label>';
		$htmlOut .= '<select name="townId" id="townId" class="validate first_opt_not_allowed">';
		$res = Database::execute_query("SELECT * FROM `towns` ORDER BY `name` ASC;");
		if (Database::num_of_rows($res) > 0) {
			$htmlOut .= '<option>انتخاب کنید...</option>';
			while ($row = Database::get_assoc_array($res))
				$htmlOut .= '<option value="' . $row['id'] . '">' . $row['name'] . '</option>';
		} else
			$htmlOut .= '<option>' . NO_TOWN_EXISTS . '</option>';
		$htmlOut .= '</select><br />';
		$htmlOut .= '<label for="centerId" class="required">نام مرکز:</label>';
		$htmlOut .= '<select name="centerId" id="centerId" class="validate first_opt_not_allowed"></select><br />';
		$htmlOut .= '<label for="hygieneUnitId" class="required">نام خانه بهداشت:</label>';
		$htmlOut .= '<select name="hygieneUnitId" id="hygieneUnitId" class="validate first_opt_not_allowed"></select><br />';
		$htmlOut .= '<script type="text/javascript">';
		$htmlOut .= '$(document).ajaxStart(function() {$(\'.loading\').show();$(\'#mngDiv\').css(\'opacity\', \'0.3\');});' . '$(document).ajaxSuccess(function() {$(\'.loading\').hide();$(\'#mngDiv\').css(\'opacity\', \'1\');});';
		$htmlOut .= 'var ajaxPost = $.post(\'../statisticalPlotter/AsynchronousProccess.php\', {\'townId\' : $(\'#townId option:selected\').val()});ajaxPost.done(function(data) {$(\'#centerId\').empty().append(data);$(\'#hygieneUnitId\').load(\'../statisticalPlotter/AsynchronousProccess.php\', {\'centerIdForHygieneUnitSelection\' : $(\'#centerId option:selected\').val()});});';
		$htmlOut .= '$(\'#townId\').change(function() {var ajaxPost1 = $.post(\'../statisticalPlotter/AsynchronousProccess.php\', {\'townId\' : $(\'#townId option:selected\').val()});ajaxPost1.done(function(data) {$(\'#centerId\').empty().append(data);$(\'#hygieneUnitId\').load(\'../statisticalPlotter/AsynchronousProccess.php\', {\'centerIdForHygieneUnitSelection\' : $(\'#centerId option:selected\').val()});});});';
		$htmlOut .= '$(\'#centerId\').change(function() {$(\'#hygieneUnitId\').load(\'../statisticalPlotter/AsynchronousProccess.php\', {\'centerIdForHygieneUnitSelection\' : $(\'#centerId option:selected\').val()});});';
		$htmlOut .= '</script>';
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
		$htmlOut .= '</textarea><br /><input type="submit" name="submit_eash_AddHygieneUnitGeneralCharge" value="ثبت هزینه" /></fieldset></form>';
		
		$htmlOut .= '<fieldset style="width: 580px;"><legend>مشاهده ی هزینه های عمومی خانه های بهداشت</legend>';
		$htmlOut .= '<br /><label for="townId_1" class="required">نام شهرستان:</label>';
		$htmlOut .= '<select name="townId_1" id="townId_1" class="validate">';
		$res = Database::execute_query("SELECT * FROM `towns` ORDER BY `name` ASC;");
		if (Database::num_of_rows($res) > 0) {
			while ($row = Database::get_assoc_array($res))
				$htmlOut .= '<option value="' . $row['id'] . '">' . $row['name'] . '</option>';
		} else
			$htmlOut .= '<option>' . NO_TOWN_EXISTS . '</option>';
		$htmlOut .= '</select>&nbsp; --> &nbsp;';
		$htmlOut .= '<label for="centerId_1" class="required">نام مرکز:</label>';
		$htmlOut .= '<select name="centerId_1" id="centerId_1" class="validate"></select><br /><br />';
		$htmlOut .= '<label for="hygieneUnitId_1" class="required">نام خانه بهداشت:</label>';
		$htmlOut .= '<select name="hygieneUnitId_1" id="hygieneUnitId_1" class="validate"></select><br />';
		$htmlOut .= '<script type="text/javascript">';
		$htmlOut .= 'var ajaxPost = $.post(\'../statisticalPlotter/AsynchronousProccess.php\', {\'townId\' : $(\'#townId_1 option:selected\').val()});ajaxPost.done(function(data) {$(\'#centerId_1\').empty().append(data);var ajaxPost1 = $.post(\'../statisticalPlotter/AsynchronousProccess.php\', {\'centerIdForHygieneUnitSelection\' : $(\'#centerId_1 option:selected\').val()});ajaxPost1.done(function(data) {$(\'#hygieneUnitId_1\').empty().append(data);$(\'#list\').load(\'./ea-sh-ajax.php\', {\'hygieneUnitIdForGeneralCharges\' : $(\'#hygieneUnitId_1 option:selected\').val()});});});';
		$htmlOut .= '$(\'#townId_1\').change(function() {var ajaxPost = $.post(\'../statisticalPlotter/AsynchronousProccess.php\', {\'townId\' : $(\'#townId_1 option:selected\').val()});ajaxPost.done(function(data) {$(\'#centerId_1\').empty().append(data);var ajaxPost1 = $.post(\'../statisticalPlotter/AsynchronousProccess.php\', {\'centerIdForHygieneUnitSelection\' : $(\'#centerId_1 option:selected\').val()});ajaxPost1.done(function(data) {$(\'#hygieneUnitId_1\').empty().append(data);$(\'#list\').load(\'./ea-sh-ajax.php\', {\'hygieneUnitIdForGeneralCharges\' : $(\'#hygieneUnitId_1 option:selected\').val()});});});});';
		$htmlOut .= '$(\'#centerId_1\').change(function() {var ajaxPost = $.post(\'../statisticalPlotter/AsynchronousProccess.php\', {\'centerIdForHygieneUnitSelection\' : $(\'#centerId_1 option:selected\').val()});ajaxPost.done(function(data) {$(\'#hygieneUnitId_1\').empty().append(data);$(\'#list\').load(\'./ea-sh-ajax.php\', {\'hygieneUnitIdForGeneralCharges\' : $(\'#hygieneUnitId_1 option:selected\').val()});});});';
		$htmlOut .= '$(\'#hygieneUnitId_1\').change(function() {$(\'#list\').load(\'./ea-sh-ajax.php\', {\'hygieneUnitIdForGeneralCharges\' : $(\'#hygieneUnitId_1 option:selected\').val()});});';
		$htmlOut .= '</script>';
		$htmlOut .= '<div id="list"></div><br /></fieldset>';
		
		$htmlOut .= '</div>';

		return $htmlOut;
	}

	private static function manageUnitsPersonnelsForm() {
		$htmlOut = '';
		$htmlOut .= '<p style="margin-right: 20px;">در این قسمت می توانید پرسنل واحدها را مدیریت نمایید.</p>';
		$htmlOut .= '<p style="text-align: right; font-weight: bold; font-size: 15px; margin-right: 20px"><a href="#" style="color: green;" id="slideLink" ><img src="../illustrator/images/icons/plus.png" />&nbsp;افزودن پرسنل به واحد</a></p>';
		$htmlOut .= '<div class="loading"><img src="../statisticalPlotter/loading.gif" />&nbsp;&nbsp;در حال بارگذاری؛ لطفا صبر کنید...</div><br />';
		$htmlOut .= '<div id="mngDiv"><form action="' . substr(strrchr($_SERVER['PHP_SELF'], '/'), 1) . '" method="post" id="slideForm" class="narin">';
		$htmlOut .= '<input type="hidden" name="narin:token" value="' . Narin::create_token() . '" /><fieldset><legend>افزودن پرسنل</legend>';
		$htmlOut .= '<br /><label for="townNameForUnit" class="required">نام شهرستان:</label>';
		$htmlOut .= '<select name="townNameForUnit" id="townNameForUnit" class="validate first_opt_not_allowed">';
		$res = Database::execute_query("SELECT * FROM `towns` ORDER BY `name` ASC;");
		if (Database::num_of_rows($res) > 0) {
			$htmlOut .= '<option>انتخاب کنید...</option>';
			while ($row = Database::get_assoc_array($res))
				$htmlOut .= '<option value="' . $row['id'] . '">' . $row['name'] . '</option>';
		} else
			$htmlOut .= '<option>' . NO_TOWN_EXISTS . '</option>';
		$htmlOut .= '</select><br />';
		$htmlOut .= '<label for="centerId" class="required">نام مرکز:</label>';
		$htmlOut .= '<select name="centerId" id="centerId" class="validate first_opt_not_allowed"></select><br />';
		$htmlOut .= '<label for="unitId" class="required">نام واحد:</label>';
		$htmlOut .= '<select name="unitId" id="unitId" class="validate first_opt_not_allowed"></select><br />';
		$htmlOut .= '<script type="text/javascript">';
		$htmlOut .= '$(document).ajaxStart(function() {$(\'.loading\').show();$(\'#mngDiv\').css(\'opacity\', \'0.3\');});' . '$(document).ajaxSuccess(function() {$(\'.loading\').hide();$(\'#mngDiv\').css(\'opacity\', \'1\');});';
		$htmlOut .= 'var ajaxPost = $.post(\'../statisticalPlotter/AsynchronousProccess.php\', {\'townId\' : $(\'#townNameForUnit option:selected\').val()});ajaxPost.done(function(data) {$(\'#centerId\').empty().append(data);$(\'#unitId\').load(\'../statisticalPlotter/AsynchronousProccess.php\', {\'centerId\' : $(\'#centerId option:selected\').val()});});';
		$htmlOut .= '$(\'#townNameForUnit\').change(function() {var ajaxPost1 = $.post(\'../statisticalPlotter/AsynchronousProccess.php\', {\'townId\' : $(\'#townNameForUnit option:selected\').val()});ajaxPost1.done(function(data) {$(\'#centerId\').empty().append(data);$(\'#unitId\').load(\'../statisticalPlotter/AsynchronousProccess.php\', {\'centerId\' : $(\'#centerId option:selected\').val()});});});';
		$htmlOut .= '$(\'#centerId\').change(function() {$(\'#unitId\').load(\'../statisticalPlotter/AsynchronousProccess.php\', {\'centerId\' : $(\'#centerId option:selected\').val()});});';
		$htmlOut .= '</script>';
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
		$htmlOut .= '<br /><input type="submit" value="ثبت پرسنل" name="submit_eash_AddUnitPersonnel" /></fieldset></form>';

		$htmlOut .= '<br /><fieldset style="width: 580px;"><legend>مشاهده ی پرسنل واحدها</legend>';
		$htmlOut .= '<br /><label for="townId" class="required">نام شهرستان:</label>';
		$htmlOut .= '<select name="townId" id="townId" class="validate">';
		$res = Database::execute_query("SELECT * FROM `towns` ORDER BY `name` ASC;");
		if (Database::num_of_rows($res) > 0) {
			while ($row = Database::get_assoc_array($res))
				$htmlOut .= '<option value="' . $row['id'] . '">' . $row['name'] . '</option>';
		} else
			$htmlOut .= '<option>' . NO_TOWN_EXISTS . '</option>';
		$htmlOut .= '</select>&nbsp-->&nbsp';
		$htmlOut .= '<label for="centerId_1" class="required">نام مرکز:</label>';
		$htmlOut .= '<select name="centerId_1" id="centerId_1" class="validate"></select><br />&nbsp-->&nbsp';
		$htmlOut .= '<label for="unitId_1" class="required">نام واحد:</label>';
		$htmlOut .= '<select name="unitId_1" id="unitId_1" class="validate"></select><br />';
		$htmlOut .= '<script type="text/javascript">';
		$htmlOut .= 'var ajaxPost = $.post(\'../statisticalPlotter/AsynchronousProccess.php\', {\'townId\' : $(\'#townId option:selected\').val()});ajaxPost.done(function(data) {$(\'#centerId_1\').empty().append(data);var ajaxPost1 = $.post(\'../statisticalPlotter/AsynchronousProccess.php\', {\'centerId\' : $(\'#centerId_1 option:selected\').val()});ajaxPost1.done(function(data) {$(\'#unitId_1\').empty().append(data);$(\'#list\').load(\'./ea-sh-ajax.php\', {\'unitIdForPersonnel\' : $(\'#unitId_1 option:selected\').val()});});});';
		$htmlOut .= '$(\'#townId\').change(function() {var ajaxPost = $.post(\'../statisticalPlotter/AsynchronousProccess.php\', {\'townId\' : $(\'#townId option:selected\').val()});ajaxPost.done(function(data) {$(\'#centerId_1\').empty().append(data);var ajaxPost1 = $.post(\'../statisticalPlotter/AsynchronousProccess.php\', {\'centerId\' : $(\'#centerId_1 option:selected\').val()});ajaxPost1.done(function(data) {$(\'#unitId_1\').empty().append(data);$(\'#list\').load(\'./ea-sh-ajax.php\', {\'unitIdForPersonnel\' : $(\'#unitId_1 option:selected\').val()});});});});';
		$htmlOut .= '$(\'#centerId_1\').change(function() {var ajaxPost = $.post(\'../statisticalPlotter/AsynchronousProccess.php\', {\'centerId\' : $(\'#centerId_1 option:selected\').val()});ajaxPost.done(function(data) {$(\'#unitId_1\').empty().append(data);$(\'#list\').load(\'./ea-sh-ajax.php\', {\'unitIdForPersonnel\' : $(\'#unitId_1 option:selected\').val()});});});';
		$htmlOut .= '$(\'#unitId_1\').change(function() {$(\'#list\').load(\'./ea-sh-ajax.php\', {\'unitIdForPersonnel\' : $(\'#unitId_1 option:selected\').val()});});';
		$htmlOut .= '</script>';

		$htmlOut .= '<div id="list"></div><br /></fieldset>';
		$htmlOut .= '</div>';

		return $htmlOut;
	}

	private static function manageUnitPersonnelFinancialInfoForm() {
		$htmlOut = '';
		$htmlOut .= '<p style="margin-right: 20px;"><span style="font-weight: bold;">گام اول</span>: برای مشاهده و تغییر اطلاعات مالی پرسنل موردنظر، ابتدا وی را بیابید.</p>';
		$htmlOut .= '<div class="loading"><img src="../statisticalPlotter/loading.gif" />&nbsp;&nbsp;در حال بارگذاری؛ لطفا صبر کنید...</div><br />';
		$htmlOut .= '<div id="mngDiv"><form action="' . substr(strrchr($_SERVER['PHP_SELF'], '/'), 1) . '?cmd=unitPersonnelFinancialInfo" method="post" class="narin">';
		$htmlOut .= '<input type="hidden" name="narin:token" value="' . Narin::create_token() . '" /><fieldset><legend>یافتن پرسنل</legend>';
		$htmlOut .= '<br /><label for="townNameForUnit" class="required">نام شهرستان:</label>';
		$htmlOut .= '<select name="townNameForUnit" id="townNameForUnit" class="validate first_opt_not_allowed">';
		$res = Database::execute_query("SELECT * FROM `towns` ORDER BY `name` ASC;");
		if (Database::num_of_rows($res) > 0) {
			$htmlOut .= '<option>انتخاب کنید...</option>';
			while ($row = Database::get_assoc_array($res))
				$htmlOut .= '<option value="' . $row['id'] . '">' . $row['name'] . '</option>';
		} else
			$htmlOut .= '<option>' . NO_TOWN_EXISTS . '</option>';
		$htmlOut .= '</select><br />';
		$htmlOut .= '<label for="centerId" class="required">نام مرکز:</label>';
		$htmlOut .= '<select name="centerId" id="centerId" class="validate first_opt_not_allowed"></select><br />';
		$htmlOut .= '<label for="unitId" class="required">نام واحد:</label>';
		$htmlOut .= '<select name="unitId" id="unitId" class="validate first_opt_not_allowed"></select><br />';
		$htmlOut .= '<script type="text/javascript">';
		$htmlOut .= '$(document).ajaxStart(function() {$(\'.loading\').show();$(\'#mngDiv\').css(\'opacity\', \'0.3\');});' . '$(document).ajaxSuccess(function() {$(\'.loading\').hide();$(\'#mngDiv\').css(\'opacity\', \'1\');});';
		$htmlOut .= 'var ajaxPost = $.post(\'../statisticalPlotter/AsynchronousProccess.php\', {\'townId\' : $(\'#townNameForUnit option:selected\').val()});ajaxPost.done(function(data) {$(\'#centerId\').empty().append(data);$(\'#unitId\').load(\'../statisticalPlotter/AsynchronousProccess.php\', {\'centerId\' : $(\'#centerId option:selected\').val()});});';
		$htmlOut .= '$(\'#townNameForUnit\').change(function() {var ajaxPost1 = $.post(\'../statisticalPlotter/AsynchronousProccess.php\', {\'townId\' : $(\'#townNameForUnit option:selected\').val()});ajaxPost1.done(function(data) {$(\'#centerId\').empty().append(data);$(\'#unitId\').load(\'../statisticalPlotter/AsynchronousProccess.php\', {\'centerId\' : $(\'#centerId option:selected\').val()});});});';
		$htmlOut .= '$(\'#centerId\').change(function() {$(\'#unitId\').load(\'../statisticalPlotter/AsynchronousProccess.php\', {\'centerId\' : $(\'#centerId option:selected\').val()});});';
		$htmlOut .= '</script>';
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
		$htmlOut .= '&nbsp;::&nbsp;<input type="text" name="searchValue" id="searchValue" maxlength="255"';
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
		$htmlOut .= '<br /><input type="submit" value="بیاب" name="submitSearchPersonnel" onclick="$(\'#list\').load(\'./ea-sh-ajax.php\', {\'unitIdForPersonnelFinancialInfo\' : $(\'#unitId option:selected\').val(), \'searchBy\' : $(\'#searchBy option:selected\').val(), \'searchValue\' : $(\'#searchValue\').val()});return false;" /></fieldset></form>';
		$htmlOut .= '<div id="list"></div><br />';

		$htmlOut .= '</div>';

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

	private static function manageHygieneUnitsPersonnelForm() {
		$htmlOut = '';
		$htmlOut .= '<p>در این قسمت می توانید بهورزان خانه های بهداشت را مدیریت نمایید.</p>';
		$htmlOut .= '<p style="text-align: right; font-weight: bold; font-size: 15px; margin-right: 20px"><a href="#" style="color: green;" id="slideLink" ><img src="../illustrator/images/icons/plus.png" />&nbsp;افزودن بهورز</a></p>';
		$htmlOut .= '<div class="loading"><img src="../statisticalPlotter/loading.gif" />&nbsp;&nbsp;در حال بارگذاری؛ لطفا صبر کنید...</div><br />';
		$htmlOut .= '<div id="mngDiv"><form action="' . substr(strrchr($_SERVER['PHP_SELF'], '/'), 1) . '" method="post" id="slideForm" class="narin">';
		$htmlOut .= '<input type="hidden" name="narin:token" value="' . Narin::create_token() . '" /><fieldset><legend>افزودن بهورز</legend>';
		$htmlOut .= '<label for="townId" class="required">نام شهرستان:</label>';
		$htmlOut .= '<select name="townId" id="townId" class="validate first_opt_not_allowed">';
		$res = Database::execute_query("SELECT * FROM `towns` ORDER BY `name` ASC;");
		if (Database::num_of_rows($res) > 0) {
			$htmlOut .= '<option>انتخاب کنید...</option>';
			while ($row = Database::get_assoc_array($res))
				$htmlOut .= '<option value="' . $row['id'] . '">' . $row['name'] . '</option>';
		} else
			$htmlOut .= '<option>' . NO_TOWN_EXISTS . '</option>';
		$htmlOut .= '</select><br />';
		$htmlOut .= '<label for="centerId" class="required">نام مرکز:</label>';
		$htmlOut .= '<select name="centerId" id="centerId" class="validate first_opt_not_allowed"></select><br />';
		$htmlOut .= '<label for="hygieneUnitId" class="required">نام خانه بهداشت:</label>';
		$htmlOut .= '<select name="hygieneUnitId" id="hygieneUnitId" class="validate first_opt_not_allowed"></select><br />';
		$htmlOut .= '<script type="text/javascript">';
		$htmlOut .= '$(document).ajaxStart(function() {$(\'.loading\').show();$(\'#mngDiv\').css(\'opacity\', \'0.3\');});' . '$(document).ajaxSuccess(function() {$(\'.loading\').hide();$(\'#mngDiv\').css(\'opacity\', \'1\');});';
		$htmlOut .= 'var ajaxPost = $.post(\'../statisticalPlotter/AsynchronousProccess.php\', {\'townId\' : $(\'#townId option:selected\').val()});ajaxPost.done(function(data) {$(\'#centerId\').empty().append(data);$(\'#hygieneUnitId\').load(\'../statisticalPlotter/AsynchronousProccess.php\', {\'centerIdForHygieneUnitSelection\' : $(\'#centerId option:selected\').val()});});';
		$htmlOut .= '$(\'#townId\').change(function() {var ajaxPost1 = $.post(\'../statisticalPlotter/AsynchronousProccess.php\', {\'townId\' : $(\'#townId option:selected\').val()});ajaxPost1.done(function(data) {$(\'#centerId\').empty().append(data);$(\'#hygieneUnitId\').load(\'../statisticalPlotter/AsynchronousProccess.php\', {\'centerIdForHygieneUnitSelection\' : $(\'#centerId option:selected\').val()});});});';
		$htmlOut .= '$(\'#centerId\').change(function() {$(\'#hygieneUnitId\').load(\'../statisticalPlotter/AsynchronousProccess.php\', {\'centerIdForHygieneUnitSelection\' : $(\'#centerId option:selected\').val()});});';
		$htmlOut .= '</script>';
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
		$htmlOut .= '<option value="بهورز">بهورز</option>';
		$htmlOut .= '</select><br />';
		$htmlOut .= '<br /><input type="submit" value="ثبت بهورز" name="submit_eash_AddHygieneUnitPersonnel" /></fieldset></form>';

		$htmlOut .= '<br /><fieldset style="width: 580px;"><legend>مشاهده ی بهورزان خانه های بهداشت</legend>';
		$htmlOut .= '<br /><label for="townId_1" class="required">نام شهرستان:</label>';
		$htmlOut .= '<select name="townId_1" id="townId_1" class="validate">';
		$res = Database::execute_query("SELECT * FROM `towns` ORDER BY `name` ASC;");
		if (Database::num_of_rows($res) > 0) {
			while ($row = Database::get_assoc_array($res))
				$htmlOut .= '<option value="' . $row['id'] . '">' . $row['name'] . '</option>';
		} else
			$htmlOut .= '<option>' . NO_TOWN_EXISTS . '</option>';
		$htmlOut .= '</select>&nbsp; --> &nbsp;';
		$htmlOut .= '<label for="centerId_1" class="required">نام مرکز:</label>';
		$htmlOut .= '<select name="centerId_1" id="centerId_1" class="validate"></select><br /><br />';
		$htmlOut .= '<label for="hygieneUnitId_1" class="required">نام خانه بهداشت:</label>';
		$htmlOut .= '<select name="hygieneUnitId_1" id="hygieneUnitId_1" class="validate"></select><br />';
		$htmlOut .= '<script type="text/javascript">';
		$htmlOut .= 'var ajaxPost = $.post(\'../statisticalPlotter/AsynchronousProccess.php\', {\'townId\' : $(\'#townId_1 option:selected\').val()});ajaxPost.done(function(data) {$(\'#centerId_1\').empty().append(data);var ajaxPost1 = $.post(\'../statisticalPlotter/AsynchronousProccess.php\', {\'centerIdForHygieneUnitSelection\' : $(\'#centerId_1 option:selected\').val()});ajaxPost1.done(function(data) {$(\'#hygieneUnitId_1\').empty().append(data);$(\'#list\').load(\'./ea-sh-ajax.php\', {\'hygieneUnitIdForPersonnel\' : $(\'#hygieneUnitId_1 option:selected\').val()});});});';
		$htmlOut .= '$(\'#townId_1\').change(function() {var ajaxPost = $.post(\'../statisticalPlotter/AsynchronousProccess.php\', {\'townId\' : $(\'#townId_1 option:selected\').val()});ajaxPost.done(function(data) {$(\'#centerId_1\').empty().append(data);var ajaxPost1 = $.post(\'../statisticalPlotter/AsynchronousProccess.php\', {\'centerIdForHygieneUnitSelection\' : $(\'#centerId_1 option:selected\').val()});ajaxPost1.done(function(data) {$(\'#hygieneUnitId_1\').empty().append(data);$(\'#list\').load(\'./ea-sh-ajax.php\', {\'hygieneUnitIdForPersonnel\' : $(\'#hygieneUnitId_1 option:selected\').val()});});});});';
		$htmlOut .= '$(\'#centerId_1\').change(function() {var ajaxPost = $.post(\'../statisticalPlotter/AsynchronousProccess.php\', {\'centerIdForHygieneUnitSelection\' : $(\'#centerId_1 option:selected\').val()});ajaxPost.done(function(data) {$(\'#hygieneUnitId_1\').empty().append(data);$(\'#list\').load(\'./ea-sh-ajax.php\', {\'hygieneUnitIdForPersonnel\' : $(\'#hygieneUnitId_1 option:selected\').val()});});});';
		$htmlOut .= '$(\'#hygieneUnitId_1\').change(function() {$(\'#list\').load(\'./ea-sh-ajax.php\', {\'hygieneUnitIdForPersonnel\' : $(\'#hygieneUnitId_1 option:selected\').val()});});';
		$htmlOut .= '</script>';
		$htmlOut .= '<div id="list"></div><br /></fieldset>';
		$htmlOut .= '</div>';

		return $htmlOut;
	}

	private static function manageHygieneUnitPersonnelFinancialInfoForm() {
		$htmlOut = '';
		$htmlOut .= '<p><span style="font-weight: bold;">گام اول</span>: برای مشاهده و تغییر اطلاعات مالی پرسنل موردنظر، ابتدا وی را بیابید.</p>';
		$htmlOut .= '<div class="loading"><img src="../statisticalPlotter/loading.gif" />&nbsp;&nbsp;در حال بارگذاری؛ لطفا صبر کنید...</div><br />';
		$htmlOut .= '<div id="mngDiv"><form action="' . substr(strrchr($_SERVER['PHP_SELF'], '/'), 1) . '?cmd=hygieneUnitPersonnelFinancialInfo" method="post" class="narin">';
		$htmlOut .= '<input type="hidden" name="narin:token" value="' . Narin::create_token() . '" /><fieldset><legend>یافتن پرسنل</legend>';
		$htmlOut .= '<label for="townId" class="required">نام شهرستان:</label>';
		$htmlOut .= '<select name="townId" id="townId" class="validate first_opt_not_allowed">';
		$res = Database::execute_query("SELECT * FROM `towns` ORDER BY `name` ASC;");
		if (Database::num_of_rows($res) > 0) {
			$htmlOut .= '<option>انتخاب کنید...</option>';
			while ($row = Database::get_assoc_array($res))
				$htmlOut .= '<option value="' . $row['id'] . '">' . $row['name'] . '</option>';
		} else
			$htmlOut .= '<option>' . NO_TOWN_EXISTS . '</option>';
		$htmlOut .= '</select><br />';
		$htmlOut .= '<label for="centerId" class="required">نام مرکز:</label>';
		$htmlOut .= '<select name="centerId" id="centerId" class="validate first_opt_not_allowed"></select><br />';
		$htmlOut .= '<label for="hygieneUnitId" class="required">نام خانه بهداشت:</label>';
		$htmlOut .= '<select name="hygieneUnitId" id="hygieneUnitId" class="validate first_opt_not_allowed"></select><br />';
		$htmlOut .= '<script type="text/javascript">';
		$htmlOut .= '$(document).ajaxStart(function() {$(\'.loading\').show();$(\'#mngDiv\').css(\'opacity\', \'0.3\');});' . '$(document).ajaxSuccess(function() {$(\'.loading\').hide();$(\'#mngDiv\').css(\'opacity\', \'1\');});';
		$htmlOut .= 'var ajaxPost = $.post(\'../statisticalPlotter/AsynchronousProccess.php\', {\'townId\' : $(\'#townId option:selected\').val()});ajaxPost.done(function(data) {$(\'#centerId\').empty().append(data);$(\'#hygieneUnitId\').load(\'../statisticalPlotter/AsynchronousProccess.php\', {\'centerIdForHygieneUnitSelection\' : $(\'#centerId option:selected\').val()});});';
		$htmlOut .= '$(\'#townId\').change(function() {var ajaxPost1 = $.post(\'../statisticalPlotter/AsynchronousProccess.php\', {\'townId\' : $(\'#townId option:selected\').val()});ajaxPost1.done(function(data) {$(\'#centerId\').empty().append(data);$(\'#hygieneUnitId\').load(\'../statisticalPlotter/AsynchronousProccess.php\', {\'centerIdForHygieneUnitSelection\' : $(\'#centerId option:selected\').val()});});});';
		$htmlOut .= '$(\'#centerId\').change(function() {$(\'#hygieneUnitId\').load(\'../statisticalPlotter/AsynchronousProccess.php\', {\'centerIdForHygieneUnitSelection\' : $(\'#centerId option:selected\').val()});});';
		$htmlOut .= '</script>';
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
		$htmlOut .= '&nbsp;::&nbsp;<input type="text" name="searchValue" id="searchValue" maxlength="255"';
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
		$htmlOut .= '<br /><input type="submit" value="بیاب" name="submitSearchBehvarz" onclick="$(\'#list\').load(\'./ea-sh-ajax.php\', {\'hygieneUnitIdForPersonnelFinancialInfo\' : $(\'#hygieneUnitId option:selected\').val(), \'searchBy\' : $(\'#searchBy option:selected\').val(), \'searchValue\' : $(\'#searchValue\').val()});return false;" /></fieldset></form>';
		$htmlOut .= '<div id="list"></div><br />';

		$htmlOut .= '</div>';

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

	private static function manageUnitsServices() {
		$htmlOut = '';
		$htmlOut .= '<p>در این قسمت شما می توانید خدمات موردنظر خود برای واحدها را تعریف نمایید.</p>';
		$htmlOut .= '<p style="text-align: right; font-weight: bold; font-size: 15px; margin-right: 20px"><a href="#" style="color: green;" id="slideLink" ><img src="../illustrator/images/icons/plus.png" />&nbsp;افزودن خدمت</a></p>';
		$htmlOut .= '<div class="loading"><img src="../statisticalPlotter/loading.gif" />&nbsp;&nbsp;در حال بارگذاری؛ لطفا صبر کنید...</div><br />';
		$htmlOut .= '<div id="mngDiv"><form action="' . substr(strrchr($_SERVER['PHP_SELF'], '/'), 1) . '" method="post" id="slideForm" class="narin"><fieldset><legend>افزودن خدمت به واحد</legend>';
		$htmlOut .= '<br /><label for="townNameForUnit" class="required">نام شهرستان:</label>';
		$htmlOut .= '<select name="townNameForUnit" id="townNameForUnit" class="validate first_opt_not_allowed">';
		$res = Database::execute_query("SELECT * FROM `towns` ORDER BY `name` ASC;");
		if (Database::num_of_rows($res) > 0) {
			$htmlOut .= '<option>انتخاب کنید...</option>';
			while ($row = Database::get_assoc_array($res))
				$htmlOut .= '<option value="' . $row['id'] . '">' . $row['name'] . '</option>';
		} else
			$htmlOut .= '<option>' . NO_TOWN_EXISTS . '</option>';
		$htmlOut .= '</select><br />';
		$htmlOut .= '<label for="centerId" class="required">نام مرکز:</label>';
		$htmlOut .= '<select name="centerId" id="centerId" class="validate first_opt_not_allowed"></select><br />';
		$htmlOut .= '<label for="unitId" class="required">نام واحد:</label>';
		$htmlOut .= '<select name="unitId" id="unitId" class="validate first_opt_not_allowed"></select><br />';
		$htmlOut .= '<script type="text/javascript">';
		$htmlOut .= '$(document).ajaxStart(function() {$(\'.loading\').show();$(\'#mngDiv\').css(\'opacity\', \'0.3\');});' . '$(document).ajaxSuccess(function() {$(\'.loading\').hide();$(\'#mngDiv\').css(\'opacity\', \'1\');});';
		$htmlOut .= 'var ajaxPost = $.post(\'../statisticalPlotter/AsynchronousProccess.php\', {\'townId\' : $(\'#townNameForUnit option:selected\').val()});ajaxPost.done(function(data) {$(\'#centerId\').empty().append(data);$(\'#unitId\').load(\'../statisticalPlotter/AsynchronousProccess.php\', {\'centerId\' : $(\'#centerId option:selected\').val()});});';
		$htmlOut .= '$(\'#townNameForUnit\').change(function() {var ajaxPost1 = $.post(\'../statisticalPlotter/AsynchronousProccess.php\', {\'townId\' : $(\'#townNameForUnit option:selected\').val()});ajaxPost1.done(function(data) {$(\'#centerId\').empty().append(data);$(\'#unitId\').load(\'../statisticalPlotter/AsynchronousProccess.php\', {\'centerId\' : $(\'#centerId option:selected\').val()});});});';
		$htmlOut .= '$(\'#centerId\').change(function() {$(\'#unitId\').load(\'../statisticalPlotter/AsynchronousProccess.php\', {\'centerId\' : $(\'#centerId option:selected\').val()});});';
		$htmlOut .= '</script>';
		$htmlOut .= '<label for="input_service_name" class="required">نام خدمت:</label>';
		$htmlOut .= '<select name="input_service_name" id="input_service_name" class="validate first_opt_not_allowed">';
		$dbRes = Database::execute_query("SELECT `id`, `name` FROM `available-services`;");
		if (Database::num_of_rows($dbRes) > 0) {
			$htmlOut .= '<option>انتخاب کنید...</option>';
			while ($dbRow = Database::get_assoc_array($dbRes))
				$htmlOut .= '<option value="' . $dbRow['id'] . '">' . $dbRow['name'] . '</option>';
		} else
			$htmlOut .= '<option>' . NO_AVAILABLE_SERVICES . '</option>';
		$htmlOut .= '</select>';
		$htmlOut .= '<br /><br /><input type="submit" name="submit_eash_AddUnitService" value="ثبت خدمت برای واحد" />';
		$htmlOut .= '</fieldset></form>';

		$htmlOut .= '<br /><fieldset style="width: 580px;"><legend>مشاهده ی سرویس های واحدها</legend>';
		$htmlOut .= '<br /><label for="townId" class="required">نام شهرستان:</label>';
		$htmlOut .= '<select name="townId" id="townId" class="validate">';
		$res = Database::execute_query("SELECT * FROM `towns` ORDER BY `name` ASC;");
		if (Database::num_of_rows($res) > 0) {
			while ($row = Database::get_assoc_array($res))
				$htmlOut .= '<option value="' . $row['id'] . '">' . $row['name'] . '</option>';
		} else
			$htmlOut .= '<option>' . NO_TOWN_EXISTS . '</option>';
		$htmlOut .= '</select>&nbsp-->&nbsp';
		$htmlOut .= '<label for="centerId_1" class="required">نام مرکز:</label>';
		$htmlOut .= '<select name="centerId_1" id="centerId_1" class="validate"></select>&nbsp-->&nbsp';
		$htmlOut .= '<label for="unitId_1" class="required">نام واحد:</label>';
		$htmlOut .= '<select name="unitId_1" id="unitId_1" class="validate"></select><br />';
		$htmlOut .= '<script type="text/javascript">';
		$htmlOut .= 'var ajaxPost = $.post(\'../statisticalPlotter/AsynchronousProccess.php\', {\'townId\' : $(\'#townId option:selected\').val()});ajaxPost.done(function(data) {$(\'#centerId_1\').empty().append(data);var ajaxPost1 = $.post(\'../statisticalPlotter/AsynchronousProccess.php\', {\'centerId\' : $(\'#centerId_1 option:selected\').val()});ajaxPost1.done(function(data) {$(\'#unitId_1\').empty().append(data);$(\'#list\').load(\'./ea-sh-ajax.php\', {\'unitIdForServices\' : $(\'#unitId_1 option:selected\').val()});});});';
		$htmlOut .= '$(\'#townId\').change(function() {var ajaxPost = $.post(\'../statisticalPlotter/AsynchronousProccess.php\', {\'townId\' : $(\'#townId option:selected\').val()});ajaxPost.done(function(data) {$(\'#centerId_1\').empty().append(data);var ajaxPost1 = $.post(\'../statisticalPlotter/AsynchronousProccess.php\', {\'centerId\' : $(\'#centerId_1 option:selected\').val()});ajaxPost1.done(function(data) {$(\'#unitId_1\').empty().append(data);$(\'#list\').load(\'./ea-sh-ajax.php\', {\'unitIdForServices\' : $(\'#unitId_1 option:selected\').val()});});});});';
		$htmlOut .= '$(\'#centerId_1\').change(function() {var ajaxPost = $.post(\'../statisticalPlotter/AsynchronousProccess.php\', {\'centerId\' : $(\'#centerId_1 option:selected\').val()});ajaxPost.done(function(data) {$(\'#unitId_1\').empty().append(data);$(\'#list\').load(\'./ea-sh-ajax.php\', {\'unitIdForServices\' : $(\'#unitId_1 option:selected\').val()});});});';
		$htmlOut .= '$(\'#unitId_1\').change(function() {$(\'#list\').load(\'./ea-sh-ajax.php\', {\'unitIdForServices\' : $(\'#unitId_1 option:selected\').val()});});';
		$htmlOut .= '</script>';
		$htmlOut .= '<div id="list"></div><br /></fieldset>';
		$htmlOut .= '</div>';

		return $htmlOut;
	}

	private static function manageHygieneUnitsServices() {
		$htmlOut = '';
		$htmlOut .= '<p>در این قسمت شما می توانید خدمات موردنظر خود را برای خانه های بهداشت تعریف نمایید.</p>';
		$htmlOut .= '<p style="text-align: right; font-weight: bold; font-size: 15px; margin-right: 20px"><a href="#" style="color: green;" id="slideLink" ><img src="../illustrator/images/icons/plus.png" />&nbsp;افزودن خدمت</a></p>';
		$htmlOut .= '<div class="loading"><img src="../statisticalPlotter/loading.gif" />&nbsp;&nbsp;در حال بارگذاری؛ لطفا صبر کنید...</div><br />';
		$htmlOut .= '<div id="mngDiv"><form action="' . substr(strrchr($_SERVER['PHP_SELF'], '/'), 1) . '" method="post" id="slideForm" class="narin"><fieldset><legend>افزودن خدمت</legend>';
		$htmlOut .= '<label for="townId" class="required">نام شهرستان:</label>';
		$htmlOut .= '<select name="townId" id="townId" class="validate first_opt_not_allowed">';
		$res = Database::execute_query("SELECT * FROM `towns` ORDER BY `name` ASC;");
		if (Database::num_of_rows($res) > 0) {
			$htmlOut .= '<option>انتخاب کنید...</option>';
			while ($row = Database::get_assoc_array($res))
				$htmlOut .= '<option value="' . $row['id'] . '">' . $row['name'] . '</option>';
		} else
			$htmlOut .= '<option>' . NO_TOWN_EXISTS . '</option>';
		$htmlOut .= '</select><br />';
		$htmlOut .= '<label for="centerId" class="required">نام مرکز:</label>';
		$htmlOut .= '<select name="centerId" id="centerId" class="validate first_opt_not_allowed"></select><br />';
		$htmlOut .= '<label for="hygieneUnitId" class="required">نام خانه بهداشت:</label>';
		$htmlOut .= '<select name="hygieneUnitId" id="hygieneUnitId" class="validate first_opt_not_allowed"></select><br />';
		$htmlOut .= '<script type="text/javascript">';
		$htmlOut .= '$(document).ajaxStart(function() {$(\'.loading\').show();$(\'#mngDiv\').css(\'opacity\', \'0.3\');});' . '$(document).ajaxSuccess(function() {$(\'.loading\').hide();$(\'#mngDiv\').css(\'opacity\', \'1\');});';
		$htmlOut .= 'var ajaxPost = $.post(\'../statisticalPlotter/AsynchronousProccess.php\', {\'townId\' : $(\'#townId option:selected\').val()});ajaxPost.done(function(data) {$(\'#centerId\').empty().append(data);$(\'#hygieneUnitId\').load(\'../statisticalPlotter/AsynchronousProccess.php\', {\'centerIdForHygieneUnitSelection\' : $(\'#centerId option:selected\').val()});});';
		$htmlOut .= '$(\'#townId\').change(function() {var ajaxPost1 = $.post(\'../statisticalPlotter/AsynchronousProccess.php\', {\'townId\' : $(\'#townId option:selected\').val()});ajaxPost1.done(function(data) {$(\'#centerId\').empty().append(data);$(\'#hygieneUnitId\').load(\'../statisticalPlotter/AsynchronousProccess.php\', {\'centerIdForHygieneUnitSelection\' : $(\'#centerId option:selected\').val()});});});';
		$htmlOut .= '$(\'#centerId\').change(function() {$(\'#hygieneUnitId\').load(\'../statisticalPlotter/AsynchronousProccess.php\', {\'centerIdForHygieneUnitSelection\' : $(\'#centerId option:selected\').val()});});';
		$htmlOut .= '</script>';
		$htmlOut .= '<label for="input_service_name" class="required">نام خدمت:</label>';
		$htmlOut .= '<select name="input_service_name" class="validate first_opt_not_allowed">';
		$dbRes = Database::execute_query("SELECT `id`, `name` FROM `available-services`;");
		if (Database::num_of_rows($dbRes) > 0) {
			$htmlOut .= '<option>انتخاب کنید...</option>';
			while ($dbRow = Database::get_assoc_array($dbRes))
				$htmlOut .= '<option value="' . $dbRow['id'] . '">' . $dbRow['name'] . '</option>';
		} else
			$htmlOut .= '<option>' . NO_AVAILABLE_SERVICES . '</option>';
		$htmlOut .= '</select>';
		$htmlOut .= '<br /><br /><input type="submit" name="submit_eash_AddHygieneUnitService" value="ثبت خدمت برای خانه بهداشت" />';
		$htmlOut .= '</fieldset></form>';

		$htmlOut .= '<fieldset style="width: 580px;"><legend>مشاهده ی خدمات خانه های بهداشت</legend>';
		$htmlOut .= '<br /><label for="townId_1" class="required">نام شهرستان:</label>';
		$htmlOut .= '<select name="townId_1" id="townId_1" class="validate">';
		$res = Database::execute_query("SELECT * FROM `towns` ORDER BY `name` ASC;");
		if (Database::num_of_rows($res) > 0) {
			while ($row = Database::get_assoc_array($res))
				$htmlOut .= '<option value="' . $row['id'] . '">' . $row['name'] . '</option>';
		} else
			$htmlOut .= '<option>' . NO_TOWN_EXISTS . '</option>';
		$htmlOut .= '</select>&nbsp; --> &nbsp;';
		$htmlOut .= '<label for="centerId_1" class="required">نام مرکز:</label>';
		$htmlOut .= '<select name="centerId_1" id="centerId_1" class="validate"></select><br /><br />';
		$htmlOut .= '<label for="hygieneUnitId_1" class="required">نام خانه بهداشت:</label>';
		$htmlOut .= '<select name="hygieneUnitId_1" id="hygieneUnitId_1" class="validate"></select><br />';
		$htmlOut .= '<script type="text/javascript">';
		$htmlOut .= 'var ajaxPost = $.post(\'../statisticalPlotter/AsynchronousProccess.php\', {\'townId\' : $(\'#townId_1 option:selected\').val()});ajaxPost.done(function(data) {$(\'#centerId_1\').empty().append(data);var ajaxPost1 = $.post(\'../statisticalPlotter/AsynchronousProccess.php\', {\'centerIdForHygieneUnitSelection\' : $(\'#centerId_1 option:selected\').val()});ajaxPost1.done(function(data) {$(\'#hygieneUnitId_1\').empty().append(data);$(\'#list\').load(\'./ea-sh-ajax.php\', {\'hygieneUnitIdForServices\' : $(\'#hygieneUnitId_1 option:selected\').val()});});});';
		$htmlOut .= '$(\'#townId_1\').change(function() {var ajaxPost = $.post(\'../statisticalPlotter/AsynchronousProccess.php\', {\'townId\' : $(\'#townId_1 option:selected\').val()});ajaxPost.done(function(data) {$(\'#centerId_1\').empty().append(data);var ajaxPost1 = $.post(\'../statisticalPlotter/AsynchronousProccess.php\', {\'centerIdForHygieneUnitSelection\' : $(\'#centerId_1 option:selected\').val()});ajaxPost1.done(function(data) {$(\'#hygieneUnitId_1\').empty().append(data);$(\'#list\').load(\'./ea-sh-ajax.php\', {\'hygieneUnitIdForServices\' : $(\'#hygieneUnitId_1 option:selected\').val()});});});});';
		$htmlOut .= '$(\'#centerId_1\').change(function() {var ajaxPost = $.post(\'../statisticalPlotter/AsynchronousProccess.php\', {\'centerIdForHygieneUnitSelection\' : $(\'#centerId_1 option:selected\').val()});ajaxPost.done(function(data) {$(\'#hygieneUnitId_1\').empty().append(data);$(\'#list\').load(\'./ea-sh-ajax.php\', {\'hygieneUnitIdForServices\' : $(\'#hygieneUnitId_1 option:selected\').val()});});});';
		$htmlOut .= '$(\'#hygieneUnitId_1\').change(function() {$(\'#list\').load(\'./ea-sh-ajax.php\', {\'hygieneUnitIdForServices\' : $(\'#hygieneUnitId_1 option:selected\').val()});});';
		$htmlOut .= '</script>';
		$htmlOut .= '<div id="list"></div><br /></fieldset>';
		$htmlOut .= '</div>';

		return $htmlOut;
	}

}//END class ShortcutsView
?>