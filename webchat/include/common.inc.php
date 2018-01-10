<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE);
ob_start();
if(function_exists(session_cache_limiter))session_cache_limiter('private, must-revalidate');
session_start();
set_magic_quotes_runtime(0);

define('IN_ONEZ', TRUE);
define('ONEZ_ROOT', substr(dirname(__FILE__), 0, -7));
header('Content-Type:text/html;charset=utf-8');

if(defined('AMFPHP_ROOTPATH')){
  global $db,$time,$PHP_SELF,$homepage,$uid;
}
if(PHP_VERSION < '4.1.0') {
	$_GET = &$HTTP_GET_VARS;
	$_POST = &$HTTP_POST_VARS;
	$_COOKIE = &$HTTP_COOKIE_VARS;
	$_SERVER = &$HTTP_SERVER_VARS;
	$_ENV = &$HTTP_ENV_VARS;
	$_FILES = &$HTTP_POST_FILES;
}

require_once ONEZ_ROOT.'/include/global.func.php';
$magic_quotes_gpc = get_magic_quotes_gpc();
$register_globals = @ini_get('register_globals');
if($magic_quotes_gpc) {
  $_POST = ostripslashes($_POST);
  $_GET = ostripslashes($_GET);
}

if(!function_exists('json_decode')){
  include(ONEZ_ROOT.'/include/json.func.php');
}
require_once ONEZ_ROOT.'/onezdata/config.inc.php';

$PHP_SELF = $_SERVER['PHP_SELF'] ? $_SERVER['PHP_SELF'] : $_SERVER['SCRIPT_NAME'];
$SCRIPT_FILENAME = str_replace('\\\\', '/', ($_SERVER['PATH_TRANSLATED'] ? $_SERVER['PATH_TRANSLATED'] : $_SERVER['SCRIPT_FILENAME']));



#如果没有设置程序网址，尝试自动获取
if(!$homepage){
	if(strpos($_SERVER['PHP_SELF'],'/admin/')===false){
	  $homepage=strtolower(($_SERVER['HTTPS'] == 'on' ? 'https' : 'http').'://'.$_SERVER['HTTP_HOST'].substr($_SERVER['PHP_SELF'], 0, strrpos($_SERVER['PHP_SELF'], '/')));
	}else{
	  $twoFile=dirname($_SERVER['PHP_SELF']);
	  $homepage=strtolower(($_SERVER['HTTPS'] == 'on' ? 'https' : 'http').'://'.$_SERVER['HTTP_HOST'].substr($twoFile, 0, strrpos($twoFile, '/')));
	}
}

substr($homepage,-1,1)=='/' && $homepage=substr($homepage,0,-1);

#ini_set('date.timezone','Asia/Shanghai'); //默认时区
$time=time();
$now=date("Y-m-d H:i:s",$time);

$cookie=_cookie('users');
if($cookie){
	$uid=StrCode($cookie,'DECODE');
  if(!$uid){
    _cookie('users','');
    unset($uid);
  }
}
$cacheFile=ONEZ_ROOT.'/onezdata/cache/chat.php';
if(!file_exists($cacheFile)){
  updatecache();
}else{
  @include($cacheFile);
}
#ucenter
$ucenter && @eval($ucenter);