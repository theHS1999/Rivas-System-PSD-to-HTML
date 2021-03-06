<?php
define("ROOT_DIR", '/');
define("APP_DIR", 'components/application/');
define("COM_DIR", 'components/');
define("USR_DIR", 'components/users/');
define("DB_DIR", 'components/database/database.php');
define("LOGIN_DIR", 'components/login/login.php');
define("CAPTCHA_DIR", 'components/securimage/securimage.php');
define("DATA_REGISTER_CONFIRMATION", '<br /><div id="cong">ثبت اطلاعات موفق بود.</div><br /><a href="javascript:history.go(-1);">[ بازگشت به مرحله ی قبل ]</a>');
define("DATA_REGISTER_INTERRUPTION", '<div id="err">' . '<p>خطا در ثبت، حذف یا تغییر اطلاعات !</p>' . '<p style="text-align: right; margin-right: 30px;">یکی از موارد زیر اتفاق افتاده است:</p>' . '<p style="text-align: right; margin-right: 100px; color: black;">1) شما دسترسی لازم به آیتم مربوطه را نداشته‌اید.</p>' . '<p style="text-align: right; margin-right: 100px; margin-left: 50px; line-height: 17px; color: black;">2) آیتم مربوطه به‌دلیل دارا بودن وابستگی قابل حذف نیست؛ مثلاً درصورتی که برای یک ساختمان یا وسیله نقلیه هزینه‌ی تعمیر و نگهداری ثبت شده باشد، آن ساختمان یا وسیله نقلیه قبل از حذف کامل وابستگی‌ها قابل حذف نیست.</p>' . '<p style="text-align: right; margin-right: 100px; color: black;">3) یک مشکل فنی در برقراری ارتباط با پایگاه‌داده رخ داده‌است.</p>' . '<p style="text-align: right; margin-right: 100px; margin-left: 50px;">در صورتی که دراثر تکرارخطا احتمال می‌دهید مورد شماره 3 رخ داده‌است، مراتب را به مدیر سیستم اطلاع دهید ...</p>' . '</div>');
define("USERNAME_NOT_ENTERED_ERROR", '<span id="loginErr">نام کاربری خود را وارد نکرده اید.</span>');
define("PASSWORD_NOT_ENTERED_ERROR", '<span id="loginErr">رمزعبور خود را وارد نکرده اید.</span>');
define("CAPTCHA_NOT_ENTERED_ERROR", '<span id="loginErr">کد امنیتی را وارد نکرده اید.</span>');
define("WRONG_CAPTCHA_ERROR", '<br /><span id="loginErr">کد امنیتی را نادرست وارد کرده اید.</span>');
define("WRONG_USERNAME_AND_OR_PASSWORD", '<br /><span id="loginErr">نام کاربری و/ یا رمزعبور وارد شده صحیح نیست.</span>');
define("NO_TOWN_EXISTS", '<p class="warning">هیچ شهرستانی ثبت نشده است.</p>');
define("NO_CENTER_EXISTS", '<p class="warning">هیچ مرکز شهرستانی ثبت نشده است.</p>');
define("NO_UNIT_EXISTS", '<p class="warning">هیچ واحدی ثبت نشده است.</p>');
define("NO_HYGIENE_UNIT_EXISTS", '<p class="warning">هیچ خانه بهداشتی ثبت نشده است.</p>');
define("NO_AVAILABLE_SERVICES", '<p class="warning">هیچ خدمتی ثبت نشده است.</p>');
define("NO_SIB_JOB_TITLES", '<p class="warning">هیچ عنوان شغلی ای ثبت نشده است.</p>');
define("NO_SIB_PERIODS", '<p class="warning">هیچ دوره زندگی ای ثبت نشده است.</p>');
define("NO_AVAILABLE_JOBTITLES", '<p class="warning">هیچ رده پرسنلی ثبت نشده است.</p>');
define("NO_AVAILABLE_DRUGS", '<p class="warning">هیچ دارویی ثبت نشده است.</p>');
define("NO_AVAILABLE_CONSUMING_EQUIPMENT", '<p class="warning">هیچ تجهیزات مصرفی ثبت نشده است.</p>');
define("NO_AVAILABLE_NONCONSUMINGEQUIPMENT", '<p class="warning">هیچ تجهیزات غیرمصرفی ثبت نشده است.</p>');
define("NO_AVAILABLE_GENERALCHARGETYPE", '<p class="warning">هیچ نوع هزینه ای ثبت نشده است.</p>');
define("NO_CENTER_BUILDINGS", '<p class="warning">هیچ ساختمانی ثبت نشده است.</p>');
define("NO_CENTER_VEHICLE", '<p class="warning">هیچ وسیله نقلیه ای ثبت نشده است.</p>');
define("NO_UNIT_PERSONNEL", '<p class="warning">هیچ پرسنلی ثبت نشده است.</p>');
define("NO_UNIT_SERVICE", '<p class="warning">هیچ خدمتی ثبت نشده است.</p>');
define("ACCESS_FORBIDDEN", '<br /><br /><br /><p style="font-weight: bold; text-align: center; color: red;">خطا: دسترسی غیر مجاز به سامانه</p>' . '<p style="font-weight: bold; text-align: center;">لطفا با نام کاربری و رمزعبور خود وارد سیستم شوید<br /><br /><a href="' . ROOT_DIR . '">برای ورود کلیک کنید</a></p>');
define('PERMISSION_DENIED', '<p id="err">با توجه به سطح دسترسی تعریف شده برای شما، اجازه دسترسی به این بخش از سیستم را ندارید.</p>');
?>
