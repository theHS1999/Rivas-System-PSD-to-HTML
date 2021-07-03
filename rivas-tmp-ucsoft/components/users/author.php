<?php

/**
 *
 * author.php
 * author class file
 *
 * @copyright	Copyright (C) 2012 Rivas Systems Inc. All rights reserved.
 * @versin	1.00 2012-02-05
 * @author	Behnam Salili
 *
 */

require_once dirname(dirname(dirname(__file__))) . '/defines.php';
require_once dirname(dirname(__file__)) . '/database/database.php';
require_once dirname(dirname(__file__)) . '/illustrator/illustrator.php';
require_once dirname(dirname(__file__)) . '/illustrator/pdate.php';
require_once dirname(dirname(__file__)) . '/form/form-proccessor.php';
require_once dirname(dirname(__file__)) . '/models/state.php';
require_once dirname(dirname(__file__)) . '/models/town.php';
require_once dirname(dirname(__file__)) . '/models/center.php';
require_once dirname(dirname(__file__)) . '/models/unit.php';
require_once dirname(dirname(__file__)) . '/models/hygiene-unit.php';

class Author {

	private $pageTitle;
	private $viewer;

	public function __construct($title) {
		$this -> pageTitle = $title;
		$this -> viewer = new Illustrator;
		State::setName('گیلان');
	}

	public function loggedIn() {
		return (isset($_SESSION['user']) and ($_SESSION['user']['acl'] == 'Unit Author' or $_SESSION['user']['acl'] == 'Hygiene Unit Author' or $_SESSION['user']['acl'] == 'Multi Author' or $_SESSION['user']['acl'] == 'State Author' or $_SESSION['user']['acl'] == 'Town Author' or $_SESSION['user']['acl'] == 'Center Author'));
	}

	public function render() {

		$this -> viewer -> setMainStylePath('../illustrator/styles/main-style.css');
		$this -> viewer -> setMainPrintStylePath('../illustrator/styles/print.css');
		$this -> viewer -> setFormStylePath('../illustrator/styles/narin-form.css');
		$this -> viewer -> setJQUIStylePath('../illustrator/styles/jquery-ui-1.10.1.custom.min.css');
		$this -> viewer -> setJQUICustomizedStylePath('../illustrator/styles/customize-jquery-ui.css');
		$this -> viewer -> setJPlotPath('../illustrator/styles/jquery.jqplot.min.css');
		$this -> viewer -> setCalendarStylePath('../illustrator/styles/calendar.css');

		$this -> viewer -> setJQueryPath('../illustrator/js/jquery-1.9.1.min.js');
		$this -> viewer -> setFormJSPath('../illustrator/js/narin-form.js');
		$this -> viewer -> setMainJSPath('../illustrator/js/main.js');
		$this -> viewer -> setAJAXPath('../statisticalPlotter/AsynchronousProcess.js');
		$this -> viewer -> setJQUIPath('../illustrator/js/jquery-ui-1.10.1.custom.min.js');
		$this -> viewer -> setJSCalendarPath('../illustrator/js/calendar.js');
		$this -> viewer -> setJSShamsiPath('../illustrator/js/shamsi.js');

		$this -> viewer -> setPageTitle($this -> pageTitle);
		
		//set header
		/*if ($_SESSION['user']['acl'] == 'State Author') {
			$res = Database::execute_query("SELECT `name` FROM `states` WHERE `id` = '" . $_SESSION['user']['acl-id'] . "';");
			$tmp = Database::get_assoc_array($res);
			$stateName = $tmp['name'];
			$this -> viewer -> setHeader('<h2><a href="' . substr(strrchr($_SERVER['PHP_SELF'], '/'), 1) . '">بخش ورود اطلاعات</a></h2><h3>استان "' . $stateName . '"</h3>');

		} elseif ($_SESSION['user']['acl'] == 'Town Author') {
			$res = Database::execute_query("SELECT `name`, `state-id` FROM `towns` WHERE `id` = '" . $_SESSION['user']['acl-id'] . "';");
			$tmp = Database::get_assoc_array($res);
			$townName = $tmp['name'];
			$res = Database::execute_query("SELECT `name` FROM `states` WHERE `id` = '" . $tmp['state-id'] . "';");
			$tmp = Database::get_assoc_array($res);
			$stateName = $tmp['name'];
			$this -> viewer -> setHeader('<h2><a href="' . substr(strrchr($_SERVER['PHP_SELF'], '/'), 1) . '">بخش ورود اطلاعات</a></h2><h3>استان "' . $stateName . '" > شهرستان "' . $townName . '"</h3>');

		} elseif ($_SESSION['user']['acl'] == 'Center Author') {
			$res = Database::execute_query("SELECT `name`, `town-id` FROM `centers` WHERE `id` = '" . $_SESSION['user']['acl-id'] . "';");
			$tmp = Database::get_assoc_array($res);
			$centerName = $tmp['name'];
			$res = Database::execute_query("SELECT `name`, `state-id` FROM `towns` WHERE `id` = '" . $tmp['town-id'] . "';");
			$tmp = Database::get_assoc_array($res);
			$townName = $tmp['name'];
			$res = Database::execute_query("SELECT `name` FROM `states` WHERE `id` = '" . $tmp['state-id'] . "';");
			$tmp = Database::get_assoc_array($res);
			$stateName = $tmp['name'];
			$this -> viewer -> setHeader('<h2><a href="' . substr(strrchr($_SERVER['PHP_SELF'], '/'), 1) . '">بخش ورود اطلاعات</a></h2><h3>استان "' . $stateName . '" > شهرستان "' . $townName . '" > مرکز "' . $centerName . '"</h3>');

		} elseif ($_SESSION['user']['acl'] == 'Unit Author') {
			$res = Database::execute_query("SELECT `name`, `center-id` FROM `units` WHERE `id` = '" . $_SESSION['user']['acl-id'] . "';");
			$tmp = Database::get_assoc_array($res);
			$unitName = $tmp['name'];
			$res = Database::execute_query("SELECT `name`, `town-id` FROM `centers` WHERE `id` = '" . $tmp['center-id'] . "';");
			$tmp = Database::get_assoc_array($res);
			$centerName = $tmp['name'];
			$res = Database::execute_query("SELECT `name`, `state-id` FROM `towns` WHERE `id` = '" . $tmp['town-id'] . "';");
			$tmp = Database::get_assoc_array($res);
			$townName = $tmp['name'];
			$res = Database::execute_query("SELECT `name` FROM `states` WHERE `id` = '" . $tmp['state-id'] . "';");
			$tmp = Database::get_assoc_array($res);
			$stateName = $tmp['name'];
			$this -> viewer -> setHeader('<h2><a href="' . substr(strrchr($_SERVER['PHP_SELF'], '/'), 1) . '">بخش ورود اطلاعات</a></h2><h3>استان "' . $stateName . '" > شهرستان "' . $townName . '" > مرکز "' . $centerName . '" > واحد "' . $unitName . '"</h3>');

		} elseif ($_SESSION['user']['acl'] == 'Hygiene Unit Author') {
			$res = Database::execute_query("SELECT `name`, `center-id` FROM `hygiene-units` WHERE `id` = '" . $_SESSION['user']['acl-id'] . "';");
			$tmp = Database::get_assoc_array($res);
			$hygieneUnitName = $tmp['name'];
			$res = Database::execute_query("SELECT `name`, `town-id` FROM `centers` WHERE `id` = '" . $tmp['center-id'] . "';");
			$tmp = Database::get_assoc_array($res);
			$centerName = $tmp['name'];
			$res = Database::execute_query("SELECT `name`, `state-id` FROM `towns` WHERE `id` = '" . $tmp['town-id'] . "';");
			$tmp = Database::get_assoc_array($res);
			$townName = $tmp['name'];
			$res = Database::execute_query("SELECT `name` FROM `states` WHERE `id` = '" . $tmp['state-id'] . "';");
			$tmp = Database::get_assoc_array($res);
			$stateName = $tmp['name'];
			$this -> viewer -> setHeader('<h2><a href="' . substr(strrchr($_SERVER['PHP_SELF'], '/'), 1) . '">بخش ورود اطلاعات</a></h2><h3>استان "' . $stateName . '" > شهرستان "' . $townName . '" > خانه بهداشت "' . $hygieneUnitName . '"</h3>');

		} elseif ($_SESSION['user']['acl'] == 'Multi Author')
			$this -> viewer -> setHeader('<h2><a href="' . substr(strrchr($_SERVER['PHP_SELF'], '/'), 1) . '">بخش ورود اطلاعات</a></h2><h3>نویسنده چندمنظوره</h3>');
		 */

		unset($res, $tmp);

		/*$date = pgetdate();
		 $this->viewer->appendDateBlock('امروز ' . $date['weekday'] .' '. $date['mday'] . ' ' . $date['month']);*/

		if (isset($_GET['cmd'])) {
			$tmp = $this -> prepareMenu($_GET['cmd']);
			if (is_array($tmp)) {
				foreach ($tmp as $element)
					$this -> viewer -> appendRightBlock($element);
			} else
				$this -> viewer -> appendRightBlock($tmp);

			$tmp = $this -> prepareContent($_GET['cmd']);
			if (is_array($tmp)) {
				foreach ($tmp as $element)
					$this -> viewer -> appendCenterBlock($element);
			} else
				$this -> viewer -> appendCenterBlock($tmp);

		} else
			$this -> viewer -> appendCenterBlock('<p>به بخش ثبت اطلاعات سامانه جامع مدیریت مراکز بهداشتی-درمانی روستایی خوش آمدید.' . '<br /><br />برای شروع از منوی سمت راست گزینه ی موردنظر را انتخاب نمایید.' . '<br/>همچنین می توانید با انتخاب گزینه [خروج از سامانه] از سیستم خارج شوید.</p>');

		$this -> viewer -> appendRightBlock('<a href="?cmd=registerService"><img src="../illustrator/images/icons/service.png" />&nbsp;ثبت خدمات انجام شده</a>' . '<a href="?cmd=editFreqRequests"><img src="../illustrator/images/icons/editReq.png" />&nbsp;اسناد اصلاحی تائید شده</a>' . '<a href="?cmd=logOut"><img src="../illustrator/images/icons/exit.png" />&nbsp;خروج از سامانه</a>');

		if (isset($_POST['submitRegisterDoneServices'])) {
			$tmp = FormProccessor::proccessRegisterDoneServicesForm();
			if (is_array($tmp))
				$this -> viewer -> appendCenterBlock('<p id="err">' . $tmp['err_msg'] . '</p><p style="text-align: center;">[<a href="javascript:history.go(-1);">بازگشت</a>]</p>');
		}
		
		if (isset($_POST['submitEditDoneServices'])) {
			$tmp = FormProccessor::proccessEditDoneServicesForm();
			if (is_array($tmp))
				$this -> viewer -> appendCenterBlock('<p id="err">' . $tmp['err_msg'] . '</p><p style="text-align: center;">[<a href="javascript:history.go(-1);">بازگشت</a>]</p>');
		}
		
		if (isset($_POST['submitEditUnitFrequencyRequest'])) {
			$tmp = FormProccessor::proccessEditUnitFrequencyRequestForm();
			if (is_array($tmp))
				$this -> viewer -> appendCenterBlock('<p id="err">' . $tmp['err_msg'] . '</p><p style="text-align: center;">[<a href="javascript:history.go(-1);">بازگشت</a>]</p>');
		}
		
		if (isset($_POST['submitEditHGUnitFrequencyRequest'])) {
			$tmp = FormProccessor::proccessEditHGUnitFrequencyRequestForm();
			if (is_array($tmp))
				$this -> viewer -> appendCenterBlock('<p id="err">' . $tmp['err_msg'] . '</p><p style="text-align: center;">[<a href="javascript:history.go(-1);">بازگشت</a>]</p>');
		}

		if (isset($_GET['success']))
			$this -> viewer -> appendCenterBlock(DATA_REGISTER_CONFIRMATION);

		$this -> viewer -> setFooter('<span>طراحی و پیاده سازی در <a href="http://www.rivasit.com">شرکت ریواس سیستم پارس</a>.</span>');

		$this -> viewer -> illustrate(dirname(dirname(__file__)) . '/illustrator/index.tpl');
	}

