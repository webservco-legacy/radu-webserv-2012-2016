<?php
echo '<article>';

if (!empty($item)) {
	
	echo '<section class="box_border_round box_light">';
	
	echo "<h3>{$item['review_title']}"; 
	if (2 == $item['review_type']) {
	
		echo '<span class="one_liner">[one-liner]</span>';
	
	} else {
	
		echo '<span class="snooze_fest">[snooze-fest]</span>';
	}
	echo "</h3>";
	
	echo '<hr />';
	
	if (!empty($item['thumb_url'])) {
		
		echo "<img class=\"review_thumb\" src=\"{$item['thumb_url']}\" title=\"{$item['review_title']}\" alt=\"{$item['review_title']}\" />";
	}
	
	echo '<div>';
	echo nl2br($item['review']);
	echo '</div>';
	
	if (!empty($item['label'])) {
		
		echo '<hr />';
		
		echo '<div>';
		echo "<strong>Label(s)</strong>: {$item['label']}";
		echo '</div>';
	}
	
	if (!empty($item['notes'])) {
		
		echo '<hr />';
		
		echo '<div>';
		echo $item['notes'];
		echo '</div>';
	}
	
	echo '<hr />';
	
	echo '<div>';
	echo '<strong>Date:</strong> ';
	echo ucwords(strftime("%A %e %B '%y", strtotime($item['review_date'])));
	echo '</div>';
	
	if (!empty($item['media']['image'])) {
		
		echo '<hr />';
		
		echo '<div class="review_media">';
		
		foreach ($item['media']['image'] as $image) {
			
			echo "<p><img class=\"review_image\" src=\"{$image}\" title=\"{$item['review_title']}\" alt=\"{$item['review_title']}\" /></p>";
		}
		
		echo '</div>';
	}
	
	echo '</section>';
}

echo '<section class="box_border_round">';
echo "<a href=\"{$url['www']}music\" title=\"Music\">Back to music</a>";
echo '</section>';

echo '</article>';
?>