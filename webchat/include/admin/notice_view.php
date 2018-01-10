<?php #删除
if(GP('action')=='delete'){
	$nid=GP('id');
	D()->delete('notice',"nid='$nid'");
	exit('Y?f=notice');
}
$nid=GP('nid');
$T=D()->one('notice','*',"nid='$nid'");
!$T && html()->showmessage('通知编号错误',html()->assign('url'));
foreach($T as $k=>$v)$$k=$v;
html()->header(false)?>
<div class="main">
<div class="path">
  <a href="<?php echo html()->assign('url') ?>">管理中心</a>
</div>
<div class="maintitle cl">
    <h2>
    	产品动态
    	[<?php if($g=='28'){?><a href="javascript:delRecord(<?php echo $nid?>)" style="color:red">删除</a><?php }?>]
    </h2>
</div>
<table class="mainframe">
  <tbody>
    <tr>
      <td class="content"><div class="pbm mbm bbda">
      <div class="article">
      <font face="微软雅黑"> <strong><font size="6"><?php echo $caption?></font></strong></font>
      <br />
      <?php echo $content?></div>
        </div></td>
      <td class="side"><h3>产品信息</h3>
        <ul class="xl">
          <script type="text/javascript" src="http://notice.onez.cn/product/<?php echo PRODUCT?>"></script>
        </ul></td>
    </tr>
  </tbody>
</table>
<?php html()->footer()?>