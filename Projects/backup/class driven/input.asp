<!-- #include virtual="Projects/ado_open.asp" -->
<%

'Define the years
dim str
public yStart, yEnd
yStart = 2011: yEnd=2015
str = "<tr class=year><td>&nbsp;</td>"
for i = yStart to yEnd
	str = str & "<td align=right>" & i & "</td>" 'year labels
next
str = str & "</tr>"

%>

<!-- #include virtual="/Projects/declarations.asp" -->
<!-- #include virtual="/Projects/load_values.asp" -->

<%
'this is a good place to save the user input
Set rsData = Server.CreateObject("ADODB.Recordset")
strSQL = "SELECT tblUsage.* FROM tblUsage;"		
rsData.CursorType = 2 'Set the cursor type we are using so we can navigate through the recordset
rsData.LockType = 3 'Set the lock type so that the record is locked by ADO when it is updated
rsData.Open strSQL, adoCon 'Open the recordset with the SQL query
rsData.addnew
rsData("FIPS") = request.Form("fips")
rsData("Time")=now()
on error resume next
for i = 0 to ubound(oCrop)-1
	rsData(oCrop(i).Label)= oCrop(i).Used
next
rsData.update
rsData.close
set rsData = nothing

%>


<center><h1>Input data</h1></center>

<form name="frm2" id="frm2" action="results.asp" method="post">

<!-- need to save which crops are used -->
<%
for i = 0 to ubound(oCrop)-1
	if oCrop(i).Used = "true" then
		response.Write(vbcrlf & "<input type=hidden name=" & oCrop(i).Label & " value="& oCrop(i).Used & " />")
	end if
next

'also need to save the years
response.Write(vbcrlf & "<input type=hidden name=yStart value=" & yStart & ">")
response.Write(vbcrlf & "<input type=hidden name=yEnd value=" & yEnd & ">")
%>

<!-- function to copy over acres -->
<script type="text/javascript">


window.CopyScale = function () {

	var a = new Array();
	<%
		j=0
		for i = 0 to ubound(oCrop)-1
			if oCrop(i).Used = "true" then
				response.Write(vbcrlf & "a[" & j & "] = '" & oCrop(i).Label & "';")
				j=j+1
			end if
		next
		
		response.Write(vbcrlf & "var yStart=" & yStart & "; yEnd=" & yEnd & ";")
	%>
	
	//Enter new values
	for( i=0; i<a.length; i++) 
		for( j=yStart+1; j<=yEnd; j++) 
			if (document.getElementById(a[i]+'_NoUnits'+yStart).value != '') {//This is to ensure zeroes are not entered for missing values
				document.getElementById(a[i]+'_NoUnits'+j).value = document.getElementById(a[i]+'_NoUnits'+yStart).value;	
				OnLeave(a[i]+'_NoUnits'+j,0);
			}
	
}

</script>

<table>
<tr><td colspan="6" class="labels">Scale of operation <input type="button" value="Copy 2011" onclick="javascript:CopyScale();" /></td></tr>

<%
response.Write(str)
for i = 0 to ubound(oCrop)-1
	if oCrop(i).Used = "true" then
		response.Write("<tr><td>" & oCrop(i).CName & ", " & oCrop(i).NoUnitsUnit & "s&nbsp;</td>")
		for j = yStart to yEnd
			response.Write(vbcrlf & "<td><input class=text type=text id=" & oCrop(i).Label & "_NoUnits" & j & " name=" & oCrop(i).Label & "_NoUnits" & j & " onclick='javascript:this.select();'" & _
				" onblur='javascript:OnLeave(this.id,0);' /></td>")
		next
		response.Write("</tr>")
	end if
next
%>

