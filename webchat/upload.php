<?php
include_once 'include/common.inc.php';
$Filedata=$_FILES['Filedata'];
!$Filedata && exit('请选择文件');
$name=$Filedata['name'];
$tmpname=$Filedata['tmp_name'];
$size=$Filedata['size'];
$filetype=$Filedata['type'];
!$tmpname && exit('上传失败');
$ext=strtolower(end(explode('.', $name)));
$token=$_GET['token'];
!$token && $token=uniqid();
!in_array($ext,array('gif','jpg','png','txt','zip','rar','swf')) && exit('不支持的文件格式');
if(in_array($ext,array('gif','jpg','png'))){
  $info=@getimagesize($tmpname);
  if(!$info || $info[0]<1 || $info[1]<1){
    exit('文件格式有误');
  }
}
$filename='uploads/'.date('Ym/d').'/'.$token.'.'.$ext;
$file=ONEZ_ROOT.'/'.$filename;
mkdirs(dirname($file));
if(@copy($tmpname,$file)){
  exit("ok$filename");
}
exit('没有权限');
?>