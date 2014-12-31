$(document).ready(function (){
  __prepare();
});
function __prepare() {
  window.location.href='./?unique='+unique()+'# Do Not Share This URL!';
  
  decks = {};
  
  placeTables();
  $(window).resize(function(){
    placeTables();
  });
  $('.leftBox').resize(function(){
    placeTables();
  });
  setInterval(function(){
    placeTables();
  },100)
  $('.refresher_b').tooltip();
  setInterval(function(){
    balRefresh();
  },400);
  placeStatic();
  decks = {};
  
  $('.leftMenu a').each(function(){
    $(this).tooltip();
  });
  $('.captchadiv').tooltip();
  
  scrolledBottom = true;
  
  $('.chat-messages').mCustomScrollbar({
    theme: 'dark',
    scrollInertia: 0,
    alwaysShowScrollbar: 1,
    mouseWheel: {
      enable: true,
      scrollAmount: 30
    },
    setWidth: '100%',
    advanced: {
      updateOnContentResize: true
    },
    callbacks: {
      onTotalScroll: function() {
        scrolledBottom = true;
      },
      onScrollStart: function() {
        scrolledBottom = false;
      }
    }
  });
  //$('.chat-messages').mCustomScrollbar('scrollTo','bottom');

  
  $('.chat-input-elem').keypress(function(e){
    if (e.which == 13) chatSend();
  });
  $('.chat-input-elem').tooltip();

  chatReceiveUpdates = false;
  chatUpdating = false;
  
  setInterval(function(){
    if (chatReceiveUpdates) chatUpdate();
  },500);
  
  $('.modal').each(function(){
    $(this).on('hide.bs.modal',function(){
      $('.modalReaction').each(function(){
        $(this).empty();
      });
    });
  });
  setInterval(function(){
    $.ajax({'url':'./content/ajax/refreshSession.php'});
  },10000);
  imitateCRON();
  setInterval(function(){
    imitateCRON();
  },1000);

  buttons = {    
    disableAll: function() {
      $('.gameControllers').attr('disabled',true);
      $('.gameControllers').addClass('btn-disabled');
    },
    enableAll: function() {
      $('.gameControllers').removeAttr('disabled');
      $('.gameControllers').removeClass('btn-disabled');
    },
    bet: new _button($('.gameControllers.gC-5,.gameControllers.gC-6,.gameControllers.gC-7')),
    hit: new _button($('.gameControllers.gC-1')),
    stand: new _button($('.gameControllers.gC-2')),
    double: new _button($('.gameControllers.gC-3')),
    split: new _button($('.gameControllers.gC-4'))
  }    

}

function placeTables() {
  gamblingTableHeight=$('.gamblingTable').height();
  // table height
  cj_height=Math.max(gamblingTableHeight,($(window).height()-140));
  $('.cj-table').height(cj_height);
  // real table top margin
  gamblingTop=(margin(gamblingTableHeight,cj_height));
  $('.gamblingTable').css('top',gamblingTop);
  // bjinfo margin-top
  bjinfoTop=(margin($('.bjinfo').height(),gamblingTableHeight));
  $('.bjinfo').css('top',bjinfoTop);
  $('.bjinfo').width(($('.gamblingTable').width()-$('.leftBox').width()));
  $('.bjinfo').css('left',$('.leftBox').width());
  // rivalTables
  $('.cj-rivalTables').css('margin-left',margin($('.cj-rivalTables').width(),$('.gamblingTable').width()-$('.leftBox').width())+$('.leftBox').width());
  // cj-control
  $('.cj-leftMargin').width($('.leftBox').width());
  
  $.each(decks,function(objName,obj){
    if (objName!='splitted') obj.sumPosition();
  });
}
function placeStatic() {
  $('.leftMenu-middle').css('margin-top',margin($('.leftMenu-middle').height(),$('.leftMenu').height()));
}

function margin(elem,wrap) {
  return ((wrap-elem)/2);
}

function imitateCRON() {
  $.ajax({'url': './content/ajax/getDeposits.php'});
}