	private function registerService() {
		$htmlOut = '';
		if ($_SESSION['user']['acl'] == 'Unit Author') {
			$htmlOut = '<p>آخرین تاریخ ثبت خدمات انجام شده: ';
			$res = Database::execute_query("SELECT `lastdate-of-done-services-register` FROM `units` WHERE `id` = '" . $_SESSION['user']['acl-id'] . "';");
			$tmp = Database::get_assoc_array($res);
			$tmp = date_parse($tmp['lastdate-of-done-services-register']);
			$htmlOut .= '<span style="text-decoration:underline;">' . $tmp['year'] . '/' . $tmp['month'] . '/' . $tmp['day'] . '</span>';
			$htmlOut .= '</p>';
		} elseif ($_SESSION['user']['acl'] == 'Hygiene Unit Author') {
			$htmlOut = '<p>آخرین تاریخ ثبت خدمات انجام شده: ';
			$res = Database::execute_query("SELECT `lastdate-of-done-services-register` FROM `hygiene-units` WHERE `id` = '" . $_SESSION['user']['acl-id'] . "';");
			$tmp = Database::get_assoc_array($res);
			$tmp = date_parse($tmp['lastdate-of-done-services-register']);
			$htmlOut .= '<span style="text-decoration:underline;">' . $tmp['year'] . '/' . $tmp['month'] . '/' . $tmp['day'] . '</span>';
			$htmlOut .= '</p>';
		}

		if ($_SESSION['user']['acl'] == 'Multi Author') {
			$htmlOut .= '<div id="tabs"><ul><li><a href="#tabs-1">مراکز</a></li><li><a href="#tabs-2">خانه های بهداشت</a></li></ul>';
			$htmlOut .= '<div id="tabs-1">';
			$htmlOut .= '<div class="loading"><img src="../statisticalPlotter/loading.gif" />&nbsp;&nbsp;در حال بارگذاری؛ لطفا صبر کنید...</div><br />';
			$htmlOut .= '<form action="' . substr(strrchr($_SERVER['PHP_SELF'], '/'), 1) . '" method="post" class="narin">';
			$htmlOut .= '<input type="hidden" name="multiAuthorType" value="center" />';
			$htmlOut .= '<input type="hidden" name="narin:token" value="' . Narin::create_token() . '" /><fieldset><legend>ثبت بار خدمت</legend>';
			$centerIds = explode(',', $_SESSION['user']['centerAuthorIds']);
			$htmlOut .= '<label for="centerId_1" class="required">نام مرکز:</label>';
			$htmlOut .= '<select name="centerId_1" id="centerId_1" class="validate">';
			foreach ($centerIds as $id) {
				$res = Database::execute_query("SELECT `name` FROM `centers` WHERE `id` = '$id';");
				$row = Database::get_assoc_array($res);
				$htmlOut .= '<option value="' . $id . '">' . $row['name'] . '</option>';
			}
			$htmlOut .= '</select>';
			$htmlOut .= '<br /><label for="unitId_1" class="required">نام واحد:</label>';
			$htmlOut .= '<select name="unitId_1" id="unitId_1" class="validate"></select><br />';
			$htmlOut .= '<script type="text/javascript">';
			$htmlOut .= '$(document).ajaxStart(function() {$(\'.loading\').show();$(\'#tabs-1\').css(\'opacity\', \'0.3\');});' . '$(document).ajaxSuccess(function() {$(\'.loading\').hide();$(\'#tabs-1\').css(\'opacity\', \'1\');});';
			$htmlOut .= 'var ajaxPost = $.post(\'../statisticalPlotter/AsynchronousProccess.php\', {\'centerId\' : $(\'#centerId_1 option:selected\').val()});ajaxPost.done(function(data) {$(\'#unitId_1\').empty().append(data);$(\'#input_service_id\').load(\'./ea-sh-ajax.php\', {\'unitIdForServiceSelection\' : $(\'#unitId_1 option:selected\').val()});});';
			$htmlOut .= '$(\'#centerId_1\').change(function() {var ajaxPost = $.post(\'../statisticalPlotter/AsynchronousProccess.php\', {\'centerId\' : $(\'#centerId_1 option:selected\').val()});ajaxPost.done(function(data) {$(\'#unitId_1\').empty().append(data);$(\'#input_service_id\').load(\'./ea-sh-ajax.php\', {\'unitIdForServiceSelection\' : $(\'#unitId_1 option:selected\').val()});});});';
			$htmlOut .= '$(\'#unitId_1\').change(function() {$(\'#input_service_id\').load(\'./ea-sh-ajax.php\', {\'unitIdForServiceSelection\' : $(\'#unitId_1 option:selected\').val()});});';
			$htmlOut .= '</script>';

			$htmlOut .= '<label for="input_service_id">نام خدمت:</label>';
			$htmlOut .= '<select name="input_service_id" id="input_service_id" class="';
			if (isset($r['invalid']['input_service_id']))
				$htmlOut .= 'invalid';
			$htmlOut .= '">';
			$htmlOut .= '</select><br />';
			$htmlOut .= '<label for="input_service_frequency" class="required">بار خدمت:</label>';
			$htmlOut .= '<input type="text" name="input_service_frequency" id="input_service_frequency" maxlength="6" dir="ltr"';
			if (isset($_POST['input_service_frequency']) && $_POST['input_service_frequency'] !== '')
				$htmlOut .= ' value="' . htmlspecialchars($_POST['input_service_frequency']) . '"';
			$htmlOut .= ' class="validate required integer';
			if (isset($r['invalid']['input_service_frequency']))
				$htmlOut .= ' invalid';
			$htmlOut .= '" />';
			$htmlOut .= '<span class="description"';
			if (!isset($r['invalid']['input_service_frequency']))
				$htmlOut .= ' style="display:none;"';
			$htmlOut .= '>عدد صحیح، حداقل بزرگتر از 0</span>';
			$htmlOut .= '<script type="text/javascript">$(\'#input_service_frequency\').data(\'num_min\',0).data(\'num_min_inc\',false);</script><br />';
			$htmlOut .= '<label for="start_date:d" class="required">از تاریخ:</label>';
			$htmlOut .= '<div class="date_picker required';
			if (isset($r['invalid']['start_date']))
				$htmlOut .= ' invalid';
			$htmlOut .= '">';
			$htmlOut .= '<select name="start_date:d" id="start_date:d" class="date_d">';
			$htmlOut .= '<option value="0">روز</option><option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option><option value="5">5</option><option value="6">6</option><option value="7">7</option><option value="8">8</option><option value="9">9</option><option value="10">10</option><option value="11">11</option><option value="12">12</option><option value="13">13</option><option value="14">14</option><option value="15">15</option><option value="16">16</option><option value="17">17</option><option value="18">18</option><option value="19">19</option><option value="20">20</option><option value="21">21</option><option value="22">22</option><option value="23">23</option><option value="24">24</option><option value="25">25</option><option value="26">26</option><option value="27">27</option><option value="28">28</option><option value="29">29</option><option value="30">30</option><option value="31">31</option></select>';
			$htmlOut .= '<select name="start_date:m" class="date_m">';
			$htmlOut .= '<option value="0">ماه</option><option value="1">فروردین</option><option value="2">اردیبهشت</option><option value="3">خرداد</option><option value="4">تیر</option><option value="5">مرداد</option><option value="6">شهریور</option><option value="7">مهر</option><option value="8">آبان</option><option value="9">آذر</option><option value="10">دی</option><option value="11">بهمن</option><option value="12">اسفند</option></select>';
			$htmlOut .= '<input type="text" name="start_date:y" size="4" maxlength="4" class="date_y"';
			if (isset($_POST['start_date:y']) && $_POST['start_date:y'] !== '')
				$htmlOut .= ' value="' . htmlspecialchars($_POST['start_date:y']) . '"';
			$htmlOut .= ' /><span class="description">باید بعد از آخرین تاریخ باشد.</span></div><br />';
			$htmlOut .= '<label for="finish_date:d" class="required">تا تاریخ:</label>';
			$htmlOut .= '<div class="date_picker required';
			if (isset($r['invalid']['finish_date']))
				$htmlOut .= ' invalid';
			$htmlOut .= '">';
			$htmlOut .= '<select name="finish_date:d" id="finish_date:d" class="date_d">';
			$htmlOut .= '<option value="0">روز</option><option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option><option value="5">5</option><option value="6">6</option><option value="7">7</option><option value="8">8</option><option value="9">9</option><option value="10">10</option><option value="11">11</option><option value="12">12</option><option value="13">13</option><option value="14">14</option><option value="15">15</option><option value="16">16</option><option value="17">17</option><option value="18">18</option><option value="19">19</option><option value="20">20</option><option value="21">21</option><option value="22">22</option><option value="23">23</option><option value="24">24</option><option value="25">25</option><option value="26">26</option><option value="27">27</option><option value="28">28</option><option value="29">29</option><option value="30">30</option><option value="31">31</option></select>';
			$htmlOut .= '<select name="finish_date:m" class="date_m">';
			$htmlOut .= '<option value="0">ماه</option><option value="1">فروردین</option><option value="2">اردیبهشت</option><option value="3">خرداد</option><option value="4">تیر</option><option value="5">مرداد</option><option value="6">شهریور</option><option value="7">مهر</option><option value="8">آبان</option><option value="9">آذر</option><option value="10">دی</option><option value="11">بهمن</option><option value="12">اسفند</option></select>';
			$htmlOut .= '<input type="text" name="finish_date:y" size="4" maxlength="4" class="date_y"';
			if (isset($_POST['finish_date:y']) && $_POST['finish_date:y'] !== '')
				$htmlOut .= ' value="' . htmlspecialchars($_POST['finish_date:y']) . '"';
			$htmlOut .= ' /><span class="description">باید بعد از آخرین تاریخ و تاریخ شروع باشد.</span></div><br />';
			$htmlOut .= '<input type="submit" name="submitRegisterDoneServices" value="ثبت" /></fieldset></form>';
			$htmlOut .= '</div>';

			/*************************************************************************************************************************/

			$htmlOut .= '<div id="tabs-2">';
			$htmlOut .= '<div class="loading"><img src="../statisticalPlotter/loading.gif" />&nbsp;&nbsp;در حال بارگذاری؛ لطفا صبر کنید...</div><br />';
			$htmlOut .= '<form action="' . substr(strrchr($_SERVER['PHP_SELF'], '/'), 1) . '" method="post" class="narin">';
			$htmlOut .= '<input type="hidden" name="multiAuthorType" value="hygieneUnit" />';
			$htmlOut .= '<input type="hidden" name="narin:token" value="' . Narin::create_token() . '" /><fieldset><legend>ثبت بار خدمت</legend>';
			$hygieneUnitIds = explode(',', $_SESSION['user']['hygieneUnitAuthorIds']);
			$htmlOut .= '<label for="hygieneUnitId" class="required">نام خانه بهداشت:</label>';
			$htmlOut .= '<select name="hygieneUnitId" id="hygieneUnitId">';
			foreach ($hygieneUnitIds as $id) {
				$res = Database::execute_query("SELECT `name` FROM `hygiene-units` WHERE `id` = '$id';");
				$row = Database::get_assoc_array($res);
				$htmlOut .= '<option value="' . $id . '">' . $row['name'] . '</option>';
			}
			$htmlOut .= '</select><br />';
			$htmlOut .= '<script type="text/javascript">';
			$htmlOut .= '$(document).ajaxStart(function() {$(\'.loading\').show();$(\'#tabs-2\').css(\'opacity\', \'0.3\');});' . '$(document).ajaxSuccess(function() {$(\'.loading\').hide();$(\'#tabs-2\').css(\'opacity\', \'1\');});';
			$htmlOut .= 'var ajaxPost = $.post(\'./ea-sh-ajax.php\', {\'hygieneUnitIdForServiceSelection\' : $(\'#hygieneUnitId option:selected\').val()});ajaxPost.done(function(data) {$(\'#input_service_id_1\').empty().append(data);});';
			$htmlOut .= '$(\'#hygieneUnitId\').change(function() {$(\'#input_service_id_1\').load(\'./ea-sh-ajax.php\', {\'hygieneUnitIdForServiceSelection\' : $(\'#hygieneUnitId option:selected\').val()});});';
			$htmlOut .= '</script>';
			$htmlOut .= '<label for="input_service_id_1">نام خدمت:</label>';
			$htmlOut .= '<select name="input_service_id_1" id="input_service_id_1" class="';
			if (isset($r['invalid']['input_service_id_1']))
				$htmlOut .= 'invalid';
			$htmlOut .= '">';
			$htmlOut .= '</select><br />';
			$htmlOut .= '<label for="input_service_frequency" class="required">بار خدمت:</label>';
			$htmlOut .= '<input type="text" name="input_service_frequency" id="input_service_frequency" maxlength="6" dir="ltr"';
			if (isset($_POST['input_service_frequency']) && $_POST['input_service_frequency'] !== '')
				$htmlOut .= ' value="' . htmlspecialchars($_POST['input_service_frequency']) . '"';
			$htmlOut .= ' class="validate required integer';
			if (isset($r['invalid']['input_service_frequency']))
				$htmlOut .= ' invalid';
			$htmlOut .= '" />';
			$htmlOut .= '<span class="description"';
			if (!isset($r['invalid']['input_service_frequency']))
				$htmlOut .= ' style="display:none;"';
			$htmlOut .= '>عدد صحیح، حداقل بزرگتر از 0</span>';
			$htmlOut .= '<script type="text/javascript">$(\'#input_service_frequency\').data(\'num_min\',0).data(\'num_min_inc\',false);</script><br />';
			$htmlOut .= '<label for="start_date:d" class="required">از تاریخ:</label>';
			$htmlOut .= '<div class="date_picker required';
			if (isset($r['invalid']['start_date']))
				$htmlOut .= ' invalid';
			$htmlOut .= '">';
			$htmlOut .= '<select name="start_date:d" id="start_date:d" class="date_d">';
			$htmlOut .= '<option value="0">روز</option><option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option><option value="5">5</option><option value="6">6</option><option value="7">7</option><option value="8">8</option><option value="9">9</option><option value="10">10</option><option value="11">11</option><option value="12">12</option><option value="13">13</option><option value="14">14</option><option value="15">15</option><option value="16">16</option><option value="17">17</option><option value="18">18</option><option value="19">19</option><option value="20">20</option><option value="21">21</option><option value="22">22</option><option value="23">23</option><option value="24">24</option><option value="25">25</option><option value="26">26</option><option value="27">27</option><option value="28">28</option><option value="29">29</option><option value="30">30</option><option value="31">31</option></select>';
			$htmlOut .= '<select name="start_date:m" class="date_m">';
			$htmlOut .= '<option value="0">ماه</option><option value="1">فروردین</option><option value="2">اردیبهشت</option><option value="3">خرداد</option><option value="4">تیر</option><option value="5">مرداد</option><option value="6">شهریور</option><option value="7">مهر</option><option value="8">آبان</option><option value="9">آذر</option><option value="10">دی</option><option value="11">بهمن</option><option value="12">اسفند</option></select>';
			$htmlOut .= '<input type="text" name="start_date:y" size="4" maxlength="4" class="date_y"';
			if (isset($_POST['start_date:y']) && $_POST['start_date:y'] !== '')
				$htmlOut .= ' value="' . htmlspecialchars($_POST['start_date:y']) . '"';
			$htmlOut .= ' /><span class="description">باید بعد از آخرین تاریخ باشد.</span></div><br />';
			$htmlOut .= '<label for="finish_date:d" class="required">تا تاریخ:</label>';
			$htmlOut .= '<div class="date_picker required';
			if (isset($r['invalid']['finish_date']))
				$htmlOut .= ' invalid';
			$htmlOut .= '">';
			$htmlOut .= '<select name="finish_date:d" id="finish_date:d" class="date_d">';
			$htmlOut .= '<option value="0">روز</option><option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option><option value="5">5</option><option value="6">6</option><option value="7">7</option><option value="8">8</option><option value="9">9</option><option value="10">10</option><option value="11">11</option><option value="12">12</option><option value="13">13</option><option value="14">14</option><option value="15">15</option><option value="16">16</option><option value="17">17</option><option value="18">18</option><option value="19">19</option><option value="20">20</option><option value="21">21</option><option value="22">22</option><option value="23">23</option><option value="24">24</option><option value="25">25</option><option value="26">26</option><option value="27">27</option><option value="28">28</option><option value="29">29</option><option value="30">30</option><option value="31">31</option></select>';
			$htmlOut .= '<select name="finish_date:m" class="date_m">';
			$htmlOut .= '<option value="0">ماه</option><option value="1">فروردین</option><option value="2">اردیبهشت</option><option value="3">خرداد</option><option value="4">تیر</option><option value="5">مرداد</option><option value="6">شهریور</option><option value="7">مهر</option><option value="8">آبان</option><option value="9">آذر</option><option value="10">دی</option><option value="11">بهمن</option><option value="12">اسفند</option></select>';
			$htmlOut .= '<input type="text" name="finish_date:y" size="4" maxlength="4" class="date_y"';
			if (isset($_POST['finish_date:y']) && $_POST['finish_date:y'] !== '')
				$htmlOut .= ' value="' . htmlspecialchars($_POST['finish_date:y']) . '"';
			$htmlOut .= ' /><span class="description">باید بعد از آخرین تاریخ و تاریخ شروع باشد.</span></div><br />';
			$htmlOut .= '<input type="submit" name="submitRegisterDoneServices" value="ثبت" /></fieldset></form>';
			$htmlOut .= '</div>';

			$htmlOut .= '</div>';
		} elseif ($_SESSION['user']['acl'] == 'State Author') {
			$htmlOut .= '<p>در این بخش می توانید بار خدمات را در سطح استان وارد نمایید.</p>';
			$htmlOut .= '<div id="tabs"><ul><li><a href="#tabs-1">واحدها</a></li><li><a href="#tabs-2">خانه های بهداشت</a></li></ul>';
			$htmlOut .= '<div id="tabs-1">';
            $htmlOut .= '<div class="loading"><img src="../statisticalPlotter/loading.gif" />&nbsp;&nbsp;در حال بارگذاری؛ لطفا صبر کنید...</div><br />';
            $htmlOut .= '<div id="mngDiv1" style="text-align: right"><form action="' . substr(strrchr($_SERVER['PHP_SELF'], '/'), 1) . '" method="post" class="narin">';
            $htmlOut .= '<input type="hidden" name="narin:token" value="' . Narin::create_token() . '" />';
            $htmlOut .= '<p style="font-weight: bold">گام اول: انتخاب نوع واحد</p>';
            $htmlOut .= '<div><input type="radio" name="unit_type" id="unit_type_0" value="0"><label for="unit_type_0">عمومی</label>';
            $htmlOut .= '<input type="radio" name="unit_type" id="unit_type_1" value="1" style="margin-right: 10px;"><label for="unit_type_1">سیب</label></div>';
            $htmlOut .= '<script type="text/javascript">';
            $htmlOut .= '$(\'input[name="unit_type"]\').change(function() {';
            $htmlOut .= 'var value = this.value;';
            $htmlOut .= 'if(value === "0") {';
            $htmlOut .= '$("#centerId0").addClass("validate");';
            $htmlOut .= '$("#centerId1").removeClass("validate");';
            $htmlOut .= '$("#unitId0").addClass("validate");';
            $htmlOut .= '$("#unitId1").removeClass("validate");';
            $htmlOut .= '$("#input_service_id0").addClass("validate");';
            $htmlOut .= '$("#input_service_id1").removeClass("validate");';
            $htmlOut .= '$("#input_service_frequency0").addClass("required");';
            $htmlOut .= '$("#input_service_frequency1").removeClass("required");';
            $htmlOut .= '$("#date_picker_start_0").addClass("required");';
            $htmlOut .= '$("#date_picker_finish_0").addClass("required");';
            $htmlOut .= '$("#date_picker_start_1").removeClass("required");';
            $htmlOut .= '$("#date_picker_finish_1").removeClass("required");';
            $htmlOut .= '$("#new_sevice_1").hide();';
            $htmlOut .= '$("#new_sevice_0").show(); }';
            $htmlOut .= 'if(value === "1") {';
            $htmlOut .= '$("#centerId1").addClass("validate");';
            $htmlOut .= '$("#centerId0").removeClass("validate");';
            $htmlOut .= '$("#unitId1").addClass("validate");';
            $htmlOut .= '$("#unitId0").removeClass("validate");';
            $htmlOut .= '$("#input_service_id1").addClass("validate");';
            $htmlOut .= '$("#input_service_id0").removeClass("validate");';
            $htmlOut .= '$("#input_service_frequency1").addClass("required");';
            $htmlOut .= '$("#input_service_frequency0").removeClass("required");';
            $htmlOut .= '$("#date_picker_start_1").addClass("required");';
            $htmlOut .= '$("#date_picker_finish_1").addClass("required");';
            $htmlOut .= '$("#date_picker_start_0").removeClass("required");';
            $htmlOut .= '$("#date_picker_finish_0").removeClass("required");';
            $htmlOut .= '$("#new_sevice_0").hide();';
            $htmlOut .= '$("#new_sevice_1").show(); }';
            $htmlOut .= '});</script>';


            $htmlOut .= '<div id="new_sevice_0" style="display: none">';
            $htmlOut .= '<p style="font-weight: bold">گام دوم: وارد کردن بار خدمت عمومی</p>';
            $htmlOut .= '<br /><label for="townNameForUnit0" class="required">نام شهرستان:</label>';
            $htmlOut .= '<select name="townNameForUnit0" id="townNameForUnit0" class="validate">';
            $res = Database::execute_query("SELECT * FROM `towns` ORDER BY `name` ASC;");
            if (Database::num_of_rows($res) > 0) {
                while ($row = Database::get_assoc_array($res))
                    $htmlOut .= '<option value="' . $row['id'] . '">' . $row['name'] . '</option>';
            } else
                $htmlOut .= '<option>' . NO_TOWN_EXISTS . '</option>';
            $htmlOut .= '</select><br />';
            $htmlOut .= '<label for="centerId0" class="required">نام مرکز:</label>';
            $htmlOut .= '<select name="centerId0" id="centerId0" class="validate first_opt_not_allowed"></select><br />';
            $htmlOut .= '<label for="unitId0" class="required">نام واحد:</label>';
            $htmlOut .= '<select name="unitId0" id="unitId0" class="validate first_opt_not_allowed"></select><br />';
            $htmlOut .= '<label for="input_service_id0" class="required">نام خدمت:</label>';
            /*$htmlOut .= '<input id="input_service_id0" name="input_service_id0" list="services-list0" title="انتخاب کنید..." class="validate required ';
            if (isset($r['invalid']['input_service_id0']))
                $htmlOut .= 'invalid';
            $htmlOut .= '">';
            $htmlOut .= '<datalist id="services-list0"></datalist><br>';*/
            $htmlOut .= '<select name="input_service_id0" id="input_service_id0" class="validate first_opt_not_allowed"></select><br />';
            $htmlOut .= '<script type="text/javascript">';
            $htmlOut .= '$(document).ajaxStart(function() {$(\'.loading\').show();$(\'#mngDiv1\').css(\'opacity\', \'0.3\');});' . '$(document).ajaxSuccess(function() {$(\'.loading\').hide();$(\'#mngDiv1\').css(\'opacity\', \'1\');});';
            $htmlOut .= 'var ajaxPost = $.post(\'../statisticalPlotter/AsynchronousProccess.php\', {\'townId\' : $(\'#townNameForUnit0 option:selected\').val()});ajaxPost.done(function(data) {$(\'#centerId0\').empty().append(data);var ajaxPost1 = $.post(\'../statisticalPlotter/AsynchronousProccess.php\', {\'centerId\' : $(\'#centerId0 option:selected\').val(), \'unitType\' : \'0\'});ajaxPost1.done(function(data) {$(\'#unitId0\').empty().append(data);var ajaxPost2 = $.post(\'./ea-sh-ajax.php\', {\'unitIdForServiceSelection\' : $(\'#unitId0 option:selected\').val(), \'serviceType\' : \'0\'});ajaxPost2.done(function(data) {$(\'#input_service_id0\').empty().append(data);$(\'#numOfService\').load(\'./ea-sh-ajax.php\', {\'unitId4CurrentYearServices\': $(\'#unitId0 option:selected\').val()});});});});';
            $htmlOut .= 'var ajaxPost = $.post(\'../statisticalPlotter/AsynchronousProccess.php\', {\'townId\' : $(\'#townNameForUnit0 option:selected\').val()});ajaxPost.done(function(data) {$(\'#centerId0\').empty().append(data);var ajaxPost1 = $.post(\'../statisticalPlotter/AsynchronousProccess.php\', {\'centerId\' : $(\'#centerId0 option:selected\').val(), "unitType" : "0"});ajaxPost1.done(function(data) {$(\'#unitId0\').empty().append(data);var ajaxPost2 = $.post(\'./ea-sh-ajax.php\', {\'unitIdForServiceSelection\' : $(\'#unitId0 option:selected\').val(), \'serviceType\' : \'0\'});ajaxPost2.done(function(data) {$(\'#input_service_id0\').empty().append(data);$(\'#numOfService\').load(\'./ea-sh-ajax.php\', {\'unitId4CurrentYearServices\': $(\'#unitId0 option:selected\').val()});});});});';
            $htmlOut .= '$(\'#townNameForUnit0\').change(function() {var ajaxPost = $.post(\'../statisticalPlotter/AsynchronousProccess.php\', {\'townId\' : $(\'#townNameForUnit0 option:selected\').val()});ajaxPost.done(function(data) {$(\'#centerId0\').empty().append(data);var ajaxPost1 = $.post(\'../statisticalPlotter/AsynchronousProccess.php\', {\'centerId\' : $(\'#centerId0 option:selected\').val(), "unitType" : "0"});ajaxPost1.done(function(data) {$(\'#unitId0\').empty().append(data);var ajaxPost2 = $.post(\'./ea-sh-ajax.php\', {\'unitIdForServiceSelection\' : $(\'#unitId0 option:selected\').val(), \'serviceType\' : \'0\'});ajaxPost2.done(function(data) {$(\'#input_service_id0\').empty().append(data);$(\'#numOfService\').load(\'./ea-sh-ajax.php\', {\'unitId4CurrentYearServices\': $(\'#unitId0 option:selected\').val()});});});});});';
            $htmlOut .= '$(\'#centerId0\').change(function() {var ajaxPost1 = $.post(\'../statisticalPlotter/AsynchronousProccess.php\', {\'centerId\' : $(\'#centerId0 option:selected\').val(), "unitType" : "0"});ajaxPost1.done(function(data) {$(\'#unitId0\').empty().append(data);var ajaxPost2 = $.post(\'./ea-sh-ajax.php\', {\'unitIdForServiceSelection\' : $(\'#unitId0 option:selected\').val()});ajaxPost2.done(function(data) {$(\'#input_service_id0\').empty().append(data);$(\'#numOfService\').load(\'./ea-sh-ajax.php\', {\'unitId4CurrentYearServices\': $(\'#unitId option:selected\').val(), \'serviceType\' : \'0\'});});});});';
            $htmlOut .= '$(\'#unitId0\').change(function() {var ajaxPost2 = $.post(\'./ea-sh-ajax.php\', {\'unitIdForServiceSelection\' : $(\'#unitId0 option:selected\').val()});ajaxPost2.done(function(data) {$(\'#input_service_id0\').empty().append(data);$(\'#numOfService\').load(\'./ea-sh-ajax.php\', {\'unitId4CurrentYearServices\': $(\'#unitId0 option:selected\').val()});});});';
            //$htmlOut .= '$(\'#input_service_id\').change(function() {$(\'#numOfService\').load(\'./ea-sh-ajax.php\', {\'unitId4CurrentYearServices\': $(\'#unitId option:selected\').val()});});';
            $htmlOut .= '</script>';
            $htmlOut .= '<label for="input_service_frequency0" class="required">بار خدمت:</label>';
            $htmlOut .= '<input type="text" name="input_service_frequency0" id="input_service_frequency0" maxlength="6" dir="ltr"';
            if (isset($_POST['input_service_frequency']) && $_POST['input_service_frequency'] !== '')
                $htmlOut .= ' value="' . htmlspecialchars($_POST['input_service_frequency']) . '"';
            $htmlOut .= ' class="validate required integer';
            if (isset($r['invalid']['input_service_frequency0']))
                $htmlOut .= ' invalid';
            $htmlOut .= '" />';
            $htmlOut .= '<span class="description"';
            if (!isset($r['invalid']['input_service_frequency0']))
                $htmlOut .= ' style="display:none;"';
            $htmlOut .= '>عدد صحیح، حداقل بزرگتر از 0</span>';
            $htmlOut .= '<script type="text/javascript">$(\'#input_service_frequency0\').data(\'num_min\',0).data(\'num_min_inc\',false);</script><br />';
            $htmlOut .= '<label for="start_date:d" class="required">از تاریخ:</label>';
            $htmlOut .= '<div id="date_picker_start_0" class="date_picker required';
            if (isset($r['invalid']['start_date0']))
                $htmlOut .= ' invalid';
            $htmlOut .= '">';
            $htmlOut .= '<select name="start_date:d0" id="start_date:d" class="date_d">';
            $htmlOut .= '<option value="0">روز</option><option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option><option value="5">5</option><option value="6">6</option><option value="7">7</option><option value="8">8</option><option value="9">9</option><option value="10">10</option><option value="11">11</option><option value="12">12</option><option value="13">13</option><option value="14">14</option><option value="15">15</option><option value="16">16</option><option value="17">17</option><option value="18">18</option><option value="19">19</option><option value="20">20</option><option value="21">21</option><option value="22">22</option><option value="23">23</option><option value="24">24</option><option value="25">25</option><option value="26">26</option><option value="27">27</option><option value="28">28</option><option value="29">29</option><option value="30">30</option><option value="31">31</option></select>';
            $htmlOut .= '<select name="start_date:m0" class="date_m">';
            $htmlOut .= '<option value="0">ماه</option><option value="1">فروردین</option><option value="2">اردیبهشت</option><option value="3">خرداد</option><option value="4">تیر</option><option value="5">مرداد</option><option value="6">شهریور</option><option value="7">مهر</option><option value="8">آبان</option><option value="9">آذر</option><option value="10">دی</option><option value="11">بهمن</option><option value="12">اسفند</option></select>';
            $htmlOut .= '<input type="text" name="start_date:y0" size="4" maxlength="4" class="date_y"';
            if (isset($_POST['start_date:y']) && $_POST['start_date:y'] !== '')
                $htmlOut .= ' value="' . htmlspecialchars($_POST['start_date:y']) . '"';
            $htmlOut .= ' /><span class="description">باید بعد از آخرین تاریخ باشد.</span></div><br />';
            $htmlOut .= '<label for="finish_date:d0" class="required">تا تاریخ:</label>';
            $htmlOut .= '<div id="date_picker_finish_0" class="date_picker required';
            if (isset($r['invalid']['finish_date0']))
                $htmlOut .= ' invalid';
            $htmlOut .= '">';
            $htmlOut .= '<select name="finish_date:d0" id="finish_date:d" class="date_d">';
            $htmlOut .= '<option value="0">روز</option><option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option><option value="5">5</option><option value="6">6</option><option value="7">7</option><option value="8">8</option><option value="9">9</option><option value="10">10</option><option value="11">11</option><option value="12">12</option><option value="13">13</option><option value="14">14</option><option value="15">15</option><option value="16">16</option><option value="17">17</option><option value="18">18</option><option value="19">19</option><option value="20">20</option><option value="21">21</option><option value="22">22</option><option value="23">23</option><option value="24">24</option><option value="25">25</option><option value="26">26</option><option value="27">27</option><option value="28">28</option><option value="29">29</option><option value="30">30</option><option value="31">31</option></select>';
            $htmlOut .= '<select name="finish_date:m0" class="date_m">';
            $htmlOut .= '<option value="0">ماه</option><option value="1">فروردین</option><option value="2">اردیبهشت</option><option value="3">خرداد</option><option value="4">تیر</option><option value="5">مرداد</option><option value="6">شهریور</option><option value="7">مهر</option><option value="8">آبان</option><option value="9">آذر</option><option value="10">دی</option><option value="11">بهمن</option><option value="12">اسفند</option></select>';
            $htmlOut .= '<input type="text" name="finish_date:y0" size="4" maxlength="4" class="date_y"';
            if (isset($_POST['finish_date:y0']) && $_POST['finish_date:y0'] !== '')
                $htmlOut .= ' value="' . htmlspecialchars($_POST['finish_date:y']) . '"';
            $htmlOut .= ' /><span class="description">باید بعد از آخرین تاریخ و تاریخ شروع باشد.</span></div><br />';
            $htmlOut .= '</div>'; //end new_service_0


            $htmlOut .= '<div id="new_sevice_1" style="display: none">';
            $htmlOut .= '<p style="font-weight: bold">گام دوم: وارد کردن بار خدمت سیب</p>';
            $htmlOut .= '<br /><label for="townNameForUnit1" class="required">نام شهرستان:</label>';
            $htmlOut .= '<select name="townNameForUnit1" id="townNameForUnit1" class="validate">';
            $res = Database::execute_query("SELECT * FROM `towns` ORDER BY `name` ASC;");
            if (Database::num_of_rows($res) > 0) {
                while ($row = Database::get_assoc_array($res))
                    $htmlOut .= '<option value="' . $row['id'] . '">' . $row['name'] . '</option>';
            } else
                $htmlOut .= '<option>' . NO_TOWN_EXISTS . '</option>';
            $htmlOut .= '</select><br />';
            $htmlOut .= '<label for="centerId1" class="required">نام مرکز:</label>';
            $htmlOut .= '<select name="centerId1" id="centerId1" class="validate first_opt_not_allowed"></select><br />';
            $htmlOut .= '<label for="unitId1" class="required">نام واحد:</label>';
            $htmlOut .= '<select name="unitId1" id="unitId1" class="validate first_opt_not_allowed"></select><br />';
            $htmlOut .= '<label for="input_service_id1" class="required">نام خدمت:</label>';
            /*$htmlOut .= '<input id="input_service_id1" name="input_service_id1" list="services-list1" title="انتخاب کنید..." class="validate required ';
            if (isset($r['invalid']['input_service_id1']))
                $htmlOut .= 'invalid';
            $htmlOut .= '">';
            $htmlOut .= '<datalist id="services-list1"></datalist><br>';*/
            $htmlOut .= '<select name="input_service_id1" id="input_service_id1" class="validate first_opt_not_allowed"></select><br />';
            $htmlOut .= '<script type="text/javascript">';
            $htmlOut .= '$(document).ajaxStart(function() {$(\'.loading\').show();$(\'#mngDiv2\').css(\'opacity\', \'0.3\');});' . '$(document).ajaxSuccess(function() {$(\'.loading\').hide();$(\'#mngDiv2\').css(\'opacity\', \'1\');});';
            $htmlOut .= 'var ajaxPost = $.post(\'../statisticalPlotter/AsynchronousProccess.php\', {\'townId\' : $(\'#townNameForUnit1 option:selected\').val()});ajaxPost.done(function(data) {$(\'#centerId1\').empty().append(data);var ajaxPost1 = $.post(\'../statisticalPlotter/AsynchronousProccess.php\', {\'centerId\' : $(\'#centerId1 option:selected\').val(), \'unitType\' : \'1\'});ajaxPost1.done(function(data) {$(\'#unitId1\').empty().append(data);var ajaxPost2 = $.post(\'./ea-sh-ajax.php\', {\'unitIdForServiceSelection\' : $(\'#unitId1 option:selected\').val(), \'serviceType\' : \'1\'});ajaxPost2.done(function(data) {$(\'#input_service_id1\').empty().append(data);$(\'#numOfService\').load(\'./ea-sh-ajax.php\', {\'unitId4CurrentYearServices\': $(\'#unitId1 option:selected\').val()});});});});';
            $htmlOut .= 'var ajaxPost = $.post(\'../statisticalPlotter/AsynchronousProccess.php\', {\'townId\' : $(\'#townNameForUnit1 option:selected\').val()});ajaxPost.done(function(data) {$(\'#centerId1\').empty().append(data);var ajaxPost1 = $.post(\'../statisticalPlotter/AsynchronousProccess.php\', {\'centerId\' : $(\'#centerId1 option:selected\').val(), "unitType" : "1"});ajaxPost1.done(function(data) {$(\'#unitId1\').empty().append(data);var ajaxPost2 = $.post(\'./ea-sh-ajax.php\', {\'unitIdForServiceSelection\' : $(\'#unitId1 option:selected\').val(), \'serviceType\' : \'1\'});ajaxPost2.done(function(data) {$(\'#input_service_id1\').empty().append(data);$(\'#numOfService\').load(\'./ea-sh-ajax.php\', {\'unitId4CurrentYearServices\': $(\'#unitId1 option:selected\').val()});});});});';
            $htmlOut .= '$(\'#townNameForUnit1\').change(function() {var ajaxPost = $.post(\'../statisticalPlotter/AsynchronousProccess.php\', {\'townId\' : $(\'#townNameForUnit1 option:selected\').val()});ajaxPost.done(function(data) {$(\'#centerId1\').empty().append(data);var ajaxPost1 = $.post(\'../statisticalPlotter/AsynchronousProccess.php\', {\'centerId\' : $(\'#centerId1 option:selected\').val(), "unitType" : "1"});ajaxPost1.done(function(data) {$(\'#unitId1\').empty().append(data);var ajaxPost2 = $.post(\'./ea-sh-ajax.php\', {\'unitIdForServiceSelection\' : $(\'#unitId1 option:selected\').val(), \'serviceType\' : \'1\'});ajaxPost2.done(function(data) {$(\'#input_service_id1\').empty().append(data);$(\'#numOfService\').load(\'./ea-sh-ajax.php\', {\'unitId4CurrentYearServices\': $(\'#unitId1 option:selected\').val()});});});});});';
            $htmlOut .= '$(\'#centerId1\').change(function() {var ajaxPost1 = $.post(\'../statisticalPlotter/AsynchronousProccess.php\', {\'centerId\' : $(\'#centerId1 option:selected\').val(), "unitType" : "1"});ajaxPost1.done(function(data) {$(\'#unitId1\').empty().append(data);var ajaxPost2 = $.post(\'./ea-sh-ajax.php\', {\'unitIdForServiceSelection\' : $(\'#unitId1 option:selected\').val()});ajaxPost2.done(function(data) {$(\'#input_service_id1\').empty().append(data);$(\'#numOfService\').load(\'./ea-sh-ajax.php\', {\'unitId4CurrentYearServices\': $(\'#unitId1 option:selected\').val(), \'serviceType\' : \'1\'});});});});';
            $htmlOut .= '$(\'#unitId1\').change(function() {var ajaxPost2 = $.post(\'./ea-sh-ajax.php\', {\'unitIdForServiceSelection\' : $(\'#unitId1 option:selected\').val()});ajaxPost2.done(function(data) {$(\'#input_service_id1\').empty().append(data);$(\'#numOfService\').load(\'./ea-sh-ajax.php\', {\'unitId4CurrentYearServices\': $(\'#unitId1 option:selected\').val()});});});';
            //$htmlOut .= '$(\'#input_service_id\').change(function() {$(\'#numOfService\').load(\'./ea-sh-ajax.php\', {\'unitId4CurrentYearServices\': $(\'#unitId option:selected\').val()});});';
            $htmlOut .= '</script>';
            $htmlOut .= '<label for="input_service_frequency1" class="required">بار خدمت:</label>';
            $htmlOut .= '<input type="text" name="input_service_frequency1" id="input_service_frequency1" maxlength="6" dir="ltr"';
            if (isset($_POST['input_service_frequency']) && $_POST['input_service_frequency'] !== '')
                $htmlOut .= ' value="' . htmlspecialchars($_POST['input_service_frequency']) . '"';
            $htmlOut .= ' class="validate required integer';
            if (isset($r['invalid']['input_service_frequency']))
                $htmlOut .= ' invalid';
            $htmlOut .= '" />';
            $htmlOut .= '<span class="description"';
            if (!isset($r['invalid']['input_service_frequency']))
                $htmlOut .= ' style="display:none;"';
            $htmlOut .= '>عدد صحیح، حداقل بزرگتر از 0</span>';
            $htmlOut .= '<script type="text/javascript">$(\'#input_service_frequency1\').data(\'num_min\',0).data(\'num_min_inc\',false);</script><br />';
            $htmlOut .= '<label for="start_date:d1" class="required">از تاریخ:</label>';
            $htmlOut .= '<div id="date_picker_start_1" class="date_picker required';
            if (isset($r['invalid']['start_date']))
                $htmlOut .= ' invalid';
            $htmlOut .= '">';
            $htmlOut .= '<select name="start_date:d1" id="start_date:d1" class="date_d">';
            $htmlOut .= '<option value="0">روز</option><option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option><option value="5">5</option><option value="6">6</option><option value="7">7</option><option value="8">8</option><option value="9">9</option><option value="10">10</option><option value="11">11</option><option value="12">12</option><option value="13">13</option><option value="14">14</option><option value="15">15</option><option value="16">16</option><option value="17">17</option><option value="18">18</option><option value="19">19</option><option value="20">20</option><option value="21">21</option><option value="22">22</option><option value="23">23</option><option value="24">24</option><option value="25">25</option><option value="26">26</option><option value="27">27</option><option value="28">28</option><option value="29">29</option><option value="30">30</option><option value="31">31</option></select>';
            $htmlOut .= '<select name="start_date:m1" class="date_m">';
            $htmlOut .= '<option value="0">ماه</option><option value="1">فروردین</option><option value="2">اردیبهشت</option><option value="3">خرداد</option><option value="4">تیر</option><option value="5">مرداد</option><option value="6">شهریور</option><option value="7">مهر</option><option value="8">آبان</option><option value="9">آذر</option><option value="10">دی</option><option value="11">بهمن</option><option value="12">اسفند</option></select>';
            $htmlOut .= '<input type="text" name="start_date:y1" size="4" maxlength="4" class="date_y"';
            if (isset($_POST['start_date:y1']) && $_POST['start_date:y1'] !== '')
                $htmlOut .= ' value="' . htmlspecialchars($_POST['start_date:y1']) . '"';
            $htmlOut .= ' /><span class="description">باید بعد از آخرین تاریخ باشد.</span></div><br />';
            $htmlOut .= '<label for="finish_date:d1" class="required">تا تاریخ:</label>';
            $htmlOut .= '<div id="date_picker_finish_1" class="date_picker required';
            if (isset($r['invalid']['finish_date']))
                $htmlOut .= ' invalid';
            $htmlOut .= '">';
            $htmlOut .= '<select name="finish_date:d1" id="finish_date:d1" class="date_d">';
            $htmlOut .= '<option value="0">روز</option><option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option><option value="5">5</option><option value="6">6</option><option value="7">7</option><option value="8">8</option><option value="9">9</option><option value="10">10</option><option value="11">11</option><option value="12">12</option><option value="13">13</option><option value="14">14</option><option value="15">15</option><option value="16">16</option><option value="17">17</option><option value="18">18</option><option value="19">19</option><option value="20">20</option><option value="21">21</option><option value="22">22</option><option value="23">23</option><option value="24">24</option><option value="25">25</option><option value="26">26</option><option value="27">27</option><option value="28">28</option><option value="29">29</option><option value="30">30</option><option value="31">31</option></select>';
            $htmlOut .= '<select name="finish_date:m1" class="date_m">';
            $htmlOut .= '<option value="0">ماه</option><option value="1">فروردین</option><option value="2">اردیبهشت</option><option value="3">خرداد</option><option value="4">تیر</option><option value="5">مرداد</option><option value="6">شهریور</option><option value="7">مهر</option><option value="8">آبان</option><option value="9">آذر</option><option value="10">دی</option><option value="11">بهمن</option><option value="12">اسفند</option></select>';
            $htmlOut .= '<input type="text" name="finish_date:y1" size="4" maxlength="4" class="date_y"';
            if (isset($_POST['finish_date:y1']) && $_POST['finish_date:y1'] !== '')
                $htmlOut .= ' value="' . htmlspecialchars($_POST['finish_date:y1']) . '"';
            $htmlOut .= ' /><span class="description">باید بعد از آخرین تاریخ و تاریخ شروع باشد.</span></div><br />';
            $htmlOut .= '</div>'; //end new_service_1


            $htmlOut .= '<div id="numOfService"></div>';

            $htmlOut .= '<input type="submit" name="submitRegisterDoneServices" value="ثبت" /></form>';
            $htmlOut .= '</div>'; //end .mngDiv1

			$htmlOut .= '</div>';//end tab-1

			/**************************************************************************************************************************/

			$htmlOut .= '<div id="tabs-2">';
			$htmlOut .= '<div class="loading"><img src="../statisticalPlotter/loading.gif" />&nbsp;&nbsp;در حال بارگذاری؛ لطفا صبر کنید...</div><br />';
			$htmlOut .= '<div id="mngDiv2"><form action="' . substr(strrchr($_SERVER['PHP_SELF'], '/'), 1) . '" method="post" class="narin"><fieldset><legend>ثبت بار خدمت</legend>';
			$htmlOut .= '<input type="hidden" name="narin:token" value="' . Narin::create_token() . '" />';
			$htmlOut .= '<br /><label for="townNameForHygieneUnit" class="required">نام شهرستان:</label>';
			$htmlOut .= '<select name="townNameForHygieneUnit" id="townNameForHygieneUnit" class="validate">';
			$res = Database::execute_query("SELECT * FROM `towns` ORDER BY `name` ASC;");
			if (Database::num_of_rows($res) > 0) {
				while ($row = Database::get_assoc_array($res))
					$htmlOut .= '<option value="' . $row['id'] . '">' . $row['name'] . '</option>';
			} else
				$htmlOut .= '<option>' . NO_TOWN_EXISTS . '</option>';
			$htmlOut .= '</select><br />';
			$htmlOut .= '<label for="centerId_1" class="required">نام مرکز:</label>';
			$htmlOut .= '<select name="centerId_1" id="centerId_1" class="validate first_opt_not_allowed"></select><br />';
			$htmlOut .= '<label for="hygieneUnitId" class="required">نام خانه بهداشت:</label>';
			$htmlOut .= '<select name="hygieneUnitId" id="hygieneUnitId" class="validate first_opt_not_allowed"></select><br />';
			$htmlOut .= '<label for="input_service_id_1" class="required">نام خدمت:</label>';
            /*$htmlOut .= '<input id="input_service_id_1" name="input_service_id_1" list="services-list2" title="انتخاب کنید..." class="validate required ';
            if (isset($r['invalid']['input_service_id_1']))
                $htmlOut .= 'invalid';
            $htmlOut .= '">';
            $htmlOut .= '<datalist id="services-list2"></datalist><br>';*/
			$htmlOut .= '<select name="input_service_id_1" id="input_service_id_1" class="validate first_opt_not_allowed"></select><br />';
			$htmlOut .= '<script type="text/javascript">';
			$htmlOut .= '$(document).ajaxStart(function() {$(\'.loading\').show();$(\'#mngDiv2\').css(\'opacity\', \'0.3\');});' . '$(document).ajaxSuccess(function() {$(\'.loading\').hide();$(\'#mngDiv2\').css(\'opacity\', \'1\');});';
			$htmlOut .= 'var ajaxPost = $.post(\'../statisticalPlotter/AsynchronousProccess.php\', {\'townId\' : $(\'#townNameForHygieneUnit option:selected\').val()});ajaxPost.done(function(data) {$(\'#centerId_1\').empty().append(data);var ajaxPost1 = $.post(\'../statisticalPlotter/AsynchronousProccess.php\', {\'centerIdForHygieneUnitSelection\' : $(\'#centerId_1 option:selected\').val()});ajaxPost1.done(function(data) {$(\'#hygieneUnitId\').empty().append(data);var ajaxPost2 = $.post(\'./ea-sh-ajax.php\', {\'hygieneUnitIdForServiceSelection\' : $(\'#hygieneUnitId option:selected\').val()});ajaxPost2.done(function(data) {$(\'#input_service_id_1\').empty().append(data);$(\'#numOfHGUnitService\').load(\'./ea-sh-ajax.php\', {\'HGunitId4CurrentYearServices\': $(\'#hygieneUnitId option:selected\').val()});});});});';
			$htmlOut .= '$(\'#townNameForHygieneUnit\').change(function() {var ajaxPost = $.post(\'../statisticalPlotter/AsynchronousProccess.php\', {\'townId\' : $(\'#townNameForHygieneUnit option:selected\').val()});ajaxPost.done(function(data) {$(\'#centerId_1\').empty().append(data);var ajaxPost1 = $.post(\'../statisticalPlotter/AsynchronousProccess.php\', {\'centerIdForHygieneUnitSelection\' : $(\'#centerId_1 option:selected\').val()});ajaxPost1.done(function(data) {$(\'#hygieneUnitId\').empty().append(data);var ajaxPost2 = $.post(\'./ea-sh-ajax.php\', {\'hygieneUnitIdForServiceSelection\' : $(\'#hygieneUnitId option:selected\').val()});ajaxPost2.done(function(data) {$(\'#input_service_id_1\').empty().append(data);$(\'#numOfHGUnitService\').load(\'./ea-sh-ajax.php\', {\'HGunitId4CurrentYearServices\': $(\'#hygieneUnitId option:selected\').val()});});});});});';
			$htmlOut .= '$(\'#centerId_1\').change(function() {var ajaxPost1 = $.post(\'../statisticalPlotter/AsynchronousProccess.php\', {\'centerIdForHygieneUnitSelection\' : $(\'#centerId_1 option:selected\').val()});ajaxPost1.done(function(data) {$(\'#hygieneUnitId\').empty().append(data);var ajaxPost2 = $.post(\'./ea-sh-ajax.php\', {\'hygieneUnitIdForServiceSelection\' : $(\'#hygieneUnitId option:selected\').val()});ajaxPost2.done(function(data) {$(\'#input_service_id_1\').empty().append(data);$(\'#numOfHGUnitService\').load(\'./ea-sh-ajax.php\', {\'HGunitId4CurrentYearServices\': $(\'#hygieneUnitId option:selected\').val()});});});});';
			$htmlOut .= '$(\'#hygieneUnitId\').change(function() {var ajaxPost2 = $.post(\'./ea-sh-ajax.php\', {\'hygieneUnitIdForServiceSelection\' : $(\'#hygieneUnitId option:selected\').val()});ajaxPost2.done(function(data) {$(\'#input_service_id_1\').empty().append(data);$(\'#numOfHGUnitService\').load(\'./ea-sh-ajax.php\', {\'HGunitId4CurrentYearServices\': $(\'#hygieneUnitId option:selected\').val()});});});';
			//$htmlOut .= '$(\'#input_service_id_1\').change(function() {$(\'#numOfHGUnitService\').load(\'./ea-sh-ajax.php\', {\'HGunitId4CurrentYearServices\': $(\'#hygieneUnitId option:selected\').val()});});';
			$htmlOut .= '</script>';
			$htmlOut .= '<label for="input_service_frequency" class="required">بار خدمت:</label>';
			$htmlOut .= '<input type="text" name="input_service_frequency" id="input_service_frequency" maxlength="6" dir="ltr"';
			if (isset($_POST['input_service_frequency']) && $_POST['input_service_frequency'] !== '')
				$htmlOut .= ' value="' . htmlspecialchars($_POST['input_service_frequency']) . '"';
			$htmlOut .= ' class="validate required integer';
			if (isset($r['invalid']['input_service_frequency']))
				$htmlOut .= ' invalid';
			$htmlOut .= '" />';
			$htmlOut .= '<span class="description"';
			if (!isset($r['invalid']['input_service_frequency']))
				$htmlOut .= ' style="display:none;"';
			$htmlOut .= '>عدد صحیح، حداقل بزرگتر از 0</span>';
			$htmlOut .= '<script type="text/javascript">$(\'#input_service_frequency\').data(\'num_min\',0).data(\'num_min_inc\',false);</script><br />';
			$htmlOut .= '<label for="start_date:d" class="required">از تاریخ:</label>';
			$htmlOut .= '<div class="date_picker required';
			if (isset($r['invalid']['start_date']))
				$htmlOut .= ' invalid';
			$htmlOut .= '">';
			$htmlOut .= '<select name="start_date:d" id="start_date:d" class="date_d">';
			$htmlOut .= '<option value="0">روز</option><option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option><option value="5">5</option><option value="6">6</option><option value="7">7</option><option value="8">8</option><option value="9">9</option><option value="10">10</option><option value="11">11</option><option value="12">12</option><option value="13">13</option><option value="14">14</option><option value="15">15</option><option value="16">16</option><option value="17">17</option><option value="18">18</option><option value="19">19</option><option value="20">20</option><option value="21">21</option><option value="22">22</option><option value="23">23</option><option value="24">24</option><option value="25">25</option><option value="26">26</option><option value="27">27</option><option value="28">28</option><option value="29">29</option><option value="30">30</option><option value="31">31</option></select>';			
			$htmlOut .= '<select name="start_date:m" class="date_m">';
			$htmlOut .= '<option value="0">ماه</option><option value="1">فروردین</option><option value="2">اردیبهشت</option><option value="3">خرداد</option><option value="4">تیر</option><option value="5">مرداد</option><option value="6">شهریور</option><option value="7">مهر</option><option value="8">آبان</option><option value="9">آذر</option><option value="10">دی</option><option value="11">بهمن</option><option value="12">اسفند</option></select>';
			$htmlOut .= '<input type="text" name="start_date:y" size="4" maxlength="4" class="date_y"';
			if (isset($_POST['start_date:y']) && $_POST['start_date:y'] !== '')
				$htmlOut .= ' value="' . htmlspecialchars($_POST['start_date:y']) . '"';
			$htmlOut .= ' /><span class="description">باید بعد از آخرین تاریخ باشد.</span></div><br />';
			$htmlOut .= '<label for="finish_date:d" class="required">تا تاریخ:</label>';
			$htmlOut .= '<div class="date_picker required';
			if (isset($r['invalid']['finish_date']))
				$htmlOut .= ' invalid';
			$htmlOut .= '">';
			$htmlOut .= '<select name="finish_date:d" id="finish_date:d" class="date_d">';
			$htmlOut .= '<option value="0">روز</option><option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option><option value="5">5</option><option value="6">6</option><option value="7">7</option><option value="8">8</option><option value="9">9</option><option value="10">10</option><option value="11">11</option><option value="12">12</option><option value="13">13</option><option value="14">14</option><option value="15">15</option><option value="16">16</option><option value="17">17</option><option value="18">18</option><option value="19">19</option><option value="20">20</option><option value="21">21</option><option value="22">22</option><option value="23">23</option><option value="24">24</option><option value="25">25</option><option value="26">26</option><option value="27">27</option><option value="28">28</option><option value="29">29</option><option value="30">30</option><option value="31">31</option></select>';
			$htmlOut .= '<select name="finish_date:m" class="date_m">';
			$htmlOut .= '<option value="0">ماه</option><option value="1">فروردین</option><option value="2">اردیبهشت</option><option value="3">خرداد</option><option value="4">تیر</option><option value="5">مرداد</option><option value="6">شهریور</option><option value="7">مهر</option><option value="8">آبان</option><option value="9">آذر</option><option value="10">دی</option><option value="11">بهمن</option><option value="12">اسفند</option></select>';
			$htmlOut .= '<input type="text" name="finish_date:y" size="4" maxlength="4" class="date_y"';
			if (isset($_POST['finish_date:y']) && $_POST['finish_date:y'] !== '')
				$htmlOut .= ' value="' . htmlspecialchars($_POST['finish_date:y']) . '"';
			$htmlOut .= ' /><span class="description">باید بعد از آخرین تاریخ و تاریخ شروع باشد.</span></div><br />';
			$htmlOut .= '<input type="submit" name="submitRegisterDoneServices" value="ثبت" /></fieldset></form></div>';
			$htmlOut .= '<div id="numOfHGUnitService"></div>';
			$htmlOut .= '</div>';
			$htmlOut .= '</div>';//end tab-2
			//end whole tabs
			
		} elseif ($_SESSION['user']['acl'] == 'Town Author') {
			$htmlOut .= '<p>در این بخش می توانید بار خدمات را در سطح شهرستان وارد نمایید.</p>';
			$htmlOut .= '<div id="tabs"><ul><li><a href="#tabs-1">واحدها</a></li><li><a href="#tabs-2">خانه های بهداشت</a></li></ul>';
			$htmlOut .= '<div id="tabs-1">';
            $htmlOut .= '<div class="loading"><img src="../statisticalPlotter/loading.gif" />&nbsp;&nbsp;در حال بارگذاری؛ لطفا صبر کنید...</div><br />';
            $htmlOut .= '<div id="mngDiv1" style="text-align: right;"><form action="' . substr(strrchr($_SERVER['PHP_SELF'], '/'), 1) . '" method="post" class="narin">';
            $htmlOut .= '<input type="hidden" name="narin:token" value="' . Narin::create_token() . '" />';
            $htmlOut .= '<p style="font-weight: bold">گام اول: انتخاب نوع واحد</p>';
            $htmlOut .= '<div><input type="radio" name="unit_type" id="unit_type_0" value="0"><label for="unit_type_0">عمومی</label>';
            $htmlOut .= '<input type="radio" name="unit_type" id="unit_type_1" value="1" style="margin-right: 10px;"><label for="unit_type_1">سیب</label></div>';
            $htmlOut .= '<script type="text/javascript">';
            $htmlOut .= '$(\'input[name="unit_type"]\').change(function() {';
            $htmlOut .= 'var value = this.value;';
            $htmlOut .= 'if(value === "0") {';
            $htmlOut .= '$("#centerId0").addClass("validate");';
            $htmlOut .= '$("#centerId1").removeClass("validate");';
            $htmlOut .= '$("#unitId0").addClass("validate");';
            $htmlOut .= '$("#unitId1").removeClass("validate");';
            $htmlOut .= '$("#input_service_id0").addClass("validate");';
            $htmlOut .= '$("#input_service_id1").removeClass("validate");';
            $htmlOut .= '$("#input_service_frequency0").addClass("required");';
            $htmlOut .= '$("#input_service_frequency1").removeClass("required");';
            $htmlOut .= '$("#date_picker_start_0").addClass("required");';
            $htmlOut .= '$("#date_picker_finish_0").addClass("required");';
            $htmlOut .= '$("#date_picker_start_1").removeClass("required");';
            $htmlOut .= '$("#date_picker_finish_1").removeClass("required");';
            $htmlOut .= '$("#new_sevice_1").hide();';
            $htmlOut .= '$("#new_sevice_0").show(); }';
            $htmlOut .= 'if(value === "1") {';
            $htmlOut .= '$("#centerId1").addClass("validate");';
            $htmlOut .= '$("#centerId0").removeClass("validate");';
            $htmlOut .= '$("#unitId1").addClass("validate");';
            $htmlOut .= '$("#unitId0").removeClass("validate");';
            $htmlOut .= '$("#input_service_id1").addClass("validate");';
            $htmlOut .= '$("#input_service_id0").removeClass("validate");';
            $htmlOut .= '$("#input_service_frequency1").addClass("required");';
            $htmlOut .= '$("#input_service_frequency0").removeClass("required");';
            $htmlOut .= '$("#date_picker_start_1").addClass("required");';
            $htmlOut .= '$("#date_picker_finish_1").addClass("required");';
            $htmlOut .= '$("#date_picker_start_0").removeClass("required");';
            $htmlOut .= '$("#date_picker_finish_0").removeClass("required");';
            $htmlOut .= '$("#new_sevice_0").hide();';
            $htmlOut .= '$("#new_sevice_1").show(); }';
            $htmlOut .= '});</script>';

            $htmlOut .= '<div id="new_sevice_0" style="display: none">';
            $htmlOut .= '<p style="font-weight: bold">گام دوم: وارد کردن بار خدمت عمومی</p>';
            $htmlOut .= '<label for="centerId0" class="required">نام مرکز:</label>';
            $htmlOut .= '<select name="centerId0" id="centerId0" class="validate first_opt_not_allowed"></select><br />';
            $htmlOut .= '<label for="unitId0" class="required">نام واحد:</label>';
            $htmlOut .= '<select name="unitId0" id="unitId0" class="validate first_opt_not_allowed"></select><br />';
            $htmlOut .= '<label for="input_service_id0" class="required">نام خدمت:</label>';
            /*$htmlOut .= '<input id="input_service_id0" name="input_service_id0" list="services-list0" title="انتخاب کنید..." class="validate required ';
            if (isset($r['invalid']['input_service_id0']))
                $htmlOut .= 'invalid';
            $htmlOut .= '">';
            $htmlOut .= '<datalist id="services-list0"></datalist><br>';*/
            $htmlOut .= '<select name="input_service_id0" id="input_service_id0" class="validate first_opt_not_allowed"></select><br />';
            $htmlOut .= '<script type="text/javascript">';
            $htmlOut .= '$(document).ajaxStart(function() {$(\'.loading\').show();$(\'#mngDiv1\').css(\'opacity\', \'0.3\');});' . '$(document).ajaxSuccess(function() {$(\'.loading\').hide();$(\'#mngDiv1\').css(\'opacity\', \'1\');});';
            $htmlOut .= 'var ajaxPost = $.post(\'../statisticalPlotter/AsynchronousProccess.php\', {\'townId\' : ' . $_SESSION['user']['acl-id'] . '});ajaxPost.done(function(data) {$(\'#centerId0\').empty().append(data);var ajaxPost1 = $.post(\'../statisticalPlotter/AsynchronousProccess.php\', {\'centerId\' : $(\'#centerId0 option:selected\').val(), \'unitType\' : \'0\'});ajaxPost1.done(function(data) {$(\'#unitId0\').empty().append(data);var ajaxPost2 = $.post(\'./ea-sh-ajax.php\', {\'unitIdForServiceSelection\' : $(\'#unitId0 option:selected\').val()});ajaxPost2.done(function(data) {$(\'#input_service_id0\').empty().append(data);$(\'#numOfUnitService\').load(\'./ea-sh-ajax.php\', {\'unitId4CurrentYearServices\': $(\'#unitId0 option:selected\').val()});});});});';
            $htmlOut .= '$(\'#centerId0\').change(function() {var ajaxPost1 = $.post(\'../statisticalPlotter/AsynchronousProccess.php\', {\'centerId\' : $(\'#centerId0 option:selected\').val(), \'unitType\' : \'0\'});ajaxPost1.done(function(data) {$(\'#unitId0\').empty().append(data);var ajaxPost2 = $.post(\'./ea-sh-ajax.php\', {\'unitIdForServiceSelection\' : $(\'#unitId0 option:selected\').val()});ajaxPost2.done(function(data) {$(\'#input_service_id0\').empty().append(data);$(\'#numOfUnitService\').load(\'./ea-sh-ajax.php\', {\'unitId4CurrentYearServices\': $(\'#unitId0 option:selected\').val()});});});});';
            $htmlOut .= '$(\'#unitId0\').change(function() {var ajaxPost2 = $.post(\'./ea-sh-ajax.php\', {\'unitIdForServiceSelection\' : $(\'#unitId0 option:selected\').val()});ajaxPost2.done(function(data) {$(\'#input_service_id0\').empty().append(data);$(\'#numOfUnitService\').load(\'./ea-sh-ajax.php\', {\'unitId4CurrentYearServices\': $(\'#unitId0 option:selected\').val()});});});';
            //$htmlOut .= '$(\'#input_service_id\').change(function() {$(\'#numOfUnitService\').load(\'./ea-sh-ajax.php\', {\'unitId4CurrentYearServices\': $(\'#unitId option:selected\').val()});});';
            $htmlOut .= '</script>';
            $htmlOut .= '<label for="input_service_frequency0" class="required">بار خدمت:</label>';
            $htmlOut .= '<input type="text" name="input_service_frequency0" id="input_service_frequency0" maxlength="6" dir="ltr"';
            if (isset($_POST['input_service_frequency0']) && $_POST['input_service_frequency0'] !== '')
                $htmlOut .= ' value="' . htmlspecialchars($_POST['input_service_frequency0']) . '"';
            $htmlOut .= ' class="validate required integer';
            if (isset($r['invalid']['input_service_frequency0']))
                $htmlOut .= ' invalid';
            $htmlOut .= '" />';
            $htmlOut .= '<span class="description"';
            if (!isset($r['invalid']['input_service_frequency0']))
                $htmlOut .= ' style="display:none;"';
            $htmlOut .= '>عدد صحیح، حداقل بزرگتر از 0</span>';
            $htmlOut .= '<script type="text/javascript">$(\'#input_service_frequency0\').data(\'num_min\',0).data(\'num_min_inc\',false);</script><br />';
            $htmlOut .= '<label for="start_date:d0" class="required">از تاریخ:</label>';
            $htmlOut .= '<div id="date_picker_start_0" class="date_picker required';
            if (isset($r['invalid']['start_date']))
                $htmlOut .= ' invalid';
            $htmlOut .= '">';
            $htmlOut .= '<select name="start_date:d0" id="start_date:d" class="date_d">';
            $htmlOut .= '<option value="0">روز</option><option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option><option value="5">5</option><option value="6">6</option><option value="7">7</option><option value="8">8</option><option value="9">9</option><option value="10">10</option><option value="11">11</option><option value="12">12</option><option value="13">13</option><option value="14">14</option><option value="15">15</option><option value="16">16</option><option value="17">17</option><option value="18">18</option><option value="19">19</option><option value="20">20</option><option value="21">21</option><option value="22">22</option><option value="23">23</option><option value="24">24</option><option value="25">25</option><option value="26">26</option><option value="27">27</option><option value="28">28</option><option value="29">29</option><option value="30">30</option><option value="31">31</option></select>';
            $htmlOut .= '<select name="start_date:m0" class="date_m">';
            $htmlOut .= '<option value="0">ماه</option><option value="1">فروردین</option><option value="2">اردیبهشت</option><option value="3">خرداد</option><option value="4">تیر</option><option value="5">مرداد</option><option value="6">شهریور</option><option value="7">مهر</option><option value="8">آبان</option><option value="9">آذر</option><option value="10">دی</option><option value="11">بهمن</option><option value="12">اسفند</option></select>';
            $htmlOut .= '<input type="text" name="start_date:y0" size="4" maxlength="4" class="date_y"';
            if (isset($_POST['start_date:y0']) && $_POST['start_date:y0'] !== '')
                $htmlOut .= ' value="' . htmlspecialchars($_POST['start_date:y']) . '"';
            $htmlOut .= ' /><span class="description">باید بعد از آخرین تاریخ باشد.</span></div><br />';
            $htmlOut .= '<label for="finish_date:d0" class="required">تا تاریخ:</label>';
            $htmlOut .= '<div id="date_picker_finish_0" class="date_picker required';
            if (isset($r['invalid']['finish_date0']))
                $htmlOut .= ' invalid';
            $htmlOut .= '">';
            $htmlOut .= '<select name="finish_date:d0" id="finish_date:d" class="date_d">';
            $htmlOut .= '<option value="0">روز</option><option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option><option value="5">5</option><option value="6">6</option><option value="7">7</option><option value="8">8</option><option value="9">9</option><option value="10">10</option><option value="11">11</option><option value="12">12</option><option value="13">13</option><option value="14">14</option><option value="15">15</option><option value="16">16</option><option value="17">17</option><option value="18">18</option><option value="19">19</option><option value="20">20</option><option value="21">21</option><option value="22">22</option><option value="23">23</option><option value="24">24</option><option value="25">25</option><option value="26">26</option><option value="27">27</option><option value="28">28</option><option value="29">29</option><option value="30">30</option><option value="31">31</option></select>';
            $htmlOut .= '<select name="finish_date:m0" class="date_m">';
            $htmlOut .= '<option value="0">ماه</option><option value="1">فروردین</option><option value="2">اردیبهشت</option><option value="3">خرداد</option><option value="4">تیر</option><option value="5">مرداد</option><option value="6">شهریور</option><option value="7">مهر</option><option value="8">آبان</option><option value="9">آذر</option><option value="10">دی</option><option value="11">بهمن</option><option value="12">اسفند</option></select>';
            $htmlOut .= '<input type="text" name="finish_date:y0" size="4" maxlength="4" class="date_y"';
            if (isset($_POST['finish_date:y0']) && $_POST['finish_date:y0'] !== '')
                $htmlOut .= ' value="' . htmlspecialchars($_POST['finish_date:y0']) . '"';
            $htmlOut .= ' /><span class="description">باید بعد از آخرین تاریخ و تاریخ شروع باشد.</span></div><br />';
            $htmlOut .= '</div>'; //new_service_0 end


            $htmlOut .= '<div id="new_sevice_1" style="display: none">';
            $htmlOut .= '<p style="font-weight: bold">گام دوم: وارد کردن بار خدمت سیب</p>';
            $htmlOut .= '<label for="centerId1" class="required">نام مرکز:</label>';
            $htmlOut .= '<select name="centerId1" id="centerId1" class="validate first_opt_not_allowed"></select><br />';
            $htmlOut .= '<label for="unitId1" class="required">نام واحد:</label>';
            $htmlOut .= '<select name="unitId1" id="unitId1" class="validate first_opt_not_allowed"></select><br />';
            $htmlOut .= '<label for="input_service_id1">نام خدمت:</label>';
            /*$htmlOut .= '<input id="input_service_id1" name="input_service_id1" list="services-list1" title="انتخاب کنید..." class="validate required ';
            if (isset($r['invalid']['input_service_id1']))
                $htmlOut .= 'invalid';
            $htmlOut .= '">';
            $htmlOut .= '<datalist id="services-list1"></datalist><br>';*/
            $htmlOut .= '<select name="input_service_id1" id="input_service_id1" class="validate first_opt_not_allowed"></select><br />';
            $htmlOut .= '<script type="text/javascript">';
            $htmlOut .= '$(document).ajaxStart(function() {$(\'.loading\').show();$(\'#mngDiv1\').css(\'opacity\', \'0.3\');});' . '$(document).ajaxSuccess(function() {$(\'.loading\').hide();$(\'#mngDiv1\').css(\'opacity\', \'1\');});';
            $htmlOut .= 'var ajaxPost = $.post(\'../statisticalPlotter/AsynchronousProccess.php\', {\'townId\' : ' . $_SESSION['user']['acl-id'] . '});ajaxPost.done(function(data) {$(\'#centerId1\').empty().append(data);var ajaxPost1 = $.post(\'../statisticalPlotter/AsynchronousProccess.php\', {\'centerId\' : $(\'#centerId1 option:selected\').val(), \'unitType\' : \'1\'});ajaxPost1.done(function(data) {$(\'#unitId1\').empty().append(data);var ajaxPost2 = $.post(\'./ea-sh-ajax.php\', {\'unitIdForServiceSelection\' : $(\'#unitId1 option:selected\').val()});ajaxPost2.done(function(data) {$(\'#input_service_id1\').empty().append(data);$(\'#numOfUnitService\').load(\'./ea-sh-ajax.php\', {\'unitId4CurrentYearServices\': $(\'#unitId1 option:selected\').val()});});});});';
            $htmlOut .= '$(\'#centerId1\').change(function() {var ajaxPost1 = $.post(\'../statisticalPlotter/AsynchronousProccess.php\', {\'centerId\' : $(\'#centerId1 option:selected\').val(), \'unitType\' : \'1\'});ajaxPost1.done(function(data) {$(\'#unitId1\').empty().append(data);var ajaxPost2 = $.post(\'./ea-sh-ajax.php\', {\'unitIdForServiceSelection\' : $(\'#unitId1 option:selected\').val()});ajaxPost2.done(function(data) {$(\'#input_service_id1\').empty().append(data);$(\'#numOfUnitService\').load(\'./ea-sh-ajax.php\', {\'unitId4CurrentYearServices\': $(\'#unitId1 option:selected\').val()});});});});';
            $htmlOut .= '$(\'#unitId1\').change(function() {var ajaxPost2 = $.post(\'./ea-sh-ajax.php\', {\'unitIdForServiceSelection\' : $(\'#unitId1 option:selected\').val()});ajaxPost2.done(function(data) {$(\'#input_service_id1\').empty().append(data);$(\'#numOfUnitService\').load(\'./ea-sh-ajax.php\', {\'unitId4CurrentYearServices\': $(\'#unitId1 option:selected\').val()});});});';
            //$htmlOut .= '$(\'#input_service_id\').change(function() {$(\'#numOfUnitService\').load(\'./ea-sh-ajax.php\', {\'unitId4CurrentYearServices\': $(\'#unitId option:selected\').val()});});';
            $htmlOut .= '</script>';
            $htmlOut .= '<label for="input_service_frequency1" class="required">بار خدمت:</label>';
            $htmlOut .= '<input type="text" name="input_service_frequency1" id="input_service_frequency1" maxlength="6" dir="ltr"';
            if (isset($_POST['input_service_frequency1']) && $_POST['input_service_frequency1'] !== '')
                $htmlOut .= ' value="' . htmlspecialchars($_POST['input_service_frequency1']) . '"';
            $htmlOut .= ' class="validate required integer';
            if (isset($r['invalid']['input_service_frequency1']))
                $htmlOut .= ' invalid';
            $htmlOut .= '" />';
            $htmlOut .= '<span class="description"';
            if (!isset($r['invalid']['input_service_frequency1']))
                $htmlOut .= ' style="display:none;"';
            $htmlOut .= '>عدد صحیح، حداقل بزرگتر از 0</span>';
            $htmlOut .= '<script type="text/javascript">$(\'#input_service_frequency1\').data(\'num_min\',0).data(\'num_min_inc\',false);</script><br />';
            $htmlOut .= '<label for="start_date:d" class="required">از تاریخ:</label>';
            $htmlOut .= '<div id="date_picker_start_1" class="date_picker required';
            if (isset($r['invalid']['start_date1']))
                $htmlOut .= ' invalid';
            $htmlOut .= '">';
            $htmlOut .= '<select name="start_date:d1" id="start_date:d" class="date_d">';
            $htmlOut .= '<option value="0">روز</option><option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option><option value="5">5</option><option value="6">6</option><option value="7">7</option><option value="8">8</option><option value="9">9</option><option value="10">10</option><option value="11">11</option><option value="12">12</option><option value="13">13</option><option value="14">14</option><option value="15">15</option><option value="16">16</option><option value="17">17</option><option value="18">18</option><option value="19">19</option><option value="20">20</option><option value="21">21</option><option value="22">22</option><option value="23">23</option><option value="24">24</option><option value="25">25</option><option value="26">26</option><option value="27">27</option><option value="28">28</option><option value="29">29</option><option value="30">30</option><option value="31">31</option></select>';
            $htmlOut .= '<select name="start_date:m1" class="date_m">';
            $htmlOut .= '<option value="0">ماه</option><option value="1">فروردین</option><option value="2">اردیبهشت</option><option value="3">خرداد</option><option value="4">تیر</option><option value="5">مرداد</option><option value="6">شهریور</option><option value="7">مهر</option><option value="8">آبان</option><option value="9">آذر</option><option value="10">دی</option><option value="11">بهمن</option><option value="12">اسفند</option></select>';
            $htmlOut .= '<input type="text" name="start_date:y1" size="4" maxlength="4" class="date_y"';
            if (isset($_POST['start_date:y1']) && $_POST['start_date:y1'] !== '')
                $htmlOut .= ' value="' . htmlspecialchars($_POST['start_date:y1']) . '"';
            $htmlOut .= ' /><span class="description">باید بعد از آخرین تاریخ باشد.</span></div><br />';
            $htmlOut .= '<label for="finish_date:d1" class="required">تا تاریخ:</label>';
            $htmlOut .= '<div id="date_picker_finish_1" class="date_picker required';
            if (isset($r['invalid']['finish_date1']))
                $htmlOut .= ' invalid';
            $htmlOut .= '">';
            $htmlOut .= '<select name="finish_date:d1" id="finish_date:d" class="date_d">';
            $htmlOut .= '<option value="0">روز</option><option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option><option value="5">5</option><option value="6">6</option><option value="7">7</option><option value="8">8</option><option value="9">9</option><option value="10">10</option><option value="11">11</option><option value="12">12</option><option value="13">13</option><option value="14">14</option><option value="15">15</option><option value="16">16</option><option value="17">17</option><option value="18">18</option><option value="19">19</option><option value="20">20</option><option value="21">21</option><option value="22">22</option><option value="23">23</option><option value="24">24</option><option value="25">25</option><option value="26">26</option><option value="27">27</option><option value="28">28</option><option value="29">29</option><option value="30">30</option><option value="31">31</option></select>';
            $htmlOut .= '<select name="finish_date:m1" class="date_m">';
            $htmlOut .= '<option value="0">ماه</option><option value="1">فروردین</option><option value="2">اردیبهشت</option><option value="3">خرداد</option><option value="4">تیر</option><option value="5">مرداد</option><option value="6">شهریور</option><option value="7">مهر</option><option value="8">آبان</option><option value="9">آذر</option><option value="10">دی</option><option value="11">بهمن</option><option value="12">اسفند</option></select>';
            $htmlOut .= '<input type="text" name="finish_date:y1" size="4" maxlength="4" class="date_y"';
            if (isset($_POST['finish_date:y1']) && $_POST['finish_date:y1'] !== '')
                $htmlOut .= ' value="' . htmlspecialchars($_POST['finish_date:y1']) . '"';
            $htmlOut .= ' /><span class="description">باید بعد از آخرین تاریخ و تاریخ شروع باشد.</span></div><br />';
            $htmlOut .= '</div>'; //new_service_1 end


            $htmlOut .= '<input type="submit" name="submitRegisterDoneServices" value="ثبت" /></fieldset></form>';
            $htmlOut .= '<div id="numOfUnitService"></div>';
			$htmlOut .= '</div></div>';

			/**************************************************************************************************************************/

			$htmlOut .= '<div id="tabs-2">';
			$htmlOut .= '<div class="loading"><img src="../statisticalPlotter/loading.gif" />&nbsp;&nbsp;در حال بارگذاری؛ لطفا صبر کنید...</div><br />';
			$htmlOut .= '<div id="mngDiv2"><form action="' . substr(strrchr($_SERVER['PHP_SELF'], '/'), 1) . '" method="post" class="narin"><fieldset><legend>ثبت بار خدمت</legend>';
			$htmlOut .= '<input type="hidden" name="narin:token" value="' . Narin::create_token() . '" />';
			$htmlOut .= '<label for="centerId_1" class="required">نام مرکز:</label>';
			$htmlOut .= '<select name="centerId_1" id="centerId_1" class="validate first_opt_not_allowed"></select><br />';
			$htmlOut .= '<label for="hygieneUnitId" class="required">نام خانه بهداشت:</label>';
			$htmlOut .= '<select name="hygieneUnitId" id="hygieneUnitId" class="validate first_opt_not_allowed"></select><br />';
			$htmlOut .= '<label for="input_service_id_1" class="required">نام خدمت:</label>';
            /*$htmlOut .= '<input id="input_service_id_1" name="input_service_id_1" list="services-list2" title="انتخاب کنید..." class="validate required ';
            if (isset($r['invalid']['input_service_id_1']))
                $htmlOut .= 'invalid';
            $htmlOut .= '">';
            $htmlOut .= '<datalist id="services-list2"></datalist><br>';*/
			$htmlOut .= '<select name="input_service_id_1" id="input_service_id_1" class="validate first_opt_not_allowed"></select><br />';
			$htmlOut .= '<script type="text/javascript">';
			$htmlOut .= '$(document).ajaxStart(function() {$(\'.loading\').show();$(\'#mngDiv2\').css(\'opacity\', \'0.3\');});' . '$(document).ajaxSuccess(function() {$(\'.loading\').hide();$(\'#mngDiv2\').css(\'opacity\', \'1\');});';
			$htmlOut .= 'var ajaxPost = $.post(\'../statisticalPlotter/AsynchronousProccess.php\', {\'townId\' : ' . $_SESSION['user']['acl-id'] . '});ajaxPost.done(function(data) {$(\'#centerId_1\').empty().append(data);var ajaxPost1 = $.post(\'../statisticalPlotter/AsynchronousProccess.php\', {\'centerIdForHygieneUnitSelection\' : $(\'#centerId_1 option:selected\').val()});ajaxPost1.done(function(data) {$(\'#hygieneUnitId\').empty().append(data);var ajaxPost2 = $.post(\'./ea-sh-ajax.php\', {\'hygieneUnitIdForServiceSelection\' : $(\'#hygieneUnitId option:selected\').val()});ajaxPost2.done(function(data) {$(\'#input_service_id_1\').empty().append(data);$(\'#numOfHGUnitService\').load(\'./ea-sh-ajax.php\', {\'HGunitId4CurrentYearServices\': $(\'#hygieneUnitId option:selected\').val()});});});});';
			$htmlOut .= '$(\'#centerId_1\').change(function() {var ajaxPost1 = $.post(\'../statisticalPlotter/AsynchronousProccess.php\', {\'centerIdForHygieneUnitSelection\' : $(\'#centerId_1 option:selected\').val()});ajaxPost1.done(function(data) {$(\'#hygieneUnitId\').empty().append(data);var ajaxPost2 = $.post(\'./ea-sh-ajax.php\', {\'hygieneUnitIdForServiceSelection\' : $(\'#hygieneUnitId option:selected\').val()});ajaxPost2.done(function(data) {$(\'#input_service_id_1\').empty().append(data);$(\'#numOfHGUnitService\').load(\'./ea-sh-ajax.php\', {\'HGunitId4CurrentYearServices\': $(\'#hygieneUnitId option:selected\').val()});});});});';
			$htmlOut .= '$(\'#hygieneUnitId\').change(function() {var ajaxPost2 = $.post(\'./ea-sh-ajax.php\', {\'hygieneUnitIdForServiceSelection\' : $(\'#hygieneUnitId option:selected\').val()});ajaxPost2.done(function(data) {$(\'#input_service_id_1\').empty().append(data);$(\'#numOfHGUnitService\').load(\'./ea-sh-ajax.php\', {\'HGunitId4CurrentYearServices\': $(\'#hygieneUnitId option:selected\').val()});});});';
			//$htmlOut .= '$(\'#input_service_id_1\').change(function() {$(\'#numOfHGUnitService\').load(\'./ea-sh-ajax.php\', {\'HGunitId4CurrentYearServices\': $(\'#hygieneUnitId option:selected\').val()});});';
			$htmlOut .= '</script>';
			$htmlOut .= '<label for="input_service_frequency" class="required">بار خدمت:</label>';
			$htmlOut .= '<input type="text" name="input_service_frequency" id="input_service_frequency" maxlength="6" dir="ltr"';
			if (isset($_POST['input_service_frequency']) && $_POST['input_service_frequency'] !== '')
				$htmlOut .= ' value="' . htmlspecialchars($_POST['input_service_frequency']) . '"';
			$htmlOut .= ' class="validate required integer';
			if (isset($r['invalid']['input_service_frequency']))
				$htmlOut .= ' invalid';
			$htmlOut .= '" />';
			$htmlOut .= '<span class="description"';
			if (!isset($r['invalid']['input_service_frequency']))
				$htmlOut .= ' style="display:none;"';
			$htmlOut .= '>عدد صحیح، حداقل بزرگتر از 0</span>';
			$htmlOut .= '<script type="text/javascript">$(\'#input_service_frequency\').data(\'num_min\',0).data(\'num_min_inc\',false);</script><br />';
			$htmlOut .= '<label for="start_date:d" class="required">از تاریخ:</label>';
			$htmlOut .= '<div class="date_picker required';
			if (isset($r['invalid']['start_date']))
				$htmlOut .= ' invalid';
			$htmlOut .= '">';
			$htmlOut .= '<select name="start_date:d" id="start_date:d" class="date_d">';
			$htmlOut .= '<option value="0">روز</option><option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option><option value="5">5</option><option value="6">6</option><option value="7">7</option><option value="8">8</option><option value="9">9</option><option value="10">10</option><option value="11">11</option><option value="12">12</option><option value="13">13</option><option value="14">14</option><option value="15">15</option><option value="16">16</option><option value="17">17</option><option value="18">18</option><option value="19">19</option><option value="20">20</option><option value="21">21</option><option value="22">22</option><option value="23">23</option><option value="24">24</option><option value="25">25</option><option value="26">26</option><option value="27">27</option><option value="28">28</option><option value="29">29</option><option value="30">30</option><option value="31">31</option></select>';
			$htmlOut .= '<select name="start_date:m" class="date_m">';
			$htmlOut .= '<option value="0">ماه</option><option value="1">فروردین</option><option value="2">اردیبهشت</option><option value="3">خرداد</option><option value="4">تیر</option><option value="5">مرداد</option><option value="6">شهریور</option><option value="7">مهر</option><option value="8">آبان</option><option value="9">آذر</option><option value="10">دی</option><option value="11">بهمن</option><option value="12">اسفند</option></select>';
			$htmlOut .= '<input type="text" name="start_date:y" size="4" maxlength="4" class="date_y"';
			if (isset($_POST['start_date:y']) && $_POST['start_date:y'] !== '')
				$htmlOut .= ' value="' . htmlspecialchars($_POST['start_date:y']) . '"';
			$htmlOut .= ' /><span class="description">باید بعد از آخرین تاریخ باشد.</span></div><br />';
			$htmlOut .= '<label for="finish_date:d" class="required">تا تاریخ:</label>';
			$htmlOut .= '<div class="date_picker required';
			if (isset($r['invalid']['finish_date']))
				$htmlOut .= ' invalid';
			$htmlOut .= '">';
			$htmlOut .= '<select name="finish_date:d" id="finish_date:d" class="date_d">';
			$htmlOut .= '<option value="0">روز</option><option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option><option value="5">5</option><option value="6">6</option><option value="7">7</option><option value="8">8</option><option value="9">9</option><option value="10">10</option><option value="11">11</option><option value="12">12</option><option value="13">13</option><option value="14">14</option><option value="15">15</option><option value="16">16</option><option value="17">17</option><option value="18">18</option><option value="19">19</option><option value="20">20</option><option value="21">21</option><option value="22">22</option><option value="23">23</option><option value="24">24</option><option value="25">25</option><option value="26">26</option><option value="27">27</option><option value="28">28</option><option value="29">29</option><option value="30">30</option><option value="31">31</option></select>';
			$htmlOut .= '<select name="finish_date:m" class="date_m">';
			$htmlOut .= '<option value="0">ماه</option><option value="1">فروردین</option><option value="2">اردیبهشت</option><option value="3">خرداد</option><option value="4">تیر</option><option value="5">مرداد</option><option value="6">شهریور</option><option value="7">مهر</option><option value="8">آبان</option><option value="9">آذر</option><option value="10">دی</option><option value="11">بهمن</option><option value="12">اسفند</option></select>';
			$htmlOut .= '<input type="text" name="finish_date:y" size="4" maxlength="4" class="date_y"';
			if (isset($_POST['finish_date:y']) && $_POST['finish_date:y'] !== '')
				$htmlOut .= ' value="' . htmlspecialchars($_POST['finish_date:y']) . '"';
			$htmlOut .= ' /><span class="description">باید بعد از آخرین تاریخ و تاریخ شروع باشد.</span></div><br />';
			$htmlOut .= '<input type="submit" name="submitRegisterDoneServices" value="ثبت" /></fieldset></form></div>';
			$htmlOut .= '<div id="numOfHGUnitService"></div>';
			$htmlOut .= '</div>';
			$htmlOut .= '</div>';
			//end whole tabs
		} elseif ($_SESSION['user']['acl'] == 'Center Author') {
			$htmlOut .= '<p>در این بخش می توانید بار خدمات را در سطح مرکز وارد نمایید.</p>';
			$htmlOut .= '<div id="tabs"><ul><li><a href="#tabs-1">واحدها</a></li><li><a href="#tabs-2">خانه های بهداشت</a></li></ul>';
			$htmlOut .= '<div id="tabs-1">';
            $htmlOut .= '<div class="loading"><img src="../statisticalPlotter/loading.gif" />&nbsp;&nbsp;در حال بارگذاری؛ لطفا صبر کنید...</div><br />';
            $htmlOut .= '<div id="mngDiv1" style="text-align: right;"><form action="' . substr( strrchr( $_SERVER['PHP_SELF'], '/' ), 1 ) . '" method="post" class="narin">';
            $htmlOut .= '<input type="hidden" name="narin:token" value="' . Narin::create_token() . '" />';
            $htmlOut .= '<p style="font-weight: bold">گام اول: انتخاب نوع واحد</p>';
            $htmlOut .= '<div><input type="radio" name="unit_type" id="unit_type_0" value="0"><label for="unit_type_0">عمومی</label>';
            $htmlOut .= '<input type="radio" name="unit_type" id="unit_type_1" value="1" style="margin-right: 10px;"><label for="unit_type_1">سیب</label></div>';
            $htmlOut .= '<script type="text/javascript">';
            $htmlOut .= '$(\'input[name="unit_type"]\').change(function() {';
            $htmlOut .= 'var value = this.value;';
            $htmlOut .= 'if(value === "0") {';
            $htmlOut .= '$("#unitId0").addClass("validate");';
            $htmlOut .= '$("#unitId1").removeClass("validate");';
            $htmlOut .= '$("#input_service_id0").addClass("validate");';
            $htmlOut .= '$("#input_service_id1").removeClass("validate");';
            $htmlOut .= '$("#input_service_frequency0").addClass("required");';
            $htmlOut .= '$("#input_service_frequency1").removeClass("required");';
            $htmlOut .= '$("#date_picker_start_0").addClass("required");';
            $htmlOut .= '$("#date_picker_finish_0").addClass("required");';
            $htmlOut .= '$("#date_picker_start_1").removeClass("required");';
            $htmlOut .= '$("#date_picker_finish_1").removeClass("required");';
            $htmlOut .= '$("#new_sevice_1").hide();';
            $htmlOut .= '$("#new_sevice_0").show(); }';
            $htmlOut .= 'if(value === "1") {';
            $htmlOut .= '$("#unitId1").addClass("validate");';
            $htmlOut .= '$("#unitId0").removeClass("validate");';
            $htmlOut .= '$("#input_service_id1").addClass("validate");';
            $htmlOut .= '$("#input_service_id0").removeClass("validate");';
            $htmlOut .= '$("#input_service_frequency1").addClass("required");';
            $htmlOut .= '$("#input_service_frequency0").removeClass("required");';
            $htmlOut .= '$("#date_picker_start_1").addClass("required");';
            $htmlOut .= '$("#date_picker_finish_1").addClass("required");';
            $htmlOut .= '$("#date_picker_start_0").removeClass("required");';
            $htmlOut .= '$("#date_picker_finish_0").removeClass("required");';
            $htmlOut .= '$("#new_sevice_0").hide();';
            $htmlOut .= '$("#new_sevice_1").show(); }';
            $htmlOut .= '});</script>';


            $htmlOut .= '<div id="new_sevice_0" style="display: none">';
            $htmlOut .= '<label for="unitId01" class="required">نام واحد:</label>';
            $htmlOut .= '<select name="unitId0" id="unitId01" class="validate first_opt_not_allowed"></select><br />';
            $htmlOut .= '<label for="input_service_id0" class="required">نام خدمت:</label>';
            $htmlOut .= '<select name="input_service_id0" id="input_service_id0" class="validate first_opt_not_allowed"></select><br />';
            /*$htmlOut .= '<input id="input_service_id0" name="input_service_id0" list="services-list0" title="انتخاب کنید..." class="validate required ';
            if (isset($r['invalid']['input_service_id0']))
                $htmlOut .= 'invalid';
            $htmlOut .= '">';
            $htmlOut .= '<datalist id="services-list0"></datalist><br>';*/
            $htmlOut .= '<script type="text/javascript">';
            $htmlOut .= '$(document).ajaxStart(function() {$(\'.loading\').show();$(\'#mngDiv1\').css(\'opacity\', \'0.3\');});' . '$(document).ajaxSuccess(function() {$(\'.loading\').hide();$(\'#mngDiv1\').css(\'opacity\', \'1\');});';
            $htmlOut .= 'var ajaxPost1 = $.post(\'../statisticalPlotter/AsynchronousProccess.php\', {\'centerId\' : ' . $_SESSION['user']['acl-id'] . ', \'unitType\' : \'0\'});ajaxPost1.done(function(data) {$("#unitId01").empty().append(data); console.log(data); var ajaxPost2 = $.post(\'./ea-sh-ajax.php\', {\'unitIdForServiceSelection\' : $(\'#unitId01 option:selected\').val()});ajaxPost2.done(function(data) {$(\'#input_service_id0\').empty().append(data);$(\'#numOfUnitService\').load(\'./ea-sh-ajax.php\', {\'unitId4CurrentYearServices\': $(\'#unitId01 option:selected\').val()});});});';
            $htmlOut .= '$(\'#unitId01\').change(function() {var ajaxPost2 = $.post(\'./ea-sh-ajax.php\', {\'unitIdForServiceSelection\' : $(\'#unitId01 option:selected\').val()});ajaxPost2.done(function(data) {$(\'#input_service_id0\').empty().append(data);$(\'#numOfUnitService\').load(\'./ea-sh-ajax.php\', {\'unitId4CurrentYearServices\': $(\'#unitId01 option:selected\').val()});});});';
            //$htmlOut .= '$(\'#input_service_id\').change(function() {$(\'#numOfUnitService\').load(\'./ea-sh-ajax.php\', {\'unitId4CurrentYearServices\': $(\'#unitId option:selected\').val()});});';
            $htmlOut .= '</script>';
            $htmlOut .= '<label for="input_service_frequency0" class="required">بار خدمت:</label>';
            $htmlOut .= '<input type="text" name="input_service_frequency0" id="input_service_frequency0" maxlength="6" dir="ltr"';
            if (isset($_POST['input_service_frequency0']) && $_POST['input_service_frequency0'] !== '')
                $htmlOut .= ' value="' . htmlspecialchars($_POST['input_service_frequency']) . '"';
            $htmlOut .= ' class="validate required integer';
            if (isset($r['invalid']['input_service_frequency0']))
                $htmlOut .= ' invalid';
            $htmlOut .= '" />';
            $htmlOut .= '<span class="description"';
            if (!isset($r['invalid']['input_service_frequency0']))
                $htmlOut .= ' style="display:none;"';
            $htmlOut .= '>عدد صحیح، حداقل بزرگتر از 0</span>';
            $htmlOut .= '<script type="text/javascript">$(\'#input_service_frequency0\').data(\'num_min\',0).data(\'num_min_inc\',false);</script><br />';
            $htmlOut .= '<label for="start_date:d" class="required">از تاریخ:</label>';
            $htmlOut .= '<div id="date_picker_start_0; class="date_picker required';
            if (isset($r['invalid']['start_date0']))
                $htmlOut .= ' invalid';
            $htmlOut .= '">';
            $htmlOut .= '<select name="start_date:d0" id="start_date:d" class="date_d">';
            $htmlOut .= '<option value="0">روز</option><option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option><option value="5">5</option><option value="6">6</option><option value="7">7</option><option value="8">8</option><option value="9">9</option><option value="10">10</option><option value="11">11</option><option value="12">12</option><option value="13">13</option><option value="14">14</option><option value="15">15</option><option value="16">16</option><option value="17">17</option><option value="18">18</option><option value="19">19</option><option value="20">20</option><option value="21">21</option><option value="22">22</option><option value="23">23</option><option value="24">24</option><option value="25">25</option><option value="26">26</option><option value="27">27</option><option value="28">28</option><option value="29">29</option><option value="30">30</option><option value="31">31</option></select>';
            $htmlOut .= '<select name="start_date:m0" class="date_m">';
            $htmlOut .= '<option value="0">ماه</option><option value="1">فروردین</option><option value="2">اردیبهشت</option><option value="3">خرداد</option><option value="4">تیر</option><option value="5">مرداد</option><option value="6">شهریور</option><option value="7">مهر</option><option value="8">آبان</option><option value="9">آذر</option><option value="10">دی</option><option value="11">بهمن</option><option value="12">اسفند</option></select>';
            $htmlOut .= '<input type="text" name="start_date:y0" size="4" maxlength="4" class="date_y"';
            if (isset($_POST['start_date:y0']) && $_POST['start_date:y0'] !== '')
                $htmlOut .= ' value="' . htmlspecialchars($_POST['start_date:y0']) . '"';
            $htmlOut .= ' /><span class="description">باید بعد از آخرین تاریخ باشد.</span></div><br />';
            $htmlOut .= '<label for="finish_date:d0" class="required">تا تاریخ:</label>';
            $htmlOut .= '<div id="date_picker_finish_0; class="date_picker required';
            if (isset($r['invalid']['finish_date']))
                $htmlOut .= ' invalid';
            $htmlOut .= '">';
            $htmlOut .= '<select name="finish_date:d0" id="finish_date:d" class="date_d">';
            $htmlOut .= '<option value="0">روز</option><option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option><option value="5">5</option><option value="6">6</option><option value="7">7</option><option value="8">8</option><option value="9">9</option><option value="10">10</option><option value="11">11</option><option value="12">12</option><option value="13">13</option><option value="14">14</option><option value="15">15</option><option value="16">16</option><option value="17">17</option><option value="18">18</option><option value="19">19</option><option value="20">20</option><option value="21">21</option><option value="22">22</option><option value="23">23</option><option value="24">24</option><option value="25">25</option><option value="26">26</option><option value="27">27</option><option value="28">28</option><option value="29">29</option><option value="30">30</option><option value="31">31</option></select>';
            $htmlOut .= '<select name="finish_date:m0" class="date_m">';
            $htmlOut .= '<option value="0">ماه</option><option value="1">فروردین</option><option value="2">اردیبهشت</option><option value="3">خرداد</option><option value="4">تیر</option><option value="5">مرداد</option><option value="6">شهریور</option><option value="7">مهر</option><option value="8">آبان</option><option value="9">آذر</option><option value="10">دی</option><option value="11">بهمن</option><option value="12">اسفند</option></select>';
            $htmlOut .= '<input type="text" name="finish_date:y0" size="4" maxlength="4" class="date_y"';
            if (isset($_POST['finish_date:y0']) && $_POST['finish_date:y0'] !== '')
                $htmlOut .= ' value="' . htmlspecialchars($_POST['finish_date:y']) . '"';
            $htmlOut .= ' /><span class="description">باید بعد از آخرین تاریخ و تاریخ شروع باشد.</span></div><br />';
            $htmlOut .= '</div>'; //new_service_0 end


            $htmlOut .= '<div id="new_sevice_1" style="display: none">';
            $htmlOut .= '<label for="unitId1" class="required">نام واحد:</label>';
            $htmlOut .= '<select name="unitId1" id="unitId1" class="validate first_opt_not_allowed"></select><br />';
            $htmlOut .= '<label for="input_service_id1" class="required">نام خدمت:</label>';
            /*$htmlOut .= '<input id="input_service_id1" name="input_service_id1" list="services-list1" title="انتخاب کنید..." class="validate required ';
            if (isset($r['invalid']['input_service_id1']))
                $htmlOut .= 'invalid';
            $htmlOut .= '">';
            $htmlOut .= '<datalist id="services-list1"></datalist><br>';*/
            $htmlOut .= '<select name="input_service_id1" id="input_service_id1" class="validate first_opt_not_allowed"></select><br />';
            $htmlOut .= '<script type="text/javascript">';
            $htmlOut .= '$(document).ajaxStart(function() {$(\'.loading\').show();$(\'#mngDiv1\').css(\'opacity\', \'0.3\');});' . '$(document).ajaxSuccess(function() {$(\'.loading\').hide();$(\'#mngDiv1\').css(\'opacity\', \'1\');});';
            $htmlOut .= 'var ajaxPost1 = $.post(\'../statisticalPlotter/AsynchronousProccess.php\', {\'centerId\' : ' . $_SESSION['user']['acl-id'] . ', \'unitType\' : \'1\'});ajaxPost1.done(function(data) {$(\'#unitId1\').empty().append(data);var ajaxPost2 = $.post(\'./ea-sh-ajax.php\', {\'unitIdForServiceSelection\' : $(\'#unitId1 option:selected\').val()});ajaxPost2.done(function(data) {$(\'#input_service_id1\').empty().append(data);$(\'#numOfUnitService\').load(\'./ea-sh-ajax.php\', {\'unitId4CurrentYearServices\': $(\'#unitId1 option:selected\').val()});});});';
            $htmlOut .= '$(\'#unitId1\').change(function() {var ajaxPost2 = $.post(\'./ea-sh-ajax.php\', {\'unitIdForServiceSelection\' : $(\'#unitId1 option:selected\').val()});ajaxPost2.done(function(data) {$(\'#input_service_id1\').empty().append(data);$(\'#numOfUnitService\').load(\'./ea-sh-ajax.php\', {\'unitId4CurrentYearServices\': $(\'#unitId1 option:selected\').val()});});});';
            //$htmlOut .= '$(\'#input_service_id\').change(function() {$(\'#numOfUnitService\').load(\'./ea-sh-ajax.php\', {\'unitId4CurrentYearServices\': $(\'#unitId option:selected\').val()});});';
            $htmlOut .= '</script>';
            $htmlOut .= '<label for="input_service_frequency1" class="required">بار خدمت:</label>';
            $htmlOut .= '<input type="text" name="input_service_frequency1" id="input_service_frequency1" maxlength="6" dir="ltr"';
            if (isset($_POST['input_service_frequency1']) && $_POST['input_service_frequency1'] !== '')
                $htmlOut .= ' value="' . htmlspecialchars($_POST['input_service_frequency']) . '"';
            $htmlOut .= ' class="validate required integer';
            if (isset($r['invalid']['input_service_frequency1']))
                $htmlOut .= ' invalid';
            $htmlOut .= '" />';
            $htmlOut .= '<span class="description"';
            if (!isset($r['invalid']['input_service_frequency1']))
                $htmlOut .= ' style="display:none;"';
            $htmlOut .= '>عدد صحیح، حداقل بزرگتر از 0</span>';
            $htmlOut .= '<script type="text/javascript">$(\'#input_service_frequency1\').data(\'num_min\',0).data(\'num_min_inc\',false);</script><br />';
            $htmlOut .= '<label for="start_date:d" class="required">از تاریخ:</label>';
            $htmlOut .= '<div id="date_picker_start_1; class="date_picker required';
            if (isset($r['invalid']['start_date']))
                $htmlOut .= ' invalid';
            $htmlOut .= '">';
            $htmlOut .= '<select name="start_date:d1" id="start_date:d" class="date_d">';
            $htmlOut .= '<option value="0">روز</option><option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option><option value="5">5</option><option value="6">6</option><option value="7">7</option><option value="8">8</option><option value="9">9</option><option value="10">10</option><option value="11">11</option><option value="12">12</option><option value="13">13</option><option value="14">14</option><option value="15">15</option><option value="16">16</option><option value="17">17</option><option value="18">18</option><option value="19">19</option><option value="20">20</option><option value="21">21</option><option value="22">22</option><option value="23">23</option><option value="24">24</option><option value="25">25</option><option value="26">26</option><option value="27">27</option><option value="28">28</option><option value="29">29</option><option value="30">30</option><option value="31">31</option></select>';
            $htmlOut .= '<select name="start_date:m1" class="date_m">';
            $htmlOut .= '<option value="0">ماه</option><option value="1">فروردین</option><option value="2">اردیبهشت</option><option value="3">خرداد</option><option value="4">تیر</option><option value="5">مرداد</option><option value="6">شهریور</option><option value="7">مهر</option><option value="8">آبان</option><option value="9">آذر</option><option value="10">دی</option><option value="11">بهمن</option><option value="12">اسفند</option></select>';
            $htmlOut .= '<input type="text" name="start_date:y1" size="4" maxlength="4" class="date_y"';
            if (isset($_POST['start_date:y1']) && $_POST['start_date:y1'] !== '')
                $htmlOut .= ' value="' . htmlspecialchars($_POST['start_date:y1']) . '"';
            $htmlOut .= ' /><span class="description">باید بعد از آخرین تاریخ باشد.</span></div><br />';
            $htmlOut .= '<label for="finish_date:d" class="required">تا تاریخ:</label>';
            $htmlOut .= '<div id="date_picker_finish_1; class="date_picker required';
            if (isset($r['invalid']['finish_date']))
                $htmlOut .= ' invalid';
            $htmlOut .= '">';
            $htmlOut .= '<select name="finish_date:d1" id="finish_date:d" class="date_d">';
            $htmlOut .= '<option value="0">روز</option><option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option><option value="5">5</option><option value="6">6</option><option value="7">7</option><option value="8">8</option><option value="9">9</option><option value="10">10</option><option value="11">11</option><option value="12">12</option><option value="13">13</option><option value="14">14</option><option value="15">15</option><option value="16">16</option><option value="17">17</option><option value="18">18</option><option value="19">19</option><option value="20">20</option><option value="21">21</option><option value="22">22</option><option value="23">23</option><option value="24">24</option><option value="25">25</option><option value="26">26</option><option value="27">27</option><option value="28">28</option><option value="29">29</option><option value="30">30</option><option value="31">31</option></select>';
            $htmlOut .= '<select name="finish_date:m1" class="date_m">';
            $htmlOut .= '<option value="0">ماه</option><option value="1">فروردین</option><option value="2">اردیبهشت</option><option value="3">خرداد</option><option value="4">تیر</option><option value="5">مرداد</option><option value="6">شهریور</option><option value="7">مهر</option><option value="8">آبان</option><option value="9">آذر</option><option value="10">دی</option><option value="11">بهمن</option><option value="12">اسفند</option></select>';
            $htmlOut .= '<input type="text" name="finish_date:y1" size="4" maxlength="4" class="date_y"';
            if (isset($_POST['finish_date:y1']) && $_POST['finish_date:y1'] !== '')
                $htmlOut .= ' value="' . htmlspecialchars($_POST['finish_date:y1']) . '"';
            $htmlOut .= ' /><span class="description">باید بعد از آخرین تاریخ و تاریخ شروع باشد.</span></div><br />';
            $htmlOut .= '</div>'; //new_service_1 end
            $htmlOut .= '<input type="submit" name="submitRegisterDoneServices" value="ثبت" /></form>';
			$htmlOut .= '<div id="numOfUnitService"></div>';
			$htmlOut .= '</div></div>';

			/**************************************************************************************************************************/

			$htmlOut .= '<div id="tabs-2">';
			$htmlOut .= '<div class="loading"><img src="../statisticalPlotter/loading.gif" />&nbsp;&nbsp;در حال بارگذاری؛ لطفا صبر کنید...</div><br />';
	        $htmlOut .= '<div id="mngDiv2"><form action="' . substr( strrchr( $_SERVER['PHP_SELF'], '/' ), 1 ) . '" method="post" class="narin"><fieldset><legend>ثبت بار خدمت برای خانه بهداشت</legend>';
			$htmlOut .= '<input type="hidden" name="narin:token" value="' . Narin::create_token() . '" />';
			$htmlOut .= '<label for="hygieneUnitId" class="required">نام خانه بهداشت:</label>';
			$htmlOut .= '<select name="hygieneUnitId" id="hygieneUnitId" class="validate first_opt_not_allowed"></select><br />';
			$htmlOut .= '<label for="input_service_id_1" class="required">نام خدمت:</label>';
            /*$htmlOut .= '<input id="input_service_id_1" name="input_service_id_1" list="services-list2" title="انتخاب کنید..." class="validate required ';
            if (isset($r['invalid']['input_service_id_1']))
                $htmlOut .= 'invalid';
            $htmlOut .= '">';
            $htmlOut .= '<datalist id="services-list2"></datalist><br>';*/
			$htmlOut .= '<select name="input_service_id_1" id="input_service_id_1" class="validate first_opt_not_allowed"></select><br />';
			$htmlOut .= '<script type="text/javascript">';
			$htmlOut .= '$(document).ajaxStart(function() {$(\'.loading\').show();$(\'#mngDiv2\').css(\'opacity\', \'0.3\');});' . '$(document).ajaxSuccess(function() {$(\'.loading\').hide();$(\'#mngDiv2\').css(\'opacity\', \'1\');});';
			$htmlOut .= 'var ajaxPost1 = $.post(\'../statisticalPlotter/AsynchronousProccess.php\', {\'centerIdForHygieneUnitSelection\' : ' . $_SESSION['user']['acl-id'] . '});ajaxPost1.done(function(data) {$(\'#hygieneUnitId\').empty().append(data);var ajaxPost2 = $.post(\'./ea-sh-ajax.php\', {\'hygieneUnitIdForServiceSelection\' : $(\'#hygieneUnitId option:selected\').val()});ajaxPost2.done(function(data) {$(\'#input_service_id_1\').empty().append(data);$(\'#numOfHGUnitService\').load(\'./ea-sh-ajax.php\', {\'HGunitId4CurrentYearServices\': $(\'#hygieneUnitId option:selected\').val()});});});';
			$htmlOut .= '$(\'#hygieneUnitId\').change(function() {var ajaxPost2 = $.post(\'./ea-sh-ajax.php\', {\'hygieneUnitIdForServiceSelection\' : $(\'#hygieneUnitId option:selected\').val()});ajaxPost2.done(function(data) {$(\'#input_service_id_1\').empty().append(data);$(\'#numOfHGUnitService\').load(\'./ea-sh-ajax.php\', {\'HGunitId4CurrentYearServices\': $(\'#hygieneUnitId option:selected\').val()});});});';
			//$htmlOut .= '$(\'#input_service_id_1\').change(function() {$(\'#numOfHGUnitService\').load(\'./ea-sh-ajax.php\', {\'HGunitId4CurrentYearServices\': $(\'#hygieneUnitId option:selected\').val()});});';
			$htmlOut .= '</script>';
			$htmlOut .= '<label for="input_service_frequency" class="required">بار خدمت:</label>';
			$htmlOut .= '<input type="text" name="input_service_frequency" id="input_service_frequency" maxlength="6" dir="ltr"';
			if (isset($_POST['input_service_frequency']) && $_POST['input_service_frequency'] !== '')
				$htmlOut .= ' value="' . htmlspecialchars($_POST['input_service_frequency']) . '"';
			$htmlOut .= ' class="validate required integer';
			if (isset($r['invalid']['input_service_frequency']))
				$htmlOut .= ' invalid';
			$htmlOut .= '" />';
			$htmlOut .= '<span class="description"';
			if (!isset($r['invalid']['input_service_frequency']))
				$htmlOut .= ' style="display:none;"';
			$htmlOut .= '>عدد صحیح، حداقل بزرگتر از 0</span>';
			$htmlOut .= '<script type="text/javascript">$(\'#input_service_frequency\').data(\'num_min\',0).data(\'num_min_inc\',false);</script><br />';
			$htmlOut .= '<label for="start_date:d" class="required">از تاریخ:</label>';
			$htmlOut .= '<div class="date_picker required';
			if (isset($r['invalid']['start_date']))
				$htmlOut .= ' invalid';
			$htmlOut .= '">';
			$htmlOut .= '<select name="start_date:d" id="start_date:d" class="date_d">';
			$htmlOut .= '<option value="0">روز</option><option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option><option value="5">5</option><option value="6">6</option><option value="7">7</option><option value="8">8</option><option value="9">9</option><option value="10">10</option><option value="11">11</option><option value="12">12</option><option value="13">13</option><option value="14">14</option><option value="15">15</option><option value="16">16</option><option value="17">17</option><option value="18">18</option><option value="19">19</option><option value="20">20</option><option value="21">21</option><option value="22">22</option><option value="23">23</option><option value="24">24</option><option value="25">25</option><option value="26">26</option><option value="27">27</option><option value="28">28</option><option value="29">29</option><option value="30">30</option><option value="31">31</option></select>';
			$htmlOut .= '<select name="start_date:m" class="date_m">';
			$htmlOut .= '<option value="0">ماه</option><option value="1">فروردین</option><option value="2">اردیبهشت</option><option value="3">خرداد</option><option value="4">تیر</option><option value="5">مرداد</option><option value="6">شهریور</option><option value="7">مهر</option><option value="8">آبان</option><option value="9">آذر</option><option value="10">دی</option><option value="11">بهمن</option><option value="12">اسفند</option></select>';
			$htmlOut .= '<input type="text" name="start_date:y" size="4" maxlength="4" class="date_y"';
			if (isset($_POST['start_date:y']) && $_POST['start_date:y'] !== '')
				$htmlOut .= ' value="' . htmlspecialchars($_POST['start_date:y']) . '"';
			$htmlOut .= ' /><span class="description">باید بعد از آخرین تاریخ باشد.</span></div><br />';
			$htmlOut .= '<label for="finish_date:d" class="required">تا تاریخ:</label>';
			$htmlOut .= '<div class="date_picker required';
			if (isset($r['invalid']['finish_date']))
				$htmlOut .= ' invalid';
			$htmlOut .= '">';
			$htmlOut .= '<select name="finish_date:d" id="finish_date:d" class="date_d">';
			$htmlOut .= '<option value="0">روز</option><option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option><option value="5">5</option><option value="6">6</option><option value="7">7</option><option value="8">8</option><option value="9">9</option><option value="10">10</option><option value="11">11</option><option value="12">12</option><option value="13">13</option><option value="14">14</option><option value="15">15</option><option value="16">16</option><option value="17">17</option><option value="18">18</option><option value="19">19</option><option value="20">20</option><option value="21">21</option><option value="22">22</option><option value="23">23</option><option value="24">24</option><option value="25">25</option><option value="26">26</option><option value="27">27</option><option value="28">28</option><option value="29">29</option><option value="30">30</option><option value="31">31</option></select>';
			$htmlOut .= '<select name="finish_date:m" class="date_m">';
			$htmlOut .= '<option value="0">ماه</option><option value="1">فروردین</option><option value="2">اردیبهشت</option><option value="3">خرداد</option><option value="4">تیر</option><option value="5">مرداد</option><option value="6">شهریور</option><option value="7">مهر</option><option value="8">آبان</option><option value="9">آذر</option><option value="10">دی</option><option value="11">بهمن</option><option value="12">اسفند</option></select>';
			$htmlOut .= '<input type="text" name="finish_date:y" size="4" maxlength="4" class="date_y"';
			if (isset($_POST['finish_date:y']) && $_POST['finish_date:y'] !== '')
				$htmlOut .= ' value="' . htmlspecialchars($_POST['finish_date:y']) . '"';
			$htmlOut .= ' /><span class="description">باید بعد از آخرین تاریخ و تاریخ شروع باشد.</span></div><br />';
			$htmlOut .= '<input type="submit" name="submitRegisterDoneServices" value="ثبت" /></fieldset></form></div>';
			$htmlOut .= '<div id="numOfHGUnitService"></div>';
			$htmlOut .= '</div>';
			$htmlOut .= '</div>';
			//end whole tabs
		} else {
			$htmlOut .= '<div class="loading"><img src="../statisticalPlotter/loading.gif" />&nbsp;&nbsp;در حال بارگذاری؛ لطفا صبر کنید...</div><br />';
			$htmlOut .= '<form action="' . substr(strrchr($_SERVER['PHP_SELF'], '/'), 1) . '" method="post" class="narin">';
			$htmlOut .= '<input type="hidden" name="last_date" value="' . $tmp['year'] . '-' . $tmp['month'] . '-' . $tmp['day'] . '" />';
			$htmlOut .= '<input type="hidden" name="narin:token" value="' . Narin::create_token() . '" /><fieldset><legend>ثبت بار خدمت</legend>';
			$htmlOut .= '<label for="input_service_id" class="required">نام خدمت:</label>';
			$htmlOut .= '<select name="input_service_id" id="input_service_id" class="validate first_opt_not_allowed">';
			if ($_SESSION['user']['acl'] == 'Unit Author') {//means unit author
				$dbRes = Database::execute_query("SELECT `available-services`.`id`, `available-services`.`name` FROM `available-services` INNER JOIN `unit-services` ON `available-services`.`id` = `unit-services`.`service-id` WHERE `unit-id` = '" . Database::filter_str($_SESSION['user']['acl-id']) . "' ORDER BY `name` ASC;");
				while ($dbRow = Database::get_assoc_array($dbRes))
					$htmlOut .= '<option value="' . $dbRow['id'] . '">' . $dbRow['name'] . '</option>';
			} elseif ($_SESSION['user']['acl'] == 'Hygiene Unit Author') {//means hygiene unit author
				$dbRes = Database::execute_query("SELECT `available-services`.`id`, `available-services`.`name` FROM `available-services` INNER JOIN `hygiene-unit-services` ON `available-services`.`id` = `hygiene-unit-services`.`service-id` WHERE `hygiene-unit-id` = '" . Database::filter_str($_SESSION['user']['acl-id']) . "' ORDER BY `name` ASC;");
				while ($dbRow = Database::get_assoc_array($dbRes))
					$htmlOut .= '<option value="' . $dbRow['id'] . '">' . $dbRow['name'] . '</option>';
			}
			$htmlOut .= '</select><br />';
			$htmlOut .= '<label for="input_service_frequency" class="required">بار خدمت:</label>';
			$htmlOut .= '<input type="text" name="input_service_frequency" id="input_service_frequency" maxlength="6" dir="ltr"';
			if (isset($_POST['input_service_frequency']) && $_POST['input_service_frequency'] !== '')
				$htmlOut .= ' value="' . htmlspecialchars($_POST['input_service_frequency']) . '"';
			$htmlOut .= ' class="validate required integer';
			if (isset($r['invalid']['input_service_frequency']))
				$htmlOut .= ' invalid';
			$htmlOut .= '" />';
			$htmlOut .= '<span class="description"';
			if (!isset($r['invalid']['input_service_frequency']))
				$htmlOut .= ' style="display:none;"';
			$htmlOut .= '>عدد صحیح، حداقل بزرگتر از 0</span>';
			$htmlOut .= '<script type="text/javascript">$(\'#input_service_frequency\').data(\'num_min\',0).data(\'num_min_inc\',false);</script><br />';
			$htmlOut .= '<label for="start_date:d" class="required">از تاریخ:</label>';
			$htmlOut .= '<div class="date_picker required';
			if (isset($r['invalid']['start_date']))
				$htmlOut .= ' invalid';
			$htmlOut .= '">';
			$_POST['start_date:d'] = "" . $tmp['day'] . "";
			$_POST['start_date:m'] = "" . $tmp['month'] . "";
			$_POST['start_date:y'] = "" . $tmp['year'] . "";
			$htmlOut .= '<select name="start_date:d" id="start_date:d" class="date_d">';
			$htmlOut .= '<option value="0">روز</option><option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option><option value="5">5</option><option value="6">6</option><option value="7">7</option><option value="8">8</option><option value="9">9</option><option value="10">10</option><option value="11">11</option><option value="12">12</option><option value="13">13</option><option value="14">14</option><option value="15">15</option><option value="16">16</option><option value="17">17</option><option value="18">18</option><option value="19">19</option><option value="20">20</option><option value="21">21</option><option value="22">22</option><option value="23">23</option><option value="24">24</option><option value="25">25</option><option value="26">26</option><option value="27">27</option><option value="28">28</option><option value="29">29</option><option value="30">30</option><option value="31">31</option></select>';
			$htmlOut .= '<select name="start_date:m" class="date_m">';
			$htmlOut .= '<option value="0">ماه</option><option value="1">فروردین</option><option value="2">اردیبهشت</option><option value="3">خرداد</option><option value="4">تیر</option><option value="5">مرداد</option><option value="6">شهریور</option><option value="7">مهر</option><option value="8">آبان</option><option value="9">آذر</option><option value="10">دی</option><option value="11">بهمن</option><option value="12">اسفند</option></select>';
			$htmlOut .= '<input type="text" name="start_date:y" size="4" maxlength="4" class="date_y"';
			if (isset($_POST['start_date:y']) && $_POST['start_date:y'] !== '')
				$htmlOut .= ' value="' . htmlspecialchars($_POST['start_date:y']) . '"';
			$htmlOut .= ' /><span class="description">باید بعد از آخرین تاریخ باشد.</span></div><br />';
			$htmlOut .= '<label for="finish_date:d" class="required">تا تاریخ:</label>';
			$htmlOut .= '<div class="date_picker required';
			if (isset($r['invalid']['finish_date']))
				$htmlOut .= ' invalid';
			$htmlOut .= '">';
			$_POST['finish_date:d'] = "" . $tmp['day'] . "";
			$_POST['finish_date:m'] = "" . $tmp['month'] . "";
			$_POST['finish_date:y'] = "" . $tmp['year'] . "";
			$htmlOut .= '<select name="finish_date:d" id="finish_date:d" class="date_d">';
			$htmlOut .= '<option value="0">روز</option><option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option><option value="5">5</option><option value="6">6</option><option value="7">7</option><option value="8">8</option><option value="9">9</option><option value="10">10</option><option value="11">11</option><option value="12">12</option><option value="13">13</option><option value="14">14</option><option value="15">15</option><option value="16">16</option><option value="17">17</option><option value="18">18</option><option value="19">19</option><option value="20">20</option><option value="21">21</option><option value="22">22</option><option value="23">23</option><option value="24">24</option><option value="25">25</option><option value="26">26</option><option value="27">27</option><option value="28">28</option><option value="29">29</option><option value="30">30</option><option value="31">31</option></select>';
			$htmlOut .= '<select name="finish_date:m" class="date_m">';
			$htmlOut .= '<option value="0">ماه</option><option value="1">فروردین</option><option value="2">اردیبهشت</option><option value="3">خرداد</option><option value="4">تیر</option><option value="5">مرداد</option><option value="6">شهریور</option><option value="7">مهر</option><option value="8">آبان</option><option value="9">آذر</option><option value="10">دی</option><option value="11">بهمن</option><option value="12">اسفند</option></select>';
			$htmlOut .= '<input type="text" name="finish_date:y" size="4" maxlength="4" class="date_y"';
			if (isset($_POST['finish_date:y']) && $_POST['finish_date:y'] !== '')
				$htmlOut .= ' value="' . htmlspecialchars($_POST['finish_date:y']) . '"';
			$htmlOut .= ' /><span class="description">باید بعد از آخرین تاریخ و تاریخ شروع باشد.</span></div><br />';
			$htmlOut .= '<input type="submit" name="submitRegisterDoneServices" value="ثبت" /></fieldset></form>';
			if ($_SESSION['user']['acl'] == 'Unit Author') {//means unit author
				$htmlOut .= '<script type="text/javascript">';
				$htmlOut .= '$(document).ajaxStart(function() {$(\'.loading\').show();$(\'#mngDiv1\').css(\'opacity\', \'0.3\');});' . '$(document).ajaxSuccess(function() {$(\'.loading\').hide();$(\'#mngDiv1\').css(\'opacity\', \'1\');});';
				$htmlOut .= 'var ajaxPost2 = $.post(\'./ea-sh-ajax.php\', {\'data[]\': [' . $_SESSION['user']['acl-id'] . ', $(\'#input_service_id option:selected\').val()]});ajaxPost2.done(function(data) {$(\'#numOfUnitService\').empty().append(data);});$(\'#input_service_id\').change(function() {$(\'#numOfUnitService\').load(\'./ea-sh-ajax.php\', {\'data[]\': [' . $_SESSION['user']['acl-id'] . ', $(\'#input_service_id option:selected\').val()]});});';
				$htmlOut .= '</script>';
				$htmlOut .= '<div id="numOfUnitService"></div>';
			}
			elseif ($_SESSION['user']['acl'] == 'Hygiene Unit Author') {//means hygiene unit author
				$htmlOut .= '<script type="text/javascript">';
				$htmlOut .= '$(document).ajaxStart(function() {$(\'.loading\').show();$(\'#mngDiv1\').css(\'opacity\', \'0.3\');});' . '$(document).ajaxSuccess(function() {$(\'.loading\').hide();$(\'#mngDiv1\').css(\'opacity\', \'1\');});';
				$htmlOut .= 'var ajaxPost2 = $.post(\'./ea-sh-ajax.php\', {\'HGdata[]\': [' . $_SESSION['user']['acl-id'] . ', $(\'#input_service_id option:selected\').val()]});ajaxPost2.done(function(data) {$(\'#numOfHGUnitService\').empty().append(data);});$(\'#input_service_id\').change(function() {$(\'#numOfHGUnitService\').load(\'./ea-sh-ajax.php\', {\'HGdata[]\': [' . $_SESSION['user']['acl-id'] . ', $(\'#input_service_id option:selected\').val()]});});';
				$htmlOut .= '</script>';
				$htmlOut .= '<div id="numOfHGUnitService"></div>';
			}
			
		}
		//end else

		return $htmlOut;
	}

