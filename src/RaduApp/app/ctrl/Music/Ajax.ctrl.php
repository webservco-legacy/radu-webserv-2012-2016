<?php
/**
 * $Id: Ajax.ctrl.php 121 2014-09-03 15:15:45Z radu $
 *
 * Ajax controller.
 *
 * Module: Music
 *
 * @author     Radu Maier <radu@webserv.co>
 * @copyright  Copyright (c) 2008 - 2013, Radu Maier
 * @license    http://www.opensource.org/licenses/bsd-license.php
 * @package    RaduApp
 * @subpackage MusicModule
 * @version    $Revision: 121 $
 * @since      Version 7
 * @access     public
 * @filesource
 */
if (! defined('_WEB')) {
	exit();
}

/**
 * Controller_Music_Ajax
 * Ajax Controller for Music module.
 *
 * @author Radu Maier <radu@webserv.co>
 * @package RaduApp
 * @subpackage MusicModule
 */
class Controller_Music_Ajax extends ControllerApp {

	public function Post() {

		$this->Init();
		
		$data = $this->Module()->GetData($this->_mRequest);
		
		if (! empty($_POST['cmd'])) {
			
			switch ($_POST['cmd']) {
				
				case 'discogs_update_field' :
					
					$data['listing_id'] = ! empty($_POST['listing_id']) ? $_POST['listing_id'] : NULL;
					$data['field'] = ! empty($_POST['field']) ? $_POST['field'] : NULL;
					$data['value'] = ! empty($_POST['value']) ? $_POST['value'] : NULL;
					
					if (! empty($data['user']['Logged'])) {
						
						if (! empty($data['listing_id']) && ! empty($data['field']) && !empty($data['value'])) {
							
							if ($this->Module()->DiscogsUpdateField($data['listing_id'], $data['field'], $data['value'])) {
							
								echo $this->Module()->DiscogsGetField($data['listing_id'], $data['field']);
							
								return;
							}
						}
					}
					
					echo 'error';
					
					return;
					
					break;
				
				default :
					break;
			}
		}
	}
}
?>