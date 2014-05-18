<?php
echo '<article>';

echo '<section class="box_border_round">';
echo "<h2>{$strings['title']}</h2>";
if (!empty($facebook['meta']['og']['image'])) {
	echo "<p><img class=\"section_image\" src=\"{$facebook['meta']['og']['image']}\" title=\"{$strings['title']}\" alt=\"{$strings['title']}\" /></p>";
}
echo "<p>{$strings['description']}</p>";
echo '</section>';

if (!empty($tradelist)) {
	
	foreach ($tradelist as $category => $items) {
		
		echo '<section class="box_border_round box_light">';
		
		echo "<h3>{$categories[$category]}</h3>";
		
		foreach ($items as $item) {
			
			echo '<p>';
			
			echo "<strong>{$item['title']}</strong>";
			
			if (!empty($item['year'])) {
				
				echo " ({$item['year']})";
			}
			
			echo " {$item['type']}";
			
			if (!empty($item['notes'])) {
				
				echo " [{$item['notes']}]";
			}
			
			echo '</p>';
		}
		
		echo '</section>';
	}
}

echo '</article>';
?>