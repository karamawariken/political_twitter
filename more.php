<?php

require_once('config.php');
require_once('codebird.php');

Codebird::setConsumerKey(CONSUMER_KEY, CONSUMER_SECRET); // static, see 'Using multiple Codebird instances'
$cb = Codebird::getInstance();
$cb->setToken(ACCESS_TOKEN, ACCESS_TOKEN_SECRET);

$params = array(
	'screen_name' => 'karamawari_ken',
	'include_rts' => true
);

if(isset($_GET['max_id'])){
	$params['max_id']=$_GET['max_id'];
	$params['count']=21;
	$tweets=(array)$cb->statuses_homeTimeline($params);
	array_shift($tweets);
}else{
	$tweets=(array)$cb->statuses_homeTimeline($params);
}



array_pop($tweets);
//var_dump($tweets);
foreach($tweets as $tweet){
	echo '<li id="tweet_' . $tweet->id_str . '">' . $tweet->text . '</li>';
}
?>
