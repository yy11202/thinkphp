$(document).ready(function(){
  PrintChat('1','<span id="conn"><font color="#999999">正在连接服务器...</font></span>','im_info');
  self.moveTo(0,0); self.resizeTo(screen.availWidth, screen.availHeight);
  $('.usrList li').livequery('mouseover',function(){
    $(this).addClass('hover');
  }).livequery('mouseout',function(){
    $(this).removeClass('hover');
  }).livequery('click',function(){
    updateTo($(this).attr('uid'));
    $('.usrList li').removeClass('select');
    $(this).addClass('select');
  });
  
  $('.btn_normal').bind('mouseover',function(){
    $(this).removeClass().addClass('btn_normal').addClass('btn_hover');
  }).bind('mouseout',function(){
    $(this).removeClass().addClass('btn_normal');
  }).bind('mousedown',function(){
    $(this).removeClass().addClass('btn_normal').addClass('btn_down');
  }).bind('mouseup',function(){
    $(this).removeClass().addClass('btn_normal').addClass('btn_hover');
  });
  
  $('#usrtype li').click(function(){
    $('#usrtype li').removeClass('select');
    $(this).addClass('select');
    var id=$(this).attr('id').substr(5);
    $('#usrbox').removeClass().addClass('filter_'+id);
  });
  $('#ufilter').bind('focus',function(){
    $(this).addClass('focus');
    if($(this).val()==$(this)[0].defaultValue){
      $(this).val('');
    }
  }).bind('blur',function(){
    $(this).addClass('focus');
    if($(this).val()==''){
      $(this).val($(this)[0].defaultValue);
    }
  });
  
  ResetSize();
  $(window).bind('resize',function(){
    ResetSize();
  });
  updateTo(lastTo);
  $('#input')[0].focus();
  var _fonts=getcookie('fonts').split('|');
  fonts={b:_fonts[0]=='1',i:_fonts[1]=='1',u:_fonts[2]=='1',name:_fonts[3],size:_fonts[4],color:_fonts[5]}
  setFontBox();
  
  
  var params = {
    menu: "false",
    scale: "noScale",
    allowFullscreen: "true",
    allowScriptAccess: "always",
    bgcolor: "",
    wmode: "direct" // can cause issues with FP settings & webcam
  };
  var attributes = {
    id:"_chat_obj"
  };
  swfobject.embedSWF(
    _flash, 
    "videoBox", _flashwidth, _flashheight, "10.1.0", 
    "expressInstall.swf", 
    _flashvars, params, attributes);
    
  var attributes = {
    id:"_msg_obj"
  };
  swfobject.embedSWF(
    _flash2, 
    "broadcastBox", _flashwidth, _flashheight, "10.1.0", 
    "expressInstall.swf", 
    _flashvars, params, attributes);
  LoadMenu();
  LoadShebei();
  ScrollControl('3');
});
function updateOnline(){
  $('#num_all').html($('#usrList li').length);
  $('#num_boy').html($('#usrList li.sex1').length);
  $('#num_girl').html($('#usrList li.sex2').length);
  $('#num_master').html($('#usrList li.master').length);
  searchUsr();
}
function searchUsr(){
  var s=$('#ufilter').val();
  if(s==$('#ufilter')[0].defaultValue){
    s='';
  }
  $('#usrList li').each(function(){
    if(s.length>0 && $(this).text().indexOf(s)==-1){
      $(this).hide();
    }else{
      $(this).show();
    }
  });
}
function Repeat(){
  alert('您的账号已在其他地方登录，被迫下线！');
  window.close();
}
var mics=[];
var cams=[];
function SheBei(_mics,_cams){
  //alert([mics,cams]);
  mics=_mics;
  cams=_cams;
}
function ResetSize(){
  var cWidth=pageWidth()-580;
  var cHeight=pageHeight()-20;
  if($('#micList').length>0){
    $('#micList').height(cHeight-$('#micList').offset().top-100);
  }else{
    cWidth+=200;
  }
  $('#boxCenter').width(cWidth);
  $('#o_marquee').width(cWidth-250);
  $('#input').width(cWidth-10);
  $('#usrList').height(cHeight-$('#usrList').offset().top-$('#props').height()-$('#showbox3').height()+5);
  $('#showbox1').height(cHeight-$('#showbox1').offset().top-$('#inputbox').height()-$('#showbox2').height()-10-25);
}

