<?php

/* Copyright (C) Rivas System, Inc. All rights reserved. */

require_once $NarinConfig->path . '/lib/narin/narintools.php';

class NarinDataViewer {

	private $form;
	private $controls;
	private $config;
	private $status;
	private $results;
	private $page;
	private $results_per_page;
	private $order_by;
	private $order_type;
	private $filter;
	private $total_pages;
	private $filtered;
	private $filtered_rows;
	private $total_rows;
	private $pagination_limit = 10;
	private $results_per_page_default = 10;
	
	
	
	public function __construct($form, $controls) {
	
		global $NarinConfig;
		$this->config = $NarinConfig;
		
		$this->form = $form;
		$this->controls = $controls;
	}
	
	public function get_title() {
		return $this->form->title;
	}
	
	public function get_view_using_post() {
		return $this->get_view($_POST['page'], $_POST['results_per_page'], $_POST['order_by'], $_POST['order_type'], $_POST['filter']);
	}
	
	public function get_view($page, $results_per_page, $order_by, $order_type, $filter) {
	
		$this->page = is_null($page) ? 1 : (int)$page;
		if ($this->page < 1)
			$this->page = 1;
		
		$this->results_per_page = is_null($results_per_page) ? $this->results_per_page_default : (int)$results_per_page;
		if ($this->results_per_page < 1)
			$this->results_per_page = 1;
		
		$this->order_by = is_null($order_by) ? '_timestamp_' : (string)$order_by;
		if ($this->order_by !== '_timestamp_' && $this->order_by !== '_id_' && !isset($this->controls[$this->order_by]))
			$this->order_by = '_timestamp_';
		
		$order_type_default = $this->order_by === '_timestamp_' ? 'desc' : 'asc';
		$this->order_type = is_null($order_type) ? $order_type_default : strtolower($order_type);
		if ($this->order_type !== 'asc' && $this->order_type !== 'desc')
			$this->order_type = $order_type_default;
		
		$this->filter = is_array($filter) ? $filter : array();
		
		$this->get_results();
		
		return $this->view();
	}

	public function export() {
		$this->send_download_headers('csv');
		echo $this->data_csv();
		exit();
	}
	
	
	
	private function get_results() {
	
		$conditions = array();
		$values = array();
		
		foreach ($this->filter as $col => $val) {
			if (preg_match('/[^\s]/', $val) && ($col === '_id_' || $col === '_timestamp_' || isset($this->controls[$col]))) {
				if ($this->controls[$col]->type === 'textarea') {
					$conditions[] = "`$col` LIKE ?";
					$values[] = '%' . $this->prepare_filter_value($val, $col) . '%';
				}
				else {
					$conditions[] = "`$col` = ?";
					$values[] = $this->prepare_filter_value($val, $col);
				}
			}
		}
		
		$where = $conditions ? ' WHERE ' . implode(' AND ', $conditions) : '';
		$order = " ORDER BY `{$this->order_by}` {$this->order_type}";
		$limit = ' LIMIT ' . (($this->page - 1) * $this->results_per_page) . ',' . $this->results_per_page;
		
		try {
			$db = new PDO("mysql:host={$this->config->db_host};dbname={$this->config->db_name};charset=utf8", $this->config->db_user, $this->config->db_pass, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC));
			$db->exec('SET NAMES utf8');
			
			$result_st = $db->prepare("SELECT * FROM `{$this->form->table}`" . $where . $order . $limit);
			$result_st->execute($values);
			
			$count_st = $db->prepare("SELECT COUNT(*) AS `rows` FROM `{$this->form->table}`" . $where);
			$count_st->execute($values);
			$count_st_row = $count_st->fetch();
			$this->filtered_rows = (int)$count_st_row['rows'];
			$this->total_pages = ceil($this->filtered_rows / $this->results_per_page);
			
			if ($where === '') {
				$this->filtered = false;
				$this->total_rows = $this->filtered_rows;
			}
			else {
				$this->filtered = true;
				$count_st = $db->query("SELECT COUNT(*) AS `rows` FROM `{$this->form->table}`");
				$count_st_row = $count_st->fetch();
				$this->total_rows = (int)$count_st_row['rows'];
			}
		}
		catch (Exception $e) {
			$this->status = 'error';
			return;
		}
		
