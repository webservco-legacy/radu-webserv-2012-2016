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
        <h2><?php echo $title; ?></h2>
        
        <ul class="car_stats">
            <li><strong>Ultima actualizare:</strong> <?php echo !empty($stats['last_update']) ? date('d.m.Y', strtotime($stats['last_update'])) : 'n/a'; ?></li>
            <li><strong>Dată statistici:</strong> <?php echo date('d.m.Y', strtotime($current_date)); ?></li>
            <li><strong>Durata de utilizare:</strong> <?php echo !empty($stats['duration']) ? "{$stats['duration'][0]} year(s), {$stats['duration'][1]} month(s), {$stats['duration'][2]} day(s)" : 'n/a'; ?></li>
            <li><strong>Distanța parcursă:</strong> <?php echo $stats['distance']; ?> km</li>
            <li><strong>Volum combustibil:</strong> <?php echo $stats['fuel_volume']; ?> lt</li>
            <li><strong>Consum mediu:</strong> <?php echo $stats['fuel_consumption']; ?> lt/100 km</li>
            <li><strong>Cost per km:</strong> <?php echo $stats['cost_km']; ?> lei</li>
            <li><strong>Cost per lună:</strong> <?php echo $stats['cost_month']; ?> lei</li>
            <li><strong>Cost total::</strong> <?php echo $stats['cost_total']; ?> lei</li>
            <li><strong>Cost total proprietar(i) anterior(i):</strong> <?php echo $stats['cost_previous']; ?> lei</li>
        <?php foreach ($stats['cost'] as $cost_name => $cost_value) { ?>
            <li><strong>Cost <?php echo $cost_name ?>:</strong> <?php echo $cost_value; ?> lei</li>
        <?php } ?>
        </ul>
    </section>
    
</article>

<?php } ?>