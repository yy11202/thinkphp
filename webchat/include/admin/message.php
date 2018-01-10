<?php !defined('IN_ONEZ') && exit('Access Denied');
!$text && $text=GP('text');
!$url && $url=$goto=GP('url');
!$class && $class='info';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>提示信息</title>
<link rel="stylesheet" type="text/css" href="<?php echo html()->assign('adminurl') ?>/images/common.css" />
<?php if($goto){?>
<meta http-equiv="refresh" content="3;url=<?php echo $goto?>">
<?php }?>
</head>
<body>
<div class="wrap">
  <div id="header">
    <h1><a href="<?php echo html()->assign('baseurl') ?>"><span>管理首页</span></a></h1>
    <?php if(!html()->assign('uid')){?>
    <div class="login"> 
      <a href="<?php echo html()->assign('url') ?>?f=login">登录</a>
     </div>
    <?php }?>
    <div id="menu"> <a href="http://t.qq.com/myonez" class="weibo" target="_blank">官方微博</a> </div>
  </div>
  <div class="main">
    <div class="path"> <a href="<?php echo html()->assign('url') ?>">管理首页</a> <em>&rsaquo;</em> <span>提示信息</span> </div>
    <div class="alert">
      <div class="<?php echo $class?>">
        <p><?php echo $text?></p>
        <p><a href="<?php echo $url?>">如果您的浏览器没有自动跳转，请点击这里。</a></p>
      </div>
    </div>
  </div>
<?php html()->footer()?>