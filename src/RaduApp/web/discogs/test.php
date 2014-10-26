<?php
require './oauthsimple-master/php/OAuthSimple.php';

$oauth = new OAuthSimple();

$oauth->reset();

$oauth_params = array(
    
    'path' => 'http://api.discogs.com/database/search',
    'signatures' => array(
        'consumer_key' => 'REDACTED',
        'shared_secret' => 'REDACTED',
        'oauth_token' => 'REDACTED',
        'oauth_secret' => 'REDACTED'
    ),
    'parameters' => "q=avaricious"
);

$result = $oauth->sign($oauth_params);








$url = $result['signed_url'];

$ch = curl_init();
curl_setopt($ch, CURLOPT_USERAGENT, 'RadiumEra/0.1 +http://radu.webserv.co');
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$output = curl_exec($ch);
curl_close($ch);



// XXX
echo '<pre>';
// print_r($_SESSION);
// echo '<hr />';
print_r($_COOKIE);
echo '<hr />';
echo '</pre>';

echo $output;

?>