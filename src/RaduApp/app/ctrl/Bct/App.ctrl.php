<?php
if (! defined('_WEB')) {
    exit();
}
class Controller_Bct_App extends ControllerApp {

    public function Home() {

        $this->Init();
        
        $data = $this->Module()->GetData($this->_mRequest);
        
        $data['strings']['title'] = $data['facebook']['meta']['og']['title'] = 'Black Chicken Tapes';
        $data['strings']['description'] = $data['facebook']['meta']['og']['description'] = __('Otherground label from Eastern Europe.');
        
        $data['facebook']['meta']['og']['image'] = "{$data['url']['media']}bct/bct_logo.gif";
        
        $data['tradelist'] = $this->Module()->TradelistGet();
        
        $data['tradelist_last_update'] = $this->Module()->TradelistGetLastUpdate();
        
        $data['tradelist_total'] = $this->Module()->TradelistGetTotal();
        
        echo $this->GetOutput($data);
        
        return TRUE;
    }

    public function Devel() {

        $this->Init();
        
        $data = $this->Module()->GetData($this->_mRequest);
        
        /* price procedure test
        $listing_id = !empty($data['query']['l']) ? $data['query']['l'] : NULL; 
        $data['devel']['listing'] = $this->Module()->DiscogsPriceInfoGetSingle($listing_id);
        $data['devel']['parsed'] = array();
        if (!empty($data['devel']['listing'])) {
            $data['devel']['parsed'] = $price_info = $this->Module()->DiscogsPriceInfoProcedureParseHtml($data['devel']['listing']['url_data']);
        }
        echo '<pre>'; print_r($data['devel']); exit();
        */
        
        //tradelist update
        $this->Module()->Devel();
        
        return;
    }

    public function DiscogsPriceInfoProcedure() {

        $this->Init();
        
        $data = $this->Module()->GetData($this->_mRequest);
        
        $this->Module()->DiscogsPriceInfoProcedure();
        
        return;
    }

    public function DiscogsPriceInfoProcedureUpdatePrices() {

        $this->Init();
        
        $data = $this->Module()->GetData($this->_mRequest);
        
        $this->Module()->DiscogsPriceInfoProcedureUpdatePrices();
        
        return;
    }

    public function DiscogsRelease() {

        $this->Init();
        
        $data = $this->Module()->GetData($this->_mRequest);
        
        $release_id = ! empty($data['query']['r']) ? $data['query']['r'] : NULL;
        
        if (empty($release_id)) {
            exit('/r/xxx');
        }
        
        $url = "https://api.discogs.com/releases/{$release_id}";
        $ch = curl_init();
        $timeout = 5;
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13');
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $res = curl_exec($ch);
        curl_close($ch);
        
        $release = NULL;
        if (! empty($res)) {
            
            $r = json_decode($res, TRUE);
            
            $release['artists'] = NULL;
            foreach ($r['artists'] as $a) {
                $release['artists'] .= "{$a['name']}{$a['join']}";
            }
            
            $release['country'] = $r['country'];
            
            foreach ($r['labels'] as $l) {
                $release['labels'][] = "{$l['name']} {$l['catno']}";
            }
            $release['year'] = $r['year'];
            
            $release['x_at'] = "{$release['artists']} - {$r['title']}";
            $release['x_cyl'] = "{$r['country']} {$r['year']} " . implode(' ', $release['labels']);
            
            $i = 0;
            $release['x_t'] = NULL;
            foreach ($r['tracklist'] as $item) {
                
                $release['x_t'] .= "{$item['position']}. ";
                if (! empty($item['artists'])) {
                    foreach ($item['artists'] as $a) {
                        $release['x_t'] .= "{$a['name']} - ";
                    }
                }
                $release['x_t'] .= "{$item['title']}\n";
                
                $i ++;
            }
        }
        
        echo '<pre>';
        //print_r($r);
        print_r($release);
        exit();
    }
}
?>