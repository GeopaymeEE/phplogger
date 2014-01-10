<?php
require dirname(__FILE__) . '/../config.php';
//database must be already created !


$sql = <<<EOT

CREATE TABLE log(
  id bigint auto_increment primary key,
  timestamp datetime,
  site varchar(100),
  sitegroup varchar(100),
  level varchar(20),
  tag varchar(200),
  message text,
  ip varchar(30),
  useragent varchar(1000),
  referer varchar(1000),
  current_url varchar(1000),
  timezone varchar(30),
  landingpage varchar(255),
  pagetype varchar(100),
  var1 varchar(100),
  status varchar(20),
  confirmed_by varchar(100),
  confirmed_at datetime
) ENGINE=MYISAM;

EOT;

//todo : timestamp doesn't support milliseconds - datetime(6) works only in mysql>5.6
// todo : try catches, drop test ...

$dbh = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_USERNAME, DB_PASSWORD);
$dbh->exec($sql);






