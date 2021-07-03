<?php

/* Copyright (C) Rivas System, Inc. All rights reserved. */

require_once $NarinConfig->path . '/lib/narin/narintools.php';

abstract class NarinForm {

	private $config;
	private $db;
	private $form;
	private $controls;
	private $user_id;
	private $prereq_error;
	private $status;
	private $blank;
	private $ref_id;
	private $captcha_valid;
	
	
	
	public function __construct($form, $controls) {
	
		global $NarinConfig;
		$this->config = $NarinConfig;
		session_start();

		try {
			$this->db = new PDO("mysql:host={$this->config->db_host};dbname={$this->config->db_name};charset=utf8", $this->config->db_user, $this->config->db_pass, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC));
			$this->db->exec('SET NAMES utf8');
		}
		catch (Exception $e) {
			die('db connect error');
		}
		
		$this->form = $form;
		$this->controls = $controls;
		$this->user_id = NarinTools::get_current_user_id();
		$this->prereq_error = $this->get_prereq_error();

		$this->process_submitted_data();
		
		if ($this->status === 'success' && $this->form->on_success === 'redir') {
			header('Location: ' . $this->form->redir_url);
			exit;
		}
	}
	
	public function get_view() {
		return '<div class="narinform">' . ($this->status === 'prereq' || ($this->status === 'success' && $this->form->on_success === 'msg') ? $this->message_view() : $this->message_view() . $this->form_view()) . '</div>';
	}
	
	public function get_title() {
		return $this->form->title;
	}
	
	public function get_status() {
		return $this->status;
	}
	
	public function get_form_name() {
		return $this->form->name;
	}
	
	public function get_table_name() {
		return $this->form->table;
	}
	
	
	
	abstract protected function form_view();
	
	protected function value($control_name, $option_value_or_part_name, $part_option_value) {
	
		$control = $this->controls[$control_name];
		
		switch ($control->type) {
		
			case 'text':
			
				$value = (string)($this->blank ? $control->def : $_POST[$control_name]);
				return $value === '' ? '' : ' value="' . htmlspecialchars($value) . '"';
				
			case 'textarea':
			
				return htmlspecialchars($this->blank ? $control->def : $_POST[$control_name]);
				
			case 'radio':
			
				return $option_value_or_part_name === ($this->blank ? $control->def : $_POST[$control_name]) ? ' checked' : '';
				
			case 'select':
			case 'select-slave':
			
				return !$this->blank && $option_value_or_part_name === $_POST[$control_name] ? ' selected' : '';
				
			case 'checkbox':
			
				return ($this->blank ? $control->def : isset($_POST[$control_name])) ? ' checked' : '';
				
			case 'checkbox-array':
			
				return in_array($option_value_or_part_name, $this->blank ? $control->def : $_POST[$control_name], true) ? ' checked' : '';
				
			case 'date':
			
				switch ($option_value_or_part_name) {
					case 'y':
						$value = (string)($this->blank ? $control->def->y : $_POST[$control_name]['y']);
						return $value === '' ? '' : ' value="' . htmlspecialchars($value) . '"';
					case 'm':
					case 'd':
						if ($this->blank) {
							$def = (array)$control->def;
							$value = $def[$option_value_or_part_name];
						}
						else {
							$value = $_POST[$control_name][$option_value_or_part_name];
						}
						return $part_option_value === (string)$value ? ' selected' : '';
					default:
						return '';
				}
				
			case 'time':
			
				switch ($option_value_or_part_name) {
					case 'h':
					case 'n':
						if ($this->blank) {
							$def = (array)$control->def;
							$value = $def[$option_value_or_part_name];
						}
						else {
							$value = $_POST[$control_name][$option_value_or_part_name];
						}
						return $part_option_value === (string)$value ? ' selected' : '';
					default:
						return '';
				}
				
			case 'datetime':
			
				switch ($option_value_or_part_name) {
					case 'y':
						$value = (string)($this->blank ? $control->def->y : $_POST[$control_name]['y']);
						return $value === '' ? '' : ' value="' . htmlspecialchars($value) . '"';
					case 'm':
					case 'd':
					case 'h':
					case 'n':
						if ($this->blank) {
							$def = (array)$control->def;
							$value = $def[$option_value_or_part_name];
						}
						else {
							$value = $_POST[$control_name][$option_value_or_part_name];
						}
						return $part_option_value === (string)$value ? ' selected' : '';
					default:
						return '';
				}
				
			default:
			
				return '';
		}
	}
	
	protected function attr($control_name) {
		return $this->valid($control_name) ? '' : ' class="invalid"';
	}
	
	protected function valid($control_name) {
		if ($this->blank) {
			return true;
		}
		if ($control_name === '_captcha_') {
			return $this->captcha_valid;
		}
		return $this->controls[$control_name]->_valid;
	}
	
	protected function error($control_name) {
		$error = $this->controls[$control_name]->_error;
		return is_null($error) ? '' : '<span class="error">' . htmlspecialchars($error) . '</span>';
	}
	
	protected function captcha_url() {
		return $this->config->url . '/captcha.php?form=' . $this->form->name . '&r=' . uniqid();
	}
	
	protected function generate_token() {
		$token = sha1(uniqid(mt_rand(), true));
		$_SESSION['narin_token_value'][$this->form->name] = $token;
		$_SESSION['narin_token_time'][$this->form->name] = time();
		return $token;
	}
	
	
	
	private function process_submitted_data() {

		$this->blank = $_POST['_form_'] !== $this->form->name;

		if ($this->prereq_error) {
			$this->status = 'prereq';
			return;
		}
	
		if ($this->blank) {
			$this->status = 'new';
			return;
		}
		
		if (!$this->validate_submitted_data()) {
			$this->status = 'invalid';
			return;
		}
		
		if (!$this->verify_token()) {
			$this->status = 'securityissue';
			return;
		}
		
		$this->prepare_control_values();
		
		if (!$this->move_uploaded_files()) {
			$this->remove_uploaded_files();
			$this->status = 'error';
			return;
		}
		
		$cols = array();
		$vals = array();
		
		if ($this->form->ref_id) {
			$this->ref_id = $this->generate_ref_id();
			$cols[] = '`_id_`';
			$vals[] = $this->ref_id;
		}

		$cols[] = '`_userid_`';
		$vals[] = $this->user_id;

		$cols[] = '`_approval_`';
		if ($this->form->approval_needed) {
			$vals[] = 'P';
		}
		else {
			$vals[] = 'A';
		}
		
		foreach ($this->controls as $control_name => $control) {
			if (!($control->type === 'checkbox' && $control->required)) {
				$cols[] = '`' . $control_name . '`';
				$vals[] = $control->_value;
			}
		}
		
		$query = "INSERT INTO `{$this->form->table}` (" . implode(',', $cols) . ') VALUES (' . substr(str_repeat(',?', count($vals)), 1) . ')';
		
		try {
			$st = $this->db->prepare($query);
			$st->execute($vals);
		}
		catch (Exception $e) {
			$this->remove_uploaded_files();
			$this->ref_id = null;
			$this->status = 'error';
			return;
		}
		
		$this->status = 'success';
		$this->blank = true;
	}
	
	private function validate_submitted_data() {
	
		$valid = true;
		
		foreach ($this->controls as $control_name => $control) {
			if ($this->validate_control($control_name, $control)) {
				$control->_valid = true;
			}
			else {
				$control->_valid = false;
				$valid = false;
			}
		}
		
		if (!$valid) {
			$this->captcha_valid = true;
			return false;
		}
		
		$this->verify_captcha();
		
		return $this->captcha_valid;
	}
	
	private function validate_control($control_name, $control) {
	
		switch ($control->type) {
		
			case 'text':
			
				if ($this->post_blank($control_name)) {
					return !$control->required;
				}
				
				$value = $_POST[$control_name];
				$value_len = mb_strlen($value, 'UTF-8');
				
				if ($value_len > (isset($control->max_len) ? $control->max_len : 255)) {
					return false;
				}
				
				switch ($control->validation) {
				
					case null:
						return true;
					
					case 'email':
						return filter_var($value, FILTER_VALIDATE_EMAIL) !== false;
					
					case 'url':
						return preg_match('/^https?:\/\/[^\s\/]+\.[^\s\/]+(\/.*)?$/i', $value) && filter_var($value, FILTER_VALIDATE_URL) !== false;
					
					case 'farsi-word':
						return preg_match('/^[پضًصٌثٍقفغعهةخحجچشَسُیِبّلاآتـنمکگظطزيركذإدأئءوؤژ ‌]+$/', $value);
					
					case 'farsi-text':
						return preg_match('/^[پ~1۱!2۲@3۳#4۴$5۵%6۶^7۷&8۸*9۹)0۰(\-_=+ضًصٌثٍقف،غ؛ع,هةخ×ح÷ج}چ{شَسُیِبّل<اآتـن«م»ک:گ"ظ\]ط[زيركذإدأئءوؤ.>\/؟ژ\\\\\s‌]+$/', $value);
					
					case 'integer':
						$value = $this->str_int($value);
						return $value !== false && (!isset($control->min) || $value >= $control->min) && (!isset($control->max) || $value <= $control->max);
					
					case 'decimal':
						$value = $this->str_float($value);
						return $value !== false && (!isset($control->min) || ($control->min_inc ? ($value >= $control->min) : ($value > $control->min))) && (!isset($control->max) || ($control->max_inc ? ($value <= $control->max) : ($value < $control->max)));
					
					case 'mobile':
						return preg_match('/^09[0-9]{9}$/', $value);
					
					case 'tel':
						return $value_len == 12 && preg_match('/^0[1-9][0-9]{1,3}-[1-9][0-9]*$/', $value);
					
					case 'postal-code':
					case 'ssn':
						return $value_len == 10 && preg_match('/^[0-9]*[1-9][0-9]*$/', $value);
					
					case 'pbn':
						return $value_len <= 10 && preg_match('/^[0-9]*[1-9][0-9]*$/', $value);
					
					case 'num-code':
						return (!isset($control->digits) || $value_len == $control->digits) && preg_match($control->no_leading_zero ? '/^[1-9][0-9]*$/' : '/^[0-9]+$/', $value);
					
					case 'regexp':
						return preg_match('/' . $control->regexp . ($control->case_insensitive ? '/i' : '/'), $value);
				}
				
				return true;
				
			case 'textarea':
				
				if ($this->post_blank($control_name)) {
					return !$control->required;
				}
				
				$value = $_POST[$control_name];
				
				if (mb_strlen($value, 'UTF-8') > (isset($control->max_len) ? $control->max_len : 65535)) {
					return false;
				}
				
				switch ($control->validation) {
				
					case null:
						return true;
					
					case 'farsi-text':
						return preg_match('/^[پ~1۱!2۲@3۳#4۴$5۵%6۶^7۷&8۸*9۹)0۰(\-_=+ضًصٌثٍقف،غ؛ع,هةخ×ح÷ج}چ{شَسُیِبّل<اآتـن«م»ک:گ"ظ\]ط[زيركذإدأئءوؤ.>\/؟ژ\\\\\s‌]+$/', $value);
					
					case 'regexp':
						return preg_match('/' . $control->regexp . ($control->case_insensitive ? '/i' : '/'), $value);
				}
				
				return true;
				
			case 'radio':
				
				if ($this->post_empty($control_name)) {
					return !$control->required;
				}
				
				return in_array($_POST[$control_name], $control->values, true);
				
			case 'select':
				
				if ($this->post_empty($control_name)) {
					return !$control->required && $control->has_null_opt;
				}
				
				return in_array($_POST[$control_name], $control->values, true);
				
			case 'select-slave':
				
				if ($this->post_empty($control_name)) {
					return !$control->required && $control->has_null_opt;
				}
				
				/*** not fully implemented yet ***/

				$values = array();

				foreach ($control->value_sets as $value_set) {
					foreach ($value_set->values as $value) {
						$values[] = $value;
					}
				}

				return in_array($_POST[$control_name], $values, true);
				
			case 'checkbox':
				
				return !$control->required || isset($_POST[$control_name]);
				
			case 'checkbox-array':
				
				$checked = count($_POST[$control_name]);
				
				if ((isset($control->min) && $checked < $control->min) || (isset($control->max) && $checked > $control->max)) {
					return false;
				}
				
				foreach ($_POST[$control_name] as $opt) {
					if (!in_array($opt, $control->values, true)) {
						return false;
					}
				}
				
				return true;
				
			case 'date':
				
				if ($this->post_blank_date($control_name)) {
					return !$control->required;
				}
				
				$value = array($this->str_int_unsigned($_POST[$control_name]['y']), $this->str_int_unsigned($_POST[$control_name]['m']), $this->str_int_unsigned($_POST[$control_name]['d']));
				
				if (!$this->is_valid_persian_date($value)) {
					$control->_error = 'تاریخ نادرست است.';
					return false;
				}
				
				$valid = true;
				
				switch ($control->validation) {
				
					case null:
						return true;
					
					case 'past':
						$valid = $this->compare_date($value, $this->now_persian_date()) < 0;
						break;
					
					case 'past-now':
						$valid = $this->compare_date($value, $this->now_persian_date()) <= 0;
						break;
					
					case 'future':
						$valid = $this->compare_date($value, $this->now_persian_date()) > 0;
						break;
					
					case 'future-now':
						$valid = $this->compare_date($value, $this->now_persian_date()) >= 0;
						break;
					
					case 'range':
						switch ($control->from) {
							case 'now-inc':
								$valid = $this->compare_date($value, $this->now_persian_date()) >= 0;
								break;
							case 'now-exc':
								$valid = $this->compare_date($value, $this->now_persian_date()) > 0;
								break;
							case 'value':
								$valid = $this->compare_date($value, $this->date_obj_to_array($control->from_value)) >= 0;
								break;
						}
						if ($valid) {
							switch ($control->to) {
								case 'now-inc':
									$valid = $this->compare_date($value, $this->now_persian_date()) <= 0;
									break;
								case 'now-exc':
									$valid = $this->compare_date($value, $this->now_persian_date()) < 0;
									break;
								case 'value':
									$valid = $this->compare_date($value, $this->date_obj_to_array($control->to_value)) <= 0;
								break;
							}
						}
						break;
				}
				
				if (!$valid) {
					$control->_error = 'تاریخ در بازه‌ی مجاز نیست.';
				}
				
				return $valid;
				
			case 'time':
				
				if ($this->post_blank_time($control_name)) {
					return !$control->required;
				}
				
				return $this->is_valid_time($this->str_int_unsigned($_POST[$control_name]['h']), $this->str_int_unsigned($_POST[$control_name]['n']));
				
			case 'datetime':
				
				if ($this->post_blank_datetime($control_name)) {
					return !$control->required;
				}
				
				$value = array($this->str_int_unsigned($_POST[$control_name]['y']), $this->str_int_unsigned($_POST[$control_name]['m']), $this->str_int_unsigned($_POST[$control_name]['d']), $this->str_int_unsigned($_POST[$control_name]['h']), $this->str_int_unsigned($_POST[$control_name]['n']));
				
				if (!$this->is_valid_persian_datetime($value)) {
					$control->_error = 'زمان نادرست است.';
					return false;
				}
				
				$valid = true;
				
				switch ($control->validation) {
				
					case null:
						return true;
					
					case 'past':
						$valid = $this->compare_datetime($value, $this->now_persian_datetime()) < 0;
						break;
					
					case 'past-now':
						$valid = $this->compare_datetime($value, $this->now_persian_datetime()) <= 0;
						break;
					
					case 'future':
						$valid = $this->compare_datetime($value, $this->now_persian_datetime()) > 0;
						break;
					
					case 'future-now':
						$valid = $this->compare_datetime($value, $this->now_persian_datetime()) >= 0;
						break;
					
					case 'range':
						switch ($control->from) {
							case 'now-inc':
								$valid = $this->compare_datetime($value, $this->now_persian_datetime()) >= 0;
								break;
							case 'now-exc':
								$valid = $this->compare_datetime($value, $this->now_persian_datetime()) > 0;
								break;
							case 'value':
								$valid = $this->compare_datetime($value, $this->datetime_obj_to_array($control->from_value)) >= 0;
								break;
						}
						if ($valid) {
							switch ($control->to) {
								case 'now-inc':
									$valid = $this->compare_datetime($value, $this->now_persian_datetime()) <= 0;
									break;
								case 'now-exc':
									$valid = $this->compare_datetime($value, $this->now_persian_datetime()) < 0;
									break;
								case 'value':
									$valid = $this->compare_datetime($value, $this->datetime_obj_to_array($control->to_value)) <= 0;
									break;
							}
						}
						break;
				}
				
				if (!$valid) {
					$control->_error = 'زمان در بازه‌ی مجاز نیست.';
				}
				
				return $valid;
				
			case 'file':
				
				if ($this->file_blank($control_name)) {
					return !$control->required;
				}
				
				$file = $_FILES[$control_name];
				
				if ($file['error'] == UPLOAD_ERR_INI_SIZE && (!isset($control->max_file_size) || $file['size'] <= $control->max_file_size)) {
					$control->_error = 'فایل بیش از حد بزرگ است. در حال حاضر حداکثر حجم مجاز ' . $this->upload_max_filesize_formatted() . ' است.';
					return false;
				}
				
				if ($file['error'] != UPLOAD_ERR_OK) {
					$control->_error = 'بارگذاری موفق نبود.';
					return false;
				}
				
				if (isset($control->max_file_size) && $file['size'] > $control->max_file_size) {
					$control->_error = 'حجم فایل بیش از حد مجاز است.';
					return false;
				}
				
				if (isset($control->valid_exts) && !$this->file_has_valid_ext($file['name'], $control->valid_exts)) {
					$control->_error = 'فرمت فایل مجاز نیست.';
					return false;
				}
				
				return true;
		}
	}
	
	private function prepare_control_values() {
		foreach ($this->controls as $control_name => $control) {
			$control->_value = $this->control_value($control_name, $control);
		}
	}
	
	private function control_value($control_name, $control) {
	
		switch ($control->type) {
		
			case 'text':
			
				if ($this->post_blank($control_name)) {
					return null;
				}
				
				switch ($control->validation) {
					case 'integer':
						return (int)$_POST[$control_name];
					case 'decimal':
						return (float)$_POST[$control_name];
					default:
						return $_POST[$control_name];
				}
				
			case 'textarea':
				
				return $this->post_blank($control_name) ? null : $_POST[$control_name];
				
			case 'radio':
			case 'select':
			case 'select-slave':
				
				return $this->post_empty($control_name) ? null : $_POST[$control_name];
				
			case 'checkbox':
				
				return isset($_POST[$control_name]) ? 1 : 0;
				
			case 'checkbox-array':
				
				return is_array($_POST[$control_name]) ? implode("\n", $_POST[$control_name]) : '';
				
			case 'date':
				
				return $this->post_blank_date($control_name) ? null : $this->persian_date_to_stdstr((int)$_POST[$control_name]['y'], (int)$_POST[$control_name]['m'], (int)$_POST[$control_name]['d']);
				
			case 'time':
				
				return $this->post_blank_time($control_name) ? null : $this->time_to_stdstr((int)$_POST[$control_name]['h'], (int)$_POST[$control_name]['n']);
				
			case 'datetime':
				
				return $this->post_blank_datetime($control_name) ? null : $this->persian_datetime_to_stdstr((int)$_POST[$control_name]['y'], (int)$_POST[$control_name]['m'], (int)$_POST[$control_name]['d'], (int)$_POST[$control_name]['h'], (int)$_POST[$control_name]['n']);
				
			case 'file':
				
				return $this->file_blank($control_name) ? null : $this->prepare_filename($_FILES[$control_name]['name']);
		}
	}
	
	private function move_uploaded_files() {
	
		foreach ($this->controls as $control_name => $control) {
			if ($control->type === 'file' && !is_null($control->_value)) {
				if (!move_uploaded_file($_FILES[$control_name]['tmp_name'], $this->config->path . '/forms/' . $this->form->name . '/uploads/' . $control->_value)) {
					return false;
				}
			}
		}
		
		return true;
	}
	
	private function remove_uploaded_files() {
		foreach ($this->controls as $control) {
			if ($control->type === 'file' && !is_null($control->_value)) {
				unlink($this->config->path . '/forms/' . $this->form->name . '/uploads/' . $control->_value);
			}
		}
	}
	
	private function message_view() {
	
		switch ($this->status) {
		
			case 'new':
			
				return '';
			
			case 'success':
			
				$s = '<div class="message success"><p>اطلاعات با موفقیت ثبت شد.</p>';

				if ($this->form->ref_id) {
					$s .=
						'<div class="narinform-refid"><p>کد پیگیری <span dir="ltr">' .
						substr($this->ref_id, 0, 4) . '-' .
						substr($this->ref_id, 4, 4) . '-' .
						substr($this->ref_id, 8, 4) . '-' .
						substr($this->ref_id, 12) .
						'</span><img src="' . $this->config->url . '/qrcode.php?code=' . $this->ref_id . '" title="کد پیگیری" /><span class="print"><button>چاپ</button></span></p></div>';
				}

				if ($this->form->next && !$this->form->approval_needed) {
					$s .= '<p>اکنون می‌توانید فرم <a href="' . htmlspecialchars(NarinTools::get_form_url($this->form->next)) . '">مرحله‌ی بعد</a> را تکمیل نمایید.</p>';
				}

				$s .= '</div>';
				
				return $s;
			
			case 'invalid':
			
				return '<div class="message fail"><p>اطلاعات وارد شده معتبر نیست. لطفاً موارد مشخص‌شده را اصلاح نمایید.</p></div>';
			
			case 'securityissue':
			
				return '<div class="message fail"><p>به دلیل وجود یک مشکل امنیتی، اطلاات ثبت نشد. لطفاً دوباره تلاش کنید.</p></div>';

			case 'prereq':

				$s = '<div class="message fail"><p>این فرم <a href="' . htmlspecialchars(NarinTools::get_form_url($this->form->prereq)) . '">پیشنیاز</a> دارد. ';
				
				switch ($this->prereq_error) {
					case 1:
						$s .= 'شما وارد سیستم نشده‌اید.';
						break;
					case 2:
						$s .= 'شما فرم پیشنیاز را پر نکرده‌اید.';
						break;
					case 3:
						$s .= 'اطلاعات شما در فرم پیشنیاز هنوز توسط مدیریت بررسی نشده است.';
						break;
					case 4:
						$s .= 'اطلاعات شما در فرم پیشنیاز توسط مدیریت رد شده است.';
						break;
					default:
						$s .= 'متأسفانه بررسی پر کردن فرم پیشنیاز با خطا مواجه شد.';
						break;
				}

				$s .= '</p></div>';

				return $s;
			
			case 'error':
			
				return '<div class="message fail"><p>متأسفانه ثبت اطلاعات با مشکل مواجه شد. لطفاً دوباره تلاش کنید.</p></div>';
		}
	}

	private function get_prereq_error() {

		if (!$this->form->prereq) {
			return 0;
		}

		if (!$this->user_id) {
			return 1;
		}

		try {
			$st = $this->db->prepare("SELECT `_approval_` FROM `{$this->form->prereq}` WHERE `_userid_` = {$this->user_id} LIMIT 1");
			$st->execute();
			$row = $st->fetch();
		}
		catch (Exception $e) {
			return 5;
		}

		if (!$row) {
			return 2;
		}

		switch ($row['_approval_']) {
			case 'P':
				return 3;
			case 'A':
				return 0;
			case 'D':
				return 4;
			default:
				return 6;
		}
	}
	
	private function verify_token() {
		$valid = is_string($_POST['_token_']) && $_POST['_token_'] === $_SESSION['narin_token_value'][$this->form->name] && is_int($_SESSION['narin_token_time'][$this->form->name]) && time() - $_SESSION['narin_token_time'][$this->form->name] <= $this->config->expiry_time;
		unset($_SESSION['narin_token_value'][$this->form->name]);
		unset($_SESSION['narin_token_time'][$this->form->name]);
		return $valid;
	}
	
	private function verify_captcha() {
		if (is_null($this->captcha_valid)) {
			if ($this->form->captcha) {
				require_once $this->config->path . '/lib/securimage/securimage.php';
				$securimage = new Securimage();
				$securimage->namespace = $this->form->name;
				$this->captcha_valid = $securimage->check($_POST['_captcha_']);
			}
			else {
				$this->captcha_valid = true;
			}
		}
	}
	
	private function generate_ref_id() {
		$t = (string)time();
		$d = 16 - strlen($t);
		return $t . str_pad(mt_rand(0, pow(10, $d) - 1), $d, '0', STR_PAD_LEFT);
	}
	
	private function file_has_valid_ext($filename, $valid_exts) {
		return in_array(strtolower(substr(strrchr($filename, '.'), 1)), explode(';', strtolower($valid_exts)), true);
	}
	
	private function prepare_filename($filename) {
		return uniqid() . str_pad(mt_rand(0, 9999999), 7, '0', STR_PAD_LEFT) . '_' . strtolower(substr(strrchr($filename, '.'), 1));
	}
	
	private function upload_max_filesize_formatted() {
		$s = trim(ini_get('upload_max_filesize'));
		$n = (int)$s;
		$u = strtolower($s[strlen($s) - 1]);
		switch ($u) {
			case 'g':
				return $n . ' گیگابایت';
			case 'm':
				return $n . ' مگابایت';
			case 'k':
				return $n . ' کیلوبایت';
			default:
				return $n . ' بایت';
		}
	}
	
	private function str_int($str) {
		return preg_match('/^[-+]?[0-9]+$/', $str) ? (int)$str : false;
	}
	
	private function str_int_unsigned($str) {
		return preg_match('/^[0-9]+$/', $str) ? (int)$str : false;
	}
	
	private function str_float($str) {
		return preg_match('/^[-+]?[0-9]*\.?[0-9]+$/', $str) ? (float)$str : false;
	}
	
	private function post_blank($name) {
		return !isset($_POST[$name]) || !preg_match('/[^\s]/', $_POST[$name]);
	}
	
	private function post_empty($name) {
		return !isset($_POST[$name]) || $_POST[$name] === '';
	}
	
	private function post_blank_date($name) {
		return !isset($_POST[$name]) || $_POST[$name] === '' || ((!isset($_POST[$name]['y']) || !preg_match('/[^\s]/', $_POST[$name]['y'])) && (!isset($_POST[$name]['m']) || $_POST[$name]['m'] === '') && (!isset($_POST[$name]['d']) || $_POST[$name]['d'] === ''));
	}
	
	private function post_blank_time($name) {
		return !isset($_POST[$name]) || $_POST[$name] === '' || ((!isset($_POST[$name]['h']) || $_POST[$name]['h'] === '') && (!isset($_POST[$name]['n']) || $_POST[$name]['n'] === ''));
	}
	
	private function post_blank_datetime($name) {
		return !isset($_POST[$name]) || $_POST[$name] === '' || ((!isset($_POST[$name]['y']) || !preg_match('/[^\s]/', $_POST[$name]['y'])) && (!isset($_POST[$name]['m']) || $_POST[$name]['m'] === '') && (!isset($_POST[$name]['d']) || $_POST[$name]['d'] === '') && (!isset($_POST[$name]['h']) || $_POST[$name]['h'] === '') && (!isset($_POST[$name]['n']) || $_POST[$name]['n'] === ''));
	}
	
	private function file_blank($name) {
		return !isset($_FILES[$name]) || $_FILES[$name]['error'] == UPLOAD_ERR_NO_FILE;
	}
	
	private function compare_date($a, $b) {
		for ($i = 0; $i < 3; $i++) {
			if ($a[$i] > $b[$i]) {
				return 1;
			}
			if ($a[$i] < $b[$i]) {
				return -1;
			}
		}
		return 0;
	}
	
	private function compare_datetime($a, $b) {
		for ($i = 0; $i < 5; $i++) {
			if ($a[$i] > $b[$i]) {
				return 1;
			}
			if ($a[$i] < $b[$i]) {
				return -1;
			}
		}
		return 0;
	}
	
	private function date_obj_to_array($date) {
		return array($date->y, $date->m, $date->d);
	}
	
	private function datetime_obj_to_array($date) {
		return array($date->y, $date->m, $date->d, $date->h, $date->n);
	}
	
	private function now_persian_date() {
		$now = time();
		return $this->gregorian_to_persian_date((int)date('Y', $now), (int)date('n', $now), (int)date('j', $now));
	}
	
	private function now_persian_datetime() {
		$now = time();
		$d = $this->gregorian_to_persian_date((int)date('Y', $now), (int)date('n', $now), (int)date('j', $now));
		$d[3] = (int)date('G', $now);
		$d[4] = (int)date('i', $now);
		return $d;
	}
	
	private function is_valid_persian_date($date) {
	
		if (!is_int($date[0]) || !is_int($date[1]) || !is_int($date[2]) || $date[0] < 1000 || $date[0] > 9377 || $date[1] < 1 || $date[1] > 12 || $date[2] < 1 || $date[2] > 31 || ($date[2] == 31 && $date[1] > 6)) {
			return false;
		}
		
		$t = $this->persian_to_gregorian_date($date[0], $date[1], $date[2]);
		$t = $this->gregorian_to_persian_date($t[0], $t[1], $t[2]);
		return $date[0] == $t[0] && $date[1] == $t[1] && $date[2] == $t[2];
	}
	
	private function is_valid_time($h, $n) {
		return is_int($h) && is_int($n) && $h >= 0 && $h <= 23 && $n >= 0 && $n <= 59;
	}
	
	private function is_valid_persian_datetime($datetime) {
		return $this->is_valid_time($datetime[3], $datetime[4]) && $this->is_valid_persian_date($datetime);
	}
	
	private function persian_date_to_stdstr($y, $m, $d) {
		$gd = $this->persian_to_gregorian_date($y, $m, $d);
		return $gd[0] . '-' . ($gd[1] > 9 ? $gd[1] : '0' . $gd[1]) . '-' . ($gd[2] > 9 ? $gd[2] : '0' . $gd[2]);
	}
	
	private function time_to_stdstr($h, $n) {
		return ($h > 9 ? $h : '0' . $h) . ':' . ($n > 9 ? $n : '0' . $n) . ':00';
	}
	
	private function persian_datetime_to_stdstr($y, $m, $d, $h, $n) {
		return $this->persian_date_to_stdstr($y, $m, $d) . ' ' . $this->time_to_stdstr($h, $n);
	}
	
	// Gregorian to Jalali Conversion - Copyright (C) 2000  Roozbeh Pournader and Mohammad Toossi
	private function gregorian_to_persian_date($g_y, $g_m, $g_d) {
	
		$g_days_in_month = array(31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
		$j_days_in_month = array(31, 31, 31, 31, 31, 31, 30, 30, 30, 30, 30, 29);
		
		$gy = $g_y-1600;
		$gm = $g_m-1;
		$gd = $g_d-1;
		
		$g_day_no = 365*$gy+$this->div($gy+3, 4)-$this->div($gy+99, 100)+$this->div($gy+399, 400);
		
		for ($i=0; $i < $gm; ++$i)
			$g_day_no += $g_days_in_month[$i];
		if ($gm>1 && (($gy%4==0 && $gy%100!=0) || ($gy%400==0)))
			$g_day_no++;
		$g_day_no += $gd;
		
		$j_day_no = $g_day_no-79;
		
		$j_np = $this->div($j_day_no, 12053);
		$j_day_no = $j_day_no % 12053;
		
		$jy = 979+33*$j_np+4*$this->div($j_day_no, 1461);
		
		$j_day_no %= 1461;
		
		if ($j_day_no >= 366) {
			$jy += $this->div($j_day_no-1, 365);
			$j_day_no = ($j_day_no-1)%365;
		}
		
		for ($i = 0; $i < 11 && $j_day_no >= $j_days_in_month[$i]; ++$i)
			$j_day_no -= $j_days_in_month[$i];
		$jm = $i+1;
		$jd = $j_day_no+1;
		
		return array($jy, $jm, $jd);
	}

	// Jalali to Gregorian Conversion - Copyright (C) 2000  Roozbeh Pournader and Mohammad Toossi
	private function persian_to_gregorian_date($j_y, $j_m, $j_d) {
	
		$g_days_in_month = array(31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
		$j_days_in_month = array(31, 31, 31, 31, 31, 31, 30, 30, 30, 30, 30, 29);
		
		$jy = $j_y-979;
		$jm = $j_m-1;
		$jd = $j_d-1;
		
		$j_day_no = 365*$jy + $this->div($jy, 33)*8 + $this->div($jy%33+3, 4);
		for ($i=0; $i < $jm; ++$i)
			$j_day_no += $j_days_in_month[$i];
		
		$j_day_no += $jd;
		
		$g_day_no = $j_day_no+79;
		
		$gy = 1600 + 400*$this->div($g_day_no, 146097);
		$g_day_no = $g_day_no % 146097;
		
		$leap = true;
		if ($g_day_no >= 36525) {
			$g_day_no--;
			$gy += 100*$this->div($g_day_no,  36524);
			$g_day_no = $g_day_no % 36524;
			
			if ($g_day_no >= 365)
				$g_day_no++;
			else
				$leap = false;
		}
		
		$gy += 4*$this->div($g_day_no, 1461);
		$g_day_no %= 1461;
		
		if ($g_day_no >= 366) {
			$leap = false;
			
			$g_day_no--;
			$gy += $this->div($g_day_no, 365);
			$g_day_no = $g_day_no % 365;
		}
		
		for ($i = 0; $g_day_no >= $g_days_in_month[$i] + ($i == 1 && $leap); $i++)
			$g_day_no -= $g_days_in_month[$i] + ($i == 1 && $leap);
		$gm = $i+1;
		$gd = $g_day_no+1;
		
		return array($gy, $gm, $gd);
	}
	
	private function div($a, $b) {
		return (int)($a / $b);
	}
}