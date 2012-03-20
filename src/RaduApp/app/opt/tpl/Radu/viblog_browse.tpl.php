<article>
<?php
if (!empty($posts)) {

 	foreach ($posts as $item) {
?>
	<section class="box_border post_summary">
		
		<h2>
			<a class="noborder" href="<?php echo $url_app; ?>post-<?php echo $item['post_id'];?>.html" title="<?php echo $item['post_title']; ?>">
				<?php echo $item['post_title']; ?>
			</a>
		</h2>
		
		<p>
			<?php echo date( 'd.m.Y', strtotime($item['post_date'])); ?>: 
			<?php echo $item['total_images'] > 0 ? $item['total_images'] : 'no'; ?> images, 
			<?php echo $item['total_videos'] > 0 ? $item['total_videos'] : 'no'; ?> videos
		</p>
		
		<?php if (!empty($item['post_description'])) { ?>		
		<p><?php echo $item['post_description']; ?></p>
		<?php } ?>
		
		<p>
			<a href="<?php echo $url_app; ?>post-<?php echo $item['post_id'];?>.html" title="<?php echo $item['post_title']; ?>">
			open gallery
			</a>
		</p>
		
	</section>

<?php }
} else {
	?>
	<section class="box_border">
		<p>No posts</p>
	</section>
	<?php
}
?>
</article>