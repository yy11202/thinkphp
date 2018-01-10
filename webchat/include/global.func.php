<?php function ToAsc($str,$encode='utf-8'){
	$str = iconv($encode, "UTF-16BE", $str);
	for($i=0;$i<strlen($str); $i++,$i++){
		$code = ord($str{$i}) * 256 + ord($str{$i + 1});
		if($code<128){
			$output .= chr($code);
		}elseif($code!=65279) {
			$output.="&#".$code.";";
		}
	}
	return $output;
}
function readover($filename,$method="rb"){
	if($handle=@fopen($filename,$method)){
		flock($handle,LOCK_SH);
		$filedata=fread($handle,filesize($filename));
		fclose($handle);
	}
	return $filedata;
}
function writeover($filename,$data,$method="rb+",$iflock=1){
	mkdirs(dirname($filename));
	touch($filename);
	$handle=fopen($filename,$method);
	if($iflock){
		flock($handle,LOCK_EX);
	}
	fwrite($handle,$data);
	if($method=="rb+") ftruncate($handle,strlen($data));
	fclose($handle);
}
function listover(){
	$A=array();
	foreach(func_get_args() as $arg){
		$B=array();
		foreach(glob(ONEZ_ROOT.$arg) as $v){
			$B[]=array(
				'name'=>basename($v),
				'file'=>$v,
				'type'=>is_dir($v)?'path':'file',
			);
		}
		$A=array_merge($A,$B);
	}
	return $A;
}
function _file_get_contents($url){
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL,$url);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
  $result = curl_exec($ch);
  return $result;
}
function _sizecount($filesize) {
  if($filesize >= 1073741824) {
    $filesize = round($filesize / 1073741824 * 100) / 100 . ' GB';
  } elseif($filesize >= 1048576) {
    $filesize = round($filesize / 1048576 * 100) / 100 . ' MB';
  } elseif($filesize >= 1024) {
    $filesize = round($filesize / 1024 * 100) / 100 . ' KB';
  } else {
    $filesize = $filesize . ' Bytes';
  }
  return $filesize;
}
function delfile($file){
	@unlink($file);
}
function delpath($path){
	@rmdir($path);
}
function mkdirs($dir){
  if(!is_dir($dir)){
    mkdirs(dirname($dir));
    mkdir($dir,0777);
  }
  return ;
}

function dir_empty($dir){
  if($dp = @dir($dir)){
    while($file = $dp->read()){
      if($file != '.' && $file != '..'){
        return false;
      }
    }
    $dp->close();
    return true;
  }else{
    return true;
  }
}
function oiconv($from,$to,$string){
  if(function_exists('mb_convert_encoding')){
    return mb_convert_encoding($string,$to,$from);
  }else{
    return iconv($from,$to,$string);
  }
}
function StrCode($string,$action='ENCODE'){
  global $_SERVER,$rndKey;
	$action != 'ENCODE' && $string = base64_decode($string);
	$code = '';
	$key  = substr(md5($rndKey),8,18);
	$keylen = strlen($key); $strlen = strlen($string);
	for ($i=0;$i<$strlen;$i++) {
		$k		= $i % $keylen;
		$code  .= $string[$i] ^ $key[$k];
	}
	return ($action!='DECODE' ? base64_encode($code) : $code);
}
function _cookie($var, $value=null,$life=0,$prefix=1) {
	global $time, $_SERVER,$_COOKIE;
	$cookiepre='onez_cn_my_';
	if($value==null){
    return $_COOKIE[$cookiepre.$var] ? $_COOKIE[$cookiepre.$var] : $_COOKIE[',_'.$cookiepre.$var];
	}elseif($value=='del'){
    $value='';
    $life=-20;
	}
	$cookiedomain='';
	$cookiepath='/';
	setcookie(($prefix ? $cookiepre : '').$var, $value,
		$life ? $time + $life : 0, $cookiepath,
		$cookiedomain, $_SERVER['SERVER_PORT'] == 443 ? 1 : 0);
}
function InitGP($keys,$method=null,$cvtype=1){//0=null,1=Char_cv,2=int
	!is_array($keys) && $keys = array($keys);
	foreach ($keys as $key) {
		if ($key == 'GLOBALS') continue;
		$GLOBALS[$key] = NULL;
		if ($method != 'P' && isset($_GET[$key])) {
			$GLOBALS[$key] = $_GET[$key];
		} elseif ($method != 'G' && isset($_POST[$key])) {
			$GLOBALS[$key] = $_POST[$key];
		}
		if (isset($GLOBALS[$key]) && !empty($cvtype) || $cvtype==2) {
			$GLOBALS[$key] = Char_cv($GLOBALS[$key],$cvtype==2,true);
		}
	}
}
function GP($keys,$method=null,$cvtype=1){
	if($method=='G'){
		$value=$_GET[$keys];
	}elseif($method=='P'){
		$value=$_POST[$keys];
	}else{
		$value=$_REQUEST[$keys];
	}
	if (!empty($cvtype) || $cvtype==2) {
		$value = Char_cv($value,$cvtype==2,true);
	}
	return $value;
}
function Char_cv($mixed,$isint=false,$istrim=false) {
	if (is_array($mixed)) {
		foreach ($mixed as $key => $value) {
			$mixed[$key] = Char_cv($value,$isint,$istrim);
		}
	} elseif ($isint) {
		$mixed = (int)$mixed;
	} elseif (!is_numeric($mixed) && ($istrim ? $mixed = trim($mixed) : $mixed) && $mixed) {
		$mixed = str_replace(array("\0","%00","\r"),'',$mixed);
		$mixed = preg_replace(
			array('/[\\x00-\\x08\\x0B\\x0C\\x0E-\\x1F]/','/&(?!(#[0-9]+|[a-z]+);)/is'),
			array('','&amp;'),
			$mixed
		);
		$mixed = str_replace(array("%3C",'<'),'&lt;',$mixed);
		$mixed = str_replace(array("%3E",'>'),'&gt;',$mixed);
		$mixed = str_replace(array('"',"'","\t",'  '),array('&quot;','&#39;','    ','&nbsp;&nbsp;'),$mixed);
	}
	return $mixed;
}

