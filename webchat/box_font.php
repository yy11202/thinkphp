<?php
include_once('check.php');
$colors=array('#E53333', '#E56600', '#FF9900', '#64451D', '#DFC5A4', '#FFE500', '#009900', '#006600', '#99BB00', '#B8D100', '#60D978', '#00D5FF', '#337FE5', '#003399', '#4C33E5', '#9933E5', '#CC33E5', '#EE33EE', '#FF00FF', '#CCCCCC', '#999999', '#666666', '#333333', '#000000');
$fontName=array(
		'SimSun' => '宋体',
		'NSimSun' => '新宋体',
		'FangSong_GB2312' => '仿宋_GB2312',
		'KaiTi_GB2312' => '楷体_GB2312',
		'SimHei' => '黑体',
		'Microsoft YaHei' => '微软雅黑',
		'Arial' => 'Arial',
		'Arial Black' => 'Arial Black',
		'Times New Roman' => 'Times New Roman',
		'Courier New' => 'Courier New',
		'Tahoma' => 'Tahoma',
		'Verdana' => 'Verdana'
);
?>
<table id="fontbox">
  <tr>
    <td>
      <a id="font_b" class="font" href="javascript:setFont('b')"><em></em></a>
      <a id="font_i" class="font" href="javascript:setFont('i')"><em></em></a>
      <a id="font_u" class="font" href="javascript:setFont('u')"><em></em></a>
    </td>
    <td>
      <select id="font_name" onchange="setFont('name',this.value)">
        <?php foreach($fontName as $k=>$v){?>
        <option value="<?php echo $k?>"><?php echo $v?></option>
        <?php }?>
      </select>
    </td>
    <td>
      <select id="font_size" onchange="setFont('size',this.value)">
        <?php for($i=9;$i<=18;$i++){?>
        <option value="<?php echo $i?>"><?php echo $i?></option>
        <?php }?>
      </select>
    </td>
  </tr>
  <tr>
    <td colspan="3">
     <?php
     $k=0;
     foreach($colors as $color){
     $k++;?>
      <a class="font color" color="<?php echo $color?>" href="javascript:setFont('color','<?php echo $color?>')"><em style="background:<?php echo $color?>"></em></a>
     <?php
     if($k % 12==0)echo'<br />';
     }?>
    </td>
  </tr>
</table>