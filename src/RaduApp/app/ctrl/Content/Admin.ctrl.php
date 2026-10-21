<?php
/**
 * $Id: Admin.ctrl.php 108 2013-08-01 19:21:34Z radu $
 *
 * Admin controller.
 *
 * Module: Content
 *
 * @author     Radu Maier <radu@webserv.co>
 * @copyright  Copyright (c) 2008 - 2013, Radu Maier
 * @license    http://www.opensource.org/licenses/bsd-license.php
 * @package    DefaultApp
 * @subpackage ContentModule
 * @version    $Revision: 108 $
 * @since      Version 7
 * @access     public
 * @filesource
 */

if (!defined('_WEB')) {
    exit();
}

/**
 * Controller_Content_Admin
 * Admin Controller for Content module.
 * 
 * @author Radu Maier <radu@webserv.co>
 * @package DefaultApp
 * @subpackage ContentModule
 */
class Controller_Content_Admin extends ControllerApp {

    protected $_mTemplatePrefix = 'admin_';

    public function Home() {

        $this->Init();
        
        $data = $this->Module()->GetData($this->_mRequest);
        
        if (!$this->CheckAdminAccess($data)) {
            
            return FALSE;
        }
        
        /**
         * Custom functionality below this line.
         */
        
        $data['strings']['title'] = __('Home');
        
        echo $this->GetOutput($data);
        
        return TRUE;
    }
}
?>
