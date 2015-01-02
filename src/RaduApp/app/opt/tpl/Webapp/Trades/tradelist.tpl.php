<?php
echo '<article>';

echo '<section class="box_border_round">';
echo "<h2>{$strings['title']}</h2>";
if (! empty($facebook['meta']['og']['image'])) {
    echo "<p><img class=\"section_image\" src=\"{$header_image}\" title=\"{$strings['title']}\" alt=\"{$strings['title']}\" /></p>";
}
echo "<p>{$strings['description']}</p>";
echo '</section>';

if (! empty($tradelist)) {
    
    foreach ($tradelist as $category => $data) {
        
        foreach ($data as $subcategory => $items) {
            
            echo '<section class="box_border_round box_light">';
            
            echo '<a name="';
            echo strtolower(str_replace(array(' ', '"'), NULL, $subcategory));
            echo '"></a>';
            
            echo "<h3>{$category} - {$subcategory}</h3>";
            
            foreach ($items as $item) {
                
                echo '<div class="trade_item_box">';
                
                echo '<div class="trade_item_thumb">';
                
                echo "<img src=\"{$item['thumb_url']}\" title=\"{$item['title']}\" alt=\"{$item['title']}\" />";
                
                echo '</div>';
                
                echo "<strong>{$item['title']}</strong><br />";
                
                if (! empty($item['year'])) {
                    
                    echo " ({$item['year']})";
                }
                
                echo " {$item['type']}";
                
                if (! empty($item['notes'])) {
                    
                    echo " [{$item['notes']}]";
                }
                
                echo '</div>';
            }
            
            echo '</section>';
        }
    }
    
    if (!empty($tradelist_badges)) {
        
        echo '<section class="box_border_round box_light"><a name="badges"></a>';
        
        echo "<h3>Badges</h3>";
        
        echo '<div class="trade_badges_left">';
        
        echo "<img class=\"trades_image\" src=\"{$url['media']}trades/badges.jpg\" title=\"Badges for trade\" alt=\"Badges for trade\" />";
        
        echo '</div>';
        
        echo '<div class="trade_badges_right">';
        
        foreach ($tradelist_badges as $id => $badge) {
            
            echo 1 == $badge['active'] ? NULL : '<del>';
            
            echo sprintf('%02d', $id);
            echo " - {$badge['title']}";

            echo 1 == $badge['active'] ? NULL : '</del>';
            
            echo '<br />';
        }
        
        echo '</div>';
        
        echo '</section>';
    }
}

echo '</article>';
?>