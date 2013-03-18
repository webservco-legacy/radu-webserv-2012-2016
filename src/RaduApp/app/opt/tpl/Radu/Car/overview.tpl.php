<?php if (!empty($error_message)) { ?>
<article>

	<section class="box_border">
	<p class="strong ko"><?php echo $error_message; ?></p>
	</section>
</article>
<?php } else { ?>

<article>
	<?php echo $carmenu; ?>
</article>

<article>

	<section class="box_border">

	<?php if (!empty($car_info)) { ?>
		<h2>
			
		<?php echo "{$car_info['car_name']} - {$car_info['car_make']} {$car_info['car_model']} {$car_info['car_year']}"; ?></h2>

		<ul>
			<li><strong>Owner:</strong> <?php echo $car_info['car_owner']; ?></li>
		</ul>
		
	<?php } else { ?>
		<h2><?php echo $title; ?></h2>
	<?php } ?>
	</section>
</article>

<?php } ?>