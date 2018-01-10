<?php
include_once('include/common.inc.php');
if(!$uid){
  header("location:u.php?f=login&goto=".$_SERVER['REQUEST_URI']);
  exit();
}
$U=D()->one('users','*',"uid='$uid'");
foreach($U as $k=>$v)$$k=$v;
$nickname=trim($nickname);
$nickname && $username=$nickname;