<?php

/**
 *
 * administrator.php
 * administrator class file
 *
 * @copyright	Copyright (C) 2012 Rivas Systems Inc. All rights reserved.
 * @versin	1.00 2011-02-05
 * @author	Behnam Salili
 *
 */

require_once dirname(dirname(dirname(__file__))) . '/defines.php';
require_once dirname(dirname(__file__)) . '/database/database.php';
require_once dirname(dirname(__file__)) . '/illustrator/illustrator.php';
require_once dirname(dirname(__file__)) . '/statisticalPlotter/statisticalPlotter.php';
require_once dirname(dirname(__file__)) . '/statisticalPlotter/AsynchronousProccess.php';
require_once dirname(dirname(__file__)) . '/illustrator/pdate.php';
require_once dirname(dirname(__file__)) . '/form/form-proccessor.php';
require_once dirname(dirname(__file__)) . '/models/state.php';
require_once dirname(dirname(__file__)) . '/models/town.php';
require_once dirname(dirname(__file__)) . '/models/center.php';
require_once dirname(dirname(__file__)) . '/models/unit.php';
require_once dirname(dirname(__file__)) . '/models/hygiene-unit.php';
require_once dirname(dirname(__file__)) . '/models/sib.php';
require_once dirname(dirname(__file__)) . '/application/ea-sh.php';

class Administrator {

	private $pageTitle;
	private $viewer;
	private $ACLHomeLink;

	public function __construct($title) {
		$this -> pageTitle = $title;
		$this -> viewer = new Illustrator();
		State::setName('گیلان');
		$this -> ACLHomeLink = '';
	}

	public function loggedIn() {
		return (isset($_SESSION['user']) and ($_SESSION['user']['acl'] == 'State Manager' or $_SESSION['user']['acl'] == 'Town Manager' or $_SESSION['user']['acl'] == 'Center Manager' or $_SESSION['user']['acl'] == 'Unit Manager' or $_SESSION['user']['acl'] == 'Hygiene Unit Manager'));
	}

