<?php
if(GP('action')=='submit'){
	$aid=GP('aid');
  setuse(array());
}
html()->header();
html()->where('查看道具');
$apps=D()->record("apps","*","uid='$uid' and status='success' and buildtime>0");
?>
<div class="bm onez-table-form">
<h3>查看道具</h3>
<form id="divForm" method="post">
<input type="hidden" name="action" value="submit" />
<table class="tb">
  <?php propsinfo()?>
  <?php if($num>0){?>
  <tr>
    <td width="120" class="onez-right"><strong>目标应用</strong>：</td>
    <td>
      <select name="aid">
      <?foreach($apps as $rs){?>
      <option value="<?=$rs['aid']?>"><?=$rs['name']?></option>
      <?}?>
      </select>
    </td>
  </tr>
  <tr>
    <td><strong></strong></td>
    <td>
      <input type="submit" name="submit" class="pn pnc" value=" 立即执行 " style="padding:5px;height:auto" />
    </td>
    <td></td>
  </tr>
  <?php }?>
</table>
</form>
</div>
<?php html()->footer();?>