function br_div() {
  var repaired=(parseFloat($(".betInput").val())/2).toFixed(8);
  if (isNaN(repaired)==true || parseFloat(repaired)<0) $(".betInput").val('0.00000000');
  else $(".betInput").val(repaired);
}
function br_multip() {
  var repaired=(parseFloat($(".betInput").val())*2).toFixed(8);
  if (isNaN(repaired)==true || parseFloat(repaired)<0) $(".betInput").val('0.00000000');
  else $(".betInput").val(repaired);
}

function _betValUp() {
  var repaired=(parseFloat($(".betInput").val())+1).toFixed(8);
  if (isNaN(repaired)==true || parseFloat(repaired)<0) $(".betInput").val('0.00000000');
  else $(".betInput").val(repaired);
}
function _betValDown() {
  var repaired=(parseFloat($(".betInput").val())-1).toFixed(8);
  if (isNaN(repaired)==true || parseFloat(repaired)<0) $(".betInput").val('0.00000000');
  else $(".betInput").val(repaired);
}
function _betChanged() {
  var repaired=parseFloat($(".betInput").val()).toFixed(8);
  if (isNaN(repaired)==true || parseFloat(repaired)<0) $(".betInput").val('0.00000000');
  else $(".betInput").val(repaired);
}

function bet_error(con) {
  alert(con);
}

function balRefresh() {
  $.ajax({
    'url': './content/ajax/getBalance.php?_unique='+unique(),
    'dataType': "json",
    'success': function (data) {
      $('.balances').each(function(){
        $(this).html(data['balance']);
      });    
    }
  });
}


function chatSend() {
  var dataToSend = $('.chat-input-elem').val();
  $.ajax({
    'url': './content/ajax/chatSend.php?_unique='+unique()+'&data='+dataToSend,
    'dataType': "json",
    'success': function(data) {
      if (data['error']=='yes' && data['content']=='max_in_row') alert('You can\'t post more than 10 messages in a row.');
      else {
        chatUpdate();
        $('.chat-input-elem').val('');
      }
    }
  });
}
function chatUpdate() {
  var lastID = 0;
  if ($('.chat-message').length)
    lastID = $('.chat-message').last().attr('data-messid');
  
  if (chatUpdating) return;

  chatUpdating=true;  

  $.ajax({
    'url': './content/ajax/chatUpdate.php?_unique='+unique()+'&lastId='+lastID,
    'dataType': "json",
    'success': function(data) {
      var _scrolledBottom = scrolledBottom;
      
      var $messages = $(data['content']);
      
      var $existingMessages = $('.chat-messages .mCSB_container');

      $messages.each(function(){
        var $message = $(this).remove();
        var messid = $message.attr('data-messid');
        
        
        if ($existingMessages.children('[data-messid="'+messid+'"]').length) {
          // $message.remove()
        }
      });
      
      $existingMessages.append($messages);
      if (_scrolledBottom)
        $('.chat-messages').mCustomScrollbar('scrollTo','last',{scrollInertia:100});
      
      chatUpdating=false;
    }
  });
}

function stats() {
  $('#modals-stats').modal('show');
  $('.statsCon').html('Loading...');
  $.ajax({
    'url': './content/ajax/getStats.php?_unique='+unique(),
    'dataType': "json",
    'success': function(data) {
      $('.statsCon').html(data['content']);
    }
  });  
}
function news() {
  $('#modals-news').modal('show');
}
function account() {
  $('#modals-account').modal('show');
}

