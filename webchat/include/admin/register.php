<?php $action=GP('action');
$cookie=_cookie('oauth_temp');
if($cookie){
  list($oauth_type,$oauth_id,$oauth_name)=explode("\t",StrCode($cookie,"DECODE"));
}
switch($action){
  case 'checkName':
    $username=GP('username','P');
    D()->rows('users',"username='$username'")>0 && exit('用户名已存在');
    exit('Y');
    break;
  case 'checkEmail':
    $loginEmail=GP('loginEmail','P');
    D()->rows('users',"loginEmail='$loginEmail'")>0 && exit('登录邮箱已存在');
    exit('Y');
    break;
  case 'save':
    unset($uid);
    $username=GP('username','P');
    $loginEmail=GP('loginEmail','P');
    $password=GP('password','P');
    $phrase=GP('phrase','P');
    $sex=(int)GP('sex','P');
    $goto=GP('goto','P');
    $_SESSION['randcode']!=$phrase && ero('验证码不正确',4);
    D()->rows('users',"username='$username'")>0 && ero('用户名已存在',4);
    D()->rows('users',"loginEmail='$loginEmail'")>0 && ero('登录邮箱已存在',4);
	  if(defined('UC_API')){
	  	include_once ONEZ_ROOT.'uc_client/client.php';
		  $uid = uc_user_register($username, $password, $loginEmail);
		  if($uid <= 0) {
		    if($uid == -1) {
		      ero('用户名不合法');
		    } elseif($uid == -2) {
		      ero('包含要允许注册的词语');
		    } elseif($uid == -3) {
		      ero('用户名已经存在');
		    } elseif($uid == -4) {
		      ero('Email 格式有误');
		    } elseif($uid == -5) {
		      ero('Email 不允许注册');
		    } elseif($uid == -6) {
		      ero('该 Email 已经被注册');
		    } else {
		      ero('未知错误');
		    }
		    exit();
		  }
	  }
	  $T=array(
      'username'=>$username,
      'loginEmail'=>$loginEmail,
      'sex'=>$sex,
      'password'=>md5($password),
      'infotime'=>time(),
      'infoip'=>onlineip(),
      'thistime'=>time(),
      'thisip'=>onlineip(),
      'logincount'=>1,
    );
    if($oauth_type && $oauth_id){
      $T['ufrom']=$oauth_type;
      $T['oauth']=$oauth_id;
    }
    _cookie('oauth_temp','del');
    $uid && $T['uid']=$uid;
    $uid=D()->insert('users',$T);
    $cookie=StrCode("$uid","ENCODE");
    _cookie('users',$cookie);
    addcharge($uid,$option['reg_credit'],'注册赠送金币');
    if($option['mail_register']=='Y'){
    	$option['mail_register_co']=str_replace('#uid#',$uid,$option['mail_register_co']);
    	$option['mail_register_co']=str_replace('#email#',$loginEmail,$option['mail_register_co']);
		  $option['mail_register_co']=str_replace('#username#',$username,$option['mail_register_co']);
		  mailTo($email,$option['mail_register_ti'],$option['mail_register_co']);
    }
    html()->showmessage('注册成功',$goto ? $goto : 'index.php','right');
    exit();
    break;
}
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>新用户注册</title>
<meta name="Keywords" content="">
<meta name="Description" content="">
<link href="<?php echo html()->assign('adminurl') ?>/images/login.css" rel="stylesheet" type="text/css">
<script src="<?php echo html()->assign('homepage') ?>/js/jquery-1.6.2.min.js" type="text/javascript"></script>
</head>
<body>
<div class="header">
  <h1 class="logo"><a href="index.php">网站首页</a></h1>
  <span>如果您已有帐号，可以<a href="<?php echo html()->assign('url') ?>?f=login" class="log-btn">立即登录</a></span> </div>
