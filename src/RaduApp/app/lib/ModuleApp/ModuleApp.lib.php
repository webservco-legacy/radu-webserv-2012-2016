<?php
/**
 * $Id: ModuleApp.lib.php 150 2013-10-04 08:35:58Z radu $
 *
 * Application module library.
 *
 * @author     Radu Maier <radu@webserv.co>
 * @copyright  Copyright (c) 2013, Radu Maier
 * @license    http://www.opensource.org/licenses/bsd-license.php
 * @package    DefaultApp
 * @subpackage ModuleApp
 * @version    $Revision: 150 $
 * @since      Version 1
 * @access     public
 * @filesource
 */

/**
 * Security check.
 */
if (! defined('_WEB')) {
	/**
	 * Stop script execution.
	 */
	exit();
}

/**
 * ModuleApp
 *
 * Application module library.
 *
 * @author Radu Maier <radu@webserv.co>
 * @package DefaultApp
 * @subpackage ModuleApp
 *            
 */
class ModuleApp extends Module {

	public $_mDbExtra = NULL;

	/**
	 * Comments library object.
	 *
	 * @var mixed
	 */
	private $_mComments = NULL;
	
	/**
	 * Discogs library object.
	 *
	 * @var mixed
	 */
	private $_mDiscogs = NULL;

	/**
	 * Open extra database connection.
	 *
	 * Old style MySQL.
	 *
	 * @return boolean
	 */
	public function OpenDbExtra() {

		if (! empty($this->_mDbExtra)) {
			return TRUE;
		}
		
		$db_cfg = Core::Config()->Get('database.extra');
		
		if (empty($db_cfg)) {
			
			return FALSE;
		}
		
		$this->_mDbExtra = mysql_connect($db_cfg['host'], $db_cfg['user'], $db_cfg['password']);
		
		mysql_select_db($db_cfg['database'], $this->_mDbExtra);
		
		mysql_set_charset('utf8', $this->_mDbExtra);
		
		return TRUE;
	}

	/**
	 * Get default messages.
	 *
	 * Must stay here and not in Module.lib in order to be picked up by Poedit.
	 *
	 * @return array
	 */
	public function GetDefaultStrings() {

		$data = array ();
		
		$data['title'] = $data['title_app'] = __(Core::Config()->Get('app.title'), 'App');
		
		$data['description'] = __(Core::Config()->Get('app.description'), 'App');
		
		$data['no_data'] = __('No data to display', 'App');
		
		return $data;
	}

