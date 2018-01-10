<?php
include_once('check.php');

$props=D()->record('props','*',"type='face' order by pid desc");
?>
<ul class="popBox">
<?php
foreach($props as $rs){
?>
<li><a href="javascript:SelFace('<?php echo $rs['pid']?>')" oneztitle="<img src=<?php echo $rs['pic']?> /> <br /><?php echo $rs['name']?>"><img src="<?php echo $rs['pic']?>" onmouseover="$('#send_price').html('<?php echo $rs['price']?>')" width="32" height="32" /></a></li>
<?php }?>
</ul>
<div style="clear:both"></div>
<table width="100%">
  <tr>
    <td>我的金币: <span style="color:orange"><?php echo $credit?></span>&nbsp;&nbsp;</td>
    <td>价格: <span id="send_price" style="color:orange">0</span>&nbsp;&nbsp;</td>
  </tr>
</table>