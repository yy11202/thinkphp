<?php
$_CACHE['apps'] = array (
  1 => 
  array (
    'appid' => '1',
    'type' => 'OTHER',
    'name' => '开放平台',
    'url' => 'http://127.0.0.1/open.onez.cn',
    'ip' => '',
    'viewprourl' => '',
    'apifilename' => 'uc.php',
    'charset' => '',
    'dbcharset' => '',
    'synlogin' => '0',
    'recvnote' => '0',
    'extra' => false,
    'tagtemplates' => '<?xml version="1.0" encoding="ISO-8859-1"?>
<root>
	<item id="template"><![CDATA[]]></item>
</root>',
    'allowips' => '',
  ),
  2 => 
  array (
    'appid' => '2',
    'type' => 'DISCUZ',
    'name' => 'Discuz!',
    'url' => 'http://127.0.0.1/discuz7.2',
    'ip' => '',
    'viewprourl' => '',
    'apifilename' => 'uc.php',
    'charset' => 'gbk',
    'dbcharset' => 'gbk',
    'synlogin' => '1',
    'recvnote' => '1',
    'extra' => false,
    'tagtemplates' => '<?xml version="1.0" encoding="ISO-8859-1"?>
<root>
	<item id="template"><![CDATA[<a href="{url}" target="_blank">{subject}</a>]]></item>
	<item id="fields">
		<item id="subject"><![CDATA[标题]]></item>
		<item id="uid"><![CDATA[用户 ID]]></item>
		<item id="username"><![CDATA[发帖者]]></item>
		<item id="dateline"><![CDATA[日期]]></item>
		<item id="url"><![CDATA[主题地址]]></item>
	</item>
</root>',
    'allowips' => '',
  ),
);

?>