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
        <h2><?php echo $strings['title']; ?></h2>
        
<?php if (!empty($log)) {

    $log = array_reverse($log);
    
    ?>
    <table class="car_log">
        <tr>
            <th>Data</th>
            <th>Kilometraj</th>
            <th>Descriere</th>
        </tr>
    <?php foreach ($log as $log_id => $log_data) {

        if (!empty($log_data['log_public'])) {
        ?>
        <tr>
            <td class="day"><?php echo date('d.m.Y', strtotime($log_data['log_date_start'])); ?></td>		
            <td class="km"><?php echo !empty($log_data['log_km']) ? $log_data['log_km'] : ''; ?></td>
            <td><?php echo $log_data['log_description']; ?></td>
        </tr>
    <?php }
        } ?>
    </table>	
<?php } ?>
    </section>
</article>

<?php } ?>