	private function prepareMenu($cmd) {
		$res = '';
		switch ( $cmd ) {
		}

		return $res;
	}

	private function prepareContent($cmd) {
		$res = '';
		switch ( $cmd ) {
			case 'registerService' :
				$res = $this -> registerService();
				break;
			
			case 'logOut' :
				$res = $this -> logOut();
				break;
			
			case 'removeUnitServiceFreq' :
				$res = $this -> removeUnitServiceFrequency($_GET['id']);
				break;
			
			case 'editUnitServiceFreq' :
				$res = $this -> submitUnitEditRequest($_GET['id']);
				break;
			
			case 'removeHGUnitServiceFreq' :
				$res = $this -> removeHGUnitServiceFrequency($_GET['id']);
				break;
			
			case 'editHGUnitServiceFreq' :
				$res = $this -> submitHGUnitEditRequest($_GET['id']);
				break;
				
			case 'editFreqRequests' :
				$res = $this -> displayConfirmedEditRequests();
				break;
				
			case 'closeUnitServReq' :
				$res = $this -> closeUnitServiceRequest($_GET['id']);
				break;
				
			case 'closeHGUnitServReq' :
				$res = $this -> closeHGUnitServiceRequest($_GET['id']);
				break;
				
			default :
				$res = '<br /><p id="err">خطا: صفحه ی موردنظر وجود ندارد.</p>';
				break;
		}

		return $res;
	}

