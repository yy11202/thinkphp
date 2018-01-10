// form 表单名称
var STATS_FORM = 'statsForm';
// 表格数据 form 表单名称
var STATS_TABLE_FORM = 'statsTableForm';
// Ajax 数据读取的标识
var AJAX_LOADING = [];
// 上一次读取数据的URL的MD5值
var AJAX_MD5 = [];
// 站点ID隐藏域的ID值
var HIDDEN_SID = 'sId';
// item 顺序数组
var SELECT_ITEMS = [];
// 站点开启统计应用的时间
var STATS_START_TIME = 0;
// 指标的说明提示
var ITEM_TIPS_MESSAGE = [];
// checkbox 的现在个数限制，checkboxName => num
var CHECKBOX_LIMIT = [];
// 图表 Flash 对象
var OBJECT_FLASH_CHARTS = [];
// 对话框样式是否加载
var INIT_CSS = false;
// 指标tip的setTimeout对象
var TIPS_TIMEOUT = {};
// 指标tip的显示时长setTimeout对象
var TIPS_SHOW_TIMEOUT = {};
// 防止用户操作太快
var FORM_CLICKED = [];
// 表格列数大于7，显示滚动条
var ROW_NUM = 7;
// 表格每列宽度
var TABLE_ROW_WIDTH = 130;
// 表格默认宽度
var TABLE_WIDTH = 762;
// 指标的Tips
var ITEM_TIPS = [];
// 指标的ID列表
var ITEM_IDS = [];

function displayMenu(mId, level) {
    var liId = 'li_' + mId;
    var openClass = 'open_' + level;
    var closeClass = 'close_' + level;

    if ($('#' + liId).attr('class') == openClass) {
        $('#' + liId).removeClass().addClass(closeClass);
    } else {
        $('#' + liId).removeClass().addClass(openClass);
    }

}
/**
 * 提示框
 * 
 * @param string
 *            $content 提示内容
 * @param title
 *            $title 标题
 * @param sting
 *            callback 回调方法
 * @param sting
 *            btnType 按钮样式 1.确定取消，2.是否
 * @access public
 * @return mixed
 */
