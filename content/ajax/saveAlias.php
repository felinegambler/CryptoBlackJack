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

if (empty($_GET['alias'])) {
  echo json_encode(array('color'=>'red','content'=>'Alias can\'t be empty.'));
  exit();
}

$repaired=substr(prot($_GET['alias']),0,25);

if (mysql_num_rows(mysql_query("SELECT `id` FROM `players` WHERE `alias`='$repaired' LIMIT 1"))!=0) {
  echo json_encode(array('color'=>'red','content'=>'This alias is already taken.'));
  exit();
}

mysql_query("UPDATE `players` SET `alias`='$repaired' WHERE `id`=$player[id] LIMIT 1");

echo json_encode(array('color'=>'green','content'=>'Alias has been saved.','repaired'=>$repaired));
?>
