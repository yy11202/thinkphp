<?php
include_once('include/common.inc.php');

include_once('header.php');
?>
<meta property="qc:admins" content="14605702666320066375" />
<div class="center">
  <div class="flashnew"><?php showad('index_top')?></div>
</div>
<div class="center">
  <div class="notice"><?php showad('index_ad')?></div>
  <div class="time">今天是 <script language="JavaScript" type="text/JavaScript">
var day="";
var month="";
var ampm="";
var ampmhour="";
var myweekday="";
var year="";
mydate=new Date();
myweekday=mydate.getDay();
mymonth=mydate.getMonth()+1;
myday= mydate.getDate();
myyear= mydate.getYear();
year=(myyear > 200) ? myyear : 1900 + myyear;
if(myweekday == 0)
weekday=" 星期日 ";
else if(myweekday == 1)
weekday=" 星期一 ";
else if(myweekday == 2)
weekday=" 星期二 ";
else if(myweekday == 3)
weekday=" 星期三 ";
else if(myweekday == 4)
weekday=" 星期四 ";
else if(myweekday == 5)
weekday=" 星期五 ";
else if(myweekday == 6)
weekday=" 星期六 ";
document.write(year+"年"+mymonth+"月"+myday+"日 "+weekday);
</script></div>
</div>
<!--广告位960-->
<!--广告位960-->
<div class="main"> 
  <div class="left">
    <div class="picture">
<script language="JavaScript" type="text/javascript">
var swf_width=346;
var swf_height=286;
var config='5|0xffffff|0x0099ff|50|0xffffff|0x0099ff|0x000000'
// config 设置分别为: 自动播放时间(秒)|文字颜色|文字背景色|文字背景透明度|按键数字色|当前按键色|普通按键色
<?php showad('slide_index')?>
document.write('<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,0,0" width="'+ swf_width +'" height="'+ swf_height +'">');
document.write('<param name="movie" value="images/focus.swf" />');
document.write('<param name="quality" value="high" />');
document.write('<param name="menu" value="false" />');
document.write('<param name=wmode value="transparent" />');
document.write('<param name="FlashVars" value="config='+config+'&bcastr_flie='+files+'&bcastr_link='+links+'&bcastr_title='+texts+'" />');
document.write('<embed src="images/focus.swf" wmode="opaque" FlashVars="config='+config+'&bcastr_flie='+files+'&bcastr_link='+links+'&bcastr_title='+texts+'& menu="false" quality="high" width="'+ swf_width +'" height="'+ swf_height +'" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" />');
document.write('</object>');
</script>
    </div>
    <div class="news">
<?php
$news=D()->record('news','*',"1 order by step desc,nid desc");
?>
      <div class="news1"><h3><a href="viewnews.php?id=<?php echo $news[0]['nid']?>" target="_blank"><?php echo $news[0]['title']?></a></h3>
      <?php echo osubstr(strip_tags($news[0]['content']),0,19)?>......</div>
      <div class="news2"><ul>
<?php
unset($news[0]);
foreach($news as $rs){?>
      	<li>[活动] <a href="viewnews.php?id=<?php echo $rs['nid']?>" target="_blank"><?php echo $rs['title']?></a></li>
<?php }?>
      </ul></div>
      <div class="news3"><ul>
        <?php showad('list_bbs')?>
      </ul></div>
    </div>
<!--广告位700-->    
    <div class="room">
    <ul id="roomlist">
<?php
$rooms=D()->record('rooms','*',"1 order by online desc");
foreach($rooms as $rs){
!$rs['icon'] && $rs['icon']='images/nopic.gif';
?>
        <li>
          <a href="javascript:OpenRoom(<?php echo $rs['rid']?>)" class="icon">
            <img src="<?php echo $rs['icon']?>" />
          </a>
          <a href="javascript:OpenRoom(<?php echo $rs['rid']?>)" class="roomname"><?php echo $rs['roomname']?></a>
          <span class="online">在线: <font color="orange"><?php echo $rs['online']?></font></span>
          <span class="id">ID: <?php echo $rs['rid']?></span>
          <span class="vmask v<?php echo $rs['videonum']?>"></span>
          <?php if(trim($rs['pass'])){?><span class="pwd" title="加密房间"></span><?php }?>
        </li>
<?php }?>
</ul>
    <div class="clear"></div>
    </div>
<!--广告位700--> 
  </div>
  <div class="right">
  <?php include_once('righter.php');?>
  </div>
</div>
<div class="center"><?php showad('index_bottom')?></div>
<?php include_once('footer.php');?>