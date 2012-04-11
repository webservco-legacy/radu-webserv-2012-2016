<?php
/**
 * $Id$
 *
 * App controlller.
 *
 * Module: Sandbox
 *
 * @author     Radu Maier <radu@webserv.co>
 * @copyright  Copyright (c) 2008 - 2012, Radu Maier
 * @license    http://www.opensource.org/licenses/bsd-license.php
 * @package    RaduApp
 * @subpackage SandboxModule
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
 * Controller_Sandbox_App
 *
 * App Controller for Sandbox module.
 *
 * @author     Radu Maier <radu@webserv.co>
 * @package    RaduApp
 * @subpackage SandboxModule
 *
 */
class Controller_Sandbox_App extends ControllerApp {

	public function Sandbox() {
	
		$this->InitController();
	
		$data = $this->Module()->GetDefaultData($this->_mRequest);
	
		$data['title'] = 'Sandbox';
		
		$data['message'] = 'Sandbox';
		
		$data['section_id'] = 2;
	
		echo $this->GetOutput($data, 'display');
	
		return TRUE;
	}
	
	public function Datetime() {
	
		$this->InitController();
	
		$data = $this->Module()->GetDefaultData($this->_mRequest);
	
		$data['title'] = 'Date &amp; Time Calculations';
		
		$data['section_id'] = 2;
		
		$data['cst'] = $this->Module()->CalculateStartTime();
	
		echo $this->GetOutput($data);
	
		return TRUE;
	}
	
	public function Oimoroi() {
	
		$this->InitController();
	
		$data = $this->Module()->GetDefaultData($this->_mRequest);
	
		$data['title'] = 'Oi MoRoi';
	
		$data['message'] = '';
	
		echo $this->GetOutput($data);
	
		return TRUE;
	}
}
?>