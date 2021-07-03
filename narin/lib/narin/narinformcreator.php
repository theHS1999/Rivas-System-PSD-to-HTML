<?php

/* Copyright (C) Rivas System, Inc. All rights reserved. */

class NarinFormCreator {

	private $form;
	private $config;
	
	
	
	public function __construct($form_source_or_form_name) {
	
		global $NarinConfig;
		$this->config = $NarinConfig;
		$this->config->months = array('', 'فروردین', 'اردیبهشت', 'خرداد', 'تیر', 'مرداد', 'شهریور', 'مهر', 'آبان', 'آذر', 'دی', 'بهمن', 'اسفند');
		
		$is_source = preg_match('/^\s*\{/', $form_source_or_form_name);
		
		if ($is_source) {
			$this->form = json_decode($form_source_or_form_name, false);
			if (!$this->validate_form_source()) {
				throw new InvalidArgumentException('Invalid form source', 1001);
			}
		}
		else {
			$form_source_file = $this->config->path . '/forms/' . $form_source_or_form_name . '/source.json';
			if (!is_file($form_source_file)) {
				throw new RuntimeException('Form source file does not exist: ' . $form_source_file, 1002);
			}
			$form_source = file_get_contents($form_source_file);
			if ($form_source === false) {
				throw new RuntimeException('Could not read form source file: ' . $form_source_file, 1003);
			}
			$this->form = json_decode($form_source, false);
			if (!$this->validate_form_source()) {
				throw new RuntimeException('Form source file contains invalid form source: ' . $form_source_file, 1004);
			}
		}
		
		$this->prepare_form_source();
	}
	
	public function create_form() {
	
		$db = new PDO("mysql:host={$this->config->db_host};dbname={$this->config->db_name}", $this->config->db_user, $this->config->db_pass, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
		
		try {
			$db->exec($this->get_form_table_source());
		}
		catch (Exception $e) {
			if ($e->getCode() === '42S01') {
				throw new RuntimeException('Form table already exists: ' . $this->form->table, 2001);
			}
			else {
				throw $e;
			}
		}
		
		$form_dir = $this->config->path . '/forms/' . $this->form->name;
		
		if (is_dir($form_dir)) {
		
			try {
				$db->exec("DROP TABLE `{$this->form->table}`");
			}
			catch (Exception $e) {}
			
			throw new RuntimeException('Form dir already exists: ' . $form_dir, 2002);
		}
		
		if (!mkdir($form_dir)) {
		
			try {
				$db->exec("DROP TABLE `{$this->form->table}`");
			}
			catch (Exception $e) {}
			
			throw new RuntimeException('Could not create form dir: ' . $form_dir, 2003);
		}
		
		$form_class_file = $form_dir . '/form.php';
		
		if (!file_put_contents($form_class_file, $this->get_form_class_source())) {
		
			try {
				$db->exec("DROP TABLE `{$this->form->table}`");
				unlink($form_class_file);
				rmdir($form_dir);
			}
			catch (Exception $e) {}
			
			throw new RuntimeException('Could not create form class file: ' . $form_class_file, 2004);
		}
		
		$dataviewer_class_file = $form_dir . '/dataviewer.php';
		
		if (!file_put_contents($dataviewer_class_file, $this->get_dataviewer_class_source())) {
		
			try {
				$db->exec("DROP TABLE `{$this->form->table}`");
				unlink($dataviewer_class_file);
				unlink($form_class_file);
				rmdir($form_dir);
			}
			catch (Exception $e) {}
			
			throw new RuntimeException('Could not create dataviewer class file: ' . $dataviewer_class_file, 2005);
		}
		
		$form_source_file = $form_dir . '/source.json';
		
		if (!file_put_contents($form_source_file, $this->get_prepared_form_source())) {
		
			try {
				$db->exec("DROP TABLE `{$this->form->table}`");
				unlink($form_source_file);
				unlink($dataviewer_class_file);
				unlink($form_class_file);
				rmdir($form_dir);
			}
			catch (Exception $e) {}
			
			throw new RuntimeException('Could not create form source file: ' . $form_source_file, 2006);
		}
		
		if ($this->has_file_input()) {
		
			$upload_dir = $form_dir . '/uploads';
			
			if (!mkdir($upload_dir)) {
			
				try {
					$db->exec("DROP TABLE `{$this->form->table}`");
					unlink($form_source_file);
					unlink($dataviewer_class_file);
					unlink($form_class_file);
					rmdir($form_dir);
				}
				catch (Exception $e) {}
				
				throw new RuntimeException('Could not create upload dir: ' . $upload_dir, 2007);
			}
		}
	}
	
	public function remove_form() {
	
		$db = new PDO("mysql:host={$this->config->db_host};dbname={$this->config->db_name}", $this->config->db_user, $this->config->db_pass, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_SILENT));
		$db->exec("DROP TABLE `{$this->form->table}`");
		
		$form_dir = $this->config->path . '/forms/' . $this->form->name;
		$upload_dir = $form_dir . '/uploads';
		
		if (is_dir($upload_dir)) {
			$uploaded_files = scandir($upload_dir);
			foreach ($uploaded_files as $file) {
				if ($file !== '.' && $file !== '..') {
					unlink($upload_dir . '/' . $file);
				}
			}
			rmdir($upload_dir);
		}
		
		unlink($form_dir . '/form.php');
		unlink($form_dir . '/dataviewer.php');
		unlink($form_dir . '/source.json');
		rmdir($form_dir);
		
		return $db->query("SELECT 1 FROM `{$this->form->table}`") === false && !is_dir($form_dir);
	}
	
