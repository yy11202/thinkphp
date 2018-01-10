<form id="divForm" method="post">
<input type="hidden" name="action" value="ucinstall" />
<table class="tb">
  <tr>
    <td width="145"><strong>当前应用名称</strong></td>
    <td><input name="app_name" type="text" class="onez-input-text" value="<?php echo $option['sitename']?>" /></td>
    <td></td>
  </tr>
  <tr>
    <td><strong>UCenter 的 URL</strong></td>
    <td><input name="ucurl" type="text" class="onez-input-text" value="http://127.0.0.1/ucenter" /></td>
    <td></td>
  </tr>
  <!--
  <tr>
    <td><strong>UCenter 的IP地址</strong></td>
    <td><input name="ucip" type="text" class="onez-input-text" /></td>
    <td></td>
  </tr>
  -->
  <tr>
    <td><strong>UCenter 创始人密码</strong></td>
    <td><input name="ucpw" type="password" class="onez-input-text" value="admin" /></td>
    <td></td>
  </tr>
</table>
</form>