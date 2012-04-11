<?php
/**
 * $Id$
 *
 * Sandbox module.
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
 * Module_Sandbox
 *
 * Model class for Sandbox module.
 *
 * @author     Radu Maier <radu@webserv.co>
 * @package    RaduApp
 * @subpackage SandboxModule
 *
 */
class Module_Sandbox extends ModuleApp {

	public function CalculateStartTime() {
		
		$cst['duration_h'] = !empty($_POST['cst_duration_h']) ? (int) $_POST['cst_duration_h'] : 0; 
		$cst['duration_m'] = !empty($_POST['cst_duration_m']) ? (int) $_POST['cst_duration_m'] : 0; 
		$cst['duration_s'] = !empty($_POST['cst_duration_s']) ? (int) $_POST['cst_duration_s'] : 0;

		$cst['endtime_h'] = !empty($_POST['cst_endtime_h']) ? (int) $_POST['cst_endtime_h'] : 0;
		$cst['endtime_m'] = !empty($_POST['cst_endtime_m']) ? (int) $_POST['cst_endtime_m'] : 0;
		$cst['endtime_s'] = !empty($_POST['cst_endtime_s']) ? (int) $_POST['cst_endtime_s'] : 0;
		
		$cst['offset'] = !empty($_POST['cst_offset']) ? (int) $_POST['cst_offset'] : 1;
		
		$duration = mktime($cst['duration_h'], $cst['duration_m'], $cst['duration_s']);
		
		$endtime = mktime($cst['endtime_h'], $cst['endtime_m'], $cst['endtime_s']);
		
		$offset = - ($cst['offset'] * 60 * 60);
		
		$cst['starttime'] = date('H:i:s', ($endtime - $duration + $offset));
		
		return $cst;
	}
}
?>