	public function update_form() {
	
		$old_form = new NarinFormCreator($this->form->name);
		
		if ($this->form->table !== $old_form->form->table) {
			throw new LogicException('Forms do not have identical table name', 3001);
		}
		if (!$this->has_identical_table_structure($old_form)) {
			throw new LogicException('Forms do not have identical table structure', 3002);
		}
		
		$form_dir = $this->config->path . '/forms/' . $this->form->name;
		$form_class_file = $form_dir . '/form.php';
		$dataviewer_class_file = $form_dir . '/dataviewer.php';
		$form_source_file = $form_dir . '/source.json';
		
		if (!file_put_contents($form_class_file, $this->get_form_class_source())) {
			throw new RuntimeException('Could not update form class file: ' . $form_class_file, 3003);
		}
		if (!file_put_contents($dataviewer_class_file, $this->get_dataviewer_class_source())) {
			throw new RuntimeException('Could not update dataviewer class file: ' . $dataviewer_class_file, 3004);
		}
		if (!file_put_contents($form_source_file, $this->get_prepared_form_source())) {
			throw new RuntimeException('Could not update form source file: ' . $form_source_file, 3005);
		}
	}
	
	public function get_form_table_source() {
	
		$sql = "CREATE TABLE `{$this->form->table}` (\r\n";
		
		if ($this->form->ref_id) {
			$sql .= "  `_id_` BIGINT NOT NULL,\r\n";
		}
		else {
			$sql .= "  `_id_` INT UNSIGNED NOT NULL AUTO_INCREMENT,\r\n";
		}
		
		$sql .= "  `_userid_` INT UNSIGNED DEFAULT NULL,\r\n";
		$sql .= "  `_approval_` CHAR(1) NOT NULL,\r\n";
		$sql .= "  `_approvalmsg_` VARCHAR(400) DEFAULT NULL,\r\n";
		$sql .= "  `_timestamp_` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,\r\n";
		
		foreach ($this->form->controls as $control) {
			$type = $this->sql_type($control);
			if ($type !== false) {
				$sql .= "  `{$control->name}` {$type},\r\n";
			}
		}
		
		$sql .=
			"  PRIMARY KEY (`_id_`),\r\n" .
			"  KEY `_userid_` (`_userid_`)\r\n" .
			") CHARACTER SET utf8 COLLATE utf8_general_ci;";
		
		return $sql;
	}
	
	public function get_form_class_source() {
		return
			"<?php\r\n" .
			"require_once \$NarinConfig->path . '/lib/narin/narinform.php';\r\n" .
			"class {$this->form->name}_form extends NarinForm {\r\n" .
			"    public function __construct() {\r\n" .
			"        parent::__construct({$this->form_obj_stringified()}, {$this->controls_array_stringified()});\r\n" .
			"    }\r\n" .
			"    protected function form_view() {\r\n" .
			"        return {$this->form_view_stringified()};\r\n" .
			"    }\r\n" .
			"}";
	}
	
	public function get_dataviewer_class_source() {
		return
			"<?php\r\n" .
			"require_once \$NarinConfig->path . '/lib/narin/narindataviewer.php';\r\n" .
			"class {$this->form->name}_dataviewer extends NarinDataViewer {\r\n" .
			"    public function __construct() {\r\n" .
			"        parent::__construct({$this->dataviewer_form_obj_stringified()}, {$this->dataviewer_controls_array_stringified()});\r\n" .
			"    }\r\n" .
			"}";
	}
	
	public function get_prepared_form_source() {
		return $this->json_encode_v($this->form);
	}
	
	public function has_identical_table_structure($form) {
	
		if ((bool)$this->form->ref_id != (bool)$form->form->ref_id) {
			return false;
		}
		
		$cols1 = array();
		foreach ($this->form->controls as $control) {
			$type = $this->sql_type($control);
			if ($type !== false) {
				$cols1[] = array(true, $control->name, $type);
			}
		}
		
		$cols2 = array();
		foreach ($form->form->controls as $control) {
			$type = $this->sql_type($control);
			if ($type !== false) {
				$cols2[] = array(true, $control->name, $type);
			}
		}
		
		$cols1_count = count($cols1);
		$cols2_count = count($cols2);
		
		if ($cols1_count != $cols2_count) {
			return false;
		}
		
		for ($i = 0; $i < $cols1_count; $i++) {
			for ($j = 0; $j < $cols2_count; $j++) {
				if ($cols1[$i][0] && $cols2[$j][0] && $cols1[$i][1] === $cols2[$j][1] && $cols1[$i][2] === $cols2[$j][2]) {
					$cols1[$i][0] = false;
					$cols2[$j][0] = false;
				}
			}
		}
		
		foreach ($cols1 as $col1) {
			if ($col1[0]) {
				return false;
			}
		}
		
		return true;
	}
	
	public function get_form_name() {
		return $this->form->name;
	}
	
	public function get_table_name() {
		return $this->form->table;
	}
	
	
	
	private function validate_form_source() {
		/*** not implemented yet ***/
		return is_object($this->form);
	}
	
