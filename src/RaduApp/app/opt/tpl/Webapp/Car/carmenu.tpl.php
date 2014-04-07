<nav class="menu car_menu">
		<ul>
			<li>
				<a class="<?php if (1 == $carmenu_id) {echo ' active';} ?>" href="<?php echo $url['www']; ?>Car/App/Overview/id/<?php echo $car_id; ?>" title="Overview">Overview</a>
			</li>
			<li>
				<a class="<?php if (2 == $carmenu_id) {echo ' active';} ?>" href="<?php echo $url['www']; ?>Car/App/LogPublic/id/<?php echo $car_id; ?>" title="Public Log">Public Log</a>
			</li>
			<li>
				<a class="<?php if (3 == $carmenu_id) {echo ' active';} ?>" href="<?php echo $url['www']; ?>Car/App/Log/id/<?php echo $car_id; ?>" title="Log">Log</a>
			</li>
			<li>
				<a class="<?php if (4 == $carmenu_id) {echo ' active';} ?>" href="<?php echo $url['www']; ?>Car/App/Stats/id/<?php echo $car_id; ?>" title="Stats">Stats</a>
			</li>
		</ul>
	</nav>