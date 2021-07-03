<?php

/**
 *
 * application.php
 * application class file
 *
 * @copyright	Copyright (C) 2012 Rivas Systems Inc. All rights reserved.
 * @versin	1.00 2011-02-05
 * @author	Behnam Salili
 *
 */

require_once dirname(dirname(dirname(__file__))) . '/defines.php';
require_once dirname(dirname(__file__)) . '/securimage/securimage.php';
require_once dirname(dirname(__file__)) . '/database/database.php';
require_once dirname(dirname(__file__)) . '/login/login.php';
require_once dirname(dirname(__file__)) . '/illustrator/illustrator.php';
require_once dirname(dirname(__file__)) . '/form/Narin.class.php';
require_once dirname(dirname(__file__)) . '/smtp/mail_f.php';

class Application {

	private $appTitle;
	private $usernameValid;
	private $passwordValid;
	private $captchaLoginValid;
	private $tmpMessage;
	private $viewer;

	public function __construct($title) {
		$this -> appTitle = $title;
		$this -> usernameValid = false;
		$this -> passwordValid = false;
		$this -> captchaLoginValid = false;
		$this -> tmpMessage = '';
		$this -> viewer = new Illustrator();
	}

	private function componentsReady() {//should be edited!
		return true;
	}

	public function initialise() {
		return $this -> componentsReady();
	}

	public function loggedIn() {
		return isset($_SESSION['user']);
	}

