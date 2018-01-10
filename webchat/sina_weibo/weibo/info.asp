<!--#include file="../config.asp"-->
<!--#include file="../api.func.asp"-->
<!--#include file="oauthv2.asp"-->
<%
token=Session("weibo_token")
if token="" then
  response.redirect "login.asp"
  response.end
end if

Dim t : Set t = New OAuthV2
t.access_token=token
t.access_uid=Session("weibo_uid")
UserInfo = t.GetUserInfo2(Request("u"))
response.write UserInfo
Set t=Nothing
%>