<?php
if (! defined('_WEB')) {
	exit();
}
class Discogs extends Library {

	/**
	 * App prefix.
	 *
	 * @var string
	 */
	protected $_mAppPrefix = NULL;

	public function Init($appPrefix) {

		$this->_mAppPrefix = $appPrefix;
	}

	/**
	 * Implement GetInstance() method.
	 * This is required for PHP < 5.3 compatibility (no late static binding)
	 *
	 * @param string $className
	 *        	class name to instantiate
	 * @return object
	 */
	public static function GetInstance($className = __CLASS__) {

		/**
		 * Call parent method with current class name as parameter.
		 */
		return parent::_GetInstance($className);
	}

	public function GetGradingCode($string) {

		return preg_match('#\((.*?)\)#', $string, $match) ? str_replace('NM or M-', 'NM', $match[1]) : NULL;
	}

	public function GetCurlData($url, $isPost = FALSE, $postData = array()) {

		$curl = curl_init();
		
		curl_setopt_array($curl, array(
			CURLOPT_RETURNTRANSFER => 1,
			CURLOPT_URL => $url,
			CURLOPT_USERAGENT => 'PabloApp/1.0',
			CURLOPT_HEADER => 1,
			CURLOPT_SSL_VERIFYPEER => false,
		));
		
		if ($isPost) {
			
			curl_setopt($curl, CURLOPT_POST, 1);
			
			if (! empty($postData)) {
				
				curl_setopt($curl, CURLOPT_POSTFIELDS, $postData);
			}
		}
		
		if ($resp = curl_exec($curl)) {
			
			curl_close($curl);
			
			list ($header, $body) = explode("\r\n\r\n", $resp, 2);
			
			$body = trim($body);
			
			if (empty($body)) {
				
				die("Error: empty body. header: {$header}");
			}
			
			// echo '<pre>'; print_r($resp); exit(); //XXX
			
			return $body;
		} else {
			
			// XXX
			
			die('Error: "' . curl_error($curl) . '" - Code: ' . curl_errno($curl));
		}
	}

	public function GetDiscogsSearchResults($query, $limit = 15) {

		$results = array();
		
		$query = urlencode($query);
		
		// XXX
		
		// $url = "https://api.discogs.com/database/search?q={$query}";
		
		$path_app = Core::Config()->Get('path.app');
		
		require_once "{$path_app}opt/oauthsimple/php/OAuthSimple.php";
		
		$oauth = new OAuthSimple();
		
		/**
		 * XXX Hard-coded for now
		 */
		$oauth_params = array(
			
			'path' => 'https://api.discogs.com/database/search',
			'signatures' => array(
				'consumer_key' => 'REDACTED',
				'shared_secret' => 'REDACTED',
				'oauth_token' => 'REDACTED',
				'oauth_secret' => 'REDACTED' 
			),
			'parameters' => "q={$query}" 
		);
		
		$oauth_result = $oauth->sign($oauth_params);
		
		if (empty($oauth_result['signed_url'])) {
			
			return FALSE;
		}
		
		$url = $oauth_result['signed_url'];
		
		// XXX
		
		$json = $this->GetCurlData($url);
		
		$data = json_decode($json, TRUE);
		
		if (empty($data)) {
			
			return FALSE;
		}
		
		if (empty($data['results'])) {
			
			return FALSE;
		}
		
		foreach ($data['results'] as $result) {
			
			$thumb = ! empty($result['thumb']) ? str_replace('api.discogs.com', 's.pixogs.com', $result['thumb']) : NULL;
			$thumb = str_replace('/images/', '/image/', $thumb);
			
			$results[] = array(
				
				'id' => $result['id'],
				'type' => $result['type'],
				'title' => $result['title'],
				'thumb' => $thumb,
				'year' => ! empty($result['year']) ? $result['year'] : NULL,
				'format' => ! empty($result['format']) ? implode(', ', $result['format']) : NULL,
				'uri' => ! empty($result['uri']) ? "https://www.discogs.com{$result['uri']}" : NULL 
			);
		}
		
		return array_slice($results, 0, $limit);
	}

