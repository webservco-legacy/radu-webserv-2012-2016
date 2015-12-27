<?php
error_reporting(E_ALL);

require 'shd.inc.php';

$file = 'temp.txt';

$url = 'http://www.discogs.com/sell/list?sort=price%2Casc&master_id=42927&ev=mb&format=Cassette';

/* gets the data from a URL */
function get_data($url) {
    $ch = curl_init();
    $timeout = 5;
    curl_setopt($ch,CURLOPT_USERAGENT,'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13');
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
    $data = curl_exec($ch);
    curl_close($ch);
    return $data;
}

/*
$file_data = get_data($url);

if (!empty($file_data)) {
    
    file_put_contents($file, $file_data);
}
*/




$data = array();

$html = file_get_html($file);

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
        
        $cond_media = $tr->find('.item_media_condition');
        
        if (!empty($cond_media[0])) {
            
            $data[$i]['cond_media'] = trim($cond_media[0]->plaintext);
        }
        
        $cond_sleeve = $tr->find('.item_sleeve_condition');
        
        if (!empty($cond_sleeve[0])) {
        
            $data[$i]['cond_sleeve'] = trim($cond_sleeve[0]->plaintext);
        }
        
        $i++;
        
    }
}

echo '<pre>'; print_r($data);

//echo $html->plaintext;

?>