	private function prepare_form_source() {
		
		if (!isset($this->form->name)) {
			$this->form->name = $this->prepare_name($this->form->title);
		}
		
		if (!isset($this->form->table)) {
			if (!preg_match('/^[A-Za-z0-9_-]*$/', $this->config->table_prefix)) {
				throw new DomainException('Invalid table_prefix in NarinConfig', 3001);
			}
			$this->form->table = $this->config->table_prefix . $this->form->name;
		}

		if (!isset($this->form->cols)) {
			$this->form->cols = 1;
		}
		
		foreach ($this->form->controls as $control) {
			if ($control->type !== 'heading' && $control->type !== 'paragraph' && !isset($control->name)) {
				$control->name = $this->prepare_name($control->label, true);
				$control->_auto_name = true;
			}
			$i = 1;
			if (isset($control->option_sets)) {
				$control->_all_options = array();
				foreach ($control->option_sets as $option_set) {
					foreach ($option_set->options as $option) {
						if (!isset($option->value)) {
							$option->value = (string)$i;
							$option->_auto_value = true;
							$i++;
						}
						$control->_all_options[] = $option;
					}
				}
			}
			elseif (isset($control->options)) {
				foreach ($control->options as $option) {
					if (!isset($option->value)) {
						$option->value = (string)$i;
						$option->_auto_value = true;
						$i++;
					}
				}
			}
		}
		
		do {
			$duplicates = false;
			for ($i = 0; $i < count($this->form->controls); $i++) {
				for ($j = $i + 1; $j < count($this->form->controls); $j++) {
					if ($this->form->controls[$i]->name === $this->form->controls[$j]->name) {
						$this->form->controls[$this->form->controls[$i]->_auto_name ? $i : $j]->name .= '_' . $i . '_' . $j;
						$duplicates = true;
					}
				}
			}
		} while ($duplicates);
		
		do {
			$duplicates = false;
			foreach ($this->form->controls as $control) {
				if (isset($control->option_sets)) {
					for ($i = 0; $i < count($control->_all_options); $i++) {
						for ($j = $i + 1; $j < count($control->_all_options); $j++) {
							if ($control->_all_options[$i]->value === $control->_all_options[$j]->value) {
								$control->_all_options[$control->_all_options[$i]->_auto_value ? $i : $j]->value .= '_' . $i . '_' . $j;
								$duplicates = true;
							}
						}
					}
				}
				elseif (isset($control->option)) {
					for ($i = 0; $i < count($control->options); $i++) {
						for ($j = $i + 1; $j < count($control->options); $j++) {
							if ($control->options[$i]->value === $control->options[$j]->value) {
								$control->options[$control->options[$i]->_auto_value ? $i : $j]->value .= '_' . $i . '_' . $j;
								$duplicates = true;
							}
						}
					}
				}
			}
		} while ($duplicates);
		
		foreach ($this->form->controls as $control) {
			unset($control->_auto_name);
			unset($control->_all_options);
			if (isset($control->option_sets)) {
				foreach ($control->option_sets as $option_set) {
					foreach ($option_set->options as $option) {
						unset($option->_auto_value);
					}
				}
			}
			elseif (isset($control->options)) {
				foreach ($control->options as $option) {
					unset($option->_auto_value);
				}
			}
		}
	}
	
	private function prepare_name($str, $hyphen_allowed = false) {
	
		$fa = array('آ', 'ا', 'ب', 'پ', 'ت', 'ث', 'ج', 'چ' , 'ح', 'خ' , 'د', 'ذ', 'ر', 'ز', 'ژ' , 'س', 'ش' , 'ص', 'ض', 'ط', 'ظ', 'ع', 'غ' , 'ف', 'ق' , 'ک', 'ك', 'گ', 'ل', 'م', 'ن', 'و', 'ه', 'ی', 'ي', '۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹');
		$en = array('a', 'a', 'b', 'p', 't', 's', 'j', 'ch', 'h', 'kh', 'd', 'z', 'r', 'z', 'zh', 's', 'sh', 's', 'z', 't', 'z', '' , 'gh', 'f', 'gh', 'k', 'k', 'g', 'l', 'm', 'n', 'u', 'h', 'y', 'y', '0', '1', '2', '3', '4', '5', '6', '7', '8', '9');
		
		$name = preg_replace('/\s+/', '_', preg_replace('/(^[^A-Za-z]+)|([^A-Za-z0-9\s_-]+)/', '', str_replace($fa, $en, trim($str))));
		
		if (!$hyphen_allowed) {
			$name = str_replace('-', '_', $name);
		}
		
		return $name === '' ? 'x' : $name;
	}
	
	private function sql_type($control) {
	
		switch ($control->type) {
		
			case 'text':
				
				switch ($control->validation) {
					case 'integer':
						if (isset($control->min) && isset($control->max)) {
							if ($control->min >= -32768 && $control->max <= 32767) {
								$type = 'SMALLINT';
							}
							else if ($control->min >= -2147483648 && $control->max <= 2147483647) {
								$type = 'INT';
							}
							else {
								$type = 'BIGINT';
							}
						}
						else if (isset($control->min) || isset($control->max)) {
							if ($control->min < -2147483648 || $control->max > 2147483647) {
								$type = 'BIGINT';
							}
							else {
								$type = 'INT';
							}
						}
						else {
							$type = 'INT';
						}
						break;
					case 'decimal':
						$type = 'DOUBLE';
						break;
					case 'mobile':
						$type = 'CHAR(11)';
						break;
					case 'tel':
						$type = 'CHAR(12)';
						break;
					case 'postal-code':
					case 'ssn':
						$type = 'CHAR(10)';
						break;
					case 'pbn':
						$type = 'VARCHAR(10)';
						break;
					case 'num-code':
						$type = isset($control->digits) ? "CHAR({$control->digits})" : 'VARCHAR(255)';
						break;
					default:
						$type = isset($control->max_len) ? "VARCHAR({$control->max_len})" : 'VARCHAR(255)';
				}
				
				return $type . ($control->required ? ' NOT NULL' : ' DEFAULT NULL');
				
			case 'textarea':
				
				if (isset($control->max_len)) {
					if ($control->max_len > 16777215) {
						$type = 'LONGTEXT';
					}
					else if ($control->max_len > 65535) {
						$type = 'MEDIUMTEXT';
					}
					else {
						$type = 'TEXT';
					}
				}
				else {
					$type = 'TEXT';
				}
				
				return $type . ($control->required ? ' NOT NULL' : ' DEFAULT NULL');
				
			case 'radio':
				
				return 'VARCHAR(255)' . ($control->required ? ' NOT NULL' : ' DEFAULT NULL');
				
			case 'select':
			case 'select-slave':
				
				return 'VARCHAR(255)' . ($control->required || !$control->has_null_opt ? ' NOT NULL' : ' DEFAULT NULL');
				
			case 'checkbox':
				
				return $control->required ? false : 'TINYINT NOT NULL';
				
			case 'checkbox-array':
				
				return 'TEXT NOT NULL';
				
			case 'date':
				
				return 'DATE' . ($control->required ? ' NOT NULL' : ' DEFAULT NULL');
				
			case 'time':
				
				return 'TIME' . ($control->required ? ' NOT NULL' : ' DEFAULT NULL');
				
			case 'datetime':
				
				return 'DATETIME' . ($control->required ? ' NOT NULL' : ' DEFAULT NULL');
				
			case 'file':
				
				return 'VARCHAR(255)' . ($control->required ? ' NOT NULL' : ' DEFAULT NULL');
				
			default:
			
				return false;
		}
	}
	
