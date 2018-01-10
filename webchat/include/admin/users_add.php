<form id="divForm" method="post">
<input type="hidden" name="action" value="add" />
<input type="hidden" name="o_infotime" value="onez.time" />
<input type="hidden" name="o_infoip" value="onez.ip" />
<table class="tb">
  <tr>
    <td class="first onez-right"><strong>所属组</strong>：</td>
    <td>
    <select name="o_g">
      <?php foreach($GLOBALS['grades'] as $k=>$v){
      $s=$_GET['g']==$k ? ' selected' : ''?>
    	<option value="<?php echo $k?>"<?php echo $s?>><?php echo $v[0]?></option>
      <?php }?>
    </select>
		</td>
    <td></td>
  </tr>
  <tr>
    <td class="first onez-right"><strong>性别</strong>：</td>
    <td>
    <select name="o_g">
      <?php foreach($GLOBALS['Sex'] as $k=>$v){
      $s=$sex==$k ? ' selected' : ''?>
    	<option value="<?php echo $k?>"<?php echo $s?>><?php echo $v?></option>
      <?php }?>
    </select>
		</td>
    <td></td>
  </tr>
  <tr>
    <td class="onez-right"><label none="用户名不能为空" name="o_username"></label><strong>用户名</strong></td>
    <td><input name="o_username" class="pt onez-text" type="text" maxlength="50"></td>
    <td></td>
  </tr>
  <tr>
    <td class="onez-right"><strong>昵称</strong></td>
    <td><input name="o_nickname" class="pt onez-text" type="text" maxlength="50"></td>
    <td></td>
  </tr>
  <tr>
    <td class="onez-right"><label none="登录邮箱不能为空" name="o_loginEmail"></label><strong>登录邮箱</strong></td>
    <td><input name="o_loginEmail" class="pt onez-text" type="text" maxlength="120"></td>
    <td></td>
  </tr>
  <tr>
    <td class="onez-right"><label none="登录密码不能为空" name="o_password"></label><strong>登录密码</strong></td>
    <td><input name="o_password" class="pt onez-text" type="text" maxlength="50"></td>
    <td></td>
  </tr>
  <tr>
    <td class="onez-right"><strong>经验</strong></td>
    <td><input name="o_exp" class="pt onez-text" type="text" maxlength="50" value="<?php echo $exp?>"></td>
    <td><img src="images/exp.gif" align="absmiddle" /></td>
  </tr>
  <tr>
    <td class="onez-right"><strong>金币</strong></td>
    <td><input name="o_credit" class="pt onez-text" type="text" maxlength="50" value="<?php echo $credit?>"></td>
    <td><img src="images/money.gif" align="absmiddle" /></td>
  </tr>
  <tr>
    <td class="onez-right"><strong>鲜花</strong></td>
    <td><input name="o_flower" class="pt onez-text" type="text" maxlength="50" value="<?php echo $flower?>"></td>
    <td><img src="images/flower.gif" align="absmiddle" /></td>
  </tr>
</table>
</form>