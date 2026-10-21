<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
    <head>
        <meta charset="utf-8" />
        
        <?php /*w3c validator error
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />*/?>
        
        <title><?php echo $strings['title']; ?> - VIBlog</title>
        
        <meta name="description" content="<?php echo $strings['description']; ?>" />

        <meta name="viewport" content="width=device-width, initial-scale=1" />
        
        <?php
        if (!empty($facebook['meta'])) {
            foreach ($facebook['meta'] as $type => $items) {
                if (!empty($items)) {
                    foreach ($items as $property => $content) {
                        echo "<meta property=\"{$type}:{$property}\" content=\"{$content}\" />\n\n\t\t";
                    }
                }
            }
        }
        ?>
        
        <?php /* USE A SIMPLE RESET INSTEAD ?>
        <link rel="stylesheet" href="<?php echo $url['skin']; ?>css/normalize.css">
        <link rel="stylesheet" href="<?php echo $url['skin']; ?>css/main.css">
        */ ?>

        <link rel="stylesheet" href="<?php echo $url['skin']; ?>css/layout.css">
        
        <link rel="stylesheet" media="all" href="<?php echo $url['skin']; ?>css/magnific-popup.css" />
        
        <link rel="stylesheet" media="all" href="<?php echo $url['skin']; ?>css/prettify.css" />
<?php /*?>
        <link rel="stylesheet" media="all" href="<?php echo $url['skin']; ?>css/prettify-desert.css" />
        */?>
        
        <script src="<?php echo $url['scripts']; ?>vendor/modernizr-2.6.2.min.js"></script>
        
    </head>
    <body>
        <!--[if lt IE 7]>
            <p class="browsehappy">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
        <![endif]-->
    
    <main id="layout">
    
        <?php echo $tpl_header; ?>

        <?php //echo $tpl_left; ?>
        
        <section id="content">
        
            <?php
            if ('catalogue' == $info['module']) {
                echo $pbmenu;
            }
            ?>
        
            <?php echo $tpl_content; ?>
            
            <?php
            if ('catalogue' == $info['module']) {
                echo $pbmenu;
            }
            ?>
            
            <?php //echo $tpl_comments; ?>
        </section>
        
        <?php //echo $tpl_right; ?>
        
        
        
        <?php echo $tpl_footer; ?>
    
    </main>

    

    <?php echo $tpl_scripts; ?>

    </body>
</html>
