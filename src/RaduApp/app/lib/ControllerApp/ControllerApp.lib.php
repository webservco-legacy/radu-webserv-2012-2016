<?php
/**
 * $Id$
 *
 * Application controller library.
 *
 * @author     Radu Maier <radu@webserv.co>
 * @copyright  Copyright (c) 2008 - 2012, Radu Maier
 * @license    http://www.opensource.org/licenses/bsd-license.php
 * @package    RaduApp
 * @subpackage ControllerApp
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
	exit();
}

/**
 * ControllerApp
 *
 * Application controller library.
 *
 * @author     Radu Maier <radu@webserv.co>
 * @package    RaduApp
 * @subpackage ControllerApp
 *
 */
class ControllerApp extends Controller {

	/**
	 * Controller initialization.
	 *
	 * @return bool
	 */
	public function InitController() {

		/**
		 * Initialize output.
		 *
		 * This is actually not required,
		 * but we need it here so we can use the
		 * Output library configuration data.
		 */
		$this->Output()->Init();

		return TRUE;
	}

	/**
	 * Get output.
	 *
	 * @param array $contentData
	 * @param string $customTemplate
	 * @param boolean $globalTemplate true if output is to use the global template
	 * specified by $customTemplate and not module specific template
	 *
	 * @return string
	 */
	public function GetOutput($contentData = array(), $customTemplate = NULL, $globalTemplate = FALSE) {

		/**
		 * Get template name.
		 *
		 * @var string
		 */
		$template_name = (FALSE !== $globalTemplate) ? $customTemplate : $this->Module()->GetTemplateName($this->_mRequest, $customTemplate);

		/**
		 * Get default data.
		 *
		 * @var array
		 */
		$default_data = $this->Module()->GetDefaultData($this->_mRequest);

		/**
		 * Set the content var to the parsed content template.
		 *
		 * @var array
		 */
		$default_data['content'] = $this->Output()->Out($template_name, $contentData);

		/**
		 * Get all data.
		 *
		 * @var array
		 */
		$data = array_merge($default_data, $contentData);

		/**
		 * Set charset
		 */
		header("Content-type: text/html; charset=utf-8");

		return $this->Output()->Out(NULL, $data);
	}
}
?>