<!--#include file="../config.asp"-->
<%
callback=homepage&"/weibo/callback.asp"
Class OAuthV2
	public client_id,client_secret,access_token,access_uid,refresh_token,code,username,password
	Private params,TimeLine,boundary
	
	Private Sub Class_Initialize()
		TimeLine= DateDiff("s","01/01/1970 08:00:00",Now()) 'oauth_timestamp
		boundary="------------------"&TimeLine
		client_id = weibo_key
		client_secret = weibo_secret
	end Sub
	
	Function accessTokenURL()
		accessTokenURL = "https://api.weibo.com/oauth2/access_token"
	End Function
	
	Function authorizeURL()
		authorizeURL = "https://api.weibo.com/oauth2/authorize"
	End Function
	
	Function params_build_query(paramser)
		paramsName = paramser.Keys
		paramsValue = paramser.Items
		For intLoop = 0 to paramser.Count - 1
			StrThisItem = paramsName(intLoop)                  
      		StrThisKey = paramsValue(intLoop)
			if build_query <> "" then build_query = build_query & "&"
			if isnull(StrThisKey) then StrThisKey = ""
			build_query = build_query & StrThisItem & "=" & rfcEncoding(StrThisKey)
		Next
		params_build_query = build_query
	End Function
	
	'获取授权地址
	Function getAuthorizeURL(url,response_type,o_state,o_display)
		Set params = Server.CreateObject("Scripting.Dictionary")
		params.Add "client_id" , client_id
		params.Add "redirect_uri" , url
		params.Add "response_type" , response_type
		params.Add "state" , o_state
		params.Add "display" , o_display
		getAuthorizeURL =  authorizeURL & "?" & params_build_query(params)
		set params = nothing
	End Function
	'获取ToKen
	
	Function getAccessToken( o_type , o_key)
		on error resume next
		Set params = Server.CreateObject("Scripting.Dictionary")
		params.Add "client_id" , client_id
		params.Add "client_secret" , client_secret
		if o_type = "token" then
			params.Add "grant_type","refresh_token"
			params.Add "refresh_token",o_key(0)
		elseif o_type = "code" then
			params.Add "grant_type","authorization_code"
			params.Add "code",o_key(0)
			params.Add "redirect_uri",o_key(1)
		elseif o_type = "password" then
			params.Add "grant_type","password"
			params.Add "username",o_key(0)
			params.Add "password",o_key(1)
		else
			Error_Msg "Error:Type Error"
		end if
		response_body = oAuthRequest(accessTokenURL,"POST",params,false)
		set token = json_decode(response_body)
		access_token = token.access_token
		access_uid = token.uid
		expires_in = token.expires_in
		if err then'授权失败
			Err.Clear()
			access_token = Null
		end if
		getAccessToken = access_token
		set params = nothing
	End Function 
	
	Function GetUserInfo(uid)
		if uid = 0 then uid = access_uid
		Set params = Server.CreateObject("Scripting.Dictionary")
		params.Add "source" , client_id
		params.Add "access_token" , access_token
		params.Add "uid" , uid
		response_body = oAuthRequest("https://api.weibo.com/2/users/show.json","GET",params,false)
		GetUserInfo = response_body
		set params = nothing
	End Function
	
	Function GetUserInfo2(username)
		Set params = Server.CreateObject("Scripting.Dictionary")
		params.Add "source" , client_id
		params.Add "access_token" , access_token
		params.Add "screen_name" , username
		response_body = oAuthRequest("https://api.weibo.com/2/users/show.json","GET",params,false)
		GetUserInfo2 = response_body
		set params = nothing
	End Function
  Function  Follow(u)
		Set params = Server.CreateObject("Scripting.Dictionary")
		params.Add "source" , client_id
		params.Add "access_token" , access_token
    params.Add "screen_name",u
		response_body = oAuthRequest("https://api.weibo.com/2/friendships/create.json","POST",params,false)
		Follow = response_body
		set params = nothing
  End Function 
  Function  DisFollow(u)
		Set params = Server.CreateObject("Scripting.Dictionary")
		params.Add "source" , client_id
		params.Add "access_token" , access_token
    params.Add "screen_name",u
		response_body = oAuthRequest("https://api.weibo.com/2/friendships/destroy.json","POST",params,false)
		DisFollow = response_body
		set params = nothing
  End Function 
  Function  getList()
		Set params = Server.CreateObject("Scripting.Dictionary")
		params.Add "source" , client_id
		params.Add "access_token" , access_token
		response_body = oAuthRequest("https://api.weibo.com/2/statuses/user_timeline.json","GET",params,false)
		getList = response_body
		set params = nothing
  End Function 
		
	Function AddPostwb(wb_content)
		Set params = Server.CreateObject("Scripting.Dictionary")
		params.Add "source" , client_id
		params.Add "access_token" , access_token
		params.Add "status",wb_content
		response_body = oAuthRequest("https://api.weibo.com/2/statuses/update.json","POST",params,false)
		AddPostwb = response_body
		set params = nothing
	End Function
	
	Function RePostwb(wb_content,wb_pid)
		Set params = Server.CreateObject("Scripting.Dictionary")
		params.Add "source" , client_id
		params.Add "access_token" , access_token
		params.Add "comment",wb_content
		params.Add "id",wb_pid
		response_body = oAuthRequest("https://api.weibo.com/2/comments/create.json","POST",params,false)
		RePostwb = response_body
		set params = nothing
	End function
	
	Function AddPostPicwb(wb_content,wb_pic)
		Set params = Server.CreateObject("Scripting.Dictionary")
		params.Add "source" , client_id
		params.Add "access_token" , access_token
		params.Add "status",wb_content
		params.Add "pic",wb_pic
		response_body = oAuthRequest("https://upload.api.weibo.com/2/statuses/upload.json","POST",params,True)
		AddPostPicwb = response_body
		set params = nothing
	End function
	
	function oAuthRequest(url, method, paramser, multi)
		if instr(url, "http://") <= 0 and instr(url, "https://") <= 0 then
			Error_Msg "Error: Url Error"
		end if
		select case method
			case "GET"
				url = url & "?" & params_build_query(paramser)
				oAuthRequest = doRequest("GET",url,Null,False)
			case else
				textbody = params_build_query(paramser)
				if multi then'如果是图片
					textbody = build_multi(textbody)
				end if
				oAuthRequest = doRequest("POST",url,textbody, multi)
			end select
	End Function 
	
	Function doRequest(verb, aUrl, objData ,multi)
		Set xmlhttp=Server.CreateObject("MSXML2.ServerXMLHTTP")
		xmlhttp.Open verb,aUrl,false		
		If(verb = "POST") Then
			If(multi) Then '如果是图片
				xmlhttp.setRequestHeader "Content-Type","multipart/form-data; boundary="&boundary
			Else   
				xmlhttp.setRequestHeader "Content-Type", "application/x-www-form-urlencoded; charset=utf-8"
			End  If 
		End  If	
		xmlhttp.send(objData)		
		doRequest = xmlhttp.responseText		
		Set xmlhttp = Nothing
	End Function 
	
	Function build_multi(str)
		Dim MPboundary,endMPboundary,multipartbody,aItems,i,objFile,arr,pic,content,filename,data
		MPboundary = "--"&boundary
		endMPboundary = MPboundary&"--"
		multipartbody = "" 			
		Set objFile   =   Server.CreateObject( "ADODB.Stream") 
		objFile.Type   =   2  
		objFile.Mode   =   3  
		objFile.Charset   =   "UTF-8" 
		objFile.Open 
		aItems=Split(str,"&")
		For i=0 To Ubound(aItems)
			arr=Split(aItems(i),"=")
			If arr(0)="pic" Then 
				pic= rfcDecoding(arr(1))
				content=getPic(pic)			
				filename=getType(pic)
				multipartbody = MPboundary&vbCrLf
				multipartbody  =multipartbody&"Content-Disposition: form-data; name="""&arr(0)&"""; filename="""&filename(1)&""""&vbCrLf
				multipartbody  =multipartbody&"Content-Type: "&filename(0)&""&vbCrLf&vbCrLf
				objFile.WriteText multipartbody
				objFile.Position   =   0 
				objFile.Type   =   1  
				objFile.Position   =   objFile.Size 
				objFile.Write   content
				objFile.Position   =   0 
				objFile.Type   =   2  
				objFile.Position   =   objFile.Size 
				objFile.WriteText  vbCrLf	
			Else 
				multipartbody = MPboundary&vbCrLf
				multipartbody = multipartbody&"Content-Disposition: form-data; name="""&arr(0)&""""&vbCrLf&vbCrLf
				multipartbody = multipartbody&rfcDecoding(arr(1))&vbCrLf
				objFile.WriteText multipartbody
			End If 
		Next
		objFile.WriteText  endMPboundary&vbCrLf 
		objFile.Position   =   0
		objFile.Type   =   1 
		data = objFile.Read(-1)	
		objFile.Close 	
		Set objFile=Nothing
		build_multi = data
	End Function
		'提前文件类型
	Function getType(url)
		Dim imgType,arr(1)
		imgType=Right(LCase(url),4)
		Select Case imgType
			Case ".jpg","jpeg"
				arr(0)="image/jpeg"
				arr(1)="tmp.jpg"
			Case ".gif"
				arr(0)="image/gif"
				arr(1)="tmp.gif"
			Case ".png"
				arr(0)="image/png"
				arr(1)="tmp.png"
			Case ".bmp"
				arr(0)="image/bmp"
				arr(1)="tmp.bmp"
			Case Else
				arr(0)="image/jpeg"
				arr(1)="tmp.jpg"
			End Select 
			getType=arr
	End Function
'获取图片数据流
	Function getPic(url)
		Dim objFile,data
		If  InStr(url,"http://")>0 Then
			Set  xmlhttp=Server.CreateObject("MSXML2.ServerXMLHTTP")
			xmlhttp.open "GET",url,false			 
			xmlhttp.send()
			data=xmlhttp.responseBody
			Set xmlhttp=Nothing
		End If 
		getPic=data
	End Function
	
	Private Sub Error_Msg(Error_Title)
		response.Write Error_Title
		response.End()
	End sub
	
End Class

%>
<script language="jscript" runat="server">
function rfcEncoding(str){var tmp=encodeURIComponent(str);tmp=tmp.replace('!','%21');tmp=tmp.replace('*','%2A');tmp=tmp.replace('(','%28');tmp=tmp.replace(')','%29');tmp=tmp.replace("'",'%27');return tmp;}function rfcDecoding(s){if(s!=null){s=s.replace(/\+/g," ");}return decodeURIComponent(s);}
Array.prototype.get = function(prop) { 
	return this[prop]; 
} 
function json_decode(json) {
eval("var o=" + json);
return o;
}
</script>   