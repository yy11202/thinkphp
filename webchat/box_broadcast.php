<?php
include_once('check.php');
$rid=(int)GP('rid');
?>

<form method="post" id="broadcast">
<table width="100%">
  <tr>
    <td colspan="3">请填写您要广播的内容</td>
  </tr>
  <tr>
    <td colspan="3"><textarea class="scroll" id="msg" name="msg" style="width:400px;height:120px;"></textarea></td>
  </tr>
  <tr>
    <td width="120">价格: <font color="#ff5000"><?php echo (int)$option['lb_credit']?></font> 金币/次&nbsp;&nbsp;</td>
    <td align="left"><a href="javascript:void(0)" oneztitle="插入房间代码" onclick="InsertRoom()">房间代码</a></td>
    <td width="80"><input type="button" class="btn_normal" value="确定" onclick="SendNotice()" /></td>
  </tr>
</table>
<input type="hidden" name="rid" value="<?php echo $rid?>" />
</form>