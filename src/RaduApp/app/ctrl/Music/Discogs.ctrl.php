<?php
/**
 * $Id$
 *
 * Discogs controller.
 *
 * Module: Music
 *
 * @author     Radu Maier <radu@webserv.co>
 * @copyright  Copyright (c) 2008 - 2013, Radu Maier
 * @license    http://www.opensource.org/licenses/bsd-license.php
 * @package    RaduApp
 * @subpackage MusicModule
 * @version    $Revision$
 * @since      Version 7
 * @access     public
 * @filesource
 */
if (!defined('_WEB')) {
	exit();
}

/**
 * Controller_Music_Discogs
 * Discogs Controller for Music module.
 *
 * @author Radu Maier <radu@webserv.co>
 * @package RaduApp
 * @subpackage MusicModule
 */
class Controller_Music_Discogs extends ControllerApp {

	public function Edit() {
		
		$this->Init();
		
		$data = $this->Module()->GetData($this->_mRequest);
		
		if (!$this->CheckAdminAccess($data)) {
			
			return FALSE;
		}
		
		$data['strings']['title'] = 'Edit Stock';
		
		$data['stock'] = $this->Module()->DiscogsGetStock();
		
		echo $this->GetOutput($data);
		
		return TRUE;
	}
	
	public function Distro() {
		
		$this->Init();
		
		$data = $this->Module()->GetData($this->_mRequest);
		
		$data['strings']['title'] = 'Distro';
		
		$data['stock'] = $this->Module()->DiscogsGetStock();
		
		echo $this->GetOutput($data);
		
		return TRUE;
	}
}
?>