<?php
/**
 *
 * AsynchronousProcessClass.php
 * asynchronousproccess class file
 *
 * @copyright	Copyright (C) 2012 Rivas Systems Inc. All rights reserved.
 * @versin	1.00 2012-02-05
 * @author	Behnam Salili
 *
 */

require_once dirname(dirname(dirname(__file__))) . '/defines.php';
require_once dirname(dirname(__file__)) . '/database/database.php';
require_once dirname(dirname(__file__)) . '/illustrator/pdate.php';
require_once dirname(dirname(__file__)) . '/form/form-proccessor.php';
require_once dirname(dirname(__file__)) . '/models/state.php';
require_once dirname(dirname(__file__)) . '/models/town.php';
require_once dirname(dirname(__file__)) . '/models/center.php';
require_once dirname(dirname(__file__)) . '/models/unit.php';
require_once dirname(dirname(__file__)) . '/models/hygiene-unit.php';
require_once dirname(__file__) . '/export.php';

class AsynchronousProcess {

	//USD
	public static function sourceSelection($source) {
		$htmlOut = '';
		switch ($source) {
			case 'state' :
				$htmlOut = AsynchronousProcess::statesList();
				break;
			case 'town' :
				$htmlOut = AsynchronousProcess::townsList();
				break;
		}

		return $htmlOut;
	}

	//USD
	public static function statesList() {
		$htmlOut = '';
		$res = Database::execute_query("SELECT `id`, `name` FROM `states` ORDER BY `name` ASC;");
		if (Database::num_of_rows($res) > 0) {
			$htmlOut .= '<option>انتخاب کنید...</option>';
			while ($row = Database::get_assoc_array($res)) {
				$htmlOut .= '<option value="' . $row['id'] . '">' . $row['name'] . '</option>';
			}
		} else {
			$htmlOut .= '<option>هیچ استانی وجود ندارد!</option>';
		}

		return $htmlOut;
	}

	//USD
	public static function townsList() {
		$htmlOut = '';
		$res = Database::execute_query("SELECT `id`, `name` FROM `towns` ORDER BY `name` ASC;");
		if (Database::num_of_rows($res) > 0) {
			$htmlOut .= '<option>انتخاب کنید...</option>';
			while ($row = Database::get_assoc_array($res)) {
				$htmlOut .= '<option value="' . $row['id'] . '">' . $row['name'] . '</option>';
			}
		} else {
			$htmlOut .= '<option>هیچ شهرستانی وجود ندارد!</option>';
		}

		return $htmlOut;
	}

	//USD
	public static function getCenters($townId) {
		$htmlOut = '';
		$res = Database::execute_query("SELECT `id`, `name` FROM `centers` WHERE `town-id` = '$townId';");
		if (Database::num_of_rows($res) > 0) {
			$htmlOut .= '<option>انتخاب کنید...</option>';
			while ($row = Database::get_assoc_array($res)) {
				$htmlOut .= '<option value="' . $row['id'] . '">' . $row['name'] . '</option>';
			}
		} else
			$htmlOut .= '<option>هیچ مرکزی وجود ندارد!</option>';

		return $htmlOut;
	}

	//USD
	public static function getUnitsByTownId($townId) {
		$htmlOut = '';
		$res = Database::execute_query("SELECT `id`, `name` FROM `centers` WHERE `town-id` = '" . $townId . "'  ORDER BY `name` ASC LIMIT 1;");
		$tmp = Database::get_assoc_array($res);
		unset($res);
		$res = Database::execute_query("SELECT `id`, `name` FROM `units` WHERE `center-id` = '" . $tmp['id'] . "'  ORDER BY `name` ASC;");
		if (Database::num_of_rows($res) > 0) {
			while ($row = Database::get_assoc_array($res)) {
				$htmlOut .= '<option value="' . $row['id'] . '">' . $row['name'] . '</option>';
			}
		} else
			$htmlOut .= '<option value="" disabled="">هیچ واحدی وجود ندارد!</option>';

		return $htmlOut;
	}

	//USD
	public static function getHygieneUnitsByCenterId($townId) {
		$htmlOut = '';
		$res = Database::execute_query("SELECT `id`, `name` FROM `hygiene-units` WHERE `center-id` = '$townId';");
		if (Database::num_of_rows($res) > 0) {
			$htmlOut .= '<option>انتخاب کنید...</option>';
			while ($row = Database::get_assoc_array($res)) {
				$htmlOut .= '<option value="' . $row['id'] . '">' . $row['name'] . '</option>';
			}
		} else {
			$htmlOut .= '<option>هیچ خانه بهداشتی وجود ندارد!</option>';
		}

		return $htmlOut;
	}

	//USD
	public static function getUnits($centerId, $unitType) {
		$htmlOut = '';
		if($unitType) {
            $res = Database::execute_query("SELECT `id`, `name` FROM `units` WHERE `center-id` = '$centerId' AND `unit_type` = '$unitType';");
            if (Database::num_of_rows($res) > 0) {
                $htmlOut .= '<option>انتخاب کنید...</option>';
                while ($row = Database::get_assoc_array($res)) {
                    $htmlOut .= '<option value="' . $row['id'] . '">' . $row['name'] . '</option>';
                }
            } else
                $htmlOut .= '<option>هیچ واحدی وجود ندارد!</option>';
        } else {
		    $res = Database::execute_query("SELECT `id`, `name`, `unit_type` FROM `units` WHERE `center-id` = '$centerId';");
            if (Database::num_of_rows($res) > 0) {

                while ($row = Database::get_assoc_array($res)) {
                    $type = $row["unit_type"] == '0' ? "عمومی" : "سیب";
                    $htmlOut .= '<option value="' . $row['id'] . '">' . $row['name'] . ' (' . $type . ')</option>';
                }

            } else
                $htmlOut .= '<option>هیچ واحدی وجود ندارد!</option>';
        }

		return $htmlOut;
	}

	//USD
	public static function getDistinctUnits() {
		$htmlOut = '';
		$res = Database::execute_query("SELECT DISTINCT `name` FROM `units`;");
		if (Database::num_of_rows($res) > 0) {
			$htmlOut .= '<option>انتخاب کنید...</option>';
			while ($row = Database::get_assoc_array($res)) {
				$htmlOut .= '<option value="' . $row['name'] . '">' . $row['name'] . '</option>';
			}
		} else
			$htmlOut .= '<option>هیچ واحدی وجود ندارد!</option>';

		return $htmlOut;
	}

	//USD
	public static function getDistinctServices() {
		$htmlOut = '';
		$res = Database::execute_query("SELECT `id`, `name` FROM `available-services`;");
		if (Database::num_of_rows($res) > 0) {
			$htmlOut .= '<option>انتخاب کنید...</option>';
			while ($row = Database::get_assoc_array($res))
				$htmlOut .= '<option value="' . $row['id'] . '">' . $row['name'] . '</option>';
		} else
			$htmlOut .= '<option>هیچ خدمتی وجود ندارد!</option>';

		return $htmlOut;
	}

	/*------------------------------------------------------------------------------------------------------------------------*/
	/*														UNIT CHARGES													*/

	//USD
	public static function getTotalUnitChrages($unitId, $fromDay, $fromMonth, $fromYear, $toDay, $toMonth, $toYear) {
		$inRangeChargesWithMonth = array();
		$baseSalaris = array();
		$reportYear = $fromYear = $toYear;
		$totalMappedResult = array();

		//trying to sum unit personnel financials except base salary
		$res = Database::execute_query("SELECT `b`.`charge-cost`, `b`.`date` FROM `unit-personnels` AS `a` INNER JOIN `unit-personnel-financial` AS `b` ON `a`.`id` = `b`.`personnel-id` WHERE `a`.`unit-id` = '" . $unitId . "';");
		while ($row = Database::get_assoc_array($res)) {
			$chargeDate = date_parse($row['date']);
			if (self::isInDateRange($chargeDate['day'], $chargeDate['month'], $chargeDate['year'], $fromDay, $fromMonth, $fromYear, $toDay, $toMonth, $toYear) === true) {
				$inRangeChargesWithMonth[] = array('year' => $chargeDate['year'], 'month' => $chargeDate['month'], 'cost' => $row['charge-cost']);
			} else {
			}
		}

		$res = Database::execute_query("SELECT `base-salary`, `deleted`, `date-of-delete` FROM `unit-personnels` WHERE `unit-id` = '" . $unitId . "';");
		while ($row = Database::get_assoc_array($res))
			$baseSalaris[] = $row;

		$datePeriodArray = self::createDatePeriodArray($fromDay, $fromMonth, $fromYear, $toDay, $toMonth, $toYear);

		//mapping date with cost arrays to an in range arrays.
		foreach ($datePeriodArray as $dateItem) {
			$tmpSum = 0;
			foreach ($inRangeChargesWithMonth as $chargeItem) {
				if (($dateItem['year'] == $chargeItem['year']) and ($dateItem['month'] == $chargeItem['month']))
					$tmpSum += $chargeItem['cost'];
			}
			$currentMonthSalary = 0;
			foreach ($baseSalaris as $salaryItem) {
				if($salaryItem['deleted']) {
					$delDate = date_parse($salaryItem['date-of-delete']);
					$deleteDate = $delDate['year'] . $delDate['month'];
					$date = $dateItem['year'] . $dateItem['month'];
					if($reportYear > $delDate['year'])//ofcourse deleted
						continue;
					else if($reportYear < $delDate['year']) //ofcourse not deleted
						$currentMonthSalary += ($salaryItem['base-salary'] / 12);
					elseif ($reportYear == $delDate['year']) { //same year!
						if($date <= $deleteDate)
							$currentMonthSalary += ($salaryItem['base-salary'] / 12);
						else //after delete
							$currentMonthSalary = 0;
					}
				}
				else
					$currentMonthSalary += ($salaryItem['base-salary'] / 12);
			}
			$totalMappedResult[] = array('year' => $dateItem['year'], 'month' => $dateItem['month'], 'totalCost' => ($tmpSum + $currentMonthSalary));
		}

		return $totalMappedResult;
	}

	/*------------------------------------------------------------------------------------------------------------------------*/
	/*														CENTER CHARGES													*/

	//USD
	public static function getCenterTotalCharges($centerId, $fromDay, $fromMonth, $fromYear, $toDay, $toMonth, $toYear) {
		$centerDrugsCharge = array();
		$centerConsumingsCharge = array();
		$centerNonConsumingCharge = array();
		$centerBuildingCharge = array();
		$centerVehicleCharge = array();
		$centerGeneralCharge = array();
		$centerUnitsTotalCharge = array();

		$centerDrugsCharge = self::getCenterDrugsCharge($centerId, $fromDay, $fromMonth, $fromYear, $toDay, $toMonth, $toYear);
		$centerConsumingsCharge = self::getCenterConsumingsCharge($centerId, $fromDay, $fromMonth, $fromYear, $toDay, $toMonth, $toYear);
		$centerNonConsumingCharge = self::getCenterNonConsumingsCharge($centerId, $fromDay, $fromMonth, $fromYear, $toDay, $toMonth, $toYear);
		$centerBuildingCharge = self::getCenterBuildingsCharge($centerId, $fromDay, $fromMonth, $fromYear, $toDay, $toMonth, $toYear);
		$centerVehicleCharge = self::getCenterVehiclesCharge($centerId, $fromDay, $fromMonth, $fromYear, $toDay, $toMonth, $toYear);
		$centerGeneralCharge = self::getCenterGeneralCharges($centerId, $fromDay, $fromMonth, $fromYear, $toDay, $toMonth, $toYear);
		$centerUnitsTotalCharge = self::getCenterUnitsTotalCharge($centerId, $fromDay, $fromMonth, $fromYear, $toDay, $toMonth, $toYear);

		$totalMappedResult = array();
		$datePeriodArray = self::createDatePeriodArray($fromDay, $fromMonth, $fromYear, $toDay, $toMonth, $toYear);

		foreach ($datePeriodArray as $value)
			$totalMappedResult[] = array('year' => $value['year'], 'month' => $value['month'], 'totalCost' => 0);

		for ($i = 0; $i < count($totalMappedResult); $i++) {
			$totalMappedResult[$i]['totalCost'] += $centerDrugsCharge[$i]['totalCost'];
			$totalMappedResult[$i]['totalCost'] += $centerConsumingsCharge[$i]['totalCost'];
			$totalMappedResult[$i]['totalCost'] += $centerNonConsumingCharge[$i]['totalCost'];
			$totalMappedResult[$i]['totalCost'] += $centerBuildingCharge[$i]['totalCost'];
			$totalMappedResult[$i]['totalCost'] += $centerVehicleCharge[$i]['totalCost'];
			$totalMappedResult[$i]['totalCost'] += $centerGeneralCharge[$i]['totalCost'];
			$totalMappedResult[$i]['totalCost'] += $centerUnitsTotalCharge[$i]['totalCost'];
		}

		return $totalMappedResult;
	}

	//USD
	public static function getCenterCustomCharge($centerId, $chargeType, $fromDay, $fromMonth, $fromYear, $toDay, $toMonth, $toYear) {
		switch($chargeType) {
			case 'drugs' :
				return self::getCenterDrugsCharge($centerId, $fromDay, $fromMonth, $fromYear, $toDay, $toMonth, $toYear);
				break;
			case 'consumings' :
				return self::getCenterConsumingsCharge($centerId, $fromDay, $fromMonth, $fromYear, $toDay, $toMonth, $toYear);
				break;
			case 'nonConsumings' :
				return self::getCenterNonConsumingsCharge($centerId, $fromDay, $fromMonth, $fromYear, $toDay, $toMonth, $toYear);
				break;
			case 'buildings' :
				return self::getCenterBuildingsCharge($centerId, $fromDay, $fromMonth, $fromYear, $toDay, $toMonth, $toYear);
				break;
			case 'vehicles' :
				return self::getCenterVehiclesCharge($centerId, $fromDay, $fromMonth, $fromYear, $toDay, $toMonth, $toYear);
				break;
			case 'general' :
				return self::getCenterGeneralCharges($centerId, $fromDay, $fromMonth, $fromYear, $toDay, $toMonth, $toYear);
				break;
			case 'totalUnits' :
				return self::getCenterUnitsTotalCharge($centerId, $fromDay, $fromMonth, $fromYear, $toDay, $toMonth, $toYear);
				break;
		}
	}

