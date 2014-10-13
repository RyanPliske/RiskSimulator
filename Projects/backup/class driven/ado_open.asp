<%
dim adoCon, strSQL, rsData
Set adoCon = Server.CreateObject("ADODB.Connection")
adoCon.Open ("Provider= Microsoft.ACE.OLEDB.12.0;Data Source=" & Server.MapPath("budget.accdb"))
%>