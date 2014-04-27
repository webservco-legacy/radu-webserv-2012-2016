<?php
/**
 * $Id$
 *
 * Car module.
 *
 * @author     Radu Maier <radu@webserv.co>
 * @copyright  Copyright (c) 2008 - 2013, Radu Maier
 * @license    http://www.opensource.org/licenses/bsd-license.php
 * @package    RaduApp
 * @subpackage CarModule
 * @version    $Revision: 108 $
 * @since      Version 7
 * @access     public
 * @filesource
 */
if (!defined('_WEB')) {
	exit();
}

/**
 * Module_Car
 * Model class for Car module.
 *
 * @author Radu Maier <radu@webserv.co>
 * @package RaduApp
 * @subpackage CarModule
 */
class Module_Car extends ModuleApp {

	public function GetCarInfo($carId) {

		$q = "SELECT U.user_name_first AS car_owner, C.car_name, C.car_make, C.car_model, YEAR(C.car_factory_date) AS car_year, C.car_factory_date, C.car_owner_date, C.car_fuel_tank
		FROM r_car_main AS C
		LEFT JOIN r_acl_object_user AS U ON (U.user_id = C.user_id)
		WHERE C.car_id = '{$carId}' LIMIT 1";
		
		$rs = Core::Database()->Query($q);
		
		$t = $rs->num_rows;
		
		if (1 == $t) {
			
			return $rs->fetch_assoc();
		}
		
		return array();
	}

	public function GetCarLog($carId) {

		$data = array();
		
		$q = "SELECT L.log_id, L.log_date_start, L.log_date_end, L.log_km, L.log_public, D.log_description, D.log_notes
		FROM r_car_log_main AS L
		LEFT JOIN r_car_log_details AS D ON (D.log_id = L.log_id AND D.i18n_locale = 'ro_RO.UTF8')
		WHERE L.car_id = '{$carId}'
		ORDER BY L.log_date_start DESC, L.log_id DESC";
		
		$rs = Core::Database()->Query($q);
		
		$t = $rs->num_rows;
		
		if (0 < $t) {
			
			while ($r = $rs->fetch_assoc()) {
				
				$data[$r['log_id']] = $r;
			}
		}
		
		return $data;
	}

	public function GetLogTypes() {

		$data = array();
		
		$q = "SELECT M.cost_type_id, D.cost_type_title
			FROM r_car_cost_types_main AS M
				LEFT JOIN r_car_cost_types_details AS D ON (D.cost_type_id = M.cost_type_id AND D.i18n_locale = 'ro_RO.UTF8')
				ORDER BY M.cost_type_id ASC";
		
		$rs = Core::Database()->Query($q);
		
		$t = $rs->num_rows;
		
		if (0 < $t) {
			
			while ($r = $rs->fetch_assoc()) {
				
				$data[$r['cost_type_id']] = $r['cost_type_title'];
			}
		}
		
		return $data;
	}

	public function GetStatsLastUpdate($carId) {

		$q = "SELECT MAX(log_date_start) AS latest
			FROM r_car_log_main
			WHERE car_id = '{$carId}'";
		
		$rs = Core::Database()->Query($q);
		
		$t = $rs->num_rows;
		
		if (1 == $t) {
			
			$r = $rs->fetch_array(MYSQLI_NUM);
			
			return $r[0];
		}
		
		return FALSE;
	}

	public function GetStatsDuration($startDay, $endDay = NULL) {

		$start_day = new DateTime($startDay);
		
		$end_day = !empty($endDay) ? new DateTime($endDay) : new DateTime();
		
		$diff = $start_day->diff($end_day);
		
		return array(
				$diff->format('%y'),
				$diff->format('%m'),
				$diff->format('%d') 
		);
	}

	public function GetStatsDistance($carId, $startDay, $endDay = NULL) {

		$end_day = !empty($endDay) ? $endDay : date('Y-m-d');
		
		$q = "SELECT (MAX(log_km) - MIN(log_km)) AS distance
				FROM r_car_log_main
					WHERE car_id = '{$carId}' AND log_km > 0 AND log_date_start BETWEEN '{$startDay}' AND '{$end_day}'";
		
		$rs = Core::Database()->Query($q);
		
		$t = $rs->num_rows;
		
		if (1 == $t) {
			
			$r = $rs->fetch_array(MYSQLI_NUM);
			
			return !empty($r[0]) ? $r[0] : 0;
		}
		
		return 0;
	}

