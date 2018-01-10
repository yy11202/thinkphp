<?php
include_once('include/common.inc.php');
$title='聊天大厅';
include_once('header.php');
?>
<div class="main"> 
  <div class="left">
<!--广告位700-->    
    <div class="room">
    <ul id="roomlist">
<?php
$rooms=D()->page('rooms','*',"1 order by online desc",21,"");
foreach($rooms[0] as $rs){
!$rs['icon'] && $rs['icon']='images/nopic.gif';
?>
        <li>
          <a href="javascript:OpenRoom(<?php echo $rs['rid']?>)" class="icon"><img src="<?php echo $rs['icon']?>" /></a>
          <a href="javascript:OpenRoom(<?php echo $rs['rid']?>)" class="roomname"><?php echo $rs['roomname']?></a>
          <span class="online">在线: <font color="orange"><?php echo $rs['online']?></font></span>
          <span class="id">ID: <?php echo $rs['rid']?></span>
          <span class="vmask v<?php echo $rs['videonum']?>"></span>
          <?php if(trim($rs['pass'])){?><span class="pwd" title="加密房间"></span><?php }?>
        </li>
<?php }?>
</ul>
    <div class="clear"></div>
    <?php echo $rooms[1]?>
    </div>
<!--广告位700--> 
  </div>
  <div class="right">
  <?php include_once('righter.php');?>
  </div>
</div>
<?php include_once('footer.php');?>