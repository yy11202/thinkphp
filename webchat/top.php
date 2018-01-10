<?php
include_once('include/common.inc.php');
$title='排行榜';
include_once('header.php');
?>
<div class="main"> 
  <div class="left">
<!--列表开始-->    
    <div class="room" style="background-image:url(images/ti_level.jpg)">
    <ul id="roomlist">
<?php
$level=D()->record('users','*',"level>0 order by level desc",9);
foreach($level as $rs){
?>
        <li>
          <a href="javascript:void(0)" class="icon"><img src="avatar.php?uid=<?php echo $rs['uid']?>" /></a>
          <a href="javascript:void(0)" class="roomname"><?php echo $rs['username']?></a>
          <span class="online">头衔: <font color="orange"><?php echo getLevel($rs['level'])?></font></span>
          <span class="id">等级: <?php echo $rs['level']?></span>
        </li>
<?php }?>
</ul>
    <div class="clear"></div>
    </div>
<!--列表结束--> 
<!--列表开始-->    
    <div class="room" style="background-image:url(images/ti_credit.jpg)">
    <ul id="roomlist">
<?php
$users=D()->record('users','*',"credit>0 order by credit desc",9);
foreach($users as $rs){
?>
        <li>
          <a href="javascript:void(0)" class="icon"><img src="avatar.php?uid=<?php echo $rs['uid']?>" /></a>
          <a href="javascript:void(0)" class="roomname"><?php echo $rs['username']?></a>
          <span class="online">金币: <font color="orange"><?php echo $rs['credit']?></font></span>
          <span class="id">ID: <?php echo $rs['uid']?></span>
        </li>
<?php }?>
</ul>
    <div class="clear"></div>
    </div>
<!--列表结束--> 
  </div>
  <div class="right">
  <?php include_once('righter.php');?>
  </div>
</div>
<?php include_once('footer.php');?>