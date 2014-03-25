<?php
require './inc/config.inc.php';

if (!empty($_POST['cmd'])) {
	
	switch ($_POST['cmd']) {
		
		case 'get_details' :
			
			$country = !empty($_POST['country']) ? $_POST['country'] : NULL;
			
			if (!empty($country) && array_key_exists($country, $tam89)) {
				
				$release = get_release($tam89[$country]);
				
				echo get_details($country, $release);
			
			} else {
				
				echo 'error';
			}
		default :
			break;
	}
}

function get_release($releaseId) {

	$release = NULL;
	
	$json_path = "./releases/{$releaseId}.json";
	
	if (is_readable($json_path) && ($file = file_get_contents($json_path))) {
		
		$data = json_decode($file, TRUE);
		
		if (!empty($data)) {
			
			$release['id'] = $releaseId;
			
			if (!empty($data['artists'])) {
				
				$total_artists = count($data['artists']);
				
				$release['artist'] = NULL;
				
				if (1 == $total_artists) {
					
					$release['artist'] = $data['artists'][0]['name'];
				} else {
					
					foreach ($data['artists'] as $artist) {
						$release['artist'] .= "{$artist['name']} {$artist['join']} ";
					}
				}
			}
			
			$release['title'] = !empty($data['title']) ? $data['title'] : NULL;
			$release['year'] = !empty($data['year']) ? $data['year'] : NULL;
			$release['cover'] = !empty($data['images'][0]['uri150']) ? $data['images'][0]['uri150'] : NULL;
			
			if (!empty($release['cover'])) {
				$release['cover'] = str_replace('api.discogs.com', 's.pixogs.com', $release['cover']);
			}
		
		}
	}
	
	return $release;
}

function get_details($country, $details) {

	$out = NULL;
	
	if (!empty($details['cover'])) {
		$out .= "<div class=\"cover\"><img src=\"{$details['cover']}\" /></div>";
	}
	
	$out .= "<div class=\"info\">";
	
	$out .= "<p><strong>{$country}</strong></p>";
	
	if (!empty($details)) {
		
		$out .= "<p>{$details['year']}</p>";
		$out .= "<p>{$details['artist']}</p>";
		$out .= "<p>{$details['title']}</p>";
		$out .= "<p><a href=\"http://www.discogs.com/release/{$details['id']}\" target=\"_blank\">Discogs</a></p>";
	
	} else {
		$out .= "<p>Sorry, no info.</p>";
	}
	
	$out .= "</div>";
	
	return $out;
}
?>