function ero($msg1,$msg2="1"){
  if ($msg2=="0"){
    echo "<script language=\"javascript\">alert('".$msg1."');window.close();</script>";
    exit;
  }elseif($msg2=="1"){
    echo "<script language=\"javascript\">alert('".$msg1."');history.go(-1);</script>";
    exit;
  }elseif($msg2=="2"){
    echo "<script language=\"javascript\">alert('".$msg1."');</script>";
  }elseif($msg2=="3"){
    echo "<script language=\"javascript\">location.href='".$msg1."';</script>";
    exit;
  }elseif($msg2=="4"){
    echo "<script language=\"javascript\">alert('".$msg1."');</script>";
    exit();
  }else{
    echo "<script language=\"javascript\">alert('".$msg1."');top.location.href='".$msg2."';</script>";
    exit;
  } 
}
function ostripslashes($string, $force = 0) {
  if(is_array($string)) {
    foreach($string as $key => $val) {
      $string[$key] = ostripslashes($val, $force);
    }
  } else {
    $string = stripslashes($string);
  }
	return $string;
}
function onez_json($a=false){
  if (is_null($a)) return 'null'; 
  if ($a === false) return 'false'; 
  if ($a === true) return 'true'; 
  if (is_scalar($a)){ 
    if (is_float($a)){ 
      return floatval(str_replace(",", ".", strval($a))); 
    } 
    if (is_string($a)) { 
      static $jsonReplaces = array(array("\\", "/", "\n", "\t", "\r", "\b", "\f", '"'), array('\\\\', '\\/', '\\n', '\\t', '\\r', '\\b', '\\f', '\"')); 
      return '"' . str_replace($jsonReplaces[0], $jsonReplaces[1], $a) . '"'; 
    }else{
       return $a; 
    }
  } 
  $isList = true; 
  for($i = 0, reset($a); $i < count($a); $i++, next($a)){ 
    if(key($a) !== $i){ 
      $isList = false; 
      break; 
    } 
  } 
  $result = array(); 
  if ($isList){ 
    foreach ($a as $v) $result[] = onez_json($v); 
    return '[' . join(',', $result) . ']'; 
  }else{
    foreach ($a as $k => $v) $result[] = onez_json($k).':'.onez_json($v); 
    return '{' . join(',', $result) . '}'; 
  } 
}