	private function displayLoginForm() {
		$this -> viewer -> setMainStylePath('components/illustrator/styles/main-style.css');
		$this -> viewer -> setFormStylePath('components/illustrator/styles/narin-form.css');
		$this -> viewer -> setJQueryPath('components/illustrator/js/jquery-1.9.1.min.js');
		$this -> viewer -> setFormJSPath('components/illustrator/js/narin-form.js');
		$this -> viewer -> setPageTitle($this -> appTitle);
		$securimage = new Securimage();

		$this -> viewer -> appendCenterBlock('<br /><br /><form id="loginForm" action="' . $_SERVER['PHP_SELF'] . '" method="post" enctype="application/x-www-form-urlencoded"><fieldset><legend><img src="components/illustrator/images/icons/log_in.png" />ورود به سامانه</legend>' . '<table border="0" align="center" width="300" cellpadding="0" cellspacing="7">' . '<tr><td><label for="username">نام کاربری:</label></td>' . '<td><input type="text" name="username" id="username" size="23" value="' . (isset($_POST['username']) ? $_POST['username'] : "") . '" dir="ltr" /></td></tr>');

		if (isset($_POST['username'])) {
			if (!trim($_POST['username']))
				$this -> viewer -> appendCenterBlock('<tr><td colspan="2">' . USERNAME_NOT_ENTERED_ERROR . '</td></tr>');
			else
				$this -> usernameValid = true;
		}

		$this -> viewer -> appendCenterBlock('<tr><td><label for="password">رمزعبور:</label></td>' . '<td><input type="password" name="password" id="password" size="23" dir="ltr" /></td></tr>');

		if (isset($_POST['password'])) {
			if (!trim($_POST['password']))
				$this -> viewer -> appendCenterBlock('<tr><td colspan="2">' . PASSWORD_NOT_ENTERED_ERROR . '</td></tr>');
			else
				$this -> passwordValid = true;
		}

		$this -> viewer -> appendCenterBlock('<tr><td><label for="login_captcha_code">تصویر امنیتی:</label></td>' . '<td><input type="text" dir="ltr" name="login_captcha_code" id="login_captcha_code" size="10" maxlength="6" />&nbsp;&nbsp;' . '<img style="width: 70px; height: 25px; border-width: 1px;" align="absbottom" id="captcha" src="components/securimage/securimage_show.php" alt="کد امنیتی" onclick="document.getElementById(\'captcha\').src=\'components/securimage/securimage_show.php?\' + Math.random(); return false" />&nbsp;' . '</td></tr></table>');

		if (isset($_POST['login_captcha_code'])) {
			if (!trim($_POST['login_captcha_code']))
				$this -> viewer -> appendCenterBlock('<tr><td colspan="2">' . CAPTCHA_NOT_ENTERED_ERROR . '</td></tr>');
			else if ($securimage -> check($_POST['login_captcha_code']) == false)
				$this -> viewer -> appendCenterBlock('<tr><td colspan="2">' . WRONG_CAPTCHA_ERROR . '</td></tr>');
			else
				$this -> captchaLoginValid = true;
		}

		if ($this -> usernameValid and $this -> passwordValid and $this -> captchaLoginValid) {
			$login = new Login(filter_var($_POST['username'], FILTER_SANITIZE_STRING), filter_var($_POST['password'], FILTER_SANITIZE_STRING));
			$res = $login -> login_user();
			if (!is_array($res) && $res == 'false')
				$this -> tmpMessage = WRONG_USERNAME_AND_OR_PASSWORD;
			else {
				$_SESSION['user'] = $res;
				switch ( $_SESSION['user']['acl'] ) {
					case 'State Manager' :
						$this -> redirect(APP_DIR . 'administrator.php');
						break;
					case 'Town Manager' :
						$_SESSION['townId'] = $_SESSION['user']['acl-id'];
						$res = Database::execute_query("SELECT `name` FROM `towns` WHERE `id` = '" . $_SESSION['user']['acl-id'] . "';");
						$row = Database::get_assoc_array($res);
						$_SESSION['user']['acl-name'] = $row['name'];
						$this -> redirect(APP_DIR . 'administrator.php?cmd=manageCenters&townId=' . $_SESSION['user']['acl-id']);
						break;
					case 'Center Manager' :
						$_SESSION['centerId'] = $_SESSION['user']['acl-id'];
						$res = Database::execute_query("SELECT `name` FROM `centers` WHERE `id` = '" . $_SESSION['user']['acl-id'] . "';");
						$row = Database::get_assoc_array($res);
						$_SESSION['user']['acl-name'] = $row['name'];
						$this -> redirect(APP_DIR . 'administrator.php?cmd=manageUnits&centerId=' . $_SESSION['user']['acl-id']);
						break;
					case 'Unit Manager' :
						$_SESSION['unitId'] = $_SESSION['user']['acl-id'];
						$res = Database::execute_query("SELECT `name` FROM `units` WHERE `id` = '" . $_SESSION['user']['acl-id'] . "';");
						$row = Database::get_assoc_array($res);
						$_SESSION['user']['acl-name'] = $row['name'];
						$this -> redirect(APP_DIR . 'administrator.php?cmd=modifyUnits&unitId=' . $_SESSION['user']['acl-id']);
						break;
					case 'Hygiene Unit Manager' :
						$_SESSION['hygieneUnitId'] = $_SESSION['user']['acl-id'];
						$res = Database::execute_query("SELECT `name` FROM `hygiene-units` WHERE `id` = '" . $_SESSION['user']['acl-id'] . "';");
						$row = Database::get_assoc_array($res);
						$_SESSION['user']['acl-name'] = $row['name'];
						$this -> redirect(APP_DIR . 'administrator.php?cmd=modifyHygieneUnits&hygieneUnitId=' . $_SESSION['user']['acl-id']);
						break;
					case 'State Author' :
					case 'Town Author' :
					case 'Center Author' :
					case 'Unit Author' :
					case 'Hygiene Unit Author' :
					case 'Multi Author' :
						$this -> redirect(APP_DIR . 'author.php');
						break;
				}
			}
		}
		$this -> viewer -> appendCenterBlock('<p style="text-align: center;"><a href="?forgotPasswd"><< بازیابی رمزعبور >></a></p>');
		$this -> viewer -> appendCenterBlock($this -> tmpMessage . '<br /><input type="submit" value="ورود" name="submitLogin" /></fieldset></form><br /><br /><br /><br />');
		$this -> viewer -> appendCenterBlock('<img src="components/illustrator/images/icons/login.png" style="float: left; margin-left: 20px; margin-top: 20px;" />');
		$this -> viewer -> illustrate('components/illustrator/index.tpl');
	}

	public function render() {
		if ($this -> loggedIn()) {
			switch ( $_SESSION['user']['acl'] ) {
				case 'State Manager' :
				case 'Town Manager' :
				case 'Center Manager' :
				case 'Unit Manager' :
				case 'Hygiene Unit Manager' :
					$this -> redirect(APP_DIR . 'administrator.php');
					break;

				case 'Unit Author' :
				case 'Hygiene Unit Author' :
				case 'Multi Author' :
					$this -> redirect(APP_DIR . 'author.php');
					break;
			}
		} elseif (isset($_GET['forgotPasswd']))
			$this -> forgotPasswd();
		else
			$this -> displayLoginForm();
	}