	public function GetStatsFuelVolume($carId, $startDay, $endDay = NULL, $fuelInTank = 0) {

		$end_day = !empty($endDay) ? $endDay : date('Y-m-d');
		
		$q = "SELECT (SUM(D.cost_quantity) - {$fuelInTank})
				FROM r_car_log_main AS M
				LEFT JOIN r_car_log_costs_main AS D ON (D.log_id = M.log_id)
				WHERE M.car_id = '{$carId}' AND M.log_date_start BETWEEN '{$startDay}' AND '{$end_day}' AND D.cost_type_id = '1'";
		
		$rs = Core::Database()->Query($q);
		
		$t = $rs->num_rows;
		
		if (1 == $t) {
			
			$r = $rs->fetch_array(MYSQLI_NUM);
			
			return $r[0];
		}
		
		return 0;
	}

	public function GetStatsFuelConsumption($distance, $fuelVolume) {

		return !empty($distance) ? round($fuelVolume * 100 / $distance, 2) : 0;
	}

	public function GetStatsCostKm($carId, $startDay, $endDay = NULL) {

		$end_day = !empty($endDay) ? $endDay : date('Y-m-d');
		
		$distance = $this->GetStatsDistance($carId, $startDay, $end_day);
		
		$q = "SELECT ROUND(
						SUM(cost_total) /
				(
				{$distance}
				),
				2) as cost_per_km
				FROM r_car_log_main AS M
				LEFT JOIN r_car_log_costs_main AS D ON (D.log_id = M.log_id)
				WHERE M.car_id = '{$carId}' AND M.log_date_start BETWEEN '{$startDay}' AND '{$end_day}'";
		
		$rs = Core::Database()->Query($q);
		
		$t = $rs->num_rows;
		
		if (1 == $t) {
			
			$r = $rs->fetch_array(MYSQLI_NUM);
			
			return !empty($r[0]) ? $r[0] : 0;
		}
		
		return 0;
	}

	public function GetStatsCostMonth($carId, $startDay, $endDay = NULL) {

		$end_day = !empty($endDay) ? $endDay : date('Y-m-d');
		
		$q = "SELECT ROUND(
						SUM(D.cost_total) / TIMESTAMPDIFF(MONTH, '{$startDay}', '{$end_day}')
						, 2) as cost_per_month
						FROM r_car_log_main AS M
						LEFT JOIN r_car_log_costs_main AS D ON (D.log_id = M.log_id)
						WHERE M.car_id = '{$carId}' AND M.log_date_start BETWEEN '{$startDay}' AND '{$end_day}'";
		
		$rs = Core::Database()->Query($q);
		
		$t = $rs->num_rows;
		
		if (1 == $t) {
			
			$r = $rs->fetch_array(MYSQLI_NUM);
			
			return !empty($r[0]) ? $r[0] : 0;
		}
		
		return 0;
	}

	public function GetStatsCostTotal($carId, $startDay, $endDay = NULL) {

		$end_day = !empty($endDay) ? $endDay : date('Y-m-d');
		
		$q = "SELECT SUM(cost_total) AS total
				FROM r_car_log_main AS M
				LEFT JOIN r_car_log_costs_main AS D ON (D.log_id = M.log_id)
				WHERE M.car_id = '{$carId}' AND M.log_date_start BETWEEN '{$startDay}' AND '{$end_day}'";
		
		$rs = Core::Database()->Query($q);
		
		$t = $rs->num_rows;
		
		if (1 == $t) {
			
			$r = $rs->fetch_array(MYSQLI_NUM);
			
			return $r[0];
		}
		
		return 0;
	}

	public function GetStatsCostPrevious($carId, $startDay) {

		$q = "SELECT SUM(cost_total) AS total
				FROM r_car_log_main AS M
				LEFT JOIN r_car_log_costs_main AS D ON (D.log_id = M.log_id)
				WHERE M.car_id = '{$carId}' AND log_date_start < '{$startDay}'";
		
		$rs = Core::Database()->Query($q);
		
		$t = $rs->num_rows;
		
		if (1 == $t) {
			
			$r = $rs->fetch_array(MYSQLI_NUM);
			
			return $r[0];
		}
		
		return 0;
	}

	public function GetStatsCost($carId, $startDay, $endDay = NULL, $costType = 0) {

		$end_day = !empty($endDay) ? $endDay : date('Y-m-d');
		
		$q = "SELECT SUM(cost_total) AS total
					FROM r_car_log_main AS M
					LEFT JOIN r_car_log_costs_main AS D ON (D.log_id = M.log_id)
					WHERE M.car_id = '{$carId}' AND M.log_date_start BETWEEN '{$startDay}' AND '{$end_day}' AND D.cost_type_id = '{$costType}'";
		
		$rs = Core::Database()->Query($q);
		
		$t = $rs->num_rows;
		
		if (1 == $t) {
			
			$r = $rs->fetch_array(MYSQLI_NUM);
			
			return !empty($r[0]) ? $r[0] : 0;
		}
		
		return 0;
	}
}
?>