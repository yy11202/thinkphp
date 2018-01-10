<?php
!defined('IN_ONEZ') && exit('Access Denied');
if($uid){
  $U=D()->one('users','*',"uid='$uid'");
  foreach($U as $k=>$v)$$k=$v;
  $nickname && $username=$nickname;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $title ? $title.' - ' : ''?>老地方聊天室</title>
<link href="images/style.css" rel="stylesheet" type="text/css" />
<script src="js/common.js" type="text/javascript"></script>
</head>

<body>
<div class="head">
  <div class="center">
    <div class="logo"></div>
    <div class="login">
<?php if($uid){?>
  <table width="250" border="0" cellspacing="3" cellpadding="0" style="float:right">
        <tr>
          <td><img src="avatar.php?uid=<?php echo $uid?>" height="16" class="avatar" align="absmiddle" /> <?php echo $username?>，欢迎您！<a href="u.php" style="color:red">管理中心</a> <a href="u.php?f=logout">注销退出</a></td>
        </tr>
      </table>
    <?}else{?>
    <a href="u.php?f=oauth&c=QQ" target="_blank" class="" title="腾讯QQ登录"><img src="images/qq_login.gif" align="absmiddle" /></a>
    <a href="u.php?f=register">注册</a> |  <a href="u.php?f=charge"><font color="red"><u>充值</u></font></a>  |  <a href="u.php?f=findpwd">忘记密码</a>
<form id="login" name="form1" method="post" action="u.php?f=login">
  <table width="250" border="0" cellspacing="3" cellpadding="0" style="float:right">
        <tr>
         
          <td scope="col" width="50">账号 </td>
          <td scope="col" width="120">
            <input type="text" name="loginEmail" id="name" tabindex="1"  class="textarea"/></td>
          <td scope="col" width="80">
            <input type="checkbox" name="iskeepalive" id="aotologin" />自动登录</td>
          
        </tr>
        <tr>
          <td>密码 </td>
          <td><input type="password" name="password" id="pass" tabindex="2" class="textarea"/></td>
          <td width="80"><input type="image" src="images/login.gif" tabindex="3" width="75" height="25"/></td>
        </tr>
      </table>
    </form>
    <?}?>
    </div>
  </div>
</div>

<div class="menu">
  <div class="center"><ul>
  	<li><a href="index.php">首页</a></li>
    <li><a href="hall.php">聊天大厅</a></li>
    <li><a href="prop.php">道具商城</a></li>
    <li><a href="news.php">活动中心</a></li>
    <li><a href="top.php">排行榜</a></li>
    <li><a href="charge.php">充值</a></li>
    <li><a href="http://www.tt365.net.cn" target="_blank">论坛</a></li>
  </ul></div>
</div>