function saveAlias() {
  $.ajax({
    'url': './content/ajax/saveAlias.php?_unique='+unique()+'&alias='+$('#input-alias').val(),
    'dataType': "json",
    'success': function(data) {
      $('#aliasSave-reaction').css('color',data['color']).html(data['content']);
      if (data['repaired']!=null) $('#input-alias').val(data['repaired']);      
    }
  });
}
function enablePass() {
  var pass = CryptoJS.SHA256($('#input-pass').val());
  $.ajax({
    'url': './content/ajax/enablePassword.php?_unique='+unique()+'&pass='+pass,
    'dataType': "json",
    'success': function(data) {
      $('#passwordM-reaction').css('color',data['color']).html(data['content']);
      if (data['color']=='green') location.reload();
    }
  });
}
function disablePass() {
  var pass = CryptoJS.SHA256($('#input-pass').val());
  $.ajax({
    'url': './content/ajax/disablePassword.php?_unique='+unique()+'&pass='+pass,
    'dataType': "json",
    'success': function(data) {
      $('#passwordM-reaction').css('color',data['color']).html(data['content']);
      if (data['color']=='green') {
        $('.pass-en_dis').html('Disabled');
        $('.passAction-btn').attr('onclick',"javascript:enablePass();return false;");
        $('.passAction-btn').html('Enable');
        $('#input-pass').val('');
      }
    }
  });
}

function giveaway() {
  $('#modals-giveaway').modal('show');

}

function fair() {
  $('#modals-fair').modal('show');
  $.ajax({
    'url': './content/ajax/getFair.php?_unique='+unique(),
    'dataType': "json",
    'success': function (data) {
      $('#input-initial_array_hash').val(data['next']['initial_array_hash']);
      $('#input-client_seed').val(data['next']['client_seed']);
      if (data['last']['initial_array']==null) $('.lastBetDiv').html('<i>No bets yet</i>');
      else {
        $('#input-initial_array_hash__last').val(data['last']['initial_array_hash']);
        $('#input-initial_array__last').val(data['last']['initial_array']);
        $('#input-client_seed__last').val(data['last']['client_seed']);
      }
    }
  });
}

function saveClientSeed() {
  $.ajax({
    'url': './content/ajax/saveClientSeed.php?_unique='+unique()+'&seed='+$('#input-client_seed').val(),
    'dataType': "json",
    'success': function (data) {
      $('#clientSeedSave-reaction').css('color',data['color']).html(data['content']);
      if (data['repaired']!=null) $('#input-client_seed').val(data['repaired']);
    }    
  });
}

function withdraw() {
  $('.m_alert').hide();
  $('#modals-withdraw').modal('show');
}
function _withdraw() {
  $.ajax({
    'url': './content/ajax/_withdraw.php?_unique='+unique()+'&valid_addr='+$('#input-address').val()+'&amount='+$('#input-am').val(),
    'dataType': "json",
    'success': function (data) {
      if (data['error']=='yes') {
        $('.m_alert').hide();
        $('.m_alert').html('<div class="alert alert-dismissable alert-warning"><button type="button" class="close" data-dismiss="alert">×</button><b>Error!</b> '+data['content']+'</div>');
        $('.m_alert').slideDown();
      }
      else {
        $('.m_alert').hide();
        $('.m_alert').html('<div class="alert alert-dismissable alert-success"><button type="button" class="close" data-dismiss="alert">×</button><b>Processed.</b><br>TXID: <small>'+data['content']+'</small></div>');
        $('.m_alert').slideDown();
        balRefresh();        
      }
    }
  });
}

