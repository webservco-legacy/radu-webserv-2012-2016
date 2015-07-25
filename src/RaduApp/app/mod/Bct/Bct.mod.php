<?php
if (! defined('_WEB')) {
	exit();
}
class Module_Bct extends ModuleApp {

	public function DiscogsCollectionLocalInsert() {

		$log = 'DiscogsCollectionLocalInsert' . PHP_EOL;
		
		$q = "TRUNCATE TABLE r_music_discogs_collection";
		Core::Database()->Query($q);
		
		$data = array();
		
		$pages = array(
			1,
			2,
			3,
			4 
		);
		
		set_time_limit(0);
		
		foreach ($pages as $page) {
			
			$items = $this->Discogs()->GetDiscogsCollection('radum', 0, $page);
			
			if (! empty($items['releases'])) {
				
				$log .= 'got ' . count($items['releases']) . " items for page {$page}" . PHP_EOL;
				
				foreach ($items['releases'] as $release) {
					
					$q = "INSERT INTO r_music_discogs_collection (instance_id, release_id, instance_data, processed)
					VALUES ('{$release['instance_id']}', '{$release['id']}', '', '0000-00-00 00:00:00')";
					Core::Database()->Query($q);
					
					$log .= "processed item {$release['instance_id']}" . PHP_EOL;
				}
			}
		}
		
		return $log;
	}

	public function DiscogsCollectionLocalGet($toUpdate = FALSE) {

		$data = array();
		
		if ($toUpdate) {
			
			$q = "SELECT instance_id, release_id FROM r_music_discogs_collection WHERE processed = '0000-00-00 00:00:00'";
		} else {
			
			$q = "SELECT instance_id, release_id, instance_data, processed FROM r_music_discogs_collection";
		}
		
		$rs = Core::Database()->Query($q);
		
		if (0 < $rs->num_rows) {
			
			while ($r = $rs->fetch_assoc()) {
				
				$data[$r['instance_id']] = $r;
			}
		}
		
		return $data;
	}

	public function DiscogsCollectionLocalUpdate() {

		$log = 'DiscogsCollectionLocalUpdate' . PHP_EOL;
		
		$items = $this->DiscogsCollectionLocalGet(TRUE);
		
		if (! empty($items)) {
			
			$log .= count($items) . ' items to update' . PHP_EOL;
			
			$i = 0;
			
			set_time_limit(0);
			
			foreach ($items as $item) {
				
				$log .= "Fetching data for {$item['instance_id']}" . PHP_EOL;
				
				$data = $this->Discogs()->GetDiscogsCollectionInstance('radum', 0, $item['release_id'], $item['instance_id']);
				
				if (! empty($data)) {
					
					$data = Core::Database()->EscapeString(json_encode($data));
					
					$q = "UPDATE r_music_discogs_collection SET instance_data = '{$data}', processed = NOW() WHERE instance_id = '{$item['instance_id']}' LIMIT 1";
					
					$rs = Core::Database()->Query($q);
					
					$log .= "Updated data for {$item['instance_id']}" . PHP_EOL;
					
					// if (0 == $i % 10) {
					
					sleep(1);
					// }
				}
			}
		} else {
			
			$log .= 'No items to update' . PHP_EOL;
		}
		
		return $log;
	}

	public function DiscogsCollectionLocalGetDataToProcess() {

		$data = array();
		
		$log = 'DiscogsCollectionLocalGetDataToProcess' . PHP_EOL;
		
		$items = $this->DiscogsCollectionLocalGet();
		
		if (! empty($items)) {
			
			$log .= count($items) . ' items to process' . PHP_EOL;
			
			foreach ($items as $item) {
				
				$log .= "Processing data for {$item['instance_id']}" . PHP_EOL;
				
				$item['instance_data_decoded'] = json_decode($item['instance_data'], TRUE);
				
				if (! empty($item['instance_data_decoded']['notes'])) {
					
					foreach ($item['instance_data_decoded']['notes'] as $notes) {
						
						if (! empty($notes['field_id'])) {
							switch ($notes['field_id']) {
								
								case 4 : /* trade */
									
									if (1 == $notes['value']) {
										
										$log .= "Adding {$item['instance_id']}" . PHP_EOL;
										$data[$item['instance_id']] = $item['instance_data_decoded'];
									}
									break;
								default :
									break;
							}
						}
					}
				}
			}
		}
		
		return $data;
	}

