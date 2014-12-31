<?php
/*
 *  © CryptoBlackJack
 *  
 *  
 *  
*/


header('X-Frame-Options: DENY'); 

$init=true;
include '../../inc/db-conf.php';
include '../../inc/functions.php';

if (empty($_GET['_unique']) || mysql_num_rows(mysql_query("SELECT `id` FROM `players` WHERE `hash`='".prot($_GET['_unique'])."' LIMIT 1"))==0) exit();

$player=mysql_fetch_array(mysql_query("SELECT * FROM `players` WHERE `hash`='".prot($_GET['_unique'])."' LIMIT 1"));

validateAccess($player['id']);

$settings=mysql_fetch_array(mysql_query("SELECT * FROM `system` WHERE `id`=1 LIMIT 1"));

if ($settings['giveaway']!=1) exit();


$captcha=$_SESSION['giveaway_captcha'];

$_SESSION['giveaway_captcha']=generateHash(7);

if (empty($captcha) || empty($_GET['sol']) || strtoupper($_GET['sol'])!=$captcha) {
  echo json_encode(array('error'=>'yes','content'=>'captcha'));
  exit();
}

mysql_query("DELETE FROM `giveaway_ip_limit` WHERE `ip`='".$_SERVER['REMOTE_ADDR']."' AND `claimed`<NOW()-INTERVAL $settings[giveaway_freq] SECOND");
if (mysql_num_rows(mysql_query("SELECT `id` FROM `giveaway_ip_limit` WHERE `ip`='".$_SERVER['REMOTE_ADDR']."' LIMIT 1"))!=0) {
  echo json_encode(array('error'=>'yes','content'=>'time'));
  exit();  
}
if ($player['balance']!=0) {
  echo json_encode(array('error'=>'yes','content'=>'balance'));
  exit();  
}


mysql_query("UPDATE `players` SET `balance`=$settings[giveaway_amount] WHERE `id`=$player[id] LIMIT 1");
mysql_query("INSERT INTO `giveaway_ip_limit` (`ip`) VALUES ('".$_SERVER['REMOTE_ADDR']."')");

echo json_encode(array('error'=>'no'));
?>
