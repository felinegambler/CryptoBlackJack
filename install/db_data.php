<?php
/*
 *  © CoinDice 
 *  Demo: http://www.btcircle.com/dice
 *  
 *  
*/

if (!isset($included_)) exit();

$fp=file('CoinJack.sql',FILE_IGNORE_NEW_LINES|FILE_SKIP_EMPTY_LINES);
$query='';
foreach ($fp as $line) {
  if ($line!='' && strpos($line,'--')===false) {
    $query.=$line;
    if (substr($query,-1)==';') {
      mysql_query($query);
        $query='';
    }
  }
}
?>