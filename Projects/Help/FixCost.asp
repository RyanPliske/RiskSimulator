<%
'get years
yStart = request.Form("yStart")
yEnd = request.Form("yEnd")
%>
<!-- #include virtual="/Projects/declarations.asp" -->
<html>
<head>
<title>Fixed costs</title>
<link rel="stylesheet" type="text/css" href="help.css" />
</head>

<body>

<center><h1>Fixed costs</h1></center>

<%

dim i
for i = 0 to ubound(oCrop)-1
	if oCrop(i).Used = "true" then
		response.write(vbcrlf & "<p><h2>" & oCrop(i).CName & "</h2>")
    	response.write(vbcrlf & oCrop(i).FixCostHelp)
    end if
next

%>

<p>&nbsp;
<img align="right" src="logo.png" alt="FAPRI logo">
<p><input type="button" value="Close" onClick="javascript:window.close();" />

</body>
</html>
