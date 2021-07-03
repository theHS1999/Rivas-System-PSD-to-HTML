<?php
/** * * form-proccessor.php * form proccessor user file * * @copyright Copyright (C) 2012 Rivas Systems Inc. All rights reserved. * @versin 1.00 2011-02-05 * @author Behnam Salili * */
require_once 'Narin.class.php';
require_once dirname(dirname(__file__)) . '/database/database.php';
require_once dirname(dirname(__file__)) . '/illustrator/pdate.php';
date_default_timezone_set('Asia/Tehran');

/** * This class proccesses the whole program submitted forms */
class FormProccessor
{
    public static function proccessAddTownForm()
    {
        if ($_SERVER['REQUEST_METHOD'] != 'POST') return isset($_GET['success']) ? array('success' => true) : false;
        if (!Narin::check_token()) return array('err_msg' => 'به دلیل وجود یک مشکل امنیتی، اطلاعات ثبت نشد. لطفاً دوباره تلاش کنید.');
        if (!(isset($_POST['input_town_name']) && Narin::validate_required('input_town_name') && strlen($_POST['input_town_name']) <= 256)) $invalid['input_town_name'] = true;
        if (isset($invalid)) return array('invalid' => $invalid, 'err_msg' => 'اطلاعات دریافت شده معتبر نیست.');
        $row = null;
        try {
            Database::execute_query("INSERT INTO `towns` (`name`, `state_id`) VALUES ('" . Database::filter_str($_POST['input_town_name']) . "', 1);");
        } catch (exception $e) {
            return array('err_msg' => $e->getMessage());
        }
        $_logTXT = 'افزودن شهرستان "' . Database::filter_str($_POST['input_town_name']) . '"';
        self::_makeLog($_logTXT);
        header('Location: ' . substr(strrchr($_SERVER['PHP_SELF'], '/'), 1) . '?success');
        exit;
    }

    public static function proccessEditTownForm()
    {
        if ($_SERVER['REQUEST_METHOD'] != 'POST') return isset($_GET['success']) ? array('success' => true) : false;
        if (!Narin::check_token()) return array('err_msg' => 'به دلیل وجود یک مشکل امنیتی، اطلاعات ثبت نشد. لطفاً دوباره تلاش کنید.');
        if (!(isset($_POST['input_town_name']) && Narin::validate_required('input_town_name') && strlen($_POST['input_town_name']) <= 256)) $invalid['input_town_name'] = true;
        if (isset($invalid)) return array('invalid' => $invalid, 'err_msg' => 'اطلاعات دریافت شده معتبر نیست.');
        try {
            Database::execute_query("UPDATE `towns` SET `name` = '" . Database::filter_str($_POST['input_town_name']) . "' WHERE `id` = '" . Database::filter_str($_POST['old_town']) . "';");
        } catch (exception $e) {
            return array('err_msg' => $e->getMessage());
        }
        $_logTXT = 'اصلاح شهرستان "' . Database::filter_str($_POST['input_town_name']) . '"';
        self::_makeLog($_logTXT);
        if (strpos($_SERVER['HTTP_REFERER'], 'ref')) {
            header('Location: ' . substr(strrchr($_SERVER['PHP_SELF'], '/'), 1) . '?es=manageTowns');
            exit;
        } else {
            header('Location: ' . substr(strrchr($_SERVER['PHP_SELF'], '/'), 1) . '?cmd=manageTowns');
            exit;
        }
    }

    public static function proccessAddCenterForm()
    {
        if ($_SERVER['REQUEST_METHOD'] != 'POST') return isset($_GET['success']) ? array('success' => true) : false;
        if (!Narin::check_token()) return array('err_msg' => 'به دلیل وجود یک مشکل امنیتی، اطلاعات ثبت نشد. لطفاً دوباره تلاش کنید.');
        if (!(isset($_POST['input_center_name']) && Narin::validate_required('input_center_name') && strlen($_POST['input_center_name']) <= 256)) $invalid['input_center_name'] = true;
        if (!(isset($_POST['input_center_phone']) && Narin::validate_required('input_center_phone') && Narin::validate_tel('input_center_phone'))) $invalid['input_center_phone'] = true;
        if (!(isset($_POST['input_center_fax']) && Narin::validate_required('input_center_fax') && Narin::validate_tel('input_center_fax'))) $invalid['input_center_fax'] = true;
        if (!(isset($_POST['input_center_address']) && Narin::validate_required('input_center_address'))) $invalid['input_center_address'] = true;
        if (isset($invalid)) return array('invalid' => $invalid, 'err_msg' => 'اطلاعات دریافت شده معتبر نیست.');
        try {
            Database::execute_query("INSERT INTO `centers` (`name`, `phone`, `fax`, `address`, `town-id`) VALUES ('" . Database::filter_str($_POST['input_center_name']) . "', '" . Database::filter_str($_POST['input_center_phone']) . "', '" . Database::filter_str($_POST['input_center_fax']) . "', '" . Database::filter_str($_POST['input_center_address']) . "', '" . Database::filter_str($_POST['townId']) . "');");
            self::setInitialDataForCenter(Database::filter_str($_POST['input_center_name']));
        } catch (exception $e) {
            return array('err_msg' => $e->getMessage());
        }
        $res = Database::execute_query("SELECT `name` FROM `towns` WHERE `id` = '" . Database::filter_str($_POST['townId']) . "';");
        $row = Database::get_assoc_array($res);
        $_logTXT = 'افزودن مرکز "' . Database::filter_str($_POST['input_center_name']) . '" به شهرستان "' . $row['name'] . '"';
        self::_makeLog($_logTXT);
        header('Location: ' . substr(strrchr($_SERVER['PHP_SELF'], '/'), 1) . '?success');
        exit;
    }

    public static function proccessEditCenterForm()
    {
        if ($_SERVER['REQUEST_METHOD'] != 'POST') return isset($_GET['success']) ? array('success' => true) : false;
        if (!Narin::check_token()) return array('err_msg' => 'به دلیل وجود یک مشکل امنیتی، اطلاعات ثبت نشد. لطفاً دوباره تلاش کنید.');
        if (!(isset($_POST['input_center_name']) && Narin::validate_required('input_center_name') && strlen($_POST['input_center_name']) <= 256)) $invalid['input_center_name'] = true;
        if (!(isset($_POST['input_center_phone']) && Narin::validate_required('input_center_phone') && Narin::validate_tel('input_center_phone'))) $invalid['input_center_phone'] = true;
        if (!(isset($_POST['input_center_fax']) && Narin::validate_required('input_center_fax') && Narin::validate_tel('input_center_fax'))) $invalid['input_center_fax'] = true;
        if (!(isset($_POST['input_center_address']) && Narin::validate_required('input_center_address'))) $invalid['input_center_address'] = true;
        if (isset($invalid)) return array('invalid' => $invalid, 'err_msg' => 'اطلاعات دریافت شده معتبر نیست.');
        try {
            Database::execute_query("UPDATE `centers` SET `name` = '" . Database::filter_str($_POST['input_center_name']) . "', `address` = '" . Database::filter_str($_POST['input_center_address']) . "', `phone` = '" . Database::filter_str($_POST['input_center_phone']) . "', `fax` = '" . Database::filter_str($_POST['input_center_fax']) . "' WHERE `id` = '" . Database::filter_str($_POST['centerId']) . "';");
        } catch (exception $e) {
            return array('err_msg' => $e->getMessage());
        }
        $resC = Database::execute_query("SELECT `town-id` FROM `centers` WHERE `id` = '" . Database::filter_str($_POST['centerId']) . "';");
        $rowC = Database::get_assoc_array($resC);
        $res = Database::execute_query("SELECT `name` FROM `towns` WHERE `id` = '" . $rowC['town-id'] . "';");
        $row = Database::get_assoc_array($res);
        $_logTXT = 'اصلاح مرکز "' . Database::filter_str($_POST['input_center_name']) . '" در شهرستان "' . $row['name'] . '"';
        self::_makeLog($_logTXT);
        if (strpos($_SERVER['HTTP_REFERER'], 'ref')) {
            header('Location: ' . substr(strrchr($_SERVER['PHP_SELF'], '/'), 1) . '?es=manageCenters');
            exit;
        } else {
            header('Location: ' . substr(strrchr($_SERVER['PHP_SELF'], '/'), 1) . '?cmd=manageCenters&townId=' . $_POST['townId']);
            exit;
        }
    }

    public static function proccessAddUnitForm()
    {
        if ($_SERVER['REQUEST_METHOD'] != 'POST') return isset($_GET['success']) ? array('success' => true) : false;
        if (!Narin::check_token()) return array('err_msg' => 'به دلیل وجود یک مشکل امنیتی، اطلاعات ثبت نشد. لطفاً دوباره تلاش کنید.');
        if (!(isset($_POST['input_unit_type']) && Narin::validate_required('input_unit_type'))) $invalid['input_unit_type'] = true;
        if (!(isset($_POST['input_unit_name']) && Narin::validate_required('input_unit_name') && strlen($_POST['input_unit_name']) <= 256)) $invalid['input_unit_name'] = true;
        if (isset($invalid)) return array('invalid' => $invalid, 'err_msg' => 'اطلاعات دریافت شده معتبر نیست.');
        try {
            Database::execute_query("INSERT INTO `units` (`unit_type`, `name`, `center-id`) VALUES ('" . Database::filter_str($_POST['input_unit_type']) . "', '" . Database::filter_str($_POST['input_unit_name']) . "', '" . Database::filter_str($_POST['centerId']) . "');");
        } catch (exception $e) {
            return array('err_msg' => $e->getMessage());
        }
        $resC = Database::execute_query("SELECT `name`, `town-id` FROM `centers` WHERE `id` = '" . Database::filter_str($_POST['centerId']) . "';");
        $rowC = Database::get_assoc_array($resC);
        $res = Database::execute_query("SELECT `name` FROM `towns` WHERE `id` = '" . $rowC['town-id'] . "';");
        $row = Database::get_assoc_array($res);
        $_logTXT = 'افزودن واحد "' . Database::filter_str($_POST['input_unit_name']) . '" به مرکز "' . $rowC['name'] . '" در شهرستان "' . $row['name'] . '"';
        self::_makeLog($_logTXT);
        header('Location: ' . substr(strrchr($_SERVER['PHP_SELF'], '/'), 1) . '?success');
        exit;
    }

    public static function proccessEditUnitForm()
    {
        if ($_SERVER['REQUEST_METHOD'] != 'POST') return isset($_GET['success']) ? array('success' => true) : false;
        if (!Narin::check_token()) return array('err_msg' => 'به دلیل وجود یک مشکل امنیتی، اطلاعات ثبت نشد. لطفاً دوباره تلاش کنید.');
        if (!(isset($_POST['input_unit_type']) && Narin::validate_required('input_unit_type'))) $invalid['input_unit_type'] = true;
        if (!(isset($_POST['input_unit_name']) && Narin::validate_required('input_unit_name') && strlen($_POST['input_unit_name']) <= 256)) $invalid['input_unit_name'] = true;
        if (isset($invalid)) return array('invalid' => $invalid, 'err_msg' => 'اطلاعات دریافت شده معتبر نیست.');
        try {
            Database::execute_query("UPDATE `units` SET `unit_type` = '" . Database::filter_str($_POST['input_unit_type']) . "', `name` = '" . Database::filter_str($_POST['input_unit_name']) . "' WHERE `id` = '" . Database::filter_str($_POST['unit_old']) . "';");
        } catch (exception $e) {
            return array('err_msg' => $e->getMessage());
        }
        $resU = Database::execute_query("SELECT `center-id` FROM `units` WHERE `id` = '" . Database::filter_str($_POST['unit_old']) . "';");
        $rowU = Database::get_assoc_array($resU);
        $resC = Database::execute_query("SELECT `name`, `town-id` FROM `centers` WHERE `id` = '" . $rowU['center-id'] . "';");
        $rowC = Database::get_assoc_array($resC);
        $res = Database::execute_query("SELECT `name` FROM `towns` WHERE `id` = '" . $rowC['town-id'] . "';");
        $row = Database::get_assoc_array($res);
        $_logTXT = 'اصلاح واحد "' . Database::filter_str($_POST['input_unit_name']) . '" در مرکز "' . $rowC['name'] . '" در شهرستان "' . $row['name'] . '"';
        self::_makeLog($_logTXT);
        if (strpos($_SERVER['HTTP_REFERER'], 'ref')) {
            header('Location: ' . substr(strrchr($_SERVER['PHP_SELF'], '/'), 1) . '?es=manageUnits');
            exit;
        } else {
            header('Location: ' . substr(strrchr($_SERVER['PHP_SELF'], '/'), 1) . '?cmd=manageUnits¢erId=' . $_POST['centerId']);
            exit;
        }
    }

    public static function proccessAddHygieneUnitForm()
    {
        if ($_SERVER['REQUEST_METHOD'] != 'POST') return isset($_GET['success']) ? array('success' => true) : false;
        if (!Narin::check_token()) return array('err_msg' => 'به دلیل وجود یک مشکل امنیتی، اطلاعات ثبت نشد. لطفاً دوباره تلاش کنید.');
        if (!(isset($_POST['input_hygiene_name']) && Narin::validate_required('input_hygiene_name') && strlen($_POST['input_hygiene_name']) <= 255)) $invalid['input_hygiene_name'] = true;
        if (!(isset($_POST['input_hygiene_phone']) && Narin::validate_required('input_hygiene_phone') && Narin::validate_tel('input_hygiene_phone'))) $invalid['input_hygiene_phone'] = true;
        if (!(isset($_POST['input_hygiene_fax']) && Narin::validate_required('input_hygiene_fax') && Narin::validate_tel('input_hygiene_fax'))) $invalid['input_hygiene_fax'] = true;
        if (!(isset($_POST['input_hygiene_address']) && Narin::validate_required('input_hygiene_address'))) $invalid['input_hygiene_address'] = true;
        if (isset($invalid)) return array('invalid' => $invalid, 'err_msg' => 'اطلاعات دریافت شده معتبر نیست.');
        try {
            Database::execute_query("INSERT INTO `hygiene-units` (`name`, `phone`, `fax`, `address`, `center-id`) VALUES ('" . Database::filter_str($_POST['input_hygiene_name']) . "', '" . Database::filter_str($_POST['input_hygiene_phone']) . "', '" . Database::filter_str($_POST['input_hygiene_fax']) . "', '" . Database::filter_str($_POST['input_hygiene_address']) . "', '" . Database::filter_str($_POST['centerId']) . "');");
            self::setInitialDataForHygieneUnit(Database::filter_str($_POST['input_hygiene_name']));
        } catch (exception $e) {
            return array('err_msg' => $e->getMessage());
        }
        $resC = Database::execute_query("SELECT `name`, `town-id` FROM `centers` WHERE `id` = '" . Database::filter_str($_POST['centerId']) . "';");
        $rowC = Database::get_assoc_array($resC);
        $res = Database::execute_query("SELECT `name` FROM `towns` WHERE `id` = '" . $rowC['town-id'] . "';");
        $row = Database::get_assoc_array($res);
        $_logTXT = 'افزودن خانه‌بهداشت "' . Database::filter_str($_POST['input_hygiene_name']) . '" در مرکز "' . $rowC['name'] . '" در شهرستان "' . $row['name'] . '"';
        self::_makeLog($_logTXT);
        header('Location: ' . substr(strrchr($_SERVER['PHP_SELF'], '/'), 1) . '?success');
        exit;
    }

    public static function proccessEditHygieneUnitForm()
    {
        if ($_SERVER['REQUEST_METHOD'] != 'POST') return isset($_GET['success']) ? array('success' => true) : false;
        if (!Narin::check_token()) return array('err_msg' => 'به دلیل وجود یک مشکل امنیتی، اطلاعات ثبت نشد. لطفاً دوباره تلاش کنید.');
        if (!(isset($_POST['input_hygiene_name']) && Narin::validate_required('input_hygiene_name') && strlen($_POST['input_hygiene_name']) <= 255)) $invalid['input_hygiene_name'] = true;
        if (!(isset($_POST['input_hygiene_phone']) && Narin::validate_required('input_hygiene_phone') && Narin::validate_tel('input_hygiene_phone'))) $invalid['input_hygiene_phone'] = true;
        if (!(isset($_POST['input_hygiene_fax']) && Narin::validate_required('input_hygiene_fax') && Narin::validate_tel('input_hygiene_fax'))) $invalid['input_hygiene_fax'] = true;
        if (!(isset($_POST['input_hygiene_address']) && Narin::validate_required('input_hygiene_address'))) $invalid['input_hygiene_address'] = true;
        if (isset($invalid)) return array('invalid' => $invalid, 'err_msg' => 'اطلاعات دریافت شده معتبر نیست.');
        try {
            Database::execute_query("UPDATE `hygiene-units` SET `name` = '" . Database::filter_str($_POST['input_hygiene_name']) . "', `address` = '" . Database::filter_str($_POST['input_hygiene_address']) . "', `phone` = '" . Database::filter_str($_POST['input_hygiene_phone']) . "', `fax` = '" . Database::filter_str($_POST['input_hygiene_fax']) . "' WHERE `id` = '" . Database::filter_str($_POST['hygiene_old_id']) . "';");
        } catch (exception $e) {
            return array('err_msg' => $e->getMessage());
        }
        $resU = Database::execute_query("SELECT `center-id` FROM `hygiene-units` WHERE `id` = '" . Database::filter_str($_POST['hygiene_old_id']) . "';");
        $rowU = Database::get_assoc_array($resU);
        $resC = Database::execute_query("SELECT `name`, `town-id` FROM `centers` WHERE `id` = '" . $rowU['center-id'] . "';");
        $rowC = Database::get_assoc_array($resC);
        $res = Database::execute_query("SELECT `name` FROM `towns` WHERE `id` = '" . $rowC['town-id'] . "';");
        $row = Database::get_assoc_array($res);
        $_logTXT = 'اصلاح خانه‌بهداشت "' . Database::filter_str($_POST['input_hygiene_name']) . '" در مرکز "' . $rowC['name'] . '" در شهرستان "' . $row['name'] . '"';
        self::_makeLog($_logTXT);
        if (strpos($_SERVER['HTTP_REFERER'], 'ref')) {
            header('Location: ' . substr(strrchr($_SERVER['PHP_SELF'], '/'), 1) . '?es=manageHygieneUnits');
            exit;
        } else {
            header('Location: ' . substr(strrchr($_SERVER['PHP_SELF'], '/'), 1) . '?cmd=manageUnits¢erId=' . $_POST['centerId']);
            exit;
        }
    }

    public static function proccessAddCenterDrugsAndConsumingEquipmentsForm()
    {
        if ($_SERVER['REQUEST_METHOD'] != 'POST') return isset($_GET['success']) ? array('success' => true) : false;
        if (!Narin::check_token()) return array('err_msg' => 'به دلیل وجود یک مشکل امنیتی، اطلاعات ثبت نشد. لطفاً دوباره تلاش کنید.');
        if (!(isset($_POST['input_drug_name']))) $invalid['input_drug_name'] = true;
        if (!(isset($_POST['input_drug_num']) && Narin::validate_required('input_drug_num') && Narin::validate_integer('input_drug_num', 0, false, null, true) && strlen($_POST['input_drug_num']) <= 6)) $invalid['input_drug_num'] = true;
        if (!(isset($_POST['input_drug_cm']))) $invalid['input_drug_cm'] = true;
        if (isset($invalid)) return array('invalid' => $invalid, 'err_msg' => 'اطلاعات دریافت شده معتبر نیست.');
        $dateTimeObj = new DateTime();
        $dateTimeZone = new DateTimeZone('Asia/Tehran');
        $dateTimeObj->setTimezone($dateTimeZone);
        $shamsiDate = gregorian_to_jalali($dateTimeObj->format('Y'), $dateTimeObj->format('m'), $dateTimeObj->format('d'));
        $date = $shamsiDate[0] . "-" . $shamsiDate[1] . "-" . $shamsiDate[2];
        try {
            $res = Database::execute_query("SELECT MAX(`id`) FROM `available-drugs-cost` WHERE `drug-id` = '" . Database::filter_str($_POST['input_drug_name']) . "'");
            $row = Database::get_assoc_array($res);
            Database::execute_query("INSERT INTO `center-drugs` (`id`, `drug-cost-id`, `num-of-drugs`, `center-id`, `comment`, `date`) VALUES (NULL, '" . $row['MAX(`id`)'] . "', '" . Database::filter_str($_POST['input_drug_num']) . "', '" . Database::filter_str($_SESSION['centerId']) . "', '" . Database::filter_str($_POST['input_drug_cm']) . "', '$date');");
            unset($dateTimeObj, $dateTimeZone, $date, $shamsiDate);
        } catch (exception $e) {
            return array('err_msg' => $e->getMessage());
        }
        try {
            $resU = Database::execute_query("SELECT `name` FROM `available-drugs-and-consuming-equipments` WHERE `id` = '" . Database::filter_str($_POST['input_drug_name']) . "';");
            $rowU = Database::get_assoc_array($resU);
            $resC = Database::execute_query("SELECT `name`, `town-id` FROM `centers` WHERE `id` = '" . Database::filter_str($_SESSION['centerId']) . "';");
            $rowC = Database::get_assoc_array($resC);
            $res = Database::execute_query("SELECT `name` FROM `towns` WHERE `id` = '" . $rowC['town-id'] . "';");
            $row = Database::get_assoc_array($res);
        } catch (exception $e) {
            return array('err_msg' => $e->getMessage());
        }
        $_logTXT = 'افزودن "' . $rowU['name'] . '" به تعداد "' . Database::filter_str($_POST['input_drug_num']) . '" به مرکز "' . $rowC['name'] . '" در شهرستان "' . $row['name'] . '"';
        self::_makeLog($_logTXT);
        header('Location: ' . substr(strrchr($_SERVER['PHP_SELF'], '/'), 1) . '?success');
        exit;
    }

    public static function proccess_eash_AddCenterDrugsAndConsumingEquipmentsForm()
    {
        if ($_SERVER['REQUEST_METHOD'] != 'POST') return isset($_GET['success']) ? array('success' => true) : false;
        if (!Narin::check_token()) return array('err_msg' => 'به دلیل وجود یک مشکل امنیتی، اطلاعات ثبت نشد. لطفاً دوباره تلاش کنید.');
        if (!(isset($_POST['input_drug_name']))) $invalid['input_drug_name'] = true;
        if (!(isset($_POST['input_drug_num']) && Narin::validate_required('input_drug_num') && Narin::validate_integer('input_drug_num', 0, false, null, true) && strlen($_POST['input_drug_num']) <= 6)) $invalid['input_drug_num'] = true;
        if (!(isset($_POST['input_drug_cm']))) $invalid['input_drug_cm'] = true;
        if (isset($invalid)) return array('invalid' => $invalid, 'err_msg' => 'اطلاعات دریافت شده معتبر نیست.');
        $dateTimeObj = new DateTime();
        $dateTimeZone = new DateTimeZone('Asia/Tehran');
        $dateTimeObj->setTimezone($dateTimeZone);
        $shamsiDate = gregorian_to_jalali($dateTimeObj->format('Y'), $dateTimeObj->format('m'), $dateTimeObj->format('d'));
        $date = $shamsiDate[0] . "-" . $shamsiDate[1] . "-" . $shamsiDate[2];
        try {
            $res = Database::execute_query("SELECT MAX(`id`) FROM `available-drugs-cost` WHERE `drug-id` = '" . Database::filter_str($_POST['input_drug_name']) . "'");
            $row = Database::get_assoc_array($res);
            Database::execute_query("INSERT INTO `center-drugs` (`id`, `drug-cost-id`, `num-of-drugs`, `center-id`, `comment`, `date`) VALUES (NULL, '" . $row['MAX(`id`)'] . "', '" . Database::filter_str($_POST['input_drug_num']) . "', '" . Database::filter_str($_POST['centerId']) . "', '" . Database::filter_str($_POST['input_drug_cm']) . "', '$date');");
            unset($dateTimeObj, $dateTimeZone, $date, $shamsiDate);
        } catch (exception $e) {
            return array('err_msg' => $e->getMessage());
        }
        $resU = Database::execute_query("SELECT `name` FROM `available-drugs-and-consuming-equipments` WHERE `id` = '" . Database::filter_str($_POST['input_drug_name']) . "';");
        $rowU = Database::get_assoc_array($resU);
        $resC = Database::execute_query("SELECT `name`, `town-id` FROM `centers` WHERE `id` = '" . Database::filter_str($_POST['centerId']) . "';");
        $rowC = Database::get_assoc_array($resC);
        $res = Database::execute_query("SELECT `name` FROM `towns` WHERE `id` = '" . $rowC['town-id'] . "';");
        $row = Database::get_assoc_array($res);
        $_logTXT = 'افزودن "' . $rowU['name'] . '" به تعداد "' . Database::filter_str($_POST['input_drug_num']) . '" به مرکز "' . $rowC['name'] . '" در شهرستان "' . $row['name'] . '"';
        self::_makeLog($_logTXT);
        header('Location: ' . substr(strrchr($_SERVER['PHP_SELF'], '/'), 1) . '?success');
        exit;
    }

    public static function proccessAddAvailableNonConsumingEquipmentsForm()
    {
        if ($_SERVER['REQUEST_METHOD'] != 'POST') return isset($_GET['success']) ? array('success' => true) : false;
        if (!Narin::check_token()) return array('err_msg' => 'به دلیل وجود یک مشکل امنیتی، اطلاعات ثبت نشد. لطفاً دوباره تلاش کنید.');
        if (!(isset($_POST['input_name']) && Narin::validate_required('input_name') && strlen($_POST['input_name']) <= 255)) $invalid['input_name'] = true;
        if (!(isset($_POST['input_price']) && Narin::validate_required('input_price') && Narin::validate_integer('input_price', 0, false, null, true))) $invalid['input_price'] = true;
        if (isset($invalid)) return array('invalid' => $invalid, 'err_msg' => 'اطلاعات دریافت شده معتبر نیست.');
        $dateTimeObj = new DateTime();
        $dateTimeZone = new DateTimeZone('Asia/Tehran');
        $dateTimeObj->setTimezone($dateTimeZone);
        $shamsiDate = gregorian_to_jalali($dateTimeObj->format('Y'), $dateTimeObj->format('m'), $dateTimeObj->format('d'));
        $date = $shamsiDate[0] . "-" . $shamsiDate[1] . "-" . $shamsiDate[2];
        try {
            Database::execute_query("INSERT INTO `available-non-consuming-equipments` (`id`, `name`, `type`, `mark`) VALUES ('NULL', '" . Database::filter_str($_POST['input_name']) . "', '" . Database::filter_str($_POST['input_type']) . "', '" . Database::filter_str($_POST['input_mark']) . "');");
            $res = Database::execute_query("SELECT LAST_INSERT_ID();");
            $row = Database::get_assoc_array($res);
            Database::execute_query("INSERT INTO `available-non-consuming-cost` (`id`, `equip-id`, `cost`, `date-of-register`) VALUES (NULL, '" . $row['LAST_INSERT_ID()'] . "', '" . Database::filter_str($_POST['input_price']) . "', '$date');");
            unset($dateTimeObj, $dateTimeZone, $date, $shamsiDate);
        } catch (exception $e) {
            return array('err_msg' => $e->getMessage());
        }
        $_logTXT = 'تعریف "' . $_POST['input_name'] . '(' . Database::filter_str($_POST['input_mark']) . ')" به عنوان تجهیزات غیرمصرفی';
        self::_makeLog($_logTXT);
        header('Location: ' . substr(strrchr($_SERVER['PHP_SELF'], '/'), 1) . '?success');
        exit;
    }

    public static function proccessEditAvailableNonConsumingEquipmentsForm()
    {
        if ($_SERVER['REQUEST_METHOD'] != 'POST') return isset($_GET['success']) ? array('success' => true) : false;
        if (!Narin::check_token()) return array('err_msg' => 'به دلیل وجود یک مشکل امنیتی، اطلاعات ثبت نشد. لطفاً دوباره تلاش کنید.');
        if (!(isset($_POST['input_name']) && Narin::validate_required('input_name') && strlen($_POST['input_name']) <= 255)) $invalid['input_name'] = true;
        if (!(isset($_POST['input_price']) && Narin::validate_required('input_price') && Narin::validate_integer('input_price', 0, false, null, true))) $invalid['input_price'] = true;
        if (isset($invalid)) return array('invalid' => $invalid, 'err_msg' => 'اطلاعات دریافت شده معتبر نیست.');
        if (!$_POST['editType']) {
            $res = Database::execute_query("SELECT MAX(`id`) FROM `available-non-consuming-cost` WHERE `equip-id` = '" . $_POST['old_non_consuming'] . "';");
            $row = Database::get_assoc_array($res);
            Database::execute_query("UPDATE `available-non-consuming-cost` SET `cost` = '" . Database::filter_str($_POST['input_price']) . "' WHERE `id` = '" . $row['MAX(`id`)'] . "';");
            var_dump($row);
            die("here");
        } else {
            $dateTimeObj = new DateTime();
            $dateTimeZone = new DateTimeZone('Asia/Tehran');
            $dateTimeObj->setTimezone($dateTimeZone);
            $shamsiDate = gregorian_to_jalali($dateTimeObj->format('Y'), $dateTimeObj->format('m'), $dateTimeObj->format('d'));
            $date = $shamsiDate[0] . "-" . $shamsiDate[1] . "-" . $shamsiDate[2];
            try {
                Database::execute_query("UPDATE `available-non-consuming-equipments` SET `name` = '" . Database::filter_str($_POST['input_name']) . "', `type` = '" . Database::filter_str($_POST['input_type']) . "', `mark` = '" . Database::filter_str($_POST['input_mark']) . "' WHERE `id` = '" . Database::filter_str($_POST['old_non_consuming']) . "';");
                Database::execute_query("INSERT INTO `available-non-consuming-cost` (`id`, `equip-id`, `cost`, `date-of-register`) VALUES (NULL, '" . $_POST['old_non_consuming'] . "', '" . Database::filter_str($_POST['input_price']) . "', '$date');");
            } catch (exception $e) {
                return array('err_msg' => $e->getMessage());
            }
            unset($dateTimeObj, $dateTimeZone, $date, $shamsiDate);
        }
        $_logTXT = 'اصلاح تعریف "' . $_POST['input_name'] . '(' . Database::filter_str($_POST['input_mark']) . ')" در تجهیزات غیرمصرفی';
        self::_makeLog($_logTXT);
        header('Location: ' . substr(strrchr($_SERVER['PHP_SELF'], '/'), 1) . '?cmd=manageAvailableNonConsumingEquipments');
        exit;
    }

    public static function proccessAddCenterNonConsumingEquipmentsForm()
    {
        if ($_SERVER['REQUEST_METHOD'] != 'POST') return isset($_GET['success']) ? array('success' => true) : false;
        if (!Narin::check_token()) return array('err_msg' => 'به دلیل وجود یک مشکل امنیتی، اطلاعات ثبت نشد. لطفاً دوباره تلاش کنید.');
        if (!(isset($_POST['input_equip_id']))) $invalid['input_equip_id'] = true;
        if (!(isset($_POST['input_equip_nums']) && Narin::validate_required('input_equip_nums') && Narin::validate_integer('input_equip_nums', 0, false, null, true) && strlen($_POST['input_equip_nums']) <= 6)) $invalid['input_equip_nums'] = true;
        if (!(isset($_POST['input_equip_cm']))) $invalid['input_equip_cm'] = true;
        if (isset($invalid)) return array('invalid' => $invalid, 'err_msg' => 'اطلاعات دریافت شده معتبر نیست.');
        $date = $_POST['input_buy_date:y'] . '-' . $_POST['input_buy_date:m'] . '-' . $_POST['input_buy_date:d'];
        try {
            $res = Database::execute_query("SELECT MAX(`id`) FROM `available-non-consuming-cost` WHERE `equip-id` = '" . Database::filter_str($_POST['input_equip_id']) . "'");
            $row = Database::get_assoc_array($res);
            Database::execute_query("INSERT INTO `center-non-consuming-equipments` (`id`, `equip-cost-id`, `num-of-equips`, `center-id`, `comment`, `date`) VALUES (NULL, '" . $row['MAX(`id`)'] . "', '" . Database::filter_str($_POST['input_equip_nums']) . "', '" . Database::filter_str($_SESSION['centerId']) . "', '" . Database::filter_str($_POST['input_equip_cm']) . "', '$date');");
        } catch (exception $e) {
            return array('err_msg' => $e->getMessage());
        }
        $resU = Database::execute_query("SELECT `name`, `mark` FROM `available-non-consuming-equipments` WHERE `id` = '" . Database::filter_str($_POST['input_equip_id']) . "';");
        $rowU = Database::get_assoc_array($resU);
        $resC = Database::execute_query("SELECT `name`, `town-id` FROM `centers` WHERE `id` = '" . Database::filter_str($_SESSION['centerId']) . "';");
        $rowC = Database::get_assoc_array($resC);
        $res = Database::execute_query("SELECT `name` FROM `towns` WHERE `id` = '" . $rowC['town-id'] . "';");
        $row = Database::get_assoc_array($res);
        $_logTXT = 'افزودن "' . $rowU['name'] . '(' . $rowU['mark'] . ')" به تعداد "' . Database::filter_str($_POST['input_equip_nums']) . '" به مرکز "' . $rowC['name'] . '" در شهرستان "' . $row['name'] . '"';
        self::_makeLog($_logTXT);
        header('Location: ' . substr(strrchr($_SERVER['PHP_SELF'], '/'), 1) . '?success');
        exit;
    }

    public static function proccess_eash_AddCenterNonConsumingEquipmentsForm()
    {
        if ($_SERVER['REQUEST_METHOD'] != 'POST') return isset($_GET['success']) ? array('success' => true) : false;
        if (!Narin::check_token()) return array('err_msg' => 'به دلیل وجود یک مشکل امنیتی، اطلاعات ثبت نشد. لطفاً دوباره تلاش کنید.');
        if (!(isset($_POST['input_equip_id']))) $invalid['input_equip_id'] = true;
        if (!(isset($_POST['input_equip_nums']) && Narin::validate_required('input_equip_nums') && Narin::validate_integer('input_equip_nums', 0, false, null, true) && strlen($_POST['input_equip_nums']) <= 6)) $invalid['input_equip_nums'] = true;
        if (!(isset($_POST['input_equip_cm']))) $invalid['input_equip_cm'] = true;
        if (isset($invalid)) return array('invalid' => $invalid, 'err_msg' => 'اطلاعات دریافت شده معتبر نیست.');
        $date = $_POST['input_buy_date:y'] . '-' . $_POST['input_buy_date:m'] . '-' . $_POST['input_buy_date:d'];
        try {
            $res = Database::execute_query("SELECT MAX(`id`) FROM `available-non-consuming-cost` WHERE `equip-id` = '" . Database::filter_str($_POST['input_equip_id']) . "'");
            $row = Database::get_assoc_array($res);
            Database::execute_query("INSERT INTO `center-non-consuming-equipments` (`id`, `equip-cost-id`, `num-of-equips`, `center-id`, `comment`, `date`) VALUES (NULL, '" . $row['MAX(`id`)'] . "', '" . Database::filter_str($_POST['input_equip_nums']) . "', '" . Database::filter_str($_POST['centerId']) . "', '" . Database::filter_str($_POST['input_equip_cm']) . "', '$date');");
        } catch (exception $e) {
            return array('err_msg' => $e->getMessage());
        }
        $resU = Database::execute_query("SELECT `name`, `mark` FROM `available-non-consuming-equipments` WHERE `id` = '" . Database::filter_str($_POST['input_equip_id']) . "';");
        $rowU = Database::get_assoc_array($resU);
        $resC = Database::execute_query("SELECT `name`, `town-id` FROM `centers` WHERE `id` = '" . Database::filter_str($_POST['centerId']) . "';");
        $rowC = Database::get_assoc_array($resC);
        $res = Database::execute_query("SELECT `name` FROM `towns` WHERE `id` = '" . $rowC['town-id'] . "';");
        $row = Database::get_assoc_array($res);
        $_logTXT = 'افزودن "' . $rowU['name'] . '(' . $rowU['mark'] . ')" به تعداد "' . Database::filter_str($_POST['input_equip_nums']) . '" به مرکز "' . $rowC['name'] . '" در شهرستان "' . $row['name'] . '"';
        self::_makeLog($_logTXT);
        header('Location: ' . substr(strrchr($_SERVER['PHP_SELF'], '/'), 1) . '?success');
        exit;
    }

    public static function proccessAddServiceForm()
    {
        if ($_SERVER['REQUEST_METHOD'] != 'POST') return isset($_GET['success']) ? array('success' => true) : false;
        if (!Narin::check_token()) return array('err_msg' => 'به دلیل وجود یک مشکل امنیتی، اطلاعات ثبت نشد. لطفاً دوباره تلاش کنید.');
        if (!(isset($_POST['input_service_type']) && Narin::validate_required('input_service_type'))) $invalid['input_service_type'] = true;
        if (isset($invalid)) return array('invalid' => $invalid, 'err_msg' => 'اطلاعات دریافت شده معتبر نیست.');
        if(isset($_POST['input_service_type']) && Database::filter_str($_POST['input_service_type']) == "0") {
            if (!(isset($_POST['input_service_name0']) && Narin::validate_required('input_service_name0'))) $invalid['input_service_name0'] = true;

            if (!(isset($_POST['input_service_cost0']) && Narin::validate_required('input_service_cost0') && Narin::validate_integer('input_service_cost0', 0, false, NULL, true))) $invalid['input_service_cost0'] = true;
            if (!(isset($_POST['input_average_time0']) && Narin::validate_required('input_average_time0'))) $invalid['input_average_time0'] = true;
            if (isset($invalid)) return array('invalid' => $invalid, 'err_msg' => 'اطلاعات دریافت شده معتبر نیست.');
            try {
                Database::execute_query("INSERT INTO `available-services` (`service_type`, `name`, `cost`, `average-time`) VALUES ('" . Database::filter_str($_POST['input_service_type']) . "', '" . Database::filter_str($_POST['input_service_name0']) . "', '" . Database::filter_str($_POST['input_service_cost0']) . "', '" . Database::filter_str($_POST['input_average_time0']) . "');");
            } catch (exception $e) {
                return array('err_msg' => $e->getMessage());
            }
        } else {
            if (!(isset($_POST['jobTitle1']) && Narin::validate_required('jobTitle1'))) $invalid['jobTitle1'] = true;
            if (!(isset($_POST['period1']) && Narin::validate_required('period1'))) $invalid['period1'] = true;
            if (!(isset($_POST['input_service_name1']) && Narin::validate_required('input_service_name1'))) $invalid['input_service_name1'] = true;
            if (!(isset($_POST['input_service_description1']) && Narin::validate_required('input_service_description1'))) $invalid['input_service_description1'] = true;
            if (!(isset($_POST['input_service_code1']) && Narin::validate_required('input_service_code1') && Narin::validate_integer('input_service_code1', 0, false, NULL, true))) $invalid['input_service_code1'] = true;
            if (!(isset($_POST['input_service_cost1']) && Narin::validate_required('input_service_cost1') && Narin::validate_integer('input_service_cost1', 0, false, NULL, true))) $invalid['input_service_cost1'] = true;
            if (!(isset($_POST['input_average_time1']) && Narin::validate_required('input_average_time1'))) $invalid['input_average_time1'] = true;
            if (isset($invalid)) return array('invalid' => $invalid, 'err_msg' => 'اطلاعات دریافت شده معتبر نیست.');
            try {
                $sib = Database::insertAndReturnInsertedId("INSERT INTO `sib_services` (`job_title`, `period`, `description`) VALUES ('" . Database::filter_str($_POST['jobTitle1']) . "', '" . Database::filter_str($_POST['period1']) . "', '" . Database::filter_str($_POST['input_service_description1']) . "');");
                Database::execute_query("INSERT INTO `available-services` (`service_type`, `name`, `service_code`, `cost`, `average-time`, `sib_service_id`) VALUES ('" . Database::filter_str($_POST['input_service_type']) . "', '" . Database::filter_str($_POST['input_service_name1']) . "', '" . Database::filter_str($_POST['input_service_code1']) . "', '" . Database::filter_str($_POST['input_service_cost1']) . "', '" . Database::filter_str($_POST['input_average_time1']) . "', '" . $sib . "');");
            } catch (exception $e) {
                return array('err_msg' => $e->getMessage());
            }
        }
        $name = isset($_POST['input_service_name0']) ? $_POST['input_service_name0'] : $_POST['input_service_name1'];
        $_logTXT = 'تعریف "' . $name . '" به عنوان خدمت';
        self::_makeLog($_logTXT);
        header('Location: ' . substr(strrchr($_SERVER['PHP_SELF'], '/'), 1) . '?success');
        var_dump("here");
        exit();
        exit;
    }

