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
Response.write t.getList()	
Set t=Nothing
%>