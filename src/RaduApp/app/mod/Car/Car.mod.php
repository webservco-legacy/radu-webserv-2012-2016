<?php
/**
 * $Id$
 *
 * Car module.
 *
 * @author     Radu Maier <radu@webserv.co>
 * @copyright  Copyright (c) 2008 - 2012, Radu Maier
 * @license    http://www.opensource.org/licenses/bsd-license.php
 * @package    RaduApp
 * @subpackage CarModule
 * @version    $Revision$
 * @since      Version 1
 * @access     public
 * @filesource
 */

/**
 * Security check.
 */
if (!defined('_WEB')) {
	/**
	 * Stop script execution.
	 */
	exit ();
}

/**
 * Module_Car
 *
 * Model class for Car module.
 *
 * @author     Radu Maier <radu@webserv.co>
 * @package    RaduApp
 * @subpackage CarModule
 *
 */
class Module_Car extends ModuleApp {

	public function GetCarInfo($carId) {

		$q = "SELECT U.user_nickname AS car_owner, C.car_name, C.car_make, C.car_model, YEAR(C.car_factory_date) AS car_year, C.car_factory_date, C.car_owner_date
				FROM r_car_main AS C
				LEFT JOIN r_acl_users AS U ON (U.user_id = C.user_id)
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

		$q = "SELECT L.id, L.day, L.type, T.type_name, L.km, L.cost, L.qty, L.notes
				FROM r_car_log AS L
				LEFT JOIN r_car_log_types AS T ON (T.type_id = L.type)
				WHERE L.car_id = '{$carId}'
				ORDER BY L.day DESC";

		$rs = Core::Database()->Query($q);

		$t = $rs->num_rows;

		if (0 < $t) {

			while ($r = $rs->fetch_assoc()) {

				$data[$r['id']] = $r;

			}
		}

		return $data;
	}
	
	public function GetLogTypes() {
		
		$data = array();
		
		$q = "SELECT type_id, type_name FROM r_car_log_types ORDER BY type_id ASC";
		
		$rs = Core::Database()->Query($q);
		
		$t = $rs->num_rows;
		
		if (0 < $t) {
		
			while ($r = $rs->fetch_assoc()) {
		
				$data[$r['type_id']] = $r['type_name'];
		
			}
		}
		
		return $data;
	}
	
	public function GetStatsLastUpdate($carId) {
	
		$q = "SELECT MAX(day) AS latest FROM r_car_log WHERE car_id = '{$carId}'";
	
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
			$diff->format('%d'),
		);
	}

	public function GetStatsDistance($carId, $startDay, $endDay = NULL) {

		$end_day = !empty($endDay) ? $endDay : date('Y-m-d');

		$q = "SELECT (MAX(km) - MIN(km)) AS distance
				FROM r_car_log
				WHERE car_id = '{$carId}' AND km > 0 AND day BETWEEN '{$startDay}' AND '{$end_day}'";

		$rs = Core::Database()->Query($q);

		$t = $rs->num_rows;

		if (1 == $t) {

			$r = $rs->fetch_array(MYSQLI_NUM);

			return !empty($r[0]) ? $r[0] : 0;
		}

		return 0;
	}

	public function GetStatsFuelVolume($carId, $startDay, $endDay = NULL) {

		$end_day = !empty($endDay) ? $endDay : date('Y-m-d');

		$q = "SELECT SUM(qty)
				FROM r_car_log
				WHERE car_id = '{$carId}' AND day BETWEEN '{$startDay}' AND '{$end_day}' AND type = '1'";

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
				SUM(cost) / ({$distance}), 
				2) as cost_per_km 
			FROM r_car_log WHERE car_id = '{$carId}' AND day BETWEEN '{$startDay}' AND '{$end_day}'";

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
				SUM(cost) / TIMESTAMPDIFF(MONTH, '{$startDay}', '{$end_day}')
				, 2) as cost_per_month 
				FROM r_car_log
				WHERE car_id = '{$carId}' AND day BETWEEN '{$startDay}' AND '{$end_day}'";
		
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
		
		$q = "SELECT SUM(cost) AS total
				FROM r_car_log
				WHERE car_id = '{$carId}' AND day BETWEEN '{$startDay}' AND '{$end_day}'";
		
		$rs = Core::Database()->Query($q);
		
		$t = $rs->num_rows;
		
		if (1 == $t) {
		
			$r = $rs->fetch_array(MYSQLI_NUM);
		
			return $r[0];
		}
		
		return 0;
	}

	public function GetStatsCostPrevious($carId, $startDay) {
		
		$q = "SELECT SUM(cost) AS total
				FROM r_car_log
				WHERE car_id = '{$carId}' AND day < '{$startDay}'";
		
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
		
		$q = "SELECT SUM(cost) AS total
				FROM r_car_log
				WHERE car_id = '{$carId}' AND day BETWEEN '{$startDay}' AND '{$end_day}' AND type = '{$costType}'";
		
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