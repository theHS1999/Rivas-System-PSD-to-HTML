<?php
/**
 *
 * sib.php
 * state model class file
 *
 * @copyright	Copyright (C) 2012 Rivas Systems Inc. All rights reserved.
 * @versin	1.00 2020-10-01
 * @author	Homayoon Salehkia
 *
 */

require_once dirname(dirname(__file__)) . '/database/database.php';

class sib
{
    public static function viewSibJobsAndPeriods() {
        $htmlOut = '';
        $htmlOut .= '<p style="text-align: right; font-weight: bold; font-size: 15px; margin-right: 20px;"><a href="#" style="color: green;" id="slideLink"><img src="../illustrator/images/icons/plus.png" />&nbsp;افزودن عنوان شغلی</a></p>';
        $htmlOut .= '<form action="' . $_SERVER['PHP_SELF'] . '" method="post" id="slideForm" class="narin">';
        $htmlOut .= '<input type="hidden" name="narin:token" value="' . Narin::create_token() . '" />';
        $htmlOut .= '<fieldset><legend>افزودن عنوان شغلی</legend><br /><label for="input_job_title" class="required">عنوان شغل :</label>';
        $htmlOut .= '<input type="text" name="input_job_title" id="input_job_title" maxlength="256" ';
        if (isset($_POST['input_job_title']) && $_POST['input_job_title'] !== '')
            $htmlOut .= ' value="' . htmlspecialchars($_POST['input_job_title']) . '"';
        $htmlOut .= ' class="validate required ';
        if (isset($r['invalid']['input_job_title']))
            $htmlOut .= 'invalid';
        $htmlOut .= '" />';
        $htmlOut .= '<span class="description" style="display:none;">فقط حروف فارسی</span>';
        $htmlOut .= '&nbsp;&nbsp;&nbsp;<input type="submit" value="ثبت عنوان شغلی" name="submitAddSibJobTitle" /></fieldset></form>';

        $htmlOut .= '<p style="text-align: right; font-weight: bold; font-size: 15px; margin-right: 20px;"><a href="#" style="color: green;" id="slideLink1"><img src="../illustrator/images/icons/plus.png" />&nbsp;افزودن دوره زندگی</a></p>';
        $htmlOut .= '<form action="' . $_SERVER['PHP_SELF'] . '" method="post" id="slideForm1" class="narin">';
        $htmlOut .= '<input type="hidden" name="narin:token" value="' . Narin::create_token() . '" />';
        $htmlOut .= '<fieldset><legend>افزودن دوره زندگی</legend><br /><label for="input_period_name" class="required">نام دوره زندگی :</label>';
        $htmlOut .= '<input type="text" name="input_period_name" id="input_period_name" maxlength="256" ';
        if (isset($_POST['input_period_name']) && $_POST['input_period_name'] !== '')
            $htmlOut .= ' value="' . htmlspecialchars($_POST['input_period_name']) . '"';
        $htmlOut .= ' class="validate required ';
        if (isset($r['invalid']['input_period_name']))
            $htmlOut .= 'invalid';
        $htmlOut .= '" />';
        $htmlOut .= '<span class="description" style="display:none;">فقط حروف فارسی</span>';
        $htmlOut .= '&nbsp;&nbsp;&nbsp;<input type="submit" value="ثبت دوره زندگی" name="submitAddSibPeriod" /></fieldset></form>';

        $htmlOut .= '<div>';
        $jobtitles = Database::execute_query("SELECT * FROM  `sib_job_titles` ORDER BY `id` ASC;");
        $counter = 1;
        if (Database::num_of_rows($jobtitles) > 0) {
            $htmlOut .= '<fieldset><legend>فهرست عناوین شغلی سیب</legend>';
            $htmlOut .= '<br /><table align="right" class="contentTable" border="0" cellpadding="0" cellspacing="1">';
            $htmlOut .= '<thead><th width="40">ردیف</th><th>عنوان</th><th width="50">حذف</th><th width="50">ویرایش</th></thead><tbody>';
            while ($row = Database::get_assoc_array($jobtitles)) {
                $htmlOut .= '<tr><td>' . $counter . '</td><td>' . $row['title'] . '</td>';
                $htmlOut .= '<td><a href="?cmd=removeSibJobTitle&jobId=' . $row['id'] . '"><img src="../illustrator/images/icons/remove.png" title="حذف عنوان شغلی ' . $row['title'] . '" /></a></td>';
                $htmlOut .= '<td><a href="?cmd=editSibJobTitle&jobId=' . $row['id'] . '"><img src="../illustrator/images/icons/edit.png" title="ویرایش عنوان شغلی ' . $row['title'] . '" /></a></td></tr>';
                $counter++;
            }

            $htmlOut .= '</tbody></table></fielset><br />';
        } else
            $htmlOut .= NO_SIB_JOB_TITLES;

        $htmlOut .= '</div>';

        $htmlOut .= '<div style="margin-top: 30px;">';
        $periods = Database::execute_query("SELECT * FROM  `sib_periods` ORDER BY `id` ASC;");
        $counter = 1;
        if (Database::num_of_rows($periods) > 0) {
            $htmlOut .= '<fieldset><legend>فهرست دوره های زندگی سیب</legend>';
            $htmlOut .= '<br /><table align="right" class="contentTable" border="0" cellpadding="0" cellspacing="1">';
            $htmlOut .= '<thead><th width="40">ردیف</th><th>نام</th><th width="50">حذف</th><th width="50">ویرایش</th></thead><tbody>';
            while ($row = Database::get_assoc_array($periods)) {
                $htmlOut .= '<tr><td>' . $counter . '</td><td>' . $row['name'] . '</td>';
                $htmlOut .= '<td><a href="?cmd=removeSibPeriod&periodId=' . $row['id'] . '"><img src="../illustrator/images/icons/remove.png" title="حذف دوره ' . $row['name'] . '" /></a></td>';
                $htmlOut .= '<td><a href="?cmd=editSibPeriod&periodId=' . $row['id'] . '"><img src="../illustrator/images/icons/edit.png" title="ویرایش دوره ' . $row['name'] . '" /></a></td></tr>';
                $counter++;
            }

            $htmlOut .= '</tbody></table></fielset><br />';
        } else
            $htmlOut .= NO_SIB_PERIODS;

        $htmlOut .= '</div>';




        return $htmlOut;
    }

