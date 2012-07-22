<?php
/**
 * $Id$
 *
 * App controlller.
 *
 * Module: Car
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
 * Controller_Car_App
 *
 * App Controller for Car module.
 *
 * @author     Radu Maier <radu@webserv.co>
 * @package    RaduApp
 * @subpackage CarModule
 *
 */
class Controller_Car_App extends ControllerApp {

	public function Overview() {

		$this->InitController();

		$data = $this->Module()->GetDefaultData($this->_mRequest);

		$data['title'] = 'Car overview';

		Core::User()->Init();

		if (Core::User()->IsLogged())
		{
			if (Core::Acl()->Check(Core::User()->mProp['list']['user_id'], $data['resource'])) {

				$data['car_id'] = !empty($this->_mRequest['query']['id']) ? $this->_mRequest['query']['id'] : 0;

				$data['car_info'] = $this->Module()->GetCarInfo($data['car_id']);

			} else {

				$data['error_message'] = 'Error: You don\'t have permission to access this resource.';
			}

		} else {

			$data['error_message'] = 'Error: You have to be logged in in order to access this resource.';
		}

		echo $this->GetOutput($data);

		return TRUE;
	}

	public function Stats() {

		$this->InitController();

		$data = $this->Module()->GetDefaultData($this->_mRequest);

		$data['title'] = 'Car stats';

		Core::User()->Init();

		if (Core::User()->IsLogged())
		{
			if (Core::Acl()->Check(Core::User()->mProp['list']['user_id'], $data['resource'])) {

				$data['car_id'] = !empty($this->_mRequest['query']['id']) ? $this->_mRequest['query']['id'] : 0;

				$data['car_info'] = $this->Module()->GetCarInfo($data['car_id']);
				
				$data['stats']['last_update'] = $this->Module()->GetStatsLastUpdate($data['car_id']);
				
				$data['current_date'] = !empty($this->_mRequest['query']['day']) ? date('Y-m-d', strtotime($this->_mRequest['query']['day'])) : date('Y-m-d');
				
				if (!empty($data['car_info']['car_owner_date'])) {
					
					$data['stats']['duration'] = $this->Module()->GetStatsDuration($data['car_info']['car_owner_date'], $data['current_date']);
					
					$data['stats']['distance'] = $this->Module()->GetStatsDistance($data['car_id'], $data['car_info']['car_owner_date'], $data['current_date']);
					
					$data['stats']['fuel_volume'] = $this->Module()->GetStatsFuelVolume($data['car_id'], $data['car_info']['car_owner_date'], $data['current_date']);
					
					$data['stats']['fuel_consumption'] = $this->Module()->GetStatsFuelConsumption($data['stats']['distance'], $data['stats']['fuel_volume']);
					
					$data['stats']['cost_km'] = $this->Module()->GetStatsCostKm($data['car_id'], $data['car_info']['car_owner_date'], $data['current_date']);
					
					$data['stats']['cost_month'] = $this->Module()->GetStatsCostMonth($data['car_id'], $data['car_info']['car_owner_date'], $data['current_date']);
					
					$data['stats']['cost_total'] = $this->Module()->GetStatsCostTotal($data['car_id'], $data['car_info']['car_owner_date'], $data['current_date']);
					
					$data['stats']['cost_previous'] = $this->Module()->GetStatsCostPrevious($data['car_id'], $data['car_info']['car_owner_date']);
					
					$log_types = $this->Module()->GetLogTypes();
					
					foreach ($log_types as $type_id => $type_name) {
						$data['stats']['cost'][$type_name] = $this->Module()->GetStatsCost($data['car_id'], $data['car_info']['car_owner_date'], $data['current_date'], $type_id);
					}
				}

			} else {

				$data['error_message'] = 'Error: You don\'t have permission to access this resource.';
			}

		} else {

			$data['error_message'] = 'Error: You have to be logged in in order to access this resource.';
		}

		echo $this->GetOutput($data);

		return TRUE;
	}

	public function Log() {

		$this->InitController();

		$data = $this->Module()->GetDefaultData($this->_mRequest);

		$data['title'] = 'Car log';

		Core::User()->Init();

		if (Core::User()->IsLogged())
		{
			if (Core::Acl()->Check(Core::User()->mProp['list']['user_id'], $data['resource'])) {

				$data['car_id'] = !empty($this->_mRequest['query']['id']) ? $this->_mRequest['query']['id'] : 0;

				$data['log'] = $this->Module()->GetCarLog($data['car_id']);

			} else {

				$data['error_message'] = 'Error: You don\'t have permission to access this resource.';
			}

		} else {

			$data['error_message'] = 'Error: You have to be logged in in order to access this resource.';
		}

		echo $this->GetOutput($data);

		return TRUE;

	}
}
?>