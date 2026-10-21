<article>

    <section class="box_border_round">

    <?php if (!empty($strings['message'])) { ?>
    
    <p><?php echo $strings['message']; ?></p>
    
    <?php
    } else {
        
        echo $strings['no_data'];
    }
    ?>
    
    </section>
</article>