	private function form_obj_stringified() {
	
		$pairs = array();
		
		foreach ($this->form as $key => $val) {
			if ($key !== 'submit' && $key !== 'reset' && $key !== 'cols' && $key !== 'controls') {
				if (is_string($val)) {
					$pairs[] = "'$key' => '{$this->escape_string($val)}'";
				}
				else { // int
					$pairs[] = "'$key' => $val";
				}
			}
		}
		
		return '(object)array(' . implode(', ', $pairs) . ')';
	}
	
	private function controls_array_stringified() {
	
		$pairs = array();
		
		foreach ($this->form->controls as $control) {
			if ($control->type !== 'heading' && $control->type !== 'paragraph') {
				$pairs[] = "'{$control->name}' => {$this->control_obj_stringified($control)}";
			}
		}
		
		return 'array(' . implode(', ', $pairs) . ')';
	}
	
	private function control_obj_stringified($control) {
	
		$control_pairs = array();
		
		foreach ($control as $key => $val) {
			if ($key !== 'name' && $key !== 'label' && $key !== 'ltr' && $key !== 'description' && $key !== 'null_opt_label' && $key !== 'options' && $key !== 'option_sets') {
				if (is_object($val)) { // date/time/datetime
					$datetime_pairs = array();
					foreach ($val as $d_key => $d_val) {
						$datetime_pairs[] = "'$d_key' => $d_val";
					}
					$control_pairs[] = "'$key' => (object)array(" . implode(', ', $datetime_pairs) . ')';
				}
				else if (is_string($val)) {
					$control_pairs[] = "'$key' => '{$this->escape_string($val)}'";
				}
				else { // int/float
					$control_pairs[] = "'$key' => $val";
				}
			}
		}
		
		if (isset($control->options)) {
		
			$option_values = array();
			foreach ($control->options as $option) {
				$option_values[] = "'{$this->escape_string($option->value)}'";
			}
			$control_pairs[] = "'values' => array(" . implode(', ', $option_values) . ')';
			
			if ($control->type === 'radio') {
				foreach ($control->options as $option) {
					if ($option->def) {
						$control_pairs[] = "'def' => '{$this->escape_string($option->value)}'";
						break;
					}
				}
			}
			else if ($control->type === 'checkbox-array') {
				$def_values = array();
				foreach ($control->options as $option) {
					if ($option->def) {
						$def_values[] =  "'{$this->escape_string($option->value)}'";
					}
				}
				if ($def_values) {
					$control_pairs[] = "'def' => array(" . implode(', ', $def_values) . ')';
				}
			}
		}
		elseif (isset($control->option_sets)) {

			$option_sets_values = array();

			foreach ($control->option_sets as $option_set) {

				/*** [master => value] instead of [master => index] ***/
				$master_values = array();
				foreach ($option_set->masters_index as $master => $index) {
					$master_values[] = "'$master' => $index";
				}

				$option_values = array();
				foreach ($option_set->options as $option) {
					$option_values[] = "'{$this->escape_string($option->value)}'";
				}

				$option_sets_values[] =
					'(object)array(' .
					"'masters' => array(" . implode(', ', $master_values) . '), ' .
					"'values' => array(" . implode(', ', $option_values) . ')' .
					')';
			}

			$control_pairs[] = "'value_sets' => array(" . implode(', ', $option_sets_values) . ')';
		}
		
		return '(object)array(' . implode(', ', $control_pairs) . ')';
	}
	
	private function form_view_stringified() {
	
		$parts = array();
		
		$form_id = 'narin_form_' . $this->form->name;
		
		$parts[] = array(0, '<form id="' . $form_id . '" method="post"' . ($this->has_file_input() ? ' enctype="multipart/form-data"' : '') . ($this->form->cols != 1 ? ' data-cols="' . $this->form->cols . '"' : '') . '>');
		$parts[] = array(0, '<input type="hidden" name="_form_" value="' . $this->form->name . '" />');
		$parts[] = array(0, '<input type="hidden" name="_token_" value="');
		$parts[] = array(1, 'parent::generate_token()');
		$parts[] = array(0, '" />');
		$parts[] = array(0, '<h1>' . $this->escape_html($this->form->title) . '</h1>');
		
		foreach ($this->form->controls as $control) {
			$this->control_view_parts($control, $parts);
		}
		
		if ($this->form->captcha) {
			$parts[] = array(0, '<p');
			$parts[] = array(1, "parent::attr('_captcha_')");
			$parts[] = array(0, '><span class="captcha"><img title="کد امنیتی" src="');
			$parts[] = array(1, "parent::captcha_url()");
			$parts[] = array(0, '" /><a href="#new">تصویر جدید</a></span><label><span>کد امنیتی:</span><input type="text" name="_captcha_" autocomplete="off" dir="ltr" /> <strong><abbr title="الزامی">*</abbr></strong></label></p>');
		}
		
		$parts[] = array(0, '<p class="buttons"><input type="submit" value="' . $this->escape_html($this->form->submit) . '" />' . (isset($this->form->reset) ? '<input type="reset" value="' . $this->escape_html($this->form->reset) . '" />' : '') . '</p>');
		$parts[] = array(0, '</form>');
		
		$form_validation_json = $this->form_validation_json();
		
		if ($form_validation_json !== false) {
			$parts[] = array(0, '<script type="text/javascript"> NarinForm.add(\'' . $form_id . '\',' . $form_validation_json . '); </script>');
		}
		
		$parts_count = count($parts);
		
		$part = $parts[0];
		$source = ($part[0] ? '(' . $part[1] . ')' : "'" . $this->escape_string($part[1]) . "'");
		
		for ($i = 1; $i < $parts_count; $i++) {
			$part = $parts[$i];
			$source .= '.' . ($part[0] ? '(' . $part[1] . ')' : "'" . $this->escape_string($part[1]) . "'");
		}
		
		return $source;
	}
	
