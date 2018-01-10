<?php !defined('IN_ONEZ') && exit('Access Denied');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $roomname?></title>
<link href="images/room.css?t=<?php echo filemtime('images/room.css')?>" rel="stylesheet" type="text/css" />
<link href="images/boxy/boxy.css" rel="stylesheet" type="text/css" />
<script src="js/jquery-1.6.2.min.js" type="text/javascript"></script>
<script type="text/javascript" src="js/jquery.boxy.js"></script>
<script src="js/limit.js" type="text/javascript"></script>
<script type="text/javascript">
function CheckPwd(){
  $.ajax({
    url:'room.php?action=checkpwd&rid=<?php echo $rid?>',
    type:'post',
    cache: false,
    dataType:'html',
    data:{pass:$('#pass').val()},
    success:function(data){
      if(data=='Y'){
        location.reload();
      }else{
        alert(data);
      }
    }
  });
}
$(document).ready(function(){
  Boxy.remove();
  Boxy.load('box_roompass.php',{
    title:'请输入房间密码',
    closeable:false,
    modal:false,
    draggable:false
  });
});
document.onmousedown = Click2;
</script>
<style type="text/css">
.boxy-content{width:210px;}
.boxy-content table{width:210px;}
</style>
</head>
<body scroll="no" style="background:url(images/bg.jpg)"></body>
</html>