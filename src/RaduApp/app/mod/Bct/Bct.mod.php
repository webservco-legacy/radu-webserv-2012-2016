<?php
if (! defined('_WEB')) {
    exit();
}

class Module_Bct extends ModuleApp
{

    public function GetDiscogsCollectionFolders() {
    	
    	$data = array();
    	
    	$folders = $this->Discogs()->GetDiscogsCollectionFolders('radum');
    	
    	if (!empty($folders['folders'])) {
    		
    		foreach ($folders['folders'] as $item) {
    			$data[$item['id']] = $item['name'];
    		}
    	}
    	
    	return $data;
    }
	
	public function DiscogsCollectionLocalInsert()
    {
        $log = 'DiscogsCollectionLocalInsert' . PHP_EOL;
        
        $q = "TRUNCATE TABLE r_music_discogs_collection";
        Core::Database()->Query($q);
        
        $data = array();
        
        $pages = array(
            1,
            2,
            3,
            4,
        ); //XXX hard coded?
        
        set_time_limit(0);
        
        $folders = $this->GetDiscogsCollectionFolders();
        
        foreach ($pages as $page) {
            
            $items = $this->Discogs()->GetDiscogsCollection('radum', 0, $page);
            
            if (! empty($items['releases'])) {
                
                $log .= 'got ' . count($items['releases']) . " items for page {$page}" . PHP_EOL;
                
                foreach ($items['releases'] as $release) {
                	
                	$folder = array_key_exists($release['folder_id'], $folders) ? $folders[$release['folder_id']] : NULL;
                    
                    $q = "INSERT INTO r_music_discogs_collection (instance_id, release_id, folder, instance_data, processed)
					VALUES ('{$release['instance_id']}', '{$release['id']}', '{$folder}', '', '0000-00-00 00:00:00')";
                    Core::Database()->Query($q);
                    
                    $log .= "processed item {$release['instance_id']}" . PHP_EOL;
                }
            }
        }
        
        $log .= "done" . PHP_EOL;
        
        return $log;
    }

