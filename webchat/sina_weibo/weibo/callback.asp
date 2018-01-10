<!--#include file="../config.asp"-->
<!--#include file="../api.func.asp"-->
<!--#include file="oauthv2.asp"-->
<%
dim tmpid
Dim t : Set t = New OAuthV2
if request.QueryString("code") <> "" then
	Dim keys(1)
	keys(0) = request.QueryString("code")
	keys(1) = callback
	token = t.getAccessToken("code",keys)
	if token <>"" then
    Session("weibo_token")=token
		'获取用户信息
		UserInfo = t.GetUserInfo(0)
		set info = json_decode(UserInfo)
		Session("weibo_uid")=info.id
    call LoginCallBack("weibo",info.id,info.name)
	else
		response.Write "授权失败<br />"
	end if
  Set t=Nothing
else
  Response.Redirect "login.asp"
  Set t=Nothing
end if
%>