<tr><td colspan="6">&nbsp;</td></tr>
<tr><td colspan="6" class="labels">Yields</td></tr>
<%
response.Write(str)
for i = 0 to ubound(oCrop)-1
	if oCrop(i).Used = "true" then
		response.Write("<tr><td>" & oCrop(i).CName & ", " & oCrop(i).YieldUnit & "&nbsp;" & iif(oCrop(i).Label="cowcalf","<br>Calving rate","") & "</td>")
		for j = yStart to yEnd
			if not isempty(oCrop(i).Yield(j-yStart)) then
				response.Write(vbcrlf &"<td><input class=text type=text id=" & oCrop(i).Label & "_Yield" & j & " name=" & oCrop(i).Label & "_Yield" & j & _
					" value=" & FormatNumber(oCrop(i).Yield(j-yStart),1) & " onclick='javascript:this.select();' onblur='javascript:OnLeave(this.id,1);' ></td>")
			else
				if oCrop(i).Label="cowcalf" then 'if this is cow/calf, we need yield per calf and calving rate
					response.Write(vbcrlf & "<td><input class=text type=text id=" & oCrop(i).Label & "_Yield_Raw" & j & " name=" & oCrop(i).Label & "_Yield_Raw" & j & _
						" value='600.0' onclick='javascript:this.select();' onblur='javascript:OnLeave(this.id,1); CalcBeefYield("&j&"); CalcBeefPrice(); OnLeave(this.id,2); CalcPrices();' />") 'shown yield input
					response.Write(vbcrlf & "<br><input class=text type=text id=" & oCrop(i).Label & "_CalvingRate" & j & " name=" & oCrop(i).Label & "_CalvingRate" & j & _
						" value='.90' onclick='javascript:this.select();' onblur='javascript:OnLeave(this.id,2); CalcBeefYield("&j&"); OnLeave(this.id,2);' />") 'calving rate input
					response.Write(vbcrlf & "<input class=text type=hidden id=" & oCrop(i).Label & "_Yield" & j & " name=" & oCrop(i).Label & "_Yield" & j & _
						" value='540' /></td>") 'hidden actual input
				else 'if not cow/calf
					response.Write(vbcrlf & "<td><input class=text type=text id=" & oCrop(i).Label & "_Yield" & j & " name=" & oCrop(i).Label & "_Yield" & j & _
						" onclick='javascript:this.select();' onblur='javascript:OnLeave(this.id,1);' /></td>")
				end if
			end if
		next
		response.Write("</tr>")
	end if
next
%>

<tr><td colspan="6">&nbsp;</td></tr>
<tr><td colspan="6" class="labels">Historical prices</td></tr>
	<tr class="year"><td>&nbsp;</td><td align="right"><% response.Write(yStart-3) %></td><td align="right"><% response.Write(yStart-2) %></td><td align="right"><% response.Write(yStart-1) %></td></tr>
<%
for i = 0 to ubound(oCrop)-1
	if oCrop(i).Used = "true" then
		response.Write("<tr><td>" & oCrop(i).CName & ", " & oCrop(i).PriceUnit & "&nbsp;</td>")
		strSQL = "SELECT tblStatePrices.* FROM tblStatePrices WHERE StFips='" & left(request.Form("fips"),2) & "' AND CommCode=" & oCrop(i).CommCode & ";"
		Set rsData = adoCon.execute(strSQL)
		for j = -3 + yStart to -1 + yStart
			if not rsData.eof and not rsData.bof and not isnull(rsData(cstr(j))) then
				response.Write(vbcrlf & "<td><input class=text type=text id=" & oCrop(i).Label & "_Price" & j & " name=" & oCrop(i).Label & "_Price" & j & " value=" & FormatNumber(rsData(cstr(j)),3) & _
				 " onblur='javascript:OnLeave(this.id,3);CalcPrices(); ' onclick='javascript:this.select();' ></td>")
			else
				response.Write(vbcrlf & "<td><input class=text type=text id=" & oCrop(i).Label & "_Price" & j & " name=" & oCrop(i).Label & "_Price" & j & _
					" onblur='javascript:OnLeave(this.id,3); CalcPrices();' onclick='javascript:this.select();' /></td>")
			end if
		next
		response.Write("</tr>")
		rsData.close
		set rsData = nothing
	end if
