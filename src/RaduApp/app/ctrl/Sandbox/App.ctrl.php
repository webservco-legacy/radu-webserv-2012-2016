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
		
		$data['menu_id'] = 'sandbox';
	
		echo $this->GetOutput($data, 'display');
	
		return TRUE;
	}
	
	public function Oimoroi() {
	
		$this->InitController();
	
		$data = $this->Module()->GetDefaultData($this->_mRequest);
	
		$data['title'] = 'Oi MoRoi';
	
		$data['message'] = '';
	
		$data['menu_id'] = 'oimoroi';
	
		echo $this->GetOutput($data);
	
		return TRUE;
	}
}
?>