<?php !defined('IN_ONEZ') && exit('Access Denied');
$action=GP('action');
if($action=='add'){
	unset($uid);
	$S=html()->postArray();
	!$S['username'] && exit('用户名不能为空');
	!$S['loginEmail'] && exit('登录邮箱不能为空');
  if(defined('UC_API')){
  	include_once ONEZ_ROOT.'uc_client/client.php';
	  $uid = uc_user_register($S['username'], $S['password'], $S['loginEmail']);
	  if($uid <= 0) {
	    if($uid == -1) {
	      ero('用户名不合法');
	    } elseif($uid == -2) {
	      ero('包含要允许注册的词语');
	    } elseif($uid == -3) {
	      ero('用户名已经存在');
	    } elseif($uid == -4) {
	      ero('Email 格式有误');
	    } elseif($uid == -5) {
	      ero('Email 不允许注册');
	    } elseif($uid == -6) {
	      ero('该 Email 已经被注册');
	    } else {
	      ero('未知错误');
	    }
	    exit();
	  }
  }
  $uid && $S['uid']=$uid;
	D()->rows('users',"username='$S[username]'")>0 && exit('用户名已存在');
	D()->rows('users',"loginEmail='$S[loginEmail]'")>0 && exit('登录邮箱已存在');
	$S['password']=md5($S['password']);
	$S['exp']=intval($S['exp']);
	$S['credit']=intval($S['credit']);
	$S['flower']=intval($S['flower']);
	D()->insert('users',$S);
	exit('Y');
}elseif($action=='add2'){
	$S=html()->postArray();
  exit(addRenqiu($S['username']));
}elseif($action=='edit'){
	$id=GP('id');
	$S=html()->postArray();
	if($S['password']){
    $S['password']=md5($S['password']);
	}else{
    unset($S['password']);
	}
	$S['exp']=intval($S['exp']);
	$S['credit']=intval($S['credit']);
	$S['flower']=intval($S['flower']);
	if($S['avatar']){
    D()->query("INSERT INTO #_status (uid,token,value,exptime) VALUES ('$id','avatar','$S[avatar]',-1) ON DUPLICATE KEY UPDATE value='$S[avatar]'");
	}
  unset($S['avatar']);
  
  D()->update('props_user',array('num'=>$S['flower']),"uid='$id' and pid='5'");
      
	D()->update('users',$S,"uid='$id'");
	exit('Y');
}elseif($action=='delete'){#删除
	$id=GP('id');
	$uid==$id && exit('您不能删除自己');
	$U=D()->one('users','*',"uid='$id'");
	if($U){
    if($U['rqid']){
      D()->delete('renqiu',"uid='$U[rqid]'");
    }
    D()->delete('status',"uid='$id'");
	}
	D()->delete('users',"uid='$id'");
	exit('Y');
}
$_g='';
$G=array();
foreach($GLOBALS['grades'] as $k=>$v){
	if($_GET['type']==$v[2]){
		$_g=$k;
		$G=$v;
	}
}
if($G){
	$ti=$G[0];
	$xxx="g='$_g'";
}else{
	$ti='所有用户';
	$xxx="1";
}
$ti2=$ti;
$keyword=GP('keyword');
$group=GP('group');
!$group && $group=$_g;
if($keyword){
  if($group=='all'){
    $ti=$ti2='所有用户';
    $xxx='1';
  }else{
    $ti2=$ti=$GLOBALS['grades']['group'][0];
    $xxx="g='$group'";
  }
  $xxx.=" and (username like '%$keyword%' or nickname like '%$keyword%')";
  $ti2.=' &raquo; 关键词: <font color="red">'.$keyword.'</font>';
}
html()->header();
html()->where('用户管理',$ti2);
function getFrom($rs){
	if($rs['ufrom']=='qq'){
    $from='腾讯QQ';
	}else if($rs['ufrom']=='sina'){
    $from='新浪微博';
	}else if($rs['ufrom']=='renqiu'){
    $from='旧聊天室';
	}else{
    $from='站内';
	}
	return $from;
}
function getSex($rs){
	global $Sex;
	return $Sex[$rs['sex']];
}
?>
<form action="u.php" method="get">
<table class="tb">
  <tr>
    <td>查找用户: </td>
    <td><input type="text" name="keyword" class="pt onez-text" value="<?php echo $keyword?>" /></td>
    <td>
    <select name="group">
    	<option value="all"<?php echo $s?>>-所有组-</option>
      <?php foreach($GLOBALS['grades'] as $k=>$v){
      $s=($group==$k && $group!='all' ? ' selected' : '')?>
    	<option value="<?php echo $k?>"<?php echo $s?>><?php echo $v[0]?></option>
      <?php }?>
    </select></td>
    <td><input type="submit" value=" 搜索 " class="pn pnc" /></td>
  </tr>
</table>
<input type="hidden" name="f" value="users" />
<input type="hidden" name="type" value="<?php echo $_GET['type']?>" />
</form>
<div class="bm">
  <?php html()->datatable(array(
  'title'=>$ti2,
  'links'=>'<a href="javascript:void(0);" onclick=setRecord("添加'.$ti.'","'.html()->assign('url').'?f=users_add&g='.$_g.'") class="add"><span>添加'.$ti.'</span></a>',
  'option'=>array(
    array(
      'width'=>'55',
      'text'=>'{uid}',
      'name'=>'UID',
    ),
    array(
      'text'=>'{username}',
      'name'=>'用户名',
    ),
    array(
      'text'=>'{nickname}',
      'name'=>'昵称',
    ),
    array(
      'func'=>'getSex',
      'name'=>'性别',
    ),
    array(
      'func'=>'getFrom',
      'name'=>'账号来源',
    ),
    array(
      'width'=>'80',
      'text'=>'<img src="images/exp.gif" align="absmiddle" />{exp}',
      'name'=>'经验',
    ),
    array(
      'width'=>'80',
      'text'=>'<img src="images/money.gif" align="absmiddle" />{credit}',
      'name'=>'金币',
    ),
    array(
      'width'=>'80',
      'text'=>'<img src="images/flower.gif" align="absmiddle" />{flower}',
      'name'=>'鲜花',
    ),
    array(
      'width'=>'80',
      'text'=>'<a href="javascript:setRecord(\'编辑用户\',\'?f=users_edit&id={uid}\')">编辑</a> &nbsp; <a href="javascript:delRecord(\'{uid}\')">删除</a>',
      'name'=>'操作',
    ),
  ),
  'data'=>D()->page('users','*',"$xxx order by uid desc",20,"u.php?keyword=$keyword&group=$group&f=users&type=$_GET[type]"),
));
?>
</div>
<?php html()->footer();?>