		$this->status = 'ok';
		$this->results = $result_st;
	}
	
	private function view() {
	
		return
			'<script type="text/javascript"> NarinDataViewer.setForm("' . $this->form->name . '"); </script>' .
			'<form class="narindataviewer" method="post">' .
			'<input type="hidden" name="page" value="' . $this->page . '" />' .
			'<input type="hidden" name="order_by" value="' . $this->order_by . '" />' .
			'<input type="hidden" name="order_type" value="' . $this->order_type . '" />' .
			'<h1>' . htmlspecialchars($this->form->title) . '</h1>' .
			$this->infobar() .
			'<table>' .
			$this->table_head() .
			$this->table_body() .
			'</table>' .
			$this->pagination() .
			'</form>';
	}
	
	private function infobar() {
	
		$view = '<p class="infobar' . ($this->status === 'error' ? ' error' : '') . '">';
		
		if ($this->status === 'error') {
			$view .= 'خواندن اطلاعات با مشکل مواجه شد.';
		}
		else {
			if ($this->total_rows == 0) {
				$view .= 'هیچ موردی ثبت نشده.';
			}
			else if ($this->filtered_rows == 0) {
				$view .= 'جستجوی شما با هیچ موردی از مجموع ' . $this->total_rows . ' مورد ثبت شده مطابقت ندارد.';
			}
			else {
				if ($this->filtered) {
					if ($this->filtered_rows == $this->total_rows) {
						$view .= 'جستجوی شما با تمام ' . $this->total_rows . ' مورد ثبت شده مطابقت دارد.';
					}
					else {
						$view .= 'جستجوی شما با ' . $this->filtered_rows . ' مورد از مجموع ' . $this->total_rows . ' مورد ثبت شده مطابقت دارد.';
					}
				}
				else {
					$view .= '' . $this->total_rows . ' مورد ثبت شده.';
				}
			}
		}
		
		if ($this->total_rows) {
			$view .= ' <a href="' . NarinTools::get_exporter_url($this->form->name) . '">دانلود همه‌ی اطلاعات ثبت شده</a>';
		}

		$view .= '</p>';
		
		return $view;
	}
	
	private function table_head() {
	
		$view = '<thead>';
		
		$view .= '<tr>';
		
		$view .= '<th>ردیف</th>';
		
		if ($this->form->ref_id) {
			$view .= '<th data-col="_id_">' . $this->table_head_sort('_id_') . 'شماره پیگیری</th>';
		}
		
		if ($this->form->approval_needed) {
			$view .= '<th data-col="_approval_">' . $this->table_head_sort('_approval_') . 'وضعیت تأیید</th>';
		}
		
		$view .= '<th data-col="_timestamp_">' . $this->table_head_sort('_timestamp_') . 'زمان ثبت</th>';
		
		foreach ($this->controls as $control_name => $control) {
			$view .= '<th data-col="' . $control_name . '">' . $this->table_head_sort($control_name, $control->type) . htmlspecialchars($control->label) . '</th>';
		}
		
		$view .= '</tr>';
		
		$view .= '</thead>';
		
		return $view;
	}
	
	private function table_head_sort($col, $type = null) {
	
		if ($type === 'file') {
			return '';
		}
		
		$order_by_col = $this->order_by === $col;
		$asc = $order_by_col && $this->order_type === 'asc';
		$desc = $order_by_col && $this->order_type === 'desc';
		
		return '<span class="sort"><a title="' . ($asc ? 'بر اساس این ستون به صورت صعودی مرتب شده' : 'مرتب‌سازی بر اساس این ستون به صورت صعودی') . '" class="asc' . ($asc ? ' active' : '') . '"' . ($asc ? '' : ' href="#"') . '></a><a title="' . ($desc ? 'بر اساس این ستون به صورت نزولی مرتب شده' : 'مرتب‌سازی بر اساس این ستون به صورت نزولی') . '" class="desc' . ($desc ? ' active' : '') . '"' . ($desc ? '' : ' href="#"') . '></a></span>';
	}
	
	private function table_body() {
	
		if ($this->status === 'error') {
			return '<tbody></tbody>';
		}
		
		$row_num = 1;
		$view = '<tbody>';
		
		while ($row = $this->results->fetch()) {
		
			$view .= '<tr data-id="' . $row['_id_'] . '">';
			
			$view .= '<td>' . ($row_num++) . '</td>';
			
			if ($this->form->ref_id) {
				$view .= '<td dir="ltr" class="fixed">' . $this->format_ref_id($row['_id_']) . '</td>';
			}
			
			if ($this->form->approval_needed) {
				$view .= '<td class="approval fixed">' . $this->approval_action($row['_id_'], $row['_approval_'], $row['_approvalmsg_']) . '</td>';
			}
			
			$view .= '<td dir="ltr" class="fixed">' . $this->format_timestamp($row['_timestamp_']) . '</td>';
			
			foreach ($this->controls as $control_name => $control) {
			
				$value = $row[$control_name];
				
				if (is_null($value)) {
					$attr = ' class="null"';
				}
				else {
					$datetime = $control->type === 'date' || $control->type === 'time' || $control->type === 'datetime';
					$attr = ($datetime || $control->ltr ? ' dir="ltr"' : '') . ($datetime || $control->type === 'file' ? ' class="fixed"' : '');
				}
				
				$view .= '<td' . $attr . '>' . (is_null($value) ? '' : $this->cell_content($value, $control)) . '</td>';
			}
			
			$view .= '</tr>';
		}
		
		$view .= '</tbody>';
		
		return $view;
	}
	
	private function cell_content($value, $control) {
	
		switch ($control->type) {
		
			case 'text':
				return htmlspecialchars($value);
			
			case 'textarea':
				return nl2br(htmlspecialchars($value));
			
			case 'radio':
			case 'select':
			case 'select-slave':
				return '<b>' . htmlspecialchars($control->options[$value]) . '</b>';
			
			case 'checkbox':
				return $value == '0' ? '<b>خیر</b>' : '<b>بلی</b>';
			
			case 'checkbox-array':
				if ($value === '') {
					return '<i>(هیچ)</i>';
				}
				$values = explode("\n", $value);
				$lis = '';
				foreach ($values as $v) {
					$lis .= '<li><b>' . htmlspecialchars($control->options[$v]) . '</b></li>';
				}
				return '<ul>' . $lis . '</ul>';
			
			case 'date':
				return $this->format_date($value);
			
			case 'time':
				return $this->format_time($value);
			
			case 'datetime':
				return $this->format_datetime($value);
			
			case 'file':
				return '<a href="' . $this->config->url . '/download.php?form=' . $this->form->name . '&file=' . str_replace('_', '.', $value) . '" target="_blank">دانلود</a>';
			
			default:
				return '<i>(خطا)</i>';
		}
	}
	
	private function cell_content_plain($value, $control) {

		if (is_null($value)) {
			return '';
		}
	
		switch ($control->type) {
		
			case 'text':
			case 'textarea':
				return $value;
			
			case 'radio':
			case 'select':
			case 'select-slave':
				return $control->options[$value];
			
			case 'checkbox':
				return $value == '0' ? 'خیر' : 'بلی';
			
			case 'checkbox-array':
				if ($value === '') {
					return '';
				}
				$values = explode("\n", $value);
				$labels = array();
				foreach ($values as $v) {
					$labels[] = $control->options[$v];
				}
				return implode(' + ', $labels);
			
			case 'date':
				return $this->format_date($value);
			
			case 'time':
				return $this->format_time($value);
			
			case 'datetime':
				return $this->format_datetime($value);
			
			case 'file':
				return $this->config->url . '/download.php?form=' . $this->form->name . '&file=' . str_replace('_', '.', $value);
			
			default:
				return '';
		}
	}
	
	private function pagination() {
	
		$view = '<div class="pagination">';
		
		if ($this->total_pages > 1) {
		
			$view .= '<p class="print">صفحه‌ی ' . $this->page . ' از ' . $this->total_pages . '</p>';
			
			$view .= '<p class="pages" dir="ltr">';
			
			if ($this->page == 1) {
				$view .= '<a title="صفحه‌ی قبل">«</a>';
			}
			else {
				$view .= '<a title="صفحه‌ی قبل" href="#" data-page="' . ($this->page - 1) . '">«</a>';
			}
			
			if ($this->total_pages <= $this->pagination_limit) {
				$view .= $this->pagination_range(1, $this->total_pages);
			}
			else {
				$ellipsis = '<a class="ellipsis">...</a>';
				if ($this->page < $this->pagination_limit) {
					$view .=
						$this->pagination_range(1, $this->pagination_limit) .
						$ellipsis .
						$this->pagination_range($this->total_pages);
				}
				else if ($this->page > $this->total_pages - $this->pagination_limit + 1) {
					$view .=
						$this->pagination_range(1) .
						$ellipsis .
						$this->pagination_range($this->total_pages - $this->pagination_limit + 1, $this->total_pages);
				}
				else {
					$hl = floor($this->pagination_limit / 2) - 1;
					$lb = $this->page - $hl;
					if ($this->pagination_limit % 2 == 0) {
						$lb++;
					}
					$view .=
						$this->pagination_range(1) .
						$ellipsis .
						$this->pagination_range($lb, $this->page + $hl) .
						$ellipsis .
						$this->pagination_range($this->total_pages);
				}
			}
			
			if ($this->page == $this->total_pages) {
				$view .= '<a title="صفحه‌ی بعد">»</a>';
			}
			else {
				$view .= '<a title="صفحه‌ی بعد" href="#" data-page="' . ($this->page + 1) . '">»</a>';
			}
			
			$view .= '</p>';
			
		}
		
		$view .= '<label>نمایش در هر صفحه: <select name="results_per_page">';
		
		$rpp = array(10, 20, 50, 100);
		
		if (!in_array($this->results_per_page, $rpp)) {
			$view .= '<option selected>' . $this->results_per_page . '</option>';
		}
		
		foreach ($rpp as $p) {
			$view .= '<option' . ($this->results_per_page == $p ? ' selected' : '') . '>' . $p . '</option>';
		}
		
		$view .= '</select></label></div>';
		
		return $view;
	}
	
	private function pagination_range($start, $end = null) {
	
		if (is_null($end)) {
			$end = $start;
		}
		
		$view = '';
		
		for ($i = $start; $i <= $end; $i++) {
			if ($this->page == $i) {
				$view .= '<a class="active">' . $i . '</a>';
			}
			else {
				$view .= '<a href="#" data-page="' . $i . '">' . $i . '</a>';
			}
		}
		
		return $view;
	}

	private function data_csv() {

		try {
			$db = new PDO("mysql:host={$this->config->db_host};dbname={$this->config->db_name};charset=utf8", $this->config->db_user, $this->config->db_pass, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC));
			$db->exec('SET NAMES utf8');
			$result_st = $db->prepare("SELECT * FROM `{$this->form->table}`");
			$result_st->execute();
		}
		catch (Exception $e) {
			return null;
		}

		ob_start();
		$out = fopen('php://output', 'w');
		fputs($out, chr(0xEF) . chr(0xBB) . chr(0xBF));

		$cells = array();

		if ($this->form->ref_id) {
			$cells[] = 'شماره پیگیری';
		}
		
		if ($this->form->approval_needed) {
			$cells[] = 'وضعیت تأیید';
		}
		
		$cells[] = 'زمان ثبت';
		
		foreach ($this->controls as $control) {
			$cells[] = $control->label;
		}

		fputcsv($out, $cells);
		
		while ($row = $result_st->fetch()) {

			$cells = array();
			
			if ($this->form->ref_id) {
				$cells[] = $this->format_ref_id($row['_id_']);
			}
			
			if ($this->form->approval_needed) {
				$cells[] = $this->approval_label($row['_approval_']);
			}
			
			$cells[] = $this->format_timestamp($row['_timestamp_']);
			
			foreach ($this->controls as $control_name => $control) {
				$cells[] = $this->cell_content_plain($row[$control_name], $control);
			}
			
			fputcsv($out, $cells);
		}

		fclose($out);
		return ob_get_clean();
	}

	private function send_download_headers($extension) {

		$filename = $this->form->name . '_' . date('YmdHis') . '.' . $extension;
		$now = gmdate('D, d M Y H:i:s');

		header("Expires: Tue, 03 Jul 2001 06:00:00 GMT");
		header("Cache-Control: max-age=0, no-cache, must-revalidate, proxy-revalidate");
		header("Last-Modified: {$now} GMT");
		header("Content-Type: application/force-download");
		header("Content-Type: application/octet-stream");
		header("Content-Type: application/download");
		header("Content-Disposition: attachment;filename={$filename}");
		header("Content-Transfer-Encoding: binary");
	}
	
	private function prepare_filter_value($val, $col) {
	
		if ($col === '_id_') {
			return preg_replace('/[\s-]/', '', $val);
		}
		
		if ($col === '_timestamp_') {
			if (!preg_match('/^\s*(\d{4})\s*\/\s*(\d{1,2})\s*\/\s*(\d{1,2})\s+(\d{1,2})\s*:\s*(\d{1,2})\s*:\s*(\d{1,2})\s*$/', $val, $matches)) {
				return '';
			}
			return $this->std_datetime($matches);
		}
		
		switch ($this->controls[$col]->type) {
		
			case 'date':
				if (!preg_match('/^\s*(\d{4})\s*\/\s*(\d{1,2})\s*\/\s*(\d{1,2})\s*$/', $val, $matches)) {
					return '';
				}
				return $this->std_datetime($matches);
			
			case 'time':
				if (!preg_match('/^\s*(\d{1,2})\s*:\s*(\d{1,2})\s*$/', $val, $matches)) {
					return '';
				}
				return $this->std_time($matches);
			
			case 'datetime':
				if (!preg_match('/^\s*(\d{4})\s*\/\s*(\d{1,2})\s*\/\s*(\d{1,2})\s+(\d{1,2})\s*:\s*(\d{1,2})\s*$/', $val, $matches)) {
					return '';
				}
				return $this->std_datetime($matches);
			
			case 'checkbox-array':
				return is_array($val) ? implode("\n", $val) : '';
			
			default:
				return $val;
		}
	}
	
	private function format_ref_id($value) {
		return substr($value, 0, 4) . '-' . substr($value, 4, 4) . '-' . substr($value, 8, 4) . '-' . substr($value, 12);
	}

	private function approval_action($id, $status, $msg) {
		return
			'<span></span>' .
			'<select>' .
			'<option value="P"' . ($status === 'P' ? ' selected' : '') . '>بررسی نشده</option>' .
			'<option value="A"' . ($status === 'A' ? ' selected' : '') . '>تأیید شده</option>' .
			'<option value="D"' . ($status === 'D' ? ' selected' : '') . '>رد شده</option>' .
			'</select>' .
			'<br><span></span>' .
			'<input type="text" maxlength="400" placeholder="پیام"' .
			(is_null($msg) || $msg === '' ? '' : ' value="' . htmlspecialchars($msg) . '"') .
			'>';
	}

	private function approval_label($value) {
		switch ($value) {
			case 'P':
				return 'بررسی نشده';
			case 'A':
				return 'تأیید شده';
			case 'D':
				return 'رد شده';
			default:
				return '';
		}
	}
	
	private function format_date($value) {
		$date = $this->gregorian_to_persian((int)substr($value, 0, 4), (int)substr($value, 5, 2), (int)substr($value, 8, 2));
		return $date[0] . '/' . $date[1] . '/' . $date[2];
	}
	
	private function format_time($value) {
		return substr($value, 0, 5);
	}
	
	private function format_datetime($value) {
		$date = $this->gregorian_to_persian((int)substr($value, 0, 4), (int)substr($value, 5, 2), (int)substr($value, 8, 2));
		return $date[0] . '/' . $date[1] . '/' . $date[2] . substr($value, 10, 6);
	}
	
	private function format_timestamp($value) {
		$date = $this->gregorian_to_persian((int)substr($value, 0, 4), (int)substr($value, 5, 2), (int)substr($value, 8, 2));
		return $date[0] . '/' . $date[1] . '/' . $date[2] . substr($value, 10, 9);
	}
	
	private function std_date($value) {
		$date = $this->persian_to_gregorian((int)$value[1], (int)$value[2], (int)$value[3]);
		return str_pad($date[0], 4, '0', STR_PAD_LEFT) . '-' . ($date[1] > 9 ? $date[1] : '0' . $date[1]) . '-' . ($date[2] > 9 ? $date[2] : '0' . $date[2]);
	}
	
	private function std_time($value) {
		$h = (int)$value[1];
		$m = (int)$value[2];
		return ($h > 9 ? $h : '0' . $h) . ':' . ($m > 9 ? $m : '0' . $m) . ':00';
	}
	
	private function std_datetime($value) {
		$date = $this->persian_to_gregorian((int)$value[1], (int)$value[2], (int)$value[3]);
		$h = (int)$value[4];
		$m = (int)$value[5];
		$s = (int)$value[6];
		return str_pad($date[0], 4, '0', STR_PAD_LEFT) . '-' . ($date[1] > 9 ? $date[1] : '0' . $date[1]) . '-' . ($date[2] > 9 ? $date[2] : '0' . $date[2]) . ' ' . ($h > 9 ? $h : '0' . $h) . ':' . ($m > 9 ? $m : '0' . $m) . ':' . ($s > 9 ? $s : '0' . $s);
	}
	
	// Gregorian to Jalali Conversion - Copyright (C) 2000  Roozbeh Pournader and Mohammad Toossi
	private function gregorian_to_persian($g_y, $g_m, $g_d) {
	
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
	private function persian_to_gregorian($j_y, $j_m, $j_d) {
	
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