    public function DiscogsCollectionLocalGet($toUpdate = FALSE)
    {
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

    public function DiscogsCollectionLocalUpdate()
    {
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
        
        $log .= "done" . PHP_EOL;
        
        return $log;
    }

    public function DiscogsCollectionLocalGetDataToProcess()
    {
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
                                default:
                                    break;
                            }
                        }
                    }
                }
            }
        }
        
        return $data;
    }

    public function DiscogsCollectionLocalGetProcessedData()
    {
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
                                case 4: /* trade */
                                default:
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

    public function DiscogsCollectionLocalProcessTrades()
    {
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
        
        $log .= "done" . PHP_EOL;
        
        return $log;
    }

    public function DiscogsTradelistLocalClear()
    {
        Core::Database()->Query("TRUNCATE TABLE r_tradelist_discogs");
        
        return TRUE;
    }

    public function DiscogsTradelistLocalInsert($item)
    {
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

    public function DiscogsTradelistLocalGet()
    {
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

    public function TradelistLocalThumbnailGet($id)
    {
        $url = Core::Config()->Get('url.www') . 'usr/media/';
        $path = Core::Config()->Get('path.www') . 'usr/media/';
        
        return is_readable("{$path}trades/thumbs/{$id}.jpg") ? "{$url}trades/thumbs/{$id}.jpg" : "{$url}trades/thumbs/noimage.jpg";
    }

    public function TradelistLocalGet()
    {
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

    public function TradelistGetLastUpdate()
    {
        $q = "SELECT GREATEST(DATE(MAX(T.last_update)), DATE(MAX(D.last_update))) AS last_update FROM r_tradelist_discogs D, r_tradelist T";
        
        $rs = Core::Database()->Query($q);
        
        if (1 == $rs->num_rows) {
            
            $r = $rs->fetch_assoc();
            
            return $r['last_update'];
        }
        
        return NULL;
    }

    public function TradelistGetTotal()
    {
        $q = "SELECT (SELECT COUNT(*) FROM r_tradelist_discogs) + (SELECT COUNT(*) FROM r_tradelist) AS total";
        
        $rs = Core::Database()->Query($q);
        
        if (1 == $rs->num_rows) {
            
            $r = $rs->fetch_assoc();
            
            return $r['total'];
        }
        
        return NULL;
    }

    public function TradelistGet()
    {
        $tradelist_local = $this->TradelistLocalGet();
        
        $tradelist_discogs = $this->DiscogsTradelistLocalGet();
        
        return array_merge($tradelist_discogs, $tradelist_local);
    }

    public function DiscogsPriceInfoGet()
    {
        $data = array();
        
        $q = "SELECT listing_id, url, url_data, price_info FROM r_music_discogs_work
            WHERE procedure_done = '0'";
        
        $rs = Core::Database()->Query($q);
        
        if (0 < $rs->num_rows) {
            
            while ($r = $rs->fetch_assoc()) {
                
                $data[$r['listing_id']] = $r;
            }
        }
        
        return $data;
    }
    
    public function DiscogsPriceInfoGetForUpdate()
    {
        $data = array();
    
        $q = "SELECT listing_id, url, url_data, price_info FROM r_music_discogs_work
            WHERE procedure_done = '1' AND url_data != '' AND price_info = ''";
    
        $rs = Core::Database()->Query($q);
    
        if (0 < $rs->num_rows) {
    
            while ($r = $rs->fetch_assoc()) {
    
                $data[$r['listing_id']] = $r;
            }
        }
    
        return $data;
    }
    
    public function DiscogsPriceInfoGetSingle($listingId)
    {
        $q = "SELECT listing_id, url, url_data, price_info FROM r_music_discogs_work
            WHERE listing_id = '{$listingId}' LIMIT 1";
    
        $rs = Core::Database()->Query($q);
    
        if (0 < $rs->num_rows) {
    
            return $rs->fetch_assoc();
        }
    
        return FALSE;
    }
    
    public function DiscogsPriceInfoSetData($listingId, $field, $data) {
        
        $data = Core::Database()->EscapeString($data);
        
        $q = "UPDATE r_music_discogs_work SET {$field} = '{$data}' WHERE listing_id = '{$listingId}' LIMIT 1";
        
        $rs = Core::Database()->Query($q);
        
        return TRUE;
    }
    public function DiscogsPriceInfoSetDone($listingId) {
        
        $q = "UPDATE r_music_discogs_work SET procedure_done = '1' WHERE listing_id = '{$listingId}' LIMIT 1";
        
        $rs = Core::Database()->Query($q);
        
        return TRUE;
    }
    
    public function DiscogsPriceInfoGetUrlData($url) {
        
        $ch = curl_init();
        $timeout = 5;
        curl_setopt($ch,CURLOPT_USERAGENT,'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13');
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    }
    
    public function DiscogsPriceInfoProcedureParseHtml($file_data) {
        
        $path_app = Core::Config()->Get('path.app');
        
        require_once "{$path_app}opt/simple_html_dom/simple_html_dom.php";
        
        $data = array();
        
        $html = str_get_html($file_data);
        
        $i = 0;
        
        
        
        foreach($html->find('table.mpitems') as $table) {
        
            foreach ($table->find('tr.shortcut_navigable') as $tr) {
        
                $seller = $tr->find('td.seller_info ul');
        
                if (!empty($seller[0]) && is_object($seller[0])) {
        
                    if ($x = $seller[0]->find('li', -1) ) {
        
                        $data[$i]['country'] = trim(str_replace('Ships From:', '', $x->plaintext));
                    }
                }
        
                $price = $tr->find('.price');
        
                if (!empty($price[0])) {
        
                    $data[$i]['price'] = trim($price[0]->plaintext);
                }
                
                $converted_price = $tr->find('.converted_price');
        
                if (!empty($converted_price[0])) {
        
                    $data[$i]['converted_price'] = trim($converted_price[0]->plaintext);
                }
        
                /*
                 * 
                 $cond_media = $tr->find('.item_media_condition');
                if (!empty($cond_media[0])) {
                	$data[$i]['cond_media'] = trim($cond_media[0]->plaintext);
                }
                
                $cond_media = $tr->find('.media-condition-tooltip');
                $data[$i]['cond_media'] = !empty($cond_media[0]->attr['data-condition']) ? $cond_media[0]->attr['data-condition'] : NULL; 

                */
                $cond_media = $tr->find('.item_condition span');
                $data[$i]['cond_media'] = !empty($cond_media[2]) ? trim($cond_media[2]->plaintext) : NULL;
                
                        
                $cond_sleeve = $tr->find('.item_sleeve_condition');
        
                if (!empty($cond_sleeve[0])) {
        
                    $data[$i]['cond_sleeve'] = trim($cond_sleeve[0]->plaintext);
                }
        
                $i++;
        
            }
        }
        
        return $data;
    }

    public function DiscogsPriceInfoProcedureUpdatePrices()
    {
        
        set_time_limit(0);
        
        $data = $this->DiscogsPriceInfoGetForUpdate();
        
        echo "DiscogsPriceInfoProcedureUpdatePrices: " . count($data) . '<br />';
        
        if (!empty($data)) {
            
            foreach ($data as $item) {
                
                echo "processing: {$item['listing_id']}<br />";
                
                $price_info = $this->DiscogsPriceInfoProcedureParseHtml($item['url_data']);
                
                if (!empty($price_info)) {
                    
                    $this->DiscogsPriceInfoSetData($item['listing_id'], 'price_info', serialize($price_info));
                }
            }
        }
        
        echo 'done.<br />';
    }
    
    public function DiscogsPriceInfoProcedure()
    {
        
        set_time_limit(0);
        
        $data = $this->DiscogsPriceInfoGet();
        
        echo "DiscogsPriceInfoProcedure: " . count($data) . '<br />';
        
        $i = 0;
        foreach ($data as $item) {
        	
        	$x = $i%25;
        	if (empty($x)) {
        		sleep(1);
        	}
            
            echo "processing: {$item['listing_id']}<br />";
            
            if (!empty($item['url'])) {
                
                $url_data = $this->DiscogsPriceInfoGetUrlData($item['url']);
                
                if (!empty($url_data)) {
                    
                    $this->DiscogsPriceInfoSetData($item['listing_id'], 'url_data', $url_data);
                }
                
                $url_data = NULL; //free memory?
                
            }
            
            $this->DiscogsPriceInfoSetDone($item['listing_id']);
            
            $i++;
        }
        
        echo 'done.<br />';
    }

    public function Devel()
    {
        $log = 'edit Bct.mod:Devel()';
        
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