    public static function editJobTitle($id) {
        $htmlOut = '';
        $dbRes = Database::execute_query("SELECT * FROM `sib_job_titles` WHERE `id` = '" . $id . "' LIMIT 1;");
        $row = Database::get_assoc_array($dbRes);
        $htmlOut .= '<form action="' . substr(strrchr($_SERVER['PHP_SELF'], '/'), 1) . '" method="post" class="narin">';
        $htmlOut .= '<input type="hidden" name="narin:token" value="' . Narin::create_token() . '" />';
        $htmlOut .= '<input type="hidden" name="jobId" value="' . $id . '" />';
        $htmlOut .= '<fieldset><legend>ویرایش عنوان شغلی سیب</legend><br /><label for="input_job_title" class="required">عنوان شغل :</label>';
        $htmlOut .= '<input type="text" name="input_job_title" id="input_job_title" maxlength="256" ';
        $htmlOut .= ' class="validate required ';
        if (isset($r['invalid']['input_job_title']))
            $htmlOut .= 'invalid';
        $htmlOut .= '" value="' . $row['title'] . '" />';
        $htmlOut .= '<span class="description" style="display:none;">فقط حروف فارسی</span>';
        $htmlOut .= '&nbsp;&nbsp;&nbsp;<input type="submit" value="ویرایش عنوان شغلی" name="submitEditSibJobTitle" /></fieldset></form>';

        return $htmlOut;
    }

    public static function editPeriod($id) {
        $htmlOut = '';
        $dbRes = Database::execute_query("SELECT * FROM `sib_periods` WHERE `id` = '" . $id . "' LIMIT 1;");
        $row = Database::get_assoc_array($dbRes);
        $htmlOut .= '<form action="' . substr(strrchr($_SERVER['PHP_SELF'], '/'), 1) . '" method="post" class="narin">';
        $htmlOut .= '<input type="hidden" name="narin:token" value="' . Narin::create_token() . '" />';
        $htmlOut .= '<input type="hidden" name="periodId" value="' . $id . '" />';
        $htmlOut .= '<fieldset><legend>ویرایش دوره زندگی سیب</legend><br /><label for="input_period_name" class="required"> نام دوره :</label>';
        $htmlOut .= '<input type="text" name="input_period_name" id="input_period_name" maxlength="256" ';
        $htmlOut .= ' class="validate required ';
        if (isset($r['invalid']['input_period_name']))
            $htmlOut .= 'invalid';
        $htmlOut .= '" value="' . $row['name'] . '" />';
        $htmlOut .= '<span class="description" style="display:none;">فقط حروف فارسی</span>';
        $htmlOut .= '&nbsp;&nbsp;&nbsp;<input type="submit" value="ویرایش دوره زندگی" name="submitEditSibPeriod" /></fieldset></form>';

        return $htmlOut;
    }

    public static function removeJobTitle($id) {
        $res = Database::execute_query("SELECT `title` FROM `sib_job_titles` WHERE `id` = '$id';");
        $row = Database::get_assoc_array($res);
        $_logTXT = 'حذف عنوان شغل سیب "' . $row['title'] . '" از سیستم';

        try {
            Database::execute_query("DELETE FROM `sib_job_titles` WHERE `id` = '" . $id . "';");
        } catch ( exception $e ) {
            return $e -> getMessage();
        }

        FormProccessor::_makeLog($_logTXT);

        header("Location: " . $_SERVER['HTTP_REFERER']);
        exit;
    }

    public static function removePeriod($id) {
        $res = Database::execute_query("SELECT `name` FROM `sib_periods` WHERE `id` = '$id';");
        $row = Database::get_assoc_array($res);
        $_logTXT = 'حذف دوره زندگی سیب "' . $row['name'] . '" از سیستم';

        try {
            Database::execute_query("DELETE FROM `sib_periods` WHERE `id` = '" . $id . "';");
        } catch ( exception $e ) {
            return $e -> getMessage();
        }

        FormProccessor::_makeLog($_logTXT);

        header("Location: " . $_SERVER['HTTP_REFERER']);
        exit;
    }
}