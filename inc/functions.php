<?php
/*
 *  © CryptoBlackJack
 *  
 *  
 *  
*/

if (!isset($init)) exit();     

function prot_mail($mail2,$max_delka=0) {
  $mail=mysql_real_escape_string(trim(chop(strip_tags($mail2))));
  if (strpos($mail,"@")==0 || strpos($mail,".")==0 || substr($mail,-1)=="@" || substr($mail,-1)==".")
    $vystup=false;
  else {
    if ($max_delka!=0) {
      if (strlen($mail)>$max_delka)  $vystup=false;
      else  $vystup=$mail;
    }
    else  $vystup=$mail;
  }
  return $vystup;
}

function prot($hodnota,$max_delka=0) {
  $text=mysql_real_escape_string(strip_tags($hodnota));
  if ($max_delka!=0)  $vystup=substr($text,0,$max_delka);
  else  $vystup=$text;
  return $vystup;
}

function generateHash($length,$capt=false) {
  if ($capt==true) $possibilities='123456789ABCDEFGHIJKLMNPQRSTUVWXYZ'; 
  else $possibilities='abcdefghijklmnopqrstuvwxyz1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZ';
  $return='';
  for ($i=0;$i<$length;$i++)  $return.=$possibilities[mt_rand(0,strlen($possibilities)-1)];
  return $return;
}
function random_num($length) {
  $possibilities='1234567890';
  $return='';
  for ($i=0;$i<$length;$i++)  $return.=$possibilities[mt_rand(0,strlen($possibilities)-1)];
  return $return;
}

function card_value($card_val) {
  if ($card_val=='A') return 1;
  else if ($card_val=='J' || $card_val=='Q' || $card_val=='K')
    return 10;
  else return $card_val;
}

function dealerPlays($dealer_deck,$final_shuffle,$used_cards) {
  $settings=mysql_fetch_array(mysql_query("SELECT * FROM `system` WHERE `id`=1 LIMIT 1"));
  
  $threshold=17; // under = HIT
  
  while (max(getSums($dealer_deck))<$threshold) {
    $dealer_deck[]=$final_shuffle[$used_cards];
    $used_cards++;
  }
  if ($settings['hits_on_soft']==1 && max(getSums($dealer_deck))==$threshold && count(getSums($dealer_deck))==2) {
    $dealer_deck[]=$final_shuffle[$used_cards];
    $used_cards++;    
  }
  
  return $dealer_deck;
}

function getSums($deck) {
  $sum=0;
  $card_vals=array();
  foreach ($deck as $cardStr) {
    $card=explode('_',$cardStr);
    $val=card_value($card[1]);
    
    $sum+=$val;
    $card_vals[]=$val;
  }
  $sums=array($sum);
  if (in_array(1,$card_vals) && ($sum+10)<=21) $sums[]=($sum+10);
  
  return $sums;
}

function stringify_shuffle($shuffle) {
  $cards=unserialize($shuffle);
  return implode(';',$cards['initial_array']).';random-string-'.$cards['random_string'];
}

function playerWon($player_id,$game_id,$wager,$regular_or_tie,$blackjack,$final_shuffle='') {
  $settings=mysql_fetch_array(mysql_query("SELECT * FROM `system` WHERE `id`=1 LIMIT 1"));

  if ($settings['bj_pays']==0) $bj_pays=2.5;
  else $bj_pays=2.2;

  if ($blackjack==true) $multip=$bj_pays;
  else $multip=2;
  if ($regular_or_tie=='tie') $multip=1;
  else if ($regular_or_tie=='lose') $multip=0;
  if ($final_shuffle!='') {
    $endGame=  ",`last_client_seed`=`client_seed`"
              .",`last_final_shuffle`='$final_shuffle'"
              .",`last_initial_shuffle`=`initial_shuffle`"
              .",`initial_shuffle`='".generateInitialShuffle()."'";

    mysql_query("UPDATE `games` SET `multiplier`=$multip WHERE `id`=$game_id LIMIT 1");
  }
  else $endGame="";
  mysql_query("UPDATE `players` SET "

              ."`balance`=ROUND((`balance`+".($wager*$multip)."),9)"
              .$endGame              
              ." WHERE `id`=$player_id LIMIT 1");

  $t_wins=0;
  $t_bets=0;
  $t_wagered=0;
  if ($regular_or_tie=='regular') {
    $t_wins=1;
  }
  if ($regular_or_tie=='tie') {
    $t_bets-=1;
    $t_wagered=$wager*-1;
  }
  else 
  mysql_query("UPDATE `system` SET `t_wagered`=`t_wagered`+$t_wagered,`t_bets`=`t_bets`+$t_bets,`t_wins`=`t_wins`+$t_wins,`t_player_profit`=ROUND((`t_player_profit`+".($wager*$multip)."),8) WHERE `id`=1 LIMIT 1");

}