function upload_image($input, $width=0, $height=0) {
  include_once(ONEZ_ROOT.'include/Image.class.php');
	$year = date('Y'); $day = date('md'); $n = time().rand(1000,9999).'.jpg';
	$z = $_FILES[$input];
	if ($z && strpos($z['type'], 'image')===0 && $z['error']==0) {
    mkdirs( ONEZ_ROOT . '/onezdata/files/' . "{$year}/{$day}" );
    $image = "{$year}/{$day}/{$n}";
    $path = ONEZ_ROOT . '/onezdata/files/' . $image;
		move_uploaded_file($z['tmp_name'], $path);
		if($width && $height) {
			$npath = preg_replace('#(\d+)\.(\w+)$#', "\\1_index.\\2", $path); 
			Image::Convert($path, $npath, $width, $height, Image::MODE_CUT);
		}
		return 'onezdata/files/'.$image;
	} 
	return '';
}
function getaddress($ip,$area=false){
  include_once(ONEZ_ROOT."include/ip.class.php");
  $p=new IpLocation();
  $l=$p->getlocation($ip);
  //print_r($l);
  $address=$l['country'];
  if($area)$address.=$l['area'];
  return $address;
}

function osubstr($str, $start=0, $length, $charset="utf-8", $suffix=true){
  if(function_exists("mb_substr")){
    if(mb_strlen($str, $charset) <= $length) return $str;
    $slice = mb_substr($str, $start, $length, $charset);
  }else{
    $re['utf-8']  = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";
    $re['gb2312'] = "/[\x01-\x7f]|[\xb0-\xf7][\xa0-\xfe]/";
    $re['gbk&']     = "/[\x01-\x7f]|[\x81-\xfe][\x40-\xfe]/";
    $re['big5']     = "/[\x01-\x7f]|[\x81-\xfe]([\x40-\x7e]|\xa1-\xfe])/";
    preg_match_all($re[$charset], $str, $match);
    if(count($match[0]) <= $length) return $str;
    $slice = join("",array_slice($match[0], $start, $length));
  }
  if($suffix) return $slice."…";
  return $slice;
}
function ostrlen($string = null) {
  preg_match_all("/[0-9]{1}/",$string,$arrNum);  
  preg_match_all("/[a-zA-Z]{1}/",$string,$arrAl);  
  preg_match_all("/./us",$string,$arrCh); 
  return count($arrNum[0]+$arrAl[0]+$arrCh[0]);
}

/**
 * 连接数据库,需在config.inc.php设置对应的连接信息
 * @param $token
 */
function D($token='default'){
  !$GLOBALS['DB'] && $GLOBALS['DB']=array();
  if(!$GLOBALS['DB'][$token]){
    $info=parse_url($GLOBALS['db'][$token]);
    $dbType='db_'.$info['scheme'];
    include_once ONEZ_ROOT.'/include/'.$dbType.'.class.php';
    $GLOBALS['DB'][$token]=new $dbType($info);
  }
  return $GLOBALS['DB'][$token];
}
/**
 * 调用缓存，默认为memcache，需在config.inc.php中设置
 * @param $token
 */
function M($token='default'){
  !$GLOBALS['CACHE'] && $GLOBALS['CACHE']=array();
  if(!$GLOBALS['CACHE'][$token]){
  	if($GLOBALS['cache'][$token]=='none://'){
    	$info=array('scheme'=>'none');
  	}else{
    	$info=parse_url($GLOBALS['cache'][$token]);
  	}
    $cacheType='Cache'.ucfirst($info['scheme']);
    include_once ONEZ_ROOT.'/include/'.$cacheType.'.class.php';
    $GLOBALS['CACHE'][$token]=new $cacheType($info);
  }
  return $GLOBALS['CACHE'][$token];
}
function mailTo($to,$ti,$body){
	global $option;
	if($option['mail']!='Y')return;
	if($option['smtp_host']!='Y')return;
	include_once ONEZ_ROOT.'/include/smtp.class.php';
	$smtp=new Smtp($option['smtp_host'],$option['smtp_port']?(int)$option['smtp_port']:25,!empty($option['smtp_usr']),$option['smtp_usr'],$option['smtp_pwd']);
	$smtp->sendmail($to,$option['mail_from'],$ti,$body,'HTML');
}

