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

$settings=mysql_fetch_array(mysql_query("SELECT * FROM `system` WHERE `id`=1 LIMIT 1"));


$return='<style>table.stats-tbl > tbody > tr > td {text-align:center;vertical-align:middle;font-size: 10px;width:30%;padding:15px 0;} table.stats-tbl > tbody > tr > td.stats-val {font-weight:bold;font-size:15px;width:35%;}</style>';

$return.='<table class="table table-bordered stats-tbl" style="color: #AAA5A5;">';

$return.='<tr>';
$return.='<td class="stats-val" style="font-size: 13px; color: #343434;font-weight:normal;">Your Stats</td>';
$return.='<td style="font-size: 10px;"><span class="glyphicon glyphicon-stats"></span></td>';
$return.='<td class="stats-val" style="font-size: 13px; color: #343434;font-weight:normal;">Global Stats</td>';
$return.='</tr>';


$t_wager=mysql_fetch_array(mysql_query("SELECT SUM(`t_wagered`) AS `t` FROM `players`"));

$wins['global']=mysql_num_rows(mysql_query("SELECT `id` FROM `games` WHERE `ended`=1 AND `winner`='player'"));
$wins['player']=mysql_num_rows(mysql_query("SELECT `id` FROM `games` WHERE `ended`=1 AND `winner`='player' AND `player`=$player[id]"));
$ties['global']=mysql_num_rows(mysql_query("SELECT `id` FROM `games` WHERE `ended`=1 AND `winner`='tie'"));
$ties['player']=mysql_num_rows(mysql_query("SELECT `id` FROM `games` WHERE `ended`=1 AND `winner`='tie' AND `player`=$player[id]"));
$losses['global']=mysql_num_rows(mysql_query("SELECT `id` FROM `games` WHERE `ended`=1 AND `winner`='dealer'"));
$losses['player']=mysql_num_rows(mysql_query("SELECT `id` FROM `games` WHERE `ended`=1 AND `winner`='dealer' AND `player`=$player[id]"));

$return.='<tr>';
$return.='<td class="stats-val">'.mysql_num_rows(mysql_query("SELECT `id` FROM `games` WHERE `ended`=1 AND `player`=$player[id]")).'</td>';
$return.='<td>NUMBER OF BETS</td>';
$return.='<td class="stats-val">'.mysql_num_rows(mysql_query("SELECT `id` FROM `games` WHERE `ended`=1")).'</td>';
$return.='</tr>';
$return.='<tr>';
$return.='<td class="stats-val">'.n_num($player['t_wagered']).'</td>';
$return.='<td>TOTAL WAGERED</td>';
$return.='<td class="stats-val">'.n_num($t_wager['t']).'</td>';
$return.='</tr>';
$return.='<tr>';
$return.='<td class="stats-val" style="color: #78D16B;">'.$wins['player'].'</td>';
$return.='<td style="color: #78D16B;">WINS</td>';
$return.='<td class="stats-val" style="color: #78D16B;">'.$wins['global'].'</td>';
$return.='</tr>';
$return.='<tr>';
$return.='<td class="stats-val">'.$ties['player'].'</td>';
$return.='<td>TIES</td>';
$return.='<td class="stats-val">'.$ties['global'].'</td>';
$return.='</tr>';
$return.='<tr>';
$return.='<td class="stats-val" style="color: #EE7171;">'.$losses['player'].'</td>';
$return.='<td style="color: #EE7171;">LOSSES</td>';
$return.='<td class="stats-val" style="color: #EE7171;">'.$losses['global'].'</td>';
$return.='</tr>';
$return.='<tr>';
$return.='<td class="stats-val" style="color: #FF9C00;">'.sprintf("%.3f",$wins['player']/$losses['player']).'</td>';
$return.='<td style="color: #FF9C00;">W/L RATIO</td>';
$return.='<td class="stats-val" style="color: #FF9C00;">'.sprintf("%.3f",@$wins['global']/$losses['global']).'</td>';
$return.='</tr>';

$return.='</table>';

echo json_encode(array('content'=>$return));
?>
