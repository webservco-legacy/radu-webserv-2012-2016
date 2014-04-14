<?php
/**
 * $Id$
 *
 * Music module.
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
 * Module_Music
 * Model class for Music module.
 *
 * @author Radu Maier <radu@webserv.co>
 * @package RaduApp
 * @subpackage MusicModule
 */
class Module_Music extends ModuleApp {

	public function GetItems($url, $path) {

		$data = array();
		
		$q = "SELECT _id, artist, title, year, format, review, notes, review_date FROM r_music_main ORDER BY review_date DESC";
		
		$rs = Core::Database()->Query($q);
		
		if (0 < $rs->num_rows) {
			
			while ($r = $rs->fetch_assoc()) {
				
				$data[$r['_id']] = $r;
				$data[$r['_id']]['review_title'] = $this->FormatTitle($r);
				$data[$r['_id']]['review_title_seo'] = $this->FormatSeoTitle($r);
				$data[$r['_id']]['review_summary'] = Core::String()->GetFirstItem($r['review'], "\n\r\n");
				$data[$r['_id']]['thumb_url'] = $this->GetThumbnail($r['_id'], $url, $path);
			}
		}
		
		return $data;
	}

	public function GetItem($itemId, $url, $path) {

		$q = "SELECT _id, artist, title, year, format, review, notes, review_date FROM r_music_main WHERE _id = '{$itemId}' LIMIT 1";
		
		$rs = Core::Database()->Query($q);
		
		if (1 == $rs->num_rows) {
			
			$data = $rs->fetch_assoc();
			
			$data['review_title'] = $this->FormatTitle($data);
			
			$data['review_summary'] = Core::String()->GetFirstItem($data['review'], "\n\r\n");
			
			$data['thumb_url'] = $this->GetThumbnail($data['_id'], $url, $path);
			
			$data['media'] = $this->GetMedia($data['_id'], $url, $path);
			
			return $data;
		}
		
		return FALSE;
	}

	public function FormatTitle($data) {

		return htmlspecialchars("{$data['artist']} - {$data['title']} ({$data['year']}, {$data['format']})");
	}

	public function FormatSeoTitle($data) {

		$remove = array(
				'/',
				'.' 
		);
		
		$title = str_replace(' ', '_', trim(str_replace($remove, NULL, Core::String()->RemoveBadChars($data['artist']))));
		$title .= '-' . str_replace(' ', '_', trim(str_replace($remove, NULL, Core::String()->RemoveBadChars($data['title']))));
		$title .= '-' . str_replace(' ', '_', trim(str_replace($remove, NULL, Core::String()->RemoveBadChars(str_replace('"', 'inch', $data['format'])))));
		$title .= '-' . $data['year'];
		
		return strtolower(str_replace('__', '_', $title));
	}

	public function GetMedia($itemId, $url, $path) {

		$data = array();
		
		$q = "SELECT _id, type, file FROM r_music_media WHERE music_id = '{$itemId}'";
		
		$rs = Core::Database()->Query($q);
		
		if (0 < $rs->num_rows) {
			
			while ($r = $rs->fetch_assoc()) {
				
				switch ($r['type']) {
					
					case 'image' :
					default :
						if (is_readable("{$path['media']}music/images/{$r['file']}")) {
							
							$data[$r['type']][$r['_id']] = "{$url['media']}music/images/{$r['file']}";
						}
						break;
				}
			}
		}
		
		return $data;
	}

	public function GetThumbnail($itemId, $url, $path) {

		return is_readable("{$path['media']}music/thumbs/{$itemId}.jpg") ? "{$url['media']}music/thumbs/{$itemId}.jpg" : FALSE;
	}
}
?>