var editor;
var usrs={};
function Online(lst){
  var uids=[];
  for(var i=0;i<lst.length;i++){
    uids.push(lst[i][0]);
    addUsr(lst[i]);
  }
  for(var uid in usrs){
    if(!in_array(uid,uids)){
      delUsr(uid);
    }
  }
  $('#online').html('在线: '+uids.length+'人');
  updateTo(lastTo);
  updateOnline();
}
var sendWords=[];
function autoSend(){
  if(sendWords.length<1){
    return;
  }
  PrintChat('1',sendWords.shift(),'im_sys');
}
setInterval('autoSend()',50);
function Sys(data){
  for(var key in data){
    switch(key){
      case'in':
        PrintChat('1',getRndWord('in').replace(/\{user\}/g,getUsrTxt(data[key][0],data[key][1],'B',0)),'im_sys');
        break;
      case'out':
        PrintChat('1',getRndWord('out').replace(/\{user\}/g,getUsrTxt(data[key][0],data[key][1],'B',0)),'im_sys');
        break;
      case'msg':
        /*
        if(data[key].indexOf('★礼物★')!=-1){
          var o=data[key].match(/ [0-9]+ 个/g);
          if(o.length>1){
            var t1=parseInt(o[0].replace(/ /g,'').replace('个',''));
            var t2=parseInt(o[1].replace(/ /g,'').replace('个',''));
            var ts=Math.floor(t1/50);
            if(ts<1)ts=1;
            var tc=t2-t1+1;
            var t=t1;
            while(t>0){
              var t_send=t>ts ? ts : t;
              tc+=t_send;
              t-=t_send;
              s=data[key].replace('共 '+t2+' 个','共 '+tc+' 个').replace(t1+' 个',t_send+' 个');
              sendWords.push(s);
            }
          }
        }else{
          PrintChat('1',data[key],'im_sys');
        }
        */
        PrintChat('1',data[key],'im_sys');
        break;
      case'code':
        try{
          eval(data[key]);
        }catch(e){}
        break;
      case'flower':
        if(data[key][0]==_uid){
          $('#infos_flower').html(data[key][1]);
        }
        break;
      case'seal':
        $('#lst_'+data[key][0]+' .seal').remove();
        $('<img src="'+data[key][1]+'" class="seal" />').appendTo('#lst_'+data[key][0]);
        break;
      case'avatar':
        $('[avatar="'+data[key]+'"]').attr('src','avatar.php?uid='+data[key]+'&t='+Math.random());
        break;
      case'system':
        _data=data[key].data;
        for(var token in _data){
          if(token=='refresh'){
            if(data[key].to && data[key].to!=_uid){
              continue;
            }
            if(data[key].nomaster && ismaster){
              continue;
            }
            if(data[key].ismaster && !ismaster){
              continue;
            }
            location.reload();
            return;
          }else if(token=='out'){
            if(data[key].to && data[key].to!=_uid){
              continue;
            }
            if(data[key].nomaster && ismaster){
              continue;
            }
            if(data[key].ismaster && !ismaster){
              continue;
            }
            location.href='index.php';
          }else if(token=='dropmic'){
            if(data[key].to && data[key].to!=_uid){
              continue;
            }
            if(data[key].nomaster && ismaster){
              continue;
            }
            if(data[key].ismaster && !ismaster){
              continue;
            }
            Cancel();
          }else if(token=='master'){
            if(_data[token][0]==_uid){
              ismaster=_data[token][1]!=0;
              LoadMenu();
            }
            if(_data[token][1]!=0){
              $('#lst_'+_data[token][0]).addClass('master');
              $('#lst_'+_data[token][0]+' .uname').addClass('mj'+_data[token][1]+'_'+usrs[_data[token][0]].sex);
              $('#lst_'+_data[token][0]+' .uname').attr('title',masterNames[_data[token][1]]);
              
            }else{
              $('#lst_'+_data[token][0]).removeClass('master');
              $('#lst_'+_data[token][0]+' .uname').attr('title','');
            }
          }else if(token=='clear'){
            if(data[key].to && data[key].to!=_uid){
              continue;
            }
            if(data[key].nomaster && ismaster){
              continue;
            }
            if(data[key].ismaster && !ismaster){
              continue;
            }
            Clear();
          }
        }
        break;
    }
  }
}
var shutup=false;
function gettip(key,s){
  if(typeof tips[key]=='undefined')return'';
  return tips[key].replace(/\<\!\-\-\$OBJECT\-\-\>/g,s);
}
var lastMic='';
function Mics(mics){
  if(in_array(_uid,mics)){
    $('#delmic').show();
    $('#addmic').hide();
    $('#addmic2').hide();
  }else{
    $('#delmic').hide();
    $('#addmic').show();
    $('#addmic2').show();
  }
  $('#micList li').remove();
  for(var i=0;i<mics.length;i++){
    addMic(mics[i]);
  }
}
var me={}
function addUsr(info){
  var uid=info[0];
  var video=info[1];
  var audio=info[2];
  if(typeof usrs[uid]=='undefined' || typeof usrs[uid].uid=='undefined'){
    usrs[uid]={video:video,audio:audio};
    $.post('onez.php?action=getusr',{uid:uid,rid:_rid},function(o){
      if(o && o.uid){
        usrs[o.uid]=o;
        usrs[o.uid].video=video;
        usrs[o.uid].audio=audio;
        addUsr(info);
      }
    },'json');
  }else{
    var u=usrs[uid];
    if(uid==_uid){
      me=u
      _username=u.username;
      $('#myname').html(_username+'('+uid+')');
      ismaster=(u.master?true:false);
      if(ismaster){
        $('#toolbar_master').show();
      }else{
        $('#toolbar_master').hide();
      }
    }
    if($('#lst_'+uid).length>0){
      return;
    }
    var pic='';
    var li=$('<li id="lst_'+uid+'" '+pic+' />').attr('uid',uid).attr('oneztitle',getInfoTable(uid)).addClass('sex'+u.sex);
    $('<img src="avatar.php?uid='+u.uid+'" avatar="'+uid+'" class="avatar" />').appendTo(li);
    if(u.seal)$('<img src="'+u.seal+'" class="seal" />').appendTo(li);
    var uname=$('<span />');
    if(typeof u['master']!='undefined'){
      uname.addClass('mj'+u.master+'_'+(u.sex=='1'?'1':'0'));
      //uname.attr('title',masterNames[u.master]);
      li.addClass('master');
    }
    if(u.pic){
      //pic='style="background-image:url('+u.pic+')"';
      pic='<img width="16" height="16" align="absmiddle" src="'+u.pic+'" /> ';
    }
    uname.addClass('uname').html(pic+u.username).appendTo(li);
    var sign=$('<span />');
    sign.addClass('sign').html(u.sign).appendTo(li);
    if(video=='1'){
      $('<img src="images/video.gif" class="video" />').appendTo(li);
    }
    li.prependTo($('#usrList'));
    updateOnline();
  }
}
var masterNames=['站长','总管','超管','巡管','房管','临管'];
var ismaster=false;
var tolist=[];
var lastTo='';
function updateTo(uid){
  if(uid==_uid){
    uid='';
  }
  try{
    var u=[['','大家']];
    var newLst=[];
    for(var i=0;i<tolist.length;i++){
      if(tolist[i]!=_uid && usrs[tolist[i]] && !in_array(tolist[i],newLst)){
        u.push([tolist[i],usrs[tolist[i]].username]);
        newLst.push(tolist[i]);
      }
    }
    if(uid!='' && usrs[uid] && !in_array(uid,newLst)){
      u.push([uid,usrs[uid].username]);
      newLst.push(uid);
    }
    tolist=newLst;
    $('#tousr option').remove();
    for(var i=0;i<u.length;i++){
      if(u[i][0]!='' && (!u[i][1] || typeof u[i][1]=='undefined' || typeof usrs[u[i][0]]=='undefined'))continue;
      $('<option value="'+u[i][0]+'">'+u[i][1]+'</option>').appendTo($('#tousr'));
    }
    lastTo=uid;
    $('#tousr option[value="'+lastTo+'"]').attr('selected',true);
    if($('#send_tousr').length>0){
      $('#send_tousr').html($('#tousr').html());
      $("#send_tousr option:nth-child(1)").remove();
      if($('#master').length>0){
        $('<option value="0">所有人</option>').prependTo($('#send_tousr'));
      }
    }
  }catch(e){
  
  }
}
function delUsr(uid){
  $('#lst_'+uid).remove();
  delete usrs[uid];
}
function addMic(uid){
  if(typeof usrs[uid]=='undefined' || typeof usrs[uid].uid=='undefined'){
    usrs[uid]={};
    $.post('onez.php?action=getusr',{uid:uid,rid:_rid},function(o){
      if(o && o.uid){
        usrs[o.uid]=o;
        addMic(uid);
      }
    },'json');
  }else{
    var u=usrs[uid];
    if($('#mic_'+uid).length>0){
      return;
    }
    var li=$('<li id="mic_'+uid+'" />').attr('uid',uid);
    $('<img src="avatar.php?uid='+u.uid+'" avatar="'+uid+'" class="avatar" />').appendTo(li);
    $('<span />').addClass('uname').html(u.username).appendTo(li);
    li.appendTo($('#micList'));
  }
}
function delMic(uid){
  $('#lst_'+uid).remove();
  delete usrs[uid];
}
function F(){
  if (navigator.appName.indexOf("Microsoft") != -1) {
    return window['_chat_obj'];
  }else{
    return document['_chat_obj'];
  }
}
function M(){
  if (navigator.appName.indexOf("Microsoft") != -1) {
    return window['_msg_obj'];
  }else{
    return document['_msg_obj'];
  }
}
function Request(){
  if(ismaster || (me['item'] && me.item=='yuetuan')){
    F().Request(-100);
  }else{
    F().Request(300);
  }
}
function Request2(){
  if(ismaster){
    if(confirm('此操作将会把当前麦上的用户挤下去，是否继续？')){
      F().Request2(-100);
    }
  }
}
function Cancel(){
  F().Cancel(_uid);
}
var timer_update=null;
function Welcome(uid){
  _uid=uid;
  if($('#conn').length>0){
    $('#conn').html('<font color="green">连接服务器成功</font>');
  }
  if(timer_update==null){
    timer_update=setInterval('update()',60000);
  }
  F().Info(curShebei);
}
function update(){
  $.ajax({
    url:'onez.php?action=update',
    type:'post',
    cache: false,
    dataType:'html',
    success:function(data){
    }
  });
}
function Debug(s){
  PrintChat('1','['+Now()+']'+s.join('|'),'im_info');
}

