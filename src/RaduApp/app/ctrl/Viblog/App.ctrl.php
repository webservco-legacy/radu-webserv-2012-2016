<?php
/**
 * $Id: App.ctrl.php 42 2012-07-22 14:41:11Z radu $
 *
 * App controlller.
 *
 * Module: Viblog
 *
 * @author     Radu Maier <radu@webserv.co>
 * @copyright  Copyright (c) 2008 - 2012, Radu Maier
 * @license    http://www.opensource.org/licenses/bsd-license.php
 * @package    RaduApp
 * @subpackage ViblogModule
 * @version    $Revision: 42 $
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
 * Controller_Viblog_App
 *
 * App Controller for Viblog module.
 *
 * @author     Radu Maier <radu@webserv.co>
 * @package    RaduApp
 * @subpackage ViblogModule
 *
 */
class Controller_Viblog_App extends ControllerApp {

	public function Items() {
		
		$this->Init();
		
		$data = $this->Module()->GetData($this->_mRequest);
		
		$data['strings']['title'] = $data['facebook']['meta']['og']['title'] = __('Visual Web Log');
		$data['strings']['description'] = $data['facebook']['meta']['og']['description'] = __('Visual Web Log');
		
		//$data['facebook']['meta']['og']['image'] = "{$data['url']['media']}records1.jpg";
		//$data['header_image'] = "{$data['url']['media']}trades.jpg";

		$data['posts'] = $this->Module()->GetPosts();
		
		echo $this->GetOutput($data);

		return TRUE;
	}

	public function Item() {
		
		$this->Init();
		
		$data = $this->Module()->GetData($this->_mRequest);
		
		$post_id = (!empty($this->_mRequest['query']['id'])) ? $this->_mRequest['query']['id'] : 0;
		
		$data['strings']['title'] = $data['facebook']['meta']['og']['title'] = __('Visual Web Log');
		$data['strings']['description'] = $data['facebook']['meta']['og']['description'] = __('Visual Web Log');
		
		//$data['facebook']['meta']['og']['image'] = "{$data['url']['media']}records1.jpg";
		//$data['header_image'] = "{$data['url']['media']}trades.jpg";
		
		$data['post'] = $this->Module()->GetPostDetails($post_id);
		
		if (!empty($data['post'])) {
		    
		    $data['strings']['title'] = $data['facebook']['meta']['og']['title'] = $data['post']['post_title'];
		}
		
		$data['images'] = $this->Module()->GetPostImages($post_id);
		
		if (!empty($data['images'])) {
		
			$data['photoswipe'] = TRUE;
		}
		
		$data['videos'] = $this->Module()->GetPostVideos($post_id);

		$data['comments'] = $this->GetComments('viblog', $post_id, $data);
		
		echo $this->GetOutput($data);

		return TRUE;
	}
}
?>