	public function redirect($to) {
		header('Location: ' . $to);
		exit ;
	}

	private function logOut() {
		//_log process
		$_logTXT = 'خروج کاربر از سیستم';
		FormProccessor::_makeLog($_logTXT);
		
		$_SESSION = array();
		unset($_SESSION);
		header('Location: ' . ROOT_DIR);
		exit ;
	}

	private function removeUnitServiceFrequency($id) {
		//_log process
		try {
			$resI = Database::execute_query("SELECT * FROM `unit-done-services` WHERE `id` = '" . $id . "';");
			$rowI = Database::get_assoc_array($resI);
			$resS = Database::execute_query("SELECT `name` FROM `available-services` WHERE `id` = '" . $rowI['service-id'] . "';");
			$rowS = Database::get_assoc_array($resS);
			$resU = Database::execute_query("SELECT `name`, `center-id` FROM `units` WHERE `id` = '" . $rowI['unit-id'] . "';");
			$rowU = Database::get_assoc_array($resU);
			$resC = Database::execute_query("SELECT `name`, `town-id` FROM `centers` WHERE `id` = '" . $rowU['center-id'] . "';");
			$rowC = Database::get_assoc_array($resC);
			$res = Database::execute_query("SELECT `name` FROM `towns` WHERE `id` = '" . $rowC['town-id'] . "';");
			$row = Database::get_assoc_array($res);
		} catch ( exception $e ) {
			return array('err_msg' => $e -> getMessage());
		}
		$_logTXT = 'حذف بارخدمت انجام‌شده "' . $rowI['service-frequency'] . '" برای خدمت "' . $rowS['name'] . '" از تاریخ "' . $rowI['period-start-date'] . '" تا تاریخ "' . $rowI['period-finish-date'] . '" در واحد "' . $rowU['name'] . '" در مرکز "' . $rowC['name'] . '" در شهرستان "' . $row['name'] . '"';
			
		try {
			Database::execute_query("DELETE FROM `unit-done-services` WHERE `id` = '" . $id . "';");
			header("Location: " . $_SERVER['HTTP_REFERER']);
			exit ;
		} catch ( exception $e ) {
			return $e -> getMessage();
		}
		FormProccessor::_makeLog($_logTXT);
		
		return true;
	}
	
