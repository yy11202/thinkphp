<?php
include_once('include/common.inc.php');
$title='道具商城';
include_once('header.php');
?>
<div class="main"> 
  <div class="left">
<!--广告位700-->    
    <div class="room" style="background-image:url(images/ti_news.jpg)">
    <ul id="newslist">
<?php
$news=D()->page('news','*',"1 order by step desc,nid desc",50,"");
foreach($news[0] as $rs){
!$rs['icon'] && $rs['icon']='images/nopic.gif';
?>
        <li>
          [活动] <a href="viewnews.php?id=<?php echo $rs['nid']?>" target="_blank"><?php echo $rs['title']?></a>
        </li>
<?php }?>
</ul>
    <div class="clear"></div>
    <?php echo $news[1]?>
    </div>
<!--广告位700--> 
  </div>
  <div class="right">
  <?php include_once('righter.php');?>
  </div>
</div>
<?php include_once('footer.php');?>