<!--#include file="config.asp"-->
<!--#include file="api.func.asp"-->
<html><head>
<meta http-equiv="X-UA-Compatible" content="IE=7">
<meta http-equiv="content-type" content="text/html;charset=gb2312">
<title>ASP登录接口</title>
<style type="text/css">
body{
  line-height:20px;
  font-size:12px;
  color:#808080;
}
a{color:#0000ff}
</style>
</head>
<body>

<h1>登录接口</h1>
<a href="<%=GetLoginUrl("qq2")%>">使用腾讯账号登录</a> <%=CheckStatus("qq2")%><br />
<a href="<%=GetLoginUrl("qq")%>">使用腾讯微博登录</a> <%=CheckStatus("qq")%><br />
<a href="<%=GetLoginUrl("weibo")%>">使用新浪微博登录</a> <%=CheckStatus("weibo")%><br />
<a href="<%=GetLoginUrl("sohu")%>">使用搜狐微博登录</a> <%=CheckStatus("sohu")%><br />
<a href="<%=GetLoginUrl("163")%>">使用网易微博登录</a> <%=CheckStatus("163")%><br />
<a href="<%=GetLoginUrl("ifeng")%>">使用凤凰微博登录</a> <font color=red>无效</font><br />
<a href="<%=GetLoginUrl("tudou")%>">使用土豆网账号登录</a> <%=CheckStatus("tudou")%><br />
<h1 style="color:#ff0000;text-align:center">以下操作须登录后进行</h1>
<hr />
<h1>发文字微博</h1>
文本信息：<font color=red><%txt="测试文字微博"&Now()%><%=txt%></font><br />
<a href="<%=PostTxt("qq","text",txt,"")%>">使用腾讯微博发送</a><br />
<a href="<%=PostTxt("weibo","text",txt,"")%>">使用新浪微博发送</a><br />
<a href="<%=PostTxt("sohu","text",txt,"")%>">使用搜狐微博发送</a><br />
<a href="<%=PostTxt("163","text",txt,"")%>">使用网易微博发送</a><br />
使用凤凰微博发送<br />

<h1>发图片微博</h1>
文本信息：<font color=red><%txt="测试图片微博"&Now():pic="http://cz886.com/sina_weibo/demo.jpg"%>
<%=txt%><img src="<%=pic%>"></font><br />
<a href="<%=PostTxt("qq","pic",txt,pic)%>">使用腾讯微博发送</a><br />
<a href="<%=PostTxt("weibo","pic",txt,pic)%>">使用新浪微博发送</a><br />
<a href="<%=PostTxt("sohu","pic",txt,pic)%>">使用搜狐微博发送</a> <font color=#ff00ff>（待）</font><br />
<a href="<%=PostTxt("163","pic",txt,pic)%>">使用网易微博发送</a><br />
使用凤凰微博发送<br />

<h1>发音乐微博</h1>
文本信息：<font color=red><%txt="测试音乐微博"&Now():data="http://demo.onez.cn/20111219/trouble is a friend.mp3|trouble is a friend|lenka"%>
<%=txt%></font> “trouble is a friend”<br />
<a href="<%=PostTxt("qq","music",txt,data)%>">使用腾讯微博发送</a><br />
使用新浪微博发送<br />
使用搜狐微博发送<br />
使用网易微博发送<br />
使用凤凰微博发送<br />

<h1>发视频微博</h1>
文本信息：<font color=red><%txt="测试视频微博"&Now():data="http://v.youku.com/v_show/id_XMjQ3MTU0MjA4.html"%>
<%=txt%></font> “http://v.youku.com/v_show/id_XMjQ3MTU0MjA4.html”<br />
<a href="<%=PostTxt("qq","video",txt,data)%>">使用腾讯微博发送</a><br />
使用新浪微博发送<br />
<a href="<%=PostTxt("sohu","video",txt,data)%>">使用搜狐微博发送</a> <font color=#ff00ff>（待）</font><br />
使用网易微博发送<br />
使用凤凰微博发送<br />

<h1>读当前用户资料</h1>
<a href="<%=GetInfo2("qq2")%>">读取腾讯账号资料</a><br />
<a href="<%=GetInfo2("qq")%>">读取腾讯微博账号资料</a><br />
<a href="<%=GetInfo2("weibo")%>">读取新浪微博账号资料</a><br />
<a href="<%=GetInfo2("sohu")%>">读取搜狐微博账号资料</a><br />
<a href="<%=GetInfo2("163")%>">读取网易微博账号资料</a><br />

<h1>读其他用户资料</h1>
<a href="<%=GetInfo("qq2","6200103")%>">读取腾讯账号“6200103”的资料</a><br />
<a href="<%=GetInfo("qq","onezcn")%>">读取腾讯微博账号“onezcn”的资料</a><br />
<a href="<%=GetInfo("weibo","佳蓝在线")%>">读取新浪微博账号“佳蓝在线”的资料</a><br />
<a href="<%=GetInfo("sohu","佳蓝工作室")%>">读取搜狐微博账号“佳蓝工作室”的资料</a><br />
<a href="<%=GetInfo("163","佳蓝工作室")%>">读取网易微博账号“佳蓝工作室”的资料</a><br />

<h1>关注用户</h1>
<a href="<%=Follow("qq","onezcn")%>">在腾讯微博中关注“onezcn”</a><br />
<a href="<%=Follow("weibo","佳蓝在线")%>">在新浪微博中关注“佳蓝在线”</a><br />
<a href="<%=Follow("sohu","305774380")%>">在搜狐微博中关注“佳蓝工作室”</a><br />
<a href="<%=Follow("163","佳蓝工作室")%>">在网易微博中关注“佳蓝工作室”</a><br />

<h1>取消关注用户</h1>
<a href="<%=DisFollow("qq","onezcn")%>">在腾讯微博中取消关注“onezcn”</a><br />
<a href="<%=DisFollow("weibo","佳蓝在线")%>">在新浪微博中取消关注“佳蓝在线”</a><br />
<a href="<%=DisFollow("sohu","305774380")%>">在搜狐微博中取消关注“佳蓝工作室”</a><br />
<a href="<%=DisFollow("163","佳蓝工作室")%>">在网易微博中取消关注“佳蓝工作室”</a><br />

<h1>读取微博列表</h1>
<a href="<%=ReadList("qq")%>">读取腾讯微博列表</a><br />
<a href="<%=ReadList("weibo")%>">读取新浪微博列表</a><br />
<a href="<%=ReadList("sohu")%>">读取搜狐微博列表</a><br />
<a href="<%=ReadList("163")%>">读取网易微博列表</a><br />

<h1>视频功能</h1>
<a href="<%=VideoUpload("tudou")%>">上传视频到土豆</a><br />
<a href="tudou/list.asp?u=hunterso">用户hunterso上传视频列表</a><br />
<a href="tudou/list.asp?u=hunterso&format=xml">用户hunterso上传视频列表(XML)</a><br />
<a href="tudou/pic.asp?code=UnCL9nAYZEo">取视频id:UnCL9nAYZEo的图片</a><br />
<a href="tudou/pic-UnCL9nAYZEo.jpg">取视频id:UnCL9nAYZEo的图片</a><br />
</body>
</html>