<?php
/*
 *  © CryptoBlackJack
 *  
 *  
 *  
*/

function walletRequest($method,$driver_login) {
  $data=array(
    'method' => $method,
    'params' => array_values(array()),
    'id' => $method
  );
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