<?php !defined('IN_ONEZ') && exit('Access Denied');?>

    <div>
<ul class="daoju">
<?php
$props=D()->record('props','*',"type='gift' order by pid desc",16);
foreach($props as $rs){
?>
<li>
  <img src="<?php echo $rs['pic']?>" height="35" class="icon" title="<?php echo $rs['name']?> <?php echo $rs['price']?>金币" />
</li>
<?php }?>
      </ul>
<div style="clear:both"></div>
    </div>
    <div style="padding:1px;"><?php showad('index_prop')?></div>
    <div class="paihang">
      <table width="239" border="0" cellspacing="1" cellpadding="0" class="daojulist">
<?php
$flower=D()->record('users','*',"flower>0 order by flower desc");
foreach($flower as $k=>$rs){
if($k==0 && $option['flowerbaby']!=$rs['uid']){
  D()->replace('option',array('token'=>'flowerbaby','value'=>$rs['uid']));
  updatecache();
}
?>
        <tr>
          <th width="26" height="26"  scope="col" style="border-bottom:#cccccc 1px dotted;"><img src="images/flower.png" width="30" height="30" /></th>
          <th width="139" height="0" scope="col" style="border-bottom:#cccccc 1px dotted;"><?php echo $rs['username']?></th>
          <td width="55" scope="col" style="border-bottom:#cccccc 1px dotted;"><?php echo $rs['flower']?></td>
        </tr>
<?php }?>
      </table>
    </div>