	private function submitUnitEditRequest($id) {
		$htmlOut = '';
		$res = Database::execute_query("SELECT `service-frequency` FROM `unit-done-services` WHERE `id` = '$id';");
		$row = Database::get_assoc_array($res);
		$res1 = Database::execute_query("SELECT * FROM `unit-service-freq-edit-requests` WHERE `done-service-id` = '$id';");
		if(Database::num_of_rows($res1) > 0)
			return '<p id="err">قبلا درخواستی برای اصلاح این آیتم ثبت شده است.</p><br /><a href="javascript:history.go(-1);">[ بازگشت به مرحله ی قبل ]</a>';
		$htmlOut .= '<form action="' . substr(strrchr($_SERVER['PHP_SELF'], '/'), 1) . '" method="post" class="narin"><fieldset><legend>ثبت درخواست سند اصلاحی</legend>';
		$htmlOut .= '<input type="hidden" name="narin:token" value="' . Narin::create_token() . '" />';
		$htmlOut .= '<input type="hidden" name="id" value="' . $id . '" />';
		$htmlOut .= '<p>درخواست خود را برای اصلاح بار خدمت موردنظر به مدیر ثبت کنید.<br />بار خدمت اصلاحی را در زیر وارد نمائید...</p>';
		$htmlOut .= '<label for="input_service_frequency" class="required">بار خدمت:</label>';
		$htmlOut .= '<input type="text" name="input_service_frequency" id="input_service_frequency" maxlength="6" dir="ltr" value="' . $row['service-frequency'] . '"';
		if (isset($_POST['input_service_frequency']) && $_POST['input_service_frequency'] !== '')
			$htmlOut .= ' value="' . htmlspecialchars($_POST['input_service_frequency']) . '"';
		$htmlOut .= ' class="validate required integer';
		if (isset($r['invalid']['input_service_frequency']))
			$htmlOut .= ' invalid';
		$htmlOut .= '" />';
		$htmlOut .= '<span class="description"';
		if (!isset($r['invalid']['input_service_frequency']))
			$htmlOut .= ' style="display:none;"';
		$htmlOut .= '>عدد صحیح، حداقل بزرگتر از 0</span>';
		$htmlOut .= '<script type="text/javascript">$(\'#input_service_frequency\').data(\'num_min\',0).data(\'num_min_inc\',false);</script><br />';
		$htmlOut .= '<br /><input type="submit" name="submitEditUnitFrequencyRequest" value="ثبت درخواست" /></fieldset></form>';
		
		return $htmlOut;
	}