    public static function proccessBatchAddServiceForm()
    {
        if ($_SERVER['REQUEST_METHOD'] != 'POST') return isset($_GET['success']) ? array('success' => true) : false;
        if (!Narin::check_token()) return array('err_msg' => 'به دلیل وجود یک مشکل امنیتی، اطلاعات ثبت نشد. لطفاً دوباره تلاش کنید.');
        if (!(isset($_POST['input_service_type1']) && Narin::validate_required('input_service_type1'))) $invalid['input_service_type1'] = true;
        if (isset($invalid)) return array('invalid' => $invalid, 'err_msg' => 'اطلاعات دریافت شده معتبر نیست.');
        if(isset($_POST['input_service_type1']) && Database::filter_str($_POST['input_service_type1']) == "0") {
            if (!(isset($_POST['input_service_name2']) && Narin::validate_required('input_service_name2'))) $invalid['input_service_name2'] = true;
            if (!(isset($_POST['input_service_cost2']) && Narin::validate_required('input_service_cost2') && Narin::validate_integer('input_service_cost2', 0, false, NULL, true))) $invalid['input_service_cost2'] = true;
            if (!(isset($_POST['input_average_time2']) && Narin::validate_required('input_average_time2'))) $invalid['input_average_time2'] = true;
            if (isset($invalid)) return array('invalid' => $invalid, 'err_msg' => 'اطلاعات دریافت شده معتبر نیست.');
            try {
                $addedServiceId = Database::insertAndReturnInsertedId("INSERT INTO `available-services` (`service_type`, `name`, `cost`, `average-time`) VALUES ('" . Database::filter_str($_POST['input_service_type1']) . "', '" . Database::filter_str($_POST['input_service_name2']) . "', '" . Database::filter_str($_POST['input_service_cost2']) . "', '" . Database::filter_str($_POST['input_average_time0']) . "');");
                $res = Database::execute_query("SELECT `id` FROM `units` WHERE `name` = '" . $_POST['unitToAddTo'] . "';");
                while ($row = Database::get_assoc_array($res)) Database::execute_query("INSERT INTO `unit-services` (`id`, `service-id`, `unit-id`) VALUES (NULL, '" . $addedServiceId . "', '" . $row['id'] . "');");
            } catch (exception $e) {
                return array('err_msg' => $e->getMessage());
            }
        } elseif(isset($_POST['input_service_type1']) && Database::filter_str($_POST['input_service_type1']) == "1") {
            if (!(isset($_POST['jobTitle3']) && Narin::validate_required('jobTitle3'))) $invalid['jobTitle3'] = true;
            if (!(isset($_POST['period3']) && Narin::validate_required('period3'))) $invalid['period3'] = true;
            if (!(isset($_POST['input_service_name3']) && Narin::validate_required('input_service_name3'))) $invalid['input_service_name3'] = true;
            if (!(isset($_POST['input_service_description3']) && Narin::validate_required('input_service_description3'))) $invalid['input_service_description3'] = true;
            if (!(isset($_POST['input_service_code3']) && Narin::validate_required('input_service_code3') && Narin::validate_integer('input_service_code3', 0, false, NULL, true))) $invalid['input_service_code3'] = true;
            if (!(isset($_POST['input_service_cost3']) && Narin::validate_required('input_service_cost3') && Narin::validate_integer('input_service_cost3', 0, false, NULL, true))) $invalid['input_service_cost3'] = true;
            if (!(isset($_POST['input_average_time3']) && Narin::validate_required('input_average_time3'))) $invalid['input_average_time3'] = true;
            if (isset($invalid)) return array('invalid' => $invalid, 'err_msg' => 'اطلاعات دریافت شده معتبر نیست.');
            try {
                $sib = Database::insertAndReturnInsertedId("INSERT INTO `sib_services` (`job_title`, `period`, `description`) VALUES ('" . Database::filter_str($_POST['jobTitle3']) . "', '" . Database::filter_str($_POST['period3']) . "', '" . Database::filter_str($_POST['input_service_description3']) . "');");
                $addedServiceId = Database::insertAndReturnInsertedId("INSERT INTO `available-services` (`service_type`, `name`, `service_code`, `cost`, `average-time`, `sib_service_id`) VALUES ('" . Database::filter_str($_POST['input_service_type3']) . "', '" . Database::filter_str($_POST['input_service_name3']) . "', '" . Database::filter_str($_POST['input_service_code3']) . "', '" . Database::filter_str($_POST['input_service_cost3']) . "', '" . Database::filter_str($_POST['input_average_time3']) . "', '" . $sib . "');");
                $res = Database::execute_query("SELECT `id` FROM `units` WHERE `name` = '" . $_POST['unitToAddTo'] . "';");
                while ($row = Database::get_assoc_array($res)) Database::execute_query("INSERT INTO `unit-services` (`id`, `service-id`, `unit-id`) VALUES (NULL, '" . $addedServiceId . "', '" . $row['id'] . "');");
            } catch (exception $e) {
                return array('err_msg' => $e->getMessage());
            }
        }
        $name = isset($_POST['input_service_name2']) ? $_POST['input_service_name2'] : $_POST['input_service_name3'];
        $_logTXT = 'تعریف "' . $name . '" به عنوان خدمت و افزودن آن به همه‌ی واحدهای "' . $_POST['unitToAddTo'] . '"';
        self::_makeLog($_logTXT);
        header('Location: ' . substr(strrchr($_SERVER['PHP_SELF'], '/'), 1) . '?success');
        exit;
    }

    public static function processSearchServiceName() {
        if ($_SERVER['REQUEST_METHOD'] != 'POST') return isset($_GET['success']) ? array('success' => true) : false;
        if (!Narin::check_token()) return array('err_msg' => 'به دلیل وجود یک مشکل امنیتی، اطلاعات ثبت نشد. لطفاً دوباره تلاش کنید.');
        if(isset($_POST['input_search_name']) && strlen(Database::filter_str($_POST['input_search_name'])) > 0) {
            header("Location: " . substr(strrchr($_SERVER['PHP_SELF'], '/'), 1) . "?cmd=manageAvailableServices&s=" . Database::filter_str($_POST['input_search_name']));
            exit;
        } else {
            header("Location: " . substr(strrchr($_SERVER['PHP_SELF'], '/'), 1) . "?cmd=manageAvailableServices");
            exit;
        }
    }

    public static function proccessEditServiceForm()
    {
        if ($_SERVER['REQUEST_METHOD'] != 'POST') return isset($_GET['success']) ? array('success' => true) : false;
        if (!Narin::check_token()) return array('err_msg' => 'به دلیل وجود یک مشکل امنیتی، اطلاعات ثبت نشد. لطفاً دوباره تلاش کنید.');
        if (!(isset($_POST['input_service_type']) && Narin::validate_required('input_service_type'))) $invalid['input_service_type'] = true;
        if (isset($invalid)) return array('invalid' => $invalid, 'err_msg' => 'اطلاعات دریافت شده معتبر نیست.');
        if(isset($_POST['input_service_type']) && $_POST['input_service_type'] == "0") {
            if (!(isset($_POST['input_service_name0']) && Narin::validate_required('input_service_name0'))) $invalid['input_service_name0'] = true;
            if (!(isset($_POST['input_average_time0']) && Narin::validate_required('input_average_time0') && Narin::validate_integer('input_average_time0', 0, false, 2000, true) && strlen($_POST['input_average_time0']) <= 4)) $invalid['input_average_time0'] = true;
            if (isset($invalid)) return array('invalid' => $invalid, 'err_msg' => 'اطلاعات دریافت شده معتبر نیست.');
        } elseif(isset($_POST['input_service_type']) && $_POST['input_service_type'] == "1") {
            if (!(isset($_POST['jobTitle1']) && Narin::validate_required('jobTitle1'))) $invalid['jobTitle1'] = true;
            if (!(isset($_POST['period1']) && Narin::validate_required('period1'))) $invalid['period1'] = true;
            if (!(isset($_POST['input_service_name1']) && Narin::validate_required('input_service_name1'))) $invalid['input_service_name1'] = true;
            if (!(isset($_POST['input_service_description1']) && Narin::validate_required('input_service_description1'))) $invalid['input_service_description1'] = true;
            if (!(isset($_POST['input_service_code1']) && Narin::validate_required('input_service_code1') && Narin::validate_integer('input_service_code1', 0, false, NULL, true))) $invalid['input_service_code1'] = true;
            if (!(isset($_POST['input_service_cost1']) && Narin::validate_required('input_service_cost1') && Narin::validate_integer('input_service_cost1', 0, false, 99999999, true) && strlen($_POST['input_service_cost1']) <= 8)) $invalid['input_service_cost1'] = true;
            if (!(isset($_POST['input_average_time1']) && Narin::validate_required('input_average_time1') && Narin::validate_integer('input_average_time1', 0, false, 2000, true) && strlen($_POST['input_average_time1']) <= 4)) $invalid['input_average_time1'] = true;
            if (isset($invalid)) return array('invalid' => $invalid, 'err_msg' => 'اطلاعات دریافت شده معتبر نیست.');
        }

        $dbRes = Database::execute_query("SELECT * FROM `available-services` WHERE `id` = '" . Database::filter_str($_POST['old_service']) . "';");
        while ($row = Database::get_assoc_array($dbRes))
            $tmp = $row;
        if($tmp['sib_service_id'] > 0) {
            $res2 = Database::execute_query("SELECT * FROM `sib_services` WHERE `id` = '" . $tmp['sib_service_id'] . "';");
            while($row = Database::get_assoc_array($res2))
                $sib = $row;
        }




        if($tmp['sib_service_id'] > 0 && $_POST['input_service_type'] == "1") {
            try {
                Database::execute_query("UPDATE `sib_services` SET `job_title` = '" .
                    Database::filter_str($_POST['jobTitle1']) . "', `period` = '" .
                    Database::filter_str($_POST['period1']) . "', `description` = '" .
                    Database::filter_str($_POST['input_service_description1']) . "' WHERE `id` = '" . $sib['id'] . "';");
                Database::execute_query("UPDATE `available-services` SET `service_type` = " .
                    Database::filter_str($_POST['input_service_type']) . ", `name` = '" .
                    Database::filter_str($_POST['input_service_name1']) . "', `service_code` = '" .
                    Database::filter_str($_POST['input_service_code1']) . "', `cost` = '" .
                    Database::filter_str($_POST['input_service_cost1']) . "', `average-time` = '" .
                    Database::filter_str($_POST['input_average_time1']) . "' WHERE `id` = '" .
                    Database::filter_str($_POST['old_service']) . "';");
            } catch (exception $e) {
                return array('err_msg' => $e->getMessage());
            }
        } elseif(!$tmp['sib_service_id'] && $_POST['input_service_type'] == "1") {
            try {
                $sib = Database::insertAndReturnInsertedId("INSERT INTO `sib_services` (`job_title`, `period`, `description`) VALUES ('" . Database::filter_str($_POST['jobTitle1']) . "', '" . Database::filter_str($_POST['period1']) . "', '" . Database::filter_str($_POST['input_service_description1']) . "');");
                Database::execute_query("UPDATE `available-services` SET `service_type` = " .
                    Database::filter_str($_POST['input_service_type']) . ", `name` = '" .
                    Database::filter_str($_POST['input_service_name1']) . "', `service_code` = '" .
                    Database::filter_str($_POST['input_service_code1']) . "', `cost` = '" .
                    Database::filter_str($_POST['input_service_cost1']) . "', `average-time` = '" .
                    Database::filter_str($_POST['input_average_time1']) . "', `sib_service_id` = '" . $sib .
                    "' WHERE `id` = '" . Database::filter_str($_POST['old_service']) . "';");
            } catch (exception $e) {
                return array('err_msg' => $e->getMessage());
            }
        } elseif($tmp['sib_service_id'] && $_POST['input_service_type'] == "0") {
            try {
                Database::execute_query("UPDATE `available-services` SET `service_type` = " .
                    Database::filter_str($_POST['input_service_type']) . ", `name` = '" .
                    Database::filter_str($_POST['input_service_name0']) . "', `service_code` = NULL, `cost` = '" .
                    Database::filter_str($_POST['input_service_cost0']) . "', `average-time` = '" .
                    Database::filter_str($_POST['input_average_time0']) . "', `sib_service_id` = NULL" .
                    " WHERE `id` = '" . Database::filter_str($_POST['old_service']) . "';");
                Database::execute_query("DELETE FROM `sib_services` WHERE `id` = '" . $tmp['sib_service_id'] . "';");
            } catch (exception $e) {
                return array('err_msg' => $e->getMessage());
            }
        } else {
            try {
                Database::execute_query("UPDATE `available-services` SET `service_type` = " .
                    Database::filter_str($_POST['input_service_type']) . ", `name` = '" .
                    Database::filter_str($_POST['input_service_name0']) . "', `service_code` = '" .
                    Database::filter_str($_POST['input_service_code0']) . "', `cost` = '" .
                    Database::filter_str($_POST['input_service_cost0']) . "', `average-time` = '" .
                    Database::filter_str($_POST['input_average_time0']) . "' WHERE `id` = '" .
                    Database::filter_str($_POST['old_service']) . "';");
            } catch (exception $e) {
                return array('err_msg' => $e->getMessage());
            }
        }
        $name = isset($_POST['input_service_name0']) ? $_POST['input_service_name0'] : $_POST['input_service_name1'];
        $_logTXT = 'اصلاح تعریف "' . $name . '" در خدمات';
        self::_makeLog($_logTXT);
        header('Location: ' . substr(strrchr($_SERVER['PHP_SELF'], '/'), 1) . '?cmd=manageAvailableServices');
        exit;
    }

    public static function proccessAddAvailableJobTitlesForm()
    {
        if ($_SERVER['REQUEST_METHOD'] != 'POST') return isset($_GET['success']) ? array('success' => true) : false;
        if (!Narin::check_token()) return array('err_msg' => 'به دلیل وجود یک مشکل امنیتی، اطلاعات ثبت نشد. لطفاً دوباره تلاش کنید.');
        if (!(isset($_POST['input_jobtitle']) && Narin::validate_required('input_jobtitle') && strlen($_POST['input_jobtitle']) <= 255)) $invalid['input_jobtitle'] = true;
        if (isset($invalid)) return array('invalid' => $invalid, 'err_msg' => 'اطلاعات دریافت شده معتبر نیست.');
        try {
            Database::execute_query("INSERT INTO `available-jobtitles` (`title`) VALUES ('" . Database::filter_str($_POST['input_jobtitle']) . "');");
        } catch (exception $e) {
            return array('err_msg' => $e->getMessage());
        }
        $_logTXT = 'تعریف "' . $_POST['input_jobtitle'] . '" به عنوان رده‌پرسنلی';
        self::_makeLog($_logTXT);
        header('Location: ' . substr(strrchr($_SERVER['PHP_SELF'], '/'), 1) . '?success');
        exit;
    }

    public static function proccessEditAvailableJobTitlesForm()
    {
        if ($_SERVER['REQUEST_METHOD'] != 'POST') return isset($_GET['success']) ? array('success' => true) : false;
        if (!Narin::check_token()) return array('err_msg' => 'به دلیل وجود یک مشکل امنیتی، اطلاعات ثبت نشد. لطفاً دوباره تلاش کنید.');
        if (!(isset($_POST['input_jobtitle']) && Narin::validate_required('input_jobtitle') && strlen($_POST['input_jobtitle']) <= 255)) $invalid['input_jobtitle'] = true;
        if (isset($invalid)) return array('invalid' => $invalid, 'err_msg' => 'اطلاعات دریافت شده معتبر نیست.');
        try {
            Database::execute_query("UPDATE `available-jobtitles` SET `title` = '" . Database::filter_str($_POST['input_jobtitle']) . "' WHERE `id` = '" . Database::filter_str($_POST['old_jobtitle']) . "';");
        } catch (exception $e) {
            return array('err_msg' => $e->getMessage());
        }
        $_logTXT = 'اصلاح تعریف "' . $_POST['input_jobtitle'] . '" در رده‌های پرسنلی';
        self::_makeLog($_logTXT);
        header('Location: ' . substr(strrchr($_SERVER['PHP_SELF'], '/'), 1) . '?cmd=manageAvailableJobTitles');
        exit;
    }

    public static function proccessAddAvailableDrugsFrom()
    {
        if ($_SERVER['REQUEST_METHOD'] != 'POST') return isset($_GET['success']) ? array('success' => true) : false;
        if (!Narin::check_token()) return array('err_msg' => 'به دلیل وجود یک مشکل امنیتی، اطلاعات ثبت نشد. لطفاً دوباره تلاش کنید.');
        if (!(isset($_POST['input_drug_name']) && Narin::validate_required('input_drug_name') && strlen($_POST['input_drug_name']) <= 255)) $invalid['input_drug_name'] = true;
        if (!(isset($_POST['input_drug_shape']) && Narin::validate_required('input_drug_shape') && strlen($_POST['input_drug_shape']) <= 255)) $invalid['input_drug_shape'] = true;
        if (!(Narin::validate_integer('input_drug_cost', 0, false, null, true))) $invalid['input_drug_cost'] = true;
        if (isset($invalid)) return array('invalid' => $invalid, 'err_msg' => 'اطلاعات دریافت شده معتبر نیست.');
        $isFree = null;
        $drugCost = 1;
        if (isset($_POST['isFree'])) $isFree = 'yes'; else {
            $isFree = 'no';
            $drugCost = Database::filter_str($_POST['input_drug_cost']);
        }
        $dateTimeObj = new DateTime();
        $dateTimeZone = new DateTimeZone('Asia/Tehran');
        $dateTimeObj->setTimezone($dateTimeZone);
        $shamsiDate = gregorian_to_jalali($dateTimeObj->format('Y'), $dateTimeObj->format('m'), $dateTimeObj->format('d'));
        $date = $shamsiDate[0] . "-" . $shamsiDate[1] . "-" . $shamsiDate[2];
        try {
            Database::execute_query("INSERT INTO `available-drugs-and-consuming-equipments` (`name`, `shape`, `is_drug`, `is_free`) VALUES ('" . Database::filter_str($_POST['input_drug_name']) . "', '" . Database::filter_str($_POST['input_drug_shape']) . "', 'yes', '$isFree');");
            $res = Database::execute_query("SELECT LAST_INSERT_ID();");
            $row = Database::get_assoc_array($res);
            Database::execute_query("INSERT INTO `available-drugs-cost` (`id`, `drug-id`, `cost`, `date-of-register`) VALUES (NULL, '" . $row['LAST_INSERT_ID()'] . "', '" . $drugCost . "', '$date');");
            unset($dateTimeObj, $dateTimeZone, $date, $shamsiDate);
        } catch (exception $e) {
            return array('err_msg' => $e->getMessage());
        }
        $_logTXT = 'تعریف "' . $_POST['input_drug_name'] . '" به عنوان دارو';
        self::_makeLog($_logTXT);
        header('Location: ' . substr(strrchr($_SERVER['PHP_SELF'], '/'), 1) . '?success');
        exit;
    }

    public static function proccessAddAvailableConsumingEquipmentsFrom()
    {
        if ($_SERVER['REQUEST_METHOD'] != 'POST') return isset($_GET['success']) ? array('success' => true) : false;
        if (!Narin::check_token()) return array('err_msg' => 'به دلیل وجود یک مشکل امنیتی، اطلاعات ثبت نشد. لطفاً دوباره تلاش کنید.');
        if (!(isset($_POST['input_drug_name']) && Narin::validate_required('input_drug_name') && strlen($_POST['input_drug_name']) <= 255)) $invalid['input_drug_name'] = true;
        if (!(isset($_POST['input_drug_shape']) && Narin::validate_required('input_drug_shape') && strlen($_POST['input_drug_shape']) <= 255)) $invalid['input_drug_shape'] = true;
        if (!(isset($_POST['input_drug_cost']) && Narin::validate_required('input_drug_cost') && Narin::validate_integer('input_drug_cost', 0, false, null, true))) $invalid['input_drug_cost'] = true;
        if (isset($invalid)) return array('invalid' => $invalid, 'err_msg' => 'اطلاعات دریافت شده معتبر نیست.');
        $dateTimeObj = new DateTime();
        $dateTimeZone = new DateTimeZone('Asia/Tehran');
        $dateTimeObj->setTimezone($dateTimeZone);
        $shamsiDate = gregorian_to_jalali($dateTimeObj->format('Y'), $dateTimeObj->format('m'), $dateTimeObj->format('d'));
        $date = $shamsiDate[0] . "-" . $shamsiDate[1] . "-" . $shamsiDate[2];
        try {
            $id = Database::insertAndReturnInsertedId("INSERT INTO `available-drugs-and-consuming-equipments` (`name`, `shape`, `is_drug`) VALUES ('" . Database::filter_str($_POST['input_drug_name']) . "', '" . Database::filter_str($_POST['input_drug_shape']) . "', 'no');");
            Database::execute_query("INSERT INTO `available-drugs-cost` (`drug-id`, `cost`, `date-of-register`) VALUES ('" . $id . "', '" . Database::filter_str($_POST['input_drug_cost']) . "', '$date');");
            unset($dateTimeObj, $dateTimeZone, $date, $shamsiDate);
        } catch (exception $e) {
            return array('err_msg' => $e->getMessage());
        }
        $_logTXT = 'تعریف "' . $_POST['input_drug_name'] . '" به عنوان تجهیزات مصرفی';
        self::_makeLog($_logTXT);
        header('Location: ' . substr(strrchr($_SERVER['PHP_SELF'], '/'), 1) . '?success');
        exit;
    }

    public static function proccessEditAvailableDrugsFrom()
    {
        if ($_SERVER['REQUEST_METHOD'] != 'POST') return isset($_GET['success']) ? array('success' => true) : false;
        if (!Narin::check_token()) return array('err_msg' => 'به دلیل وجود یک مشکل امنیتی، اطلاعات ثبت نشد. لطفاً دوباره تلاش کنید.');
        if (!(isset($_POST['input_drug_name']) && Narin::validate_required('input_drug_name') && strlen($_POST['input_drug_name']) <= 255)) $invalid['input_drug_name'] = true;
        if (!(isset($_POST['input_drug_shape']) && Narin::validate_required('input_drug_shape') && strlen($_POST['input_drug_shape']) <= 255)) $invalid['input_drug_shape'] = true;
        if (!(Narin::validate_integer('input_drug_cost', 0, false, null, true) && strlen($_POST['input_drug_cost']) <= 8)) $invalid['input_drug_cost'] = true;
        if (!(isset($_POST['editType']))) $invalid['editType'] = true;
        if (isset($invalid)) return array('invalid' => $invalid, 'err_msg' => 'اطلاعات دریافت شده معتبر نیست.');
        if (isset($_POST['isFree'])) $isFree = 'yes'; else $isFree = 'no';
        if (!$_POST['editType']) {
            Database::execute_query("UPDATE `available-drugs-and-consuming-equipments` SET `name` = '" . Database::filter_str($_POST['input_drug_name']) . "', `shape` = '" . Database::filter_str($_POST['input_drug_shape']) . "' WHERE `id` = '" . Database::filter_str($_POST['old_drug']) . "';");
            $res = Database::execute_query("SELECT MAX(`id`) FROM `available-drugs-cost` WHERE `drug-id` = '" . $_POST['old_drug'] . "';");
            $row = Database::get_assoc_array($res);
            Database::execute_query("UPDATE `available-drugs-cost` SET `cost` = '" . Database::filter_str($_POST['input_drug_cost']) . "' WHERE `available-drugs-cost`.`id` = '" . $row['MAX(`id`)'] . "';");
        } else {
            $dateTimeObj = new DateTime();
            $dateTimeZone = new DateTimeZone('Asia/Tehran');
            $dateTimeObj->setTimezone($dateTimeZone);
            $shamsiDate = gregorian_to_jalali($dateTimeObj->format('Y'), $dateTimeObj->format('m'), $dateTimeObj->format('d'));
            $date = $shamsiDate[0] . "-" . $shamsiDate[1] . "-" . $shamsiDate[2];
            try {
                Database::execute_query("UPDATE `available-drugs-and-consuming-equipments` SET `name` = '" . Database::filter_str($_POST['input_drug_name']) . "', `shape` = '" . Database::filter_str($_POST['input_drug_shape']) . "' WHERE `id` = '" . Database::filter_str($_POST['old_drug']) . "';");
                Database::execute_query("INSERT INTO `available-drugs-cost` (`id`, `drug-id`, `cost`, `date-of-register`) VALUES (NULL, '" . $_POST['old_drug'] . "', '" . Database::filter_str($_POST['input_drug_cost']) . "', '$date');");
            } catch (exception $e) {
                return array('err_msg' => $e->getMessage());
            }
            unset($dateTimeObj, $dateTimeZone, $date, $shamsiDate);
        }
        $_logTXT = 'اصلاح تعریف "' . $_POST['input_drug_name'] . '" به عنوان دارو';
        self::_makeLog($_logTXT);
        header('Location: ' . substr(strrchr($_SERVER['PHP_SELF'], '/'), 1) . '?cmd=manageAvailableDrugs');
        exit;
    }

    public static function proccessEditAvailableConsumingEquipmentsForm()
    {
        if ($_SERVER['REQUEST_METHOD'] != 'POST') return isset($_GET['success']) ? array('success' => true) : false;
        if (!Narin::check_token()) return array('err_msg' => 'به دلیل وجود یک مشکل امنیتی، اطلاعات ثبت نشد. لطفاً دوباره تلاش کنید.');
        if (!(isset($_POST['input_drug_name']) && Narin::validate_required('input_drug_name') && strlen($_POST['input_drug_name']) <= 255)) $invalid['input_drug_name'] = true;
        if (!(isset($_POST['input_drug_shape']) && Narin::validate_required('input_drug_shape') && strlen($_POST['input_drug_shape']) <= 255)) $invalid['input_drug_shape'] = true;
        if (!(isset($_POST['input_drug_cost']) && Narin::validate_required('input_drug_cost') && Narin::validate_integer('input_drug_cost', 0, false, null, true) && strlen($_POST['input_drug_cost']) <= 8)) $invalid['input_drug_cost'] = true;
        if (isset($invalid)) return array('invalid' => $invalid, 'err_msg' => 'اطلاعات دریافت شده معتبر نیست.');
        if ($_POST['editType']) {
            Database::execute_query("UPDATE `available-drugs-and-consuming-equipments` SET `name` = '" . Database::filter_str($_POST['input_drug_name']) . "', `shape` = '" . Database::filter_str($_POST['input_drug_shape']) . "' WHERE `id` = '" . Database::filter_str($_POST['old_drug']) . "';");
            $res = Database::execute_query("SELECT MAX(`id`) FROM `available-drugs-cost` WHERE `drug-id` = '" . $_POST['old_drug'] . "';");
            $row = Database::get_assoc_array($res);
            Database::execute_query("UPDATE `available-drugs-cost` SET `cost` = '" . Database::filter_str($_POST['input_drug_cost']) . "' WHERE `id` = '" . $row['MAX(`id`)'] . "';");
        } else {
            $dateTimeObj = new DateTime();
            $dateTimeZone = new DateTimeZone('Asia/Tehran');
            $dateTimeObj->setTimezone($dateTimeZone);
            $shamsiDate = gregorian_to_jalali($dateTimeObj->format('Y'), $dateTimeObj->format('m'), $dateTimeObj->format('d'));
            $date = $shamsiDate[0] . "-" . $shamsiDate[1] . "-" . $shamsiDate[2];
            try {
                Database::execute_query("UPDATE `available-drugs-and-consuming-equipments` SET `name` = '" . Database::filter_str($_POST['input_drug_name']) . "', `shape` = '" . Database::filter_str($_POST['input_drug_shape']) . "' WHERE `id` = '" . Database::filter_str($_POST['old_drug']) . "';");
                Database::execute_query("INSERT INTO `available-drugs-cost` (`drug-id`, `cost`, `date-of-register`) VALUES ('" . $_POST['old_drug'] . "', '" . Database::filter_str($_POST['input_drug_cost']) . "', '$date');");
            } catch (exception $e) {
                return array('err_msg' => $e->getMessage());
            }
            unset($dateTimeObj, $dateTimeZone, $date, $shamsiDate);
        }
        $_logTXT = 'اصلاح تعریف "' . $_POST['input_drug_name'] . '" به عنوان تجهیزات مصرفی';
        self::_makeLog($_logTXT);
        header('Location: ' . substr(strrchr($_SERVER['PHP_SELF'], '/'), 1) . '?cmd=manageAvailableConsumingEquipments');
        exit;
    }

    public static function proccessAddUnitServiceForm()
    {
        if (isset($_POST['input_service_name'])) {
            try {
                Database::execute_query("INSERT INTO `unit-services` (`id`, `service-id`, `unit-id`) VALUES (NULL, '" . Database::filter_str($_POST['input_service_name']) . "', '" . Database::filter_str($_SESSION['unitId']) . "');");
            } catch (exception $e) {
                return $e->getMessage();
            }
            $resS = Database::execute_query("SELECT `name` FROM `available-services` WHERE `id` = '" . Database::filter_str($_POST['input_service_name']) . "';");
            $rowS = Database::get_assoc_array($resS);
            $resU = Database::execute_query("SELECT `name`, `center-id` FROM `units` WHERE `id` = '" . Database::filter_str($_SESSION['unitId']) . "';");
            $rowU = Database::get_assoc_array($resU);
            $resC = Database::execute_query("SELECT `name`, `town-id` FROM `centers` WHERE `id` = '" . $rowU['center-id'] . "';");
            $rowC = Database::get_assoc_array($resC);
            $res = Database::execute_query("SELECT `name` FROM `towns` WHERE `id` = '" . $rowC['town-id'] . "';");
            $row = Database::get_assoc_array($res);
            $_logTXT = 'افزودن خدمت "' . $rowS['name'] . '" به واحد "' . $rowU['name'] . '" در مرکز "' . $rowC['name'] . '" در شهرستان "' . $row['name'] . '"';
            self::_makeLog($_logTXT);
        }
        return true;
    }

    public static function proccess_eash_AddUnitServiceForm()
    {
        if (isset($_POST['input_service_name'])) {
            try {
                Database::execute_query("INSERT INTO `unit-services` (`id`, `service-id`, `unit-id`) VALUES (NULL, '" . Database::filter_str($_POST['input_service_name']) . "', '" . Database::filter_str($_POST['unitId']) . "');");
            } catch (exception $e) {
                return $e->getMessage();
            }
            $resS = Database::execute_query("SELECT `name` FROM `available-services` WHERE `id` = '" . Database::filter_str($_POST['input_service_name']) . "';");
            $rowS = Database::get_assoc_array($resS);
            $resU = Database::execute_query("SELECT `name`, `center-id` FROM `units` WHERE `id` = '" . Database::filter_str($_POST['unitId']) . "';");
            $rowU = Database::get_assoc_array($resU);
            $resC = Database::execute_query("SELECT `name`, `town-id` FROM `centers` WHERE `id` = '" . $rowU['center-id'] . "';");
            $rowC = Database::get_assoc_array($resC);
            $res = Database::execute_query("SELECT `name` FROM `towns` WHERE `id` = '" . $rowC['town-id'] . "';");
            $row = Database::get_assoc_array($res);
            $_logTXT = 'افزودن خدمت "' . $rowS['name'] . '" به واحد "' . $rowU['name'] . '" در مرکز "' . $rowC['name'] . '" در شهرستان "' . $row['name'] . '"';
            self::_makeLog($_logTXT);
        }
        return true;
    }

    public static function proccessAddUnitPersonnelForm()
    {
        if ($_SERVER['REQUEST_METHOD'] != 'POST') return isset($_GET['success']) ? array('success' => true) : false;
        if (!Narin::check_token()) return array('err_msg' => 'به دلیل وجود یک مشکل امنیتی، اطلاعات ثبت نشد. لطفاً دوباره تلاش کنید.');
        if (!(isset($_POST['input_name']) && Narin::validate_required('input_name') && strlen($_POST['input_name']) <= 255)) $invalid['input_name'] = true;
        if (!(isset($_POST['input_lastname']) && Narin::validate_required('input_lastname') && strlen($_POST['input_lastname']) <= 255)) $invalid['input_lastname'] = true;
        if (!(isset($_POST['input_last_ed_deg']) && Narin::validate_required('input_last_ed_deg') && strlen($_POST['input_last_ed_deg']) <= 255)) $invalid['input_last_ed_deg'] = true;
        if (!(isset($_POST['input_base_salary']) && Narin::validate_required('input_base_salary') && Narin::validate_integer('input_base_salary', 0, false, null, true))) $invalid['input_base_salary'] = true;
        if (isset($_POST['input_work-bg']) && !Narin::validate_integer('input_work-bg', 0, false, 360, true) && !strlen($_POST['input_work-bg']) <= 3) $invalid['input_work-bg'] = true;
        if (!(isset($_POST['input_sex']))) $invalid['input_sex'] = true;
        if (!(isset($_POST['input_jobtitle']))) $invalid['input_jobtitle'] = true;
        if (isset($invalid)) return array('invalid' => $invalid, 'err_msg' => 'اطلاعات دریافت شده معتبر نیست.');
        try {
            Database::execute_query("INSERT INTO `unit-personnels` (`id`, `unit-id`, `name`, `lastname`, `job-title`, `last-educational-degree`, `age`, `sex`, `work-background`, `base-salary`) VALUES (NULL, '" . Database::filter_str($_SESSION['unitId']) . "', '" . Database::filter_str($_POST['input_name']) . "', '" . Database::filter_str($_POST['input_lastname']) . "', '" . Database::filter_str($_POST['input_jobtitle']) . "', '" . Database::filter_str($_POST['input_last_ed_deg']) . "', '" . Database::filter_str($_POST['input_age']) . "', '" . Database::filter_str($_POST['input_sex']) . "', '" . Database::filter_str($_POST['input_work-bg']) . "', '" . Database::filter_str($_POST['input_base_salary']) . "');");
        } catch (exception $e) {
            return array('err_msg' => $e->getMessage());
        }
        $resU = Database::execute_query("SELECT `name`, `center-id` FROM `units` WHERE `id` = '" . Database::filter_str($_SESSION['unitId']) . "';");
        $rowU = Database::get_assoc_array($resU);
        $resC = Database::execute_query("SELECT `name`, `town-id` FROM `centers` WHERE `id` = '" . $rowU['center-id'] . "';");
        $rowC = Database::get_assoc_array($resC);
        $res = Database::execute_query("SELECT `name` FROM `towns` WHERE `id` = '" . $rowC['town-id'] . "';");
        $row = Database::get_assoc_array($res);
        $_logTXT = 'افزودن "' . $_POST['input_name'] . ' ' . $_POST['input_lastname'] . '" به عنوان پرسنل واحد "' . $rowU['name'] . '" در مرکز "' . $rowC['name'] . '" در شهرستان "' . $row['name'] . '"';
        self::_makeLog($_logTXT);
        header('Location: ' . substr(strrchr($_SERVER['PHP_SELF'], '/'), 1) . '?success');
        exit;
    }

    public static function proccessEditUnitPersonnelForm()
    {
        if ($_SERVER['REQUEST_METHOD'] != 'POST') return isset($_GET['success']) ? array('success' => true) : false;
        if (!Narin::check_token()) return array('err_msg' => 'به دلیل وجود یک مشکل امنیتی، اطلاعات ثبت نشد. لطفاً دوباره تلاش کنید.');
        if (!(isset($_POST['input_name']) && Narin::validate_required('input_name') && strlen($_POST['input_name']) <= 255)) $invalid['input_name'] = true;
        if (!(isset($_POST['input_lastname']) && Narin::validate_required('input_lastname') && strlen($_POST['input_lastname']) <= 255)) $invalid['input_lastname'] = true;
        if (!(isset($_POST['input_last_ed_deg']) && Narin::validate_required('input_last_ed_deg') && strlen($_POST['input_last_ed_deg']) <= 255)) $invalid['input_last_ed_deg'] = true;
        if (!(isset($_POST['input_base_salary']) && Narin::validate_required('input_base_salary') && Narin::validate_integer('input_base_salary', 0, false, null, true))) $invalid['input_base_salary'] = true;
        if (isset($_POST['input_work-bg']) && !Narin::validate_integer('input_work-bg', 0, false, 360, true) && !strlen($_POST['input_work-bg']) <= 3) $invalid['input_work-bg'] = true;
        if (!(isset($_POST['input_sex']))) $invalid['input_sex'] = true;
        if (!(isset($_POST['input_jobtitle']))) $invalid['input_jobtitle'] = true;
        if (isset($invalid)) return array('invalid' => $invalid, 'err_msg' => 'اطلاعات دریافت شده معتبر نیست.');
        try {
            Database::execute_query("UPDATE `unit-personnels` SET `name` = '" . Database::filter_str($_POST['input_name']) . "', `lastname` = '" . Database::filter_str($_POST['input_lastname']) . "', `job-title` = '" . Database::filter_str($_POST['input_jobtitle']) . "', `last-educational-degree` = '" . Database::filter_str($_POST['input_last_ed_deg']) . "', `age` = '" . Database::filter_str($_POST['input_age']) . "', `sex` = '" . Database::filter_str($_POST['input_sex']) . "', `work-background` = '" . Database::filter_str($_POST['input_work-bg']) . "', `base-salary` = '" . Database::filter_str($_POST['input_base_salary']) . "' WHERE `id` = '" . $_POST['id'] . "';");
        } catch (exception $e) {
            return array('err_msg' => $e->getMessage());
        }
        $resI = Database::execute_query("SELECT `unit-id` FROM `unit-personnels` WHERE `id` = '" . Database::filter_str($_POST['id']) . "';");
        $rowI = Database::get_assoc_array($resI);
        $resU = Database::execute_query("SELECT `name`, `center-id` FROM `units` WHERE `id` = '" . $rowI['unit-id'] . "';");
        $rowU = Database::get_assoc_array($resU);
        $resC = Database::execute_query("SELECT `name`, `town-id` FROM `centers` WHERE `id` = '" . $rowU['center-id'] . "';");
        $rowC = Database::get_assoc_array($resC);
        $res = Database::execute_query("SELECT `name` FROM `towns` WHERE `id` = '" . $rowC['town-id'] . "';");
        $row = Database::get_assoc_array($res);
        $_logTXT = 'اصلاح "' . $_POST['input_name'] . ' ' . $_POST['input_lastname'] . '" در پرسنل‌های واحد "' . $rowU['name'] . '" در مرکز "' . $rowC['name'] . '" در شهرستان "' . $row['name'] . '"';
        self::_makeLog($_logTXT);
        if ($_SESSION['ref'] == 1) {
            unset($_SESSION['ref']);
            header('Location: ' . substr(strrchr($_SERVER['PHP_SELF'], '/'), 1) . '?es=manageUnitsPersonnels');
            exit;
        } else {
            unset($_SESSION['ref']);
            header('Location: ' . substr(strrchr($_SERVER['PHP_SELF'], '/'), 1) . '?cmd=manageUnitPersonnel');
            exit;
        }
    }