function generateInitialShuffle() {
  $settings=mysql_fetch_array(mysql_query("SELECT `number_of_decks` FROM `system` LIMIT 1"));
  $initial_shuffle=array();
  for ($i=0;$i<$settings['number_of_decks'];$i++) {
    shuffle($initial_shuffle);
    $newDeck=listDeck();
    shuffle($newDeck);
    $initial_shuffle=array_merge($initial_shuffle,listDeck());
    shuffle($initial_shuffle);
  }
  shuffle($initial_shuffle);
  $initial_shuffle=cs_shuffle(mt_rand(),$initial_shuffle);
  return serialize(array('initial_array'=>$initial_shuffle,'random_string'=>generateHash(32)));
}                                                                

function newPlayer($wallet) {
  do $hash=generateHash(32);
  while (mysql_num_rows(mysql_query("SELECT `id` FROM `players` WHERE `hash`='$hash' LIMIT 1"))!=0);
  $alias='Player_';
  $alias_i=mysql_fetch_array(mysql_query("SELECT `autoalias_increment` AS `data` FROM `system` LIMIT 1"));
  $alias_i=$alias_i['data'];
  mysql_query("UPDATE `system` SET `autoalias_increment`=`autoalias_increment`+1 LIMIT 1");
  mysql_query("INSERT INTO `players` (`hash`,`alias`,`time_last_active`,`initial_shuffle`,`client_seed`) VALUES ('$hash','".$alias.$alias_i."',NOW(),'".generateInitialShuffle()."','".random_num(12)."')");
  header('Location: ./?unique='.$hash.'# Do Not Share This URL!');
  exit();
}

function zkrat($str,$max,$iflonger) {
  if (strlen($str)>$max) {
    $str=substr($str,0,$max).$iflonger;
  }
  return $str;
}
function n_num($num,$showall=false) {
  $r=sprintf("%.8f",$num);
  if ($showall==true) return $r;
  else return rtrim(rtrim($r,'0'),'.');
}
function listDeck() {
  $blacks=array(
    '♠_A_black',  '♥_A_black',  '♦_A_black',  '♣_A_black',
    '♠_2_black',  '♥_2_black',  '♦_2_black',  '♣_2_black',
    '♠_3_black',  '♥_3_black',  '♦_3_black',  '♣_3_black',
    '♠_4_black',  '♥_4_black',  '♦_4_black',  '♣_4_black',
    '♠_5_black',  '♥_5_black',  '♦_5_black',  '♣_5_black',
    '♠_6_black',  '♥_6_black',  '♦_6_black',  '♣_6_black',
    '♠_7_black',  '♥_7_black',  '♦_7_black',  '♣_7_black',
    '♠_8_black',  '♥_8_black',  '♦_8_black',  '♣_8_black',
    '♠_9_black',  '♥_9_black',  '♦_9_black',  '♣_9_black',
    '♠_10_black', '♥_10_black', '♦_10_black', '♣_10_black',
    '♠_J_black',  '♥_J_black',  '♦_J_black',  '♣_J_black',
    '♠_Q_black',  '♥_Q_black',  '♦_Q_black',  '♣_Q_black',
    '♠_K_black',  '♥_K_black',  '♦_K_black',  '♣_K_black',
  );
  
  $return=array();
  
  foreach ($blacks as $black) {
    $return[]=$black;
    $return[]=str_replace('_black','_red',$black);
  }
  return $return;
}

function cs_shuffle($client_seed,$deck) {
  
  $final_deck=$deck; // copy deck to final_deck
  
  srand((int)$client_seed);
    
  foreach ($final_deck as $key => $final_card) {
    do {
      $deck_index = rand(0,count($deck)-1);
    } while ($deck[$deck_index]===null);
    
    $final_deck[$key]=$deck[$deck_index];
    
    $deck[$deck_index]=null;
  }
  
  srand(mt_rand());
  
  return $final_deck;
}

function validateAccess($player_id) {
  $player=mysql_fetch_array(mysql_query("SELECT `password` FROM `players` WHERE `id`=$player_id LIMIT 1"));
  session_start();
  if ($player['password']!='' && (empty($_SESSION['granted']) || $_SESSION['granted']!='yes')) {
    exit();
  }
}

?>