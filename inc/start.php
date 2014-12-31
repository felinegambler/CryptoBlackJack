<?php
/*
 *  © CryptoBlackJack
 *  
 *  
 *  
*/

if (!isset($init)) exit();


session_start();

$conf_c=false;
include __DIR__.'/db-conf.php';
if ($conf_c==false) {
  header('Location: ./install/');
  exit();
}
include __DIR__.'/wallet_driver.php';
include __DIR__.'/functions.php';


if (empty($_GET['unique'])) {
  if (!empty($_COOKIE['unique_J_']) && mysql_num_rows(mysql_query("SELECT `id` FROM `players` WHERE `hash`='".prot($_COOKIE['unique_J_'])."' LIMIT 1"))!=0) {
    header('Location: ./?unique='.$_COOKIE['unique_J_'].'# Do Not Share This URL!');
    exit();  
  }
  newPlayer($wallet);
}
else { // !empty($_GET['unique'])
  if (mysql_num_rows(mysql_query("SELECT `id` FROM `players` WHERE `hash`='".prot($_GET['unique'])."' LIMIT 1"))!=0) {
    $player=mysql_fetch_array(mysql_query("SELECT * FROM `players` WHERE `hash`='".prot($_GET['unique'])."' LIMIT 1"));
    $unique=prot($_GET['unique']);
    setcookie('unique_J_',prot($_GET['unique']),(time()+60*60*24*365*5),'/');  
  }
  else {
    setcookie('unique_J_',false,(time()-10000),'/');
    header('Location: ./');    
    exit();
  }
}


$settings=mysql_fetch_array(mysql_query("SELECT * FROM `system` WHERE `id`=1 LIMIT 1"));


if ($player['password']!='' && (empty($_SESSION['granted']) || $_SESSION['granted']!='yes')) {  
  include __DIR__.'/unlockAccess.php';
  exit();
}


$playingGame=false;

if (mysql_num_rows(mysql_query("SELECT `id` FROM `games` WHERE `ended`=0 AND `player`=$player[id] LIMIT 1"))!=0)
  $playingGame=true;

?>