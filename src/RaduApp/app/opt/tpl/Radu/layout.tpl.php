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
<!doctype html>

<html class="no-js" lang="<?php echo $lang; ?>" dir="ltr" manifest="<?php echo $url_app; ?>cache.appcache">

	<head>
		
		<meta charset="utf-8" />

		<meta http-equiv="X-UA-Compatible" content="chrome=1" />
		
		<title><?php echo $title; ?> - <?php echo $title_app; ?></title>
		
		<meta name="description" content="" />
		<meta name="author" content="Radu Maier" />
		<meta name="keywords" content="" />
		
		<meta name="HandheldFriendly" content="True">
		<meta name="MobileOptimized" content="320">
		<meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; minimum-scale=1.0; user-scalable=0;">
		
		<link rel="apple-touch-icon-precomposed" sizes="114x114" href="<?php echo $url_skin; ?>img/apple-touch-icon-114x114-precomposed.png">
		<link rel="apple-touch-icon-precomposed" sizes="72x72" href="<?php echo $url_skin; ?>img/apple-touch-icon-72x72-precomposed.png">
		<link rel="apple-touch-icon-precomposed" href="<?php echo $url_skin; ?>img/apple-touch-icon-precomposed.png">
		<link rel="shortcut icon" href="<?php echo $url_skin; ?>img/apple-touch-icon-precomposed.png">
		
		<meta name="apple-mobile-web-app-capable" content="yes" />
		<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent" />

		<script>
		//if (window.navigator.standalone != true) {
			(function(a,b,c){if(c in b&&b[c]){var d,e=a.location,f=/^(a|html)$/i;a.addEventListener("click",function(a){d=a.target;while(!f.test(d.nodeName))d=d.parentNode;"href"in d&&(d.href.indexOf("http")||~d.href.indexOf(e.host))&&(a.preventDefault(),e.href=d.href)},!1)}})(document,window.navigator,"standalone")
		//}
		</script>

		<link rel="apple-touch-startup-image" href="<?php echo $url_skin; ?>img/splash.png">
		
		<meta property="og:title" content="<?php echo $title_app; ?>" />
		<meta property="og:description" content="" />
		<meta property="og:image" content="<?php echo $url_skin; ?>img/apple-touch-icon-precomposed.png" />
		
		<meta http-equiv="cleartype" content="on">
		
		<link rel="stylesheet" media="all" href="<?php echo $url_skin; ?>css/style.css" />
		
<?php if ('viblog' == $module) { ?>
		<link rel="stylesheet" media="all" href="<?php echo $url_skin; ?>css/photoswipe.css" />
<?php } ?>

		<link rel="stylesheet" media="all" href="<?php echo $url_skin; ?>css/prettify.css" />
		<link rel="stylesheet" media="all" href="<?php echo $url_skin; ?>css/prettify-desert.css" />
		
		<script src="<?php echo $url_scripts; ?>lib/modernizr-custom.js"></script>
		
		<script>Modernizr.mq('(min-width:0)') || document.write('<script src="<?php echo $url_scripts; ?>lib/respond.min.js">\x3C/script>')</script>
		
	</head>
	
	<body onload="prettyPrint()">
	
		<section id="layout">
		
			<header>
			
				<pre id="title" class="prettyprint">&lt;?Radu_Maier&gt;</pre>
				
				<nav id="menu">
					<ul>
					<?php if(!empty($menu)) { ?>
						<?php foreach ($menu as $menu_name => $menu_data) {
							
							$menu_url = (!empty($section_data['url'])) ? $section_data['url'] : $url_app . $menu_data['link'] . '.html'; 
							?>
							<li>
								<a class="<?php if ($menu_name == $menu_id) {echo 'active';} ?>" href="<?php echo $menu_url; ?>" title="<?php echo $menu_data['title']; ?>">
									<?php echo $menu_data['title']; ?>
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
			
			<footer id="footer" class="box_border">
				<a href="http://www.webserv.co" title="Web Services">Web Services</a>
			</footer>
		
		</section>
	
		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
		<script>window.jQuery || document.write('<script src="<?php echo $url_scripts; ?>lib/jquery-1.7.1.min.js"><\/script>')</script>
  
		<script src="<?php echo $url_scripts; ?>lib/helper.js"></script>
		
		<script>
		MBP.scaleFix();
		</script>
		
		<script src="<?php echo $url_scripts; ?>lib/prettify/prettify.js"></script>
		
		<script defer src="<?php echo $url_scripts; ?>script.js"></script>
		<script defer src="<?php echo $url_scripts; ?>appcache.js"></script>
		
<?php if ('viblog' == $module) { ?>
		<script src="<?php echo $url_scripts; ?>lib/klass.min.js"></script>
		<script src="<?php echo $url_scripts; ?>lib/code.photoswipe.jquery-3.0.4.min.js"></script>
		<script type="text/javascript"> 
		//<![CDATA[ 
		$(document).ready(function() {
			var myPhotoSwipe = $(".images a").photoSwipe({ enableMouseWheel: false , enableKeyboard: false});
		});
		//]]> 
		</script>
<?php } ?>
	
		<!--[if lt IE 7 ]>
		<script src="//ajax.googleapis.com/ajax/libs/chrome-frame/1.0.3/CFInstall.min.js"></script>
		<script>window.attachEvent('onload',function(){CFInstall.check({mode:'overlay'})})</script>
		<![endif]-->
		
	</body>
	
</html>