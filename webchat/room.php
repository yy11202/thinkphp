<?php
include_once('check.php');
$rid=GP('rid');
$action=GP('action');
$T=D()->one('rooms','*',"rid='$rid'");
!$T && exit('房间不存在');
foreach($T as $k=>$v)$$k=$v;
$pass=trim($pass);
if($action=='checkpwd'){
  $pwd=trim(GP('pass'));
  if($pwd==$pass){
    $_SESSION["pass,$rid"]=$pwd;
    exit('Y');
  }else{
    exit('密码不正确');
  }
}
if($pass && $_SESSION["pass,$rid"]!=$pass){
  include_once('roompass.php');
  exit();
}

$words=array();
$T=D()->record('words','*',"");
foreach($T as $rs){
  !$words[$rs['type']] && $words[$rs['type']]=array();
  $words[$rs['type']][]=$rs['word'];
}
$words=str_replace("\n",' ',onez_json($words));
#清除状态
D()->delete("status","uid='$uid' and exptime>-1 and exptime<$time");
include_once('onezdata/effect.inc.php');
include_once('onezdata/emote1.inc.php');
include_once('onezdata/emote2.inc.php');
include_once('onezdata/tip.inc.php');
$emote=array_intersect($emote1,$emote2);
$ismaster=ismaster($uid,$rid);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $roomname?></title>
<link href="images/room.css?t=<?php echo filemtime(ONEZ_ROOT.'/images/room.css')?>" rel="stylesheet" type="text/css" />
<link href="images/boxy/boxy.css" rel="stylesheet" type="text/css" />
<script src="js/jquery-1.6.2.min.js" type="text/javascript"></script>
<script src="js/jquery.livequery.js" type="text/javascript"></script>
<script src="js/common.js" type="text/javascript"></script>
<script src="js/chat.js" type="text/javascript"></script>
<script src="js/swfobject.js" type="text/javascript"></script>
<script src="js/limit.js" type="text/javascript"></script>
<script type="text/javascript" src="js/jquery.boxy.js"></script>
<script type="text/javascript" src="js/title.js"></script>
<script type="text/javascript" src="onezdata/effect.inc.php?js=1"></script>
<script type="text/javascript" src="onezdata/tip.inc.php?js=1"></script>
<script type="text/javascript">
<?php if($videonum==1){?>
var _flash='res/OnezChat1.swf?t=<?php echo filemtime(ONEZ_ROOT.'/res/OnezChat1.swf')?>';
var _flashwidth='240';
var _flashheight='180';
<?php }elseif($videonum==2){?>
var _flash='res/OnezChat2.swf?t=<?php echo filemtime(ONEZ_ROOT.'/res/OnezChat2.swf')?>';
var _flashwidth='240';
var _flashheight='362';
<?php }else{?>
var _flash='res/OnezChat0.swf?t=<?php echo filemtime(ONEZ_ROOT.'/res/OnezChat0.swf')?>';
var _flashwidth='1';
var _flashheight='1';
<?php }?>
var _flash2='res/OnezChatSite.swf?t=<?php echo filemtime(ONEZ_ROOT.'/res/OnezChatSite.swf')?>';
var _flashvars={
  bufferTime:'1',
  quality:'100',
  fps:'24',
  rid:'<?php echo $rid?>',
  uid:'<?php echo $uid?>',
  username:'<?php echo $username?>'
};
var _rid='<?php echo $rid?>';
var _uid='<?php echo $uid?>';
var _username='<?php echo $username?>';
var _roomname='<?php echo $roomname?>';
var words=<?php echo $words?>;
</script>
<!--[if IE 6]>
<script type="text/javascript" src="js/pngFix.js" ></script>
<script type="text/javascript">
DD_belatedPNG.fix('*');
</script>
<![endif]-->
</head>
<body scroll="no">
<table id="mainBox">
  <tr>
<?php if($videonum>0){?>
    <td id="boxLeft">
      <div id="videoBox"><a href="http://get.adobe.com/cn/flashplayer/" target="_blank">您的Flash插件版本太低，点击这里升级！</a></div>
      <div id="micBtn">
        <button id="addmic" class="btn_normal" onclick="Request()" title="加入麦序">排麦</button>
        <?php if($ismaster){?>
        <button id="addmic2" class="btn_normal" onclick="Request2()" title="加入麦序并排到最前面">抢麦</button>
        <?php }?>
        <button id="delmic" class="btn_normal" style="display:none" onclick="Cancel()">放麦</button>
        <button id="micsetting" class="btn_normal" onclick="VideoSetting()" title="语音/视频设置">设置</button>
      </div>
      <div id="micTi">
        <span id="mic_ti">麦序</span>
        <span id="micsec"></span>
      </div>
      <ul id="micList" class="usrList scroll"></ul>
      <div id="giftBox">领取金币</div>
    </td>
<?php }?>
    <td id="boxCenter" width="*">
      <div id="adtop"><?php showad('room_top')?></div>
      <table id="subject" width="100%" background="images/4.jpg">
        <tr>
          <td><object codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,29,0" classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" width="220" align="left" height="58"><param name="movie" value="images/4.swf"><param name="quality" value="high"><param name="wmode" value="transparent"> 
				<embed src="images/4.swf" quality="high" wmode="transparent" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" width="220" height="58" value="transparent"></object></td>
          <td><marquee style="width:580px;HEIGHT: 60px" id="o_marquee" scrollamount="6" direction="left" onmouseover=stop() onmouseout=start()>
