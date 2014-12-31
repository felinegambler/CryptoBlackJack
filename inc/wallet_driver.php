<?php 
/*
 *  © CryptoBlackJack
 *  
 *  
 *  
*/

if (!isset($init)) exit();


function walletRequest($method,$params=null) {
  $data=array(
    'method' => $method,
    'params' => array_values((array)$params),
    'id' => $method
  );
  include __DIR__.'/driver-conf.php';
  $options=array(
    'http' => array(
      'method'  => 'POST',
      'header'  => 'Content-type: application/json',
      'content' => json_encode($data)
    )
  );
  $context=stream_context_create($options);
  if ($response=@file_get_contents($driver_login,false,$context)) {
    $return=json_decode($response,true);
    return $return['result'];
  }
  else return null;
}
?>