    public static function proccess_eash_AddUnitPersonnelForm()
    {
        if ($_SERVER['REQUEST_METHOD'] != 'POST') return isset($_GET['success']) ? array('success' => true) : false;
        if (!Narin::check_token()) return array('err_msg' => 'به دلیل وجود یک مشکل امنیتی، اطلاعات ثبت نشد. لطفاً دوباره تلاش کنید.');
        if (!(isset($_POST['input_name']) && Narin::validate_required('input_name') && strlen($_POST['input_name']) <= 255)) $invalid['input_name'] = true;
        if (!(isset($_POST['input_lastname']) && Narin::validate_required('input_lastname') && strlen($_POST['input_lastname']) <= 255)) $invalid['input_lastname'] = true;
        if (!(isset($_POST['input_last_ed_deg']) && Narin::validate_required('input_last_ed_deg') && strlen($_POST['input_last_ed_deg']) <= 255)) $invalid['input_last_ed_deg'] = true;
        if (!(isset($_POST['input_base_salary']) && Narin::validate_required('input_base_salary') && Narin::validate_integer('input_base_salary', 0, false, null, true))) $invalid['input_base_salary'] = true;
        if (isset($_POST['input_work-bg']) && !Narin::validate_integer('input_work-bg', 0, false, 360, true)) $invalid['input_work-bg'] = true;
        if (!(isset($_POST['input_sex']))) $invalid['input_sex'] = true;
        if (!(isset($_POST['input_jobtitle']))) $invalid['input_jobtitle'] = true;
        if (isset($invalid)) return array('invalid' => $invalid, 'err_msg' => 'اطلاعات دریافت شده معتبر نیست.');
        try {
            Database::execute_query("INSERT INTO `unit-personnels` (`id`, `unit-id`, `name`, `lastname`, `job-title`, `last-educational-degree`, `age`, `sex`, `work-background`, `base-salary`) VALUES (NULL, '" . Database::filter_str($_POST['unitId']) . "', '" . Database::filter_str($_POST['input_name']) . "', '" . Database::filter_str($_POST['input_lastname']) . "', '" . Database::filter_str($_POST['input_jobtitle']) . "', '" . Database::filter_str($_POST['input_last_ed_deg']) . "', '" . Database::filter_str($_POST['input_age']) . "', '" . Database::filter_str($_POST['input_sex']) . "', '" . Database::filter_str($_POST['input_work-bg']) . "', '" . Database::filter_str($_POST['input_base_salary']) . "');");
        } catch (exception $e) {
            return array('err_msg' => $e->getMessage());
        }
        $resU = Database::execute_query("SELECT `name`, `center-id` FROM `units` WHERE `id` = '" . Database::filter_str($_POST['unitId']) . "';");
        $rowU = Database::get_assoc_array($resU);
        $resC = Database::execute_query("SELECT `name`, `town-id` FROM `centers` WHERE `id` = '" . $rowU['center-id'] . "';");
        $rowC = Database::get_assoc_array($resC);
        $res = Database::execute_query("SELECT `name` FROM `towns` WHERE `id` = '" . $rowC['town-id'] . "';");
        $row = Database::get_assoc_array($res);
        $_logTXT = 'افزودن "' . $_POST['input_name'] . ' ' . $_POST['input_lastname'] . '" به عنوان پرسنل واحد "' . $rowU['name'] . '" در مرکز "' . $rowC['name'] . '" در شهرستان "' . $row['name'] . '"';
        self::_makeLog($_logTXT);
        header('Location: ' . substr(strrchr($_SERVER['PHP_SELF'], '/'), 1) . '?success');
        exit;
    }

    public static function proccessAddHygieneUnitBehvarzForm()
    {
        if ($_SERVER['REQUEST_METHOD'] != 'POST') return isset($_GET['success']) ? array('success' => true) : false;
        if (!Narin::check_token()) return array('err_msg' => 'به دلیل وجود یک مشکل امنیتی، اطلاعات ثبت نشد. لطفاً دوباره تلاش کنید.');
        if (!(isset($_POST['input_name']) && Narin::validate_required('input_name') && strlen($_POST['input_name']) <= 255)) $invalid['input_name'] = true;
        if (!(isset($_POST['input_lastname']) && Narin::validate_required('input_lastname') && strlen($_POST['input_lastname']) <= 255)) $invalid['input_lastname'] = true;
        if (!(isset($_POST['input_last_ed_deg']) && Narin::validate_required('input_last_ed_deg') && strlen($_POST['input_last_ed_deg']) <= 255)) $invalid['input_last_ed_deg'] = true;
        if (!(isset($_POST['input_base_salary']) && Narin::validate_required('input_base_salary') && Narin::validate_integer('input_base_salary', 0, false, null, true))) $invalid['input_base_salary'] = true;
        if (isset($_POST['input_work-bg']) && Narin::validate_required('input_work-bg') && !Narin::validate_integer('input_work-bg', 0, false, 360, true) && !strlen($_POST['input_work-bg']) <= 3) $invalid['input_work-bg'] = true;
        if (!(isset($_POST['input_sex']))) $invalid['input_sex'] = true;
        if (isset($invalid)) return array('invalid' => $invalid, 'err_msg' => 'اطلاعات دریافت شده معتبر نیست.');
        try {
            Database::execute_query("INSERT INTO `hygiene-unit-personnels` (`id`, `hygiene-unit-id`, `name`, `lastname`, `job-title`, `last-educational-degree`, `age`, `sex`, `work-background`, `base-salary`) VALUES (NULL, '" . Database::filter_str($_SESSION['hygieneUnitId']) . "', '" . Database::filter_str($_POST['input_name']) . "', '" . Database::filter_str($_POST['input_lastname']) . "', '" . Database::filter_str($_POST['input_jobtitle']) . "', '" . Database::filter_str($_POST['input_last_ed_deg']) . "', '" . Database::filter_str($_POST['input_age']) . "', '" . Database::filter_str($_POST['input_sex']) . "', '" . Database::filter_str($_POST['input_work-bg']) . "', '" . Database::filter_str($_POST['input_base_salary']) . "');");
        } catch (exception $e) {
            return array('err_msg' => $e->getMessage());
        }
        $resU = Database::execute_query("SELECT `name`, `center-id` FROM `hygiene-units` WHERE `id` = '" . Database::filter_str($_SESSION['hygieneUnitId']) . "';");
        $rowU = Database::get_assoc_array($resU);
        $resC = Database::execute_query("SELECT `name`, `town-id` FROM `centers` WHERE `id` = '" . $rowU['center-id'] . "';");
        $rowC = Database::get_assoc_array($resC);
        $res = Database::execute_query("SELECT `name` FROM `towns` WHERE `id` = '" . $rowC['town-id'] . "';");
        $row = Database::get_assoc_array($res);
        $_logTXT = 'افزودن "' . $_POST['input_name'] . ' ' . $_POST['input_lastname'] . '" به عنوان بهورز خانه‌بهداشت "' . $rowU['name'] . '" در مرکز "' . $rowC['name'] . '" در شهرستان "' . $row['name'] . '"';
        self::_makeLog($_logTXT);
        header('Location: ' . substr(strrchr($_SERVER['PHP_SELF'], '/'), 1) . '?success');
        exit;
    }

    public static function proccessEditHygieneUnitBehvarzForm()
    {
        if ($_SERVER['REQUEST_METHOD'] != 'POST') return isset($_GET['success']) ? array('success' => true) : false;
        if (!Narin::check_token()) return array('err_msg' => 'به دلیل وجود یک مشکل امنیتی، اطلاعات ثبت نشد. لطفاً دوباره تلاش کنید.');
        if (!(isset($_POST['input_name']) && Narin::validate_required('input_name') && strlen($_POST['input_name']) <= 255)) $invalid['input_name'] = true;
        if (!(isset($_POST['input_lastname']) && Narin::validate_required('input_lastname') && strlen($_POST['input_lastname']) <= 255)) $invalid['input_lastname'] = true;
        if (!(isset($_POST['input_last_ed_deg']) && Narin::validate_required('input_last_ed_deg') && strlen($_POST['input_last_ed_deg']) <= 255)) $invalid['input_last_ed_deg'] = true;
        if (!(isset($_POST['input_base_salary']) && Narin::validate_required('input_base_salary') && Narin::validate_integer('input_base_salary', 0, false, null, true))) $invalid['input_base_salary'] = true;
        if (isset($_POST['input_work-bg']) && Narin::validate_required('input_work-bg') && !Narin::validate_integer('input_work-bg', 0, false, 360, true) && !strlen($_POST['input_work-bg']) <= 3) $invalid['input_work-bg'] = true;
        if (!(isset($_POST['input_sex']))) $invalid['input_sex'] = true;
        if (isset($invalid)) return array('invalid' => $invalid, 'err_msg' => 'اطلاعات دریافت شده معتبر نیست.');
        try {
            Database::execute_query("UPDATE `hygiene-unit-personnels` SET `name` = '" . Database::filter_str($_POST['input_name']) . "', `lastname` = '" . Database::filter_str($_POST['input_lastname']) . "', `last-educational-degree` = '" . Database::filter_str($_POST['input_last_ed_deg']) . "', `age` = '" . Database::filter_str($_POST['input_age']) . "', `sex` = '" . Database::filter_str($_POST['input_sex']) . "', `work-background` = '" . Database::filter_str($_POST['input_work-bg']) . "', `base-salary` = '" . Database::filter_str($_POST['input_base_salary']) . "' WHERE `id` = '" . $_POST['id'] . "';");
        } catch (exception $e) {
            return array('err_msg' => $e->getMessage());
        }
        $resI = Database::execute_query("SELECT `hygiene-unit-id` FROM `hygiene-unit-personnels` WHERE `id` = '" . Database::filter_str($_POST['id']) . "';");
        $rowI = Database::get_assoc_array($resI);
        $resU = Database::execute_query("SELECT `name`, `center-id` FROM `hygiene-units` WHERE `id` = '" . $rowI['hygiene-unit-id'] . "';");
        $rowU = Database::get_assoc_array($resU);
        $resC = Database::execute_query("SELECT `name`, `town-id` FROM `centers` WHERE `id` = '" . $rowU['center-id'] . "';");
        $rowC = Database::get_assoc_array($resC);
        $res = Database::execute_query("SELECT `name` FROM `towns` WHERE `id` = '" . $rowC['town-id'] . "';");
        $row = Database::get_assoc_array($res);
        $_logTXT = 'اصلاح "' . $_POST['input_name'] . ' ' . $_POST['input_lastname'] . '" در بهورزهای خانه‌بهداشت "' . $rowU['name'] . '" در مرکز "' . $rowC['name'] . '" در شهرستان "' . $row['name'] . '"';
        self::_makeLog($_logTXT);
        if ($_SESSION['ref'] == 1) {
            unset($_SESSION['ref']);
            header('Location: ' . substr(strrchr($_SERVER['PHP_SELF'], '/'), 1) . '?es=manageHygieneUnitsPersonnel');
            exit;
        } else {
            unset($_SESSION['ref']);
            header('Location: ' . substr(strrchr($_SERVER['PHP_SELF'], '/'), 1) . '?cmd=manageHygieneUnitPersonnel');
            exit;
        }
    }

    public static function proccess_eash_AddHygieneUnitBehvarzForm()
    {
        if ($_SERVER['REQUEST_METHOD'] != 'POST') return isset($_GET['success']) ? array('success' => true) : false;
        if (!Narin::check_token()) return array('err_msg' => 'به دلیل وجود یک مشکل امنیتی، اطلاعات ثبت نشد. لطفاً دوباره تلاش کنید.');
        if (!(isset($_POST['input_name']) && Narin::validate_required('input_name') && strlen($_POST['input_name']) <= 255)) $invalid['input_name'] = true;
        if (!(isset($_POST['input_lastname']) && Narin::validate_required('input_lastname') && strlen($_POST['input_lastname']) <= 255)) $invalid['input_lastname'] = true;
        if (!(isset($_POST['input_last_ed_deg']) && Narin::validate_required('input_last_ed_deg') && strlen($_POST['input_last_ed_deg']) <= 255)) $invalid['input_last_ed_deg'] = true;
        if (!(isset($_POST['input_base_salary']) && Narin::validate_required('input_base_salary') && Narin::validate_integer('input_base_salary', 0, false, null, true))) $invalid['input_base_salary'] = true;
        if (isset($_POST['input_work-bg']) && !Narin::validate_integer('input_work-bg', 0, false, 360, true)) $invalid['input_work-bg'] = true;
        if (!(isset($_POST['input_sex']))) $invalid['input_sex'] = true;
        if (isset($invalid)) return array('invalid' => $invalid, 'err_msg' => 'اطلاعات دریافت شده معتبر نیست.');
        try {
            Database::execute_query("INSERT INTO `hygiene-unit-personnels` (`id`, `hygiene-unit-id`, `name`, `lastname`, `job-title`, `last-educational-degree`, `age`, `sex`, `work-background`, `base-salary`) VALUES (NULL, '" . Database::filter_str($_POST['hygieneUnitId']) . "', '" . Database::filter_str($_POST['input_name']) . "', '" . Database::filter_str($_POST['input_lastname']) . "', '" . Database::filter_str($_POST['input_jobtitle']) . "', '" . Database::filter_str($_POST['input_last_ed_deg']) . "', '" . Database::filter_str($_POST['input_age']) . "', '" . Database::filter_str($_POST['input_sex']) . "', '" . Database::filter_str($_POST['input_work-bg']) . "', '" . Database::filter_str($_POST['input_base_salary']) . "');");
        } catch (exception $e) {
            return array('err_msg' => $e->getMessage());
        }
        $resU = Database::execute_query("SELECT `name`, `center-id` FROM `hygiene-units` WHERE `id` = '" . Database::filter_str($_POST['hygieneUnitId']) . "';");
        $rowU = Database::get_assoc_array($resU);
        $resC = Database::execute_query("SELECT `name`, `town-id` FROM `centers` WHERE `id` = '" . $rowU['center-id'] . "';");
        $rowC = Database::get_assoc_array($resC);
        $res = Database::execute_query("SELECT `name` FROM `towns` WHERE `id` = '" . $rowC['town-id'] . "';");
        $row = Database::get_assoc_array($res);
        $_logTXT = 'افزودن "' . $_POST['input_name'] . ' ' . $_POST['input_lastname'] . '" به عنوان بهورز خانه‌بهداشت "' . $rowU['name'] . '" در مرکز "' . $rowC['name'] . '" در شهرستان "' . $row['name'] . '"';
        self::_makeLog($_logTXT);
        header('Location: ' . substr(strrchr($_SERVER['PHP_SELF'], '/'), 1) . '?success');
        exit;
    }

    public static function proccessAddHygieneUnitServiceForm()
    {
        if (isset($_POST['input_service_name'])) {
            try {
                Database::execute_query("INSERT INTO `hygiene-unit-services` (`id`, `service-id`, `hygiene-unit-id`) VALUES (NULL, '" . Database::filter_str($_POST['input_service_name']) . "', '" . Database::filter_str($_SESSION['hygieneUnitId']) . "');");
            } catch (exception $e) {
                return $e->getMessage();
            }
            $resS = Database::execute_query("SELECT `name` FROM `available-services` WHERE `id` = '" . Database::filter_str($_POST['input_service_name']) . "';");
            $rowS = Database::get_assoc_array($resS);
            $resU = Database::execute_query("SELECT `name`, `center-id` FROM `hygiene-units` WHERE `id` = '" . Database::filter_str($_SESSION['hygieneUnitId']) . "';");
            $rowU = Database::get_assoc_array($resU);
            $resC = Database::execute_query("SELECT `name`, `town-id` FROM `centers` WHERE `id` = '" . $rowU['center-id'] . "';");
            $rowC = Database::get_assoc_array($resC);
            $res = Database::execute_query("SELECT `name` FROM `towns` WHERE `id` = '" . $rowC['town-id'] . "';");
            $row = Database::get_assoc_array($res);
            $_logTXT = 'افزودن خدمت "' . $rowS['name'] . '" به خانه‌بهداشت "' . $rowU['name'] . '" در مرکز "' . $rowC['name'] . '" در شهرستان "' . $row['name'] . '"';
            self::_makeLog($_logTXT);
        }
        return true;
    }

    public static function proccess_eash_AddHygieneUnitServiceForm()
    {
        if (isset($_POST['input_service_name'])) {
            try {
                Database::execute_query("INSERT INTO `hygiene-unit-services` (`id`, `service-id`, `hygiene-unit-id`) VALUES (NULL, '" . Database::filter_str($_POST['input_service_name']) . "', '" . Database::filter_str($_POST['hygieneUnitId']) . "');");
            } catch (exception $e) {
                return $e->getMessage();
            }
            $resS = Database::execute_query("SELECT `name` FROM `available-services` WHERE `id` = '" . Database::filter_str($_POST['input_service_name']) . "';");
            $rowS = Database::get_assoc_array($resS);
            $resU = Database::execute_query("SELECT `name`, `center-id` FROM `hygiene-units` WHERE `id` = '" . Database::filter_str($_POST['hygieneUnitId']) . "';");
            $rowU = Database::get_assoc_array($resU);
            $resC = Database::execute_query("SELECT `name`, `town-id` FROM `centers` WHERE `id` = '" . $rowU['center-id'] . "';");
            $rowC = Database::get_assoc_array($resC);
            $res = Database::execute_query("SELECT `name` FROM `towns` WHERE `id` = '" . $rowC['town-id'] . "';");
            $row = Database::get_assoc_array($res);
            $_logTXT = 'افزودن خدمت "' . $rowS['name'] . '" به خانه‌بهداشت "' . $rowU['name'] . '" در مرکز "' . $rowC['name'] . '" در شهرستان "' . $row['name'] . '"';
            self::_makeLog($_logTXT);
        }
        return true;
    }

    public static function proccessAddCenterBuildingForm()
    {
        if ($_SERVER['REQUEST_METHOD'] != 'POST') return isset($_GET['success']) ? array('success' => true) : false;
        if (!Narin::check_token()) return array('err_msg' => 'به دلیل وجود یک مشکل امنیتی، اطلاعات ثبت نشد. لطفاً دوباره تلاش کنید.');
        if (!(isset($_POST['input_building_title']) && Narin::validate_required('input_building_title') && strlen($_POST['input_building_title']) <= 255)) $invalid['input_building_title'] = true;
        if (!(isset($_POST['input_ground_area']) && Narin::validate_required('input_ground_area') && Narin::validate_integer('input_ground_area', 0, false, null, true))) $invalid['input_ground_area'] = true;
        if (!(isset($_POST['input_building_area']) && Narin::validate_required('input_building_area') && Narin::validate_integer('input_building_area', 0, false, null, true))) $invalid['input_building_area'] = true;
        if (!(isset($_POST['input_ownership_type']))) $invalid['input_ownership_type'] = true;
        if (isset($_POST['input_ownership_type']) and $_POST['input_ownership_type'] == 1) {
            if (!(isset($_POST['input_rent_cost']) && Narin::validate_required('input_rent_cost') && Narin::validate_integer('input_rent_cost', 0, false, null, true))) $invalid['input_rent_cost'] = true;
        }
        if (isset($_POST['input_ownership_type']) and $_POST['input_ownership_type'] == 0) {
            if (!isset($_POST['input_built_date:y'])) $invalid['input_built_date'] = true;
        }
        if (isset($invalid)) return array('invalid' => $invalid, 'err_msg' => 'اطلاعات دریافت شده معتبر نیست.');
        try {
            if ($_POST['input_ownership_type'] == 0) {
                Database::execute_query("INSERT INTO `center-buildings` (`title`, `center-id`, `ownership-type`, `ground-area`, `building-area`, `rent-cost`, `building-worth`, `built-date`) VALUES ('" . Database::filter_str($_POST['input_building_title']) . "', '" . Database::filter_str($_SESSION['centerId']) . "', '" . Database::filter_str($_POST['input_ownership_type']) . "', '" . Database::filter_str($_POST['input_ground_area']) . "', '" . Database::filter_str($_POST['input_building_area']) . "', NULL, '" . Database::filter_str($_POST['input_building_worth']) . "', '" . Database::filter_str($_POST['input_built_date:y'] . '-' . $_POST['input_built_date:m'] . '-' . $_POST['input_built_date:d']) . "');");
            } else if ($_POST['input_ownership_type'] == 1) {
                Database::execute_query("INSERT INTO `center-buildings` (`title`, `center-id`, `ownership-type`, `ground-area`, `building-area`, `rent-cost`, `building-worth`, `built-date`) VALUES ('" . Database::filter_str($_POST['input_building_title']) . "', '" . Database::filter_str($_SESSION['centerId']) . "', '" . Database::filter_str($_POST['input_ownership_type']) . "', '" . Database::filter_str($_POST['input_ground_area']) . "', '" . Database::filter_str($_POST['input_building_area']) . "', " . Database::filter_str($_POST['input_rent_cost']) . ", NULL, NULL);");
            } else if ($_POST['input_ownership_type'] == 2) {
                Database::execute_query("INSERT INTO `center-buildings` (`title`, `center-id`, `ownership-type`, `ground-area`, `building-area`, `rent-cost`, `building-worth`, `built-date`) VALUES ('" . Database::filter_str($_POST['input_building_title']) . "', '" . Database::filter_str($_SESSION['centerId']) . "', '" . Database::filter_str($_POST['input_ownership_type']) . "', '" . Database::filter_str($_POST['input_ground_area']) . "', '" . Database::filter_str($_POST['input_building_area']) . "', NULL, NULL, NULL);");
            }
        } catch (exception $e) {
            return array('err_msg' => $e->getMessage());
        }
        $resC = Database::execute_query("SELECT `name`, `town-id` FROM `centers` WHERE `id` = '" . Database::filter_str($_SESSION['centerId']) . "';");
        $rowC = Database::get_assoc_array($resC);
        $res = Database::execute_query("SELECT `name` FROM `towns` WHERE `id` = '" . $rowC['town-id'] . "';");
        $row = Database::get_assoc_array($res);
        $_logTXT = 'افزودن "' . $_POST['input_building_title'] . '" به ساختمان‌های مرکز "' . $rowC['name'] . '" در شهرستان "' . $row['name'] . '"';
        self::_makeLog($_logTXT);
        header('Location: ' . substr(strrchr($_SERVER['PHP_SELF'], '/'), 1) . '?success');
        exit;
    }

    public static function proccessEditCenterBuildingForm()
    {
        if ($_SERVER['REQUEST_METHOD'] != 'POST') return isset($_GET['success']) ? array('success' => true) : false;
        if (!Narin::check_token()) return array('err_msg' => 'به دلیل وجود یک مشکل امنیتی، اطلاعات ثبت نشد. لطفاً دوباره تلاش کنید.');
        if (!(isset($_POST['input_building_title']) && Narin::validate_required('input_building_title') && strlen($_POST['input_building_title']) <= 255)) $invalid['input_building_title'] = true;
        if (!(isset($_POST['input_ground_area']) && Narin::validate_required('input_ground_area') && Narin::validate_integer('input_ground_area', 0, false, null, true))) $invalid['input_ground_area'] = true;
        if (!(isset($_POST['input_building_area']) && Narin::validate_required('input_building_area') && Narin::validate_integer('input_building_area', 0, false, null, true))) $invalid['input_building_area'] = true;
        if (isset($invalid)) return array('invalid' => $invalid, 'err_msg' => 'اطلاعات دریافت شده معتبر نیست.');
        if (isset($_POST['input_building_worth'])) {
            try {
                Database::execute_query("UPDATE `center-buildings` SET `title` = '" . Database::filter_str($_POST['input_building_title']) . "', `ground-area` = '" . Database::filter_str($_POST['input_ground_area']) . "', `building-area` = '" . Database::filter_str($_POST['input_building_area']) . "', `building-worth` = '" . Database::filter_str($_POST['input_building_worth']) . "', `built-date` = '" . Database::filter_str($_POST['input_built_date:y'] . '-' . $_POST['input_built_date:m'] . '-' . $_POST['input_built_date:d']) . "' WHERE `id` = '" . $_POST['buildingId'] . "';");
            } catch (exception $e) {
                return array('err_msg' => $e->getMessage());
            }
        } elseif (isset($_POST['input_rent_cost'])) {
            try {
                Database::execute_query("UPDATE `center-buildings` SET `title` = '" . Database::filter_str($_POST['input_building_title']) . "', `ground-area` = '" . Database::filter_str($_POST['input_ground_area']) . "', `building-area` = '" . Database::filter_str($_POST['input_building_area']) . "', `rent-cost` = '" . Database::filter_str($_POST['input_rent_cost']) . "' WHERE `id` = '" . $_POST['buildingId'] . "';");
            } catch (exception $e) {
                return array('err_msg' => $e->getMessage());
            }
        } else {
            try {
                Database::execute_query("UPDATE `center-buildings` SET `title` = '" . Database::filter_str($_POST['input_building_title']) . "', `ground-area` = '" . Database::filter_str($_POST['input_ground_area']) . "', `building-area` = '" . Database::filter_str($_POST['input_building_area']) . "' WHERE `id` = '" . $_POST['buildingId'] . "';");
            } catch (exception $e) {
                return array('err_msg' => $e->getMessage());
            }
        }
        $resI = Database::execute_query("SELECT `center-id` FROM `center-buildings` WHERE `id` = '" . Database::filter_str($_POST['buildingId']) . "';");
        $rowI = Database::get_assoc_array($resI);
        $resC = Database::execute_query("SELECT `name`, `town-id` FROM `centers` WHERE `id` = '" . $rowI['center-id'] . "';");
        $rowC = Database::get_assoc_array($resC);
        $res = Database::execute_query("SELECT `name` FROM `towns` WHERE `id` = '" . $rowC['town-id'] . "';");
        $row = Database::get_assoc_array($res);
        $_logTXT = 'اصلاح "' . $_POST['input_building_title'] . '" در ساختمان‌های مرکز "' . $rowC['name'] . '" در شهرستان "' . $row['name'] . '"';
        self::_makeLog($_logTXT);
        if (strpos($_SERVER['HTTP_REFERER'], 'ref')) {
            header('Location: ' . substr(strrchr($_SERVER['PHP_SELF'], '/'), 1) . '?es=manageCentersBuildings');
            exit;
        } else {
            header('Location: ' . substr(strrchr($_SERVER['PHP_SELF'], '/'), 1) . '?cmd=manageCenterBuildings');
            exit;
        }
    }

    public static function proccess_eash_AddCenterBuildingForm()
    {
        if ($_SERVER['REQUEST_METHOD'] != 'POST') return isset($_GET['success']) ? array('success' => true) : false;
        if (!Narin::check_token()) return array('err_msg' => 'به دلیل وجود یک مشکل امنیتی، اطلاعات ثبت نشد. لطفاً دوباره تلاش کنید.');
        if (!(isset($_POST['input_building_title']) && Narin::validate_required('input_building_title') && strlen($_POST['input_building_title']) <= 255)) $invalid['input_building_title'] = true;
        if (!(isset($_POST['input_ground_area']) && Narin::validate_required('input_ground_area') && Narin::validate_integer('input_ground_area', 0, false, null, true))) $invalid['input_ground_area'] = true;
        if (!(isset($_POST['input_building_area']) && Narin::validate_required('input_building_area') && Narin::validate_integer('input_building_area', 0, false, null, true))) $invalid['input_building_area'] = true;
        if (!(isset($_POST['input_ownership_type']))) $invalid['input_ownership_type'] = true;
        if (isset($_POST['input_ownership_type']) and $_POST['input_ownership_type'] == 1) {
            if (!(isset($_POST['input_rent_cost']) && Narin::validate_required('input_rent_cost') && Narin::validate_integer('input_rent_cost', 0, false, null, true))) $invalid['input_rent_cost'] = true;
        } else if (isset($_POST['input_ownership_type']) and $_POST['input_ownership_type'] == 0) {
            if (!isset($_POST['input_built_date:y'])) $invalid['input_built_date'] = true;
        }
        if (isset($invalid)) return array('invalid' => $invalid, 'err_msg' => 'اطلاعات دریافت شده معتبر نیست.');
        try {
            if ($_POST['input_ownership_type'] == 0) {
                Database::execute_query("INSERT INTO `center-buildings` (`title`, `center-id`, `ownership-type`, `ground-area`, `building-area`, `rent-cost`, `building-worth`, `built-date`) VALUES ('" . Database::filter_str($_POST['input_building_title']) . "', '" . Database::filter_str($_POST['centerId']) . "', '" . Database::filter_str($_POST['input_ownership_type']) . "', '" . Database::filter_str($_POST['input_ground_area']) . "', '" . Database::filter_str($_POST['input_building_area']) . "', NULL, '" . Database::filter_str($_POST['input_building_worth']) . "', '" . Database::filter_str($_POST['input_built_date:y'] . '-' . $_POST['input_built_date:m'] . '-' . $_POST['input_built_date:d']) . "');");
            } else if ($_POST['input_ownership_type'] == 1) {
                Database::execute_query("INSERT INTO `center-buildings` (`title`, `center-id`, `ownership-type`, `ground-area`, `building-area`, `rent-cost`, `building-worth`, `built-date`) VALUES ('" . Database::filter_str($_POST['input_building_title']) . "', '" . Database::filter_str($_POST['centerId']) . "', '" . Database::filter_str($_POST['input_ownership_type']) . "', '" . Database::filter_str($_POST['input_ground_area']) . "', '" . Database::filter_str($_POST['input_building_area']) . "', " . Database::filter_str($_POST['input_rent_cost']) . ", NULL, NULL);");
            } else if ($_POST['input_ownership_type'] == 2) {
                Database::execute_query("INSERT INTO `center-buildings` (`title`, `center-id`, `ownership-type`, `ground-area`, `building-area`, `rent-cost`, `building-worth`, `built-date`) VALUES ('" . Database::filter_str($_POST['input_building_title']) . "', '" . Database::filter_str($_POST['centerId']) . "', '" . Database::filter_str($_POST['input_ownership_type']) . "', '" . Database::filter_str($_POST['input_ground_area']) . "', '" . Database::filter_str($_POST['input_building_area']) . "', NULL, NULL, NULL);");
            }
        } catch (exception $e) {
            return array('err_msg' => $e->getMessage());
        }
        $resC = Database::execute_query("SELECT `name`, `town-id` FROM `centers` WHERE `id` = '" . Database::filter_str($_POST['centerId']) . "';");
        $rowC = Database::get_assoc_array($resC);
        $res = Database::execute_query("SELECT `name` FROM `towns` WHERE `id` = '" . $rowC['town-id'] . "';");
        $row = Database::get_assoc_array($res);
        $_logTXT = 'افزودن "' . $_POST['input_building_title'] . '" به ساختمان‌های مرکز "' . $rowC['name'] . '" در شهرستان "' . $row['name'] . '"';
        self::_makeLog($_logTXT);
        header('Location: ' . substr(strrchr($_SERVER['PHP_SELF'], '/'), 1) . '?success');
        exit;
    }

    public static function proccessAddCenterBuildingChargeForm()
    {
        if ($_SERVER['REQUEST_METHOD'] != 'POST') return isset($_GET['success']) ? array('success' => true) : false;
        if (!Narin::check_token()) return array('err_msg' => 'به دلیل وجود یک مشکل امنیتی، اطلاعات ثبت نشد. لطفاً دوباره تلاش کنید.');
        if (!(isset($_POST['input_charge_name']) && Narin::validate_required('input_charge_name') && strlen($_POST['input_charge_name']) <= 255)) $invalid['input_charge_name'] = true;
        if (!(isset($_POST['input_charge_amount']) && Narin::validate_required('input_charge_amount') && Narin::validate_integer('input_charge_amount', 0, false, null, true))) $invalid['input_charge_amount'] = true;
        if (!(isset($_POST['input_building_id']))) $invalid['input_building_id'] = true;
        if (isset($invalid)) return array('invalid' => $invalid, 'err_msg' => 'اطلاعات دریافت شده معتبر نیست.');
        $dateTimeObj = new DateTime();
        $dateTimeZone = new DateTimeZone('Asia/Tehran');
        $dateTimeObj->setTimezone($dateTimeZone);
        $shamsiDate = gregorian_to_jalali($dateTimeObj->format('Y'), $dateTimeObj->format('m'), $dateTimeObj->format('d'));
        $date = $shamsiDate[0] . "-" . $shamsiDate[1] . "-" . $shamsiDate[2] . " " . $dateTimeObj->format('H:i:s');
        try {
            Database::execute_query("INSERT INTO `center-building-maintain-charges` (`id`, `building-id`, `charge-name`, `charge-amount`, `date`) VALUES ('', '" . Database::filter_str($_POST['input_building_id']) . "', '" . Database::filter_str($_POST['input_charge_name']) . "', '" . Database::filter_str($_POST['input_charge_amount']) . "', '$date');");
        } catch (exception $e) {
            return array('err_msg' => $e->getMessage());
        }
        $resI = Database::execute_query("SELECT `title`, `center-id` FROM `center-buildings` WHERE `id` = '" . Database::filter_str($_POST['input_building_id']) . "';");
        $rowI = Database::get_assoc_array($resI);
        $resC = Database::execute_query("SELECT `name`, `town-id` FROM `centers` WHERE `id` = '" . $rowI['center-id'] . "';");
        $rowC = Database::get_assoc_array($resC);
        $res = Database::execute_query("SELECT `name` FROM `towns` WHERE `id` = '" . $rowC['town-id'] . "';");
        $row = Database::get_assoc_array($res);
        $_logTXT = 'افزودن هزینه "' . $_POST['input_charge_name'] . '" به ساختمان "' . $rowI['title'] . '" در مرکز "' . $rowC['name'] . '" در شهرستان "' . $row['name'] . '"';
        self::_makeLog($_logTXT);
        header('Location: ' . substr(strrchr($_SERVER['PHP_SELF'], '/'), 1) . '?success');
        exit;
    }

    public static function proccessEditCenterBuildingChargeForm()
    {
        if ($_SERVER['REQUEST_METHOD'] != 'POST') return isset($_GET['success']) ? array('success' => true) : false;
        if (!Narin::check_token()) return array('err_msg' => 'به دلیل وجود یک مشکل امنیتی، اطلاعات ثبت نشد. لطفاً دوباره تلاش کنید.');
        if (!(isset($_POST['input_charge_name']) && Narin::validate_required('input_charge_name') && strlen($_POST['input_charge_name']) <= 255)) $invalid['input_charge_name'] = true;
        if (!(isset($_POST['input_charge_amount']) && Narin::validate_required('input_charge_amount') && Narin::validate_integer('input_charge_amount', 0, false, null, true))) $invalid['input_charge_amount'] = true;
        if (isset($invalid)) return array('invalid' => $invalid, 'err_msg' => 'اطلاعات دریافت شده معتبر نیست.');
        try {
            Database::execute_query("UPDATE `center-building-maintain-charges` SET `charge-name` = '" . Database::filter_str($_POST['input_charge_name']) . "', `charge-amount` = '" . Database::filter_str($_POST['input_charge_amount']) . "' WHERE `id` = '" . $_POST['id'] . "';");
        } catch (exception $e) {
            return array('err_msg' => $e->getMessage());
        }
        $resG = Database::execute_query("SELECT `building-id` FROM `center-building-maintain-charges` WHERE `id` = '" . Database::filter_str($_POST['id']) . "';");
        $rowG = Database::get_assoc_array($resG);
        $resI = Database::execute_query("SELECT `title`, `center-id` FROM `center-buildings` WHERE `id` = '" . $rowG['building-id'] . "';");
        $rowI = Database::get_assoc_array($resI);
        $resC = Database::execute_query("SELECT `name`, `town-id` FROM `centers` WHERE `id` = '" . $rowI['center-id'] . "';");
        $rowC = Database::get_assoc_array($resC);
        $res = Database::execute_query("SELECT `name` FROM `towns` WHERE `id` = '" . $rowC['town-id'] . "';");
        $row = Database::get_assoc_array($res);
        $_logTXT = 'اصلاح هزینه "' . $_POST['input_charge_name'] . '" در ساختمان "' . $rowI['title'] . '" در مرکز "' . $rowC['name'] . '" در شهرستان "' . $row['name'] . '"';
        self::_makeLog($_logTXT);
        if ($_SESSION['ref'] == 1) {
            unset($_SESSION['ref']);
            header('Location: ' . substr(strrchr($_SERVER['PHP_SELF'], '/'), 1) . '?es=manageCentersBuildingsCharge');
            exit;
        } else {
            unset($_SESSION['ref']);
            header('Location: ' . substr(strrchr($_SERVER['PHP_SELF'], '/'), 1) . '?cmd=addCenterBuildingCharge');
            exit;
        }
    }

