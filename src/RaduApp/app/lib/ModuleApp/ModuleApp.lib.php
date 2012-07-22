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
		$default_data['lang'] = substr(Core::Config()->Get('i18n.locale'), 0, 2);

		/**
		 * @var array
		 */
		$default_data['appcache'] = (bool) Core::Config()->Get('appcache.enabled');

		/**
		 * @var array
		 */
		$default_data['url_app'] = Core::Config()->Get('url.app');

		/**
		 * @var array
		 */
		$default_data['url_skin'] = Core::Config()->Get('url.app') . Core::Config()->Get('dir.app.usr') . '/' . Core::Config()->Get('dir.app.skins') . '/' . Core::Config()->Get('output.skin') . '/';

		/**
		 * @var array
		 */
		$default_data['url_scripts'] = Core::Config()->Get('url.app') . Core::Config()->Get('dir.app.usr') . '/scripts/';

		$default_data['url_assets_viblog'] = $this->GetAssetsUrl('viblog');

		$default_data['url_assets_sandbox'] = $this->GetAssetsUrl('sandbox');

		/**
		 * @var array
		 */
		$default_data['title'] = NULL;

		/**
		 * @var array
		 */
		$default_data['title_app'] = Core::Config()->Get('app.title');

		/**
		 * @var array
		 */
		$default_data['sections'] = $this->GetSections();

		$default_data['section_id'] = 0;

		/**
		 * @var array
		 */
		$default_data['string_item_not_found'] = __('Item not found', 'App');

		/**
		 * @var string
		 */
		$default_data['module'] = strtolower(str_replace('Module_', '', $request['module']));
		
		$module = ucfirst($default_data['module']);
		
		$controller = str_replace(array("{$module}_", 'Controller_'), NULL, $request['controller']);
		
		$default_data['resource'] = $module . '/' . $controller . '/' . $request['action'];
		
		Core::User()->Init();
		
		if (Core::User()->IsLogged()) {
			
			$default_data['user'] = array (
				'id' => Core::User()->mProp['list']['user_id'],
				'name' => Core::User()->mProp['list']['user_nickname'],
			);
		}

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
			$template_name = str_replace('Module_', NULL, $request['module']) . '/' . strtolower($template_suffix);


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
	public function GetSections() {

		$db_fields = array(
			'section_id:id',
			'section_title:title',
			'section_link:link',
			'section_url:url',
		);

		$db_options['conditions'][] = array('section_active', 1);

		$db_options['order'][] = array('section_order', 'asc');

		$db_options['order'][] = array('section_title', 'asc');

		return Core::Database()->Retrieve($db_fields, 'r_config_sections', $db_options);
	}

	public function GetAssetsUrl($type) {

		return Core::Config()->Get('url.app') . Core::Config()->Get('dir.app.usr') . '/assets/' . $type . '/';
	}

	/**
	 * Retrieve the last update date and time.
	 *
	 */
	public function GetLastUpdate() {

		return Core::Database()->GetMax('section_update', 'r_config_sections');
	}
}
?>
