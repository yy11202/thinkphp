
//记录默认信息
var def={};
function checkField(id,checkEmpty){
  if(token=='login'){
    return true;
  }
  var input=$('#'+id);
  var v=input.val();
  
  if(v.length<1){
    if(checkEmpty){
      $('span.tips-'+id).html($('label[for="'+id+'"]').html()+'不能为空');
      setStatus(id,'wrong');
    }else{
      $('span.tips-'+id).html(def[id]);
      setStatus(id,'');
    }
    return false;
  }
  
  var minLength=parseInt(input.attr('minLength'));
  if(!isNaN(minLength) && v.length<minLength){
    $('span.tips-'+id).html($('label[for="'+id+'"]').html()+'长度不能小于'+minLength+'位');
    setStatus(id,'wrong');
    return false;
  }
  
  var maxLength=parseInt(input.attr('maxLength'));
  if(!isNaN(maxLength) && v.length>maxLength){
    $('span.tips-'+id).html($('label[for="'+id+'"]').html()+'长度不能大于'+maxLength+'位');
    setStatus(id,'wrong');
    return false;
  }
  if(id=='loginEmail'){
    if(!/^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/.test(v)){
      $('span.tips-'+id).html('请填写有效的邮箱地址');
      setStatus(id,'wrong');
      return false;
    }
    $.post(window.location.href+'&t='+Math.random(),{action:'checkEmail',loginEmail:v},function(s){
      if(s=='Y'){
        $('span.tips-'+id).html('邮箱可以使用');
        setStatus(id,'success');
      }else{
        $('span.tips-'+id).html(s);
        setStatus(id,'wrong');
        ok=false;
      }
    });
  }else if(id=='username'){
    $.post(window.location.href+'&t='+Math.random(),{action:'checkName',username:v},function(s){
      if(s=='Y'){
        $('span.tips-'+id).html('用户名可以使用');
        setStatus(id,'success');
      }else{
        $('span.tips-'+id).html(s);
        setStatus(id,'wrong');
        ok=false;
      }
    });
  }else if(id=='rePassword'){
    if(v!=$('#password').val()){
      $('span.tips-'+id).html('两次密码不一致');
      setStatus(id,'wrong');
      return false;
    }
  }
  setStatus(id,'success');
  $('span.tips-'+id).html($('label[for="'+id+'"]').html()+'可以使用');
  return true;
}
function setStatus(id,s){
  $('b.icon-'+id).removeClass('icon-wrong').removeClass('icon-success').hide();
  $('span.tips-'+id).removeClass('reg-tips-wrong').removeClass('reg-tips-success');
  if(s=='wrong' || s=='success'){
    $('b.icon-'+id).addClass('icon-'+s).show();
    $('span.tips-'+id).addClass('reg-tips-'+s);
  }
}
var token='';
function formInit(type){
  token=type;
  $(document).click(function(){
    $('#sug_css').hide();
  });
  if(type=='register'){
    $('input.ipt').each(function(){
      var id=$(this).attr('id');
      def[id]=$('span.tips-'+id).html();
      $('#'+id).bind('blur',function(){
        checkField(id);
      }).bind('focus',function(){
        checkField(id);
      });
    });
  }
}
var lIndex=-1;
function selEmail(e){
  lIndex=e;
  if(lIndex<0){
    lIndex=0;
  }else if(lIndex>$('#sug_css li').length-1){
    lIndex=$('#sug_css li').length-1;
  }
  $('#sug_css li').removeClass();
  $('#sug_css li#l'+lIndex).addClass('hover');
}
function setEmail(e){
  $('#loginEmail').val(e);
  $('#sug_css').hide();
  lIndex=-1;
  checkField('loginEmail');
}
var ok=true;
function CheckSubmit(){
	if($('#sug_css').is(':visible'))return false;
  ok=true;
  for(var id in def){
    if(!checkField(id,true)){
      ok=false;
    }
  }
  return ok;
}
var domainListDef = new Array(
    "163.com","gmail.com","hotmail.com","qq.com","sohu.com","yahoo.com.cn"
    )
function filterEmail(input){
  if(input.length<1){
    return [];
  }
    var atIndex    = input.indexOf("@");
    var emailList  = new Array();
    var domainList = domainListDef;
    if(atIndex < 0){
        input = input + "@";
        for(key in domainList){
            emailList.push(input+domainList[key]);
        }
    }
    else if(atIndex + 1 == input.length){
        for(key in domainList){
            emailList.push(input+domainList[key]);
        }
    }
    else{
        var domainBegin = input.substring(atIndex + 1);
        name = input.substring(0,atIndex+1);
        for(key in domainListDef){
            if(domainListDef[key].indexOf(domainBegin) == 0 && name+domainListDef[key] != input){
                emailList.push(name+domainListDef[key]);
            }
        }
    }
    return emailList;
}