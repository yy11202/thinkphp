<?php define('CP_MAIN',true);
include_once('include/common.inc.php');
function _url($url){
	return html()->url($url);
}
if($uid){
	$U=D()->one('users','*',"uid='$uid'");
	if(!$U){
		_cookie('users','del');
		html()->showmessage('登录超时，请重新登录',$PHP_SELF,'error');
	}
	!$U['nickname'] && $U['nickname']=$U['username'];
	!$U['nickname'] && $U['nickname']=$U['loginEmail'];
}
if($U['g']==28){//管理员
	$menus=<<<ONEZ
<menu token="setting" name="聊天设置">
  <menu token="setting_credit" name="金币设置"></menu>
  <menu token="setting_levels" name="等级设置"></menu>
  <menu token="words&amp;type=in" name="进入提示语"></menu>
  <menu token="words&amp;type=out" name="离开提示语"></menu>
  <menu token="words&amp;type=good" name="喝彩"></menu>
</menu>
<menu token="room" name="房间设置">
  <menu token="room_sort" name="房间类型"></menu>
  <menu token="rooms" name="房间列表"></menu>
</menu>
<menu token="users" name="用户设置">
  <menu token="yuetuan" name="音悦乐团"></menu>
</menu>
<menu token="shop" name="商城管理">
  <menu token="gift" name="道具管理"></menu>
  <menu token="seal" name="印章管理"></menu>
  <menu token="face" name="头像管理"></menu>
</menu>
<menu token="article" name="内容管理">
  <menu token="news" name="公告管理"></menu>
</menu>
ONEZ;
$menus.='<menu token="ads" name="广告设置">';
foreach($Ads as $k=>$v){
	$menus.=<<<ONEZ
  <menu token="ads&amp;token=$k" name="$v"></menu>
ONEZ;
}
$menus.='</menu>';
}else{
	$menus=<<<ONEZ
<menu token="main" name="网站概况" />
ONEZ;

}
if($U['g']==3){//超级管理
	$menus.=<<<ONEZ
<menu token="room" name="房间设置">
  <menu token="room_sort" name="房间类型"></menu>
  <menu token="rooms" name="房间列表"></menu>
</menu>
<menu token="users" name="用户设置">
  <menu token="yuetuan" name="音悦乐团"></menu>
</menu>
<menu token="article" name="内容管理">
  <menu token="news" name="公告管理"></menu>
</menu>
ONEZ;
$menus.='<menu token="ads" name="广告设置">';
foreach($Ads as $k=>$v){
	$menus.=<<<ONEZ
  <menu token="ads&amp;token=$k" name="$v"></menu>
ONEZ;
}
$menus.='</menu>';
ONEZ;
}
	$menus.=<<<ONEZ
<menu token="user" name="账户管理">
  <menu token="users_modify" name="修改资料"></menu>
  <menu token="charge" name="在线充值"></menu>
  <menu token="chargelog" name="充值记录"></menu>
  <menu token="moneylog" name="交易明细"></menu>
  <menu token="mygift" name="我收到的礼物"></menu>
</menu>
ONEZ;
html()->init('admin');

html()->display();