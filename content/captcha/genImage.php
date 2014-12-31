<?php
/*
 *  © CryptoBlackJack
 *  
 *  
 *  
*/
	
  
session_start();
$possibilities='ABCDEFGHIJKLMNPQRSTUVWXYZ123456789';
$randomnr='';
for ($i=0;$i<7;$i++)    
  $randomnr.=substr($possibilities,rand(0,(strlen($possibilities)-1)),1);
$_SESSION['giveaway_captcha']=strtoupper($randomnr);
$im=imagecreatetruecolor(220,50);
$white=imagecolorallocate($im,255,255,255);
$grey=imagecolorallocate($im,150,150,150); 
for ($i=0;$i<50;$i++) {
  if ($i%2==0)
    imagefilledrectangle($im,0,$i,220,$i,$grey);
  else
    imagefilledrectangle($im,0,$i,220,$i,$white);
}
for ($i=0;$i<100;$i++) {
  if (rand(0,2)==0) $color=$white; else $color=$grey;
  $rand1=rand(0,1000);
  $rand2=rand(0,1000);
  imageline($im,($rand1%220),($rand1%50),($rand2%220),($rand2%50),$color);
}
$font='./font/captcha-font.ttf';
$rotation=rand(-2,2);
imagettftext($im,35,$rotation,19,45,$white,$font,$randomnr);
imagettftext($im,35,$rotation,12,42,$grey,$font,$randomnr);
header("Expires: Wed, 1 Jan 1997 00:00:00 GMT");
header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0",false);
header("Pragma: no-cache");
header ("Content-type: image/gif");
imagegif($im);
imagedestroy($im);

?>