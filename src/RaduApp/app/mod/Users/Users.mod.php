<?php
/**
 * $Id$
 *
 * Users module.
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
 * Module_Users
 *
 * Model class for Users module.
 *
 * @author     Radu Maier <radu@webserv.co>
 * @package    RaduApp
 * @subpackage UsersModule
 *
 */
class Module_Users extends ModuleApp
{
	/**
	 * Handles login functionality.
	 *
	 * @return mixed
	 */
	public function GetLoginForm()
	{
		Core::Form()->SetMethod('post');

		Core::Form()->SetData(Core::Config()->Get('users.form.login'));

		/**
		 * @var array
		 */
		$form['meta'] = Core::Form()->GetMeta();

		/**
		 * @var array
		 */
		$form['errors'] = array();
		
		if (!empty($_POST))
		{
			/**
			 * @var bool
			 */
			$result_validation = Core::Form()->Validate();

			if (FALSE !== $result_validation)
			{
				/**
				 * @var array
				 */
				$form['data'] = Core::Form()->GetFilteredData();

				/**
				 * @var bool
				 */
				$result_login = Core::User()->Login($form['data']['username'], $form['data']['password'], $form['data']['remember']);

				if (TRUE !== $result_login)
				{
					Core::Captcha()->GetNewToken();

					Core::Router()->Redirect('Users/App/LoginError');

					exit();
				}
				else
				{
					Core::Router()->Redirect();

					exit();
				}
			}
			else
			{
				Core::Captcha()->GetNewToken();

				/**
				 * @var array
				 */
				$form['data'] = Core::Form()->GetData();

				/**
				 * @var array
				 */
				$form['errors'] = Core::Form()->GetErrors();
			}
		}
		else
		{
			/**
			 * @var array
			 */
			$form['data'] = Core::Form()->GetData();
		}

		return $form;
	}
}
?>