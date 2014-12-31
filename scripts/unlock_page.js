$(document).ready(function(){
  _ready();
});
function _ready() {
  $div=$('.loginDiv');
  placeElems();
  $(window).resize(function(){
    rePlaceElems();
  });
  
  $('input').keypress(function(e){
    if (e.which == 13) $('a').click();
  });
}

function placeElems() {
  rePlaceElems();
  
  var $clone = $div.clone().appendTo('body');
  var divOffset = $div.offset();
  $div.css('visibility','hidden');
  $clone
    .css('position','absolute')
    .css('margin-top',($clone.height()*-1))
  .animate({
    'top': (divOffset.top+$clone.height())
  },function(){
    $clone.remove();
    $div.css('visibility','visible');          
  });
  
}

function rePlaceElems() {
  $div.css('margin-top',margin($div.height(),$(window).height()));          
  $div.css('margin-left',margin($div.width(),$(window).width()));
}

function unlock() {
  $.ajax({
    'url': './content/ajax/unlockAccount.php?_unique='+unique()+'&pass='+CryptoJS.SHA256($('input').val()),
    'dataType': "json",
    'success': function (data) {
      if (data['error']=='yes') alert('Incorrect password.');
      else location.reload();
    }
  });
}

function margin(elem,wrap) {
  return ((wrap-elem)/2);
}
