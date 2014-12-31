<?php
/*
 *  © CryptoBlackJack
 *  
 *  
 *  
*/


header('X-Frame-Options: DENY'); 

$init=true;
include __DIR__.'/inc/start.php';
?>
<!DOCTYPE html>
<html>
  <head>
    <title><?php echo $settings['title'].' - '.$settings['description']; ?></title>
    <meta charset="utf-8">
    <link type="text/css" rel="stylesheet" href="./styles/bootstrap-coinjack-edit.css">
    <link type="text/css" rel="stylesheet" href="./styles/mcs.css">
    <link type="text/css" rel="stylesheet" href="./styles/main.css">
    <link type="text/css" rel="stylesheet" href="./styles/cards.css">
    <link rel="icon" href="./styles/imgs/favicon.ico" type="image/x-icon">
    <script type="text/javascript" src="./scripts/jquery.js"></script>
    <script type="text/javascript" src="./scripts/bootstrap.js"></script>
    <script type="text/javascript" src="./scripts/qrlib.js"></script>
    <script type="text/javascript" src="./scripts/mcs.min.js"></script>
    <script type="text/javascript" src="./scripts/sha256.js"></script>
    <script type="text/javascript" src="./scripts/main.js"></script>
    <script type="text/javascript">
      function unique() {
        return '<?php echo $unique; ?>';
      }
      function cursig() {
        return '<?php echo $settings['currency_sign']; ?>';
      }
      function giveaway_freq() {
        return '<?php echo $settings['giveaway_freq']; ?>';
      }
      $(document).ready(function(){
        <?php if ($playingGame) echo 'playingOnInit();'; ?>
      });
    </script>
  </head>
  <body>
    <div class="all c0">
      <div style="padding: 0 20px;">
        <a href="./" class="logo"><span class="text"><?php echo $settings['title']; ?></span></a>
        <div class="c0-cashier">
          <div class="cashier_block">
            <small><small><b>BALANCE</b> <a href="#" class="refresher_b" data-toggle="tooltip" data-placement="bottom" title="Refresh balance" onclick="balRefresh();return false;"><span class="glyphicon glyphicon-refresh"></span></a></small></small><br>
            <b><span class="balances"><?php echo n_num($player['balance'],true); ?></span></b> <?php echo $settings['currency_sign']; ?>
          </div>
          <div class="cashier_block" style="margin-left: 12px;">
            <a href="#" class="btn btn-sm btn-main" style="position:relative;top:5px;" onclick="javascript:deposit();return false;">Deposit</a><!--
         --><a href="#" class="btn btn-sm withdrawBtn" style="position:relative;top:5px;" onclick="javascript:withdraw();return false;">Withdraw</a>
          </div>
        </div>
      </div>
    </div>
    <div style="height: 60px;"></div>
    <div class="all c1">

      <div class="cj-table">
        <div class="gamblingTable">
          <div class="leftBox">
            <div class="chatBox">
              <div class="chat-messages"></div>
              <div class="chat-input">
                <input class="chat-input-elem" maxlength="200" title="Press ENTER to send" data-toggle="tooltip" data-placement="bottom" type="text" placeholder="Enter message">
              </div>              
            </div>
            <div class="leftMenu">
              <div class="leftMenu-middle">
                <a href="#" onclick="javscript:account();return false;" data-toggle="tooltip" data-placement="right" title="Your&nbsp;Account"><span class="glyphicon glyphicon-user"></span></a>
                <a href="#" onclick="javscript:fair();return false;" data-toggle="tooltip" data-placement="right" title="Provably&nbsp;Fair"><span class="glyphicon glyphicon-ok"></span></a>
                <a href="#" onclick="javscript:stats();return false;" data-toggle="tooltip" data-placement="right" title="Statistics"><span class="glyphicon glyphicon-stats"></span></a>
                <a href="#" onclick="javscript:news();return false;" data-toggle="tooltip" data-placement="right" title="News"><span class="glyphicon glyphicon-flag"></span></a>
                <?php if ($settings['chat_enable']==1) { ?>
                  <a href="#" onclick="javscript:toggle_chat();return false;" class="chatButton" data-toggle="tooltip" data-placement="right" title="Chat"><span class="glyphicon glyphicon-comment"></span></a>
                <?php } if ($settings['giveaway']==1) { ?>
                  <a href="#" onclick="javscript:giveaway();return false;" data-toggle="tooltip" data-placement="right" title="Giveaway"><span class="glyphicon glyphicon-gift"></span></a>
                <?php } ?>
              </div>
            </div>
          </div>
          <div class="cj-rivalTables">
            <div class="cj-dealerTable"></div>
            <div class="cj-playerTable"></div>
          </div>
          <div class="bjinfo">
            <div class="bjinfo-image"><img src="./styles/imgs/26.png"></div>
            <div class="bjinfo-text">BLACKJACK PAYS <?php if ($settings['bj_pays']==0) $bj_pays='3 TO 2'; else $bj_pays='6 TO 5'; echo $bj_pays; ?></div>
          </div>
        </div>
      </div>

    </div>
    <div class="all cj-control-all">
      <div class="cj-table-control">
        <div class="cj-leftMargin"></div>
        <div class="cj-table-actions">
          <div class="g_controls">
            <a href="#" class="btn btn-main gameControllers gC-4 btn-disabled" onclick="javascript:gameAction('split');return false;" disabled>SPLIT</a>
            <a href="#" class="btn btn-main gameControllers gC-3 btn-disabled" onclick="javascript:gameAction('double');return false;" disabled>DOUBLE</a>
            <a href="#" class="btn btn-main gameControllers gC-2 btn-disabled" onclick="javascript:gameAction('stand');return false;" disabled>STAND</a>
            <a href="#" class="btn btn-main gameControllers gC-1 btn-disabled" onclick="javascript:gameAction('hit');return false;" disabled>HIT</a>
          </div>
          <div class="betRegulators">
            <a href="#" class="btn btn-main gameControllers gC-5" onclick="javascript:br_multip();return false;"><small><small><b>x2</b></small></small></a>
            <a href="#" class="btn btn-main gameControllers gC-6" onclick="javascript:br_div();return false;" style="border-top: none;height:26px;"><small><small><b>/2</b></small></small></a>
          </div><!--
      ---><div class="betAmount">
            <input type="text" onchange="javascript:_betChanged();" class="betInput" value="0.00000000">
            <div class="betUpdown">
              <a href="#" onclick="javascript:_betValUp();return false;"><span class="glyphicon glyphicon-chevron-up"></span></a>
              <a class="valdown" href="#" onclick="javascript:_betValDown();return false;"><span class="glyphicon glyphicon-chevron-down"></span></a>
            </div>
          </div><!--
      ---><a href="#" class="btn btn-main betButton gameControllers gC-7" onclick="javascript:bet();return false;">BET</a>
        </div>
      </div>
    </div>
    <!-- <MODALS> -->
      <div class="modal fade" id="modals-deposit" aria-labelledby="mlabels-deposit" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
              <h4 class="modal-title" id="mlabels-deposit">Deposit Funds</h4>
            </div>
            <div class="modal-body" style="text-align: center;">
              Please send minimal <b><?php echo n_num($settings['min_deposit']); ?></b> <?php echo $settings['currency_sign']; ?> to this address:
              <div class="addr-p" style="margin:15px;font-weight:bold;font-size:18px;"></div>
              <div class="addr-qr"></div>
              <div style="margin:15px;">
                <a href="#" class="gray_a" onclick="javascript:_genNewAddress();return false;">New Address</a> <span class="color: lightgray">·</span> <a href="#" class="gray_a pendingbutton" cj-opened="no" onclick="javascript:clickPending();return false;">Show Pending</a>
              </div>
              <div class="pendingDeposits" style="display:none;"></div>
            </div>
          </div>
        </div>
      </div>
      <div class="modal fade" id="modals-withdraw" aria-labelledby="mlabels-withdraw" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
              <h4 class="modal-title" id="mlabels-withdraw">Withdraw Funds</h4>
            </div>
            <div class="modal-body">
              <div class="m_alert"></div>
              <div class="form-group">
                <label for="input-address">Enter valid <?php echo $settings['currency_sign']; ?> address:</label>
                <input type="text" class="form-control" id="input-address">
              </div>
              <div class="form-group">
                <label for="input-am">Enter amount to be paid-out:</label>
                <input type="text" class="form-control" id="input-am" style="width:150px;text-align:center;">
                <small>
                  Balance: <span class="balances" style="font-weight: bold;"><?php echo n_num($player['balance'],true); ?></span> <?php echo $settings['currency_sign']; ?>
                </small>
              </div>
              <a href="#" class="btn btn-sm btn-main" onclick="javascript:_withdraw();return false;">Withdraw</a>
              <button class="btn btn-sm btn-default" data-dismiss="modal">Cancel</button>
            </div>
          </div>
        </div>
      </div>
      <div class="modal fade" id="modals-fair" aria-labelledby="mlabels-fair" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
              <h4 class="modal-title" id="mlabels-fair">Provably Fair</h4>
            </div>
            <div class="modal-body">
              <fieldset>
                <legend>Next Bet</legend>
                <div class="form-group">
                  <label for="input-initial_array_hash">Initial Array (hash)</label>
                  <input type="text" class="form-control" id="input-initial_array_hash" disabled>
                </div>
                <div class="form-group">
                  <label for="input-client_seed">Client seed (number)</label>
                  <div class="cover">
                    <div class="input-group" style="width: 40%;float:left;">
                      <input type="text" class="form-control" maxlength="12" id="input-client_seed">
                      <span class="input-group-btn">
                        <a class="btn btn-main" href="#" onclick="javascript:saveClientSeed();return false;">Save</a>
                      </span>
                    </div>
                    <div class="modalReaction" id="clientSeedSave-reaction"></div>
                  </div>
                </div>
              </fieldset>

              <fieldset>
                <legend>Last Bet</legend>
                <div class="lastBetDiv">
                  <div class="form-group">
                    <label for="input-initial_array_hash">Initial Array (hash)</label>
                    <input type="text" class="form-control" id="input-initial_array_hash__last" disabled>
                  </div>
                  <div class="form-group">
                    <label for="input-initial_array">Initial Array</label>
                    <input type="text" class="form-control" id="input-initial_array__last" disabled>
                  </div>
                  <div class="form-group">
                    <label for="input-client_seed">Client seed</label>
                    <input type="text" class="form-control" id="input-client_seed__last" disabled>
                  </div>
                </div>
              </fieldset>  

            </div>
          </div>
        </div>
      </div>
      <div class="modal fade" id="modals-stats" aria-labelledby="mlabels-stats" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
              <h4 class="modal-title" id="mlabels-stats">Statistics</h4>
            </div>
            <div class="modal-body">
              <div class="statsCon"></div>
            </div>
          </div>
        </div>
      </div>
      <div class="modal fade" id="modals-news" aria-labelledby="mlabels-news" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
              <h4 class="modal-title" id="mlabels-news">News</h4>
            </div>
            <div class="modal-body">
              <?php
              $news_q=mysql_query("SELECT * FROM `news` ORDER BY `time` DESC");
              if (mysql_num_rows($news_q)==0) echo '<i>No news available</i>';
              while ($new=mysql_fetch_array($news_q)) {
                echo '<div class="well" style="overflow: hidden;">';
                echo '<div style="width:75%;float:left;text-align:justify;">'.$new['content'].'</div>';
                echo '<div style="width:20%;float:right;"><small><i>'.date('Y-m-d',strtotime($new['time'])).'</i></small></div>';
                echo '</div>';
              }  
              ?>                                  
            </div>
          </div>
        </div>
      </div>
      <div class="modal fade" id="modals-giveaway" aria-labelledby="mlabels-giveaway" aria-hidden="true">
        <div class="modal-dialog modal-giveaway">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
              <h4 class="modal-title" id="mlabels-giveaway">Giveaway</h4>
            </div>
            <div class="modal-body">
              <div class="m_alert_giveaway"></div>
              <div class="form-group">
                <label>Giveaway Amount</label><br>
                <?php echo '<b>'.n_num($settings['giveaway_amount']).'</b> '.$settings['currency_sign']; ?>
              </div>
              <div class="form-group">
                <label>Enter text from image</label><br>
                <input type="text" class="form-control captchaInput" maxlength="7" id="input-captcha">
              </div>
              <a href="#" onclick="javascript:claim_bonus();return false;" class="btn btn-main btn-lg claimButton">CLAIM</a>
              <a class="captchadiv" href="#" onclick="javascript:$(this).children().remove().clone().appendTo($(this));return false;" data-toggle="tooltip" data-placement="bottom" title="Click to refresh"><img src="./content/captcha/genImage.php"></a>
            </div>
          </div>
        </div>
      </div>
      <div class="modal fade" id="modals-account" aria-labelledby="mlabels-account" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
              <h4 class="modal-title" id="mlabels-account">Your Account</h4>
            </div>
            <div class="modal-body">
              <div class="m_alert_account"></div>

              <div class="form-group">
                <label for="input-alias">Player Alias</label>
                <div class="cover">
                  <div class="input-group" style="width: 40%;float:left;">
                    <input type="text" class="form-control" maxlength="25" id="input-alias" value="<?php echo $player['alias']; ?>">
                    <span class="input-group-btn">
                      <a class="btn btn-main" href="#" onclick="javascript:saveAlias();return false;">Save</a>
                    </span>
                  </div>
                  <div class="modalReaction" id="aliasSave-reaction"></div>
                </div>
              </div>
              <div class="form-group">
                <label for="input-pass">Password - <b><span class="pass-en_dis"><?php if ($player['password']=='') echo 'Disabled'; else echo 'Enabled'; ?></span></b></label>
                <div class="cover">
                  <div class="input-group" style="width: 40%;float:left;">
                    <input type="password" class="form-control" id="input-pass">
                    <span class="input-group-btn">
                      <a class="btn btn-main passAction-btn" href="#" onclick="javascript:<?php if ($player['password']=='') echo 'enablePass'; else echo 'disablePass'; ?>();return false;"><?php if ($player['password']=='') echo 'Enable'; else echo 'Disable'; ?></a>
                    </span>
                  </div>
                  <div class="modalReaction" id="passwordM-reaction"></div>
                </div>
              </div>

              <div class="form-group">
                <label for="input-unique">Unique URL</label>
                <input type="text" class="form-control" id="input-unique" style="cursor:pointer;cursor:hand;" onclick="$(this).select();" value="<?php echo $settings['url'].'/?unique='.$player['hash']; ?>">
              </div>

            </div>
          </div>
        </div>
      </div>
    <!-- </MODALS> -->
  </body>
</html>
<?php include __DIR__.'/inc/end.php'; ?>