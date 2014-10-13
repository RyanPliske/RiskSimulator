<?php
/*************************************************************************************
** File: results.php
*
* User Input and data from database are displayed here
* JavaScript functions to hide certain pieces of this page 
* in order to display what the user desires to see by choosing 
* from a drop down list
*
*************************************************************************************/
	if (!session_start())
	{
		session_start();
	}
	//Get State, County and fips
	$strCounty = $_POST['County'];
	$strState = $_POST['State'];
	$fips = $_POST['fips'];
	//Get Years
	$yStart = $_POST['yStart'];
	$yEnd = $_POST['yEnd'];

	//If years (aka all post variables) are empty, send user back to input page
	if($yStart=='')
	{
		header( 'Location: budget.php');
	}
	//Define the Years
	$str = '<tr><td>&nbsp;</td>';
	for ($i=$yStart; $i<=$yEnd; $i++)
	{
		$str .= '<td align=right><i>'.$i.'</i></td>'; //Year Labels
	}
	$str .= '</tr>';
	//Tabs
	$tab1 = "&nbsp&nbsp;";
	$tab2 = $tab1.$tab1;
	$tab3 = $tab1.$tab2;
	//Include Database files
	include('ado_open.php');
	include('declarations.php');
	include('load_values.php');

	//Place keys of $_POST array into an Array
	$keysOfArray = array_keys($_POST);
	//Place values into Sessions
	$_SESSION['forms'] = '';
	for ($i=0; $i<=count($_POST)-1; $i++)
	{
		$_SESSION['forms'] = $_SESSION['forms']."\r\n".'origVars.name['.$i.']="'.$keysOfArray[$i].'"; origVars.value['.$i.']="'.$_POST[$keysOfArray[$i]].'";';
	}
	//See which crops are used -> Load them into $oCrop object
	for ($i=0; $i<=count($oCrop)-1;$i++)
	{
		if ($_POST[$oCrop[$i]->Label] == 'true')
		{
			$oCrop[$i]->Used = 'true';
		}
		else
			$oCrop[$i]->Used = 'false';
	}
	//Load the rest of the Variables into $oCrop object
	for ($i=0; $i<=count($oCrop)-1; $i++)
	{
		if ($oCrop[$i]->Used == 'true')
		{
			for ($j=0; $j<= $yEnd-$yStart; $j++)
			{
				//str_replace Parses the  commas out
				$oCrop[$i]->NoUnits[$j] = floatval(str_replace(',','',$_POST[$oCrop[$i]->Label."_NoUnits".($j+$yStart)])); //Scale of Operation

				$oCrop[$i]->Price[$j] = floatval(str_replace(',','',$_POST[$oCrop[$i]->Label."_Price".($j+$yStart)])); //Prices


				$oCrop[$i]->Yield[$j] = floatval(str_replace(',','',$_POST[$oCrop[$i]->Label."_Yield".($j+$yStart)])); //Yields

				$oCrop[$i]->OthRev[$j] = floatval(str_replace(',','',$_POST[$oCrop[$i]->Label."_OthRev".($j+$yStart)])); //Other Revenue
				
				for ($k=0; $k<=count($oCrop[$i]->VarCost[0],1)-1; $k++) //Variable Costs
				{
					$oCrop[$i]->VarCost[$j][$k] = floatval(str_replace(',','',$_POST[$oCrop[$i]->Label."_VarCost".($j+$yStart)."_".($k+1)])); 
				}

				for ($k=0; $k<=count($oCrop[$i]->FixCost[0],1)-1; $k++) //Fix Costs
				{
					$oCrop[$i]->FixCost[$j][$k] = floatval(str_replace(',','',$_POST[$oCrop[$i]->Label."_FixCost".($j+$yStart)."_".($k+1)]));
				}
				
				if ($oCrop[$i]->Label=='dairy')
				{
					$oCrop[$i]->CostUnitAdj[$j] = $oCrop[$i]->Yield[$j]/100*$oCrop[$i]->NoUnits[$j]; //Cost Unit Adjuster for Dairy
				}
				
				if (isset($_POST[$oCrop[$i]->Label."_Price".($yStart+$j)]))
					$oCrop[$i]->UserDraws[$j] = floatval(str_replace(',','',$_POST[$oCrop[$i]->Label."_Price".($yStart+$j)])); //Stochastic Draws
			}
			//Calculate Results
			$oCrop[$i]->Calc();
		}
	}