	private function editUnitServiceFrequency($id) {
		$htmlOut = '';
		$res = Database::execute_query("SELECT `service-frequency` FROM `unit-done-services` WHERE `id` = '$id';");
		$row = Database::get_assoc_array($res);
		$htmlOut .= '<form action="' . substr(strrchr($_SERVER['PHP_SELF'], '/'), 1) . '" method="post" class="narin"><fieldset><legend>ثبت درخواست سند اصلاحی</legend>';
		$htmlOut .= '<input type="hidden" name="narin:token" value="' . Narin::create_token() . '" />';
		$htmlOut .= '<input type="hidden" name="id" value="' . $id . '" />';
		$htmlOut .= '<label for="input_service_frequency" class="required">بار خدمت:</label>';
		$htmlOut .= '<input type="text" name="input_service_frequency" id="input_service_frequency" maxlength="6" dir="ltr" value="' . $row['service-frequency'] . '"';
		if (isset($_POST['input_service_frequency']) && $_POST['input_service_frequency'] !== '')
			$htmlOut .= ' value="' . htmlspecialchars($_POST['input_service_frequency']) . '"';
		$htmlOut .= ' class="validate required integer';
		if (isset($r['invalid']['input_service_frequency']))
			$htmlOut .= ' invalid';
		$htmlOut .= '" />';
		$htmlOut .= '<span class="description"';
		if (!isset($r['invalid']['input_service_frequency']))
			$htmlOut .= ' style="display:none;"';
		$htmlOut .= '>عدد صحیح، حداقل بزرگتر از 0</span>';
		$htmlOut .= '<script type="text/javascript">$(\'#input_service_frequency\').data(\'num_min\',0).data(\'num_min_inc\',false);</script><br />';
		$htmlOut .= '<input type="submit" name="submitEditDoneServices" value="ثبت درخواست" /></fieldset></form>';
		
		return $htmlOut;
	}

