<?php $action=GP('action');
if($action=='setread'){
	$messages=GP('messages');
	D()->update('sms',array('isread'=>'1'),"sid in (".implode(',',$messages).")");
	exit('SUCCESS');
}elseif($action=='delete'){
	$messages=GP('messages');
	D()->delete('sms',"sid in (".implode(',',$messages).")");
	exit('SUCCESS');
}
$smsList=D()->page('sms','*',"touid='$uid' order by sid desc",1,"onez.admin");
html()->header(false)?>
<style>
.error { color: #CC0000; }
.right { color: #4BBB2E; }
</style>
<div class="main">
<div class="path mbm cl">
            <div class="y">
                您有<strong id="unreadNumStrong" class="xi2"><?php echo $unreadNum=D()->rows('sms',"isread='0'")?></strong>条未读消息
            </div>
            <a href="<?php echo html()->assign('url') ?>">管理中心</a>
            <em>&rsaquo;</em>
            <a href="<?php echo html()->assign('url') ?>?f=sms">短消息</a>
        </div>
<table id="messageTable" class="messbox">
<?php foreach($smsList[0] as $rs){?>
  <tbody id="row_<?php echo $rs['sid']?>">
    <tr>
      <td class="o"><input type="checkbox" value="<?php echo $rs['sid']?>" name="messages[]" class="pc" /></td>
      <th><a id="<?php echo $rs['sid']?>"<?php if(!$rs['isread'])echo' class="strong"'?> onclick="viewMessage(this);" href="javascript:void(0);"><?php echo $rs['caption']?></a></th>
      <td class="d"><?php echo date('Y年m月d日',$rs['time'])?></td>
    </tr>
    <tr id="c_<?php echo $rs['sid']?>" style="display:none;">
      <td></td>
      <td colspan="2"><?php echo $rs['content']?></td>
    </tr>
  </tbody>
<?php }?>
  <tbody>
    <tr class="bw0_all">
      <td class="o"><input type="checkbox" onclick="selectAll(this);" id="checkboxAll" name="checkboxAll" class="pc" /></td>
      <td colspan="2"><div id="pageAjax">
          <div class="pg y"> </div>
        </div>
        <button class="pn" id="delMessages" name="delMessages" onclick="delMessages();return false;"><span>删除</span></button>
        <button class="pn" id="readMessages" name="readMessages" onclick="readMessages();return false;"><span>标记为已读</span></button>
        <span id="tips"></span></td>
    </tr>
  </tbody>
</table>

<div class="pgs cl mbm">
<div class="pg"><?php echo $smsList[1]?></div>
</div>

</div>
<script type="text/javascript">
var unreadMessageNum = '<?php echo $unreadNum;?>';
// 查看短消息
function viewMessage(obj) {
    var SELECT_MESSAGES = [];
    showTips('');
    var id = $(obj).attr('id');
    if ($('#c_' + id).is(':hidden')) {
        if ($(obj).attr('class') == 'strong') {
            SELECT_MESSAGES.push('messages[]=' + id);
            maskAsRead(SELECT_MESSAGES);
            $(obj).removeClass('strong');
        }
        $('#c_' + id).show();
    } else {
        $('#c_' + id).hide();
    }
}

// 全选短消息
function selectAll(obj) {
    showTips('');
    var checked = $(obj).attr('checked');
    $('#messageTable').find(':checkbox').each(function() {
        if('messages[]' == $(this).attr('name')) {
            $(this).attr('checked', checked);
        }
    });
}

// 删除短消息
function delMessages() {
    var SELECT_MESSAGES = [];
    showTips('');
    $('#messageTable').find(':checkbox').each(function() {
        var id = $(this).val();
        if('messages[]' == $(this).attr('name') && $(this).attr('checked')) {
            SELECT_MESSAGES.push('messages[]=' + id);
        }
    });
    if (SELECT_MESSAGES.length > 0) {
        showWindows('您确定删除所选短消息吗？', '提示信息', delMessageAjax, 1);
    } else {
        showTips('请选择要操作的短消息', 'error');
    }

    return false;
}

// 删除短消息AJAX操作
function delMessageAjax() {
    var SELECT_MESSAGES = [];
    var unReadMsgNum = 0;
    showTips('');
    $('#fwin_dialog_close').click();
    $('#messageTable').find(':checkbox').each(function() {
        var id = $(this).val();
        if('messages[]' == $(this).attr('name') && $(this).attr('checked')) {
        		$('#row_' + id).remove();
            SELECT_MESSAGES.push('messages[]=' + id);
            if ($('#' + id).attr('class') == 'strong') {
                unReadMsgNum ++;
            }
        }
    });

    if (SELECT_MESSAGES.length > 0) {
        doAjax({
            type : 'GET',
            url : '<?php echo html()->assign('self') ?>&action=delete&' + SELECT_MESSAGES.join('&')
        });
        unreadMessageNum -= unReadMsgNum;
        if (unreadMessageNum > 0) {
            $('#unreadNumStrong').text(unreadMessageNum);
            $('#unreadNumEm').text('(' + unreadMessageNum + ')');
        } else if (unreadMessageNum == 0) {
            $('#unreadNumStrong').text(unreadMessageNum);
            $('#message').removeClass('message');
            $('#unreadNumEm').remove();
        }
        showTips('操作成功', 'right');
    } else {
        showTips('请选择要操作的短消息', 'error');
    }
}

// 标记已读短消息
function readMessages() {
    var SELECT_MESSAGES = [];
    showTips('');
    $('#messageTable').find(':checkbox').each(function() {
        var id = $(this).val();
        if('messages[]' == $(this).attr('name') && $(this).attr('checked')) {
            SELECT_MESSAGES.push('messages[]=' + id);
            $('#' + id).removeClass('strong');
        }
    });
    if (SELECT_MESSAGES.length > 0) {
        maskAsRead(SELECT_MESSAGES);
    } else {
        showTips('请选择要操作的短消息', 'error');
    }

    return false;
}

// 标记已读短消息请求后台
function maskAsRead(messages) {
    $.get('<?php echo html()->assign('self') ?>&action=setread&' + messages.join('&'), function(data) {
        if (data == 'SUCCESS') {
            unreadMessageNum -= messages.length;
            if (unreadMessageNum > 0) {
                $('#unreadNumStrong').text(unreadMessageNum);
                $('#unreadNumEm').text('(' + unreadMessageNum + ')');
            } else if (unreadMessageNum == 0) {
                $('#unreadNumStrong').text(unreadMessageNum);
                $('#message').removeClass('message');
                $('#unreadNumEm').remove();
            }
            showTips('操作成功', 'right');
        } else {
            showTips(data, 'error');
        }
    });
}

// 显示提示信息
function showTips(msg, type) {
    if (type == 'right') {
        $('#tips').removeClass('error');
        $('#tips').addClass('right');
        $('#tips').text(msg);
    } else {
        $('#tips').removeClass('right');
        $('#tips').addClass('error');
        $('#tips').text(msg);
    }
}
</script>
<?php html()->footer()?>