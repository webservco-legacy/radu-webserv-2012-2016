<?php if (!empty($error_message)) { ?>
<article>

	<section class="box_border">
	<p class="strong ko"><?php echo $error_message; ?></p>
	</section>
</article>
<?php } else { ?>

<article>
	<nav class="menu car_menu">
		<ul>
			<li>
				<a href="<?php echo $url_app; ?>Car/App/Overview/id/<?php echo $car_id; ?>" title="Overview">Overview</a>
			</li>
			<li>
				<a href="<?php echo $url_app; ?>Car/App/Stats/id/<?php echo $car_id; ?>" title="Stats">Stats</a>
			</li>
			<li>
				<a href="<?php echo $url_app; ?>Car/App/Log/id/<?php echo $car_id; ?>" title="Log">Log</a>
			</li>
		</ul>
	</nav>
</article>

<article>

	<section class="box_border">
		<h2><?php echo $title; ?></h2>
		
		<ul>
			<li><strong>Last log update:</strong> <?php echo !empty($stats['last_update']) ? date('d.m.Y', strtotime($stats['last_update'])) : 'n/a'; ?></li>
			<li><strong>Stats date:</strong> <?php echo date('d.m.Y', strtotime($current_date)); ?></li>
			<li><strong>Duration:</strong> <?php echo !empty($stats['duration']) ? "{$stats['duration'][0]} year(s), {$stats['duration'][1]} month(s), {$stats['duration'][2]} day(s)" : 'n/a'; ?></li>
			<li><strong>Distance:</strong> <?php echo $stats['distance']; ?> km</li>
			<li><strong>Fuel Volume:</strong> <?php echo $stats['fuel_volume']; ?> liters</li>
			<li><strong>Fuel Consumption:</strong> <?php echo $stats['fuel_consumption']; ?> liters/100 km</li>
			<li><strong>Cost per km:</strong> <?php echo $stats['cost_km']; ?> lei</li>
			<li><strong>Cost per month:</strong> <?php echo $stats['cost_month']; ?> lei</li>
			<li><strong>Total Cost:</strong> <?php echo $stats['cost_total']; ?> lei</li>
			<li><strong>Total Cost previous owner:</strong> <?php echo $stats['cost_previous']; ?> lei</li>
		<?php foreach ($stats['cost'] as $cost_name => $cost_value) { ?>
			<li><strong><?php echo $cost_name ?> cost:</strong> <?php echo $cost_value; ?> lei</li>
		<?php } ?>
		</ul>
	</section>
	
</article>

<?php } ?>