	private function control_view_parts($control, &$parts) {
	
		switch ($control->type) {
		
			case 'text':
			
				$parts[] = array(0, '<p');
				$parts[] = array(1, "parent::attr('{$control->name}')");
				$parts[] = array(0, '><label><span>' . $this->escape_html($control->label) . '</span><input type="text" name="' . $control->name . '" maxlength="' . (isset($control->max_len) ? $control->max_len : '255') . '"' . ($this->is_ltr($control) ? ' dir="ltr"' : ''));
				$parts[] = array(1, "parent::value('{$control->name}')");
				$parts[] = array(0, ' />' . $this->control_view_required($control) . '</label>' . $this->control_view_description($control) . '</p>');
				
				return;
			
			case 'textarea':
			
				$parts[] = array(0, '<p');
				$parts[] = array(1, "parent::attr('{$control->name}')");
				$parts[] = array(0, '><label><span>' . $this->escape_html($control->label) . '</span><textarea name="' . $control->name . '"' . ($this->is_ltr($control) ? ' dir="ltr"' : '') . (isset($control->max_len) ? ' data-maxlength="' . $control->max_len . '"' : '') . '>');
				$parts[] = array(1, "parent::value('{$control->name}')");
				$parts[] = array(0, '</textarea>' . $this->control_view_required($control) . '</label>' . $this->control_view_description($control) . (isset($control->max_len) ? '<span class="textarea-maxlen">حداکثر ' . $control->max_len . ' کارکتر</span>' : '') . '</p>');
				
				return;
			
			case 'radio':
			
				$parts[] = array(0, '<div');
				$parts[] = array(1, "parent::attr('{$control->name}')");
				$parts[] = array(0, '><fieldset><legend>' . $this->escape_html($control->label) . $this->control_view_required($control) . '</legend><ul>');
				
				foreach ($control->options as $option) {
					$parts[] = array(0, '<li><label><input type="radio" name="' . $control->name . '" value="' . $this->escape_html($option->value) . '"');
					$parts[] = array(1, "parent::value('{$control->name}','{$this->escape_string($option->value)}')");
					$parts[] = array(0, ' /><span>' . $this->escape_html($option->label) . '</span></label></li>');
				}
				
				$parts[] = array(0, '</ul></fieldset></div>');
				
				return;
			
			case 'select':
			
				$parts[] = array(0, '<p');
				$parts[] = array(1, "parent::attr('{$control->name}')");
				$parts[] = array(0, '><label><span>' . $this->escape_html($control->label) . '</span><select name="' . $control->name . '">');
				
				if ($control->has_null_opt) {
					$parts[] = array(0, '<option value="">' . $this->escape_html($control->null_opt_label) . '</option>');
				}
				
				foreach ($control->options as $option) {
					$parts[] = array(0, '<option value="' . $this->escape_html($option->value) . '"');
					$parts[] = array(1, "parent::value('{$control->name}','{$this->escape_string($option->value)}')");
					$parts[] = array(0, '>' . $this->escape_html($option->label) . '</option>');
				}
				
				$parts[] = array(0, '</select>' . $this->control_view_required($control) . '</label></p>');
				
				return;
			
			case 'select-slave':

				$parts[] = array(0, '<p');
				$parts[] = array(1, "parent::attr('{$control->name}')");
				$parts[] = array(0, '><label><span>' . $this->escape_html($control->label) . '</span><select name="' . $control->name . '" data-master="' . $control->master_name . '" data-masters="' . implode(' ', array_keys((array)$control->option_sets[0]->masters_index)) . '">');
				
				if ($control->has_null_opt) {
					$parts[] = array(0, '<option value="">' . $this->escape_html($control->null_opt_label) . '</option>');
				}
				
				foreach ($control->option_sets as $option_set) {
					$parts[] = array(0, '<optgroup label="' . $this->escape_string($option_set->masters_label) . '"');
					foreach ($option_set->masters_index as $name => $index) {
						$parts[] = array(0, ' data-master-' . $name . '="' . $index . '"');
					}
					$parts[] = array(0, '>');
					foreach ($option_set->options as $option) {
						$parts[] = array(0, '<option value="' . $this->escape_html($option->value) . '"');
						$parts[] = array(1, "parent::value('{$control->name}','{$this->escape_string($option->value)}')");
						$parts[] = array(0, '>' . $this->escape_html($option->label) . '</option>');
					}
					$parts[] = array(0, '</optgroup>');
				}
				
				$parts[] = array(0, '</select>' . $this->control_view_required($control) . '</label></p>');
				
				return;
			
			case 'checkbox':
			
				$parts[] = array(0, '<p');
				$parts[] = array(1, "parent::attr('{$control->name}')");
				$parts[] = array(0, '><label><input type="checkbox" name="' . $control->name . '"');
				$parts[] = array(1, "parent::value('{$control->name}')");
				$parts[] = array(0, ' /><span>' . $this->escape_html($control->label) . '</span></label></p>');
				
				return;
			
			case 'checkbox-array':
			
				$parts[] = array(0, '<div');
				$parts[] = array(1, "parent::attr('{$control->name}')");
				$parts[] = array(0, '><fieldset><legend>' . $this->escape_html($control->label) . $this->control_view_required($control) . '</legend>' . $this->control_view_description($control) . '<ul>');
				
				foreach ($control->options as $option) {
					$parts[] = array(0, '<li><label><input type="checkbox" name="' . $control->name . '[]" value="' . $this->escape_html($option->value) . '"');
					$parts[] = array(1, "parent::value('{$control->name}','{$this->escape_string($option->value)}')");
					$parts[] = array(0, ' /><span>' . $this->escape_html($option->label) . '</span></label></li>');
				}
				
				$parts[] = array(0, '</ul></fieldset></div>');
				
				return;
			
			case 'date':
			
				$parts[] = array(0, '<p');
				$parts[] = array(1, "parent::attr('{$control->name}')");
				$parts[] = array(0, '>');
				$parts[] = array(1, "parent::error('{$control->name}')");
				$parts[] = array(0, '<label><span>' . $this->escape_html($control->label) . '</span></label><span class="muti-inp"><select name="' . $control->name . '[d]" title="روز"><option value=""></option>');
				
				for ($i = 1; $i <= 31; $i++) {
					$parts[] = array(0, '<option value="' . $i . '"');
					$parts[] = array(1, "parent::value('{$control->name}','d','$i')");
					$parts[] = array(0, '>' . $i . '</option>');
				}
				
				$parts[] = array(0, '</select> <select name="' . $control->name . '[m]" title="ماه"><option value=""></option>');
				
				for ($i = 1; $i <= 12; $i++) {
					$parts[] = array(0, '<option value="' . $i . '"');
					$parts[] = array(1, "parent::value('{$control->name}','m','$i')");
					$parts[] = array(0, '>' . $this->config->months[$i] . '</option>');
				}
				
				$parts[] = array(0, '</select> <input type="text" name="' . $control->name . '[y]" title="سال؛ مانند 1390" dir="ltr" size="4" maxlength="4"');
				$parts[] = array(1, "parent::value('{$control->name}','y')");
				$parts[] = array(0, ' /></span>' . $this->control_view_required($control) . $this->control_view_description($control) . '</p>');
				
				return;
			
			case 'time':
			
				$parts[] = array(0, '<p');
				$parts[] = array(1, "parent::attr('{$control->name}')");
				$parts[] = array(0, '><label><span>' . $this->escape_html($control->label) . '</span></label><span class="muti-inp"><select name="' . $control->name . '[n]" title="دقیقه"><option value=""></option>');
				
				for ($i = 0; $i <= 59; $i++) {
					$parts[] = array(0, '<option value="' . $i . '"');
					$parts[] = array(1, "parent::value('{$control->name}','n','$i')");
					$parts[] = array(0, '>' . ($i > 9 ? $i : '0' . $i) . '</option>');
				}
				
				$parts[] = array(0, '</select> : <select name="' . $control->name . '[h]" title="ساعت"><option value=""></option>');
				
				for ($i = 0; $i <= 23; $i++) {
					$parts[] = array(0, '<option value="' . $i . '"');
					$parts[] = array(1, "parent::value('{$control->name}','h','$i')");
					$parts[] = array(0, '>' . ($i > 9 ? $i : '0' . $i) . '</option>');
				}
				
				$parts[] = array(0, '</select></span>' . $this->control_view_required($control) . $this->control_view_description($control) . '</p>');
				
				return;
			
			case 'datetime':
			
				$parts[] = array(0, '<p');
				$parts[] = array(1, "parent::attr('{$control->name}')");
				$parts[] = array(0, '>');
				$parts[] = array(1, "parent::error('{$control->name}')");
				$parts[] = array(0, '<label><span>' . $this->escape_html($control->label) . '</span></label><span class="muti-inp"><select name="' . $control->name . '[d]" title="روز"><option value=""></option>');
				
				for ($i = 1; $i <= 31; $i++) {
					$parts[] = array(0, '<option value="' . $i . '"');
					$parts[] = array(1, "parent::value('{$control->name}','d','$i')");
					$parts[] = array(0, '>' . $i . '</option>');
				}
				
				$parts[] = array(0, '</select> <select name="' . $control->name . '[m]" title="ماه"><option value=""></option>');
				
				for ($i = 1; $i <= 12; $i++) {
					$parts[] = array(0, '<option value="' . $i . '"');
					$parts[] = array(1, "parent::value('{$control->name}','m','$i')");
					$parts[] = array(0, '>' . $this->config->months[$i] . '</option>');
				}
				
				$parts[] = array(0, '</select> <input type="text" name="' . $control->name . '[y]" title="سال؛ مانند 1390" dir="ltr" size="4" maxlength="4"');
				$parts[] = array(1, "parent::value('{$control->name}','y')");
				$parts[] = array(0, ' /> ساعت <select name="' . $control->name . '[n]" title="دقیقه"><option value=""></option>');
				
				for ($i = 0; $i <= 59; $i++) {
					$parts[] = array(0, '<option value="' . $i . '"');
					$parts[] = array(1, "parent::value('{$control->name}','n','$i')");
					$parts[] = array(0, '>' . ($i > 9 ? $i : '0' . $i) . '</option>');
				}
				
				$parts[] = array(0, '</select> : <select name="' . $control->name . '[h]" title="ساعت"><option value=""></option>');
				
				for ($i = 0; $i <= 23; $i++) {
					$parts[] = array(0, '<option value="' . $i . '"');
					$parts[] = array(1, "parent::value('{$control->name}','h','$i')");
					$parts[] = array(0, '>' . ($i > 9 ? $i : '0' . $i) . '</option>');
				}
				
				$parts[] = array(0, '</select></span>' . $this->control_view_required($control) . $this->control_view_description($control) . '</p>');
				
				return;
			
			case 'file':
			
				$parts[] = array(0, '<p');
				$parts[] = array(1, "parent::attr('{$control->name}')");
				$parts[] = array(0, '>');
				$parts[] = array(1, "parent::error('{$control->name}')");
				$parts[] = array(0, '<label><span>' . $this->escape_html($control->label) . '</span><input type="file" name="' . $control->name . '" />' . $this->control_view_required($control) . '</label>' . $this->control_view_description($control) . '</p>');
				
				return;
			
			case 'heading':
			
				$parts[] = array(0, "<h{$control->level}>{$this->escape_html($control->title)}</h{$control->level}>");
				
				return;
			
			case 'paragraph':
			
				$parts[] = array(0, '<p class="description">' . $this->escape_html($control->text) . '</p>');
				
				return;
		}
	}
	
