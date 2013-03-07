<?php

require_once('config.php');
require_once('codebird.php');

session_start();

Codebird::setConsumerKey(CONSUMER_KEY, CONSUMER_SECRET); // static, see 'Using multiple Codebird instances'
$cb = Codebird::getInstance();

if (! isset($_GET['oauth_verifier'])) {
    // gets a request token
    $reply = $cb->oauth_requestToken(array(
        'oauth_callback' => 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']
    ));

    // stores it
    $cb->setToken($reply->oauth_token, $reply->oauth_token_secret);
    $_SESSION['oauth_token'] = $reply->oauth_token;
    $_SESSION['oauth_token_secret'] = $reply->oauth_token_secret;

    // gets the authorize screen URL
    $auth_url = $cb->oauth_authorize();
    header('Location: ' . $auth_url);
    die();

} else {
    // gets the access token
    $cb->setToken($_SESSION['oauth_token'], $_SESSION['oauth_token_secret']);
    $reply = $cb->oauth_accessToken(array(
        'oauth_verifier' => $_GET['oauth_verifier']
    ));
    //$_SESSION['oauth_token'] = $reply->oauth_token;
    //$_SESSION['oauth_token_secret'] = $reply->oauth_token_secret;
	$cb->setToken($reply->oauth_token, $reply->oauth_token_secret);   //これでユーザAPIたたける
	
	$me = $cb->account_verifyCredentials();  //APIのアクセス方法
	//var_dump($me);
	//exit;
	
	try{
		$dbh = new PDO(DSN, DB_USER, DB_PASSWORD);
	}catch(PDOException $e){
		echo $e->getMessage();
		exit;
	}
	
	$sql  = "select * from users where tw_user_id = :id limit 1";
	$stmt = $dbh->prepare($sql);
	$stmt -> execute(array(":id"=>$me->id_str));
	$user = $stmt->fetch();
	
	if(!$user){
		$sql ="insert into users
			(tw_user_id, tw_screen_name, tw_access_token,
			tw_access_token_secret, created, modified)
			values
(:tw_user_id, :tw_screen_name, :tw_access_token,
			:tw_access_token_secret, now(), now())";
	
//データ格納
	$stmt = $dbh->prepare($sql);
	$params = array(
			":tw_user_id"=> $me->id_str,
			":tw_screen_name"=>$me->screen_name,
			":tw_access_token"=>$reply->oauth_token,
			":tw_access_token_secret"=> $reply->oauth_token_secret);
	$stmt->execute($params);
	
	$myId = $dbh->lastIsertId();
	$sql="select * from users where id = :id limit 1";
	$stmt = $dbh->prepare($sql);
	$stmt -> execute(array(":id"=>$myId));
	$user = $stmt->fetch();
	}
	
	if(!empty($user)){
		//セッションハイジャック対策
		session_regenerate_id(true);
		$_SESSION['me'] =$user;
		
	}
	
	//ホームに飛ばす
	header('Location:' .SITE_URL);
}



?>
