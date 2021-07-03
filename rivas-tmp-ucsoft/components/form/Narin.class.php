<?php

session_start();
define('TOKEN_LIFE', 108000);
define('SITE_KEY', 'pmHr2$7_[:";>VxzsW!%$7Uhd3gdUY86#@j[kh6hY!~T2ERdrtdWASdsjKh-0(uig');

class Narin
{
    private static $db_connection = NULL;

    private static function get_db_connection()
    {
        if (self::$db_connection) {
            return self::$db_connection;
        }
        self::$db_connection = @new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        if (self::$db_connection->connect_error) {
            throw new Exception('ثبت اطلاعات موفق نبود.', 11);
        }
        self::$db_connection->set_charset('utf8');
        return self::$db_connection;
    }

    private static function prepare_string($str)
    {
        if (get_magic_quotes_gpc()) {
            $str = stripslashes($str);
        }
        return self::get_db_connection()->real_escape_string($str);
    }

    public static function get_prepared_value($name)
    {
        return !isset($_POST[$name]) || trim($_POST[$name]) === '' ? 'NULL' : '"' . self::prepare_string($_POST[$name]) . '"';
    }

    private static function hash_password($str)
    {
        return sha1(hash_hmac('sha512', $str, SITE_KEY));
    }

    public static function get_hashed_value($name)
    {
        return !isset($_POST[$name]) || $_POST[$name] === '' ? 'NULL' : '"' . self::hash_password($_POST[$name]) . '"';
    }

    public static function get_checkbox_value($name)
    {
        return isset($_POST[$name]) ? '"1"' : '"0"';
    }

    public static function get_array_input_value($name)
    {
        if (!isset($_POST[$name])) {
            return 'NULL';
        }
        if (!is_array($_POST[$name])) {
            return trim($_POST[$name]) === '' ? 'NULL' : '"' . self::prepare_string($_POST[$name]) . '"';
        }
        return '"' . self::prepare_string(implode("\n", $_POST[$name])) . '"';
    }

    public static function get_date_value($name)
    {
        if (empty($_POST[$name . ':y']) || empty($_POST[$name . ':m']) || empty($_POST[$name . ':y'])) return 'NULL';
        $gdate = self::jalali_to_gregorian((int)$_POST[$name . ':y'], (int)$_POST[$name . ':m'], (int)$_POST[$name . ':d']);
        return '"' . $gdate[0] . '-' . ($gdate[1] > 9 ? $gdate[1] : '0' . $gdate[1]) . '-' . ($gdate[2] > 9 ? $gdate[2] : '0' . $gdate[2]) . '"';
    }

    public static function db_insert($query)
    {
        if (!self::get_db_connection()->query($query)) {
            throw new Exception('ثبت اطلاعات موفق نبود.', 12);
        }
    }

    public static function upload_file($name, $dir)
    {
        switch ($_FILES[$name]['error']) {
            case UPLOAD_ERR_NO_FILE:
                return;
            case UPLOAD_ERR_OK:
                $basename = basename($_FILES[$name]['name']);
                while (is_file($dir . $basename)) {
                    $basename = '_' . $basename;
                }
                if (!move_uploaded_file($_FILES[$name]['tmp_name'], $dir . $basename)) {
                    throw new Exception('مشکلی در روند آپلود فایل پیش آمد.', 21);
                }
                break;
            case UPLOAD_ERR_INI_SIZE:
            case UPLOAD_ERR_FORM_SIZE:
                throw new Exception('فایل آپلود شده بیش از حد بزرگ است.', 22);
                break;
            case UPLOAD_ERR_PARTIAL:
                throw new Exception('آپلود فایل موفق نبود.', 23);
                break;
            default:
                throw new Exception('مشکلی در روند آپلود فایل پیش آمد.', 24);
                break;
        }
    }

    public static function create_token()
    {
        $token = hash_hmac('md5', uniqid('', true), SITE_KEY);
        $_SESSION['narin:tokens'][$token] = time();
        return $token;
    }