	private function removeHGUnitServiceFrequency($id) {
		//_log process
		try {
			$resI = Database::execute_query("SELECT * FROM `hygiene-unit-done-services` WHERE `id` = '" . $id . "';");
			$rowI = Database::get_assoc_array($resI);
			$resS = Database::execute_query("SELECT `name` FROM `available-services` WHERE `id` = '" . $rowI['service-id'] . "';");
			$rowS = Database::get_assoc_array($resS);
			$resU = Database::execute_query("SELECT `name`, `center-id` FROM `hygiene-units` WHERE `id` = '" . $rowI['hygiene-unit-id'] . "';");
			$rowU = Database::get_assoc_array($resU);
			$resC = Database::execute_query("SELECT `name`, `town-id` FROM `centers` WHERE `id` = '" . $rowU['center-id'] . "';");
			$rowC = Database::get_assoc_array($resC);
			$res = Database::execute_query("SELECT `name` FROM `towns` WHERE `id` = '" . $rowC['town-id'] . "';");
			$row = Database::get_assoc_array($res);
		} catch ( exception $e ) {
			return array('err_msg' => $e -> getMessage());
		}
		$_logTXT = 'حذف بارخدمت انجام‌شده "' . $rowI['service-frequency'] . '" برای خدمت "' . $rowS['name'] . '" از تاریخ "' . $rowI['period-start-date'] . '" تا تاریخ "' . $rowI['period-finish-date'] . '" در خانه‌بهداشت "' . $rowU['name'] . '" در مرکز "' . $rowC['name'] . '" در شهرستان "' . $row['name'] . '"';
		
		try {
			Database::execute_query("DELETE FROM `hygiene-unit-done-services` WHERE `id` = '" . $id . "';");
			header("Location: " . $_SERVER['HTTP_REFERER']);
			exit ;
		} catch ( exception $e ) {
			return $e -> getMessage();
		}
		FormProccessor::_makeLog($_logTXT);
		
		return true;
	}

