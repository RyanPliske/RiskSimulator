<%
'get years
yStart = request.Form("yStart")
yEnd = request.Form("yEnd")
%>
<!-- #include virtual="/Projects/declarations.asp" -->

<html>
<head>
<title>Other revenues</title>
<link rel="stylesheet" type="text/css" href="help.css" />
</head>

<body>

<center><h1>Other revenue</h1></center>
<i>Other revenue is any other revenue obtained from the crop or livestock that is not obtained from the primary products.</i>

<%

dim i
for i = 0 to ubound(oCrop)-1
	if oCrop(i).Used = "true" then
		if oCrop(i).Group = "crop" then cropUsed=true
		if oCrop(i).Label = "cowcalf" then beefUsed=true
		if oCrop(i).Label = "dairy" then dairyUsed=true
    end if
next

%>

<% if cropUsed then %>
<p><h2>Crops</h2>
Other revenue for crops includes crop insurance indemnities, relevant government payments, and the sale of secondary products such as straw. 
These are set to zero by default.
<% end if %>

<% if beefUsed then %>
<p><h2>Cow/calf</h2>
Revenue from the sale of cull cattle and breeding stock sales.  The default value is based on forecasts of USDA data.
<% end if %>

<% if dairyUsed then %>
<p><h2>Dairy</h2>
Revenue from the sale of cattle.  The default value is based on forecasts of USDA data.
<% end if %>

<p>&nbsp;
<img align="right" src="logo.png" alt="FAPRI logo">
<p><input type="button" value="Close" onClick="javascript:window.close();" />

</body>
</html>