	private function control_view_required($control) {
		return $control->required || (($control->type === 'select' || $control->type === 'select-slave') && !$control->has_null_opt) ? ' <strong><abbr title="الزامی">*</abbr></strong>' : '';
	}
	
	private function control_view_description($control) {
	
		switch ($control->type) {
		
			case 'text':
			
				switch ($control->validation) {
					case 'email':
						return ' <em>مانند <b dir="ltr" lang="en">someone@somewhere.com</b></em>';
					case 'url':
						return ' <em>مانند <b dir="ltr" lang="en">http://www.somewhere.com/somepage</b></em>';
					case 'farsi-word':
						return ' <em>فقط حروف فارسی</em>';
					case 'farsi-text':
						return ' <em>فقط حروف و علائم فارسی</em>';
					case 'integer':
						$d = isset($control->min) ? 'حداقل <b dir="ltr">' . $control->min . '</b>' : '';
						$d .= isset($control->max) ? ($d ? ' و ' : '') . 'حداکثر <b dir="ltr">' . $control->max . '</b>' : '';
						return ' <em>عدد صحیح' . ($d ? '؛ ' . $d : '') . '</em>';
					case 'decimal':
						$d = isset($control->min) ? 'حداقل ' . ($control->min_inc ? '' : 'بزرگتر از ') . '<b dir="ltr">' . $control->min . '</b>' : '';
						$d .= isset($control->max) ? ($d ? ' و ' : '') . 'حداکثر ' . ($control->max_inc ? '' : 'کوچکتر از ') . '<b dir="ltr">' . $control->max . '</b>' : '';
						return ' <em>عدد اعشاری' . ($d ? '؛ ' . $d : '') . '؛ قسمت صحیح و اعشار را با نقطه (<b>.</b>) جدا کنید</em>';
					case 'mobile':
						return ' <em class="important">مانند <b dir="ltr">09121234567</b></em>';
					case 'tel':
						return ' <em class="important">مانند <b dir="ltr">021-12345678</b></em>';
					case 'postal-code':
						return ' <em>کد پستی ده رقمی، بدون فاصله یا خط تیره</em>';
					case 'ssn':
						return ' <em>شماره ملی ده رقمی، بدون فاصله یا خط تیره</em>';
					case 'pbn':
						return ' <em>بدون فاصله یا خط تیره</em>';
					case 'num-code':
						return ' <em>کد عددی' . (isset($control->digits) ? ' <b dir="ltr">' . $control->digits . '</b> رقمی' : '') . ($control->no_leading_zero ? '؛ نمی‌تواند با صفر شروع شود' : '') . '</em>';
					case 'regexp':
						return isset($control->description) ? ' <em class="important">' . $this->escape_html($control->description) . '</em>' : '';
				}
				
				return '';
			
			case 'textarea':
			
				switch ($control->validation) {
					case 'farsi-text':
						return ' <em>فقط حروف و علائم فارسی</em>';
					case 'regexp':
						return isset($control->description) ? ' <em class="important">' . $this->escape_html($control->description) . '</em>' : '';
				}
				
				return '';
			
			case 'checkbox-array':
			
				$d = isset($control->min) ? 'حداقل <b dir="ltr">' . $control->min . '</b>' : '';
				$d .= isset($control->max) ? ($d ? ' و ' : '') . 'حداکثر <b dir="ltr">' . $control->max . '</b>' : '';
				return $d ? ' <em class="important">' . $d . ' انتخاب</em>' : '';
			
			case 'date':
			case 'datetime':
			
				$now = $control->type === 'date' ? 'تاریخ جاری' : 'زمان جاری';
				$from = '';
				$to = '';
				
				switch ($control->validation) {
					case 'past':
						$to = 'تا پیش از ' . $now;
						break;
					case 'past-now':
						$to = 'تا ' . $now;
						break;
					case 'future':
						$from = 'پس از ' . $now;
						break;
					case 'future-now':
						$from = 'از ' . $now;
						break;
					case 'range':
						switch ($control->from) {
							case 'now-inc':
								$from = 'از ' . $now;
								break;
							case 'now-exc':
								$from = 'پس از ' . $now;
								break;
							case 'value':
								$from = 'از <b>' . $this->format_persian_datetime($control->from_value) . '</b>';
								break;
						}
						switch ($control->to) {
							case 'now-inc':
								$to = 'تا ' . $now;
								break;
							case 'now-exc':
								$to = 'تا پیش از ' . $now;
								break;
							case 'value':
								$to = 'تا <b>' . $this->format_persian_datetime($control->to_value) . '</b>';
								break;
						}
				}
				
				return $from || $to ? ' <em>بازه‌ی مجاز: ' . $from . ($from ? ' ' : '') . $to . '</em>' : '';
			
			case 'file':
			
				$d = isset($control->description) ? $this->escape_html($control->description) : '';
				$d .= isset($control->valid_exts) ? (($d ? ' ' : '') . '<b dir="ltr" lang="en">' . ($d ? '(' : '') . $this->escape_html('*.' . str_replace(';', ';*.', $control->valid_exts)) . ($d ? ')' : '') . '</b>') : '';
				$d = ($d ? 'فرمت مجاز: ' : '') . $d;
				$d .= isset($control->max_file_size) ? ($d ? '؛ ' : '') . 'حداکثر حجم: <b>' . $this->format_file_size($control->max_file_size) . '</b>' : '';
				return $d ? ' <em class="important">' . $d . '</em>' : '';
			
			default:
			
				return '';
		}
	}
	