	/**
	 * Get custom data.
	 *
	 * Add here any custom initializations.
	 *
	 * If necessary, you can also override any default data.
	 *
	 * Important: Data marked with "[OPTIONAL]" is only available if the corresponding setting is set to "1" in app configuration.
	 *
	 * Default data:
	 * (already available here, can be overwritten)
	 *
	 * **************************************************
	 * $data['query']
	 *
	 * Request query.
	 *
	 * Values:
	 * array of key/data
	 *
	 * Usage:
	 * Controller: $data['query'][STUFF]
	 * Template: $query[STUFF]
	 * **************************************************
	 * $data['info']
	 *
	 * General information
	 *
	 * Values:
	 * $data['info']['prefix'] - application prefix
	 * $data['info']['module'] - current module name
	 * $data['info']['location'] - current location (Module/App/Controller)
	 * $data['info]['path'] - current location path (Item/1/page/3)
	 * $data['info]['path_pagination'] - current location path without page number (Item/1/)
	 * $data['info']['url_next'] - next url
	 *
	 * Usage:
	 * Controller: $data['info']['module']
	 * Template: $info['module']
	 * **************************************************
	 * $data['url']
	 *
	 * Default URLs
	 *
	 * Values:
	 * $data['url']['www'] (http://www.example.com)
	 * $data['url']['skin'] (http://www.example.com/myskin/)
	 * $data['url']['scripts'] (http://www.example.com/js/)
	 * $data['url']['media'] (http://www.example.com/media/)
	 *
	 * Usage:
	 * Controller: $data['url']['www']
	 * Template: $url['www']
	 * **************************************************
	 * $data['path']
	 *
	 * Default paths.
	 *
	 * Values:
	 * $data['path']['app'] (/full/server/path/to/app/dir/)
	 * $data['path']['www'] (/full/server/path/to/www/dir/)
	 * $data['path']['media'] (/full/server/path/to/media/dir/)
	 *
	 * Usage:
	 * Controller: $data['path']['www']
	 * Template: $path['www']
	 * **************************************************
	 * $data['i18n']
	 *
	 * Internationalization.
	 *
	 * Values:
	 * $data['i18n']['lang'] - all language (en, it, etc)
	 * $data['i18n']['langs'] - array of all available languages - language / locale
	 *
	 * Usage:
	 * Controller: $data['i18n']['lang']
	 * Template: $i18n['lang']
	 * **************************************************
	 * $data['strings']
	 *
	 * Default messages used in most pages.
	 *
	 * Values:
	 * $data['strings']['title']
	 * $data['strings']['description']
	 * $data['strings']['app_title']
	 * $data['strings']['no_data']
	 *
	 * Usage:
	 * Controller: $data['strings']['title']
	 * Template: $strings['title']
	 * **************************************************
	 * $data['menu'] [OPTIONAL]
	 *
	 * Custom menu functionality.
	 *
	 * Values:
	 * $data['menu'][MENU_NODE_FROM _CONFIG]['title']
	 * $data['menu'][MENU_NODE_FROM_CONFIG]['href']
	 * $data['menu'][MENU_NODE_FROM_CONFIG]['target']
	 * $data['menu'][MENU_NODE_FROM_CONFIG]['submenu'] (another array withmenu data)
	 * $data['menu'][MENU_NODE_FROM_CONFIG]['resource'] $data['menu'][MENU_NODE_FROM_CONFIG]['submenu'] (another array withmenu data)
	 *
	 * Usage:
	 * Controller: $data['menu']['MENU_NODE_FROM_CONFIG']
	 * Template: $menu['MENU_NODE_FROM_CONFIG']
	 * **************************************************
	 * $data['timezone'] [OPTIONAL]
	 *
	 * Custom timezone functionality.
	 *
	 * A custom timezone can be set by adding:
	 * /timezone/ENCODEDTIMEZONEVALUE
	 * to the URL
	 *
	 * ENCODEDTIMEZONEVALUE is the timezone value encoded with Core::String()->EncodeUrlString
	 * Example: "RXVyb3BlL1JvbWU." (Europe/Rome)
	 *
	 * If session functionality is enabled, the custom timezone value is also stored in the variable:
	 * $_SESSION['Date']['TimeZone']
	 *
	 * Value:
	 * string representation of custom timezone (Europe/Rome)
	 *
	 * Usage:
	 * Controller: $data['timezone']
	 * Template: $timezone
	 * **************************************************
	 * $data['webapp'] [OPTIONAL]
	 *
	 * Web app info.
	 *
	 * Values:
	 * $data['webapp']['app_id'] - custom app id (from config)
	 * $data['webapp']['os_type'] - os type (android, ios, other)
	 * $data['webapp']['device_type'] - mobile device type (tablet, phone, other)
	 * $data['webapp']['version'] - custom app version set by_mobile devices
	 * $data['webapp']['market'] - app market id (0 - none, 1 - google, 2 - samsung, 3 - amazon, 4 - itunes)
	 * $data['webapp']['type'] - app type (free, pro, etc)
	 * $data['webapp']['ie'] - Internet explorer version (0 if other browser).
	 *
	 * Usage:
	 * Controller: $data['webapp']['os_type']
	 * Template: $webapp['os_type']
	 * **************************************************
	 * $data['user'] [OPTIONAL]
	 *
	 * User functionality.
	 *
	 * Values (not logged in):
	 * $data['user']['StatsCurrent']['time']
	 * $data['user']['StatsCurrent']['ip']
	 * $data['user']['StatsCurrent']['agent']
	 * $data['user']['StatsCurrent']['auto']
	 * $data['user']['InitialVisit']
	 *
	 * Values (logged in):
	 * $data['user']['Id']
	 * $data['user']['Logged']
	 * $data['user']['Persistent']
	 * $data['user']['Prop']['user_id']
	 * $data['user']['Prop']['user_email']
	 * $data['user']['Prop']['user_status']
	 * + custom user properties
	 * $data['user']['StatsCurrent']['time']
	 * $data['user']['StatsCurrent']['ip']
	 * $data['user']['StatsCurrent']['agent']
	 * $data['user']['StatsCurrent']['auto']
	 * $data['user']['StatsPrevious']['time']
	 * $data['user']['StatsPrevious']['ip']
	 * $data['user']['StatsPrevious']['agent']
	 * $data['user']['StatsPrevious']['auto']
	 * $data['user']['InitialVisit']
	 *
	 * Usage:
	 * Controller: $data['user']['Id']
	 * Template: $user['Id']
	 * **************************************************
	 *
	 * @param array $request        	
	 * @return array
	 */
	public function GetDataCustom($request, $data) {

		/**
		 * Custom app data.
		 */
		
		/**
		 * Example: performm additional initializations for logged in users.
		 */
		if (! empty($data['user']['Logged'])) {
		/**
		 * Do stuff.
		 */
		}
		
		/**
		 * Example (use as $data['test'] (Controller) or $test (Output template).
		 */
		$data['test'] = 'test data';
		
		if ('bct.webserv.co' == $_SERVER['HTTP_HOST']) {
			$data['url']['skin'] = $data['url']['www'] . Core::Config()->Get('dir.app.usr') . '/' . Core::Config()->Get('dir.app.skins') . '/BCT/';
		}
		
		$data['url']['next_string'] = ! empty($data['query']['next']) ? "/next/{$data['query']['next']}" : NULL;
		
		$data['url']['items_admin'] = ! empty($data['info']['url_next']) ? "{$data['url']['www']}{$data['info']['url_next']}" : "{$data['url']['www']}Items/Admin/Items";
		
		$data['random_quote'] = $this->GetRandomQuote();
		
		$data['facebook']['meta'] = Core::FacebookApi()->GetDefaultMeta($data);
		
		return $data;
	}

