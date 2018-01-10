<?php
include_once('check.php');

$props=D()->record('props','*',"type='gift' order by pid desc");
?>
<ul class="popBox scroll emote">
<?php
foreach(glob(ONEZ_ROOT.'/images/emote/ww/*.gif') as $v){
$name=substr(basename($v),0,-4);
?>
<li><a href="javascript:SelEmote('ww','<?php echo $name?>')"><img src="images/emote/ww/<?php echo $name?>.gif" /></a></li>
<?php }?>
</ul>