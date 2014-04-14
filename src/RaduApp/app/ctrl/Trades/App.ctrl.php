<?php
/**
 * $Id$
 *
 * App controller.
 *
 * Module: Trades
 *
 * @author     Radu Maier <radu@webserv.co>
 * @copyright  Copyright (c) 2008 - 2013, Radu Maier
 * @license    http://www.opensource.org/licenses/bsd-license.php
 * @package    RaduApp
 * @subpackage TradesModule
 * @version    $Revision$
 * @since      Version 7
 * @access     public
 * @filesource
 */
if (!defined('_WEB')) {
	exit();
}

/**
 * Controller_Trades_App
 * App Controller for Trades module.
 *
 * @author Radu Maier <radu@webserv.co>
 * @package RaduApp
 * @subpackage TradesModule
 */
class Controller_Trades_App extends ControllerApp {

	public function Tradelist() {

		$this->Init();
		
		$data = $this->Module()->GetData($this->_mRequest);
		
		$data['strings']['title'] = $data['fb_meta']['og']['title'] = __('Trades');
		$data['strings']['description'] = $data['fb_meta']['og']['description'] = __('Looking for vinyl records. Offering records, CDs and fanzines in exchange.');
		
		$data['fb_meta']['og']['image'] = "{$data['url']['media']}trades.jpg";
		
		$data['categories'] = $this->Module()->GetCategories();
		
		$data['tradelist'] = $this->Module()->GetTradelist();
		
		//echo '<pre>'; print_r($data); exit(); // XXX
		
		echo $this->GetOutput($data);
		
		return TRUE;
	}
}
?>