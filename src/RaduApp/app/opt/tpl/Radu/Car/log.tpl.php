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
		
<?php if (!empty($log)) { ?>
	<table class="car_log">
		<tr>
			<th>Date</th>
			<th>Type</th>
			<th>Mileage</th>
			<th>Cost</th>
			<th>Notes</th>
		</tr>
	<?php foreach ($log as $log_id => $log_data) { ?>
		<tr>
			<td class="day"><?php echo date('d.m.Y', strtotime($log_data['day'])); ?></td>		
			<td class="type"><?php echo $log_data['type_name']?></td>
			<td class="km"><?php echo !empty($log_data['km']) ? $log_data['km'] : ''; ?></td>
			<td class="cost"><?php echo ('0.00' != $log_data['cost']) ? "{$log_data['cost']} lei" : ''; ?></td>
			<td class="notes">
				<?php echo $log_data['notes']; ?>
				<?php
				if (1 == $log_data['type']) {
					echo " {$log_data['qty']} liters x " . round($log_data['cost'] / $log_data['qty'], 2) . " lei";
				} ?>
			</td>
		</tr>
	<?php } ?>
	</table>	
<?php } ?>
	</section>
</article>

<?php } ?>