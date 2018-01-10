<?php class AdminHtml {
	var $path='';
	var $assigns=array();
	function AdminHtml() {
		
	}
	//初始化类
	function init($path){
		global $uid,$U,$menus,$Grade;
		//后台路径
		$this->assign('path',ONEZ_ROOT.$path);
		#uid
		$this->assign('uid',$uid);
		#f
		$f=$_GET['f']?$_GET['f']:'main';
		$this->assign('f',$f);
		#用户资料
		$this->assign('info',(object)$U);
		#程序网址
		$this->assign('homepage',$GLOBALS['homepage']);
		#当前页URL（不含参数）
		$this->assign('url','http://'.$_SERVER['HTTP_HOST'].$GLOBALS['PHP_SELF']);
		#当前页完整网址（含参数）
		$this->assign('self','http://'.$_SERVER['HTTP_HOST'].$GLOBALS['PHP_SELF'].($_SERVER['QUERY_STRING']?'?'.$_SERVER['QUERY_STRING']:''));
		#内置模块根目录
		$this->assign('adminurl',$GLOBALS['homepage'].'/include/admin');
		#后台网址
		$this->assign('baseurl',$GLOBALS['homepage'].'/'.$option['path']);
		#目录列表
		
		if($Grade[$f]){
      if(!in_array($U['g'],$Grade[$f])){
        html()->showmessage('没有权限','?f=main','error');
      }
		}
		if($U['g']==28 && defined('CP_MAIN')){
			$this->assign('grade','超级管理');
			$preMenu=<<<ONEZ
<menu token="main_admin" name="网站概况" />
<menu token="system" name="高级选项">
	<menu token="config_global" name="全局参数" />
	<menu token="config_oauth" name="整合登录" />
</menu>
ONEZ;
			$menus=$preMenu.$menus;
			$menus.='<menu token="users" name="用户管理">';
			krsort($GLOBALS['grades']);
			foreach($GLOBALS['grades'] as $k=>$v){
				$menus.='<menu token="users&amp;type='.$v[2].'" name="'.$v[0].'" />';
			}
			$menus.='</menu>';
			$menus.=<<<ONEZ
<menu token="database" name="数据库管理">
	<menu token="database_bak" name="备份与还原" />
	<menu token="database_sql" name="运行SQL语句" />
</menu>
ONEZ;
		}
		if($menus){
			$menus=@simplexml_load_string('<root>'.$menus.'</root>');
			$menus=$this->object_array($menus);
			$menus=$menus['menu'];
			$this->parseMenuAttr($menus);
		}
		$this->assign('menu',(array)$menus);
		if($_GET['f']=='logout'){
			_cookie('users','del');
			_cookie('oauth_temp','del');
			$this->showmessage('您已成功退出管理中心','index.php','right');
		}
		$guestPage=array('login','register','findpwd','oauth','welcome');
		if(!$uid){
			!in_array($_GET['f'],$guestPage) && $_GET['f']='login';
			html()->dialog($_GET['f']);
		}elseif($uid){
			if(in_array($_GET['f'],$guestPage)){
				html()->dialog($_GET['f']);
			}
		}
	}
	function object_array($array)
  {
      if(is_object($array))
      {
          $array = (array)$array;
      }
      if(is_array($array))
      {
          foreach($array as $key=>$value)
          {
              $array[$key] = $this->object_array($value);
          }
     }
     return $array;
  }

	function parseMenuAttr(&$arr){
		foreach($arr as $k=>$v){
			if($v['@attributes']){
				foreach($v['@attributes'] as $key=>$value){
					$arr[$k][$key]=$value;
				}
				unset($arr[$k]['@attributes']);
			}
			if($v['menu']){
				if($arr[$k]['menu']['@attributes']){
					$arr[$k]['menu']=array($arr[$k]['menu']['@attributes']);
				}
				$arr[$k]['child']=$arr[$k]['menu'];
				unset($arr[$k]['menu']);
				$this->parseMenuAttr($arr[$k]['child']);
			}
		}
	}
	//转换文件中的URL
	function url($url){
		return $GLOBALS['PHP_SELF'].'?f='.$url;
	}
	//读取与赋值
	function assign($token,$value=null){
		if($value===null){
			return $this->assigns[$token];
		}else{
			$this->assigns[$token]=$value;
		}
	}
	//
	function showmessage($text,$type='1',$class=false){
		ob_clean();
		if($type=='1'){
			$goto=$url='javascript:history.go(-1)';
			!$class && $class='error';
		}else{
			$goto=$url=$type;
			!$class && $class='right';
		}
		require_once(ONEZ_ROOT.'include/admin/message.php');
		exit();
	}
	function header($showMenu=true){
		require_once(ONEZ_ROOT.'include/admin/header.php');
	}
	function footer(){
		require_once(ONEZ_ROOT.'include/admin/footer.php');
	}
	function where(){
		echo '<div class="path">';
		echo '<a href="'.html()->assign('url').'">管理首页</a>';
		foreach(func_get_args() as $arg){
			list($token,$name)=explode('|',$arg);
			echo ' <em>›</em> ';
			if($name){
				echo '<a href="'.html()->assign('url').'?f='.$token.'">'.$name.'</a>';
			}else{
				echo '<a>'.$token.'</a>';
			}
		}
		echo '</div>';
	}
	function tip($data){
		echo '<div id="tipsDiv" class="notice notice mbm"><p>'.$data.'</p></div>';
	}
	//调用文件
	function display(){
		global $uid,$U,$g,$option;
		$g=$U['g'];
		$f=$_GET['f'];
		if($f=='delattach'){
      $id=GP('id');
      $T=D()->one('attachments','*',"aid='$id'");
      if(!$T)exit('附件不存在');
      @unlink(ONEZ_ROOT.'/'.$T['file']);
      D()->delete('attachments',"aid='$id'");
      exit('Y');
		}
		$menu=$this->assign('menu');
		!$f && $f=$menu[0]['token'];
		$file=$this->assign('path').'/'.$f.'.php';
		!file_exists($file) && $file=ONEZ_ROOT.'/include/admin/'.$f.'.php';
		if(file_exists($file)){
			include_once($file);
		}else{
			$this->showmessage('非法调用，请检查页面来源！','?');
		}
		//!$hideHeader && !$ajax && $this->header();
		if(function_exists('showhtml')){
			showhtml();
		}
		//!$hideFooter && !$ajax && $this->footer();
	}
	//调用内置文件
	function dialog($f){
		global $uid,$U,$g,$option;
		require_once(ONEZ_ROOT.'include/admin/'.$f.'.php');
		exit();
	}
	//显示上传按钮
	function upload($input='',$value='',$swfFile='upload'){
		global $uploadNum;
		$data='';
		$token=uniqid();
    $url=html()->assign('homepage').'/upload.php';
    $input=str_replace('#','',$input);
		if(!$uploadNum){
		$data.=<<<ONEZ
<script type="text/javascript">
function upload(input,s){
  $('#'+input).val(s);
  var o=s.split('.');
  switch(o[o.length-1].toLowerCase()){
    case 'gif':
    case 'png':
    case 'jpg':
      var obj=$('div[token="'+input+'"]');
      if(obj.length<1){
        obj=$('<div token="'+input+'"></div>');
        obj.insertAfter($('#'+input));
      }
      //obj.html('<img src="'+s+'" align="absmiddle" class="thumb" />');
      break;
  }
}
function uploadError(input,s){
  alert(s);
}
</script>
ONEZ;
		}
		$uploadNum++;
		$mtime=filemtime(ONEZ_ROOT."onezdata/flash/$swfFile.swf");
		$data.=<<<ONEZ
<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=10,0,0,0" width="200" height="30" align="middle">
  <param name="allowFullScreen" value="false" />
  <param name="allowScriptAccess" value="always" />
  <param name="movie" value="onezdata/flash/$swfFile.swf?t=$mtime" />
  <param name="quality" value="high" />
  <param name="bgcolor" value="#ffffff" />
  <param name="flashvars" value="token=$token&input=$input&url=$url">
  <param name="wmode" value="transparent">
  <embed src="onezdata/flash/$swfFile.swf?t=$mtime" quality="high" bgcolor="#ffffff" flashvars="token=$token&input=$input&url=$url" width="200" height="30" name="update" align="middle" allowScriptAccess="always" wmode="transparent" allowFullScreen="false" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" />
</object>
<input type="hidden" name="o_$input" id="$input" value="$value" />
ONEZ;
    if($value){
      $data.='<br /><br />'.html()->attach($value);
    }
    return $data;
	}
	function attach($filename,$isadmin=0){
		global $attachNum;
    $ext=strtolower(end(explode('.', $filename)));
    $file=ONEZ_ROOT.$filename;
    if(!$filename || !file_exists($file))return;
    $name=basename($filename);
    $size=filesize($file);
    $icon='common';
    if(file_exists(ONEZ_ROOT.'images/extension/'.$ext.'.gif')){
      $icon=$ext;
    }elseif(in_array($ext,array('gif','jpg','bmp','png','jpeg'))){
      $icon='image';
    }elseif(in_array($ext,array('txt'))){
      $icon='text';
    }elseif(in_array($ext,array('asp','php','aspx','jsp','css','js'))){
      $icon='html';
    }elseif(in_array($ext,array('rm','rmvb'))){
      $icon='real';
    }elseif(in_array($ext,array('word','xls'))){
      $icon='msoffice';
    }
    $data='<div><img src="images/extension/'.$icon.'.gif" align="absmiddle" /> <font color="#666">'.$name.'</font> ('._sizecount($filesize).')</div>';
    if($icon=='image'){
      $data.='<div><img src="'.$filename.'" align="absmiddle" class="thumb" /></div>';
    }
    return $data;
	}
	//生成POST数组
	function postArray(){
		$S=array();
		foreach($_POST as $k=>$v){
			if(substr($k,0,2)=='o_'){
				if($v=='onez.time'){
					$v=time();
				}else if($v=='onez.ip'){
					$v=onlineip();
				}
				$S[substr($k,2)]=$v;
			}
		}
		return $S;
	}
	//控件
	//表格
	function datatable($A){
		echo'<div id="dataTable">';
		#标题
		echo'<h3>';
		foreach((array)$A['links'] as $item){
			echo$item;
		}
		echo$A['title'];
		echo'</h3>';
		#内容
		echo'<div class="contbox mbn">';
		echo'<table class="tablesorter" style="table-layout:fixed"';
		foreach((array)$A['table'] as $key=>$value){
			echo' '.$key.'="'.$value.'"';
		}
		if(!$A['nohead']){
			echo'><thead><tr>';
			foreach($A['option'] as $k=>$v){
				echo'<th';
				foreach($v as $key=>$value){
					if(in_array($key,array('name','text','func')))continue;
					echo' '.$key.'="'.$value.'"';
				}
				echo'>'.$v['name'].'</th>';
			}
			echo'</tr></thead>';
		}
		echo'<tbody>';
		foreach($A['data'][0] as $rs){
			echo'<tr>';
			foreach($A['option'] as $k=>$v){
				$v['text']=preg_replace('/\{([a-z0-9\_]+)\}/ie','\$rs["$1"]',$v['text']);
				if($v['func'] && function_exists($v['func'])){
					$v['text']=$v['func']($rs);
				}
				echo'<td';
				foreach($v as $key=>$value){
					if(in_array($key,array('name','text','width','func')))continue;
					echo' '.$key.'="'.$value.'"';
				}
				echo'>'.$v['text'].'</td>';
			}
			echo'</tr>';
		}
		echo'</tbody>';
		echo'</table>';
		echo'</div>';
		#分页
		echo'<div class="pgs cl mbm">';
		echo'<div class="pg">'.$A['data'][1].'</div>';
		echo'</div>';
		
		echo'</div>';
	}
}
function toDate($rs,$field='time'){
  return date('Y-m-d H:i:s',$rs[$field]);
}