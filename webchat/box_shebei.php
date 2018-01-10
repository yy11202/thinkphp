<?php
include_once('check.php');
?>
<form method="post" id="shebei">
<h3>视频设置</h3>
<table width="100%" class="shebei">
  <tr>
    <td width="60" align="right">视频驱动</td>
    <td><select id="videolist" name="camIndex"></select>&nbsp;&nbsp;</td>
  </tr>
  <tr>
    <td align="right">带宽</td>
    <td>
    <select name="bandwidth">
<?php foreach(array(0,8,16,32,64,128,256) as $v){?>
<option value="<?php echo $v?>"><?php echo $v?> KB</option>
<?php }?>
    </select>&nbsp;&nbsp;
当前输出视频可以使用的最大带宽量
    </td>
  </tr>
  <tr>
    <td align="right">品质</td>
    <td>
    <select name="quality">
<?php for($i=0;$i<=10;$i++){
$k=$i*10;?>
<option value="<?php echo $k?>"><?php echo $k?></option>
<?php }?>
    </select>&nbsp;&nbsp;
   范围从 1（最低品质）到 100（最高品质）
    </td>
  </tr>
  <tr>
    <td align="right">视频速率</td>
    <td>
    <select name="fps">
<?php foreach(array(5,15,24,32,64) as $v){?>
<option value="<?php echo $v?>"><?php echo $v?> fps</option>
<?php }?>
    </select>&nbsp;&nbsp;
    </td>
  </tr>
</table>
<h3>语音设置</h3>
<table width="100%" class="shebei">
  <tr>
    <td width="60" align="right">语音驱动</td>
    <td><select id="audiolist" name="micIndex"></select>&nbsp;&nbsp;</td>
  </tr>
  <tr>
    <td align="right">增益</td>
    <td>
    <select name="gain">
<?php for($i=0;$i<=10;$i++){
$k=$i*10;?>
<option value="<?php echo $k?>"><?php echo $k?></option>
<?php }?>
    </select>&nbsp;&nbsp;
    (0～100) 
    0 表示没有音量，50 表示正常音量
    </td>
  </tr>
  <tr>
    <td align="right">采样率</td>
    <td>
    <select name="rate">
<?php foreach(array(5,8,11,22,44) as $v){?>
<option value="<?php echo $v?>"><?php echo $v?> kHz</option>
<?php }?>
    </select>&nbsp;&nbsp;
较高的采样率可提高声音品质，但需要更多网速
    </td>
  </tr>
</table>
<br />
<table width="100%">
  <tr>
    <td>
      <input type="button" class="btn_normal" value="确定" onclick="SetVideo()" />
      <input type="button" class="btn_normal" value="恢复默认" onclick="LoadShebei(1)" />
    </td>
  </tr>
</table>
</form>