<p ><div style="color:yellow;font-family: Microsoft yahei;font-size: 26px;line-height:58px;height:58px;display:block;overflow:hidden;cursor:default;white-space:nowrap;" id="subject_span" oneztitle="<?php echo $subject?>"><?php echo $subject?></div></p >
</marquee ></td>
        </tr>
      </table>
      <div id="showbox1" class="scroll showbox"><?php showad('room_showbox')?></div>
      <div id="showbox2" class="scroll showbox"></div>
      <div id="toolbars">
        <select id="tousr"></select>
        <input id="qqh" type="checkbox" />
        <label for="qqh">悄悄话</label>
        <a href="javascript:Font()" title="字体设置"><img src="images/ico_font.gif" />字体</a>
        <a href="javascript:Emote()" title="选择动画表情"><img src="images/ico_emote.gif" />表情</a>
        <a href="javascript:Gift()" title="赠送礼物"><img src="images/ico_gift.gif" />礼物</a>
        <a href="javascript:Good()" title="喝彩"><img src="images/ico_good.gif" />喝彩</a>
        <a href="javascript:Seal()" title="给别人盖个戳"><img src="images/ico_seal.gif" />印章</a>
        <a href="javascript:Clear()" title="清屏"><img src="images/ico_clear.gif" />清屏</a>
<div id="toolbars2">
        <select id="menus" onchange="SelMenu()"></select>
        <select id="htmls" onchange="SelEffect()">
<option value="">※HTML特效※</option>
<?php foreach($effects as $k=>$v){
if(!$ismaster && $U['level']<$v[1])continue;?>
<option value="<?php echo $v[0]?>"><?php echo $v[0]?></option>
<?php }?>
        </select>
        <select id="emotes1" onchange="SelEmotes1()">
<option value="">聊天动作</option>
<?php foreach($emote as $k=>$v){
if(strpos($v[1],'<img')!==false)continue;?>
<option value="<?php echo $k?>"><?php echo $v[0]?></option>
<?php }?>
        </select>
        <select id="emotes2" onchange="SelEmotes2()">
<option value="">※图片趣语※</option>
<?php foreach($emote as $k=>$v){
if(strpos($v[1],'<img')===false)continue;?>
<option value="<?php echo $k?>"><?php echo $v[0]?></option>
<?php }?>
        </select>
        <a href="javascript:Notice()" id="broadcast" title="发布广播"><img src="images/ico_broadcast.gif" />广播</a>
</div>
      </div>
      <table id="inputbtns">
        <tr>
          <td id="inputbox"><textarea id="input" onkeydown="input_onkeydown(event)"></textarea></td>
          <td id="inputbtns"><button id="btnsend" class="btn_normal" onclick="Send()">发送</button></td>
        </tr>
      </table>
    </td>
    <td id="boxRight">
      <div id="infoBox">
        <img src="avatar.php?uid=<?php echo $uid?>" avatar="<?php echo $uid?>" title="点击更换头像" onclick="Face()" class="avatar" />
        <span id="myname"><?php echo $username?>(<?php echo $uid?>)</span>
        <span id="infos">
          <span id="infos_money"><?php echo $credit?></span>
          <span id="infos_flower"><?php echo $flower?></span>
          <span id="infos_level"><?php echo getLevel($level)?></span>
        </span>
      </div>
<?php if(!$videonum){?>
      <div id="videoBox"><a href="http://get.adobe.com/cn/flashplayer/" target="_blank">您的Flash插件版本太低，点击这里升级！</a></div>
<?php }?>
<input type="text" id="ufilter" class="px" value="搜索用户" onkeyup="searchUsr(event)" />
<ul id="usrtype">
  <li id="type_all" class="select">全部/<span id="num_all">0</span></li>
  <li id="type_boy">男士/<span id="num_boy">0</span></li>
  <li id="type_girl">女士/<span id="num_girl">0</span></li>
  <li id="type_master">管理/<span id="num_master">0</span></li>
</ul>
      <div id="usrbox" class="">
        <ul id="usrList" class="usrList scroll"></ul>
      </div>
      <div id="showbox3" class="scroll">

<?php
$broadcast=D()->record('broadcast','*',"1 order by id desc",4);
array_reverse($broadcast);
foreach($broadcast as $rs){
$rs['message']=str_replace("\n",' ',$rs['message']);
?>
<div class="im_usr">※<?php echo $rs['fromname']?></div>
<div class="im_msg"><script>document.writeln(ubbtohtml(<?php echo var_export($rs['message'],true)?>));</script></div>
<?php }?>
      </div>
      <div id="broadcastBox"></div>
    </td>
  </tr>
</table>
</body>
</html>