	private function submitHGUnitEditRequest($id) {
		$htmlOut = '';
		$res = Database::execute_query("SELECT `service-frequency` FROM `hygiene-unit-done-services` WHERE `id` = '$id';");
		$row = Database::get_assoc_array($res);
		$res1 = Database::execute_query("SELECT * FROM `hygiene-unit-service-freq-edit-requests` WHERE `done-service-id` = '$id';");
		if(Database::num_of_rows($res1) > 0)
			return '<p id="err">قبلا درخواستی برای اصلاح این آیتم ثبت شده است.</p><br /><a href="javascript:history.go(-1);">[ بازگشت به مرحله ی قبل ]</a>';
		$htmlOut .= '<form action="' . substr(strrchr($_SERVER['PHP_SELF'], '/'), 1) . '" method="post" class="narin"><fieldset><legend>ثبت درخواست سند اصلاحی</legend>';
		$htmlOut .= '<input type="hidden" name="narin:token" value="' . Narin::create_token() . '" />';
		$htmlOut .= '<input type="hidden" name="id" value="' . $id . '" />';
		$htmlOut .= '<p>درخواست خود را برای اصلاح بار خدمت موردنظر به مدیر ثبت کنید.<br />بار خدمت اصلاحی را در زیر وارد نمائید...</p>';
		$htmlOut .= '<label for="input_service_frequency" class="required">بار خدمت:</label>';
		$htmlOut .= '<input type="text" name="input_service_frequency" id="input_service_frequency" maxlength="6" dir="ltr" value="' . $row['service-frequency'] . '"';
		if (isset($_POST['input_service_frequency']) && $_POST['input_service_frequency'] !== '')
			$htmlOut .= ' value="' . htmlspecialchars($_POST['input_service_frequency']) . '"';
		$htmlOut .= ' class="validate required integer';
		if (isset($r['invalid']['input_service_frequency']))
			$htmlOut .= ' invalid';
		$htmlOut .= '" />';
		$htmlOut .= '<span class="description"';
		if (!isset($r['invalid']['input_service_frequency']))
			$htmlOut .= ' style="display:none;"';
		$htmlOut .= '>عدد صحیح، حداقل بزرگتر از 0</span>';
		$htmlOut .= '<script type="text/javascript">$(\'#input_service_frequency\').data(\'num_min\',0).data(\'num_min_inc\',false);</script><br />';
		$htmlOut .= '<br /><input type="submit" name="submitEditHGUnitFrequencyRequest" value="ثبت درخواست" /></fieldset></form>';
		
		return $htmlOut;
	}

	private function editHGUnitServiceFrequency($id) {
		$htmlOut = '';
		$res = Database::execute_query("SELECT `service-frequency` FROM `hygiene-unit-done-services` WHERE `id` = '$id';");
		$row = Database::get_assoc_array($res);
		$htmlOut .= '<form action="' . substr(strrchr($_SERVER['PHP_SELF'], '/'), 1) . '" method="post" class="narin"><fieldset><legend>تصحیح بار خدمت</legend>';
		$htmlOut .= '<input type="hidden" name="narin:token" value="' . Narin::create_token() . '" />';
		$htmlOut .= '<input type="hidden" name="id" value="' . $id . '" />';
		$htmlOut .= '<label for="input_service_frequency" class="required">بار خدمت:</label>';
		$htmlOut .= '<input type="text" name="input_service_frequency" id="input_service_frequency" maxlength="6" dir="ltr" value="' . $row['service-frequency'] . '"';
		if (isset($_POST['input_service_frequency']) && $_POST['input_service_frequency'] !== '')
			$htmlOut .= ' value="' . htmlspecialchars($_POST['input_service_frequency']) . '"';
		$htmlOut .= ' class="validate required integer';
		if (isset($r['invalid']['input_service_frequency']))
			$htmlOut .= ' invalid';
		$htmlOut .= '" />';
		$htmlOut .= '<span class="description"';
		if (!isset($r['invalid']['input_service_frequency']))
			$htmlOut .= ' style="display:none;"';
		$htmlOut .= '>عدد صحیح، حداقل بزرگتر از 0</span>';
		$htmlOut .= '<script type="text/javascript">$(\'#input_service_frequency\').data(\'num_min\',0).data(\'num_min_inc\',false);</script><br />';
		$htmlOut .= '<input type="submit" name="submitEditDoneServices" value="ثبت" /></fieldset></form>';
		
		return $htmlOut;
	}

	private function displayConfirmedEditRequests() {
		$htmlOut = '';
		$resUnit = Database::execute_query("SELECT * FROM `unit-service-freq-edit-requests` WHERE `user-id` = '" . $_SESSION['user']['userId'] . "' AND `approved` = '1';");
		$resHGUnit = Database::execute_query("SELECT * FROM `hygiene-unit-service-freq-edit-requests` WHERE `user-id` = '" . $_SESSION['user']['userId'] . "' AND `approved` = '1';");
		$counter = 1;
		if (Database::num_of_rows($resUnit) > 0 or Database::num_of_rows($resHGUnit) > 0) {
			$htmlOut .= '<p>باید اسناد اصلاحی تائیدشده توسط مدیریت را برای تائید نهایی و محاسبه در اسناد مالی ببندید...</p>';
			$htmlOut .= '<fieldset><legend>اسناد اصلاحی تائیدشده توسط مدیریت</legend>';
			$htmlOut .= '<br /><table align="right" class="contentTable" border="0" cellpadding="0" cellspacing="1">';
			$htmlOut .= '<thead><th width="40">ردیف</th><th>نام خدمت</th><th width="130">بار خدمت تائیدشده</th><th width="50">بستن</th></thead><tbody>';
			while ($row = Database::get_assoc_array($resUnit)) {
				$resTmp = Database::execute_query("SELECT `service-id` FROM `unit-done-services` WHERE `id` = '" . $row['done-service-id'] . "';");
				$rowTmp = Database::get_assoc_array($resTmp);
				$resTmp = Database::execute_query("SELECT `name` FROM `available-services` WHERE `id` = '" . $rowTmp['service-id'] . "';");
				$rowTmp = Database::get_assoc_array($resTmp);
				$htmlOut .= '<tr><td>' . $counter . '</td><td>' . $rowTmp['name'] . '</td><td>' . $row['suggested-freq'] . '</td><td><a href="?cmd=closeUnitServReq&id=' . $row['id'] . '"><img src="../illustrator/images/icons/checked.png"></a></td></tr>';
				$counter++;
			}
			while ($row = Database::get_assoc_array($resHGUnit)) {
				$resTmp = Database::execute_query("SELECT `service-id` FROM `hygiene-unit-done-services` WHERE `id` = '" . $row['done-service-id'] . "';");
				$rowTmp = Database::get_assoc_array($resTmp);
				$resTmp = Database::execute_query("SELECT `name` FROM `available-services` WHERE `id` = '" . $rowTmp['service-id'] . "';");
				$rowTmp = Database::get_assoc_array($resTmp);
				$htmlOut .= '<tr><td>' . $counter . '</td><td>' . $rowTmp['name'] . '</td><td>' . $row['suggested-freq'] . '</td><td><a href="?cmd=closeHGUnitServReq&id=' . $row['id'] . '"><img src="../illustrator/images/icons/checked.png"></a></td></tr>';
				$counter++;
			}
			$htmlOut .= '</tbody></table></fieldset><br />';
		}
		else
			$htmlOut .= '<p class="warning">هیچ سند اصلاحی تائیدشده‌ای وجود ندارد...</p>';
		
		return $htmlOut;
	}

	private function closeUnitServiceRequest($id) {
		$htmlOut = '';
		//_log process
		try {
			$resR = Database::execute_query("SELECT * FROM `unit-service-freq-edit-requests` WHERE `id` = '$id';");
			$rowR = Database::get_assoc_array($resR);
			$resI = Database::execute_query("SELECT * FROM `unit-done-services` WHERE `id` = '" . $rowR['done-service-id'] . "';");
			$rowI = Database::get_assoc_array($resI);
			$resS = Database::execute_query("SELECT `name` FROM `available-services` WHERE `id` = '" . $rowI['service-id'] . "';");
			$rowS = Database::get_assoc_array($resS);
			$resU = Database::execute_query("SELECT `name`, `center-id` FROM `units` WHERE `id` = '" . $rowI['unit-id'] . "';");
			$rowU = Database::get_assoc_array($resU);
			$resC = Database::execute_query("SELECT `name`, `town-id` FROM `centers` WHERE `id` = '" . $rowU['center-id'] . "';");
			$rowC = Database::get_assoc_array($resC);
			$res = Database::execute_query("SELECT `name` FROM `towns` WHERE `id` = '" . $rowC['town-id'] . "';");
			$row = Database::get_assoc_array($res);
		} catch ( exception $e ) {
			return array('err_msg' => $e -> getMessage());
		}
		$_logTXT = 'بستن سنداصلاحی تائید شده برای بارخدمت "' . $rowI['service-frequency'] . '" به "' . $rowR['suggested-freq'] . '" برای خدمت "' . $rowS['name'] . '" از تاریخ "' . $rowI['period-start-date'] . '" تا تاریخ "' . $rowI['period-finish-date'] . '" در واحد "' . $rowU['name'] . '" در مرکز "' . $rowC['name'] . '" در شهرستان "' . $row['name'] . '"';
		
		$res = Database::execute_query("SELECT * FROM `unit-service-freq-edit-requests` WHERE `id` = '$id';");
		$rowRequest = Database::get_assoc_array($res);
		try {
			Database::execute_query("UPDATE `unit-done-services` SET `service-frequency` = '" . $rowRequest['suggested-freq'] . "' WHERE `id` = '" . $rowRequest['done-service-id'] . "';");
			Database::execute_query("DELETE FROM `unit-service-freq-edit-requests` WHERE `id` = '$id';");
		} catch ( exception $e ) {
			$htmlOut .= DATA_REGISTER_INTERRUPTION;
		}
		FormProccessor::_makeLog($_logTXT);
		
		$htmlOut .= '<p id="cong">سند اصلاحی موردنظر با موفقیت بسته شد و تغییرات اعمال گردید...</p><p style="text-align: center;">[<a href="javascript:history.go(-1);">بازگشت</a>]</p>';
		
		return $htmlOut;
	}
	
	private function closeHGUnitServiceRequest($id) {
		$htmlOut = '';
		
		//_log process
		try {
			$resR = Database::execute_query("SELECT * FROM `hygiene-unit-service-freq-edit-requests` WHERE `id` = '$id';");
			$rowR = Database::get_assoc_array($resR);
			$resI = Database::execute_query("SELECT * FROM `hygiene-unit-done-services` WHERE `id` = '" . $rowR['done-service-id'] . "';");
			$rowI = Database::get_assoc_array($resI);
			$resS = Database::execute_query("SELECT `name` FROM `available-services` WHERE `id` = '" . $rowI['service-id'] . "';");
			$rowS = Database::get_assoc_array($resS);
			$resU = Database::execute_query("SELECT `name`, `center-id` FROM `hygiene-units` WHERE `id` = '" . $rowI['hygiene-unit-id'] . "';");
			$rowU = Database::get_assoc_array($resU);
			$resC = Database::execute_query("SELECT `name`, `town-id` FROM `centers` WHERE `id` = '" . $rowU['center-id'] . "';");
			$rowC = Database::get_assoc_array($resC);
			$res = Database::execute_query("SELECT `name` FROM `towns` WHERE `id` = '" . $rowC['town-id'] . "';");
			$row = Database::get_assoc_array($res);
		} catch ( exception $e ) {
			return array('err_msg' => $e -> getMessage());
		}
		$_logTXT = 'بستن سنداصلاحی تائید شده برای بارخدمت "' . $rowI['service-frequency'] . '" به "' . $rowR['suggested-freq'] . '" برای خدمت "' . $rowS['name'] . '" از تاریخ "' . $rowI['period-start-date'] . '" تا تاریخ "' . $rowI['period-finish-date'] . '" در خانه‌بهداشت "' . $rowU['name'] . '" در مرکز "' . $rowC['name'] . '" در شهرستان "' . $row['name'] . '"';
		
		$res = Database::execute_query("SELECT * FROM `hygiene-unit-service-freq-edit-requests` WHERE `id` = '$id';");
		$rowRequest = Database::get_assoc_array($res);
		try {
			Database::execute_query("UPDATE `hygiene-unit-done-services` SET `service-frequency` = '" . $rowRequest['suggested-freq'] . "' WHERE `id` = '" . $rowRequest['done-service-id'] . "';");
			Database::execute_query("DELETE FROM `hygiene-unit-service-freq-edit-requests` WHERE `id` = '$id';");
		} catch ( exception $e ) {
			$htmlOut .= DATA_REGISTER_INTERRUPTION;
		}
		FormProccessor::_makeLog($_logTXT);
		$htmlOut .= '<p id="cong">سند اصلاحی موردنظر با موفقیت بسته شد و تغییرات اعمال گردید...</p><p style="text-align: center;">[<a href="javascript:history.go(-1);">بازگشت</a>]</p>';
		
		return $htmlOut;
	}
	
}
?>