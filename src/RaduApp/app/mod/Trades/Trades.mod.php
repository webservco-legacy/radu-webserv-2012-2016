<?php
/**
 * $Id$
 *
 * Trades module.
 *
 * @author     Radu Maier <radu@webserv.co>
 * @copyright  Copyright (c) 2008 - 2013, Radu Maier
 * @license    http://www.opensource.org/licenses/bsd-license.php
 * @package    RaduApp
 * @subpackage TradesModule
 * @version    $Revision$
 * @since      Version 7
 * @access     public
 * @filesource
 */
if (!defined('_WEB')) {
	exit();
}

/**
 * Module_Trades
 * Model class for Trades module.
 *
 * @author Radu Maier <radu@webserv.co>
 * @package RaduApp
 * @subpackage TradesModule
 */
class Module_Trades extends ModuleApp {

	public function GetCategories() {

		return array(
				'pub' => 'Publications',
				'audio' => 'Audio' 
		);
	}

	public function GetTradelist($url, $path) {

		$data = array();
		
		$q = "SELECT _id, category, type, title, year, notes FROM r_tradelist
				ORDER BY category, title";
		
		$rs = Core::Database()->Query($q);
		
		if (0 < $rs->num_rows) {
			
			while ($r = $rs->fetch_assoc()) {
				
				$data[$r['category']][$r['_id']] = $r;
				$data[$r['category']][$r['_id']]['thumb_url'] = $this->GetThumbnail($r['_id'], $url, $path);
			}
		}
		
		return $data;
	}

	public function GetThumbnail($itemId, $url, $path) {

		return is_readable("{$path['media']}trades/thumbs/{$itemId}.jpg") ? "{$url['media']}trades/thumbs/{$itemId}.jpg" : "{$url['media']}trades/thumbs/noimage.jpg";
	}
}
?>