function _genNewAddress() {
  $.ajax({
    'url': './content/ajax/getAddress.php?_unique='+unique(),
    'dataType': "json",
    'success': function (data) {
      $('.addr-p').html(data['confirmed']);
      $('.addr-qr').empty();
      $('.addr-qr').qrcode(data['confirmed']);
    }
  });
}
function deposit() {
  $('#modals-deposit').modal('show');
  _genNewAddress();  
}
function clickPending() {
  if ($('.pendingbutton').attr('cj-opened')=='yes') hidePending();
  else showPending();
}                    
function showPending() {
  $.ajax({
    'url': './content/ajax/getPending.php?_unique='+unique(),
    'dataType': "json",
    'success': function (data) {
      $('.pendingDeposits').html(data['content']);
      $('.pendingDeposits').slideDown();
      $('.pendingbutton').html('Hide Pending');
      $('.pendingbutton').attr('cj-opened','yes')
    }
  });
}
function hidePending() {
  $('.pendingDeposits').slideUp();
  $('.pendingbutton').html('Show Pending');
  $('.pendingbutton').attr('cj-opened','no');
}
function claim_bonus() {
  $('.m_alert_giveaway').hide();
  var sol=$('#input-captcha').val();
  $('#input-captcha').val('');
  $.ajax({
    'url': './content/ajax/getBonus.php?_unique='+unique()+'&sol='+sol,
    'dataType': "json",
    'success': function(data) {
      if (data['error']=='yes') {
        var m_alert = "";
        if (data['content']=='balance') m_alert='Your balance must be 0 to proceed.';
        else if (data['content']=='captcha') m_alert='Incorrect captcha solution!';
        else if (data['content']=='time') m_alert='You must wait '+giveaway_freq()+' seconds.';
        $('.m_alert_giveaway').hide();
        $('.m_alert_giveaway').html('<div class="alert alert-dismissable alert-warning"><button type="button" class="close" data-dismiss="alert">×</button>'+m_alert+'</div>');
        $('.m_alert_giveaway').slideDown();
      }
      else {
        $('#modals-giveaway').modal('hide');
        balRefresh();
      }
      $('.captchadiv img').remove().clone().appendTo($('.captchadiv'));
    }
  });
}

function toggle_chat() {
  if (!$('.chatBox').is(':visible')) openChat();
  else closeChat();
}
function openChat() {
  $('.chatButton').addClass('lm-olwaysOn');
  var $box=$('.chatBox');
  var chatboxwidth=parseInt($box.css('width'));
  chatReceiveUpdates = true;
  $box.css({
    'margin-left': (chatboxwidth*-1)
  }).show().animate({
    'margin-left': 0
  },
  {
    step: function() {
      placeTables();
    },
    easing: 'linear',
    duration: 250
  });
}

function closeChat() {
  $('.chatButton').removeClass('lm-olwaysOn');
  var $box=$('.chatBox');
  var chatboxwidth=parseInt($box.css('width'));
  chatReceiveUpdates = false;
  $box.animate({
    'margin-left': (chatboxwidth*-1)
  },
  {
    step: function() {
      placeTables();
    },
    easing: 'linear',
    duration: 250,
    done: function() {
      $box.hide().css('margin-left',0)
    }
  });
}

function gameAction(action) {
  buttons.disableAll();
  
  $.ajax({
    'url': './content/ajax/gameAction.php?_unique='+unique()+'&action='+action,
    'dataType': "json",
    'success': function (data) {
      if (data['error']=='balance') {
        alert('You have insufficient funds. Please deposit.');
        buttonsAccess(data['accessable']);
        return;
      }
      if (data['split']=='true') splitPlayerDecks(function(){
        gameUpdate(data['data']);
        if (data['data']['mark']==1) decks.player.setMark();
        if (data['data']['mark']==2) decks.player_2.setMark();
      });
      else gameUpdate(data['data']);
    }
  });
}

