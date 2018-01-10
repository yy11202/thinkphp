<?php
include_once('check.php');
$rid=(int)GP('rid');
$grade=D()->select('status','value',"uid='$uid' and token='master,$rid'");
$U['g']==28 && $grade=1;
$U['g']==2 && $grade=2;
!$grade && exit('<p>没有权限</p>');
?>

<form method="post" id="master">
<table width="100%">
  <tr>
    <td><input type="checkbox" class="checkbox" name="refresh" id="m_refresh" value="1" /><label for="m_refresh">强刷新房间</label>&nbsp;&nbsp;</td>
    <td><input type="checkbox" class="checkbox" name="out" id="m_out" value="1" /><label for="m_out">强制离开</label>&nbsp;&nbsp;</td>
  </tr>
  <tr>
    <td><input type="checkbox" class="checkbox" name="clear" id="m_clear" value="1" /><label for="m_clear">强制清屏</label>&nbsp;&nbsp;</td>
    <td><input type="checkbox" class="checkbox" name="dropmic" id="m_dropmic" value="1" /><label for="m_dropmic">强制放麦</label>&nbsp;&nbsp;</td>
  </tr>
<?php if($grade<=3){?>
  <tr>
    <td><input type="checkbox" class="checkbox" name="addmaster" id="m_addmaster" value="1" /><label for="m_addmaster">提为临时管理员</label>&nbsp;&nbsp;</td>
    <td><input type="checkbox" class="checkbox" name="delmaster" id="m_delmaster" value="1" /><label for="m_delmaster">取消临时管理员</label>&nbsp;&nbsp;</td>
  </tr>
<?php }?>
</table>
<br />
<table width="100%">
  <tr>
    <td>对象: <select id="send_tousr" name="to"></select>&nbsp;&nbsp;</td>
    <td><input type="button" class="btn_normal" value="确定" onclick="SetMaster()" /></td>
  </tr>
</table>
<input type="hidden" name="rid" value="<?php echo $rid?>" />
</form>