    public static function check_token()
    {
        $valid = isset($_POST['narin:token']) && isset($_SESSION['narin:tokens'][$_POST['narin:token']]) && (TOKEN_LIFE == 0 || time() - $_SESSION['narin:tokens'][$_POST['narin:token']] <= TOKEN_LIFE);
        unset($_SESSION['narin:tokens'][$_POST['narin:token']]);
        return $valid;
    }

    public static function validate_required($name)
    {
        return trim($_POST[$name]) !== '';
    }

    public static function validate_required_file($name)
    {
        return $_FILES[$name]['error'] !== UPLOAD_ERR_NO_FILE;
    }

    public static function validate_required_date($name)
    {
        return $_POST[$name . ':d'] !== '0' && $_POST[$name . ':m'] !== '0' && trim($_POST[$name . ':y']) !== '';
    }

    private static function check_number_range($num, $min, $min_inc, $max, $max_inc)
    {
        return ($min === NULL || ($min_inc ? $num >= $min : $num > $min)) && ($max === NULL || ($max_inc ? $num <= $max : $num < $max));
    }

    public static function validate_integer($name, $min, $min_inc, $max, $max_inc)
    {
        $value = $_POST[$name];
        return trim($value) === '' || (preg_match('/^\\d+$/', $value) && self::check_number_range((int)$value, $min, $min_inc, $max, $max_inc));
    }

    public static function validate_decimal($name, $min, $min_inc, $max, $max_inc)
    {
        $value = $_POST[$name];
        return trim($value) === '' || (preg_match('/^\\d*\\.?\\d*$/', $value) && $value != '.' && self::check_number_range((float)$value, $min, $min_inc, $max, $max_inc));
    }

    public static function validate_email($name)
    {
        $value = $_POST[$name];
        return trim($value) === '' || filter_var($value, FILTER_VALIDATE_EMAIL) !== false;
    }

    public static function validate_farsi_word($name)
    {
        $value = $_POST[$name];
        return trim($value) === '' || preg_match('/^[‌ ضصثقفغعهخحجچپشسیيبلاتنمکكگظطزرذدئوآةيژؤإأءۀًٌٍَُِّ]*$/', $value);
    }

    public static function validate_farsi_text($name)
    {
        $value = $_POST[$name];
        return trim($value) === '' || preg_match('/^[۰۱۲۳۴۵۶۷۸۹‌\\s\\d÷\\-=×!@#$%^&*)(_+.\/،؛,\\\\}{|\\]\\[ـ«»:"<>؟ضصثقفغعهخحجچپشسیيبلاتنمکكگظطزرذدئوآةيژؤإأءۀًٌٍَُِّ]*$/', $value);
    }

    public static function validate_mobile($name)
    {
        $value = $_POST[$name];
        return trim($value) === '' || preg_match('/^0[1-9]\\d{9}$/', $value);
    }

    public static function validate_tel($name)
    {
        $value = $_POST[$name];
        return trim($value) === '' || preg_match('/^0[1-9]\\d{1,3}-[1-9]\\d{6,7}$/', $value);
    }

    public static function validate_ssn($name)
    {
        $value = $_POST[$name];
        return trim($value) === '' || preg_match('/^\\d{10}$/', $value);
    }

    public static function validate_postnum($name)
    {
        $value = $_POST[$name];
        return trim($value) === '' || preg_match('/^\\d{10}$/', $value);
    }

    public static function validate_fixedlen_num($name, $digits, $first_digit_not_zero)
    {
        $value = $_POST[$name];
        return trim($value) === '' || preg_match($first_digit_not_zero ? '/^[1-9]\\d{' . ($digits - 1) . '}$/' : '/^\\d{' . $digits . '}$/', $value);
    }

    public static function validate_regexp($name, $regexp)
    {
        $value = $_POST[$name];
        return trim($value) === '' || preg_match('/' . $regexp . '/', $value);
    }

    public static function validate_date($name)
    {
        $d = $_POST[$name . ':d'];
        $m = $_POST[$name . ':m'];
        $y = $_POST[$name . ':y'];
        return ($d === '0' && $m === '0' && trim($y) === '') || self::is_valid_jalali_date((int)$y, (int)$m, (int)$d);
    }

