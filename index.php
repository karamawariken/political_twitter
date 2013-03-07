<?php

require_once('config.php');
require_once('codebird.php');

session_start();

if (empty($_SESSION['me'])){
	header('Location: '.SITE_URL.'login.php');
	exit;
}

function h($s) {
	return htmlspecialchars($s, ENT_QUOTES,"UTF-8");
}

Codebird::setConsumerKey(CONSUMER_KEY, CONSUMER_SECRET); // static, see 'Using multiple Codebird instances'
$cb = Codebird::getInstance();

$cb->setToken($_SESSION['me']['tw_access_token'], $_SESSION['me']['tw_access_token_secret']);

$tweets = (array) $cb->statuses_userTimeline();

array_pop($tweets);  //HTTPの情報をなくして表示

//var_dump($tweets);
//exit;

?>

<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8">
	<title>ホーム画面</title>
	<script src="http://code.jquery.com/jquery-1.9.1.min.js"></script>   
	<link rel="stylesheet" href="tweetbox.css">
	<!--- オフラインの時はdownloadしてjquery-1.9.1.min.js　--->
</head>
<body>
<h1 id="toptilte">ホーム画面</h1>
<p><?php echo h($_SESSION['me']['tw_screen_name']); ?>のTwitterアカウントでログインしています</p>
<p><a href="logout.php">[ログアウト]</a></p>
<p><?php echo h($_SESSION['me']['tw_screen_name']); ?>のツイートログ</p>
<ul>
	<?php foreach($tweets as $tweet): ?>
	<?php if(!$tweet->user->protected): ?>
<li id="tweetbox1"><?php echo h($tweet->text); ?></li>
<?php endif; ?>
<?php endforeach; ?>
</ul>

<p><?php echo h($_SESSION['me']['tw_screen_name']); ?>のフォローしているツイートログ</p>
<ul id="tweets">
	</ul>
	<p id="loading" style="display:none;">loading...</p>
	<input type="button" id="more" value="少し前のツイートを読む">
	<script>
		$(function(){
			var max_id;
			$('#more').click(function() {			//id=moreのボタンをクリックした時の動作
				
				$('#loading').show();				//loadingの文字を有効化
				
				if($('#tweets > li').length) {
					max_id =$('#tweets > li:last').attr('id').replace(/^tweet_/, '');
				}
				//console.log(max_id);
				$.get('more.php' , {
					max_id: max_id
				}, function(rs) {
					$('#loading').hide();
					
					$(rs).appendTo('#tweets').css('border','1px solid #ccc').hide().fadeIn(800); //800ms後に表示させる
					
				});
			});
			/*
			$('#tweets')
			.click(function(){
				$(this).css('font-size','36px')	
			})
			.click(function(){
				$(this).css('font-size','11px')	
			});
			*/
		});
	</script>


</body>
</html>