function gameUpdate(data) {
  if (typeof data['splitted_cards']!='undefined') {
    decks.player.addCard(
      data['splitted_cards']['card-1'][0], 
      data['splitted_cards']['card-1'][1], 
      data['splitted_cards']['card-1'][2] 
    );
    decks.player_2.addCard(
      data['splitted_cards']['card-2'][0], 
      data['splitted_cards']['card-2'][1], 
      data['splitted_cards']['card-2'][2] 
    ,function(){
      buttonsAccess(data['accessable']);
      decks.player.setSum(data['splitted_cards']['deck-1-value']);
      decks.player_2.setSum(data['splitted_cards']['deck-2-value']);
      
      if (data['winner']!='-' && data['winner']!='tie') ceremonial(true,'<b>WON</b>');
      else if (data['winner']=='tie') ceremonial(true,'<b>TIE</b>');
    });
  }
  if (typeof data['hitted_card-player_deck']!='undefined') {
    decks.player.addCard(
      data['hitted_card-player_deck'][0],
      data['hitted_card-player_deck'][1],
      data['hitted_card-player_deck'][2]
    ,function(){
      decks.player.setSum(data['hitted_sum']);
      buttonsAccess(data['accessable']);
      if (data['dealer_new']!='-') decks.dealer.revealCards(data['dealer_new']);
      if (data['dealer_sum']!='-') decks.dealer.setSum(data['dealer_sum']);
      if (data['winner']=='player') ceremonial(true,'<b>WON</b>');
      if (data['winner']=='tie') ceremonial(true,'<b>TIE</b>');
      if (data['winner']=='dealer') ceremonial(false,'<b>LOSE</b>');

      if (typeof data['re-stand']!='undefined' && data['winner']=='-') gameAction('stand');
      
      
    });
  }
  if (typeof data['nextDeck']!='undefined' && data['nextDeck']=='yes') {
    decks.player.removeMark();
    
    if (typeof decks.player_2!='undefined')  decks.player_2.setMark();
  }
  if (typeof data['hitted_card-player_deck_2']!='undefined') {
    decks.player_2.addCard(
      data['hitted_card-player_deck_2'][0],
      data['hitted_card-player_deck_2'][1],
      data['hitted_card-player_deck_2'][2]
    ,function(){
      decks.player_2.setSum(data['hitted_sum']);
      buttonsAccess(data['accessable']);
      if (data['dealer_new']!='-') decks.dealer.revealCards(data['dealer_new']);
      if (data['dealer_sum']!='-') decks.dealer.setSum(data['dealer_sum']);
      if (data['winner']=='player') ceremonial(true,'<b>WON</b>');
      if (data['winner']=='tie') ceremonial(true,'<b>TIE</b>');
      if (data['winner']=='dealer') ceremonial(false,'<b>LOSE</b>');
      
      if (typeof data['re-stand']!='undefined' && data['winner']=='-') gameAction('stand');

      
      
    });
  }
  if (typeof data['standed']!='undefined') {
      buttonsAccess(data['accessable']);
      if (data['dealer_new']!='-') decks.dealer.revealCards(data['dealer_new']);
      if (data['dealer_sum']!='-') decks.dealer.setSum(data['dealer_sum']);
      if (data['winner']=='player') ceremonial(true,'<b>WON</b>');
      if (data['winner']=='tie') ceremonial(true,'<b>TIE</b>');
      if (data['winner']=='dealer') ceremonial(false,'<b>LOSE</b>');
  
  } 
}

function deck(_deckObj) {
  
  this.$deckObj = _deckObj;
  this.$sumObj = $('<div class="deckSum"></div>')
                        .appendTo('body');
  
  this.$markObj = $('<div class="deck_mark"><span class="glyphicon glyphicon-chevron-down"></span></div>')
                        .appendTo('body');
  
  this.addCard = function (suit,val,colour,done) {

    var $deck=this.$deckObj;
  
    // init card
    var $emptyOuter=$('<div class="cardOuter"></div>').appendTo($deck);
    var $newCard=$('<div class="card"><div class="value">'+val+'</div><div class="suit">'+suit+'</div></div>');  
    
    if (suit=='-') $newCard.prepend('<div class="back"></div>');
    
    if (colour=='red') $newCard.addClass('red');
    
    var $temp=$emptyOuter.clone().appendTo('body');
    $temp.addClass('clone');
    $temp.css('left',$emptyOuter.offset().left);  
    $temp.append($newCard);
    
    $emptyOuter.append($newCard.clone().css('visibility','hidden'));
    
    $temp.animate({'top':$emptyOuter.offset().top},600,'linear',function(){
      $emptyOuter.children().css('visibility','visible');
      $temp.remove();
      if (done!=null) done();  
    });    
  
  }
  this.revealCards = function (cards) {
    var $deck=this.$deckObj;
    
    $deck.empty();
    
    for (var i=0;i<cards.length;i++) {
      var $emptyOuter=$('<div class="cardOuter"></div>').appendTo($deck);
      var $newCard=$('<div class="card"><div class="value">'+cards[i][1]+'</div><div class="suit">'+cards[i][0]+'</div></div>');  

      if (cards[i][2]=='red') $newCard.addClass('red');

      $emptyOuter.append($newCard);
    
    }
    
    original_width=$deck.width();
    
  }
  
  this.setSum = function (newsum) {
    var $sum=this.$sumObj;
    
    $sum.html(newsum);
    $sum.css('display','block');
  }

  this.sumPosition = function () {
    var $sum=this.$sumObj;
    
    var left=((this.$deckObj.offset().left) - ($sum.width() + 2*parseInt($sum.css('padding'))) - 30);
    
    if (this.$deckObj.hasClass('deck-second'))
      left=((this.$deckObj.offset().left) + (this.$deckObj.width()) + 30);
    
    $sum
        .css('top', (this.$deckObj.offset().top) + margin($sum.height(),this.$deckObj.height()))
        .css('left', left );

  }
  this.setMark = function () {
    var $mark=this.$markObj;
    
    deck_offset = this.$deckObj.offset();
    deck_width = this.$deckObj.width();
    deck_height = this.$deckObj.height();
    
    var top = (deck_offset.top - 30);
    var left = (deck_offset.left + margin($mark.width(),deck_width));
    
    $mark.css({
      display: 'block',
      left: left,
      top: top
    });    
  }
  this.removeMark = function () {
    var $mark=this.$markObj;
    
    $mark.css({
      display: 'none'
    });        
  }
}