    public static function processEditCenterVehicleForm()
    {
        if ($_SERVER['REQUEST_METHOD'] != 'POST') return isset($_GET['success']) ? array('success' => true) : false;
        if (!Narin::check_token()) return array('err_msg' => 'به دلیل وجود یک مشکل امنیتی، اطلاعات ثبت نشد. لطفاً دوباره تلاش کنید.');
        if (!(isset($_POST['input_vehicle_title']) && Narin::validate_required('input_vehicle_title') && strlen($_POST['input_vehicle_title']) <= 255)) $invalid['input_vehicle_title'] = true;
        if (!(isset($_POST['input_vehicle_type']) && Narin::validate_required('input_vehicle_type') && strlen($_POST['input_vehicle_type']) <= 255)) $invalid['input_vehicle_type'] = true;
        if (isset($invalid)) return array('invalid' => $invalid, 'err_msg' => 'اطلاعات دریافت شده معتبر نیست.');
        if (isset($_POST['input_vehicle_worth'])) {
            try {
                Database::execute_query("UPDATE `center-vehicles` SET `title` = '" . Database::filter_str($_POST['input_vehicle_title']) . "', `vehicle-type` = '" . Database::filter_str($_POST['input_vehicle_type']) . "', `vehicle-worth` = '" . Database::filter_str($_POST['input_vehicle_worth']) . "', `buy-date` = '" . Database::filter_str($_POST['input_buy_date:y'] . '-' . $_POST['input_buy_date:m'] . '-' . $_POST['input_buy_date:d']) . "' WHERE `id` = '" . $_POST['vehicleId'] . "';");
            } catch (exception $e) {
                return array('err_msg' => $e->getMessage());
            }
        } elseif (isset($_POST['input_rent_cost'])) {
            try {
                Database::execute_query("UPDATE `center-vehicles` SET `title` = '" . Database::filter_str($_POST['input_vehicle_title']) . "', `vehicle-type` = '" . Database::filter_str($_POST['input_vehicle_type']) . "', `rent-cost` = '" . Database::filter_str($_POST['input_rent_cost']) . "' WHERE `id` = '" . $_POST['vehicleId'] . "';");
            } catch (exception $e) {
                return array('err_msg' => $e->getMessage());
            }
        }
        $resI = Database::execute_query("SELECT `center-id` FROM `center-vehicles` WHERE `id` = '" . Database::filter_str($_POST['vehicleId']) . "';");
        $rowI = Database::get_assoc_array($resI);
        $resC = Database::execute_query("SELECT `name`, `town-id` FROM `centers` WHERE `id` = '" . $rowI['center-id'] . "';");
        $rowC = Database::get_assoc_array($resC);
        $res = Database::execute_query("SELECT `name` FROM `towns` WHERE `id` = '" . $rowC['town-id'] . "';");
        $row = Database::get_assoc_array($res);
        $_logTXT = 'اصلاح "' . $_POST['input_vehicle_title'] . '" در وسایل نقلیه‌ی مرکز "' . $rowC['name'] . '" در شهرستان "' . $row['name'] . '"';
        self::_makeLog($_logTXT);
        if (strpos($_SERVER['HTTP_REFERER'], 'ref')) {
            unset($_SESSION['ref']);
            header('Location: ' . substr(strrchr($_SERVER['PHP_SELF'], '/'), 1) . '?es=manageCentersVehicles');
            exit;
        } else {
            unset($_SESSION['ref']);
            header('Location: ' . substr(strrchr($_SERVER['PHP_SELF'], '/'), 1) . '?cmd=manageCenterVehicles');
            exit;
        }
    }

    public static function proccessAddCenterVehicleForm()
    {
        if ($_SERVER['REQUEST_METHOD'] != 'POST') return isset($_GET['success']) ? array('success' => true) : false;
        if (!Narin::check_token()) return array('err_msg' => 'به دلیل وجود یک مشکل امنیتی، اطلاعات ثبت نشد. لطفاً دوباره تلاش کنید.');
        if (!(isset($_POST['input_vehicle_title']) && Narin::validate_required('input_vehicle_title') && strlen($_POST['input_vehicle_title']) <= 255)) $invalid['input_vehicle_title'] = true;
        if (!(isset($_POST['input_vehicle_type']) && Narin::validate_required('input_vehicle_type') && strlen($_POST['input_vehicle_type']) <= 255)) $invalid['input_vehicle_type'] = true;
        if (isset($_POST['input_vehicle_ownership_type']) and $_POST['input_vehicle_ownership_type'] == 0) {
            if (!isset($_POST['input_buy_date:y'])) $invalid['input_buy_date'] = true;
        }
        if (isset($_POST['input_vehicle_ownership_type']) and $_POST['input_vehicle_ownership_type'] == 1) {
            if (!(isset($_POST['input_rent_cost']) && Narin::validate_required('input_rent_cost') && Narin::validate_integer('input_rent_cost', 0, false, null, true))) $invalid['input_rent_cost'] = true;
        }
        if (isset($invalid)) return array('invalid' => $invalid, 'err_msg' => 'اطلاعات دریافت شده معتبر نیست.');
        try {
            if ($_POST['input_vehicle_ownership_type'] == 0) Database::execute_query("INSERT INTO `center-vehicles` (`id`, `center-id`, `title`, `ownership-type`, `vehicle-type`, `rent-cost`, `vehicle-worth`, `buy-date`) VALUES (NULL, '" . $_SESSION['centerId'] . "', '" . Database::filter_str($_POST['input_vehicle_title']) . "', '" . Database::filter_str($_POST['input_vehicle_ownership_type']) . "', '" . Database::filter_str($_POST['input_vehicle_type']) . "', NULL, '" . ( int )Database::filter_str($_POST['input_vehicle_worth']) . "', '" . Database::filter_str($_POST['input_buy_date:y'] . '-' . $_POST['input_buy_date:m'] . '-' . $_POST['input_buy_date:d']) . "');"); else if ($_POST['input_vehicle_ownership_type'] == 1) Database::execute_query("INSERT INTO `center-vehicles` (`id`, `center-id`, `title`, `ownership-type`, `vehicle-type`, `rent-cost`, `vehicle-worth`, `buy-date`) VALUES (NULL, '" . $_SESSION['centerId'] . "', '" . Database::filter_str($_POST['input_vehicle_title']) . "', '" . Database::filter_str($_POST['input_vehicle_ownership_type']) . "', '" . Database::filter_str($_POST['input_vehicle_type']) . "', '" . Database::filter_str($_POST['input_rent_cost']) . "', NULL, NULL);");
        } catch (exception $e) {
            return array('err_msg' => $e->getMessage());
        }
        $resC = Database::execute_query("SELECT `name`, `town-id` FROM `centers` WHERE `id` = '" . Database::filter_str($_SESSION['centerId']) . "';");
        $rowC = Database::get_assoc_array($resC);
        $res = Database::execute_query("SELECT `name` FROM `towns` WHERE `id` = '" . $rowC['town-id'] . "';");
        $row = Database::get_assoc_array($res);
        $_logTXT = 'افزودن "' . $_POST['input_vehicle_title'] . '" به وسایل نقلیه مرکز "' . $rowC['name'] . '" در شهرستان "' . $row['name'] . '"';
        self::_makeLog($_logTXT);
        header('Location: ' . substr(strrchr($_SERVER['PHP_SELF'], '/'), 1) . '?success');
        exit;
    }

    public static function proccess_eash_AddCenterVehicleForm()
    {
        if ($_SERVER['REQUEST_METHOD'] != 'POST') return isset($_GET['success']) ? array('success' => true) : false;
        if (!Narin::check_token()) return array('err_msg' => 'به دلیل وجود یک مشکل امنیتی، اطلاعات ثبت نشد. لطفاً دوباره تلاش کنید.');
        if (!(isset($_POST['input_vehicle_title']) && Narin::validate_required('input_vehicle_title') && strlen($_POST['input_vehicle_title']) <= 255)) $invalid['input_vehicle_title'] = true;
        if (!(isset($_POST['input_vehicle_type']) && Narin::validate_required('input_vehicle_type') && strlen($_POST['input_vehicle_type']) <= 255)) $invalid['input_vehicle_type'] = true;
        if (isset($_POST['input_vehicle_ownership_type']) and $_POST['input_vehicle_ownership_type'] == 0) {
            if (!isset($_POST['input_buy_date:y'])) $invalid['input_buy_date'] = true;
        }
        if (isset($_POST['input_vehicle_ownership_type']) and $_POST['input_vehicle_ownership_type'] == 1) {
            if (!(isset($_POST['input_rent_cost']) && Narin::validate_required('input_rent_cost') && Narin::validate_integer('input_rent_cost', 0, false, null, true))) $invalid['input_rent_cost'] = true;
        }
        if (isset($invalid)) return array('invalid' => $invalid, 'err_msg' => 'اطلاعات دریافت شده معتبر نیست.');
        try {
            if ($_POST['input_vehicle_ownership_type'] == 0) Database::execute_query("INSERT INTO `center-vehicles` (`id`, `center-id`, `title`, `ownership-type`, `vehicle-type`, `rent-cost`, `vehicle-worth`, `buy-date`) VALUES (NULL, '" . Database::filter_str($_POST['centerId']) . "', '" . Database::filter_str($_POST['input_vehicle_title']) . "', '" . Database::filter_str($_POST['input_vehicle_ownership_type']) . "', '" . Database::filter_str($_POST['input_vehicle_type']) . "', NULL, '" . ( int )Database::filter_str($_POST['input_vehicle_worth']) . "', '" . Database::filter_str($_POST['input_buy_date:y'] . '-' . $_POST['input_buy_date:m'] . '-' . $_POST['input_buy_date:d']) . "');"); else if ($_POST['input_vehicle_ownership_type'] == 1) Database::execute_query("INSERT INTO `center-vehicles` (`id`, `center-id`, `title`, `ownership-type`, `vehicle-type`, `rent-cost`, `vehicle-worth`, `buy-date`) VALUES (NULL, '" . Database::filter_str($_POST['centerId']) . "', '" . Database::filter_str($_POST['input_vehicle_title']) . "', '" . Database::filter_str($_POST['input_vehicle_ownership_type']) . "', '" . Database::filter_str($_POST['input_vehicle_type']) . "', '" . Database::filter_str($_POST['input_rent_cost']) . "', NULL, NULL);");
        } catch (exception $e) {
            return array('err_msg' => $e->getMessage());
        }
        $resC = Database::execute_query("SELECT `name`, `town-id` FROM `centers` WHERE `id` = '" . Database::filter_str($_POST['centerId']) . "';");
        $rowC = Database::get_assoc_array($resC);
        $res = Database::execute_query("SELECT `name` FROM `towns` WHERE `id` = '" . $rowC['town-id'] . "';");
        $row = Database::get_assoc_array($res);
        $_logTXT = 'افزودن "' . $_POST['input_vehicle_title'] . '" به وسایل نقلیه مرکز "' . $rowC['name'] . '" در شهرستان "' . $row['name'] . '"';
        self::_makeLog($_logTXT);
        header('Location: ' . substr(strrchr($_SERVER['PHP_SELF'], '/'), 1) . '?success');
        exit;
    }

    public static function proccessAddCenterVehicleChargeForm()
    {
        if ($_SERVER['REQUEST_METHOD'] != 'POST') return isset($_GET['success']) ? array('success' => true) : false;
        if (!Narin::check_token()) return array('err_msg' => 'به دلیل وجود یک مشکل امنیتی، اطلاعات ثبت نشد. لطفاً دوباره تلاش کنید.');
        if (!(isset($_POST['input_charge_name']) && Narin::validate_required('input_charge_name') && strlen($_POST['input_charge_name']) <= 255)) $invalid['input_charge_name'] = true;
        if (!(isset($_POST['input_charge_amount']) && Narin::validate_required('input_charge_amount') && Narin::validate_integer('input_charge_amount', 0, false, null, true))) $invalid['input_charge_amount'] = true;
        if (!(isset($_POST['input_vehicle_id']))) $invalid['input_vehicle_id'] = true;
        if (isset($invalid)) return array('invalid' => $invalid, 'err_msg' => 'اطلاعات دریافت شده معتبر نیست.');
        $dateTimeObj = new DateTime();
        $dateTimeZone = new DateTimeZone('Asia/Tehran');
        $dateTimeObj->setTimezone($dateTimeZone);
        $shamsiDate = gregorian_to_jalali($dateTimeObj->format('Y'), $dateTimeObj->format('m'), $dateTimeObj->format('d'));
        $date = $shamsiDate[0] . "-" . $shamsiDate[1] . "-" . $shamsiDate[2] . " " . $dateTimeObj->format('H:i:s');
        try {
            Database::execute_query("INSERT INTO `center-vehicle-maintain-charges` (`id`, `vehicle-id`, `charge-name`, `charge-amount`, `date`) VALUES (NULL, '" . Database::filter_str($_POST['input_vehicle_id']) . "', '" . Database::filter_str($_POST['input_charge_name']) . "', '" . ( int )Database::filter_str($_POST['input_charge_amount']) . "', '$date');");
            unset($dateTimeObj, $dateTimeZone, $shamsiDate, $date);
        } catch (exception $e) {
            return array('err_msg' => $e->getMessage());
        }
        $resI = Database::execute_query("SELECT `title`, `center-id` FROM `center-vehicles` WHERE `id` = '" . Database::filter_str($_POST['input_vehicle_id']) . "';");
        $rowI = Database::get_assoc_array($resI);
        $resC = Database::execute_query("SELECT `name`, `town-id` FROM `centers` WHERE `id` = '" . $rowI['center-id'] . "';");
        $rowC = Database::get_assoc_array($resC);
        $res = Database::execute_query("SELECT `name` FROM `towns` WHERE `id` = '" . $rowC['town-id'] . "';");
        $row = Database::get_assoc_array($res);
        $_logTXT = 'افزودن هزینه "' . $_POST['input_charge_name'] . '" به وسیله نقلیه "' . $rowI['title'] . '" در مرکز "' . $rowC['name'] . '" در شهرستان "' . $row['name'] . '"';
        self::_makeLog($_logTXT);
        header('Location: ' . substr(strrchr($_SERVER['PHP_SELF'], '/'), 1) . '?success');
        exit;
    }

    public static function proccessEditCenterVehicleChargeForm()
    {
        if ($_SERVER['REQUEST_METHOD'] != 'POST') return isset($_GET['success']) ? array('success' => true) : false;
        if (!Narin::check_token()) return array('err_msg' => 'به دلیل وجود یک مشکل امنیتی، اطلاعات ثبت نشد. لطفاً دوباره تلاش کنید.');
        if (!(isset($_POST['input_charge_name']) && Narin::validate_required('input_charge_name') && strlen($_POST['input_charge_name']) <= 255)) $invalid['input_charge_name'] = true;
        if (!(isset($_POST['input_charge_amount']) && Narin::validate_required('input_charge_amount') && Narin::validate_integer('input_charge_amount', 0, false, null, true))) $invalid['input_charge_amount'] = true;
        if (isset($invalid)) return array('invalid' => $invalid, 'err_msg' => 'اطلاعات دریافت شده معتبر نیست.');
        try {
            Database::execute_query("UPDATE `center-vehicle-maintain-charges` SET `charge-name` = '" . Database::filter_str($_POST['input_charge_name']) . "', `charge-amount` = '" . Database::filter_str($_POST['input_charge_amount']) . "' WHERE `id` = '" . $_POST['id'] . "';");
        } catch (exception $e) {
            return array('err_msg' => $e->getMessage());
        }
        $resG = Database::execute_query("SELECT `vehicle-id` FROM `center-vehicle-maintain-charges` WHERE `id` = '" . Database::filter_str($_POST['id']) . "';");
        $rowG = Database::get_assoc_array($resG);
        $resI = Database::execute_query("SELECT `title`, `center-id` FROM `center-vehicles` WHERE `id` = '" . $rowG['vehicle-id'] . "';");
        $rowI = Database::get_assoc_array($resI);
        $resC = Database::execute_query("SELECT `name`, `town-id` FROM `centers` WHERE `id` = '" . $rowI['center-id'] . "';");
        $rowC = Database::get_assoc_array($resC);
        $res = Database::execute_query("SELECT `name` FROM `towns` WHERE `id` = '" . $rowC['town-id'] . "';");
        $row = Database::get_assoc_array($res);
        $_logTXT = 'اصلاح هزینه "' . $_POST['input_charge_name'] . '" در وسیله نقلیه "' . $rowI['title'] . '" در مرکز "' . $rowC['name'] . '" در شهرستان "' . $row['name'] . '"';
        self::_makeLog($_logTXT);
        if ($_SESSION['ref'] == 1) {
            unset($_SESSION['ref']);
            header('Location: ' . substr(strrchr($_SERVER['PHP_SELF'], '/'), 1) . '?es=manageCentersVehiclesCharge');
            exit;
        } else {
            unset($_SESSION['ref']);
            header('Location: ' . substr(strrchr($_SERVER['PHP_SELF'], '/'), 1) . '?cmd=addCenterVehicleCharge');
            exit;
        }
    }

    public static function proccessAddGeneralChargeTypeForm()
    {
        if ($_SERVER['REQUEST_METHOD'] != 'POST') return isset($_GET['success']) ? array('success' => true) : false;
        if (!Narin::check_token()) return array('err_msg' => 'به دلیل وجود یک مشکل امنیتی، اطلاعات ثبت نشد. لطفاً دوباره تلاش کنید.');
        if (!(isset($_POST['input_charge_type']) && Narin::validate_required('input_charge_type') && strlen($_POST['input_charge_type']) <= 255)) $invalid['input_charge_type'] = true;
        if (!(isset($_POST['input_charge_type_cm']) && Narin::validate_farsi_text('input_charge_type_cm'))) $invalid['input_charge_type_cm'] = true;
        if (isset($invalid)) return array('invalid' => $invalid, 'err_msg' => 'اطلاعات دریافت شده معتبر نیست.');
        try {
            Database::execute_query("INSERT INTO `general-charge-types` (`type`, `comment`) VALUES ('" . Database::filter_str($_POST['input_charge_type']) . "', '" . Database::filter_str($_POST['input_charge_type_cm']) . "');");
        } catch (exception $e) {
            return array('err_msg' => $e->getMessage());
        }
        $_logTXT = 'تعریف "' . $_POST['input_charge_type'] . '" به عنوان هزینه عمومی';
        self::_makeLog($_logTXT);
        header('Location: ' . substr(strrchr($_SERVER['PHP_SELF'], '/'), 1) . '?success');
        exit;
    }

    public static function proccessEditGeneralChargeTypeForm()
    {
        if ($_SERVER['REQUEST_METHOD'] != 'POST') return isset($_GET['success']) ? array('success' => true) : false;
        if (!Narin::check_token()) return array('err_msg' => 'به دلیل وجود یک مشکل امنیتی، اطلاعات ثبت نشد. لطفاً دوباره تلاش کنید.');
        if (!(isset($_POST['input_charge_type']) && Narin::validate_required('input_charge_type') && strlen($_POST['input_charge_type']) <= 255)) $invalid['input_charge_type'] = true;
        if (!(isset($_POST['input_charge_type_cm']) && Narin::validate_farsi_text('input_charge_type_cm'))) $invalid['input_charge_type_cm'] = true;
        if (isset($invalid)) return array('invalid' => $invalid, 'err_msg' => 'اطلاعات دریافت شده معتبر نیست.');
        try {
            Database::execute_query("UPDATE `general-charge-types` SET `type` = '" . Database::filter_str($_POST['input_charge_type']) . "', `comment` = '" . Database::filter_str($_POST['input_charge_type_cm']) . "' WHERE `id` = '" . $_POST['chargeId'] . "';");
        } catch (exception $e) {
            return array('err_msg' => $e->getMessage());
        }
        $_logTXT = 'اصلاح تعریف "' . $_POST['input_charge_type'] . '" به عنوان هزینه عمومی';
        self::_makeLog($_logTXT);
        header('Location: ' . substr(strrchr($_SERVER['PHP_SELF'], '/'), 1) . '?cmd=manageGeneralChargesTypes');
        exit;
    }

    public static function proccessAddCenterGeneralChargeForm()
    {
        if ($_SERVER['REQUEST_METHOD'] != 'POST') return isset($_GET['success']) ? array('success' => true) : false;
        if (!Narin::check_token()) return array('err_msg' => 'به دلیل وجود یک مشکل امنیتی، اطلاعات ثبت نشد. لطفاً دوباره تلاش کنید.');
        if (!(isset($_POST['input_charge_name']))) $invalid['input_charge_name'] = true;
        if (!(isset($_POST['input_charge_amount']) && Narin::validate_required('input_charge_amount') && Narin::validate_integer('input_charge_amount', 0, false, null, true))) $invalid['input_charge_amount'] = true;
        if (!(isset($_POST['input_charge_cm']))) $invalid['input_charge_cm'] = true;
        if (isset($invalid)) return array('invalid' => $invalid, 'err_msg' => 'اطلاعات دریافت شده معتبر نیست.');
        try {
            Database::execute_query("INSERT INTO `center-general-charges` (`id`, `center-id`, `charge-id`, `charge-amount`, `comment`, `date`) VALUES (NULL, '" . Database::filter_str($_SESSION['centerId']) . "', '" . Database::filter_str($_POST['input_charge_name']) . "', '" . ( int )Database::filter_str($_POST['input_charge_amount']) . "', '" . Database::filter_str($_POST['input_charge_cm']) . "', '" . $_POST['input_date'] . "')");
        } catch (exception $e) {
            return array('err_msg' => $e->getMessage());
        }
        $resU = Database::execute_query("SELECT `type` FROM `general-charge-types` WHERE `id` = '" . Database::filter_str($_POST['input_charge_name']) . "';");
        $rowU = Database::get_assoc_array($resU);
        $resC = Database::execute_query("SELECT `name`, `town-id` FROM `centers` WHERE `id` = '" . Database::filter_str($_SESSION['centerId']) . "';");
        $rowC = Database::get_assoc_array($resC);
        $res = Database::execute_query("SELECT `name` FROM `towns` WHERE `id` = '" . $rowC['town-id'] . "';");
        $row = Database::get_assoc_array($res);
        $_logTXT = 'ثبت هزینه عمومی "' . $rowU['type'] . '" به تاریخ "' . $_POST['input_date'] . '" در مرکز "' . $rowC['name'] . '" در شهرستان "' . $row['name'] . '"';
        self::_makeLog($_logTXT);
        header('Location: ' . substr(strrchr($_SERVER['PHP_SELF'], '/'), 1) . '?success');
        exit;
    }

    public static function proccessEditCenterGeneralChargeForm()
    {
        if ($_SERVER['REQUEST_METHOD'] != 'POST') return isset($_GET['success']) ? array('success' => true) : false;
        if (!Narin::check_token()) return array('err_msg' => 'به دلیل وجود یک مشکل امنیتی، اطلاعات ثبت نشد. لطفاً دوباره تلاش کنید.');
        if (!(isset($_POST['input_charge_amount']) && Narin::validate_required('input_charge_amount') && Narin::validate_integer('input_charge_amount', 0, false, null, true))) $invalid['input_charge_amount'] = true;
        if (!(isset($_POST['input_charge_cm']))) $invalid['input_charge_cm'] = true;
        if (isset($invalid)) return array('invalid' => $invalid, 'err_msg' => 'اطلاعات دریافت شده معتبر نیست.');
        try {
            Database::execute_query("UPDATE `center-general-charges` SET `charge-amount` = '" . ( int )Database::filter_str($_POST['input_charge_amount']) . "', `comment` = '" . Database::filter_str($_POST['input_charge_cm']) . "', `date` = '" . $_POST['input_date'] . "' WHERE `id` = " . $_POST['chargeId'] . ";");
        } catch (exception $e) {
            return array('err_msg' => $e->getMessage());
        }
        $resI = Database::execute_query("SELECT `center-id`, `charge-id`, `date` FROM `center-general-charges` WHERE `id` = '" . Database::filter_str($_POST['chargeId']) . "';");
        $rowI = Database::get_assoc_array($resI);
        $resG = Database::execute_query("SELECT `type` FROM `general-charge-types` WHERE `id` = '" . $rowI['charge-id'] . "';");
        $rowG = Database::get_assoc_array($resG);
        $resC = Database::execute_query("SELECT `name`, `town-id` FROM `centers` WHERE `id` = '" . $rowI['center-id'] . "';");
        $rowC = Database::get_assoc_array($resC);
        $res = Database::execute_query("SELECT `name` FROM `towns` WHERE `id` = '" . $rowC['town-id'] . "';");
        $row = Database::get_assoc_array($res);
        $_logTXT = 'اصلاح هزینه عمومی "' . $rowG['type'] . '" به تاریخ "' . $rowI['date'] . '" در مرکز "' . $rowC['name'] . '" در شهرستان "' . $row['name'] . '"';
        self::_makeLog($_logTXT);
        if ($_SESSION['ref'] == 1) {
            unset($_SESSION['ref']);
            header('Location: ' . substr(strrchr($_SERVER['PHP_SELF'], '/'), 1) . '?es=manageCentersGeneralCharges');
            exit;
        } else {
            unset($_SESSION['ref']);
            header('Location: ' . substr(strrchr($_SERVER['PHP_SELF'], '/'), 1) . '?cmd=addCenterGeneralCharge');
            exit;
        }
    }

    public static function proccess_eash_AddCenterGeneralChargeForm()
    {
        if ($_SERVER['REQUEST_METHOD'] != 'POST') return isset($_GET['success']) ? array('success' => true) : false;
        if (!Narin::check_token()) return array('err_msg' => 'به دلیل وجود یک مشکل امنیتی، اطلاعات ثبت نشد. لطفاً دوباره تلاش کنید.');
        if (!(isset($_POST['input_charge_name']))) $invalid['input_charge_name'] = true;
        if (!(isset($_POST['input_charge_amount']) && Narin::validate_required('input_charge_amount') && Narin::validate_integer('input_charge_amount', 0, false, null, true))) $invalid['input_charge_amount'] = true;
        if (!(isset($_POST['input_charge_cm']) && Narin::validate_farsi_text('input_charge_cm'))) $invalid['input_charge_cm'] = true;
        if (isset($invalid)) return array('invalid' => $invalid, 'err_msg' => 'اطلاعات دریافت شده معتبر نیست.');
        try {
            Database::execute_query("INSERT INTO `center-general-charges` (`id`, `center-id`, `charge-id`, `charge-amount`, `comment`, `date`) VALUES (NULL, '" . Database::filter_str($_POST['centerId']) . "', '" . Database::filter_str($_POST['input_charge_name']) . "', '" . ( int )Database::filter_str($_POST['input_charge_amount']) . "', '" . Database::filter_str($_POST['input_charge_cm']) . "', '" . Database::filter_str($_POST['input_date']) . "')");
        } catch (exception $e) {
            return array('err_msg' => $e->getMessage());
        }
        $resU = Database::execute_query("SELECT `type` FROM `general-charge-types` WHERE `id` = '" . Database::filter_str($_POST['input_charge_name']) . "';");
        $rowU = Database::get_assoc_array($resU);
        $resC = Database::execute_query("SELECT `name`, `town-id` FROM `centers` WHERE `id` = '" . Database::filter_str($_POST['centerId']) . "';");
        $rowC = Database::get_assoc_array($resC);
        $res = Database::execute_query("SELECT `name` FROM `towns` WHERE `id` = '" . $rowC['town-id'] . "';");
        $row = Database::get_assoc_array($res);
        $_logTXT = 'ثبت هزینه عمومی "' . $rowU['type'] . '" به تاریخ "' . $_POST['input_date'] . '" در مرکز "' . $rowC['name'] . '" در شهرستان "' . $row['name'] . '"';
        self::_makeLog($_logTXT);
        header('Location: ' . substr(strrchr($_SERVER['PHP_SELF'], '/'), 1) . '?success');
        exit;
    }

    public static function proccessRegisterServicesForm()
    {
        if ($_SERVER['REQUEST_METHOD'] != 'POST') return isset($_GET['success']) ? array('success' => true) : false;
        if (!Narin::check_token()) return array('err_msg' => 'به دلیل وجود یک مشکل امنیتی، اطلاعات ثبت نشد. لطفاً دوباره تلاش کنید.');
        if (!(isset($_POST['input_service_id']))) $invalid['input_service_id'] = true;
        if (!(isset($_POST['input_service_frequency']) && Narin::validate_required('input_service_frequency') && Narin::validate_integer('input_service_frequency', 0, false, null, true) && strlen($_POST['input_service_frequency']) <= 6)) $invalid['input_service_frequency'] = true;
        if (!(isset($_POST['start_date:d']) && isset($_POST['start_date:m']) && isset($_POST['start_date:y']) && Narin::validate_date('start_date') && Narin::validate_required_date('start_date'))) $invalid['start_date'] = true;
        if (!(isset($_POST['finish_date:d']) && isset($_POST['finish_date:m']) && isset($_POST['finish_date:y']) && Narin::validate_date('finish_date') && Narin::validate_required_date('finish_date'))) $invalid['finish_date'] = true;
        if (isset($invalid)) return array('invalid' => $invalid, 'err_msg' => 'اطلاعات دریافت شده معتبر نیست.');
        if (trim($_SESSION['hygieneUnit']) == '') {
            try {
                Database::execute_query("INSERT INTO `unit-done-services` (`id`, `unit-id`, `unit-service-id`, `service-frequency`, `period-start-date`, `period-finish-date`) VALUES (NULL, '', '', '', '', '');");
            } catch (exception $e) {
                return array('err_msg' => $e->getMessage());
            }
        } else {
            try {
                Database::execute_query("INSERT INTO `hygiene-unit-done-services` (`id`, `hygiene-unit-id`, `hygiene-unit-service-id`, `service-frequency`, `period-start-date`, `period-finish-date`) VALUES (NULL, '', '', '', '', '');");
            } catch (exception $e) {
                return array('err_msg' => $e->getMessage());
            }
        }
        header('Location: ' . substr(strrchr($_SERVER['PHP_SELF'], '/'), 1) . '?success');
        exit;
    }

    public static function proccessRegisterUnitPersonnelCost()
    {
        if ($_SERVER['REQUEST_METHOD'] != 'POST') return isset($_GET['success']) ? array('success' => true) : false;
        if (!Narin::check_token()) return array('err_msg' => 'به دلیل وجود یک مشکل امنیتی، اطلاعات ثبت نشد. لطفاً دوباره تلاش کنید.');
        if (!(isset($_POST['input_title']))) $invalid['input_title'] = true;
        if (!(isset($_POST['input_cost']) && Narin::validate_required('input_cost') && Narin::validate_integer('input_cost', 0, false, null, true))) $invalid['input_cost'] = true;
        if (isset($invalid)) return array('invalid' => $invalid, 'err_msg' => 'اطلاعات دریافت شده معتبر نیست.');
        $dateTimeObj = new DateTime();
        $dateTimeZone = new DateTimeZone('Asia/Tehran');
        $dateTimeObj->setTimezone($dateTimeZone);
        $shamsiDate = gregorian_to_jalali($dateTimeObj->format('Y'), $dateTimeObj->format('m'), $dateTimeObj->format('d'));
        $date = $shamsiDate[0] . "-" . $shamsiDate[1] . "-" . $shamsiDate[2] . " " . $dateTimeObj->format('H:i:s');
        try {
            Database::execute_query("INSERT INTO `unit-personnel-financial` (`id`, `personnel-id`, `charge-type`, `charge-cost`, `date`) VALUES (NULL, '" . Database::filter_str($_POST['personnelId']) . "', '" . Database::filter_str($_POST['input_title']) . "', '" . Database::filter_str($_POST['input_cost']) . "', '$date');");
            unset($dateTimeObj, $dateTimeZone, $date, $shamsiDate);
        } catch (exception $e) {
            return array('err_msg' => $e->getMessage());
        }
        try {
            $resP = Database::execute_query("SELECT `unit-id`, `name`, `lastname` FROM `unit-personnels` WHERE `id` = '" . Database::filter_str($_POST['personnelId']) . "';");
            $rowP = Database::get_assoc_array($resP);
            $resU = Database::execute_query("SELECT `name`, `center-id` FROM `units` WHERE `id` = '" . $rowP['unit-id'] . "';");
            $rowU = Database::get_assoc_array($resU);
            $resC = Database::execute_query("SELECT `name`, `town-id` FROM `centers` WHERE `id` = '" . $rowU['center-id'] . "';");
            $rowC = Database::get_assoc_array($resC);
            $res = Database::execute_query("SELECT `name` FROM `towns` WHERE `id` = '" . $rowC['town-id'] . "';");
            $row = Database::get_assoc_array($res);
        } catch (exception $e) {
            return array('err_msg' => $e->getMessage());
        }
        $chargeType = '';
        switch ($_POST['input_title']) {
            case 'additional-charge' :
                $chargeType = 'مزایا';
                break;
            case 'extra-work-salary' :
                $chargeType = 'اضافه کاری';
                break;
            case 'gift-cost' :
                $chargeType = 'عیدی و پاداش';
                break;
            case 'mission-income' :
                $chargeType = 'ماموریت';
                break;
            case 'clothes-cost' :
                $chargeType = 'لباس';
                break;
            case 'insurance-cost' :
                $chargeType = 'بیمه';
                break;
            case 'vacation-left' :
                $chargeType = 'ذخیره ی مرخصی و پاداش بازنشستگی';
                break;
            case 'other' :
                $chargeType = 'سایر';
                break;
        }
        $_logTXT = 'ثبت مبلغ "' . $_POST['input_cost'] . '" به عنوان "' . $chargeType . '" برای پرسنل به‌نام "' . $rowP['name'] . ' ' . $rowP['lastname'] . '" در واحد "' . $rowU['name'] . '" در مرکز "' . $rowC['name'] . '" در شهرستان "' . $row['name'] . '"';
        self::_makeLog($_logTXT);
        header('Location: ' . substr(strrchr($_SERVER['PHP_SELF'], '/'), 1) . '?success');
        exit;
    }

    public static function proccessRegisterBehvarzCost()
    {
        if ($_SERVER['REQUEST_METHOD'] != 'POST') return isset($_GET['success']) ? array('success' => true) : false;
        if (!Narin::check_token()) return array('err_msg' => 'به دلیل وجود یک مشکل امنیتی، اطلاعات ثبت نشد. لطفاً دوباره تلاش کنید.');
        if (!(isset($_POST['input_title']))) $invalid['input_title'] = true;
        if (!(isset($_POST['input_cost']) && Narin::validate_required('input_cost') && Narin::validate_integer('input_cost', 0, false, null, true))) $invalid['input_cost'] = true;
        if (isset($invalid)) return array('invalid' => $invalid, 'err_msg' => 'اطلاعات دریافت شده معتبر نیست.');
        try {
            $dateTimeObj = new DateTime();
            $dateTimeZone = new DateTimeZone('Asia/Tehran');
            $dateTimeObj->setTimezone($dateTimeZone);
            $shamsiDate = gregorian_to_jalali($dateTimeObj->format('Y'), $dateTimeObj->format('m'), $dateTimeObj->format('d'));
            $date = $shamsiDate[0] . "-" . $shamsiDate[1] . "-" . $shamsiDate[2] . " " . $dateTimeObj->format('H:i:s');
            Database::execute_query("INSERT INTO `hygiene-unit-personnel-financial` (`id`, `personnel-id`, `charge-type`, `charge-cost`, `date`) VALUES (NULL, '" . Database::filter_str($_POST['personnelId']) . "', '" . Database::filter_str($_POST['input_title']) . "', '" . Database::filter_str($_POST['input_cost']) . "', '$date');");
        } catch (exception $e) {
            return array('err_msg' => $e->getMessage());
        }
        try {
            $resP = Database::execute_query("SELECT `hygiene-unit-id`, `name`, `lastname` FROM `hygiene-unit-personnels` WHERE `id` = '" . Database::filter_str($_POST['personnelId']) . "';");
            $rowP = Database::get_assoc_array($resP);
            $resU = Database::execute_query("SELECT `name`, `center-id` FROM `hygiene-units` WHERE `id` = '" . $rowP['hygiene-unit-id'] . "';");
            $rowU = Database::get_assoc_array($resU);
            $resC = Database::execute_query("SELECT `name`, `town-id` FROM `centers` WHERE `id` = '" . $rowU['center-id'] . "';");
            $rowC = Database::get_assoc_array($resC);
            $res = Database::execute_query("SELECT `name` FROM `towns` WHERE `id` = '" . $rowC['town-id'] . "';");
            $row = Database::get_assoc_array($res);
        } catch (exception $e) {
            return array('err_msg' => $e->getMessage());
        }
        $chargeType = '';
        switch ($_POST['input_title']) {
            case 'additional-charge' :
                $chargeType = 'مزایا';
                break;
            case 'extra-work-salary' :
                $chargeType = 'اضافه کاری';
                break;
            case 'gift-cost' :
                $chargeType = 'عیدی و پاداش';
                break;
            case 'mission-income' :
                $chargeType = 'ماموریت';
                break;
            case 'clothes-cost' :
                $chargeType = 'لباس';
                break;
            case 'insurance-cost' :
                $chargeType = 'بیمه';
                break;
            case 'vacation-left' :
                $chargeType = 'ذخیره ی مرخصی و پاداش بازنشستگی';
                break;
            case 'other' :
                $chargeType = 'سایر';
                break;
        }
        $_logTXT = 'ثبت مبلغ "' . $_POST['input_cost'] . '" به عنوان "' . $chargeType . '" برای بهورز به‌نام "' . $rowP['name'] . ' ' . $rowP['lastname'] . '" در خانه‌بهداشت "' . $rowU['name'] . '" در مرکز "' . $rowC['name'] . '" در شهرستان "' . $row['name'] . '"';
        self::_makeLog($_logTXT);
        header('Location: ' . substr(strrchr($_SERVER['PHP_SELF'], '/'), 1) . '?success');
        exit;
    }

