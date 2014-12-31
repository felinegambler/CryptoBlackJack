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

if (empty($_GET['seed']) || (int)$_GET['seed']==0) {
  echo json_encode(array('color'=>'red','content'=>'This must be a number.'));
  exit();
}

$repaired=(int)$_GET['seed'];

mysql_query("UPDATE `players` SET `client_seed`='".substr((string)$repaired,0,12)."' WHERE `id`=$player[id] LIMIT 1");

echo json_encode(array('color'=>'green','content'=>'Client seed has been set.','repaired'=>substr((string)$repaired,0,12)));
?>
