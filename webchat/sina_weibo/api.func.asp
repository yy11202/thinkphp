<%
function CheckStatus(token)
  dim islogin
  select case token
    case "qq"
      islogin=Session("openid")<>""
    case "qq2"
      islogin=Session("openid2")<>""
    case "weibo"
      islogin=Session("weibo_token")<>""
    case "sohu"
      islogin=IsArray(Session("sohu_token"))
    case "163"
      islogin=IsArray(Session("wy_token"))
    case "tudou"
      islogin=IsArray(Session("tudou_token"))
  end select
  
  
  if islogin then
    CheckStatus="<font color=green>已登录</font>"
  else
    CheckStatus="<font color=gray>未登录</font>"
  end if
end function

function GetLoginUrl(token)
  GetLoginUrl=token&"/login.asp"
end function

function PostTxt(token,sort,txt,data)
  PostTxt=token&"/post.asp?sort="&sort&"&txt="&urlencode(txt)&"&data="&urlencode(data)
end function

function GetInfo(token,u)
  if token="qq2" then
    GetInfo="http://base.qzone.qq.com/fcg-bin/cgi_get_portrait.fcg?uins="&urlencode(u)
    exit function
  end if
  GetInfo=token&"/info.asp?u="&(u)
end function

function GetInfo2(token)
  GetInfo2=token&"/info2.asp"
end function

function Follow(token,u)
  Follow=token&"/add.asp?u="&(u)
end function
function DisFollow(token,u)
  DisFollow=token&"/del.asp?u="&(u)
end function

function ReadList(token)
  ReadList=token&"/list.asp"
end function

function VideoUpload(token)
  VideoUpload=token&"/upload.asp"
end function

function LoginCallBack(token,id,username)
  response.write "TYPE: " & token & "<br />"
  response.write "OPENID: " & id & "<br />"
  response.write "USERNAME: " & username & "<br />"
  
  
  response.write "<br /><a href="""&homepage&""">进入接口主页</a>"
end function

%>
<script language="javascript" runat="server">
function urlencode(str){var tmp=encodeURIComponent(str);tmp=tmp.replace('!','%21');tmp=tmp.replace('*','%2A');tmp=tmp.replace('(','%28');tmp=tmp.replace(')','%29');tmp=tmp.replace("'",'%27');return tmp;}
</script>