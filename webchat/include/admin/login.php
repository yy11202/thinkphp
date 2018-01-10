<?php $cookie=_cookie('oauth_temp');
if($cookie){
  list($oauth_type,$oauth_id,$oauth_name)=explode("\t",StrCode($cookie,"DECODE"));
}
if($_POST){
  $loginEmail=GP('loginEmail','P');
  $password=$loginPw=GP('password','P');
  (!$loginEmail || !$password) &&  html()->showmessage('用户名或密码不能为空');
  $goto=GP('goto','P');
  $iskeepalive=GP('iskeepalive','P');
  if(defined('UC_API')){
  	include_once ONEZ_ROOT.'uc_client/client.php';
	  $api = uc_user_login($loginEmail,$password,0);
	  list($uid,$username,$password,$email)=$api;
	  if($uid<0){
	    if($uid==-1){
	      html()->showmessage('用户不存在，或者被删除');
	    }elseif($uid==-2){
	      html()->showmessage('用户名或密码不正确');
	    }elseif($uid==-3){
	      html()->showmessage('安全提问错');
	    }
	    html()->showmessage('未知错误');
	  }
	  $T=D()->one('users','*',"uid='$uid'");
	  if(!$T){
	  	D()->rows('users',"username='$username'")>0 && $username.=uniqid();
	  	$T=array(
	      'uid'=>$uid,
	      'username'=>$username,
	      'loginEmail'=>$email,
	      'password'=>md5($loginPw),
	      'infotime'=>time(),
	      'infoip'=>onlineip(),
	      'thistime'=>time(),
	      'thisip'=>onlineip(),
	      'logincount'=>0,
	    );
	    D()->insert('users',$T);
	  }
  }else{
  	$password=md5($password);
	  $T=D()->one('users','*',"(loginEmail='$loginEmail' or username='$loginEmail') and password='$password'");
	  //!$T && html()->showmessage('用户名或密码不正确');
	  #尝试旧版
	  if(!$T){
      $add=false;
       $T=D()->one('renqiu','*',"nickname='$loginEmail'");
       if($T){
          if($T['pass']!=$loginPw){
            html()->showmessage('用户名或密码不正确');
          }else{
            $T=D()->one('users','*',"rqid='$T[uid]'");
          }
       }else{
        $result=addRenqiu($loginEmail);
        if($result!='Y'){
          html()->showmessage($result);
        }
      }
	  }
	  
	  #####################
  }
  $A=array(
    'lasttime'=>$T['thistime'],
    'thistime'=>time(),
    'lastip'=>$T['thisip'],
    'thisip'=>onlineip(),
    'logincount'=>$T['logincount']+1,
  );
  if($oauth_type && $oauth_id){
    $A['ufrom']=$oauth_type;
    $A['oauth']=$oauth_id;
  }
  _cookie('oauth_temp','del');
  $uid=$T['uid'];
  D()->update('users',$A,"uid='$T[uid]'");
  if(date('Ymd',$T['thistime'])!=date('Ymd',time())){
    addcharge($uid,$option['login_credit'],'登录赠送金币');
  }
  $cookie=StrCode("$T[uid]","ENCODE");
  _cookie('users',$cookie,$iskeepalive ? 86400*7 : 0);
  checkLevel($uid);
  html()->showmessage('登录成功',$goto ? $goto : 'index.php','right');
  exit();
}
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>登录</title>
<meta name="Keywords" content="">
<meta name="Description" content="">
<link href="<?php echo html()->assign('adminurl') ?>/images/login.css" rel="stylesheet" type="text/css">
<script src="<?php echo html()->assign('homepage') ?>/js/jquery-1.6.2.min.js" type="text/javascript"></script>
</head>
<body>
<div class="header">
  <h1 class="logo"><a href="index.php">网站首页</a></h1>
  <span>如果您还没有帐号，可以<a href="<?php echo html()->assign('url') ?>?f=register" class="reg-btn">立即注册</a></span> </div>
<!--end header-->
<div id="loginWrap" class="login wrap">
  <div id="modLoginWrap" class="mod-qiuser-pop">
    <form id="loginForm" method="post" action="<?php echo html()->assign('self') ?>">
      <dl class="login-wrap">
        <dt><span id="loginTitle">
        <?php if($oauth_type)echo'<font color="red">您首次通过开放平台['.$oauth_type.']登录，请登录本站账号进行绑定</font>'?>
        </span></dt>
        <dd>
          <div class="clearfix login-item">
            <label for="loginEmail">帐号</label>
            <span class="input-bg">
            <input placeholder="请输入您的用户名或登录邮箱" type="text" value="<?php echo $oauth_name?>" tabindex="1" id="loginEmail" name="loginEmail" autocomplete="off" maxlength="100" class="ipt tipinput1">
            </span><b class="tips-wrong icon-loginEmail"></b></div>
        </dd>
        <dd>
          <div class="clearfix login-item">
            <label for="lpassword">密码</label>
            <span class="input-bg">
            <input placeholder="请输入您的密码" type="password" tabindex="2" id="lpassword" name="password" maxlength="20" autocomplete="off" class="ipt tipinput1">
            </span><b class="tips-wrong icon-lpassword"></b></div>
        </dd>
        <dd class="find">
          <label for="iskeepalive">
            <input type="checkbox" tabindex="4" name="iskeepalive" id="iskeepalive" checked="checked">
            下次自动登录</label>
          <a href="<?php echo html()->assign('url') ?>?f=findpwd" style="font-size:12px" id="findPwd">忘记密码？</a></dd>
        <dd class="rem" id="phraseLoginwarp" style="display:none">
          <label for="phraseLogin">验证码</label>
          <span class="verify-code">
          <input type="text" tabindex="3" maxlength="4" id="phraseLogin" name="phrase" class="ipt1 tipinput1 verify-code" autocomplete="off">
          </span>
          <p class="yz"><img width="99" height="33" style="cursor: pointer;" id="lwm"><b class="tips-wrong  icon-phraseLogin"></b><br>
            <a href="#nogo" id="refreshCaptchaLogin">看不清？换一张</a></p>
          <p><span id="tips-phraseLogin" class="login-tips tips-phraseLogin">请输入图中的字母或数字，不区分大小写</span></p>
        </dd>
        <dd class="submit">
          <input type="submit" id="loginSubmit" value="" class="btn-login">
        </dd>
        <dd class="other"><span>用其他帐号登录：</span>
        <?php if($option['oauth_qq_appid']){?><a href="<?php echo html()->assign('url') ?>?f=oauth&c=QQ" target="_blank" class="" title="腾讯QQ登录"><img src="images/qq_login.gif" align="absmiddle" /></a><?php }?>
        <?php if($option['oauth_sina_appid']){?><a href="<?php echo html()->assign('url') ?>?f=oauth&c=Sina" target="_blank" class="loginbtn_sina" title="新浪微博登录"></a><?php }?>
        <?php if($option['oauth_renren_appid']){?><a href="<?php echo html()->assign('url') ?>?f=oauth&c=RenRen" target="_blank" class="loginbtn_rr" title="人人登录"></a><?php }?>
        </dd>
        <dd>
          <div id="error_tips" class="login-error"></div>
          <input id="goto" type="hidden" value="<?php echo $_GET['goto']?>" name="goto">
        </dd>
      </dl>
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
formInit('login');
</script>
</body>
</html>