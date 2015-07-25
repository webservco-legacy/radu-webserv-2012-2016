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
	echo '	<th>price</th>';
	echo '	<th>market</th>';
	echo '</tr>';
	
	$l = 1;
	$i = 1;
	foreach ($stock as $location => $items) {
		
		$class = $l % 2 ? 'one' : 'two';
		
		foreach ($items as $item) {
			
			echo "<tr class=\"{$class}\">";
			echo "	<td class=\"location\">{$item['location']}</td>";
			echo '	<td class="artist">';
			echo Core::String()->Truncate($item['artist'], 40);
			echo '</td>';
			echo '	<td class="title"> - ';
			echo Core::String()->Truncate($item['title'], 40);
			echo '</td>';
			echo '	<td class="format">'; echo Core::String()->Truncate($item['format'], 20); echo '</td>';
			echo '	<td class="condition">';
			echo !empty($item['media_condition_code']) ? $item['media_condition_code'] : '-'; 
			echo '/'; 
			echo !empty($item['sleeve_condition_code']) ? $item['sleeve_condition_code'] : '-'; 
			'</td>';
			echo '	<td class="price">';
			echo "{$item['price']}&euro;";
			echo '</td>';
			echo '<td>';
			echo "<a href=\"{$item['market_url']}\" target=\"_blank\">";
			echo 'buy';
			echo '</a>';
			echo '</td>';
			echo '</tr>';
			
			echo "<tr class=\"{$class}\">";
			echo '<td class="noborder">&#160;</td>';
			echo '<td colspan="5" class="comments noborder">';
			echo str_replace(array('Open to offers and some trades. More items available, please visit my profile for full list.', 'All cassettes fully tested.'), '', $item['comments']);
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