function showWindows(content, title, callback, btnType, extra) {

	dialogPosition = ($.browser.msie && $.browser.version < 7) ? 'absolute'
			: 'fixed';

	if(extra && typeof(extra['heatmap']) != 'undefined'){
        	dialogPosition = 'absolute';
	}
    
	
	btnText1 = '确定';
	btnText2 = '取消';
	switch (btnType) {
	case 2:
		btnText1 = '是';
		btnText2 = '否';
		break;
	case 3:
		btnText1 = '确定';
		btnText2 = false;
		break;
	case 4:
		btnText1 = '继续';
		btnText2 = false;
		break;
	case 5:
		btnText1 = false;
		btnText2 = false;
	}

	if (btnType != -1 && btnText1) {
		if (btnType && typeof ('btnType') != 'undefined'
				&& isNaN(parseInt(btnType)) && btnType.length > 1) {
			btnText1 = btnType;
			btnText2 = '';
		}
		btnHtml = '<div class="o pns"><span id="promptOff" style="color:#aaaaaa"></span><button id="btn_1" class="pn pnc"><span>' + btnText1 + '</span></button>';
		if (btnText2) {
			btnHtml += '&nbsp;&nbsp;<button class="pn" onclick="$(\'#fwin_dialog_close\').click();"><span>' + btnText2 + '</span></button>';
		}
		btnHtml += '</div>';
	} else {
		btnHtml = '';
	}
    var zIndex = '1001';
    if(extra && typeof(extra['zIndex']) != 'undefined'){
        zIndex = extra['zIndex'];
    }	

	message_id = 'fs_100' + zIndex;// + parseInt((Math.random()*6)+1);
	dialogId = "fwin_dialog_" + message_id;

	win_dialog = "<div style=\"position: " + dialogPosition
			+ "; z-index: " + zIndex + "; " + "display:none;\" class=\"fwinmask\" id=\""
			+ dialogId + "\">";

	win_dialog += "<table cellspacing=\"0\" cellpadding=\"0\" class=\"fwin\">"
			+ "<tr><td class=\"t_l\"></td><td class=\"t_c\"></td><td class=\"t_r\"></td></tr>"
			+ "<tr><td class=\"m_l\">&nbsp;&nbsp;</td><td class=\"m_c\">"
			+ "<h3 class=\"flb\" style=\"cursor: move;\"><em>"
			+ ((title == undefined || title == "") ? "提示信息" : title)
			+ "</em><span><a title=\"关闭\" onclick=\"hideWindows('fwin_dialog_"
			+ message_id
			+ "');\" class=\"flbc\" id=\"fwin_dialog_close\" href=\"javascript:;\">关闭</a></span></h3>"
			+ "<div id=\"fwin_content_"
			+ message_id
			+ "\">"
			+ "</div>"
			+ "<p class=\"o pns\" id=\"fwin_pns_"
			+ message_id
			+ "\" style=\"display:none; margin-top:10px;\"><span class=\"z xg1\"></span>"
			+ "<button class=\"pn pnc\" value=\"true\" id=\"fwin_dialog_submit\" onclick=\"submit_content('"
			+ message_id
			+ "');\"><strong>确定</strong></button></p>"
			+ "</td><td class=\"m_r\"></td></tr>"
			+ "<tr><td class=\"b_l\"></td><td class=\"b_c\"></td><td class=\"b_r\"></td></tr></table>";

	win_dialog += "</div>";

	if (!$('#' + dialogId)[0]) {
		$(win_dialog).appendTo("body");
	}

	$(".flb em")[0].className = '';
	$(".flb em").html((title == undefined || title == "") ? "提示信息" : title);
	$('#fwin_content_' + message_id)
			.html(
					'<div style="padding: 10px; margin-bottom:20px; table-layout:fixed; word-break: break-all; overflow:hidden;" class="c">'
							+ content + '</div>' + btnHtml);
	$("#" + dialogId).show();

	dialogWidth = (extra && typeof (extra['width']) != 'undefined') ? parseInt(extra['width'])
			: 'auto';
	if (dialogWidth != 'auto') {
		$('#fwin_content_' + message_id).css( {
			"width" : (dialogWidth) + "px"
		});
	} else {
		$('#fwin_content_' + message_id).css( {
			"width" : "auto"
		});
	}

	dialogLeft = (extra && typeof (extra['left']) != 'undefined') ? extra['left']
			: ($(window).width() - $('#' + dialogId).width()) / 2;
	dialogTop = (extra && typeof (extra['top']) != 'undefined') ? extra['top']
			: ($(window).height() - $('#' + dialogId).height()) / 2;
	$("#" + dialogId).css( {
		"top" : dialogTop + "px",
		"left" : dialogLeft + "px"
	});
    
	if(!extra || typeof(extra['heatmap']) == 'undefined'){

         $("#" + dialogId).draggable({
             handle:"h3.flb",
             drag:function(){ 
                 if($("#frm_100_" + dialogId)){
                     $("#frm_100_" + dialogId).css({"top": $("#" + dialogId).css("top"),"left": $("#" + dialogId).css("left")});   
                 }
				 //解决拖拽过程中的日期选择的位置问题
				 if($("div[id^='calendar_']")){
				 	$("div[id^='calendar_']").css('display', 'none');
				 }
             }
         });
    }
	if ($('#btn_1')[0]) {
		if (callback) {
			$("#" + dialogId + " #btn_1").click(callback);
		} else {
			$("#" + dialogId + " #btn_1").click(function() {
				$('#fwin_dialog_close').click();
			});
		}
	}

	if (typeof (extra) != 'undefined') {
		switch (extra['type']) {
		case 'tips':
			$("#fwin_dialog_close").css('display', 'none');break;
		case 'report_tips':
			$("#fwin_dialog_close").css('display','none');
			if($("#"+dialogId)){
				$("#"+dialogId).css({'left' : extra['left'] , 'top' : extra['top']});
			}
			break;
		}
	}

	var noPrompt = (extra && typeof (extra['noPrompt']) != 'undefined') ? extra['noPrompt'] : false;
	if (noPrompt) {
		$('#' + 'promptOff').html(
				'<input type="checkbox" id="noDataPromptOff" name="noDataPromptOff" value="1" style="position:relative;top:2px;"/> 不再提醒 '
				);
	}

	// 解决IE6select控件bug
	hidIframeId = "frm_100_" + dialogId;
	//如果已经存在，那么删除
	if($("#"+hidIframeId)){
		$("#"+hidIframeId).remove();
	}
    hidIframe = "<iframe id=\"" + hidIframeId + "\"></iframe>";
	$(hidIframe).appendTo("body");
    zIndex = parseInt(zIndex);
    zIndex--;
	$("#" + hidIframeId).css({
		"width" : $("#" + dialogId).width(),
		"height" : $("#" + dialogId).height(),
		"position" : dialogPosition,
		"top" : $("#" + dialogId).css("top"),
		"left" : $("#" + dialogId).css("left"),
		"z-index" : zIndex,
		"scrolling":"no",
		"border":"0"
	});

	return dialogId;
}

