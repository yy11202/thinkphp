<?php
include_once('include/common.inc.php');
$title='道具商城';
include_once('header.php');
?>
<div class="main"> 
  <div class="left">
<!--广告位700-->    
    <div class="room" style="background-image:url(images/ti_prop.jpg)">
    <ul id="roomlist">
<?php
$props=D()->page('props','*',"type='gift' order by pid desc",21,"");
foreach($props[0] as $rs){
!$rs['thumb'] && $rs['thumb']='images/nopic.gif';
?>
        <li>
          <a href="javascript:void(0)" class="icon"><img src="<?php echo $rs['pic']?>" /></a>
          <a href="javascript:void(0)" class="roomname"><?php echo $rs['name']?></a>
          <span class="online">价格: <img src="images/money.gif" align="absmiddle" /> <font color="orange"><?php echo $rs['price']?></font></span>
        </li>
<?php }?>
</ul>
    <div class="clear"></div>
    <?php echo $props[1]?>
    </div>
<!--广告位700--> 
  </div>
  <div class="right">
  <?php include_once('righter.php');?>
  </div>
</div>
<?php include_once('footer.php');?>