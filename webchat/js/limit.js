function Click(event){
	//禁止右键
	try{
    var obj=isIE ? window.event.srcElement.parentElement : event.target.parentNode;
    if(obj.id=='showbox'){
      return true;
    }
	}catch(e){}
	if(isIE){
    window.event.returnValue = false;
  }else{
    event.preventDefault();
  }
}
function KeyDown(event){
  if(typeof event=='undefined')return;
  var keycode=isIE?event.keyCode:event.which;
  var ctrl=isIE?window.event.ctrlKey:event.ctrlKey;
	if(ctrl){//Ctrl+键组合
    if(keycode !== 67 && keycode !== 80 && keycode !== 86 && keycode !== 88 && keycode !== 90){//限制在复制、打印
      try{
        if(isIE){
          window.event.returnValue = false;
        }else{
          event.preventDefault();
        }
        return;
      }catch(e){
      
      }
    }
	}
	//禁止F5刷新
	if (keycode== 116){	
    if(isIE){
      window.event.returnValue = false;
    }else{
      event.preventDefault();
    }
        return;
	}
}
//防止对话框中有人点击超联接,造成showcontent 变成其它联连接而网页出错
function Click2(){
	if(isIE){
    window.event.returnValue = false;
  }else{
    event.preventDefault();
  }
	return ;
}
document.oncontextmenu = Click;
//document.onmousedown = Click2; 
document.onkeydown = KeyDown;