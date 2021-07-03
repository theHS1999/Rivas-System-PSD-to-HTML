<?php

/* Copyright (C) Rivas System, Inc. All rights reserved. */
class NarinTools {

	public static function get_current_user_id() {


		/*

		TODO:

		return the current user id (int)
		return null if no user is logged in

		*/

		return 1;
	}

	public static function get_form_url($form_name) {

		/*

		TODO:

		return the form url

		*/

		return 'http://narin.localhost.com/demo/formdemo.php?form=' . $form_name;
	}

	public static function get_exporter_url($form_name) {

		/*

		TODO:

		return the exporter url

		*/

		return 'http://narin.localhost.com/demo/exporterdemo.php?form=' . $form_name;
	}

	public static function copy_data($from_form_name, $to_form_name) {

		global $NarinConfig;

		$from_form = self::get_form($from_form_name);
		$to_form = self::get_form($to_form_name);

		$from_table_cols = array();
		$to_table_cols = array();

		foreach ($from_form->controls as $control) {
			if (isset($control->name) && !($control->type === 'checkbox' && $control->required)) {
				$from_table_cols[] = $control->name;
			}
		}

		foreach ($to_form->controls as $control) {
			if (isset($control->name) && !($control->type === 'checkbox' && $control->required)) {
				$to_table_cols[] = $control->name;
			}
		}

		$common_cols = array('_id_', '_timestamp_');

		foreach ($from_table_cols as $col) {
			if (in_array($col, $to_table_cols)) {
				$common_cols[] = $col;
			}
		}

		$db = new PDO("mysql:host={$NarinConfig->db_host};dbname={$NarinConfig->db_name};charset=utf8", $NarinConfig->db_user, $NarinConfig->db_pass, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
		$db->exec('SET NAMES utf8');

		$select = $db->prepare('SELECT ' . implode(',', $common_cols) . " FROM `{$from_form->table}`");
		$insert = $db->prepare("INSERT INTO `{$to_form->table}` (" . implode(',', $common_cols) . ') VALUES (' . substr(str_repeat(',?', count($common_cols)), 1) . ')');

		$success = 0;
		$fail = 0;
		$select->execute();

		while ($row = $select->fetch(PDO::FETCH_NUM)) {
			try {
				$insert->execute($row);
				$success++;
			}
			catch (Exception $e) {
				$fail++;
			}
		}

		return array($success, $fail);
	}

	public static function search($form_name, $ref_id) {

		global $NarinConfig;

		$form = self::get_form($form_name);

		$db = new PDO("mysql:host={$NarinConfig->db_host};dbname={$NarinConfig->db_name};charset=utf8", $NarinConfig->db_user, $NarinConfig->db_pass, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
		$db->exec('SET NAMES utf8');

		$select = $db->prepare("SELECT * FROM `{$form->table}` WHERE `_id_` = ?");
		$select->execute(array(str_replace('-', '', $ref_id)));
		$row = $select->fetch(PDO::FETCH_ASSOC);

		if (!$row) {
			return false;
		}

		return self::process_form_data($form, $row);
	}

	private static function process_form_data($form, $data) {

		global $NarinConfig;

		$labels = array();
		$isfile = array();
		$options = array();

		foreach ($form->controls as $control) {
			if (isset($control->name)) {
				if (isset($control->label)) {
					$labels[$control->name] = $control->label;
				}
				$isfile[$control->name] = $control->type === 'file';
				if (isset($control->options)) {
					$options[$control->name] = array();
					foreach ($control->options as $option) {
						$options[$control->name][$option->value] = $option->label;
					}
				}
				elseif (isset($control->option_sets)) {
					$options[$control->name] = array();
					foreach ($control->option_sets as $option_set) {
						foreach ($option_set->options as $option) {
							$options[$control->name][$option->value] = $option->label;
						}
					}
				}
			}
		}

		$result = array();

		foreach ($data as $field => $value) {
			if (isset($labels[$field])) {
				$result[$labels[$field]] = isset($options[$field]) ? $options[$field][$value] : ($isfile[$field] ? $NarinConfig->url . '/download.php?form=' . $form->name . '&file=' . str_replace('_', '.', $value) : $value);
			}
		}

		return $result;
	}

	public static function user_search($ref_id) {

		global $NarinConfig;

		$user_id = self::get_current_user_id();

		if (is_null($user_id)) {
			return false;
		}

		$ref_id = str_replace('-', '', $ref_id);
		$results = array();

		$db = new PDO("mysql:host={$NarinConfig->db_host};dbname={$NarinConfig->db_name};charset=utf8", $NarinConfig->db_user, $NarinConfig->db_pass, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
		$db->exec('SET NAMES utf8');

		foreach (self::get_form_names() as $form_name) {

			$form = self::get_form($form_name);

			if ($form->ref_id) {

				$select = $db->prepare("SELECT * FROM `{$form->table}` WHERE `_id_` = ? AND `_userid_` = ?");
				$select->execute(array($ref_id, $user_id));
				$row = $select->fetch(PDO::FETCH_ASSOC);

				if ($row) {
					$results[] = array(
						'form_name' => $form_name,
						'status' => $form->approval_needed ? $row['_approval_'] : 'S',
						'msg' => $row['_approvalmsg_'],
						'prereq' => $form->prereq,
						'next' => $form->next,
						'data' => self::process_form_data($form, $row)
					);
				}
			}
		}

		return $results;
	}

	public static function get_form($form_name) {

		global $NarinConfig;

		$form_source_file = $NarinConfig->path . '/forms/' . $form_name . '/source.json';

		if (!is_file($form_source_file)) {
			throw new RuntimeException('Form source file does not exist: ' . $form_source_file, 1002);
		}

		$form_source = file_get_contents($form_source_file);

		if ($form_source === false) {
			throw new RuntimeException('Could not read form source file: ' . $form_source_file, 1003);
		}

		$form = json_decode($form_source, false);

		if (!self::validate_form_source($form)) {
			throw new RuntimeException('Form source file contains invalid form source: ' . $form_source_file, 1004);
		}

		return $form;
	}

	public static function get_form_names() {

		global $NarinConfig;

		$path = $NarinConfig->path . '/forms';
		$results = scandir($path);
		$form_names = array();

		foreach ($results as $result) {
			if ($result !== '.' && $result !== '..' && is_dir($path . '/' . $result)) {
				$form_names[] = $result;
			}
		}

		return $form_names;
	}

	private static function validate_form_source($form) {
		return is_object($form);
	}
}
