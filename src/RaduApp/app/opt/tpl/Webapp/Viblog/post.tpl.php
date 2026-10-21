<article>
<?php if (!empty($error_message)) { ?>
    <section class="box_border">
    <p class="strong ko"><?php echo $error_message; ?></p>
    </section>
<?php } else if (!empty($post)) {
    ?>
    <section class="box_border">
        
        <h1>
            <?php echo $post['post_title']; ?>
        </h1>
        
        <h2>
            <?php echo date('d.m.Y', strtotime($post['post_date_start'])); ?>
            <?php if ($post['post_date_end'] != '0000-00-00') { echo ' - ' . date('d.m.y', strtotime($post['post_date_end'])); } ?>
        </h2>
        
        <?php if (!empty($post['post_description'])) { ?>
        <p><?php echo $post['post_description']; ?></p>
        <?php } ?>
        
    </section>
    
    <section class="box_border">
        
        <h4>Images</h4>
        
        <?php if (!empty($images)) { ?>
            
            <div id="image"><div></div></div>
    
            <ul class="images">
            <?php foreach($images as $image) {
                if (empty($image['image_description'])) { $image['image_description'] = $image['image_file']; }
                 ?>
                <li>
                    <a href="<?php echo $url_assets_viblog; ?><?php echo $post['post_id']; ?>/images/<?php echo $image['image_file']; ?>.<?php echo $image['image_type']; ?>" title="<?php echo $image['image_description']; ?>">
                        <img src="<?php echo $url_assets_viblog; ?><?php echo $post['post_id']; ?>/thumbnails/<?php echo $image['image_file']; ?>.<?php echo $image['image_type']; ?>" title="<?php echo $image['image_description']; ?>" alt="<?php echo $image['image_description']; ?>" />
                    </a>
                </li>		
            <?php } ?>
            </ul>		
        
        <?php } else { ?>
            
            <p>No images for this post</p>
            
        <?php } ?>
        
    </section>
    
    <section class="box_border">
        
        <h4>Videos</h4>
        
        <?php if (!empty($videos)) { ?>
            
            <ul class="videos">
            <?php foreach($videos as $video) {
                if (empty($video['video_description'])) { $video['video_description'] = $video['video_file']; }
                 ?>
                <li>
                    <video class="video" width="90%" poster="<?php echo $url_assets_viblog; ?><?php echo $post['post_id']; ?>/videos/<?php echo $video['video_file']; ?>.jpg" autobuffer="true" controls="controls">
                        <source src='<?php echo $url_assets_viblog; ?><?php echo $post['post_id']; ?>/videos/<?php echo $video['video_file']; ?>.mp4' type='video/mp4; codecs="avc1.42E01E, mp4a.40.2"'></source>
                        <source src='<?php echo $url_assets_viblog; ?><?php echo $post['post_id']; ?>/videos/<?php echo $video['video_file']; ?>.webm' type='video/webm; codecs="vp8, vorbis"'></source>
                        <source src='<?php echo $url_assets_viblog; ?><?php echo $post['post_id']; ?>/videos/<?php echo $video['video_file']; ?>.ogv' type='video/ogg; codecs="theora, vorbis"'></source>
                    </video>					
                    <p><?php echo $video['video_description']; ?></p>
                </li>		
            <?php } ?>
            </ul>		
        
        <?php } else { ?>
            
            <p>No videos for this post</p>
            
        <?php } ?>
        
    </section>
    
    <section class="box_border back">
        
        <a href="<?php echo $url_app; ?>viblog.html" title="VIBlog">
            Back
        </a>
        
    </section>
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
