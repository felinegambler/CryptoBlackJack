<?php
/*
 *  © CryptoBlackJack
 *  
 *  
 *  
*/

if (!isset($included)) exit();
?>
<h1>Stats</h1>
<table class="vypis_table">
  <tr class="vypis_table_obsah">
    <td>Number of bets:</td>
    <td><b><?php echo $settings['t_bets']; ?></b></td>
  </tr>
  <tr class="vypis_table_obsah">
    <td>Total wagered:</td>
    <td><b><?php echo sprintf("%.8f",$settings['t_wagered']); ?></b> <?php echo $settings['currency_sign']; ?></td>
  </tr>
  <tr class="vypis_table_obsah">
    <td style="color: green;">Wins:</td>
    <td style="color: green;"><b><?php echo $settings['t_wins']; ?></b></td>
  </tr>
  <tr class="vypis_table_obsah">
    <td style="color: #d10000;">Losses:</td>
    <td style="color: #d10000;"><b><?php echo ($settings['t_bets']-$settings['t_wins']); ?></b></td>
  </tr>
  <tr class="vypis_table_obsah">
    <td style="color: #a06d00;">W/L ratio:</td>
    <td style="color: #a06d00;"><b><?php if (($settings['t_bets']-$settings['t_wins'])>0) echo sprintf("%.3f",$settings['t_wins']/($settings['t_bets']-$settings['t_wins'])); else echo 0; ?></b></td>
  </tr>
