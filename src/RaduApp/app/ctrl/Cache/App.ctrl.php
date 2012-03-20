<?php
/**
 * $Id$
 *
 * App controlller.
 *
 * Module: Cache
 *
 * @author     Radu Maier <radu@webserv.co>
 * @copyright  Copyright (c) 2008 - 2012, Radu Maier
 * @license    http://www.opensource.org/licenses/bsd-license.php
 * @package    RaduApp
 * @subpackage CacheModule
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
 * Controller_Cache_App
 *
 * App Controller for Cache module.
 *
 * @author     Radu Maier <radu@webserv.co>
 * @package    RaduApp
 * @subpackage CacheModule
 *
 */
class Controller_Cache_App extends ControllerApp {

	/**
	 * Display content.
	 *
	 * @return boolean
	 */
	public function Offline() {

		$this->InitController();

		/**
		 * Initialize default data.
		 *
		 * @var array
		 */
		$data = $this->Module()->GetDefaultData($this->_mRequest);

		/**
		 * @var string
		 */
		$data['message'] = 'Offline mode';

		/**
		 * Display processed output data.
		 */
		echo $this->GetOutput($data, 'display');

		return TRUE;
	}

	public function Cache() {

		$this->InitController();

		$last_update = $this->Module()->GetLastUpdate();

		//XXX Include viblog posts?

		header("Content-Type: text/cache-manifest");

		$output = "CACHE MANIFEST\n" .
			
			"\n" .
			"# {$last_update}\n" .
			
			"\n" .
			"CACHE:\n" .
			
			"offline.html\n" .
			
		/**
		 * Pages
		*/
			"\n" .
			"home.html\n";
			"sandbox.html\n";
			"contact.html\n";
			"viblog.html\n";
		
		/**
		 * Layout images
		 */
		$output .=	"\n" .
			"usr/skins/Radu/img/apple-touch-icon-114x114-precomposed.png\n" .
			"usr/skins/Radu/img/apple-touch-icon-72x72-precomposed.png\n" .
			"usr/skins/Radu/img/apple-touch-icon-precomposed.png\n" .
			
			"usr/skins/Radu/img/splash.png\n" .
			"\n";

		/**
		 * General images.
		 */
		$output .=	"\n" .
			"usr/skins/Radu/img/error.gif\n" .
			
			"usr/skins/Radu/img/icons.png\n" .
			"usr/skins/Radu/img/icons@2x.png\n" .
			"usr/skins/Radu/img/loader.gif\n" .
			
			"usr/skins/Radu/img/background.jpg\n" .
			"usr/skins/Radu/img/nothumb.jpg\n" .
			
		/**
		 * Css styles.
		*/
			"\n" .
			"usr/skins/Radu/css/style.css\n" .
			"usr/skins/Radu/css/photoswipe.css\n" .
		/**
		 * JavaScript
		*/
			"\n" .
			"usr/scripts/lib/code.photoswipe.jquery-3.0.4.min.js\n" .
			"usr/scripts/lib/jquery-1.7.1.min.js\n" .
			"usr/scripts/lib/helper.js\n" .
			"usr/scripts/lib/klass.min.js\n" .
			"usr/scripts/lib/modernizr-custom.js\n" .
			"usr/scripts/lib/respond.min.js\n" .
			"usr/scripts/script.js\n" .
			
		/**
		 * Ignored files
		*/
			"\n" .
			"NETWORK:\n" .
			"*\n" .
			
		/**
		 * Offline page
		*/
			"\n" .
			"FALLBACK:\n" .
			"/ offline.html\n" .
			"";

		echo $output;
	}
}
?>