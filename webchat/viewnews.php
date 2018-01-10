<?php
include_once('include/common.inc.php');
$id=GP('id');
$T=D()->one('news','*',"nid='$id'");
if(!$T){
  header('location:news.php');
  exit();
}
foreach($T as $k=>$v)$$k=$v;
include_once('header.php');
?>
<div class="main"> 
  <div class="left">
<!--广告位700-->    
    <div class="room" style="background-image:url(images/ti_news.jpg)">
<h1 style="height:50px;line-height:50px"><?php echo $title?></h1>
<div class="content"><?php echo $content?></div>
    </div>
<!--广告位700--> 
  </div>
  <div class="right">
  <?php include_once('righter.php');?>
  </div>
</div>
<?php include_once('footer.php');?>