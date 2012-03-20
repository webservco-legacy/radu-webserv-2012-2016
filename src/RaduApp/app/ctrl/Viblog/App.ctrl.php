<?php
/**
 * $Id$
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

	public function Browse() {

		$this->InitController();

		$data = $this->Module()->GetDefaultData($this->_mRequest);

		$data['title'] = 'VIBlog';

		$data['posts'] = $this->Module()->GetPosts();

		$data['menu_id'] = 'viblog';

		echo $this->GetOutput($data);

		return TRUE;
	}

	public function Post() {

		$this->InitController();

		$data = $this->Module()->GetDefaultData($this->_mRequest);

		$post_id = (!empty($this->_mRequest['query']['id'])) ? $this->_mRequest['query']['id'] : 0;

		$data['post'] = $this->Module()->GetPostDetails($post_id);

		$data['images'] = $this->Module()->GetPostImages($post_id);

		$data['videos'] = $this->Module()->GetPostVideos($post_id);

		$data['menu_id'] = 'viblog';

		echo $this->GetOutput($data);

		return TRUE;
	}
}
?>