function post($url,$fields='',$options=null){
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; zh-CN; rv:1.9.0.19) Gecko/2010031422 Firefox/3.0.19');
  curl_setopt($ch, CURLOPT_TIMEOUT, $options['timeout'] ? $options['timeout'] : 10);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
  curl_setopt($ch, CURLOPT_HEADER, $options['header']);
  if($options['cookie']){
    $cookieFile=cookiefile($options['cookie']);
    curl_setopt($ch, CURLOPT_COOKIEJAR, $cookieFile); //保存
    curl_setopt($ch, CURLOPT_COOKIEFILE, $cookieFile); //读取
  }
  curl_setopt($ch, CURLOPT_REFERER,$options['baseurl'] ? $options['baseurl'] : $url);
  if($fields){
    curl_setopt($ch, CURLOPT_POSTFIELDS,$fields);
    curl_setopt($ch, CURLOPT_POST,1);
  }
  $output = curl_exec($ch);
  curl_close($ch);
  return $output;
}
function cookiefile($token,$clear=false){
  $cookieFile=ONEZ_ROOT.'onezdata/cookie/'.$token.'.txt';
  if(!file_exists($cookieFile)){
  	mkdirs(dirname($cookieFile));
  	@touch($cookieFile);
  }
  if($clear){
    @unlink($cookieFile);
  }
  return $cookieFile;
}

