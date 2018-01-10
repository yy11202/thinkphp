<?php
include_once('include/common.inc.php');
/*
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
header('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT');
header('Cache-Control: no-cache, must-revalidate');
header('Pramga: no-cache');
Header("Content-type: image/gif");
*/
$uid=(int)GP('uid');
$img=D()->select('status','value',"uid='$uid' and token='avatar'");
!$img && $img='images/faces/2.gif';
header("location:$img");
//echo @readover($img);
?>