    public static function proccessAddHygieneUnitDrugsAndConsumingEquipmentsForm()
    {
        if ($_SERVER['REQUEST_METHOD'] != 'POST') return isset($_GET['success']) ? array('success' => true) : false;
        if (!Narin::check_token()) return array('err_msg' => 'به دلیل وجود یک مشکل امنیتی، اطلاعات ثبت نشد. لطفاً دوباره تلاش کنید.');
        if (!(isset($_POST['input_drug_name']))) $invalid['input_drug_name'] = true;
        if (!(isset($_POST['input_drug_num']) && Narin::validate_required('input_drug_num') && Narin::validate_integer('input_drug_num', 0, false, null, true) && strlen($_POST['input_drug_num']) <= 6)) $invalid['input_drug_num'] = true;
        if (!(isset($_POST['input_drug_cm']))) $invalid['input_drug_cm'] = true;
        if (isset($invalid)) return array('invalid' => $invalid, 'err_msg' => 'اطلاعات دریافت شده معتبر نیست.');
        $dateTimeObj = new DateTime();
        $dateTimeZone = new DateTimeZone('Asia/Tehran');
        $dateTimeObj->setTimezone($dateTimeZone);
        $shamsiDate = gregorian_to_jalali($dateTimeObj->format('Y'), $dateTimeObj->format('m'), $dateTimeObj->format('d'));
        $date = $shamsiDate[0] . "-" . $shamsiDate[1] . "-" . $shamsiDate[2];
        try {
            $res = Database::execute_query("SELECT MAX(`id`) FROM `available-drugs-cost` WHERE `drug-id` = '" . Database::filter_str($_POST['input_drug_name']) . "'");
            $row = Database::get_assoc_array($res);
            Database::execute_query("INSERT INTO `hygiene-unit-drugs` (`id`, `drug-cost-id`, `num-of-drugs`, `hygiene-unit-id`, `comment`, `date`) VALUES (NULL, '" . $row['MAX(`id`)'] . "', '" . Database::filter_str($_POST['input_drug_num']) . "', '" . Database::filter_str($_SESSION['hygieneUnitId']) . "', '" . Database::filter_str($_POST['input_drug_cm']) . "', '$date');");
            unset($dateTimeObj, $dateTimeZone, $date, $shamsiDate);
        } catch (exception $e) {
            return array('err_msg' => $e->getMessage());
        }
        try {
            $resU = Database::execute_query("SELECT `name` FROM `available-drugs-and-consuming-equipments` WHERE `id` = '" . Database::filter_str($_POST['input_drug_name']) . "';");
            $rowU = Database::get_assoc_array($resU);
            $resH = Database::execute_query("SELECT `name`, `center-id` FROM `hygiene-units` WHERE `id` = '" . Database::filter_str($_SESSION['hygieneUnitId']) . "';");
            $rowH = Database::get_assoc_array($resH);
            $resC = Database::execute_query("SELECT `name`, `town-id` FROM `centers` WHERE `id` = '" . $rowH['center-id'] . "';");
            $rowC = Database::get_assoc_array($resC);
            $res = Database::execute_query("SELECT `name` FROM `towns` WHERE `id` = '" . $rowC['town-id'] . "';");
            $row = Database::get_assoc_array($res);
        } catch (exception $e) {
            return array('err_msg' => $e->getMessage());
        }
        $_logTXT = 'افزودن "' . $rowU['name'] . '" به تعداد "' . Database::filter_str($_POST['input_drug_num']) . '" به خانه‌بهداشت "' . $rowH['name'] . '" در مرکز "' . $rowC['name'] . '" در شهرستان "' . $row['name'] . '"';
        self::_makeLog($_logTXT);
        header('Location: ' . substr(strrchr($_SERVER['PHP_SELF'], '/'), 1) . '?success');
        exit;
    }

    public static function proccess_eash_AddHygieneUnitDrugsAndConsumingEquipmentsForm()
    {
        if ($_SERVER['REQUEST_METHOD'] != 'POST') return isset($_GET['success']) ? array('success' => true) : false;
        if (!Narin::check_token()) return array('err_msg' => 'به دلیل وجود یک مشکل امنیتی، اطلاعات ثبت نشد. لطفاً دوباره تلاش کنید.');
        if (!(isset($_POST['input_drug_name']))) $invalid['input_drug_name'] = true;
        if (!(isset($_POST['input_drug_num']) && Narin::validate_required('input_drug_num') && Narin::validate_integer('input_drug_num', 0, false, null, true) && strlen($_POST['input_drug_num']) <= 6)) $invalid['input_drug_num'] = true;
        if (!(isset($_POST['input_drug_cm']))) $invalid['input_drug_cm'] = true;
        if (isset($invalid)) return array('invalid' => $invalid, 'err_msg' => 'اطلاعات دریافت شده معتبر نیست.');
        $dateTimeObj = new DateTime();
        $dateTimeZone = new DateTimeZone('Asia/Tehran');
        $dateTimeObj->setTimezone($dateTimeZone);
        $shamsiDate = gregorian_to_jalali($dateTimeObj->format('Y'), $dateTimeObj->format('m'), $dateTimeObj->format('d'));
        $date = $shamsiDate[0] . "-" . $shamsiDate[1] . "-" . $shamsiDate[2];
        try {
            $res = Database::execute_query("SELECT MAX(`id`) FROM `available-drugs-cost` WHERE `drug-id` = '" . Database::filter_str($_POST['input_drug_name']) . "'");
            $row = Database::get_assoc_array($res);
            Database::execute_query("INSERT INTO `hygiene-unit-drugs` (`id`, `drug-cost-id`, `num-of-drugs`, `hygiene-unit-id`, `comment`, `date`) VALUES (NULL, '" . $row['MAX(`id`)'] . "', '" . Database::filter_str($_POST['input_drug_num']) . "', '" . Database::filter_str($_POST['hygieneUnitId']) . "', '" . Database::filter_str($_POST['input_drug_cm']) . "', '$date');");
            unset($dateTimeObj, $dateTimeZone, $date, $shamsiDate);
        } catch (exception $e) {
            return array('err_msg' => $e->getMessage());
        }
        try {
            $resU = Database::execute_query("SELECT `name` FROM `available-drugs-and-consuming-equipments` WHERE `id` = '" . Database::filter_str($_POST['input_drug_name']) . "';");
            $rowU = Database::get_assoc_array($resU);
            $resH = Database::execute_query("SELECT `name`, `center-id` FROM `hygiene-units` WHERE `id` = '" . Database::filter_str($_POST['hygieneUnitId']) . "';");
            $rowH = Database::get_assoc_array($resH);
            $resC = Database::execute_query("SELECT `name`, `town-id` FROM `centers` WHERE `id` = '" . $rowH['center-id'] . "';");
            $rowC = Database::get_assoc_array($resC);
            $res = Database::execute_query("SELECT `name` FROM `towns` WHERE `id` = '" . $rowC['town-id'] . "';");
            $row = Database::get_assoc_array($res);
        } catch (exception $e) {
            return array('err_msg' => $e->getMessage());
        }
        $_logTXT = 'افزودن "' . $rowU['name'] . '" به تعداد "' . Database::filter_str($_POST['input_drug_num']) . '" به خانه‌بهداشت "' . $rowH['name'] . '" در مرکز "' . $rowC['name'] . '" در شهرستان "' . $row['name'] . '"';
        self::_makeLog($_logTXT);
        header('Location: ' . substr(strrchr($_SERVER['PHP_SELF'], '/'), 1) . '?success');
        exit;
    }

    public static function proccessAddHygieneUnitNonConsumingEquipmentsForm()
    {
        if ($_SERVER['REQUEST_METHOD'] != 'POST') return isset($_GET['success']) ? array('success' => true) : false;
        if (!Narin::check_token()) return array('err_msg' => 'به دلیل وجود یک مشکل امنیتی، اطلاعات ثبت نشد. لطفاً دوباره تلاش کنید.');
        if (!(isset($_POST['input_equip_id']))) $invalid['input_equip_id'] = true;
        if (!(isset($_POST['input_equip_nums']) && Narin::validate_required('input_equip_nums') && Narin::validate_integer('input_equip_nums', 0, false, null, true) && strlen($_POST['input_equip_nums']) <= 6)) $invalid['input_equip_nums'] = true;
        if (!(isset($_POST['input_equip_cm']))) $invalid['input_equip_cm'] = true;
        if (isset($invalid)) return array('invalid' => $invalid, 'err_msg' => 'اطلاعات دریافت شده معتبر نیست.');
        $date = $_POST['input_buy_date:y'] . '-' . $_POST['input_buy_date:m'] . '-' . $_POST['input_buy_date:d'];
        try {
            $res = Database::execute_query("SELECT MAX(`id`) FROM `available-non-consuming-cost` WHERE `equip-id` = '" . Database::filter_str($_POST['input_equip_id']) . "'");
            $row = Database::get_assoc_array($res);
            Database::execute_query("INSERT INTO `hygiene-unit-non-consuming-equipments` (`id`, `equip-cost-id`, `num-of-equips`, `hygiene-unit-id`, `comment`, `date`) VALUES (NULL, '" . $row['MAX(`id`)'] . "', '" . Database::filter_str($_POST['input_equip_nums']) . "', '" . Database::filter_str($_SESSION['hygieneUnitId']) . "', '" . Database::filter_str($_POST['input_equip_cm']) . "', '$date');");
        } catch (exception $e) {
            return array('err_msg' => $e->getMessage());
        }
        try {
            $resU = Database::execute_query("SELECT `name`, `mark` FROM `available-non-consuming-equipments` WHERE `id` = '" . Database::filter_str($_POST['input_equip_id']) . "';");
            $rowU = Database::get_assoc_array($resU);
            $resH = Database::execute_query("SELECT `name`, `center-id` FROM `hygiene-units` WHERE `id` = '" . Database::filter_str($_SESSION['hygieneUnitId']) . "';");
            $rowH = Database::get_assoc_array($resH);
            $resC = Database::execute_query("SELECT `name`, `town-id` FROM `centers` WHERE `id` = '" . $rowH['center-id'] . "';");
            $rowC = Database::get_assoc_array($resC);
            $res = Database::execute_query("SELECT `name` FROM `towns` WHERE `id` = '" . $rowC['town-id'] . "';");
            $row = Database::get_assoc_array($res);
        } catch (exception $e) {
            return array('err_msg' => $e->getMessage());
        }
        $_logTXT = 'افزودن "' . $rowU['name'] . '(' . $rowU['mark'] . ')" به تعداد "' . Database::filter_str($_POST['input_equip_nums']) . '" به خانه‌بهداشت "' . $rowH['name'] . '" در مرکز "' . $rowC['name'] . '" در شهرستان "' . $row['name'] . '"';
        self::_makeLog($_logTXT);
        header('Location: ' . substr(strrchr($_SERVER['PHP_SELF'], '/'), 1) . '?success');
        exit;
    }

    public static function proccess_eash_AddHygieneUnitNonConsumingEquipmentsForm()
    {
        if ($_SERVER['REQUEST_METHOD'] != 'POST') return isset($_GET['success']) ? array('success' => true) : false;
        if (!Narin::check_token()) return array('err_msg' => 'به دلیل وجود یک مشکل امنیتی، اطلاعات ثبت نشد. لطفاً دوباره تلاش کنید.');
        if (!(isset($_POST['input_equip_id']))) $invalid['input_equip_id'] = true;
        if (!(isset($_POST['input_equip_nums']) && Narin::validate_required('input_equip_nums') && Narin::validate_integer('input_equip_nums', 0, false, null, true) && strlen($_POST['input_equip_nums']) <= 6)) $invalid['input_equip_nums'] = true;
        if (!(isset($_POST['input_equip_cm']))) $invalid['input_equip_cm'] = true;
        if (isset($invalid)) return array('invalid' => $invalid, 'err_msg' => 'اطلاعات دریافت شده معتبر نیست.');
        $date = $_POST['input_buy_date:y'] . '-' . $_POST['input_buy_date:m'] . '-' . $_POST['input_buy_date:d'];
        try {
            $res = Database::execute_query("SELECT MAX(`id`) FROM `available-non-consuming-cost` WHERE `equip-id` = '" . Database::filter_str($_POST['input_equip_id']) . "'");
            $row = Database::get_assoc_array($res);
            Database::execute_query("INSERT INTO `hygiene-unit-non-consuming-equipments` (`id`, `equip-cost-id`, `num-of-equips`, `hygiene-unit-id`, `comment`, `date`) VALUES (NULL, '" . $row['MAX(`id`)'] . "', '" . Database::filter_str($_POST['input_equip_nums']) . "', '" . Database::filter_str($_POST['hygieneUnitId']) . "', '" . Database::filter_str($_POST['input_equip_cm']) . "', '$date');");
        } catch (exception $e) {
            return array('err_msg' => $e->getMessage());
        }
        try {
            $resU = Database::execute_query("SELECT `name`, `mark` FROM `available-non-consuming-equipments` WHERE `id` = '" . Database::filter_str($_POST['input_equip_id']) . "';");
            $rowU = Database::get_assoc_array($resU);
            $resH = Database::execute_query("SELECT `name`, `center-id` FROM `hygiene-units` WHERE `id` = '" . Database::filter_str($_POST['hygieneUnitId']) . "';");
            $rowH = Database::get_assoc_array($resH);
            $resC = Database::execute_query("SELECT `name`, `town-id` FROM `centers` WHERE `id` = '" . $rowH['center-id'] . "';");
            $rowC = Database::get_assoc_array($resC);
            $res = Database::execute_query("SELECT `name` FROM `towns` WHERE `id` = '" . $rowC['town-id'] . "';");
            $row = Database::get_assoc_array($res);
        } catch (exception $e) {
            return array('err_msg' => $e->getMessage());
        }
        $_logTXT = 'افزودن "' . $rowU['name'] . '(' . $rowU['mark'] . ')" به تعداد "' . Database::filter_str($_POST['input_equip_nums']) . '" به خانه‌بهداشت "' . $rowH['name'] . '" در مرکز "' . $rowC['name'] . '" در شهرستان "' . $row['name'] . '"';
        self::_makeLog($_logTXT);
        header('Location: ' . substr(strrchr($_SERVER['PHP_SELF'], '/'), 1) . '?success');
        exit;
    }

    public static function proccessAddHygieneUnitBuildingForm()
    {
        if ($_SERVER['REQUEST_METHOD'] != 'POST') return isset($_GET['success']) ? array('success' => true) : false;
        if (!Narin::check_token()) return array('err_msg' => 'به دلیل وجود یک مشکل امنیتی، اطلاعات ثبت نشد. لطفاً دوباره تلاش کنید.');
        if (!(isset($_POST['input_building_title']) && Narin::validate_required('input_building_title') && strlen($_POST['input_building_title']) <= 255)) $invalid['input_building_title'] = true;
        if (!(isset($_POST['input_ground_area']) && Narin::validate_required('input_ground_area') && Narin::validate_integer('input_ground_area', 0, false, null, true))) $invalid['input_ground_area'] = true;
        if (!(isset($_POST['input_building_area']) && Narin::validate_required('input_building_area') && Narin::validate_integer('input_building_area', 0, false, null, true))) $invalid['input_building_area'] = true;
        if (!(isset($_POST['input_ownership_type']))) $invalid['input_ownership_type'] = true;
        if (isset($_POST['input_ownership_type']) and $_POST['input_ownership_type'] == 1) {
            if (!(isset($_POST['input_rent_cost']) && Narin::validate_required('input_rent_cost') && Narin::validate_integer('input_rent_cost', 0, false, null, true))) $invalid['input_rent_cost'] = true;
        }
        if (isset($_POST['input_ownership_type']) and $_POST['input_ownership_type'] == 0) {
            if (!isset($_POST['input_built_date:y'])) $invalid['input_built_date'] = true;
        }
        if (isset($invalid)) return array('invalid' => $invalid, 'err_msg' => 'اطلاعات دریافت شده معتبر نیست.');
        try {
            if ($_POST['input_ownership_type'] == 0) {
                Database::execute_query("INSERT INTO `hygiene-unit-buildings` (`title`, `hygiene-unit-id`, `ownership-type`, `ground-area`, `building-area`, `rent-cost`, `building-worth`, `built-date`) VALUES ('" . Database::filter_str($_POST['input_building_title']) . "', '" . Database::filter_str($_SESSION['hygieneUnitId']) . "', '" . Database::filter_str($_POST['input_ownership_type']) . "', '" . Database::filter_str($_POST['input_ground_area']) . "', '" . Database::filter_str($_POST['input_building_area']) . "', NULL, '" . Database::filter_str($_POST['input_building_worth']) . "', '" . Database::filter_str($_POST['input_built_date:y'] . '-' . $_POST['input_built_date:m'] . '-' . $_POST['input_built_date:d']) . "');");
            } else if ($_POST['input_ownership_type'] == 1) {
                Database::execute_query("INSERT INTO `hygiene-unit-buildings` (`title`, `hygiene-unit-id`, `ownership-type`, `ground-area`, `building-area`, `rent-cost`, `building-worth`, `built-date`) VALUES ('" . Database::filter_str($_POST['input_building_title']) . "', '" . Database::filter_str($_SESSION['hygieneUnitId']) . "', '" . Database::filter_str($_POST['input_ownership_type']) . "', '" . Database::filter_str($_POST['input_ground_area']) . "', '" . Database::filter_str($_POST['input_building_area']) . "', " . Database::filter_str($_POST['input_rent_cost']) . ", NULL, NULL);");
            } else if ($_POST['input_ownership_type'] == 2) {
                Database::execute_query("INSERT INTO `hygiene-unit-buildings` (`title`, `hygiene-unit-id`, `ownership-type`, `ground-area`, `building-area`, `rent-cost`, `building-worth`, `built-date`) VALUES ('" . Database::filter_str($_POST['input_building_title']) . "', '" . Database::filter_str($_SESSION['hygieneUnitId']) . "', '" . Database::filter_str($_POST['input_ownership_type']) . "', '" . Database::filter_str($_POST['input_ground_area']) . "', '" . Database::filter_str($_POST['input_building_area']) . "', NULL, NULL, NULL);");
            }
        } catch (exception $e) {
            return array('err_msg' => $e->getMessage());
        }
        try {
            $resH = Database::execute_query("SELECT `name`, `center-id` FROM `hygiene-units` WHERE `id` = '" . Database::filter_str($_SESSION['hygieneUnitId']) . "';");
            $rowH = Database::get_assoc_array($resH);
            $resC = Database::execute_query("SELECT `name`, `town-id` FROM `centers` WHERE `id` = '" . $rowH['center-id'] . "';");
            $rowC = Database::get_assoc_array($resC);
            $res = Database::execute_query("SELECT `name` FROM `towns` WHERE `id` = '" . $rowC['town-id'] . "';");
            $row = Database::get_assoc_array($res);
        } catch (exception $e) {
            return array('err_msg' => $e->getMessage());
        }
        $_logTXT = 'افزودن "' . $_POST['input_building_title'] . '" به ساختمان‌های خانه‌بهداشت "' . $rowH['name'] . '" در مرکز "' . $rowC['name'] . '" در شهرستان "' . $row['name'] . '"';
        self::_makeLog($_logTXT);
        header('Location: ' . substr(strrchr($_SERVER['PHP_SELF'], '/'), 1) . '?success');
        exit;
    }

    public static function proccessEditHygieneUnitBuildingForm()
    {
        if ($_SERVER['REQUEST_METHOD'] != 'POST') return isset($_GET['success']) ? array('success' => true) : false;
        if (!Narin::check_token()) return array('err_msg' => 'به دلیل وجود یک مشکل امنیتی، اطلاعات ثبت نشد. لطفاً دوباره تلاش کنید.');
        if (!(isset($_POST['input_building_title']) && Narin::validate_required('input_building_title') && strlen($_POST['input_building_title']) <= 255)) $invalid['input_building_title'] = true;
        if (!(isset($_POST['input_ground_area']) && Narin::validate_required('input_ground_area') && Narin::validate_integer('input_ground_area', 0, false, null, true))) $invalid['input_ground_area'] = true;
        if (!(isset($_POST['input_building_area']) && Narin::validate_required('input_building_area') && Narin::validate_integer('input_building_area', 0, false, null, true))) $invalid['input_building_area'] = true;
        if (isset($invalid)) return array('invalid' => $invalid, 'err_msg' => 'اطلاعات دریافت شده معتبر نیست.');
        if (isset($_POST['input_building_worth'])) {
            try {
                Database::execute_query("UPDATE `hygiene-unit-buildings` SET `title` = '" . Database::filter_str($_POST['input_building_title']) . "', `ground-area` = '" . Database::filter_str($_POST['input_ground_area']) . "', `building-area` = '" . Database::filter_str($_POST['input_building_area']) . "', `building-worth` = '" . Database::filter_str($_POST['input_building_worth']) . "', `built-date` = '" . Database::filter_str($_POST['input_built_date:y'] . '-' . $_POST['input_built_date:m'] . '-' . $_POST['input_built_date:d']) . "' WHERE `id` = '" . $_POST['buildingId'] . "';");
            } catch (exception $e) {
                return array('err_msg' => $e->getMessage());
            }
        } elseif (isset($_POST['input_rent_cost'])) {
            try {
                Database::execute_query("UPDATE `hygiene-unit-buildings` SET `title` = '" . Database::filter_str($_POST['input_building_title']) . "', `ground-area` = '" . Database::filter_str($_POST['input_ground_area']) . "', `building-area` = '" . Database::filter_str($_POST['input_building_area']) . "', `rent-cost` = '" . Database::filter_str($_POST['input_rent_cost']) . "' WHERE `id` = '" . $_POST['buildingId'] . "';");
            } catch (exception $e) {
                return array('err_msg' => $e->getMessage());
            }
        } else {
            try {
                Database::execute_query("UPDATE `hygiene-unit-buildings` SET `title` = '" . Database::filter_str($_POST['input_building_title']) . "', `ground-area` = '" . Database::filter_str($_POST['input_ground_area']) . "', `building-area` = '" . Database::filter_str($_POST['input_building_area']) . "' WHERE `id` = '" . $_POST['buildingId'] . "';");
            } catch (exception $e) {
                return array('err_msg' => $e->getMessage());
            }
        }
        try {
            $resI = Database::execute_query("SELECT `hygiene-unit-id` FROM `hygiene-unit-buildings` WHERE `id` = '" . Database::filter_str($_POST['buildingId']) . "';");
            $rowI = Database::get_assoc_array($resI);
            $resH = Database::execute_query("SELECT `name`, `center-id` FROM `hygiene-units` WHERE `id` = '" . $rowI['hygiene-unit-id'] . "';");
            $rowH = Database::get_assoc_array($resH);
            $resC = Database::execute_query("SELECT `name`, `town-id` FROM `centers` WHERE `id` = '" . $rowH['center-id'] . "';");
            $rowC = Database::get_assoc_array($resC);
            $res = Database::execute_query("SELECT `name` FROM `towns` WHERE `id` = '" . $rowC['town-id'] . "';");
            $row = Database::get_assoc_array($res);
        } catch (exception $e) {
            return array('err_msg' => $e->getMessage());
        }
        $_logTXT = 'اصلاح "' . $_POST['input_building_title'] . '" در ساختمان‌های خانه‌بهداشت "' . $rowH['name'] . '" در مرکز "' . $rowC['name'] . '" در شهرستان "' . $row['name'] . '"';
        self::_makeLog($_logTXT);
        if ($_SESSION['ref'] == 1) {
            unset($_SESSION['ref']);
            header('Location: ' . substr(strrchr($_SERVER['PHP_SELF'], '/'), 1) . '?es=manageHygieneUnitsBuildings');
            exit;
        } else {
            unset($_SESSION['ref']);
            header('Location: ' . substr(strrchr($_SERVER['PHP_SELF'], '/'), 1) . '?cmd=manageHygieneUnitBuildings');
            exit;
        }
    }

    public static function proccess_eash_AddHygieneUnitBuildingForm()
    {
        if ($_SERVER['REQUEST_METHOD'] != 'POST') return isset($_GET['success']) ? array('success' => true) : false;
        if (!Narin::check_token()) return array('err_msg' => 'به دلیل وجود یک مشکل امنیتی، اطلاعات ثبت نشد. لطفاً دوباره تلاش کنید.');
        if (!(isset($_POST['input_building_title']) && Narin::validate_required('input_building_title') && strlen($_POST['input_building_title']) <= 255)) $invalid['input_building_title'] = true;
        if (!(isset($_POST['input_ground_area']) && Narin::validate_required('input_ground_area') && Narin::validate_integer('input_ground_area', 0, false, null, true))) $invalid['input_ground_area'] = true;
        if (!(isset($_POST['input_building_area']) && Narin::validate_required('input_building_area') && Narin::validate_integer('input_building_area', 0, false, null, true))) $invalid['input_building_area'] = true;
        if (!(isset($_POST['input_ownership_type']))) $invalid['input_ownership_type'] = true;
        if (isset($_POST['input_ownership_type']) and $_POST['input_ownership_type'] == 1) {
            if (!(isset($_POST['input_rent_cost']) && Narin::validate_required('input_rent_cost') && Narin::validate_integer('input_rent_cost', 0, false, null, true))) $invalid['input_rent_cost'] = true;
        }
        if (isset($_POST['input_ownership_type']) and $_POST['input_ownership_type'] == 0) {
            if (!isset($_POST['input_built_date:y'])) $invalid['input_built_date'] = true;
        }
        if (isset($invalid)) return array('invalid' => $invalid, 'err_msg' => 'اطلاعات دریافت شده معتبر نیست.');
        try {
            if ($_POST['input_ownership_type'] == 0) {
                Database::execute_query("INSERT INTO `hygiene-unit-buildings` (`title`, `hygiene-unit-id`, `ownership-type`, `ground-area`, `building-area`, `rent-cost`, `building-worth`, `built-date`) VALUES ('" . Database::filter_str($_POST['input_building_title']) . "', '" . Database::filter_str($_POST['hygieneUnitId']) . "', '" . Database::filter_str($_POST['input_ownership_type']) . "', '" . Database::filter_str($_POST['input_ground_area']) . "', '" . Database::filter_str($_POST['input_building_area']) . "', NULL, '" . Database::filter_str($_POST['input_building_worth']) . "', '" . Database::filter_str($_POST['input_built_date:y'] . '-' . $_POST['input_built_date:m'] . '-' . $_POST['input_built_date:d']) . "');");
            } else if ($_POST['input_ownership_type'] == 1) {
                Database::execute_query("INSERT INTO `hygiene-unit-buildings` (`title`, `hygiene-unit-id`, `ownership-type`, `ground-area`, `building-area`, `rent-cost`, `building-worth`, `built-date`) VALUES ('" . Database::filter_str($_POST['input_building_title']) . "', '" . Database::filter_str($_POST['hygieneUnitId']) . "', '" . Database::filter_str($_POST['input_ownership_type']) . "', '" . Database::filter_str($_POST['input_ground_area']) . "', '" . Database::filter_str($_POST['input_building_area']) . "', " . Database::filter_str($_POST['input_rent_cost']) . ", NULL, NULL);");
            } else if ($_POST['input_ownership_type'] == 2) {
                Database::execute_query("INSERT INTO `hygiene-unit-buildings` (`title`, `hygiene-unit-id`, `ownership-type`, `ground-area`, `building-area`, `rent-cost`, `building-worth`, `built-date`) VALUES ('" . Database::filter_str($_POST['input_building_title']) . "', '" . Database::filter_str($_POST['hygieneUnitId']) . "', '" . Database::filter_str($_POST['input_ownership_type']) . "', '" . Database::filter_str($_POST['input_ground_area']) . "', '" . Database::filter_str($_POST['input_building_area']) . "', NULL, NULL, NULL);");
            }
        } catch (exception $e) {
            return array('err_msg' => $e->getMessage());
        }
        try {
            $resH = Database::execute_query("SELECT `name`, `center-id` FROM `hygiene-units` WHERE `id` = '" . Database::filter_str($_POST['hygieneUnitId']) . "';");
            $rowH = Database::get_assoc_array($resH);
            $resC = Database::execute_query("SELECT `name`, `town-id` FROM `centers` WHERE `id` = '" . $rowH['center-id'] . "';");
            $rowC = Database::get_assoc_array($resC);
            $res = Database::execute_query("SELECT `name` FROM `towns` WHERE `id` = '" . $rowC['town-id'] . "';");
            $row = Database::get_assoc_array($res);
        } catch (exception $e) {
            return array('err_msg' => $e->getMessage());
        }
        $_logTXT = 'افزودن "' . $_POST['input_building_title'] . '" به ساختمان‌های خانه‌بهداشت "' . $rowH['name'] . '" در مرکز "' . $rowC['name'] . '" در شهرستان "' . $row['name'] . '"';
        self::_makeLog($_logTXT);
        header('Location: ' . substr(strrchr($_SERVER['PHP_SELF'], '/'), 1) . '?success');
        exit;
    }

    public static function proccessAddHygieneUnitBuildingChargeForm()
    {
        if ($_SERVER['REQUEST_METHOD'] != 'POST') return isset($_GET['success']) ? array('success' => true) : false;
        if (!Narin::check_token()) return array('err_msg' => 'به دلیل وجود یک مشکل امنیتی، اطلاعات ثبت نشد. لطفاً دوباره تلاش کنید.');
        if (!(isset($_POST['input_charge_name']) && Narin::validate_required('input_charge_name') && strlen($_POST['input_charge_name']) <= 255)) $invalid['input_charge_name'] = true;
        if (!(isset($_POST['input_charge_amount']) && Narin::validate_required('input_charge_amount') && Narin::validate_integer('input_charge_amount', 0, false, null, true))) $invalid['input_charge_amount'] = true;
        if (!(isset($_POST['input_building_id']))) $invalid['input_building_id'] = true;
        if (isset($invalid)) return array('invalid' => $invalid, 'err_msg' => 'اطلاعات دریافت شده معتبر نیست.');
        $dateTimeObj = new DateTime();
        $dateTimeZone = new DateTimeZone('Asia/Tehran');
        $dateTimeObj->setTimezone($dateTimeZone);
        $shamsiDate = gregorian_to_jalali($dateTimeObj->format('Y'), $dateTimeObj->format('m'), $dateTimeObj->format('d'));
        $date = $shamsiDate[0] . "-" . $shamsiDate[1] . "-" . $shamsiDate[2] . " " . $dateTimeObj->format('H:i:s');
        try {
            Database::execute_query("INSERT INTO `hygiene-unit-building-maintain-charges` (`id`, `building-id`, `charge-name`, `charge-amount`, `date`) VALUES ('', '" . Database::filter_str($_POST['input_building_id']) . "', '" . Database::filter_str($_POST['input_charge_name']) . "', '" . Database::filter_str($_POST['input_charge_amount']) . "', '$date');");
            unset($dateTimeObj, $dateTimeZone, $shamsiDate, $date);
        } catch (exception $e) {
            return array('err_msg' => $e->getMessage());
        }
        $resI = Database::execute_query("SELECT `title`, `hygiene-unit-id` FROM `hygiene-unit-buildings` WHERE `id` = '" . Database::filter_str($_POST['input_building_id']) . "';");
        $rowI = Database::get_assoc_array($resI);
        $resH = Database::execute_query("SELECT `name`, `center-id` FROM `hygiene-units` WHERE `id` = '" . $rowI['hygiene-unit-id'] . "';");
        $rowH = Database::get_assoc_array($resH);
        $resC = Database::execute_query("SELECT `name`, `town-id` FROM `centers` WHERE `id` = '" . $rowH['center-id'] . "';");
        $rowC = Database::get_assoc_array($resC);
        $res = Database::execute_query("SELECT `name` FROM `towns` WHERE `id` = '" . $rowC['town-id'] . "';");
        $row = Database::get_assoc_array($res);
        $_logTXT = 'افزودن هزینه "' . $_POST['input_charge_name'] . '" به ساختمان "' . $rowI['title'] . '" در خانه‌بهداشت "' . $rowH['name'] . '" در مرکز "' . $rowC['name'] . '" در شهرستان "' . $row['name'] . '"';
        self::_makeLog($_logTXT);
        header('Location: ' . substr(strrchr($_SERVER['PHP_SELF'], '/'), 1) . '?success');
        exit;
    }

    public static function proccessEditHygieneUnitBuildingChargeForm()
    {
        if ($_SERVER['REQUEST_METHOD'] != 'POST') return isset($_GET['success']) ? array('success' => true) : false;
        if (!Narin::check_token()) return array('err_msg' => 'به دلیل وجود یک مشکل امنیتی، اطلاعات ثبت نشد. لطفاً دوباره تلاش کنید.');
        if (!(isset($_POST['input_charge_name']) && Narin::validate_required('input_charge_name') && strlen($_POST['input_charge_name']) <= 255)) $invalid['input_charge_name'] = true;
        if (!(isset($_POST['input_charge_amount']) && Narin::validate_required('input_charge_amount') && Narin::validate_integer('input_charge_amount', 0, false, null, true))) $invalid['input_charge_amount'] = true;
        if (isset($invalid)) return array('invalid' => $invalid, 'err_msg' => 'اطلاعات دریافت شده معتبر نیست.');
        try {
            Database::execute_query("UPDATE `hygiene-unit-building-maintain-charges` SET `charge-name` = '" . Database::filter_str($_POST['input_charge_name']) . "', `charge-amount` = '" . Database::filter_str($_POST['input_charge_amount']) . "' WHERE `id` = '" . $_POST['id'] . "';");
        } catch (exception $e) {
            return array('err_msg' => $e->getMessage());
        }
        $resG = Database::execute_query("SELECT `building-id` FROM `hygiene-unit-building-maintain-charges` WHERE `id` = '" . Database::filter_str($_POST['id']) . "';");
        $rowG = Database::get_assoc_array($resG);
        $resI = Database::execute_query("SELECT `title`, `hygiene-unit-id` FROM `hygiene-unit-buildings` WHERE `id` = '" . $rowG['building-id'] . "';");
        $rowI = Database::get_assoc_array($resI);
        $resH = Database::execute_query("SELECT `name`, `center-id` FROM `hygiene-units` WHERE `id` = '" . $rowI['hygiene-unit-id'] . "';");
        $rowH = Database::get_assoc_array($resH);
        $resC = Database::execute_query("SELECT `name`, `town-id` FROM `centers` WHERE `id` = '" . $rowH['center-id'] . "';");
        $rowC = Database::get_assoc_array($resC);
        $res = Database::execute_query("SELECT `name` FROM `towns` WHERE `id` = '" . $rowC['town-id'] . "';");
        $row = Database::get_assoc_array($res);
        $_logTXT = 'اصلاح هزینه "' . $_POST['input_charge_name'] . '" در ساختمان "' . $rowI['title'] . '" در خانه‌بهداشت "' . $rowH['name'] . '" در مرکز "' . $rowC['name'] . '" در شهرستان "' . $row['name'] . '"';
        self::_makeLog($_logTXT);
        if ($_SESSION['ref'] == 1) {
            unset($_SESSION['ref']);
            header('Location: ' . substr(strrchr($_SERVER['PHP_SELF'], '/'), 1) . '?es=manageHygieneUnitsBuildingsCharge');
            exit;
        } else {
            unset($_SESSION['ref']);
            header('Location: ' . substr(strrchr($_SERVER['PHP_SELF'], '/'), 1) . '?cmd=addHygieneUnitBuildingCharge');
            exit;
        }
    }

    public static function proccessAddHygieneUnitVehicleForm()
    {
        if ($_SERVER['REQUEST_METHOD'] != 'POST') return isset($_GET['success']) ? array('success' => true) : false;
        if (!Narin::check_token()) return array('err_msg' => 'به دلیل وجود یک مشکل امنیتی، اطلاعات ثبت نشد. لطفاً دوباره تلاش کنید.');
        if (!(isset($_POST['input_vehicle_title']) && Narin::validate_required('input_vehicle_title') && strlen($_POST['input_vehicle_title']) <= 255)) $invalid['input_vehicle_title'] = true;
        if (!(isset($_POST['input_vehicle_type']) && Narin::validate_required('input_vehicle_type') && strlen($_POST['input_vehicle_type']) <= 255)) $invalid['input_vehicle_type'] = true;
        if (isset($_POST['input_vehicle_ownership_type']) and $_POST['input_vehicle_ownership_type'] == 0) {
            if (!isset($_POST['input_buy_date:y'])) $invalid['input_buy_date'] = true;
        }
        if (isset($_POST['input_vehicle_ownership_type']) and $_POST['input_vehicle_ownership_type'] == 1) {
            if (!(isset($_POST['input_rent_cost']) && Narin::validate_required('input_rent_cost') && Narin::validate_integer('input_rent_cost', 0, false, null, true))) $invalid['input_rent_cost'] = true;
        }
        if (isset($invalid)) return array('invalid' => $invalid, 'err_msg' => 'اطلاعات دریافت شده معتبر نیست.');
        try {
            if ($_POST['input_vehicle_ownership_type'] == 0) Database::execute_query("INSERT INTO `hygiene-unit-vehicles` (`id`, `hygiene-unit-id`, `title`, `ownership-type`, `vehicle-type`, `rent-cost`, `vehicle-worth`, `buy-date`) VALUES (NULL, '" . $_SESSION['hygieneUnitId'] . "', '" . Database::filter_str($_POST['input_vehicle_title']) . "', '" . Database::filter_str($_POST['input_vehicle_ownership_type']) . "', '" . Database::filter_str($_POST['input_vehicle_type']) . "', NULL, '" . ( int )Database::filter_str($_POST['input_vehicle_worth']) . "', '" . Database::filter_str($_POST['input_buy_date:y'] . '-' . $_POST['input_buy_date:m'] . '-' . $_POST['input_buy_date:d']) . "');"); else if ($_POST['input_vehicle_ownership_type'] == 1) Database::execute_query("INSERT INTO `hygiene-unit-vehicles` (`id`, `hygiene-unit-id`, `title`, `ownership-type`, `vehicle-type`, `rent-cost`, `vehicle-worth`, `buy-date`) VALUES (NULL, '" . $_SESSION['hygieneUnitId'] . "', '" . Database::filter_str($_POST['input_vehicle_title']) . "', '" . Database::filter_str($_POST['input_vehicle_ownership_type']) . "', '" . Database::filter_str($_POST['input_vehicle_type']) . "', '" . Database::filter_str($_POST['input_rent_cost']) . "', NULL, NULL);");
        } catch (exception $e) {
            return array('err_msg' => $e->getMessage());
        }
        try {
            $resH = Database::execute_query("SELECT `name`, `center-id` FROM `hygiene-units` WHERE `id` = '" . Database::filter_str($_SESSION['hygieneUnitId']) . "';");
            $rowH = Database::get_assoc_array($resH);
            $resC = Database::execute_query("SELECT `name`, `town-id` FROM `centers` WHERE `id` = '" . $rowH['center-id'] . "';");
            $rowC = Database::get_assoc_array($resC);
            $res = Database::execute_query("SELECT `name` FROM `towns` WHERE `id` = '" . $rowC['town-id'] . "';");
            $row = Database::get_assoc_array($res);
        } catch (exception $e) {
            return array('err_msg' => $e->getMessage());
        }
        $_logTXT = 'افزودن "' . $_POST['input_vehicle_title'] . '" به وسایل نقلیه خانه‌بهداشت "' . $rowH['name'] . '" در مرکز "' . $rowC['name'] . '" در شهرستان "' . $row['name'] . '"';
        self::_makeLog($_logTXT);
        header('Location: ' . substr(strrchr($_SERVER['PHP_SELF'], '/'), 1) . '?success');
        exit;
    }