	public function GetRandomQuote() {

		$quotes = array (
			'Is it nice, my precious? Is it juicy? Is it scrumptiously crunchable?',
			'Eile mit weile.',
			'Sinistre muze lacustre.',
			'Gimme likkle bass, make me wine up me waist.',
			'If it\'s done in entertainment, take it as entertainment.',
			'Who says rhyme doesn\'t pay?',
			'Killer wolf out there. As big as a pony but a lot more bloodthirsty.',
			'Save the creature, kill the girl!',
			'We will not tolerate intolerance!',
			'You can take a punk out of punk, but you can\'t take the punk out of a punk.',
			'I jus\' t\'rowing words at the moon, sah.',
			'But since they had these weird looking modified wolves to protect them she risked her life every single time.',
			'Y la carencia? Arriba! Y los salarios? Abajo!',
			'No garlis. No frinds. No manny. No broblam!',
			'Your scientists were so preoccupied with whether or not they could that they didn\'t stop to think if they should.',
			'Man will never be free until the last king is strangled with the entrails of the last priest.',
			'It\'s gr8 m8, no d-b8, I r8 it 8/8!',
			'You can\'t touch music, but music can touch you',
			'I preferred to visit the mall long before it was built.',
			'Eisbär\'n müssen nie weinen.',
			'And if life is just a highway - then the soul is just a car.',
			'Art is how we decorate space; Music is how we decoarate time.',
			'Those who mind don\'t matter; those who matter don\'t mind.',
			'Is de dog talking and de mon barking.',
			'If the pen is mightier than the sword why do actions speak louder than words?',
			'Si Aretha Franklin et Sean Connery étaient mariés, ça ferait Aretha Connery!',
			'It\'s not how old I am, it\'s how old I feel', 
			'Lose some sleep and say you tried',
			'Stand in the ashes of a trillion dead souls, and ask the ghosts if honor matters.',
			'D\'disch werdäd umdräyt',
			'Appetite for distractions',
		);
		
		return $quotes[array_rand($quotes)];
	}

	public function Comments() {

		if (NULL == $this->_mComments) {
			
			$this->_mComments = Core::Get('Library_Comments');
			
			$app_prefix = Core::Config()->Get('app.prefix');
			
			$this->_mComments->Init($app_prefix);
		}
		
		return $this->_mComments;
	}
	
	public function Discogs() {

		if (NULL == $this->_mDiscogs) {
			
			$this->_mDiscogs = Core::Get('Library_Discogs');
			
			$app_prefix = Core::Config()->Get('app.prefix');
			
			$this->_mDiscogs->Init($app_prefix);
		}
		
		return $this->_mDiscogs;
	}
}
?>
