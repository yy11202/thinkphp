<?php !defined('IN_ONEZ') && exit('Access Denied');
if(GP('action')=='sql'){
  $sql=trim(GP('sql'));
  !$sql && html()->showmessage('SQL语句不能为空');
}
?>
<?php html()->header();
html()->where('运行SQL语句');
?>
<div class="bm">
<?php html()->tip('此功能仅用于数据库升级和测试SQL，因此请勿执行大量的代码。<br />如果有需要，请改用phpMyAdmin或其他MySQL管理工具！');?>
<form id="divForm" method="post">
<input type="hidden" name="action" value="sql" />
<table class="tb">
  <tr>
    <td width="65"><strong>SQL语句</strong></td>
    <td><textarea id="sql" name="sql" style="width:600px;height:200px;overflow:visible"><?php echo $sql?></textarea></td>
    <td></td>
  </tr>
  <tr>
    <td><strong></strong></td>
    <td>
      <input type="submit" name="submit" class="pn pnc" value=" 运行 " style="padding:5px;height:auto" />
    </td>
    <td></td>
  </tr>
</table>
</form>
<?php $data=$option=array();
 $link=D()->link;
 foreach(D()->runquery($sql) as $k=>$item){
	 $type='unknow';
	 $table='none';
	 $row=0;
	if(@preg_match('/^[a-z]+\s/i',$item,$mat)){
		$type=strtolower(trim($mat[0]));
	}
	if(@preg_match('/(form|update)[\s`]+'.D()->tbl.'([a-zA-Z_0-9]+)[\s`]/i',$item,$mat)){
		$table=strtolower(trim($mat[2]));
	}
 	if($type=='select'){
 		if(!preg_match('/limit[0-9,\s]/i',$item)){
 			$item.=' limit 0,50';
 			$link=D()->link2;
 		}
 	}
 	$result=@mysql_query($item, $link);
	if($result){
		$row=mysql_affected_rows($link);
		if($type=='select'){
		  while($onez=D()->fetch_array($result)){
		  	if(!$option){
		  		foreach($onez as $k=>$v){
						$option[]=array(
				      'text'=>'{'.$k.'}',
				      'name'=>$k,
						);
		  		}
		  	}
		  	$data[]=$onez;
			}
		}else{
			$option[]=array(
	      'text'=>'{type}',
	      'name'=>'类型',
			);
			if($table!='none'){
				$option[]=array(
		      'text'=>'{table}',
		      'name'=>'数据库',
				);
			}
			$option[]=array(
	      'text'=>'{row}',
	      'name'=>'影响的行数',
			);
			if($table!='none'){
				$count=D()->rows($table,'');
				$option[]=array(
		      'text'=>'{count}',
		      'name'=>'现有记录数',
				);
			}
			$data[]=array(
				'type'=>$type,
				'table'=>'<font color=#cccccc>'.D()->tbl.'</font>'.$table,
				'count'=>$count,
				'row'=>$row,
			);
		}
	}else{
		$option[]=array(
      'width'=>'60',
      'text'=>'{status}',
      'name'=>'状态',
		);
		$option[]=array(
      'width'=>'120',
      'text'=>'{errno}',
      'name'=>'错误编号',
		);
		$option[]=array(
      'width'=>'500',
      'text'=>'{error}',
      'name'=>'错误信息',
		);
		$data[]=array(
			'status'=>'错误',
			'error'=>D()->error(),
			'errno'=>D()->errno(),
		);
	}
	$s_item=$item;
	if(ostrlen($s_item)>200){
		$s_item=osubstr($s_item,0,200)."\n...";
	}
	$s_item=str_replace("\n",'<br />',$s_item);
	 html()->tip($s_item);
	 html()->datatable(array(
	 		'table'=>array(
					 			'id'=>'table_'.($k+1),
					 		),
	    'title'=>'查询结果'.($k+1).' [<a href="javascript:openWin('.($k+1).')" style="color:red">全屏查看</a>]',
	    'option'=>$option,
	    'data'=>array($data),
	  ));
}
?>
</div>
<?php html()->footer();?>
<script type="text/javascript">
function resBak(id) {
  showWindows('此操作将会清空您的现有数据库，确定要继续吗？', '提示信息', function(){
  	location.href='?f=database_bak&action=res&id='+id;
  }, 1);
}
function openWin(id) {
	var win=window.open('about:blank','_target','');
	win.document.writeln('<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">');
	win.document.writeln('<html xmlns="http://www.w3.org/1999/xhtml">');
	win.document.writeln('<head>');
	win.document.writeln('<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />');
	win.document.writeln('<title>查询结果'+id+'</title>');
	win.document.writeln('<style type="text/css">th,td{font-size:12px;}\ntable{background:#999999;border:0;}\nth{background:#f0f0f0;height:25px;border:0}\ntd{background:#ffffff;height:20px}</style>');
	win.document.writeln('</head><body>');
	var data=$('#table_'+id)[0].outerHTML;
	data='<table width="100%" cellpadding="1" cellspacing="1" '+data.substr(6);
	data=data.replace('table-layout:fixed','');
	win.document.writeln(data);
	win.document.writeln('</body></html>');
}
</script>

