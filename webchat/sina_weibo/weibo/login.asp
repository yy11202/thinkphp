<!--#include file="oauthv2.asp"-->
<%
Dim t : Set t = New OAuthV2
aurl=t.getAuthorizeURL(callback,"code",NULL,NULL)
Response.Redirect aurl
Set t=Nothing
%>