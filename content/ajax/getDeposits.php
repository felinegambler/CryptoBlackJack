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

if (ini_get('safe_mode')==false) set_time_limit(0);

if (mysql_num_rows(mysql_query("SELECT * FROM `system` WHERE `id`=1 AND `deposits_last_round`<NOW()-INTERVAL 30 SECOND LIMIT 1"))==1) {
  include '../../inc/check_deposits.php';
  _checkDeposits();
}

?>