    private static function is_valid_jalali_date($y, $m, $d)
    {
        return !($y < 1000 || $y > 9377 || $m < 1 || $m > 12 || $d < 1 || $d > 31 || ($m > 6 && $d == 31) || ($d == 30 && $m == 12 && ((((((($y - 474) % 2820) + 474) + 38) * 682) % 2816) < 682)));
    }

    /** * Gregorian to Jalali Conversion * Copyright (C) 2000  Roozbeh Pournader and Mohammad Toossi * */
    private static function gregorian_to_jalali($g_y, $g_m, $g_d)
    {
        $g_days_in_month = array(31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
        $j_days_in_month = array(31, 31, 31, 31, 31, 31, 30, 30, 30, 30, 30, 29);
        $gy = $g_y - 1600;
        $gm = $g_m - 1;
        $gd = $g_d - 1;
        $g_day_no = 365 * $gy + self::div($gy + 3, 4) - self::div($gy + 99, 100) + self::div($gy + 399, 400);
        for ($i = 0; $i < $gm; ++$i) $g_day_no += $g_days_in_month[$i];
        if ($gm > 1 && (($gy % 4 == 0 && $gy % 100 != 0) || ($gy % 400 == 0))) $g_day_no++;
        $g_day_no += $gd;
        $j_day_no = $g_day_no - 79;
        $j_np = self::div($j_day_no, 12053);
        $j_day_no = $j_day_no % 12053;
        $jy = 979 + 33 * $j_np + 4 * self::div($j_day_no, 1461);
        $j_day_no %= 1461;
        if ($j_day_no >= 366) {
            $jy += self::div($j_day_no - 1, 365);
            $j_day_no = ($j_day_no - 1) % 365;
        }
        for ($i = 0; $i < 11 && $j_day_no >= $j_days_in_month[$i]; ++$i) $j_day_no -= $j_days_in_month[$i];
        $jm = $i + 1;
        $jd = $j_day_no + 1;
        return array($jy, $jm, $jd);
    }

    /** * Jalali to Gregorian Conversion * Copyright (C) 2000  Roozbeh Pournader and Mohammad Toossi * */
    private static function jalali_to_gregorian($j_y, $j_m, $j_d)
    {
        $g_days_in_month = array(31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
        $j_days_in_month = array(31, 31, 31, 31, 31, 31, 30, 30, 30, 30, 30, 29);
        $jy = $j_y - 979;
        $jm = $j_m - 1;
        $jd = $j_d - 1;
        $j_day_no = 365 * $jy + self::div($jy, 33) * 8 + self::div($jy % 33 + 3, 4);
        for ($i = 0; $i < $jm; ++$i) $j_day_no += $j_days_in_month[$i];
        $j_day_no += $jd;
        $g_day_no = $j_day_no + 79;
        $gy = 1600 + 400 * self::div($g_day_no, 146097);
        $g_day_no = $g_day_no % 146097;
        $leap = true;
        if ($g_day_no >= 36525) {
            $g_day_no--;
            $gy += 100 * self::div($g_day_no, 36524);
            $g_day_no = $g_day_no % 36524;
            if ($g_day_no >= 365) $g_day_no++; else $leap = false;
        }
        $gy += 4 * self::div($g_day_no, 1461);
        $g_day_no %= 1461;
        if ($g_day_no >= 366) {
            $leap = false;
            $g_day_no--;
            $gy += self::div($g_day_no, 365);
            $g_day_no = $g_day_no % 365;
        }
        for ($i = 0; $g_day_no >= $g_days_in_month[$i] + ($i == 1 && $leap); $i++) $g_day_no -= $g_days_in_month[$i] + ($i == 1 && $leap);
        $gm = $i + 1;
        $gd = $g_day_no + 1;
        return array($gy, $gm, $gd);
    }

    private static function div($a, $b)
    {
        return (int)($a / $b);
    }
}


?>
