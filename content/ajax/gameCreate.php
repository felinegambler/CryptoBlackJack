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


$settings=mysql_fetch_array(mysql_query("SELECT * FROM `system` WHERE `id`=1 LIMIT 1"));


if (empty($_GET['wager']) || (double)$_GET['wager']<0) $wager=0; else $wager=(double)$_GET['wager'];

$wbalance=walletRequest('getbalance');
$max_wager=(double)$wbalance/$settings['bankroll_maxbet_ratio'];
if ($wager>$max_wager) {
  echo json_encode(array('error'=>'yes','content'=>'too_big'));
  exit();  
}



$player=mysql_fetch_array(mysql_query("SELECT * FROM `players` WHERE `hash`='".prot($_GET['_unique'])."' LIMIT 1"));

validateAccess($player['id']);

if (mysql_num_rows(mysql_query("SELECT `id` FROM `games` WHERE `player`=$player[id] AND `ended`=0 LIMIT 1"))!=0) {
  echo json_encode(array('error'=>'yes','content'=>'playing'));
  exit();
}

if ($wager>$player['balance']) {
  echo json_encode(array('error'=>'yes','content'=>'balance'));
  exit();  
}

mysql_query("UPDATE `players` SET `balance`=ROUND((`balance`-$wager),8) WHERE `id`=$player[id] LIMIT 1");

 
$initial_shuffle=unserialize($player['initial_shuffle']);
$client_seed=$player['client_seed'];

$final_shuffle['initial_array']=cs_shuffle($client_seed,$initial_shuffle['initial_array']);


$dealer_deck=array(
  $final_shuffle['initial_array'][0],
  $final_shuffle['initial_array'][2]
);
$player_deck=array(
  $final_shuffle['initial_array'][1],
  $final_shuffle['initial_array'][3]
);

$used_cards=4;

$cards=array(
  'dealer-1' => explode('_',$dealer_deck[0]),
  'dealer-2' => explode('_',$dealer_deck[1]),
  'player-1' => explode('_',$player_deck[0]),
  'player-2' => explode('_',$player_deck[1]),
);


if (card_value($cards['player-1'][1])==card_value($cards['player-2'][1])) $accessable=2;
else $accessable=1;


mysql_query("INSERT INTO `games` (`player`,`bet_amount`,`player_deck`,`dealer_deck`,`initial_shuffle`,`client_seed`,`final_shuffle`,`used_cards`,`accessable_actions`) VALUES ($player[id],$wager,'".serialize($player_deck)."','".serialize($dealer_deck)."','$player[initial_shuffle]','$client_seed','".serialize($final_shuffle)."',$used_cards,$accessable)");

$gameID=mysql_insert_id();


$dealerSums=getSums($dealer_deck);
$playerSums=getSums($player_deck);

$data['winner']='-';
  
if (in_array(21,$dealerSums)) {
  mysql_query("UPDATE `games` SET `ended`=1,`winner`='dealer' WHERE `id`=$gameID LIMIT 1");
  $accessable=0;
  $winner='dealer';
  $data['winner']='dealer';
  playerWon($player['id'],$gameID,$wager,'lose',true,serialize($final_shuffle));
}  
else if (in_array(21,$playerSums)) {
  mysql_query("UPDATE `games` SET `ended`=1,`winner`='player' WHERE `id`=$gameID LIMIT 1");
  $accessable=0;
  $winner='player';
  $data['winner']='player';
  playerWon($player['id'],$gameID,$wager,'regular',true,serialize($final_shuffle));
}
else {
  $cards['dealer-2'][0]='-';
  $cards['dealer-2'][1]='-';
  $winner='-';
  $dealerSums='-';
}

if ($dealerSums!='-') $dealerSums=implode(',',$dealerSums);
$playerSums=implode(',',$playerSums);

echo json_encode(array('error'=>'no','content'=>$cards,'sums'=>array('dealer'=>$dealerSums,'player'=>$playerSums),'wager'=>n_num($wager,true),'accessable'=>$accessable,'winner'=>$winner,'data'=>$data));

mysql_query("UPDATE `system` SET `t_bets`=`t_bets`+1,`t_wagered`=ROUND((`t_wagered`+$wager),8),`t_player_profit`=ROUND((`t_player_profit`-".($wager)."),8) WHERE `id`=1 LIMIT 1");
mysql_query("UPDATE `players` SET `t_bets`=`t_bets`+1,`t_wagered`=ROUND((`t_wagered`+$wager),8) WHERE `id`=$player[id] LIMIT 1");
?>
