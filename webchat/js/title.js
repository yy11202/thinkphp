document.writeln('<div id="titleBox" style="position:absolute;z-index:6200106;border:1px solid #dfdfdf;background:#fffad7;display:none;padding:5px;;filter:alpha(opacity=90);-moz-opacity:0.9;-khtml-opacity: 0.9; opacity: 0.9"></div>');
document.writeln('<div id="titleMask" style="position:absolute;z-index:6200105;display:none;background:#000;filter:alpha(opacity=30);-moz-opacity:0.3;-khtml-opacity: 0.3; opacity: 0.3;"></div>');
$(document).ready(function(){
  var mWidth=3;
  $('#titleBox').click(function(){
    $('#titleBox,#titleMask').hide();
  });
  $('[oneztitle]').livequery('mouseover',function(e){
    var offsetX=10,offsetY=10;
    $('#titleBox').html($(this).attr('oneztitle'));
    if(e.pageX>pageWidth()-$('#titleBox').width()-20){
      offsetX=-$('#titleBox').width()-10;
    }
    if(e.pageY>pageHeight()-$('#titleBox').height()-20){
      offsetY=-$('#titleBox').height()-10;
    }
    $('#titleBox').css({'left':e.pageX+offsetX+'px','top':e.pageY+offsetY+'px'}).show();
    $('#titleMask').css({'left':e.pageX+offsetX+mWidth+'px','top':e.pageY+offsetY+mWidth+'px','width':$('#titleBox').width()+10+'px','height':$('#titleBox').height()+10+'px'}).show();
  }).livequery('mouseout',function(e){
    $('#titleBox,#titleMask').hide();
  }).livequery('mousemove',function(e){
    var offsetX=10,offsetY=10;
    if(e.pageX>pageWidth()-$('#titleBox').width()-20){
      offsetX=-$('#titleBox').width()-10;
    }
    if(e.pageY>pageHeight()-$('#titleBox').height()-20){
      offsetY=-$('#titleBox').height()-10;
    }
    $('#titleBox').css({'left':e.pageX+offsetX+'px','top':e.pageY+offsetY+'px'});
    $('#titleMask').css({'left':e.pageX+offsetX+mWidth+'px','top':e.pageY+offsetY+mWidth+'px'});
  });
});