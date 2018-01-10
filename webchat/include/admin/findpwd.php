<?php $step=GP('step');
if($step=='2'){
	$option['mail']!='Y' && html()->showmessage('网站已暂停邮件取回密码的功能，请与管理员联系');
  $loginEmail=GP('loginEmail','P');
  $T=D()->one('users','*',"loginEmail='$loginEmail' or username='$loginEmail'");
  !$T && html()->showmessage('登录邮箱/用户名不存在');
  $email=$T['loginEmail']?$T['loginEmail']:$T['email'];
  !$email && html()->showmessage('用户没有填写有效的邮箱，请联系客服取回');
  
  $key=StrCode("$T[uid]\t".time(),"ENCODE");
  D()->update('users',array('rndkey'=>$key),"uid='$T[uid]'");
  
  $url=html()->assign('url').'?f=findpwd&step=3&key='.$key;
  $option['mail_findpwd_co']=str_replace('#uid#',$T['uid'],$option['mail_findpwd_co']);
  $option['mail_findpwd_co']=str_replace('#nickname#',$T['nickname'],$option['mail_findpwd_co']);
  $option['mail_findpwd_co']=str_replace('#username#',$T['username'],$option['mail_findpwd_co']);
  $option['mail_findpwd_co']=str_replace('#url#',"<a href='$url' target='_blank'>$url</a>",$option['mail_findpwd_co']);
  mailTo($email,$option['mail_findpwd_ti'],$option['mail_findpwd_co']);
}elseif($step=='3'){
  $key=GP('key','G');
  $T=D()->one('users','*',"rndkey='$key'");
  !$T && html()->showmessage('链接已失效',html()->assign('url').'?f=login');
  list($uid,$time)=explode("\t",StrCode($key,"DECODE"));
  $uid!=$T['uid'] && html()->showmessage('非法链接',html()->assign('url').'?f=login');
  $time<time()-1800 && html()->showmessage('链接已超时',html()->assign('url').'?f=login');
  
}elseif($step=='4'){
  $key=GP('key','P');
  $password=GP('password','P');
  $rePassword=GP('rePassword','P');
  (strlen($password)<6 || strlen($password)>20) && html()->showmessage('新密码长度必须在6-20之间');
  $password!=$rePassword && html()->showmessage('两次密码不一致');
  $T=D()->one('users','*',"rndkey='$key'");
  !$T && html()->showmessage('链接已失效',html()->assign('url').'?f=login');
  list($uid,$time)=explode("\t",StrCode($key,"DECODE"));
  $uid!=$T['uid'] && html()->showmessage('非法链接',html()->assign('url').'?f=login');
  $time<time()-1800 && html()->showmessage('链接已超时',html()->assign('url').'?f=login');
  D()->update('users',array('rndkey'=>'','password'=>md5($password)),"uid='$T[uid]'");
  html()->showmessage('密码修改成功，请重新登录！',html()->assign('url').'?f=login','right');
}
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>找回密码</title>
<meta name="Keywords" content="">
<meta name="Description" content="">
<link href="<?php echo html()->assign('adminurl') ?>/images/login.css" rel="stylesheet" type="text/css">
</head>
<body>
<div class="header">
  <h1 class="logo"><a href="index.php">网站首页</a></h1>
  <span>
    <a href="<?php echo html()->assign('url') ?>?f=login" class="log-btn">登录</a>
    <a href="<?php echo html()->assign('url') ?>?f=register" class="reg-btn">注册</a>
  </span>
 </div>
<!--end header-->
<div id="loginWrap" class="login wrap">
  <div id="modLoginWrap" class="mod-qiuser-pop">
    <form id="loginForm" method="post" action="<?php echo html()->assign('self') ?>">
      <dl class="login-wrap">
        <dt><span id="loginTitle"></span></dt>
        <?php if($step=='2'){?>
          <meta http-equiv="refresh" content="5;url=<?php echo html()->assign('url') ?>?f=login">
          <div class="clearfix login-item" style="font-size:14px;color:#000"><br /><br />
            密码找回链接发送成功，请在30分钟内查收！
            <a href="<?php echo html()->assign('url') ?>?f=login">5秒后返回</a>
          </div>
        <?php }elseif($step=='3'){?>
        <dd>
          <div class="clearfix login-item">
            <label for="loginEmail">新密码</label>
            <span class="input-bg">
            <input placeholder="新登录密码" type="password" tabindex="1" id="password" name="password" autocomplete="off" maxlength="20" class="ipt tipinput1">
            </span><b class="tips-wrong icon-loginEmail"></b></div>
        </dd>
        <dd>
          <div class="clearfix login-item">
            <label for="loginEmail">再填一次</label>
            <span class="input-bg">
            <input placeholder="确认新登录密码" type="password" tabindex="2" id="rePassword" name="rePassword" autocomplete="off" maxlength="20" class="ipt tipinput1">
            </span><b class="tips-wrong icon-loginEmail"></b></div>
        </dd>
        <dd class="submit">
          <input type="hidden" value="<?php echo $key?>" name="key">
          <input type="hidden" value="4" name="step">
          <input type="submit" id="loginSubmit" value="立即修改" class="btn">
        </dd>
        <?php }else{?>
        <dd>
          <div class="clearfix login-item">
            请填写您登录网站所使用的帐号（登录邮箱或用户名）
          </div>
        </dd>
        <dd>
          <div class="clearfix login-item">
            <label for="loginEmail">帐号</label>
            <span class="input-bg">
            <input placeholder="登录邮箱/用户名" type="text" tabindex="1" id="loginEmail" name="loginEmail" autocomplete="off" maxlength="100" class="ipt tipinput1">
            </span><b class="tips-wrong icon-loginEmail"></b></div>
        </dd>
        <dd>
          <div class="clearfix login-item">
            如您忘记登录邮箱与用户名，请联系客服
          </div>
        </dd>
        <dd class="submit">
          <input type="hidden" value="2" name="step" class="btn">
          <input type="submit" id="loginSubmit" value="下一步" class="btn">
        </dd>
        <?php }?>
      </dl>
    </form>
    <div id="sug_css" class="sug_css_wpr" style="display: none; "></div>
  </div>
</div>
<div class="footer"> Copyright &copy; 2002-2012 ONEZ.CN All Rights Reserved 佳蓝在线 </div>
<!--end footer-->
</body>
</html>