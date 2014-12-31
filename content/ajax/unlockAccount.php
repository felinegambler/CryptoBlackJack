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

$player=mysql_fetch_array(mysql_query("SELECT `id`,`password` FROM `players` WHERE `hash`='".prot($_GET['_unique'])."' LIMIT 1"));


if (empty($_GET['pass']) || $player['password']!=hash('sha256',$_GET['pass'])) {
  echo json_encode(array('error'=>'yes','content'=>'Entered password is invalid.'));
  exit();
}


session_start();

$_SESSION['granted']='yes';

echo json_encode(array('error'=>'no'));
?>
