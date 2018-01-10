<?php !defined('IN_ONEZ') && exit('Access Denied');
$num_room=D()->rows('rooms','');
$num_user=D()->rows('users','');
$num_online=D()->select('rooms','sum(online)',"");
?>
<div class="clear"></div>
<div id="footer">
  <p>本系统开放[<?php echo $num_room?>]个聊天室 总在线人数[<?php echo $num_online?>]人 注册用户[<?php echo $num_user?>]人</p>
  <p></p>
</div>
</body>
</html>