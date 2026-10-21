<?php
/**
 * $Id$
 *
 * Layout.
 *
 * @author     Radu Maier <radu@webserv.co>
 * @copyright  Copyright (c) 2008 - 2012, Radu Maier
 * @license    http://www.opensource.org/licenses/bsd-license.php
 */
?>
<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="<?php echo $lang; ?>"<?php if ($appcache) { echo " manifest=\"{$url_app}cache.appcache\"";} ?>> <!--<![endif]-->
    <head>
    
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title><?php echo !empty($title) ? "{$title} - {$title_app}" : $title_app; ?></title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width">

        <link rel="stylesheet" href="<?php echo $url_skin; ?>css/normalize.css">
        <link rel="stylesheet" href="<?php echo $url_skin; ?>css/main.css">
        
        <link rel="stylesheet" media="all" href="<?php echo $url_skin; ?>css/prettify.css" />
        <link rel="stylesheet" media="all" href="<?php echo $url_skin; ?>css/prettify-desert.css" />
        
        <script src="<?php echo $url_scripts; ?>vendor/modernizr-2.6.2.min.js"></script>
    
    </head>
    
    <body>
    
        <!--[if lt IE 7]>
            <p class="chromeframe">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">activate Google Chrome Frame</a> to improve your experience.</p>
        <![endif]-->
    
        <section id="layout">
        
            <header>
            
                <nav id="user">
                    <ul>
                    <?php if (!empty($user)) { ?>
                        <li>
                            <?php echo $user['name']; ?>
                        </li>
                        <li>
                            <a href="<?php echo $url_app; ?>logout.html" title="Log in">Log out</a>
                        </li>
                    <?php } else { ?>
                        <li>
                            <a href="<?php echo $url_app; ?>account.html" title="Log in">Log in</a>
                        </li>
                    <?php } ?>
                    </ul>
                </nav>
            
                <h1 id="title"><a href="<?php echo $url_app; ?>" title="<?php echo $title_app; ?>"><span class="tag">&lt;?</span>Radu<span class="tag">_</span>Maier<span class="tag">&gt;</span></a></h1>
                
                <nav class="menu">
                    <ul>
                    <?php if(!empty($sections)) { ?>
                        <?php foreach ($sections as $section_data) {
                            
                            $section_url = (!empty($section_data['url'])) ? $section_data['url'] : $url_app . $section_data['link'] . '.html'; 
                            ?>
                            <li>
                                <a class="<?php if ($section_data['id'] == $section_id) {echo 'active';} ?>" href="<?php echo $section_url; ?>" title="<?php echo $section_data['title']; ?>">
                                    <?php echo $section_data['title']; ?>
                                </a>
                            </li>
                        <?php } ?>
                    <?php } ?>
                    
                    </ul>
                </nav>
            
            </header>
        
            <article id="content">
                
                <?php echo $content; ?>
            
            </article>
            
            <footer id="footer">
                <a href="http://www.webserv.co" title="Web Services">Web Services</a>
            </footer>
        
        </section>
        
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
        <script>window.jQuery || document.write('<script src="<?php echo $url_scripts; ?>vendor/jquery-1.9.1.min.js"><\/script>')</script>
        
        <script src="<?php echo $url_scripts; ?>vendor/prettify/prettify.js"></script>
        
        <script src="<?php echo $url_scripts; ?>plugins.js"></script>
        <script src="<?php echo $url_scripts; ?>main.js"></script>
        
        <?php if ($appcache) { ?>
        <script src="<?php echo $url_scripts; ?>appcache.js"></script>
        <?php } ?>
        
        <script type="text/javascript">

        <?php if ($appcache) { ?>
        var url_ping_js = "<?php echo $url_scripts; ?>ping.js";
        <?php } ?>
        
        prettyPrint();
        
        </script>
       
        
        <?php /* ?>
        <!-- Google Analytics: change UA-XXXXX-X to be your site's ID. -->
        <script>
            var _gaq=[['_setAccount','UA-XXXXX-X'],['_trackPageview']];
            (function(d,t){var g=d.createElement(t),s=d.getElementsByTagName(t)[0];
            g.src=('https:'==location.protocol?'//ssl':'//www')+'.google-analytics.com/ga.js';
            s.parentNode.insertBefore(g,s)}(document,'script'));
        </script>
        <?php */ ?>
        
    </body>
    
</html>