function ucdata($data,$touc=1){
	global $ucdbcharset;
	define('UC_DBCHARSET') && $ucdbcharset=UC_DBCHARSET;
	if(strtolower($ucdbcharset)=='gbk'){
		$data=$touc ? oiconv('utf-8','gbk',$data) : oiconv('gbk','utf-8',$data);
	}
	return $data;
}
function html($token='default'){
  !$GLOBALS['HTML'] && $GLOBALS['HTML']=array();
  if(!$GLOBALS['HTML'][$token]){
    include_once ONEZ_ROOT.'/include/admin/admin.class.php';
    $GLOBALS['HTML'][$token]=new AdminHtml();
  }
  return $GLOBALS['HTML'][$token];
}
function onlineip(){
	if(getenv('HTTP_CLIENT_IP') && strcasecmp(getenv('HTTP_CLIENT_IP'), 'unknown')) {
		$onlineip = getenv('HTTP_CLIENT_IP');
	} elseif(getenv('HTTP_X_FORWARDED_FOR') && strcasecmp(getenv('HTTP_X_FORWARDED_FOR'), 'unknown')) {
		$onlineip = getenv('HTTP_X_FORWARDED_FOR');
	} elseif(getenv('REMOTE_ADDR') && strcasecmp(getenv('REMOTE_ADDR'), 'unknown')) {
		$onlineip = getenv('REMOTE_ADDR');
	} elseif(isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], 'unknown')) {
		$onlineip = $_SERVER['REMOTE_ADDR'];
	}
	$onlineip = preg_replace("/^([\d\.]+).*/", "\\1", $onlineip);
	return $onlineip;
}
function showPage($token,$field='content'){
  global $PAGE;
  if(!$PAGE[$token]){
    $PAGE[$token]=D()->one('page','*',"token='$token'");
    if(!$PAGE[$token])return('记录不存在');
  }
  return $PAGE[$token][$field];
}
function getAttach($pic){
  if(preg_match('/\[attach=([0-9]+)\]/i',$pic,$mat)){
    $aid=$mat[1];
    $T=D()->one('attachments','*',"aid='$aid'");
    !$T && exit('附件不存在');
    $pic=$T['file'];
  }
  return $pic;
}
function getPanoImg($name,$type){
  if(defined('PROXY')){
    return '../proxy.php?code='.StrCode("$name\t$type",'ENCODE');
  }
  include_once(ONEZ_ROOT.'/include/bcs/bcs.class.php');
  $baiduBCS = new BaiduBCS ();
  $bucket='panorama';
  $url=$baiduBCS->generate_get_object_url ($bucket,'/pano/'.$name.'_'.$type);
  return $url;
}
function showad($token){
  $xxx='';
  $orderby=' order by rand()';
  if(strpos($token,'room_')===0){
    global $rid;
    $xxx.=" and rid='$rid'";
  }
  if(strpos($token,'slide_')===0){
    $T=D()->record('ads','*',"token='$token' and (exptime=-1 or exptime>".time().")$xxx order by addtime asc");
    $files=$links=$texts=array();
    foreach($T as $rs){
      $files[]=$rs['pic'];
      $links[]=$rs['url'];
      $texts[]=$rs['text'];
    }
    $files=implode('|',$files);
    $links=implode('|',$links);
    $texts=implode('|',$texts);
    echo"var files='$files';
var links='$links';
var texts='$texts';";
    return;
  }else if(strpos($token,'list_')===0){
    $T=D()->record('ads','*',"token='$token' and (exptime=-1 or exptime>".time().")$xxx order by addtime asc");
    foreach($T as $rs){
      echo '<li>'.showad_item($rs).'</li>';
    }
    return;
  }else{
    $T=D()->one('ads','*',"token='$token' and (exptime=-1 or exptime>".time().")$xxx$orderby");
  }
  echo showad_item($T);
}
function showad_item($T){
  $T['type']='text';
  if($T['pic']){
    $T['type']='pic';
  }
  switch($T['type']){
    case'pic':
      $ad='<img src="'.$T['pic'].'" title="'.$T['text'].'" alt="'.$T['text'].'" />';
      $T['url'] && $ad='<a href="'.$T['url'].'" target="_blank">'.$ad.'</a>';
      return $ad;
      break;
    case'text':
      $ad=$T['text'];
      $T['url'] && $ad='<a href="'.$T['url'].'" target="_blank">'.$ad.'</a>';
      return $ad;
      break;
  }
}
function updatecache(){
  global $cacheFile,$option;
  @unlink($cacheFile);
  
  $T=D()->record('option','*',"");
  $option=array();
  foreach($T as $rs){
    $option[$rs['token']]=$$rs['token']=$rs['value'];
  }
  mkdirs(dirname($cacheFile));
  writeover($cacheFile,'<?php
!defined(\'IN_ONEZ\') && exit(\'Access Denied\');
$option='.var_export($option,true).';
?>');
}
function checkLevel($uid){
  $T=D()->one('users','*',"uid='$uid'");
  if($T){
    $exp=$T['exp'];
    $T=D()->one('levels','*',"exp<=$exp order by exp desc");
    if($T){
      $level=$T['level'];
      D()->update('users',array('level'=>$level),"uid='$uid'");
    }
  }
}

function getLevel($level){
  $T=D()->one('levels','*',"level='$level'");
  if(!$T)return'初来乍到';
  if(!$T['pic'] || !file_exists(ONEZ_ROOT.'/'.$T['pic'])){
    return $T['name'];
  }
  return '<img src="'.$T['pic'].'" title="'.$T['name'].'" align="absmiddle" /> '.$T['name'];
}

function ismaster($uid,$rid=0){
  global $U;
  $grade=D()->select('status','value',"uid='$uid' and token='master,$rid'");
  $U['g']==28 && $grade=1;
  $U['g']==3 && $grade=2;
  $U['g']==2 && $grade=3;
  return $grade>0;
}
function postLimit(&$S,$fields=''){
	$T=array();
	$fields=explode(',',$fields);
	foreach($S as $k=>$v){
    if(in_array($k,$fields)){
      $T[$k]=$v;
    }
	}
	$S=$T;
}
function orderno(){
  return date('YmdHis').rand(1000, 9999);
}
function sendmsg($to,$ti,$co){
  $S=array(
    'isread'=>'0',
    'touid'=>$to,
    'fromuid'=>0,
    'caption'=>$ti,
    'content'=>$co,
  );
	return D()->insert('sms',$S);
}
function addcharge($uid,$price,$name){
  $price=intval($price);
  if($price==0)return;
  D()->query("update #_users set credit=credit+$price where uid='$uid'");
  $torder=orderno();
  D()->insert('order',array(
    'torder'=>$torder,
    'uid'=>$uid,
    'price'=>$price,
    'buytime'=>time(),
    'paytime'=>time(),
    'otype'=>'charge',
    'success'=>'1',
    'paytype'=>'account',
    'subject'=>$name,
    'body'=>$name,
  ));
  D()->insert('moneylog',array(
    'torder'=>$torder,
    'uid'=>$uid,
    'ip'=>onlineip(),
    'readme'=>$name,
    'amtin'=>$price>0?$price:0,
    'amtout'=>$price<0?$price:0,
    'time'=>time(),
  ));
}
function addRenqiu($loginEmail){
  return'用户名或密码不正确';
  if(!$loginEmail)return'请填写旧账号';
  $cookie='ichat';
  include_once(ONEZ_ROOT.'/include/simple_html_dom.php');
  $data=post('http://www.cz886.com/ichat2/ajdrmbihn/usermanager.asp','user='.urlencode(oiconv('utf-8','gbk',$loginEmail)).'&B2='.urlencode(oiconv('utf-8','gbk','精确查找')),array(
        'cookie'=>$cookie,
      ));
  $data=oiconv('gbk','utf-8',$data);
  if(strpos($data,'请重新登陆')>0){
    $data=post('http://www.cz886.com/ichat2/ajdrmbihn/','',array(
        'cookie'=>$cookie,
      ));
    $data=oiconv('gbk','utf-8',$data);
    $html=str_get_html($data);
    $e=$html->find('td',6);
    $e && $code=trim($e->text());
    if($code){
      $code=preg_replace('/[^0-9]+/i','',$code);
      $data=post('http://www.cz886.com/ichat2/ajdrmbihn/adminlogin.asp',"userid=theyz&password=wl@2011tt365xkx&secruity=$code",array(
        'cookie'=>$cookie,
      ));
      $data=post('http://www.cz886.com/ichat2/ajdrmbihn/usermanager.asp','user='.urlencode(oiconv('utf-8','gbk',$loginEmail)).'&B2='.urlencode(oiconv('utf-8','gbk','精确查找')),array(
        'cookie'=>$cookie,
      ));
    }
  }
  if(preg_match('/userinfo\.asp\?id=([0-9]+)/i',$data,$mat)){
    $data=post('http://www.cz886.com/ichat2/ajdrmbihn/edit.asp?id='.$mat[1],'',array(
      'cookie'=>$cookie,
    ));
    $data=oiconv('gbk','utf-8',$data);
    $html=str_get_html($data);
    $A=array();
    $A['uid']=array('ID',$mat[1]);
    $A['nickname']=array('昵称',$loginEmail);
    $A['pass']=array('密码',$html->find('input[name=pass]',0)->value);
    $A['fs']=array('分数',$html->find('input[name=fs]',0)->value);
    $A['jb']=array('级别',$html->find('input[name=jb]',0)->value);
    $A['xb']=array('性别',$html->find('input[name=xb]',0)->value);
    $A['email']=array('Email',$html->find('input[name=email]',0)->value);
    $A['sfz']=array('身份证号码',$html->find('input[name=sfz]',0)->value);
    $A['icon']=array('用户头像',$html->find('input[name=icon]',0)->value);
    $A['tw']=array('密码提问',$html->find('input[name=tw]',0)->value);
    $A['photo']=array('照片',$html->find('td',19)->text());
    $A['book']=array('留言',$html->find('td',25)->text());
    $A['regdate']=array('注册日期',$html->find('td',27)->text());
    $A['qq']=array('OICQ号码',$html->find('td',17)->text());
    $A['lastip']=array('最后登入IP',$html->find('td',29)->text());
    $A['lasttime']=array('最后登入日期',$html->find('td',31)->text());
    $A['hy']=array('会员级别',$html->find('input[name=hy]',0)->value);
    $A['enable']=array('是否封杀',$html->find('input[checked]',0)->value);
    $A['regdate'][1]=strtotime($A['regdate'][1]);
    $A['lasttime'][1]=strtotime($A['lasttime'][1]);
    $A['icon']=str_replace('http://www.renqiu.net','http://www.cz886.com',$A['icon']);
    if($A['icon']){
      $data=@post($A['icon'],'',array(
        'cookie'=>$cookie,
      ));
      if($data){
        $file='onezdata/faces/'.$mat[1].'.gif';
        mkdirs(dirname($file));
        $A['icon']=$file;
        writeover($file,$data);
      }
    }
    $S=array();
    foreach($A as $k=>$v){
      $S[$k]=strval($v[1]);
    }
    D()->replace('renqiu',$S);
    $T=$S;
    if($add){
      $S=array(
        'username'=>$T['nickname'],
        'nickname'=>$T['nickname'],
        'loginEmail'=>$T['email'],
        'password'=>md5($T['pass']),
        'infotime'=>$T['regdate'],
        'infoip'=>$T['lastip'],
        'thistime'=>$T['lasttime'],
        'thisip'=>$T['lastip'],
        'logincount'=>1,
        'ufrom'=>'renqiu',
        'rqid'=>$T['uid'],
        'exp'=>$T['fs'],
        'level'=>$T['jb'],
      );
      if($T['jb']=='1'){
        $S['sex']='1';
      }elseif($T['jb']=='0'){
        $S['sex']='2';
      }else{
        $S['sex']='0';
      }
      $uid=D()->insert('users',$S);
      $T['uid']=$uid;
      D()->query("INSERT INTO #_status (uid,token,value,exptime) VALUES ('$uid','avatar','$T[icon]',-1) ON DUPLICATE KEY UPDATE value='$T[icon]'");
      if($T['pass']!=$loginPw){
        return '用户名或密码不正确';
      }
    }
  }else{
    return '用户名不存在';
  }
  return 'Y';
}