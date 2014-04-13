<?php
/**
 * $Id: App.ctrl.php 108 2013-08-01 19:21:34Z radu $
 *
 * App controller.
 *
 * Module: Sandbox
 *
 * @author     Radu Maier <radu@webserv.co>
 * @copyright  Copyright (c) 2008 - 2013, Radu Maier
 * @license    http://www.opensource.org/licenses/bsd-license.php
 * @package    RaduApp
 * @subpackage SandboxModule
 * @version    $Revision: 108 $
 * @since      Version 7
 * @access     public
 * @filesource
 */
if (!defined('_WEB')) {
	exit();
}

/**
 * Controller_Sandbox_App
 * App Controller for Sandbox module.
 *
 * @author Radu Maier <radu@webserv.co>
 * @package RaduApp
 * @subpackage SandboxModule
 */
class Controller_Sandbox_App extends ControllerApp {

	public function Sandbox() {

		return $this->GetDefaultOutput();
	}
}
?>