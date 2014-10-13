<%@LANGUAGE="VBSCRIPT" CODEPAGE="65001"%>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">


<%
dim j

'get years
yStart = request.Form("yStart")
yEnd = request.Form("yEnd")

'Define the years
dim str
str = "<tr><td>&nbsp;</td>"
for i = yStart to yEnd
	str = str & "<td align=right><i>" & i & "</i></td>" 'year labels
next
str = str & "</tr>"
%>

<!-- #include virtual="/Projects/declarations.asp" --> 

<%

'save forms to session variable
session("forms")=request.Form("inpfrm")

'See which crops are used
for i = 0 to ubound(oCrop)-1
	if request.Form(oCrop(i).Label) = "true" then
		oCrop(i).Used = "true"
	else
		oCrop(i).Used = "false"
	end if
next

'Get the sent variables
for i = 0 to ubound(oCrop)-1
	if oCrop(i).Used = "true" then
		for j = 0 to yEnd - yStart
			oCrop(i).NoUnits(j) = cdbl(request.Form(oCrop(i).Label & "_NoUnits" & j + yStart)) 'scale of operation
			oCrop(i).Price(j) = cdbl(request.Form(oCrop(i).Label & "_Price" & j + yStart)) 'prices
			oCrop(i).Yield(j) = cdbl(request.Form(oCrop(i).Label & "_Yield" & j + yStart)) 'yields
			oCrop(i).OthRev(j) = cdbl(request.Form(oCrop(i).Label & "_OthRev" & j + yStart)) 'other revenue
			oCrop(i).VarCost(j) = cdbl(request.Form(oCrop(i).Label & "_VarCost" & j + yStart)) 'variable costs
			oCrop(i).FixCost(j) = cdbl(request.Form(oCrop(i).Label & "_FixCost" & j + yStart)) 'variable costs
			if oCrop(i).Label="dairy" then oCrop(i).CostUnitAdj(j)=oCrop(i).Yield(j)/100 * oCrop(i).NoUnits(j)
		next
		oCrop(i).Calc 'calculate the financials
	end if
next

%>

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link rel="stylesheet" type="text/css" href="projects.css" />
<script type="text/javascript" language="javascript" src="results.js"></script>
</head>

<body>

<center><h1>Results</h1></center>

<table cellspacing="0" cellpadding="20px" id="tblmain">
	<tr>
		<td valign="top">

          <h2>Per acre profits</h2>
          <table class="result">
          <%
          response.Write(str)
          
          dim TotalProfit(), TotalAcres()
          redim TotalProfit(yEnd-yStart+1), TotalAcres(yEnd-yStart+1)
          
          'commodity specific per acre profits
          for i = 0 to ubound(oCrop)-1
              if oCrop(i).Used = "true" and oCrop(i).Group = "crop" then
                  response.Write(vbcrlf & "<tr><td>" & oCrop(i).CName & "</td>")
                  for j = 0 to yEnd - yStart
                      TotalProfit(j)=TotalProfit(j)+oCrop(i).Profit(j)
                      TotalAcres(j)=TotalAcres(j)+oCrop(i).NoUnits(j)
                      response.Write("<td align=right>" & FormatNumber(oCrop(i).Profit(j)/oCrop(i).NoUnits(j),2) & "</td>")
                  next
                  response.Write("</tr>")
              end if
          next
          
          'Show per acre profits
          response.Write("<tr><td>Avg. profits per acre</td>")
          for j = 0 to yEnd - yStart
              response.Write("<td align=right>" & FormatNumber(TotalProfit(j)/TotalAcres(j),2) & "</td>")
          next
          response.Write("</tr>")
          
          %>
          </table>
          
		</td>
        <td>
        
          <h2>Total profits</h2>
          <table class="result">
          <%
          response.Write(str)
          
          redim TotalProfit(yEnd-yStart+1)
          
          'commodity specific total profits
          for i = 0 to ubound(oCrop)-1
              if oCrop(i).Used = "true" then
                  response.Write(vbcrlf & "<tr><td>" & oCrop(i).CName & "</td>")
                  for j = 0 to yEnd - yStart
                      TotalProfit(j)=TotalProfit(j)+oCrop(i).Profit(j)
                      response.Write("<td align=right>" & FormatNumber(oCrop(i).Profit(j),0) & "</td>")
                  next
                  response.Write("</tr>")
              end if
          next
          
          'Show total profits
          response.Write("<tr><td>Total profit</td>")
          for j = 0 to yEnd - yStart
              response.Write("<td align=right>" & FormatNumber(TotalProfit(j),0) & "</td>")
          next
          response.Write("</tr>")
          
          %>
          </table>

		</td>
	</tr>
    
