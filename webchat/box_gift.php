<?php
include_once('check.php');

$props=D()->record('props','*',"type='gift' order by pid desc");
?>
<ul class="propBox" id="gift">
<?php
foreach($props as $rs){
?>
<li onclick="SelGift('<?php echo $rs['pid']?>')" onmouseover="$('#send_price').html('<?php echo $rs['price']?>')">
  <img src="<?php echo $rs['thumb']?>" class="thumb" oneztitle="<img src=<?php echo $rs['pic']?> /> <br /><?php echo $rs['name']?> <br />价格: <?php echo $rs['price']?> 金币" />
  <span class="name"><?php echo $rs['name']?></span>
  <span class="price"><img src="images/money.gif" align="absmiddle" /> <?php echo $rs['price']?></span>
</li>
<?php }?>
</ul>
<div class="clear"></div>

<table width="100%">
  <tr>
    <td>赠与: <select id="send_tousr"></select>&nbsp;&nbsp;</td>
    <td>我的金币: <span style="color:orange"><?php echo $credit?></span>&nbsp;&nbsp;</td>
    <td>价格: <span id="send_price" style="color:orange">0</span>&nbsp;&nbsp;</td>
    <td>赠送数: <input type="text" id="send_num" value="1" style="width:50px" /></td>
  </tr>
</table>