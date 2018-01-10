<?php
include_once('include/common.inc.php');
$action=GP('action');
switch($action){
  case'init':
    $A=array('rooms'=>array(),'users'=>array());
    $rooms=D()->record('rooms','rid,roomname,videonum,botnum',"1 order by online desc");
    foreach($rooms as $rs){
      $A['rooms']['r'.$rs['rid']]=$rs;
    }
    $users=D()->record('users','uid,username',"g='-2'");
    foreach($users as $rs){
      $A['users']['u'.$rs['uid']]=$rs;
    }
    echo onez_json($A);
    break;
  case'add':
    exit();
    for($i=$i;$i<=100;$i++){
      $A=array(
        'g'=>'-2',
        'loginEmail'=>'user'.$i.'@cz886.com',
        'username'=>'游客'.$i,
        'password'=>md5('951753'),
        'infotime'=>time()-60000*rand(1,20),
        'flower'=>rand(1,100),
        'exp'=>rand(1,2000),
        'credit'=>rand(1,1000),
        'level'=>rand(1,3),
        'sex'=>rand(0,2),
      );
      $uid=D()->insert('users',$A);
      $pic=D()->select('props','pic',"type='face' order by rand()");
      D()->query("INSERT INTO #_status (uid,token,value,exptime) VALUES ('$uid','avatar','$pic',-1) ON DUPLICATE KEY UPDATE value='$pic'");
    }
    echo'OK';
    break;
}