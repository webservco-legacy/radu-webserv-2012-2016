<?php
/**
 * $Id$
 *
 * App controller.
 *
 * Module: Car
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
 * Controller_Car_App
 * App Controller for Car module.
 *
 * @author Radu Maier <radu@webserv.co>
 * @package RaduApp
 * @subpackage CarModule
 */
class Controller_Car_App extends ControllerApp {

    public function Overview() {

        $this->Init();
        
        $data = $this->Module()->GetData($this->_mRequest);
        
        $data['error_message'] = $this->CheckUserCarAccess($data);
        
        if (empty($data['error_message'])) {
            
            $data['car_id'] = !empty($data['query']['id']) ? $data['query']['id'] : 0;
            
            $data['car_info'] = $this->Module()->GetCarInfo($data['car_id']);
            
            $data['carmenu_id'] = 1;
            
            $data['carmenu'] = $this->Output()->Out('Car/carmenu', $data);
        }
        
        echo $this->GetOutput($data);
        
        return TRUE;
    }

    public function Stats() {

        $this->Init();
        
        $data = $this->Module()->GetData($this->_mRequest);
        
        $data['error_message'] = $this->CheckUserCarAccess($data);
        
        if (empty($data['error_message'])) {
            
            $data['car_id'] = !empty($data['query']['id']) ? $data['query']['id'] : 0;
            
            $data['car_info'] = $this->Module()->GetCarInfo($data['car_id']);
            
            $data['stats']['last_update'] = $this->Module()->GetStatsLastUpdate($data['car_id']);
            
            $data['current_date'] = !empty($data['query']['day']) ? date('Y-m-d', strtotime($data['query']['day'])) : date('Y-m-d');
            
            if (!empty($data['car_info']['car_owner_date'])) {
                
                $data['stats']['duration'] = $this->Module()->GetStatsDuration($data['car_info']['car_owner_date'], $data['current_date']);
                
                $data['stats']['distance'] = $this->Module()->GetStatsDistance($data['car_id'], $data['car_info']['car_owner_date'], $data['current_date']);
                
                $data['stats']['fuel_volume'] = $this->Module()->GetStatsFuelVolume($data['car_id'], $data['car_info']['car_owner_date'], $data['current_date'], $data['car_info']['car_fuel_tank']);
                
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
            
            $data['carmenu_id'] = 4;
            
            $data['carmenu'] = $this->Output()->Out('Car/carmenu', $data);
        }
        
        echo $this->GetOutput($data);
        
        return TRUE;
    }

    public function Log() {

        $this->Init();
        
        $data = $this->Module()->GetData($this->_mRequest);
        
        $data['error_message'] = $this->CheckUserCarAccess($data);
        
        if (empty($data['error_message'])) {
            
            $data['car_id'] = !empty($data['query']['id']) ? $data['query']['id'] : 0;
            
            $data['log'] = $this->Module()->GetCarLog($data['car_id']);
            
            $data['carmenu_id'] = 3;
            
            $data['carmenu'] = $this->Output()->Out('Car/carmenu', $data);
        }
        
        echo $this->GetOutput($data);
        
        return TRUE;
    }

    public function LogPublic() {

        $this->Init();
        
        $data = $this->Module()->GetData($this->_mRequest);
        
        $data['error_message'] = $this->CheckUserCarAccess($data);
        
        if (empty($data['error_message'])) {
            
            $data['car_id'] = !empty($data['query']['id']) ? $data['query']['id'] : 0;
            
            $data['log'] = $this->Module()->GetCarLog($data['car_id']);
            
            $data['carmenu_id'] = 2;
            
            $data['carmenu'] = $this->Output()->Out('Car/carmenu', $data);
        }
        
        echo $this->GetOutput($data);
        
        return TRUE;
    }

    public function XXX() {

        $this->Init();
        
        $data = $this->Module()->GetData($this->_mRequest);
        
        $data['error_message'] = $this->CheckUserCarAccess($data);
        
        if (empty($data['error_message'])) {
            
            // XXXX
        }
        
        echo $this->GetOutput($data);
        
        return TRUE;
    }
}
?>