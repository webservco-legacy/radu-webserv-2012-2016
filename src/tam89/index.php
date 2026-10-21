<?php require './inc/config.inc.php'; ?>
<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!-->
<html class="no-js">
<!--<![endif]-->
    <head>
        <meta charset="utf-8">
        
        <title><?php echo $title; ?></title>
        
        <meta name="description" content="<?php echo $description; ?>">
        
        <meta property="og:url" content="http://radu.webserv.co/tam89/" />

        <meta property="og:image" content="http://radu.webserv.co/tam89/img/tam89.jpg" />

        <meta property="og:title" content="<?php echo $title; ?>" />

        <meta property="og:description" content="<?php echo $description; ?>" />

        <meta property="og:site_name" content="Radu Maier" />
        
        <meta name="viewport" content="width=device-width">
        
        <link rel="stylesheet" href="./css/normalize.css" />
        <link rel="stylesheet" href="./css/main.css" />
        <link rel="stylesheet" href="./css/layout.css" />
  </head>
  <body>
      
    <!--[if lt IE 7]>
        <p class="chromeframe">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">activate Google Chrome Frame</a> to improve your experience.</p>
    <![endif]-->
    
    <article id="content">
    
        <header>
        
            <a id="ws_btn" href="http://www.tam89records.com/"target="_blank">TAM89 Website</a>
            <span id="help_btn">Info</span>
        
            <h1><?php echo $title; ?></h1>
    
        </header>
      
        <div id="main">
            <div id="help"><?php require './inc/help.inc.php'; ?></div>
            <div id="legend">&#160;</div>
            <div id="map">&#160;</div>
            <div id="details">&#160;</div>
        </div>
        
    
    </article>
    
    <?php require './inc/scripts.inc.php'; ?>
    
    
  </body>
</html>