next
%>

<!-- function to calculate future farm prices -->
<script type="text/javascript">

window.CalcPrices = function () {

	var a = new Array();
	<%
		j=0
		for i = 0 to ubound(oCrop)-1
			if oCrop(i).Used = "true" then
				response.Write(vbcrolf & "a[" & j & "] = '" & oCrop(i).Label & "';")
				j=j+1
			end if
		next
		
		response.Write(vbcrlf & "var yStart=" & yStart & "; yEnd=" & yEnd & ";")
	%>
	//calculate the basis;
	var basis = new Array();
	for(var i=0; i<a.length; i++) {
		basis[i]=0;
		for( j=yStart-3; j<yStart; j++) {
			//format: basis = local price - US price
			basis[i] += document.getElementById(a[i]+'_Price'+j).value.replace(',','')*1 - document.getElementById(a[i]+'_Price'+j+'_US').value*1;
		}
		 basis[i] /= 3;
	}
	
	//Enter new values
	for( i=0; i<a.length; i++) 
		for( j=yStart; j<=yEnd; j++) 
			//format: basis = local price - US price
			if (document.getElementById(a[i]+'_Price'+j+'_US').value != '') {//This is to ensure zeroes are not entered for missing prices
				if(document.getElementById(a[i]+'_Price'+(yStart-1)*1).value != '' && document.getElementById(a[i]+'_Price'+(yStart-2)*1).value != '' 
					&& document.getElementById(a[i]+'_Price'+(yStart-3)*1).value != '') {//don't enter future prices if any of the historical are not entered
					document.getElementById(a[i]+'_Price'+j).value = basis[i]*1 + document.getElementById(a[i]+'_Price'+j+'_US').value*1; 
				}
				else document.getElementById(a[i]+'_Price'+j).value = ''; //future prices should be blank if historical are
				OnLeave(a[i]+'_Price'+j,3);
			}
				
}

</script>

<!-- save national prices -->
<%
for i = 0 to ubound(oCrop)-1
	if oCrop(i).Used = "true" then
		if oCrop(i).Label <> "dairy" then
			strSQL = "SELECT tblNationalPrices.* FROM tblNationalPrices WHERE CommCode=" & oCrop(i).CommCode & ";"
		else
			strSQL = "SELECT tblStatePrices.* FROM tblStatePrices WHERE StFips='" & left(request.Form("fips"),2) & "' AND CommCode=" & oCrop(i).CommCode & ";"
		end if
		Set rsData = adoCon.execute(strSQL)
		for j = -3 + yStart to yEnd
			if not rsData.eof and not rsData.bof and not isnull(rsData(cstr(j))) then
				response.Write(vbcrlf & "<input class=text type=hidden name='" & oCrop(i).Label & "_Price" & j & "_US' id='" & oCrop(i).Label & "_Price" & j & "_US' value=" & rsData(cstr(j)) & " />")
			else
				response.Write(vbcrlf & "<input class=text type=hidden name='" & oCrop(i).Label & "_Price" & j & "_US' id='" & oCrop(i).Label & "_Price" & j & "_US' />")
			end if
		next
		rsData.close
		set rsData = nothing
	end if
next

'save historical local beef prices if necessary
dim intBeef: intBeef=LookupComm("cowcalf")
if oCrop(intBeef).Used="true" then
	strSQL = "SELECT tblStatePrices.* FROM tblStatePrices WHERE StFips='" & left(request.Form("fips"),2) & "' AND CommCode=" & oCrop(intBeef).CommCode & ";"
	Set rsData = adoCon.execute(strSQL)
	for j = yStart-3 to yStart-1
		if not rsData.eof and not rsData.bof and not isnull(rsData(cstr(j))) then
			  response.Write(vbcrlf & "<input type=hidden id=" & oCrop(intBeef).Label & "_Price" & j & "_St name=" & oCrop(intBeef).Label & "_Price" & j & "_St value=" & rsData(cstr(j)) & " />")
		  else
			  response.Write(vbcrlf & "<input type=hidden id=" & oCrop(intBeef).Label & "_Price" & j & "_St name=" & oCrop(intBeef).Label & "_Price" & j & "_St />")
		  end if
	next
	rsData.close
	set rsData = nothing
