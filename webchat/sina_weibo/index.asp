<!--#include file="config.asp"-->
<!--#include file="api.func.asp"-->
<html><head>
<meta http-equiv="X-UA-Compatible" content="IE=7">
<meta http-equiv="content-type" content="text/html;charset=gb2312">
<title>ASP��¼�ӿ�</title>
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

<h1>��¼�ӿ�</h1>
<a href="<%=GetLoginUrl("qq2")%>">ʹ����Ѷ�˺ŵ�¼</a> <%=CheckStatus("qq2")%><br />
<a href="<%=GetLoginUrl("qq")%>">ʹ����Ѷ΢����¼</a> <%=CheckStatus("qq")%><br />
<a href="<%=GetLoginUrl("weibo")%>">ʹ������΢����¼</a> <%=CheckStatus("weibo")%><br />
<a href="<%=GetLoginUrl("sohu")%>">ʹ���Ѻ�΢����¼</a> <%=CheckStatus("sohu")%><br />
<a href="<%=GetLoginUrl("163")%>">ʹ������΢����¼</a> <%=CheckStatus("163")%><br />
<a href="<%=GetLoginUrl("ifeng")%>">ʹ�÷��΢����¼</a> <font color=red>��Ч</font><br />
<a href="<%=GetLoginUrl("tudou")%>">ʹ���������˺ŵ�¼</a> <%=CheckStatus("tudou")%><br />
<h1 style="color:#ff0000;text-align:center">���²������¼�����</h1>
<hr />
<h1>������΢��</h1>
�ı���Ϣ��<font color=red><%txt="��������΢��"&Now()%><%=txt%></font><br />
<a href="<%=PostTxt("qq","text",txt,"")%>">ʹ����Ѷ΢������</a><br />
<a href="<%=PostTxt("weibo","text",txt,"")%>">ʹ������΢������</a><br />
<a href="<%=PostTxt("sohu","text",txt,"")%>">ʹ���Ѻ�΢������</a><br />
<a href="<%=PostTxt("163","text",txt,"")%>">ʹ������΢������</a><br />
ʹ�÷��΢������<br />

<h1>��ͼƬ΢��</h1>
�ı���Ϣ��<font color=red><%txt="����ͼƬ΢��"&Now():pic="http://cz886.com/sina_weibo/demo.jpg"%>
<%=txt%><img src="<%=pic%>"></font><br />
<a href="<%=PostTxt("qq","pic",txt,pic)%>">ʹ����Ѷ΢������</a><br />
<a href="<%=PostTxt("weibo","pic",txt,pic)%>">ʹ������΢������</a><br />
<a href="<%=PostTxt("sohu","pic",txt,pic)%>">ʹ���Ѻ�΢������</a> <font color=#ff00ff>������</font><br />
<a href="<%=PostTxt("163","pic",txt,pic)%>">ʹ������΢������</a><br />
ʹ�÷��΢������<br />

<h1>������΢��</h1>
�ı���Ϣ��<font color=red><%txt="��������΢��"&Now():data="http://demo.onez.cn/20111219/trouble is a friend.mp3|trouble is a friend|lenka"%>
<%=txt%></font> ��trouble is a friend��<br />
<a href="<%=PostTxt("qq","music",txt,data)%>">ʹ����Ѷ΢������</a><br />
ʹ������΢������<br />
ʹ���Ѻ�΢������<br />
ʹ������΢������<br />
ʹ�÷��΢������<br />

<h1>����Ƶ΢��</h1>
�ı���Ϣ��<font color=red><%txt="������Ƶ΢��"&Now():data="http://v.youku.com/v_show/id_XMjQ3MTU0MjA4.html"%>
<%=txt%></font> ��http://v.youku.com/v_show/id_XMjQ3MTU0MjA4.html��<br />
<a href="<%=PostTxt("qq","video",txt,data)%>">ʹ����Ѷ΢������</a><br />
ʹ������΢������<br />
<a href="<%=PostTxt("sohu","video",txt,data)%>">ʹ���Ѻ�΢������</a> <font color=#ff00ff>������</font><br />
ʹ������΢������<br />
ʹ�÷��΢������<br />

<h1>����ǰ�û�����</h1>
<a href="<%=GetInfo2("qq2")%>">��ȡ��Ѷ�˺�����</a><br />
<a href="<%=GetInfo2("qq")%>">��ȡ��Ѷ΢���˺�����</a><br />
<a href="<%=GetInfo2("weibo")%>">��ȡ����΢���˺�����</a><br />
<a href="<%=GetInfo2("sohu")%>">��ȡ�Ѻ�΢���˺�����</a><br />
<a href="<%=GetInfo2("163")%>">��ȡ����΢���˺�����</a><br />

<h1>�������û�����</h1>
<a href="<%=GetInfo("qq2","6200103")%>">��ȡ��Ѷ�˺š�6200103��������</a><br />
<a href="<%=GetInfo("qq","onezcn")%>">��ȡ��Ѷ΢���˺š�onezcn��������</a><br />
<a href="<%=GetInfo("weibo","��������")%>">��ȡ����΢���˺š��������ߡ�������</a><br />
<a href="<%=GetInfo("sohu","����������")%>">��ȡ�Ѻ�΢���˺š����������ҡ�������</a><br />
<a href="<%=GetInfo("163","����������")%>">��ȡ����΢���˺š����������ҡ�������</a><br />

<h1>��ע�û�</h1>
<a href="<%=Follow("qq","onezcn")%>">����Ѷ΢���й�ע��onezcn��</a><br />
<a href="<%=Follow("weibo","��������")%>">������΢���й�ע���������ߡ�</a><br />
<a href="<%=Follow("sohu","305774380")%>">���Ѻ�΢���й�ע�����������ҡ�</a><br />
<a href="<%=Follow("163","����������")%>">������΢���й�ע�����������ҡ�</a><br />

<h1>ȡ����ע�û�</h1>
<a href="<%=DisFollow("qq","onezcn")%>">����Ѷ΢����ȡ����ע��onezcn��</a><br />
<a href="<%=DisFollow("weibo","��������")%>">������΢����ȡ����ע���������ߡ�</a><br />
<a href="<%=DisFollow("sohu","305774380")%>">���Ѻ�΢����ȡ����ע�����������ҡ�</a><br />
<a href="<%=DisFollow("163","����������")%>">������΢����ȡ����ע�����������ҡ�</a><br />

<h1>��ȡ΢���б�</h1>
<a href="<%=ReadList("qq")%>">��ȡ��Ѷ΢���б�</a><br />
<a href="<%=ReadList("weibo")%>">��ȡ����΢���б�</a><br />
<a href="<%=ReadList("sohu")%>">��ȡ�Ѻ�΢���б�</a><br />
<a href="<%=ReadList("163")%>">��ȡ����΢���б�</a><br />

<h1>��Ƶ����</h1>
<a href="<%=VideoUpload("tudou")%>">�ϴ���Ƶ������</a><br />
<a href="tudou/list.asp?u=hunterso">�û�hunterso�ϴ���Ƶ�б�</a><br />
<a href="tudou/list.asp?u=hunterso&format=xml">�û�hunterso�ϴ���Ƶ�б�(XML)</a><br />
<a href="tudou/pic.asp?code=UnCL9nAYZEo">ȡ��Ƶid:UnCL9nAYZEo��ͼƬ</a><br />
<a href="tudou/pic-UnCL9nAYZEo.jpg">ȡ��Ƶid:UnCL9nAYZEo��ͼƬ</a><br />
</body>
</html>