	public function redirect($to) {
		header('Location: ' . $to);
		exit ;
	}

	private function forgotPasswd() {
		$this -> viewer -> setMainStylePath('components/illustrator/styles/main-style.css');
		$this -> viewer -> setFormStylePath('components/illustrator/styles/narin-form.css');
		$this -> viewer -> setJQueryPath('components/illustrator/js/jquery-1.9.1.min.js');
		$this -> viewer -> setFormJSPath('components/illustrator/js/narin-form.js');
		$this -> viewer -> setPageTitle($this -> appTitle);

		if (isset($_POST['submitSendPasswdToEmail'])) {
			if (!(isset($_POST['email']) && Narin::validate_required('email') && Narin::validate_email('email') && strlen($_POST['email']) <= 255))
				$this -> viewer -> appendCenterBlock('<p id="err">خطا در وارد کردن آدرس پست الکترونیکی.</p>');
			else {
				$charset = "123456789abcdefghijklmnopqrstuvwxyz";
				$newPasswd = null;
				for ($i = 0; $i < 8; $i++)
					$newPasswd .= $charset{rand(0, strlen($charset) - 1)};

				$res = Database::execute_query("SELECT `username` FROM `users` WHERE `email` = '" . Database::filter_str($_POST['email']) . "' LIMIT 1;");

				if (Database::num_of_rows($res) > 0) {
					$row = Database::get_assoc_array($res);
					$username = $row['username'];
					$email = $_POST['email'];
					$hashedPasswd = hash_hmac('sha512', $newPasswd, $username);
					Database::execute_query("UPDATE `users` SET `password` = '$hashedPasswd' WHERE `username` = '$username';");
					$body = "<div><h2>رمزعبور و نام کاربری شما<h2>" . "<p>نام کاربری شما:<br />" . $username . "</p>" . "<p>رمزعبور جدید شما:<br />" . $newPasswd . "</p>" . "</div>";
					send_mail("بازیابی نام کاربری و رمزعبور سامانه ی نرم افزاری مدیریت مراکز بهداشتی-درمانی", $body, $email);
					$this -> viewer -> appendCenterBlock('<p id="cong">نام کاربری و رمز عبور با موفقیت برایتان ارسال شد.<br /><br /><a href="javascript:history.go(-1);">[بازگشت]</a></p>');
				} else
					$this -> viewer -> appendCenterBlock('<p id="err">ایمیل وارد شده موجود نیست. در صحیح وارد کردن آن دقت کنید.<br /><br /><a href="javascript:history.go(-1);">[بازگشت]</a></p>');
				
			}
		} else {
			$this -> viewer -> appendCenterBlock('<p>درصورتی که رمزعبور خود را فراموش کرده اید، برای دریافت رمزعبور جدید آدرس پست الکترونیک خود را وارد نمایید.<br />دقت کنید که ممکن است ایمیل موردنظر به جای Inbox به پوشه دیگری مثل Spam/Junk (هرزنامه) منتقل شده باشد.</p>');
			$this -> viewer -> appendCenterBlock('<form action="?forgotPasswd" method="post" class="narin">');
			$this -> viewer -> appendCenterBlock('<input type="hidden" name="narin:token" value="' . Narin::create_token() . '" />');
			$this -> viewer -> appendCenterBlock('<fieldset><legend>بازیابی رمزعبور</legend>');
			$this -> viewer -> appendCenterBlock('<label for="email" class="required">پست الکترونیک:</label>');
			$this -> viewer -> appendCenterBlock('<input type="text" name="email" id="email" maxlength="255" dir="ltr" class="validate required email" /><br />');
			$this -> viewer -> appendCenterBlock('<input name="submitSendPasswdToEmail" value="ارسال" type="submit" /></fieldset></form>');
			$this -> viewer -> appendCenterBlock('<p><a href="' . ROOT_DIR . '"><< بازگشت به صفحه اصلی >></a></p>');
		}

		$this -> viewer -> illustrate('components/illustrator/index.tpl');
	}

} //end of Application class
?>
