var isIE = document.all && window.external ? 1 : 0;
var isW3C =typeof document.compatMode != 'undefined' && document.compatMode != 'BackCompat'?true:false;
function stopError() {
  return true;
}
window.onerror = stopError;
function getcookie(name) {
	var cookie_start = document.cookie.indexOf(name);
	var cookie_end = document.cookie.indexOf(";", cookie_start);
	return cookie_start == -1 ? '' : unescape(document.cookie.substring(cookie_start + name.length + 1, (cookie_end > cookie_start ? cookie_end : document.cookie.length)));
}

function OpenRoom(rid){
  if(typeof _rid!='undefined' && _rid==rid){
    alert('您已经在这个房间中了');
    return;
  }
  window.open('room.php?rid='+rid,'Room'+rid,'toolbar=no,location=no,status=no,menubar=no,scrollbars=no,resizable=no,width='+screen.availWidth+',height='+screen.availHeight);
}
function setcookie(cookieName, cookieValue, seconds, path, domain, secure) {
	seconds = seconds ? seconds : 8400000;
	var expires = new Date();
	expires.setTime(expires.getTime() + seconds);
	document.cookie = escape(cookieName) + '=' + escape(cookieValue)
		+ (expires ? '; expires=' + expires.toGMTString() : '')
		+ (path ? '; path=' + path : '/')
		+ (domain ? '; domain=' + domain : '')
		+ (secure ? '; secure' : '');
}
function InsertFlash(Flash,Vars,Width,Height,ID,Box){
  var FlashHtml='<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" ';
  FlashHtml+='codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,29,0" ';
  FlashHtml+='width="' + Width + '" height="' + Height + '" id="' + ID + '">';
  FlashHtml+='<param name="movie" value="' + Flash + '">';
  FlashHtml+='<param name="quality" value="high">';
  FlashHtml+='<param name="wmode" value="window">';
  FlashHtml+='<param name="allowScriptAccess" value="always">';
  FlashHtml+='<param name="FlashVars" value="'+Vars+'">';
  FlashHtml+='<embed src="' + Flash + '" name="' + ID + '" quality="high" allowScriptAccess="always" pluginspage="http://www.macromedia.com/go/getflashplayer" ';
  FlashHtml+='type="application/x-shockwave-flash" width="' + Width + '" FlashVars="'+Vars+'" wmode="window" height="' + Height + '"></embed>';
  FlashHtml+='</object>';
  $(FlashHtml).appendTo(Box);
}
function pageHeight(){ 
  if($.browser.msie){ 
    return document.compatMode == "CSS1Compat"? document.documentElement.clientHeight : document.body.clientHeight; 
  }else{ 
    return self.innerHeight; 
  } 
}
function pageWidth(){ 
  if($.browser.msie){ 
    return document.compatMode == "CSS1Compat"? document.documentElement.clientWidth : document.body.clientWidth; 
  }else{ 
    return self.innerWidth; 
  }
}
function in_array(k,arr){
  for(var i=0;i<arr.length;i++){
    if(k==arr[i])return true;
  }
  return false;
}
function D(){
  return top.document.getElementById("Group_body").contentWindow;
}
function drag(o){
	o.onmousedown=function(a){
		var d=document;if(!a)a=window.event;
		var x=a.layerX?a.layerX:a.offsetX,y=a.layerY?a.layerY:a.offsetY;
    var b=document.all?a.srcElement:a.target;
		if(o.setCapture)
			o.setCapture();
		else if(window.captureEvents)
			window.captureEvents(Event.MOUSEMOVE|Event.MOUSEUP);

		d.onmousemove=function(a){
			if(!a)a=window.event;
			if(!a.pageX)a.pageX=a.clientX;
			if(!a.pageY)a.pageY=a.clientY;
			var tx=a.pageX-x,ty=a.pageY-y;
			var pX=isIE ? document.documentElement.scrollLeft : pageXOffset;
			var pY=isIE ? document.documentElement.scrollTop : pageYOffset;
      o.style.left=(pX + tx)+'px';
      o.style.top=(pY + ty)+'px';
		};

		d.onmouseup=function(){
			if(o.releaseCapture)
				o.releaseCapture();
			else if(window.captureEvents)
				window.captureEvents(Event.MOUSEMOVE|Event.MOUSEUP);
			d.onmousemove=null;
			d.onmouseup=null;
		};
	};
}