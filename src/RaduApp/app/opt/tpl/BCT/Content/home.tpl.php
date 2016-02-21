<?php /*?>
<div id="bct_fixed">
	<a href="<?php echo $url['www']; ?>" title="<?php echo $strings['title']; ?>">
		<span class="logo">/\v/\</span> <span class="title">Black Chicken Tapes</span>
	</a>
</div>
<?php */ ?>
<?php
echo '<article>';


echo '<section class="box_border_round bct_box">';
echo '<h2><span class="logo">/\v/\</span> <span class="title">Black Chicken Tapes</span></h2>';
echo '</section>';

echo '<section class="box_border_round bct_box">';
echo $strings['description'];
echo '</section>';

echo '<section class="box_border_round bct_box">';
echo 'BCT 01: (2001) <strong>Skandall - Demo</strong>';
echo '<br />';
echo 'Romanian Streetpunk from Bacău. Currently out of print.';
echo '</section>';

echo '<section class="box_border_round bct_box">';
echo 'BCT 02: (2014) <strong>Avaricious - Sid Vicious Wⓐs Not So Avaricious</strong>';
echo '<br />';
echo 'One-off international old school anarcho punk project with members from Greece, England and Switzerland.
 80\'s passion of DIRT, Crass, Alternative and the likes, initially captured on vinyl in \'99 and reissued officially on cassette 15 years later.';
echo '<br />';
echo 'Fold-out cover containing all the lyrics.';
echo '<br />';
echo 'No youtube, no bandcamp, no mp3. You will have to discover this cassette the old fashioned way..';
echo '<br />';
echo 'Third edition still available.';
echo '<br />';
echo '<br />';

echo '<div class="popup-gallery">';
for ($a = 1; $a <= 6; $a++) {
	echo "<a href=\"{$url['www']}usr/media/bct/avaricious{$a}.jpg\" title=\"Avaricious tapes\" />";
	echo "<img src=\"{$url['www']}usr/media/bct/thumbnails/avaricious{$a}.jpg\" title=\"Avaricious tapes\" />";
	echo '</a>&#160;';
}
echo '</div>';

echo '<br />';
echo '<br />';
echo '<hr />';
echo '</section>';

echo '<section class="box_border_round bct_box">';
echo '<h2><span class="logo">/\v/\</span> <span class="title">Black Chicken Trades</span></h2>';
echo '</section>';

echo '<section class="box_border_round bct_box">';
echo 'The following items are available for trade. Apart from Black Chicken Tapes releases, everyting is single copies only.';
echo '<br />';
echo 'See anything you like? Get in touch now: <a href="mailto:bct@webserv.co">bct@webserv.co</a>';
if (!empty($tradelist_last_update)) {
	echo '<br />';
	echo '<br />';
	echo '<span class="last_update">';
	echo "Total {$tradelist_total} items. ";
	echo 'Last updated: ' . strftime('%e %B %Y', strtotime($tradelist_last_update)) . '</span>';
	echo '<br />';
}
echo '</section>';

if (! empty($tradelist)) {
	
	foreach ($tradelist as $category => $data) {
		
		foreach ($data as $subcategory => $items) {
			
			echo '<section class="box_border_round box_light trade_item_container">';
			
			echo '<a name="';
			echo strtolower(str_replace(array(
				' ',
				'"' 
			), NULL, $subcategory));
			echo '"></a>';
			
			echo "<h3>{$category} - {$subcategory}</h3>";
			
			foreach ($items as $item) {
				
				echo '<div class="trade_item_box">';
				
				echo '<div class="trade_item_thumb">';
				
				echo "<img src=\"{$item['thumb']}\" title=\"{$item['title']}\" alt=\"{$item['title']}\" />";
				
				echo '</div>';
				
				echo "<strong>{$item['title']}</strong>";
				
				if (! empty($item['release_id'])) {
					echo " <a class=\"discogs\" href=\"https://www.discogs.com/release/{$item['release_id']}\" target=\"_blank\" title=\"View info on Discogs\">◎</a>";
				}
				
				if (! empty($item['condition'])) {
					
					echo " {$item['condition']}";
				}
				
				echo "<br />";
				
				echo " {$item['type']}";
				
				if (! empty($item['year'])) {
					
					echo " ({$item['year']})";
				}
				
				if (! empty($item['notes'])) {
					
					echo '<br />';
					echo Core::String()->Linkify($item['notes'], 'target="_blank"');
				}
				
				echo '</div>';
			}
			
			echo '</section>';
		}
	}
	echo '<section class="box_border_round bct_box clear">';
	echo '<hr />';
	echo '</section>';
	
	echo '<section class="box_border_round bct_box">';
	echo '<h2><span class="logo">/\v/\</span> <span class="title">BCT Related</span></h2>';
	echo '</section>';
	
	echo '<section class="box_border_round bct_box">';
	echo "<strong>Pick It Up!</strong> fanzine: <a href=\"{$url['www']}usr/media/fanzines/pick_it_up_1.pdf\" title=\"Pick It Up!\">Issue 1 (2001)</a>";
	echo '<br />';
	echo "<strong>Bollocks - Read All About It</strong> fanzine: <a href=\"{$url['www']}usr/media/fanzines/bollocks-read_all_about_it_0.pdf\" title=\"Bollocks - Read All About It\">Issue 0 (1998)</a>";
	echo '</section>';
}

echo '</article>';
?>