end if
%>

<tr><td colspan="6">&nbsp;</td></tr>
<tr><td colspan="6" class="labels">Forecasted prices</td></tr>
<%
response.Write(str)
for i = 0 to ubound(oCrop)-1
	if oCrop(i).Used = "true" then
		response.Write("<tr><td>" & oCrop(i).CName & ", " & oCrop(i).PriceUnit & "&nbsp;</td>")
		for j = yStart to yEnd
			response.Write("<td><input class=text type=text id=" & oCrop(i).Label & "_Price" & j & " name=" & oCrop(i).Label & "_Price" & j & _
				" onclick='javascript:this.select();' onblur='javascript:OnLeave(this.id,3);' /></td>")
		next
		response.Write("</tr>")
	end if
next
%>

<script type="text/javascript">CalcPrices();</script>

<tr><td colspan="6">&nbsp;</td></tr>
<tr><td colspan="6" class="labels">Other revenues</td></tr>
<%

response.Write(str)
for i = 0 to ubound(oCrop)-1
	if oCrop(i).Used = "true" then
		response.Write("<tr><td>" & oCrop(i).CName & ", $ per " & oCrop(i).NoUnitsUnit & "&nbsp;</td>")
		
		'get the region
		strSQL = "SELECT tblOtherRev.* FROM tblOtherRev INNER JOIN tblOtherRev ON tblRegions.Region = tblOtherRev.Region " & _
			"WHERE tblRegions.Fips='" & request.Form("fips") & "' AND tblOtherRev.CommCode=" & oCrop(i).CommCode & ";"
		Set rsData = adoCon.execute(strSQL)
		if rsData.eof and rsData.bof then
			Region = 0 'if there is no cost data, use the national
		else
			Region = rsData("Region")
		end if
		
		'regional other revenue
		strSQL = "SELECT tblOtherRev.* FROM tblOtherRev " & _
			"WHERE tblOtherRev.Region=" & Region & " AND tblOtherRev.CommCode=" & oCrop(i).CommCode & ";"
		Set rsData = adoCon.execute(strSQL)
		
		for j = yStart to yEnd
			if not rsData.eof and not rsData.bof and not isnull(rsData(cstr(j))) then
				response.Write(vbcrlf & "<td><input class=text type=text id=" & oCrop(i).Label & "_OthRev" & j & " name=" & oCrop(i).Label & "_OthRev" & j & _
					" onclick='javascript:this.select();' onblur='javascript:OnLeave(this.id,2);' value=" & FormatNumber(rsData(cstr(j)),2) & " /></td>")
			else
				response.Write(vbcrlf & "<td><input class=text type=text id=" & oCrop(i).Label & "_OthRev" & j & " name=" & oCrop(i).Label & "_OthRev" & j & _
					" onclick='javascript:this.select();' onblur='javascript:OnLeave(this.id,2);' value=0.00 /></td>")
			end if
		next
		response.Write("</tr>")
	end if
next

%>

<tr><td colspan="6">&nbsp;</td></tr>
<tr><td colspan="6" class="labels">Variable costs</td></tr>
<%
dim rsCoYield, rsRgYield, rsRegion, k, CoYield, RgYield, Region
response.Write(str)

