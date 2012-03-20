<?php
/**
 * $Id$
 *
 * Viblog module.
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
 * Module_Viblog
 *
 * Model class for Viblog module.
 *
 * @author     Radu Maier <radu@webserv.co>
 * @package    RaduApp
 * @subpackage ViblogModule
 *
 */
class Module_Viblog extends ModuleApp {

	public function GetPostDetails($postId) {

		$db_fields = array(
			'post_id',
			'post_title',
			'post_description',
			'post_date_start',
			'post_date_end',
		);

		$db_options['conditions'][] = array('post_id', $postId);

		$db_options['conditions'][] = array('post_active', 1);

		return Core::Database()->RetrieveOne($db_fields, 'r_viblog', $db_options);
	}

	public function GetPostImages($postId) {

		$db_fields = array(
			'image_id',
			'image_file',
			'image_type',
			'image_description',
		);

		$db_options['conditions'][] = array('post_id', $postId);

		$db_options['order'][] = array('image_order', 'asc');

		$db_options['order'][] = array('image_id', 'asc');

		return Core::Database()->Retrieve($db_fields, 'r_viblog_images', $db_options);
	}

	public function GetPostVideos($postId) {

		$db_fields = array(
			'video_id',
			'video_file',
			'video_type',
			'video_description',
		);

		$db_options['conditions'][] = array('post_id', $postId);

		$db_options['order'][] = array('video_order', 'asc');

		$db_options['order'][] = array('video_id', 'asc');

		return Core::Database()->Retrieve($db_fields, 'r_viblog_videos', $db_options);
	}
	
	public function GetPosts() {
	
		$db_fields = array(
					'post_id',
					'post_thumbnail',
					'post_title',
					'post_description',
					'post_date',
					'total_images',
					'total_videos'
		);
	
		$db_options['order'][] = array('post_date', 'desc');
	
		$db_options['order'][] = array('post_id', 'desc');
	
		return Core::Database()->Retrieve($db_fields, 'r_viblog_v_posts', $db_options);
	}
}
?>