    public static function processEditHygieneUnitVehicleForm()
    {
        if ($_SERVER['REQUEST_METHOD'] != 'POST') return isset($_GET['success']) ? array('success' => true) : false;
        if (!Narin::check_token()) return array('err_msg' => 'به دلیل وجود یک مشکل امنیتی، اطلاعات ثبت نشد. لطفاً دوباره تلاش کنید.');
        if (!(isset($_POST['input_vehicle_title']) && Narin::validate_required('input_vehicle_title') && strlen($_POST['input_vehicle_title']) <= 255)) $invalid['input_vehicle_title'] = true;
        if (!(isset($_POST['input_vehicle_type']) && Narin::validate_required('input_vehicle_type') && strlen($_POST['input_vehicle_type']) <= 255)) $invalid['input_vehicle_type'] = true;
        if (isset($invalid)) return array('invalid' => $invalid, 'err_msg' => 'اطلاعات دریافت شده معتبر نیست.');
        if (isset($_POST['input_vehicle_worth'])) {
            try {
                Database::execute_query("UPDATE `hygiene-unit-vehicles` SET `title` = '" . Database::filter_str($_POST['input_vehicle_title']) . "', `vehicle-type` = '" . Database::filter_str($_POST['input_vehicle_type']) . "', `vehicle-worth` = '" . Database::filter_str($_POST['input_vehicle_worth']) . "', `buy-date` = '" . Database::filter_str($_POST['input_buy_date:y'] . '-' . $_POST['input_buy_date:m'] . '-' . $_POST['input_buy_date:d']) . "' WHERE `id` = '" . $_POST['vehicleId'] . "';");
            } catch (exception $e) {
                return array('err_msg' => $e->getMessage());
            }
        } elseif (isset($_POST['input_rent_cost'])) {
            try {
                Database::execute_query("UPDATE `hygiene-unit-vehicles` SET `title` = '" . Database::filter_str($_POST['input_vehicle_title']) . "', `vehicle-type` = '" . Database::filter_str($_POST['input_vehicle_type']) . "', `rent-cost` = '" . Database::filter_str($_POST['input_rent_cost']) . "' WHERE `id` = '" . $_POST['vehicleId'] . "';");
            } catch (exception $e) {
                return array('err_msg' => $e->getMessage());
            }
        }
        try {
            $resI = Database::execute_query("SELECT `hygiene-unit-id` FROM `hygiene-unit-vehicles` WHERE `id` = '" . Database::filter_str($_POST['vehicleId']) . "';");
            $rowI = Database::get_assoc_array($resI);
            $resH = Database::execute_query("SELECT `name`, `center-id` FROM `hygiene-units` WHERE `id` = '" . $rowI['hygiene-unit-id'] . "';");
            $rowH = Database::get_assoc_array($resH);
            $resC = Database::execute_query("SELECT `name`, `town-id` FROM `centers` WHERE `id` = '" . $rowH['center-id'] . "';");
            $rowC = Database::get_assoc_array($resC);
            $res = Database::execute_query("SELECT `name` FROM `towns` WHERE `id` = '" . $rowC['town-id'] . "';");
            $row = Database::get_assoc_array($res);
        } catch (exception $e) {
            return array('err_msg' => $e->getMessage());
        }
        $_logTXT = 'اصلاح "' . $_POST['input_vehicle_title'] . '" در وسایل نقلیه خانه‌بهداشت "' . $rowH['name'] . '" در مرکز "' . $rowC['name'] . '" در شهرستان "' . $row['name'] . '"';
        self::_makeLog($_logTXT);
        if ($_SESSION['ref'] == 1) {
            unset($_SESSION['ref']);
            header('Location: ' . substr(strrchr($_SERVER['PHP_SELF'], '/'), 1) . '?es=manageHygieneUnitsVehicles');
            exit;
        } else {
            unset($_SESSION['ref']);
            header('Location: ' . substr(strrchr($_SERVER['PHP_SELF'], '/'), 1) . '?cmd=manageHygieneUnitVehicles');
            exit;
        }
    }

    public static function proccess_eash_AddHygieneUnitVehicleForm()
    {
        if ($_SERVER['REQUEST_METHOD'] != 'POST') return isset($_GET['success']) ? array('success' => true) : false;
        if (!Narin::check_token()) return array('err_msg' => 'به دلیل وجود یک مشکل امنیتی، اطلاعات ثبت نشد. لطفاً دوباره تلاش کنید.');
        if (!(isset($_POST['input_vehicle_title']) && Narin::validate_required('input_vehicle_title') && strlen($_POST['input_vehicle_title']) <= 255)) $invalid['input_vehicle_title'] = true;
        if (!(isset($_POST['input_vehicle_type']) && Narin::validate_required('input_vehicle_type') && strlen($_POST['input_vehicle_type']) <= 255)) $invalid['input_vehicle_type'] = true;
        if (isset($_POST['input_vehicle_ownership_type']) and $_POST['input_vehicle_ownership_type'] == 0) {
            if (!isset($_POST['input_buy_date:y'])) $invalid['input_buy_date'] = true;
        }
        if (isset($_POST['input_vehicle_ownership_type']) and $_POST['input_vehicle_ownership_type'] == 1) {
            if (!(isset($_POST['input_rent_cost']) && Narin::validate_required('input_rent_cost') && Narin::validate_integer('input_rent_cost', 0, false, null, true))) $invalid['input_rent_cost'] = true;
        }
        if (isset($invalid)) return array('invalid' => $invalid, 'err_msg' => 'اطلاعات دریافت شده معتبر نیست.');
        try {
            if ($_POST['input_vehicle_ownership_type'] == 0) Database::execute_query("INSERT INTO `hygiene-unit-vehicles` (`id`, `hygiene-unit-id`, `title`, `ownership-type`, `vehicle-type`, `rent-cost`, `vehicle-worth`, `buy-date`) VALUES (NULL, '" . $_POST['hygieneUnitId'] . "', '" . Database::filter_str($_POST['input_vehicle_title']) . "', '" . Database::filter_str($_POST['input_vehicle_ownership_type']) . "', '" . Database::filter_str($_POST['input_vehicle_type']) . "', NULL, '" . ( int )Database::filter_str($_POST['input_vehicle_worth']) . "', '" . Database::filter_str($_POST['input_buy_date:y'] . '-' . $_POST['input_buy_date:m'] . '-' . $_POST['input_buy_date:d']) . "');"); else if ($_POST['input_vehicle_ownership_type'] == 1) Database::execute_query("INSERT INTO `hygiene-unit-vehicles` (`id`, `hygiene-unit-id`, `title`, `ownership-type`, `vehicle-type`, `rent-cost`, `vehicle-worth`, `buy-date`) VALUES (NULL, '" . $_POST['hygieneUnitId'] . "', '" . Database::filter_str($_POST['input_vehicle_title']) . "', '" . Database::filter_str($_POST['input_vehicle_ownership_type']) . "', '" . Database::filter_str($_POST['input_vehicle_type']) . "', '" . Database::filter_str($_POST['input_rent_cost']) . "', NULL, NULL);");
        } catch (exception $e) {
            return array('err_msg' => $e->getMessage());
        }
        try {
            $resH = Database::execute_query("SELECT `name`, `center-id` FROM `hygiene-units` WHERE `id` = '" . Database::filter_str($_POST['hygieneUnitId']) . "';");
            $rowH = Database::get_assoc_array($resH);
            $resC = Database::execute_query("SELECT `name`, `town-id` FROM `centers` WHERE `id` = '" . $rowH['center-id'] . "';");
            $rowC = Database::get_assoc_array($resC);
            $res = Database::execute_query("SELECT `name` FROM `towns` WHERE `id` = '" . $rowC['town-id'] . "';");
            $row = Database::get_assoc_array($res);
        } catch (exception $e) {
            return array('err_msg' => $e->getMessage());
        }
        $_logTXT = 'افزودن "' . $_POST['input_vehicle_title'] . '" به وسایل نقلیه خانه‌بهداشت "' . $rowH['name'] . '" در مرکز "' . $rowC['name'] . '" در شهرستان "' . $row['name'] . '"';
        self::_makeLog($_logTXT);
        header('Location: ' . substr(strrchr($_SERVER['PHP_SELF'], '/'), 1) . '?success');
        exit;
    }

    public static function proccessAddHygieneUnitVehicleChargeForm()
    {
        if ($_SERVER['REQUEST_METHOD'] != 'POST') return isset($_GET['success']) ? array('success' => true) : false;
        if (!Narin::check_token()) return array('err_msg' => 'به دلیل وجود یک مشکل امنیتی، اطلاعات ثبت نشد. لطفاً دوباره تلاش کنید.');
        if (!(isset($_POST['input_charge_name']) && Narin::validate_required('input_charge_name') && strlen($_POST['input_charge_name']) <= 255)) $invalid['input_charge_name'] = true;
        if (!(isset($_POST['input_charge_amount']) && Narin::validate_required('input_charge_amount') && Narin::validate_integer('input_charge_amount', 0, false, null, true))) $invalid['input_charge_amount'] = true;
        if (!(isset($_POST['input_vehicle_id']))) $invalid['input_vehicle_id'] = true;
        if (isset($invalid)) return array('invalid' => $invalid, 'err_msg' => 'اطلاعات دریافت شده معتبر نیست.');
        $dateTimeObj = new DateTime();
        $dateTimeZone = new DateTimeZone('Asia/Tehran');
        $dateTimeObj->setTimezone($dateTimeZone);
        $shamsiDate = gregorian_to_jalali($dateTimeObj->format('Y'), $dateTimeObj->format('m'), $dateTimeObj->format('d'));
        $date = $shamsiDate[0] . "-" . $shamsiDate[1] . "-" . $shamsiDate[2] . " " . $dateTimeObj->format('H:i:s');
        try {
            Database::execute_query("INSERT INTO `hygiene-unit-vehicle-maintain-charges` (`id`, `vehicle-id`, `charge-name`, `charge-amount`, `date`) VALUES (NULL, '" . Database::filter_str($_POST['input_vehicle_id']) . "', '" . Database::filter_str($_POST['input_charge_name']) . "', '" . ( int )Database::filter_str($_POST['input_charge_amount']) . "', '$date');");
            unset($dateTimeObj, $dateTimeZone, $shamsiDate, $date);
        } catch (exception $e) {
            return array('err_msg' => $e->getMessage());
        }
        $resI = Database::execute_query("SELECT `title`, `hygiene-unit-id` FROM `hygiene-unit-vehicles` WHERE `id` = '" . Database::filter_str($_POST['input_vehicle_id']) . "';");
        $rowI = Database::get_assoc_array($resI);
        $resH = Database::execute_query("SELECT `name`, `center-id` FROM `hygiene-units` WHERE `id` = '" . $rowI['hygiene-unit-id'] . "';");
        $rowH = Database::get_assoc_array($resH);
        $resC = Database::execute_query("SELECT `name`, `town-id` FROM `centers` WHERE `id` = '" . $rowH['center-id'] . "';");
        $rowC = Database::get_assoc_array($resC);
        $res = Database::execute_query("SELECT `name` FROM `towns` WHERE `id` = '" . $rowC['town-id'] . "';");
        $row = Database::get_assoc_array($res);
        $_logTXT = 'افزودن هزینه "' . $_POST['input_charge_name'] . '" به وسیله نقلیه "' . $rowI['title'] . '" در خانه‌بهداشت "' . $rowH['name'] . '" در مرکز "' . $rowC['name'] . '" در شهرستان "' . $row['name'] . '"';
        self::_makeLog($_logTXT);
        header('Location: ' . substr(strrchr($_SERVER['PHP_SELF'], '/'), 1) . '?success');
        exit;
    }

    public static function proccessEditHygieneUnitVehicleChargeForm()
    {
        if ($_SERVER['REQUEST_METHOD'] != 'POST') return isset($_GET['success']) ? array('success' => true) : false;
        if (!Narin::check_token()) return array('err_msg' => 'به دلیل وجود یک مشکل امنیتی، اطلاعات ثبت نشد. لطفاً دوباره تلاش کنید.');
        if (!(isset($_POST['input_charge_name']) && Narin::validate_required('input_charge_name') && strlen($_POST['input_charge_name']) <= 255)) $invalid['input_charge_name'] = true;
        if (!(isset($_POST['input_charge_amount']) && Narin::validate_required('input_charge_amount') && Narin::validate_integer('input_charge_amount', 0, false, null, true))) $invalid['input_charge_amount'] = true;
        if (isset($invalid)) return array('invalid' => $invalid, 'err_msg' => 'اطلاعات دریافت شده معتبر نیست.');
        try {
            Database::execute_query("UPDATE `hygiene-unit-vehicle-maintain-charges` SET `charge-name` = '" . Database::filter_str($_POST['input_charge_name']) . "', `charge-amount` = '" . Database::filter_str($_POST['input_charge_amount']) . "' WHERE `id` = '" . $_POST['id'] . "';");
        } catch (exception $e) {
            return array('err_msg' => $e->getMessage());
        }
        $resG = Database::execute_query("SELECT `vehicle-id` FROM `hygiene-unit-vehicle-maintain-charges` WHERE `id` = '" . Database::filter_str($_POST['id']) . "';");
        $rowG = Database::get_assoc_array($resG);
        $resI = Database::execute_query("SELECT `title`, `hygiene-unit-id` FROM `hygiene-unit-vehicles` WHERE `id` = '" . $rowG['vehicle-id'] . "';");
        $rowI = Database::get_assoc_array($resI);
        $resH = Database::execute_query("SELECT `name`, `center-id` FROM `hygiene-units` WHERE `id` = '" . $rowI['hygiene-unit-id'] . "';");
        $rowH = Database::get_assoc_array($resH);
        $resC = Database::execute_query("SELECT `name`, `town-id` FROM `centers` WHERE `id` = '" . $rowH['center-id'] . "';");
        $rowC = Database::get_assoc_array($resC);
        $res = Database::execute_query("SELECT `name` FROM `towns` WHERE `id` = '" . $rowC['town-id'] . "';");
        $row = Database::get_assoc_array($res);
        $_logTXT = 'اصلاح هزینه "' . $_POST['input_charge_name'] . '" در وسیله نقلیه "' . $rowI['title'] . '" در خانه‌بهداشت "' . $rowH['name'] . '" در مرکز "' . $rowC['name'] . '" در شهرستان "' . $row['name'] . '"';
        self::_makeLog($_logTXT);
        if ($_SESSION['ref'] == 1) {
            unset($_SESSION['ref']);
            header('Location: ' . substr(strrchr($_SERVER['PHP_SELF'], '/'), 1) . '?es=manageHygieneUnitsVehiclesCharge');
            exit;
        } else {
            unset($_SESSION['ref']);
            header('Location: ' . substr(strrchr($_SERVER['PHP_SELF'], '/'), 1) . '?cmd=addHygieneUnitVehicleCharge');
            exit;
        }
    }

    public static function proccessAddHygieneUnitGeneralChargeForm()
    {
        if ($_SERVER['REQUEST_METHOD'] != 'POST') return isset($_GET['success']) ? array('success' => true) : false;
        if (!Narin::check_token()) return array('err_msg' => 'به دلیل وجود یک مشکل امنیتی، اطلاعات ثبت نشد. لطفاً دوباره تلاش کنید.');
        if (!(isset($_POST['input_charge_name']))) $invalid['input_charge_name'] = true;
        if (!(isset($_POST['input_charge_amount']) && Narin::validate_required('input_charge_amount') && Narin::validate_integer('input_charge_amount', 0, false, null, true))) $invalid['input_charge_amount'] = true;
        if (!(isset($_POST['input_charge_cm']))) $invalid['input_charge_cm'] = true;
        if (isset($invalid)) return array('invalid' => $invalid, 'err_msg' => 'اطلاعات دریافت شده معتبر نیست.');
        try {
            Database::execute_query("INSERT INTO `hygiene-unit-general-charges` (`id`, `hygiene-unit-id`, `charge-id`, `charge-amount`, `comment`, `date`) VALUES (NULL, '" . Database::filter_str($_SESSION['hygieneUnitId']) . "', '" . Database::filter_str($_POST['input_charge_name']) . "', '" . ( int )Database::filter_str($_POST['input_charge_amount']) . "', '" . Database::filter_str($_POST['input_charge_cm']) . "', '" . $_POST['input_date'] . "')");
        } catch (exception $e) {
            return array('err_msg' => $e->getMessage());
        }
        try {
            $resU = Database::execute_query("SELECT `type` FROM `general-charge-types` WHERE `id` = '" . Database::filter_str($_POST['input_charge_name']) . "';");
            $rowU = Database::get_assoc_array($resU);
            $resH = Database::execute_query("SELECT `name`, `center-id` FROM `hygiene-units` WHERE `id` = '" . Database::filter_str($_SESSION['hygieneUnitId']) . "';");
            $rowH = Database::get_assoc_array($resH);
            $resC = Database::execute_query("SELECT `name`, `town-id` FROM `centers` WHERE `id` = '" . $rowH['center-id'] . "';");
            $rowC = Database::get_assoc_array($resC);
            $res = Database::execute_query("SELECT `name` FROM `towns` WHERE `id` = '" . $rowC['town-id'] . "';");
            $row = Database::get_assoc_array($res);
        } catch (exception $e) {
            return array('err_msg' => $e->getMessage());
        }
        $_logTXT = 'ثبت هزینه عمومی "' . $rowU['type'] . '" به تاریخ "' . $_POST['input_date'] . '" در خانه‌بهداشت "' . $rowH['name'] . '" در مرکز "' . $rowC['name'] . '" در شهرستان "' . $row['name'] . '"';
        self::_makeLog($_logTXT);
        header('Location: ' . substr(strrchr($_SERVER['PHP_SELF'], '/'), 1) . '?success');
        exit;
    }

    public static function proccessEditHygieneUnitGeneralChargeForm()
    {
        if ($_SERVER['REQUEST_METHOD'] != 'POST') return isset($_GET['success']) ? array('success' => true) : false;
        if (!Narin::check_token()) return array('err_msg' => 'به دلیل وجود یک مشکل امنیتی، اطلاعات ثبت نشد. لطفاً دوباره تلاش کنید.');
        if (!(isset($_POST['input_charge_amount']) && Narin::validate_required('input_charge_amount') && Narin::validate_integer('input_charge_amount', 0, false, null, true))) $invalid['input_charge_amount'] = true;
        if (!(isset($_POST['input_charge_cm']))) $invalid['input_charge_cm'] = true;
        if (isset($invalid)) return array('invalid' => $invalid, 'err_msg' => 'اطلاعات دریافت شده معتبر نیست.');
        try {
            Database::execute_query("UPDATE `hygiene-unit-general-charges` SET `charge-amount` = '" . ( int )Database::filter_str($_POST['input_charge_amount']) . "', `comment` = '" . Database::filter_str($_POST['input_charge_cm']) . "', `date` = '" . $_POST['input_date'] . "' WHERE `id` = " . $_POST['chargeId'] . ";");
        } catch (exception $e) {
            return array('err_msg' => $e->getMessage());
        }
        try {
            $resI = Database::execute_query("SELECT `hygiene-unit-id`, `charge-id`, `date` FROM `hygiene-unit-general-charges` WHERE `id` = '" . Database::filter_str($_POST['chargeId']) . "';");
            $rowI = Database::get_assoc_array($resI);
            $resU = Database::execute_query("SELECT `type` FROM `general-charge-types` WHERE `id` = '" . $rowI['charge-id'] . "';");
            $rowU = Database::get_assoc_array($resU);
            $resH = Database::execute_query("SELECT `name`, `center-id` FROM `hygiene-units` WHERE `id` = '" . $rowI['hygiene-unit-id'] . "';");
            $rowH = Database::get_assoc_array($resH);
            $resC = Database::execute_query("SELECT `name`, `town-id` FROM `centers` WHERE `id` = '" . $rowH['center-id'] . "';");
            $rowC = Database::get_assoc_array($resC);
            $res = Database::execute_query("SELECT `name` FROM `towns` WHERE `id` = '" . $rowC['town-id'] . "';");
            $row = Database::get_assoc_array($res);
        } catch (exception $e) {
            return array('err_msg' => $e->getMessage());
        }
        $_logTXT = 'اصلاح هزینه عمومی "' . $rowU['type'] . '" به تاریخ "' . $_POST['input_date'] . '" در خانه‌بهداشت "' . $rowH['name'] . '" در مرکز "' . $rowC['name'] . '" در شهرستان "' . $row['name'] . '"';
        self::_makeLog($_logTXT);
        if ($_SESSION['ref'] == 1) {
            unset($_SESSION['ref']);
            header('Location: ' . substr(strrchr($_SERVER['PHP_SELF'], '/'), 1) . '?es=manageHygieneUnitsGeneralCharges');
            exit;
        } else {
            unset($_SESSION['ref']);
            header('Location: ' . substr(strrchr($_SERVER['PHP_SELF'], '/'), 1) . '?cmd=addHygieneUnitGeneralCharge');
            exit;
        }
    }

    public static function proccess_eash_AddHygieneUnitGeneralChargeForm()
    {
        if ($_SERVER['REQUEST_METHOD'] != 'POST') return isset($_GET['success']) ? array('success' => true) : false;
        if (!Narin::check_token()) return array('err_msg' => 'به دلیل وجود یک مشکل امنیتی، اطلاعات ثبت نشد. لطفاً دوباره تلاش کنید.');
        if (!(isset($_POST['input_charge_name']))) $invalid['input_charge_name'] = true;
        if (!(isset($_POST['input_charge_amount']) && Narin::validate_required('input_charge_amount') && Narin::validate_integer('input_charge_amount', 0, false, null, true))) $invalid['input_charge_amount'] = true;
        if (!(isset($_POST['input_charge_cm']))) $invalid['input_charge_cm'] = true;
        if (isset($invalid)) return array('invalid' => $invalid, 'err_msg' => 'اطلاعات دریافت شده معتبر نیست.');
        try {
            Database::execute_query("INSERT INTO `hygiene-unit-general-charges` (`id`, `hygiene-unit-id`, `charge-id`, `charge-amount`, `comment`, `date`) VALUES (NULL, '" . Database::filter_str($_POST['hygieneUnitId']) . "', '" . Database::filter_str($_POST['input_charge_name']) . "', '" . ( int )Database::filter_str($_POST['input_charge_amount']) . "', '" . Database::filter_str($_POST['input_charge_cm']) . "', '" . $_POST['input_date'] . "')");
        } catch (exception $e) {
            return array('err_msg' => $e->getMessage());
        }
        try {
            $resU = Database::execute_query("SELECT `type` FROM `general-charge-types` WHERE `id` = '" . Database::filter_str($_POST['input_charge_name']) . "';");
            $rowU = Database::get_assoc_array($resU);
            $resH = Database::execute_query("SELECT `name`, `center-id` FROM `hygiene-units` WHERE `id` = '" . Database::filter_str($_POST['hygieneUnitId']) . "';");
            $rowH = Database::get_assoc_array($resH);
            $resC = Database::execute_query("SELECT `name`, `town-id` FROM `centers` WHERE `id` = '" . $rowH['center-id'] . "';");
            $rowC = Database::get_assoc_array($resC);
            $res = Database::execute_query("SELECT `name` FROM `towns` WHERE `id` = '" . $rowC['town-id'] . "';");
            $row = Database::get_assoc_array($res);
        } catch (exception $e) {
            return array('err_msg' => $e->getMessage());
        }
        $_logTXT = 'ثبت هزینه عمومی "' . $rowU['type'] . '" به تاریخ "' . $_POST['input_date'] . '" در خانه‌بهداشت "' . $rowH['name'] . '" در مرکز "' . $rowC['name'] . '" در شهرستان "' . $row['name'] . '"';
        self::_makeLog($_logTXT);
        header('Location: ' . substr(strrchr($_SERVER['PHP_SELF'], '/'), 1) . '?success');
        exit;
    }

    public static function proccessRegisterDoneServicesForm()
    {
        if ($_SERVER['REQUEST_METHOD'] != 'POST') return isset($_GET['success']) ? array('success' => true) : false;
        if (!Narin::check_token()) return array('err_msg' => 'به دلیل وجود یک مشکل امنیتی، اطلاعات ثبت نشد. لطفاً دوباره تلاش کنید.');
        if(!isset($_POST['unit_type'])) $invalid['unit_type'] = true;
        if(isset($_POST['unit_type']) && $_POST['unit_type'] == "0") {
            if (!(isset($_POST['input_service_frequency0']) && Narin::validate_required('input_service_frequency0') && Narin::validate_integer('input_service_frequency0', 0, false, null, true))) $invalid['input_service_frequency0'] = true;
            if (isset($invalid)) return array('invalid' => $invalid, 'err_msg' => 'اطلاعات دریافت شده معتبر نیست.');
            $greg_start_date = jalali_to_gregorian($_POST['start_date:y0'], $_POST['start_date:m0'], $_POST['start_date:d0']);
            $greg_finish_date = jalali_to_gregorian($_POST['finish_date:y0'], $_POST['finish_date:m0'], $_POST['finish_date:d0']);
            $unitId = $_POST['unitId0'];
            $inputServiceId = Database::filter_str($_POST['input_service_id0']);
            $inputServiceFrequency = Database::filter_str($_POST['input_service_frequency0']);
            $startDateY = $_POST['start_date:y0'];
            $startDateM = $_POST['start_date:m0'];
            $startDateD = $_POST['start_date:d0'];
            $finishDateY = $_POST['finish_date:y0'];
            $finishDateM = $_POST['finish_date:m0'];
            $finishDateD = $_POST['finish_date:d0'];

        } elseif(isset($_POST['unit_type']) && $_POST['unit_type'] == "1") {
            $greg_start_date = jalali_to_gregorian($_POST['start_date:y1'], $_POST['start_date:m1'], $_POST['start_date:d1']);
            $greg_finish_date = jalali_to_gregorian($_POST['finish_date:y1'], $_POST['finish_date:m1'], $_POST['finish_date:d1']);
            $unitId = $_POST['unitId1'];
            $inputServiceId = Database::filter_str($_POST['input_service_id1']);
            $inputServiceFrequency = Database::filter_str($_POST['input_service_frequency1']);
            $startDateY = $_POST['start_date:y1'];
            $startDateM = $_POST['start_date:m1'];
            $startDateD = $_POST['start_date:d1'];
            $finishDateY = $_POST['finish_date:y1'];
            $finishDateM = $_POST['finish_date:m1'];
            $finishDateD = $_POST['finish_date:d1'];
        }

        $start_date = Date(mktime(0, 0, 0, $greg_start_date['1'], $greg_start_date['2'], $greg_start_date['0']));
        $finish_date = Date(mktime(0, 0, 0, $greg_finish_date['1'], $greg_finish_date['2'], $greg_finish_date['0']));
        $start_finish_gap = self::count_days($finish_date, $start_date);
        if ($start_finish_gap <= 0) return array('err_msg' => 'تاریخ پایان باید بعد از تاریخ شروع باشد.');
        $dateTimeObj = new DateTime();
        $dateTimeZone = new DateTimeZone('Asia/Tehran');
        $dateTimeObj->setTimezone($dateTimeZone);
        $shamsiDate = gregorian_to_jalali($dateTimeObj->format('Y'), $dateTimeObj->format('m'), $dateTimeObj->format('d'));
        $date = $shamsiDate[0] . "-" . $shamsiDate[1] . "-" . $shamsiDate[2] . " " . $dateTimeObj->format('H:i:s');
        if ($_SESSION['user']['acl'] == 'State Author') {
            if (isset($_POST['unitId0']) || isset($_POST['unitId1'])) { /*$res = Database::execute_query("SELECT `lastdate-of-done-services-register` FROM `units` WHERE `id` = '" . $_POST['unitId'] . "';"); $row = Database::get_assoc_array($res); $lastDate = date_parse($row['lastdate-of-done-services-register']); if ($lastDate['error_count'] == 0) { $greg_last_date = jalali_to_gregorian($lastDate['year'], $lastDate['month'], $lastDate['day']); $last_date = Date(mktime(0, 0, 0, $greg_last_date['1'], $greg_last_date['2'], $greg_last_date['0'])); $last_start_gap = self::count_days($start_date, $last_date); if ($last_start_gap <= 0) return array('err_msg' => 'تاریخ شروع باید بعد از آخرین تاریخ باشد.
آخرین تاریخ: ' . $row['lastdate-of-done-services-register'] . ''); }*/
                try {
                    Database::execute_query("INSERT INTO `unit-done-services` (`unit-id`, `service-id`, `service-frequency`, `period-start-date`, `period-finish-date`, `date`) VALUES ('" . $unitId . "', '" . $inputServiceId . "', '" . $inputServiceFrequency . "', '" . $startDateY . "-" . $startDateM . "-" . $startDateD . "', '" . $finishDateY . "-" . $finishDateM . "-" . $finishDateD . "', '$date');");
                    Database::execute_query("UPDATE `units` SET `lastdate-of-done-services-register` = '" . $finishDateY . "-" . $finishDateM . "-" . $finishDateD . "' WHERE `id` = '" . $unitId . "';");
                } catch (exception $e) {
                    return array('err_msg' => $e->getMessage());
                }
            } elseif (isset($_POST['hygieneUnitId'])) { /*$res = Database::execute_query("SELECT `lastdate-of-done-services-register` FROM `hygiene-units` WHERE `id` = '" . $_POST['hygieneUnitId'] . "';"); $row = Database::get_assoc_array($res); $lastDate = date_parse($row['lastdate-of-done-services-register']); if ($lastDate['error_count'] == 0) { $greg_last_date = jalali_to_gregorian($lastDate['year'], $lastDate['month'], $lastDate['day']); $last_date = Date(mktime(0, 0, 0, $greg_last_date['1'], $greg_last_date['2'], $greg_last_date['0'])); $last_start_gap = self::count_days($start_date, $last_date); if ($last_start_gap <= 0) return array('err_msg' => 'تاریخ شروع باید بعد از آخرین تاریخ باشد.
آخرین تاریخ: ' . $row['lastdate-of-done-services-register'] . ''); }*/
                try {
                    Database::execute_query("INSERT INTO `hygiene-unit-done-services` (`id`, `hygiene-unit-id`, `service-id`, `service-frequency`, `period-start-date`, `period-finish-date`, `date`) VALUES (NULL, '" . $_POST['hygieneUnitId'] . "', '" . Database::filter_str($_POST['input_service_id_1']) . "', '" . Database::filter_str($_POST['input_service_frequency']) . "', '" . $_POST['start_date:y'] . "-" . $_POST['start_date:m'] . "-" . $_POST['start_date:d'] . "', '" . $_POST['finish_date:y'] . "-" . $_POST['finish_date:m'] . "-" . $_POST['finish_date:d'] . "', '$date');");
                    Database::execute_query("UPDATE `hygiene-units` SET `lastdate-of-done-services-register` = '" . $_POST['finish_date:y'] . "-" . $_POST['finish_date:m'] . "-" . $_POST['finish_date:d'] . "' WHERE `id` = '" . $_POST['hygieneUnitId'] . "';");
                } catch (exception $e) {
                    return array('err_msg' => $e->getMessage());
                }
            }
        } elseif ($_SESSION['user']['acl'] == 'Town Author') {
            if (isset($_POST['unitId0']) || isset($_POST['unitId1'])) { /*$res = Database::execute_query("SELECT `lastdate-of-done-services-register` FROM `units` WHERE `id` = '" . $_POST['unitId'] . "';"); $row = Database::get_assoc_array($res); $lastDate = date_parse($row['lastdate-of-done-services-register']); if ($lastDate['error_count'] == 0) { $greg_last_date = jalali_to_gregorian($lastDate['year'], $lastDate['month'], $lastDate['day']); $last_date = Date(mktime(0, 0, 0, $greg_last_date['1'], $greg_last_date['2'], $greg_last_date['0'])); $last_start_gap = self::count_days($start_date, $last_date); if ($last_start_gap <= 0) return array('err_msg' => 'تاریخ شروع باید بعد از آخرین تاریخ باشد.
آخرین تاریخ: ' . $row['lastdate-of-done-services-register'] . ''); }*/
                try {
                    Database::execute_query("INSERT INTO `unit-done-services` (`unit-id`, `service-id`, `service-frequency`, `period-start-date`, `period-finish-date`, `date`) VALUES ('" . $unitId . "', '" . $inputServiceId . "', '" . $inputServiceFrequency . "', '" . $startDateY . "-" . $startDateM . "-" . $startDateD . "', '" . $finishDateY . "-" . $finishDateM . "-" . $finishDateD . "', '$date');");
                    Database::execute_query("UPDATE `units` SET `lastdate-of-done-services-register` = '" . $_POST['finish_date:y'] . "-" . $_POST['finish_date:m'] . "-" . $_POST['finish_date:d'] . "' WHERE `id` = '" . $unitId . "';");
                } catch (exception $e) {
                    return array('err_msg' => $e->getMessage());
                }
            } elseif (isset($_POST['hygieneUnitId'])) { /*$res = Database::execute_query("SELECT `lastdate-of-done-services-register` FROM `hygiene-units` WHERE `id` = '" . $_POST['hygieneUnitId'] . "';"); $row = Database::get_assoc_array($res); $lastDate = date_parse($row['lastdate-of-done-services-register']); if ($lastDate['error_count'] == 0) { $greg_last_date = jalali_to_gregorian($lastDate['year'], $lastDate['month'], $lastDate['day']); $last_date = Date(mktime(0, 0, 0, $greg_last_date['1'], $greg_last_date['2'], $greg_last_date['0'])); $last_start_gap = self::count_days($start_date, $last_date); if ($last_start_gap <= 0) return array('err_msg' => 'تاریخ شروع باید بعد از آخرین تاریخ باشد.
آخرین تاریخ: ' . $row['lastdate-of-done-services-register'] . ''); }*/
                try {
                    Database::execute_query("INSERT INTO `hygiene-unit-done-services` (`hygiene-unit-id`, `service-id`, `service-frequency`, `period-start-date`, `period-finish-date`, `date`) VALUES ('" . $_POST['hygieneUnitId'] . "', '" . Database::filter_str($_POST['input_service_id_1']) . "', '" . Database::filter_str($_POST['input_service_frequency']) . "', '" . $_POST['start_date:y'] . "-" . $_POST['start_date:m'] . "-" . $_POST['start_date:d'] . "', '" . $_POST['finish_date:y'] . "-" . $_POST['finish_date:m'] . "-" . $_POST['finish_date:d'] . "', '$date');");
                    Database::execute_query("UPDATE `hygiene-units` SET `lastdate-of-done-services-register` = '" . $_POST['finish_date:y'] . "-" . $_POST['finish_date:m'] . "-" . $_POST['finish_date:d'] . "' WHERE `id` = '" . $_POST['hygieneUnitId'] . "';");
                } catch (exception $e) {
                    return array('err_msg' => $e->getMessage());
                }
            }
        } elseif ($_SESSION['user']['acl'] == 'Center Author') {
            if (isset($_POST['unitId0']) || isset($_POST['unitId1'])) { /*$res = Database::execute_query("SELECT `lastdate-of-done-services-register` FROM `units` WHERE `id` = '" . $_POST['unitId'] . "';"); $row = Database::get_assoc_array($res); $lastDate = date_parse($row['lastdate-of-done-services-register']); if ($lastDate['error_count'] == 0) { $greg_last_date = jalali_to_gregorian($lastDate['year'], $lastDate['month'], $lastDate['day']); $last_date = Date(mktime(0, 0, 0, $greg_last_date['1'], $greg_last_date['2'], $greg_last_date['0'])); $last_start_gap = self::count_days($start_date, $last_date); if ($last_start_gap <= 0) return array('err_msg' => 'تاریخ شروع باید بعد از آخرین تاریخ باشد.
آخرین تاریخ: ' . $row['lastdate-of-done-services-register'] . ''); }*/
                try {
                    Database::execute_query("INSERT INTO `unit-done-services` (`unit-id`, `service-id`, `service-frequency`, `period-start-date`, `period-finish-date`, `date`) VALUES ('" . $unitId . "', '" . $inputServiceId . "', '" . $inputServiceFrequency . "', '" . $startDateY. "-" . $startDateM . "-" . $startDateD . "', '" . $finishDateY . "-" . $finishDateM . "-" . $finishDateD . "', '$date');");
                    Database::execute_query("UPDATE `units` SET `lastdate-of-done-services-register` = '" . $finishDateY . "-" . $finishDateM . "-" . $finishDateD . "' WHERE `id` = '" . $unitId . "';");
                } catch (exception $e) {
                    return array('err_msg' => $e->getMessage());
                }
            } elseif (isset($_POST['hygieneUnitId'])) { /*$res = Database::execute_query("SELECT `lastdate-of-done-services-register` FROM `hygiene-units` WHERE `id` = '" . $_POST['hygieneUnitId'] . "';"); $row = Database::get_assoc_array($res); $lastDate = date_parse($row['lastdate-of-done-services-register']); if ($lastDate['error_count'] == 0) { $greg_last_date = jalali_to_gregorian($lastDate['year'], $lastDate['month'], $lastDate['day']); $last_date = Date(mktime(0, 0, 0, $greg_last_date['1'], $greg_last_date['2'], $greg_last_date['0'])); $last_start_gap = self::count_days($start_date, $last_date); if ($last_start_gap <= 0) return array('err_msg' => 'تاریخ شروع باید بعد از آخرین تاریخ باشد.
آخرین تاریخ: ' . $row['lastdate-of-done-services-register'] . ''); }*/
                try {
                    Database::execute_query("INSERT INTO `hygiene-unit-done-services` (`id`, `hygiene-unit-id`, `service-id`, `service-frequency`, `period-start-date`, `period-finish-date`, `date`) VALUES (NULL, '" . $_POST['hygieneUnitId'] . "', '" . Database::filter_str($_POST['input_service_id_1']) . "', '" . Database::filter_str($_POST['input_service_frequency']) . "', '" . $_POST['start_date:y'] . "-" . $_POST['start_date:m'] . "-" . $_POST['start_date:d'] . "', '" . $_POST['finish_date:y'] . "-" . $_POST['finish_date:m'] . "-" . $_POST['finish_date:d'] . "', '$date');");
                    Database::execute_query("UPDATE `hygiene-units` SET `lastdate-of-done-services-register` = '" . $_POST['finish_date:y'] . "-" . $_POST['finish_date:m'] . "-" . $_POST['finish_date:d'] . "' WHERE `id` = '" . $_POST['hygieneUnitId'] . "';");
                } catch (exception $e) {
                    return array('err_msg' => $e->getMessage());
                }
            }
        } elseif ($_SESSION['user']['acl'] == 'Unit Author') { /*$res = Database::execute_query("SELECT `lastdate-of-done-services-register` FROM `units` WHERE `id` = '" . $_SESSION['user']['acl-id'] . "';"); $row = Database::get_assoc_array($res); $lastDate = date_parse($row['lastdate-of-done-services-register']); if ($lastDate['error_count'] == 0) { $greg_last_date = jalali_to_gregorian($lastDate['year'], $lastDate['month'], $lastDate['day']); $last_date = Date(mktime(0, 0, 0, $greg_last_date['1'], $greg_last_date['2'], $greg_last_date['0'])); $last_start_gap = self::count_days($start_date, $last_date); if ($last_start_gap <= 0) return array('err_msg' => 'تاریخ شروع باید بعد از آخرین تاریخ باشد.
آخرین تاریخ: ' . $row['lastdate-of-done-services-register'] . ''); }*/
            try {
                Database::execute_query("INSERT INTO `unit-done-services` (`unit-id`, `service-id`, `service-frequency`, `period-start-date`, `period-finish-date`, `date`) VALUES ('" . $_SESSION['user']['acl-id'] . "', '" . $inputServiceId . "', '" . $inputServiceFrequency . "', '" . $startDateY . "-" . $startDateM . "-" . $startDateD . "', '" . $finishDateY . "-" . $finishDateM . "-" . $finishDateD . "', '$date');");
                Database::execute_query("UPDATE `units` SET `lastdate-of-done-services-register` = '" . $finishDateY . "-" . $finishDateM . "-" . $finishDateD . "' WHERE `id` = '" . $_SESSION['user']['acl-id'] . "';");
            } catch (exception $e) {
                return array('err_msg' => $e->getMessage());
            }
        } elseif ($_SESSION['user']['acl'] == 'Hygiene Unit Author') { /*$res = Database::execute_query("SELECT `lastdate-of-done-services-register` FROM `hygiene-units` WHERE `id` = '" . $_SESSION['user']['acl-id'] . "';"); $row = Database::get_assoc_array($res); $lastDate = date_parse($row['lastdate-of-done-services-register']); if ($lastDate['error_count'] == 0) { $greg_last_date = jalali_to_gregorian($lastDate['year'], $lastDate['month'], $lastDate['day']); $last_date = Date(mktime(0, 0, 0, $greg_last_date['1'], $greg_last_date['2'], $greg_last_date['0'])); $last_start_gap = self::count_days($start_date, $last_date); if ($last_start_gap <= 0) return array('err_msg' => 'تاریخ شروع باید بعد از آخرین تاریخ باشد.
آخرین تاریخ: ' . $row['lastdate-of-done-services-register'] . ''); }*/
            try {
                Database::execute_query("INSERT INTO `hygiene-unit-done-services` (`id`, `hygiene-unit-id`, `service-id`, `service-frequency`, `period-start-date`, `period-finish-date`, `date`) VALUES (NULL, '" . $_SESSION['user']['acl-id'] . "', '" . Database::filter_str($_POST['input_service_id']) . "', '" . Database::filter_str($_POST['input_service_frequency']) . "', '" . $_POST['start_date:y'] . "-" . $_POST['start_date:m'] . "-" . $_POST['start_date:d'] . "', '" . $_POST['finish_date:y'] . "-" . $_POST['finish_date:m'] . "-" . $_POST['finish_date:d'] . "', '$date');");
                Database::execute_query("UPDATE `hygiene-units` SET `lastdate-of-done-services-register` = '" . $_POST['finish_date:y'] . "-" . $_POST['finish_date:m'] . "-" . $_POST['finish_date:d'] . "' WHERE `id` = '" . $_SESSION['user']['acl-id'] . "';");
            } catch (exception $e) {
                return array('err_msg' => $e->getMessage());
            }
        } elseif ($_SESSION['user']['acl'] == 'Multi Author') {
            if ($_POST['multiAuthorType'] == 'center') { /*$res = Database::execute_query("SELECT `lastdate-of-done-services-register` FROM `units` WHERE `id` = '" . $_POST['unitId_1'] . "';"); $row = Database::get_assoc_array($res); $lastDate = date_parse($row['lastdate-of-done-services-register']); if ($lastDate['error_count'] == 0) { $greg_last_date = jalali_to_gregorian($lastDate['year'], $lastDate['month'], $lastDate['day']); $last_date = Date(mktime(0, 0, 0, $greg_last_date['1'], $greg_last_date['2'], $greg_last_date['0'])); $last_start_gap = self::count_days($start_date, $last_date); if ($last_start_gap <= 0) return array('err_msg' => 'تاریخ شروع باید بعد از آخرین تاریخ باشد.
آخرین تاریخ: ' . $row['lastdate-of-done-services-register'] . ''); }*/
                try {
                    Database::execute_query("INSERT INTO `unit-done-services` (`unit-id`, `service-id`, `service-frequency`, `period-start-date`, `period-finish-date`, `date`) VALUES ('" . $_POST['unitId_1'] . "', '" . Database::filter_str($_POST['input_service_id']) . "', '" . Database::filter_str($_POST['input_service_frequency']) . "', '" . $_POST['start_date:y'] . "-" . $_POST['start_date:m'] . "-" . $_POST['start_date:d'] . "', '" . $_POST['finish_date:y'] . "-" . $_POST['finish_date:m'] . "-" . $_POST['finish_date:d'] . "', '$date');");
                    Database::execute_query("UPDATE `units` SET `lastdate-of-done-services-register` = '" . $_POST['finish_date:y'] . "-" . $_POST['finish_date:m'] . "-" . $_POST['finish_date:d'] . "' WHERE `id` = '" . $_POST['unitId_1'] . "';");
                } catch (exception $e) {
                    return array('err_msg' => $e->getMessage());
                }
            } else { /*$res = Database::execute_query("SELECT `lastdate-of-done-services-register` FROM `hygiene-units` WHERE `id` = '" . $_POST['hygieneUnitId'] . "';"); $row = Database::get_assoc_array($res); $lastDate = date_parse($row['lastdate-of-done-services-register']); if ($lastDate['error_count'] == 0) { $greg_last_date = jalali_to_gregorian($lastDate['year'], $lastDate['month'], $lastDate['day']); $last_date = Date(mktime(0, 0, 0, $greg_last_date['1'], $greg_last_date['2'], $greg_last_date['0'])); $last_start_gap = self::count_days($start_date, $last_date); if ($last_start_gap <= 0) return array('err_msg' => 'تاریخ شروع باید بعد از آخرین تاریخ باشد.
آخرین تاریخ: ' . $row['lastdate-of-done-services-register'] . ''); }*/
                try {
                    Database::execute_query("INSERT INTO `hygiene-unit-done-services` (`id`, `hygiene-unit-id`, `service-id`, `service-frequency`, `period-start-date`, `period-finish-date`, `date`) VALUES (NULL, '" . $_POST['hygieneUnitId'] . "', '" . Database::filter_str($_POST['input_service_id_1']) . "', '" . Database::filter_str($_POST['input_service_frequency']) . "', '" . $_POST['start_date:y'] . "-" . $_POST['start_date:m'] . "-" . $_POST['start_date:d'] . "', '" . $_POST['finish_date:y'] . "-" . $_POST['finish_date:m'] . "-" . $_POST['finish_date:d'] . "', '$date');");
                    Database::execute_query("UPDATE `hygiene-units` SET `lastdate-of-done-services-register` = '" . $_POST['finish_date:y'] . "-" . $_POST['finish_date:m'] . "-" . $_POST['finish_date:d'] . "' WHERE `id` = '" . $_POST['hygieneUnitId'] . "';");
                } catch (exception $e) {
                    return array('err_msg' => $e->getMessage());
                }
            }
        }
        unset($dateTimeObj, $dateTimeZone, $shamsiDate, $date);
        if (isset($_POST['unitId0']) || isset($_POST['unitId1'])) {
            try {
                $resS = Database::execute_query("SELECT `name` FROM `available-services` WHERE `id` = '" . $inputServiceId . "';");
                $rowS = Database::get_assoc_array($resS);
                $resU = Database::execute_query("SELECT `name`, `center-id` FROM `units` WHERE `id` = '" . $unitId . "';");
                $rowU = Database::get_assoc_array($resU);
                $resC = Database::execute_query("SELECT `name`, `town-id` FROM `centers` WHERE `id` = '" . $rowU['center-id'] . "';");
                $rowC = Database::get_assoc_array($resC);
                $res = Database::execute_query("SELECT `name` FROM `towns` WHERE `id` = '" . $rowC['town-id'] . "';");
                $row = Database::get_assoc_array($res);
            } catch (exception $e) {
                return array('err_msg' => $e->getMessage());
            }
            $_logTXT = 'ثبت بار خدمت "' . Database::filter_str($_POST['input_service_frequency']) . '" برای خدمت "' . $rowS['name'] . '" از تاریخ "' . $_POST['start_date:y'] . '/' . $_POST['start_date:m'] . '/' . $_POST['start_date:d'] . '" تا تاریخ "' . $_POST['finish_date:y'] . '/' . $_POST['finish_date:m'] . '/' . $_POST['finish_date:d'] . '" در واحد "' . $rowU['name'] . '" در مرکز "' . $rowC['name'] . '" در شهرستان "' . $row['name'] . '"';
        } elseif (isset($_POST['hygieneUnitId'])) {
            try {
                $resS = Database::execute_query("SELECT `name` FROM `available-services` WHERE `id` = '" . Database::filter_str($_POST['input_service_id_1']) . "';");
                $rowS = Database::get_assoc_array($resS);
                $resU = Database::execute_query("SELECT `name`, `center-id` FROM `hygiene-units` WHERE `id` = '" . Database::filter_str($_POST['hygieneUnitId']) . "';");
                $rowU = Database::get_assoc_array($resU);
                $resC = Database::execute_query("SELECT `name`, `town-id` FROM `centers` WHERE `id` = '" . $rowU['center-id'] . "';");
                $rowC = Database::get_assoc_array($resC);
                $res = Database::execute_query("SELECT `name` FROM `towns` WHERE `id` = '" . $rowC['town-id'] . "';");
                $row = Database::get_assoc_array($res);
            } catch (exception $e) {
                return array('err_msg' => $e->getMessage());
            }
            $_logTXT = 'ثبت بار خدمت "' . $inputServiceFrequency . '" برای خدمت "' . $rowS['name'] . '" از تاریخ "' . $_POST['start_date:y'] . '/' . $_POST['start_date:m'] . '/' . $_POST['start_date:d'] . '" تا تاریخ "' . $_POST['finish_date:y'] . '/' . $_POST['finish_date:m'] . '/' . $_POST['finish_date:d'] . '" در خانه‌بهداشت "' . $rowU['name'] . '" در مرکز "' . $rowC['name'] . '" در شهرستان "' . $row['name'] . '"';
        }
        self::_makeLog($_logTXT);
        header('Location: ' . substr(strrchr($_SERVER['PHP_SELF'], '/'), 1) . '?success');
        exit;
    } /*public static function proccessEditDoneServicesForm() { if (!Narin::check_token()) return array('err_msg' => 'به دلیل وجود یک مشکل امنیتی، اطلاعات ثبت نشد. لطفاً دوباره تلاش کنید.'); if (!(isset($_POST['input_service_frequency']) && Narin::validate_required('input_service_frequency') && Narin::validate_integer('input_service_frequency', 0, false, null, true))) $invalid['input_service_frequency'] = true; if (isset($invalid)) return array('invalid' => $invalid, 'err_msg' => 'اطلاعات دریافت شده معتبر نیست.'); try { Database::execute_query("UPDATE `unit-done-services` SET `service-frequency` = '" . $_POST['input_service_frequency'] . "' WHERE `id` = '" . $_POST['id'] . "';"); } catch ( exception $e ) { return array('err_msg' => $e -> getMessage()); } header('Location: ' . substr(strrchr($_SERVER['PHP_SELF'], '/'), 1) . '?success'); exit ; }*/
    public static function proccessEditUnitFrequencyRequestForm()
    {
        if (!Narin::check_token()) return array('err_msg' => 'به دلیل وجود یک مشکل امنیتی، اطلاعات ثبت نشد. لطفاً دوباره تلاش کنید.');
        if (!(isset($_POST['input_service_frequency']) && Narin::validate_required('input_service_frequency') && Narin::validate_integer('input_service_frequency', 0, false, null, true))) $invalid['input_service_frequency'] = true;
        if (isset($invalid)) return array('invalid' => $invalid, 'err_msg' => 'اطلاعات دریافت شده معتبر نیست.');
        $dateTimeObj = new DateTime();
        $dateTimeZone = new DateTimeZone('Asia/Tehran');
        $dateTimeObj->setTimezone($dateTimeZone);
        $shamsiDate = gregorian_to_jalali($dateTimeObj->format('Y'), $dateTimeObj->format('m'), $dateTimeObj->format('d'));
        $date = $shamsiDate[0] . "/" . $shamsiDate[1] . "/" . $shamsiDate[2] . " " . $dateTimeObj->format('H:i:s');
        try {
            Database::execute_query("INSERT INTO `unit-service-freq-edit-requests` (`id`, `user-id`, `done-service-id`, `suggested-freq`, `approved`, `request-date`) VALUES (NULL, '" . $_SESSION['user']['userId'] . "', '" . $_POST['id'] . "', '" . Database::filter_str($_POST['input_service_frequency']) . "', '0', '$date');");
            unset($dateTimeObj, $dateTimeZone, $shamsiDate, $date);
        } catch (exception $e) {
            return array('err_msg' => $e->getMessage());
        }
        try {
            $resI = Database::execute_query("SELECT * FROM `unit-done-services` WHERE `id` = '" . Database::filter_str($_POST['id']) . "';");
            $rowI = Database::get_assoc_array($resI);
            $resS = Database::execute_query("SELECT `name` FROM `available-services` WHERE `id` = '" . $rowI['service-id'] . "';");
            $rowS = Database::get_assoc_array($resS);
            $resU = Database::execute_query("SELECT `name`, `center-id` FROM `units` WHERE `id` = '" . $rowI['unit-id'] . "';");
            $rowU = Database::get_assoc_array($resU);
            $resC = Database::execute_query("SELECT `name`, `town-id` FROM `centers` WHERE `id` = '" . $rowU['center-id'] . "';");
            $rowC = Database::get_assoc_array($resC);
            $res = Database::execute_query("SELECT `name` FROM `towns` WHERE `id` = '" . $rowC['town-id'] . "';");
            $row = Database::get_assoc_array($res);
        } catch (exception $e) {
            return array('err_msg' => $e->getMessage());
        }
        $_logTXT = 'ثبت درخواست سنداصلاحی برای بارخدمت "' . $rowI['service-frequency'] . '" برای اصلاح به "' . Database::filter_str($_POST['input_service_frequency']) . '" برای خدمت "' . $rowS['name'] . '" از تاریخ "' . $rowI['period-start-date'] . '" تا تاریخ "' . $rowI['period-finish-date'] . '" در واحد "' . $rowU['name'] . '" در مرکز "' . $rowC['name'] . '" در شهرستان "' . $row['name'] . '"';
        self::_makeLog($_logTXT);
        header('Location: ' . substr(strrchr($_SERVER['PHP_SELF'], '/'), 1) . '?success');
        exit;
    }