function bet() {
  //ajax call (success: initGame())
  buttons.disableAll();
  $.ajax({
    'url': './content/ajax/gameCreate.php?wager='+$('.betInput').val()+'&_unique='+unique(),
    'dataType': "json",
    'success': function (data) {
      if (data['error']=='yes') {
        buttons.bet.enable();
        if (data['content']=='balance') bet_error('Your balance is too small. Please deposit.');
        if (data['content']=='too_big') bet_error('We can not currently operate such big bets.');
        if (data['playing']) location.reload();
      }
      else {
        initGame();
        decks.dealer.addCard(
          data['content']['dealer-1'][0],
          data['content']['dealer-1'][1],
          data['content']['dealer-1'][2]
        ,function(){
          decks.player.addCard(
            data['content']['player-1'][0],
            data['content']['player-1'][1],
            data['content']['player-1'][2]
          ,function(){
            decks.dealer.addCard(
              data['content']['dealer-2'][0],
              data['content']['dealer-2'][1],
              data['content']['dealer-2'][2]
            ,function(){
              decks.player.addCard(
                data['content']['player-2'][0],
                data['content']['player-2'][1],
                data['content']['player-2'][2]
              ,function(){
                buttonsAccess(data['accessable']);
                balRefresh();
                decks.player.setSum(data['sums']['player']);
                if (data['sums']['dealer']!='-') decks.dealer.setSum(data['sums']['dealer']);

                if (data['winner']=='player') ceremonial(true,'<b>WON</b>');
                if (data['winner']=='tie') ceremonial(true,'<b>TIE</b>');
                if (data['winner']=='dealer') ceremonial(false,'<b>LOSE</b>');
              });                  
            });          
          });        
        });
        
      }
    }    
  });
}

function ceremonial(won,content) {
  $cermess=$('<div class="ceremonial"></div>').appendTo('.cj-rivalTables');
  
  $cermess.html(content);
  
  $cermess.css({
    'display': 'block',
    'top': margin($cermess.height(),$('.cj-rivalTables').height()),
    'left': margin($cermess.width(),$('.cj-rivalTables').width())
  });
}

function _button(_obj) {

  this.$obj = _obj;

  this.disable = function() {
    var $obj=this.$obj;
    $obj.attr('disabled',true);
    $obj.addClass('btn-disabled');
  }

  this.enable = function() {
    var $obj=this.$obj;
    $obj.removeAttr("disabled");
    $obj.removeClass('btn-disabled');
  }

}