	public function DiscogsCollectionLocalGetProcessedData() {

		$data = array();
		
		$log = 'DiscogsCollectionLocalGetProcessedData' . PHP_EOL;
		
		$data_to_process = $this->DiscogsCollectionLocalGetDataToProcess();
		
		if (! empty($data_to_process)) {
			
			$log .= count($data_to_process) . ' items to process' . PHP_EOL;
			
			foreach ($data_to_process as $line) {
				
				$item = array(
					'instance_id' => $line['instance_id'],
					'release_id' => $line['id'],
					'category' => 'Audio',
					'subcategory' => $line['basic_information']['formats'][0]['name'],
					'type' => NULL,
					'title' => NULL,
					'year' => $line['basic_information']['year'],
					'condition' => NULL,
					'notes' => NULL,
					'thumb' => $line['basic_information']['thumb'] 
				);
				
				$format_descriptions = array();
				
				foreach ($line['basic_information']['formats'] as $format) {
					
					if (! empty($format['descriptions'])) {
						
						$format_descriptions[] = implode(', ', $format['descriptions']);
					}
				}
				
				$item['type'] = implode(', ', $format_descriptions);
				
				if (! empty($line['basic_information']['artists'])) {
					
					$artists = array();
					
					foreach ($line['basic_information']['artists'] as $artist) {
						
						$artists[] = ! empty($artist['anv']) ? $artist['anv'] : $artist['name'];
					}
					
					$item['title'] .= implode(' / ', $artists);
				}
				
				$item['title'] .= ' - ' . $line['basic_information']['title'];
				
				$condition_media = NULL;
				$condition_sleeve = NULL;
				
				if (! empty($line['notes'])) {
					
					foreach ($line['notes'] as $note) {
						
						if (! empty($note['field_id'])) {
							switch ($note['field_id']) {
								
								case 1 : /* media condition */
									$condition_media = $this->Discogs()->GetGradingCode($note['value']);
									break;
								case 2 : /* sleeve condition */
									$condition_sleeve = $this->Discogs()->GetGradingCode($note['value']);
									break;
								case 3 : /* comments */
									$item['notes'] = $note['value'];
									break;
								case 4 : /* trade */
								default :
									break;
							}
						}
					}
				}
				
				$item['condition'] = "{$condition_media}/{$condition_sleeve}";
				
				$log .= "Processed {$item['instance_id']}" . PHP_EOL;
				
				$data[$item['instance_id']] = $item;
			}
		} else {
			
			$log .= 'No items' . PHP_EOL;
		}
		
		return $data;
	}
	
	public function DiscogsCollectionLocalProcessTrades() {
	
		$log = 'DiscogsCollectionLocalProcessTrades' . PHP_EOL;
	
		$processed_data = $this->DiscogsCollectionLocalGetProcessedData();
	
		if (! empty($processed_data)) {
				
			$this->DiscogsTradelistLocalClear();
				
			$log .= 'cleared existing data' . PHP_EOL;
				
			$log .= count($processed_data) . ' items to process' . PHP_EOL;
				
			foreach ($processed_data as $item) {
	
				$this->DiscogsTradelistLocalInsert($item);
	
				$log .= "Processed {$item['instance_id']}" . PHP_EOL;
			}
		} else {
				
			$log .= "No data to process" . PHP_EOL;
		}
	
		return $log;
	}

	public function DiscogsTradelistLocalClear() {

		Core::Database()->Query("TRUNCATE TABLE r_tradelist_discogs");
		
		return TRUE;
	}

