<?php 
	//Save Forms to Session Variable
	if (!session_start())
	{
		session_start();
	}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd"> 
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel="stylesheet" type="text/css" href="projects.css" />
    <script type="text/javascript" src="budget.js"></script>
    
    <title>FAPRI - Food and Agricultural Policy Research Institute</title>
    
    <script language="JavaScript1.2" src="../coolmenus4.js">
        /*****************************************************************************
        Copyright (c) 2001 Thomas Brattli (webmaster@dhtmlcentral.com)
        
        DHTML coolMenus - Get it at coolmenus.dhtmlcentral.com
        Version 4.0_beta
        This script can be used freely as long as all copyright messages are
        intact.
        
        Extra info - Coolmenus reference/help - Extra links to help files **** 
        CSS help: http://coolmenus.dhtmlcentral.com/projects/coolmenus/reference.asp?m=37
        General: http://coolmenus.dhtmlcentral.com/reference.asp?m=35
        Menu properties: http://coolmenus.dhtmlcentral.com/properties.asp?m=47
        Level properties: http://coolmenus.dhtmlcentral.com/properties.asp?m=48
        Background bar properties: http://coolmenus.dhtmlcentral.com/properties.asp?m=49
        Item properties: http://coolmenus.dhtmlcentral.com/properties.asp?m=50
        ******************************************************************************/
    </script>

    <script language="JavaScript" src="../get_vars.js">
    </script>
    
</head>

<body>

	<center>
	<?php
		// include '../header.php';
	?>
	<p>
    <table width=800 border=0 cellspacing=0 cellpadding=0>
	<tr>
        <td width=8 valign="top" align="left"><img src="../images/spacers_curves/left_top_curve.gif"></td>
        <td width=784 valign="top">&nbsp;</td>
        <td width=8 valign="top" align="right"><img src="../images/spacers_curves/right_top_curve.gif"></td>
    <tr><td colspan="3">

	<center>
	<div class="budget">
    <center><h1>Input parameters</h1></center>
    <center><noscript><span style="color:red; font-weight:bolder;">You must enable Javascript!</span></noscript></center>
	
    <form name="frm1">
    <br /><i>Select your location:</i>
    <table><tr>
    <td>State:&nbsp;</td><td><select name="cboSt" onChange="javascript:ajaxFunction();">
    <option value="0">Select state...</option>
    
	<?php
	include('ado_open.php');
	//Set Query
	$sql= 'SELECT * FROM tblStates';
	//Execute Query, set Record Set equal to Execution so that you can find the End Of File later
	$rs = $db->Execute($sql) or die('<br/><b><font color="red">Query to MS Access Failed: '.$db->ErrorMsg() );
	do
	{
		echo "<option value='".$rs->fields[0]."'>".$rs->fields[1]."</option>\n\t";
		$rs->MoveNext();
	} while(!$rs->EOF); 
	//Close connection
	$rs->Close();
	include('ado_close.php');
	?>
    </select></td></tr>
   	<tr><td>
   	County:&nbsp;</td><td><select name=cboCt id=cboCt disabled="disabled"><option value="0">Select county...</option>

    <</select></td></tr></table>

    <br /><i>Select crop and livestock operations on farm:</i>
    <table id="tbl1" border="0"><tr>
    <?php
	require 'declarations.php' ;
	//Display Checkbox buttons for the amount of crops in the Database
	for ($i=0; $i<=count($oCrop)-1; $i++)
	{
	  if ($i % 3 == 0 && $i != 0)
	  {
		echo "\r\n</tr><tr>";
	  }
	  else {}
	echo "\r\n<td>".$oCrop[$i]->CName.": </td><td><input type=checkbox name=chkComm value=".$oCrop[$i]->Label." /></td>"; 
	}
	?>
    </tr></table>
	</form>
	<p><input type="button" name="btnSubmit" value="Submit" onMouseUp="javascript:fnValidation();" />
    </div>
    </center>

	<center>
    <p><div class="budget" id=inputform>
    	<center><h1>Input data</h1></center>
    	<p><center><i>Input parameters must first be selected</i></center></p>
        <input type="hidden" name="firstrun" id="firstrun" value="true" />
    </div>
	<br/>
	<div class="budget" id='loadingmsg' style='display: none;'>
	<center><i><b>Loading Results, please wait...</b></i><br/>
	<img src='help/ajax-loader.gif' alt='Searching'/></center>
	</div>

	</center>
    
   
    </td>
    <tr>
        <td valign="bottom" align="left"><img src="../images/spacers_curves/left_bottom_curve.gif"></td>
        <td>&nbsp;</td>
        <td valign="bottom" align="right"><img src="../images/spacers_curves/right_bottom_curve.gif"></td>
	</tr>
	</table>
    <p>
	<?php
		include '../footer.php';
	?>
	</center>

</body>
</html>
