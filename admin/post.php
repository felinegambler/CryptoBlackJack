<?php
/*
 *  © CryptoBlackJack
 *  
 *  
 *  
*/

if (isset($included) && $logged==true) {

  if (!empty($_POST['s_title']) && !empty($_POST['s_url']) && !empty($_POST['s_desc']) && !empty($_POST['cur']) && !empty($_POST['cur_s']) && isset($_POST['min_withdrawal']) && is_numeric((double)$_POST['min_withdrawal']) && isset($_POST['bj_pays']) && is_numeric((int)$_POST['bj_pays']) && isset($_POST['hits_on_soft']) && is_numeric((int)$_POST['hits_on_soft']) && isset($_POST['number_of_decks']) && is_numeric((int)$_POST['number_of_decks']) && isset($_POST['min_confirmations']) && is_numeric((int)$_POST['min_confirmations']) && isset($_POST['min_deposit']) && is_numeric((double)$_POST['min_deposit']) && isset($_POST['txfee']) && is_numeric((double)$_POST['txfee']) && isset($_POST['bankroll_maxbet_ratio']) && is_numeric((double)$_POST['bankroll_maxbet_ratio'])) {
    mysql_query("UPDATE `system` SET `title`='".prot($_POST['s_title'])."',`url`='".prot($_POST['s_url'])."',`currency`='".prot($_POST['cur'])."',`min_withdrawal`=".(double)$_POST['min_withdrawal'].",`min_confirmations`=".(int)$_POST['min_confirmations'].",`min_deposit`=".(double)$_POST['min_deposit'].",`currency_sign`='".prot($_POST['cur_s'])."',`description`='".prot($_POST['s_desc'])."',`bankroll_maxbet_ratio`=".(double)$_POST['bankroll_maxbet_ratio'].",`number_of_decks`=".(int)$_POST['number_of_decks'].",`hits_on_soft`=".(int)$_POST['hits_on_soft'].",`bj_pays`=".(int)$_POST['bj_pays']." WHERE `id`=1 LIMIT 1");  
    walletRequest('settxfee',array(round((double)$_POST['txfee'],8)));
    $warnStatus='<div class="zpravagreen"><b>Success!</b> Data was successfuly saved.</div>';
  }
  else if (isset($_POST['s_title'])) {
    $warnStatus='<div class="zpravared"><b>Error!</b> One of fields is empty.</div>';
  }
  if (isset($_POST['addons_form'])) {
    $giveaway=(isset($_POST['giveaway']))?1:0;
    $chat_enable=(isset($_POST['chat_enable']))?1:0;
    
    mysql_query("UPDATE `system` SET `giveaway`=$giveaway,`giveaway_amount`=".(double)$_POST['giveaway_amount'].",`giveaway_freq`=".(int)$_POST['giveaway_freq'].",`chat_enable`=$chat_enable LIMIT 1");
  }

}
?>