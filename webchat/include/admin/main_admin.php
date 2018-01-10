<?php !defined('IN_ONEZ') && exit('Access Denied');
html()->header();
?>
<?php html()->where('token|网站概况');?>
<div class="bm">
  <?php html()->tip($U['nickname'].'，您好！下面是您网站的基本统计信息！');
  $data=array();
  $data[]=array(
    '会员总金币',
    (float)D()->select('users','sum(credit)',"").'',
    '会员总鲜花数:',
    (float)D()->select('users','sum(flower)',"").'',
  );
  $data[]=array(
    '房间:',
    D()->rows('rooms',"").' 个',
  );
  $data[]=array(
    '短消息:',
    D()->rows('sms',"isread='0'").' / '.D()->rows('sms',"").' <a href="?f=sms">查看</a>',
    '通知:',
    D()->rows('notice',"").' <a href="?f=notice">查看</a>',
  );
  html()->datatable(array(
    'title'=>'统计信息',
    'nohead'=>true,
    'option'=>array(
      array('text'=>'{0}'),
      array('text'=>'{1}'),
      array('text'=>'{2}'),
      array('text'=>'{3}'),
    ),
    'data'=>array($data),
  ));
?>
</div>
<?php html()->footer();?>