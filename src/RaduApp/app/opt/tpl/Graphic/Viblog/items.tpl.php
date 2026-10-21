<article>

<?php if (!empty($posts)) {

    /*
    echo '<section class="box_border_round">';
    echo "<h2>{$strings['title']}</h2>";
    //if (!empty($facebook['meta']['og']['image'])) {
        //echo "<p><img class=\"section_image\" src=\"{$facebook['meta']['og']['image']}\" title=\"{$strings['title']}\" alt=\"{$strings['title']}\" /></p>";
    //}
    echo "<p class=\"viblog_description\">{$strings['description']}</p>";
    echo '</section>';
    */

    foreach ($posts as $item) {
?>
    <section class="box_border_round box_light">
        
        <h3>
            <a class="" href="<?php echo $url['www']; ?>Viblog/App/Item/id/<?php echo $item['post_id'];?>" title="<?php echo $item['post_title']; ?>">
                <?php echo $item['post_title']; ?> <span class="viblog_date">(<?php echo date('d.m.y', strtotime($item['post_date'])); ?>)</span>
            </a>
        </h3>

        <a href="<?php echo $url['www']; ?>Viblog/App/Item/id/<?php echo $item['post_id'];?>" title="<?php echo $item['post_title']; ?>">

        
            <?php if (!empty($item['post_description'])) { ?>		
                <div class="viblog_summary_desc"><?php echo $item['post_description']; ?></div>
            <?php } ?>
        
            <div class="viblog_summary_stats">
                <?php echo $item['total_images'] > 0 ? $item['total_images'] : 'no'; ?> image(s), 
                <?php echo $item['total_videos'] > 0 ? $item['total_videos'] : 'no'; ?> video(s)
            </div>

        </a>
        
    </section>

<?php }
} else {
    ?>
    <section class="box_border_round">
        <p>No posts</p>
    </section>
    <?php
}
?>

</article>