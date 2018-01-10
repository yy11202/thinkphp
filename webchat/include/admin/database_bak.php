<?php !defined('IN_ONEZ') && exit('Access Denied');
//处理上次未处理完的操作
$T=D()->one('quene','*',"token='database' order by id asc");
if($T){
  list($type,$name,$table,$filename,$area,$page)=explode('.',$T['value']);
  $file='onezdata/sqls/'.$name.'/'.$filename.'.php';
  switch($type){
    case'bak':
      !file_exists($file) && writeover($file,"<?php !defined('IN_ONEZ') && exit('Access Denied');");
      if($area=='info'){
        $A=array('query','DROP TABLE IF EXISTS #_'.$table);
        writeover($file,"\n\$item[]=".var_export($A,true).";",'a+');
        $A=array('query',str_replace(' `'.D()->tbl,' `#_',str_replace(' '.D()->tbl,' #_',D()->sql($table))));
        writeover($file,"\n\$item[]=".var_export($A,true).";",'a+');
      }elseif($area=='clear'){
        $A=array('query','TRUNCATE TABLE #_'.$table);
        writeover($file,"\n\$item[]=".var_export($A,true).";",'a+');
      }elseif($area=='data'){
        $list=D()->record($table,'*',"1 limit ".(($page-1)*1000).",1000");
        foreach($list as $rs){
          $A=array('insert',$table,$rs);
          writeover($file,"\n\$item[]=".var_export($A,true).";",'a+');
        }
      }
      break;
    case'res':
    	if(file_exists($file)){
    		include_once($file);
    		foreach((array)$item as $v){
    			if($v[0]=='query'){
    				D()->query($v[1]);
    			}elseif($v[0]=='insert'){
    				D()->insert($v[1],$v[2]);
    			}
    		}
    	}
      break;
  }
  D()->delete('quene',"id='$T[id]'");
  exit('<meta http-equiv="refresh" content="0;url=?f=database_bak">备份中,请稍候......(剩余:'.D()->rows('quene',"token='database'").')');
}
defined('DB_RES') && exit('还原成功');
$action=GP('action');
if($action=='bak'){
  $readme=GP('readme','P');
  $area_info=GP('area_info','P')=='1';
  $area_data=GP('area_data','P')=='1';
  $area=array();
  $area_info && $area[]='info';
  $area_data && $area[]='data';
  !$area && exit('结构与数据至少要备份一项');
  $Con=array(
    'name'=>date('YmdHis'),
    'uid'=>$uid,
    'readme'=>$readme,
    'area'=>$area,
    'admin'=>$U['nickname'],
    'time'=>time(),
    'ip'=>onlineip(),
  );
  $configFile='onezdata/sqls/'.$Con['name'].'/config.php';
  writeover($configFile,"<?php !defined('IN_ONEZ') && exit('Access Denied');\n\$Con=".var_export($Con,true).";");
  //写队列
  $result=D()->query("show table status"); 
  while($row=D()->fetch_array($result)) {
    if(strpos($row["Name"],D()->tbl)!==0)continue;
    $table=substr($row["Name"],strlen(D()->tbl));
    $page=1;
    $row=D()->rows($table,'');
    if($page % 1000==0){
      $page=$page/1000;
    }else{
      $page=intval($page/1000)+1;
    }
    writeover($configFile,"\n\$tables['$table']=$page;",'a+');
    for($i=1;$i<=$page;$i++){
      $filename=$i==1 ? $table : $table.'_'.$i;
      if($area_info && $i==1){
        D()->insert('quene',array('token'=>'database','value'=>"bak.$Con[name].$table.$filename.info"));
      }
      if($area_data && $i==1){
        D()->insert('quene',array('token'=>'database','value'=>"bak.$Con[name].$table.$filename.clear"));
      }
      D()->insert('quene',array('token'=>'database','value'=>"bak.$Con[name].$table.$filename.data.$i"));
    }
  }
  exit('Y');
}elseif($action=='res'){
	$name=GP('id');
  $configFile='onezdata/sqls/'.$name.'/config.php';
  !file_exists($configFile) && html()->showmessage('备份文件不存在');
  include_once($configFile);
  !$tables && html()->showmessage('备份文件已失败');
  foreach($tables as $table=>$page){
  	for($i=1;$i<=$page;$i++){
      $filename=$i==1 ? $table : $table.'_'.$i;
  		D()->insert('quene',array('token'=>'database','value'=>"res.$name.$table.$filename"));
  	}
  }
  header('location:?f=database_bak');
	exit();
}elseif($action=='delete'){
	$name=GP('id');
	foreach(listover('onezdata/sqls/'.$name.'/*') as $v){
    delfile($v['file']);
	}
	delpath('onezdata/sqls/'.$name);
	exit('Y');
}elseif($action=='download'){
	$name=GP('id');
  include_once(ONEZ_ROOT.'include/zip.class.php');
  $zip = new PHPZip();
  $zip->start('sqlbak.zip');
	foreach(listover('onezdata/sqls/'.$name.'/*') as $v){
    $zip->addfile(readover($v['file']), $name.'/'.$v['name']);
	}
  header('Cache-control: max-age=31536000');
  header('Content-Encoding: none');
  header('Content-Disposition: attachment; filename=sqlbak.zip');
  header('Content-Type: application/octet-stream');
  echo $zip->zip(0);
  exit();
}
html()->header();
?>
<?php html()->where('备份与还原');?>
<div class="bm">
<?php $list=listover('onezdata/sqls/*');
 $data=array();
 foreach($list as $v){
  if($v['type']=='file')continue;
  if(!file_exists($v['file'].'/config.php'))continue;
  unset($Con);
  include_once($v['file'].'/config.php');
  $data[]=array(
    'name'=>$v['name'],
    'admin'=>$Con['admin'],
    'ip'=>$Con['ip'],
    'readme'=>$Con['readme'],
    'time'=>date('Y-m-d H:i:s',$Con['time']),
  );
 }
 html()->datatable(array(
  'title'=>'备份文件列表',
  'links'=>'<a href="javascript:void(0);" onclick=setRecord("备份数据库","'.html()->assign('url').'?f=database_bak_add") class="add"><span>备份数据库</span></a>',
  'option'=>array(
    array(
      'width'=>'55',
      'text'=>'{name}',
      'name'=>'文件名',
    ),
    array(
      'width'=>'55',
      'text'=>'{readme}',
      'name'=>'备注',
    ),
    array(
      'width'=>'55',
      'text'=>'{admin}',
      'name'=>'备份者',
    ),
    array(
      'width'=>'55',
      'text'=>'{time}',
      'name'=>'备份时间',
    ),
    array(
      'width'=>'55',
      'text'=>'{ip}',
      'name'=>'备份者IP',
    ),
    array(
      'width'=>'80',
      'text'=>'<a href="javascript:resBak(\'{name}\')">立即恢复</a> &nbsp; <a href="?f=database_bak&action=download&id={name}&t='.time().'">下载</a> &nbsp; <a href="javascript:delRecord(\'{name}\')">删除</a>',
      'name'=>'',
    ),
  ),
  'data'=>array($data),
));
?>
</div>
<?php html()->footer();?>
<script type="text/javascript">
function resBak(id) {
  showWindows('此操作将会清空您的现有数据库，确定要继续吗？', '提示信息', function(){
  	location.href='?f=database_bak&action=res&id='+id;
  }, 1);
}
</script>