	public function render() {
		/*switch ($_SESSION['user']['acl'])
		 {
		 case 0:
		 $this->redirect('administrator.php');
		 break;
		 case 1:
		 $this->redirect('administrator.php?cmd=manageCenters&town=' . $_SESSION['user']['town']);
		 break;
		 case 2:
		 $this->redirect(APP_DIR . 'administrator.php');
		 break;
		 case 3:
		 $this->redirect(APP_DIR . 'author.php');
		 break;
		 case 4:
		 $this->redirect(APP_DIR . 'auditor.php');
		 break;
		 }*/

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

		if (isset($_POST['drawChart'])) {
			switch ($_POST['report_type']) {
				case '1' :
					$this -> viewer -> appendCenterBlock(AsynchronousProcess::drawFirstTypeChart());
					break;
				case '2' :
					$this -> viewer -> appendCenterBlock(AsynchronousProcess::drawSecondTypeChart());
					break;
				case '3' :
					$this -> viewer -> appendCenterBlock(AsynchronousProcess::drawThirdTypeChart());
					break;
				case '4' :
					$this -> viewer -> appendCenterBlock(AsynchronousProcess::drawForthTypeChart());
					break;
				case '5' :
					$this -> viewer -> appendCenterBlock(AsynchronousProcess::drawFifthTypeChart());
					break;
				default :
					$this -> viewer -> appendCenterBlock('<p id="err">خطا: نوع گزارش را انتخاب نکرده اید.</p>');
					break;
			}
		}

		if (isset($_GET['chartExportPage']))
			$this -> viewer -> illustrate(dirname(dirname(__file__)) . '/illustrator/chartExport.tpl');

		$this -> viewer -> setPageTitle($this -> pageTitle);
		//set header
		/*switch ( $_SESSION['user']['acl'] ) {
		 case 'State Manager' :
		 $this -> ACLHomeLink = '';
		 $this -> viewer -> setHeader('<h2><a href="' . substr(strrchr($_SERVER['PHP_SELF'], '/'), 1) . $this -> ACLHomeLink . '">بخش مدیریت سامانه</a></h2><h3>مدیریت استان "' . State::getName() . '"</h3>');
		 break;
		 case 'Town Manager' :
		 $this -> ACLHomeLink = '?cmd=manageCenters&townId=' . $_SESSION['user']['acl-id'];
		 $this -> viewer -> setHeader('<h2><a href="' . substr(strrchr($_SERVER['PHP_SELF'], '/'), 1) . $this -> ACLHomeLink . '">بخش مدیریت سامانه</a></h2><h3>مدیریت شهرستان "' . $_SESSION['user']['acl-name'] . '"</h3>');
		 break;
		 case 'Center Manager' :
		 $this -> ACLHomeLink = '?cmd=manageUnits&centerId=' . $_SESSION['user']['acl-id'];
		 $this -> viewer -> setHeader('<h2><a href="' . substr(strrchr($_SERVER['PHP_SELF'], '/'), 1) . $this -> ACLHomeLink . '">بخش مدیریت سامانه</a></h2><h3>مدیریت مرکز "' . $_SESSION['user']['acl-name'] . '"</h3>');
		 break;
		 case 'Unit Manager' :
		 $this -> ACLHomeLink = '?cmd=modifyUnits&unitId=' . $_SESSION['user']['acl-id'];
		 $this -> viewer -> setHeader('<h2><a href="' . substr(strrchr($_SERVER['PHP_SELF'], '/'), 1) . $this -> ACLHomeLink . '">بخش مدیریت سامانه</a></h2><h3>مدیریت واحد "' . $_SESSION['user']['acl-name'] . '"</h3>');
		 break;
		 case 'Hygiene Unit Manager' :
		 $this -> ACLHomeLink = '?cmd=modifyHygieneUnits&hygieneUnitId=' . $_SESSION['user']['acl-id'];
		 $this -> viewer -> setHeader('<h2><a href="' . substr(strrchr($_SERVER['PHP_SELF'], '/'), 1) . $this -> ACLHomeLink . '">بخش مدیریت سامانه</a></h2><h3>مدیریت خانه بهداشت "' . $_SESSION['user']['acl-name'] . '"</h3>');
		 break;
		 }*/

		/*
		 $dateObj = new DateTime();
		 $dateTimeZone = new DateTimeZone( 'Asia/Tehran' );
		 $dateObj->setTimezone( $dateTimeZone );
		 print_r( $dateObj->format( 'H:i:s' ) );
		 print_r( gregorian_to_jalali( $dateObj->format( 'Y' ), $dateObj->format( 'm' ), $dateObj->format( 'd' ) ) );
		 $date = pgetdate();
		 $this->viewer->appendDateBlock( 'امروز ' . $date['weekday'] . ' ' . $date['mday'] . ' ' . $date['month'] );
		 */

		if (isset($_GET['townId'])) {
			Town::setId($_GET['townId']);
			$_SESSION['townId'] = $_GET['townId'];
		}

		if (isset($_GET['centerId'])) {
			Center::setId($_GET['centerId']);
			$_SESSION['centerId'] = $_GET['centerId'];
		}

		if (isset($_GET['unitId'])) {
			Unit::setId($_GET['unitId']);
			$_SESSION['unitId'] = $_GET['unitId'];
		}

		if (isset($_GET['hygieneUnitId'])) {
			HygieneUnit::setId($_GET['hygieneUnitId']);
			$_SESSION['hygieneUnitId'] = $_GET['hygieneUnitId'];
		}

		if (isset($_GET['cmd'])) {
			if ($this -> hasPermission($_GET['cmd'])) {
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
				$this -> viewer -> appendCenterBlock(PERMISSION_DENIED);
		} else {
			if ($_SESSION['user']['acl'] == 'State Manager') {
				$this -> viewer -> appendRightBlock('<a href="' . $_SERVER['PHP_SELF'] . '"><img src="../illustrator/images/icons/home.png" />&nbsp;صفحه‌اصلی</a>');
				$this -> viewer -> appendRightBlock('<a href="?cmd=manageTowns"><img src="../illustrator/images/icons/towns.png" />&nbsp;شهرستان‌ها</a>');
				$this -> viewer -> appendRightBlock('<a href="?cmd=manageAvailableServices"><img src="../illustrator/images/icons/service.png" />&nbsp;خدمات موجود</a>');
				$this -> viewer -> appendRightBlock('<a href="?cmd=manageSibJobsAndPeriods"><img src="../illustrator/images/icons/sib.png">&nbsp;مدیریت جزئیات سیب</a>');
				$this -> viewer -> appendRightBlock('<a href="?cmd=manageAvailableJobTitles"><img src="../illustrator/images/icons/crm.png" />&nbsp;رده‌های پرسنلی</a>');
				$this -> viewer -> appendRightBlock('<a href="?cmd=manageAvailableDrugs"><img src="../illustrator/images/icons/drug.png" />&nbsp;داروها</a>');
				$this -> viewer -> appendRightBlock('<a href="?cmd=manageAvailableConsumingEquipments"><img src="../illustrator/images/icons/enjektor.png" />&nbsp;تجهیزات مصرفی</a>');
				$this -> viewer -> appendRightBlock('<a href="?cmd=manageAvailableNonConsumingEquipments"><img src="../illustrator/images/icons/non_consuming.png" />&nbsp;تجهیزات غیرمصرفی</a>');
				$this -> viewer -> appendRightBlock('<a href="?cmd=manageGeneralChargesTypes"><img src="../illustrator/images/icons/charge.png" />&nbsp;هزینه‌های عمومی</a>');
				$this -> viewer -> appendRightBlock('<a href="?cmd=reports"><img src="../illustrator/images/icons/chart-1.png" />&nbsp;گزارشات و نمودارها</a>');
				$this -> viewer -> appendRightBlock('<a href="?cmd=searchPersonnel"><img src="../illustrator/images/icons/search.png" />&nbsp;جستجوی پرسنل</a>');
				$this -> viewer -> appendRightBlock('<a href="?cmd=manageUsers"><img src="../illustrator/images/icons/users.png" />&nbsp;مدیریت کاربران</a>');
				$this -> viewer -> appendRightBlock('<a href="?cmd=manageEditFrequencyRequests"><img src="../illustrator/images/icons/editReq.png" />&nbsp;مدیریت اسناداصلاحی</a>');
				$this -> viewer -> appendRightBlock('<a href="?cmd=_LOGS"><img src="../illustrator/images/icons/log.png" />&nbsp;وقایع سیستم</a>');
				$this -> viewer -> appendRightBlock('<a href="?cmd=logOut"><img src="../illustrator/images/icons/exit.png" />&nbsp;خروج از سامانه</a>');

				if (!isset($_GET['success']) and !isset($_GET['es'])) {
					$this -> viewer -> appendCenterBlock('<p>به سامانه جامع مدیریت مراکز بهداشتی-درمانی خوش آمدید. برای شروع گزینه‌ی موردنظر را انتخاب نمایید.</p>');

					$this -> viewer -> appendCenterBlock('<br /><div id="topLevelEtitiesManagement" class="homeMenu"><p class="mngTitle">مدیریت زیرمجموعه‌های استان</p><ul class="homeMenuList">');
					$this -> viewer -> appendCenterBlock('<li><a href="?es=manageTowns">مدیریت شهرستان‌ها</a></li>');
					$this -> viewer -> appendCenterBlock('<li><a href="?es=manageCenters">مدیریت مراکز</a></li>');
					$this -> viewer -> appendCenterBlock('<li><a href="?es=manageHygieneUnits">مدیریت خانه‌های بهداشت</a></li>');
					$this -> viewer -> appendCenterBlock('<li><a href="?es=manageUnits">مدیریت واحدها</a></li>');
					$this -> viewer -> appendCenterBlock('</ul></div>');

					$this -> viewer -> appendCenterBlock('<div id="centerEtitiesManagement" class="homeMenu"><p class="mngTitle">مدیریت پارامترهای مراکز</p><ul class="homeMenuList">');
					$this -> viewer -> appendCenterBlock('<li><a href="?es=manageCentersDrugs">داروهای مراکز</a></li>');
					$this -> viewer -> appendCenterBlock('<li><a href="?es=manageCentersConsumings">تجهیزات مصرفی مراکز</a></li>');
					$this -> viewer -> appendCenterBlock('<li><a href="?es=manageCentersNonConsumings">تجهیزات غیرمصرفی مراکز</a></li>');
					$this -> viewer -> appendCenterBlock('<li><a href="?es=manageCentersBuildings">ساختمان‌های مراکز</a></li>');
					$this -> viewer -> appendCenterBlock('<li><a href="?es=manageCentersBuildingsCharge">هزینه نگهداری و تعمیرات ساختمان‌های مراکز</a></li>');
					$this -> viewer -> appendCenterBlock('<li><a href="?es=manageCentersVehicles">وسایل نقلیه مراکز</a></li>');
					$this -> viewer -> appendCenterBlock('<li><a href="?es=manageCentersVehiclesCharge">هزینه نگهداری و تعمیرات وسایل نقلیه مراکز</a></li>');
					$this -> viewer -> appendCenterBlock('<li><a href="?es=manageCentersGeneralCharges">هزینه‌های عمومی مراکز</a></li>');
					$this -> viewer -> appendCenterBlock('</ul></div>');

					$this -> viewer -> appendCenterBlock('<div id="hygieneUnitEtitiesManagement" class="homeMenu"><p class="mngTitle">مدیریت پارامترهای خانه‌های بهداشت</p><ul class="homeMenuList">');
					$this -> viewer -> appendCenterBlock('<li><a href="?es=manageHygieneUnitsDrugs">داروهای خانه‌های بهداشت</a></li>');
					$this -> viewer -> appendCenterBlock('<li><a href="?es=manageHygieneUnitsConsumings">تجهیزات مصرفی خانه‌های بهداشت</a></li>');
					$this -> viewer -> appendCenterBlock('<li><a href="?es=manageHygieneUnitsNonConsumings">تجهیزات غیرمصرفی خانه‌های بهداشت</a></li>');
					$this -> viewer -> appendCenterBlock('<li><a href="?es=manageHygieneUnitsBuildings">ساختمان‌های خانه‌های بهداشت</a></li>');
					$this -> viewer -> appendCenterBlock('<li><a href="?es=manageHygieneUnitsBuildingsCharge">هزینه نگهداری و تعمیرات ساختمان‌های خانه‌های بهداشت</a></li>');
					$this -> viewer -> appendCenterBlock('<li><a href="?es=manageHygieneUnitsVehicles">وسایل نقلیه خانه‌های بهداشت</a></li>');
					$this -> viewer -> appendCenterBlock('<li><a href="?es=manageHygieneUnitsVehiclesCharge">هزینه نگهداری و تعمیرات وسایل نقلیه خانه‌های بهداشت</a></li>');
					$this -> viewer -> appendCenterBlock('<li><a href="?es=manageHygieneUnitsGeneralCharges">هزینه‌های عمومی خانه‌های بهداشت</a></li>');
					$this -> viewer -> appendCenterBlock('<li><a href="?es=manageHygieneUnitsPersonnel">پرسنل خانه‌های بهداشت</a></li>');
					$this -> viewer -> appendCenterBlock('<li><a href="?es=manageHygieneUnitPersonnelFinancialInfo">اطلاعات مالی پرسنل خانه‌های بهداشت</a></li>');
					$this -> viewer -> appendCenterBlock('<li><a href="?es=manageHygieneUnitsServices">خدمات خانه‌های بهداشت</a></li>');
					$this -> viewer -> appendCenterBlock('</ul></div>');

					$this -> viewer -> appendCenterBlock('<div id="unitEtitiesManagement" class="homeMenu"><p class="mngTitle">مدیریت پارامترهای واحدها</p><ul class="homeMenuList">');
					$this -> viewer -> appendCenterBlock('<li><a href="?es=manageUnitsPersonnels">پرسنل واحدها</a></li>');
					$this -> viewer -> appendCenterBlock('<li><a href="?es=manageUnitPersonnelFinancialInfo">اطلاعات مالی پرسنل واحدها</a></li>');
					$this -> viewer -> appendCenterBlock('<li><a href="?es=manageUnitsServices">خدمات واحدها</a></li>');
					$this -> viewer -> appendCenterBlock('</ul></div><br />');

				}
			} else if (!isset($_GET['success']))
				$this -> viewer -> appendCenterBlock(PERMISSION_DENIED);
		}

		if (isset($_GET['es'])) {
			if ($this -> hasPermission($_GET['es'])) {
				$this -> viewer -> appendCenterBlock(ShortcutsView::processES($_GET['es']));
			} else
				$this -> viewer -> appendCenterBlock(PERMISSION_DENIED);
		}

		if (isset($_POST['submitAddTown'])) {
			$tmp = FormProccessor::proccessAddTownForm();
			if (is_array($tmp))
				$this -> viewer -> appendCenterBlock('<p id="err">' . $tmp['err_msg'] . '</p><p style="text-align: center;">[<a href="javascript:history.go(-1);">بازگشت</a>]</p>');
		}

		if (isset($_POST['submitEditTown'])) {
			$tmp = FormProccessor::proccessEditTownForm();
			if (is_array($tmp))
				$this -> viewer -> appendCenterBlock('<p id="err">' . $tmp['err_msg'] . '</p><p style="text-align: center;">[<a href="javascript:history.go(-1);">بازگشت</a>]</p>');
		}

		if (isset($_POST['submitAddCenter'])) {
			$tmp = FormProccessor::proccessAddCenterForm();
			if (is_array($tmp))
				$this -> viewer -> appendCenterBlock('<p id="err">' . $tmp['err_msg'] . '</p>');
		}

		if (isset($_POST['submitEditCenter'])) {
			$tmp = FormProccessor::proccessEditCenterForm();
			if (is_array($tmp))
				$this -> viewer -> appendCenterBlock('<p id="err">' . $tmp['err_msg'] . '</p>');
		}

		if (isset($_POST['submitAddUnit'])) {
			$tmp = FormProccessor::proccessAddUnitForm();
			if (is_array($tmp))
				$this -> viewer -> appendCenterBlock('<p id="err">' . $tmp['err_msg'] . '</p>');
		}

		if (isset($_POST['submitEditUnit'])) {
			$tmp = FormProccessor::proccessEditUnitForm();
			if (is_array($tmp))
				$this -> viewer -> appendCenterBlock('<p id="err">' . $tmp['err_msg'] . '</p>');
		}

		if (isset($_POST['submitAddHygieneUnit'])) {
			$tmp = FormProccessor::proccessAddHygieneUnitForm();
			if (is_array($tmp))
				$this -> viewer -> appendCenterBlock('<p id="err">' . $tmp['err_msg'] . '</p>');
		}

		if (isset($_POST['submitEditHygieneUnit'])) {
			$tmp = FormProccessor::proccessEditHygieneUnitForm();
			if (is_array($tmp))
				$this -> viewer -> appendCenterBlock('<p id="err">' . $tmp['err_msg'] . '</p>');
		}

		if (isset($_POST['submitAddService'])) {
			$tmp = FormProccessor::proccessAddServiceForm();
			if (is_array($tmp))
				$this -> viewer -> appendCenterBlock('<p id="err">' . $tmp['err_msg'] . '</p>');
		}

		if (isset($_POST['submitBatchAddService'])) {
			$tmp = FormProccessor::proccessBatchAddServiceForm();
			if (is_array($tmp))
				$this -> viewer -> appendCenterBlock('<p id="err">' . $tmp['err_msg'] . '</p>');
		}

		if(isset($_POST['submitSearchServices'])) {
		    $tmp = FormProccessor::processSearchServiceName();
            if (is_array($tmp))
                $this -> viewer -> appendCenterBlock('<p id="err">' . $tmp['err_msg'] . '</p>');
        }

		if (isset($_POST['submitEditService'])) {
			$tmp = FormProccessor::proccessEditServiceForm();
			if (is_array($tmp))
				$this -> viewer -> appendCenterBlock('<p id="err">' . $tmp['err_msg'] . '</p>');
		}

		if (isset($_POST['submitAddJobTitle'])) {
			$tmp = FormProccessor::proccessAddAvailableJobTitlesForm();
			if (is_array($tmp))
				$this -> viewer -> appendCenterBlock('<p id="err">' . $tmp['err_msg'] . '</p>');
		}

		if (isset($_POST['submitEditJobTitle'])) {
			$tmp = FormProccessor::proccessEditAvailableJobTitlesForm();
			if (is_array($tmp))
				$this -> viewer -> appendCenterBlock('<p id="err">' . $tmp['err_msg'] . '</p>');
		}

		if (isset($_POST['submitAddDrug'])) {
			$tmp = FormProccessor::proccessAddAvailableDrugsFrom();
			if (is_array($tmp))
				$this -> viewer -> appendCenterBlock('<p id="err">' . $tmp['err_msg'] . '</p>');
		}

		if (isset($_POST['submitEditDrug'])) {
			$tmp = FormProccessor::proccessEditAvailableDrugsFrom();
			if (is_array($tmp))
				$this -> viewer -> appendCenterBlock('<p id="err">' . $tmp['err_msg'] . '</p>');
		}

		if (isset($_POST['submitEditConsuminEquipment'])) {
			$tmp = FormProccessor::proccessEditAvailableConsumingEquipmentsForm();
			if (is_array($tmp))
				$this -> viewer -> appendCenterBlock('<p id="err">' . $tmp['err_msg'] . '</p>');
		}

		if (isset($_POST['submitAddConsumingEquipment'])) {
			$tmp = FormProccessor::proccessAddAvailableConsumingEquipmentsFrom();
			if (is_array($tmp))
				$this -> viewer -> appendCenterBlock('<p id="err">' . $tmp['err_msg'] . '</p>');
		}

		if (isset($_POST['submitaddAvailableNonConsumingEquipment'])) {
			$tmp = FormProccessor::proccessAddAvailableNonConsumingEquipmentsForm();
			if (is_array($tmp))
				$this -> viewer -> appendCenterBlock('<p id="err">' . $tmp['err_msg'] . '</p>');
		}

		if (isset($_POST['submitEditAvailableNonConsumingEquipment'])) {
			$tmp = FormProccessor::proccessEditAvailableNonConsumingEquipmentsForm();
			if (is_array($tmp))
				$this -> viewer -> appendCenterBlock('<p id="err">' . $tmp['err_msg'] . '</p>');
		}

		if (isset($_POST['submitAddUnitService'])) {
			$tmp = FormProccessor::proccessAddUnitServiceForm();
			if ($tmp === true) {
				header('Location: ' . substr(strrchr($_SERVER['PHP_SELF'], '/'), 1) . '?success');
				exit ;
			} else
				$this -> viewer -> appendCenterBlock($tmp);
		}

		if (isset($_POST['submit_eash_AddUnitService'])) {
			$tmp = FormProccessor::proccess_eash_AddUnitServiceForm();
			if ($tmp === true) {
				header('Location: ' . substr(strrchr($_SERVER['PHP_SELF'], '/'), 1) . '?success');
				exit ;
			} else
				$this -> viewer -> appendCenterBlock($tmp);
		}

		if (isset($_POST['submitAddCenterDrugsAndConsumingEquipments'])) {
			$tmp = FormProccessor::proccessAddCenterDrugsAndConsumingEquipmentsForm();
			if ($tmp === true) {
				header('Location: ' . substr(strrchr($_SERVER['PHP_SELF'], '/'), 1) . '?success');
				exit ;
			} else
				$this -> viewer -> appendCenterBlock($tmp);
		}

		if (isset($_POST['submit_eash_AddCenterDrugsAndConsumingEquipments'])) {
			$tmp = FormProccessor::proccess_eash_AddCenterDrugsAndConsumingEquipmentsForm();
			if ($tmp === true) {
				header('Location: ' . substr(strrchr($_SERVER['PHP_SELF'], '/'), 1) . '?success');
				exit ;
			} else
				$this -> viewer -> appendCenterBlock($tmp);
		}

		if (isset($_POST['submitAddCenterNonConsumingEquipments'])) {
			$tmp = FormProccessor::proccessAddCenterNonConsumingEquipmentsForm();
			if ($tmp === true) {
				header('Location: ' . substr(strrchr($_SERVER['PHP_SELF'], '/'), 1) . '?success');
				exit ;
			} else
				$this -> viewer -> appendCenterBlock($tmp);
		}

		if (isset($_POST['submit_eash_AddCenterNonConsumingEquipments'])) {
			$tmp = FormProccessor::proccess_eash_AddCenterNonConsumingEquipmentsForm();
			if ($tmp === true) {
				header('Location: ' . substr(strrchr($_SERVER['PHP_SELF'], '/'), 1) . '?success');
				exit ;
			} else
				$this -> viewer -> appendCenterBlock($tmp);
		}

		if (isset($_POST['submitAddHygieneUnitService'])) {
			$tmp = FormProccessor::proccessAddHygieneUnitServiceForm();
			if ($tmp === true) {
				header('Location: ' . substr(strrchr($_SERVER['PHP_SELF'], '/'), 1) . '?success');
				exit ;
			} else
				$this -> viewer -> appendCenterBlock($tmp);
		}

		if (isset($_POST['submit_eash_AddHygieneUnitService'])) {
			$tmp = FormProccessor::proccess_eash_AddHygieneUnitServiceForm();
			if ($tmp === true) {
				header('Location: ' . substr(strrchr($_SERVER['PHP_SELF'], '/'), 1) . '?success');
				exit ;
			} else
				$this -> viewer -> appendCenterBlock($tmp);
		}

		if (isset($_POST['submitAddUnitPersonnel'])) {
			$tmp = FormProccessor::proccessAddUnitPersonnelForm();
			if (is_array($tmp))
				$this -> viewer -> appendCenterBlock('<p id="err">' . $tmp['err_msg'] . '</p>');
		}

		if (isset($_POST['submitEditUnitPersonnel'])) {
			$tmp = FormProccessor::proccessEditUnitPersonnelForm();
			if (is_array($tmp))
				$this -> viewer -> appendCenterBlock('<p id="err">' . $tmp['err_msg'] . '</p>');
		}

		if (isset($_POST['submit_eash_AddUnitPersonnel'])) {
			$tmp = FormProccessor::proccess_eash_AddUnitPersonnelForm();
			if (is_array($tmp))
				$this -> viewer -> appendCenterBlock('<p id="err">' . $tmp['err_msg'] . '</p>');
		}

		if (isset($_POST['submitAddHygieneUnitPersonnel'])) {
			$tmp = FormProccessor::proccessAddHygieneUnitBehvarzForm();
			if (is_array($tmp))
				$this -> viewer -> appendCenterBlock('<p id="err">' . $tmp['err_msg'] . '</p>');
		}

		if (isset($_POST['submit_eash_AddHygieneUnitPersonnel'])) {
			$tmp = FormProccessor::proccess_eash_AddHygieneUnitBehvarzForm();
			if (is_array($tmp))
				$this -> viewer -> appendCenterBlock('<p id="err">' . $tmp['err_msg'] . '</p>');
		}

		if (isset($_POST['submitAddCenterBuilding'])) {
			$tmp = FormProccessor::proccessAddCenterBuildingForm();
			if (is_array($tmp))
				$this -> viewer -> appendCenterBlock('<p id="err">' . $tmp['err_msg'] . '</p>');
		}

		if (isset($_POST['submitEditCenterBuilding'])) {
			$tmp = FormProccessor::proccessEditCenterBuildingForm();
			if (is_array($tmp))
				$this -> viewer -> appendCenterBlock('<p id="err">' . $tmp['err_msg'] . '</p>');
		}

		if (isset($_POST['submit_eash_AddCenterBuilding'])) {
			$tmp = FormProccessor::proccess_eash_AddCenterBuildingForm();
			if (is_array($tmp))
				$this -> viewer -> appendCenterBlock('<p id="err">' . $tmp['err_msg'] . '</p>');
		}

		if (isset($_POST['submitAddCenterBuildingCharge'])) {
			$tmp = FormProccessor::proccessAddCenterBuildingChargeForm();
			if (is_array($tmp))
				$this -> viewer -> appendCenterBlock('<p id="err">' . $tmp['err_msg'] . '</p>');
		}

		if (isset($_POST['submitEditCenterBuildingCharge'])) {
			$tmp = FormProccessor::proccessEditCenterBuildingChargeForm();
			if (is_array($tmp))
				$this -> viewer -> appendCenterBlock('<p id="err">' . $tmp['err_msg'] . '</p>');
		}

		if (isset($_POST['submitAddCenterVehicle'])) {
			$tmp = FormProccessor::proccessAddCenterVehicleForm();
			if (is_array($tmp))
				$this -> viewer -> appendCenterBlock('<p id="err">' . $tmp['err_msg'] . '</p>');
		}

		if (isset($_POST['submitEditCenterVehicle'])) {
			$tmp = FormProccessor::processEditCenterVehicleForm();
			if (is_array($tmp))
				$this -> viewer -> appendCenterBlock('<p id="err">' . $tmp['err_msg'] . '</p>');
		}

		if (isset($_POST['submit_eash_AddCenterVehicle'])) {
			$tmp = FormProccessor::proccess_eash_AddCenterVehicleForm();
			if (is_array($tmp))
				$this -> viewer -> appendCenterBlock('<p id="err">' . $tmp['err_msg'] . '</p>');
		}

		if (isset($_POST['submitAddCenterVehicleCharge'])) {
			$tmp = FormProccessor::proccessAddCenterVehicleChargeForm();
			if (is_array($tmp))
				$this -> viewer -> appendCenterBlock('<p id="err">' . $tmp['err_msg'] . '</p>');
		}

		if (isset($_POST['submitEditCenterVehicleCharge'])) {
			$tmp = FormProccessor::proccessEditCenterVehicleChargeForm();
			if (is_array($tmp))
				$this -> viewer -> appendCenterBlock('<p id="err">' . $tmp['err_msg'] . '</p>');
		}

		if (isset($_POST['submitAddGeneralChargeType'])) {
			$tmp = FormProccessor::proccessAddGeneralChargeTypeForm();
			if (is_array($tmp))
				$this -> viewer -> appendCenterBlock('<p id="err">' . $tmp['err_msg'] . '</p>');
		}

		if (isset($_POST['submitEditGeneralChargeType'])) {
			$tmp = FormProccessor::proccessEditGeneralChargeTypeForm();
			if (is_array($tmp))
				$this -> viewer -> appendCenterBlock('<p id="err">' . $tmp['err_msg'] . '</p>');
		}

		if (isset($_POST['submitAddCenterGeneralCharge'])) {
			$tmp = FormProccessor::proccessAddCenterGeneralChargeForm();
			if (is_array($tmp))
				$this -> viewer -> appendCenterBlock('<p id="err">' . $tmp['err_msg'] . '</p>');
		}

		if (isset($_POST['submitEditCenterGeneralCharge'])) {
			$tmp = FormProccessor::proccessEditCenterGeneralChargeForm();
			if (is_array($tmp))
				$this -> viewer -> appendCenterBlock('<p id="err">' . $tmp['err_msg'] . '</p>');
		}

		if (isset($_POST['submit_eash_AddCenterGeneralCharge'])) {
			$tmp = FormProccessor::proccess_eash_AddCenterGeneralChargeForm();
			if (is_array($tmp))
				$this -> viewer -> appendCenterBlock('<p id="err">' . $tmp['err_msg'] . '</p>');
		}

		if (isset($_POST['submitSearchPersonnel'])) {
			Unit::setId($_SESSION['unitId']);
			$this -> viewer -> appendCenterBlock(Unit::personnelSearchResult());
		}

		if (isset($_POST['submitRegisterUnitPersonnelCost'])) {
			$tmp = FormProccessor::proccessRegisterUnitPersonnelCost();
			if (is_array($tmp))
				$this -> viewer -> appendCenterBlock('<p id="err">' . $tmp['err_msg'] . '</p>');
		}

		if (isset($_POST['submitSearchBehvarz'])) {
			HygieneUnit::setId($_SESSION['hygieneUnitId']);
			$this -> viewer -> appendCenterBlock(HygieneUnit::behvarzSearchResult());
		}

		if (isset($_POST['submitRegisterBehvarzCost'])) {
			$tmp = FormProccessor::proccessRegisterBehvarzCost();
			if (is_array($tmp))
				$this -> viewer -> appendCenterBlock('<p id="err">' . $tmp['err_msg'] . '</p>');
		}

		if (isset($_POST['submitAddHygieneUnitDrugsAndConsumingEquipments'])) {
			$tmp = FormProccessor::proccessAddHygieneUnitDrugsAndConsumingEquipmentsForm();
			if ($tmp === true) {
				header('Location: ' . substr(strrchr($_SERVER['PHP_SELF'], '/'), 1) . '?success');
				exit ;
			} else
				$this -> viewer -> appendCenterBlock($tmp);
		}

		if (isset($_POST['submit_eash_AddHygieneUnitDrugsAndConsumingEquipments'])) {
			$tmp = FormProccessor::proccess_eash_AddHygieneUnitDrugsAndConsumingEquipmentsForm();
			if ($tmp === true) {
				header('Location: ' . substr(strrchr($_SERVER['PHP_SELF'], '/'), 1) . '?success');
				exit ;
			} else
				$this -> viewer -> appendCenterBlock($tmp);
		}

		if (isset($_POST['submitAddHygieneUnitNonConsumingEquipments'])) {
			$tmp = FormProccessor::proccessAddHygieneUnitNonConsumingEquipmentsForm();
			if ($tmp === true) {
				header('Location: ' . substr(strrchr($_SERVER['PHP_SELF'], '/'), 1) . '?success');
				exit ;
			} else
				$this -> viewer -> appendCenterBlock($tmp);
		}

		if (isset($_POST['submit_eash_AddHygieneUnitNonConsumingEquipments'])) {
			$tmp = FormProccessor::proccess_eash_AddHygieneUnitNonConsumingEquipmentsForm();
			if ($tmp === true) {
				header('Location: ' . substr(strrchr($_SERVER['PHP_SELF'], '/'), 1) . '?success');
				exit ;
			} else
				$this -> viewer -> appendCenterBlock($tmp);
		}

		if (isset($_POST['submitAddHygieneUnitBuilding'])) {
			$tmp = FormProccessor::proccessAddHygieneUnitBuildingForm();
			if (is_array($tmp))
				$this -> viewer -> appendCenterBlock('<p id="err">' . $tmp['err_msg'] . '</p>');
		}

		if (isset($_POST['submitEditHygieneUnitBuilding'])) {
			$tmp = FormProccessor::proccessEditHygieneUnitBuildingForm();
			if (is_array($tmp))
				$this -> viewer -> appendCenterBlock('<p id="err">' . $tmp['err_msg'] . '</p>');
		}

		if (isset($_POST['submit_eash_AddHygieneUnitBuilding'])) {
			$tmp = FormProccessor::proccess_eash_AddHygieneUnitBuildingForm();
			if (is_array($tmp))
				$this -> viewer -> appendCenterBlock('<p id="err">' . $tmp['err_msg'] . '</p>');
		}

		if (isset($_POST['submitEditHygieneUnitPersonnel'])) {
			$tmp = FormProccessor::proccessEditHygieneUnitBehvarzForm();
			if (is_array($tmp))
				$this -> viewer -> appendCenterBlock('<p id="err">' . $tmp['err_msg'] . '</p>');
		}

		if (isset($_POST['submitAddHygieneUnitBuildingCharge'])) {
			$tmp = FormProccessor::proccessAddHygieneUnitBuildingChargeForm();
			if (is_array($tmp))
				$this -> viewer -> appendCenterBlock('<p id="err">' . $tmp['err_msg'] . '</p>');
		}

		if (isset($_POST['submitEditHygieneUnitBuildingCharge'])) {
			$tmp = FormProccessor::proccessEditHygieneUnitBuildingChargeForm();
			if (is_array($tmp))
				$this -> viewer -> appendCenterBlock('<p id="err">' . $tmp['err_msg'] . '</p>');
		}

		if (isset($_POST['submitAddHygieneUnitVehicle'])) {
			$tmp = FormProccessor::proccessAddHygieneUnitVehicleForm();
			if (is_array($tmp))
				$this -> viewer -> appendCenterBlock('<p id="err">' . $tmp['err_msg'] . '</p>');
		}

		if (isset($_POST['submitEditHygieneUnitVehicle'])) {
			$tmp = FormProccessor::processEditHygieneUnitVehicleForm();
			if (is_array($tmp))
				$this -> viewer -> appendCenterBlock('<p id="err">' . $tmp['err_msg'] . '</p>');
		}

		if (isset($_POST['submit_eash_AddHygieneUnitVehicle'])) {
			$tmp = FormProccessor::proccess_eash_AddHygieneUnitVehicleForm();
			if (is_array($tmp))
				$this -> viewer -> appendCenterBlock('<p id="err">' . $tmp['err_msg'] . '</p>');
		}

		if (isset($_POST['submitAddHygieneUnitVehicleCharge'])) {
			$tmp = FormProccessor::proccessAddHygieneUnitVehicleChargeForm();
			if (is_array($tmp))
				$this -> viewer -> appendCenterBlock('<p id="err">' . $tmp['err_msg'] . '</p>');
		}

		if (isset($_POST['submitEditHygieneUnitVehicleCharge'])) {
			$tmp = FormProccessor::proccessEditHygieneUnitVehicleChargeForm();
			if (is_array($tmp))
				$this -> viewer -> appendCenterBlock('<p id="err">' . $tmp['err_msg'] . '</p>');
		}

		if (isset($_POST['submitAddHygieneUnitGeneralCharge'])) {
			$tmp = FormProccessor::proccessAddHygieneUnitGeneralChargeForm();
			if (is_array($tmp))
				$this -> viewer -> appendCenterBlock('<p id="err">' . $tmp['err_msg'] . '</p>');
		}

		if (isset($_POST['submitEditHygieneUnitGeneralCharge'])) {
			$tmp = FormProccessor::proccessEditHygieneUnitGeneralChargeForm();
			if (is_array($tmp))
				$this -> viewer -> appendCenterBlock('<p id="err">' . $tmp['err_msg'] . '</p>');
		}

		if (isset($_POST['submit_eash_AddHygieneUnitGeneralCharge'])) {
			$tmp = FormProccessor::proccess_eash_AddHygieneUnitGeneralChargeForm();
			if (is_array($tmp))
				$this -> viewer -> appendCenterBlock('<p id="err">' . $tmp['err_msg'] . '</p>');
		}

		if (isset($_POST['submitAddUser'])) {
			$tmp = FormProccessor::processAddUserForm();
			if (is_array($tmp))
				$this -> viewer -> appendCenterBlock('<p id="err">' . $tmp['err_msg'] . '</p>');
		}

		if (isset($_POST['submitEditUser'])) {
			$tmp = FormProccessor::processEditUserForm();
			if (is_array($tmp))
				$this -> viewer -> appendCenterBlock('<p id="err">' . $tmp['err_msg'] . '</p>');
		}

		if(isset($_POST['submitAddSibJobTitle'])) {
		    $tmp = FormProccessor::processAddSibJobTitleForm();
		    if(is_array($tmp)) {
		        $this -> viewer -> appendCenterBlock('<p id="err">' . $tmp['err_msg'] . '</p>');
            }
        }

        if (isset($_POST['submitEditSibJobTitle'])) {
            $tmp = FormProccessor::proccessEditSibJobTitleForm();
            if (is_array($tmp))
                $this -> viewer -> appendCenterBlock('<p id="err">' . $tmp['err_msg'] . '</p><p style="text-align: center;">[<a href="javascript:history.go(-1);">بازگشت</a>]</p>');
        }

        if(isset($_POST['submitAddSibPeriod'])) {
            $tmp = FormProccessor::processAddSibPeriodForm();
            if(is_array($tmp)) {
                $this -> viewer -> appendCenterBlock('<p id="err">' . $tmp['err_msg'] . '</p>');
            }
        }

        if (isset($_POST['submitEditSibPeriod'])) {
            $tmp = FormProccessor::proccessEditSibPeriodForm();
            if (is_array($tmp))
                $this -> viewer -> appendCenterBlock('<p id="err">' . $tmp['err_msg'] . '</p><p style="text-align: center;">[<a href="javascript:history.go(-1);">بازگشت</a>]</p>');
        }

		if (isset($_GET['success']))
			$this -> viewer -> appendCenterBlock(DATA_REGISTER_CONFIRMATION);

		$this -> viewer -> setFooter('<span>طراحی و پیاده سازی در <a href="http://www.rivasit.com">شرکت ریواس سیستم پارس</a>.</span>');

		if (!isset($_GET['chartExportPage']))
			$this -> viewer -> illustrate(dirname(dirname(__file__)) . '/illustrator/index.tpl');

	}

	private function prepareMenu($cmd) {
		$res = '';
		switch ( $cmd ) {
			case 'manageTowns' :
			case 'editTown' :
			case 'manageAvailableServices' :
            case 'manageSibJobsAndPeriods':
			case 'editService' :
			case 'manageAvailableJobTitles' :
			case 'editJobtitle' :
			case 'manageAvailableDrugs' :
			case 'editDrug' :
			case 'manageAvailableNonConsumingEquipments' :
			case 'editNonConsumingEquipment' :
			case 'manageGeneralChargesTypes' :
			case 'removeGeneralChargeType' :
			case 'manageUsers' :
			case 'editUser' :
			case 'reports' :
			case 'editCenterVehicle' :
			case 'editHygieneUnitVehicle' :
			case 'editCenterBuilding' :
			case 'editHygieneUnitBuilding' :
			case 'manageEditFrequencyRequests' :
			case 'viewEditReqDetail' :
			case '_LOGS' :
				$res = State::viewActionsMenu();
				break;

			case 'manageCenters' :
				if (isset($_GET['townId'])) {
					$resDB = Database::execute_query("SELECT `id` FROM `towns` WHERE `id` = '" . Database::filter_str($_GET['townId']) . "';");
					if (Database::num_of_rows($resDB) > 0)
						$res = Town::viewActionsMenu();
				} else
					header('Location: ' . substr(strrchr($_SERVER['PHP_SELF'], '/'), 1));
				break;

			case 'editCenter' :
			case 'editHygieneUnit' :
				$res = Town::viewActionsMenu();
				break;

			case 'manageUnits' :
				if (isset($_GET['centerId'])) {
					$resDB = Database::execute_query("SELECT `id` FROM `centers` WHERE `id` = '" . Database::filter_str($_GET['centerId']) . "';");
					if (Database::num_of_rows($resDB) > 0)
						$res = Center::viewActionsMenu();
				} else
					header('Location: ' . substr(strrchr($_SERVER['PHP_SELF'], '/'), 1));
				break;

			case 'editUnit' :
			case 'manageCenterDrugs' :
			case 'manageCenterConsumingEquipments':
			case 'addCenterNonConsumingEquipments' :
			case 'addCenterGeneralCharge' :
			case 'manageCenterBuildings' :
			case 'removeCenterBuilding' :
			case 'addCenterBuildingCharge' :
			case 'manageCenterVehicles' :
			case 'removeCenterVehicle' :
			case 'addCenterVehicleCharge' :
				$res = Center::viewActionsMenu();
				break;

			case 'modifyUnits' :
				if (isset($_GET['unitId'])) {
					$resDB = Database::execute_query("SELECT `id` FROM `units` WHERE `id` = '" . Database::filter_str($_GET['unitId']) . "';");
					if (Database::num_of_rows($resDB) > 0)
						$res = Unit::viewActionsMenu();
				} else
					header('Location: ' . substr(strrchr($_SERVER['PHP_SELF'], '/'), 1));
				break;

			case 'manageUnitServices' :
			case 'removeUnitService' :
			case 'manageUnitPersonnel' :
			case 'removeUnitPersonnel' :
			case 'unitPersonnelFinancialInfo' :
				$res = Unit::viewActionsMenu();
				break;

			case 'modifyHygieneUnits' :
				if (isset($_GET['hygieneUnitId'])) {
					$resDB = Database::execute_query("SELECT `id` FROM `hygiene-units` WHERE `id` = '" . Database::filter_str($_GET['hygieneUnitId']) . "';");
					if (Database::num_of_rows($resDB) > 0)
						$res = HygieneUnit::viewActionsMenu();
				} else
					header('Location: ' . substr(strrchr($_SERVER['PHP_SELF'], '/'), 1));
				break;

			case 'manageHygieneUnitServices' :
			case 'removeHygieneUnitService' :
			case 'addHygieneUnitDrugsAndConsumingEquipments' :
			case 'addHygieneUnitDrugs' :
			case 'addHygieneUnitConsumingEquipments' :
			case 'addHygieneUnitNonConsumingEquipments' :
			case 'manageHygieneUnitPersonnel' :
			case 'removeHygieneUnitPersonnel' :
			case 'hygieneUnitPersonnelFinancialInfo' :
			case 'addHygieneUnitGeneralCharge' :
			case 'manageHygieneUnitBuildings' :
			case 'removeHygieneUnitBuilding' :
			case 'addHygieneUnitBuildingCharge' :
			case 'manageHygieneUnitVehicles' :
			case 'removeHygieneUnitVehicle' :
			case 'addHygieneUnitVehicleCharge' :
				$res = HygieneUnit::viewActionsMenu();
				break;
			default :
				$res = State::viewActionsMenu();
				break;
		}

		return $res;
	}

	private function prepareContent($cmd) {
		$res = '';
		switch ( $cmd ) {
			case 'manageTowns' :
				$res = State::viewTowns();
				break;

			case 'removeTown' :
				if (isset($_GET['townId'])) {
					try {
						$res = State::removeTown($_GET['townId']);
					} catch ( exception $e ) {
					}
				} else
					$this -> redirect(substr(strrchr($_SERVER['PHP_SELF'], '/'), 1));
				break;

			case 'editTown' :
				if (isset($_GET['townId']))
					$res = State::editTown($_GET['townId']);
				else
					$this -> redirect(substr(strrchr($_SERVER['PHP_SELF'], '/'), 1));
				break;

			case 'manageCenters' :
				if (isset($_GET['townId'])) {
					$resDB = Database::execute_query("SELECT `id` FROM `towns` WHERE `id` = '" . Database::filter_str($_GET['townId']) . "';");
					if (Database::num_of_rows($resDB) > 0) {
						if ($this -> hasIdAccess('manageCenters', $_GET['townId'])) {
							Town::setId($_GET['townId']);
							$res = Town::viewCenters();
						} else
							$res = PERMISSION_DENIED;
					} else
						$res = '<p id="err">خطا: این صفحه وجود ندارد.<br />به <a href="' . substr(strrchr($_SERVER['PHP_SELF'], '/'), 1) . $this -> ACLHomeLink . '">[صفحه اصلی]</a> بازگردید.</p>';
				} else
					header('Location: ' . substr(strrchr($_SERVER['PHP_SELF'], '/'), 1));
				break;

			case 'removeCenter' :
				if (isset($_GET['centerId'])) {
					try {
						Town::setId($_SESSION['townId']);
						$res = Town::removeCenter($_GET['centerId']);
					} catch ( exception $e ) {
					}
				} else
					$this -> redirect(substr(strrchr($_SERVER['PHP_SELF'], '/'), 1));
				break;

			case 'editCenter' :
				if (isset($_GET['centerId'])) {
					Town::setId($_SESSION['townId']);
					$res = Town::editCenter($_GET['centerId']);
				} else
					$this -> redirect(substr(strrchr($_SERVER['PHP_SELF'], '/'), 1));
				break;

			case 'manageUnits' :
				if (isset($_GET['centerId'])) {
					$resDB = Database::execute_query("SELECT `id` FROM `centers` WHERE `id` = '" . Database::filter_str($_GET['centerId']) . "';");
					if (Database::num_of_rows($resDB) > 0) {
						if ($this -> hasIdAccess('manageUnits', $_GET['centerId'])) {
							Center::setId($_GET['centerId']);
							$res = Center::viewUnitsAndHygieneUnits();
						} else
							$res = PERMISSION_DENIED;
					} else
						$this -> viewer -> appendCenterBlock('<p id="err">خطا: این صفحه وجود ندارد.<br />به <a href="' . substr(strrchr($_SERVER['PHP_SELF'], '/'), 1) . $this -> ACLHomeLink . '">[صفحه اصلی]</a> بازگردید.</p>');
				} else
					header('Location: ' . substr(strrchr($_SERVER['PHP_SELF'], '/'), 1));
				break;

			case 'removeUnit' :
				if (isset($_GET['unitId'])) {
					try {
						Center::setId($_SESSION['centerId']);
						$res = Center::removeUnit($_GET['unitId']);
					} catch ( exception $e ) {
					}
				} else
					$this -> redirect(substr(strrchr($_SERVER['PHP_SELF'], '/'), 1));
				break;

			case 'editUnit' :
				if (isset($_GET['unitId'])) {
					Center::setId($_SESSION['centerId']);
					$res = Center::editUnit($_GET['unitId']);
				} else
					$this -> redirect(substr(strrchr($_SERVER['PHP_SELF'], '/'), 1));
				break;

			case 'modifyUnits' :
				if (isset($_GET['unitId'])) {
					$resDB = Database::execute_query("SELECT `id` FROM `units` WHERE `id` = '" . Database::filter_str($_GET['unitId']) . "';");
					if (Database::num_of_rows($resDB) > 0) {
						if ($this -> hasIdAccess('modifyUnits', $_GET['unitId'])) {
							Unit::setId($_GET['unitId']);
							$res = Unit::modifyUnitsContent();
						} else
							$res = PERMISSION_DENIED;
					} else
						$this -> viewer -> appendCenterBlock('<p id="err">خطا: این صفحه وجود ندارد.<br />به <a href="' . substr(strrchr($_SERVER['PHP_SELF'], '/'), 1) . $this -> ACLHomeLink . '">[صفحه اصلی]</a> بازگردید.</p>');
				} else
					header('Location: ' . substr(strrchr($_SERVER['PHP_SELF'], '/'), 1));
				break;

			case 'removeHygieneUnit' :
				if (isset($_GET['hygieneUnitId'])) {
					try {
						Town::setId($_SESSION['townId']);
						$res = Town::removeHygieneUnit($_GET['hygieneUnitId']);
					} catch ( exception $e ) {
					}
				} else
					$this -> redirect(substr(strrchr($_SERVER['PHP_SELF'], '/'), 1));
				break;

			case 'editHygieneUnit' :
				if (isset($_GET['hygieneUnitId'])) {
					Center::setId($_SESSION['centerId']);
					$res = Center::editHygieneUnit($_GET['hygieneUnitId']);
				} else
					$this -> redirect(substr(strrchr($_SERVER['PHP_SELF'], '/'), 1));
				break;

				case 'manageCenterDrugs' :
				if ($_SESSION['user']['acl-details']['addCenterDrugsAndConsumingEquipments'] or $_SESSION['user']['acl'] == 'State Manager') {
					Center::setId($_SESSION['centerId']);
					$res = Center::manageCenterDrugs();
				} else
					$res = PERMISSION_DENIED;
				break;

				case 'manageCenterConsumingEquipments' :
				if ($_SESSION['user']['acl'] == 'State Manager') {
					Center::setId($_SESSION['centerId']);
					$res = Center::manageCenterConsumingEquipments();
				} else
					$res = PERMISSION_DENIED;
				break;

			case 'removeCenterDrug' :
				if (isset($_GET['drugId'])) {
					try {
						$res = Center::removeCenterDrugsAndConsumingEquipments($_GET['drugId']);
					} catch ( exception $e ) {
					}
				} else
					$this -> redirect(substr(strrchr($_SERVER['PHP_SELF'], '/'), 1));
				break;

			case 'manageAvailableServices' :
			    if(!isset($_GET['p'])) $_GET['p'] = 1;
				$res = State::manageAvailableServices();
				break;

			case 'removeService' :
				if (isset($_GET['serviceId'])) {
					try {
						$res = State::removeService($_GET['serviceId']);
					} catch ( exception $e ) {
					}
				} else
					$this -> redirect(substr(strrchr($_SERVER['PHP_SELF'], '/'), 1));
				break;

			case 'editService' :
				if (isset($_GET['serviceId'])) {
					$res = State::editService($_GET['serviceId']);
				} else
					$this -> redirect(substr(strrchr($_SERVER['PHP_SELF'], '/'), 1));
				break;

			case 'manageAvailableJobTitles' :
				$res = State::manageAvailableJobTitles();
				break;

			case 'removeJobtitle' :
				if (isset($_GET['jobtitleId'])) {
					try {
						$res = State::removeJobTitle($_GET['jobtitleId']);
					} catch ( exception $e ) {
					}
				} else
					$this -> redirect(substr(strrchr($_SERVER['PHP_SELF'], '/'), 1));
				break;

			case 'editJobtitle' :
				if (isset($_GET['jobtitleId'])) {
					$res = State::editJobTitle($_GET['jobtitleId']);
				} else
					$this -> redirect(substr(strrchr($_SERVER['PHP_SELF'], '/'), 1));
				break;

			case 'manageAvailableDrugs' :
				$res = State::manageAvailableDrugs();
				break;

			case 'removeDrug' :
				if (isset($_GET['drugId'])) {
					try {
						$res = State::removeDrug($_GET['drugId']);
					} catch ( exception $e ) {
					}
				} else
					$this -> redirect(substr(strrchr($_SERVER['PHP_SELF'], '/'), 1));
				break;

			case 'editDrug' :
				if (isset($_GET['drugId'])) {
					$res = State::editDrug($_GET['drugId']);
				} else
					$this -> redirect(substr(strrchr($_SERVER['PHP_SELF'], '/'), 1));
				break;

			case 'manageAvailableConsumingEquipments' :
				$res = State::manageAvailableConsumingEquipments();
				break;

			case 'editConsumingEquip' :
				if (isset($_GET['id'])) {
					$res = State::editConsumingEquip($_GET['id']);
				} else
					$this -> redirect(substr(strrchr($_SERVER['PHP_SELF'], '/'), 1));
				break;

			case 'manageAvailableNonConsumingEquipments' :
				$res = State::manageAvailableNonConsumingEquipments();
				break;

			case 'removeNonConsumingEquipment' :
				if (isset($_GET['nonConsumingEquipmentId'])) {
					try {
						$res = State::removeAvailableNonConsumingEquipment($_GET['nonConsumingEquipmentId']);
					} catch ( exception $e ) {
					}
				} else
					$this -> redirect(substr(strrchr($_SERVER['PHP_SELF'], '/'), 1));
				break;

			case 'editNonConsumingEquipment' :
				if (isset($_GET['nonConsumingEquipmentId'])) {
					$res = State::editAvailableNonConsumingEquipment($_GET['nonConsumingEquipmentId']);
				} else
					$this -> redirect(substr(strrchr($_SERVER['PHP_SELF'], '/'), 1));
				break;

			case 'addCenterNonConsumingEquipments' :
				if ($_SESSION['user']['acl-details']['addCenterNonConsumingEquipments'] or $_SESSION['user']['acl'] == 'State Manager') {
					Center::setId($_SESSION['centerId']);
					$res = Center::addCenterNonConsumingEquipments();
				} else
					$res = PERMISSION_DENIED;
				break;

			case 'removeCenterNonConsumingEquip' :
				if (isset($_GET['id'])) {
					try {
						$res = Center::removeCenterNonConsumingEquipment($_GET['id']);
					} catch ( exception $e ) {
					}
				} else
					$this -> redirect(substr(strrchr($_SERVER['PHP_SELF'], '/'), 1));
				break;

			case 'manageGeneralChargesTypes' :
				$res = State::manageGeneralChargesTypes();
				break;

			case 'removeGeneralChargeType' :
				if (isset($_GET['generalChargeTypeId'])) {
					try {
						$res = State::removeGeneralChargeType($_GET['generalChargeTypeId']);
					} catch ( exception $e ) {
					}
				} else
					$this -> redirect(substr(strrchr($_SERVER['PHP_SELF'], '/'), 1));
				break;

			case 'editGeneralChargeType' :
				if (isset($_GET['generalChargeTypeId'])) {
					try {
						$res = State::editGeneralChargeType($_GET['generalChargeTypeId']);
					} catch ( exception $e ) {
					}
				} else
					$this -> redirect(substr(strrchr($_SERVER['PHP_SELF'], '/'), 1));
				break;

			case 'manageUnitServices' :
				if ($_SESSION['user']['acl-details']['manageUnitServices'] or $_SESSION['user']['acl'] == 'State Manager') {
					Unit::setId($_SESSION['unitId']);
					$res = Unit::unitServices();
				} else
					$res = PERMISSION_DENIED;
				break;

			case 'removeUnitService' :
				if (isset($_GET['unitServiceId'])) {
					try {
						Unit::setId($_SESSION['unitId']);
						$res = Unit::removeUnitService($_GET['unitServiceId']);
					} catch ( exception $e ) {
					}
				} else
					$this -> redirect(substr(strrchr($_SERVER['PHP_SELF'], '/'), 1));
				break;

			case 'modifyHygieneUnits' :
				if (isset($_GET['hygieneUnitId'])) {
					$resDB = Database::execute_query("SELECT `id` FROM `hygiene-units` WHERE `id` = '" . Database::filter_str($_GET['hygieneUnitId']) . "';");
					if (Database::num_of_rows($resDB) > 0) {
						if ($this -> hasIdAccess('modifyHygieneUnits', $_GET['hygieneUnitId'])) {
							HygieneUnit::setId($_GET['hygieneUnitId']);
							$res = HygieneUnit::modifyHygieneUnitsContent();
						} else
							$res = PERMISSION_DENIED;
					} else
						$this -> viewer -> appendCenterBlock('<p id="err">خطا: این صفحه وجود ندارد.<br />به <a href="' . substr(strrchr($_SERVER['PHP_SELF'], '/'), 1) . $this -> ACLHomeLink . '">[صفحه اصلی]</a> بازگردید.</p>');
				} else
					header('Location: ' . substr(strrchr($_SERVER['PHP_SELF'], '/'), 1));
				break;

			case 'manageHygieneUnitServices' :
				if ($_SESSION['user']['acl-details']['manageHygieneUnitServices'] or $_SESSION['user']['acl'] == 'State Manager') {
					HygieneUnit::setId($_SESSION['hygieneUnitId']);
					$res = HygieneUnit::hygieneUnitServices();
				} else
					$res = PERMISSION_DENIED;
				break;

			case 'removeHygieneUnitService' :
				if (isset($_GET['hygieneUnitServiceId'])) {
					try {
						HygieneUnit::setId($_SESSION['hygieneUnitId']);
						$res = HygieneUnit::removeHygieneUnitService($_GET['hygieneUnitServiceId']);
					} catch ( exception $e ) {
					}
				} else
					$this -> redirect(substr(strrchr($_SERVER['PHP_SELF'], '/'), 1));
				break;

			case 'manageUnitPersonnel' :
				if ($_SESSION['user']['acl-details']['manageUnitPersonnel'] or $_SESSION['user']['acl'] == 'State Manager') {
					Unit::setId($_SESSION['unitId']);
					$res = Unit::unitPersonnel();
				} else
					$res = PERMISSION_DENIED;
				break;

			case 'removeUnitPersonnel' :
				if (isset($_GET['unitPersonnelId'])) {
					try {
						Unit::setId($_SESSION['unitId']);
						$res = Unit::removeUnitPersonnel($_GET['unitPersonnelId']);
					} catch ( exception $e ) {
					}
				} else
					$this -> redirect(substr(strrchr($_SERVER['PHP_SELF'], '/'), 1));
				break;

			case 'editUnitPersonnel' :
				if (isset($_GET['unitPersonnelId'])) {
					try {
						$res = Unit::editUnitPersonnel($_GET['unitPersonnelId']);
					} catch ( exception $e ) {
					}
				} else
					$this -> redirect(substr(strrchr($_SERVER['PHP_SELF'], '/'), 1));
				break;

			case 'unitPersonnelFinancialInfo' :
				if ($_SESSION['user']['acl-details']['unitPersonnelFinancialInfo'] or $_SESSION['user']['acl'] == 'State Manager') {
					if (isset($_GET['id'])) {
						Unit::setId($_SESSION['unitId']);
						$res = Unit::editUnitPersonnelFinancialInfo($_GET['id']);
					} else {
						Unit::setId($_SESSION['unitId']);
						$res = Unit::personnelFinancialInfo();
					}
				} else
					$res = PERMISSION_DENIED;
				break;

			case 'manageHygieneUnitPersonnel' :
				if ($_SESSION['user']['acl-details']['manageHygieneUnitPersonnel'] or $_SESSION['user']['acl'] == 'State Manager') {
					HygieneUnit::setId($_SESSION['hygieneUnitId']);
					$res = HygieneUnit::manageHygieneUnitBehvarz();
				} else
					$res = PERMISSION_DENIED;
				break;

			case 'removeHygieneUnitPersonnel' :
				if (isset($_GET['hygieneUnitPersonnelId'])) {
					try {
						HygieneUnit::setId($_SESSION['hygieneUnitId']);
						$res = HygieneUnit::removeHygieneUnitBehvarz($_GET['hygieneUnitPersonnelId']);
					} catch ( exception $e ) {
					}
				} else
					$this -> redirect(substr(strrchr($_SERVER['PHP_SELF'], '/'), 1));
				break;

			case 'editHygieneUnitPersonnel' :
				if (isset($_GET['hygieneUnitPersonnelId'])) {
					try {
						$res = HygieneUnit::editHygieneUnitBehvarz($_GET['hygieneUnitPersonnelId']);
					} catch ( exception $e ) {
					}
				} else
					$this -> redirect(substr(strrchr($_SERVER['PHP_SELF'], '/'), 1));
				break;

			case 'hygieneUnitPersonnelFinancialInfo' :
				if ($_SESSION['user']['acl-details']['hygieneUnitPersonnelFinancialInfo'] or $_SESSION['user']['acl'] == 'State Manager') {
					if (isset($_GET['id'])) {
						HygieneUnit::setId($_SESSION['hygieneUnitId']);
						$res = HygieneUnit::editBehvarzFinancialInfo($_GET['id']);
					} else {
						HygieneUnit::setId($_SESSION['hygieneUnitId']);
						$res = HygieneUnit::behvarzFinancialInfo();
					}
				} else
					$res = PERMISSION_DENIED;
				break;

			case 'manageCenterCharges' :
				Center::setId($_SESSION['centerId']);
				$res = Center::manageCenterCharges();
				break;

			case 'manageCenterBuildings' :
				if ($_SESSION['user']['acl-details']['manageCenterBuildings'] or $_SESSION['user']['acl'] == 'State Manager') {
					Center::setId($_SESSION['centerId']);
					$res = Center::manageCenterBuildings();
				} else
					$res = PERMISSION_DENIED;
				break;

			case 'removeCenterBuilding' :
				if (isset($_GET['buildingId'])) {
					try {
						Center::setId($_SESSION['centerId']);
						$res = Center::removeCenterBuilding($_GET['buildingId']);
					} catch ( exception $e ) {
					}
				} else
					$this -> redirect(substr(strrchr($_SERVER['PHP_SELF'], '/'), 1));
				break;

			case 'editCenterBuilding' :
				if (isset($_GET['buildingId'])) {
					try {
						$res = Center::editCenterBuilding($_GET['buildingId']);
					} catch ( exception $e ) {
					}
				} else
					$this -> redirect(substr(strrchr($_SERVER['PHP_SELF'], '/'), 1));
				break;

			case 'addCenterBuildingCharge' :
				if ($_SESSION['user']['acl-details']['addCenterBuildingCharge'] or $_SESSION['user']['acl'] == 'State Manager') {
					Center::setId($_SESSION['centerId']);
					$res = Center::addCenterBuildingCharge();
				} else
					$res = PERMISSION_DENIED;
				break;

			case 'removeCenterBuildingCharge' :
				if (isset($_GET['chargeId'])) {
					try {
						$res = Center::removeCenterBuildingCharge($_GET['chargeId']);
					} catch ( exception $e ) {
					}
				} else
					$this -> redirect(substr(strrchr($_SERVER['PHP_SELF'], '/'), 1));
				break;

			case 'editCenterBuildingCharge' :
				if (isset($_GET['chargeId'])) {
					try {
						$res = Center::editCenterBuildingCharge($_GET['chargeId']);
					} catch ( exception $e ) {
					}
				} else
					$this -> redirect(substr(strrchr($_SERVER['PHP_SELF'], '/'), 1));
				break;

			case 'manageCenterVehicles' :
				if ($_SESSION['user']['acl-details']['manageCenterVehicles'] or $_SESSION['user']['acl'] == 'State Manager') {
					Center::setId($_SESSION['centerId']);
					$res = Center::manageCenterVehicles();
				} else
					$res = PERMISSION_DENIED;
				break;

			case 'removeCenterVehicle' :
				if (isset($_GET['vehicleId'])) {
					try {
						$res = Center::removeCenterVehicle($_GET['vehicleId']);
					} catch ( exception $e ) {
					}
				} else
					$this -> redirect(substr(strrchr($_SERVER['PHP_SELF'], '/'), 1));
				break;

			case 'editCenterVehicle' :
				if (isset($_GET['vehicleId'])) {
					try {
						$res = Center::editCenterVehicle($_GET['vehicleId']);
					} catch ( exception $e ) {
					}
				} else
					$this -> redirect(substr(strrchr($_SERVER['PHP_SELF'], '/'), 1));
				break;

			case 'addCenterVehicleCharge' :
				if ($_SESSION['user']['acl-details']['addCenterVehicleCharge'] or $_SESSION['user']['acl'] == 'State Manager') {
					Center::setId($_SESSION['centerId']);
					$res = Center::addCenterVehicleCharge();
				} else
					$res = PERMISSION_DENIED;
				break;

			case 'addCenterGeneralCharge' :
				if ($_SESSION['user']['acl-details']['addCenterGeneralCharge'] or $_SESSION['user']['acl'] == 'State Manager') {
					Center::setId($_SESSION['centerId']);
					$res = Center::addCenterGeneralCharge();
				} else
					$res = PERMISSION_DENIED;
				break;

			case 'removeCenterVehicleCharge' :
				if (isset($_GET['chargeId'])) {
					try {
						$res = Center::removeCenterVehicleCharge($_GET['chargeId']);
					} catch ( exception $e ) {
					}
				} else
					$this -> redirect(substr(strrchr($_SERVER['PHP_SELF'], '/'), 1));
				break;

			case 'editCenterVehicleCharge' :
				if (isset($_GET['chargeId'])) {
					try {
						$res = Center::editCenterVehicleCharge($_GET['chargeId']);
					} catch ( exception $e ) {
					}
				} else
					$this -> redirect(substr(strrchr($_SERVER['PHP_SELF'], '/'), 1));
				break;

			case 'removeCenterGeneralCharge' :
				if (isset($_GET['chargeId'])) {
					try {
						$res = Center::removeCenterGeneralCharge($_GET['chargeId']);
					} catch ( exception $e ) {
					}
				} else
					$this -> redirect(substr(strrchr($_SERVER['PHP_SELF'], '/'), 1));
				break;

			case 'editCenterGeneralCharge' :
				if (isset($_GET['chargeId'])) {
					try {
						$res = Center::editCenterGeneralCharge($_GET['chargeId']);
					} catch ( exception $e ) {
					}
				} else
					$this -> redirect(substr(strrchr($_SERVER['PHP_SELF'], '/'), 1));
				break;

			case 'addHygieneUnitDrugs' :
				if ($_SESSION['user']['acl-details']['addHygieneUnitDrugsAndConsumingEquipments'] or $_SESSION['user']['acl'] == 'State Manager') {
					HygieneUnit::setId($_SESSION['hygieneUnitId']);
					$res = HygieneUnit::addHygieneUnitDrugs();
				} else
					$res = PERMISSION_DENIED;
				break;
				
			case 'addHygieneUnitConsumingEquipments' :
				if ($_SESSION['user']['acl-details']['addHygieneUnitDrugsAndConsumingEquipments'] or $_SESSION['user']['acl'] == 'State Manager') {
					HygieneUnit::setId($_SESSION['hygieneUnitId']);
					$res = HygieneUnit::addHygieneUnitConsumingEquipments();
				} else
					$res = PERMISSION_DENIED;
				break;

			case 'removeHygieneUnitDrug' :
				if (isset($_GET['drugId'])) {
					try {
						$res = HygieneUnit::removeHygieneUnitDrugsAndConsumingEquipments($_GET['drugId']);
					} catch ( exception $e ) {
					}
				} else
					$this -> redirect(substr(strrchr($_SERVER['PHP_SELF'], '/'), 1));
				break;

			case 'addHygieneUnitNonConsumingEquipments' :
				if ($_SESSION['user']['acl-details']['addHygieneUnitNonConsumingEquipments'] or $_SESSION['user']['acl'] == 'State Manager') {
					HygieneUnit::setId($_SESSION['hygieneUnitId']);
					$res = HygieneUnit::addHygieneUnitNonConsumingEquipments();
				} else
					$res = PERMISSION_DENIED;
				break;

			case 'removeHygieneUnitNonConsumingEquip' :
				if (isset($_GET['id'])) {
					try {
						$res = HygieneUnit::removeHygieneUnitNonConsumingEquipment($_GET['id']);
					} catch ( exception $e ) {
					}
				} else
					$this -> redirect(substr(strrchr($_SERVER['PHP_SELF'], '/'), 1));
				break;

			case 'manageHygieneUnitCharges' :
				HygieneUnit::setId($_SESSION['hygieneUnitId']);
				$res = HygieneUnit::manageHygieneUnitCharges();
				break;

			case 'manageHygieneUnitBuildings' :
				if ($_SESSION['user']['acl-details']['addCenterBuildingCharge'] or $_SESSION['user']['acl'] == 'State Manager') {
					HygieneUnit::setId($_SESSION['hygieneUnitId']);
					$res = HygieneUnit::manageHygieneUnitBuildings();
				} else
					$res = PERMISSION_DENIED;
				break;

			case 'removeHygieneUnitBuilding' :
				if (isset($_GET['buildingId'])) {
					try {
						HygieneUnit::setId($_SESSION['hygieneUnitId']);
						$res = HygieneUnit::removeHygieneUnitBuilding($_GET['buildingId']);
					} catch ( exception $e ) {
					}
				} else
					$this -> redirect(substr(strrchr($_SERVER['PHP_SELF'], '/'), 1));
				break;

			case 'editHygieneUnitBuilding' :
				if (isset($_GET['buildingId'])) {
					try {
						$res = HygieneUnit::editHygieneUnitBuilding($_GET['buildingId']);
					} catch ( exception $e ) {
					}
				} else
					$this -> redirect(substr(strrchr($_SERVER['PHP_SELF'], '/'), 1));
				break;

			case 'addHygieneUnitBuildingCharge' :
				if ($_SESSION['user']['acl-details']['addCenterBuildingCharge'] or $_SESSION['user']['acl'] == 'State Manager') {
					HygieneUnit::setId($_SESSION['hygieneUnitId']);
					$res = HygieneUnit::addHygieneUnitBuildingCharge();
				} else
					$res = PERMISSION_DENIED;
				break;

			case 'removeHGBuildingCharge' :
				if (isset($_GET['chargeId'])) {
					try {
						$res = HygieneUnit::removeHygieneUnitBuildingCharge($_GET['chargeId']);
					} catch ( exception $e ) {
					}
				} else
					$this -> redirect(substr(strrchr($_SERVER['PHP_SELF'], '/'), 1));
				break;

			case 'editHGBuildingCharge' :
				if (isset($_GET['chargeId'])) {
					try {
						$res = HygieneUnit::editHygieneUnitBuildingCharge($_GET['chargeId']);
					} catch ( exception $e ) {
					}
				} else
					$this -> redirect(substr(strrchr($_SERVER['PHP_SELF'], '/'), 1));
				break;

			case 'manageHygieneUnitVehicles' :
				if ($_SESSION['user']['acl-details']['manageHygieneUnitVehicles'] or $_SESSION['user']['acl'] == 'State Manager') {
					HygieneUnit::setId($_SESSION['hygieneUnitId']);
					$res = HygieneUnit::manageHygieneUnitVehicles();
				} else
					$res = PERMISSION_DENIED;
				break;

			case 'removeHygieneUnitVehicle' :
				if (isset($_GET['vehicleId'])) {
					try {
						HygieneUnit::setId($_SESSION['hygieneUnitId']);
						$res = HygieneUnit::removeHygieneUnitVehicle($_GET['vehicleId']);
					} catch ( exception $e ) {
					}
				} else
					$this -> redirect(substr(strrchr($_SERVER['PHP_SELF'], '/'), 1));
				break;

			case 'editHygieneUnitVehicle' :
				if (isset($_GET['vehicleId'])) {
					try {
						$res = HygieneUnit::editHygieneUnitVehicle($_GET['vehicleId']);
					} catch ( exception $e ) {
					}
				} else
					$this -> redirect(substr(strrchr($_SERVER['PHP_SELF'], '/'), 1));
				break;

			case 'addHygieneUnitVehicleCharge' :
				if ($_SESSION['user']['acl-details']['addHygieneUnitVehicleCharge'] or $_SESSION['user']['acl'] == 'State Manager') {
					HygieneUnit::setId($_SESSION['hygieneUnitId']);
					$res = HygieneUnit::addHygieneUnitVehicleCharge();
				} else
					$res = PERMISSION_DENIED;
				break;

			case 'removeHGVehicleCharge' :
				if (isset($_GET['chargeId'])) {
					$res = HygieneUnit::removeHygieneUnitVehicleCharge($_GET['chargeId']);
				} else
					$this -> redirect(substr(strrchr($_SERVER['PHP_SELF'], '/'), 1));
				break;

			case 'editHGVehicleCharge' :
				if (isset($_GET['chargeId'])) {
					try {
						$res = HygieneUnit::editHygieneUnitVehicleCharge($_GET['chargeId']);
					} catch ( exception $e ) {
					}
				} else
					$this -> redirect(substr(strrchr($_SERVER['PHP_SELF'], '/'), 1));
				break;

			case 'addHygieneUnitGeneralCharge' :
				if ($_SESSION['user']['acl-details']['addHygieneUnitGeneralCharge'] or $_SESSION['user']['acl'] == 'State Manager') {
					HygieneUnit::setId($_SESSION['hygieneUnitId']);
					$res = HygieneUnit::addHygieneUnitGeneralCharge();
				} else
					$res = PERMISSION_DENIED;
				break;

			case 'editHygieneUnitGeneralCharge' :
				if (isset($_GET['chargeId'])) {
					$res = HygieneUnit::editHygieneUnitGeneralCharge($_GET['chargeId']);
				} else
					$this -> redirect(substr(strrchr($_SERVER['PHP_SELF'], '/'), 1));
				break;

			case 'removeHygieneUnitGeneralCharge' :
				if (isset($_GET['chargeId'])) {
					$res = HygieneUnit::removeHygieneUnitGeneralCharge($_GET['chargeId']);
				} else
					$this -> redirect(substr(strrchr($_SERVER['PHP_SELF'], '/'), 1));
				break;

            case 'manageSibJobsAndPeriods' :
                $res = Sib::viewSibJobsAndPeriods();
                break;

            case 'editSibJobTitle' :
                if (isset($_GET['jobId']))
                    $res = Sib::editJobTitle($_GET['jobId']);
                else
                    $this -> redirect(substr(strrchr($_SERVER['PHP_SELF'], '/'), 1));
                break;

            case 'removeSibJobTitle' :
                if (isset($_GET['jobId'])) {
                    try {
                        $res = Sib::removeJobTitle($_GET['jobId']);
                    } catch ( exception $e ) {
                    }
                } else
                    $this -> redirect(substr(strrchr($_SERVER['PHP_SELF'], '/'), 1));
                break;

            case 'editSibPeriod' :
                if (isset($_GET['periodId']))
                    $res = Sib::editPeriod($_GET['periodId']);
                else
                    $this -> redirect(substr(strrchr($_SERVER['PHP_SELF'], '/'), 1));
                break;

            case 'removeSibPeriod' :
                if (isset($_GET['periodId'])) {
                    try {
                        $res = Sib::removePeriod($_GET['periodId']);
                    } catch ( exception $e ) {
                    }
                } else
                    $this -> redirect(substr(strrchr($_SERVER['PHP_SELF'], '/'), 1));
                break;

			case 'reports' :
				$res = StatisticalPlotter::reportsMainInfo();
				break;

			case 'manageUsers' :
				$res = $this -> manageUsers();
				break;

			case 'removeUser' :
				$res = $this -> removeUser($_GET['userId']);
				break;

			case 'editUser' :
				$res = $this -> editUser($_GET['userId']);
				break;

			case 'searchPersonnel' :
				$res = $this -> searchPersonnel();
				break;

			case 'manageEditFrequencyRequests' :
				$res = $this -> manageEditFrequencyRequests();
				break;

			case 'viewUnitEditReqDetail' :
				$res = $this -> viewUnitEditRequestDetail($_GET['reqId']);
				break;

			case 'viewHGUnitEditReqDetail' :
				$res = $this -> viewHGUnitEditRequestDetail($_GET['reqId']);
				break;

			case '_LOGS' :
				$res = $this -> logs();
				break;

			case 'logOut' :
				$res = $this -> logOut();
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

	private function manageEditFrequencyRequests() {
		$htmlOut = '';

		$htmlOut .= '<div id="tabs"><ul><li><a href="#tabs-1">اسناد اصلاحی واحدها</a></li><li><a href="#tabs-2">اسناد اصلاحی خانه‌های بهداشت</a></li></ul>';
		$htmlOut .= '<div id="tabs-1">';
		$res = Database::execute_query("SELECT `id` FROM `unit-service-freq-edit-requests` WHERE `approved` = '0';");
		$counter = 1;
		if (Database::num_of_rows($res) > 0) {
			$htmlOut .= '<fieldset><legend>مدیریت اسناد اصلاحی واحدها</legend>';
			$htmlOut .= '<br /><table align="right" class="contentTable" border="0" cellpadding="0" cellspacing="1">';
			$htmlOut .= '<thead><th width="40">ردیف</th><th>عنوان</th><th width="100">جزئیات</th></thead><tbody>';
			while ($row = Database::get_assoc_array($res)) {
				$htmlOut .= '<tr><td>' . $counter . '</td><td>سند اصلاحی شماره ' . ($row['id'] + 1000) . '</td>';
				$htmlOut .= '<td><a href="?cmd=viewUnitEditReqDetail&reqId=' . $row['id'] . '"><img src="../illustrator/images/icons/det.png" /></a></td></tr>';
				$counter++;
			}
			$htmlOut .= '</tbody></table></fielset>';
		} else
			$htmlOut .= '<p class="warning">هیچ سند اصلاحی برای واحدها ثبت نشده است.</p>';
		$htmlOut .= '</div>';
		//end tab1
		/**********************************************************************************************************************************/

		$htmlOut .= '<div id="tabs-2">';
		$res = Database::execute_query("SELECT `id` FROM `hygiene-unit-service-freq-edit-requests` WHERE `approved` = '0';");
		$counter = 1;
		if (Database::num_of_rows($res) > 0) {
			$htmlOut .= '<fieldset><legend>مدیریت اسناد اصلاحی خانه‌های بهداشت</legend>';
			$htmlOut .= '<br /><table align="right" class="contentTable" border="0" cellpadding="0" cellspacing="1">';
			$htmlOut .= '<thead><th width="40">ردیف</th><th>عنوان</th><th width="100">جزئیات</th></thead><tbody>';
			while ($row = Database::get_assoc_array($res)) {
				$htmlOut .= '<tr><td>' . $counter . '</td><td>سند اصلاحی شماره ' . ($row['id'] + 1000) . '</td>';
				$htmlOut .= '<td><a href="?cmd=viewHGUnitEditReqDetail&reqId=' . $row['id'] . '"><img src="../illustrator/images/icons/det.png" /></a></td></tr>';
				$counter++;
			}
			$htmlOut .= '</tbody></table></fielset>';
		} else
			$htmlOut .= '<p class="warning">هیچ سند اصلاحی برای خانه‌های بهداشت ثبت نشده است.</p>';
		$htmlOut .= '</div>';
		//end tab2

		$htmlOut .= '</div>';
		//end whole tabs

		return $htmlOut;
	}

	private function viewUnitEditRequestDetail($id) {
		$htmlOut = '';
		if (isset($_POST['submitConfirmUnitEditRequest'])) {
			$tmp = FormProccessor::processConfirmUnitEditRequest();
			if (is_array($tmp))
				$this -> viewer -> appendCenterBlock('<p id="err">' . $tmp['err_msg'] . '</p><p style="text-align: center;">[<a href="javascript:history.go(-1);">بازگشت</a>]</p>');
			else if ($tmp)
				$htmlOut .= '<p id="cong">سند موردنظر با موفقیت از طرف مدیریت تایید گردید.</p>';
		} elseif (isset($_POST['rejectConfirmUnitEditRequest'])) {
			$tmp = FormProccessor::processRejectUnitEditRequest();
			if (is_array($tmp))
				$this -> viewer -> appendCenterBlock('<p id="err">' . $tmp['err_msg'] . '</p><p style="text-align: center;">[<a href="javascript:history.go(-1);">بازگشت</a>]</p>');
			else if ($tmp)
				$htmlOut .= '<p id="cong">سند موردنظر با موفقیت از طرف مدیریت لغو و حذف گردید.</p>';
		} else {
			$res = Database::execute_query("SELECT `a`.`id`, `a`.`user-id`, `a`.`suggested-freq`, `a`.`request-date`, `b`.`unit-id`, `b`.`service-id`, `b`.`service-frequency`, `b`.`period-start-date`, `b`.`period-finish-date`, `b`.`date` FROM `unit-service-freq-edit-requests` AS `a` INNER JOIN `unit-done-services` AS `b` ON `a`.`done-service-id` = `b`.`id` WHERE `a`.`id` = '$id';");
			$row = Database::get_assoc_array($res);
			$userRes = Database::execute_query("SELECT `name`, `last-name`, `access-level`, `access-level-id` FROM `users` WHERE `id` = '" . $row['user-id'] . "';");
			$userRow = Database::get_assoc_array($userRes);
			$acl = '';
			switch ($userRow['access-level']) {
				case 'State Author' :
					$resT = Database::execute_query("SELECT `name` FROM `states` WHERE `id` = '" . $userRow['access-level-id'] . "';");
					$rowT = Database::get_assoc_array($resT);
					$acl = 'نویسنده استان ' . $rowT['name'];
					break;
				case 'Town Author' :
					$resT = Database::execute_query("SELECT `name` FROM `towns` WHERE `id` = '" . $userRow['access-level-id'] . "';");
					$rowT = Database::get_assoc_array($resT);
					$acl = 'نویسنده شهرستان ' . $rowT['name'];
					break;
				case 'Center Author' :
					$resT = Database::execute_query("SELECT `name` FROM `centers` WHERE `id` = '" . $userRow['access-level-id'] . "';");
					$rowT = Database::get_assoc_array($resT);
					$acl = 'نویسنده مرکز ' . $rowT['name'];
					break;
				case 'Unit Author' :
					$resT = Database::execute_query("SELECT `name` FROM `units` WHERE `id` = '" . $userRow['access-level-id'] . "';");
					$rowT = Database::get_assoc_array($resT);
					$acl = 'نویسنده واحد ' . $rowT['name'];
					break;
				case 'Hygiene Unit Author' :
					$resT = Database::execute_query("SELECT `name` FROM `hygiene-units` WHERE `id` = '" . $userRow['access-level-id'] . "';");
					$rowT = Database::get_assoc_array($resT);
					$acl = 'نویسنده خانه بهداشت ' . $rowT['name'];
					break;
			}
			$htmlOut .= '<fieldset><legend>جزئیات سند اصلاحی ' . ($id + 1000) . '</legend>';
			$htmlOut .= '<br /><table align="right" class="contentTable" border="0" cellpadding="0" cellspacing="1">';
			$htmlOut .= '<thead><th>جزئیات سند اصلاحی ' . ($id + 1000) . '</th></thead><tbody style="text-align: right;">';
			$htmlOut .= '<tr><td style="padding-right: 10px;"><br /><span style="font-weight: bold; font-size: larger;">جزئیات کاربر نویسنده:</span><br />';
			$htmlOut .= '<p style="margin-right: 140px;"><span style="font-weight: bold;">نام: </span>' . $userRow['name'] . '</p>';
			$htmlOut .= '<p style="margin-right: 140px;"><span style="font-weight: bold;">نام خانوادگی: </span>' . $userRow['last-name'] . '</p>';
			$htmlOut .= '<p style="margin-right: 140px;"><span style="font-weight: bold;">سطح دسترسی: </span>' . $acl . '</p>';
			$htmlOut .= '<p style="margin-right: 140px;"><span style="font-weight: bold;">تاریخ ثبت سند: </span>' . $row['request-date'] . '</p><hr style="width: 500px;" />';

			$resUnit = Database::execute_query("SELECT `name`, `center-id` FROM  `units` WHERE `id` = '" . $row['unit-id'] . "';");
			$rowUnit = Database::get_assoc_array($resUnit);
			$resCenter = Database::execute_query("SELECT `name`, `town-id` FROM `centers` WHERE `id` = '" . $rowUnit['center-id'] . "';");
			$rowCenter = Database::get_assoc_array($resCenter);
			$resTown = Database::execute_query("SELECT `name`, `state-id` FROM `towns` WHERE `id` = '" . $rowCenter['town-id'] . "';");
			$rowTown = Database::get_assoc_array($resTown);
			$resState = Database::execute_query("SELECT `name` FROM `states` WHERE `id` = '" . $rowTown['state-id'] . "';");
			$rowState = Database::get_assoc_array($resState);

			$htmlOut .= '<br /><span style="font-weight: bold; font-size: larger;">جزئیات مکان مربوطه:</span><br />';
			$htmlOut .= '<p style="margin-right: 140px;"><span style="font-weight: bold;">نام واحد: </span>' . $rowUnit['name'] . '</p>';
			$htmlOut .= '<p style="margin-right: 140px;"><span style="font-weight: bold;">نام مرکز: </span>' . $rowCenter['name'] . '</p>';
			$htmlOut .= '<p style="margin-right: 140px;"><span style="font-weight: bold;">نام شهرستان: </span>' . $rowTown['name'] . '</p>';
			$htmlOut .= '<p style="margin-right: 140px;"><span style="font-weight: bold;">نام استان: </span>' . $rowState['name'] . '</p><hr style="width: 500px;" />';

			$resServ = Database::execute_query("SELECT `name` FROM `available-services` WHERE `id` = '" . $row['service-id'] . "';");
			$rowServ = Database::get_assoc_array($resServ);

			$htmlOut .= '<br /><span style="font-weight: bold; font-size: larger;">جزئیات خدمت و بارخدمت:</span><br />';
			$htmlOut .= '<p style="margin-right: 140px;"><span style="font-weight: bold;">نام خدمت: </span>' . $rowServ['name'] . '</p>';
			$htmlOut .= '<p style="margin-right: 140px; margin-bottom: 0;"><span style="font-weight: bold;">بار خدمت ثبت شده: </span>' . $row['service-frequency'] . '</p>';
			$htmlOut .= '<div style="margin-right: 140px; height: 25px;"><span style="font-weight: bold;">از تاریخ: </span><p style="direction: ltr; display: inline-block;">' . $row['period-start-date'] . '</p></div>';
			$htmlOut .= '<div style="margin-right: 140px; height: 25px;"><span style="font-weight: bold;">تا تاریخ: </span><p style="direction: ltr; display: inline-block;">' . $row['period-finish-date'] . '</p></div>';
			$htmlOut .= '<p style="margin-right: 140px;"><span style="font-weight: bold;">بار خدمت اصلاحی پیشنهادی کاربر: </span>' . $row['suggested-freq'] . '</p><hr style="width: 500px;" /><br />';
			$htmlOut .= '</td></tr>';
			$htmlOut .= '</tbody></table></fielset><br />';

			$htmlOut .= '<form action="' . substr(strrchr($_SERVER['PHP_SELF'], '/'), 1) . '?cmd=viewUnitEditReqDetail&reqId=' . $id . '" method="post" class="narin">';
			$htmlOut .= '<input type="hidden" name="narin:token" value="' . Narin::create_token() . '" />';
			$htmlOut .= '<input type="hidden" name="reqId" value="' . $id . '" />';
			$htmlOut .= '<input type="submit" name="submitConfirmUnitEditRequest" value="تایید سند" />';
			$htmlOut .= '<input type="submit" name="rejectConfirmUnitEditRequest" value="لغو سند" />';
			$htmlOut .= '</form>';
		}

		return $htmlOut;
	}

	private function viewHGUnitEditRequestDetail($id) {
		$htmlOut = '';
		if (isset($_POST['submitConfirmHGUnitEditRequest'])) {
			$tmp = FormProccessor::processConfirmHGUnitEditRequest();
			if (is_array($tmp))
				$this -> viewer -> appendCenterBlock('<p id="err">' . $tmp['err_msg'] . '</p><p style="text-align: center;">[<a href="javascript:history.go(-1);">بازگشت</a>]</p>');
			else if ($tmp)
				$htmlOut .= '<p id="cong">سند موردنظر با موفقیت از طرف مدیریت تایید گردید.</p>';
		} elseif (isset($_POST['rejectConfirmHGUnitEditRequest'])) {
			$tmp = FormProccessor::processRejectHGUnitEditRequest();
			if (is_array($tmp))
				$this -> viewer -> appendCenterBlock('<p id="err">' . $tmp['err_msg'] . '</p><p style="text-align: center;">[<a href="javascript:history.go(-1);">بازگشت</a>]</p>');
			else if ($tmp)
				$htmlOut .= '<p id="cong">سند موردنظر با موفقیت از طرف مدیریت لغو و حذف گردید.</p>';
		} else {
			$res = Database::execute_query("SELECT `a`.`id`, `a`.`user-id`, `a`.`suggested-freq`, `a`.`request-date`, `b`.`hygiene-unit-id`, `b`.`service-id`, `b`.`service-frequency`, `b`.`period-start-date`, `b`.`period-finish-date`, `b`.`date` FROM `hygiene-unit-service-freq-edit-requests` AS `a` INNER JOIN `hygiene-unit-done-services` AS `b` ON `a`.`done-service-id` = `b`.`id` WHERE `a`.`id` = '$id';");
			$row = Database::get_assoc_array($res);
			$userRes = Database::execute_query("SELECT `name`, `last-name`, `access-level`, `access-level-id` FROM `users` WHERE `id` = '" . $row['user-id'] . "';");
			$userRow = Database::get_assoc_array($userRes);
			$acl = '';
			switch ($userRow['access-level']) {
				case 'State Author' :
					$resT = Database::execute_query("SELECT `name` FROM `states` WHERE `id` = '" . $userRow['access-level-id'] . "';");
					$rowT = Database::get_assoc_array($resT);
					$acl = 'نویسنده استان ' . $rowT['name'];
					break;
				case 'Town Author' :
					$resT = Database::execute_query("SELECT `name` FROM `towns` WHERE `id` = '" . $userRow['access-level-id'] . "';");
					$rowT = Database::get_assoc_array($resT);
					$acl = 'نویسنده شهرستان ' . $rowT['name'];
					break;
				case 'Center Author' :
					$resT = Database::execute_query("SELECT `name` FROM `centers` WHERE `id` = '" . $userRow['access-level-id'] . "';");
					$rowT = Database::get_assoc_array($resT);
					$acl = 'نویسنده مرکز ' . $rowT['name'];
					break;
				case 'Unit Author' :
					$resT = Database::execute_query("SELECT `name` FROM `units` WHERE `id` = '" . $userRow['access-level-id'] . "';");
					$rowT = Database::get_assoc_array($resT);
					$acl = 'نویسنده واحد ' . $rowT['name'];
					break;
				case 'Hygiene Unit Author' :
					$resT = Database::execute_query("SELECT `name` FROM `hygiene-units` WHERE `id` = '" . $userRow['access-level-id'] . "';");
					$rowT = Database::get_assoc_array($resT);
					$acl = 'نویسنده خانه بهداشت ' . $rowT['name'];
					break;
			}
			$htmlOut .= '<fieldset><legend>جزئیات سند اصلاحی ' . ($id + 1000) . '</legend>';
			$htmlOut .= '<br /><table align="right" class="contentTable" border="0" cellpadding="0" cellspacing="1">';
			$htmlOut .= '<thead><th>جزئیات سند اصلاحی ' . ($id + 1000) . '</th></thead><tbody style="text-align: right;">';
			$htmlOut .= '<tr><td style="padding-right: 10px;"><br /><span style="font-weight: bold; font-size: larger;">جزئیات کاربر نویسنده:</span><br />';
			$htmlOut .= '<p style="margin-right: 140px;"><span style="font-weight: bold;">نام: </span>' . $userRow['name'] . '</p>';
			$htmlOut .= '<p style="margin-right: 140px;"><span style="font-weight: bold;">نام خانوادگی: </span>' . $userRow['last-name'] . '</p>';
			$htmlOut .= '<p style="margin-right: 140px;"><span style="font-weight: bold;">سطح دسترسی: </span>' . $acl . '</p>';
			$htmlOut .= '<p style="margin-right: 140px;"><span style="font-weight: bold;">تاریخ ثبت سند: </span>' . $row['request-date'] . '</p><hr style="width: 500px;" />';

			$resUnit = Database::execute_query("SELECT `name`, `center-id` FROM  `units` WHERE `id` = '" . $row['hygiene-unit-id'] . "';");
			$rowUnit = Database::get_assoc_array($resUnit);
			$resCenter = Database::execute_query("SELECT `name`, `town-id` FROM `centers` WHERE `id` = '" . $rowUnit['center-id'] . "';");
			$rowCenter = Database::get_assoc_array($resCenter);
			$resTown = Database::execute_query("SELECT `name`, `state-id` FROM `towns` WHERE `id` = '" . $rowCenter['town-id'] . "';");
			$rowTown = Database::get_assoc_array($resTown);
			$resState = Database::execute_query("SELECT `name` FROM `states` WHERE `id` = '" . $rowTown['state-id'] . "';");
			$rowState = Database::get_assoc_array($resState);

			$htmlOut .= '<br /><span style="font-weight: bold; font-size: larger;">جزئیات مکان مربوطه:</span><br />';
			$htmlOut .= '<p style="margin-right: 140px;"><span style="font-weight: bold;">نام واحد: </span>' . $rowUnit['name'] . '</p>';
			$htmlOut .= '<p style="margin-right: 140px;"><span style="font-weight: bold;">نام مرکز: </span>' . $rowCenter['name'] . '</p>';
			$htmlOut .= '<p style="margin-right: 140px;"><span style="font-weight: bold;">نام شهرستان: </span>' . $rowTown['name'] . '</p>';
			$htmlOut .= '<p style="margin-right: 140px;"><span style="font-weight: bold;">نام استان: </span>' . $rowState['name'] . '</p><hr style="width: 500px;" />';

			$resServ = Database::execute_query("SELECT `name` FROM `available-services` WHERE `id` = '" . $row['service-id'] . "';");
			$rowServ = Database::get_assoc_array($resServ);

			$htmlOut .= '<br /><span style="font-weight: bold; font-size: larger;">جزئیات خدمت و بارخدمت:</span><br />';
			$htmlOut .= '<p style="margin-right: 140px;"><span style="font-weight: bold;">نام خدمت: </span>' . $rowServ['name'] . '</p>';
			$htmlOut .= '<p style="margin-right: 140px; margin-bottom: 0;"><span style="font-weight: bold;">بار خدمت ثبت شده: </span>' . $row['service-frequency'] . '</p>';
			$htmlOut .= '<div style="margin-right: 140px; height: 25px;"><span style="font-weight: bold;">از تاریخ: </span><p style="direction: ltr; display: inline-block;">' . $row['period-start-date'] . '</p></div>';
			$htmlOut .= '<div style="margin-right: 140px; height: 25px;"><span style="font-weight: bold;">تا تاریخ: </span><p style="direction: ltr; display: inline-block;">' . $row['period-finish-date'] . '</p></div>';
			$htmlOut .= '<p style="margin-right: 140px;"><span style="font-weight: bold;">بار خدمت اصلاحی پیشنهادی کاربر: </span>' . $row['suggested-freq'] . '</p><hr style="width: 500px;" /><br />';
			$htmlOut .= '</td></tr>';
			$htmlOut .= '</tbody></table></fielset><br />';

			$htmlOut .= '<form action="' . substr(strrchr($_SERVER['PHP_SELF'], '/'), 1) . '?cmd=viewHGUnitEditReqDetail&reqId=' . $id . '" method="post" class="narin">';
			$htmlOut .= '<input type="hidden" name="narin:token" value="' . Narin::create_token() . '" />';
			$htmlOut .= '<input type="hidden" name="reqId" value="' . $id . '" />';
			$htmlOut .= '<input type="submit" name="submitConfirmHGUnitEditRequest" value="تایید سند" />';
			$htmlOut .= '<input type="submit" name="rejectConfirmHGUnitEditRequest" value="لغو سند" />';
			$htmlOut .= '</form>';
		}

		return $htmlOut;
	}

	public function manageUsers() {
		$htmlOut = '';
		$htmlOut .= '<script type="text/javascript">';
		$htmlOut .= '$(document).ajaxStart(function() {$(\'#loadingForUnitSelection\').show();$(\'#mngDiv\').css(\'opacity\', \'0.3\');});' . '$(document).ajaxSuccess(function() {$(\'#loadingForUnitSelection\').hide();$(\'#mngDiv\').css(\'opacity\', \'1\');});';
		$htmlOut .= '$(document).ready(function () {$(\'#accessLevel\').trigger(\'change\');});';
		$htmlOut .= '</script>';
		$htmlOut .= '<p>در این قسمت شما می توانید کاربران کل سیستم را مدیریت نمایید.</p>';
		$htmlOut .= '<p style="text-align: right; font-weight: bold; font-size: 15px; margin-right: 20px"><a href="#" style="color: green;" id="slideLink" ><img src="../illustrator/images/icons/plus.png" />&nbsp;افزودن کاربر</a></p>';
		$htmlOut .= '<div class="loading" id="loadingRep">در حال بارگذاری؛ لطفا صبر کنید... <br /><br /><img src="../statisticalPlotter/loading.gif" /></div>';
		$htmlOut .= '<div id="mngDiv"><form action="' . substr(strrchr($_SERVER['PHP_SELF'], '/'), 1) . '" method="post" id="slideForm" class="narin">';
		$htmlOut .= '<input type="hidden" name="narin:token" value="' . Narin::create_token() . '" />';
		$htmlOut .= '<fieldset><legend>افزودن کاربر جدید</legend>';
		$htmlOut .= '<label for="accessLevel" class="required">سطح دسترسی:</label>';
		$htmlOut .= '<select name="accessLevel" id="accessLevel">';
		$htmlOut .= '<optGroup label="مدیران">';
		$htmlOut .= '<option value="stateManager">مدیر استان</option>';
		$htmlOut .= '<option value="townManager">مدیر شهرستان</option>';
		$htmlOut .= '<option value="centerManager">مدیر مرکز</option>';
		$htmlOut .= '<option value="unitManager">مدیر واحد</option>';
		$htmlOut .= '<option value="hygieneUnitManager">مدیر خانه بهداشت</option>';
		$htmlOut .= '</optGroup>';
		$htmlOut .= '<option value="" disabled="">-------------------------------------</option>';
		$htmlOut .= '<optGroup label="نویسندگان">';
		$htmlOut .= '<option value="stateAuthor">نویسنده استان</option>';
		$htmlOut .= '<option value="townAuthor">نویسنده شهرستان</option>';
		$htmlOut .= '<option value="centerAuthor">نویسنده مرکز</option>';
		$htmlOut .= '<option value="unitAuthor">نویسنده واحد</option>';
		$htmlOut .= '<option value="hygieneUnitAuthor">نویسنده خانه بهداشت</option>';
		//$htmlOut .= '<option value="multiAuthor">نویسنده چندمنظوره</option>';
		$htmlOut .= '</optGroup>';
		$htmlOut .= '</select><br />';
		$htmlOut .= '<div id="acl_items"></div>';

		$htmlOut .= '<div id="townManagerACLDiv">';
		$htmlOut .= '<fieldset style="width: 300px; margin: 10px 5px 10px auto;"><legend>جزئیات سطح دسترسی ها</legend>';
		$htmlOut .= '<input type="checkbox" name="addCenterDrugsAndConsumingEquipments" id="addCenterDrugsAndConsumingEquipments" />';
		$htmlOut .= '<label for="addCenterDrugsAndConsumingEquipments">مدیریت داروها و تجهیزات مصرفی مراکز</label><br />';
		$htmlOut .= '<input type="checkbox" name="addCenterNonConsumingEquipments" id="addCenterNonConsumingEquipments" />';
		$htmlOut .= '<label for="addCenterNonConsumingEquipments">مدیریت تجهیزات غیرمصرفی مراکز</label><br />';
		$htmlOut .= '<input type="checkbox" name="manageCenterBuildings" id="manageCenterBuildings" />';
		$htmlOut .= '<label for="manageCenterBuildings">مدیریت ساختمان های مراکز</label><br />';
		$htmlOut .= '<input type="checkbox" name="addCenterBuildingCharge" id="addCenterBuildingCharge" />';
		$htmlOut .= '<label for="addCenterBuildingCharge">مدیریت هزینه نگهداری ساختمان های  مراکز</label><br />';
		$htmlOut .= '<input type="checkbox" name="manageCenterVehicles" id="manageCenterVehicles" />';
		$htmlOut .= '<label for="manageCenterVehicles">مدیریت وسایل نقلیه مراکز</label><br />';
		$htmlOut .= '<input type="checkbox" name="addCenterVehicleCharge" id="addCenterVehicleCharge" />';
		$htmlOut .= '<label for="addCenterVehicleCharge">مدیریت هزینه نگهداری وسایل نقلیه مراکز</label><br />';
		$htmlOut .= '<input type="checkbox" name="addCenterGeneralCharge" id="addCenterGeneralCharge" />';
		$htmlOut .= '<label for="addCenterGeneralCharge">مدیریت هزینه های عمومی مراکز</label></fieldset><br />';
		$htmlOut .= '</div>';

		$htmlOut .= '<div id="centerManagerACLDiv">';
		$htmlOut .= '<fieldset style="width: 300px; margin: 10px 5px 10px auto;"><legend>جزئیات سطح دسترسی ها</legend>';
		$htmlOut .= '<input type="checkbox" name="c_addCenterDrugsAndConsumingEquipments" id="c_addCenterDrugsAndConsumingEquipments" />';
		$htmlOut .= '<label for="c_addCenterDrugsAndConsumingEquipments">مدیریت داروها و تجهیزات مصرفی مرکز</label><br />';
		$htmlOut .= '<input type="checkbox" name="c_addCenterNonConsumingEquipments" id="c_addCenterNonConsumingEquipments" />';
		$htmlOut .= '<label for="c_addCenterNonConsumingEquipments">مدیریت تجهیزات غیرمصرفی مرکز</label><br />';
		$htmlOut .= '<input type="checkbox" name="c_manageCenterBuildings" id="c_manageCenterBuildings" />';
		$htmlOut .= '<label for="c_manageCenterBuildings">مدیریت ساختمان های مرکز</label><br />';
		$htmlOut .= '<input type="checkbox" name="c_addCenterBuildingCharge" id="c_addCenterBuildingCharge" />';
		$htmlOut .= '<label for="c_addCenterBuildingCharge">مدیریت هزینه نگهداری ساختمان های  مرکز</label><br />';
		$htmlOut .= '<input type="checkbox" name="c_manageCenterVehicles" id="c_manageCenterVehicles" />';
		$htmlOut .= '<label for="c_manageCenterVehicles">مدیریت وسایل نقلیه مرکز</label><br />';
		$htmlOut .= '<input type="checkbox" name="c_addCenterVehicleCharge" id="c_addCenterVehicleCharge" />';
		$htmlOut .= '<label for="c_addCenterVehicleCharge">مدیریت هزینه نگهداری وسایل نقلیه مرکز</label><br />';
		$htmlOut .= '<input type="checkbox" name="c_addCenterGeneralCharge" id="c_addCenterGeneralCharge" />';
		$htmlOut .= '<label for="c_addCenterGeneralCharge">مدیریت هزینه های عمومی مرکز</label></fieldset><br />';
		$htmlOut .= '</div>';

		$htmlOut .= '<div id="unitManagerACLDiv">';
		$htmlOut .= '<fieldset style="width: 300px; margin: 10px 5px 10px auto;"><legend>جزئیات سطح دسترسی ها</legend>';
		$htmlOut .= '<input type="checkbox" name="manageUnitPersonnel" id="manageUnitPersonnel" />';
		$htmlOut .= '<label for="manageUnitPersonnel">مدیریت پرسنل واحد</label><br />';
		$htmlOut .= '<input type="checkbox" name="unitPersonnelFinancialInfo" id="unitPersonnelFinancialInfo" />';
		$htmlOut .= '<label for="unitPersonnelFinancialInfo">مدیریت اطلاعات مالی پرسنل واحد</label><br />';
		$htmlOut .= '<input type="checkbox" name="manageUnitServices" id="manageUnitServices" />';
		$htmlOut .= '<label for="manageUnitServices">مدیریت خدمات واحد</label><br />';
		$htmlOut .= '</fieldset><br />';
		$htmlOut .= '</div>';

		$htmlOut .= '<div id="hygieneUnitManagerACLDiv">';
		$htmlOut .= '<fieldset style="width: 400px; margin: 10px 5px 10px auto;"><legend>جزئیات سطح دسترسی ها</legend>';
		$htmlOut .= '<input type="checkbox" name="addHygieneUnitDrugsAndConsumingEquipments" id="addHygieneUnitDrugsAndConsumingEquipments" />';
		$htmlOut .= '<label for="addHygieneUnitDrugsAndConsumingEquipments">مدیریت داروها و تجهیزات مصرفی خانه بهداشت</label><br />';
		$htmlOut .= '<input type="checkbox" name="addHygieneUnitNonConsumingEquipments" id="addHygieneUnitNonConsumingEquipments" />';
		$htmlOut .= '<label for="addHygieneUnitNonConsumingEquipments">مدیریت تجهیزات غیرمصرفی خانه بهداشت</label><br />';
		$htmlOut .= '<input type="checkbox" name="manageHygieneUnitPersonnel" id="manageHygieneUnitPersonnel" />';
		$htmlOut .= '<label for="manageHygieneUnitPersonnel">مدیریت بهورزان خانه بهداشت</label><br />';
		$htmlOut .= '<input type="checkbox" name="hygieneUnitPersonnelFinancialInfo" id="hygieneUnitPersonnelFinancialInfo" />';
		$htmlOut .= '<label for="hygieneUnitPersonnelFinancialInfo">مدیریت اطلاعات مالی بهورزان خانه بهداشت</label><br />';
		$htmlOut .= '<input type="checkbox" name="manageHygieneUnitBuildings" id="manageHygieneUnitBuildings" />';
		$htmlOut .= '<label for="manageHygieneUnitBuildings">مدیریت ساختمان های خانه بهداشت</label><br />';
		$htmlOut .= '<input type="checkbox" name="addHygieneUnitBuildingCharge" id="addHygieneUnitBuildingCharge" />';
		$htmlOut .= '<label for="addHygieneUnitBuildingCharge">مدیریت هزینه نگهداری ساختمان های خانه بهداشت</label><br />';
		$htmlOut .= '<input type="checkbox" name="manageHygieneUnitVehicles" id="manageHygieneUnitVehicles" />';
		$htmlOut .= '<label for="manageHygieneUnitVehicles">مدیریت وسایل نقلیه خانه بهداشت</label><br />';
		$htmlOut .= '<input type="checkbox" name="addHygieneUnitVehicleCharge" id="addHygieneUnitVehicleCharge" />';
		$htmlOut .= '<label for="addHygieneUnitVehicleCharge">مدیریت هزینه نگهداری وسایل نقلیه خانه بهداشت</label><br />';
		$htmlOut .= '<input type="checkbox" name="manageHygieneUnitServices" id="manageHygieneUnitServices" />';
		$htmlOut .= '<label for="manageHygieneUnitServices">مدیریت خدمات خانه بهداشت</label><br />';
		$htmlOut .= '<input type="checkbox" name="addHygieneUnitGeneralCharge" id="addHygieneUnitGeneralCharge" />';
		$htmlOut .= '<label for="addHygieneUnitGeneralCharge">مدیریت هزینه های عمومی خانه بهداشت</label></fieldset><br />';
		$htmlOut .= '</div>';

		$htmlOut .= '<label for="name" class="required">نام:</label>';
		$htmlOut .= '<input type="text" name="name" id="name" maxlength="50"';
		if (isset($_POST['name']) && $_POST['name'] !== '')
			$htmlOut .= ' value="' . htmlspecialchars($_POST['name']) . '"';
		$htmlOut .= ' class="validate required';
		if (isset($r['invalid']['name']))
			$htmlOut .= ' invalid';
		$htmlOut .= '" />';
		$htmlOut .= '<span class="description"';
		if (!isset($r['invalid']['name']))
			$htmlOut .= ' style="display:none;"';
		$htmlOut .= '>فقط حروف فارسی</span><br />';
		$htmlOut .= '<label for="lastName" class="required">نام خانوادگی:</label>';
		$htmlOut .= '<input type="text" name="lastName" id="lastName" maxlength="50"';
		if (isset($_POST['lastName']) && $_POST['lastName'] !== '')
			$htmlOut .= ' value="' . htmlspecialchars($_POST['lastName']) . '"';
		$htmlOut .= ' class="validate required';
		if (isset($r['invalid']['lastName']))
			$htmlOut .= ' invalid';
		$htmlOut .= '" />';
		$htmlOut .= '<span class="description"';
		if (!isset($r['invalid']['lastName']))
			$htmlOut .= ' style="display:none;"';
		$htmlOut .= '>فقط حروف فارسی</span><br />';
		$htmlOut .= '<label for="userName" class="required">نام کاربری:</label>';
		$htmlOut .= '<input type="text" name="userName" id="userName" maxlength="255"';
		if (isset($_POST['userName']) && $_POST['userName'] !== '')
			$htmlOut .= ' value="' . htmlspecialchars($_POST['userName']) . '"';
		$htmlOut .= ' class="validate required';
		if (isset($r['invalid']['userName']))
			$htmlOut .= ' invalid';
		$htmlOut .= '" /><br />';
		$htmlOut .= '<label for="passwd" class="required">رمز عبور:</label>';
		$htmlOut .= '<input type="password" name="passwd" id="passwd" class="validate required minlength';
		if (isset($r['invalid']['passwd']))
			$htmlOut .= ' invalid';
		$htmlOut .= '"';
		if (isset($_POST['passwd']))
			$htmlOut .= ' value="' . htmlspecialchars($_POST['passwd']) . '"';
		$htmlOut .= ' />';
		$htmlOut .= '<span class="description">حداقل 8 کارکتر</span><br />';
		$htmlOut .= '<label for="passwdconfirm" class="required">تکرار رمز عبور:</label>';
		$htmlOut .= '<input type="password" name="passwdconfirm" id="passwdconfirm" class="validate required match';
		if (isset($r['invalid']['passwdconfirm']))
			$htmlOut .= ' invalid';
		$htmlOut .= '"';
		if (isset($_POST['passwdconfirm']))
			$htmlOut .= ' value="' . htmlspecialchars($_POST['passwdconfirm']) . '"';
		$htmlOut .= ' />';
		$htmlOut .= '<script type="text/javascript">$(\'#passwdconfirm\').data(\'match\',\'passwd\');$(\'#passwd\').data(\'minlength\',8);$(\'form.narin\').find(\'label:not(input[type="checkbox"]+label,input[type="radio"]+label,.labelbr)\').autoWidth();</script><br />';
		$htmlOut .= '<label for="email" class="required">پست الکترونیک:</label>';
		$htmlOut .= '<input type="text" name="email" id="email" maxlength="255" dir="ltr" class="validate required email" />';
		$htmlOut .= '<br /><input type="submit" name="submitAddUser" value="افزودن کاربر به سیستم" />';
		$htmlOut .= '</fieldset></form></div>';

		$res = Database::execute_query("SELECT * FROM  `users` ORDER BY `last-name` ASC;");
		$counter = 1;
		if (Database::num_of_rows($res) > 0) {
			$htmlOut .= '<br /><fieldset style="width: 590px;"><legend>فهرست کاربران سیستم</legend>';
			$htmlOut .= '<br /><table align="right" class="contentTable" border="0" cellpadding="0" cellspacing="1">';
			$htmlOut .= '<thead><th width="40">ردیف</th><th>نام</th><th>نام خانوادگی</th><th width="260">سطح دسترسی</th><th width="40">حذف</th><th width="50">ویرایش</th></thead><tbody>';
			while ($row = Database::get_assoc_array($res)) {
				$htmlOut .= '<tr><td>' . $counter . '</td><td>' . $row['name'] . '</td>';
				$htmlOut .= '<td>' . $row['last-name'] . '</td>';
				$acl = '';
				switch ( $row['access-level'] ) {
					case 'State Manager' :
						$resS = Database::execute_query("SELECT `name` FROM `states` WHERE `id` = '" . $row['access-level-id'] . "';");
						$rowS = Database::get_assoc_array($resS);
						$acl = 'مدیر استان <' . $rowS['name'] . '>';
						break;
					case 'Town Manager' :
						$resT = Database::execute_query("SELECT `name`, `state-id` FROM `towns` WHERE `id` = '" . $row['access-level-id'] . "';");
						$rowT = Database::get_assoc_array($resT);
						$resS = Database::execute_query("SELECT `name` FROM `states` WHERE `id` = '" . $rowT['state-id'] . "';");
						$rowS = Database::get_assoc_array($resS);
						$acl = 'مدیر شهرستان <' . $rowT['name'] . '> در استان <' . $rowS['name'] . '>';
						break;
					case 'Center Manager' :
						$resC = Database::execute_query("SELECT `name`, `town-id` FROM `centers` WHERE `id` = '" . $row['access-level-id'] . "';");
						$rowC = Database::get_assoc_array($resC);
						$resT = Database::execute_query("SELECT `name`, `state-id` FROM `towns` WHERE `id` = '" . $rowC['town-id'] . "';");
						$rowT = Database::get_assoc_array($resT);
						$resS = Database::execute_query("SELECT `name` FROM `states` WHERE `id` = '" . $rowT['state-id'] . "';");
						$rowS = Database::get_assoc_array($resS);
						$acl = 'مدیر مرکز <' . $rowC['name'] . '> در شهرستان <' . $rowT['name'] . '> در استان <' . $rowS['name'] . '>';
						break;
					case 'Unit Manager' :
						$resU = Database::execute_query("SELECT `name`, `center-id` FROM `units` WHERE `id` = '" . $row['access-level-id'] . "';");
						$rowU = Database::get_assoc_array($resU);
						$resC = Database::execute_query("SELECT `name`, `town-id` FROM `centers` WHERE `id` = '" . $rowU['center-id'] . "';");
						$rowC = Database::get_assoc_array($resC);
						$resT = Database::execute_query("SELECT `name`, `state-id` FROM `towns` WHERE `id` = '" . $rowC['town-id'] . "';");
						$rowT = Database::get_assoc_array($resT);
						$resS = Database::execute_query("SELECT `name` FROM `states` WHERE `id` = '" . $rowT['state-id'] . "';");
						$rowS = Database::get_assoc_array($resS);
						$acl = 'مدیر واحد <' . $rowU['name'] . '> در مرکز <' . $rowC['name'] . '> در شهرستان <' . $rowT['name'] . '> در استان <' . $rowS['name'] . '>';
						break;
					case 'Hygiene Unit Manager' :
						$resH = Database::execute_query("SELECT `name`, `center-id` FROM `hygiene-units` WHERE `id` = '" . $row['access-level-id'] . "';");
						$rowH = Database::get_assoc_array($resH);
						$resC = Database::execute_query("SELECT `name`, `town-id` FROM `centers` WHERE `id` = '" . $rowH['center-id'] . "';");
						$rowC = Database::get_assoc_array($resC);
						$resT = Database::execute_query("SELECT `name`, `state-id` FROM `towns` WHERE `id` = '" . $rowC['town-id'] . "';");
						$rowT = Database::get_assoc_array($resT);
						$resS = Database::execute_query("SELECT `name` FROM `states` WHERE `id` = '" . $rowT['state-id'] . "';");
						$rowS = Database::get_assoc_array($resS);
						$acl = 'مدیر خانه‌بهداشت <' . $rowH['name'] . '> در مرکز <' . $rowC['name'] . '> در شهرستان <' . $rowT['name'] . '> در استان <' . $rowS['name'] . '>';
						break;
					case 'State Author' :
						$resS = Database::execute_query("SELECT `name` FROM `states` WHERE `id` = '" . $row['access-level-id'] . "';");
						$rowS = Database::get_assoc_array($resS);
						$acl = 'نویسنده استان <' . $rowS['name'] . '>';
						break;
					case 'Town Author' :
						$resT = Database::execute_query("SELECT `name`, `state-id` FROM `towns` WHERE `id` = '" . $row['access-level-id'] . "';");
						$rowT = Database::get_assoc_array($resT);
						$resS = Database::execute_query("SELECT `name` FROM `states` WHERE `id` = '" . $rowT['state-id'] . "';");
						$rowS = Database::get_assoc_array($resS);
						$acl = 'نویسنده شهرستان <' . $rowT['name'] . '> در استان <' . $rowS['name'] . '>';
						break;
					case 'Center Author' :
						$resC = Database::execute_query("SELECT `name`, `town-id` FROM `centers` WHERE `id` = '" . $row['access-level-id'] . "';");
						$rowC = Database::get_assoc_array($resC);
						$resT = Database::execute_query("SELECT `name`, `state-id` FROM `towns` WHERE `id` = '" . $rowC['town-id'] . "';");
						$rowT = Database::get_assoc_array($resT);
						$resS = Database::execute_query("SELECT `name` FROM `states` WHERE `id` = '" . $rowT['state-id'] . "';");
						$rowS = Database::get_assoc_array($resS);
						$acl = 'نویسنده مرکز <' . $rowC['name'] . '> در شهرستان <' . $rowT['name'] . '> در استان <' . $rowS['name'] . '>';
						break;
					case 'Unit Author' :
						$resU = Database::execute_query("SELECT `name`, `center-id` FROM `units` WHERE `id` = '" . $row['access-level-id'] . "';");
						$rowU = Database::get_assoc_array($resU);
						$resC = Database::execute_query("SELECT `name`, `town-id` FROM `centers` WHERE `id` = '" . $rowU['center-id'] . "';");
						$rowC = Database::get_assoc_array($resC);
						$resT = Database::execute_query("SELECT `name`, `state-id` FROM `towns` WHERE `id` = '" . $rowC['town-id'] . "';");
						$rowT = Database::get_assoc_array($resT);
						$resS = Database::execute_query("SELECT `name` FROM `states` WHERE `id` = '" . $rowT['state-id'] . "';");
						$rowS = Database::get_assoc_array($resS);
						$acl = 'نویسنده واحد <' . $rowU['name'] . '> در مرکز <' . $rowC['name'] . '> در شهرستان <' . $rowT['name'] . '> در استان <' . $rowS['name'] . '>';
						break;
					case 'Hygiene Unit Author' :
						$resH = Database::execute_query("SELECT `name`, `center-id` FROM `hygiene-units` WHERE `id` = '" . $row['access-level-id'] . "';");
						$rowH = Database::get_assoc_array($resH);
						$resC = Database::execute_query("SELECT `name`, `town-id` FROM `centers` WHERE `id` = '" . $rowH['center-id'] . "';");
						$rowC = Database::get_assoc_array($resC);
						$resT = Database::execute_query("SELECT `name`, `state-id` FROM `towns` WHERE `id` = '" . $rowC['town-id'] . "';");
						$rowT = Database::get_assoc_array($resT);
						$resS = Database::execute_query("SELECT `name` FROM `states` WHERE `id` = '" . $rowT['state-id'] . "';");
						$rowS = Database::get_assoc_array($resS);
						$acl = 'نویسنده خانه‌بهداشت <' . $rowH['name'] . '> در مرکز <' . $rowC['name'] . '> در شهرستان <' . $rowT['name'] . '> در استان <' . $rowS['name'] . '>';
						break;
					case 'Multi Author' :
						$acl = 'نویسنده چندمنظوره';
						break;
				}
				$htmlOut .= '<td>' . $acl . '</td>';
				$htmlOut .= '<td>';
				if (($row['access-level'] == 'State Manager') and ($row['id'] != $_SESSION['user']['userId'])) {
				} else
					$htmlOut .= '<a href="?cmd=removeUser&userId=' . $row['id'] . '"><img src="../illustrator/images/icons/remove.png" title="حذف کاربر ' . $row['username'] . '" /></a>';
				$htmlOut .= '</td><td>';
				if (($row['access-level'] == 'State Manager') and ($row['id'] != $_SESSION['user']['userId'])) {
				} else
					$htmlOut .= '<a href="?cmd=editUser&userId=' . $row['id'] . '"><img src="../illustrator/images/icons/edit.png" title="ویرایش کاربر ' . $row['username'] . '" /></a>';

				$htmlOut .= '</td></tr>';
				$counter++;
			}
			$htmlOut .= '</tbody></table></fieldset><br />';
		}

		return $htmlOut;
	}

	public function removeUser($id) {
		$res = Database::execute_query("SELECT * FROM `users` WHERE `id` = '$id';");
		$row = Database::get_assoc_array($res);
		$_logTXT = 'حذف کاربر به‌نام "' . $row['name'] . ' ' . $row['last-name'] . '" از سیستم';

		try {
			Database::execute_query("DELETE FROM `users` WHERE `id` = '" . $id . "';");
		} catch ( exception $e ) {
			return $e -> getMessage();
		}
		FormProccessor::_makeLog($_logTXT);

		header("Location: " . $_SERVER['HTTP_REFERER']);
		exit ;
	}

	public function editUser($id) {
		$htmlOut = '';
		$res = Database::execute_query("SELECT * FROM `users` WHERE `id` = '$id';");
		$row = Database::get_assoc_array($res);
		$htmlOut .= '<script type="text/javascript">';
		$htmlOut .= '$(document).ajaxStart(function() {$(\'#loading\').show();$(\'#mngDiv\').css(\'opacity\', \'0.3\');});' . '$(document).ajaxSuccess(function() {$(\'#loading\').hide();$(\'#mngDiv\').css(\'opacity\', \'1\');});';
		$htmlOut .= '</script>';
		$htmlOut .= '<div class="loading" id="loadingRep">در حال بارگذاری؛ لطفا صبر کنید... <img src="../statisticalPlotter/loading.gif" /></div>';
		$htmlOut .= '<div id="mngDiv"><form action="' . substr(strrchr($_SERVER['PHP_SELF'], '/'), 1) . '" method="post" class="narin">';
		$htmlOut .= '<input type="hidden" name="narin:token" value="' . Narin::create_token() . '" />';
		$htmlOut .= '<input type="hidden" name="userId" value="' . $_GET['userId'] . '" />';
		$htmlOut .= '<input type="hidden" name="username" value="' . $row['username'] . '" />';
		$htmlOut .= '<fieldset><legend>اصلاح کاربر</legend>';
		$acl = '';
		switch ($row['access-level']) {
			case 'State Manager' :
				$resT = Database::execute_query("SELECT `name` FROM `states` WHERE `id` = '" . $row['access-level-id'] . "';");
				$rowT = Database::get_assoc_array($resT);
				$acl = 'مدیر استان "' . $rowT['name'] . '"';
				break;
			case 'Town Manager' :
				$resT = Database::execute_query("SELECT `name` FROM `towns` WHERE `id` = '" . $row['access-level-id'] . "';");
				$rowT = Database::get_assoc_array($resT);
				$acl = 'مدیر شهرستان "' . $rowT['name'] . '"';
				break;
			case 'Center Manager' :
				$resT = Database::execute_query("SELECT `name` FROM `centers` WHERE `id` = '" . $row['access-level-id'] . "';");
				$rowT = Database::get_assoc_array($resT);
				$acl = 'مدیر مرکز "' . $rowT['name'] . '"';
				break;
			case 'Unit Manager' :
				$resT = Database::execute_query("SELECT `name` FROM `units` WHERE `id` = '" . $row['access-level-id'] . "';");
				$rowT = Database::get_assoc_array($resT);
				$acl = 'مدیر واحد "' . $rowT['name'] . '"';
				break;
			case 'Hygiene Unit Manager' :
				$resT = Database::execute_query("SELECT `name` FROM `hygiene-units` WHERE `id` = '" . $row['access-level-id'] . "';");
				$rowT = Database::get_assoc_array($resT);
				$acl = 'مدیر خانه بهداشت "' . $rowT['name'] . '"';
				break;
			case 'State Author' :
				$resT = Database::execute_query("SELECT `name` FROM `states` WHERE `id` = '" . $row['access-level-id'] . "';");
				$rowT = Database::get_assoc_array($resT);
				$acl = 'نویسنده استان "' . $rowT['name'] . '"';
				break;
			case 'Town Author' :
				$resT = Database::execute_query("SELECT `name` FROM `towns` WHERE `id` = '" . $row['access-level-id'] . "';");
				$rowT = Database::get_assoc_array($resT);
				$acl = 'نویسنده شهرستان "' . $rowT['name'] . '"';
				break;
			case 'Center Author' :
				$resT = Database::execute_query("SELECT `name` FROM `centers` WHERE `id` = '" . $row['access-level-id'] . "';");
				$rowT = Database::get_assoc_array($resT);
				$acl = 'نویسنده مرکز "' . $rowT['name'] . '"';
				break;
			case 'Unit Author' :
				$resT = Database::execute_query("SELECT `name` FROM `units` WHERE `id` = '" . $row['access-level-id'] . "';");
				$rowT = Database::get_assoc_array($resT);
				$acl = 'نویسنده واحد "' . $rowT['name'] . '"';
				break;
			case 'Hygiene Unit Author' :
				$resT = Database::execute_query("SELECT `name` FROM `hygiene-units` WHERE `id` = '" . $row['access-level-id'] . "';");
				$rowT = Database::get_assoc_array($resT);
				$acl = 'نویسنده خانه بهداشت "' . $rowT['name'] . '"';
				break;
			case 'Multi Author' :
				$acl = 'نویسنده چند منظوره';
				break;
		}
		$htmlOut .= '<p>(<a href="#" style="color: blue; font-weight: bold;" onclick="editACL();return false;">تغییر سطح دسترسی</a>)&nbsp;-&nbsp;(<a href="#" style="color: blue; font-weight: bold;" onclick="editPasswd();return false;">تغییر رمزعبور</a>)</p>';
		$htmlOut .= '<p>سطح دسترسی فعلی کاربر: <span style="font-weight: bold;">' . $acl . '</span>.</p>';
		$htmlOut .= '<div id="acl_cat"></div>';
		$htmlOut .= '<div id="acl_items"></div>';

		$htmlOut .= '<div id="townManagerACLDiv">';
		$htmlOut .= '<fieldset style="width: 300px; margin: 10px 5px 10px auto;"><legend>جزئیات سطح دسترسی ها</legend>';
		$htmlOut .= '<input type="checkbox" name="addCenterDrugsAndConsumingEquipments" id="addCenterDrugsAndConsumingEquipments" />';
		$htmlOut .= '<label for="addCenterDrugsAndConsumingEquipments">مدیریت داروها و تجهیزات مصرفی مراکز</label><br />';
		$htmlOut .= '<input type="checkbox" name="addCenterNonConsumingEquipments" id="addCenterNonConsumingEquipments" />';
		$htmlOut .= '<label for="addCenterNonConsumingEquipments">مدیریت تجهیزات غیرمصرفی مراکز</label><br />';
		$htmlOut .= '<input type="checkbox" name="manageCenterBuildings" id="manageCenterBuildings" />';
		$htmlOut .= '<label for="manageCenterBuildings">مدیریت ساختمان های مراکز</label><br />';
		$htmlOut .= '<input type="checkbox" name="addCenterBuildingCharge" id="addCenterBuildingCharge" />';
		$htmlOut .= '<label for="addCenterBuildingCharge">مدیریت هزینه نگهداری ساختمان های  مراکز</label><br />';
		$htmlOut .= '<input type="checkbox" name="manageCenterVehicles" id="manageCenterVehicles" />';
		$htmlOut .= '<label for="manageCenterVehicles">مدیریت وسایل نقلیه مراکز</label><br />';
		$htmlOut .= '<input type="checkbox" name="addCenterVehicleCharge" id="addCenterVehicleCharge" />';
		$htmlOut .= '<label for="addCenterVehicleCharge">مدیریت هزینه نگهداری وسایل نقلیه مراکز</label><br />';
		$htmlOut .= '<input type="checkbox" name="addCenterGeneralCharge" id="addCenterGeneralCharge" />';
		$htmlOut .= '<label for="addCenterGeneralCharge">مدیریت هزینه های عمومی مراکز</label></fieldset><br />';
		$htmlOut .= '</div>';

		$htmlOut .= '<div id="centerManagerACLDiv">';
		$htmlOut .= '<fieldset style="width: 300px; margin: 10px 5px 10px auto;"><legend>جزئیات سطح دسترسی ها</legend>';
		$htmlOut .= '<input type="checkbox" name="c_addCenterDrugsAndConsumingEquipments" id="c_addCenterDrugsAndConsumingEquipments" />';
		$htmlOut .= '<label for="c_addCenterDrugsAndConsumingEquipments">مدیریت داروها و تجهیزات مصرفی مرکز</label><br />';
		$htmlOut .= '<input type="checkbox" name="c_addCenterNonConsumingEquipments" id="c_addCenterNonConsumingEquipments" />';
		$htmlOut .= '<label for="c_addCenterNonConsumingEquipments">مدیریت تجهیزات غیرمصرفی مرکز</label><br />';
		$htmlOut .= '<input type="checkbox" name="c_manageCenterBuildings" id="c_manageCenterBuildings" />';
		$htmlOut .= '<label for="c_manageCenterBuildings">مدیریت ساختمان های مرکز</label><br />';
		$htmlOut .= '<input type="checkbox" name="c_addCenterBuildingCharge" id="c_addCenterBuildingCharge" />';
		$htmlOut .= '<label for="c_addCenterBuildingCharge">مدیریت هزینه نگهداری ساختمان های  مرکز</label><br />';
		$htmlOut .= '<input type="checkbox" name="c_manageCenterVehicles" id="c_manageCenterVehicles" />';
		$htmlOut .= '<label for="c_manageCenterVehicles">مدیریت وسایل نقلیه مرکز</label><br />';
		$htmlOut .= '<input type="checkbox" name="c_addCenterVehicleCharge" id="c_addCenterVehicleCharge" />';
		$htmlOut .= '<label for="c_addCenterVehicleCharge">مدیریت هزینه نگهداری وسایل نقلیه مرکز</label><br />';
		$htmlOut .= '<input type="checkbox" name="c_addCenterGeneralCharge" id="c_addCenterGeneralCharge" />';
		$htmlOut .= '<label for="c_addCenterGeneralCharge">مدیریت هزینه های عمومی مرکز</label></fieldset><br />';
		$htmlOut .= '</div>';

		$htmlOut .= '<div id="unitManagerACLDiv">';
		$htmlOut .= '<fieldset style="width: 300px; margin: 10px 5px 10px auto;"><legend>جزئیات سطح دسترسی ها</legend>';
		$htmlOut .= '<input type="checkbox" name="manageUnitPersonnel" id="manageUnitPersonnel" />';
		$htmlOut .= '<label for="manageUnitPersonnel">مدیریت پرسنل واحد</label><br />';
		$htmlOut .= '<input type="checkbox" name="unitPersonnelFinancialInfo" id="unitPersonnelFinancialInfo" />';
		$htmlOut .= '<label for="unitPersonnelFinancialInfo">مدیریت اطلاعات مالی پرسنل واحد</label><br />';
		$htmlOut .= '<input type="checkbox" name="manageUnitServices" id="manageUnitServices" />';
		$htmlOut .= '<label for="manageUnitServices">مدیریت خدمات واحد</label><br />';
		$htmlOut .= '</fieldset><br />';
		$htmlOut .= '</div>';

		$htmlOut .= '<div id="hygieneUnitManagerACLDiv">';
		$htmlOut .= '<fieldset style="width: 400px; margin: 10px 5px 10px auto;"><legend>جزئیات سطح دسترسی ها</legend>';
		$htmlOut .= '<input type="checkbox" name="addHygieneUnitDrugsAndConsumingEquipments" id="addHygieneUnitDrugsAndConsumingEquipments" />';
		$htmlOut .= '<label for="addHygieneUnitDrugsAndConsumingEquipments">مدیریت داروها و تجهیزات مصرفی خانه بهداشت</label><br />';
		$htmlOut .= '<input type="checkbox" name="addHygieneUnitNonConsumingEquipments" id="addHygieneUnitNonConsumingEquipments" />';
		$htmlOut .= '<label for="addHygieneUnitNonConsumingEquipments">مدیریت تجهیزات غیرمصرفی خانه بهداشت</label><br />';
		$htmlOut .= '<input type="checkbox" name="manageHygieneUnitPersonnel" id="manageHygieneUnitPersonnel" />';
		$htmlOut .= '<label for="manageHygieneUnitPersonnel">مدیریت بهورزان خانه بهداشت</label><br />';
		$htmlOut .= '<input type="checkbox" name="hygieneUnitPersonnelFinancialInfo" id="hygieneUnitPersonnelFinancialInfo" />';
		$htmlOut .= '<label for="hygieneUnitPersonnelFinancialInfo">مدیریت اطلاعات مالی بهورزان خانه بهداشت</label><br />';
		$htmlOut .= '<input type="checkbox" name="manageHygieneUnitBuildings" id="manageHygieneUnitBuildings" />';
		$htmlOut .= '<label for="manageHygieneUnitBuildings">مدیریت ساختمان های خانه بهداشت</label><br />';
		$htmlOut .= '<input type="checkbox" name="addHygieneUnitBuildingCharge" id="addHygieneUnitBuildingCharge" />';
		$htmlOut .= '<label for="addHygieneUnitBuildingCharge">مدیریت هزینه نگهداری ساختمان های خانه بهداشت</label><br />';
		$htmlOut .= '<input type="checkbox" name="manageHygieneUnitVehicles" id="manageHygieneUnitVehicles" />';
		$htmlOut .= '<label for="manageHygieneUnitVehicles">مدیریت وسایل نقلیه خانه بهداشت</label><br />';
		$htmlOut .= '<input type="checkbox" name="addHygieneUnitVehicleCharge" id="addHygieneUnitVehicleCharge" />';
		$htmlOut .= '<label for="addHygieneUnitVehicleCharge">مدیریت هزینه نگهداری وسایل نقلیه خانه بهداشت</label><br />';
		$htmlOut .= '<input type="checkbox" name="manageHygieneUnitServices" id="manageHygieneUnitServices" />';
		$htmlOut .= '<label for="manageHygieneUnitServices">مدیریت خدمات خانه بهداشت</label><br />';
		$htmlOut .= '<input type="checkbox" name="addHygieneUnitGeneralCharge" id="addHygieneUnitGeneralCharge" />';
		$htmlOut .= '<label for="addHygieneUnitGeneralCharge">مدیریت هزینه های عمومی خانه بهداشت</label></fieldset><br />';
		$htmlOut .= '</div>';

		$htmlOut .= '<label for="name" class="required">نام:</label>';
		$htmlOut .= '<input type="text" name="name" id="name" maxlength="50" value="' . $row['name'] . '"';
		$htmlOut .= ' class="validate required ';
		if (isset($r['invalid']['name']))
			$htmlOut .= ' invalid';
		$htmlOut .= '" />';
		$htmlOut .= '<span class="description"';
		if (!isset($r['invalid']['name']))
			$htmlOut .= ' style="display:none;"';
		$htmlOut .= '>فقط حروف فارسی</span><br />';
		$htmlOut .= '<label for="lastName" class="required">نام خانوادگی:</label>';
		$htmlOut .= '<input type="text" name="lastName" id="lastName" maxlength="50" value="' . $row['last-name'] . '"';
		$htmlOut .= ' class="validate required ';
		if (isset($r['invalid']['lastName']))
			$htmlOut .= ' invalid';
		$htmlOut .= '" />';
		$htmlOut .= '<span class="description"';
		if (!isset($r['invalid']['lastName']))
			$htmlOut .= ' style="display:none;"';
		$htmlOut .= '>فقط حروف فارسی</span><br />';
		$htmlOut .= '<label for="email" class="required">پست الکترونیک:</label>';
		$htmlOut .= '<input type="text" name="email" id="email" maxlength="255" dir="ltr" class="validate required email" value="' . $row['email'] . '" />';
		$htmlOut .= '<div id="newPasswd"></div>';
		$htmlOut .= '<br /><input type="submit" name="submitEditUser" value="اصلاح کاربر" />';
		$htmlOut .= '</fieldset></form></div>';

		return $htmlOut;
	}

	private function hasPermission($cmd) {
		//take care of _logs actions
		$stateManagerCMDs = array('logOut', 'manageTowns', 'editTown', 'removeTown', 'manageAvailableServices', 'manageSibJobsAndPeriods', 'editSibJobTitle', 'removeSibJobTitle', 'editSibPeriod', 'removeSibPeriod', 'removeService', 'editService', 'manageAvailableJobTitles', 'editJobtitle', 'removeJobtitle', 'manageAvailableDrugs', 'editDrug', 'removeDrug', 'manageAvailableConsumingEquipments', 'editConsumingEquip', 'manageAvailableNonConsumingEquipments', 'editNonConsumingEquipment', 'removeNonConsumingEquipment', 'manageGeneralChargesTypes', 'editGeneralChargeType', 'removeGeneralChargeType', 'reports', 'manageUsers', 'editUser', 'removeUser', 'manageCenters', 'manageUnits', 'removeCenter', 'editCenter', 'modifyHygieneUnits', 'removeHygieneUnit', 'editHygieneUnit', 'manageCenterDrugs', 'manageCenterConsumingEquipments', 'removeCenterDrug', 'addCenterNonConsumingEquipments', 'removeCenterNonConsumingEquip', 'manageCenterBuildings', 'removeCenterBuilding', 'editCenterBuilding', 'addCenterBuildingCharge', 'removeCenterBuildingCharge', 'editCenterBuildingCharge', 'manageCenterVehicles', 'removeCenterVehicle', 'editCenterVehicle', 'addCenterVehicleCharge', 'removeCenterVehicleCharge', 'editCenterVehicleCharge', 'addCenterGeneralCharge', 'removeCenterGeneralCharge', 'editCenterGeneralCharge', 'modifyUnits', 'removeUnit', 'editUnit', 'manageUnitPersonnel', 'removeUnitPersonnel', 'unitPersonnelFinancialInfo', 'manageUnitServices', 'removeUnitService', 'addHygieneUnitDrugs', 'addHygieneUnitConsumingEquipments', 'removeHygieneUnitDrug', 'addHygieneUnitNonConsumingEquipments', 'removeHygieneUnitNonConsumingEquip', 'manageHygieneUnitPersonnel', 'removeHygieneUnitPersonnel', 'editHygieneUnitPersonnel', 'hygieneUnitPersonnelFinancialInfo', 'manageHygieneUnitBuildings', 'editHygieneUnitBuilding', 'removeHygieneUnitBuilding', 'addHygieneUnitBuildingCharge', 'manageHygieneUnitVehicles', 'editHygieneUnitVehicle', 'removeHygieneUnitVehicle', 'addHygieneUnitVehicleCharge', 'removeHGVehicleCharge', 'editHGVehicleCharge', 'addHygieneUnitGeneralCharge', 'editHygieneUnitGeneralCharge', 'removeHygieneUnitGeneralCharge', 'manageHygieneUnitServices', 'removeHygieneUnitService', 'manageHygieneUnits', 'manageCentersDrugs', 'manageCentersConsumings', 'manageCentersNonConsumings', 'manageCentersBuildings', 'manageCentersBuildingsCharge', 'manageCentersVehicles', 'manageCentersVehiclesCharge', 'manageCentersGeneralCharges', 'manageHygieneUnitsDrugs', 'manageHygieneUnitsConsumings', 'manageHygieneUnitsNonConsumings', 'manageHygieneUnitsBuildings', 'manageHygieneUnitsBuildingsCharge', 'removeHGBuildingCharge', 'editHGBuildingCharge', 'manageHygieneUnitsVehicles', 'manageHygieneUnitsVehiclesCharge', 'manageHygieneUnitsGeneralCharges', 'manageHygieneUnitsPersonnel', 'manageHygieneUnitPersonnelFinancialInfo', 'manageHygieneUnitsServices', 'manageUnitsPersonnels', 'editUnitPersonnel', 'manageUnitPersonnelFinancialInfo', 'manageUnitsServices', 'searchPersonnel', 'manageEditFrequencyRequests', 'viewUnitEditReqDetail', 'viewHGUnitEditReqDetail', '_LOGS');
		$townManagerCMDs = array('logOut', 'manageCenters', 'manageUnits', 'removeCenter', 'editCenter', 'modifyHygieneUnits', 'removeHygieneUnit', 'editHygieneUnit', 'manageCenterDrugs', 'manageCenterConsumingEquipments', 'removeCenterDrug', 'addCenterNonConsumingEquipments', 'removeCenterNonConsumingEquip', 'manageCenterBuildings', 'removeCenterBuilding', 'editCenterBuilding', 'addCenterBuildingCharge', 'removeCenterBuildingCharge', 'editCenterBuildingCharge', 'manageCenterVehicles', 'removeCenterVehicle', 'editCenterVehicle', 'addCenterVehicleCharge', 'removeCenterVehicleCharge', 'editCenterVehicleCharge', 'addCenterGeneralCharge', 'removeCenterGeneralCharge', 'editCenterGeneralCharge', 'modifyUnits', 'removeUnit', 'editUnit', 'manageUnitPersonnel', 'removeUnitPersonnel', 'editUnitPersonnel', 'unitPersonnelFinancialInfo', 'manageUnitServices', 'removeUnitService', 'addHygieneUnitDrugs', 'addHygieneUnitConsumingEquipments', 'removeHygieneUnitDrug', 'addHygieneUnitNonConsumingEquipments', 'removeHygieneUnitNonConsumingEquip', 'manageHygieneUnitPersonnel', 'removeHygieneUnitPersonnel', 'editHygieneUnitPersonnel', 'hygieneUnitPersonnelFinancialInfo', 'manageHygieneUnitBuildings', 'editHygieneUnitBuilding', 'removeHygieneUnitBuilding', 'addHygieneUnitBuildingCharge', 'removeHGBuildingCharge', 'editHGBuildingCharge', 'manageHygieneUnitVehicles', 'editHygieneUnitVehicle', 'removeHygieneUnitVehicle', 'addHygieneUnitVehicleCharge', 'removeHGVehicleCharge', 'editHGVehicleCharge', 'addHygieneUnitGeneralCharge', 'editHygieneUnitGeneralCharge', 'removeHygieneUnitGeneralCharge', 'manageHygieneUnitServices', 'removeHygieneUnitService');
		$centerManagerCMDs = array('logOut', 'manageUnits', 'manageCenterDrugs', 'manageCenterConsumingEquipments', 'removeCenterDrug', 'addCenterNonConsumingEquipments', 'removeCenterNonConsumingEquip', 'manageCenterBuildings', 'removeCenterBuilding', 'editCenterBuilding', 'addCenterBuildingCharge', 'removeCenterBuildingCharge', 'editCenterBuildingCharge', 'manageCenterVehicles', 'removeCenterVehicle', 'editCenterVehicle', 'addCenterVehicleCharge', 'removeCenterVehicleCharge', 'editCenterVehicleCharge', 'addCenterGeneralCharge', 'removeCenterGeneralCharge', 'editCenterGeneralCharge', 'modifyUnits', 'removeUnit', 'editUnit', 'manageUnitPersonnel', 'removeUnitPersonnel', 'editUnitPersonnel', 'unitPersonnelFinancialInfo', 'manageUnitServices', 'removeUnitService', 'modifyHygieneUnits', 'removeHygieneUnit', 'editHygieneUnit', 'addHygieneUnitDrugs', 'addHygieneUnitConsumingEquipments', 'removeHygieneUnitDrug', 'addHygieneUnitNonConsumingEquipments', 'removeHygieneUnitNonConsumingEquip', 'manageHygieneUnitPersonnel', 'removeHygieneUnitPersonnel', 'editHygieneUnitPersonnel', 'hygieneUnitPersonnelFinancialInfo', 'manageHygieneUnitBuildings', 'editHygieneUnitBuilding', 'removeHygieneUnitBuilding', 'addHygieneUnitBuildingCharge', 'removeHGBuildingCharge', 'editHGBuildingCharge', 'manageHygieneUnitVehicles', 'editHygieneUnitVehicle', 'removeHygieneUnitVehicle', 'addHygieneUnitVehicleCharge', 'removeHGVehicleCharge', 'editHGVehicleCharge', 'addHygieneUnitGeneralCharge', 'editHygieneUnitGeneralCharge', 'removeHygieneUnitGeneralCharge', 'manageHygieneUnitServices', 'removeHygieneUnitService');
		$hygieneUnitManagerCMDs = array('logOut', 'modifyHygieneUnits', 'addHygieneUnitDrugs', 'addHygieneUnitConsumingEquipments', 'removeHygieneUnitDrug', 'addHygieneUnitNonConsumingEquipments', 'removeHygieneUnitNonConsumingEquip', 'manageHygieneUnitPersonnel', 'removeHygieneUnitPersonnel', 'editHygieneUnitPersonnel', 'hygieneUnitPersonnelFinancialInfo', 'manageHygieneUnitBuildings', 'editHygieneUnitBuilding', 'removeHygieneUnitBuilding', 'addHygieneUnitBuildingCharge', 'removeHGBuildingCharge', 'editHGBuildingCharge', 'manageHygieneUnitVehicles', 'editHygieneUnitVehicle', 'removeHygieneUnitVehicle', 'addHygieneUnitVehicleCharge', 'removeHGVehicleCharge', 'editHGVehicleCharge', 'addHygieneUnitGeneralCharge', 'editHygieneUnitGeneralCharge', 'removeHygieneUnitGeneralCharge', 'manageHygieneUnitServices', 'removeHygieneUnitService');
		$unitManagerCMDs = array('logOut', 'modifyUnits', 'manageUnitPersonnel', 'removeUnitPersonnel', 'editUnitPersonnel', 'unitPersonnelFinancialInfo', 'manageUnitServices', 'removeUnitService');

		switch ( $_SESSION['user']['acl'] ) {
			case 'State Manager' :
				if (in_array($cmd, $stateManagerCMDs))
					return TRUE;
				break;
			case 'Town Manager' :
				if (in_array($cmd, $townManagerCMDs))
					return TRUE;
				break;
			case 'Center Manager' :
				if (in_array($cmd, $centerManagerCMDs))
					return TRUE;
				break;
			case 'Hygiene Unit Manager' :
				if (in_array($cmd, $hygieneUnitManagerCMDs))
					return TRUE;
				break;
			case 'Unit Manager' :
				if (in_array($cmd, $unitManagerCMDs))
					return TRUE;
				break;
		}

		return FALSE;
	}

	private function hasIdAccess($cmd, $id) {
		$centerIds = array();
		$unitIds = array();
		$hygieneUnitIds = array();
		switch ( $_SESSION['user']['acl'] ) {
			case 'State Manager' :
				return TRUE;
				break;
			case 'Town Manager' :
				switch ($cmd) {
					case 'manageCenters' :
						if ($id == $_SESSION['user']['acl-id'])
							return TRUE;
						break;
					case 'manageUnits' :
						$res = Database::execute_query("SELECT `id` FROM `centers` WHERE `town-id` = '" . $_SESSION['user']['acl-id'] . "';");
						while ($row = Database::get_assoc_array($res))
							$centerIds[] = $row['id'];
						if (in_array($id, $centerIds))
							return TRUE;
						break;
					case 'modifyUnits' :
						foreach ($centerIds as $value) {
							$res = Database::execute_query("SELECT `id` FROM `units` WHERE `center-id` = '" . $value . "';");
							while ($row = Database::get_assoc_array($res))
								$unitIds[] = $row['id'];
						}
						if (in_array($id, $unitIds))
							return TRUE;
						break;
					case 'modifyHygieneUnits' :
						foreach ($centerIds as $value) {
							$res = Database::execute_query("SELECT `id` FROM `hygiene-units` WHERE `center-id` = '" . $value . "';");
							while ($row = Database::get_assoc_array($res))
								$hygieneUnitIds[] = $row['id'];
						}
						if (in_array($id, $hygieneUnitIds))
							return TRUE;
						break;
				}
				break;
			case 'Center Manager' :
				switch ($cmd) {
					case 'manageUnits' :
						if ($id == $_SESSION['user']['acl-id'])
							return TRUE;
						break;
					case 'modifyUnits' :
						$res = Database::execute_query("SELECT `id` FROM `unit` WHERE `center-id` = '" . $_SESSION['user']['acl-id'] . "';");
						while ($row = Database::get_assoc_array($res))
							$unitIds[] = $row['id'];
						if (in_array($id, $unitIds))
							return TRUE;
						break;
					case 'modifyHygieneUnits' :
						$res = Database::execute_query("SELECT `id` FROM `hygiene-unit` WHERE `center-id` = '" . $_SESSION['user']['acl-id'] . "';");
						while ($row = Database::get_assoc_array($res))
							$hygieneUnitIds[] = $row['id'];
						if (in_array($id, $hygieneUnitIds))
							return TRUE;
						break;
				}
				break;
			case 'Hygiene Unit Manager' :
				if ($cmd == 'modifyHygieneUnits') {
					if ($id == $_SESSION['user']['acl-id'])
						return TRUE;
				}
				break;
			case 'Unit Manager' :
				if ($cmd == 'modifyUnits') {
					if ($id == $_SESSION['user']['acl-id'])
						return TRUE;
				}
				break;
		}

		return FALSE;
	}

	private function searchPersonnel() {
		$htmlOut = '';
		$htmlOut .= '<fieldset><legend>جستجوی پرسنل</legend>';
		$htmlOut .= '<p style="text-align: center;">در این بخش می توانید پرسنل یک واحد یا خانه بهداشت را بر اساس نام و/یا نام خانوادگی جستجو نمایید.</p>';
		$htmlOut .= '<fieldset style="width: 250px;"><legend>جستجو براساس: </legend>';
		$htmlOut .= '<form action="' . substr(strrchr($_SERVER['PHP_SELF'], '/'), 1) . '?cmd=searchPersonnel" method="post" class="narin" style="width: 300px; margin-right: auto; margin-left: auto;">';
		$htmlOut .= '<label for="input_name">نام:</label>';
		$htmlOut .= '<input type="text" name="input_name" id="input_name" maxlength="255" />';
		$htmlOut .= '<label for="input_lastname">نام خانوادگی:</label>';
		$htmlOut .= '<input type="text" name="input_lastname" id="input_lastname" maxlength="255" />';
		$htmlOut .= '</fieldset>';
		$htmlOut .= '<input type="submit" value="جستجو" name="submitSearchAllPersonnel" style="margin: 10px 370px 0 auto;" /></form>';
		$htmlOut .= '<br /><div id="personnelSearchResult">';
		if (isset($_POST['submitSearchAllPersonnel'])) {
			if ($_POST['input_name'] != '' || $_POST['input_lastname'] != '') {
				$resUnit = Database::execute_query("SELECT * FROM `unit-personnels` WHERE (`name` = '" . Database::filter_str($_POST['input_name']) . "' OR `lastname` = '" . Database::filter_str($_POST['input_lastname']) . "') AND `deleted` = '0';");
				$resHygieneUnit = Database::execute_query("SELECT * FROM `hygiene-unit-personnels` WHERE (`name` = '" . Database::filter_str($_POST['input_name']) . "' OR `lastname` = '" . Database::filter_str($_POST['input_lastname']) . "') AND `deleted` = '0';");
				if (Database::num_of_rows($resUnit) > 0) {
					$htmlOut .= '<p>نتیجه جستجو در واحدها</p>';
					$htmlOut .= '<table align="center" class="contentTable" border="0" cellpadding="0" cellspacing="1"><thead><tr><th>نام</th><th>نام خانوادگی</th><th>رده پرسنلی</th><th>نام واحد</th><th>نام مرکز</th></tr></thead><tbody>';
					while ($rowUnit = Database::get_assoc_array($resUnit)) {
						$resUnitName = Database::execute_query("SELECT `center-id`, `name` FROM `units` WHERE `id` = '" . $rowUnit['unit-id'] . "';");
						$tmp = Database::get_assoc_array($resUnitName);
						$unitName = $tmp['name'];
						$resCenterName = Database::execute_query("SELECT `id`, `name` FROM `centers` WHERE `id` = '" . $tmp['center-id'] . "';");
						$tmp = Database::get_assoc_array($resCenterName);
						$centerName = $tmp['name'];
						$htmlOut .= '<tr><td>' . $rowUnit['name'] . '</td>';
						$htmlOut .= '<td>' . $rowUnit['lastname'] . '</td>';
						$htmlOut .= '<td>' . $rowUnit['job-title'] . '</td>';
						$htmlOut .= '<td>' . $unitName . '</td>';
						$htmlOut .= '<td>' . $centerName . '</td></tr>';
					}
					$htmlOut .= '</tbody></table>';
				} else
					$htmlOut .= '<p class="warning">هیچ پرسنلی در واحدها یافت نشد.</p>';

				if (Database::num_of_rows($resHygieneUnit) > 0) {
					$htmlOut .= '<br /><br /><p>نتیجه جستجو در خانه های بهداشت</p>';
					$htmlOut .= '<table align="center" class="contentTable" border="0" cellpadding="0" cellspacing="1"><thead><tr><th>نام</th><th>نام خانوادگی</th><th>رده پرسنلی</th><th>نام خانه بهداشت</th><th>نام مرکز</th></tr></thead><tbody>';
					while ($rowHygieneUnit = Database::get_assoc_array($resHygieneUnit)) {
						$resHygieneUnitName = Database::execute_query("SELECT `center-id`, `name` FROM `hygiene-units` WHERE `id` = '" . $rowHygieneUnit['hygiene-unit-id'] . "';");
						$tmp = Database::get_assoc_array($resHygieneUnitName);
						$hygieneUnitName = $tmp['name'];
						$resCenterName = Database::execute_query("SELECT `id`, `name` FROM `centers` WHERE `id` = '" . $tmp['center-id'] . "';");
						$tmp = Database::get_assoc_array($resCenterName);
						$centerName = $tmp['name'];
						$htmlOut .= '<tr><td>' . $rowHygieneUnit['name'] . '</td>';
						$htmlOut .= '<td>' . $rowHygieneUnit['lastname'] . '</td>';
						$htmlOut .= '<td>' . $rowHygieneUnit['job-title'] . '</td>';
						$htmlOut .= '<td>' . $hygieneUnitName . '</td>';
						$htmlOut .= '<td>' . $centerName . '</td></tr>';
					}
					$htmlOut .= '</tbody></table>';
				} else
					$htmlOut .= '<p class="warning">هیچ بهورزی در خانه های بهداشت یافت نشد.</p>';

			} else
				$htmlOut .= '<p id="err">نام یا نام خانوادگی را وارد نمایید.</p>';
		}
		$htmlOut .= '</div></fieldset>';

		return $htmlOut;
	}

	private function logs() {
		$htmlOut = '';
		$logs = array();
		if (isset($_POST['viewLog']) and isset($_POST['userId'])) {
			$resLog = Database::execute_query("SELECT * FROM `_logs` WHERE `user-id` = '" . Database::filter_str($_POST['userId']) . "';");
			if (Database::num_of_rows($resLog) > 0) {
				while ($rowLog = Database::get_assoc_array($resLog)) {
					$logDate = date_parse($rowLog['_log-date']);
					if (AsynchronousProcess::isInDateRange($logDate['day'], $logDate['month'], $logDate['year'], $_POST['start_date:d'], $_POST['start_date:m'], $_POST['start_date:y'], $_POST['finish_date:d'], $_POST['finish_date:m'], $_POST['finish_date:y']) === true)
						$logs[] = $rowLog;
				}
				if(count($logs) > 0) {
					$fromDate = $_POST['start_date:y'] . '/' . $_POST['start_date:m'] . '/' . $_POST['start_date:d'];
					$toDate = $_POST['finish_date:y'] . '/' . $_POST['finish_date:m'] . '/' . $_POST['finish_date:d'];
					$htmlOut .= '<fieldset><legend>جزئیات کاربر انتخابی در بازه ' . $fromDate . ' تا ' . $toDate . '</legend>';
					$htmlOut .= '<br /><table align="right" class="contentTable" border="0" cellpadding="0" cellspacing="1">';
					$htmlOut .= '<thead><th width="40">ردیف</th><th width="70">زمان</th><th>جزئیات</th></thead><tbody>';
					$counter = 1;
					foreach ($logs as $logValue) {
						$htmlOut .= '<tr><td>' . $counter . '</td><td>' . $logValue['_log-date'] . '</td><td>' . $logValue['action-details'] . '</td></tr>';
						$counter++;
					}
					$htmlOut .= '</tbody></table></fieldset><br />';
					return $htmlOut;
				}
				else
					$htmlOut .= '<p class="warning">هیچ موردی برای کاربر موردنظر در بازه‌ی زمانی وارد شده وجود ندارد.</p>';
			}
			else
				$htmlOut .= '<p class="warning">هیچ موردی برای کاربر موردنظر وجود ندارد.</p>';
		}
		if (isset($_POST['viewLog']) and !isset($_POST['userId']))
				$htmlOut .= '<p id="err">خطا: کاربر موردنظر را انتخاب نکرده‌اید.</p>';
		$htmlOut .= '<p>در این بخش می‌توانید وقایع کاربران سیستم را مشاهده نمایید.<br />برای شروع بازه‌ی تاریخ موردنظر را وارد کرده و کاربر موردنظر را انتخاب کنید. </p>';
		$res = Database::execute_query("SELECT * FROM  `users` ORDER BY `last-name` ASC;");
		$counter = 1;
		if (Database::num_of_rows($res) > 0) {
			$htmlOut .= '<form action="" method="post" class="narin">';
			$htmlOut .= '<fieldset style="width: 520px;"><legend>بازه زمانی</legend>';
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
			$htmlOut .= '<br /><fieldset  style="width: 590px;"><legend>فهرست کاربران سیستم</legend>';
			$htmlOut .= '<br /><table align="right" class="contentTable" border="0" cellpadding="0" cellspacing="1">';
			$htmlOut .= '<thead><th width="40">ردیف</th><th>نام</th><th>نام خانوادگی</th><th width="300">سطح دسترسی</th><th width="50">انتخاب</th></thead><tbody>';
			$counter = 1;
			while ($row = Database::get_assoc_array($res)) {
				$htmlOut .= '<tr><td>' . $counter . '</td><td>' . $row['name'] . '</td>';
				$htmlOut .= '<td>' . $row['last-name'] . '</td>';
				$acl = '';
				switch ( $row['access-level'] ) {
					case 'State Manager' :
						$resS = Database::execute_query("SELECT `name` FROM `states` WHERE `id` = '" . $row['access-level-id'] . "';");
						$rowS = Database::get_assoc_array($resS);
						$acl = 'مدیر استان <' . $rowS['name'] . '>';
						break;
					case 'Town Manager' :
						$resT = Database::execute_query("SELECT `name`, `state-id` FROM `towns` WHERE `id` = '" . $row['access-level-id'] . "';");
						$rowT = Database::get_assoc_array($resT);
						$resS = Database::execute_query("SELECT `name` FROM `states` WHERE `id` = '" . $rowT['state-id'] . "';");
						$rowS = Database::get_assoc_array($resS);
						$acl = 'مدیر شهرستان <' . $rowT['name'] . '> در استان <' . $rowS['name'] . '>';
						break;
					case 'Center Manager' :
						$resC = Database::execute_query("SELECT `name`, `town-id` FROM `centers` WHERE `id` = '" . $row['access-level-id'] . "';");
						$rowC = Database::get_assoc_array($resC);
						$resT = Database::execute_query("SELECT `name`, `state-id` FROM `towns` WHERE `id` = '" . $rowC['town-id'] . "';");
						$rowT = Database::get_assoc_array($resT);
						$resS = Database::execute_query("SELECT `name` FROM `states` WHERE `id` = '" . $rowT['state-id'] . "';");
						$rowS = Database::get_assoc_array($resS);
						$acl = 'مدیر مرکز <' . $rowC['name'] . '> در شهرستان <' . $rowT['name'] . '> در استان <' . $rowS['name'] . '>';
						break;
					case 'Unit Manager' :
						$resU = Database::execute_query("SELECT `name`, `center-id` FROM `units` WHERE `id` = '" . $row['access-level-id'] . "';");
						$rowU = Database::get_assoc_array($resU);
						$resC = Database::execute_query("SELECT `name`, `town-id` FROM `centers` WHERE `id` = '" . $rowU['center-id'] . "';");
						$rowC = Database::get_assoc_array($resC);
						$resT = Database::execute_query("SELECT `name`, `state-id` FROM `towns` WHERE `id` = '" . $rowC['town-id'] . "';");
						$rowT = Database::get_assoc_array($resT);
						$resS = Database::execute_query("SELECT `name` FROM `states` WHERE `id` = '" . $rowT['state-id'] . "';");
						$rowS = Database::get_assoc_array($resS);
						$acl = 'مدیر واحد <' . $rowU['name'] . '> در مرکز <' . $rowC['name'] . '> در شهرستان <' . $rowT['name'] . '> در استان <' . $rowS['name'] . '>';
						break;
					case 'Hygiene Unit Manager' :
						$resH = Database::execute_query("SELECT `name`, `center-id` FROM `hygiene-units` WHERE `id` = '" . $row['access-level-id'] . "';");
						$rowH = Database::get_assoc_array($resH);
						$resC = Database::execute_query("SELECT `name`, `town-id` FROM `centers` WHERE `id` = '" . $rowH['center-id'] . "';");
						$rowC = Database::get_assoc_array($resC);
						$resT = Database::execute_query("SELECT `name`, `state-id` FROM `towns` WHERE `id` = '" . $rowC['town-id'] . "';");
						$rowT = Database::get_assoc_array($resT);
						$resS = Database::execute_query("SELECT `name` FROM `states` WHERE `id` = '" . $rowT['state-id'] . "';");
						$rowS = Database::get_assoc_array($resS);
						$acl = 'مدیر خانه‌بهداشت <' . $rowH['name'] . '> در مرکز <' . $rowC['name'] . '> در شهرستان <' . $rowT['name'] . '> در استان <' . $rowS['name'] . '>';
						break;
					case 'State Author' :
						$resS = Database::execute_query("SELECT `name` FROM `states` WHERE `id` = '" . $row['access-level-id'] . "';");
						$rowS = Database::get_assoc_array($resS);
						$acl = 'نویسنده استان <' . $rowS['name'] . '>';
						break;
					case 'Town Author' :
						$resT = Database::execute_query("SELECT `name`, `state-id` FROM `towns` WHERE `id` = '" . $row['access-level-id'] . "';");
						$rowT = Database::get_assoc_array($resT);
						$resS = Database::execute_query("SELECT `name` FROM `states` WHERE `id` = '" . $rowT['state-id'] . "';");
						$rowS = Database::get_assoc_array($resS);
						$acl = 'نویسنده شهرستان <' . $rowT['name'] . '> در استان <' . $rowS['name'] . '>';
						break;
					case 'Center Author' :
						$resC = Database::execute_query("SELECT `name`, `town-id` FROM `centers` WHERE `id` = '" . $row['access-level-id'] . "';");
						$rowC = Database::get_assoc_array($resC);
						$resT = Database::execute_query("SELECT `name`, `state-id` FROM `towns` WHERE `id` = '" . $rowC['town-id'] . "';");
						$rowT = Database::get_assoc_array($resT);
						$resS = Database::execute_query("SELECT `name` FROM `states` WHERE `id` = '" . $rowT['state-id'] . "';");
						$rowS = Database::get_assoc_array($resS);
						$acl = 'نویسنده مرکز <' . $rowC['name'] . '> در شهرستان <' . $rowT['name'] . '> در استان <' . $rowS['name'] . '>';
						break;
					case 'Unit Author' :
						$resU = Database::execute_query("SELECT `name`, `center-id` FROM `units` WHERE `id` = '" . $row['access-level-id'] . "';");
						$rowU = Database::get_assoc_array($resU);
						$resC = Database::execute_query("SELECT `name`, `town-id` FROM `centers` WHERE `id` = '" . $rowU['center-id'] . "';");
						$rowC = Database::get_assoc_array($resC);
						$resT = Database::execute_query("SELECT `name`, `state-id` FROM `towns` WHERE `id` = '" . $rowC['town-id'] . "';");
						$rowT = Database::get_assoc_array($resT);
						$resS = Database::execute_query("SELECT `name` FROM `states` WHERE `id` = '" . $rowT['state-id'] . "';");
						$rowS = Database::get_assoc_array($resS);
						$acl = 'نویسنده واحد <' . $rowU['name'] . '> در مرکز <' . $rowC['name'] . '> در شهرستان <' . $rowT['name'] . '> در استان <' . $rowS['name'] . '>';
						break;
					case 'Hygiene Unit Author' :
						$resH = Database::execute_query("SELECT `name`, `center-id` FROM `hygiene-units` WHERE `id` = '" . $row['access-level-id'] . "';");
						$rowH = Database::get_assoc_array($resH);
						$resC = Database::execute_query("SELECT `name`, `town-id` FROM `centers` WHERE `id` = '" . $rowH['center-id'] . "';");
						$rowC = Database::get_assoc_array($resC);
						$resT = Database::execute_query("SELECT `name`, `state-id` FROM `towns` WHERE `id` = '" . $rowC['town-id'] . "';");
						$rowT = Database::get_assoc_array($resT);
						$resS = Database::execute_query("SELECT `name` FROM `states` WHERE `id` = '" . $rowT['state-id'] . "';");
						$rowS = Database::get_assoc_array($resS);
						$acl = 'نویسنده خانه‌بهداشت <' . $rowH['name'] . '> در مرکز <' . $rowC['name'] . '> در شهرستان <' . $rowT['name'] . '> در استان <' . $rowS['name'] . '>';
						break;
					case 'Multi Author' :
						$acl = 'نویسنده چندمنظوره';
						break;
				}
				$htmlOut .= '<td>' . $acl . '</td>';
				$htmlOut .= '<td>';
				$htmlOut .= '<input type="radio" name="userId" id="userId_' . $row['id'] . '" value="' . $row['id'] . '" />';
				$htmlOut .= '</td></tr>';
				$counter++;
			}
			$htmlOut .= '</tbody></table></fieldset><br />';
			$htmlOut .= '<br /><input type="submit" value="مشاهده" name="viewLog" /></form>';
		}

		return $htmlOut;
	}

}//END CLASS ADMINISTRATOR
?>