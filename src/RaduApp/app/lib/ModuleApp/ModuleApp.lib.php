<?php

/**
 * $Id$
 *
 * Application module library.
 *
 * @author     Radu Maier <radu@webserv.co>
 * @copyright  Copyright (c) 2008 - 2012, Radu Maier
 * @license    http://www.opensource.org/licenses/bsd-license.php
 * @package    RaduApp
 * @subpackage ModuleApp
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
 * ModuleApp
 *
 * Application module library.
 *
 * @author     Radu Maier <radu@webserv.co>
 * @package    RaduApp
 * @subpackage ModuleApp
 *
 */
class ModuleApp extends Module {

	/**
	 * Get default output data.
	 *
	 * @param array $request
	 *
	 * @return array
	 */
	public function GetDefaultData($request) {

		/**
		 * @var array
		 */
		$default_data = array ();

		/**
		 * @var array
		 */
		$default_data['lang'] = substr(Core :: Config()->Get('i18n.locale'), 0, 2);

		/**
		 * @var array
		 */
		$default_data['url_app'] = Core :: Config()->Get('url.app');

		/**
		 * @var array
		 */
		$default_data['url_skin'] = Core :: Config()->Get('url.app') . Core :: Config()->Get('dir.app.usr') . '/' . Core :: Config()->Get('dir.app.skins') . '/' . Core :: Config()->Get('output.skin') . '/';

		/**
		 * @var array
		 */
		$default_data['url_scripts'] = Core :: Config()->Get('url.app') . Core :: Config()->Get('dir.app.usr') . '/scripts/';

		/**
		 * @var string
		 */
		$default_data['url_viblog_assets'] = $this->GetViblogAssetsUrl(); //XXX

		/**
		 * @var array
		 */
		$default_data['title'] = NULL;

		/**
		 * @var array
		 */
		$default_data['title_app'] = Core :: Config()->Get('app.title');

		/**
		 * @var array
		 */
		$default_data['menu'] = $this->GetMenu();
		
		$default_data['menu_id'] = NULL;

		/**
		 * @var array
		 */
		$default_data['string_item_not_found'] = __('Item not found', 'App');

		/**
		 * @var string
		 */
		$default_data['module'] = strtolower(str_replace('Module_', '', $request['module']));

		return $default_data;
	}

	/**
	 * Get template name.
	 *
	 * @param array $request
	 * @param string $customTemplate
	 *
	 * @return string
	 */
	public function GetTemplateName($request, $customTemplate = NULL) {

		if (!empty ($request['action']) && !empty ($request['module'])) {

			/**
			 * @var string
			 */
			$template_suffix = !empty ($customTemplate) ? $customTemplate : $request['action'];

			/**
			 * @var string
			 */
			$template_name = strtolower(str_replace('Module_', NULL, $request['module']) . '_' . $template_suffix);

		} else {

			/**
			 * @var string
			 */
			$template_name = !empty ($customTemplate) ? $customTemplate : 'error';
		}

		return $template_name;
	}

	/**
	 * Get navigation menu
	 */
	public function GetMenu() {

		$menu = array(
		'home' => array(
			'title' => 'Home',
			'link' => 'home',
			'url' => NULL
		),
		'viblog' => array(
			'title' => 'VIBlog',
			'link' => 'viblog',
			'url' => NULL
		),
		'about' => array(
			'title' => 'About',
			'link' => 'about',
			'url' => NULL
		),
		'contact' => array(
			'title' => 'Contact',
			'link' => 'contact',
			'url' => NULL
		),
		);

		return $menu;
	}

	/**
	 * Get images url for current client.
	 *
	 * @return string
	 */
	public function GetViblogAssetsUrl() {

		return Core :: Config()->Get('url.app') . Core :: Config()->Get('dir.app.usr') . '/viblog/assets/';
	}

	/**
	 * Retrieve the last update date and time.
	 *
	 */
	public function GetLastUpdate() {

		return date('Y-m-d');
	}
}
?>