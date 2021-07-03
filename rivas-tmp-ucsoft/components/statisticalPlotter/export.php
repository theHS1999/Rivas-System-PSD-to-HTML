<?php
/**
 *
 * export.php
 * Export class file
 *
 * @copyright	Copyright (C) 2012 Rivas Systems Inc. All rights reserved.
 * @versin	1.00 2012-02-05
 * @author	Behnam Salili
 *
 */

require_once dirname(dirname(__file__)) . '/illustrator/pdate.php';
require_once dirname(dirname(__file__)) . '/fileExport/Classes/PHPExcel.php';

/*error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);*/

date_default_timezone_set('Asia/Tehran');

class Export {

	public static function makeChargeExcelExport($input) {
		$dateTimeObj = new DateTime();
		$dateTimeZone = new DateTimeZone('Asia/Tehran');
		$dateTimeObj -> setTimezone($dateTimeZone);
		$shamsiDate = gregorian_to_jalali($dateTimeObj -> format('Y'), $dateTimeObj -> format('m'), $dateTimeObj -> format('d'));
		// Creat new  cellAddress object
		$cellAdd = new cellAddress();
		// Create new PHPExcel object
		$objPHPExcel = new PHPExcel();
		// Set document properties
		$objPHPExcel -> getProperties() -> setCreator("Rivas Systems INC.") -> setLastModifiedBy("Rivas Systems INC.") -> setTitle("گزارش هزینه ها") -> setSubject("گزارش هزینه ها") -> setDescription("گزارش هزینه ها") -> setKeywords("") -> setCategory("");
		$objPHPExcel -> getDefaultStyle() -> getFont() -> setName('Arial') -> setSize(12);
		// Add some data
		$objPHPExcel -> setActiveSheetIndex(0);
		$objPHPExcel -> getActiveSheet() -> getStyle($cellAdd -> getCurrentCell()) -> applyFromArray(array('font' => array('bold' => true, 'size' => '18'), 'alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER, ), 'borders' => array('top' => array('style' => PHPExcel_Style_Border::BORDER_THIN), 'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN))));
		$objPHPExcel -> getActiveSheet() -> setCellValue($cellAdd -> getCurrentCell(), 'گزارش هزینه ها - تهیه شده توسط نرم افزار جامع مدیریت مراکز بهداشتی-درمانی');
		$objPHPExcel -> getActiveSheet() -> getStyle($cellAdd -> getCurrentColumn()) -> getFont() -> setSize(12);
		$objPHPExcel -> getActiveSheet() -> getColumnDimension($cellAdd -> getCurrentColumn()) -> setAutoSize(true);
		$cellAdd -> goNextRow();
		$cellAdd -> goNextRow();

		//A3
		$objPHPExcel -> getActiveSheet() -> getStyle($cellAdd -> getCurrentColumn()) -> getFont() -> setBold(true);
		$objPHPExcel -> getActiveSheet() -> setCellValue($cellAdd -> getCurrentCell(), 'نوع منبع');
		$objPHPExcel -> getActiveSheet() -> getStyle($cellAdd -> getCurrentColumn()) -> getFont() -> setBold(false);
		$objPHPExcel -> getActiveSheet() -> getColumnDimension($cellAdd -> getCurrentColumn()) -> setAutoSize(true);
		$cellAdd -> goNextColumn();

		//B3
		$objPHPExcel -> getActiveSheet() -> getStyle($cellAdd -> getCurrentColumn()) -> getFont() -> setBold(true);
		$objPHPExcel -> getActiveSheet() -> setCellValue($cellAdd -> getCurrentCell(), 'نام منبع');
		$objPHPExcel -> getActiveSheet() -> getStyle($cellAdd -> getCurrentColumn()) -> getFont() -> setBold(false);
		$objPHPExcel -> getActiveSheet() -> getColumnDimension($cellAdd -> getCurrentColumn()) -> setAutoSize(true);

		// Making date row
		$item = $input['0'];
		if (isset($item['townId'])) {
			foreach ($item['townCharge'] as $value) {
				$cellAdd -> goNextColumn();
				$objPHPExcel -> getActiveSheet() -> getStyle($cellAdd -> getCurrentColumn()) -> getFont() -> setBold(true);
				$objPHPExcel -> getActiveSheet() -> setCellValue($cellAdd -> getCurrentCell(), $value['year'] . '/' . $value['month']);
				$objPHPExcel -> getActiveSheet() -> getColumnDimension($cellAdd -> getCurrentColumn()) -> setAutoSize(true);
				$objPHPExcel -> getActiveSheet() -> getStyle($cellAdd -> getCurrentColumn()) -> getFont() -> setBold(false);
			}
		} else if (isset($item['centerId'])) {
			foreach ($item['centerCharge'] as $value) {
				$cellAdd -> goNextColumn();
				$objPHPExcel -> getActiveSheet() -> setCellValue($cellAdd -> getCurrentCell(), $value['year'] . '/' . $value['month']);
				$objPHPExcel -> getActiveSheet() -> getColumnDimension($cellAdd -> getCurrentColumn()) -> setAutoSize(true);
			}
		} else if (isset($item['hygieneUnitId'])) {
			foreach ($item['hygieneUnitCharge'] as $value) {
				$cellAdd -> goNextColumn();
				$objPHPExcel -> getActiveSheet() -> setCellValue($cellAdd -> getCurrentCell(), $value['year'] . '/' . $value['month']);
				$objPHPExcel -> getActiveSheet() -> getColumnDimension($cellAdd -> getCurrentColumn()) -> setAutoSize(true);
			}
		} else if (isset($item['unitId'])) {
			foreach ($item['unitCharge'] as $value) {
				$cellAdd -> goNextColumn();
				$objPHPExcel -> getActiveSheet() -> setCellValue($cellAdd -> getCurrentCell(), $value['year'] . '/' . $value['month']);
				$objPHPExcel -> getActiveSheet() -> getColumnDimension($cellAdd -> getCurrentColumn()) -> setAutoSize(true);
			}
		}

		// Add Costs to file
		foreach ($input as $items) {
			if (isset($items['townId'])) {
				// return to the start of the next row
				$cellAdd -> goNextRow();
				$cellAdd -> setCurrentColumn(1);
				$objPHPExcel -> getActiveSheet() -> setCellValue($cellAdd -> getCurrentCell(), 'شهرستان');
				$objPHPExcel -> getActiveSheet() -> getColumnDimension($cellAdd -> getCurrentColumn()) -> setAutoSize(true);
				$cellAdd -> goNextColumn();
				$res = Database::execute_query("SELECT `name` FROM `towns` WHERE `id` = '" . $items['townId'] . "';");
				$row = Database::get_assoc_array($res);
				$objPHPExcel -> getActiveSheet() -> setCellValue($cellAdd -> getCurrentCell(), $row['name']);
				$objPHPExcel -> getActiveSheet() -> getColumnDimension($cellAdd -> getCurrentColumn()) -> setAutoSize(true);
				foreach ($items['townCharge'] as $value) {
					$cellAdd -> goNextColumn();
					$objPHPExcel -> getActiveSheet() -> setCellValue($cellAdd -> getCurrentCell(), $value['totalCost']);
					$objPHPExcel -> getActiveSheet() -> getColumnDimension($cellAdd -> getCurrentColumn()) -> setAutoSize(true);
				}
			}

			if (isset($items['centerId'])) {
				// return to the start of the next row
				$cellAdd -> goNextRow();
				$cellAdd -> setCurrentColumn(1);
				$objPHPExcel -> getActiveSheet() -> setCellValue($cellAdd -> getCurrentCell(), 'مرکز');
				$objPHPExcel -> getActiveSheet() -> getColumnDimension($cellAdd -> getCurrentColumn()) -> setAutoSize(true);
				$cellAdd -> goNextColumn();
				$res = Database::execute_query("SELECT `name` FROM `centers` WHERE `id` = '" . $items['centerId'] . "';");
				$row = Database::get_assoc_array($res);
				$objPHPExcel -> getActiveSheet() -> setCellValue($cellAdd -> getCurrentCell(), $row['name']);
				$objPHPExcel -> getActiveSheet() -> getColumnDimension($cellAdd -> getCurrentColumn()) -> setAutoSize(true);
				foreach ($items['centerCharge'] as $value) {
					$cellAdd -> goNextColumn();
					$objPHPExcel -> getActiveSheet() -> setCellValue($cellAdd -> getCurrentCell(), $value['totalCost']);
					$objPHPExcel -> getActiveSheet() -> getColumnDimension($cellAdd -> getCurrentColumn()) -> setAutoSize(true);
				}
			}
			if (isset($items['hygieneUnitId'])) {
				// return to the start of the next row
				$cellAdd -> goNextRow();
				$cellAdd -> setCurrentColumn(1);
				$objPHPExcel -> getActiveSheet() -> setCellValue($cellAdd -> getCurrentCell(), 'خانه بهداشت');
				$objPHPExcel -> getActiveSheet() -> getColumnDimension($cellAdd -> getCurrentColumn()) -> setAutoSize(true);
				$cellAdd -> goNextColumn();
				$res = Database::execute_query("SELECT `name` FROM `hygiene-units` WHERE `id` = '" . $items['hygieneUnitId'] . "';");
				$row = Database::get_assoc_array($res);
				$objPHPExcel -> getActiveSheet() -> setCellValue($cellAdd -> getCurrentCell(), $row['name']);
				$objPHPExcel -> getActiveSheet() -> getColumnDimension($cellAdd -> getCurrentColumn()) -> setAutoSize(true);
				foreach ($items['hygieneUnitCharge'] as $value) {
					$cellAdd -> goNextColumn();
					$objPHPExcel -> getActiveSheet() -> setCellValue($cellAdd -> getCurrentCell(), $value['totalCost']);
					$objPHPExcel -> getActiveSheet() -> getColumnDimension($cellAdd -> getCurrentColumn()) -> setAutoSize(true);
				}
			}
			if (isset($items['unitId'])) {
				// return to the start of the next row
				$cellAdd -> goNextRow();
				$cellAdd -> setCurrentColumn(1);
				$objPHPExcel -> getActiveSheet() -> setCellValue($cellAdd -> getCurrentCell(), 'واحد');
				$objPHPExcel -> getActiveSheet() -> getColumnDimension($cellAdd -> getCurrentColumn()) -> setAutoSize(true);
				$cellAdd -> goNextColumn();
				$res = Database::execute_query("SELECT `name` FROM `units` WHERE `id` = '" . $items['unitId'] . "';");
				$row = Database::get_assoc_array($res);
				$objPHPExcel -> getActiveSheet() -> setCellValue($cellAdd -> getCurrentCell(), $row['name']);
				$objPHPExcel -> getActiveSheet() -> getColumnDimension($cellAdd -> getCurrentColumn()) -> setAutoSize(true);
				foreach ($items['unitCharge'] as $value) {
					$cellAdd -> goNextColumn();
					$objPHPExcel -> getActiveSheet() -> setCellValue($cellAdd -> getCurrentCell(), $value['totalCost']);
					$objPHPExcel -> getActiveSheet() -> getColumnDimension($cellAdd -> getCurrentColumn()) -> setAutoSize(true);
				}
			}
		}
		// merge title cells
		$objPHPExcel -> getActiveSheet() -> mergeCells('A1:' . $cellAdd -> getCurrentColumn() . '1');
		// Rename worksheet
		$objPHPExcel -> getActiveSheet() -> setTitle('گزارش هزینه ها');
		// Set active sheet index to the first sheet, so Excel opens this as the first sheet
		$objPHPExcel -> setActiveSheetIndex(0);
		// Redirect output to a client’s web browser (Excel2007)
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		$dateStr = $shamsiDate[0] . "-" . $shamsiDate[1] . "-" . $shamsiDate[2] . "_" . $dateTimeObj -> format('H-i-s');
		$fileAddress = dirname(dirname(dirname(__FILE__))) . '\dl_files\ExcelReport_' . $dateStr . '.xlsx';
		$objWriter -> save($fileAddress);
		self::download($fileAddress);

		return;
	}

	public static function makeChargeCSVExport($input) {
		$dateTimeObj = new DateTime();
		$dateTimeZone = new DateTimeZone('Asia/Tehran');
		$dateTimeObj -> setTimezone($dateTimeZone);
		$shamsiDate = gregorian_to_jalali($dateTimeObj -> format('Y'), $dateTimeObj -> format('m'), $dateTimeObj -> format('d'));
		// Creat new  cellAddress object
		$cellAdd = new cellAddress();
		// Create new PHPExcel object
		$objPHPExcel = new PHPExcel();
		// Set document properties
		$objPHPExcel -> getProperties() -> setCreator("Rivas Systems INC.") -> setLastModifiedBy("Rivas Systems INC.") -> setTitle("گزارش") -> setSubject("گزارش") -> setDescription("گزارش هزینه ها") -> setKeywords("") -> setCategory("");
		$objPHPExcel -> getDefaultStyle() -> getFont() -> setName('Arial') -> setSize(12);
		// Add some data
		$objPHPExcel -> setActiveSheetIndex(0);
		
		$objPHPExcel -> getActiveSheet() -> getStyle($cellAdd -> getCurrentCell()) -> applyFromArray(array('font' => array('bold' => true, 'size' => '18'), 'alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER, ), 'borders' => array('top' => array('style' => PHPExcel_Style_Border::BORDER_THIN), 'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN))));
		$objPHPExcel -> getActiveSheet() -> setCellValue($cellAdd -> getCurrentCell(), 'گزارش هزینه ها - تهیه شده توسط نرم افزار جامع مدیریت مراکز بهداشتی-درمانی');
		$objPHPExcel -> getActiveSheet() -> getStyle($cellAdd -> getCurrentColumn()) -> getFont() -> setSize(12);
		$objPHPExcel -> getActiveSheet() -> getColumnDimension($cellAdd -> getCurrentColumn()) -> setAutoSize(true);
		$cellAdd -> goNextRow();
		//$cellAdd -> goNextRow();

		//A3
		$objPHPExcel -> getActiveSheet() -> getStyle($cellAdd -> getCurrentColumn()) -> getFont() -> setBold(true);
		$objPHPExcel -> getActiveSheet() -> setCellValue($cellAdd -> getCurrentCell(), 'نوع منبع');
		$objPHPExcel -> getActiveSheet() -> getStyle($cellAdd -> getCurrentColumn()) -> getFont() -> setBold(false);
		$objPHPExcel -> getActiveSheet() -> getColumnDimension($cellAdd -> getCurrentColumn()) -> setAutoSize(true);
		$cellAdd -> goNextColumn();

		//B3
		$objPHPExcel -> getActiveSheet() -> getStyle($cellAdd -> getCurrentColumn()) -> getFont() -> setBold(true);
		$objPHPExcel -> getActiveSheet() -> setCellValue($cellAdd -> getCurrentCell(), 'نام منبع');
		$objPHPExcel -> getActiveSheet() -> getStyle($cellAdd -> getCurrentColumn()) -> getFont() -> setBold(false);
		$objPHPExcel -> getActiveSheet() -> getColumnDimension($cellAdd -> getCurrentColumn()) -> setAutoSize(true);

		// Making date row
		$item = $input['0'];
		if (isset($item['townId'])) {
			foreach ($item['townCharge'] as $value) {
				$cellAdd -> goNextColumn();
				$objPHPExcel -> getActiveSheet() -> getStyle($cellAdd -> getCurrentColumn()) -> getFont() -> setBold(true);
				$objPHPExcel -> getActiveSheet() -> setCellValue($cellAdd -> getCurrentCell(), $value['year'] . '/' . $value['month']);
				$objPHPExcel -> getActiveSheet() -> getColumnDimension($cellAdd -> getCurrentColumn()) -> setAutoSize(true);
				$objPHPExcel -> getActiveSheet() -> getStyle($cellAdd -> getCurrentColumn()) -> getFont() -> setBold(false);
			}
		} else if (isset($item['centerId'])) {
			foreach ($item['centerCharge'] as $value) {
				$cellAdd -> goNextColumn();
				$objPHPExcel -> getActiveSheet() -> setCellValue($cellAdd -> getCurrentCell(), $value['year'] . '/' . $value['month']);
				$objPHPExcel -> getActiveSheet() -> getColumnDimension($cellAdd -> getCurrentColumn()) -> setAutoSize(true);
			}
		} else if (isset($item['hygieneUnitId'])) {
			foreach ($item['hygieneUnitCharge'] as $value) {
				$cellAdd -> goNextColumn();
				$objPHPExcel -> getActiveSheet() -> setCellValue($cellAdd -> getCurrentCell(), $value['year'] . '/' . $value['month']);
				$objPHPExcel -> getActiveSheet() -> getColumnDimension($cellAdd -> getCurrentColumn()) -> setAutoSize(true);
			}
		} else if (isset($item['unitId'])) {
			foreach ($item['unitCharge'] as $value) {
				$cellAdd -> goNextColumn();
				$objPHPExcel -> getActiveSheet() -> setCellValue($cellAdd -> getCurrentCell(), $value['year'] . '/' . $value['month']);
				$objPHPExcel -> getActiveSheet() -> getColumnDimension($cellAdd -> getCurrentColumn()) -> setAutoSize(true);
			}
		}

		// Add Costs to file
		foreach ($input as $items) {
			if (isset($items['townId'])) {
				// return to the start of the next row
				$cellAdd -> goNextRow();
				$cellAdd -> setCurrentColumn(1);
				$objPHPExcel -> getActiveSheet() -> setCellValue($cellAdd -> getCurrentCell(), 'شهرستان');
				$objPHPExcel -> getActiveSheet() -> getColumnDimension($cellAdd -> getCurrentColumn()) -> setAutoSize(true);
				$cellAdd -> goNextColumn();
				$res = Database::execute_query("SELECT `name` FROM `towns` WHERE `id` = '" . $items['townId'] . "';");
				$row = Database::get_assoc_array($res);
				$objPHPExcel -> getActiveSheet() -> setCellValue($cellAdd -> getCurrentCell(), $row['name']);
				$objPHPExcel -> getActiveSheet() -> getColumnDimension($cellAdd -> getCurrentColumn()) -> setAutoSize(true);
				foreach ($items['townCharge'] as $value) {
					$cellAdd -> goNextColumn();
					$objPHPExcel -> getActiveSheet() -> setCellValue($cellAdd -> getCurrentCell(), $value['totalCost']);
					$objPHPExcel -> getActiveSheet() -> getColumnDimension($cellAdd -> getCurrentColumn()) -> setAutoSize(true);
				}
			}

			if (isset($items['centerId'])) {
				// return to the start of the next row
				$cellAdd -> goNextRow();
				$cellAdd -> setCurrentColumn(1);
				$objPHPExcel -> getActiveSheet() -> setCellValue($cellAdd -> getCurrentCell(), 'مرکز');
				$objPHPExcel -> getActiveSheet() -> getColumnDimension($cellAdd -> getCurrentColumn()) -> setAutoSize(true);
				$cellAdd -> goNextColumn();
				$res = Database::execute_query("SELECT `name` FROM `centers` WHERE `id` = '" . $items['centerId'] . "';");
				$row = Database::get_assoc_array($res);
				$objPHPExcel -> getActiveSheet() -> setCellValue($cellAdd -> getCurrentCell(), $row['name']);
				$objPHPExcel -> getActiveSheet() -> getColumnDimension($cellAdd -> getCurrentColumn()) -> setAutoSize(true);
				foreach ($items['centerCharge'] as $value) {
					$cellAdd -> goNextColumn();
					$objPHPExcel -> getActiveSheet() -> setCellValue($cellAdd -> getCurrentCell(), $value['totalCost']);
					$objPHPExcel -> getActiveSheet() -> getColumnDimension($cellAdd -> getCurrentColumn()) -> setAutoSize(true);
				}
			}
			if (isset($items['hygieneUnitId'])) {
				// return to the start of the next row
				$cellAdd -> goNextRow();
				$cellAdd -> setCurrentColumn(1);
				$objPHPExcel -> getActiveSheet() -> setCellValue($cellAdd -> getCurrentCell(), 'خانه بهداشت');
				$objPHPExcel -> getActiveSheet() -> getColumnDimension($cellAdd -> getCurrentColumn()) -> setAutoSize(true);
				$cellAdd -> goNextColumn();
				$res = Database::execute_query("SELECT `name` FROM `hygiene-units` WHERE `id` = '" . $items['hygieneUnitId'] . "';");
				$row = Database::get_assoc_array($res);
				$objPHPExcel -> getActiveSheet() -> setCellValue($cellAdd -> getCurrentCell(), $row['name']);
				$objPHPExcel -> getActiveSheet() -> getColumnDimension($cellAdd -> getCurrentColumn()) -> setAutoSize(true);
				foreach ($items['hygieneUnitCharge'] as $value) {
					$cellAdd -> goNextColumn();
					$objPHPExcel -> getActiveSheet() -> setCellValue($cellAdd -> getCurrentCell(), $value['totalCost']);
					$objPHPExcel -> getActiveSheet() -> getColumnDimension($cellAdd -> getCurrentColumn()) -> setAutoSize(true);
				}
			}
			if (isset($items['unitId'])) {
				// return to the start of the next row
				$cellAdd -> goNextRow();
				$cellAdd -> setCurrentColumn(1);
				$objPHPExcel -> getActiveSheet() -> setCellValue($cellAdd -> getCurrentCell(), 'واحد');
				$objPHPExcel -> getActiveSheet() -> getColumnDimension($cellAdd -> getCurrentColumn()) -> setAutoSize(true);
				$cellAdd -> goNextColumn();
				$res = Database::execute_query("SELECT `name` FROM `units` WHERE `id` = '" . $items['unitId'] . "';");
				$row = Database::get_assoc_array($res);
				$objPHPExcel -> getActiveSheet() -> setCellValue($cellAdd -> getCurrentCell(), $row['name']);
				$objPHPExcel -> getActiveSheet() -> getColumnDimension($cellAdd -> getCurrentColumn()) -> setAutoSize(true);
				foreach ($items['unitCharge'] as $value) {
					$cellAdd -> goNextColumn();
					$objPHPExcel -> getActiveSheet() -> setCellValue($cellAdd -> getCurrentCell(), $value['totalCost']);
					$objPHPExcel -> getActiveSheet() -> getColumnDimension($cellAdd -> getCurrentColumn()) -> setAutoSize(true);
				}
			}
		}
		
		// merge title cells
		$objPHPExcel -> getActiveSheet() -> mergeCells('A1:' . $cellAdd -> getCurrentColumn() . '1');
		
		// Rename worksheet
		$objPHPExcel -> getActiveSheet() -> setTitle('گزارش هزینه ها');
		
		// Set active sheet index to the first sheet, so Excel opens this as the first sheet
		$objPHPExcel -> setActiveSheetIndex(0);
		
		// Redirect output to a client’s web browser (CSV)
		$objWriter = new PHPExcel_Writer_CSV($objPHPExcel);
		$objWriter -> setUseBOM(true);
		$objWriter -> setDelimiter(',');
		$objWriter -> setEnclosure('');
		$objWriter -> setLineEnding("\r\n");
		$objWriter -> setSheetIndex(0);
		$dateStr = $shamsiDate[0] . "-" . $shamsiDate[1] . "-" . $shamsiDate[2] . "_" . $dateTimeObj -> format('H-i-s');
		$fileAddress = dirname(dirname(dirname(__FILE__))) . '\dl_files\CSVReport_' . $dateStr . '.csv';
		$objWriter -> save($fileAddress);
		self::download($fileAddress);

		return;
	}

	public static function makeChargeXMLExport($input) {
		$dateTimeObj = new DateTime();
		$dateTimeZone = new DateTimeZone('Asia/Tehran');
		$dateTimeObj -> setTimezone($dateTimeZone);
		$shamsiDate = gregorian_to_jalali($dateTimeObj -> format('Y'), $dateTimeObj -> format('m'), $dateTimeObj -> format('d'));
		$date = $shamsiDate[0] . "-" . $shamsiDate[1] . "-" . $shamsiDate[2] . " " . $dateTimeObj -> format('H:i:s');
		$doc = new DOMDocument('1.0', "UTF-8");
		$doc -> formatOutput = true;
		$reportTag = $doc -> createElement('report');
		$reportTag -> setAttributeNode(new DOMAttr('date', $date));
		$doc -> appendChild($reportTag);

		// Add Costs to file
		foreach ($input as $items) {
			if (isset($items['townId'])) {
				$res = Database::execute_query("SELECT `name` FROM `towns` WHERE `id` = '" . $items['townId'] . "';");
				$row = Database::get_assoc_array($res);
				$sourceTag = $doc -> createElement('source');
				$sourceTag -> setAttributeNode(new DOMAttr('name', $row['name']));
				$sourceTag -> setAttributeNode(new DOMAttr('type', 'شهرستان'));
				$reportTag -> appendChild($sourceTag);
				foreach ($items['townCharge'] as $value) {
					$costTag = $doc -> createElement('cost');
					$costTag -> setAttributeNode(new DOMAttr('date', $value['year'] . '/' . $value['month']));
					$costTag -> setAttributeNode(new DOMAttr('value', $value['totalCost']));
					$sourceTag -> appendChild($costTag);
				}
			}

			if (isset($items['centerId'])) {
				$res = Database::execute_query("SELECT `name` FROM `centers` WHERE `id` = '" . $items['centerId'] . "';");
				$row = Database::get_assoc_array($res);
				$sourceTag = $doc -> createElement('source');
				$sourceTag -> setAttributeNode(new DOMAttr('name', $row['name']));
				$sourceTag -> setAttributeNode(new DOMAttr('type', 'مرکز'));
				$reportTag -> appendChild($sourceTag);
				foreach ($items['centerCharge'] as $value) {
					$costTag = $doc -> createElement('cost');
					$costTag -> setAttributeNode(new DOMAttr('date', $value['year'] . '/' . $value['month']));
					$costTag -> setAttributeNode(new DOMAttr('value', $value['totalCost']));
					$sourceTag -> appendChild($costTag);
				}
			}
			if (isset($items['hygieneUnitId'])) {
				$res = Database::execute_query("SELECT `name` FROM `hygiene-units` WHERE `id` = '" . $items['hygieneUnitId'] . "';");
				$row = Database::get_assoc_array($res);
				$sourceTag = $doc -> createElement('source');
				$sourceTag -> setAttributeNode(new DOMAttr('name', $row['name']));
				$sourceTag -> setAttributeNode(new DOMAttr('type', 'خانه بهداشت'));
				$reportTag -> appendChild($sourceTag);
				foreach ($items['hygieneUnitCharge'] as $value) {
					$costTag = $doc -> createElement('cost');
					$costTag -> setAttributeNode(new DOMAttr('date', $value['year'] . '/' . $value['month']));
					$costTag -> setAttributeNode(new DOMAttr('value', $value['totalCost']));
					$sourceTag -> appendChild($costTag);
				}
			}
			if (isset($items['unitId'])) {
				$res = Database::execute_query("SELECT `name` FROM `units` WHERE `id` = '" . $items['unitId'] . "';");
				$row = Database::get_assoc_array($res);
				$sourceTag = $doc -> createElement('source');
				$sourceTag -> setAttributeNode(new DOMAttr('name', $row['name']));
				$sourceTag -> setAttributeNode(new DOMAttr('type', 'واحد'));
				$reportTag -> appendChild($sourceTag);
				foreach ($items['unitCharge'] as $value) {
					$costTag = $doc -> createElement('cost');
					$costTag -> setAttributeNode(new DOMAttr('date', $value['year'] . '/' . $value['month']));
					$costTag -> setAttributeNode(new DOMAttr('value', $value['totalCost']));
					$sourceTag -> appendChild($costTag);
				}
			}
		}

		$fileAddress = dirname(dirname(dirname(__FILE__))) . '\dl_files\XMLReport_' . $shamsiDate[0] . "-" . $shamsiDate[1] . "-" . $shamsiDate[2] . "_" . $dateTimeObj -> format('H-i-s') . '.xml';
		$doc -> save($fileAddress);
		self::download($fileAddress);
	}

	public static function makeIncomeCSVExport($data, $fromDate, $toDate) {
		$dateTimeObj = new DateTime();
		$dateTimeZone = new DateTimeZone('Asia/Tehran');
		$dateTimeObj -> setTimezone($dateTimeZone);
		$shamsiDate = gregorian_to_jalali($dateTimeObj -> format('Y'), $dateTimeObj -> format('m'), $dateTimeObj -> format('d'));
		// Creat new  cellAddress object
		$cellAdd = new cellAddress();
		// Create new PHPExcel object
		$objPHPExcel = new PHPExcel();
		// Set document properties
		$objPHPExcel -> getProperties() -> setCreator("Rivas Systems INC.") -> setLastModifiedBy("Rivas Systems INC.") -> setTitle("گزارش") -> setSubject("گزارش") -> setDescription("گزارش هزینه ها") -> setKeywords("") -> setCategory("");
		$objPHPExcel -> getDefaultStyle() -> getFont() -> setName('Arial') -> setSize(12);
		// Add some data
		$objPHPExcel -> setActiveSheetIndex(0);
		
		$objPHPExcel -> getActiveSheet() -> getStyle($cellAdd -> getCurrentCell()) -> applyFromArray(array('font' => array('bold' => true, 'size' => '18'), 'alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER, ), 'borders' => array('top' => array('style' => PHPExcel_Style_Border::BORDER_THIN), 'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN))));
		$objPHPExcel -> getActiveSheet() -> setCellValue($cellAdd -> getCurrentCell(), 'گزارش درآمدها از ' . $fromDate . ' تا ' . $toDate . ' - تهیه شده توسط نرم افزار جامع مدیریت مراکز بهداشتی-درمانی');
		$objPHPExcel -> getActiveSheet() -> getStyle($cellAdd -> getCurrentColumn()) -> getFont() -> setSize(12);
		$objPHPExcel -> getActiveSheet() -> getColumnDimension($cellAdd -> getCurrentColumn()) -> setAutoSize(true);
		$cellAdd -> goNextRow();
		
		// Add Costs to file
		foreach ($data as $items) {
			// return to the start of the next row
			$cellAdd -> goNextRow();
			$cellAdd -> setCurrentColumn(1);
			$objPHPExcel -> getActiveSheet() -> setCellValue($cellAdd -> getCurrentCell(), $items['name']);
			$objPHPExcel -> getActiveSheet() -> getColumnDimension($cellAdd -> getCurrentColumn()) -> setAutoSize(true);
			$cellAdd -> goNextColumn();
			$objPHPExcel -> getActiveSheet() -> setCellValue($cellAdd -> getCurrentCell(), $items['totalIncome']);
			$objPHPExcel -> getActiveSheet() -> getColumnDimension($cellAdd -> getCurrentColumn()) -> setAutoSize(true);
		}
		
		// merge title cells
		$objPHPExcel -> getActiveSheet() -> mergeCells('A1:B1');
		
		// Rename worksheet
		$objPHPExcel -> getActiveSheet() -> setTitle('گزارش درآمدها');
		
		// Set active sheet index to the first sheet, so Excel opens this as the first sheet
		$objPHPExcel -> setActiveSheetIndex(0);
		
		// Redirect output to a client’s web browser (CSV)
		$objWriter = new PHPExcel_Writer_CSV($objPHPExcel);
		$objWriter -> setUseBOM(true);
		$objWriter -> setDelimiter(',');
		$objWriter -> setEnclosure('');
		$objWriter -> setLineEnding("\r\n");
		$objWriter -> setSheetIndex(0);
		$dateStr = $shamsiDate[0] . "-" . $shamsiDate[1] . "-" . $shamsiDate[2] . "_" . $dateTimeObj -> format('H-i-s');
		$fileAddress = dirname(dirname(dirname(__FILE__))) . '\dl_files\CSVReport_' . $dateStr . '.csv';
		$objWriter -> save($fileAddress);
		self::download($fileAddress);

		return;
	}

	public static function makeIncomeExcelExport($data, $fromDate, $toDate) {
		$dateTimeObj = new DateTime();
		$dateTimeZone = new DateTimeZone('Asia/Tehran');
		$dateTimeObj -> setTimezone($dateTimeZone);
		$shamsiDate = gregorian_to_jalali($dateTimeObj -> format('Y'), $dateTimeObj -> format('m'), $dateTimeObj -> format('d'));
		// Creat new  cellAddress object
		$cellAdd = new cellAddress();
		// Create new PHPExcel object
		$objPHPExcel = new PHPExcel();
		// Set document properties
		$objPHPExcel -> getProperties() -> setCreator("Rivas Systems INC.") -> setLastModifiedBy("Rivas Systems INC.") -> setTitle("گزارش درآمدها") -> setSubject("گزارش درآمدها") -> setDescription("گزارش درآمدها") -> setKeywords("") -> setCategory("");
		$objPHPExcel -> getDefaultStyle() -> getFont() -> setName('Arial') -> setSize(12);
		// Add some data
		$objPHPExcel -> setActiveSheetIndex(0);
		$objPHPExcel -> getActiveSheet() -> getStyle($cellAdd -> getCurrentCell()) -> applyFromArray(array('font' => array('bold' => true, 'size' => '18'), 'alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER, ), 'borders' => array('top' => array('style' => PHPExcel_Style_Border::BORDER_THIN), 'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN))));
		$objPHPExcel -> getActiveSheet() -> setCellValue($cellAdd -> getCurrentCell(), 'گزارش درآمدها از ' . $fromDate . ' تا ' . $toDate . ' - تهیه شده توسط نرم افزار جامع مدیریت مراکز بهداشتی-درمانی');
		$objPHPExcel -> getActiveSheet() -> getStyle($cellAdd -> getCurrentColumn()) -> getFont() -> setSize(12);
		$objPHPExcel -> getActiveSheet() -> getColumnDimension($cellAdd -> getCurrentColumn()) -> setAutoSize(true);
		$cellAdd -> goNextRow();
		$cellAdd -> goNextRow();

		// Add Costs to file
		foreach ($data as $items) {
			// return to the start of the next row
			$cellAdd -> setCurrentColumn(1);
			$objPHPExcel -> getActiveSheet() -> setCellValue($cellAdd -> getCurrentCell(), $items['name']);
			$objPHPExcel -> getActiveSheet() -> getColumnDimension($cellAdd -> getCurrentColumn()) -> setAutoSize(true);
			$cellAdd -> goNextColumn();
			$objPHPExcel -> getActiveSheet() -> setCellValue($cellAdd -> getCurrentCell(), $items['totalIncome']);
			$objPHPExcel -> getActiveSheet() -> getColumnDimension($cellAdd -> getCurrentColumn()) -> setAutoSize(true);
			$cellAdd -> goNextRow();
		}
		
		// merge title cells
		$objPHPExcel -> getActiveSheet() -> mergeCells('A1:L1');
		
		// Rename worksheet
		$objPHPExcel -> getActiveSheet() -> setTitle('گزارش درآمدها');
		
		// Set active sheet index to the first sheet, so Excel opens this as the first sheet
		$objPHPExcel -> setActiveSheetIndex(0);
		
		// Redirect output to a client’s web browser (Excel2007)
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		$dateStr = $shamsiDate[0] . "-" . $shamsiDate[1] . "-" . $shamsiDate[2] . "_" . $dateTimeObj -> format('H-i-s');
		$fileAddress = dirname(dirname(dirname(__FILE__))) . '\dl_files\ExcelReport_' . $dateStr . '.xlsx';
		$objWriter -> save($fileAddress);
		self::download($fileAddress);

		return;
	}

	public static function makeIncomeXMLExport($data, $fromDate, $toDate) {
		$dateTimeObj = new DateTime();
		$dateTimeZone = new DateTimeZone('Asia/Tehran');
		$dateTimeObj -> setTimezone($dateTimeZone);
		$shamsiDate = gregorian_to_jalali($dateTimeObj -> format('Y'), $dateTimeObj -> format('m'), $dateTimeObj -> format('d'));
		$date = $shamsiDate[0] . "-" . $shamsiDate[1] . "-" . $shamsiDate[2] . " " . $dateTimeObj -> format('H:i:s');
		$doc = new DOMDocument('1.0', "UTF-8");
		$doc -> formatOutput = true;
		$reportTag = $doc -> createElement('report');
		$reportTag -> setAttributeNode(new DOMAttr('date', 'از ' . $fromDate . ' تا ' . $toDate));
		$doc -> appendChild($reportTag);

		// Add Costs to file
		foreach ($data as $item) {
			$sourceTag = $doc -> createElement('source');
			$sourceTag -> setAttributeNode(new DOMAttr('name', $item['name']));
			$sourceTag -> setAttributeNode(new DOMAttr('income', $item['totalIncome']));
			$reportTag -> appendChild($sourceTag);
		}

		$fileAddress = dirname(dirname(dirname(__FILE__))) . '\dl_files\XMLReport_' . $shamsiDate[0] . "." . $shamsiDate[1] . "." . $shamsiDate[2] . "_" . $dateTimeObj -> format('H-i-s') . '.xml';
		$doc -> save($fileAddress);
		self::download($fileAddress);
	}

	public static function download($fileAddress) {
		if (file_exists($fileAddress)) {
			header('Content-Description: File Transfer');
			header('Content-Type: application/octet-stream');
			header('Content-Disposition: attachment; filename=' . basename($fileAddress));
			header('Content-Transfer-Encoding: binary');
			header('Expires: 0');
			header('Cache-Control: must-revalidate');
			header('Pragma: public');
			header('Content-Length: ' . filesize($fileAddress));
			ob_clean();
			flush();
			readfile($fileAddress);
			exit;
		}
	}

}//END CLASS EXCELREPORT



class cellAddress {
	private $curRow;
	private $curCol;

	public function __construct() {
		$this -> curRow = 1;
		$this -> curCol = 1;
	}

	public function setCurrentRow($row) {
		$this -> curRow = $row;
	}

	public function setCurrentColumn($column) {
		$this -> curCol = $column;
	}

	public function getCurrentRow() {
		return $this -> curRow;
	}

	public function getCurrentColumn() {
		return $this -> getNameFromNumber($this -> curCol);
	}

	public function getCurrentCell() {
		return ($this -> getCurrentColumn() . $this -> getCurrentRow());
	}

	public function goNextRow() {
		$this -> curRow++;
	}

	public function goNextColumn() {
		$this -> curCol++;
	}

	public function goPreviousRow() {
		$this -> curRow--;
	}

	public function goPreviousColumn() {
		$this -> curCol--;
	}

	public function getNameFromNumber($num) {
		$numeric = ($num - 1) % 26;
		$letter = chr(65 + $numeric);
		$num2 = intval(($num - 1) / 26);
		if ($num2 > 0)
			return $this -> getNameFromNumber($num2) . $letter;
		else
			return $letter;
	}

}
?>