	private function form_validation_json() {
	
		$controls = array();
		
		foreach ($this->form->controls as $control) {
			if ($control->type !== 'heading' && $control->type !== 'paragraph') {
				foreach ($control as $key => $val) {
					if ($key !== 'name' && $key !== 'label' && $key !== 'def' && $key !== 'ltr' && $key !== 'description' && $key !== 'has_null_opt' && $key !== 'null_opt_label' && $key !== 'master_name' && $key !== 'options' && $key !== 'option_sets' && $key !== 'max_file_size' && !(($control->type === 'date' || $control->type === 'datetime') && ($key === 'validation' || $key === 'from' || $key === 'to'))) {
						if ($control->type === 'date' && ($key === 'from_value' || $key === 'to_value')) {
							$controls[$control->name][$key] = array($val->y, $val->m, $val->d);
						}
						else if ($control->type === 'datetime' && ($key === 'from_value' || $key === 'to_value')) {
							$controls[$control->name][$key] = array($val->y, $val->m, $val->d, $val->h, $val->n);
						}
						else {
							$controls[$control->name][$key] = $val;
						}
					}
				}
			}
		}
		
		$useless = array();
		
		foreach ($controls as $control_name => $control_props) {
			if (count($control_props) == 1 && $control_props['type'] !== 'date' && $control_props['type'] !== 'time' && $control_props['type'] !== 'datetime') {
				$useless[] = $control_name;
			}
		}
		
		foreach ($useless as $control_name) {
			unset($controls[$control_name]);
		}
		
		return $controls ? $this->json_encode_v($controls) : false;
	}
	
