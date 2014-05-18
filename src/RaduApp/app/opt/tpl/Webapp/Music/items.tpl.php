<?php
echo '<article>';


echo '<section class="box_border_round">';
	echo "<h2>{$strings['title']}</h2>";
	if (!empty($facebook['meta']['og']['image'])) {
		echo "<p><img class=\"section_image\" src=\"{$facebook['meta']['og']['image']}\" title=\"{$strings['title']}\" alt=\"{$strings['title']}\" /></p>";
	}
	echo "<p>{$strings['description']}</p>";
echo '</section>';


if (!empty($items)) {
	
	foreach ($items as $item) {
		
		echo '<section class="box_border_round box_light">';
		
		echo "<h3><a href=\"{$url['www']}music/{$item['_id']}-{$item['review_title_seo']}\" title=\"{$item['review_title']}\">{$item['review_title']}</a></h3>";
		
		echo '<hr />';
		
		if (!empty($item['thumb_url'])) {
			
			echo "<a href=\"{$url['www']}music/{$item['_id']}-{$item['review_title_seo']}\" title=\"{$item['review_title']}\">";
				echo "<img class=\"review_thumb\" src=\"{$item['thumb_url']}\" title=\"{$item['review_title']}\" alt=\"{$item['review_title']}\" />";
			echo "</a>";
		}
		
		echo '<div class="review_summary">';
		echo nl2br($item['review_summary']);
		echo '</div>';
		
		echo "<a href=\"{$url['www']}music/{$item['_id']}-{$item['review_title_seo']}\" title=\"{$item['review_title']}\">Read on</a>";
		
		echo '</section>';
	}
}

echo '</article>';
?>