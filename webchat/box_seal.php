<?php
include_once('check.php');

$props=D()->record('props','*',"type='seal' order by pid desc");
?>
<ul class="propBox">
<?php
foreach($props as $rs){
?>
<li><a href="javascript:SelSeal('<?php echo $rs['pid']?>')" oneztitle="<?php echo $rs['name']?>"><img src="<?php echo $rs['thumb']?>" onmouseover="$('#send_price').html('<?php echo $rs['price']?>')" /></a></li>
<?php }?>
</ul>
<div class="clear"></div>

<table width="100%">
  <tr>
    <td>赠与: <select id="send_tousr"></select>&nbsp;&nbsp;</td>
    <td>我的金币: <span style="color:orange"><?php echo $credit?></span>&nbsp;&nbsp;</td>
    <td>价格: <span id="send_price" style="color:orange">0</span>&nbsp;&nbsp;</td>
  </tr>
</table>