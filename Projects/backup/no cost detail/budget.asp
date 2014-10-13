<%@LANGUAGE="VBSCRIPT" CODEPAGE="65001"%>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link rel="stylesheet" type="text/css" href="projects.css" />
<script type="text/javascript" src="budget.js"></script>
</head>

<body>

	<div class="budget">
    <center><h1>Input parameters</h1></center>
    
    <center><noscript><span style="color:red; font-weight:bolder;">You must enable Javascript!</span></noscript></center>
    
    <form name="frm1">
    <br /><i>Select your county:</i>
    <table><tr>
    <td>State:&nbsp;</td><td><select name="cboSt" onchange="javascript:ajaxFunction();">
    <option value="0">Select state...</option>
    
    <!-- #include virtual="Projects/ado_open.asp" -->
	<%
		strSQL = "SELECT tblStates.* FROM tblStates;"
		Set rsArea = adoCon.execute(strSQL)
		Do while not rsArea.eof
			response.Write(vbcrlf & "<option value='" & rsArea("ID") & "'>" & rsArea("fldState") & "</option>")
			rsArea.movenext
		loop
		
		rsArea.close
		set rsArea = nothing
	%>
    <!-- #include virtual="Projects/ado_close.asp" -->
    </select></td></tr>
   
   	<tr><td>
   	County:&nbsp;</td><td><select name=cboCt id=cboCt disabled="disabled"><option value="0">Select county...</option></select>
    </td></tr></table>
    
    <!-- #include virtual="/Projects/declarations.asp" -->
    
    <br /><i>Select crop and livestock operations on farm:</i>
    <table id="tbl1" border="0"><tr>
    <%
	for i = 0 to ubound(oCrop)-1
		if oCrop(i).Used = "true" then
			if i mod 3 = 0 and i <> 0 then response.Write(vbcrlf & "</tr><tr>")
			response.Write(vbcrlf & "<td>" & oCrop(i).CName & ": </td><td><input type=checkbox name=chkComm value="& oCrop(i).Label & " /></td>")
		end if
	next
	
	%>
    </tr></table>
    <script type="text/javascript">resizetable('tbl1'); checkState();</script>

    </form>
    
	<p><input type="button" name="btnSubmit" value="Submit" onmouseup="javascript:fnValidation();" />
    </div>

    <p><div class="budget" id=inputform>
    	<center><h1>Input data</h1></center>
    	<p><center><i>Input parameters must first be selected</i></center></p>
        <input type="hidden" name="firstrun" id="firstrun" value="true" />
    </div>
    
    <script type="text/javascript">timeInput();</script>
    

</body>
</html>