for i = 0 to ubound(oCrop)-1
	if oCrop(i).Used = "true" then
	
		'label column
		response.Write("<tr><td>" & oCrop(i).CName & ", $ per " & oCrop(i).CostUnitLabel & "&nbsp;</td>")
		
		'get the region
		strSQL = "SELECT tblRegionVarCosts.* FROM tblRegions INNER JOIN tblRegionVarCosts ON tblRegions.Region = tblRegionVarCosts.Region " & _
			"WHERE tblRegions.Fips='" & request.Form("fips") & "' AND tblRegionVarCosts.CommCode=" & oCrop(i).CommCode & ";"
		Set rsData = adoCon.execute(strSQL)
		if rsData.eof and rsData.bof then
			Region = 0 'if there is no cost data, use the national
		else
			Region = rsData("Region")
		end if
		
		'regional costs
		strSQL = "SELECT tblRegionVarCosts.* FROM tblRegionVarCosts " & _
			"WHERE tblRegionVarCosts.Region=" & Region & " AND tblRegionVarCosts.CommCode=" & oCrop(i).CommCode & ";"
		Set rsData = adoCon.execute(strSQL)
		
		'regional yields
		strSQL = "SELECT tblRegions.Region, tblCountyYields.CommCode" 'these are the weighted regional yields
			for k = yStart - 12 to yStart - 3
				strSQL = strSQL & ", Sum(tblCountyYields.[" & k & "]*tblCountyHarvested.[" & k & "])/Sum(tblCountyHarvested.[" & k & "]) AS " & k & " "
			next
		strSQL = strSQL & "FROM (tblRegions INNER JOIN tblCountyHarvested ON tblRegions.Fips = tblCountyHarvested.Fips) INNER JOIN tblCountyYields ON (tblCountyHarvested.Fips = tblCountyYields.Fips) AND " & _
			"(tblCountyHarvested.CommCode = tblCountyYields.CommCode) " & _
			"WHERE ((tblCountyYields.CommCode)=" & oCrop(i).CommCode & ")" & iif(Region<>0," AND ((tblRegions.Region)=" & Region & ")","") & _
			"Group by tblRegions.Region, tblCountyYields.CommCode;"
		set rsRgYield = adoCon.execute(strSQL)
		AvgRgYield = 1
		if not rsRgYield.eof and not rsRgYield.bof then 'make sure there is a regional yield
			for k = yStart - 12 to yStart - 3
				AvgRgYield = AvgRgYield + rsRgYield(cstr(k))
			next
		end if
		
		'county yields
		strSQL = "SELECT tblCountyYields.* FROM tblCountyYields WHERE Fips='" & request.Form("fips") & "' AND CommCode=" & oCrop(i).CommCode & ";"
		Set rsCoYield = adoCon.execute(strSQL)
		AvgCoYield=1
		for k = yStart - 12 to yStart - 3
			if not rsCoYield.eof and not rsCoYield.bof and not isnull(rsCoYield(cstr(k))) then 'make sure all the yields exist
				AvgCoYield = AvgCoYield + rsCoYield(cstr(k))
			else 'if they don't, set county yield equal to regional yield
				AvgCoYield = AvgRgYield
				exit for
			end if
		next

		'create input boxes
		for j = yStart to yEnd
			if not rsData.eof and not rsData.bof and not isnull(rsData(cstr(j))) then
				response.Write("<td><input class=text type=text id=" & oCrop(i).Label & "_VarCost" & j & " name=" & oCrop(i).Label & "_VarCost" & j & " value='" & _
					FormatNumber(rsData(cstr(j))*((AvgCoYield/AvgRgYield-1)*.5+1),2) & "' onclick='javascript:this.select();' onblur='javascript:OnLeave(this.id,2);' /></td>")
			else
				response.Write("<td><input class=text type=text id=" & oCrop(i).Label & "_VarCost" & j & " name=" & oCrop(i).Label & "_VarCost" & j & _
					" onclick='javascript:this.select();' onblur='javascript:OnLeave(this.id,2);' /></td>")
			end if
		next
		response.Write("</tr>")
		
		rsData.close
		rsCoYield.close
		rsRgYield.close
		set rsData = nothing
		set rsCoYield = nothing
		set rsRgYield = nothing
	end if
next
%>

