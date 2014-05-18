<?php
/**
 * $Id$
 *
 * App controller.
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
 * Controller_Music_App
 * App Controller for Music module.
 *
 * @author Radu Maier <radu@webserv.co>
 * @package RaduApp
 * @subpackage MusicModule
 */
class Controller_Music_App extends ControllerApp {

	public function Items() {

		$this->Init();
		
		$data = $this->Module()->GetData($this->_mRequest);
		
		$data['strings']['title'] = $data['facebook']['meta']['og']['title'] = __('Music');
		$data['strings']['description'] = $data['facebook']['meta']['og']['description'] = __('Thoughts about underground releases.');
		
		$data['facebook']['meta']['og']['image'] = "{$data['url']['media']}music.jpg";
		
		$data['items'] = $this->Module()->GetItems($data['url'], $data['path']);
		
		echo $this->GetOutput($data);
		
		return TRUE;
	}

	public function Item() {

		$this->Init();
		
		$data = $this->Module()->GetData($this->_mRequest);
		
		$data['item_id'] = !empty($data['query']['id']) ? $data['query']['id'] : NULL;
		
		$data['item'] = $this->Module()->GetItem($data['item_id'], $data['url'], $data['path']);
		
		$data['strings']['title'] = $data['facebook']['meta']['og']['title'] = $data['item']['review_title'];
		$data['strings']['description'] = $data['facebook']['meta']['og']['description'] = $data['item']['review_summary'];
		
		if (!empty($data['item']['media']['image'])) {
			
			$id = key($data['item']['media']['image']);
			
			$data['facebook']['meta']['og']['image'] = $data['item']['media']['image'][$id];
		}
		
		$data['comments'] = $this->GetComments('music', $data['item_id'], $data);
		
		echo $this->GetOutput($data);
		
		return TRUE;
	}
}
?>