<?php
/*
 *  © CryptoBlackJack
 *  
 *  
 *  
*/


function _checkDeposits() {
  $init=true;
  include __DIR__.'/db-conf.php';
  include __DIR__.'/wallet_driver.php';

  
  mysql_query("UPDATE `system` SET `deposits_last_round`=NOW() WHERE `id`=1 LIMIT 1");
  
  $settings=mysql_fetch_array(mysql_query("SELECT * FROM `system` WHERE `id`=1 LIMIT 1"));  
  
  $deposits=mysql_query("SELECT * FROM `deposits`");
  while ($dp=mysql_fetch_array($deposits)) {
    $received=0;
    $txid='';
    $txs=walletRequest('listtransactions',array('',1000));
    $txs=array_reverse($txs);
    foreach ($txs as $tx) {
      if ($tx['category']!='receive') continue;
      if ($tx['address']!=$dp['address']) continue;
      $received=$tx['amount'];
      break;
    }
    if ($received<$settings['min_deposit']) continue;
    $txid=($tx['txid']=='')?'[unknown]':$tx['txid'];
    if ($dp['received']==1) {
      if ($tx['confirmations']>=$settings['min_confirmations']) {
        $delExed=false;
        do {
          $delExed=mysql_query("DELETE FROM `deposits` WHERE `id`=$dp[id] LIMIT 1");
        } while ($delExed==false);
        if ($delExed==true) {
          if (mysql_num_rows(mysql_query("SELECT `id` FROM `transactions` WHERE `txid`='$dp[txid]' AND `txid`!='[unknown]' LIMIT 1"))!=0) continue;
          mysql_query("UPDATE `players` SET `balance`=TRUNCATE(ROUND((`balance`+$received),9),8) WHERE `id`=$dp[player_id] LIMIT 1");
          mysql_query("INSERT INTO `transactions` (`player_id`,`amount`,`txid`) VALUES ($dp[player_id],$dp[amount],'$dp[txid]')");
        }
      }
      continue;
    }  
    
    mysql_query("UPDATE `deposits` SET `received`=1,`amount`=$received,`txid`='$txid' WHERE `id`=$dp[id] LIMIT 1");
  }
  mysql_query("DELETE FROM `deposits` WHERE `time_generated`<NOW()-INTERVAL 7 DAY");

}

?>