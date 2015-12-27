<?php
echo '<article>';
if (! empty($stock)) {
	
	echo '<table class="discogs_stock">';
	
	echo '<tr>';
	echo '	<th>location</th>';
	echo '	<th>artist / title</th>';
	echo '	<th>&#160;</th>';
	echo '	<th>format</th>';
	echo '	<th>condition</th>';
	echo '	<th>info</th>';
	echo '	<th>price</th>';
	echo '	<th>&#160;</th>';
	echo '	<th>market</th>';
	echo '</tr>';
	
	$l = 1;
	$i = 1;
	foreach ($stock as $location => $items) {
		
		$class = $l % 2 ? 'one' : 'two';
		
		foreach ($items as $item) {
			
			echo "<tr class=\"{$class}\">";
			echo '	<td class="location">';
			echo "<input class=\"discogs_location\" id=\"discogs_location_{$item['listing_id']}\" value=\"";
			echo htmlentities($item['location'], ENT_QUOTES);
			echo "\" />";
			echo '</td>';
			echo '	<td class="artist">';
			echo "<a href=\"{$item['market_url']}\" target=\"_blank\">";
			echo Core::String()->Truncate($item['artist'], 40);
			echo '</a>';
			echo '</td>';
			echo '	<td class="title"> - ';
			echo "<a href=\"{$item['market_url']}\" target=\"_blank\">";
			echo Core::String()->Truncate($item['title'], 40);
			echo '</a>';
			echo '</td>';
			echo '	<td class="format">'; echo Core::String()->Truncate($item['format'], 20); echo '</td>';
			echo '	<td class="condition">';
			echo !empty($item['media_condition_code']) ? $item['media_condition_code'] : '-'; 
			echo '/'; 
			echo !empty($item['sleeve_condition_code']) ? $item['sleeve_condition_code'] : '-'; 
			'</td>';
			echo '	<td class="info">';
			if (!empty($item['price_info'])) {
			    
			    $price_info = unserialize($item['price_info']);
			    
			    $i = 0;
			    foreach ($price_info as $info) {
			        
			        if ($i < 3) {
			            
			            echo "{$info['price']}";
			            if (!empty($info['converted_price'])) {
			                echo "({$info['converted_price']})";
			            }
			            echo ' ';
			            echo "{$info['country']} ";
			            echo " ({$info['cond_media']}";
			            if (!empty($info['cond_sleeve'])) {
			                echo "/ {$info['cond_sleeve']}";
			            }
			            echo ')';
			            echo '<br />';
			            
			        }
			        
			        $i++;
			        
			        
			    }
			}
			echo '</td>';
			echo '	<td class="price">';
			echo "<input tabindex=\"{$i}\" class=\"discogs_price\" id=\"discogs_price_{$item['listing_id']}\" value=\"{$item['price']}\" />";
			echo '</td>';
			echo '	<td>';
			echo "<a href=\"\" onclick=\"JavaScript:discogs_update_price('{$item['listing_id']}'); return false;\">[&euro;]<a/>";
			echo '</td>';
			echo '	<td class="market">'; 
			if (!empty($item['external_id'])) {
				echo "<a href=\"{$item['external_id']}\" target=\"_blank\">market</a>";
			} else {
				echo '&#160;';
			}
			echo '</td>';
			echo '</tr>';
			
			echo "<tr class=\"{$class}\">";
			echo '<td class="noborder">';
			echo "<a href=\"\" onclick=\"JavaScript:discogs_update_location('{$item['listing_id']}'); return false;\">[l]<a/>";
			echo '</td>';
			echo '<td colspan="6" class="comments noborder">';
			echo "<input class=\"discogs_comments\" id=\"discogs_comments_{$item['listing_id']}\" value=\"";
			echo htmlentities($item['comments'], ENT_QUOTES);
			echo "\" />";
			echo '</td>';
			echo '<td class="noborder">';
			echo "<a href=\"\" onclick=\"JavaScript:discogs_update_comments('{$item['listing_id']}'); return false;\">[c]<a/>";
			echo '</td>';
			echo '<td class="noborder">&#160;</td>';

			echo '</tr>';
			
			$i++;
		}
		
		$l++;
	}
	
	echo '</table>';
}
echo '</article>';
?>