</table>
<br><br>
<table class="vypis_table">
  <tr class="vypis_table_head">
    <th>Period</th>
    <th>Real house edge</th>
    <th>Profit</th>
  </tr>
  <tr>
    <td>Last hour</td>
    <td><?php $this_q=mysql_fetch_array(mysql_query("SELECT SUM(-1*((`bet_amount`*`multiplier`)-`bet_amount`)) AS `total_profit`,SUM(`bet_amount`) AS `total_wager` FROM `games` WHERE `time`>NOW()-INTERVAL 1 HOUR AND `ended`=1 AND `winner`!='tie'")); $h_e_['h_e']=($this_q['total_wager']!=0)?(($this_q['total_profit']/$this_q['total_wager'])*100):0; echo ($h_e_['h_e']>=0)?'<span style="color: green;">+'.sprintf("%.5f",$h_e_['h_e']).'%</span>':'<span style="color: #d10000;">'.sprintf("%.5f",$h_e_['h_e']).'%</span>'; ?></td>
    <td><?php echo ($this_q['total_profit']>=0)?'<span style="color: green;">+'.sprintf("%.8f",$this_q['total_profit']).'</span>':'<span style="color: #d10000;">'.sprintf("%.8f",$this_q['total_profit']).'</span>'; ?></td>
  </tr>
  <tr>
    <td>Last 24h</td>
    <td><?php $this_q=mysql_fetch_array(mysql_query("SELECT SUM(-1*((`bet_amount`*`multiplier`)-`bet_amount`)) AS `total_profit`,SUM(`bet_amount`) AS `total_wager` FROM `games` WHERE `time`>NOW()-INTERVAL 24 HOUR AND `ended`=1 AND `winner`!='tie'")); $h_e_['h_e']=($this_q['total_wager']!=0)?(($this_q['total_profit']/$this_q['total_wager'])*100):0; echo ($h_e_['h_e']>=0)?'<span style="color: green;">+'.sprintf("%.5f",$h_e_['h_e']).'%</span>':'<span style="color: #d10000;">'.sprintf("%.5f",$h_e_['h_e']).'%</span>'; ?></td>
    <td><?php echo ($this_q['total_profit']>=0)?'<span style="color: green;">+'.sprintf("%.8f",$this_q['total_profit']).'</span>':'<span style="color: #d10000;">'.sprintf("%.8f",$this_q['total_profit']).'</span>'; ?></td>
  </tr>
  <tr>
    <td>Last 7d</td>
    <td><?php $this_q=mysql_fetch_array(mysql_query("SELECT SUM(-1*((`bet_amount`*`multiplier`)-`bet_amount`)) AS `total_profit`,SUM(`bet_amount`) AS `total_wager` FROM `games` WHERE `time`>NOW()-INTERVAL 7 DAY AND `ended`=1 AND `winner`!='tie'")); $h_e_['h_e']=($this_q['total_wager']!=0)?(($this_q['total_profit']/$this_q['total_wager'])*100):0; echo ($h_e_['h_e']>=0)?'<span style="color: green;">+'.sprintf("%.5f",$h_e_['h_e']).'%</span>':'<span style="color: #d10000;">'.sprintf("%.5f",$h_e_['h_e']).'%</span>'; ?></td>
    <td><?php echo ($this_q['total_profit']>=0)?'<span style="color: green;">+'.sprintf("%.8f",$this_q['total_profit']).'</span>':'<span style="color: #d10000;">'.sprintf("%.8f",$this_q['total_profit']).'</span>'; ?></td>
  </tr>
  <tr>
    <td>Last 30d</td>
    <td><?php $this_q=mysql_fetch_array(mysql_query("SELECT SUM(-1*((`bet_amount`*`multiplier`)-`bet_amount`)) AS `total_profit`,SUM(`bet_amount`) AS `total_wager` FROM `games` WHERE `time`>NOW()-INTERVAL 30 DAY AND `ended`=1 AND `winner`!='tie'")); $h_e_['h_e']=($this_q['total_wager']!=0)?(($this_q['total_profit']/$this_q['total_wager'])*100):0; echo ($h_e_['h_e']>=0)?'<span style="color: green;">+'.sprintf("%.5f",$h_e_['h_e']).'%</span>':'<span style="color: #d10000;">'.sprintf("%.5f",$h_e_['h_e']).'%</span>'; ?></td>
    <td><?php echo ($this_q['total_profit']>=0)?'<span style="color: green;">+'.sprintf("%.8f",$this_q['total_profit']).'</span>':'<span style="color: #d10000;">'.sprintf("%.8f",$this_q['total_profit']).'</span>'; ?></td>
  </tr>
  <tr>
    <td>Last 6m</td>
    <td><?php $this_q=mysql_fetch_array(mysql_query("SELECT SUM(-1*((`bet_amount`*`multiplier`)-`bet_amount`)) AS `total_profit`,SUM(`bet_amount`) AS `total_wager` FROM `games` WHERE `time`>NOW()-INTERVAL 6 MONTH AND `ended`=1 AND `winner`!='tie'")); $h_e_['h_e']=($this_q['total_wager']!=0)?(($this_q['total_profit']/$this_q['total_wager'])*100):0; echo ($h_e_['h_e']>=0)?'<span style="color: green;">+'.sprintf("%.5f",$h_e_['h_e']).'%</span>':'<span style="color: #d10000;">'.sprintf("%.5f",$h_e_['h_e']).'%</span>'; ?></td>
    <td><?php echo ($this_q['total_profit']>=0)?'<span style="color: green;">+'.sprintf("%.8f",$this_q['total_profit']).'</span>':'<span style="color: #d10000;">'.sprintf("%.8f",$this_q['total_profit']).'</span>'; ?></td>
  </tr>
  <tr>
    <td>Last 12m</td>
    <td><?php $this_q=mysql_fetch_array(mysql_query("SELECT SUM(-1*((`bet_amount`*`multiplier`)-`bet_amount`)) AS `total_profit`,SUM(`bet_amount`) AS `total_wager` FROM `games` WHERE `time`>NOW()-INTERVAL 12 MONTH AND `ended`=1 AND `winner`!='tie'")); $h_e_['h_e']=($this_q['total_wager']!=0)?(($this_q['total_profit']/$this_q['total_wager'])*100):0; echo ($h_e_['h_e']>=0)?'<span style="color: green;">+'.sprintf("%.5f",$h_e_['h_e']).'%</span>':'<span style="color: #d10000;">'.sprintf("%.5f",$h_e_['h_e']).'%</span>'; ?></td>
    <td><?php echo ($this_q['total_profit']>=0)?'<span style="color: green;">+'.sprintf("%.8f",$this_q['total_profit']).'</span>':'<span style="color: #d10000;">'.sprintf("%.8f",$this_q['total_profit']).'</span>'; ?></td>
  </tr>
  <tr>
    <td>Since start</td>
    <td><?php $this_q=mysql_fetch_array(mysql_query("SELECT SUM(-1*((`bet_amount`*`multiplier`)-`bet_amount`)) AS `total_profit`,SUM(`bet_amount`) AS `total_wager` FROM `games` WHERE `ended`=1 AND `winner`!='tie'")); $h_e_['h_e']=($this_q['total_wager']!=0)?(($this_q['total_profit']/$this_q['total_wager'])*100):0; echo ($h_e_['h_e']>=0)?'<span style="color: green;">+'.sprintf("%.5f",$h_e_['h_e']).'%</span>':'<span style="color: #d10000;">'.sprintf("%.5f",$h_e_['h_e']).'%</span>'; ?></td>
    <td><?php echo ($this_q['total_profit']>=0)?'<span style="color: green;">+'.sprintf("%.8f",$this_q['total_profit']).'</span>':'<span style="color: #d10000;">'.sprintf("%.8f",$this_q['total_profit']).'</span>'; ?></td>
  </tr>
</table>