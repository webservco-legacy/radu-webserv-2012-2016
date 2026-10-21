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
    
    protected $_mMobile = FALSE;

    /**
     * Controller initialization.
     *
     * @return bool
     */
    public function InitController($isMobile = FALSE) {
        
        if ($isMobile) {
            $this->_mMobile = TRUE;
        }

        /**
         * Initialize output.
         *
         * This is actually not required,
         * but we need it here so we can use the
         * Output library configuration data.
         */
        $this->Output()->Init();
        
        /**
         * No multilanguage!
         * 
         * $this->InitLocale();
         */

        return TRUE;
    }
    
    public function InitLocale() {

        $app_langs = array(
                    'en' => 'en_US',
                    'it' => 'it_IT.UTF8',
        );

        $user_lang = !empty($_SERVER['HTTP_ACCEPT_LANGUAGE']) ? substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2) : NULL;

        if (!empty($user_lang) && array_key_exists($user_lang, $app_langs)) {
            Core::I18n()->SetLocale($app_langs[$user_lang]);

            return TRUE;
        }

        return FALSE;
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
        $default_data = $this->Module()->GetDefaultData($this->_mRequest, $this->_mMobile);

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
        
        $main_template = $this->Output()->GetTemplate();
        
        if ($this->_mMobile) {
            
            $main_template .= '_mobile';
        }

        return $this->Output()->Out($main_template, $data);
    }
}
?>