function initGame() {
  $('.cj-rivalTables').children().empty();
  $('.deckSum').remove();
  $('.ceremonial').remove();
  $('.deck_mark').remove();
  
  decks = {
    dealer: new deck($('<div class="deck"></div>').appendTo('.cj-dealerTable')),
    player: new deck($('<div class="deck"></div>').appendTo('.cj-playerTable'))
  }
  
}

function playingOnInit() {
  buttons.disableAll();
  $.ajax({
    'url': './content/ajax/gameContinue.php?_unique='+unique(),
    'dataType': "json",
    'success': function (data) {
      initGame();
      
      for (var i=0;i<data['dealer']['cards'].length;i++) {

        decks.dealer.addCard(
          data['dealer']['cards'][i][0],
          data['dealer']['cards'][i][1],
          data['dealer']['cards'][i][2]
        ,function(){
          buttonsAccess(data['accessable']);
          //decks.dealer.setSum(data['sums']['dealer']);
          decks.player.setSum(data['sums']['player']);
          if (data['sums']['player2']!='-') decks.player_2.setSum(data['sums']['player2']);
        });
        
               
      }
      for (var i=0;i<data['player']['cards'].length;i++) {

        decks.player.addCard(
          data['player']['cards'][i][0],
          data['player']['cards'][i][1],
          data['player']['cards'][i][2]
        );
        
        if (data['player']['cards2'] && i==1) splitPlayerDecks(function(){});
        
               
      }
      if (data['player']['cards2']) {
        for (var i=0;i<data['player']['cards2'].length;i++) {
    
          decks.player_2.addCard(
            data['player']['cards2'][i][0],
            data['player']['cards2'][i][1],
            data['player']['cards2'][i][2]
          );               
        }

      }
    }    
  });

}

function buttonsAccess(v) {
  if (v==0) {
    buttons.disableAll();
    buttons.bet.enable();
  }
  else if (v==1) {
    buttons.enableAll();
    buttons.bet.disable();
    buttons.split.disable();
  }
  else if (v==2) {
    buttons.enableAll();
    buttons.bet.disable();
  }
}

function splitPlayerDecks(done_fc) {
  var $secDeck = $('<div class="deck deck-2"></div>');
  decks.player_2 = new deck($secDeck);

  var $firstDeck = decks.player.$deckObj;

  var firstCard_offset_1 = $firstDeck
    .children().eq(-1)
    .offset();
  var firstCard_clone = $firstDeck
    .children().eq(-1)
    .clone().appendTo('body')
    .css('z-index','30')
    .css('position','absolute')
    .css('left',firstCard_offset_1.left)
    .css('top',firstCard_offset_1.top)
    .addClass('clone');
  var secCard_offset_1 = $firstDeck
    .children().eq(0)
    .offset();
  var secCard_clone = $firstDeck
    .children().eq(0)
    .clone().appendTo('body')
    .css('position','absolute')
    .css('left',secCard_offset_1.left)
    .css('top',secCard_offset_1.top)
    .addClass('clone');
      
  $firstDeck
    .children().eq(-1)
    .remove()
    .clone()
    .appendTo($secDeck);
    
  
  $firstDeck
    .wrap($('<table></table>').addClass('splitted'))
    .wrap('<tr></tr>')
    .parent().append($secDeck).children()
    .children().children().css('visibility','hidden').parent().parent()
    .wrap('<td></td>');
    
  decks.splitted = true;

  var firstCard_offset_2 = $secDeck
    .children().eq(0)
    .offset();
  var secCard_offset_2 = $firstDeck
    .children().eq(0)
    .offset();

  firstCard_clone.animate({
    'top': firstCard_offset_2.top,
    'left': firstCard_offset_2.left,
  },100,'linear',function(){
    firstCard_clone.remove();
    $firstDeck.children().children().css('visibility','visible');
    done_fc();
  });
  secCard_clone.animate({
    'top': secCard_offset_2.top,
    'left': secCard_offset_2.left,
  },100,'linear',function(){
    secCard_clone.remove();
    $secDeck.children().children().css('visibility','visible');
  });

}