<tr><td colspan="6">&nbsp;</td></tr>
<tr><td colspan="6" class="labels">Fixed costs</td></tr>
<%
response.Write(str)
for i = 0 to ubound(oCrop)-1
	if oCrop(i).Used = "true" then
		response.Write("<tr><td>" & oCrop(i).CName & ", $ per " & oCrop(i).CostUnitLabel & "&nbsp;</td>")
		
		'get the region
		strSQL = "SELECT tblRegionFixedCosts.* FROM tblRegions INNER JOIN tblRegionFixedCosts ON tblRegions.Region = tblRegionFixedCosts.Region " & _
			"WHERE tblRegions.Fips='" & request.Form("fips") & "' AND tblRegionFixedCosts.CommCode=" & oCrop(i).CommCode & ";"
		Set rsData = adoCon.execute(strSQL)
		if rsData.eof and rsData.bof then
			Region = 0 'if there is no cost data, use the national
		else
			Region = rsData("Region")
		end if
		
		'regional yields
		strSQL = "SELECT tblRegions.Region, tblCountyYields.CommCode" 'these are the weighted regional yields
			for k = yStart - 12 to yStart - 3
				strSQL = strSQL & ", Sum(tblCountyYields.[" & k & "]*tblCountyHarvested.[" & k & "])/Sum(tblCountyHarvested.[" & k & "]) AS " & k & " "
			next
		strSQL = strSQL & "FROM (tblRegions INNER JOIN tblCountyHarvested ON tblRegions.Fips = tblCountyHarvested.Fips) INNER JOIN tblCountyYields ON (tblCountyHarvested.Fips = tblCountyYields.Fips) AND " & _
			"(tblCountyHarvested.CommCode = tblCountyYields.CommCode) " & _
			"WHERE ((tblCountyYields.CommCode)=" & oCrop(i).CommCode & ")" & iif(Region<>0," AND ((tblRegions.Region)=" & Region & ")","") & _
			"Group by tblRegions.Region, tblCountyYields.CommCode;"
			'response.Write("<tr><td colspan=5>" & strSQL & "</tr></td>")
		set rsRgYield = adoCon.execute(strSQL)
		AvgRgYield = 1
		if not rsRgYield.eof and not rsRgYield.bof then 'make sure there is a regional yield
			for k = yStart - 12 to yStart - 3
				AvgRgYield = AvgRgYield + rsRgYield(cstr(k))
			next
		end if
		
		'county yields
		strSQL = "SELECT tblCountyYields.* FROM tblCountyYields WHERE Fips='" & request.Form("fips") & "' AND CommCode=" & oCrop(i).CommCode & ";"
		Set rsCoYield = adoCon.execute(strSQL)
		AvgCoYield=1
		for k = yStart - 12 to yStart - 3
			if not rsCoYield.eof and not rsCoYield.bof and not isnull(rsCoYield(cstr(k))) then 'make sure all the yields exist
				AvgCoYield = AvgCoYield + rsCoYield(cstr(k))
			else 'if they don't, set county yield equal to regional yield
				AvgCoYield = AvgRgYield
				exit for
			end if
		next

		'regional costs
		strSQL = "SELECT tblRegionFixedCosts.* FROM tblRegionFixedCosts " & _
			"WHERE tblRegionFixedCosts.Region=" & Region & " AND tblRegionFixedCosts.CommCode=" & oCrop(i).CommCode & ";"
		Set rsData = adoCon.execute(strSQL)
		for j = yStart to yEnd
			if not rsData.eof and not rsData.bof and not isnull(rsData(cstr(j))) then
				response.Write("<td><input class=text type=text id=" & oCrop(i).Label & "_FixCost" & j & " name=" & oCrop(i).Label & "_FixCost" & j & " value='" & _
					FormatNumber(rsData(cstr(j))*((AvgCoYield/AvgRgYield-1)*.5+1),2) & "' onclick='javascript:this.select();' onblur='javascript:OnLeave(this.id,2);' /></td>")
			else
				response.Write("<td><input class=text type=text id=" & oCrop(i).Label & "_FixCost" & j & " name=" & oCrop(i).Label & "_FixCost" & j & _
					" onclick='javascript:this.select();' onblur='javascript:OnLeave(this.id,2);' /></td>")
			end if	
		next
		response.Write("</tr>")
		
		rsData.close
		rsCoYield.close
		rsRgYield.close
		set rsData = nothing
		set rsCoYield = nothing
		set rsRgYield = nothing
	end if
