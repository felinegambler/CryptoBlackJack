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

if (empty($_GET['lastId']) || (int)$_GET['lastId']==0) {
  $lastid=0;
  $limit=100;
}
else {
  $lastid=(int)$_GET['lastId'];
  $limit=500;
}

$content='';

$messages=mysql_query("SELECT * FROM `chat` WHERE `id`>$lastid ORDER BY `time` DESC,`id` DESC LIMIT $limit");
$messages_array=array();

while ($message=mysql_fetch_array($messages)) {
  $messages_array[]=$message;  
}

$messages=array_reverse($messages_array);

foreach ($messages as $message) {
  $content.='<div class="chat-message" data-messid="'.$message['id'].'">';  
  $sender=mysql_fetch_array(mysql_query("SELECT `alias` FROM `players` WHERE `id`=$message[sender] LIMIT 1"));  
  
  if ($sender==false) $sender['alias']='[unknown]';
  
  $content.='<div class="chat-m-user">'.$sender['alias'].'</div>';
  $content.='<div class="chat-m-time">'.date('i:s',strtotime($message['time'])).'</div>';
  $content.='<div class="chat-m-text">'.$message['content'].'</div>';
  $content.='</div>';
}

echo json_encode(array('content'=>$content));

?>
