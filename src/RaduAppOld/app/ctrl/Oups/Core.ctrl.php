<?php
/**
 * $Id$
 *
 * Core controlller.
 *
 * Module: Oups
 *
 * @author     Radu Maier <radu@webserv.co>
 * @copyright  Copyright (c) 2008 - 2012, Radu Maier
 * @license    http://www.opensource.org/licenses/bsd-license.php
 * @package    RaduApp
 * @subpackage OupsModule
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
	exit();
}

/**
 * Controller_Oups_Core
 *
 * Core Controller for Oups module.
 *
 * @author     Radu Maier <radu@webserv.co>
 * @package    RaduApp
 * @subpackage OupsModule
 *
 */
class Controller_Oups_Core extends ControllerApp {

	public function Oups404() {
		
		/**
		 * Initialize controller.
		 */
		$this->InitController();
		
		/**
		 * Initialize default data.
		 * 
		 * @var array
		 */
		$data = $this->Module()->GetDefaultData($this->_mRequest);
		
		/**
		 * @var string
		 */
		$data['title'] = __('Page not found');
				
		/**
		 * @var string
		 */
		$data['message'] = __('Sorry! Page not found.');
		
		/**
		 * Set 404 header.
		 */
		header(__('HTTP/1.1 404 Not Found'));
			
		/**
		 * Display processed output data.
		 */
		echo $this->GetOutput($data, 'error');

		return TRUE;
	}

}
?>