	public function DiscogsTradelistLocalInsert($item) {

		$item_escaped = array_map(array(
			Core::Database(),
			'EscapeString' 
		), $item);
		
		$q = "INSERT INTO r_tradelist_discogs (`instance_id`, `release_id`, `category`, `subcategory`, `type`, `title`, `year`, `condition`, `notes`, `thumb`) VALUES (
				'{$item_escaped['instance_id']}', '{$item_escaped['release_id']}', '{$item_escaped['category']}', '{$item_escaped['subcategory']}', 
				'{$item_escaped['type']}', '{$item_escaped['title']}', '{$item_escaped['year']}',
				'{$item_escaped['condition']}', '{$item_escaped['notes']}', '{$item_escaped['thumb']}'
				)";
		
		Core::Database()->Query($q);
		
		return TRUE;
	}

	public function DiscogsTradelistLocalGet() {

		$data = array();
		
		$q = "SELECT `instance_id`, `release_id`, `category`, `subcategory`, `type`, `title`, `year`, `condition`, `notes`, `thumb`
				FROM r_tradelist_discogs ORDER BY subcategory, title";
		
		$rs = Core::Database()->Query($q);
		
		if (0 < $rs->num_rows) {
			
			while ($r = $rs->fetch_assoc()) {
				
				$data[$r['category']][$r['subcategory']][$r['instance_id']] = $r;
			}
		}
		
		return $data;
	}
	
	public function TradelistLocalThumbnailGet($id) {
		
		$url = Core::Config()->Get('url.www') . 'usr/media/';
		$path = Core::Config()->Get('path.www') . 'usr/media/';
		
		return is_readable("{$path}trades/thumbs/{$id}.jpg") ? "{$url}trades/thumbs/{$id}.jpg" : "{$url}trades/thumbs/noimage.jpg";
	}
	
	public function TradelistLocalGet() {
		
		$data = array();
		
		$q = "SELECT _id, category, subcategory, type, title, year, notes FROM r_tradelist
				WHERE active = '1'
				ORDER BY category, subcategory, title";
		
		$rs = Core::Database()->Query($q);
		
		if (0 < $rs->num_rows) {
		
			while ($r = $rs->fetch_assoc()) {
		
				$data[$r['category']][$r['subcategory']][$r['_id']] = $r;
				$data[$r['category']][$r['subcategory']][$r['_id']]['thumb'] = $this->TradelistLocalThumbnailGet($r['_id']);
			}
		}
		
		return $data;
	}
	
	public function TradelistGetLastUpdate() {
		
		$q = "SELECT GREATEST(DATE(MAX(T.last_update)), DATE(MAX(D.last_update))) AS last_update FROM r_tradelist_discogs D, r_tradelist T";
		
		$rs = Core::Database()->Query($q);
		
		if (1 == $rs->num_rows) {
			
			$r = $rs->fetch_assoc();
			
			return $r['last_update'];
		}
		
		return NULL;
	}
	
	public function TradelistGetTotal() {
		
		$q = "SELECT (SELECT COUNT(*) FROM r_tradelist_discogs) + (SELECT COUNT(*) FROM r_tradelist) AS total";
		
		$rs = Core::Database()->Query($q);
		
		if (1 == $rs->num_rows) {
			
			$r = $rs->fetch_assoc();
			
			return $r['total'];
		}
		
		return NULL;
	}
	
	public function TradelistGet() {
		
		$tradelist_local = $this->TradelistLocalGet();
		
		$tradelist_discogs = $this->DiscogsTradelistLocalGet();
		
		return array_merge($tradelist_discogs, $tradelist_local);
	}
	
	

	public function Devel() {
		
		$log = 'none';

		/**
		 * Step 1 - get initial data from Discogs
		 */
		//$log = $this->DiscogsCollectionLocalInsert();
		
		/**
		 * Step 2 - get rest of data from Discogs (run multiple times?)
		 */
		//$log = $this->DiscogsCollectionLocalUpdate();
		
		/**
		 * Step 3 - process local data.
		 */
		//$log = $this->DiscogsCollectionLocalProcessTrades();
		
		echo '<pre>';
		print_r($log);
		exit(); // XXX
	}
}

?>