<!--end header-->
<div id="regWrap" class="wrap">
  <div id="modRegWrap" class="mod-qiuser-pop">
    <form id="regForm" method="post" name="regForm" onsubmit="return CheckSubmit()" action="<?php echo html()->assign('self') ?>&action=save">
      <dl class="reg-wrap">
        <dt><span id="regTitle"></span></dt>
        <dd>
          <div class="clearfix reg-item">
            <label for="username">用户名</label>
            <span class="input-bg">
            <input type="text" id="username" name="username" value="<?php echo $oauth_name?>" minlength="2" maxlength="20" autocomplete="off" class="ipt tipinput" tabindex="1">
            </span><b class="tips-wrong  icon-username" style="display: none; "></b><a href="http://reg.email.163.com/mailregAll/reg0.jsp " style="position: absolute;width: 130px;top: 64px;left: 205px;text-decoration: underline;" target="_blank"></a></div>
          <span id="tips-username" class="login-tips tips-username">2-20个字符，可以是中文</span></dd>
        <dd>
          <div class="clearfix reg-item">
            <label for="loginEmail">邮箱</label>
            <span class="input-bg">
            <input type="text" id="loginEmail" name="loginEmail" maxlength="100" autocomplete="off" class="ipt tipinput" tabindex="2">
            </span><b class="tips-wrong  icon-loginEmail" style="display: none; "></b><a href="http://reg.email.163.com/mailregAll/reg0.jsp " style="position: absolute;width: 130px;top: 128px;left: 205px;text-decoration: underline;" target="_blank">免费注册163超大邮箱</a></div>
          <span id="tips-loginEmail" class="login-tips tips-loginEmail">请输入有效的邮箱地址</span></dd>
        <dd>
          <div class="clearfix reg-item">
            <label for="password">密码</label>
            <span class="input-bg">
            <input type="password" minlength="6" maxlength="20" id="password" name="password" autocomplete="off" class="ipt tipinput" tabindex="3">
            </span><b class="tips-wrong  icon-password"></b></div>
          <span id="tips-password" class="login-tips tips-password">6-20个字符，不能为汉字（区分大小写）</span></dd>
        <dd>
          <div class="clearfix reg-item">
            <label for="rePassword">确认密码</label>
            <span class="input-bg">
            <input type="password" minlength="6" maxlength="20" id="rePassword" autocomplete="off" class="ipt tipinput" tabindex="4">
            </span><b class="tips-wrong icon-rePassword "></b></div>
          <span id="tips-rePassword" class="login-tips tips-rePassword">请再输入一次上面输入的密码</span></dd>
        <dd style="height:30px;overflow:hidden">
          <div class="clearfix reg-item">
            <label for="Sex" style="line-height:22px;overflow:hidden">性别</label>
            <span class="bg">
            <input type="radio" id="sex1" name="sex" tabindex="5" value="1">帅哥
            <input type="radio" id="sex2" name="sex" tabindex="5" value="2">美女
            <input type="radio" id="sex0" name="sex" tabindex="5" value="0" checked>保密
            </span></div>
          </dd>
        <dd class="rem" id="phraseLi">
          <label for="phrase">验证码</label>
          <span class="verify-code">
          <input type="text" minlength="4" maxlength="4" id="phrase" name="phrase" class="ipt1 tipinput verify-code" autocomplete="off" tabindex="4">
          </span>
          <p class="yz"><img width="99" height="33" style="cursor: pointer;" id="wm" src="captcha.php"><b class="tips-wrong  icon-phrase"></b><br>
            <a href="#nogo" id="refreshCaptcha">看不清？换一张</a></p>
          <p><span id="tips-phrase" class="login-tips tips-phrase">请输入图中的字母或数字，不区分大小写</span></p>
        </dd>
        <dd class="submit">
          <input type="submit" id="regSubmitBtn" value="" class="btn-register">
        </dd>
      </dl>
      <input id="goto" type="hidden" value="<?php echo $_GET['goto']?>" name="goto">
    </form>
    <div id="sug_css" class="sug_css_wpr" style="display: none; "></div>
  </div>
</div>
<div class="footer"> Copyright &copy; 2002-2012 ONEZ.CN All Rights Reserved 佳蓝在线 </div>
<!--end footer-->
<script src="<?php echo html()->assign('homepage') ?>/js/jquery.livequery.js" type="text/javascript"></script>
<script src="<?php echo html()->assign('adminurl') ?>/js/login.js" type="text/javascript"></script>
<script type="text/javascript">
$('#refreshCaptcha,#wm').bind('click',function(){
  $('#wm').attr('src','captcha.php?t='+Math.random());
});
formInit('register');
</script>
</body>
</html>