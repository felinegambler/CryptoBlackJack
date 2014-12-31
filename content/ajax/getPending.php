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

$pendings='<table class="table table-striped" style="text-align: left;">';
$pendings.='<tr><th>Amount ('.$settings['currency_sign'].')</th><th>Confirmations Left</th></tr>';
$searcher=mysql_query("SELECT * FROM `deposits` WHERE `player_id`=$player[id] AND `received`!=0");
if (mysql_num_rows($searcher)==0) $pendings.='<tr><td colspan="2"><i>No pending deposits</i></td></tr>';
while ($dp=mysql_fetch_array($searcher)) {
  $mins_left=$settings['min_confirmations']-$dp['confirmations'];
  $amount=$dp['amount'];
  
  $pendings.='<tr><td><b>'.n_num($amount,true).'</b></td><td>'.$mins_left.'</td></tr>';        
}
 $pendings.='</table>';

echo json_encode(array('content'=>$pendings));
?>
