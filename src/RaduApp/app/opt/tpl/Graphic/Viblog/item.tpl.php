<article>
<?php if (!empty($post)) {
    ?>
    <section class="box_border_round box_light">
        
        <h1>
            <?php echo $post['post_title']; ?>
        </h1>
        
        <p class="viblog_date">
            <?php echo date('d.m.Y', strtotime($post['post_date_start'])); ?>
            <?php if ($post['post_date_end'] != '0000-00-00') { echo ' - ' . date('d.m.y', strtotime($post['post_date_end'])); } ?>
        </p>
        
        <?php if (!empty($post['post_description'])) { ?>
        <p><?php echo $post['post_description']; ?></p>
        <?php } ?>
        
    </section>
    
    <?php if (!empty($post['post_tpl_extra'])) { ?>
    <section class="box_border_round box_light">
    <?php require "{$post['post_tpl_extra']}.tpl.php"; ?></p>
    </section>
    <?php } ?>

    <?php if (!empty($images)) { ?>

    
    <section class="box_border_round box_light">
        <h4>Images</h4>
        
            <div id="image"><div></div></div>
    
            <ul class="images popup-gallery">
            <?php foreach($images as $image) {
                if (empty($image['image_description'])) { $image['image_description'] = $image['image_file']; }
                 ?>
                <li>
                    <a <?php /*class="image-popup-vertical-fit"*/?> href="<?php echo $url['media']; ?>viblog/<?php echo $post['post_id']; ?>/images/<?php echo $image['image_file']; ?>.<?php echo $image['image_type']; ?>" title="<?php echo $image['image_description']; ?>">
                        <img src="<?php echo $url['media']; ?>viblog/<?php echo $post['post_id']; ?>/thumbnails/<?php echo $image['image_file']; ?>.<?php echo $image['image_type']; ?>" title="<?php echo $image['image_description']; ?>" alt="<?php echo $image['image_description']; ?>" />
                    </a>
                </li>		
            <?php } ?>
            </ul>		
    </section>	

    <?php } ?>


    <?php if (!empty($videos)) { ?>
    
    <section class="box_border_round box_light">
    
        <h4>Videos</h4>
        
            <ul class="videos">
            <?php foreach($videos as $video) {
                if (empty($video['video_description'])) { $video['video_description'] = $video['video_file']; }
                 ?>
                <li>
                    <video class="video" width="" poster="<?php echo $url['media']; ?>viblog/<?php echo $post['post_id']; ?>/videos/<?php echo $video['video_file']; ?>.jpg" autobuffer="true" controls="controls">
                        <source src='<?php echo $url['media']; ?>viblog/<?php echo $post['post_id']; ?>/videos/<?php echo $video['video_file']; ?>.mp4' type='video/mp4; codecs="avc1.42E01E, mp4a.40.2"'></source>
                        <source src='<?php echo $url['media']; ?>viblog/<?php echo $post['post_id']; ?>/videos/<?php echo $video['video_file']; ?>.webm' type='video/webm; codecs="vp8, vorbis"'></source>
                        <source src='<?php echo $url['media']; ?>viblog/<?php echo $post['post_id']; ?>/videos/<?php echo $video['video_file']; ?>.ogv' type='video/ogg; codecs="theora, vorbis"'></source>
                    </video>					
                    <p><?php echo $video['video_description']; ?></p>
                </li>		
            <?php } ?>
            </ul>		
    
    </section>	

    <?php } ?>
    
    
<?php /*?>
    <section class="box_border_round">
    
        <a href="<?php echo $url['www']; ?>Viblog/App/Items" title="VIBlog">
            Back
        </a>
        
    </section>
    */?>
    <?php
} else {
    ?>
    <section class="box_border">
        <p><?php echo $string_item_not_found; ?></p>
    </section>
    <?php
}
?>
</article>