function PrintChat(p,str,cname){
  var o=$('<div />').html(str);
  if(cname!='')o.addClass(cname);
  o.appendTo($('#showbox'+p));
  ScrollControl(p);
}
function ScrollControl(p){
  $('#showbox'+p).scrollTop($('#showbox'+p)[0].scrollHeight);
}
function Send(){
  if(_uid==''){
    return;
  }
  if(shutup){
    PrintChat('2','你已被系统禁言!','im_info');
    return;
  }
  var message=$('#input').val();
  message=message.replace(/\n/g,'<br />');
	message=message.replace(new RegExp('<scr'+'ipt[^>]*?>.*?</scr'+'ipt>','g'), "");
	message=message.replace(new RegExp('\<\!\-\-.*?\-\-\>','g'), "");
	message=message.replace('\<\!\-\-', "");
  if(message.length<1){
    $('#input').val('');
    $('#input')[0].focus();
    return false;
  }
  if(!checkSendSec()){
    return;
  }
  $('#input').val('');
  $('#input')[0].focus();
  sendTo(message);
  
}
function Error(s){
  PrintChat('2',s,'im_info');
}
var lasttime=0;
function checkSendSec(){
  var thistime=new Date().getTime();
  var sec=Math.floor((thistime-lasttime)/1000);
  if(!ismaster && sec<3){
    PrintChat('2','您的发言太快!','im_info');
    return false;
  }
  lasttime=thistime;
  return true;
}
function sendTo(message,to){
  if(!to)to=$('#tousr').val();
  if(message.substr(0,1)=='/'){
    $.ajax({
      url:'onez.php?action=checkmsg',
      type:'post',
      cache: false,
      dataType:'html',
      data:{to:to,message:message,rid:_rid},
      success:function(s){
        try{
          eval(s);
        }catch(e){}
      }
    });
    return;
  }
  sendToServer(message);
}
function sendToServer(message){
  var to=$('#tousr').val();
  var qqh=$('#qqh').attr('checked');
  F().Send(qqh?to:'',{
    qqh:qqh?'1':'0',
    uid:_uid,
    username:_username,
    to:to,
    toname:$('#tousr option:selected').text(),
    message:fontStyle[0].join('')+message+fontStyle[1].join('')
  });
}
function getMsg(from,to,msg){
  var message=ubbtohtml(msg.message);
  if(msg.qqh=='0'){
    PrintChat('1',getUsrTxt(msg.uid,msg.username,'A')+'对'+getUsrTxt(msg.to,msg.toname,'B')+'说： <span class="time">'+Now()+'</span>','im_name');
    PrintChat('1',message,'im_content');
  }
  if(msg.uid==_uid || msg.to==_uid){
    PrintChat('2',getUsrTxt(msg.uid,msg.username,'A')+(msg.qqh=='1'?'悄悄地':'')+'对'+getUsrTxt(msg.to,msg.toname,'B')+'说： <span class="time">'+Now()+'</span>','im_name');
    PrintChat('2',message,'im_content');
  }
  MsgTip();
}
function toUsr(uid){
  updateTo(uid);
}
function Good(){
  if(!checkSendSec()){
    return;
  }
  sendTo(getRndWord('good'));
}
function getRndWord(type){
  var w=words[type];
  if(!w){
    return '';
  }
  var index=Math.floor(Math.random()*w.length);
  if(index<0){
    return '';
  }
  return w[index];
}
function getUsrTxt(uid,uname,pos,toyou){
  if(typeof toyou=='undefined')toyou=1;
  if(toyou==1 && uid==_uid){
    uname='你';
  }
  var sex='0';
  if(usrs[uid] && usrs[uid].sex){
    sex=usrs[uid].sex;
  }
  return '<a href="javascript:toUsr(\''+uid+'\')" class="sex'+sex+'">'+uname+'</a>';
}
function input_onkeydown(e){
  var keycode=isIE?e.keyCode:e.which;
  var SendStr=false;
  if(keycode==13){
    SendStr=true;
  }
  if(SendStr){
    isIE ? e.returnValue=false : e.preventDefault();
    Send();
  }
}
function Now(){
	date = new Date();
	H_=date.getHours().toString();
	i_=date.getMinutes().toString();
	s_=date.getSeconds().toString();
	if(i_.length==1)i_="0"+i_;
	if(s_.length==1)s_="0"+s_;
	return H_+":"+i_+":"+s_;
}
function ubbtohtml(fdata){
  fdata=fdata.replace(new RegExp('\\[b\\]','g'),'<b>');
  fdata=fdata.replace(new RegExp('\\[i\\]','g'),'<i>');
  fdata=fdata.replace(new RegExp('\\[u\\]','g'),'<u>');
  fdata=fdata.replace(new RegExp('\\[\\/b\\]','g'),'</b>');
  fdata=fdata.replace(new RegExp('\\[\\/i\\]','g'),'</i>');
  fdata=fdata.replace(new RegExp('\\[\\/u\\]','g'),'</u>');
  fdata=fdata.replace(new RegExp('\\[name=([^\\]]+)\\]','g'),'<span style="font-family:$1">');
  fdata=fdata.replace(new RegExp('\\[size=([^\\]]+)\\]','g'),'<span style="font-size:$1px">');
  fdata=fdata.replace(new RegExp('\\[color=([^\\]]+)\\]','g'),'<span style="color:$1">');
  fdata=fdata.replace(new RegExp('\\[\\/(name|size|color)\\]','g'),'</span>');
  
  fdata=fdata.replace(new RegExp('\\[room:([0-9]+)\\]','g'),'<a href="javascript:OpenRoom($1)">[房间入口]</a>');
  
  fdata=fdata.replace(new RegExp('\\[:([^_]+)_([^\\]]+)\\]','g'),'<img src="images/emote/$1/$2.gif">');
  fdata=fdata.replace(new RegExp('\\[url\\](www.|http:\/\/){1}([^\[\"\']+?)\\[\/url\\]','gi'),'<a href="$1$2" target="_blank">$1$2</a>');
  fdata=fdata.replace(new RegExp('\\[url=(www.|http:\/\/){1}([^\[\"\']+?)\\](.+?)\\[\/url\\]','gi'),'<a href="$1$2" target="_blank">$3</a>');
  fdata=fdata.replace(new RegExp('\\[img\\]([^\[\"\']+?)\.(gif|jpg|bmp|png){1}\\[\/img\\]','gi'),'<img src="$1.$2" />');
  return fdata;
}
function Sound(){
  if (navigator.appName.indexOf("Microsoft") != -1) {
    return window['_sound_obj'];
  }else{
    return document['_sound_obj'];
  }
}
var MsgTipBool=true;
function MsgTip(){
  if(top.document.hasFocus()){
    top.document.title=_roomname;
    return;
  }
  top.document.title=MsgTipBool?'【有新消息】':'【　　　　】';
  MsgTipBool=!MsgTipBool;
  setTimeout('MsgTip()',800);
}
function Gift(){
  Boxy.remove();
  Boxy.load('box_gift.php',{
    title:'礼物赠送',
    closeable:true,
    modal:false,
    draggable:true,
    afterShow:function(){
      $('#send_tousr').html($('#tousr').html());
      $("#send_tousr option:nth-child(1)").remove();
    }
  });
}
function SelGift(pid){
  $.ajax({
    url:'onez.php?action=gift',
    type:'post',
    cache: false,
    dataType:'json',
    data:{pid:pid,to:$('#send_tousr').val(),num:$('#send_num').val()},
    success:function(o){
      Boxy.remove();
      if(o.error){
        PrintChat('2',o.error,'im_info');
      }else if(o.success){
        F().Sys({msg:o.success});
        if(o.data){
          if(o.data.sys){
            F().Sys(o.data.sys);
          }
        }
      }
    }
  });
  $('#titleBox,#titleMask').hide();
}
function Seal(){
  Boxy.remove();
  Boxy.load('box_seal.php',{
    title:'印章赠送',
    closeable:true,
    modal:false,
    draggable:true,
    afterShow:function(){
      $('#send_tousr').html($('#tousr').html());
      $("#send_tousr option:nth-child(1)").remove();
    }
  });
}
function SelSeal(pid){
  $.ajax({
    url:'onez.php?action=seal',
    type:'post',
    cache: false,
    dataType:'json',
    data:{pid:pid,to:$('#send_tousr').val()},
    success:function(o){
      Boxy.remove();
      if(o.error){
        PrintChat('2',o.error,'im_info');
      }else if(typeof o.success!='undefined'){
        F().Sys({msg:o.success});
        if(o.data){
          if(o.data.sys){
            F().Sys(o.data.sys);
          }
        }
      }
    }
  });
  $('#titleBox,#titleMask').hide();
}
function Face(){
  Boxy.remove();
  Boxy.load('box_face.php',{
    title:'选择头像',
    closeable:true,
    modal:false,
    draggable:true
  });
}
function SelFace(pid){
  if(confirm('您确定要购买这个头像吗？')){
    $.ajax({
      url:'onez.php?action=face',
      type:'post',
      cache: false,
      dataType:'json',
      data:{pid:pid},
      success:function(o){
        Boxy.remove();
        if(o.error){
          PrintChat('2',o.error,'im_info');
        }else if(typeof o.success!='undefined'){
          if(o.data){
            if(o.data.sys){
              F().Sys(o.data.sys);
            }
          }
        }
      }
    });
  }
}
function Master(){
  Boxy.remove();
  Boxy.load('box_master.php?rid='+_rid,{
    title:'房间管理',
    closeable:true,
    modal:false,
    draggable:true,
    afterShow:function(){
      $('#send_tousr').html($('#tousr').html());
      $("#send_tousr option:nth-child(1)").remove();
      $('<option value="0">所有人</option>').prependTo($('#send_tousr'));
    }
  });
}
function SetMaster(){
  $.ajax({
    url:'onez.php?action=master',
    type:'post',
    cache: false,
    dataType:'json',
    data:$('form#master').serialize(),
    success:function(o){
      if(o.error){
        PrintChat('2',o.error,'im_info');
      }else if(typeof o.success!='undefined'){
        Boxy.remove();
        if(o.success!='')F().Sys({msg:o.success});
        if(o.data){
          if(o.data.sys){
            F().Sys(o.data.sys);
          }
        }
      }
    }
  });
}
function Notice(){
  Boxy.remove();
  Boxy.load('box_broadcast.php?rid='+_rid,{
    title:'发布广播',
    closeable:true,
    modal:false,
    draggable:true
  });
}
function SendNotice(){
  var message=$('#msg').val().replace(/^\s+/g,'').replace(/\s+$/g,'');
  if(message.length<1){
    alert('请填写您要发布的广播内容');
    return;
  }
  $.ajax({
    url:'onez.php?action=broadcast',
    type:'post',
    cache: false,
    dataType:'html',
    data:$('form#broadcast').serialize(),
    success:function(o){
      try{
        eval(o);
      }catch(e){}
    }
  });
}
function InsertRoom(){
  $('#msg').val($('#msg').val()+_roomname+'[room:'+_rid+']');
}
function getMsg2(from,to,msg){
  var message=msg.msg;
  if(msg.type=='flower'){
    
    return;
  }
  message=ubbtohtml(message);
  PrintChat('2','★广播★'+msg.usr+': '+message,'im_msg');
  PrintChat('3','※'+msg.usr+':','im_usr');
  PrintChat('3',message,'im_msg');
}
function LoadMenu(){
  $('#menus').html('');
  $.ajax({
    url:'onez.php?action=menus',
    type:'post',
    cache: false,
    dataType:'html',
    data:{rid:_rid},
    success:function(s){
      if(s.indexOf('<option')==0){
        $('#menus').html(s);
      }
    }
  });
}
var curShebei={};
function LoadShebei(def){
  $('#menus').html('');
  $.ajax({
    url:'onez.php?action=shebei&def='+def,
    type:'post',
    cache: false,
    dataType:'json',
    data:{rid:_rid},
    success:function(o){
      curShebei=o;
      try{
        for(var key in curShebei){
          $('select[name="'+key+'"] option[value="'+curShebei[key]+'"]').attr('selected',true);
        }
      }catch(e){}
    }
  });
}
function SelMenu(){
  var c=$('#menus').val();
  $('#menus option[value=""]').attr('selected',true);
  if(c.length<1){
    return;
  }
  if(c=='/c shutup' || c=='/c kick' || c=='/c kill'){
    var theResponse = window.prompt("请输入操作原因",gettip('Kick Message1'));
    if(theResponse==null || theResponse==''){
      return;
    }
    c+=' '+theResponse;
  }else if(c=='/c warn'){
    var theResponse = window.prompt("请输入警告内容","");
    if(theResponse==null || theResponse==''){
      return;
    }
    c+=' '+theResponse;
  }else if(c=='/c subject'){
    var theResponse = window.prompt("请输入话题内容","");
    if(theResponse==null || theResponse==''){
      return;
    }
    c+=' '+theResponse;
  }else if(c=='/c addmic'){
    var theResponse = window.prompt("请输入新的时间","300");
    if(theResponse==null || theResponse==''){
      return;
    }
    c+=' '+theResponse;
  }
  sendTo(c);
}
var effect='';
function SelEffect(){
  var c=$('#htmls').val();
  $('#htmls option[value=""]').attr('selected',true);
  if(c.length<1){
    return;
  }
  if(typeof effects[c]=='undefined'){
    return;
  }
  effect=c;
  var theResponse = window.prompt("请输入准备使用特效的文本","").replace(/^\s+/g,'').replace(/\s+$/g,'');
  if(theResponse!=''){
    sendTo(effects[c].replace('<!--$OBJECT-->',theResponse));
  }
}
function SelEmotes1(){
  var c=$('#emotes1').val();
  $('#emotes1 option[value=""]').attr('selected',true);
  if(c.length<1){
    return;
  }
  sendTo('//'+c);
}
function SelEmotes2(){
  var c=$('#emotes2').val();
  $('#emotes2 option[value=""]').attr('selected',true);
  if(c.length<1){
    return;
  }
  sendTo('//'+c);
}
var micIndex=0;
var camIndex=0;
function VideoSetting(){
  Boxy.remove();
  Boxy.load('box_shebei.php?rid='+_rid,{
    title:'视频/语音设置',
    closeable:true,
    modal:false,
    draggable:true,
    afterShow:function(){
      $('#audiolist,#videolist').html('');
      for(var i=0;i<mics.length;i++){
        var s=i==micIndex?'selected':'';
        $('<option value="'+i+'" '+s+'>'+mics[i]+'</option>').appendTo('#audiolist');
      }
      for(var i=0;i<cams.length;i++){
        var s=i==camIndex?'selected':'';
        $('<option value="'+i+'" '+s+'>'+cams[i]+'</option>').appendTo('#videolist');
      }
      for(var key in curShebei){
        $('select[name="'+key+'"] option[value="'+curShebei[key]+'"]').attr('selected',true);
      }
    }
  });
}
function SetVideo(){
  
  $('#shebei select[name]').each(function(){
    curShebei[$(this).attr('name')]=$(this).val();
  });
  F().Info(curShebei,true);
  Boxy.remove();
  $.ajax({
    url:'onez.php?action=shebei_save',
    type:'post',
    cache: false,
    dataType:'json',
    data:curShebei,
    success:function(o){
    }
  });
}
function Emote(){
  Boxy.remove();
  Boxy.load('box_emote.php',{
    title:'选择动画表情',
    modal:false,
    draggable:true
  });
}
function SelEmote(type,name){
  $('#input').val($('#input').val()+'[:'+type+'_'+name+']');
  Boxy.remove();
  $('#input')[0].focus();
}
function Font(){
  Boxy.remove();
  Boxy.load('box_font.php',{
    title:'字体设置',
    modal:false,
    draggable:true
  });
}
var fonts={};
function Font(){
  Boxy.remove();
  Boxy.load('box_font.php',{
    title:'字体设置',
    modal:false,
    center:false,
    draggable:true,
    afterShow:setFontBox
  });
}
var fontStyle=[[],[]];
function setFontBox(){
  var f={};
  fontStyle=[[],[]];
  if(fonts.b){
    f['font-weight']='bold';
    $('#font_b').addClass('select');
    fontStyle[0].unshift('[b]');
    fontStyle[1].push('[/b]');
  }else{
    f['font-weight']='normal';
    $('#font_b').removeClass('select');
  }
  if(fonts.i){
    f['font-style']='italic';
    $('#font_i').addClass('select');
    fontStyle[0].unshift('[i]');
    fontStyle[1].push('[/i]');
  }else{
    f['font-style']='normal';
    $('#font_i').removeClass('select');
  }
  if(fonts.u){
    f['text-decoration']='underline';
    $('#font_u').addClass('select');
    fontStyle[0].unshift('[u]');
    fontStyle[1].push('[/u]');
  }else{
    f['text-decoration']='none';
    $('#font_u').removeClass('select');
  
  $('#font_name option[value="'+fonts.name+'"]').attr('selected',true);
  $('#font_size option[value="'+fonts.size+'"]').attr('selected',true);
  
  $('a[color="'+fonts.color+'"]').addClass('select');
  }
  
  if(fonts.name){
    fontStyle[0].unshift('[name='+fonts.name+']');
    fontStyle[1].push('[/name]');
  }
  if(fonts.size){
    fontStyle[0].unshift('[size='+fonts.size+']');
    fontStyle[1].push('[/size]');
  }
  if(fonts.color){
    fontStyle[0].unshift('[color='+fonts.color+']');
    fontStyle[1].push('[/color]');
  }
  f['font-family']=fonts.name;
  f['font-size']=fonts.size+'px';
  f['color']=fonts.color;
  $('#input').css(f);
}
function setFont(type,value){
  if(type=='b')fonts.b=(fonts.b?false:true);
  if(type=='i')fonts.i=(fonts.i?false:true);
  if(type=='u')fonts.u=(fonts.u?false:true);
  if(type=='name')fonts.name=value;
  if(type=='size')fonts.size=value;
  if(type=='color')fonts.color=value;
  
  setcookie('fonts',[fonts.b?'1':'0',fonts.i?'1':'0',fonts.u?'1':'0',fonts.name,fonts.size,fonts.color].join('|'));
  
  setFontBox();
}
function Clear(){
  $('#showbox1').html('');
  ScrollControl('1');
  
  $('#showbox2').html('');
  ScrollControl('2');
}
function getInfoTable(uid){
  var u=usrs[uid];
  var s='<table border="0" width="300">';
  s+='<tr>';
  s+='<td width="50" height="20">昵称:</td><td>'+u.username+'</td>';
  s+='</tr>';
  s+='<tr>';
  s+='<td height="120">头像:</td><td><div style="height:120px;overflow:hidden"><img src="avatar.php?uid='+uid+'" onload="if(this.height>120)this.height=120" /></div></td>';
  s+='</tr>';
  var sex='保密';
  if(u.sex=='1'){
    sex='帅哥';
  }else if(u.sex=='2'){
    sex='美女';
  }
  s+='<tr>';
  s+='<td height="20">性别:</td><td>'+sex+'</td>';
  s+='</tr>';
  s+='<tr>';
  s+='<td height="20">签名:</td><td>'+u.sign+'</td>';
  s+='</tr>';
  
  var sf='';
  if(typeof u['master']!='undefined' && masterNames[u.master]){
    sf+='<img src="images/mj/'+u.master+','+(u.sex=='1'?'1':'0')+'.png" align="absmiddle" /> '+masterNames[u.master];
  }else{
    sf='无';
  }
  s+='<tr>';
  s+='<td height="20">身份:</td><td>'+sf+'</td>';
  s+='</tr>';
  s+='<tr>';
  s+='<td height="20">鲜花:</td><td><img src="images/flower2.gif" align="absmiddle" /> '+u.flower+'</td>';
  s+='</tr>';
  s+='<tr>';
  s+='<td height="20">金币:</td><td><img src="images/money.gif" align="absmiddle" /> '+u.credit+'</td>';
  s+='</tr>';
  s+='<tr>';
  s+='<td height="20">经验:</td><td><img src="images/exp.gif" align="absmiddle" /> '+u.exp+'</td>';
  s+='</tr>';
  s+='<tr>';
  s+='<td height="20">等级:</td><td>'+u.level+'</td>';
  s+='</tr>';
  s+='</table>';
  s=s.replace(/undefined/g,'0');
  return s;
}