	//USD
	public static function getCenterDrugsCharge($centerId, $fromDay, $fromMonth, $fromYear, $toDay, $toMonth, $toYear) {
		$inRangeCenterDrugsChargesWithDate = array();
		$totalMappedResult = array();

		//drugs and consuming equipments
		//$res = Database::execute_query("SELECT * FROM `v_centers_drugs_full` WHERE `is_drug` = 'yes' AND `center-id` = '" . $centerId . "'");
        $res = Database::execute_query("SELECT `center-drugs`.`num-of-drugs`, `center-drugs`.`date`, `available-drugs-cost`.`cost`, `available-drugs-and-consuming-equipments`.`is_free` FROM `center-drugs` 
                                    LEFT JOIN `available-drugs-cost` ON `center-drugs`.`drug-cost-id` = `available-drugs-cost`.`id`
                                    LEFT JOIN `available-drugs-and-consuming-equipments` ON `available-drugs-cost`.`drug-id` = `available-drugs-and-consuming-equipments`.`id`
                                    WHERE `center-drugs`.`center-id` = '" . $centerId . "' AND `available-drugs-and-consuming-equipments`.`is_drug` = 'yes' 
                                    ORDER BY `available-drugs-and-consuming-equipments`.`name` ASC;");
        while ($row = Database::get_assoc_array($res)) {
			$chargeDate = date_parse($row['date']);
			if (self::isInDateRange($chargeDate['day'], $chargeDate['month'], $chargeDate['year'], $fromDay, $fromMonth, $fromYear, $toDay, $toMonth, $toYear) === true) {
				if($row['is_free'] == 'no')
					$cost = 0.7 * ($row['num-of-drugs'] * $row['cost']);
				else
					$cost = 1;
				$inRangeCenterDrugsChargesWithDate[] = array('year' => $chargeDate['year'], 'month' => $chargeDate['month'], 'cost' => $cost);
			}
		}

		$datePeriodArray = self::createDatePeriodArray($fromDay, $fromMonth, $fromYear, $toDay, $toMonth, $toYear);

		//mapping date with cost arrays to an in range arrays.
		foreach ($datePeriodArray as $dateItem) {
			$tmpSum = 0;
			foreach ($inRangeCenterDrugsChargesWithDate as $chargeItem) {
				if (($dateItem['year'] == $chargeItem['year']) and ($dateItem['month'] == $chargeItem['month']))
					$tmpSum += $chargeItem['cost'];
			}
			$totalMappedResult[] = array('year' => $dateItem['year'], 'month' => $dateItem['month'], 'totalCost' => $tmpSum);
		}

		return $totalMappedResult;
	}

	//USD
	public static function getCenterConsumingsCharge($centerId, $fromDay, $fromMonth, $fromYear, $toDay, $toMonth, $toYear) {
		$inRangeCenterDrugsChargesWithDate = array();
		$totalMappedResult = array();

		//drugs and consuming equipments
		//$res = Database::execute_query("SELECT * FROM `v_centers_drugs_full` WHERE `is_drug` = 'no' AND `center-id` = '" . $centerId . "'");
        $res = Database::execute_query("SELECT `center-drugs`.`num-of-drugs`, `center-drugs`.`date`, `available-drugs-cost`.`cost`, `available-drugs-and-consuming-equipments`.`is_free` FROM `center-drugs` 
                                    LEFT JOIN `available-drugs-cost` ON `center-drugs`.`drug-cost-id` = `available-drugs-cost`.`id`
                                    LEFT JOIN `available-drugs-and-consuming-equipments` ON `available-drugs-cost`.`drug-id` = `available-drugs-and-consuming-equipments`.`id`
                                    WHERE `center-drugs`.`center-id` = '" . $centerId . "' AND `available-drugs-and-consuming-equipments`.`is_drug` = 'no' 
                                    ORDER BY `available-drugs-and-consuming-equipments`.`name` ASC;");
		while ($row = Database::get_assoc_array($res)) {
			$chargeDate = date_parse($row['date']);
			if (self::isInDateRange($chargeDate['day'], $chargeDate['month'], $chargeDate['year'], $fromDay, $fromMonth, $fromYear, $toDay, $toMonth, $toYear) === true) {
				$cost = $row['num-of-drugs'] * $row['cost'];
				$inRangeCenterDrugsChargesWithDate[] = array('year' => $chargeDate['year'], 'month' => $chargeDate['month'], 'cost' => $cost);
			}
		}

		$datePeriodArray = self::createDatePeriodArray($fromDay, $fromMonth, $fromYear, $toDay, $toMonth, $toYear);

		//mapping date with cost arrays to an in range arrays.
		foreach ($datePeriodArray as $dateItem) {
			$tmpSum = 0;
			foreach ($inRangeCenterDrugsChargesWithDate as $chargeItem) {
				if (($dateItem['year'] == $chargeItem['year']) and ($dateItem['month'] == $chargeItem['month']))
					$tmpSum += $chargeItem['cost'];
			}
			$totalMappedResult[] = array('year' => $dateItem['year'], 'month' => $dateItem['month'], 'totalCost' => $tmpSum);
		}

		return $totalMappedResult;
	}

	//USD
	public static function getCenterNonConsumingsCharge($centerId, $fromDay, $fromMonth, $fromYear, $toDay, $toMonth, $toYear) {
		$inRangeCenterNonConsumingChargesWithMonth = array();
		$reportYear = $fromYear = $toYear;
		$nonConsumings = array();
		$datePeriodArray = self::createDatePeriodArray($fromDay, $fromMonth, $fromYear, $toDay, $toMonth, $toYear);
		foreach ($datePeriodArray as $dateItem)//initializing
			$inRangeCenterNonConsumingChargesWithMonth[] = array('year' => $dateItem['year'], 'month' => $dateItem['month'], 'totalCost' => 0);

		//$res = Database::execute_query("SELECT * FROM `v_centers_nonconsumings_full` WHERE `center-id` = '" . $centerId . "';");
        $res = Database::execute_query("SELECT `center-non-consuming-equipments`.`deleted`, `center-non-consuming-equipments`.`date-of-delete`, `center-non-consuming-equipments`.`num-of-equips`, `center-non-consuming-equipments`.`date`, `available-non-consuming-cost`.`cost` FROM `center-non-consuming-equipments` 
                                    LEFT JOIN `available-non-consuming-cost` ON `center-non-consuming-equipments`.`equip-cost-id` = `available-non-consuming-cost`.`id` 
                                    LEFT JOIN `available-non-consuming-equipments` ON `available-non-consuming-cost`.`equip-id` = `available-non-consuming-equipments`.`id` 
                                    WHERE `center-non-consuming-equipments`.`center-id` = " . $centerId . ";");
		while ($rowDB = Database::get_assoc_array($res))
			$nonConsumings[] = $rowDB;
			
		for ($i = 0; $i < count($inRangeCenterNonConsumingChargesWithMonth); $i++) {
			$monthCost = 0;
			foreach ($nonConsumings as $row) {
				$cost = 0;
				$regDate = date_parse($row['date']);
				$regYear = $regDate['year'];
				if ($regYear > $reportYear)
					$cost = 0;// registered after report
				else {
					if ($row['deleted']) {
						$delDate = date_parse($row['date-of-delete']);
						if($reportYear > $delDate['year']) //ofcourse deleted
							$cost = 0;
						elseif($reportYear < $delDate['year']) {//ofcourse not deleted
							if (($reportYear - 9) <= $regYear) //less than 10 years
								$cost = ($row['cost'] * $row['num-of-equips']) / 120;
							else
								$cost = 1;
						}
						elseif ($reportYear == $delDate['year']) { //same year!
							$deleteDate = $delDate['year'] . $delDate['month'];
							$date = $inRangeCenterNonConsumingChargesWithMonth[$i]['year'] . $inRangeCenterNonConsumingChargesWithMonth[$i]['month'];
							if($date <= $deleteDate) {
								if (($reportYear - 9) <= $regYear) //less than 10 years
									$cost = ($row['cost'] * $row['num-of-equips']) / 120;
								else
									$cost = 1;
							}
							else //after delete
								$cost = 0;
						}
					}
					else {
						if (($reportYear - 9) <= $regYear) //less than 10 years
							$cost = ($row['cost'] * $row['num-of-equips']) / 120;
						else
							$cost = 1;
					}
				}
				$monthCost += $cost;
			}//end foreach
			$inRangeCenterNonConsumingChargesWithMonth[$i]['totalCost'] += $monthCost;
		}//end for
		
		return $inRangeCenterNonConsumingChargesWithMonth;
	}

	//USD
	public static function getCenterBuildingsCharge($centerId, $fromDay, $fromMonth, $fromYear, $toDay, $toMonth, $toYear) {
		$inRangeCenterBuildingsWorthMappedWithMonth = array();
		//for buildings owned by center
		$inRangeCenterBuildingsMaintainChargesWithMonth = array();
		//mapped to $inRangeCenterBuildingsMaintainChargesMappedWithMonth
		$totalBuildingsRentCostPerMonth = 0;
		$totalMappedResult = array();

		$datePeriodArray = self::createDatePeriodArray($fromDay, $fromMonth, $fromYear, $toDay, $toMonth, $toYear);
		foreach ($datePeriodArray as $dateItem)//initializing
			$inRangeCenterBuildingsWorthMappedWithMonth[] = array('year' => $dateItem['year'], 'month' => $dateItem['month'], 'cost' => 0);

		$res = Database::execute_query("SELECT `ownership-type`, `rent-cost`, `building-worth`, `built-date` FROM `center-buildings` WHERE `center-id` = '" . $centerId . "';");
		while ($row = Database::get_assoc_array($res)) {
			if ($row['ownership-type'] == 0) {//melki
				//trying to calculate the difference between builtDate and reportDate
				$builtDate = date_parse($row['built-date']);
				$greg_built_date = jalali_to_gregorian($builtDate['year'], $builtDate['month'], $builtDate['day']);
				$building_build_date = Date(mktime(0, 0, 0, $greg_built_date['1'], $greg_built_date['2'], $greg_built_date['0']));
				foreach ($datePeriodArray as $dateItem) {
					$greg_now_date = jalali_to_gregorian($dateItem['year'], $dateItem['month'], $builtDate['day']);
					$date = Date(mktime(0, 0, 0, $greg_now_date['1'], $greg_now_date['2'], $greg_now_date['0']));
					$from_to_gap = self::count_days($date, $building_build_date);
					//calculationg leap years
					$leapYears = 0;
					for ($year = $builtDate['year']; $year <= $dateItem['year']; $year++) {
						if (isKabise($year))
							$leapYears++;
					}
					if ($from_to_gap > (5475 + $leapYears + 1)) {//5475 = 15 * 365
						for ($i = 0; $i < count($inRangeCenterBuildingsWorthMappedWithMonth); $i++) {
							if (($inRangeCenterBuildingsWorthMappedWithMonth[$i]['year'] == $dateItem['year']) and ($inRangeCenterBuildingsWorthMappedWithMonth[$i]['month'] == $dateItem['month']))
								$inRangeCenterBuildingsWorthMappedWithMonth[$i]['cost'] += 1;
						}
					} else if ($from_to_gap <= (5475 + $leapYears + 1) and $from_to_gap > 0) {
						for ($i = 0; $i < count($inRangeCenterBuildingsWorthMappedWithMonth); $i++) {
							if (($inRangeCenterBuildingsWorthMappedWithMonth[$i]['year'] == $dateItem['year']) and ($inRangeCenterBuildingsWorthMappedWithMonth[$i]['month'] == $dateItem['month']))
								$inRangeCenterBuildingsWorthMappedWithMonth[$i]['cost'] += ($row['building-worth'] / 180);
						}
					}
				}
			} else if ($row['ownership-type'] == 1)//estijari
				$totalBuildingsRentCostPerMonth += $row['rent-cost'];
		}

		//building maintain charges
		$res = Database::execute_query("SELECT `b`.`charge-amount`, `b`.`date` FROM `center-buildings` AS `a` INNER JOIN `center-building-maintain-charges` AS `b` ON `a`.`id` = `b`.`building-id` WHERE `a`.`center-id` = '" . $centerId . "';");
		while ($row = Database::get_assoc_array($res)) {
			$chargeDate = date_parse($row['date']);
			if (self::isInDateRange($chargeDate['day'], $chargeDate['month'], $chargeDate['year'], $fromDay, $fromMonth, $fromYear, $toDay, $toMonth, $toYear) === true) {
				$inRangeCenterBuildingsMaintainChargesWithMonth[] = array('year' => $chargeDate['year'], 'month' => $chargeDate['month'], 'cost' => $row['charge-amount']);
			} else {
				//nothing todo yet
			}
		}

		//mapping month with cost arrays to an in range arrays. Numerical names described at the start of function.
		$inRangeCenterBuildingsMaintainChargesMappedWithMonth = array();
		foreach ($datePeriodArray as $dateItem) {
			$tmpSum = 0;
			foreach ($inRangeCenterBuildingsMaintainChargesWithMonth as $chargeItem) {
				if (($dateItem['year'] == $chargeItem['year']) and ($dateItem['month'] == $chargeItem['month']))
					$tmpSum += $chargeItem['cost'];
			}
			$inRangeCenterBuildingsMaintainChargesMappedWithMonth[] = array('year' => $dateItem['year'], 'month' => $dateItem['month'], 'cost' => $tmpSum);
		}

		for ($i = 0; $i < count($datePeriodArray); $i++) {
			$totalMappedResult[] = array('year' => $datePeriodArray[$i]['year'], 'month' => $datePeriodArray[$i]['month'], 'totalCost' => 0);
			$totalMappedResult[$i]['totalCost'] += $inRangeCenterBuildingsWorthMappedWithMonth[$i]['cost'];
			$totalMappedResult[$i]['totalCost'] += $inRangeCenterBuildingsMaintainChargesMappedWithMonth[$i]['cost'];
			$totalMappedResult[$i]['totalCost'] += $totalBuildingsRentCostPerMonth;
		}

		return $totalMappedResult;
	}

	//USD
	public static function getCenterVehiclesCharge($centerId, $fromDay, $fromMonth, $fromYear, $toDay, $toMonth, $toYear) {
		$inRangeCenterVehiclesWorthMappedWithMonth = array();
		$inRangeCenterVehiclesMaintainChargesWithMonth = array();
		$totalVehiclesRentCostPerMonth = 0;
		//mapped to $inRangeCenterVehiclesMaintainChargesMappedWithMonth
		$totalMappedResult = array();

		$datePeriodArray = self::createDatePeriodArray($fromDay, $fromMonth, $fromYear, $toDay, $toMonth, $toYear);
		foreach ($datePeriodArray as $dateItem)//initializing
			$inRangeCenterVehiclesWorthMappedWithMonth[] = array('year' => $dateItem['year'], 'month' => $dateItem['month'], 'cost' => 0);

		$res = Database::execute_query("SELECT `ownership-type`, `rent-cost`, `vehicle-worth`, `buy-date` FROM `center-vehicles` WHERE `center-id` = '" . $centerId . "';");
		while ($row = Database::get_assoc_array($res)) {
			if ($row['ownership-type'] == 0) {//gov
				//trying to calculate the difference between buyDate and reportDate
				$buyDate = date_parse($row['buy-date']);
				$greg_buy_date = jalali_to_gregorian($buyDate['year'], $buyDate['month'], $buyDate['day']);
				$vehicle_buy_date = Date(mktime(0, 0, 0, $greg_buy_date['1'], $greg_buy_date['2'], $greg_buy_date['0']));
				foreach ($datePeriodArray as $dateItem) {
					$greg_now_date = jalali_to_gregorian($dateItem['year'], $dateItem['month'], $buyDate['day']);
					$date = Date(mktime(0, 0, 0, $greg_now_date['1'], $greg_now_date['2'], $greg_now_date['0']));
					$from_to_gap = self::count_days($date, $vehicle_buy_date);
					//calculationg leap years
					$leapYears = 0;
					for ($year = $buyDate['year']; $year <= $dateItem['year']; $year++) {
						if (isKabise($year))
							$leapYears++;
					}
					if ($from_to_gap > (3650 + $leapYears + 1)) {//3650 = 10 * 365 days
						for ($i = 0; $i < count($inRangeCenterVehiclesWorthMappedWithMonth); $i++) {
							if (($inRangeCenterVehiclesWorthMappedWithMonth[$i]['year'] == $dateItem['year']) and ($inRangeCenterVehiclesWorthMappedWithMonth[$i]['month'] == $dateItem['month']))
								$inRangeCenterVehiclesWorthMappedWithMonth[$i]['cost'] += 1;
						}
					} else if ($from_to_gap <= (3650 + $leapYears + 1) and $from_to_gap > 0) {
						for ($i = 0; $i < count($inRangeCenterVehiclesWorthMappedWithMonth); $i++) {
							if (($inRangeCenterVehiclesWorthMappedWithMonth[$i]['year'] == $dateItem['year']) and ($inRangeCenterVehiclesWorthMappedWithMonth[$i]['month'] == $dateItem['month']))
								$inRangeCenterVehiclesWorthMappedWithMonth[$i]['cost'] += ($row['vehicle-worth'] / 120);
						}
					}
				}
			} else if ($row['ownership-type'] == 1)//rent
				$totalVehiclesRentCostPerMonth += $row['rent-cost'];
		}//while

		//vehicle maintain charges
		$res = Database::execute_query("SELECT `b`.`charge-amount`, `b`.`date` FROM `center-vehicles` AS `a` INNER JOIN `center-vehicle-maintain-charges` AS `b` ON `a`.`id` = `b`.`vehicle-id` WHERE `a`.`center-id` = '" . $centerId . "';");
		while ($row = Database::get_assoc_array($res)) {
			$chargeDate = date_parse($row['date']);
			if (self::isInDateRange($chargeDate['day'], $chargeDate['month'], $chargeDate['year'], $fromDay, $fromMonth, $fromYear, $toDay, $toMonth, $toYear) === true) {
				$inRangeCenterVehiclesMaintainChargesWithMonth[] = array('year' => $chargeDate['year'], 'month' => $chargeDate['month'], 'cost' => $row['charge-amount']);
			} else {
				//nothing todo yet
			}
		}

		//mapping month with cost arrays to an in range arrays.
		$inRangeCenterVehiclesMaintainChargesMappedWithMonth = array();
		foreach ($datePeriodArray as $dateItem) {
			$tmpSum = 0;
			foreach ($inRangeCenterVehiclesMaintainChargesWithMonth as $chargeItem) {
				if (($dateItem['year'] == $chargeItem['year']) and ($dateItem['month'] == $chargeItem['month']))
					$tmpSum += $chargeItem['cost'];
			}
			$inRangeCenterVehiclesMaintainChargesMappedWithMonth[] = array('year' => $dateItem['year'], 'month' => $dateItem['month'], 'cost' => $tmpSum);
		}

		for ($i = 0; $i < count($datePeriodArray); $i++) {
			$totalMappedResult[] = array('year' => $datePeriodArray[$i]['year'], 'month' => $datePeriodArray[$i]['month'], 'totalCost' => 0);
			$totalMappedResult[$i]['totalCost'] += $inRangeCenterVehiclesWorthMappedWithMonth[$i]['cost'];
			$totalMappedResult[$i]['totalCost'] += $inRangeCenterVehiclesMaintainChargesMappedWithMonth[$i]['cost'];
			$totalMappedResult[$i]['totalCost'] += $totalVehiclesRentCostPerMonth;
		}

		return $totalMappedResult;
	}

	//USD
	public static function getCenterGeneralCharges($centerId, $fromDay, $fromMonth, $fromYear, $toDay, $toMonth, $toYear) {
		$inRangeCenterGeneralChargesWithMonth = array();
		$totalMappedResult = array();

		//center general charges
		$res = Database::execute_query("SELECT `charge-amount`, `date` FROM `center-general-charges` WHERE `center-id` = '" . $centerId . "'");
		while ($row = Database::get_assoc_array($res)) {
			$chargeDate = date_parse($row['date']);
			if (self::isInDateRange($chargeDate['day'], $chargeDate['month'], $chargeDate['year'], $fromDay, $fromMonth, $fromYear, $toDay, $toMonth, $toYear) === true) {
				$inRangeCenterGeneralChargesWithMonth[] = array('year' => $chargeDate['year'], 'month' => $chargeDate['month'], 'cost' => $row['charge-amount']);
			}
		}

		$datePeriodArray = self::createDatePeriodArray($fromDay, $fromMonth, $fromYear, $toDay, $toMonth, $toYear);

		//mapping date with cost arrays to an in range arrays.
		foreach ($datePeriodArray as $dateItem) {
			$tmpSum = 1;
			foreach ($inRangeCenterGeneralChargesWithMonth as $chargeItem) {
				if (($dateItem['year'] == $chargeItem['year']) and ($dateItem['month'] == $chargeItem['month']))
					$tmpSum += $chargeItem['cost'];
			}
			$totalMappedResult[] = array('year' => $dateItem['year'], 'month' => $dateItem['month'], 'totalCost' => $tmpSum);
		}

		return $totalMappedResult;
	}

	//USD
	public static function getCenterUnitsTotalCharge($centerId, $fromDay, $fromMonth, $fromYear, $toDay, $toMonth, $toYear) {
		$totalMappedResult = array();
		$datePeriodArray = self::createDatePeriodArray($fromDay, $fromMonth, $fromYear, $toDay, $toMonth, $toYear);

		foreach ($datePeriodArray as $dateItem)//initializing
			$totalMappedResult[] = array('year' => $dateItem['year'], 'month' => $dateItem['month'], 'totalCost' => 0);

		$tmpUnitCharge = array();
		$res = Database::execute_query("SELECT `id` FROM `units` WHERE `center-id` = '$centerId';");
		while ($row = Database::get_assoc_array($res)) {
			$tmpUnitCharge = AsynchronousProcess::getTotalUnitChrages($row['id'], $fromDay, $fromMonth, $fromYear, $toDay, $toMonth, $toYear);
			for ($i = 0; $i < count($totalMappedResult); $i++)
				$totalMappedResult[$i]['totalCost'] += $tmpUnitCharge[$i]['totalCost'];
		}

		return $totalMappedResult;
	}

	/*------------------------------------------------------------------------------------------------------------------------*/
	/*														HYGIENE UNIT CHARGES											*/

	//USD
	public static function getHygieneUnitTotalCharges($hygieneUnitId, $fromDay, $fromMonth, $fromYear, $toDay, $toMonth, $toYear) {
		$hygieneUnitDrugsCharge = array();
		$hygieneUnitConsumingsCharge = array();
		$hygieneUnitNonConsumingCharge = array();
		$hygieneUnitBuildingCharge = array();
		$hygieneUnitVehicleCharge = array();
		$hygieneUnitGeneralCharge = array();

		$hygieneUnitDrugsCharge = self::getHygieneUnitDrugsCharge($hygieneUnitId, $fromDay, $fromMonth, $fromYear, $toDay, $toMonth, $toYear);
		$hygieneUnitConsumingsCharge = self::getHygieneUnitConsumingsCharge($hygieneUnitId, $fromDay, $fromMonth, $fromYear, $toDay, $toMonth, $toYear);
		$hygieneUnitNonConsumingCharge = self::getHygieneUnitNonConsumingsCharge($hygieneUnitId, $fromDay, $fromMonth, $fromYear, $toDay, $toMonth, $toYear);
		$hygieneUnitBuildingCharge = self::getHygieneUnitBuildingsCharge($hygieneUnitId, $fromDay, $fromMonth, $fromYear, $toDay, $toMonth, $toYear);
		$hygieneUnitVehicleCharge = self::getHygieneUnitVehiclesCharge($hygieneUnitId, $fromDay, $fromMonth, $fromYear, $toDay, $toMonth, $toYear);
		$hygieneUnitGeneralCharge = self::getHygieneUnitGeneralCharges($hygieneUnitId, $fromDay, $fromMonth, $fromYear, $toDay, $toMonth, $toYear);

		$totalMappedResult = array();
		$datePeriodArray = self::createDatePeriodArray($fromDay, $fromMonth, $fromYear, $toDay, $toMonth, $toYear);

		foreach ($datePeriodArray as $value)
			$totalMappedResult[] = array('year' => $value['year'], 'month' => $value['month'], 'totalCost' => 0);

		for ($i = 0; $i < count($totalMappedResult); $i++) {
			$totalMappedResult[$i]['totalCost'] += $hygieneUnitDrugsCharge[$i]['totalCost'];
			$totalMappedResult[$i]['totalCost'] += $hygieneUnitConsumingsCharge[$i]['totalCost'];
			$totalMappedResult[$i]['totalCost'] += $hygieneUnitNonConsumingCharge[$i]['totalCost'];
			$totalMappedResult[$i]['totalCost'] += $hygieneUnitBuildingCharge[$i]['totalCost'];
			$totalMappedResult[$i]['totalCost'] += $hygieneUnitVehicleCharge[$i]['totalCost'];
			$totalMappedResult[$i]['totalCost'] += $hygieneUnitGeneralCharge[$i]['totalCost'];
		}

		return $totalMappedResult;
	}

	//USD
	public static function getHygieneUnitCustomCharge($hygieneUnitId, $chargeType, $fromDay, $fromMonth, $fromYear, $toDay, $toMonth, $toYear) {

		switch($chargeType) {
			case 'drugs' :
				return self::getHygieneUnitDrugsCharge($hygieneUnitId, $fromDay, $fromMonth, $fromYear, $toDay, $toMonth, $toYear);
				break;
			case 'consumings' :
				return self::getHygieneUnitConsumingsCharge($hygieneUnitId, $fromDay, $fromMonth, $fromYear, $toDay, $toMonth, $toYear);
				break;
			case 'nonConsumings' :
				return self::getHygieneUnitNonConsumingsCharge($hygieneUnitId, $fromDay, $fromMonth, $fromYear, $toDay, $toMonth, $toYear);
				break;
			case 'buildings' :
				return self::getHygieneUnitBuildingsCharge($hygieneUnitId, $fromDay, $fromMonth, $fromYear, $toDay, $toMonth, $toYear);
				break;
			case 'vehicles' :
				return self::getHygieneUnitVehiclesCharge($hygieneUnitId, $fromDay, $fromMonth, $fromYear, $toDay, $toMonth, $toYear);
				break;
			case 'general' :
				return self::getHygieneUnitGeneralCharges($hygieneUnitId, $fromDay, $fromMonth, $fromYear, $toDay, $toMonth, $toYear);
				break;
			case 'personnel' :
				return self::getHygieneUnitBehvarzChrages($hygieneUnitId, $fromDay, $fromMonth, $fromYear, $toDay, $toMonth, $toYear);
				break;
		}

	}

	//USD
	public static function getHygieneUnitDrugsCharge($hygieneUnitId, $fromDay, $fromMonth, $fromYear, $toDay, $toMonth, $toYear) {
		$inRangeHygieneUnitDrugsChargesWithDate = array();
		$totalMappedResult = array();

		//drugs and consuming equipments
		//$res = Database::execute_query("SELECT * FROM `v_hygiene-units_drugs_full` WHERE `is_drug` = 'yes' AND `hygiene-unit-id` = '" . $hygieneUnitId . "';");
        $res = Database::execute_query("SELECT `hygiene-unit-drugs`.`date`, `hygiene-unit-drugs`.`num-of-drugs`, `available-drugs-cost`.`cost`, `available-drugs-and-consuming-equipments`.`is_free` FROM `hygiene-unit-drugs` 
                                    LEFT JOIN `available-drugs-cost` ON `hygiene-unit-drugs`.`drug-cost-id` = `available-drugs-cost`.`id`
                                    LEFT JOIN `available-drugs-and-consuming-equipments` ON `available-drugs-cost`.`drug-id` = `available-drugs-and-consuming-equipments`.`id`
                                    WHERE `hygiene-unit-drugs`.`hygiene-unit-id` = '" . $hygieneUnitId . "' AND `available-drugs-and-consuming-equipments`.`is_drug` = 'yes';");
        while ($row = Database::get_assoc_array($res)) {
			$chargeDate = date_parse($row['date']);
			if (self::isInDateRange($chargeDate['day'], $chargeDate['month'], $chargeDate['year'], $fromDay, $fromMonth, $fromYear, $toDay, $toMonth, $toYear) === true) {
				if($row['is_free'] == 'no')
					$cost = 0.7 * ($row['num-of-drugs'] * $row['cost']);
				else
					$cost = 1;
				$inRangeHygieneUnitDrugsChargesWithDate[] = array('year' => $chargeDate['year'], 'month' => $chargeDate['month'], 'cost' => $cost);
			}
		}

		$datePeriodArray = self::createDatePeriodArray($fromDay, $fromMonth, $fromYear, $toDay, $toMonth, $toYear);

		//mapping date with cost arrays to an in range arrays.
		foreach ($datePeriodArray as $dateItem) {
			$tmpSum = 0;
			foreach ($inRangeHygieneUnitDrugsChargesWithDate as $chargeItem) {
				if (($dateItem['year'] == $chargeItem['year']) and ($dateItem['month'] == $chargeItem['month']))
					$tmpSum += $chargeItem['cost'];
			}
			$totalMappedResult[] = array('year' => $dateItem['year'], 'month' => $dateItem['month'], 'totalCost' => $tmpSum);
		}

		return $totalMappedResult;
	}

	//USD
	public static function getHygieneUnitConsumingsCharge($hygieneUnitId, $fromDay, $fromMonth, $fromYear, $toDay, $toMonth, $toYear) {
		$inRangeHygieneUnitDrugsChargesWithDate = array();
		$totalMappedResult = array();

		//drugs and consuming equipments
		//$res = Database::execute_query("SELECT * FROM `v_hygiene-units_drugs_full` WHERE `is_drug` = 'no' AND `hygiene-unit-id` = '" . $hygieneUnitId . "';");
        $res = Database::execute_query("SELECT `hygiene-unit-drugs`.`date`, `hygiene-unit-drugs`.`num-of-drugs`, `available-drugs-cost`.`cost`, `available-drugs-and-consuming-equipments`.`is_free` FROM `hygiene-unit-drugs` 
                                    LEFT JOIN `available-drugs-cost` ON `hygiene-unit-drugs`.`drug-cost-id` = `available-drugs-cost`.`id`
                                    LEFT JOIN `available-drugs-and-consuming-equipments` ON `available-drugs-cost`.`drug-id` = `available-drugs-and-consuming-equipments`.`id`
                                    WHERE `hygiene-unit-drugs`.`hygiene-unit-id` = '" . $hygieneUnitId . "' AND `available-drugs-and-consuming-equipments`.`is_drug` = 'no';");
		while ($row = Database::get_assoc_array($res)) {
			$chargeDate = date_parse($row['date']);
			if (self::isInDateRange($chargeDate['day'], $chargeDate['month'], $chargeDate['year'], $fromDay, $fromMonth, $fromYear, $toDay, $toMonth, $toYear) === true) {
				$cost = $row['num-of-drugs'] * $row['cost'];
				$inRangeHygieneUnitDrugsChargesWithDate[] = array('year' => $chargeDate['year'], 'month' => $chargeDate['month'], 'cost' => $cost);
			}
		}

		$datePeriodArray = self::createDatePeriodArray($fromDay, $fromMonth, $fromYear, $toDay, $toMonth, $toYear);
		//mapping date with cost arrays to an in range arrays.
		foreach ($datePeriodArray as $dateItem) {
			$tmpSum = 0;
			foreach ($inRangeHygieneUnitDrugsChargesWithDate as $chargeItem) {
				if (($dateItem['year'] == $chargeItem['year']) and ($dateItem['month'] == $chargeItem['month']))
					$tmpSum += $chargeItem['cost'];
			}
			$totalMappedResult[] = array('year' => $dateItem['year'], 'month' => $dateItem['month'], 'totalCost' => $tmpSum);
		}

		return $totalMappedResult;
	}

	//USD
	public static function getHygieneUnitNonConsumingsCharge($hygieneUnitId, $fromDay, $fromMonth, $fromYear, $toDay, $toMonth, $toYear) {
		$inRangeHygieneUnitNonConsumingChargesWithMonth = array();
		$reportYear = $fromYear = $toYear;
		$nonConsumings = array();
		$datePeriodArray = self::createDatePeriodArray($fromDay, $fromMonth, $fromYear, $toDay, $toMonth, $toYear);
		
		foreach ($datePeriodArray as $dateItem)//initializing
			$inRangeHygieneUnitNonConsumingChargesWithMonth[] = array('year' => $dateItem['year'], 'month' => $dateItem['month'], 'totalCost' => 0);
		
		//$res = Database::execute_query("SELECT * FROM `v_hygiene-units_nonconsumings_full` WHERE `hygiene-unit-id` = '" . $hygieneUnitId . "';");
        $res = Database::execute_query("SELECT `hygiene-unit-non-consuming-equipments`.`date`, `hygiene-unit-non-consuming-equipments`.`deleted`, `hygiene-unit-non-consuming-equipments`.`date-of-delete`, `hygiene-unit-non-consuming-equipments`.`num-of-equips`, `available-non-consuming-cost`.`cost` FROM `hygiene-unit-non-consuming-equipments` 
                                    LEFT JOIN `available-non-consuming-cost` ON `hygiene-unit-non-consuming-equipments`.`equip-cost-id` = `available-non-consuming-cost`.`id` 
                                    LEFT JOIN `available-non-consuming-equipments` ON `available-non-consuming-cost`.`equip-id` = `available-non-consuming-equipments`.`id` 
                                    WHERE `hygiene-unit-non-consuming-equipments`.`hygiene-unit-id` = " . $hygieneUnitId . ";");
		while ($rowDB = Database::get_assoc_array($res))
			$nonConsumings[] = $rowDB;
			
		for ($i = 0; $i < count($inRangeHygieneUnitNonConsumingChargesWithMonth); $i++) {
			$monthCost = 0;
			foreach ($nonConsumings as $row) {
				$cost = 0;
				$regDate = date_parse($row['date']);
				$regYear = $regDate['year'];
				if ($regYear > $reportYear)
					$cost = 0;// registered after report
				else {
					if ($row['deleted']) {
						$delDate = date_parse($row['date-of-delete']);
						if($reportYear > $delDate['year']) //ofcourse deleted
							$cost = 0;
						elseif($reportYear < $delDate['year']) {//ofcourse not deleted
							if (($reportYear - 9) <= $regYear) //less than 10 years
								$cost = ($row['cost'] * $row['num-of-equips']) / 120;
							else
								$cost = 1;
						}
						elseif ($reportYear == $delDate['year']) { //same year!
							$deleteDate = $delDate['year'] . $delDate['month'];
							$date = $inRangeHygieneUnitNonConsumingChargesWithMonth[$i]['year'] . $inRangeHygieneUnitNonConsumingChargesWithMonth[$i]['month'];
							if($date <= $deleteDate) {
								if (($reportYear - 9) <= $regYear) //less than 10 years
									$cost = ($row['cost'] * $row['num-of-equips']) / 120;
								else
									$cost = 1;
							}
							else //after delete
								$cost = 0;
						}
					}
					else {
						if (($reportYear - 9) <= $regYear) //less than 10 years
							$cost = ($row['cost'] * $row['num-of-equips']) / 120;
						else
							$cost = 1;
					}
				}
				$monthCost += $cost;
			}//end foreach
			$inRangeHygieneUnitNonConsumingChargesWithMonth[$i]['totalCost'] += $monthCost;
		}//end for

		return $inRangeHygieneUnitNonConsumingChargesWithMonth;
	}

	//USD
	public static function getHygieneUnitBuildingsCharge($hygieneUnitId, $fromDay, $fromMonth, $fromYear, $toDay, $toMonth, $toYear) {
		$inRangeHygieneUnitBuildingsWorthMappedWithMonth = array();
		//for buildings owned by hygiene unit
		$inRangeHygieneUnitBuildingsMaintainChargesWithMonth = array();
		//mapped to $inRangeHygieneUnitBuildingsMaintainChargesMappedWithMonth
		$totalBuildingsRentCostPerMonth = 0;
		$totalMappedResult = array();

		$datePeriodArray = self::createDatePeriodArray($fromDay, $fromMonth, $fromYear, $toDay, $toMonth, $toYear);
		foreach ($datePeriodArray as $dateItem)//initializing
			$inRangeHygieneUnitBuildingsWorthMappedWithMonth[] = array('year' => $dateItem['year'], 'month' => $dateItem['month'], 'cost' => 0);

		$res = Database::execute_query("SELECT `ownership-type`, `rent-cost`, `building-worth`, `built-date` FROM `hygiene-unit-buildings` WHERE `hygiene-unit-id` = '" . $hygieneUnitId . "';");
		while ($row = Database::get_assoc_array($res)) {
			if ($row['ownership-type'] == 0) {//melki
				//trying to calculate the difference between builtDate and reportDate
				$builtDate = date_parse($row['built-date']);
				$greg_built_date = jalali_to_gregorian($builtDate['year'], $builtDate['month'], $builtDate['day']);
				$building_build_date = Date(mktime(0, 0, 0, $greg_built_date['1'], $greg_built_date['2'], $greg_built_date['0']));
				foreach ($datePeriodArray as $dateItem) {
					$greg_now_date = jalali_to_gregorian($dateItem['year'], $dateItem['month'], $builtDate['day']);
					$date = Date(mktime(0, 0, 0, $greg_now_date['1'], $greg_now_date['2'], $greg_now_date['0']));
					$from_to_gap = self::count_days($date, $building_build_date);
					//calculationg leap years
					$leapYears = 0;
					for ($year = $builtDate['year']; $year <= $dateItem['year']; $year++) {
						if (isKabise($year))
							$leapYears++;
					}
					if ($from_to_gap > (5475 + $leapYears + 1)) {//5475 = 15 * 365
						for ($i = 0; $i < count($inRangeHygieneUnitBuildingsWorthMappedWithMonth); $i++) {
							if (($inRangeHygieneUnitBuildingsWorthMappedWithMonth[$i]['year'] == $dateItem['year']) and ($inRangeHygieneUnitBuildingsWorthMappedWithMonth[$i]['month'] == $dateItem['month']))
								$inRangeHygieneUnitBuildingsWorthMappedWithMonth[$i]['cost'] += 1;
						}
					} else if ($from_to_gap <= (5475 + $leapYears + 1) and $from_to_gap > 0) {
						for ($i = 0; $i < count($inRangeHygieneUnitBuildingsWorthMappedWithMonth); $i++) {
							if (($inRangeHygieneUnitBuildingsWorthMappedWithMonth[$i]['year'] == $dateItem['year']) and ($inRangeHygieneUnitBuildingsWorthMappedWithMonth[$i]['month'] == $dateItem['month']))
								$inRangeHygieneUnitBuildingsWorthMappedWithMonth[$i]['cost'] += ($row['building-worth'] / 180);
						}
					}
				}
			} else if ($row['ownership-type'] == 1)//estijari
				$totalBuildingsRentCostPerMonth += $row['rent-cost'];
		}

		//building maintain charges
		$res = Database::execute_query("SELECT `b`.`charge-amount`, `b`.`date` FROM `hygiene-unit-buildings` AS `a` INNER JOIN `hygiene-unit-building-maintain-charges` AS `b` ON `a`.`id` = `b`.`building-id` WHERE `a`.`hygiene-unit-id` = '" . $hygieneUnitId . "';");
		while ($row = Database::get_assoc_array($res)) {
			$chargeDate = date_parse($row['date']);
			if (self::isInDateRange($chargeDate['day'], $chargeDate['month'], $chargeDate['year'], $fromDay, $fromMonth, $fromYear, $toDay, $toMonth, $toYear) === true) {
				$inRangeHygieneUnitBuildingsMaintainChargesWithMonth[] = array('year' => $chargeDate['year'], 'month' => $chargeDate['month'], 'cost' => $row['charge-amount']);
			} else {
				//nothing todo yet
			}
		}

		//mapping month with cost arrays to an in range arrays. Numerical names described at the start of function.
		$inRangeHygieneUnitBuildingsMaintainChargesMappedWithMonth = array();
		foreach ($datePeriodArray as $dateItem) {
			$tmpSum = 0;
			foreach ($inRangeHygieneUnitBuildingsMaintainChargesWithMonth as $chargeItem) {
				if (($dateItem['year'] == $chargeItem['year']) and ($dateItem['month'] == $chargeItem['month']))
					$tmpSum += $chargeItem['cost'];
			}
			$inRangeHygieneUnitBuildingsMaintainChargesMappedWithMonth[] = array('year' => $dateItem['year'], 'month' => $dateItem['month'], 'cost' => $tmpSum);
		}

		for ($i = 0; $i < count($datePeriodArray); $i++) {
			$totalMappedResult[] = array('year' => $datePeriodArray[$i]['year'], 'month' => $datePeriodArray[$i]['month'], 'totalCost' => 0);
			$totalMappedResult[$i]['totalCost'] += $inRangeHygieneUnitBuildingsWorthMappedWithMonth[$i]['cost'];
			$totalMappedResult[$i]['totalCost'] += $inRangeHygieneUnitBuildingsMaintainChargesMappedWithMonth[$i]['cost'];
			$totalMappedResult[$i]['totalCost'] += $totalBuildingsRentCostPerMonth;
		}

		return $totalMappedResult;
	}

	//USD
	public static function getHygieneUnitVehiclesCharge($hygieneUnitId, $fromDay, $fromMonth, $fromYear, $toDay, $toMonth, $toYear) {
		$inRangeHygieneUnitVehiclesWorthMappedWithMonth = array();
		$inRangeHygieneUnitVehiclesMaintainChargesWithMonth = array();
		$totalVehiclesRentCostPerMonth = 0;
		//mapped to $inRangeHygieneUnitVehiclesMaintainChargesMappedWithMonth
		$totalMappedResult = array();

		$datePeriodArray = self::createDatePeriodArray($fromDay, $fromMonth, $fromYear, $toDay, $toMonth, $toYear);
		foreach ($datePeriodArray as $dateItem)//initializing
			$inRangeHygieneUnitVehiclesWorthMappedWithMonth[] = array('year' => $dateItem['year'], 'month' => $dateItem['month'], 'cost' => 0);

		$res = Database::execute_query("SELECT `ownership-type`, `rent-cost`, `vehicle-worth`, `buy-date` FROM `hygiene-unit-vehicles` WHERE `hygiene-unit-id` = '" . $hygieneUnitId . "';");
		while ($row = Database::get_assoc_array($res)) {
			if ($row['ownership-type'] == 0) {//gov
				//trying to calculate the difference between buyDate and reportDate
				$buyDate = date_parse($row['buy-date']);
				$greg_buy_date = jalali_to_gregorian($buyDate['year'], $buyDate['month'], $buyDate['day']);
				$vehicle_buy_date = Date(mktime(0, 0, 0, $greg_buy_date['1'], $greg_buy_date['2'], $greg_buy_date['0']));
				foreach ($datePeriodArray as $dateItem) {
					$greg_now_date = jalali_to_gregorian($dateItem['year'], $dateItem['month'], $buyDate['day']);
					$date = Date(mktime(0, 0, 0, $greg_now_date['1'], $greg_now_date['2'], $greg_now_date['0']));
					$from_to_gap = self::count_days($date, $vehicle_buy_date);
					//calculationg leap years
					$leapYears = 0;
					for ($year = $buyDate['year']; $year <= $dateItem['year']; $year++) {
						if (isKabise($year))
							$leapYears++;
					}
					if ($from_to_gap > (3650 + $leapYears + 1)) {//3650 = 10 * 365
						for ($i = 0; $i < count($inRangeHygieneUnitVehiclesWorthMappedWithMonth); $i++) {
							if (($inRangeHygieneUnitVehiclesWorthMappedWithMonth[$i]['year'] == $dateItem['year']) and ($inRangeHygieneUnitVehiclesWorthMappedWithMonth[$i]['month'] == $dateItem['month']))
								$inRangeHygieneUnitVehiclesWorthMappedWithMonth[$i]['cost'] += 1;
						}
					} else if ($from_to_gap <= (3650 + $leapYears + 1) and $from_to_gap > 0) {
						for ($i = 0; $i < count($inRangeHygieneUnitVehiclesWorthMappedWithMonth); $i++) {
							if (($inRangeHygieneUnitVehiclesWorthMappedWithMonth[$i]['year'] == $dateItem['year']) and ($inRangeHygieneUnitVehiclesWorthMappedWithMonth[$i]['month'] == $dateItem['month']))
								$inRangeHygieneUnitVehiclesWorthMappedWithMonth[$i]['cost'] += ($row['vehicle-worth'] / 120);
						}
					}
				}
			} else if ($row['ownership-type'] == 1)//rent
				$totalVehiclesRentCostPerMonth += $row['rent-cost'];
		}

		//vehicle maintain charges
		$res = Database::execute_query("SELECT `b`.`charge-amount`, `b`.`date` FROM `hygiene-unit-vehicles` AS `a` INNER JOIN `hygiene-unit-vehicle-maintain-charges` AS `b` ON `a`.`id` = `b`.`vehicle-id` WHERE `a`.`hygiene-unit-id` = '" . $hygieneUnitId . "';");
		while ($row = Database::get_assoc_array($res)) {
			$chargeDate = date_parse($row['date']);
			if (self::isInDateRange($chargeDate['day'], $chargeDate['month'], $chargeDate['year'], $fromDay, $fromMonth, $fromYear, $toDay, $toMonth, $toYear) === true) {
				$inRangeHygieneUnitVehiclesMaintainChargesWithMonth[] = array('year' => $chargeDate['year'], 'month' => $chargeDate['month'], 'cost' => $row['charge-amount']);
			} else {
				//nothing todo yet
			}
		}

		//mapping month with cost arrays to an in range arrays. Numerical names described at the start of function.
		$inRangeHygieneUnitVehiclesMaintainChargesMappedWithMonth = array();
		foreach ($datePeriodArray as $dateItem) {
			$tmpSum = 0;
			foreach ($inRangeHygieneUnitVehiclesMaintainChargesWithMonth as $chargeItem) {
				if (($dateItem['year'] == $chargeItem['year']) and ($dateItem['month'] == $chargeItem['month']))
					$tmpSum += $chargeItem['cost'];
			}
			$inRangeHygieneUnitVehiclesMaintainChargesMappedWithMonth[] = array('year' => $dateItem['year'], 'month' => $dateItem['month'], 'cost' => $tmpSum);
		}

		for ($i = 0; $i < count($datePeriodArray); $i++) {
			$totalMappedResult[] = array('year' => $datePeriodArray[$i]['year'], 'month' => $datePeriodArray[$i]['month'], 'totalCost' => 0);
			$totalMappedResult[$i]['totalCost'] += $inRangeHygieneUnitVehiclesWorthMappedWithMonth[$i]['cost'];
			$totalMappedResult[$i]['totalCost'] += $inRangeHygieneUnitVehiclesMaintainChargesMappedWithMonth[$i]['cost'];
			$totalMappedResult[$i]['totalCost'] += $totalVehiclesRentCostPerMonth;
		}

		return $totalMappedResult;
	}

	//USD
	public static function getHygieneUnitGeneralCharges($hygieneUnitId, $fromDay, $fromMonth, $fromYear, $toDay, $toMonth, $toYear) {
		$inRangeHygieneUnitGeneralChargesWithMonth = array();
		$totalMappedResult = array();

		//hygieneUnit general charges
		$res = Database::execute_query("SELECT `charge-amount`, `date` FROM `hygiene-unit-general-charges` WHERE `hygiene-unit-id` = '" . $hygieneUnitId . "';");
		while ($row = Database::get_assoc_array($res)) {
			$chargeDate = date_parse($row['date']);
			if (self::isInDateRange($chargeDate['day'], $chargeDate['month'], $chargeDate['year'], $fromDay, $fromMonth, $fromYear, $toDay, $toMonth, $toYear) === true) {
				$inRangeHygieneUnitGeneralChargesWithMonth[] = array('year' => $chargeDate['year'], 'month' => $chargeDate['month'], 'cost' => $row['charge-amount']);
			} else {
				//nothing todo yet
			}
		}

		$datePeriodArray = self::createDatePeriodArray($fromDay, $fromMonth, $fromYear, $toDay, $toMonth, $toYear);

		//mapping date with cost arrays to an in range arrays.
		foreach ($datePeriodArray as $dateItem) {
			$tmpSum = 1;
			foreach ($inRangeHygieneUnitGeneralChargesWithMonth as $chargeItem) {
				if (($dateItem['year'] == $chargeItem['year']) and ($dateItem['month'] == $chargeItem['month']))
					$tmpSum += $chargeItem['cost'];
			}
			$totalMappedResult[] = array('year' => $dateItem['year'], 'month' => $dateItem['month'], 'totalCost' => $tmpSum);
		}

		return $totalMappedResult;
	}

	//USD
	public static function getHygieneUnitBehvarzChrages($hygieneUnitId, $fromDay, $fromMonth, $fromYear, $toDay, $toMonth, $toYear) {
		$inRangeChargesWithMonth = array();
		$baseSalaris = array();
		$reportYear = $fromYear = $toYear;
		$totalMappedResult = array();

		//trying to sum unit personnel financials except base salary
		$res = Database::execute_query("SELECT `b`.`charge-cost`, `b`.`date` FROM `hygiene-unit-personnels` AS `a` INNER JOIN `hygiene-unit-personnel-financial` AS `b` ON `a`.`id` = `b`.`personnel-id` WHERE `a`.`hygiene-unit-id` = '" . $hygieneUnitId . "';");
		while ($row = Database::get_assoc_array($res)) {
			$chargeDate = date_parse($row['date']);
			if (self::isInDateRange($chargeDate['day'], $chargeDate['month'], $chargeDate['year'], $fromDay, $fromMonth, $fromYear, $toDay, $toMonth, $toYear) === true) {
				$inRangeChargesWithMonth[] = array('year' => $chargeDate['year'], 'month' => $chargeDate['month'], 'cost' => $row['charge-cost']);
			} else {
			}
		}

		$res = Database::execute_query("SELECT `base-salary`, `deleted`, `date-of-delete` FROM `hygiene-unit-personnels` WHERE `hygiene-unit-id` = '" . $hygieneUnitId . "';");
		while ($row = Database::get_assoc_array($res))
			$baseSalaris[] = $row;

		$datePeriodArray = self::createDatePeriodArray($fromDay, $fromMonth, $fromYear, $toDay, $toMonth, $toYear);

		//mapping date with cost arrays to an in range arrays.
		foreach ($datePeriodArray as $dateItem) {
			$tmpSum = 0;
			foreach ($inRangeChargesWithMonth as $chargeItem) {
				if (($dateItem['year'] == $chargeItem['year']) and ($dateItem['month'] == $chargeItem['month']))
					$tmpSum += $chargeItem['cost'];
			}
			$currentMonthSalary = 0;
			foreach ($baseSalaris as $salaryItem) {
				if($salaryItem['deleted']) {
					$delDate = date_parse($salaryItem['date-of-delete']);
					$deleteDate = $delDate['year'] . $delDate['month'];
					$date = $dateItem['year'] . $dateItem['month'];
					if($reportYear > $delDate['year'])//ofcourse deleted
						continue;
					else if($reportYear < $delDate['year']) //ofcourse not deleted
						$currentMonthSalary += ($salaryItem['base-salary'] / 12);
					elseif ($reportYear == $delDate['year']) { //same year!
						if($date <= $deleteDate)
							$currentMonthSalary += ($salaryItem['base-salary'] / 12);
						else //after delete
							$currentMonthSalary = 0;
					}
				}
				else
					$currentMonthSalary += ($salaryItem['base-salary'] / 12);
			}
			$totalMappedResult[] = array('year' => $dateItem['year'], 'month' => $dateItem['month'], 'totalCost' => ($tmpSum + $currentMonthSalary));
		}

		return $totalMappedResult;
	}

	/*------------------------------------------------------------------------------------------------------------------------*/

	//USD
	public static function getTownTotalCharges($townId, $fromDay, $fromMonth, $fromYear, $toDay, $toMonth, $toYear) {
		$totalTownMappedCharges = array();
		$datePeriodArray = self::createDatePeriodArray($fromDay, $fromMonth, $fromYear, $toDay, $toMonth, $toYear);

		//initializing
		foreach ($datePeriodArray as $value)
			$totalTownMappedCharges[] = array('year' => $value['year'], 'month' => $value['month'], 'totalCost' => 0);

		//fetch centers charges
		$res = Database::execute_query("SELECT `id` FROM `centers` WHERE `town-id` = '$townId';");
		while ($row = Database::get_assoc_array($res)) {
			$tmpCharge = self::getCenterTotalCharges($row['id'], $fromDay, $fromMonth, $fromYear, $toDay, $toMonth, $toYear);
			for ($i = 0; $i < count($totalTownMappedCharges); $i++)
				$totalTownMappedCharges[$i]['totalCost'] += $tmpCharge[$i]['totalCost'];
		}

		//fetch hygiene units charges
		$res = Database::execute_query("SELECT `id` FROM `hygiene-units` WHERE `center-id` = '$townId';");
		while ($row = Database::get_assoc_array($res)) {
			$tmpCharge = self::getHygieneUnitTotalCharges($row['id'], $fromDay, $fromMonth, $fromYear, $toDay, $toMonth, $toYear);
			for ($i = 0; $i < count($totalTownMappedCharges); $i++) {
				$totalTownMappedCharges[$i]['totalCost'] += $tmpCharge[$i]['totalCost'];
			}
		}

		return $totalTownMappedCharges;
	}

	//USD
	public static function createDatePeriodArray($fromDay, $fromMonth, $fromYear, $toDay, $toMonth, $toYear) {
		//supposed that 2 dates have at least 60 days difference
		$resultDateArray = array();
		$tmpMonth = $fromMonth;
		if ($fromYear == $toYear) {
			while ($tmpMonth <= $toMonth) {
				$resultDateArray[] = array('year' => $fromYear, 'month' => $tmpMonth);
				$tmpMonth++;
			}
		} else {
			$tmpYear = $fromYear;
			while (true) {
				$resultDateArray[] = array('year' => $tmpYear, 'month' => $tmpMonth);
				if ($tmpMonth == 12) {
					$tmpMonth = 1;
					if ($tmpYear < $toYear)
						$tmpYear++;
					else
						break;
				} else
					$tmpMonth++;
				if (($tmpYear == $toYear) and ($tmpMonth > $toMonth))
					break;
			}
		}

		return $resultDateArray;
	}

	//USD
	public static function isInDateRange($day, $month, $year, $rangeStartD, $rangeStartM, $rangeStartY, $rangeFinishD, $rangeFinishM, $rangeFinishY) {
		$greg_item_date = jalali_to_gregorian($year, $month, $day);
		$greg_start_date = jalali_to_gregorian($rangeStartY, $rangeStartM, $rangeStartD);
		$greg_finish_date = jalali_to_gregorian($rangeFinishY, $rangeFinishM, $rangeFinishD);
		$item_date = mktime(0, 0, 0, $greg_item_date['1'], $greg_item_date['2'], $greg_item_date['0']);
		$start_date = mktime(0, 0, 0, $greg_start_date['1'], $greg_start_date['2'], $greg_start_date['0']);
		$finish_date = mktime(0, 0, 0, $greg_finish_date['1'], $greg_finish_date['2'], $greg_finish_date['0']);

		if ((self::count_days($item_date, $start_date) >= 0) and (self::count_days($finish_date, $item_date) >= 0))
			return true;

		return false;
	}

	//USD
	private static function count_days($a, $b) {
		$gd_a = getdate($a);
		$gd_b = getdate($b);
		$a_new = mktime(12, 0, 0, $gd_a['mon'], $gd_a['mday'], $gd_a['year']);
		$b_new = mktime(12, 0, 0, $gd_b['mon'], $gd_b['mday'], $gd_b['year']);

		return (round(($a_new - $b_new) / 86400) + 1);
	}

	//USD
	public static function drawFirstTypeChart() {
		$htmlOut = '';
		$date_period_from_day = $_POST['start_date:d'];
		$date_period_from_month = $_POST['start_date:m'];
		$date_period_from_year = $_POST['start_date:y'];

		$date_period_to_day = $_POST['finish_date:d'];
		$date_period_to_month = $_POST['finish_date:m'];
		$date_period_to_year = $_POST['finish_date:y'];

		$greg_from_date = jalali_to_gregorian($date_period_from_year, $date_period_from_month, $date_period_from_day);
		$greg_to_date = jalali_to_gregorian($date_period_to_year, $date_period_to_month, $date_period_to_day);
		$from_date = mktime(0, 0, 0, $greg_from_date['1'], $greg_from_date['2'], $greg_from_date['0']);
		$to_date = mktime(0, 0, 0, $greg_to_date['1'], $greg_to_date['2'], $greg_to_date['0']);

		$from_to_gap = self::count_days($to_date, $from_date);
		if ($from_to_gap <= 60)
			return '<p id="err">خطا در انتخاب بازه زمانی.<br />فاصله ی دو تاریخ باید حداقل 60 روز باشد.</p>';

		$townsId = array();
		$centersId = array();
		$centersChargeTypes = array();
		$hygieneUnitsId = array();
		$hygieneUnitsChargeTypes = array();
		$unitsId = array();
		$seriesId = array();
		$series = array();
		$datePeriodArray = self::createDatePeriodArray($date_period_from_day, $date_period_from_month, $date_period_from_year, $date_period_to_day, $date_period_to_month, $date_period_to_year);

		foreach ($_POST as $key => $value) {
			$fieldName = explode('_', $key);
			switch ($fieldName['0']) {
				case 'townNameForTown' :
					$townsId[] = $value;
					break;
				case 'centerNameForCenter' :
					$centersId[] = $value;
					break;
				case 'centerChargeTypes' :
					$centersChargeTypes[] = $value;
					break;
				case 'hygieneUnitName' :
					$hygieneUnitsId[] = $value;
					break;
				case 'hygieneUnitChargeTypes' :
					$hygieneUnitsChargeTypes[] = $value;
					break;
				case 'unitName' :
					$unitsId[] = $value;
					break;
			}
		}

		if ((count($townsId) + count($centersId) + count($hygieneUnitsId) + count($unitsId)) == 0)
			return '<p id="err">خطا: هیچ منبعی برای گزارش انتخاب نشده است.</p>';

		$townsCharges = array();
		if (count($townsId) > 0) {
			foreach ($townsId as $value) {
				$townCharge = self::getTownTotalCharges($value, $date_period_from_day, $date_period_from_month, $date_period_from_year, $date_period_to_day, $date_period_to_month, $date_period_to_year);
				//round values
				for ($i = 0; $i < count($townCharge); $i++)
					$townCharge[$i]['totalCost'] = round($townCharge[$i]['totalCost'] / 1000);
				$townsCharges[] = array('townId' => $value, 'townCharge' => $townCharge);
			}
		}


		$centersCharges = array();
		if (count($centersId) > 0) {
			for ($i = 0; $i < count($centersId); $i++) {
				$centerCharge = array();
				//initializing
				foreach ($datePeriodArray as $value)
					$centerCharge[] = array('year' => $value['year'], 'month' => $value['month'], 'totalCost' => 0);

				foreach ($centersChargeTypes[$i] as $value) {
					$tmpCenterCharge = self::getCenterCustomCharge($centersId[$i], $value, $date_period_from_day, $date_period_from_month, $date_period_from_year, $date_period_to_day, $date_period_to_month, $date_period_to_year);
					for ($j = 0; $j < count($centerCharge); $j++)
						$centerCharge[$j]['totalCost'] += $tmpCenterCharge[$j]['totalCost'];
				}
				//round values
				for ($T = 0; $T < count($centerCharge); $T++)
					$centerCharge[$T]['totalCost'] = round($centerCharge[$T]['totalCost'] / 1000);

				$centersCharges[] = array('centerId' => $centersId[$i], 'centerCharge' => $centerCharge);
			}
		}

		$hygieneUnitsCharges = array();
		if (count($hygieneUnitsId) > 0) {
			for ($i = 0; $i < count($hygieneUnitsId); $i++) {
				$hygieneUnitCharge = array();
				//initializing
				foreach ($datePeriodArray as $value)
					$hygieneUnitCharge[] = array('year' => $value['year'], 'month' => $value['month'], 'totalCost' => 0);
				foreach ($hygieneUnitsChargeTypes[$i] as $value) {
					$tmpHygieneUnitCharge = self::getHygieneUnitCustomCharge($hygieneUnitsId[$i], $value, $date_period_from_day, $date_period_from_month, $date_period_from_year, $date_period_to_day, $date_period_to_month, $date_period_to_year);
					for ($j = 0; $j < count($hygieneUnitCharge); $j++)
						$hygieneUnitCharge[$j]['totalCost'] += $tmpHygieneUnitCharge[$j]['totalCost'];
				}
				//round values
				for ($T = 0; $T < count($hygieneUnitCharge); $T++)
					$hygieneUnitCharge[$T]['totalCost'] = round($hygieneUnitCharge[$T]['totalCost'] / 1000);
				$hygieneUnitsCharges[] = array('hygieneUnitId' => $hygieneUnitsId[$i], 'hygieneUnitCharge' => $hygieneUnitCharge);
			}
		}

		$unitsCharges = array();
		if (count($unitsId) > 0) {
			foreach ($unitsId as $value) {
				$unitCharge = self::getTotalUnitChrages($value, $date_period_from_day, $date_period_from_month, $date_period_from_year, $date_period_to_day, $date_period_to_month, $date_period_to_year);
				//round values
				for ($T = 0; $T < count($unitCharge); $T++)
					$unitCharge[$T]['totalCost'] = round($unitCharge[$T]['totalCost'] / 1000);
				$unitsCharges[] = array('unitId' => $value, 'unitCharge' => $unitCharge);
			}
		}

		$htmlOut .= '<p><input type="button" value="چاپ نمودار" onclick="alert(\'لطفا مد Landscape را برای پرینت انتخاب کنید.\');window.print();return false;"></p>';
		$htmlOut .= '<br /><div id="chartDiv"></div>';
		$htmlOut .= '<script type="text/javascript" src="../illustrator/js/jquery.jqplot.min.js"></script>';
		$htmlOut .= '<script type="text/javascript" src="../illustrator/js/jqplot.canvasTextRenderer.min.js"></script>';
		$htmlOut .= '<script type="text/javascript" src="../illustrator/js/jqplot.canvasAxisLabelRenderer.min.js"></script>';
		$htmlOut .= '<script type="text/javascript" src="../illustrator/js/jqplot.cursor.min.js"></script>';
		$htmlOut .= '<script type="text/javascript" src="../illustrator/js/jqplot.highlighter.min.js"></script>';
		$htmlOut .= '<script type="text/javascript">';
		$htmlOut .= 'var ticks = [';
		for ($i = 0; $i < count($datePeriodArray); $i++) {
			$htmlOut .= '[' . ($i + 1) . ', "' . $datePeriodArray[$i]['year'] . '/' . $datePeriodArray[$i]['month'] . '"]';
			if ($i != (count($datePeriodArray) - 1))
				$htmlOut .= ',';
		}
		$htmlOut .= '];';
		$htmlOut .= 'var plot = $.jqplot (\'chartDiv\', [';
		$tmp = '';

		if (count($townsCharges) > 0) {
			for ($i = 0; $i < count($townsCharges); $i++) {
				$seriesId[] = $townsCharges[$i]['townId'];
				$tmp .= '[';
				for ($j = 0; $j < count($townsCharges[$i]['townCharge']); $j++) {
					$tmp .= $townsCharges[$i]['townCharge'][$j]['totalCost'];
					if ($j != (count($townsCharges[$i]['townCharge']) - 1))
						$tmp .= ', ';
				}
				$tmp .= ']';
				if ($i != (count($townsCharges) - 1))
					$tmp .= ', ';
			}
			$tmp .= ', ';
			foreach ($seriesId as $value) {
				$res = Database::execute_query("SELECT `name` FROM `towns` WHERE `id` = '$value';");
				$row = Database::get_assoc_array($res);
				$series[] = $row['name'];
			}
			$seriesId = array();
		}

		if (count($centersCharges) > 0) {
			for ($i = 0; $i < count($centersCharges); $i++) {
				$seriesId[] = $centersCharges[$i]['centerId'];
				$tmp .= '[';
				for ($j = 0; $j < count($centersCharges[$i]['centerCharge']); $j++) {
					$tmp .= $centersCharges[$i]['centerCharge'][$j]['totalCost'];
					if ($j != (count($centersCharges[$i]['centerCharge']) - 1))
						$tmp .= ', ';
				}
				$tmp .= ']';
				if ($i != (count($centersCharges) - 1))
					$tmp .= ', ';
			}
			$tmp .= ', ';
			foreach ($seriesId as $value) {
				$res = Database::execute_query("SELECT `name` FROM `centers` WHERE `id` = '$value';");
				$row = Database::get_assoc_array($res);
				$series[] = $row['name'];
			}
			$seriesId = array();
		}

		if (count($hygieneUnitsCharges) > 0) {
			for ($i = 0; $i < count($hygieneUnitsCharges); $i++) {
				$seriesId[] = $hygieneUnitsCharges[$i]['hygieneUnitId'];
				$tmp .= '[';
				for ($j = 0; $j < count($hygieneUnitsCharges[$i]['hygieneUnitCharge']); $j++) {
					$tmp .= $hygieneUnitsCharges[$i]['hygieneUnitCharge'][$j]['totalCost'];
					if ($j != (count($hygieneUnitsCharges[$i]['hygieneUnitCharge']) - 1))
						$tmp .= ', ';
				}
				$tmp .= ']';
				if ($i != (count($hygieneUnitsCharges) - 1))
					$tmp .= ', ';
			}
			$tmp .= ', ';
			foreach ($seriesId as $value) {
				$res = Database::execute_query("SELECT `name` FROM `hygiene-units` WHERE `id` = '$value';");
				$row = Database::get_assoc_array($res);
				$series[] = $row['name'];
			}
			$seriesId = array();
		}

		if (count($unitsCharges) > 0) {
			for ($i = 0; $i < count($unitsCharges); $i++) {
				$seriesId[] = $unitsCharges[$i]['unitId'];
				$tmp .= '[';
				for ($j = 0; $j < count($unitsCharges[$i]['unitCharge']); $j++) {
					$tmp .= $unitsCharges[$i]['unitCharge'][$j]['totalCost'];
					if ($j != (count($unitsCharges[$i]['unitCharge']) - 1))
						$tmp .= ', ';
				}
				$tmp .= ']';
				if ($i != (count($unitsCharges) - 1))
					$tmp .= ', ';
			}
			foreach ($seriesId as $value) {
				$res = Database::execute_query("SELECT `name` FROM `units` WHERE `id` = '$value';");
				$row = Database::get_assoc_array($res);
				$series[] = $row['name'];
			}
			$seriesId = array();
		}
		$htmlOut .= $tmp;

		$tmp = '';
		$tmp .= '], {
				animate: true,animateReplot: true,
				title: \'نمودار مقایسه ی هزینه ها از تاریخ ' . $date_period_from_year . '/' . $date_period_from_month . '/' . $date_period_from_day . ' تا تاریخ ' . $date_period_to_year . '/' . $date_period_to_month . '/' . $date_period_to_day . '\',legend: {show: true, location: \'ne\', placement: \'inside\'},axesDefaults: {labelRenderer: $.jqplot.CanvasAxisLabelRenderer},axes: { xaxis: {ticks: ticks, label: \'ماه های انتخابی\'}, yaxis: {min: 0, label: \'مبلغ هزینه(ريال)\'} },cursor: {style: \'crosshair\', show: true, showTooltip: true},highlighter: {showTooltip: true, show: true, showLabel: true, fadeTooltip: true},grid: {drawGridLines: true, gridLineColor: \'#CCC\'},seriesDefaults: {shadow: true, showMarker: true},
				series: [';
		foreach ($series as $value) {
			$tmp .= '{label: \'' . $value . '\'}, ';
		}
		$tmp .= ']';
		$tmp .= '});';
		$htmlOut .= $tmp;
		$htmlOut .= '$(\'.jqplot-highlighter-tooltip\').addClass(\'ui-corner-all\');';
		$htmlOut .= '</script>';

		$allElementsCharges = array_merge($townsCharges, $centersCharges, $hygieneUnitsCharges, $unitsCharges);

		$tmpArray = array();
		foreach ($allElementsCharges as $value) {
			if (isset($value['townCharge']))
				$tmpArray[] = $value['townCharge'];
			if (isset($value['centerCharge']))
				$tmpArray[] = $value['centerCharge'];
			if (isset($value['hygieneUnitCharge']))
				$tmpArray[] = $value['hygieneUnitCharge'];
			if (isset($value['unitCharge']))
				$tmpArray[] = $value['unitCharge'];
		}
		
		$allElementsCharges = $tmpArray;
		$tmpArray = array();
		$htmlOut .= '<p style="text-align: center; font-size: large; "><a href="#" onclick="$(\'#reportDescription\').slideToggle(500);return false;" style="text-decoration: underline;"><img src="../illustrator/images/icons/1.png" style="height: 40px; width: 40px; vertical-align: middle;"> جزئیات</a></p>';
		$htmlOut .= '<div id="reportDescription"><fieldset><legend>جزئیات آماری نمودار</legend>';
		$maxCharges = array();
		$minCharges = array();
		$averageCharges = array();
		for ($i = 0; $i < count($series); $i++) {
			$max = $allElementsCharges[$i]['0'];
			$min = $allElementsCharges[$i]['0'];
			$sum = 0;
			for ($j = 0; $j < count($allElementsCharges[$i]); $j++) {
				if ($allElementsCharges[$i][$j]['totalCost'] > $max['totalCost'])
					$max = $allElementsCharges[$i][$j];
				if ($allElementsCharges[$i][$j]['totalCost'] < $min['totalCost'])
					$min = $allElementsCharges[$i][$j];
				$sum += $allElementsCharges[$i][$j]['totalCost'];
			}
			$average = $sum / count($allElementsCharges[$i]);
			$maxCharges[] = array('name' => $series[$i], 'max' => $max);
			$minCharges[] = array('name' => $series[$i], 'min' => $min);
			$averageCharges[] = array('name' => $series[$i], 'average' => $average);
			//preparing data for variance function
			$varArray = array();
			foreach ($allElementsCharges[$i] as $value)
				$varArray[] = $value['totalCost'];
			$variance = self::variance($varArray);

			$htmlOut .= '<br /><fieldset style="width: 520px;"><legend>نمودار "' . $series[$i] . '"</legend>';
			$htmlOut .= '<p><span style="font-weight: bold;">حداکثر هزینه در تاریخ: </span>"' . $max['year'] . '/' . $max['month'] . '"<span style="font-weight: bold;"> به مبلغ: </span>' . round($max['totalCost']) . ' هزار ريال؛</p>';
			$htmlOut .= '<p><span style="font-weight: bold;">حداقل هزینه در تاریخ: </span>"' . $min['year'] . '/' . $min['month'] . '"<span style="font-weight: bold;"> به مبلغ: </span>' . round($min['totalCost']) . ' هزار ريال؛</p>';
			$htmlOut .= '<p><span style="font-weight: bold;">میانگین هزینه ها: </span>' . round($average) . ' هزار ريال؛</p>';
			$htmlOut .= '<p><span style="font-weight: bold;">واریانس هزینه ها: </span>' . round($variance) . '</p>';
			$htmlOut .= '<p><span style="font-weight: bold;">انحراف معیار: </span>' . round(sqrt($variance)) . '</p>';
			$htmlOut .= '</fieldset>';
		}

		//calculating totals
		$totalMax = $maxCharges['0'];
		$totalMin = $minCharges['0'];

		//calculating total min and total max; maxCharges and minCharges have same length
		for ($c = 0; $c < count($maxCharges); $c++) {
			if ($maxCharges[$c]['max']['totalCost'] > $totalMax['max']['totalCost'])
				$totalMax = $maxCharges[$c];
			if ($minCharges[$c]['min']['totalCost'] < $totalMin['min']['totalCost'])
				$totalMin = $minCharges[$c];
		}
		//calculatin total average
		$sumOfAverages = 0;
		foreach ($averageCharges as $value) {
			$sumOfAverages += $value['average'];
		}
		$totalAverage = $sumOfAverages / count($averageCharges);

		$totalVarArr = array();
		//calculating total variance
		foreach ($allElementsCharges as $elements) {
			foreach ($elements as $value)
				$totalVarArr[] = $value['totalCost'];
		}
		$variance = self::variance($totalVarArr);

		$htmlOut .= '<br /><fieldset style="width: 520px;"><legend>کل نمودارها</legend>';
		$htmlOut .= '<p><span style="font-weight: bold;">حداکثر هزینه در تاریخ: </span>"' . $totalMax['max']['year'] . '/' . $totalMax['max']['month'] . '"<span style="font-weight: bold;"> به مبلغ: </span>' . round($totalMax['max']['totalCost']) . ' هزار ريال؛ <span style="font-weight: bold;">در:</span> ' . $totalMax['name'] . '</p>';
		$htmlOut .= '<p><span style="font-weight: bold;">حداقل هزینه در تاریخ: </span>"' . $totalMin['min']['year'] . '/' . $totalMin['min']['month'] . '"<span style="font-weight: bold;"> به مبلغ: </span>' . round($totalMin['min']['totalCost']) . ' هزار ريال؛ <span style="font-weight: bold;">در:</span> ' . $totalMin['name'] . '</p>';
		$htmlOut .= '<p><span style="font-weight: bold;">میانگین هزینه ها: </span>' . round($totalAverage) . ' هزار ريال؛</p>';
		$htmlOut .= '<p><span style="font-weight: bold;">واریانس هزینه ها: </span>' . round($variance) . '</p>';
		$htmlOut .= '<p><span style="font-weight: bold;">انحراف معیار: </span>' . round(sqrt($variance)) . '</p>';
		$htmlOut .= '</fieldset>';
		$htmlOut .= '</fieldset></div>';

		if (count($centersId) > 0) {
			$htmlOut .= '<p style="text-align: center; font-size: large;"><a href="#" onclick="$(\'#unitDescription\').slideToggle(500);return false;" style="text-decoration: underline;"><img src="../illustrator/images/icons/2.png" style="height: 40px; width: 40px; vertical-align: middle;"> جزئیات هزینه های واحدهای مراکز انتخابی</a></p>';
			$htmlOut .= '<div id="unitDescription"><fieldset><legend>جزئیات هزینه های واحدهای مراکز انتخابی</legend>';
			foreach ($centersId as $value) {
				//trying to sum center charges
				$centerId = $value;
				$buildingsCharge = array();
				$vehiclesCharge = array();
				$generalCharge = array();
				$nonConsumingsCharge = array();
				$drugsCharge = array();
				$totalCenterChargeWithDate = array();
				$res = Database::execute_query("SELECT `id`, `name` FROM `units` WHERE `center-id` = '$centerId';");
				$numOfUnits = Database::num_of_rows($res);
				$buildingsCharge = self::getCenterBuildingsCharge($value, $date_period_from_day, $date_period_from_month, $date_period_from_year, $date_period_to_day, $date_period_to_month, $date_period_to_year);
				$vehiclesCharge = self::getCenterVehiclesCharge($value, $date_period_from_day, $date_period_from_month, $date_period_from_year, $date_period_to_day, $date_period_to_month, $date_period_to_year);
				$generalCharge = self::getCenterGeneralCharges($value, $date_period_from_day, $date_period_from_month, $date_period_from_year, $date_period_to_day, $date_period_to_month, $date_period_to_year);
				$nonConsumingsCharge = self::getCenterNonConsumingsCharge($value, $date_period_from_day, $date_period_from_month, $date_period_from_year, $date_period_to_day, $date_period_to_month, $date_period_to_year);
				$drugsCharge = self::getCenterDrugsCharge($value, $date_period_from_day, $date_period_from_month, $date_period_from_year, $date_period_to_day, $date_period_to_month, $date_period_to_year);
				for ($i = 0; $i < count($datePeriodArray); $i++) {
					$totalCenterChargeWithDate[] = array('year' => $datePeriodArray[$i]['year'], 'month' => $datePeriodArray[$i]['month'], 'totalCost' => 0);
					$totalCenterChargeWithDate[$i]['totalCost'] += $buildingsCharge[$i]['totalCost'];
					$totalCenterChargeWithDate[$i]['totalCost'] += $vehiclesCharge[$i]['totalCost'];
					$totalCenterChargeWithDate[$i]['totalCost'] += $generalCharge[$i]['totalCost'];
					$totalCenterChargeWithDate[$i]['totalCost'] += $nonConsumingsCharge[$i]['totalCost'];
					$totalCenterChargeWithDate[$i]['totalCost'] += $drugsCharge[$i]['totalCost'];
				}
				$totalCenterCharge = 0;
				foreach ($totalCenterChargeWithDate as $value)
					$totalCenterCharge += $value['totalCost'];
				$centerChargePerUnit = $totalCenterCharge / $numOfUnits;
				$overallUnitCharge = array();
				while ($row = Database::get_assoc_array($res)) {
					$unitChargesWithDate = self::getTotalUnitChrages($row['id'], $date_period_from_day, $date_period_from_month, $date_period_from_year, $date_period_to_day, $date_period_to_month, $date_period_to_year);
					$totalUnitCharge = 0;
					foreach ($unitChargesWithDate as $value)
						$totalUnitCharge += $value['totalCost'];
					$overallUnitCharge[] = array('id' => $row['id'], 'name' => $row['name'], 'charge' => ($totalUnitCharge + $centerChargePerUnit));
				}
				$overallUnitChargeWithTotalServiceTime = array();
				foreach ($overallUnitCharge as $value) {
					$inRangeUnitServicesFrequency = array();
					$servicesFrequencyRes = Database::execute_query("SELECT `a`.`id`, `a`.`name`, `a`.`cost`, `a`.`average-time`, `b`.`service-frequency`, `b`.`period-start-date`, `b`.`period-finish-date` FROM `available-services` AS `a` INNER JOIN `unit-done-services` AS `b` ON `a`.`id` = `b`.`service-id` WHERE `b`.`unit-id` = '" . $value['id'] . "' ORDER BY `name` ASC;");
					while ($row = Database::get_assoc_array($servicesFrequencyRes)) {
						$periodStartDate = date_parse($row['period-start-date']);
						$periodFinishDate = date_parse($row['period-finish-date']);
						if (self::isInDateRange($periodStartDate['day'], $periodStartDate['month'], $periodStartDate['year'], $date_period_from_day, $date_period_from_month, $date_period_from_year, $date_period_to_day, $date_period_to_month, $date_period_to_year) and self::isInDateRange($periodFinishDate['day'], $periodFinishDate['month'], $periodFinishDate['year'], $date_period_from_day, $date_period_from_month, $date_period_from_year, $date_period_to_day, $date_period_to_month, $date_period_to_year))
							$inRangeUnitServicesFrequency[] = array('id' => $row['id'], 'name' => $row['name'], 'cost' => $row['cost'], 'time' => $row['average-time'], 'freq' => $row['service-frequency']);
					}
					$totalUnitServiceTime = 0;
					if (count($inRangeUnitServicesFrequency) > 0) {
						foreach ($inRangeUnitServicesFrequency as $time)
							$totalUnitServiceTime += ($time['time'] * $time['freq']);
					}
					$overallUnitChargeWithTotalServiceTime[] = array('id' => $value['id'], 'name' => $value['name'], 'charge' => $value['charge'], 'totalServiceTime' => $totalUnitServiceTime);
				}

				//round values
				for ($T = 0; $T < count($overallUnitChargeWithTotalServiceTime); $T++)
					$overallUnitChargeWithTotalServiceTime[$T]['charge'] = round($overallUnitChargeWithTotalServiceTime[$T]['charge'] / 1000);

				$counter = 1;
				$nameRes = Database::execute_query("SELECT `name` FROM `centers` WHERE `id` = '$centerId';");
				$nameRow = Database::get_assoc_array($nameRes);
				$htmlOut .= '<p style="text-align: center; font-weight: bold; display: block; line-height: 20px; margin-bottom: 0; margin-top: 20px;">مرکز "' . $nameRow['name'] . '"</p>';
				$htmlOut .= '<table align="center" class="contentTable" border="0" cellpadding="0" cellspacing="1">';
				$htmlOut .= '<thead><tr><th width="40">ردیف</th><th>نام واحد</th><th>هزینه(هزار ريال)</th><th>کل زمان خدمت(دقیقه)</th><th>هزینه در دقیقه(هزار ريال)</th></tr></thead><tbody>';
				foreach ($overallUnitChargeWithTotalServiceTime as $value) {
					$htmlOut .= '<tr><td>' . $counter . '</td><td>' . $value['name'] . '</td>';
					$htmlOut .= '<td>' . $value['charge'] . '</td>';
					$htmlOut .= '<td>' . round($value['totalServiceTime'] * 1.1) . '</td>';
					if ($value['totalServiceTime'] != 0) {
						$chargePerMinute = round($value['charge'] / ($value['totalServiceTime'] * 1.1));
						$htmlOut .= '<td>' . $chargePerMinute . '</td></tr>';
					} else
						$htmlOut .= '<td>-</td></tr>';
					$counter++;
				}
				$htmlOut .= '</tbody></table>';

				$counter = 1;
				foreach ($overallUnitChargeWithTotalServiceTime as $value) {
					$htmlOut .= '<p style="text-align: center; font-weight: bold; display: block; line-height: 40px; margin-bottom: 0; margin-top: 10px;">واحد "' . $value['name'] . '" در مرکز "' . $nameRow['name'] . '"</p>';
					$htmlOut .= '<table align="center" class="contentTable" border="0" cellpadding="0" cellspacing="1">';
					$htmlOut .= '<thead><tr><th width="40">ردیف</th><th>نام خدمت</th><th>میانگین زمان مصرفی خدمت(دقیقه)</th><th>هزینه در دقیقه(هزار ريال)</th><th>هزینه خدمت(هزار ريال)</th></tr></thead><tbody>';
					$resDB = Database::execute_query("SELECT `available-services`.`id`, `available-services`.`name`, `available-services`.`average-time` FROM `available-services` INNER JOIN `unit-services` ON `available-services`.`id` = `unit-services`.`service-id` WHERE `unit-id` = '" . $value['id'] . "' ORDER BY `name` ASC;");
					while ($rowDB = Database::get_assoc_array($resDB)) {
						$chargePerMinute = 0;
						$htmlOut .= '<tr><td>' . $counter . '</td><td>' . $rowDB['name'] . '</td>';
						$htmlOut .= '<td>' . round($rowDB['average-time'] * 1.1) . '</td>';
						if ($value['totalServiceTime'] != 0) {
							$chargePerMinute = round($value['charge'] / ($value['totalServiceTime'] * 1.1));
							$htmlOut .= '<td>' . $chargePerMinute . '</td>';
						} else
							$htmlOut .= '<td>-</td>';
						$htmlOut .= '<td>' . ($chargePerMinute * ($rowDB['average-time'])) . '</td></tr>';
						$counter++;
					}
					$htmlOut .= '</tbody></table>';
				}
			}//end foreach
			$htmlOut .= '</fieldset></div>';

			$htmlOut .= '<p style="text-align: center; font-size: large;"><a href="#" onclick="$(\'#servicesChart\').slideToggle(500);return false;" style="text-decoration: underline;"><img src="../illustrator/images/icons/3.png" style="height: 40px; width: 40px; vertical-align: middle;">نمودار مقایسه ی خدمات واحدها در مراکز انتخابی</a></p>';
			$htmlOut .= '<div class="loading"><img src="../statisticalPlotter/loading.gif" />&nbsp;&nbsp;در حال بارگذاری؛ لطفا صبر کنید...</div><br />';
			$htmlOut .= '<div id="servicesChart"><fieldset><legend>نمودار مقایسه ی خدمات واحدها در مراکز انتخابی</legend>';
			$htmlOut .= '<label for="centerId" class="required">نام مرکز:</label>';
			$htmlOut .= '<select name="centerId" id="centerId" class="validate first_opt_not_allowed">';
			foreach ($centersId as $value) {
				$res = Database::execute_query("SELECT `id`, `name` FROM `centers` WHERE `id` = '$value';");
				$row = Database::get_assoc_array($res);
				$htmlOut .= '<option value="' . $row['id'] . '">' . $row['name'] . '</option>';
			}
			$htmlOut .= '</select><br />';
			$htmlOut .= '<label for="unitId" class="required">نام واحد:</label>';
			$htmlOut .= '<select name="unitId" id="unitId" class="validate first_opt_not_allowed"></select><br />';
			$htmlOut .= '<label for="input_service_id">نام خدمت:</label>';
			$htmlOut .= '<select name="input_service_id" id="input_service_id" class="validate first_opt_not_allowed"></select><br />';
			$htmlOut .= '<script type="text/javascript">';
			$htmlOut .= '$(document).ajaxStart(function() {$(\'.loading\').show();$(\'#servicesChart\').css(\'opacity\', \'0.3\');});' . '$(document).ajaxSuccess(function() {$(\'.loading\').hide();$(\'#servicesChart\').css(\'opacity\', \'1\');});';
			$htmlOut .= 'var ajaxPost1 = $.post(\'../statisticalPlotter/AsynchronousProccess.php\', {\'centerId\' : $(\'#centerId option:selected\').val()});ajaxPost1.done(function(data) {$(\'#unitId\').empty().append(data);$(\'#input_service_id\').load(\'./ea-sh-ajax.php\', {\'unitIdForServiceSelection\' : $(\'#unitId option:selected\').val()});});';
			$htmlOut .= '$(\'#centerId\').change(function() {var ajaxPost1 = $.post(\'../statisticalPlotter/AsynchronousProccess.php\', {\'centerId\' : $(\'#centerId option:selected\').val()});ajaxPost1.done(function(data) {$(\'#unitId\').empty().append(data);$(\'#input_service_id\').load(\'./ea-sh-ajax.php\', {\'unitIdForServiceSelection\' : $(\'#unitId option:selected\').val()});});});';
			$htmlOut .= '$(\'#unitId\').change(function() {$(\'#input_service_id\').load(\'./ea-sh-ajax.php\', {\'unitIdForServiceSelection\' : $(\'#unitId option:selected\').val()});});';
			$htmlOut .= '</script>';
			$htmlOut .= '<input type="submit" name="viewServicesChart" value="رسم" onClick="$(\'#servicesChartDiv\').load(\'../statisticalPlotter/AsynchronousProccess.php\', {\'centersId[]\' : [' . implode(',', $centersId) . '], \'serviceId\': $(\'#input_service_id option:selected\').val(), \'date_period_from_day\' : ' . $date_period_from_day . ', \'date_period_from_month\' : ' . $date_period_from_month . ', \'date_period_from_year\' : ' . $date_period_from_year . ', \'date_period_to_day\' : ' . $date_period_to_day . ', \'date_period_to_month\' : ' . $date_period_to_month . ', \'date_period_to_year\' : ' . $date_period_to_year . '});" />';
			$htmlOut .= '<div id="servicesChartDiv"></div>';
			$htmlOut .= '</fieldset></div>';
		}//end if

		if (isset($_POST['exportFileType'])) {
			//preparing data for export
			$expArr = array();
			if (count($townsCharges) > 0)
				$expArr = array_merge($expArr, $townsCharges);
			if (count($centersCharges) > 0)
				$expArr = array_merge($expArr, $centersCharges);
			if (count($hygieneUnitsCharges) > 0)
				$expArr = array_merge($expArr, $hygieneUnitsCharges);
			if (count($unitsCharges) > 0)
				$expArr = array_merge($expArr, $unitsCharges);

			switch ($_POST['exportFileType']) {
				case '1' :
					Export::makeChargeCSVExport($expArr);
					break;
				case '2' :
					Export::makeChargeExcelExport($expArr);
					break;
				case '3' :
					Export::makeChargeXMLExport($expArr);
					break;
			}//END SWITCH
		}

		return $htmlOut;
	}

	//USD
	public static function drawSecondTypeChart() {
		$htmlOut = '';

		$date_period_1_from_day = $_POST['start_date1:d'];
		$date_period_1_from_month = $_POST['start_date1:m'];
		$date_period_1_from_year = $_POST['start_date1:y'];

		$date_period_1_to_day = $_POST['finish_date1:d'];
		$date_period_1_to_month = $_POST['finish_date1:m'];
		$date_period_1_to_year = $_POST['finish_date1:y'];

		$date_period_2_from_day = $_POST['start_date2:d'];
		$date_period_2_from_month = $_POST['start_date2:m'];
		$date_period_2_from_year = $_POST['start_date2:y'];

		$date_period_2_to_day = $_POST['finish_date2:d'];
		$date_period_2_to_month = $_POST['finish_date2:m'];
		$date_period_2_to_year = $_POST['finish_date2:y'];

		$greg_from_date_1 = jalali_to_gregorian($date_period_1_from_year, $date_period_1_from_month, $date_period_1_from_day);
		$greg_to_date_1 = jalali_to_gregorian($date_period_1_to_year, $date_period_1_to_month, $date_period_1_to_day);
		$from_date_1 = Date(mktime(0, 0, 0, $greg_from_date_1['1'], $greg_from_date_1['2'], $greg_from_date_1['0']));
		$to_date_1 = Date(mktime(0, 0, 0, $greg_to_date_1['1'], $greg_to_date_1['2'], $greg_to_date_1['0']));
		$from_to_gap_1 = self::count_days($to_date_1, $from_date_1);

		$greg_from_date_2 = jalali_to_gregorian($date_period_2_from_year, $date_period_2_from_month, $date_period_2_from_day);
		$greg_to_date_2 = jalali_to_gregorian($date_period_2_to_year, $date_period_2_to_month, $date_period_2_to_day);
		$from_date_2 = Date(mktime(0, 0, 0, $greg_from_date_2['1'], $greg_from_date_2['2'], $greg_from_date_2['0']));
		$to_date_2 = Date(mktime(0, 0, 0, $greg_to_date_2['1'], $greg_to_date_2['2'], $greg_to_date_2['0']));
		$from_to_gap_2 = self::count_days($to_date_2, $from_date_2);

		if (($from_to_gap_1 <= 60) or ($from_to_gap_2 <= 60))
			return '<p id="err">خطا در انتخاب بازه زمانی.<br />فاصله ی دو تاریخ باید حداقل 60 روز باشد.</p>';

		$firstSource;
		$secondSource;
		$sourceName;

		if (isset($_POST['townNameForTown'])) {
			$sourceId = $_POST['townNameForTown'];
			$res = Database::execute_query("SELECT `name` FROM `towns` WHERE `id` = '$sourceId';");
			$row = Database::get_assoc_array($res);
			$sourceName = 'شهرستان "' . $row['name'] . '"';
			$firstSource = self::getTownTotalCharges($sourceId, $date_period_1_from_day, $date_period_1_from_month, $date_period_1_from_year, $date_period_1_to_day, $date_period_1_to_month, $date_period_1_to_year);
			$secondSource = self::getTownTotalCharges($sourceId, $date_period_2_from_day, $date_period_2_from_month, $date_period_2_from_year, $date_period_2_to_day, $date_period_2_to_month, $date_period_2_to_year);
		} else if (isset($_POST['centerNameForCenter'])) {
			$sourceId = $_POST['centerNameForCenter'];
			$res = Database::execute_query("SELECT `name` FROM `centers` WHERE `id` = '$sourceId';");
			$row = Database::get_assoc_array($res);
			$sourceName = 'مرکز "' . $row['name'] . '"';
			$centersChargeTypes = $_POST['centerChargeTypes'];
			//first source
			$datePeriodArray = self::createDatePeriodArray($date_period_1_from_day, $date_period_1_from_month, $date_period_1_from_year, $date_period_1_to_day, $date_period_1_to_month, $date_period_1_to_year);
			foreach ($datePeriodArray as $value)
				$firstSource[] = array('year' => $value['year'], 'month' => $value['month'], 'totalCost' => 0);
			foreach ($centersChargeTypes as $value) {
				$tmpCenterCharge = self::getCenterCustomCharge($sourceId, $value, $date_period_1_from_day, $date_period_1_from_month, $date_period_1_from_year, $date_period_1_to_day, $date_period_1_to_month, $date_period_1_to_year);
				for ($j = 0; $j < count($firstSource); $j++)
					$firstSource[$j]['totalCost'] += $tmpCenterCharge[$j]['totalCost'];
			}
			//second source
			$datePeriodArray = self::createDatePeriodArray($date_period_2_from_day, $date_period_2_from_month, $date_period_2_from_year, $date_period_2_to_day, $date_period_2_to_month, $date_period_2_to_year);
			foreach ($datePeriodArray as $value)
				$secondSource[] = array('year' => $value['year'], 'month' => $value['month'], 'totalCost' => 0);
			foreach ($centersChargeTypes as $value) {
				$tmpCenterCharge = self::getCenterCustomCharge($sourceId, $value, $date_period_2_from_day, $date_period_2_from_month, $date_period_2_from_year, $date_period_2_to_day, $date_period_2_to_month, $date_period_2_to_year);
				for ($j = 0; $j < count($secondSource); $j++)
					$secondSource[$j]['totalCost'] += $tmpCenterCharge[$j]['totalCost'];
			}
		} else if (isset($_POST['hygieneUnitName'])) {
			$sourceId = $_POST['hygieneUnitName'];
			$res = Database::execute_query("SELECT `name` FROM `hygiene-units` WHERE `id` = '$sourceId';");
			$row = Database::get_assoc_array($res);
			$sourceName = 'خانه بهداشت "' . $row['name'] . '"';
			$hygieneUnitChargeTypes = $_POST['hygieneUnitChargeTypes'];
			//first source
			$datePeriodArray = self::createDatePeriodArray($date_period_1_from_day, $date_period_1_from_month, $date_period_1_from_year, $date_period_1_to_day, $date_period_1_to_month, $date_period_1_to_year);
			foreach ($datePeriodArray as $value)
				$firstSource[] = array('year' => $value['year'], 'month' => $value['month'], 'totalCost' => 0);
			foreach ($hygieneUnitChargeTypes as $value) {
				$tmpCenterCharge = self::getHygieneUnitCustomCharge($sourceId, $value, $date_period_1_from_day, $date_period_1_from_month, $date_period_1_from_year, $date_period_1_to_day, $date_period_1_to_month, $date_period_1_to_year);
				for ($j = 0; $j < count($firstSource); $j++)
					$firstSource[$j]['totalCost'] += $tmpCenterCharge[$j]['totalCost'];
			}
			//second source
			$datePeriodArray = self::createDatePeriodArray($date_period_2_from_day, $date_period_2_from_month, $date_period_2_from_year, $date_period_2_to_day, $date_period_2_to_month, $date_period_2_to_year);
			foreach ($datePeriodArray as $value)
				$secondSource[] = array('year' => $value['year'], 'month' => $value['month'], 'totalCost' => 0);
			foreach ($hygieneUnitChargeTypes as $value) {
				$tmpCenterCharge = self::getHygieneUnitCustomCharge($sourceId, $value, $date_period_2_from_day, $date_period_2_from_month, $date_period_2_from_year, $date_period_2_to_day, $date_period_2_to_month, $date_period_2_to_year);
				for ($j = 0; $j < count($secondSource); $j++)
					$secondSource[$j]['totalCost'] += $tmpCenterCharge[$j]['totalCost'];
			}
			$firstSource = self::getHygieneUnitTotalCharges($sourceId, $date_period_1_from_day, $date_period_1_from_month, $date_period_1_from_year, $date_period_1_to_day, $date_period_1_to_month, $date_period_1_to_year);
			$secondSource = self::getHygieneUnitTotalCharges($sourceId, $date_period_2_from_day, $date_period_2_from_month, $date_period_2_from_year, $date_period_2_to_day, $date_period_2_to_month, $date_period_2_to_year);
		} else if (isset($_POST['unitName'])) {
			$sourceId = $_POST['unitName'];
			$res = Database::execute_query("SELECT `name` FROM `units` WHERE `id` = '$sourceId';");
			$row = Database::get_assoc_array($res);
			$sourceName = 'واحد "' . $row['name'] . '"';
			$firstSource = self::getTotalUnitChrages($sourceId, $date_period_1_from_day, $date_period_1_from_month, $date_period_1_from_year, $date_period_1_to_day, $date_period_1_to_month, $date_period_1_to_year);
			$secondSource = self::getTotalUnitChrages($sourceId, $date_period_2_from_day, $date_period_2_from_month, $date_period_2_from_year, $date_period_2_to_day, $date_period_2_to_month, $date_period_2_to_year);
		} else
			return '<p id="err">خطا: هیچ منبعی برای گزارش انتخاب نشده است.</p>';

		//round values
		$tmpArr = array();
		foreach ($firstSource as $value) {
			$tmpArr[] = array('year' => $value['year'], 'month' => $value['month'], 'totalCost' => round($value['totalCost'] / 1000));
		}
		$firstSource = $tmpArr;

		$tmpArr = array();
		foreach ($secondSource as $value) {
			$tmpArr[] = array('year' => $value['year'], 'month' => $value['month'], 'totalCost' => round($value['totalCost'] / 1000));
		}
		$secondSource = $tmpArr;

		$htmlOut .= '<p><input type="button" value="چاپ نمودار" onclick="alert(\'لطفا مد Landscape را برای پرینت انتخاب کنید.\');window.print();return false;"></p>';
		$htmlOut .= '<br /><div id="chartDiv1"></div>';
		$htmlOut .= '<div class="reportDescription1">';
		$max = $firstSource['0'];
		$min = $firstSource['0'];
		$sum = 0;
		foreach ($firstSource as $value) {
			if ($value['totalCost'] > $max['totalCost'])
				$max = $value;
			if ($value['totalCost'] < $min['totalCost'])
				$min = $value;
			$sum += $value['totalCost'];
		}

		$average = $sum / count($firstSource);
		//preparing data for variance function
		$varArray = array();
		foreach ($firstSource as $value)
			$varArray[] = $value['totalCost'];
		$variance = self::variance($varArray);

		$htmlOut .= '<br /><fieldset style="width: 520px;"><legend>جزئیات آماری نمودار ' . $sourceName . '</legend>';
		$htmlOut .= '<p><span style="font-weight: bold;">حداکثر هزینه در تاریخ: </span>"' . $max['year'] . '/' . $max['month'] . '"<span style="font-weight: bold;"> به مبلغ: </span>' . round($max['totalCost']) . ' هزار ريال؛</p>';
		$htmlOut .= '<p><span style="font-weight: bold;">حداقل هزینه در تاریخ: </span>"' . $min['year'] . '/' . $min['month'] . '"<span style="font-weight: bold;"> به مبلغ: </span>' . round($min['totalCost']) . ' هزار ريال؛</p>';
		$htmlOut .= '<p><span style="font-weight: bold;">میانگین هزینه ها: </span>' . round($average) . ' هزار ريال؛</p>';
		$htmlOut .= '<p><span style="font-weight: bold;">واریانس هزینه ها: </span>' . round($variance) . '</p>';
		$htmlOut .= '<p><span style="font-weight: bold;">انحراف معیار: </span>' . round(sqrt($variance)) . '</p>';
		$htmlOut .= '</fieldset></div>';

		$htmlOut .= '<br /><br /><hr style="width: 620px;" /><br /><div id="chartDiv2"></div>';
		$htmlOut .= '<div class="reportDescription2">';
		$max = $secondSource['0'];
		$min = $secondSource['0'];
		$sum = 0;
		foreach ($secondSource as $value) {
			if ($value['totalCost'] > $max['totalCost'])
				$max = $value;
			if ($value['totalCost'] < $min['totalCost'])
				$min = $value;
			$sum += $value['totalCost'];
		}

		$average = $sum / count($secondSource);
		//preparing data for variance function
		$varArray = array();
		foreach ($secondSource as $value)
			$varArray[] = $value['totalCost'];
		$variance = self::variance($varArray);

		$htmlOut .= '<br /><fieldset style="width: 520px;"><legend>جزئیات آماری نمودار ' . $sourceName . '</legend>';
		$htmlOut .= '<p><span style="font-weight: bold;">حداکثر هزینه در تاریخ: </span>"' . $max['year'] . '/' . $max['month'] . '"<span style="font-weight: bold;"> به مبلغ: </span>' . round($max['totalCost']) . ' هزار ريال؛</p>';
		$htmlOut .= '<p><span style="font-weight: bold;">حداقل هزینه در تاریخ: </span>"' . $min['year'] . '/' . $min['month'] . '"<span style="font-weight: bold;"> به مبلغ: </span>' . round($min['totalCost']) . ' هزار ريال؛</p>';
		$htmlOut .= '<p><span style="font-weight: bold;">میانگین هزینه ها: </span>' . round($average) . ' هزار ريال؛</p>';
		$htmlOut .= '<p><span style="font-weight: bold;">واریانس هزینه ها: </span>' . round($variance) . '</p>';
		$htmlOut .= '<p><span style="font-weight: bold;">انحراف معیار: </span>' . round(sqrt($variance)) . '</p>';
		$htmlOut .= '</fieldset></div>';

		$htmlOut .= '<script type="text/javascript" src="../illustrator/js/jquery.jqplot.min.js"></script>';
		$htmlOut .= '<script type="text/javascript" src="../illustrator/js/jqplot.canvasTextRenderer.min.js"></script>';
		$htmlOut .= '<script type="text/javascript" src="../illustrator/js/jqplot.canvasAxisLabelRenderer.min.js"></script>';
		$htmlOut .= '<script type="text/javascript" src="../illustrator/js/jqplot.cursor.min.js"></script>';
		$htmlOut .= '<script type="text/javascript" src="../illustrator/js/jqplot.highlighter.min.js"></script>';
		$htmlOut .= '<script type="text/javascript">';

		//first chart
		$datePeriodArray = self::createDatePeriodArray($date_period_1_from_day, $date_period_1_from_month, $date_period_1_from_year, $date_period_1_to_day, $date_period_1_to_month, $date_period_1_to_year);
		$htmlOut .= 'var ticks1 = [';
		for ($i = 0; $i < count($datePeriodArray); $i++) {
			$htmlOut .= '[' . ($i + 1) . ',"' . $datePeriodArray[$i]['year'] . '/' . $datePeriodArray[$i]['month'] . '"]';
			if ($i != (count($datePeriodArray) - 1))
				$htmlOut .= ', ';
		}
		$htmlOut .= '];';
		$htmlOut .= 'var plot1 = $.jqplot (\'chartDiv1\', [[';
		for ($i = 0; $i < count($firstSource); $i++) {
			$htmlOut .= $firstSource[$i]['totalCost'];
			if ($i != (count($firstSource) - 1))
				$htmlOut .= ', ';
		}
		$htmlOut .= ']], {
				animate: true,
				animateReplot: true,
				title: \'نمودار مقایسه ی هزینه ها از تاریخ ' . $date_period_1_from_year . '/' . $date_period_1_from_month . '/' . $date_period_1_from_day . ' تا تاریخ ' . $date_period_1_to_year . '/' . $date_period_1_to_month . '/' . $date_period_1_to_day . '\',
				legend: {show: true, placement: \'inside\'},
				axesDefaults: {labelRenderer: $.jqplot.CanvasAxisLabelRenderer},
				axes: { xaxis: {ticks: ticks1, label: \'ماه های انتخابی\'}, yaxis: {min: 0, label: \'مبلغ هزینه(ريال)\'} },
				cursor: {style: \'crosshair\', show: true, showTooltip: true},
				highlighter: {showTooltip: true, show: true, showLabel: true, fadeTooltip: true},
				grid: {drawGridLines: true, gridLineColor: \'#CCC\'},
				seriesDefaults: {shadow: true, showMarker: true},
				series: [{label: \'' . $sourceName . '\'}]';
		$htmlOut .= '});';

		//second chart
		$datePeriodArray = self::createDatePeriodArray($date_period_2_from_day, $date_period_2_from_month, $date_period_2_from_year, $date_period_2_to_day, $date_period_2_to_month, $date_period_2_to_year);
		$htmlOut .= 'var ticks2 = [';
		for ($i = 0; $i < count($datePeriodArray); $i++) {
			$htmlOut .= '[' . ($i + 1) . ',"' . $datePeriodArray[$i]['year'] . '/' . $datePeriodArray[$i]['month'] . '"]';
			if ($i != (count($datePeriodArray) - 1))
				$htmlOut .= ', ';
		}
		$htmlOut .= '];';
		$htmlOut .= 'var plot2 = $.jqplot (\'chartDiv2\', [[';
		for ($i = 0; $i < count($secondSource); $i++) {
			$htmlOut .= $secondSource[$i]['totalCost'];
			if ($i != (count($secondSource) - 1))
				$htmlOut .= ', ';
		}
		$htmlOut .= ']], {
				animate: true,
				animateReplot: true,
				title: \'نمودار مقایسه ی هزینه ها از تاریخ ' . $date_period_2_from_year . '/' . $date_period_2_from_month . '/' . $date_period_2_from_day . ' تا تاریخ ' . $date_period_2_to_year . '/' . $date_period_2_to_month . '/' . $date_period_2_to_day . '\',
				legend: {show: true, placement: \'inside\'},
				axesDefaults: {labelRenderer: $.jqplot.CanvasAxisLabelRenderer},
				axes: { xaxis: {ticks: ticks2, label: \'ماه های انتخابی\'}, yaxis: {min: 0, label: \'مبلغ هزینه(ريال)\'} },
				cursor: {style: \'crosshair\', show: true, showTooltip: true},
				highlighter: {showTooltip: true, show: true, showLabel: true, fadeTooltip: true},
				grid: {drawGridLines: true, gridLineColor: \'#CCC\'},
				seriesDefaults: {shadow: true, showMarker: true},
				series: [{label: \'' . $sourceName . '\'}]';
		$htmlOut .= '});';
		$htmlOut .= '</script>';

		return $htmlOut;
	}

	//USD
	public static function drawThirdTypeChart() {
		$htmlOut = '';

		$date_period_from_pie_day = $_POST['start_date_pie:d'];
		$date_period_from_pie_month = $_POST['start_date_pie:m'];
		$date_period_from_pie_year = $_POST['start_date_pie:y'];

		$date_period_to_pie_day = $_POST['finish_date_pie:d'];
		$date_period_to_pie_month = $_POST['finish_date_pie:m'];
		$date_period_to_pie_year = $_POST['finish_date_pie:y'];

		$greg_from_date = jalali_to_gregorian($date_period_from_pie_year, $date_period_from_pie_month, $date_period_from_pie_day);
		$greg_to_date = jalali_to_gregorian($date_period_to_pie_year, $date_period_to_pie_month, $date_period_to_pie_day);
		$from_date = Date(mktime(0, 0, 0, $greg_from_date['1'], $greg_from_date['2'], $greg_from_date['0']));
		$to_date = Date(mktime(0, 0, 0, $greg_to_date['1'], $greg_to_date['2'], $greg_to_date['0']));
		$from_to_gap = self::count_days($to_date, $from_date);
		if ($from_to_gap <= 60)
			return '<p id="err">خطا در انتخاب بازه زمانی.<br />فاصله ی دو تاریخ باید حداقل 60 روز باشد.</p>';

		if (isset($_POST['chart_source_type']))
			$sourceType = $_POST['chart_source_type'];
		else
			return '<p id="err">خطا: هیچ منبعی برای گزارش انتخاب نشده است.</p>';

		$chartTitle;
		$sourceId;
		$sourceChargesWithLabel = array();
		switch ($sourceType) {
			case '1' :
				// All units in a center
				$sourceId = $_POST['centerNameForCenter'];
				$res1 = Database::execute_query("SELECT `name` FROM `centers` WHERE `id` = '$sourceId';");
				$row1 = Database::get_assoc_array($res1);
				$chartTitle = 'نمودار مقایسه ی تمامی واحدهای مرکز <' . $row1['name'] . '> از تاریخ ' . $date_period_from_pie_year . '/' . $date_period_from_pie_month . '/' . $date_period_from_pie_day . ' تا تاریخ ' . $date_period_to_pie_year . '/' . $date_period_to_pie_month . '/' . $date_period_to_pie_day;
				$res = Database::execute_query("SELECT `id`, `name` FROM `units` WHERE `center-id` = '" . $sourceId . "';");
				while ($row = Database::get_assoc_array($res)) {
					$sourceTotalCharge = self::getTotalUnitChrages($row['id'], $date_period_from_pie_day, $date_period_from_pie_month, $date_period_from_pie_year, $date_period_to_pie_day, $date_period_to_pie_month, $date_period_to_pie_year);
					$sourceTotalChargesSum = 0;
					foreach ($sourceTotalCharge as $value)
						$sourceTotalChargesSum += $value['totalCost'];
					$sourceChargesWithLabel[] = array('name' => $row['name'], 'totalCost' => $sourceTotalChargesSum);
				}
				break;
			case '2' :
				// All centers in a town
				$sourceId = $_POST['townNameForTown'];
				$res1 = Database::execute_query("SELECT `name` FROM `towns` WHERE `id` = '$sourceId';");
				$row1 = Database::get_assoc_array($res1);
				$chartTitle = 'نمودار مقایسه ی تمامی مراکز شهرستان <' . $row1['name'] . '> از تاریخ ' . $date_period_from_pie_year . '/' . $date_period_from_pie_month . '/' . $date_period_from_pie_day . ' تا تاریخ ' . $date_period_to_pie_year . '/' . $date_period_to_pie_month . '/' . $date_period_to_pie_day;
				$res = Database::execute_query("SELECT `id`, `name` FROM `centers` WHERE `town-id` = '" . $sourceId . "';");
				while ($row = Database::get_assoc_array($res)) {
					$sourceTotalCharge = self::getCenterTotalCharges($row['id'], $date_period_from_pie_day, $date_period_from_pie_month, $date_period_from_pie_year, $date_period_to_pie_day, $date_period_to_pie_month, $date_period_to_pie_year);
					$sourceTotalChargesSum = 0;
					foreach ($sourceTotalCharge as $value)
						$sourceTotalChargesSum += $value['totalCost'];
					$sourceChargesWithLabel[] = array('name' => $row['name'], 'totalCost' => $sourceTotalChargesSum);
				}
				break;
			case '3' :
				// All hygiene units in a center
				$sourceId = $_POST['centerNameForCenter'];
				$res1 = Database::execute_query("SELECT `name` FROM `centers` WHERE `id` = '$sourceId';");
				$row1 = Database::get_assoc_array($res1);
				$chartTitle = 'نمودار مقایسه ی تمامی خانه های بهداشت مرکز <' . $row1['name'] . '> از تاریخ ' . $date_period_from_pie_year . '/' . $date_period_from_pie_month . '/' . $date_period_from_pie_day . ' تا تاریخ ' . $date_period_to_pie_year . '/' . $date_period_to_pie_month . '/' . $date_period_to_pie_day;
				$res = Database::execute_query("SELECT `id`, `name` FROM `hygiene-units` WHERE `center-id` = '" . $sourceId . "';");
				while ($row = Database::get_assoc_array($res)) {
					$sourceTotalCharge = self::getHygieneUnitTotalCharges($row['id'], $date_period_from_pie_day, $date_period_from_pie_month, $date_period_from_pie_year, $date_period_to_pie_day, $date_period_to_pie_month, $date_period_to_pie_year);
					$sourceTotalChargesSum = 0;
					foreach ($sourceTotalCharge as $value)
						$sourceTotalChargesSum += $value['totalCost'];
					$sourceChargesWithLabel[] = array('name' => $row['name'], 'totalCost' => $sourceTotalChargesSum);
				}
				break;
			case '4' :
				// All unit items
				$sourceId = $_POST['unitName'];
				break;
			case '5' :
				// All center items
				$sourceId = $_POST['centerNameForCenter'];
				$res1 = Database::execute_query("SELECT `name` FROM `centers` WHERE `id` = '$sourceId';");
				$row1 = Database::get_assoc_array($res1);
				$chartTitle = 'نمودار مقایسه ی تمامی آیتم های هزینه ای مرکز <' . $row1['name'] . '> از تاریخ ' . $date_period_from_pie_year . '/' . $date_period_from_pie_month . '/' . $date_period_from_pie_day . ' تا تاریخ ' . $date_period_to_pie_year . '/' . $date_period_to_pie_month . '/' . $date_period_to_pie_day;
				// Drugs equipments
				$sourceTotalCharge = self::getCenterCustomCharge($sourceId, 'drugs', $date_period_from_pie_day, $date_period_from_pie_month, $date_period_from_pie_year, $date_period_to_pie_day, $date_period_to_pie_month, $date_period_to_pie_year);
				$sourceTotalChargesSum = 0;
				foreach ($sourceTotalCharge as $value)
					$sourceTotalChargesSum += $value['totalCost'];
				$sourceChargesWithLabel[] = array('name' => 'داروها', 'totalCost' => $sourceTotalChargesSum);

				// consuming equipments
				$sourceTotalCharge = self::getCenterCustomCharge($sourceId, 'consumings', $date_period_from_pie_day, $date_period_from_pie_month, $date_period_from_pie_year, $date_period_to_pie_day, $date_period_to_pie_month, $date_period_to_pie_year);
				$sourceTotalChargesSum = 0;
				foreach ($sourceTotalCharge as $value)
					$sourceTotalChargesSum += $value['totalCost'];
				$sourceChargesWithLabel[] = array('name' => 'تجهیزات مصرفی', 'totalCost' => $sourceTotalChargesSum);


				// Non consuming equipments
				$sourceTotalCharge = self::getCenterCustomCharge($sourceId, 'nonConsumings', $date_period_from_pie_day, $date_period_from_pie_month, $date_period_from_pie_year, $date_period_to_pie_day, $date_period_to_pie_month, $date_period_to_pie_year);
				$sourceTotalChargesSum = 0;
				foreach ($sourceTotalCharge as $value)
					$sourceTotalChargesSum += $value['totalCost'];
				$sourceChargesWithLabel[] = array('name' => 'تجهیزات غیر مصرفی', 'totalCost' => $sourceTotalChargesSum);

				// Buildings
				$sourceTotalCharge = self::getCenterCustomCharge($sourceId, 'buildings', $date_period_from_pie_day, $date_period_from_pie_month, $date_period_from_pie_year, $date_period_to_pie_day, $date_period_to_pie_month, $date_period_to_pie_year);
				$sourceTotalChargesSum = 0;
				foreach ($sourceTotalCharge as $value)
					$sourceTotalChargesSum += $value['totalCost'];
				$sourceChargesWithLabel[] = array('name' => 'ساختمان ها', 'totalCost' => $sourceTotalChargesSum);

				// Vehicles
				$sourceTotalCharge = self::getCenterCustomCharge($sourceId, 'vehicles', $date_period_from_pie_day, $date_period_from_pie_month, $date_period_from_pie_year, $date_period_to_pie_day, $date_period_to_pie_month, $date_period_to_pie_year);
				$sourceTotalChargesSum = 0;
				foreach ($sourceTotalCharge as $value)
					$sourceTotalChargesSum += $value['totalCost'];
				$sourceChargesWithLabel[] = array('name' => 'وسایل نقلیه', 'totalCost' => $sourceTotalChargesSum);

				// General
				$sourceTotalCharge = self::getCenterCustomCharge($sourceId, 'general', $date_period_from_pie_day, $date_period_from_pie_month, $date_period_from_pie_year, $date_period_to_pie_day, $date_period_to_pie_month, $date_period_to_pie_year);
				$sourceTotalChargesSum = 0;
				foreach ($sourceTotalCharge as $value)
					$sourceTotalChargesSum += $value['totalCost'];
				$sourceChargesWithLabel[] = array('name' => 'هزینه های عمومی', 'totalCost' => $sourceTotalChargesSum);

				// Units
				$sourceTotalCharge = self::getCenterCustomCharge($sourceId, 'totalUnits', $date_period_from_pie_day, $date_period_from_pie_month, $date_period_from_pie_year, $date_period_to_pie_day, $date_period_to_pie_month, $date_period_to_pie_year);
				$sourceTotalChargesSum = 0;
				foreach ($sourceTotalCharge as $value)
					$sourceTotalChargesSum += $value['totalCost'];
				$sourceChargesWithLabel[] = array('name' => 'واحدهای زیر مجموعه', 'totalCost' => $sourceTotalChargesSum);
				break;
			case '6' :
				// All hygiene unit items
				$sourceId = $_POST['hygieneUnitName'];
				$res1 = Database::execute_query("SELECT `name` FROM `hygiene-units` WHERE `id` = '$sourceId';");
				$row1 = Database::get_assoc_array($res1);
				$chartTitle = 'نمودار مقایسه ی تمامی آیتم های هزینه ای خانه بهداشت <' . $row1['name'] . '> از تاریخ ' . $date_period_from_pie_year . '/' . $date_period_from_pie_month . '/' . $date_period_from_pie_day . ' تا تاریخ ' . $date_period_to_pie_year . '/' . $date_period_to_pie_month . '/' . $date_period_to_pie_day;
				// Drugs equipments
				$sourceTotalCharge = self::getHygieneUnitCustomCharge($sourceId, 'drugs', $date_period_from_pie_day, $date_period_from_pie_month, $date_period_from_pie_year, $date_period_to_pie_day, $date_period_to_pie_month, $date_period_to_pie_year);
				$sourceTotalChargesSum = 0;
				foreach ($sourceTotalCharge as $value)
					$sourceTotalChargesSum += $value['totalCost'];
				$sourceChargesWithLabel[] = array('name' => 'داروها', 'totalCost' => $sourceTotalChargesSum);

				// consuming equipments
				$sourceTotalCharge = self::getHygieneUnitCustomCharge($sourceId, 'consumings', $date_period_from_pie_day, $date_period_from_pie_month, $date_period_from_pie_year, $date_period_to_pie_day, $date_period_to_pie_month, $date_period_to_pie_year);
				$sourceTotalChargesSum = 0;
				foreach ($sourceTotalCharge as $value)
					$sourceTotalChargesSum += $value['totalCost'];
				$sourceChargesWithLabel[] = array('name' => 'تجهیزات مصرفی', 'totalCost' => $sourceTotalChargesSum);

				// Non consuming equipments
				$sourceTotalCharge = self::getHygieneUnitCustomCharge($sourceId, 'nonConsumings', $date_period_from_pie_day, $date_period_from_pie_month, $date_period_from_pie_year, $date_period_to_pie_day, $date_period_to_pie_month, $date_period_to_pie_year);
				$sourceTotalChargesSum = 0;
				foreach ($sourceTotalCharge as $value)
					$sourceTotalChargesSum += $value['totalCost'];
				$sourceChargesWithLabel[] = array('name' => 'تجهیزات غیر مصرفی', 'totalCost' => $sourceTotalChargesSum);

				// Buildings
				$sourceTotalCharge = self::getHygieneUnitCustomCharge($sourceId, 'buildings', $date_period_from_pie_day, $date_period_from_pie_month, $date_period_from_pie_year, $date_period_to_pie_day, $date_period_to_pie_month, $date_period_to_pie_year);
				$sourceTotalChargesSum = 0;
				foreach ($sourceTotalCharge as $value)
					$sourceTotalChargesSum += $value['totalCost'];
				$sourceChargesWithLabel[] = array('name' => 'ساختمان ها', 'totalCost' => $sourceTotalChargesSum);

				// Vehicles
				$sourceTotalCharge = self::getHygieneUnitCustomCharge($sourceId, 'vehicles', $date_period_from_pie_day, $date_period_from_pie_month, $date_period_from_pie_year, $date_period_to_pie_day, $date_period_to_pie_month, $date_period_to_pie_year);
				$sourceTotalChargesSum = 0;
				foreach ($sourceTotalCharge as $value)
					$sourceTotalChargesSum += $value['totalCost'];
				$sourceChargesWithLabel[] = array('name' => 'وسایل نقلیه', 'totalCost' => $sourceTotalChargesSum);

				// General
				$sourceTotalCharge = self::getHygieneUnitCustomCharge($sourceId, 'general', $date_period_from_pie_day, $date_period_from_pie_month, $date_period_from_pie_year, $date_period_to_pie_day, $date_period_to_pie_month, $date_period_to_pie_year);
				$sourceTotalChargesSum = 0;
				foreach ($sourceTotalCharge as $value)
					$sourceTotalChargesSum += $value['totalCost'];
				$sourceChargesWithLabel[] = array('name' => 'هزینه های عمومی', 'totalCost' => $sourceTotalChargesSum);

				// Behvarz
				$sourceTotalCharge = self::getHygieneUnitCustomCharge($sourceId, 'personnel', $date_period_from_pie_day, $date_period_from_pie_month, $date_period_from_pie_year, $date_period_to_pie_day, $date_period_to_pie_month, $date_period_to_pie_year);
				$sourceTotalChargesSum = 0;
				foreach ($sourceTotalCharge as $value)
					$sourceTotalChargesSum += $value['totalCost'];
				$sourceChargesWithLabel[] = array('name' => 'بهورزان', 'totalCost' => $sourceTotalChargesSum);
				break;
		}

		$htmlOut .= '<p><input type="button" value="چاپ نمودار" onclick="alert(\'لطفا مد Landscape را برای پرینت انتخاب کنید.\');window.print();return false;"></p>';
		$htmlOut .= '<br /><div id="chartDiv"></div>';
		$htmlOut .= '<div class="reportDescription3">';
		$max = $sourceChargesWithLabel['0'];
		$min = $sourceChargesWithLabel['0'];
		$sum = 0;
		foreach ($sourceChargesWithLabel as $value) {
			if ($value['totalCost'] > $max['totalCost'])
				$max = $value;
			if ($value['totalCost'] < $min['totalCost'])
				$min = $value;
			$sum += $value['totalCost'];
		}

		$average = $sum / count($sourceChargesWithLabel);
		//preparing data for variance function
		$varArray = array();
		foreach ($sourceChargesWithLabel as $value) {
			$varArray[] = round($value['totalCost']);
		}
		$variance = self::variance($varArray);

		$htmlOut .= '<br /><fieldset style="width: 520px;"><legend>جزئیات آماری نمودار</legend>';
		$htmlOut .= '<p><span style="font-weight: bold;">حداکثر هزینه برای: </span>"' . $max['name'] . '"<span style="font-weight: bold;"> به مبلغ: </span>' . round($max['totalCost'] / 1000) . ' هزار ريال؛</p>';
		$htmlOut .= '<p><span style="font-weight: bold;">حداقل هزینه برای: </span>"' . $min['name'] . '"<span style="font-weight: bold;"> به مبلغ: </span>' . round($min['totalCost'] / 1000) . ' هزار ريال؛</p>';
		$htmlOut .= '<p><span style="font-weight: bold;">میانگین هزینه ها: </span>' . round($average / 1000) . ' هزار ريال؛</p>';
		$htmlOut .= '<p><span style="font-weight: bold;">واریانس: </span>' . $variance . '</p>';
		$htmlOut .= '<p><span style="font-weight: bold;">انحراف معیار: </span>' . round(sqrt($variance)) . '</p>';
		$htmlOut .= '</fieldset></div>';

		//includes
		$htmlOut .= '<script type="text/javascript" src="../illustrator/js/jquery.jqplot.min.js"></script>';
		$htmlOut .= '<script type="text/javascript" src="../illustrator/js/jqplot.pieRenderer.min.js"></script>';
		$htmlOut .= '<script type="text/javascript">';

		$htmlOut .= 'jQuery.jqplot.config.enablePlugins = true;';
		$htmlOut .= 'var plot = jQuery.jqplot(\'chartDiv\', [ [ ';
		for ($i = 0; $i < count($sourceChargesWithLabel); $i++) {
			$htmlOut .= '["' . $sourceChargesWithLabel[$i]['name'] . '", ' . $sourceChargesWithLabel[$i]['totalCost'] . '], ';
		}
		$htmlOut .= ' ] ], {
			title: "' . $chartTitle . '", 
      		seriesDefaults: {shadow: false, renderer: jQuery.jqplot.PieRenderer, rendererOptions: { startAngle: 180, sliceMargin: 2, showDataLabels: true } }, 
      		legend: { show: true, location: "e" }
    	});';
		$htmlOut .= '</script>';

		return $htmlOut;
	}

	//USD
	public static function drawForthTypeChart() {
		$htmlOut = '';

		$date_period_from_total_day = $_POST['start_date_total:d'];
		$date_period_from_total_month = $_POST['start_date_total:m'];
		$date_period_from_total_year = $_POST['start_date_total:y'];

		$date_period_to_total_day = $_POST['finish_date_total:d'];
		$date_period_to_total_month = $_POST['finish_date_total:m'];
		$date_period_to_total_year = $_POST['finish_date_total:y'];

		$greg_from_date = jalali_to_gregorian($date_period_from_total_year, $date_period_from_total_month, $date_period_from_total_day);
		$greg_to_date = jalali_to_gregorian($date_period_to_total_year, $date_period_to_total_month, $date_period_to_total_day);
		$from_date = Date(mktime(0, 0, 0, $greg_from_date['1'], $greg_from_date['2'], $greg_from_date['0']));
		$to_date = Date(mktime(0, 0, 0, $greg_to_date['1'], $greg_to_date['2'], $greg_to_date['0']));
		$from_to_gap = self::count_days($to_date, $from_date);
		if ($from_to_gap <= 60)
			return '<p id="err">خطا در انتخاب بازه زمانی.<br />فاصله ی دو تاریخ باید حداقل 60 روز باشد.</p>';

		if (isset($_POST['TotalCostChart']))
			$sourceType = $_POST['TotalCostChart'];
		else
			return '<p id="err">هیچ منبعی برای گزارش انتخاب نشده است.</p>';

		$finalTotalCosts = array();
		$finalMax = array();
		$finalMin = array();
		$finalAverage = 0;
		$chartTitle = '';
		switch ($sourceType) {
			case '1' :
				//all state centers
				$chartTitle = 'نمودار مقایسه ی مجموع هزینه های مراکز استان از تاریخ ' . $date_period_from_total_year . '/' . $date_period_from_total_month . '/' . $date_period_from_total_day . ' تا تاریخ ' . $date_period_to_total_year . '/' . $date_period_to_total_month . '/' . $date_period_to_total_day;
				$centerIds = array();
				$resTowns = Database::execute_query("SELECT `id` FROM `towns` WHERE `state-id` = '" . $_SESSION['user']['acl-id'] . "';");
				while ($rowTowns = Database::get_assoc_array($resTowns)) {
					$resCenters = Database::execute_query("SELECT `id` FROM `centers` WHERE `town-id` = '" . $rowTowns['id'] . "';");
					while ($rowCenters = Database::get_assoc_array($resCenters))
						$centerIds[] = $rowCenters['id'];
				}
				$min = array();
				$max = array();
				//lookig for min != 0 !!!
				for ($i = 0; $i < count($centerIds); $i++) { 
					$tmp = self::getCenterTotalCharges($centerIds[$i], $date_period_from_total_day, $date_period_from_total_month, $date_period_from_total_year, $date_period_to_total_day, $date_period_to_total_month, $date_period_to_total_year);
					$res = Database::execute_query("SELECT `name` FROM `centers` WHERE `id` = '$value';");
					$row = Database::get_assoc_array($res);
					$sum = 0;
					foreach ($tmp as $tmpValue)
						$sum += $tmpValue['totalCost'];
					$sum = round($sum / 1000);
					if($sum > 0) {
						$min = array('name' => $row['name'], 'totalCost' => $sum);
						$max = array('name' => $row['name'], 'totalCost' => $sum);
						break;
					}
				}
				$allCentersSum = 0;
				foreach ($centerIds as $value) {
					$tmp = self::getCenterTotalCharges($value, $date_period_from_total_day, $date_period_from_total_month, $date_period_from_total_year, $date_period_to_total_day, $date_period_to_total_month, $date_period_to_total_year);
					$sum = 0;
					foreach ($tmp as $tmpValue)
						$sum += $tmpValue['totalCost'];
					$sum = round($sum / 1000);
					$res = Database::execute_query("SELECT `name` FROM `centers` WHERE `id` = '$value';");
					$row = Database::get_assoc_array($res);
					if ($sum >= $max['totalCost']) {
						$max['name'] = $row['name'];
						$max['totalCost'] = $sum;
					}
					if ($sum < $min['totalCost'] and $sum > 0) {
						$min['name'] = $row['name'];
						$min['totalCost'] = $sum;
					}
					if ($sum > 0)
						$finalTotalCosts[] = array('name' => $row['name'], 'totalCost' => $sum);
					$allCentersSum += $sum;
				}
				$finalMax = $max;
				$finalMin = $min;
				$finalAverage = $allCentersSum / count($finalTotalCosts);
				break;
			case '2' :
				//all specified units in state
				$chartTitle = 'نمودار مقایسه ی مجموع هزینه های واحد <' . $_POST['unitName'] . '> در کل استان از تاریخ ' . $date_period_from_total_year . '/' . $date_period_from_total_month . '/' . $date_period_from_total_day . ' تا تاریخ ' . $date_period_to_total_year . '/' . $date_period_to_total_month . '/' . $date_period_to_total_day;
				$unitIds = array();
				$resTowns = Database::execute_query("SELECT `id` FROM `towns` WHERE `state-id` = '" . $_SESSION['user']['acl-id'] . "';");
				while ($rowTowns = Database::get_assoc_array($resTowns)) {
					$resCenters = Database::execute_query("SELECT `id` FROM `centers` WHERE `town-id` = '" . $rowTowns['id'] . "';");
					while ($rowCenters = Database::get_assoc_array($resCenters)) {
						$resUnits = Database::execute_query("SELECT `id` FROM `units` WHERE `center-id` = '" . $rowCenters['id'] . "' AND `name` = '" . Database::filter_str($_POST['unitName']) . "';");
						while ($rowUnits = Database::get_assoc_array($resUnits))
							$unitIds[] = $rowUnits['id'];
					}
				}
				$tmp = self::getTotalUnitChrages($unitIds['0'], $date_period_from_total_day, $date_period_from_total_month, $date_period_from_total_year, $date_period_to_total_day, $date_period_to_total_month, $date_period_to_total_year);
				$sum = 0;
				foreach ($tmp as $tmpValue)
					$sum += $tmpValue['totalCost'];
				$sum = round($sum / 1000);
				$min = array('name' => $row['name'], 'totalCost' => $sum);
				$max = array('name' => $row['name'], 'totalCost' => $sum);
				$allUnitsSum = 0;
				foreach ($unitIds as $value) {
					$tmp = self::getTotalUnitChrages($value, $date_period_from_total_day, $date_period_from_total_month, $date_period_from_total_year, $date_period_to_total_day, $date_period_to_total_month, $date_period_to_total_year);
					$sum = 0;
					foreach ($tmp as $tmpValue)
						$sum += $tmpValue['totalCost'];
					$sum = round($sum / 1000);
					$res = Database::execute_query("SELECT `center-id` FROM `units` WHERE `id` = '$value';");
					$row = Database::get_assoc_array($res);
					$res = Database::execute_query("SELECT `name` FROM `centers` WHERE `id` = '" . $row['center-id'] . "';");
					$row = Database::get_assoc_array($res);
					if ($sum >= $max['totalCost']) {
						$max['name'] = $row['name'];
						$max['totalCost'] = $sum;
					}
					if ($sum < $min['totalCost'] and $sum > 0) {
						$min['name'] = $row['name'];
						$min['totalCost'] = $sum;
					}
					$allCentersSum += $sum;
					if($sum > 0)
						$finalTotalCosts[] = array('name' => $row['name'], 'totalCost' => $sum);
				}
				$finalMax = $max;
				$finalMin = $min;
				$finalAverage = round($allCentersSum / count($finalTotalCosts));
				break;
			case '3' :
				//a specified service in state
				$selectedServiceId = $_POST['serviceName'];
				$resService = Database::execute_query("SELECT `name` FROM `available-services` WHERE `id` = '$selectedServiceId';");
				$rowService = Database::get_assoc_array($resService);
				$serviceName = $rowService['name'];
				$chartTitle = 'نمودار مقایسه ی مجموع هزینه های خدمت <' . $serviceName . '> در کل استان از تاریخ ' . $date_period_from_total_year . '/' . $date_period_from_total_month . '/' . $date_period_from_total_day . ' تا تاریخ ' . $date_period_to_total_year . '/' . $date_period_to_total_month . '/' . $date_period_to_total_day;
				$centers = array();
				$unitRes = Database::execute_query("SELECT `unit-id` FROM `unit-services` WHERE `service-id` = '$selectedServiceId';");
				while ($unitRow = Database::get_assoc_array($unitRes)) {
					$centerRes = Database::execute_query("SELECT `center-id`, `name` FROM `units` WHERE `id` = '" . $unitRow['unit-id'] . "';");
					$centerRow = Database::get_assoc_array($centerRes);
					$resDB = Database::execute_query("SELECT `name` FROM `centers` WHERE `id` = '" . $centerRow['center-id'] . "';");
					$rowDB = Database::get_assoc_array($resDB);
					$centers[] = array('unitId' => $unitRow['unit-id'], 'unitName' => $centerRow['name'], 'parentCenterId' => $centerRow['center-id'], 'parentCenterName' => $rowDB['name']);
				}
				$allCentersSum = 0;
				$min = array('name' => $value['parentCenterName'], 'totalCost' => 999999999999999999999999999999999);
				$max = array('name' => $value['parentCenterName'], 'totalCost' => 0);
				foreach ($centers as $value) {
					$totalCenterChargeWithDate = self::getCenterTotalCharges($value['parentCenterId'], $date_period_from_total_day, $date_period_from_total_month, $date_period_from_total_year, $date_period_to_total_day, $date_period_to_total_month, $date_period_to_total_year);
					$totalCenterCharge = 0;
					foreach ($totalCenterChargeWithDate as $val)
						$totalCenterCharge += $val['totalCost'];
					$resNumOfUnits = Database::execute_query("SELECT `id` FROM `units` WHERE `center-id` = '" . $value['parentCenterId'] . "';");
					$numOfUnits = Database::num_of_rows($resNumOfUnits);
					$centerChargePerUnit = $totalCenterCharge / $numOfUnits;
					$overallUnitCharge = 0;
					$unitChargesWithDate = self::getTotalUnitChrages($value['unitId'], $date_period_from_total_day, $date_period_from_total_month, $date_period_from_total_year, $date_period_to_total_day, $date_period_to_total_month, $date_period_to_total_year);
					$totalUnitCharge = 0;
					foreach ($unitChargesWithDate as $val)
						$totalUnitCharge += $val['totalCost'];
					$overallUnitCharge = $totalUnitCharge + $centerChargePerUnit;
					$inRangeUnitServicesFrequency = array();
					$totalUnitServiceTime = 0;
					$servicesFrequencyRes = Database::execute_query("SELECT `a`.`id`, `a`.`name`, `a`.`cost`, `a`.`average-time`, `b`.`service-frequency`, `b`.`period-start-date`, `b`.`period-finish-date` FROM `available-services` AS `a` INNER JOIN `unit-done-services` AS `b` ON `a`.`id` = `b`.`service-id` WHERE `b`.`unit-id` = '" . $value['unitId'] . "' ORDER BY `name` ASC;");
					while ($row = Database::get_assoc_array($servicesFrequencyRes)) {
						$periodStartDate = date_parse($row['period-start-date']);
						$periodFinishDate = date_parse($row['period-finish-date']);
						if (self::isInDateRange($periodStartDate['day'], $periodStartDate['month'], $periodStartDate['year'], $date_period_from_total_day, $date_period_from_total_month, $date_period_from_total_year, $date_period_to_total_day, $date_period_to_total_month, $date_period_to_total_year) and self::isInDateRange($periodFinishDate['day'], $periodFinishDate['month'], $periodFinishDate['year'], $date_period_from_total_day, $date_period_from_total_month, $date_period_from_total_year, $date_period_to_total_day, $date_period_to_total_month, $date_period_to_total_year))
							$totalUnitServiceTime += ($row['service-frequency'] * $row['average-time']);
					}
					$resDB = Database::execute_query("SELECT `id`, `name`, `average-time` FROM `available-services` WHERE `id` = '" . $selectedServiceId . "';");
					$rowDB = Database::get_assoc_array($resDB);
					$chargePerMinute = 0;
					if ($totalUnitServiceTime != 0)
						$chargePerMinute = $overallUnitCharge / ($totalUnitServiceTime * 1.1);
					else
						$chargePerMinute = 0;
					$serviceCost = $chargePerMinute * ($rowDB['average-time'] * 1.1);
					$serviceCost = round($serviceCost / 1000);
					if ($serviceCost >= $max['totalCost']) {
						$max['name'] = $value['parentCenterName'];
						$max['totalCost'] = $serviceCost;
					}
					if ($serviceCost < $min['totalCost'] and $serviceCost > 0) {
						$min['name'] = $value['parentCenterName'];
						$min['totalCost'] = $serviceCost;
					}
					$allCentersSum += $serviceCost;
					if ($serviceCost > 0)
						$finalTotalCosts[] = array('name' => $value['parentCenterName'], 'totalCost' => $serviceCost);
				}
				$finalMax = $max;
				$finalMin = $min;
				$finalAverage = $allCentersSum / count($finalTotalCosts);
				break;
		}

		if (count($finalTotalCosts) > 0){
		$htmlOut .= '<p><input type="button" value="چاپ نمودار" onclick="alert(\'لطفا مد Landscape را برای پرینت انتخاب کنید.\');window.print();return false;"></p>';
		$htmlOut .= '<br /><div id="chartDiv"></div>';
		$htmlOut .= '<script type="text/javascript" src="../illustrator/js/jquery.jqplot.min.js"></script>';
		$htmlOut .= '<script type="text/javascript" src="../illustrator/js/jqplot.barRenderer.min.js"></script>';
		$htmlOut .= '<script type="text/javascript" src="../illustrator/js/jqplot.pieRenderer.min.js"></script>';
		$htmlOut .= '<script type="text/javascript" src="../illustrator/js/jqplot.categoryAxisRenderer.min.js"></script>';
		$htmlOut .= '<script type="text/javascript" src="../illustrator/js/jqplot.pointLabels.min.js"></script>';
		$htmlOut .= '<script type="text/javascript">';

		//round values
		$tmpArr = array();
		//must clear /1000
		foreach ($finalTotalCosts as $value) {
			$tmpArr[] = array('name' => $value['name'], 'totalCost' => $value['totalCost']);
		}
		$finalTotalCosts = $tmpArr;

		$htmlOut .= "$(document).ready(function(){
        $.jqplot.config.enablePlugins = true;
        var s1 = [";
		foreach ($finalTotalCosts as $value)
			$htmlOut .= $value['totalCost'] . ", ";
		$htmlOut .= "]; var ticks = [";
		foreach ($finalTotalCosts as $value)
			$htmlOut .= "'" . $value['name'] . "', ";
		$htmlOut .= "];
        
        plot1 = $.jqplot('chartDiv', [s1], {
            animate: !$.jqplot.use_excanvas,
            title: '$chartTitle',
            seriesDefaults:{
                renderer:$.jqplot.BarRenderer,
                pointLabels: { show: true }
            },
            axes: {
                xaxis: {
                    renderer: $.jqplot.CategoryAxisRenderer,
                    ticks: ticks
                }
            },
            highlighter: { show: false }
        });});";

		$htmlOut .= '</script>';

		//preparing data for variance function
		$varArray = array();
		foreach ($finalTotalCosts as $value) {
			$varArray[] = $value['totalCost'];
		}
		$variance = self::variance($varArray);

		$htmlOut .= '<br /><br /><fieldset><legend>جزئیات آماری نمودار</legend>';
		$htmlOut .= '<p><span style="font-weight: bold;">حداکثر هزینه در : </span>"' . $finalMax['name'] . '"<span style="font-weight: bold;"> به مبلغ: </span>' . round($finalMax['totalCost']) . ' هزار ريال؛</p>';
		$htmlOut .= '<p><span style="font-weight: bold;">حداقل هزینه در : </span>"' . $finalMin['name'] . '"<span style="font-weight: bold;"> به مبلغ: </span>' . round($finalMin['totalCost']) . ' هزار ريال؛</p>';
		$htmlOut .= '<p><span style="font-weight: bold;">میانگین هزینه ها: </span>' . round($finalAverage) . ' هزار ريال؛</p>';
		$htmlOut .= '<p><span style="font-weight: bold;">واریانس: </span>' . round($variance) . '</p>';
		$htmlOut .= '<p><span style="font-weight: bold;">انحراف معیار: </span>' . round(sqrt($variance)) . '</p>';
		$htmlOut .= '</fieldset>';
		}
		else
			return '<p class="warning">هیچ مورد ثبت شده ای برای نمایش وجود ندارد.</p>';

		return $htmlOut;
	}

	//USD
	public static function drawFifthTypeChart() {
		$htmlOut = '';

		$date_period_from_incomes_day = $_POST['start_date_incomes:d'];
		$date_period_from_incomes_month = $_POST['start_date_incomes:m'];
		$date_period_from_incomes_year = $_POST['start_date_incomes:y'];

		$date_period_to_incomes_day = $_POST['finish_date_incomes:d'];
		$date_period_to_incomes_month = $_POST['finish_date_incomes:m'];
		$date_period_to_incomes_year = $_POST['finish_date_incomes:y'];

		$greg_from_date = jalali_to_gregorian($date_period_from_incomes_year, $date_period_from_incomes_month, $date_period_from_incomes_day);
		$greg_to_date = jalali_to_gregorian($date_period_to_incomes_year, $date_period_to_incomes_month, $date_period_to_incomes_day);
		$from_date = Date(mktime(0, 0, 0, $greg_from_date['1'], $greg_from_date['2'], $greg_from_date['0']));
		$to_date = Date(mktime(0, 0, 0, $greg_to_date['1'], $greg_to_date['2'], $greg_to_date['0']));
		$from_to_gap = self::count_days($to_date, $from_date);
		if ($from_to_gap <= 60)
			return '<p id="err">خطا در انتخاب بازه زمانی.<br />فاصله ی دو تاریخ باید حداقل 60 روز باشد.</p>';

		if (isset($_POST['incomes']))
			$sourceType = $_POST['incomes'];
		else
			return '<p id="err">هیچ منبعی برای گزارش انتخاب نشده است.</p>';

		$finalTotalIncome = array();
		$chartTitle = '';
		switch ($sourceType) {
			case '1' :
				//all state centers
				$chartTitle = 'نمودار مقایسه ی مجموع درآمدهای مراکز استان از تاریخ ' . $date_period_from_incomes_year . '/' . $date_period_from_incomes_month . '/' . $date_period_from_incomes_day . ' تا تاریخ ' . $date_period_to_incomes_year . '/' . $date_period_to_incomes_month . '/' . $date_period_to_incomes_day;
				$resTowns = Database::execute_query("SELECT `id` FROM `towns` WHERE `state-id` = '" . $_SESSION['user']['acl-id'] . "';");
				while ($rowTowns = Database::get_assoc_array($resTowns)) {
					$resCenters = Database::execute_query("SELECT `id`, `name` FROM `centers` WHERE `town-id` = '" . $rowTowns['id'] . "';");
					while ($rowCenters = Database::get_assoc_array($resCenters)) {
						$currentCenterIncome = self::getCenterIncome($rowCenters['id'], $date_period_from_incomes_day, $date_period_from_incomes_month, $date_period_from_incomes_year, $date_period_to_incomes_day, $date_period_to_incomes_month, $date_period_to_incomes_year);
						$finalTotalIncome[] = array('name' => $rowCenters['name'], 'totalIncome' => round($currentCenterIncome / 1000));
					}
				}
				break;
			case '2' :
				//all specified units in state
				$chartTitle = 'نمودار مقایسه ی مجموع درآمدهای واحد <' . $_POST['unitName'] . '> در کل استان از تاریخ ' . $date_period_from_incomes_year . '/' . $date_period_from_incomes_month . '/' . $date_period_from_incomes_day . ' تا تاریخ ' . $date_period_to_incomes_year . '/' . $date_period_to_incomes_month . '/' . $date_period_to_incomes_day;
				$resTowns = Database::execute_query("SELECT `id` FROM `towns` WHERE `state-id` = '" . $_SESSION['user']['acl-id'] . "';");
				while ($rowTowns = Database::get_assoc_array($resTowns)) {
					$resCenters = Database::execute_query("SELECT `id`, `name` FROM `centers` WHERE `town-id` = '" . $rowTowns['id'] . "';");
					while ($rowCenters = Database::get_assoc_array($resCenters)) {
						$resUnits = Database::execute_query("SELECT `id` FROM `units` WHERE `center-id` = '" . $rowCenters['id'] . "' AND `name` = '" . Database::filter_str($_POST['unitName']) . "';");
						$rowUnits = Database::get_assoc_array($resUnits);
						$currentUnitIncome = self::getUnitIncome($rowUnits['id'], $date_period_from_incomes_day, $date_period_from_incomes_month, $date_period_from_incomes_year, $date_period_to_incomes_day, $date_period_to_incomes_month, $date_period_to_incomes_year);
						$finalTotalIncome[] = array('name' => $rowCenters['name'], 'totalIncome' => round($currentUnitIncome / 1000));
					}
				}
				break;
		}

//var_dump($finalTotalIncome);exit;

		if (isset($_POST['export5FileType'])) {
			$fromDate = $date_period_from_incomes_year . '/' . $date_period_from_incomes_month . '/' . $date_period_from_incomes_day;
			$toDate = $date_period_to_incomes_year . '/' . $date_period_to_incomes_month . '/' . $date_period_to_incomes_day;
			switch ($_POST['export5FileType']) {
				case '1' :
					Export::makeIncomeCSVExport($finalTotalIncome, $fromDate, $toDate);
					break;
					
				case '2' :
					Export::makeIncomeExcelExport($finalTotalIncome, $fromDate, $toDate);
					break;
				
				case '3' :
					Export::makeIncomeXMLExport($finalTotalIncome, $fromDate, $toDate);
					break;
			}
			//return;
		}

		$htmlOut .= '<p><input type="button" value="چاپ نمودار" onclick="alert(\'لطفا مد Landscape را برای پرینت انتخاب کنید.\');window.print();return false;"></p>';
		$htmlOut .= '<br /><div id="chartDiv"></div>';
		$htmlOut .= '<script type="text/javascript" src="../illustrator/js/jquery.jqplot.min.js"></script>';
		$htmlOut .= '<script type="text/javascript" src="../illustrator/js/jqplot.barRenderer.min.js"></script>';
		$htmlOut .= '<script type="text/javascript" src="../illustrator/js/jqplot.pieRenderer.min.js"></script>';
		$htmlOut .= '<script type="text/javascript" src="../illustrator/js/jqplot.categoryAxisRenderer.min.js"></script>';
		$htmlOut .= '<script type="text/javascript" src="../illustrator/js/jqplot.pointLabels.min.js"></script>';
		$htmlOut .= '<script type="text/javascript">';

		$htmlOut .= "$(document).ready(function(){
        $.jqplot.config.enablePlugins = true;
        var s1 = [";
		foreach ($finalTotalIncome as $value)
			$htmlOut .= $value['totalIncome'] . ", ";
		$htmlOut .= "]; var ticks = [";
		foreach ($finalTotalIncome as $value)
			$htmlOut .= "'" . $value['name'] . "', ";
		$htmlOut .= "];
        
        plot1 = $.jqplot('chartDiv', [s1], {
            animate: !$.jqplot.use_excanvas,
            title: '$chartTitle',
            seriesDefaults:{
                renderer:$.jqplot.BarRenderer,
                pointLabels: { show: true }
            },
            axes: {
                xaxis: {
                    renderer: $.jqplot.CategoryAxisRenderer,
                    ticks: ticks
                }
            },
            highlighter: { show: false }
        });});";

		$htmlOut .= '</script>';

		//preparing data for variance function
		$varArray = array();
		foreach ($finalTotalIncome as $value)
			$varArray[] = $value['totalIncome'];
		$variance = self::variance($varArray);

		$finalMax = array('name' => $finalTotalIncome['0']['name'], 'totalIncome' => $finalTotalIncome['0']['totalIncome']);
		$finalMin = array('name' => $finalTotalIncome['0']['name'], 'totalIncome' => $finalTotalIncome['0']['totalIncome']);
		
		for ($i = 1; $i < count($finalTotalIncome); $i++) {
			if ($finalTotalIncome[$i]['totalIncome'] > $finalMax['totalIncome']) {
				$finalMax['name'] = $finalTotalIncome[$i]['name'];
				$finalMax['totalIncome'] = $finalTotalIncome[$i]['totalIncome'];
			}
			if ($finalTotalIncome[$i]['totalIncome'] < $finalMin['totalIncome']) {
				$finalMin['name'] = $finalTotalIncome[$i]['name'];
				$finalMin['totalIncome'] = $finalTotalIncome[$i]['totalIncome'];
			}
		}
		
		$sum = 0;
		foreach ($finalTotalIncome as $value)
			$sum += $value['totalIncome'];
		$finalAverage = $sum / count($finalTotalIncome);
		

		$htmlOut .= '<br /><br /><fieldset><legend>جزئیات آماری نمودار</legend>';
		$htmlOut .= '<p><span style="font-weight: bold;">حداکثر درآمد در : </span>"' . $finalMax['name'] . '"<span style="font-weight: bold;"> به مبلغ: </span>' . round($finalMax['totalIncome']) . ' هزار ريال؛</p>';
		$htmlOut .= '<p><span style="font-weight: bold;">حداقل درآمد در : </span>"' . $finalMin['name'] . '"<span style="font-weight: bold;"> به مبلغ: </span>' . round($finalMin['totalIncome']) . ' هزار ريال؛</p>';
		$htmlOut .= '<p><span style="font-weight: bold;">میانگین درآمدها: </span>' . round($finalAverage) . ' هزار ريال؛</p>';
		$htmlOut .= '<p><span style="font-weight: bold;">واریانس: </span>' . round($variance) . '</p>';
		$htmlOut .= '<p><span style="font-weight: bold;">انحراف معیار: </span>' . round(sqrt($variance)) . '</p>';
		$htmlOut .= '</fieldset>';

		return $htmlOut;
	}

	//USD
	public static function drawServicesChart($centersId, $serviceId, $date_period_from_day, $date_period_from_month, $date_period_from_year, $date_period_to_day, $date_period_to_month, $date_period_to_year) {
		$htmlOut = '';
		$result = array();
		if (!is_numeric($serviceId))
			return '<p id="err">لطفا خدمت موردنظر را انتخاب کنید.</p>';
		else {
			foreach ($centersId as $value) {
				//trying to sum center charges
				$centerId = $value;
				$datePeriodArray = self::createDatePeriodArray($date_period_from_day, $date_period_from_month, $date_period_from_year, $date_period_to_day, $date_period_to_month, $date_period_to_year);
				$buildingsCharge = array();
				$vehiclesCharge = array();
				$generalCharge = array();
				$nonConsumingsCharge = array();
				$drugsCharge = array();
				$totalCenterChargeWithDate = array();
				$res = Database::execute_query("SELECT `id`, `name` FROM `units` WHERE `center-id` = '$centerId';");
				$numOfUnits = Database::num_of_rows($res);
				$buildingsCharge = self::getCenterBuildingsCharge($value, $date_period_from_day, $date_period_from_month, $date_period_from_year, $date_period_to_day, $date_period_to_month, $date_period_to_year);
				$vehiclesCharge = self::getCenterVehiclesCharge($value, $date_period_from_day, $date_period_from_month, $date_period_from_year, $date_period_to_day, $date_period_to_month, $date_period_to_year);
				$generalCharge = self::getCenterGeneralCharges($value, $date_period_from_day, $date_period_from_month, $date_period_from_year, $date_period_to_day, $date_period_to_month, $date_period_to_year);
				$nonConsumingsCharge = self::getCenterNonConsumingsCharge($value, $date_period_from_day, $date_period_from_month, $date_period_from_year, $date_period_to_day, $date_period_to_month, $date_period_to_year);
				$drugsCharge = self::getCenterDrugsCharge($value, $date_period_from_day, $date_period_from_month, $date_period_from_year, $date_period_to_day, $date_period_to_month, $date_period_to_year);
				for ($i = 0; $i < count($datePeriodArray); $i++) {
					$totalCenterChargeWithDate[] = array('year' => $datePeriodArray[$i]['year'], 'month' => $datePeriodArray[$i]['month'], 'totalCost' => 0);
					$totalCenterChargeWithDate[$i]['totalCost'] += $buildingsCharge[$i]['totalCost'];
					$totalCenterChargeWithDate[$i]['totalCost'] += $vehiclesCharge[$i]['totalCost'];
					$totalCenterChargeWithDate[$i]['totalCost'] += $generalCharge[$i]['totalCost'];
					$totalCenterChargeWithDate[$i]['totalCost'] += $nonConsumingsCharge[$i]['totalCost'];
					$totalCenterChargeWithDate[$i]['totalCost'] += $drugsCharge[$i]['totalCost'];
				}
				$totalCenterCharge = 0;
				foreach ($totalCenterChargeWithDate as $value)
					$totalCenterCharge += $value['totalCost'];
				$centerChargePerUnit = $totalCenterCharge / $numOfUnits;
				$overallUnitCharge = array();
				while ($row = Database::get_assoc_array($res)) {
					$unitChargesWithDate = self::getTotalUnitChrages($row['id'], $date_period_from_day, $date_period_from_month, $date_period_from_year, $date_period_to_day, $date_period_to_month, $date_period_to_year);
					$totalUnitCharge = 0;
					foreach ($unitChargesWithDate as $value)
						$totalUnitCharge += $value['totalCost'];
					$overallUnitCharge[] = array('id' => $row['id'], 'name' => $row['name'], 'charge' => ($totalUnitCharge + $centerChargePerUnit));
				}
				$overallUnitChargeWithTotalServiceTime = array();
				foreach ($overallUnitCharge as $value) {
					$inRangeUnitServicesFrequency = array();
					$servicesFrequencyRes = Database::execute_query("SELECT `a`.`id`, `a`.`name`, `a`.`cost`, `a`.`average-time`, `b`.`service-frequency`, `b`.`period-start-date`, `b`.`period-finish-date` FROM `available-services` AS `a` INNER JOIN `unit-done-services` AS `b` ON `a`.`id` = `b`.`service-id` WHERE `b`.`unit-id` = '" . $value['id'] . "' ORDER BY `name` ASC;");
					while ($row = Database::get_assoc_array($servicesFrequencyRes)) {
						$periodStartDate = date_parse($row['period-start-date']);
						$periodFinishDate = date_parse($row['period-finish-date']);
						if (self::isInDateRange($periodStartDate['day'], $periodStartDate['month'], $periodStartDate['year'], $date_period_from_day, $date_period_from_month, $date_period_from_year, $date_period_to_day, $date_period_to_month, $date_period_to_year) and self::isInDateRange($periodFinishDate['day'], $periodFinishDate['month'], $periodFinishDate['year'], $date_period_from_day, $date_period_from_month, $date_period_from_year, $date_period_to_day, $date_period_to_month, $date_period_to_year))
							$inRangeUnitServicesFrequency[] = array('id' => $row['id'], 'name' => $row['name'], 'cost' => $row['cost'], 'time' => $row['average-time'], 'freq' => $row['service-frequency']);
					}
					$totalUnitServiceTime = 0;
					if (count($inRangeUnitServicesFrequency) > 0) {
						foreach ($inRangeUnitServicesFrequency as $time)
							$totalUnitServiceTime += ($time['time'] * $time['freq']);
					}
					$overallUnitChargeWithTotalServiceTime[] = array('id' => $value['id'], 'name' => $value['name'], 'charge' => $value['charge'], 'totalServiceTime' => $totalUnitServiceTime);
				}

				$res1 = Database::execute_query("SELECT `id`, `name` FROM `units` WHERE `center-id` = '$centerId';");
				$unitId;
				while ($row1 = Database::get_assoc_array($res1)) {
					$res2 = Database::execute_query("SELECT `id` FROM `unit-services` WHERE `service-id` = '$serviceId' AND `unit-id` = '" . $row1['id'] . "';");
					if (Database::num_of_rows($res2) > 0)
						$unitId = $row1['id'];
				}
				foreach ($overallUnitChargeWithTotalServiceTime as $value) {
					if ($value['id'] == $unitId) {
						$resDB = Database::execute_query("SELECT `id`, `name`, `average-time` FROM `available-services` WHERE `id` = '" . $serviceId . "';");
						$rowDB = Database::get_assoc_array($resDB);
						$chargePerMinute = 0;
						if ($value['totalServiceTime'] != 0)
							$chargePerMinute = $value['charge'] / ($value['totalServiceTime'] * 1.1);
						else
							$chargePerMinute = 0;
						$serviceCost = $chargePerMinute * ($rowDB['average-time'] * 1.1);
						$res1 = Database::execute_query("SELECT `id`, `name` FROM `centers` WHERE `id` = '$centerId';");
						$row1 = Database::get_assoc_array($res1);
						$result[] = array('centerId' => $centerId, 'centerName' => $row1['name'], 'serviceCost' => $serviceCost);
					}
				}
			}//end foreach

			//round values
			for ($T = 0; $T < count($result); $T++)
				$result[$T]['serviceCost'] = round($result[$T]['serviceCost'] / 1000);

			$htmlOut .= '<div id="chart1"></div>';
			$htmlOut .= '<script class="include" type="text/javascript" src="../illustrator/js/jqplot.barRenderer.min.js"></script>';
			$htmlOut .= '<script type="text/javascript" src="../illustrator/js/jqplot.categoryAxisRenderer.min.js"></script>';
			$htmlOut .= '<script type="text/javascript">';
			$htmlOut .= '$(document).ready(function(){
	    		$.jqplot.config.enablePlugins = true;
    	    	var s1 = [';
			foreach ($result as $value)
				$htmlOut .= $value['serviceCost'] . ', ';
			$htmlOut .= '];
        		var ticks = [';
			foreach ($result as $value)
				$htmlOut .= '\'' . $value['centerName'] . '\', ';
			$htmlOut .= '];
        		plot1 = $.jqplot(\'chart1\', [s1], {
            		seriesDefaults:{
                		renderer:$.jqplot.BarRenderer,
                		pointLabels: { show: true }
            		},
            		axes: {
                		xaxis: {
                    		renderer: $.jqplot.CategoryAxisRenderer,
                    		ticks: ticks
                		}
            		},
            		highlighter: { show: false }
        		});
     
    			});';
			$htmlOut .= '</script>';
		}//end else

		return $htmlOut;
	}

	//USD
	public static function getUnitIncome($unitId, $fromDay, $fromMonth, $fromYear, $toDay, $toMonth, $toYear) {
		$totalUnitIncome = 0;
		$res = Database::execute_query("SELECT `a`.`service-frequency`, `a`.`period-start-date`, `a`.`period-finish-date`, `b`.`cost` FROM `unit-done-services` AS `a` INNER JOIN `available-services` AS `b` ON `a`.`service-id` = `b`.`id` WHERE `a`.`unit-id` = '$unitId';");
		while ($row = Database::get_assoc_array($res)) {
			$periodStartDate = date_parse($row['period-start-date']);
			$periodFinishDate = date_parse($row['period-finish-date']);
			if (self::isInDateRange($periodStartDate['day'], $periodStartDate['month'], $periodStartDate['year'], $fromDay, $fromMonth, $fromYear, $toDay, $toMonth, $toYear) and self::isInDateRange($periodFinishDate['day'], $periodFinishDate['month'], $periodFinishDate['year'], $fromDay, $fromMonth, $fromYear, $toDay, $toMonth, $toYear))
				$totalUnitIncome += ($row['service-frequency'] * $row['cost']);
		}

		return $totalUnitIncome;
	}

	//USD
	public static function getCenterIncome($centerId, $fromDay, $fromMonth, $fromYear, $toDay, $toMonth, $toYear) {
		$totalCenterIncome = 0;
		$res = Database::execute_query("SELECT `id` FROM `units` WHERE `center-id` = '$centerId';");
		while ($row = Database::get_assoc_array($res)) {
			$CurrentUnitIncome = self::getUnitIncome($row['id'], $fromDay, $fromMonth, $fromYear, $toDay, $toMonth, $toYear);
			$totalCenterIncome += $CurrentUnitIncome;
		}

		return $totalCenterIncome;
	}

	//USD
	public static function variance($a) {
		if (count($a) <= 1)
			return 0;
		//variable and initializations
		$the_variance = 0.0;
		$the_mean = 0.0;
		$the_array_sum = array_sum($a);
		//sum the elements
		$number_elements = count($a);
		//count the number of elements

		//calculate the mean
		$the_mean = $the_array_sum / $number_elements;

		//calculate the variance
		for ($i = 0; $i < $number_elements; $i++) {
			//sum the array
			$the_variance += pow(($a[$i] - $the_mean), 2);
		}

		$the_variance = $the_variance / ($number_elements - 1.0);

		//return the variance
		return $the_variance;
	}

}//end AsynchronousProcess class
?>