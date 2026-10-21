<?php
/**
 * $Id$
 *
 * App controlller.
 *
 * Module: Content
 *
 * @author     Radu Maier <radu@webserv.co>
 * @copyright  Copyright (c) 2008 - 2012, Radu Maier
 * @license    http://www.opensource.org/licenses/bsd-license.php
 * @package    RaduApp
 * @subpackage ContentModule
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
 * Controller_Content_App
 *
 * App Controller for Content module.
 *
 * @author     Radu Maier <radu@webserv.co>
 * @package    RaduApp
 * @subpackage ContentModule
 *
 */
class Controller_Content_App extends ControllerApp {

    public function Home() {

        $this->InitController();

        $data = $this->Module()->GetDefaultData($this->_mRequest);

        $data['message'] = 'Hello World!';
        
        $data['section_id'] = 1;

        echo $this->GetOutput($data, 'home');

        return TRUE;
    }
    
    public function Contact() {
    
        $this->InitController();
    
        $data = $this->Module()->GetDefaultData($this->_mRequest);
    
        $data['title'] = 'Contact';
        
        $data['message'] = 'radu at webserv.co';
        
        $data['section_id'] = 3;
    
        echo $this->GetOutput($data, 'display');
    
        return TRUE;
    }
}
?>