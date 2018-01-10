<?php !defined('IN_ONEZ') && exit('Access Denied');
if(GP('action')=='save'){
  foreach(html()->postArray() as $key=>$value){
    D()->replace('option',array('token'=>$key,'value'=>$value));
  }
  updatecache();
  html()->showmessage('修改成功',html()->assign('self'),'right');
}
?>
<?php html()->header();
html()->where('高级选项','全局参数');
?>
<div class="bm onez-table-form">
<form id="divForm" method="post">
<input type="hidden" name="action" value="save" />
<h3>基本设置</h3>
<table class="tb">
  <tr>
    <td width="125"><strong>网站名称</strong></td>
    <td><input type="text" name="o_sitename" class="basic-input onez-input-text" value="<?php echo $option['sitename']?>"/></td>
    <td></td>
  </tr>
  <tr>
    <td><strong>keywords</strong></td>
    <td><textarea id="keywords" name="o_keywords" class="onez-textarea"><?php echo $option['keywords']?></textarea></td>
    <td></td>
  </tr>
  <tr>
    <td><strong>decription</strong></td>
    <td><textarea id="description" name="o_description" class="onez-textarea"><?php echo $option['description']?></textarea></td>
    <td></td>
  </tr>
  <tr>
    <td><strong></strong></td>
    <td>
      <input type="submit" name="submit" class="pn pnc" value=" 确定 " style="padding:5px;height:auto" />
    </td>
    <td></td>
  </tr>
</table>
</form>
<?php html()->footer();?>