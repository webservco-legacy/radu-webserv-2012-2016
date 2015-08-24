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
if (! defined('_WEB')) {
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

	public function DiscogsUpdateField($listingId, $field, $value) {
		
		$value = Core::Database()->EscapeString($value);

		$q = "UPDATE r_music_discogs SET {$field} = '{$value}' WHERE listing_id = '{$listingId}' LIMIT 1";
		
		$rs = Core::Database()->Query($q);
		
		return TRUE;
	}

	public function DiscogsGetField($listingId, $field) {

		$q = "SELECT {$field} FROM r_music_discogs WHERE listing_id = '{$listingId}' LIMIT 1";
		
		$rs = Core::Database()->Query($q);
		
		if (1 == $rs->num_rows) {
			
			$r = $rs->fetch_assoc();
			
			return $r[$field];
		}
		
		return NULL;
	}

	public function DiscogsGetStock() {

		$data = array();
		
		$q = "SELECT * FROM r_music_discogs ORDER BY location, artist, title";
		
		$rs = Core::Database()->Query($q);
		
		if (0 < $rs->num_rows) {
			
			while ($r = $rs->fetch_assoc()) {
				
				$data[$r['location']][$r['listing_id']] = $r;
				
				if (! empty($r['media_condition'])) {
					
					$data[$r['location']][$r['listing_id']]['media_condition_code'] = $this->Discogs()->GetGradingCode($r['media_condition']);
				}
				
				if (! empty($r['sleeve_condition'])) {
					
					$data[$r['location']][$r['listing_id']]['sleeve_condition_code'] = $this->Discogs()->GetGradingCode($r['sleeve_condition']);
				}
				
				$data[$r['location']][$r['listing_id']]['market_url'] = 'http://www.discogs.com/buy/radum/' . $this->FormatSeoTitle(array(
					'artist' => $r['artist'],
					'title' => $r['title'] 
				)) . "/{$r['listing_id']}";
			}
		}
		
		return $data;
	}

	public function GetItems($url, $path) {

		$data = array();
		
		$q = "SELECT _id, artist, title, year, format, review, notes, review_date, review_type 
				FROM r_music_main 
				WHERE review_status = '1'
				ORDER BY review_date DESC";
		
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

		$q = "SELECT _id, artist, title, year, format, label, review, notes, review_date, review_type FROM r_music_main WHERE _id = '{$itemId}' LIMIT 1";
		
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
		
		$title = NULL;
		
		if (! empty($data['artist'])) {
			
			$title .= str_replace(' ', '_', trim(str_replace($remove, NULL, Core::String()->RemoveBadChars($data['artist']))));
		}
		
		if (! empty($data['title'])) {
			
			$title .= '-' . str_replace(' ', '_', trim(str_replace($remove, NULL, Core::String()->RemoveBadChars($data['title']))));
		}
		
		if (! empty($data['format'])) {
			
			$title .= '-' . str_replace(' ', '_', trim(str_replace($remove, NULL, Core::String()->RemoveBadChars(str_replace('"', 'inch', $data['format'])))));
		}
		
		if (! empty($data['year'])) {
			
			$title .= '-' . $data['year'];
		}
		
		return mb_strtolower(str_replace('__', '_', $title), 'UTF-8');
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
