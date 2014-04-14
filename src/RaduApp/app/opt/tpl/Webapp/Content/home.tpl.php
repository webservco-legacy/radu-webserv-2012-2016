<article>
		
<?php

/*
<section class="box_border">

<pre class="prettyprint">
#!/bin/bash

MESSAGE='Hello World!'
		
echo ${MESSAGE}
</pre>

</section>
*/

	$boxes = array(
		'music' => array(
			'title' => 'Music',
			'description' => 'Reviews of underground releases.',
			'url' => 'http://radu.webserv.co/music',
		),
		'updb' => array(
			'title' => 'UPDb',
			'description' => 'The Underground Press Database.',
			'url' => 'http://updb.webserv.co',
		),
		'anddevro' => array(
			'title' => 'Android Dev Romania',
			'description' => 'Android and programming forums.',
			'url' => 'http://www.android-developer.ro/',
		),
		'tam89' => array(
			'title' => 'TAM89',
			'description' => 'TAM89 Records releases world map.',
			'url' => 'http://radu.webserv.co/tam89',
		),
	);
	
	echo '<section class="box_border">';
	
	foreach ($boxes as $id => $box) {
		
		echo '<div class="home_box">';
			echo "<a href=\"{$box['url']}\" title=\"{$box['title']}\">";
				echo "<strong>{$box['title']}</strong>";
				echo '<br />';
				echo "<img src=\"{$url['media']}boxes/{$id}.jpg\" alt=\"{$box['title']}\" />";
				echo '<br />';
				echo $box['description'];
			echo '</a>';
		echo '</div>';
	}

	echo '</section>';
?>		

</article>
