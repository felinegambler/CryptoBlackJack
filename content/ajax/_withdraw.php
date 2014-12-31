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

$player=mysql_fetch_array(mysql_query("SELECT `id`,`balance` FROM `players` WHERE `hash`='".prot($_GET['_unique'])."' LIMIT 1"));

validateAccess($player['id']);

$validate=walletRequest('validateaddress',array($_GET['valid_addr']));
if ($validate['isvalid']==false) {
  $error='yes';
  $con='Address is not valid.';
}
else {
  $player=mysql_fetch_array(mysql_query("SELECT `id`,`balance` FROM `players` WHERE `hash`='".prot($_GET['_unique'])."' LIMIT 1"));
  if (!is_numeric($_GET['amount']) || (double)$_GET['amount']>$player['balance'] || (double)$_GET['amount']<$settings['min_withdrawal']) {
    $error='yes';
    $con='You have insufficient funds.';
  }
  else {
    $amount=(double)$_GET['amount'];
    mysql_query("UPDATE `players` SET `balance`=TRUNCATE(ROUND((`balance`-$amount),9),8) WHERE `id`=$player[id] LIMIT 1");    
    $txid=walletRequest('sendtoaddress',array($_GET['valid_addr'],$amount));
    mysql_query("INSERT INTO `transactions` (`player_id`,`amount`,`txid`) VALUES ($player[id],(0-$amount),'$txid')");
    $error='no';
    $con=$txid;
  }
}
$return=array(
  'error' => $error,
  'content' => $con
);

echo json_encode($return);
?>