//Calculate Draws
include('load_draws.php');	
?>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel="stylesheet" type="text/css" href="projects.css" />
    <script type="text/javascript" src="results.js"></script>
    <script language="javascript" type="text/javascript" src="budget.js"></script>
    
    <title>FAPRI - Food and Agricultural Policy Research Institute</title>
    
    <script language="JavaScript1.2" src="/coolmenus4.js">
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

    <script language="JavaScript" src="/get_vars.js">
    </script>
    
</head>

<body>

	<center>
	<?php include('../header.php'); ?>
	<p>
    <table width=800 border=0 cellspacing=0 cellpadding=0>
	<tr>
        <td width=8 valign="top" align="left"><img src="/images/spacers_curves/left_top_curve.gif"></td>
        <td width=784 valign="top">&nbsp;</td>
        <td width=8 valign="top" align="right"><img src="/images/spacers_curves/right_top_curve.gif"></td>
	</tr>
    <tr>
	<td colspan="3">
	<input onClick="javascript:window.history.go(-1);" type="button" value="Return to input" class="backbutton">
    <center>
	<div id="header">&nbsp;<p><h1>Results</h1>
	<?php echo $strCounty.iif($strState=="Louisiana" || $strState=="Alaska" || strpos($strCounty,"City") !== false || strpos($strCounty,"city") !== false, "", " County").", ".$strState; ?>
	</div>
    <form id="frm2" name="frm2" class="choices">
	<select name="cboBudget" onChange="javascript:BudgetType();">
    	<optgroup>
        	<option value="output_budget_total">Whole farm returns: detail</option>
            <option value="output_type_total">Whole farm returns: summary</option>
            <option value="output_commodity_total">Returns by commodity: total</option>
            <option value="output_commodity_unit">Returns by commodity: per unit</option>
			<option value="output_draw">Revenues calc by Stochastic Draws</option>
			<option value="output_latin">Random Latin HyperCube</option>
			<option value="output_eigen">Calculated Eigenvalues</option>
        </optgroup>
    </select>
	</form>
    <a href="javascript:printSpecial(document.forms.frm2.cboBudget.value,document.getElementById('header').innerHTML);">Print preview</a> || <a href="javascript:fnHelps();">Save to File</a><br/>
	</center>
	&nbsp;<br>
   
    <!-- Output types -->
    <div id="output_budget_total"><?php require('output_budget_total.php'); ?></div>
	<div id="output_type_total"><?php require('output_type_total.php'); ?></div>
    <div id="output_commodity_total"><?php require('output_commodity_total.php'); ?></div>
    <div id="output_commodity_unit"><?php require('output_commodity_unit.php'); ?></div>
    <div id="output_draw"><?php require('output_draws.php'); ?></div>
	<div id="output_latin"><?php require('output_latin.php'); ?></div>
	<div id="output_eigen"><?php include('output_eigen.php'); ?></div>
    </td>
    <tr>
        <td valign="bottom" align="left"><img src="/images/spacers_curves/left_bottom_curve.gif"></td>
        <td>&nbsp;</td>
        <td valign="bottom" align="right"><img src="/images/spacers_curves/right_bottom_curve.gif"></td>
	</tr>
	</table>
    <p>
	<?php include('../footer.php'); ?>
    <script type="text/javascript">BudgetType(); HideCosts_r1(); HideCosts_r2(); HideCosts_r3();</script>
	<!--  <script type="text/javascript">ResizeAllTables(); BudgetType();</script> -->
	</center>

</body>
</html>
<?php include('ado_close.php'); ?>
