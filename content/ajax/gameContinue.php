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
include '../../inc/wallet_driver.php';
include '../../inc/functions.php';


if (empty($_GET['_unique']) || mysql_num_rows(mysql_query("SELECT `id` FROM `players` WHERE `hash`='".prot($_GET['_unique'])."' LIMIT 1"))==0) exit();


$player=mysql_fetch_array(mysql_query("SELECT * FROM `players` WHERE `hash`='".prot($_GET['_unique'])."' LIMIT 1"));

validateAccess($player['id']);

$settings=mysql_fetch_array(mysql_query("SELECT * FROM `system` WHERE `id`=1 LIMIT 1"));

$gD_q=mysql_query("SELECT * FROM `games` WHERE `ended`=0 AND `player`=$player[id] LIMIT 1");
if (mysql_num_rows($gD_q)==0) exit();

$gameData=mysql_fetch_array($gD_q);

$dealer['cards']=array();
foreach (unserialize($gameData['dealer_deck']) as $card) {
  $dealer['cards'][]=explode('_',$card);
}
$dealer['cards'][1][0]='-';
$dealer['cards'][1][1]='-';

$player_['cards']=array();
foreach (unserialize($gameData['player_deck']) as $card) {
  $player_['cards'][]=explode('_',$card);
}
$data['mark']='-';
if ($gameData['player_deck_2']!='') {
  $player_['cards2']=array();
  foreach (unserialize($gameData['player_deck_2']) as $card) {
    $player_['cards2'][]=explode('_',$card);
  }
  array_splice($player_['cards'],1,0,array($player_['cards2'][0]));
  unset($player_['cards2'][0]);
  $player_['cards2']=array_values($player_['cards2']);

  $playerSums2=implode(',',getSums(unserialize($gameData['player_deck_2'])));
}
else $playerSums2='-';

$playerSums=implode(',',getSums(unserialize($gameData['player_deck'])));
$dealerSums=implode(',',getSums(unserialize($gameData['dealer_deck'])));

echo json_encode(array('dealer'=>$dealer,'player'=>$player_,'accessable'=>$gameData['accessable_actions'],'sums'=>array('player'=>$playerSums,'player2'=>$playerSums2)));


?>
