<?php $smsNum=D()->rows('sms',"isread='0'");
if($smsNum){
	$smsNum=' <em id="unreadNumEm">('.$smsNum.')</em>';
}else{
	unset($smsNum);
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $title=html()->assign('title') ? $title : '管理中心'?></title>
<script src="<?php echo html()->assign('homepage') ?>/js/jquery-1.6.2.min.js" type="text/javascript"></script>
<script src="<?php echo html()->assign('homepage') ?>/js/jquery-ui.min.js" type="text/javascript"></script>
<script src="<?php echo html()->assign('homepage') ?>/js/editor/kindeditor.js" type="text/javascript"></script>
<script src="<?php echo html()->assign('homepage') ?>/js/editor/lang/zh_CN.js" type="text/javascript"></script>
<script src="<?php echo html()->assign('adminurl') ?>/js/common.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="<?php echo html()->assign('adminurl') ?>/images/common.css" />
</head>
<body>
<div id="appendParent"></div>
<div class="wrap">
  <div id="header">
    <h1><a href="index.php"><span>管理中心</span></a></h1>
    <div class="login"> 您好，<span id="navSiteMasterName"><?php echo html()->assign('info')->nickname ?></span> &nbsp;&nbsp;[ <a href="<?php echo html()->assign('url') ?>?f=logout">退出</a> ] </div>
    <div id="menu"> <a class="message" href="<?php echo html()->assign('url') ?>?f=sms">短消息<?php echo $smsNum?></a></div>
  </div>
      <?php if($showMenu){?>
  <div class="main mt_2">
    <table class="mainframe">
      <tr>
        <td class="side"><div class="bm">
            <ul id="leftFuncMenu" class="nav">
              <?php function keyZero(&$k){
								$k+=10;
							}
							foreach(html()->assign('menu') as $k1=>$m1){keyZero($k1);?>
              <?php if($m1['child']){?>
              <li id="li_<?php echo $k1?>" class="open_s">
								<a href="javascript:;" onclick="displayMenu(<?php echo $k1?>, 's');"><em></em><?php echo $m1['name']?></a>
                <ul>
                	<?php foreach($m1['child'] as $k2=>$m2){keyZero($k2);?>
              		<?php if($m2['child']){?>
                  <li id="li_<?php echo $k1?><?php echo $k2?>" class="close_t"><a href="javascript:;" onclick="displayMenu(<?php echo $k1?><?php echo $k2?>, 't');"><span><?php echo $m2['name']?></span></a>
                    <ul>
                    	<?php foreach($m2['child'] as $k3=>$m3){keyZero($k3);?>
                      <li<?php if(html()->assign('f')==$m3['token'])echo' class="a"'?>><a href="<?php echo html()->assign('url') ?>?f=<?php echo $m3['token']?>"><span><?php echo $m3['name']?></span></a></li>
                    	<?php }?>
                    </ul>
                  </li>
                	<?php }else{?>
                	<li id="li_<?php echo $k1?><?php echo $k2?>" <?php if(html()->assign('f')==$m2['token'])echo' class="a"'?>><a href="<?php echo html()->assign('url') ?>?f=<?php echo $m2['token']?>"><span><?php echo $m2['name']?></span></a> </li>
                  <?php }?>
                  <?php }?>
                </ul>
                
              </li>
              <?php }else{?>
              <li id="li_<?php echo $k1?>" class="open_h"> <a href="<?php echo html()->assign('url') ?>?f=<?php echo $m1['token']?>"><em></em>网站概况</a> </li>
              <?php }?>
              <?php }?>
            </ul>
          </div></td>
        <td class="content">
          <div class="bm_h cl">
            <div class="webset" style="display:">
              <label for="web">当前等级：</label>
              <span><?php echo html()->assign('grade') ?></span>
            </div>
            <div class="ann cl" id="scrollDiv"> <a href="<?php echo html()->assign('url') ?>?f=notice" class="more" title="更多">更多</a>
              <div class="scrollText">
                <ul>
                <?php $T=D()->record('notice','*',"1 order by nid desc",1);
                foreach($T as $rs){
                ?>
                  <li>
                    <p><a href="<?php echo html()->assign('url') ?>?f=notice_view&nid=<?php echo $rs['nid']?>" title="<?php echo $rs['caption']?>"><?php echo $rs['caption']?></a>&nbsp;（<?php echo date('Y年m月d日',$rs['time'])?>）</p>
                  </li>
                <?php }?>
                </ul>
              </div>
            </div>
          </div>
        <?}?>