function hideWindows(windowId) {
    $("#" + windowId).hide();
    $("#" + windowId).remove();
    $("#frm_100_"+windowId).remove();
	//解决IE浏览器下a标签不向上冒泡的问题
	if($("div[id^='calendar_']")){
		$("div[id^='calendar_']").css('display', 'none');
	}
	return false;
}



/**
 * 执行 Ajax 操作
 * @param obj Ajax 请求所需的参数
 * @param frmId 表单ID
 * @param func function 函数对象
 */
function doAjax(obj, frmId, func) {
    if('undefined' == typeof(frmId)) {
        frmId = 'defaultFrm';
    }
    if(true == AJAX_LOADING[frmId]) {
        alert('正在为您读取数据，请稍候……');
        return false;
    }
    // 正在进行 Ajax 操作的标识
    AJAX_LOADING[frmId] = true;
    // 数据读取失败
    obj.error = function() {
        // 清除 flash 数据读取时的层
        clearLoadingDiv();
        AJAX_LOADING[frmId] = false;
        // 处理回调函数
        if('function' == typeof(func)) {
            func();
        }
    }
    // 成功返回数据的处理函数
    obj.success = function(msg) {
        AJAX_LOADING[frmId] = false;
        try {
            // ajax 标识
            var ajaxReg = /<ajax id="(.*?)">([^\x00]+?)<\/ajax>/ig;
            var ajaxAlertReg = /<ajaxAlert>([^\x00]+?)<\/ajaxAlert>/ig;
            // 剥离提示信息
            if(msg.match(ajaxAlertReg)) {
                ajaxTipsMsg = msg.replace(ajaxAlertReg, function($0, $1) {
                    alert($1);
                    return '';
                });
                return true;
            }
            // Ajax 操作
            ajaxMsg = msg.replace(ajaxReg, function($0, $1, $2) {
                // flash 这种特殊的 DOM 对象
                var objectReg = /(embed|object)$/ig;
                // script
                var scriptReg = /<script(.*?)>([^\x00]+?)<\/script>/ig;
                // object
                var obj = $('#' + $1);
                if(1 > obj.length) {
                    return '';
                }
                // 如果是 flash 对象元素
                if(objectReg.exec(obj.attr('nodeName'))) {
                    var script = ($2).match(scriptReg);
                    if(script[0]) {
                        obj.append(script[0]);
                    }
                    return '';
                }
                if ('dataTable' == $1) {
                    var tableObj = jQuery($2);
                    var thNum = tableObj.get(0).rows.item(0).cells.length;
                    var defaultWidth = parseInt(tableObj.attr('defaultWidth'));
                    if (defaultWidth) {
                        var defaultRows = parseInt(tableObj.attr('defaultRows'));
                        var newRowWidth = parseInt(tableObj.attr('newRowWidth'));
                        var dataTableWidth = defaultWidth + (thNum - defaultRows) * newRowWidth;
                        if (dataTableWidth > TABLE_WIDTH) {
                            $('#dataTable').css('width', dataTableWidth + 'px');
                        } else {
                            $('#dataTable').css('width', 'auto');
                        }
                    } else {
                        if (ROW_NUM < thNum) {
                            var dataTableWidth = (thNum - ROW_NUM) * TABLE_ROW_WIDTH + TABLE_WIDTH + 'px';
                            $('#dataTable').css('width', dataTableWidth);
                        } else {
                            $('#dataTable').css('width', 'auto');
                        }
                    }
                }
                obj.empty().append($2);
                return '';
            });
        } catch(err) {
            alert("Error name: " + err.name + ";\r\nError message: " + err.message + ";");
        } finally {
            // 清除 flash 数据读取时的层
            clearLoadingDiv();
            // 处理回调函数
            if('function' == typeof(func)) {
                func();
            }
        }
    }
    // 完成以后执行操作
    obj.complete = function(){
        if($('#overflowDiv') && $('#overflowDiv').hasClass('overflowDivPlus')) {
            $('#overflowDiv').removeClass('overflowDivPlus');
        }
    }
    // 超时时间为25s
    obj.timeout = 25000;
    // 在 flash 上覆盖一个层
    showLoadingDiv();
    // 提交表单
    $.ajax(obj);
}

/**
 * 去除 loading 加载层
 * @param className 加载层的样式名称;
 */
