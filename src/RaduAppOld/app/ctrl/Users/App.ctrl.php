<?php
/**
 * $Id$
 *
 * App controlller.
 *
 * Module: Users
 *
 * @author     Radu Maier <radu@webserv.co>
 * @copyright  Copyright (c) 2008 - 2012, Radu Maier
 * @license    http://www.opensource.org/licenses/bsd-license.php
 * @package    RaduApp
 * @subpackage UsersModule
 * @version    $Revision$
 * @since      Version 5
 * @access     public
 * @filesource
 */

/**
 * Security check.
 */
if (!defined('_WEB'))
{
    /**
     * Stop script execution.
     */
    exit();
}

/**
 * Controller_Users_App
 *
 * App Controller for Users module.
 *
 * @author     Radu Maier <radu@webserv.co>
 * @package    RaduApp
 * @subpackage UsersModule
 *
 */
class Controller_Users_App extends ControllerApp
{
    /**
     * Account.
     *
     * @return mixed
     */
    public function Account()
    {
        $this->InitController();

        $data = $this->Module()->GetDefaultData($this->_mRequest);

        $data['title'] = 'Account';

        $data['section_id'] = 0;

        Core::User()->Init();

        if (Core::User()->IsLogged()) {
            
            echo $this->GetOutput($data);
        }
        else {
            $data['form'] = $this->Module()->GetLoginForm();

            echo $this->GetOutput($data);
        }

        return TRUE;
    }

    /**
     * Login error.
     *
     * @return mixed
     */
    public function LoginError()
    {
        $this->InitController();

        $data = $this->Module()->GetDefaultData($this->_mRequest);

        $data['title'] = 'Login error';

        $data['section_id'] = 0;

        $data['error_message'] = 'Login error: Please make sure your credentials are correct.';

        echo $this->GetOutput($data,  'account');
    }

    /**
     * Logout.
     *
     * @return mixed
     */
    public function Logout()
    {
        Core::User()->Logout();

        Core::Router()->Redirect('/');

        exit();
    }

    /**
     * Display captcha image.
     *
     * @return mixed
     */
    public function Captcha()
    {
        echo Core::Captcha()->GetImage();

        return TRUE;
    }
}
?>