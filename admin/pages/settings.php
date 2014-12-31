<?php
/*
 *  © CryptoBlackJack
 *  
 *  
 *  
*/

if (!isset($included)) exit();

if (!empty($warnStatus)) {
  echo $warnStatus;
}

?>

<h1>Settings</h1>
<br>
<form action="./?p=settings" method="post">
  <table>
    <tr>
      <td style="width: 180px;">Site Title:</td>
      <td style="width: 200px;"><input type="text" name="s_title" value="<?php echo $settings['title']; ?>"></td>
    </tr>
    <tr>
      <td>Site URL:</td>
      <td><input type="text" name="s_url" value="<?php echo $settings['url']; ?>"></td>
      <td><small><i>Without <b>http://</b>.</i></small></td>
    </tr>
    <tr>
      <td>Site Description:</td>
      <td><input type="text" name="s_desc" value="<?php echo $settings['description']; ?>"></td>
    </tr>
    <tr>
      <td>Currency:</td>
      <td><input type="text" name="cur" value="<?php echo $settings['currency']; ?>"></td>
    </tr>
    <tr>
      <td>Currency Sign:</td>
      <td><input type="text" name="cur_s" value="<?php echo $settings['currency_sign']; ?>"></td>
    </tr>
    <tr>
      <td>Minimal deposit:</td>
      <td><input type="text" name="min_deposit" value="<?php echo $settings['min_deposit']; ?>"> <?php echo $settings['currency_sign']; ?></td>
    </tr>
    <tr>
      <td>Required confirmations:</td>
      <td><input type="text" name="min_confirmations" value="<?php echo $settings['min_confirmations']; ?>"></td>
    </tr>
    <tr>
      <td>Minimal withdrawal:</td>
      <td><input type="text" name="min_withdrawal" value="<?php echo $settings['min_withdrawal']; ?>"> <?php echo $settings['currency_sign']; ?></td>
    </tr>
    <tr>
      <td>Transaction fee:</td>
      <td><input type="text" name="txfee" value="<?php $infofee=walletRequest('getinfo'); echo $infofee['paytxfee']; ?>"> <?php echo $settings['currency_sign']; ?></td>
      <td><small><i>Transaction fee to <?php echo $settings['currency']; ?> network.</i></small></td>
    </tr>
    <tr>
      <td>Bankroll/max bet ratio</td>
      <td><input type="text" name="bankroll_maxbet_ratio" value="<?php echo $settings['bankroll_maxbet_ratio']; ?>"></td>
      <td><small><i>The default ratio between amount in wallet and max available bet is set to 25. So for example if you want to allow players to bet 1 <?php echo $settings['currency_sign']; ?>, you have to have 25 <?php echo $settings['currency_sign']; ?> in wallet.</i></small></td>
    </tr>
    <tr><td colspan="3" style="border-top: 1px solid gray;"></td></tr>
    <tr>
      <td>Blackjack pays:</td>
      <td>
        <select name="bj_pays">
          <option value="0"<?php if ($settings['bj_pays']==0) echo ' selected="selected"'; ?>>3 to 2 (lower house edge)
          <option value="1"<?php if ($settings['bj_pays']==1) echo ' selected="selected"'; ?>>6 to 5 (higher house edge)
        </select>
      </td>
      <td><small></small></td>
    </tr>
    <tr>
      <td>Dealer hits on soft 17:</td>
      <td>
        <select name="hits_on_soft">
          <option value="1"<?php if ($settings['hits_on_soft']==1) echo ' selected="selected"'; ?>>yes (higher house edge)
          <option value="0"<?php if ($settings['hits_on_soft']==0) echo ' selected="selected"'; ?>>no (lower house edge)
        </select>
      </td>
      <td><small></small></td>
    </tr>
    <tr>
      <td>Number of decks:</td>
      <td>
        <select name="number_of_decks">
          <option value="1"<?php if ($settings['number_of_decks']==1) echo ' selected="selected"'; ?>>1 (lowest house edge)
          <option value="2"<?php if ($settings['number_of_decks']==2) echo ' selected="selected"'; ?>>2
          <option value="4"<?php if ($settings['number_of_decks']==4) echo ' selected="selected"'; ?>>4
          <option value="6"<?php if ($settings['number_of_decks']==6) echo ' selected="selected"'; ?>>6
          <option value="8"<?php if ($settings['number_of_decks']==8) echo ' selected="selected"'; ?>>8 (highest house edge)
        </select>
      </td>
      <td><small></small></td>
    </tr>
    <tr>
      <td></td>
      <td><input type="submit" value="Save"></td>
    </tr>
  </table>
</form>
