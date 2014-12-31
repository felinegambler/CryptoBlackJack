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

$player=mysql_fetch_array(mysql_query("SELECT `id` FROM `players` WHERE `hash`='".prot($_GET['_unique'])."' LIMIT 1"));

validateAccess($player['id']);

$settings=mysql_fetch_array(mysql_query("SELECT * FROM `system` WHERE `id`=1 LIMIT 1"));

if ($settings['chat_enable']==0) exit();

if (empty($_GET['data'])) {
  echo json_encode(array('error'=>'yes','content'=>'nodata'));
  exit();
}


$alone=true;
$lastTen=mysql_query("SELECT * FROM `chat` ORDER BY `time` DESC LIMIT 10");
if (mysql_num_rows($lastTen)<10) $alone=false;
else {
  while ($each=mysql_fetch_array($lastTen)) {
    if ($each['sender']!=$player['id']) {
      $alone=false;
      break;
    }
  }
}

if ($alone) {
  echo json_encode(array('error'=>'yes','content'=>'max_in_row'));
  exit();
}


mysql_query("INSERT INTO `chat` (`sender`,`content`) VALUES ($player[id],'".substr(prot($_GET['data']),0,200)."')");

echo json_encode(array('error'=>'no'));
?>
