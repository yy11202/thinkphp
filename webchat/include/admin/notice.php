<?php $action=GP('action');
if($action=='add'){
	$S=html()->postArray();
	D()->insert('notice',$S);
	exit('Y');
}elseif($action=='edit'){
	$nid=GP('id');
	$S=html()->postArray();
	D()->update('notice',$S,"nid='$nid'");
	exit('Y');
}elseif($action=='delete'){#删除
	$nid=GP('id');
	D()->delete('notice',"nid='$nid'");
	exit('Y');
}
html()->header(false)?>
<div class="main">
<div class="path">
  <a href="<?php echo html()->assign('url') ?>">管理中心</a>
</div>
<div class="maintitle cl">
    <h2>
    	产品动态
    	[<?php if($g=='28'){?><a href="javascript:setRecord('发布新通知','?f=notice_add')" style="color:red">发布新通知</a><?php }?>]
    </h2>
</div>
<table class="mainframe">
  <tbody>
    <tr>
      <td class="content"><div class="pbm mbm bbda">
      <ul class="tl tlann">
            <?php $T=D()->record('notice','*',"1 order by nid desc",20);
            foreach($T as $rs){
            ?>
            <li>
            <em><?php echo date('Y-m-d',$rs['time'])?></em><a href="<?php echo html()->assign('url') ?>?f=notice_view&nid=<?php echo $rs['nid']?>" target="_blank" title="<?php echo $rs['caption']?>"><?php echo $rs['caption']?></a>
            [<?php if($g=='28'){?><a href="javascript:delRecord(<?php echo $rs['nid']?>)" style="color:red">删除</a><?php }?>]
            </li>
            <?php }?>
          </ul>
        </div></td>
      <td class="side"><h3>产品信息</h3>
        <ul class="xl">
          <script type="text/javascript" src="http://notice.onez.cn/product/<?php echo PRODUCT?>"></script>
        </ul></td>
    </tr>
  </tbody>
</table>
<?php html()->footer()?>