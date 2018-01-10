<?php
include_once('check.php');
$action=GP('action');
switch($action){
  case'getusr':
    $uid=(int)GP('uid');
    $rid=(int)GP('rid');
    if($uid<=0){
      $u['uid']=$uid;
      $u['sex']='0';
      $u['flower']='0';
      $u['credit']='0';
      $u['level']='0';
      $u['sign']='';
      $u['username']='游客'.abs($uid);
    }else{
      $u=D()->one('users','uid,username,nickname,sex,g,flower,credit,level,exp,sign',"uid='$uid'");
      $u['sign']=trim($u['sign']);
      $u['nickname']=trim($u['nickname']);
      $u['nickname'] && $u['username']=$u['nickname'];
      $u['level']=getLevel($u['level']);
      $A=D()->record('status','*',"uid='$uid'");
      foreach($A as $rs){
        if(strpos($rs['token'],'master,')===0){
          list(,$_rid)=explode(',',$rs['token']);
          if($_rid==$rid){
            #$rs['token']='master';
            $u['master']=4;
          }
        }
        if($rs['token']=='item'){
          if(file_exists(ONEZ_ROOT.'/images/pics/'.$rs['value'].'.gif')){
            $u['pic']='images/pics/'.$rs['value'].'.gif';
          }
        }
        $u[$rs['token']]=$rs['value'];
      }
      $u['g']==28 && $u['master']=0;
      $u['g']==3 && $u['master']=2;
      $u['g']==2 && $u['master']=3;
      if($option['flowerbaby']==$uid){
        $u['pic']='images/pics/flowerbaby.gif';
      }
    }
    echo onez_json($u);
    break;
  case'bot':
    $A=array('rooms'=>array(),'users'=>array());
    $rooms=D()->record('rooms','rid,roomname',"1 order by online desc");
    foreach($rooms as $rs){
      $A['rooms'][(string)$rs['rid']]=$rs;
    }
    for($i=0;$i<20;$i++){
      $A['users'][(string)$i]=array('uid'=>'0','username'=>'');
    }
    echo onez_json($A);
    break;
  case'update':
    !$uid && exit('N1');
    $T=D()->one('users','*',"uid='$uid'");
    !$T && exit('N2');
    !$exp=$T['exp'];
    !$credit=$T['credit'];
    $updatetime=$T['updatetime'];
    $sec=time()-$updatetime;
    $sec>60 && $sec=60;
    $sec<50 && exit('N3');
    $exp+=(int)$option['add_exp'];
    $credit+=(int)$option['add_credit'];
    D()->update('users',array('updatetime'=>time(),'exp'=>$exp,'credit'=>$credit),"uid='$uid'");
    exit('Y');
    break;
  case'gift':
    $pid=(int)GP('pid');
    $to=(int)GP('to');
    $num=(int)GP('num');
    $to==$uid && showError('您不能赠给自己');
    $num<1 && showError('数量必须大于0');
    $P=D()->one('props','*',"pid='$pid'");
    !$P && showError('礼物不存在');
    $To=D()->one('users','*',"uid='$to'");
    !$To && showError('您要赠与的人不在此房间');
    $price=$P['price'];
    $money=$price*$num;
    $credit<$money && showError('您的金币不足');
    
    $To=getUsrTxt($To);
    $Me=getUsrTxt($U);
    #扣金币
    addcharge($uid,-$money,'送给'.$To.' '.$num.' 个'.$P['name']);
    addcharge($to,$P['amt']*$num,'收到'.$Me.' '.$num.' 个'.$P['name']);
    
    $S='';
    #对方的礼物数
    D()->query("INSERT INTO #_props_user (pid,uid,num,gettime) VALUES ('$pid','$to','$num','$time') ON DUPLICATE KEY UPDATE num=num+$num");
    $total=D()->select('props_user','num',"uid='$to' and pid='$pid'");
    if($P['name']=='鲜花'){
      D()->update('users',array('flower'=>$total),"uid='$to'");
      $S=array(
        'sys'=>array('flower'=>array($to,$total)),
      );
    }
    
   
    showSuccess('<font color="red">★礼物★'.getUsrTxt($U).'送给'.$To.' '.$num.' 个'.$P['name'].'<img src="'.$P['pic'].'" />,共 '.$total.' 个</font>',$S);
    break;
  case'seal':
    $pid=(int)GP('pid');
    $to=(int)GP('to');
    $to==$uid && showError('您不能赠给自己');
    
    $P=D()->one('props','*',"pid='$pid'");
    !$P && showError('印章不存在');
    $To=D()->one('users','*',"uid='$to'");
    !$To && showError('您要赠与的人不在此房间');
    $price=$P['price'];
    $money=$price;
    $credit<$money && showError('您的金币不足');
    
    #扣金币
    D()->update('users',array('credit'=>$credit-$money),"uid='$uid'");
    
    $t=$time+900;
    #对方的礼物数
    D()->query("INSERT INTO #_status (uid,token,value,exptime) VALUES ('$to','seal','$P[pic]','$t') ON DUPLICATE KEY UPDATE value='$P[pic]',exptime='$t'");


    showSuccess('<font color="#ff5000">★印章★'.getUsrTxt($U).'手起章落，'.getUsrTxt($To).'身上出现了一个大大的印章<img src="'.$P['thumb'].'" /></font>',array(
      'sys'=>array('seal'=>array($to,$P['pic'])),
    ));
    break;
  case'face':
    $pid=(int)GP('pid');
    
    $P=D()->one('props','*',"pid='$pid'");
    !$P && showError('头像不存在');
    
    $price=$P['price'];
    $money=$price;
    $credit<$money && showError('您的金币不足');
    
    #扣金币
    D()->update('users',array('credit'=>$credit-$money),"uid='$uid'");
    
    D()->query("INSERT INTO #_status (uid,token,value,exptime) VALUES ('$uid','avatar','$P[pic]',-1) ON DUPLICATE KEY UPDATE value='$P[pic]'");


    showSuccess('',array(
      'sys'=>array('avatar'=>$uid),
    ));
    break;
  case'shebei':
    $def=(int)GP('def');
    if(!$def){
      $shebei=D()->select('status','value',"uid='$uid' and token='shebei'");
      if($shebei){
        exit($shebei);
      }
    }
    $A=array(
      'bandwidth'=>'64',
      'quality'=>'100',
      'fps'=>'24',
      'gain'=>'50',
      'rate'=>'22',
      'mic2'=>'0',
      'micIndex'=>'0',
      'camIndex'=>'0',
    );
    echo onez_json($A);
    break;
  case'shebei_save':
    $P=$_POST;
    unset($P['action']);
    $shebei=onez_json($P);
    D()->query("INSERT INTO #_status (uid,token,value,exptime) VALUES ('$uid','shebei','$shebei',-1) ON DUPLICATE KEY UPDATE value='$shebei'");
    break;
  case'broadcast':
    $rid=(int)GP('rid');
    $msg=trim(GP('msg'));
    $money=(int)$option['lb_credit'];
    !$msg && exit(PrintChat('<font color=red>请填写您要发布的内容</font>'));
    if($money>0){
      $credit<$money && showError('您的金币不足');
      
      addcharge($uid,-$money,'发喇叭“'.$msg.'”');
    }
    $Me=var_export($Me2=getUsrTxt($U),true);
    D()->insert('broadcast',array(
      'fromuid'=>$uid,
      'fromname'=>$Me2,
      'area'=>0,
      'message'=>$msg,
      'time'=>$time,
    ));
    $msg=array(
      'type'=>'notice',
      'usr'=>$Me2,
      'msg'=>$msg,
    );
    $msg=onez_json($msg);
    $code=<<<ONEZ
Boxy.remove();
M().Send('',$msg);
ONEZ;
    exit($code);
    break;
  case'master':
    //showError(var_export($_POST,true));
    $A=array('data'=>array());
    $rid=(int)GP('rid');
    !$rid && showError('请选择房间');
    
    $to=(int)GP('to');
    $addmaster=(int)GP('addmaster');
    $delmaster=(int)GP('delmaster');
    $to && $A['to']=$to;
    
    $addmaster && !$to && showError('请选择对象');
    $delmaster && !$to && showError('请选择对象');
    
    if($addmaster){
      $A['data']['master']=array($to,5);
      D()->query("INSERT INTO #_status (uid,token,value) VALUES ('$to','master,$rid','5') ON DUPLICATE KEY UPDATE value='5'");
    }
    if($delmaster){
      $A['data']['master']=array($to,0);
      D()->delete('status',"uid='$to' and token='master,$rid'");
    }
    
    foreach(array('refresh','out','clear','dropmic') as $v){
      $item=(int)GP($v);$item && $A['data'][$v]='1';
    }
    
    showSuccess('',array(
      'sys'=>array('system'=>$A),
    ));
    break;
  case'setFarID':
    $rid=(int)GP('rid');
    $uid=(int)GP('uid');
    $farID=GP('farID');
    D()->query("INSERT INTO #_status (uid,token,value) VALUES ('$uid','farid,$rid','$farID') ON DUPLICATE KEY UPDATE value='$farID'");
    exit('{}');
    break;
  case'getFarID':
    $rid=(int)GP('rid');
    $uid=(int)GP('uid');
    $farID=D()->select('status','value',"uid='$uid' and token='farid,$rid'");
    exit('{"farID":"'.$farID.'"}');
    break;
  case'menus':
    $rid=(int)GP('rid');
    include_once('onezdata/menu.inc.php');
    
    echo'<option value="">※系统指令※</option>';
    if(ismaster($uid,$rid)){
      foreach($Menu['master'] as $k=>$v){
        if($v[2]>0 && $U['g']<$v[2]){
          continue;
        }
        echo'<option value="'.$v[0].'">'.$v[1].'</option>';
      }
    }
    /*
    echo'<optgroup label="高级功能">';
    foreach($Menu['super'] as $k=>$v){
      echo'<option value="'.$v[0].'">'.$v[1].'</option>';
    }
    echo'</optgroup>';
    */
    break;
  case'checkmsg':
    $rid=(int)GP('rid');
    $to=(int)GP('to');
    $message=GP('message');
    $message=str_replace('&nbsp;',' ',$message);
    if(strpos($message,'//')===0){
      if($to){
        include_once('onezdata/emote2.inc.php');
        $emote=$emote2;
      }else{
        include_once('onezdata/emote1.inc.php');
        $emote=$emote1;
      }
      $msg='';
      $message=trim(substr($message,2));
      foreach($emote as $k=>$v){
        if($k==$message){
          $msg=str_replace('<!--$OBJECT-->',getUsrTxt($to),$v[1]);
          break;
        }
      }
      if($msg){
        echo"F().Sys({msg:".var_export(getUsrTxt($U).$msg,true)."});";
      }
    }elseif(strpos($message,'/c ')===0){
      $Me=var_export($Me2=getUsrTxt($U),true);
      $message=trim(substr($message,3));
      $t=$time+3600;
      if($message=='clear'){
        echo"F().Sys({system:{data:{clear:{}}},msg:gettip('Clear Screen Message',$Me)});";
      }elseif($message=='show shutup'){
        D()->delete("status","token='shutup,$rid' and exptime>-1 and exptime<$time");
        $code='<h3>被禁言名单</h3><table width="550" border="1">';
        $code.='<tr height="25">';
        $code.='<td width="80">&nbsp;昵称</td>';
        $code.='<td width="120">&nbsp;剩余时间</td>';
        $code.='<td width="*">&nbsp;原因</td>';
        $code.='<td width="50">&nbsp;操作</td>';
        $code.='</tr>';
        
        $T=D()->record('status',"value,exptime,uid","token='shutup,$rid' order by exptime desc");
        foreach($T as $rs){
          $code.='<tr>';
          $code.='<td height="20">&nbsp;'.getUsrTxt($rs['uid']).'</td>';
          $code.='<td>&nbsp;'.intval($rs['exptime']-$time).'秒</td>';
          $code.='<td>&nbsp;'.$rs['value'].'</td>';
          $code.='<td>&nbsp;<a href="javascript:sendTo(\'/c free shutup\','.$rs['uid'].')">解除</a></td>';
          $code.='</tr>';
        }
        $code.='</table>';
        exit(PrintChat($code));
      }elseif($message=='show kick'){
        D()->delete("status","token='kick,$rid' and exptime>-1 and exptime<$time");
        $code='<h3>被封昵称名单</h3><table width="550" border="1">';
        $code.='<tr height="25">';
        $code.='<td width="80">&nbsp;昵称</td>';
        $code.='<td width="120">&nbsp;剩余时间</td>';
        $code.='<td width="*">&nbsp;原因</td>';
        $code.='<td width="50">&nbsp;操作</td>';
        $code.='</tr>';
        
        $T=D()->record('status',"value,exptime,uid","token='kick,$rid' order by exptime desc");
        foreach($T as $rs){
          $code.='<tr>';
          $code.='<td height="20">&nbsp;'.getUsrTxt($rs['uid']).'</td>';
          $code.='<td>&nbsp;'.intval($rs['exptime']-$time).'秒</td>';
          $code.='<td>&nbsp;'.$rs['value'].'</td>';
          $code.='<td>&nbsp;<a href="javascript:sendTo(\'/c free kick\','.$rs['uid'].')">解除</a></td>';
          $code.='</tr>';
        }
        $code.='</table>';
        exit(PrintChat($code));
      }elseif($message=='show kill'){
        D()->delete("status","token='kill,$rid' and exptime>-1 and exptime<$time");
        $code='<h3>被封IP名单</h3><table width="550" border="1">';
        $code.='<tr height="25">';
        $code.='<td width="80">&nbsp;IP地址</td>';
        $code.='<td width="120">&nbsp;剩余时间</td>';
        $code.='<td width="*">&nbsp;原因</td>';
        $code.='<td width="50">&nbsp;操作</td>';
        $code.='</tr>';
        
        $T=D()->record('status',"value,exptime,uid","token='kill,$rid' order by exptime desc");
        foreach($T as $rs){
          list($ip,$result)=explode("\t",$rs['value']);
          $code.='<tr>';
          $code.='<td height="20">&nbsp;'.$ip.'</td>';
          $code.='<td>&nbsp;'.intval($rs['exptime']-$time).'秒</td>';
          $code.='<td>&nbsp;'.$result.'</td>';
          $code.='<td>&nbsp;<a href="javascript:sendTo(\'/c free kill\',\''.$ip.'\')">解除</a></td>';
          $code.='</tr>';
        }
        $code.='</table>';
        exit(PrintChat($code));
      }elseif(strpos($message,'subject')===0){
        $result=trim(substr($message,7));
        !$result && exit(PrintChat('<font color=red>话题内容不能为空</font>'));
        D()->update('rooms',array('subject'=>'&nbsp;'.$result),"rid='$rid'");
        $word1=var_export("<font color=red>★系统★管理员{$Me2}更改了房间话题“{$result}”。</font>",true);
        $result=var_export($result,true);
        $code=<<<ONEZ
PrintChat('1',$word1,'im_info');
$('#subject_span').html($result).attr('oneztitle',$result);
ONEZ;
        PrintCode($code);
        
      }else{
        !$to && exit(PrintChat('<font color=red>请选择被管理对象</font>'));
        $To=var_export($To2=getUsrTxt($to),true);
        
        if(strpos($message,'shutup')===0){
          $result=trim(substr($message,6));
          D()->query("INSERT INTO #_status (uid,token,value,exptime) VALUES ('$to','shutup,$rid','$result','$t') ON DUPLICATE KEY UPDATE exptime='$t'");
          $word1=var_export("<font color=red>★系统★{$To2}被管理员{$Me2}禁言!</font>",true);
          $word2=var_export("<font color=\"red\">★系统★你已被管理员{$Me2}禁言!</font>",true);
          $code=<<<ONEZ
PrintChat('1',$word1,'im_info');
if(_uid=='$to'){
  shutup=true;
  PrintChat('2',$word2,'im_info');
};
ONEZ;
          PrintCode($code);
        }elseif($message=='free shutup'){
          $word1=var_export("<font color=red>★系统★{$To2}的禁言已被管理员{$Me2}解除!</font>",true);
          D()->delete("status","token='shutup,$rid' and uid='$to'");
          $word2=var_export("<font color=\"red\">★系统★你的禁言已被管理员{$Me2}解除!</font>",true);
          $code=<<<ONEZ
PrintChat('1',$word1,'im_info');
if(_uid=='$to'){
  shutup=false;
  PrintChat('2',$word2,'im_info');
};
ONEZ;
          PrintCode($code);
        }elseif(strpos($message,'kill')===0){
          checkGrade($to);
          $result=trim(substr($message,5));
          $ip=D()->select('users','thisip',"uid='$to'");
          D()->query("INSERT INTO #_status (uid,token,value,exptime) VALUES ('$to','kill,$rid',\"$ip\t$result\",'$t') ON DUPLICATE KEY UPDATE value=\"$ip\t$result\",exptime='$t'");
          $word1=var_export("<font color=red>★系统★{$To2}被请出聊天室，原因是：$result</font>",true);
          $code=<<<ONEZ
PrintChat('1',$word1,'im_info');
if(_uid=='$to'){
  alert('你被踢出聊天室，原因是: $result');
  window.close();
};
ONEZ;
          PrintCode($code);
        }elseif($message=='free kill'){
          D()->delete("status","token='kill,$rid' and value like '$to%'");
          exit(PrintChat("gettip('Admin Success')",0));
        }elseif(strpos($message,'kick')===0){
          checkGrade($to);
          $result=trim(substr($message,4));
          D()->query("INSERT INTO #_status (uid,token,value,exptime) VALUES ('$to','kick,$rid','$result','$t') ON DUPLICATE KEY UPDATE exptime='$t'");
          $word1=var_export("<font color=red>★系统★{$To2}被请出聊天室，原因是：$result</font>",true);
          $code=<<<ONEZ
PrintChat('1',$word1,'im_info');
if(_uid=='$to'){
  alert('你被踢出聊天室，原因是: $result');
  window.close();
};
ONEZ;
          PrintCode($code);
        }elseif($message=='free kick'){
          D()->delete("status","token='kick,$rid' and uid='$to'");
          exit(PrintChat("gettip('Admin Success')",0));
        }elseif(strpos($message,'warn')===0){
          $result=trim(substr($message,4));
          $code=<<<ONEZ
if(_uid=='$to'){
  alert('$result');
};
ONEZ;
          PrintCode($code);
        /*
        }elseif(strpos($message,'+credit')===0){
          $credit=intval(trim(substr($message,7)));
          $credit<1 && exit(PrintChat('积分必须大于0'));
          $credit>1000 && exit(PrintChat('积分不能大于1000'));
          addcharge($to,$credit,'系统奖励');
          $code=<<<ONEZ
if(_uid=='$to'){
  PrintChat('2','<font color=red>★系统★'+gettip('AddCredit Message')+'</font>','im_info');
};
ONEZ;
          PrintCode($code);
        }elseif(strpos($message,'-credit')===0){
          $credit=intval(trim(substr($message,7)));
          $credit<1 && exit(PrintChat('积分必须大于0'));
          $credit>1000 && exit(PrintChat('积分不能大于1000'));
          addcharge($to,-$credit,'系统惩罚');
          $code=<<<ONEZ
if(_uid=='$to'){
  PrintChat('2','<font color=red>★系统★'+gettip('SubCredit Message')+'</font>','im_info');
};
ONEZ;
          PrintCode($code);
        */
        }elseif(strpos($message,'addmic')===0){
          $sec=intval(trim(substr($message,6)));
          $sec<1 && exit(PrintChat('时间必须大于0'));
          $code=<<<ONEZ
if(_uid=='$to'){
  F().Info({sec:'999'});
};
ONEZ;
          PrintCode($code);
        }elseif(strpos($message,'addmaster')===0){
          D()->query("INSERT INTO #_status (uid,token,value) VALUES ('$to','master,$rid','5') ON DUPLICATE KEY UPDATE value='5'");
          $A=array(
            'system'=>array(
              'data'=>array('master'=>array($to,5)),
            ),
          );
          exit('F().Sys('.onez_json($A).');');
        }elseif(strpos($message,'delmaster')===0){
          D()->delete('status',"uid='$to' and token='master,$rid'");
          $A=array(
            'system'=>array(
              'data'=>array('master'=>array($to,0)),
            ),
          );
          exit('F().Sys('.onez_json($A).');');
        }else{
          exit(PrintChat('请选择您要操作的指令'.$message));
        }
      }
    }
    break;
}
function checkGrade($to){
  global $rid,$uid;
  $masterNames=array('站长','总管','超管','巡管','房管','临管');
  $g1=getG($uid);
  $g2=getG($to);
  if($g1<0){
    $error='没有权限';
  }elseif($g2==$g1){
    $error='你的能力不足以踢动此人';
  }elseif($g2<$g1){
    $error='你连'.$masterNames[$g2].'都敢踢？不想混了？？';
  }
  $error && exit('alert('.var_export($error,true).');');
}
function getG($uid){
  global $rid;
  $u=D()->one('users','uid,username,nickname,sex,g,flower,credit,level,exp,sign',"uid='$uid'");
  $u['master']=-1;
  $A=D()->record('status','*',"uid='$uid'");
  foreach($A as $rs){
    if(strpos($rs['token'],'master,')===0){
      list(,$_rid)=explode(',',$rs['token']);
      if($_rid==$rid){
        $u['master']=4;
      }
    }
  }
  $u['g']==28 && $u['master']=0;
  $u['g']==3 && $u['master']=2;
  $u['g']==2 && $u['master']=3;
  return intval($u['master']);
}
function PrintChat($s,$string=1){
  $string && $s=var_export($s,true);
  return "PrintChat('2',$s,'im_info');";
}
function PrintCode($code){
  $code=var_export($code,true);
  $code=preg_replace("/[\r\n\t\s]+/i",' ',$code);
  exit("F().Sys({code:$code});");
}
function showError($s){
  echo onez_json(array('error'=>$s));
  exit();
}
function showSuccess($s,$data=''){
  echo onez_json(array('success'=>$s,'data'=>$data));
  exit();
}
function getUsrTxt($u){
  !is_array($u) && $u=D()->one('users','*',"uid='$u'");
  $u['nickname'] && $u['username']=$u['nickname'];
  return'<a href="javascript:toUsr('.$u['uid'].')" class="sex'.$u['sex'].'">'.$u['username'].'</a>';
}