	private function dataviewer_form_obj_stringified() {
	
		$pairs = array();
		
		foreach ($this->form as $key => $val) {
			if ($key !== 'submit' && $key !== 'reset' && $key !== 'cols' && $key !== 'prereq' && $key !== 'captcha' && $key !== 'on_success' && $key !== 'redir_url' && $key !== 'controls') {
				if (is_string($val)) {
					$pairs[] = "'$key' => '{$this->escape_string($val)}'";
				}
				else { // int
					$pairs[] = "'$key' => $val";
				}
			}
		}
		
		return '(object)array(' . implode(', ', $pairs) . ')';
	}
	
	private function dataviewer_controls_array_stringified() {
	
		$pairs = array();
		
		foreach ($this->form->controls as $control) {
			if ($control->type !== 'heading' && $control->type !== 'paragraph' && !($control->type === 'checkbox' && $control->required)) {
				$pairs[] = "'{$control->name}' => {$this->dataviewer_control_obj_stringified($control)}";
			}
		}
		
		return 'array(' . implode(', ', $pairs) . ')';
	}
	
	private function dataviewer_control_obj_stringified($control) {
	
		$control_pairs = array();
		
		foreach ($control as $key => $val) {
			if ($key === 'type' || $key === 'label') {
				$control_pairs[] = "'$key' => '{$this->escape_string($val)}'";
			}
		}
		
		if ($this->is_ltr($control)) {
			$control_pairs[] = "'ltr' => 1";
		}
		
		$options = null;
		if (isset($control->options)) {
			$options = $control->options;
		}
		elseif (isset($control->option_sets)) {
			$options = array();
			foreach ($control->option_sets as $option_set) {
				foreach ($option_set->options as $option) {
					$options[] = $option;
				}
			}
		}

		if ($options) {
			$option_pairs = array();
			foreach ($options as $option) {
				$option_pairs[] = "'{$this->escape_string($option->value)}' => '{$this->escape_string($option->label)}'";
			}
			$control_pairs[] = "'options' => array(" . implode(', ', $option_pairs) . ')';
		}
		
		return '(object)array(' . implode(', ', $control_pairs) . ')';
	}
	
	private function is_ltr($control) {
		return ($control->type === 'text' && ($control->ltr || $control->validation === 'email' || $control->validation === 'url' || $control->validation === 'integer' || $control->validation === 'decimal' || $control->validation === 'mobile' || $control->validation === 'tel' || $control->validation === 'postal-code' || $control->validation === 'ssn' || $control->validation === 'pbn' || $control->validation === 'num-code')) || ($control->type === 'textarea' && $control->ltr);
	}
	
	private function has_file_input() {
		foreach ($this->form->controls as $control) {
			if ($control->type === 'file') {
				return true;
			}
		}
		return false;
	}
	
	private function escape_string($str) {
		return str_replace('\'', '\\\'', str_replace('\\', '\\\\', $str));
	}
	
	private function escape_html($str) {
		return str_replace(array("\n", "\r"), array('&#10;', '&#13;'), htmlspecialchars($str, ENT_QUOTES | ENT_HTML401));
	}
	
	private function json_encode_v($value) {
		return json_encode($value, version_compare(PHP_VERSION, '5.4.0') >= 0 && defined('JSON_UNESCAPED_UNICODE') ? JSON_UNESCAPED_UNICODE : 0);
	}
	
	private function format_persian_datetime($datetime) {
		if (isset($datetime->y) && isset($datetime->h)) { // datetime
			return $datetime->d . ' ' . $this->config->months[$datetime->m] . ' ' . $datetime->y . ' ساعت ' . ($datetime->h > 9 ? $datetime->h : '0' . $datetime->h) . ':' . ($datetime->n > 9 ? $datetime->n : '0' . $datetime->n);
		}
		if (isset($datetime->y)) { // date
			return $datetime->d . ' ' . $this->config->months[$datetime->m] . ' ' . $datetime->y;
		}
		if (isset($datetime->h)) { // time
			return ($datetime->h > 9 ? $datetime->h : '0' . $datetime->h) . ':' . ($datetime->n > 9 ? $datetime->n : '0' . $datetime->n);
		}
		return '';
	}
	
	private function format_file_size($size) {
		if ($size < 1024) {
			return $size . ' بایت';
		}
		if ($size < 1048576) {
			return round(($size / 1024), 1) . ' کیلوبایت';
		}
		if ($size < 1073741824) {
			return round(($size / 1048576), 1) . ' مگابایت';
		}
		return round(($size / 1073741824), 1) . ' گیگابایت';
	}
}