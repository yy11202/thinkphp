<?php
include_once(dirname(dirname(dirname(__FILE__))).'/include/common.inc.php');
class ChatMain {
  public function online($name,$num){
    list($app,$rid)=explode('/',$name);
    D()->update('rooms',array('online'=>(int)$num),"rid='$rid'");
  }
}
?>