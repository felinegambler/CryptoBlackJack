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

$player=mysql_fetch_array(mysql_query("SELECT * FROM `players` WHERE `hash`='".prot($_GET['_unique'])."' LIMIT 1"));

validateAccess($player['id']);

if ($player['last_initial_shuffle']=='') 
  $last=array(
    'client_seed' => null,
    'initial_array' => null,
    'initial_array_hash' => null
  );
else
  $last=array(
    'client_seed' => $player['last_client_seed'],
    'initial_array' => stringify_shuffle($player['last_initial_shuffle']),
    'initial_array_hash' => hash('sha256',stringify_shuffle($player['last_initial_shuffle']))
  );



echo json_encode(
      array(
        'next' => array(
                    'client_seed' => $player['client_seed'],
                    'initial_array_hash' => hash('sha256',stringify_shuffle($player['initial_shuffle']))
                  ),
        'last' => $last
      )
);
?>