    public static function proccessEditHGUnitFrequencyRequestForm()
    {
        if (!Narin::check_token()) return array('err_msg' => 'به دلیل وجود یک مشکل امنیتی، اطلاعات ثبت نشد. لطفاً دوباره تلاش کنید.');
        if (!(isset($_POST['input_service_frequency']) && Narin::validate_required('input_service_frequency') && Narin::validate_integer('input_service_frequency', 0, false, null, true))) $invalid['input_service_frequency'] = true;
        if (isset($invalid)) return array('invalid' => $invalid, 'err_msg' => 'اطلاعات دریافت شده معتبر نیست.');
        $dateTimeObj = new DateTime();
        $dateTimeZone = new DateTimeZone('Asia/Tehran');
        $dateTimeObj->setTimezone($dateTimeZone);
        $shamsiDate = gregorian_to_jalali($dateTimeObj->format('Y'), $dateTimeObj->format('m'), $dateTimeObj->format('d'));
        $date = $shamsiDate[0] . "/" . $shamsiDate[1] . "/" . $shamsiDate[2] . " " . $dateTimeObj->format('H:i:s');
        try {
            Database::execute_query("INSERT INTO `hygiene-unit-service-freq-edit-requests` (`id`, `user-id`, `done-service-id`, `suggested-freq`, `approved`, `request-date`) VALUES (NULL, '" . $_SESSION['user']['userId'] . "', '" . $_POST['id'] . "', '" . Database::filter_str($_POST['input_service_frequency']) . "', '0', '$date');");
            unset($dateTimeObj, $dateTimeZone, $shamsiDate, $date);
        } catch (exception $e) {
            return array('err_msg' => $e->getMessage());
        }
        try {
            $resI = Database::execute_query("SELECT * FROM `hygiene-unit-done-services` WHERE `id` = '" . Database::filter_str($_POST['id']) . "';");
            $rowI = Database::get_assoc_array($resI);
            $resS = Database::execute_query("SELECT `name` FROM `available-services` WHERE `id` = '" . $rowI['service-id'] . "';");
            $rowS = Database::get_assoc_array($resS);
            $resU = Database::execute_query("SELECT `name`, `center-id` FROM `hygiene-units` WHERE `id` = '" . $rowI['hygiene-unit-id'] . "';");
            $rowU = Database::get_assoc_array($resU);
            $resC = Database::execute_query("SELECT `name`, `town-id` FROM `centers` WHERE `id` = '" . $rowU['center-id'] . "';");
            $rowC = Database::get_assoc_array($resC);
            $res = Database::execute_query("SELECT `name` FROM `towns` WHERE `id` = '" . $rowC['town-id'] . "';");
            $row = Database::get_assoc_array($res);
        } catch (exception $e) {
            return array('err_msg' => $e->getMessage());
        }
        $_logTXT = 'ثبت درخواست سنداصلاحی برای بارخدمت "' . $rowI['service-frequency'] . '" برای اصلاح به "' . Database::filter_str($_POST['input_service_frequency']) . '" برای خدمت "' . $rowS['name'] . '" از تاریخ "' . $rowI['period-start-date'] . '" تا تاریخ "' . $rowI['period-finish-date'] . '" در خانه‌بهداشت "' . $rowU['name'] . '" در مرکز "' . $rowC['name'] . '" در شهرستان "' . $row['name'] . '"';
        self::_makeLog($_logTXT);
        header('Location: ' . substr(strrchr($_SERVER['PHP_SELF'], '/'), 1) . '?success');
        exit;
    }

    public static function processConfirmUnitEditRequest()
    {
        if (!Narin::check_token()) return array('err_msg' => 'به دلیل وجود یک مشکل امنیتی، اطلاعات ثبت نشد. لطفاً دوباره تلاش کنید.');
        try {
            Database::execute_query("UPDATE `unit-service-freq-edit-requests` SET `approved` = '1' WHERE `id` = '" . $_POST['reqId'] . "';");
        } catch (exception $e) {
            return array('err_msg' => $e->getMessage());
        }
        try {
            $resR = Database::execute_query("SELECT * FROM `unit-service-freq-edit-requests` WHERE `id` = '" . Database::filter_str($_POST['reqId']) . "';");
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
        } catch (exception $e) {
            return array('err_msg' => $e->getMessage());
        }
        $_logTXT = 'تائید سنداصلاحی برای بارخدمت "' . $rowI['service-frequency'] . '" اصلاح به "' . $rowR['suggested-freq'] . '" برای خدمت "' . $rowS['name'] . '" از تاریخ "' . $rowI['period-start-date'] . '" تا تاریخ "' . $rowI['period-finish-date'] . '" در واحد "' . $rowU['name'] . '" در مرکز "' . $rowC['name'] . '" در شهرستان "' . $row['name'] . '"';
        self::_makeLog($_logTXT);
        return true;
    }

    public static function processRejectUnitEditRequest()
    {
        if (!Narin::check_token()) return array('err_msg' => 'به دلیل وجود یک مشکل امنیتی، اطلاعات ثبت نشد. لطفاً دوباره تلاش کنید.');
        try {
            $resR = Database::execute_query("SELECT * FROM `unit-service-freq-edit-requests` WHERE `id` = '" . Database::filter_str($_POST['reqId']) . "';");
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
        } catch (exception $e) {
            return array('err_msg' => $e->getMessage());
        }
        $_logTXT = 'لغو سنداصلاحی برای بارخدمت "' . $rowI['service-frequency'] . '" اصلاح به "' . $rowR['suggested-freq'] . '" برای خدمت "' . $rowS['name'] . '" از تاریخ "' . $rowI['period-start-date'] . '" تا تاریخ "' . $rowI['period-finish-date'] . '" در واحد "' . $rowU['name'] . '" در مرکز "' . $rowC['name'] . '" در شهرستان "' . $row['name'] . '"';
        try {
            Database::execute_query("DELETE FROM `unit-service-freq-edit-requests` WHERE `id` = '" . $_POST['reqId'] . "';");
        } catch (exception $e) {
            return array('err_msg' => $e->getMessage());
        }
        self::_makeLog($_logTXT);
        return true;
    }

    public static function processConfirmHGUnitEditRequest()
    {
        if (!Narin::check_token()) return array('err_msg' => 'به دلیل وجود یک مشکل امنیتی، اطلاعات ثبت نشد. لطفاً دوباره تلاش کنید.');
        try {
            Database::execute_query("UPDATE `hygiene-unit-service-freq-edit-requests` SET `approved` = '1' WHERE `id` = '" . $_POST['reqId'] . "';");
        } catch (exception $e) {
            return array('err_msg' => $e->getMessage());
        }
        try {
            $resR = Database::execute_query("SELECT * FROM `hygiene-unit-service-freq-edit-requests` WHERE `id` = '" . Database::filter_str($_POST['reqId']) . "';");
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
        } catch (exception $e) {
            return array('err_msg' => $e->getMessage());
        }
        $_logTXT = 'تائید سنداصلاحی برای بارخدمت "' . $rowI['service-frequency'] . '" اصلاح به "' . $rowR['suggested-freq'] . '" برای خدمت "' . $rowS['name'] . '" از تاریخ "' . $rowI['period-start-date'] . '" تا تاریخ "' . $rowI['period-finish-date'] . '" در خانه‌بهداشت "' . $rowU['name'] . '" در مرکز "' . $rowC['name'] . '" در شهرستان "' . $row['name'] . '"';
        self::_makeLog($_logTXT);
        return true;
    }

    public static function processRejectHGUnitEditRequest()
    {
        if (!Narin::check_token()) return array('err_msg' => 'به دلیل وجود یک مشکل امنیتی، اطلاعات ثبت نشد. لطفاً دوباره تلاش کنید.');
        try {
            $resR = Database::execute_query("SELECT * FROM `hygiene-unit-service-freq-edit-requests` WHERE `id` = '" . Database::filter_str($_POST['reqId']) . "';");
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
        } catch (exception $e) {
            return array('err_msg' => $e->getMessage());
        }
        $_logTXT = 'لغو سنداصلاحی برای بارخدمت "' . $rowI['service-frequency'] . '" اصلاح به "' . $rowR['suggested-freq'] . '" برای خدمت "' . $rowS['name'] . '" از تاریخ "' . $rowI['period-start-date'] . '" تا تاریخ "' . $rowI['period-finish-date'] . '" در خانه‌بهداشت "' . $rowU['name'] . '" در مرکز "' . $rowC['name'] . '" در شهرستان "' . $row['name'] . '"';
        try {
            Database::execute_query("DELETE FROM `hygiene-unit-service-freq-edit-requests` WHERE `id` = '" . $_POST['reqId'] . "';");
        } catch (exception $e) {
            return array('err_msg' => $e->getMessage());
        }
        self::_makeLog($_logTXT);
        return true;
    }

    public static function processAddUserForm()
    {
        if ($_SERVER['REQUEST_METHOD'] != 'POST') return isset($_GET['success']) ? array('success' => true) : false;
        if (!Narin::check_token()) return array('err_msg' => 'به دلیل وجود یک مشکل امنیتی، اطلاعات ثبت نشد. لطفاً دوباره تلاش کنید.');
        if (!(isset($_POST['name']) && Narin::validate_required('name') && strlen($_POST['name']) <= 50)) $invalid['name'] = true;
        if (!(isset($_POST['lastName']) && Narin::validate_required('lastName') && strlen($_POST['lastName']) <= 50)) $invalid['lastName'] = true;
        if (!(isset($_POST['userName']) && Narin::validate_required('userName') && strlen($_POST['userName']) <= 255)) $invalid['userName'] = true;
        if (!(isset($_POST['passwd']) && isset($_POST['passwdconfirm']) && $_POST['passwd'] === $_POST['passwdconfirm'] && $_POST['passwd'] !== '')) $invalid['passwd'] = true;
        if (!(isset($_POST['email']) && Narin::validate_required('email') && Narin::validate_email('email') && strlen($_POST['email']) <= 255)) $invalid['email'] = true;
        if (isset($invalid)) return array('invalid' => $invalid, 'err_msg' => 'اطلاعات دریافت شده معتبر نیست.');
        $acl = '';
        $aclId = 0;
        switch ($_POST['accessLevel']) {
            case 'stateManager' :
                $acl = 'State Manager';
                $aclId = $_POST['stateId'];
                try {
                    Database::execute_query("INSERT INTO `users` (`name`, `last-name`, `username`, `password`, `email`, `access-level`, `access-level-id`, `center-author-ids`, `hygiene-unit-author-ids`) VALUES ('" . Database::filter_str($_POST['name']) . "', '" . Database::filter_str($_POST['lastName']) . "', '" . Database::filter_str($_POST['userName']) . "', '" . hash_hmac('sha512', Database::filter_str($_POST['passwd']), Database::filter_str($_POST['userName'])) . "', '" . Database::filter_str($_POST['email']) . "', '" . $acl . "', '" . $aclId . "', NULL, NULL);");
                } catch (Exception $e) {
                    return array('err_msg' => $e->getMessage());
                }
                break;
            case 'townManager' :
                $acl = 'Town Manager';
                $aclId = $_POST['townId'];
                try {
                    $id = Database::insertAndReturnInsertedId("INSERT INTO `users` (`name`, `last-name`, `username`, `password`, `email`, `access-level`, `access-level-id`, `center-author-ids`, `hygiene-unit-author-ids`) VALUES ('" . Database::filter_str($_POST['name']) . "', '" . Database::filter_str($_POST['lastName']) . "', '" . Database::filter_str($_POST['userName']) . "', '" . hash_hmac('sha512', Database::filter_str($_POST['passwd']), Database::filter_str($_POST['userName'])) . "', '" . Database::filter_str($_POST['email']) . "', '" . $acl . "', '" . $aclId . "', NULL, NULL);");
                    Database::execute_query("INSERT INTO `town-manager-acl` (`user-id`, `addCenterDrugsAndConsumingEquipments`, `addCenterNonConsumingEquipments`, `manageCenterBuildings`, `addCenterBuildingCharge`, `manageCenterVehicles`, `addCenterVehicleCharge`, `addCenterGeneralCharge`) VALUES ('" . $id . "', '" . intval(isset($_POST['addCenterDrugsAndConsumingEquipments'])) . "', '" . intval(isset($_POST['addCenterNonConsumingEquipments'])) . "', '" . intval(isset($_POST['manageCenterBuildings'])) . "', '" . intval(isset($_POST['addCenterBuildingCharge'])) . "', '" . intval(isset($_POST['manageCenterVehicles'])) . "', '" . intval(isset($_POST['addCenterVehicleCharge'])) . "', '" . intval(isset($_POST['addCenterGeneralCharge'])) . "');");
                } catch (Exception $e) {
                    return array('err_msg' => $e->getMessage());
                }
                break;
            case 'centerManager' :
                $acl = 'Center Manager';
                $aclId = $_POST['centerId'];
                try {
                    $id = Database::insertAndReturnInsertedId("INSERT INTO `users` (`name`, `last-name`, `username`, `password`, `email`, `access-level`, `access-level-id`, `center-author-ids`, `hygiene-unit-author-ids`) VALUES ('" . Database::filter_str($_POST['name']) . "', '" . Database::filter_str($_POST['lastName']) . "', '" . Database::filter_str($_POST['userName']) . "', '" . hash_hmac('sha512', Database::filter_str($_POST['passwd']), Database::filter_str($_POST['userName'])) . "', '" . Database::filter_str($_POST['email']) . "', '" . $acl . "', '" . $aclId . "', NULL, NULL);");
                    Database::execute_query("INSERT INTO `center-manager-acl` (`user-id`, `addCenterDrugsAndConsumingEquipments`, `addCenterNonConsumingEquipments`, `manageCenterBuildings`, `addCenterBuildingCharge`, `manageCenterVehicles`, `addCenterVehicleCharge`, `addCenterGeneralCharge`) VALUES ('" . $id . "', '" . intval(isset($_POST['c_addCenterDrugsAndConsumingEquipments'])) . "', '" . intval(isset($_POST['c_addCenterNonConsumingEquipments'])) . "', '" . intval(isset($_POST['c_manageCenterBuildings'])) . "', '" . intval(isset($_POST['c_addCenterBuildingCharge'])) . "', '" . intval(isset($_POST['c_manageCenterVehicles'])) . "', '" . intval(isset($_POST['c_addCenterVehicleCharge'])) . "', '" . intval(isset($_POST['c_addCenterGeneralCharge'])) . "');");
                } catch (Exception $e) {
                    return array('err_msg' => $e->getMessage());
                }
                break;
            case 'unitManager' :
                $acl = 'Unit Manager';
                $aclId = $_POST['unitId'];
                try {
                    $id = Database::insertAndReturnInsertedId("INSERT INTO `users` (`name`, `last-name`, `username`, `password`, `email`, `access-level`, `access-level-id`, `center-author-ids`, `hygiene-unit-author-ids`) VALUES ('" . Database::filter_str($_POST['name']) . "', '" . Database::filter_str($_POST['lastName']) . "', '" . Database::filter_str($_POST['userName']) . "', '" . hash_hmac('sha512', Database::filter_str($_POST['passwd']), Database::filter_str($_POST['userName'])) . "', '" . Database::filter_str($_POST['email']) . "', '" . $acl . "', '" . $aclId . "', NULL, NULL);");
                    Database::execute_query("INSERT INTO `unit-manager-acl` (`user-id`, `manageUnitPersonnel`, `unitPersonnelFinancialInfo`, `manageUnitServices`) VALUES ('" . $id . "', '" . intval(isset($_POST['manageUnitPersonnel'])) . "', '" . intval(isset($_POST['unitPersonnelFinancialInfo'])) . "', '" . intval(isset($_POST['manageUnitServices'])) . "');");
                } catch (Exception $e) {
                    return array('err_msg' => $e->getMessage());
                }
                break;
            case 'hygieneUnitManager' :
                $acl = 'Hygiene Unit Manager';
                $aclId = $_POST['hygieneUnitId'];
                try {
                    $id = Database::insertAndReturnInsertedId("INSERT INTO `users` (`name`, `last-name`, `username`, `password`, `email`, `access-level`, `access-level-id`, `center-author-ids`, `hygiene-unit-author-ids`) VALUES ('" . Database::filter_str($_POST['name']) . "', '" . Database::filter_str($_POST['lastName']) . "', '" . Database::filter_str($_POST['userName']) . "', '" . hash_hmac('sha512', Database::filter_str($_POST['passwd']), Database::filter_str($_POST['userName'])) . "', '" . Database::filter_str($_POST['email']) . "', '" . $acl . "', '" . $aclId . "', NULL, NULL);");
                    Database::execute_query("INSERT INTO `hygiene-unit-manager-acl` (`user-id`, `addHygieneUnitDrugsAndConsumingEquipments`, `addHygieneUnitNonConsumingEquipments`, `manageHygieneUnitPersonnel`, `hygieneUnitPersonnelFinancialInfo`, `manageHygieneUnitBuildings`, `addHygieneUnitBuildingCharge`, `manageHygieneUnitVehicles`, `addHygieneUnitVehicleCharge`, `manageHygieneUnitServices`, `addHygieneUnitGeneralCharge`) VALUES ('" . $id . "', '" . intval(isset($_POST['addHygieneUnitDrugsAndConsumingEquipments'])) . "', '" . intval(isset($_POST['addHygieneUnitNonConsumingEquipments'])) . "', '" . intval(isset($_POST['manageHygieneUnitPersonnel'])) . "', '" . intval(isset($_POST['hygieneUnitPersonnelFinancialInfo'])) . "', '" . intval(isset($_POST['manageHygieneUnitBuildings'])) . "', '" . intval(isset($_POST['addHygieneUnitBuildingCharge'])) . "', '" . intval(isset($_POST['manageHygieneUnitVehicles'])) . "', '" . intval(isset($_POST['addHygieneUnitVehicleCharge'])) . "', '" . intval(isset($_POST['manageHygieneUnitServices'])) . "', '" . intval(isset($_POST['addHygieneUnitGeneralCharge'])) . "');");
                } catch (Exception $e) {
                    return array('err_msg' => $e->getMessage());
                }
                break;
            case 'stateAuthor' :
                $acl = 'State Author';
                $aclId = $_POST['stateId'];
                try {
                    Database::execute_query("INSERT INTO `users` (`name`, `last-name`, `username`, `password`, `email`, `access-level`, `access-level-id`, `center-author-ids`, `hygiene-unit-author-ids`) VALUES ('" . Database::filter_str($_POST['name']) . "', '" . Database::filter_str($_POST['lastName']) . "', '" . Database::filter_str($_POST['userName']) . "', '" . hash_hmac('sha512', Database::filter_str($_POST['passwd']), Database::filter_str($_POST['userName'])) . "', '" . Database::filter_str($_POST['email']) . "', '" . $acl . "', '" . $aclId . "', NULL, NULL);");
                } catch (Exception $e) {
                    return array('err_msg' => $e->getMessage());
                }
                break;
            case 'townAuthor' :
                $acl = 'Town Author';
                $aclId = $_POST['townId'];
                try {
                    Database::execute_query("INSERT INTO `users` (`name`, `last-name`, `username`, `password`, `email`, `access-level`, `access-level-id`, `center-author-ids`, `hygiene-unit-author-ids`) VALUES ('" . Database::filter_str($_POST['name']) . "', '" . Database::filter_str($_POST['lastName']) . "', '" . Database::filter_str($_POST['userName']) . "', '" . hash_hmac('sha512', Database::filter_str($_POST['passwd']), Database::filter_str($_POST['userName'])) . "', '" . Database::filter_str($_POST['email']) . "', '" . $acl . "', '" . $aclId . "', NULL, NULL);");
                } catch (Exception $e) {
                    return array('err_msg' => $e->getMessage());
                }
                break;
            case 'centerAuthor' :
                $acl = 'Center Author';
                $aclId = $_POST['centerId'];
                try {
                    Database::execute_query("INSERT INTO `users` (`name`, `last-name`, `username`, `password`, `email`, `access-level`, `access-level-id`, `center-author-ids`, `hygiene-unit-author-ids`) VALUES ('" . Database::filter_str($_POST['name']) . "', '" . Database::filter_str($_POST['lastName']) . "', '" . Database::filter_str($_POST['userName']) . "', '" . hash_hmac('sha512', Database::filter_str($_POST['passwd']), Database::filter_str($_POST['userName'])) . "', '" . Database::filter_str($_POST['email']) . "', '" . $acl . "', '" . $aclId . "', NULL, NULL);");
                } catch (Exception $e) {
                    return array('err_msg' => $e->getMessage());
                }
                break;
            case 'unitAuthor' :
                $acl = 'Unit Author';
                $aclId = $_POST['unitId'];
                try {
                    Database::execute_query("INSERT INTO `users` (`id`, `name`, `last-name`, `username`, `password`, `email`, `access-level`, `access-level-id`, `center-author-ids`, `hygiene-unit-author-ids`) VALUES (NULL, '" . Database::filter_str($_POST['name']) . "', '" . Database::filter_str($_POST['lastName']) . "', '" . Database::filter_str($_POST['userName']) . "', '" . hash_hmac('sha512', Database::filter_str($_POST['passwd']), Database::filter_str($_POST['userName'])) . "', '" . Database::filter_str($_POST['email']) . "', '" . $acl . "', '" . $aclId . "', NULL, NULL);");
                } catch (Exception $e) {
                    return array('err_msg' => $e->getMessage());
                }
                break;
            case 'hygieneUnitAuthor' :
                $acl = 'Hygiene Unit Author';
                $aclId = $_POST['hygieneUnitId'];
                try {
                    Database::execute_query("INSERT INTO `users` (`id`, `name`, `last-name`, `username`, `password`, `email`, `access-level`, `access-level-id`, `center-author-ids`, `hygiene-unit-author-ids`) VALUES (NULL, '" . Database::filter_str($_POST['name']) . "', '" . Database::filter_str($_POST['lastName']) . "', '" . Database::filter_str($_POST['userName']) . "', '" . hash_hmac('sha512', Database::filter_str($_POST['passwd']), Database::filter_str($_POST['userName'])) . "', '" . Database::filter_str($_POST['email']) . "', '" . $acl . "', '" . $aclId . "', NULL, NULL);");
                } catch (Exception $e) {
                    return array('err_msg' => $e->getMessage());
                }
                break;
            case 'multiAuthor' :
                $acl = 'Multi Author';
                try {
                    Database::execute_query("INSERT INTO `users` (`id`, `name`, `last-name`, `username`, `password`, `email`, `access-level`, `access-level-id`, `center-author-ids`, `hygiene-unit-author-ids`) VALUES (NULL, '" . Database::filter_str($_POST['name']) . "', '" . Database::filter_str($_POST['lastName']) . "', '" . Database::filter_str($_POST['userName']) . "', '" . hash_hmac('sha512', Database::filter_str($_POST['passwd']), Database::filter_str($_POST['userName'])) . "', '" . Database::filter_str($_POST['email']) . "', '" . $acl . "', NULL, '" . implode(', ', $_POST['centerId']) . "', '" . implode(', ', $_POST['hygieneUnitId']) . "');");
                } catch (Exception $e) {
                    return array('err_msg' => $e->getMessage());
                }
                break;
        }
        $_logTXT = 'ثبت کاربر جدید به‌نام "' . Database::filter_str($_POST['name']) . ' ' . Database::filter_str($_POST['lastName']) . '" در سیستم';
        self::_makeLog($_logTXT);
        header('Location: ' . substr(strrchr($_SERVER['PHP_SELF'], '/'), 1) . '?success');
        exit;
    }

