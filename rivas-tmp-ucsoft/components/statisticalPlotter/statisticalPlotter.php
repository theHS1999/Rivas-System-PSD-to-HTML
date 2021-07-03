<?php

/**
 *
 * statisticalPlotter.php
 * statisticalPlotter class file
 *
 * @copyright	Copyright (C) 2012 Rivas Systems Inc. All rights reserved.
 * @versin	1.00 2011-02-05
 * @author	Behnam Salili
 *
 */

require_once dirname(dirname(dirname(__file__))) . '/defines.php';
require_once dirname(dirname(__file__)) . '/database/database.php';
require_once dirname(dirname(__file__)) . '/illustrator/pdate.php';
require_once dirname(dirname(__file__)) . '/form/form-proccessor.php';
require_once dirname(dirname(__file__)) . '/models/state.php';
require_once dirname(dirname(__file__)) . '/models/town.php';
require_once dirname(dirname(__file__)) . '/models/center.php';
require_once dirname(dirname(__file__)) . '/models/unit.php';
require_once dirname(dirname(__file__)) . '/models/hygiene-unit.php';

class StatisticalPlotter {

	public static function reportsMainInfo() {
		$htmlOut = '';
		$htmlOut .= '<p style="width: 550px; text-align: center; margin-right: auto; margin-left: auto;">در این قسمت می توانید از تمامی شهرستان‌ها، مراکز، واحدها و خانه‌های بهداشت گزارشات و نمودارهای عددی و آماری به دست آورید.</p>';
		$htmlOut .= '<div class="loading" id="loadingRep">در حال بارگذاری؛ لطفا صبر کنید... <br /><br /><img src="../statisticalPlotter/loading.gif" /></div>';
		$htmlOut .= '<form action="' . substr(strrchr($_SERVER['PHP_SELF'], '/'), 1) . '?chartExportPage" method="post" name="reportsForm" class="narin">';		
		$htmlOut .= '<fieldset><legend>گزارش هزینه ها</legend>';
		$htmlOut .= '<br /><p style="font-weight: bold;">گام اول:</p><fieldset style="width: 520px; margin: 0 auto 0 auto;"><legend>انواع گزارش</legend>';
		$htmlOut .= '<input type="radio" name="report_type" id="report_type_0" value="1" /><label for="report_type_0">نوع اول - نمودار مقایسه‌ای خطی هزینه‌ها (بازه زمانی مشترک)</label><br />';
		$htmlOut .= '<input type="radio" name="report_type" id="report_type_1" value="2" /><label for="report_type_1">نوع دوم - نمودار مقایسه‌ای خطی هزینه‌ها (مکان مشترک)</label><br />';
		$htmlOut .= '<input type="radio" name="report_type" id="report_type_2" value="3" /><label for="report_type_2">نوع سوم - نمودار نسبی دایره‌ای هزینه‌ها (نسبت یک آیتم به کل در یک مجموعه)</label><br />';
		$htmlOut .= '<input type="radio" name="report_type" id="report_type_3" value="4" /><label for="report_type_3">نوع چهارم - نمودار مقایسه‌ای کلی هزینه‌ها</label><br />';
		$htmlOut .= '<input type="radio" name="report_type" id="report_type_4" value="5" /><label for="report_type_4">نوع پنجم - نمودار مقایسه‌ای درآمدها</label>';
		$htmlOut .= '</fieldset><br />';

		/*----------------------------------------------------------------------------------------------------------------------*/
		/*													REPORT FIRST TYPE													*/
		$htmlOut .= '<div id="reportFirstType"><br /><p style="font-weight: bold;">گام دوم:</p><fieldset style="width: 520px;"><legend>بازه زمانی مشترک</legend>';
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
		$htmlOut .= ' /></div><br />';
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
		$htmlOut .= ' /><span class="description">باید بعد از تاریخ شروع باشد.</span></div><br />';
		$htmlOut .= '</fieldset><br />';
		$htmlOut .= '<br /><input type="checkbox" name="export" id="export" />';
		$htmlOut .= '<label for="export">خروجی به فایل؟</label><br />';
		$htmlOut .= '<fieldset id="exportFieldset" style="width: 520px;"><legend>نوع فایل خروجی</legend>';
		$htmlOut .= '<input type="radio" name="exportFileType" id="exportFileType_0" value="1" /><label for="exportFileType_0">Comma Seperated Values - CSV</label><br />';
		$htmlOut .= '<input type="radio" name="exportFileType" id="exportFileType_1" value="2" /><label for="exportFileType_1">XLSX (برای MS EXCEL 2007 و بالاتر و IBM SPSS)</label><br />';
		$htmlOut .= '<input type="radio" name="exportFileType" id="exportFileType_2" value="3" /><label for="exportFileType_2">Extensible Markup Language - XML</label>';
		$htmlOut .= '</fieldset><br />';
		$htmlOut .= '<br /><select name="location_type1" id="location_type1" class="validate first_opt_not_allowed">';
		$htmlOut .= '<option value="none">نوع مکان ...</option>';
		$htmlOut .= '<option value="town">شهرستان</option>';
		$htmlOut .= '<option value="center">مرکز</option>';
		$htmlOut .= '<option value="hygieneUnit">خانه بهداشت</option>';
		$htmlOut .= '<option value="unit">واحد</option>';
		$htmlOut .= '</select>';
		$htmlOut .= '<input type="button" value="افزودن" onclick="addLocation($(\'#location_type1 option:selected\').val());return false;" /><hr style="width: 540px; margin-top: 20px; margin-bottom: 20px;" />';
		$htmlOut .= '</div>';
		
		/*----------------------------------------------------------------------------------------------------------------------*/
		/*													REPORT SECOND TYPE													*/
		
		$htmlOut .= '<div id="reportSecondType"><br /><p style="font-weight: bold;">گام دوم:</p><fieldset style="width: 520px;"><legend>بازه زمانی اول</legend>';
		$htmlOut .= '<label for="start_date1:d" class="required">از تاریخ:</label>';
		$htmlOut .= '<div class="date_picker required';
		if (isset($r['invalid']['start_date1']))
			$htmlOut .= ' invalid';
		$htmlOut .= '">';
		$htmlOut .= '<select name="start_date1:d" id="start_date1:d" class="date_d">';
		$htmlOut .= '<option value="0">روز</option><option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option><option value="5">5</option><option value="6">6</option><option value="7">7</option><option value="8">8</option><option value="9">9</option><option value="10">10</option><option value="11">11</option><option value="12">12</option><option value="13">13</option><option value="14">14</option><option value="15">15</option><option value="16">16</option><option value="17">17</option><option value="18">18</option><option value="19">19</option><option value="20">20</option><option value="21">21</option><option value="22">22</option><option value="23">23</option><option value="24">24</option><option value="25">25</option><option value="26">26</option><option value="27">27</option><option value="28">28</option><option value="29">29</option><option value="30">30</option><option value="31">31</option></select>';
		$htmlOut .= '<select name="start_date1:m" class="date_m">';
		$htmlOut .= '<option value="0">ماه</option><option value="1">فروردین</option><option value="2">اردیبهشت</option><option value="3">خرداد</option><option value="4">تیر</option><option value="5">مرداد</option><option value="6">شهریور</option><option value="7">مهر</option><option value="8">آبان</option><option value="9">آذر</option><option value="10">دی</option><option value="11">بهمن</option><option value="12">اسفند</option></select>';
		$htmlOut .= '<input type="text" name="start_date1:y" size="4" maxlength="4" class="date_y"';
		if (isset($_POST['start_date1:y']) && $_POST['start_date1:y'] !== '')
			$htmlOut .= ' value="' . htmlspecialchars($_POST['start_date1:y']) . '"';
		$htmlOut .= ' /></div><br />';
		$htmlOut .= '<label for="finish_date1:d" class="required">تا تاریخ:</label>';
		$htmlOut .= '<div class="date_picker required';
		if (isset($r['invalid']['finish_date1']))
			$htmlOut .= ' invalid';
		$htmlOut .= '">';
		$htmlOut .= '<select name="finish_date1:d" id="finish_date1:d" class="date_d">';
		$htmlOut .= '<option value="0">روز</option><option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option><option value="5">5</option><option value="6">6</option><option value="7">7</option><option value="8">8</option><option value="9">9</option><option value="10">10</option><option value="11">11</option><option value="12">12</option><option value="13">13</option><option value="14">14</option><option value="15">15</option><option value="16">16</option><option value="17">17</option><option value="18">18</option><option value="19">19</option><option value="20">20</option><option value="21">21</option><option value="22">22</option><option value="23">23</option><option value="24">24</option><option value="25">25</option><option value="26">26</option><option value="27">27</option><option value="28">28</option><option value="29">29</option><option value="30">30</option><option value="31">31</option></select>';
		$htmlOut .= '<select name="finish_date1:m" class="date_m">';
		$htmlOut .= '<option value="0">ماه</option><option value="1">فروردین</option><option value="2">اردیبهشت</option><option value="3">خرداد</option><option value="4">تیر</option><option value="5">مرداد</option><option value="6">شهریور</option><option value="7">مهر</option><option value="8">آبان</option><option value="9">آذر</option><option value="10">دی</option><option value="11">بهمن</option><option value="12">اسفند</option></select>';
		$htmlOut .= '<input type="text" name="finish_date1:y" size="4" maxlength="4" class="date_y"';
		if (isset($_POST['finish_date1:y']) && $_POST['finish_date1:y'] !== '')
			$htmlOut .= ' value="' . htmlspecialchars($_POST['finish_date1:y']) . '"';
		$htmlOut .= ' /><span class="description">باید بعد از تاریخ شروع باشد.</span></div><br />';
		$htmlOut .= '<br /></fieldset>';

		$htmlOut .= '<br /><br /><fieldset style="width: 520px;"><legend>بازه زمانی دوم</legend>';
		$htmlOut .= '<label for="start_date2:d" class="required">از تاریخ:</label>';
		$htmlOut .= '<div class="date_picker required';
		if (isset($r['invalid']['start_date2']))
			$htmlOut .= ' invalid';
		$htmlOut .= '">';
		$htmlOut .= '<select name="start_date2:d" id="start_date2:d" class="date_d">';
		$htmlOut .= '<option value="0">روز</option><option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option><option value="5">5</option><option value="6">6</option><option value="7">7</option><option value="8">8</option><option value="9">9</option><option value="10">10</option><option value="11">11</option><option value="12">12</option><option value="13">13</option><option value="14">14</option><option value="15">15</option><option value="16">16</option><option value="17">17</option><option value="18">18</option><option value="19">19</option><option value="20">20</option><option value="21">21</option><option value="22">22</option><option value="23">23</option><option value="24">24</option><option value="25">25</option><option value="26">26</option><option value="27">27</option><option value="28">28</option><option value="29">29</option><option value="30">30</option><option value="31">31</option></select>';
		$htmlOut .= '<select name="start_date2:m" class="date_m">';
		$htmlOut .= '<option value="0">ماه</option><option value="1">فروردین</option><option value="2">اردیبهشت</option><option value="3">خرداد</option><option value="4">تیر</option><option value="5">مرداد</option><option value="6">شهریور</option><option value="7">مهر</option><option value="8">آبان</option><option value="9">آذر</option><option value="10">دی</option><option value="11">بهمن</option><option value="12">اسفند</option></select>';
		$htmlOut .= '<input type="text" name="start_date2:y" size="4" maxlength="4" class="date_y"';
		if (isset($_POST['start_date2:y']) && $_POST['start_date2:y'] !== '')
			$htmlOut .= ' value="' . htmlspecialchars($_POST['start_date2:y']) . '"';
		$htmlOut .= ' /></div><br />';
		$htmlOut .= '<label for="finish_date2:d" class="required">تا تاریخ:</label>';
		$htmlOut .= '<div class="date_picker required';
		if (isset($r['invalid']['finish_date2']))
			$htmlOut .= ' invalid';
		$htmlOut .= '">';
		$htmlOut .= '<select name="finish_date2:d" id="finish_date2:d" class="date_d">';
		$htmlOut .= '<option value="0">روز</option><option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option><option value="5">5</option><option value="6">6</option><option value="7">7</option><option value="8">8</option><option value="9">9</option><option value="10">10</option><option value="11">11</option><option value="12">12</option><option value="13">13</option><option value="14">14</option><option value="15">15</option><option value="16">16</option><option value="17">17</option><option value="18">18</option><option value="19">19</option><option value="20">20</option><option value="21">21</option><option value="22">22</option><option value="23">23</option><option value="24">24</option><option value="25">25</option><option value="26">26</option><option value="27">27</option><option value="28">28</option><option value="29">29</option><option value="30">30</option><option value="31">31</option></select>';
		$htmlOut .= '<select name="finish_date2:m" class="date_m">';
		$htmlOut .= '<option value="0">ماه</option><option value="1">فروردین</option><option value="2">اردیبهشت</option><option value="3">خرداد</option><option value="4">تیر</option><option value="5">مرداد</option><option value="6">شهریور</option><option value="7">مهر</option><option value="8">آبان</option><option value="9">آذر</option><option value="10">دی</option><option value="11">بهمن</option><option value="12">اسفند</option></select>';
		$htmlOut .= '<input type="text" name="finish_date2:y" size="4" maxlength="4" class="date_y"';
		if (isset($_POST['finish_date2:y']) && $_POST['finish_date2:y'] !== '')
			$htmlOut .= ' value="' . htmlspecialchars($_POST['finish_date2:y']) . '"';
		$htmlOut .= ' /><span class="description">باید بعد از تاریخ شروع باشد.</span></div><br />';
		$htmlOut .= '<br /></fieldset>';
		$htmlOut .= '<br /><br /><select name="location_type2" id="location_type2" class="validate first_opt_not_allowed">';
		$htmlOut .= '<option value="none">نوع مکان ...</option>';
		$htmlOut .= '<option value="town">شهرستان</option>';
		$htmlOut .= '<option value="center">مرکز</option>';
		$htmlOut .= '<option value="hygieneUnit">خانه بهداشت</option>';
		$htmlOut .= '<option value="unit">واحد</option>';
		$htmlOut .= '</select>';
		$htmlOut .= '<input type="button" value="انتخاب" onclick="addLocationOnce($(\'#location_type2 option:selected\').val());return false;" /><hr style="width: 540px; margin-top: 20px; margin-bottom: 20px;" />';
		$htmlOut .= '</div>';
		
		/*----------------------------------------------------------------------------------------------------------------------*/
		/*													REPORT THIRD TYPE													*/
		
		$htmlOut .= '<br /><div id="reportThirdType"><p style="font-weight: bold;">گام دوم:</p>';
		$htmlOut .= '<fieldset style="width: 520px;"><legend>بازه زمانی</legend>';
		$htmlOut .= '<label for="start_date_pie:d" class="required">از تاریخ:</label>';
		$htmlOut .= '<div class="date_picker required';
		if (isset($r['invalid']['start_date_pie']))
			$htmlOut .= ' invalid';
		$htmlOut .= '">';
		$htmlOut .= '<select name="start_date_pie:d" id="start_date_pie:d" class="date_d">';
		$htmlOut .= '<option value="0">روز</option><option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option><option value="5">5</option><option value="6">6</option><option value="7">7</option><option value="8">8</option><option value="9">9</option><option value="10">10</option><option value="11">11</option><option value="12">12</option><option value="13">13</option><option value="14">14</option><option value="15">15</option><option value="16">16</option><option value="17">17</option><option value="18">18</option><option value="19">19</option><option value="20">20</option><option value="21">21</option><option value="22">22</option><option value="23">23</option><option value="24">24</option><option value="25">25</option><option value="26">26</option><option value="27">27</option><option value="28">28</option><option value="29">29</option><option value="30">30</option><option value="31">31</option></select>';
		$htmlOut .= '<select name="start_date_pie:m" class="date_m">';
		$htmlOut .= '<option value="0">ماه</option><option value="1">فروردین</option><option value="2">اردیبهشت</option><option value="3">خرداد</option><option value="4">تیر</option><option value="5">مرداد</option><option value="6">شهریور</option><option value="7">مهر</option><option value="8">آبان</option><option value="9">آذر</option><option value="10">دی</option><option value="11">بهمن</option><option value="12">اسفند</option></select>';
		$htmlOut .= '<input type="text" name="start_date_pie:y" size="4" maxlength="4" class="date_y"';
		if (isset($_POST['start_date_pie:y']) && $_POST['start_date_pie:y'] !== '')
			$htmlOut .= ' value="' . htmlspecialchars($_POST['start_date_pie:y']) . '"';
		$htmlOut .= ' /></div><br />';
		$htmlOut .= '<label for="finish_date_pie:d" class="required">تا تاریخ:</label>';
		$htmlOut .= '<div class="date_picker required';
		if (isset($r['invalid']['finish_date_pie']))
			$htmlOut .= ' invalid';
		$htmlOut .= '">';
		$htmlOut .= '<select name="finish_date_pie:d" id="finish_date_pie:d" class="date_d">';
		$htmlOut .= '<option value="0">روز</option><option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option><option value="5">5</option><option value="6">6</option><option value="7">7</option><option value="8">8</option><option value="9">9</option><option value="10">10</option><option value="11">11</option><option value="12">12</option><option value="13">13</option><option value="14">14</option><option value="15">15</option><option value="16">16</option><option value="17">17</option><option value="18">18</option><option value="19">19</option><option value="20">20</option><option value="21">21</option><option value="22">22</option><option value="23">23</option><option value="24">24</option><option value="25">25</option><option value="26">26</option><option value="27">27</option><option value="28">28</option><option value="29">29</option><option value="30">30</option><option value="31">31</option></select>';
		$htmlOut .= '<select name="finish_date_pie:m" class="date_m">';
		$htmlOut .= '<option value="0">ماه</option><option value="1">فروردین</option><option value="2">اردیبهشت</option><option value="3">خرداد</option><option value="4">تیر</option><option value="5">مرداد</option><option value="6">شهریور</option><option value="7">مهر</option><option value="8">آبان</option><option value="9">آذر</option><option value="10">دی</option><option value="11">بهمن</option><option value="12">اسفند</option></select>';
		$htmlOut .= '<input type="text" name="finish_date_pie:y" size="4" maxlength="4" class="date_y"';
		if (isset($_POST['finish_date_pie:y']) && $_POST['finish_date_pie:y'] !== '')
			$htmlOut .= ' value="' . htmlspecialchars($_POST['finish_date_pie:y']) . '"';
		$htmlOut .= ' /><span class="description">باید بعد از تاریخ شروع باشد.</span></div><br />';
		$htmlOut .= '</fieldset><br /><br />';
		$htmlOut .= '<fieldset style="width: 520px; margin: 0 auto 0 auto;"><legend>منبع گزارش‌گیری</legend>';
		$htmlOut .= '<input type="radio" name="chart_source_type" id="chart_source_type_0" value="1" /><label for="chart_source_type_0">مقایسه ی تمام واحدهای یک مرکز</label><br />';
		$htmlOut .= '<input type="radio" name="chart_source_type" id="chart_source_type_1" value="2" /><label for="chart_source_type_1">مقایسه ی تمام مراکز یک شهرستان</label><br />';
		$htmlOut .= '<input type="radio" name="chart_source_type" id="chart_source_type_2" value="3" /><label for="chart_source_type_2">مقایسه ی تمام خانه های بهداشت یک مرکز</label><br /><hr style="width: 400px; margin-right: 0;" />';
		//$htmlOut .= '<input type="radio" name="chart_source_type" id="chart_source_type_3" value="4" /><label for="chart_source_type_3">مقایسه ی آیتم های هزینه ای یک واحد</label><br />';
		$htmlOut .= '<input type="radio" name="chart_source_type" id="chart_source_type_4" value="5" /><label for="chart_source_type_4">مقایسه ی آیتم های هزینه ای یک مرکز</label><br />';
		$htmlOut .= '<input type="radio" name="chart_source_type" id="chart_source_type_5" value="6" /><label for="chart_source_type_5">مقایسه ی آیتم های هزینه ای یک خانه بهداشت</label>';
		$htmlOut .= '</fieldset><br /><br />';
		$htmlOut .= '</div>';
		
		/*----------------------------------------------------------------------------------------------------------------------*/
		/*													REPORT FORTH TYPE													*/
		
		$htmlOut .= '<br /><div id="reportForthType"><p style="font-weight: bold;">گام دوم:</p>';
		$htmlOut .= '<fieldset style="width: 520px;"><legend>بازه زمانی</legend>';
		$htmlOut .= '<label for="start_date_total:d" class="required">از تاریخ:</label>';
		$htmlOut .= '<div class="date_picker required';
		if (isset($r['invalid']['start_date_total']))
			$htmlOut .= ' invalid';
		$htmlOut .= '">';
		$htmlOut .= '<select name="start_date_total:d" id="start_date_total:d" class="date_d">';
		$htmlOut .= '<option value="0">روز</option><option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option><option value="5">5</option><option value="6">6</option><option value="7">7</option><option value="8">8</option><option value="9">9</option><option value="10">10</option><option value="11">11</option><option value="12">12</option><option value="13">13</option><option value="14">14</option><option value="15">15</option><option value="16">16</option><option value="17">17</option><option value="18">18</option><option value="19">19</option><option value="20">20</option><option value="21">21</option><option value="22">22</option><option value="23">23</option><option value="24">24</option><option value="25">25</option><option value="26">26</option><option value="27">27</option><option value="28">28</option><option value="29">29</option><option value="30">30</option><option value="31">31</option></select>';
		$htmlOut .= '<select name="start_date_total:m" class="date_m">';
		$htmlOut .= '<option value="0">ماه</option><option value="1">فروردین</option><option value="2">اردیبهشت</option><option value="3">خرداد</option><option value="4">تیر</option><option value="5">مرداد</option><option value="6">شهریور</option><option value="7">مهر</option><option value="8">آبان</option><option value="9">آذر</option><option value="10">دی</option><option value="11">بهمن</option><option value="12">اسفند</option></select>';
		$htmlOut .= '<input type="text" name="start_date_total:y" size="4" maxlength="4" class="date_y"';
		if (isset($_POST['start_date_total:y']) && $_POST['start_date_total:y'] !== '')
			$htmlOut .= ' value="' . htmlspecialchars($_POST['start_date_total:y']) . '"';
		$htmlOut .= ' /></div><br />';
		$htmlOut .= '<label for="finish_date_total:d" class="required">تا تاریخ:</label>';
		$htmlOut .= '<div class="date_picker required';
		if (isset($r['invalid']['finish_date_total']))
			$htmlOut .= ' invalid';
		$htmlOut .= '">';
		$htmlOut .= '<select name="finish_date_total:d" id="finish_date_total:d" class="date_d">';
		$htmlOut .= '<option value="0">روز</option><option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option><option value="5">5</option><option value="6">6</option><option value="7">7</option><option value="8">8</option><option value="9">9</option><option value="10">10</option><option value="11">11</option><option value="12">12</option><option value="13">13</option><option value="14">14</option><option value="15">15</option><option value="16">16</option><option value="17">17</option><option value="18">18</option><option value="19">19</option><option value="20">20</option><option value="21">21</option><option value="22">22</option><option value="23">23</option><option value="24">24</option><option value="25">25</option><option value="26">26</option><option value="27">27</option><option value="28">28</option><option value="29">29</option><option value="30">30</option><option value="31">31</option></select>';
		$htmlOut .= '<select name="finish_date_total:m" class="date_m">';
		$htmlOut .= '<option value="0">ماه</option><option value="1">فروردین</option><option value="2">اردیبهشت</option><option value="3">خرداد</option><option value="4">تیر</option><option value="5">مرداد</option><option value="6">شهریور</option><option value="7">مهر</option><option value="8">آبان</option><option value="9">آذر</option><option value="10">دی</option><option value="11">بهمن</option><option value="12">اسفند</option></select>';
		$htmlOut .= '<input type="text" name="finish_date_total:y" size="4" maxlength="4" class="date_y"';
		if (isset($_POST['finish_date_total:y']) && $_POST['finish_date_total:y'] !== '')
			$htmlOut .= ' value="' . htmlspecialchars($_POST['finish_date_total:y']) . '"';
		$htmlOut .= ' /><span class="description">باید بعد از تاریخ شروع باشد.</span></div><br />';
		$htmlOut .= '</fieldset><br /><br />';
		$htmlOut .= '<fieldset style="width: 520px; margin: 0 auto 0 auto;"><legend>منبع گزارش‌گیری</legend>';
		$htmlOut .= '<input type="radio" name="TotalCostChart" id="TotalCostChart_0" value="1" /><label for="TotalCostChart_0">مقایسه ی تمام مراکز استان</label><br />';
		$htmlOut .= '<input type="radio" name="TotalCostChart" id="TotalCostChart_1" value="2" /><label for="TotalCostChart_1">مقایسه ی یک واحد در کل استان</label><br />';
		$htmlOut .= '<input type="radio" name="TotalCostChart" id="TotalCostChart_2" value="3" /><label for="TotalCostChart_2">مقایسه ی یک خدمت در کل استان</label><br />';
		$htmlOut .= '</fieldset><br /><br />';
		$htmlOut .= '</div>';
		
		/*----------------------------------------------------------------------------------------------------------------------*/
		/*													REPORT FIFTH TYPE													*/
		
		$htmlOut .= '<div id="reportFifthType"><p style="font-weight: bold;">گام دوم:</p>';
		$htmlOut .= '<fieldset style="width: 520px;"><legend>بازه زمانی</legend>';
		$htmlOut .= '<label for="start_date_incomes:d" class="required">از تاریخ:</label>';
		$htmlOut .= '<div class="date_picker required';
		if (isset($r['invalid']['start_date_incomes']))
			$htmlOut .= ' invalid';
		$htmlOut .= '">';
		$htmlOut .= '<select name="start_date_incomes:d" id="start_date_incomes:d" class="date_d">';
		$htmlOut .= '<option value="0">روز</option><option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option><option value="5">5</option><option value="6">6</option><option value="7">7</option><option value="8">8</option><option value="9">9</option><option value="10">10</option><option value="11">11</option><option value="12">12</option><option value="13">13</option><option value="14">14</option><option value="15">15</option><option value="16">16</option><option value="17">17</option><option value="18">18</option><option value="19">19</option><option value="20">20</option><option value="21">21</option><option value="22">22</option><option value="23">23</option><option value="24">24</option><option value="25">25</option><option value="26">26</option><option value="27">27</option><option value="28">28</option><option value="29">29</option><option value="30">30</option><option value="31">31</option></select>';
		$htmlOut .= '<select name="start_date_incomes:m" class="date_m">';
		$htmlOut .= '<option value="0">ماه</option><option value="1">فروردین</option><option value="2">اردیبهشت</option><option value="3">خرداد</option><option value="4">تیر</option><option value="5">مرداد</option><option value="6">شهریور</option><option value="7">مهر</option><option value="8">آبان</option><option value="9">آذر</option><option value="10">دی</option><option value="11">بهمن</option><option value="12">اسفند</option></select>';
		$htmlOut .= '<input type="text" name="start_date_incomes:y" size="4" maxlength="4" class="date_y"';
		if (isset($_POST['start_date_incomes:y']) && $_POST['start_date_incomes:y'] !== '')
			$htmlOut .= ' value="' . htmlspecialchars($_POST['start_date_incomes:y']) . '"';
		$htmlOut .= ' /></div><br />';
		$htmlOut .= '<label for="finish_date_incomes:d" class="required">تا تاریخ:</label>';
		$htmlOut .= '<div class="date_picker required';
		if (isset($r['invalid']['finish_date_incomes']))
			$htmlOut .= ' invalid';
		$htmlOut .= '">';
		$htmlOut .= '<select name="finish_date_incomes:d" id="finish_date_incomes:d" class="date_d">';
		$htmlOut .= '<option value="0">روز</option><option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option><option value="5">5</option><option value="6">6</option><option value="7">7</option><option value="8">8</option><option value="9">9</option><option value="10">10</option><option value="11">11</option><option value="12">12</option><option value="13">13</option><option value="14">14</option><option value="15">15</option><option value="16">16</option><option value="17">17</option><option value="18">18</option><option value="19">19</option><option value="20">20</option><option value="21">21</option><option value="22">22</option><option value="23">23</option><option value="24">24</option><option value="25">25</option><option value="26">26</option><option value="27">27</option><option value="28">28</option><option value="29">29</option><option value="30">30</option><option value="31">31</option></select>';
		$htmlOut .= '<select name="finish_date_incomes:m" class="date_m">';
		$htmlOut .= '<option value="0">ماه</option><option value="1">فروردین</option><option value="2">اردیبهشت</option><option value="3">خرداد</option><option value="4">تیر</option><option value="5">مرداد</option><option value="6">شهریور</option><option value="7">مهر</option><option value="8">آبان</option><option value="9">آذر</option><option value="10">دی</option><option value="11">بهمن</option><option value="12">اسفند</option></select>';
		$htmlOut .= '<input type="text" name="finish_date_incomes:y" size="4" maxlength="4" class="date_y"';
		if (isset($_POST['finish_date_incomes:y']) && $_POST['finish_date_incomes:y'] !== '')
			$htmlOut .= ' value="' . htmlspecialchars($_POST['finish_date_incomes:y']) . '"';
		$htmlOut .= ' /><span class="description">باید بعد از تاریخ شروع باشد.</span></div><br />';
		$htmlOut .= '</fieldset><br /><br />';
		
		$htmlOut .= '<input type="checkbox" name="export5" id="export5" />';
		$htmlOut .= '<label for="export5">خروجی به فایل؟</label><br />';
		$htmlOut .= '<fieldset id="export5Fieldset" style="width: 520px;"><legend>نوع فایل خروجی</legend>';
		$htmlOut .= '<input type="radio" name="export5FileType" id="export5FileType_0" value="1" /><label for="export5FileType_0">Comma Seperated Values - CSV</label><br />';
		$htmlOut .= '<input type="radio" name="export5FileType" id="export5FileType_1" value="2" /><label for="export5FileType_1">XLSX (برای MS EXCEL 2007 و بالاتر و IBM SPSS)</label><br />';
		$htmlOut .= '<input type="radio" name="export5FileType" id="export5FileType_2" value="3" /><label for="export5FileType_2">Extensible Markup Language - XML</label>';
		$htmlOut .= '</fieldset><br /><br />';
		$htmlOut .= '<fieldset style="width: 520px; margin: 0 auto 0 auto;"><legend>منبع گزارش‌گیری</legend>';
		$htmlOut .= '<input type="radio" name="incomes" id="incomes_0" value="1" /><label for="incomes_0">درآمد تمام مراکز استان</label><br />';
		$htmlOut .= '<input type="radio" name="incomes" id="incomes_1" value="2" /><label for="incomes_1">درآمد یک واحد در کل استان</label><br />';
		$htmlOut .= '</fieldset><br /><br />';
		$htmlOut .= '</div>';
		
		$htmlOut .= '<input type="submit" value="ترسیم نمودار" name="drawChart" />';
		$htmlOut .= '</fieldset></form>';

		return $htmlOut;
	}

}//end of class
?>