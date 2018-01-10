<!--#include file="oauthv2.asp"-->
<!--#include file="../api.func.asp"-->
<%
token=Session("weibo_token")
if token="" then
  response.redirect "login.asp"
  response.end
end if



sort=Request("sort")
txt=Request("txt")
data=Request("data")

'sort="text"
'txt=Session("txt")

Dim t : Set t = New OAuthV2
t.access_token=token
if sort="text" then
  result=t.AddPostwb(txt)
elseif sort="pic" then
  result=t.AddPostPicwb(txt,data)
end if
Set t=Nothing
response.write result
%>