	public function GetDiscogsRelease($id, $type = 'release') {

		$release = array();
		
		switch ($type) {
			
			case 'master' :
				$url = "https://api.discogs.com/masters/{$id}";
				break;
			case 'release' :
			default :
				$url = "https://api.discogs.com/releases/{$id}";
				break;
		}
		
		// XXX
		
		// $url = "https://api.discogs.com/database/search?q={$query}";
		
		$path_app = Core::Config()->Get('path.app');
		
		require_once "{$path_app}opt/oauthsimple/php/OAuthSimple.php";
		
		$oauth = new OAuthSimple();
		
		/**
		 * XXX Hard-coded for now
		 */
		$oauth_params = array(
			
			'path' => $url,
			'signatures' => array(
				'consumer_key' => 'REDACTED',
				'shared_secret' => 'REDACTED',
				'oauth_token' => 'REDACTED',
				'oauth_secret' => 'REDACTED' 
			),
			'parameters' => NULL 
		);
		
		$oauth_result = $oauth->sign($oauth_params);
		
		if (empty($oauth_result['signed_url'])) {
			
			return FALSE;
		}
		
		$url = $oauth_result['signed_url'];
		
		// XXX
		
		$json = $this->GetCurlData($url);
		
		$data = json_decode($json, TRUE);
		
		if (empty($data)) {
			
			return FALSE;
		}
		
		$release['discogs_type'] = $type;
		
		$release['discogs_id'] = $id;
		
		$release['uri'] = ! empty($data['uri']) ? $data['uri'] : NULL;
		
		$release['artists'] = NULL;
		
		if (! empty($data['artists'])) {
			
			foreach ($data['artists'] as $artist) {
				
				$release['artists'][] = ! empty($artist['anv']) ? $artist['anv'] : $artist['name'];
			}
		}
		
		$release['title'] = ! empty($data['title']) ? $data['title'] : NULL;
		
		$release['format'] = NULL;
		
		if (! empty($data['formats'])) {
			
			foreach ($data['formats'] as $format) {
				
				if (1 != $format['qty']) {
					
					$release['format'] .= "{$format['qty']} x ";
				}
				
				$release['format'] .= $format['name'];
				
				if (! empty($format['descriptions'])) {
					
					$release['format'] .= ' (' . implode(', ', $format['descriptions']) . ')';
				}
				
				$release['format'] .= ' ';
			}
		}
		
		$release['country'] = ! empty($data['country']) ? $data['country'] : NULL;
		
		$release['year'] = ! empty($data['year']) ? $data['year'] : NULL;
		
		$release['tracks'] = 0;
		
		if (! empty($data['tracklist'])) {
			
			foreach ($data['tracklist'] as $track) {
				
				if ('track' == $track['type_']) {
					
					$release['tracks'] ++;
				}
			}
		}
		
		$release['thumb'] = NULL;
		
		if (! empty($data['images'][0]['uri150'])) {
			
			$release['thumb'] = $data['images'][0]['uri150'];
			// $release['thumb'] = str_replace('api.discogs.com', 's.pixogs.com', $data['images'][0]['uri150']);
			// $release['thumb'] = str_replace('/images/', '/image/', $release['thumb']);
		}
		
		// echo '<pre>'; print_r($data); print_r($release); exit(); // XXX
		
		return $release;
	}

	public function GetDiscogsCollection($userName, $folderId = 0, $page = 1) {

		$collection = array();
		
		// $url = "https://api.discogs.com/users/{$userName}/collection/folders/{$folderId}";
		$url = "https://api.discogs.com/users/{$userName}/collection/folders/{$folderId}/releases";
		// $url = "https://api.discogs.com/users/{$userName}/collection/folders/{$folderId}/releases/6438424/instances/121334708";
		// $url = "https://api.discogs.com/users/{$userName}/collection/folders/{$folderId}/releases";
		// $url = "https://api.discogs.com/users/{$userName}/collection/fields";
		// XXX
		
		$path_app = Core::Config()->Get('path.app');
		
		require_once "{$path_app}opt/oauthsimple/php/OAuthSimple.php";
		
		$oauth = new OAuthSimple();
		
		/**
		 * XXX Hard-coded for now
		 */
		$oauth_params = array(
			
			'path' => $url,
			'signatures' => array(
				'consumer_key' => 'REDACTED',
				'shared_secret' => 'REDACTED',
				'oauth_token' => 'REDACTED',
				'oauth_secret' => 'REDACTED' 
			),
			
			// 'parameters' => 'sort=year&sort_order=desc'
			// 'parameters' => NULL
			'parameters' => "per_page=100&page={$page}" 
		);
		
		$oauth_result = $oauth->sign($oauth_params);
		
		if (empty($oauth_result['signed_url'])) {
			
			return FALSE;
		}
		
		$url = $oauth_result['signed_url'];
		
		$json = $this->GetCurlData($url);
		
		$data = json_decode($json, TRUE);
		
		if (empty($data)) {
			
			return FALSE;
		}
		
		return $data;
	}

	public function GetDiscogsCollectionInstance($userName, $folderId = 0, $releaseId, $instanceId) {

		$collection = array();
		
		$url = "https://api.discogs.com/users/{$userName}/collection/folders/{$folderId}/releases/{$releaseId}/instances/{$instanceId}";
		
		$path_app = Core::Config()->Get('path.app');
		
		require_once "{$path_app}opt/oauthsimple/php/OAuthSimple.php";
		
		$oauth = new OAuthSimple();
		
		/**
		 * XXX Hard-coded for now
		 */
		$oauth_params = array(
			
			'path' => $url,
			'signatures' => array(
				'consumer_key' => 'REDACTED',
				'shared_secret' => 'REDACTED',
				'oauth_token' => 'REDACTED',
				'oauth_secret' => 'REDACTED' 
			),
			
			'parameters' => NULL 
		);
		
		$oauth_result = $oauth->sign($oauth_params);
		
		if (empty($oauth_result['signed_url'])) {
			
			return FALSE;
		}
		
		$url = $oauth_result['signed_url'];
		
		$json = $this->GetCurlData($url);
		
		$data = json_decode($json, TRUE);
		
		if (empty($data)) {
			
			return FALSE;
		}
		
		return $data;
	}
}
?>