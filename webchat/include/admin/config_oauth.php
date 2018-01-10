<?php !defined('IN_ONEZ') && exit('Access Denied');
$action=GP('action');
if($action=='save'){
  foreach(html()->postArray() as $key=>$value){
    D()->replace('option',array('token'=>$key,'value'=>$value));
  }
  updatecache();
  html()->showmessage('修改成功',html()->assign('self'),'right');
}elseif($action=='ucinstall'){
  $ucurl=GP('ucurl');
  $ucip=GP('ucip');
  $ucpw=GP('ucpw');
  $app_name=GP('app_name');
  !defined('UC_API') && define('UC_API',true);
  include_once ONEZ_ROOT.'uc_client/client.php';
	global $ucdbcharset;
  $ucinfo=post($ucurl.'/index.php?m=app&a=ucinfo&release='.UC_CLIENT_RELEASE);
  list($status, $ucversion, $ucrelease, $uccharset, $ucdbcharset, $apptypes) = explode('|', $ucinfo);
  $status != 'UC_STATUS_OK' && exit('UCenter 的 URL 地址可能填写错误，请检查');
  $dbcharset = strtolower($dbcharset ? str_replace('-', '', $dbcharset) : $dbcharset);
	$ucdbcharset = strtolower($ucdbcharset ? str_replace('-', '', $ucdbcharset) : $ucdbcharset);
	UC_CLIENT_VERSION > $ucversion && exit('您的 UCenter 服务端版本过低，请升级 UCenter 服务端到最新版本，并且升级，下载地址：http://www.comsenz.com/ 。');
	$dbcharset && $ucdbcharset != $dbcharset && exit('UCenter 数据库字符集与当前应用字符集不一致');
  $app_type='OTHER';
  $app_url=html()->assign('homepage');
  $app_name=ucdata($app_name,1);
  $postdata = "m=app&a=add&ucfounder=&ucfounderpw=".urlencode($ucpw)."&apptype=".urlencode($app_type)."&appname=".urlencode($app_name)."&appurl=".urlencode($app_url)."&appip=&appcharset=".$ucdbcharset.'&appdbcharset='.$ucdbcharset.'&'.$app_tagtemplates.'&release='.UC_CLIENT_RELEASE;
  $ucconfig = post($ucurl.'/index.php', $postdata);
  empty($ucconfig) && exit('向 UCenter 添加应用错误');
  $ucconfig=='-1' && exit('UCenter 创始人密码错误，请重新填写');
  list($appauthkey, $appid) = explode('|', $ucconfig);
  if(empty($appauthkey) || empty($appid)) {
  	exit('通信失败，请检查 UCenter 的URL 地址是否正确');
  }
	list($appauthkey, $appid, $ucdbhost, $ucdbname, $ucdbuser, $ucdbpw, $ucdbcharset, $uctablepre, $uccharset, $ucapi, $ucip) = explode('|', $ucconfig);
  
	$link = mysql_connect($ucdbhost, $ucdbuser, $ucdbpw, 1);
	$uc_connnect = $link && mysql_select_db($ucdbname, $link) ? 'mysql' : '';
  $ucenter=<<<ONEZ
define('UC_CONNECT', '$uc_connnect');
define('UC_DBHOST', '$ucdbhost');
define('UC_DBUSER', '$ucdbuser');
define('UC_DBPW', '$ucdbpw');
define('UC_DBNAME', '$ucdbname');
define('UC_DBCHARSET', '$ucdbcharset');
define('UC_DBTABLEPRE', '`$ucdbname`.$uctablepre');
define('UC_DBCONNECT', '0');
define('UC_KEY', '$appauthkey');
define('UC_API', '$ucurl');
define('UC_CHARSET', '$uccharset');
define('UC_IP', '$ucip');
define('UC_APPID', '$appid');
define('UC_PPP', '20');
ONEZ;
	D()->replace('option',array('token'=>'ucenter','value'=>$ucenter));
  exit('Y?f=message&text='.urlencode('恭喜您,UC安装成功').'&url='.urlencode('?f=config_oauth'));
}
?>
<?php html()->header();
html()->where('高级选项','全局参数');
?>
<div class="bm onez-table-form">
<form id="divForm" method="post">
<input type="hidden" name="action" value="save" />
<h3>UCenter配置
<?php if(!$option['ucenter']){?>
[<a href="javascript:setRecord('整合UCenter','?f=config_oauth_ucenter')">点此安装</a>]
<?}?>
</h3>
<table class="tb">
  <tr>
    <td class="first"><strong>UC配置信息</strong>：</td>
    <td><textarea name="o_ucenter" class="onez-textarea"><?php echo $option['ucenter']?></textarea></td>
    <td></td>
  </tr>
</table>
<h3>授权QQ授权信息  &nbsp; [<a href="http://open.qq.com/" target="_blank">点此申请</a>]</h3>
<table class="tb">
  <tr>
    <td class="first"><strong>APP ID</strong>：</td>
    <td><input type="text" name="o_oauth_qq_appid" class="basic-input onez-input-text" value="<?php echo $option['oauth_qq_appid']?>"/></td>
    <td></td>
  </tr>
  <tr>
    <td><strong>KEY</strong>：</td>
    <td><input type="text" name="o_oauth_qq_appkey" class="basic-input onez-input-text" value="<?php echo $option['oauth_qq_appkey']?>"/></td>
    <td></td>
  </tr>
</table>
<h3>新浪微博授权信息  &nbsp; [<a href="http://open.weibo.com/" target="_blank">点此申请</a>]</h3>
<table class="tb">
  <tr>
    <td class="first"><strong>App Key</strong>：</td>
    <td><input type="text" name="o_oauth_sina_appid" class="basic-input onez-input-text" value="<?php echo $option['oauth_sina_appid']?>"/></td>
    <td></td>
  </tr>
  <tr>
    <td><strong>App Secret</strong>：</td>
    <td><input type="text" name="o_oauth_sina_appkey" class="basic-input onez-input-text" value="<?php echo $option['oauth_sina_appkey']?>"/></td>
    <td></td>
  </tr>
</table>
<h3>人人网授权信息  &nbsp; [<a href="http://dev.renren.com/" target="_blank">点此申请</a>]</h3>
<table class="tb">
  <tr>
    <td class="first"><strong>API ID</strong>：</td>
    <td><input type="text" name="o_oauth_renren_appid" class="basic-input onez-input-text" value="<?php echo $option['oauth_renren_appid']?>"/></td>
    <td></td>
  </tr>
  <tr>
    <td class="first"><strong>API Key</strong>：</td>
    <td><input type="text" name="o_oauth_renren_appkey" class="basic-input onez-input-text" value="<?php echo $option['oauth_renren_appkey']?>"/></td>
    <td></td>
  </tr>
  <tr>
    <td><strong>Secret Key</strong>：</td>
    <td><input type="text" name="o_oauth_renren_appsecret" class="basic-input onez-input-text" value="<?php echo $option['oauth_renren_appsecret']?>"/></td>
    <td></td>
  </tr>
</table>
<p class="onez-form-button">
<input type="submit" name="submit" class="pn pnc" value=" 确定 " style="padding:5px;height:auto" />
</p>
</form>
</div>
<?php html()->footer();?>