    public static function processEditUserForm()
    {
        if ($_SERVER['REQUEST_METHOD'] != 'POST') return isset($_GET['success']) ? array('success' => true) : false;
        if (!Narin::check_token()) return array('err_msg' => 'به دلیل وجود یک مشکل امنیتی، اطلاعات ثبت نشد. لطفاً دوباره تلاش کنید.');
        if (!(isset($_POST['name']) && Narin::validate_required('name') && strlen($_POST['name']) <= 50)) $invalid['name'] = true;
        if (!(isset($_POST['lastName']) && Narin::validate_required('lastName') && strlen($_POST['lastName']) <= 50)) $invalid['lastName'] = true;
        if (isset($invalid)) return array('invalid' => $invalid, 'err_msg' => 'اطلاعات دریافت شده معتبر نیست.');
        $query = '';
        if (isset($_POST['accessLevel']) and isset($_POST['passwd'])) {
            if (!(isset($_POST['passwd']) && isset($_POST['passwdconfirm']) && $_POST['passwd'] === $_POST['passwdconfirm'] && $_POST['passwd'] !== '')) $invalid['passwd'] = true;
            if (isset($invalid)) return array('invalid' => $invalid, 'err_msg' => 'اطلاعات دریافت شده معتبر نیست.');
            $acl = '';
            $aclId = 0;
            switch ($_POST['accessLevel']) {
                case 'stateManager' :
                    $acl = 'State Manager';
                    $aclId = $_POST['stateId'];
                    try {
                        Database::execute_query("UPDATE `users` SET `name` = '" . $_POST['name'] . "', `last-name` = '" . $_POST['lastName'] . "', `password` = '" . hash_hmac('sha512', Database::filter_str($_POST['passwd']), Database::filter_str($_POST['username'])) . "', `email` = '" . Database::filter_str($_POST['email']) . "', `access-level` = '" . $acl . "', `access-level-id` = '" . $aclId . "' WHERE `id` = " . $_POST['userId'] . ";");
                    } catch (Exception $e) {
                        return array('err_msg' => $e->getMessage());
                    }
                    break;
                case 'townManager' :
                    $acl = 'Town Manager';
                    $aclId = $_POST['townId'];
                    try {
                        Database::execute_query("UPDATE `users` SET `name` = '" . $_POST['name'] . "', `last-name` = '" . $_POST['lastName'] . "', `password` = '" . hash_hmac('sha512', Database::filter_str($_POST['passwd']), Database::filter_str($_POST['username'])) . "', `email` = '" . Database::filter_str($_POST['email']) . "', `access-level` = '" . $acl . "', `access-level-id` = '" . $aclId . "' WHERE `id` = " . $_POST['userId'] . ";");
                        Database::execute_query("UPDATE `town-manager-acl` SET `addCenterDrugsAndConsumingEquipments` = '" . intval(isset($_POST['addCenterDrugsAndConsumingEquipments'])) . "', `addCenterNonConsumingEquipments` = '" . intval(isset($_POST['addCenterNonConsumingEquipments'])) . "', `manageCenterBuildings` = '" . intval(isset($_POST['manageCenterBuildings'])) . "', `addCenterBuildingCharge` = '" . intval(isset($_POST['addCenterBuildingCharge'])) . "', `manageCenterVehicles` = '" . intval(isset($_POST['manageCenterVehicles'])) . "', `addCenterVehicleCharge` = '" . intval(isset($_POST['addCenterVehicleCharge'])) . "', `addCenterGeneralCharge` = '" . intval(isset($_POST['addCenterGeneralCharge'])) . "' WHERE `user-id` = " . $_POST['userId'] . ";");
                    } catch (Exception $e) {
                        return array('err_msg' => $e->getMessage());
                    }
                    break;
                case 'centerManager' :
                    $acl = 'Center Manager';
                    $aclId = $_POST['centerId'];
                    try {
                        Database::execute_query("UPDATE `users` SET `name` = '" . $_POST['name'] . "', `last-name` = '" . $_POST['lastName'] . "', `password` = '" . hash_hmac('sha512', Database::filter_str($_POST['passwd']), Database::filter_str($_POST['username'])) . "', `email` = '" . Database::filter_str($_POST['email']) . "', `access-level` = '" . $acl . "', `access-level-id` = '" . $aclId . "' WHERE `id` = " . $_POST['userId'] . ";");
                        Database::execute_query("UPDATE `center-manager-acl` SET `addCenterDrugsAndConsumingEquipments` = '" . intval(isset($_POST['addCenterDrugsAndConsumingEquipments'])) . "', `addCenterNonConsumingEquipments` = '" . intval(isset($_POST['addCenterNonConsumingEquipments'])) . "', `manageCenterBuildings` = '" . intval(isset($_POST['manageCenterBuildings'])) . "', `addCenterBuildingCharge` = '" . intval(isset($_POST['addCenterBuildingCharge'])) . "', `manageCenterVehicles` = '" . intval(isset($_POST['manageCenterVehicles'])) . "', `addCenterVehicleCharge` = '" . intval(isset($_POST['addCenterVehicleCharge'])) . "', `addCenterGeneralCharge` = '" . intval(isset($_POST['addCenterGeneralCharge'])) . "' WHERE `user-id` = " . $_POST['userId'] . ";");
                    } catch (Exception $e) {
                        return array('err_msg' => $e->getMessage());
                    }
                    break;
                case 'unitManager' :
                    $acl = 'Unit Manager';
                    $aclId = $_POST['unitId'];
                    try {
                        Database::execute_query("UPDATE `users` SET `name` = '" . $_POST['name'] . "', `last-name` = '" . $_POST['lastName'] . "', `password` = '" . hash_hmac('sha512', Database::filter_str($_POST['passwd']), Database::filter_str($_POST['username'])) . "', `email` = '" . Database::filter_str($_POST['email']) . "', `access-level` = '" . $acl . "', `access-level-id` = '" . $aclId . "' WHERE `id` = " . $_POST['userId'] . ";");
                        Database::execute_query("UPDATE `unit-manager-acl` SET `manageUnitPersonnel` = '" . intval(isset($_POST['manageUnitPersonnel'])) . "', `unitPersonnelFinancialInfo` = '" . intval(isset($_POST['unitPersonnelFinancialInfo'])) . "', `manageUnitServices` = '" . intval(isset($_POST['manageUnitServices'])) . "' WHERE `user-id` = '" . $_POST['userId'] . "';");
                    } catch (Exception $e) {
                        return array('err_msg' => $e->getMessage());
                    }
                    break;
                case 'hygieneUnitManager' :
                    $acl = 'Hygiene Unit Manager';
                    $aclId = $_POST['hygieneUnitId'];
                    try {
                        Database::execute_query("UPDATE `users` SET `name` = '" . $_POST['name'] . "', `last-name` = '" . $_POST['lastName'] . "', `password` = '" . hash_hmac('sha512', Database::filter_str($_POST['passwd']), Database::filter_str($_POST['username'])) . "', `email` = '" . Database::filter_str($_POST['email']) . "', `access-level` = '" . $acl . "', `access-level-id` = '" . $aclId . "' WHERE `id` = " . $_POST['userId'] . ";");
                        Database::execute_query("UPDATE `hygiene-unit-manager-acl` SET `addHygieneUnitDrugsAndConsumingEquipments` = '" . intval(isset($_POST['addHygieneUnitDrugsAndConsumingEquipments'])) . "', `addHygieneUnitNonConsumingEquipments` = '" . intval(isset($_POST['addHygieneUnitNonConsumingEquipments'])) . "', `manageHygieneUnitPersonnel` = '" . intval(isset($_POST['manageHygieneUnitPersonnel'])) . "', `hygieneUnitPersonnelFinancialInfo` = '" . intval(isset($_POST['hygieneUnitPersonnelFinancialInfo'])) . "', `manageHygieneUnitBuildings` = '" . intval(isset($_POST['manageHygieneUnitBuildings'])) . "', `addHygieneUnitBuildingCharge` = '" . intval(isset($_POST['addHygieneUnitBuildingCharge'])) . "', `manageHygieneUnitVehicles` = '" . intval(isset($_POST['manageHygieneUnitVehicles'])) . "', `addHygieneUnitVehicleCharge` = '" . intval(isset($_POST['addHygieneUnitVehicleCharge'])) . "', `manageHygieneUnitServices` = '" . intval(isset($_POST['manageHygieneUnitServices'])) . "', `addHygieneUnitGeneralCharge` = '" . intval(isset($_POST['addHygieneUnitGeneralCharge'])) . "' WHERE `user-id` = " . $_POST['userId'] . ";");
                    } catch (Exception $e) {
                        return array('err_msg' => $e->getMessage());
                    }
                    break;
                case 'stateAuthor' :
                    $acl = 'State Author';
                    $aclId = $_POST['stateId'];
                    $query = "UPDATE `users` SET `name` = '" . $_POST['name'] . "', `last-name` = '" . $_POST['lastName'] . "', `password` = '" . hash_hmac('sha512', Database::filter_str($_POST['passwd']), Database::filter_str($_POST['username'])) . "', `email` = '" . Database::filter_str($_POST['email']) . "', `access-level` = '" . $acl . "', `access-level-id` = '" . $aclId . "' WHERE `id` = " . $_POST['userId'] . ";";
                    try {
                        Database::execute_query($query);
                    } catch (Exception $e) {
                        return array('err_msg' => $e->getMessage());
                    }
                    break;
                case 'townAuthor' :
                    $acl = 'Town Author';
                    $aclId = $_POST['townId'];
                    $query = "UPDATE `users` SET `name` = '" . $_POST['name'] . "', `last-name` = '" . $_POST['lastName'] . "', `password` = '" . hash_hmac('sha512', Database::filter_str($_POST['passwd']), Database::filter_str($_POST['username'])) . "', `email` = '" . Database::filter_str($_POST['email']) . "', `access-level` = '" . $acl . "', `access-level-id` = '" . $aclId . "' WHERE `id` = " . $_POST['userId'] . ";";
                    try {
                        Database::execute_query($query);
                    } catch (Exception $e) {
                        return array('err_msg' => $e->getMessage());
                    }
                    break;
                case 'centerAuthor' :
                    $acl = 'Center Author';
                    $aclId = $_POST['centerId'];
                    $query = "UPDATE `users` SET `name` = '" . $_POST['name'] . "', `last-name` = '" . $_POST['lastName'] . "', `password` = '" . hash_hmac('sha512', Database::filter_str($_POST['passwd']), Database::filter_str($_POST['username'])) . "', `email` = '" . Database::filter_str($_POST['email']) . "', `access-level` = '" . $acl . "', `access-level-id` = '" . $aclId . "' WHERE `id` = " . $_POST['userId'] . ";";
                    try {
                        Database::execute_query($query);
                    } catch (Exception $e) {
                        return array('err_msg' => $e->getMessage());
                    }
                    break;
                case 'unitAuthor' :
                    $acl = 'Unit Author';
                    $aclId = $_POST['unitId'];
                    $query = "UPDATE `users` SET `name` = '" . $_POST['name'] . "', `last-name` = '" . $_POST['lastName'] . "', `password` = '" . hash_hmac('sha512', Database::filter_str($_POST['passwd']), Database::filter_str($_POST['username'])) . "', `email` = '" . Database::filter_str($_POST['email']) . "', `access-level` = '" . $acl . "', `access-level-id` = '" . $aclId . "' WHERE `id` = " . $_POST['userId'] . ";";
                    try {
                        Database::execute_query($query);
                    } catch (Exception $e) {
                        return array('err_msg' => $e->getMessage());
                    }
                    break;
                case 'hygieneUnitAuthor' :
                    $acl = 'Hygiene Unit Author';
                    $aclId = $_POST['hygieneUnitId'];
                    $query = "UPDATE `users` SET `name` = '" . $_POST['name'] . "', `last-name` = '" . $_POST['lastName'] . "', `password` = '" . hash_hmac('sha512', Database::filter_str($_POST['passwd']), Database::filter_str($_POST['username'])) . "', `email` = '" . Database::filter_str($_POST['email']) . "', `access-level` = '" . $acl . "', `access-level-id` = '" . $aclId . "' WHERE `id` = " . $_POST['userId'] . ";";
                    try {
                        Database::execute_query($query);
                    } catch (Exception $e) {
                        return array('err_msg' => $e->getMessage());
                    }
                    break;
                case 'multiAuthor' :
                    $acl = 'Multi Author';
                    $query = "UPDATE `users` SET `name` = '" . $_POST['name'] . "', `last-name` = '" . $_POST['lastName'] . "', `password` = '" . hash_hmac('sha512', Database::filter_str($_POST['passwd']), Database::filter_str($_POST['username'])) . "', `email` = '" . Database::filter_str($_POST['email']) . "', `access-level` = '" . $acl . "', `center-author-ids` = '" . implode(', ', $_POST['centerId']) . "', `hygiene-unit-author-ids` = '" . implode(', ', $_POST['hygieneUnitId']) . "' WHERE `id` = " . $_POST['userId'] . ";";
                    try {
                        Database::execute_query($query);
                    } catch (Exception $e) {
                        return array('err_msg' => $e->getMessage());
                    }
                    break;
            }
        } else if (isset($_POST['accessLevel']) and !isset($_POST['passwd'])) {
            $acl = '';
            $aclId = 0;
            switch ($_POST['accessLevel']) {
                case 'stateManager' :
                    $acl = 'State Manager';
                    $aclId = $_POST['stateId'];
                    try {
                        Database::execute_query("UPDATE `users` SET `name` = '" . $_POST['name'] . "', `last-name` = '" . $_POST['lastName'] . "', `email` = '" . Database::filter_str($_POST['email']) . "', `access-level` = '" . $acl . "', `access-level-id` = '" . $aclId . "' WHERE `id` = " . $_POST['userId'] . ";");
                    } catch (Exception $e) {
                        return array('err_msg' => $e->getMessage());
                    }
                    break;
                case 'townManager' :
                    $acl = 'Town Manager';
                    $aclId = $_POST['townId'];
                    try {
                        Database::execute_query("UPDATE `users` SET `name` = '" . $_POST['name'] . "', `last-name` = '" . $_POST['lastName'] . "', `email` = '" . Database::filter_str($_POST['email']) . "', `access-level` = '" . $acl . "', `access-level-id` = '" . $aclId . "' WHERE `id` = " . $_POST['userId'] . ";");
                        Database::execute_query("UPDATE `town-manager-acl` SET `addCenterDrugsAndConsumingEquipments` = '" . intval(isset($_POST['addCenterDrugsAndConsumingEquipments'])) . "', `addCenterNonConsumingEquipments` = '" . intval(isset($_POST['addCenterNonConsumingEquipments'])) . "', `manageCenterBuildings` = '" . intval(isset($_POST['manageCenterBuildings'])) . "', `addCenterBuildingCharge` = '" . intval(isset($_POST['addCenterBuildingCharge'])) . "', `manageCenterVehicles` = '" . intval(isset($_POST['manageCenterVehicles'])) . "', `addCenterVehicleCharge` = '" . intval(isset($_POST['addCenterVehicleCharge'])) . "', `addCenterGeneralCharge` = '" . intval(isset($_POST['addCenterGeneralCharge'])) . "' WHERE `user-id` = " . $_POST['userId'] . ";");
                    } catch (Exception $e) {
                        return array('err_msg' => $e->getMessage());
                    }
                    break;
                case 'centerManager' :
                    $acl = 'Center Manager';
                    $aclId = $_POST['centerId'];
                    try {
                        Database::execute_query("UPDATE `users` SET `name` = '" . $_POST['name'] . "', `last-name` = '" . $_POST['lastName'] . "', `email` = '" . Database::filter_str($_POST['email']) . "', `access-level` = '" . $acl . "', `access-level-id` = '" . $aclId . "' WHERE `id` = " . $_POST['userId'] . ";");
                        Database::execute_query("UPDATE `center-manager-acl` SET `addCenterDrugsAndConsumingEquipments` = '" . intval(isset($_POST['addCenterDrugsAndConsumingEquipments'])) . "', `addCenterNonConsumingEquipments` = '" . intval(isset($_POST['addCenterNonConsumingEquipments'])) . "', `manageCenterBuildings` = '" . intval(isset($_POST['manageCenterBuildings'])) . "', `addCenterBuildingCharge` = '" . intval(isset($_POST['addCenterBuildingCharge'])) . "', `manageCenterVehicles` = '" . intval(isset($_POST['manageCenterVehicles'])) . "', `addCenterVehicleCharge` = '" . intval(isset($_POST['addCenterVehicleCharge'])) . "', `addCenterGeneralCharge` = '" . intval(isset($_POST['addCenterGeneralCharge'])) . "' WHERE `user-id` = " . $_POST['userId'] . ";");
                    } catch (Exception $e) {
                        return array('err_msg' => $e->getMessage());
                    }
                    break;
                case 'unitManager' :
                    $acl = 'Unit Manager';
                    $aclId = $_POST['unitId'];
                    try {
                        Database::execute_query("UPDATE `users` SET `name` = '" . $_POST['name'] . "', `last-name` = '" . $_POST['lastName'] . "', `email` = '" . Database::filter_str($_POST['email']) . "', `access-level` = '" . $acl . "', `access-level-id` = '" . $aclId . "' WHERE `id` = " . $_POST['userId'] . ";");
                        Database::execute_query("UPDATE `unit-manager-acl` SET `manageUnitPersonnel` = '" . intval(isset($_POST['manageUnitPersonnel'])) . "', `unitPersonnelFinancialInfo` = '" . intval(isset($_POST['unitPersonnelFinancialInfo'])) . "', `manageUnitServices` = '" . intval(isset($_POST['manageUnitServices'])) . "' WHERE `user-id` = '" . $_POST['userId'] . "';");
                    } catch (Exception $e) {
                        return array('err_msg' => $e->getMessage());
                    }
                    break;
                case 'hygieneUnitManager' :
                    $acl = 'Hygiene Unit Manager';
                    $aclId = $_POST['hygieneUnitId'];
                    try {
                        Database::execute_query("UPDATE `users` SET `name` = '" . $_POST['name'] . "', `last-name` = '" . $_POST['lastName'] . "', `email` = '" . Database::filter_str($_POST['email']) . "', `access-level` = '" . $acl . "', `access-level-id` = '" . $aclId . "' WHERE `id` = " . $_POST['userId'] . ";");
                        Database::execute_query("UPDATE `hygiene-unit-manager-acl` SET `addHygieneUnitDrugsAndConsumingEquipments` = '" . intval(isset($_POST['addHygieneUnitDrugsAndConsumingEquipments'])) . "', `addHygieneUnitNonConsumingEquipments` = '" . intval(isset($_POST['addHygieneUnitNonConsumingEquipments'])) . "', `manageHygieneUnitPersonnel` = '" . intval(isset($_POST['manageHygieneUnitPersonnel'])) . "', `hygieneUnitPersonnelFinancialInfo` = '" . intval(isset($_POST['hygieneUnitPersonnelFinancialInfo'])) . "', `manageHygieneUnitBuildings` = '" . intval(isset($_POST['manageHygieneUnitBuildings'])) . "', `addHygieneUnitBuildingCharge` = '" . intval(isset($_POST['addHygieneUnitBuildingCharge'])) . "', `manageHygieneUnitVehicles` = '" . intval(isset($_POST['manageHygieneUnitVehicles'])) . "', `addHygieneUnitVehicleCharge` = '" . intval(isset($_POST['addHygieneUnitVehicleCharge'])) . "', `manageHygieneUnitServices` = '" . intval(isset($_POST['manageHygieneUnitServices'])) . "', `addHygieneUnitGeneralCharge` = '" . intval(isset($_POST['addHygieneUnitGeneralCharge'])) . "' WHERE `user-id` = " . $_POST['userId'] . ";");
                    } catch (Exception $e) {
                        return array('err_msg' => $e->getMessage());
                    }
                    break;
                case 'stateAuthor' :
                    $acl = 'State Author';
                    $aclId = $_POST['stateId'];
                    $query = "UPDATE `users` SET `name` = '" . $_POST['name'] . "', `last-name` = '" . $_POST['lastName'] . "', `email` = '" . Database::filter_str($_POST['email']) . "', `access-level` = '" . $acl . "', `access-level-id` = '" . $aclId . "' WHERE `id` = " . $_POST['userId'] . ";";
                    try {
                        Database::execute_query($query);
                    } catch (Exception $e) {
                        return array('err_msg' => $e->getMessage());
                    }
                    break;
                case 'townAuthor' :
                    $acl = 'Town Author';
                    $aclId = $_POST['townId'];
                    $query = "UPDATE `users` SET `name` = '" . $_POST['name'] . "', `last-name` = '" . $_POST['lastName'] . "', `email` = '" . Database::filter_str($_POST['email']) . "', `access-level` = '" . $acl . "', `access-level-id` = '" . $aclId . "' WHERE `id` = " . $_POST['userId'] . ";";
                    try {
                        Database::execute_query($query);
                    } catch (Exception $e) {
                        return array('err_msg' => $e->getMessage());
                    }
                    break;
                case 'centerAuthor' :
                    $acl = 'Center Author';
                    $aclId = $_POST['centerId'];
                    $query = "UPDATE `users` SET `name` = '" . $_POST['name'] . "', `last-name` = '" . $_POST['lastName'] . "', `email` = '" . Database::filter_str($_POST['email']) . "', `access-level` = '" . $acl . "', `access-level-id` = '" . $aclId . "' WHERE `id` = " . $_POST['userId'] . ";";
                    try {
                        Database::execute_query($query);
                    } catch (Exception $e) {
                        return array('err_msg' => $e->getMessage());
                    }
                    break;
                case 'unitAuthor' :
                    $acl = 'Unit Author';
                    $aclId = $_POST['unitId'];
                    $query = "UPDATE `users` SET `name` = '" . $_POST['name'] . "', `last-name` = '" . $_POST['lastName'] . "', `email` = '" . Database::filter_str($_POST['email']) . "', `access-level` = '" . $acl . "', `access-level-id` = '" . $aclId . "' WHERE `id` = " . $_POST['userId'] . ";";
                    try {
                        Database::execute_query($query);
                    } catch (Exception $e) {
                        return array('err_msg' => $e->getMessage());
                    }
                    break;
                case 'hygieneUnitAuthor' :
                    $acl = 'Hygiene Unit Author';
                    $aclId = $_POST['hygieneUnitId'];
                    $query = "UPDATE `users` SET `name` = '" . $_POST['name'] . "', `last-name` = '" . $_POST['lastName'] . "', `email` = '" . Database::filter_str($_POST['email']) . "', `access-level` = '" . $acl . "', `access-level-id` = '" . $aclId . "' WHERE `id` = " . $_POST['userId'] . ";";
                    try {
                        Database::execute_query($query);
                    } catch (Exception $e) {
                        return array('err_msg' => $e->getMessage());
                    }
                    break;
                case 'multiAuthor' :
                    $acl = 'Multi Author';
                    $query = "UPDATE `users` SET `name` = '" . $_POST['name'] . "', `last-name` = '" . $_POST['lastName'] . "', `email` = '" . Database::filter_str($_POST['email']) . "', `access-level` = '" . $acl . "', `center-author-ids` = '" . implode(', ', $_POST['centerId']) . "', `hygiene-unit-author-ids` = '" . implode(', ', $_POST['hygieneUnitId']) . "' WHERE `id` = " . $_POST['userId'] . ";";
                    try {
                        Database::execute_query($query);
                    } catch (Exception $e) {
                        return array('err_msg' => $e->getMessage());
                    }
                    break;
            }
        } else if (!isset($_POST['accessLevel']) and isset($_POST['passwd'])) {
            if (!(isset($_POST['passwd']) && isset($_POST['passwdconfirm']) && $_POST['passwd'] === $_POST['passwdconfirm'] && $_POST['passwd'] !== '')) $invalid['passwd'] = true;
            if (isset($invalid)) return array('invalid' => $invalid, 'err_msg' => 'اطلاعات دریافت شده معتبر نیست.');
            $query = "UPDATE `users` SET `name` = '" . $_POST['name'] . "', `last-name` = '" . $_POST['lastName'] . "', `password` = '" . hash_hmac('sha512', Database::filter_str($_POST['passwd']), Database::filter_str($_POST['username'])) . "', `email` = '" . Database::filter_str($_POST['email']) . "' WHERE `id` = " . $_POST['userId'] . ";";
            try {
                Database::execute_query($query);
            } catch (Exception $e) {
                return array('err_msg' => $e->getMessage());
            }
        } else if (!isset($_POST['accessLevel']) and !isset($_POST['passwd'])) {
            $query = "UPDATE `users` SET `name` = '" . $_POST['name'] . "', `last-name` = '" . $_POST['lastName'] . "', `email` = '" . Database::filter_str($_POST['email']) . "' WHERE `id` = " . $_POST['userId'] . ";";
            try {
                Database::execute_query($query);
            } catch (Exception $e) {
                return array('err_msg' => $e->getMessage());
            }
        }
        $_logTXT = 'اصلاح کاربر به‌نام "' . Database::filter_str($_POST['name']) . ' ' . Database::filter_str($_POST['lastName']) . '" در سیستم';
        self::_makeLog($_logTXT);
        header('Location: ' . substr(strrchr($_SERVER['PHP_SELF'], '/'), 1) . '?cmd=manageUsers');
        exit;
    }

    private static function count_days($a, $b)
    {
        $gd_a = getdate($a);
        $gd_b = getdate($b);
        $a_new = mktime(12, 0, 0, $gd_a['mon'], $gd_a['mday'], $gd_a['year']);
        $b_new = mktime(12, 0, 0, $gd_b['mon'], $gd_b['mday'], $gd_b['year']);
        return round(($a_new - $b_new) / 86400);
    }

    private static function setInitialDataForCenter($centerName)
    {
        $res = Database::execute_query("SELECT MAX(`id`) FROM `centers` WHERE `name` = '" . $centerName . "'");
        $row = Database::get_assoc_array($res);
        $addedCenterId = $row['MAX(`id`)'];
        $unitNames = array('بهداشت خانواده', 'بهداشت محیط و حرفه ای', 'بهداشت دهان و دندان', 'واحد دارویی', 'مامایی', 'پزشکی', 'متصدی امور عمومی', 'مبارزه با بیماریها');
        $query = "INSERT INTO `units` (`id`, `name`, `center-id`, `lastdate-of-done-services-register`) VALUES ";
        for ($i = 0; $i < count($unitNames); $i++) {
            $query .= "(NULL, '" . $unitNames[$i] . "', '" . $addedCenterId . "', '')";
            if ($i != (count($unitNames) - 1)) $query .= ", ";
        }
        $query .= ";";
        Database::execute_query($query);
        $unitServicesId = array();
        foreach ($unitNames as $value) {
            switch ($value) {
                case 'بهداشت خانواده' :
                    $unitServicesId = array('2', '3', '4', '5', '8', '7', '9', '10', '11', '12', '13', '14');
                    $res = Database::execute_query("SELECT `id` FROM `units` WHERE `name` = 'بهداشت خانواده' AND `center-id` = '" . $addedCenterId . "';");
                    $row = Database::get_assoc_array($res);
                    $addedUnitId = $row['id'];
                    $query = "INSERT INTO `unit-services` (`id`, `service-id`, `unit-id`) VALUES ";
                    for ($i = 0; $i < count($unitServicesId); $i++) {
                        $query .= "(NULL, '" . $unitServicesId[$i] . "', '" . $addedUnitId . "')";
                        if ($i != (count($unitServicesId) - 1)) $query .= ", ";
                    }
                    $query .= ";";
                    Database::execute_query($query);
                    break;
                case 'بهداشت محیط و حرفه ای' :
                    $unitServicesId = array('15', '16', '142', '18', '19', '20', '21', '22', '23', '24', '25', '26', '29', '30', '31', '32', '33', '34', '35', '36', '37', '38', '39', '40', '41', '42', '43', '44', '45');
                    $res = Database::execute_query("SELECT `id` FROM `units` WHERE `name` = 'بهداشت محیط و حرفه ای' AND `center-id` = '" . $addedCenterId . "';");
                    $row = Database::get_assoc_array($res);
                    $addedUnitId = $row['id'];
                    $query = "INSERT INTO `unit-services` (`id`, `service-id`, `unit-id`) VALUES ";
                    for ($i = 0; $i < count($unitServicesId); $i++) {
                        $query .= "(NULL, '" . $unitServicesId[$i] . "', '" . $addedUnitId . "')";
                        if ($i != (count($unitServicesId) - 1)) $query .= ", ";
                    }
                    $query .= ";";
                    Database::execute_query($query);
                    break;
                case 'بهداشت دهان و دندان' :
                    $unitServicesId = array('46', '47', '48', '49', '50', '51', '52', '53', '54', '55');
                    $res = Database::execute_query("SELECT `id` FROM `units` WHERE `name` = 'بهداشت دهان و دندان' AND `center-id` = '" . $addedCenterId . "';");
                    $row = Database::get_assoc_array($res);
                    $addedUnitId = $row['id'];
                    $query = "INSERT INTO `unit-services` (`id`, `service-id`, `unit-id`) VALUES ";
                    for ($i = 0; $i < count($unitServicesId); $i++) {
                        $query .= "(NULL, '" . $unitServicesId[$i] . "', '" . $addedUnitId . "')";
                        if ($i != (count($unitServicesId) - 1)) $query .= ", ";
                    }
                    $query .= ";";
                    Database::execute_query($query);
                    break;
                case 'واحد دارویی' :
                    $unitServicesId = array('56', '57', '58', '59', '60', '61', '62');
                    $res = Database::execute_query("SELECT `id` FROM `units` WHERE `name` = 'واحد دارویی' AND `center-id` = '" . $addedCenterId . "';");
                    $row = Database::get_assoc_array($res);
                    $addedUnitId = $row['id'];
                    $query = "INSERT INTO `unit-services` (`id`, `service-id`, `unit-id`) VALUES ";
                    for ($i = 0; $i < count($unitServicesId); $i++) {
                        $query .= "(NULL, '" . $unitServicesId[$i] . "', '" . $addedUnitId . "')";
                        if ($i != (count($unitServicesId) - 1)) $query .= ", ";
                    }
                    $query .= ";";
                    Database::execute_query($query);
                    break;
                case 'مامایی' :
                    $unitServicesId = array('63', '64', '65', '66', '67', '68', '69', '70', '71');
                    $res = Database::execute_query("SELECT `id` FROM `units` WHERE `name` = 'مامایی' AND `center-id` = '" . $addedCenterId . "';");
                    $row = Database::get_assoc_array($res);
                    $addedUnitId = $row['id'];
                    $query = "INSERT INTO `unit-services` (`id`, `service-id`, `unit-id`) VALUES ";
                    for ($i = 0; $i < count($unitServicesId); $i++) {
                        $query .= "(NULL, '" . $unitServicesId[$i] . "', '" . $addedUnitId . "')";
                        if ($i != (count($unitServicesId) - 1)) $query .= ", ";
                    }
                    $query .= ";";
                    Database::execute_query($query);
                    break;
                case 'پزشکی' :
                    $unitServicesId = array('72', '73', '74', '75', '76', '77', '78', '79', '80', '81');
                    $res = Database::execute_query("SELECT `id` FROM `units` WHERE `name` = 'پزشکی' AND `center-id` = '" . $addedCenterId . "';");
                    $row = Database::get_assoc_array($res);
                    $addedUnitId = $row['id'];
                    $query = "INSERT INTO `unit-services` (`id`, `service-id`, `unit-id`) VALUES ";
                    for ($i = 0; $i < count($unitServicesId); $i++) {
                        $query .= "(NULL, '" . $unitServicesId[$i] . "', '" . $addedUnitId . "')";
                        if ($i != (count($unitServicesId) - 1)) $query .= ", ";
                    }
                    $query .= ";";
                    Database::execute_query($query);
                    break;
                case 'متصدی امور عمومی' :
                    $unitServicesId = array('82', '83', '84', '85', '86', '87', '88', '89', '90', '91', '92', '93', '94');
                    $res = Database::execute_query("SELECT `id` FROM `units` WHERE `name` = 'متصدی امور عمومی' AND `center-id` = '" . $addedCenterId . "';");
                    $row = Database::get_assoc_array($res);
                    $addedUnitId = $row['id'];
                    $query = "INSERT INTO `unit-services` (`id`, `service-id`, `unit-id`) VALUES ";
                    for ($i = 0; $i < count($unitServicesId); $i++) {
                        $query .= "(NULL, '" . $unitServicesId[$i] . "', '" . $addedUnitId . "')";
                        if ($i != (count($unitServicesId) - 1)) $query .= ", ";
                    }
                    $query .= ";";
                    Database::execute_query($query);
                    break;
                case 'مبارزه با بیماریها' :
                    $unitServicesId = array('95', '96', '97', '98', '99', '100', '101', '102', '103', '104', '105', '106', '107', '108');
                    $res = Database::execute_query("SELECT `id` FROM `units` WHERE `name` = 'مبارزه با بیماریها' AND `center-id` = '" . $addedCenterId . "';");
                    $row = Database::get_assoc_array($res);
                    $addedUnitId = $row['id'];
                    $query = "INSERT INTO `unit-services` (`id`, `service-id`, `unit-id`) VALUES ";
                    for ($i = 0; $i < count($unitServicesId); $i++) {
                        $query .= "(NULL, '" . $unitServicesId[$i] . "', '" . $addedUnitId . "')";
                        if ($i != (count($unitServicesId) - 1)) $query .= ", ";
                    }
                    $query .= ";";
                    Database::execute_query($query);
                    break;
            }
        }
    }

    private static function setInitialDataForHygieneUnit($hygieneUnitName)
    {
        $res = Database::execute_query("SELECT MAX(`id`) FROM `hygiene-units` WHERE `name` = '" . $hygieneUnitName . "';");
        $row = Database::get_assoc_array($res);
        $addedHygieneUnitId = $row['MAX(`id`)'];
        $hygieneUnitServicesId = array('1', '109', '110', '111', '112', '113', '114', '115', '116', '117', '118', '119', '120', '121', '122', '123', '124', '125', '126', '127', '128', '129', '130', '131', '132', '133', '134', '135', '136', '137', '138', '139', '140', '141', '143', '144', '145', '146', '147', '148', '149', '150', '151', '152', '153', '154', '155', '156', '157', '158', '159', '160', '161', '162', '163', '164', '165', '166', '167', '168', '169', '170');
        $query = "INSERT INTO `hygiene-unit-services` (`id`, `service-id`, `hygiene-unit-id`) VALUES ";
        for ($i = 0; $i < count($hygieneUnitServicesId); $i++) {
            $query .= "(NULL, '" . $hygieneUnitServicesId[$i] . "', '" . $addedHygieneUnitId . "')";
            if ($i != (count($hygieneUnitServicesId) - 1)) $query .= ", ";
        }
        $query .= ";";
        Database::execute_query($query);
    }

    public function _makeLog($actionDetails = '')
    {
        $dateTimeObj = new DateTime();
        $dateTimeZone = new DateTimeZone('Asia/Tehran');
        $dateTimeObj->setTimezone($dateTimeZone);
        $shamsiDate = gregorian_to_jalali($dateTimeObj->format('Y'), $dateTimeObj->format('m'), $dateTimeObj->format('d'));
        $date = $shamsiDate[0] . "/" . $shamsiDate[1] . "/" . $shamsiDate[2] . " " . $dateTimeObj->format('H:i:s');
        try {
            Database::execute_query("INSERT INTO `_logs` (`id`, `user-id`, `action-details`, `_log-date`) VALUES (NULL, '" . $_SESSION['user']['userId'] . "', '$actionDetails', '$date');");
        } catch (exception $e) {
            return FALSE;
        }
        return TRUE;
    }

    public static function processAddSibJobTitleForm() {
        if ($_SERVER['REQUEST_METHOD'] != 'POST') return isset($_GET['success']) ? array('success' => true) : false;
        if (!Narin::check_token()) return array('err_msg' => 'به دلیل وجود یک مشکل امنیتی، اطلاعات ثبت نشد. لطفاً دوباره تلاش کنید.');
        if (!(isset($_POST['input_job_title']) && Narin::validate_required('input_job_title') && strlen($_POST['input_job_title']) <= 50)) $invalid['input_job_title'] = true;
        if (isset($invalid)) return array('invalid' => $invalid, 'err_msg' => 'اطلاعات دریافت شده معتبر نیست.');
        try {
            Database::execute_query("INSERT INTO `sib_job_titles` (`title`) VALUES ('" . Database::filter_str($_POST['input_job_title']) . "');");
        } catch (exception $e) {
            return array('err_msg' => $e->getMessage());
        }
        $_logTXT = 'افزودن عنوان شغلی سیب "' . Database::filter_str($_POST['input_job_title']) . '"';
        self::_makeLog($_logTXT);
        header('Location: ' . substr(strrchr($_SERVER['PHP_SELF'], '/'), 1) . '?success');
        exit;
    }

    public static function proccessEditSibJobTitleForm()
    {
        if ($_SERVER['REQUEST_METHOD'] != 'POST') return isset($_GET['success']) ? array('success' => true) : false;
        if (!Narin::check_token()) return array('err_msg' => 'به دلیل وجود یک مشکل امنیتی، اطلاعات ثبت نشد. لطفاً دوباره تلاش کنید.');
        if (!(isset($_POST['input_job_title']) && Narin::validate_required('input_job_title') && strlen($_POST['input_job_title']) <= 50)) $invalid['input_job_title'] = true;
        if (isset($invalid)) return array('invalid' => $invalid, 'err_msg' => 'اطلاعات دریافت شده معتبر نیست.');
        try {
            Database::execute_query("UPDATE `sib_job_titles` SET `title` = '" . Database::filter_str($_POST['input_job_title']) . "' WHERE `id` = '" . Database::filter_str($_POST['jobId']) . "';");
        } catch (exception $e) {
            return array('err_msg' => $e->getMessage());
        }
        $_logTXT = 'اصلاح عنوان شغل سیب "' . Database::filter_str($_POST['input_job_title']) . '"';
        self::_makeLog($_logTXT);
        header('Location: ' . substr(strrchr($_SERVER['PHP_SELF'], '/'), 1) . '?cmd=manageSibJobsAndPeriods');
        exit;
    }

    public static function processAddSibPeriodForm() {
        if ($_SERVER['REQUEST_METHOD'] != 'POST') return isset($_GET['success']) ? array('success' => true) : false;
        if (!Narin::check_token()) return array('err_msg' => 'به دلیل وجود یک مشکل امنیتی، اطلاعات ثبت نشد. لطفاً دوباره تلاش کنید.');
        if (!(isset($_POST['input_period_name']) && Narin::validate_required('input_period_name') && strlen($_POST['input_period_name']) <= 50)) $invalid['input_period_name'] = true;
        if (isset($invalid)) return array('invalid' => $invalid, 'err_msg' => 'اطلاعات دریافت شده معتبر نیست.');
        try {
            Database::execute_query("INSERT INTO `sib_periods` (`name`) VALUES ('" . Database::filter_str($_POST['input_period_name']) . "');");
        } catch (exception $e) {
            return array('err_msg' => $e->getMessage());
        }
        $_logTXT = 'افزودن دوره زندگی سیب "' . Database::filter_str($_POST['input_period_name']) . '"';
        self::_makeLog($_logTXT);
        header('Location: ' . substr(strrchr($_SERVER['PHP_SELF'], '/'), 1) . '?success');
        exit;
    }


    public static function proccessEditSibPeriodForm()
    {
        if ($_SERVER['REQUEST_METHOD'] != 'POST') return isset($_GET['success']) ? array('success' => true) : false;
        if (!Narin::check_token()) return array('err_msg' => 'به دلیل وجود یک مشکل امنیتی، اطلاعات ثبت نشد. لطفاً دوباره تلاش کنید.');
        if (!(isset($_POST['input_period_name']) && Narin::validate_required('input_period_name') && strlen($_POST['input_period_name']) <= 50)) $invalid['input_period_name'] = true;
        if (isset($invalid)) return array('invalid' => $invalid, 'err_msg' => 'اطلاعات دریافت شده معتبر نیست.');
        try {
            Database::execute_query("UPDATE `sib_periods` SET `name` = '" . Database::filter_str($_POST['input_period_name']) . "' WHERE `id` = '" . Database::filter_str($_POST['periodId']) . "';");
        } catch (exception $e) {
            return array('err_msg' => $e->getMessage());
        }
        $_logTXT = 'اصلاح دوره زندگی سیب "' . Database::filter_str($_POST['input_period_name']) . '"';
        self::_makeLog($_logTXT);
        header('Location: ' . substr(strrchr($_SERVER['PHP_SELF'], '/'), 1) . '?cmd=manageSibJobsAndPeriods');
        exit;
    }
}