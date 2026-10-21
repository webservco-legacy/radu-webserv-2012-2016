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
            
            echo '<div class="trade_item_box">';
            
                echo '<div class="trade_item_thumb">';
            
                echo "<img src=\"{$item['thumb_url']}\" title=\"{$item['title']}\" alt=\"{$item['title']}\" />";
                
                echo '</div>';
            
            echo "<strong>{$item['title']}</strong><br />";
            
            if (!empty($item['year'])) {
                
                echo " ({$item['year']})";
            }
            
            echo " {$item['type']}";
            
            if (!empty($item['notes'])) {
                
                echo " [{$item['notes']}]";
            }
            
            echo '</div>';
        }
        
        echo '</section>';
    }
}

echo '</article>';
?>