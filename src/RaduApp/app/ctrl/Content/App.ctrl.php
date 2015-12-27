<?php
/**
 * $Id: App.ctrl.php 108 2013-08-01 19:21:34Z radu $
 *
 * App controller.
 *
 * Module: Content
 *
 * @author     Radu Maier <radu@webserv.co>
 * @copyright  Copyright (c) 2008 - 2013, Radu Maier
 * @license    http://www.opensource.org/licenses/bsd-license.php
 * @package    DefaultApp
 * @subpackage ContentModule
 * @version    $Revision: 108 $
 * @since      Version 7
 * @access     public
 * @filesource
 */

if (!defined('_WEB')) {
	exit();
}

/**
 * Controller_Content_App
 * App Controller for Content module.
 * 
 * @author Radu Maier <radu@webserv.co>
 * @package DefaultApp
 * @subpackage ContentModule
 */
class Controller_Content_App extends ControllerApp {

	public function Home() {
		
		/**
		 * If you need to output static data, just use this:
		 * (alternatively you could set the title (see Info())
		 * return $this->GetDefaultOutput();
		 */
	    if ('viblog.webserv.co' == $_SERVER['HTTP_HOST']) {
	    
	        Core::Router()->Redirect('Viblog/App/Items');
	    
	    }
	
		$this->Init();
	
		$data = $this->Module()->GetData($this->_mRequest);
		
		if ('bct.webserv.co' == $_SERVER['HTTP_HOST']) {
			

			$data['strings']['title'] = $data['facebook']['meta']['og']['title'] = 'Black Chicken Tapes';
			$data['strings']['description'] = $data['facebook']['meta']['og']['description'] = __('Otherground label from Eastern Europe.');
			$data['facebook']['meta']['og']['image'] = "{$data['url']['media']}bct/bct_logo.gif";
			
			$bctModule = Core::Get('Module_Bct');
			$bctModule->GetData($this->_mRequest);
			

			$data['tradelist'] = $bctModule->TradelistGet();
			$data['tradelist_last_update'] = $bctModule->TradelistGetLastUpdate();
			$data['tradelist_total'] = $bctModule->TradelistGetTotal();
		}
	
		echo $this->GetOutput($data);
	
		return TRUE;
	}

	public function Info() {

		return $this->GetDefaultOutput(__('Info'));
	}

	public function Debug() {

		$this->Init();
		
		$data = $this->Module()->GetData($this->_mRequest);
		
		/**
		 * Debug using the log instead of output in browser (useful for live apps).
		 */
		//Core::Log()->Debug('Debugging $data', $data, 'info');
		
		//echo $this->GetOutput($data);

		echo '<pre>'; print_r($_SERVER);
		
		return TRUE;
	}

	public function User() {

		$this->Init();
		
		$data = $this->Module()->GetData($this->_mRequest);
		
		/**
		 * Load external module.
		 */
		$usersModule = Core::Get('Module_Users');
		
		/**
		 * Set default data to the external module by using the GetData() method.
		 * This is optional but note that if you don't set it, the defaults are not set.
		 */
		$usersModule->GetData($this->_mRequest);
		
		/**
		 * Get data from external module.
		 */
		$data['user_info'] = $usersModule->Get(1);
		
		echo $this->GetOutput($data);
		
		return TRUE;
	}

	public function Credits() {

		$this->Init();
		
		$data = $this->Module()->GetData($this->_mRequest);
		
		$data['strings']['title'] = __('Credits');
		
		$data['apps'] = $this->Module()->GetAppList();
		
		$data['app_intro'] = $this->Module()->GetAppIntro($data['webapp']['app_id']);
		
		echo $this->GetOutput($data);
		
		return TRUE;
	}
}
?>