<%
	'summary for each commodity
	for i = 0 to ubound(oCrop)-1
		if oCrop(i).Used = "true" then
			response.Write(vbcrlf & "<tr>")
				
				'summary per unit
				response.Write(vbcrlf & "<td><h2>" & oCrop(i).CName & " summary per " & oCrop(i).ResultUnit & "</h2>")
				  response.Write(vbcrlf & "<table class=result>")
					  response.Write(vbcrlf & str)
					  response.Write(vbcrlf & "<tr><td>Revenues</td>")
					  for j = 0 to yEnd - yStart
						  response.Write(vbcrlf & "<td align=right>" & FormatNumber(oCrop(i).Revenue(j)/oCrop(i).CostUnitAdj(j),2) & "</td>")
					  next
					  response.Write(vbcrlf & "</tr>")
					  response.Write(vbcrlf & "<tr><td>Variable costs</td>")
					  for j = 0 to yEnd - yStart
						  response.Write(vbcrlf & "<td align=right>" & FormatNumber(oCrop(i).VarCost(j),2) & "</td>")
					  next
					  response.Write(vbcrlf & "</tr>")
					  response.Write(vbcrlf & "<tr><td>Fixed costs</td>")
					  for j = 0 to yEnd - yStart
						  response.Write(vbcrlf & "<td align=right>" & FormatNumber(oCrop(i).FixCost(j),2) & "</td>")
					  next
					  response.Write(vbcrlf & "</tr>")
					  response.Write(vbcrlf & "<tr><td>Net profit</td>")
					  for j = 0 to yEnd - yStart
						  response.Write(vbcrlf & "<td align=right>" & FormatNumber(oCrop(i).Profit(j)/oCrop(i).CostUnitAdj(j),2) & "</td>")
					  next
					  response.Write(vbcrlf & "</tr>")
				  response.Write(vbcrlf & "</table>")
				response.Write(vbcrlf & "</td>")
				
				'total
				response.Write(vbcrlf & "<td><h2>" & oCrop(i).CName & " summary</h2>")
				  response.Write(vbcrlf & "<table class=result>")
					  response.Write(vbcrlf & str)
					  response.Write(vbcrlf & "<tr><td>Revenues</td>")
					  for j = 0 to yEnd - yStart
						  response.Write(vbcrlf & "<td align=right>" & FormatNumber(oCrop(i).Revenue(j),0) & "</td>")
					  next
					  response.Write(vbcrlf & "</tr>")
					  response.Write(vbcrlf & "<tr><td>Variable costs</td>")
					  for j = 0 to yEnd - yStart
						  response.Write(vbcrlf & "<td align=right>" & FormatNumber(oCrop(i).VarCost(j)*oCrop(i).CostUnitAdj(j),0) & "</td>")
					  next
					  response.Write(vbcrlf & "</tr>")
					  response.Write(vbcrlf & "<tr><td>Fixed costs</td>")
					  for j = 0 to yEnd - yStart
						  response.Write(vbcrlf & "<td align=right>" & FormatNumber(oCrop(i).FixCost(j)*oCrop(i).CostUnitAdj(j),0) & "</td>")
					  next
					  response.Write(vbcrlf & "</tr>")
					  response.Write(vbcrlf & "<tr><td>Net profit</td>")
					  for j = 0 to yEnd - yStart
						  response.Write(vbcrlf & "<td align=right>" & FormatNumber(oCrop(i).Profit(j),0) & "</td>")
					  next
					  response.Write(vbcrlf & "</tr>")
				  response.Write(vbcrlf & "</table>")
				response.Write(vbcrlf & "</td>")
				
			response.Write("</tr>")
		end if
	next

%>
    
</table>

<script type="text/javascript">ResizeAllTables();</script>

</body>
</html>