function clearLoadingDiv(className) {
    var divClass = 'div.flashChartLoading';
    if('undefined' != typeof(className)) {
        divClass = className;
    }
    $(divClass).each(function() {
        $(this).remove();
    });
}

/**
 * 显示 loading 加载层
 * @param id 需要被覆盖的元素ID;
 */
function showLoadingDiv(id) {
    var id_class = '.flashChart, .overflowDiv';
    if('undefined' != typeof(id)) {
        id_class = '#' + id;
    }
    var isIe6 = false;
    var top_extra = 0; 
    var browser_diff = 0;
    $(id_class).each(function() {
        if($(this).hasClass("overflowDiv")) {
            top_extra = 30;
            if($.browser.msie) {
                if ($.browser.version < 7) {
                    isIe6 = true;
                    browser_diff = top_extra / 2; 
                } else {
                    browser_diff = top_extra;
                }
            }
        };
        var pos = $(this).offset();
        var fDiv = document.createElement('div');
        var jDiv = $(fDiv);
        jDiv.css('position', 'absolute');
        jDiv.css('left', pos.left);
        jDiv.css('top', pos.top + top_extra);
        jDiv.css('width', $(this).attr('offsetWidth'));
        jDiv.css('height', $(this).attr('offsetHeight') - browser_diff);
        jDiv.css('line-height', $(this).attr('offsetHeight') - browser_diff + 'px');
        jDiv.addClass('flashChartLoading');
        jDiv.html('<img src="images/loading.gif" />数据加载中...');
        $(this).parent().append(jDiv);
    });
}
var dialogId = 0;
var editors={};
function viewRecord(ti,url){
  $.ajax({
    url:url,
    type:'post',
    cache: false,
    dataType:'html',
    success:function(data){
      dialogId ? hideWindows(dialogId) : 0;
      dialogId = showWindows(data,ti, null, 3);
    }
  });
}
function setRecord(ti,url){
  $.ajax({
    url:url,
    type:'post',
    cache: false,
    dataType:'html',
    success:function(data){
      dialogId ? hideWindows(dialogId) : 0;
      dialogId = showWindows(data,ti, doRecord, 1);
      var o=data.match(/editor_[0-9a-zA-Z]+/gi);
      for(var k in editors){
        delete editors[k];
      }
      $('.form-js').each(function(){
        try{
          eval($(this).attr('call'));
        }catch(e){}
      });
      $('#divForm td label').html('<font color=red> * </font>');
      for(var i=0;i<o.length;i++){
        editors[o[i]]=KindEditor.create('#'+o[i],{
          allowPreviewEmoticons : false,
          allowImageUpload : true,
          items : [
            'fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold', 'italic', 'underline',
            'removeformat', '|', 'justifyleft', 'justifycenter', 'justifyright', 'insertorderedlist',
            'insertunorderedlist', '|', 'emoticons', 'image', 'link','|','source']
        });
      }
      $('form#divForm').submit(function(){
        return false;
      });
    }
  });
}
function doRecord(){
  for(var k in editors){
    $('#divForm #'+k).val(editors[k].html());
  }
  //需要验证的表单
  var error=0;
  $('#divForm label[name]').each(function(){
    if(typeof($(this).attr('none'))!='undefined'){
      var v=$('form#divForm input[name="'+$(this).attr('name')+'"]');
      if(v.val().length<1){
        error++;
        alert($(this).attr('none'));
        return false;
      }
    }
  });
  if(error>0){
    return false;
  }
  $.ajax({
    url:window.location.href,
    type:'post',
    dataType:'html',
    cache: false,
    data:$('form#divForm').serialize(),
    success:function(data){
      if(data.substr(0,1)=='Y'){
        if(data.length>1){
          window.location.href=data.substr(1);
        }else{
          window.location.reload();
        }
      }else{
        showWindows(data, '提示信息', null, 3);
      }
    }
  });
}
var curId='';
function delRecord(id) {
  curId=id;
  showWindows('您确定删除所选记录吗？', '提示信息', delRecordConfirm, 1);
}
function delRecordConfirm(){
  $.ajax({
    url:window.location.href,
    type:'post',
    cache: false,
    dataType:'html',
    data:{action:'delete',id:curId},
    success:function(data){
      if(data.substr(0,1)=='Y'){
        if(data.length>1){
          window.location.href=data.substr(1);
        }else{
          window.location.reload();
        }
      }else{
        showWindows(data, '提示信息', null, 3);
      }
    }
  });
}