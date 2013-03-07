<?php

/*


create database political_tw_connect_php;
grant all on political_tw_connect_php.* to dbuser@localhost identified by
'karamawari';

use political_tw_connect_php;

create table users(
	id int not null auto_increment primary key,
	tw_user_id varchar(30) unique,
	tw_screen_name varchar(15),
	tw_access_token varchar(255),
	tw_access_token_secret varchar(255),
	created datetime,
	modified datetime
);

*/

define('DSN','mysql:host=localhost;dbname=political_tw_connect_php');
define('DB_USER', 'dbuser');
define('DB_PASSWORD','karamawari');

define('CONSUMER_KEY','VGU41gwG0HPyGkUweqK8Fg');
define('CONSUMER_SECRET','sw91075P2CvUhVlDB7RhbxuXL0DpbWNTz4onkYMAU');
define('ACCESS_TOKEN' , '224114860-f6slC0Djd2yb4uJ0zsBaW4XyZ1Rx897CrWKEOnE');
define('ACCESS_TOKEN_SECRET', 'XNFwO2QP4rGChkIpGa5dwMrdi9MTi5m9VL2Qn3wMU');

define('SITE_URL','http://localhost:8888/blogtest/tw_mtm/');

error_reporting(E_ALL & ~E_NOTICE);

session_set_cookie_params(0, 'tw_connect_php/');
