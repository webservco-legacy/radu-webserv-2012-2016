<article>
<?php if (!empty($error_message)) { ?>
	<section class="box_border">
		
		<p class="strong ko"><?php echo $error_message; ?></p>
		
	</section>
<?php } else if (!empty($form)) { ?>
	<section class="box_border">
	
		<h1>Account</h1>
		
		<form class="login" enctype="multipart/form-data" method="post" action="">
		
			<fieldset>
					
				<legend></legend>
					
			<?php foreach ($form['meta'] as $field_id => $meta) { ?>
				<label class="tooltip" for="<?php echo $field_id; ?>" title="<?php echo $meta['description']; ?>">
					<?php echo $meta['title']; ?>&#160;
				</label>
					
				<p>
					<input class="tooltip" title="<?php echo $meta['description']; ?>" type="<?php echo $meta['type']; ?>" name="<?php echo $field_id; ?>" id="<?php echo $field_id; ?>" value="<?php echo $form['data'][$field_id]; ?>" /> 
				</p>
					
				<?php if (!empty($form['errors'][$field_id])) { ?>
				<p class="form_error ko">
					<?php foreach ($form['errors'][$field_id] as $error_msg) {
						echo $error_msg . '<br />';
					} ?> 
				</p>
				<?php } ?>				
			<?php } ?>				
					
			</fieldset>		
		
		</form>
		
	</section>
<?php } else if (!empty($user)) { ?>
	<section class="box_border">
	
		<h1>Account</h1>
		
		<p>Welcome <?php echo $user['name']; ?>!</p>
	</section>
<?php } else { ?>
	<section class="box_border">
	
		<h1>Account</h1>
	
		<p><?php echo $string_item_not_found; ?></p>
	</section>
<?php } ?>
</article>