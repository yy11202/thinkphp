<?php $c=GP('c');
$oauth=GP('oauth');
$name=GP('name');
if($c!=''){
  if($oauth){
    $T=D()->one('users','*',"ufrom='$c' and oauth='$oauth'");
    if($T){
      $cookie=StrCode("$T[uid]","ENCODE");
      _cookie('users',$cookie,0);
      checkLevel($uid);
      html()->showmessage('登录成功',$goto ? $goto : html()->assign('url'),'right');
    }else{
      $cookie=StrCode("$c\t$oauth\t$name","ENCODE");
      _cookie('oauth_temp',$cookie,0);
      header('location:/u.php?f=login');
    }
    exit();
  }else{
    include_once(ONEZ_ROOT.'api/oauth/'.$c.'/login.php');
    exit();
	}
}