next
%>
</table>

<p><input type="button" onclick="javascript:Validate();" value="Submit" />

<input type="hidden" name="inpfrm" id="inpfrm" />

</form>

<script type="text/javascript">
window.LoadCheck = function () {

	var inputs = document.forms['frm2'].getElementsByTagName('input');
	var inpname;

	for (var i=0; i<inputs.length; i++) 
		if (inputs.item(i).value=='' && inputs[i].getAttribute('type')=='text')
			inpname = isBlank(inputs.item(i).id);

}

LoadCheck();

window.Validate = function () {

	var inputs = document.forms['frm2'].getElementsByTagName('input');
	var inpname = '';

	for (var i=0; i<inputs.length; i++) 
		if (inputs.item(i).value=='' && inputs[i].getAttribute('type')=='text') {
			inpname = inputs.item(i).name;
			break;
		}
			
	if (inpname != '') {
		alert('At least one field is blank!');
		document.getElementById(inpname).select();
	}
	else {
		//document.getElementById('inpfrm').value = document.getElementById('inputform').innerHTML;
		document.getElementById('inpfrm').value = document.body.innerHTML;
		document.forms['frm2'].submit();
	}
}

<% 'inclue the beef wedges if cow/calf is chosen
	if oCrop(LookupComm("cowcalf")).Used="true" then %>

window.BeefWedge = function (wt) {

	var Price = new Array();
	var lWeight = new Array();
	var uWeight = new Array();
	wt = wt.toString().replace(',','')*1; //get rid of comma

	<%
		j=0
		strSQL = "SELECT tblCalfWedges.* FROM tblCalfWedges;"
		Set rsData = adoCon.execute(strSQL)
		do while not rsData.eof
			response.Write(vbcrlf & "   Price[" & j & "] = " & rsData("Price") & "; lWeight[" & j & "]= " & rsData("lWeight") & "; uWeight[" & j & "]= " & rsData("uWeight") & ";")
			j=j+1
			rsData.movenext
		loop
		rsData.close
		set rsData = nothing
	%>

	//make sure weight is in range
	if (wt<lWeight[0] || wt>uWeight[uWeight.length-1]) wt=600;
	
	var result=0;
	for(var i=0; i<Price.length; i++) {
		if (wt>=lWeight[i] && wt<uWeight[i]) {
			result=Price[i];
			break;
		}
	}
	
	return result;
	
}

<% end if %>

window.CalcBeefYield = function (yr) {
	document.getElementById('cowcalf_Yield'+yr).value = document.getElementById('cowcalf_Yield_Raw'+yr).value.replace(',','')*document.getElementById('cowcalf_CalvingRate'+yr).value.replace(',','');
}

window.CalcBeefPrice = function () {

	//Get average weight over projection period
	var cwedge=0; var cweight=0;
	for (var i=<%response.Write(yStart)%>; i<=<%response.Write(yEnd)%>; i++)
		cweight += document.getElementById('cowcalf_Yield_Raw'+i).value.replace(',','')*1;
	cweight /= (<%response.Write(yEnd)%>-<%response.Write(yStart)%>+1);
	cwedge = BeefWedge(cweight)*1;
	
	//change historical prices
	for (i=<%response.Write(yStart-3)%>; i<=<%response.Write(yStart-1)%>; i++) {
		document.getElementById('cowcalf_Price'+i).value = document.getElementById('cowcalf_Price'+i+'_St').value.replace(',','')*1 + cwedge;
		OnLeave('cowcalf_Price'+i,3);
	}
	CalcPrices();

}

</script>


<!-- #include virtual="Projects/ado_close.asp" -->