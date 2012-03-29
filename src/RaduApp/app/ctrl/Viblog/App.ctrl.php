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

		$data['section_id'] = 3;

		Core::User()->Init();

		if (Core::User()->IsLogged())
		{
			if (Core::Acl()->Check(Core::User()->mProp['list']['bin.user_id'], $data['resource'])) {

				$data['posts'] = $this->Module()->GetPosts();

			} else {

				$data['error_message'] = 'Error: You don\'t have permission to access this resource.';
			}

		} else {

			$data['error_message'] = 'Error: You have to be logged in in order to access this resource.';
		}

		echo $this->GetOutput($data);

		return TRUE;
	}

	public function Post() {

		$this->InitController();

		$data = $this->Module()->GetDefaultData($this->_mRequest);

		$post_id = (!empty($this->_mRequest['query']['id'])) ? $this->_mRequest['query']['id'] : 0;

		$data['title'] = 'VIBlog';

		$data['section_id'] = 3;

		Core::User()->Init();

		if (Core::User()->IsLogged())
		{
			if (Core::Acl()->Check(Core::User()->mProp['list']['bin.user_id'], $data['resource'])) {

				$data['post'] = $this->Module()->GetPostDetails($post_id);

				$data['images'] = $this->Module()->GetPostImages($post_id);

				if (!empty($data['images'])) {

					$data['photoswipe'] = TRUE;
				}

				$data['videos'] = $this->Module()->GetPostVideos($post_id);

			} else {

				$data['error_message'] = 'Error: You don\'t have permission to access this resource.';
			}

		} else {

			$data['error_message'] = 'Error: You have to be logged in in order to access this resource.';
		}

		echo $this->GetOutput($data);

		return TRUE;
	}
}
?>