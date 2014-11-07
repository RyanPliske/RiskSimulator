<?php
	include('ado_open.php');
	//public variables?
	$yStart = 2014;
	$yEnd = 2018;
	$str = "<tr class=year><td>&nbsp;</td>";
	for ($i=$yStart; $i<=$yEnd;$i++)
	{
		$str .= "<td align=right>".$i."</td>"; //Year Labels
	}
	$str .= "</tr>";
	include('declarations.php');
	include('load_values.php');
	
	//Save the user input

	$strSQL = "SELECT * FROM tblUsage";
	//$rsData = $db->Execute($strSQL) or die('<br/><font color="red">Query Error on: <b>'.__FILE__.' Line: '.__LINE__.' </b><br/>'.$db->ErrorMsg() );
	$rsData = mysqli_query($db, $strSQL);
	if(!$rsData){
			die("Query Failed: " . mysqli_connect_error());
		}
	//Get last row of tblUsage
	$numrows = 843 + $rsData->PO_RecordCount("tblUsage", $db);
	//Set timezone
	date_default_timezone_set('America/Chicago');
	//Format Time
	$date = date("n/d/Y g:i:s A");
	//Create SQL for Inserting values into tblUsage
	$strSQL = "INSERT INTO tblUsage (ID, FIPS, Time_Of_Visit, ";
	for ($i=0; $i<=count($oCrop)-1;$i++)
	{
		$strSQL .=$oCrop[$i]->Label;
		if ($i != count($oCrop)-1)
		{
			$strSQL .=",";
		}
	}
	$strSQL .= ") values (".$numrows.",'".$_POST['fips']."','".$date."',";
	for ($i=0; $i<=count($oCrop)-1;$i++)
	{
		if ($oCrop[$i]->Used=='true')
		{
			$strSQL .="1";
			if ($i != count($oCrop)-1)
			{
				$strSQL .=",";
			}
		}
		else
		{
			$strSQL .="0";
			if ($i != count($oCrop)-1)
			{
				$strSQL .=",";
			}
		}
	}
	$strSQL .= ")";

	//Execute SQL
	$rsData = $db->Execute($strSQL) or die('<br/><b><font color="red">Query Error: '.$db->ErrorMsg() );
	
	//record state name
	$strSQL = "SELECT fldState FROM tblStates WHERE ID='".substr($_POST['fips'],0,2)."';";
	$rsData = $db->Execute($strSQL) or die('<br/><b><font color="red">Query Error: '.$db->ErrorMsg() );
	$strState = $rsData->fields[0];
	
	//record county name
	$strSQL = "SELECT fldCounty FROM tblCounties WHERE ID='".$_POST['fips']."';";
	$rsData = $db->Execute($strSQL) or die('<br/><b><font color="red">Query Error: '.$db->ErrorMsg() );
	$strCounty = $rsData->fields[0];
?>

<center>
	<h1>Input data</h1>
	<?php echo $strCounty.iif($strState=="Louisiana" || $strState=="Alaska" || strpos($strCounty,"City") !== false || strpos($strCounty,"city") !== false, "", " County").", ".$strState."<br>&nbsp;";?>
</center>

<form name="frm2" id="frm2" action="results.php" method="post" >

<!--Save data via "hidden" fields submitted to results.php-->
<?php
	//Save the Crops
	for ($i=0; $i<=count($oCrop)-1; $i++)
	{
		echo "\r\n<input type=hidden name=".$oCrop[$i]->Label." value=".$oCrop[$i]->Used." />";
	}
	//Save the years
	echo "\r\n<input type=hidden name=yStart value=".$yStart.">";
	echo "\r\n<input type=hidden name=yEnd value=".$yEnd.">";
	//Save state, county and fips
	echo "\r\n<input type=hidden name=State value=".$strState.">";
	echo "\r\n<input type=hidden name=County value=".$strCounty.">";
	echo "\r\n<input type=hidden name=fips value=".$_POST['fips'].">";
?>
<!--Function to copy over acres-->
<script type="text/javascript">
window.CopyScale = function () {

	var a = new Array();
	<?php
		$j=0;
		for($i=0;$i<=count($oCrop)-1;$i++)
		{
			if ($oCrop[$i]->Used == "true")
			{
				echo "\r\n a[".$j."] = '".$oCrop[$i]->Label."';";
				$j++;
			}
		}
		echo "\r\nvar yStart=".$yStart."; yEnd=".$yEnd.";";
	?>
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
<tr><td colspan="6" class="labels">Scale of operation |<input type="button" value="Copy <?php echo $yStart; ?>" onclick="javascript:CopyScale();" /></td></tr>
<?php
	echo $str;
	for($i=0;$i<=count($oCrop)-1;$i++)
	{
		if ($oCrop[$i]->Used == "true")
		{
			echo"<tr><td>".$oCrop[$i]->CName." (".$oCrop[$i]->NoUnitsUnit."s)&nbsp;</td>";
			for($j=$yStart;$j<=$yEnd;$j++)
			{
				echo "\r\n<td><input class=text type=text id=".$oCrop[$i]->Label."_NoUnits".$j." name=".$oCrop[$i]->Label."_NoUnits".$j;
				echo " onfocus='javascript:onEnter(this);' onblur='javascript:OnLeave(this.id,0);' /></td>";
			}
			echo "</tr>";
		}
	}
?>
<tr><td colspan="6">&nbsp;</td></tr>
<tr><td colspan="6" class="labels">Yields</td></tr>
<?php
	echo $str;
	for($i=0;$i<=count($oCrop)-1;$i++)
	{
		if ($oCrop[$i]->Used == "true")
		{
			echo "<tr><td>".$oCrop[$i]->CName." (".$oCrop[$i]->YieldUnit.")&nbsp;".iif($oCrop[$i]->Label==="cowcalf","<br>Calving rate","")."</td>";
			for($j=$yStart;$j<=$yEnd;$j++)
			{
				//If this is cow/calf, we need yield per calf and calving rate
				if ($oCrop[$i]->Label=="cowcalf")
				{
					//Shown yield input
					echo "\r\n<td><input class=text type=text id=".$oCrop[$i]->Label."_Yield_Raw".$j." name=".$oCrop[$i]->Label."_Yield_Raw".$j;
					echo " value='600.0' onclick='javascript:this.select();' onblur='javascript:OnLeave(this.id,1); CalcBeefYield(".$j."); CalcBeefPrice(); OnLeave(this.id,2); CalcPrices();' />";
					//Calving Rate Input
					echo "\r\n<br><input class=text type=text id=".$oCrop[$i]->Label."_CalvingRate".$j." name=".$oCrop[$i]->Label."_CalvingRate".$j;
					echo " value='.90' onclick='javascript:this.select();' onblur='javascript:OnLeave(this.id,2); CalcBeefYield(".$j."); OnLeave(this.id,2);' />";
					//Hidden actual input
					echo "\r\n<br><input class=text type=hidden id=".$oCrop[$i]->Label."_Yield".$j." name=".$oCrop[$i]->Label."_Yield".$j;
					echo " value='540' /></td>";
				}
				//Else if not cow/calf
				else
				{
					if(isset($oCrop[$i]->Yield[$j-$yStart]))
					{
						echo "\r\n<td><input class=text type=text id=".$oCrop[$i]->Label."_Yield".$j." name=".$oCrop[$i]->Label."_Yield".$j;
						echo " value='".FormatNum($oCrop[$i]->Yield[$j-$yStart],1)."' onfocus='javascript:onEnter(this);' onblur='javascript:OnLeave(this.id,1);' ></td>";
					}
					else
					{
						echo "\r\n<td><input class=text type=text id=".$oCrop[$i]->Label."_Yield".$j." name=".$oCrop[$i]->Label."_Yield".$j;
						echo " value='' onfocus='javascript:onEnter(this);' onblur='javascript:OnLeave(this.id,1);' ></td>";
					}
				}
			}
			echo "</tr>";
		}
	}
?>

<tr><td colspan="6">&nbsp;</td></tr>
<tr><td colspan="6" class="labels">Historical prices</td></tr>
	<tr class="year"><td>&nbsp;</td><td align="right"><?php echo $yStart-3; ?></td><td align="right"><?php echo $yStart-2; ?></td><td align="right"><?php echo $yStart-1; ?></td></tr>
	
<?php
	for($i=0;$i<=count($oCrop)-1;$i++)
	{
		if ($oCrop[$i]->Used == "true")
		{
			echo "<tr><td>".$oCrop[$i]->CName." (".$oCrop[$i]->PriceUnit.")&nbsp;</td>";
			for ($j=-3+$yStart; $j<=-1+$yStart; $j++)
			{
				if (isset($oCrop[$i]->HPrice[$j-$yStart+3] ))
					echo "\r\n<td><input class=text type=text id=".$oCrop[$i]->Label."_Price".$j." name=".$oCrop[$i]->Label."_Price".$j." value='".FormatNum($oCrop[$i]->HPrice[$j-$yStart+3],3);
				else
					echo "\r\n<td><input class=text type=text id=".$oCrop[$i]->Label."_Price".$j." name=".$oCrop[$i]->Label."_Price".$j." value=''";
					
				echo  "' onblur='javascript:OnLeave(this.id,3); CalcPrices();' onfocus='javascript:onEnter(this);' ></td>";
			}
			echo "</tr>";
		}
	}
?>

<!--Function to calculate future farm prices-->
<script type="text/javascript">
window.CalcPrices = function() {
	var a = new Array();
	<?php
		$j=0;
		for($i=0;$i<=count($oCrop)-1;$i++)
		{
			if ($oCrop[$i]->Used == "true")
			{
				echo"\r\n a[".$j."] = '".$oCrop[$i]->Label."';";
				$j++;
			}
		}
		echo "\r\n var yStart=".$yStart."; yEnd=".$yEnd.";";
	?>
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

<!--save national prices-->
<?php
	for($i=0;$i<=count($oCrop)-1;$i++)
	{
		if ($oCrop[$i]->Used == "true")
		{
			for($j=$yStart-3;$j<=$yEnd;$j++)
			{
				echo "\r\n <input class=text type=hidden name='".$oCrop[$i]->Label."_Price".$j."_US' id='".$oCrop[$i]->Label."_Price".$j."_US' value='".$oCrop[$i]->Price[$j-$yStart+3]."' />";
			}
		}
	}
	//Save historical local beef prices
	$intBeef = LookupComm("cowcalf", $oCrop);

	if ($oCrop[$intBeef]->Used == "true")
	{	
		$strSQL = "SELECT * FROM tblStatePrices WHERE StFips='".substr($_POST['fips'],0,2)."' AND CommCode=".$oCrop[$intBeef]->CommCode.";";
		$rsData = $db->Execute($strSQL) or die('<br/><font color="red">Query Error on: <b>'.__FILE__.' Line: '.__LINE__.' </b><br/>'.$db->ErrorMsg() );
		for ($j=$yStart-3; $j<=$yStart-1;$j++)
		{
			echo "\r\n"."<input type=hidden id=".$oCrop[$intBeef]->Label."_Price".$j."_St name=".$oCrop[$intBeef]->Label."_Price".$j."_St value='".$rsData->fields($j)."' />";
		}
		$rsData->Close();
	}
?>
<tr><td colspan="6">&nbsp;</td></tr>
<tr><td colspan="6" class="labels">Forecasted Prices |<a href="javascript:fnHelp('Draws');">More Info</a></td></tr>
<?php
	echo $str;
	for($i=0;$i<=count($oCrop)-1;$i++)
	{
		if ($oCrop[$i]->Used == "true")
		{
			echo "<tr><td>";
			echo $oCrop[$i]->CName." (".$oCrop[$i]->PriceUnit.")";
			echo "</a>";
			echo "&nbsp;</td>";
			//Originally defaults to Average
			for($j=$yStart-$yStart;$j<=$yEnd-$yStart;$j++)
			{
				if(isset($oCrop[$i]->AvgDraw[0]))
				{
					echo "\r\n<td><input class=text type=text id=".$oCrop[$i]->Label."_Price".($yStart+$j)." name=".$oCrop[$i]->Label."_Price".($yStart+$j);
					echo " value='".FormatNum($oCrop[$i]->AvgDraw[$j],4)."' onfocus='javascript:onEnter(this);' onblur='javascript:OnLeave(this.id,3);' ></td>";
				}
				else
				{
					echo "\r\n<td><input class=text type=text id=".$oCrop[$i]->Label."_Price".($yStart+$j)." name=".$oCrop[$i]->Label."_Price".($yStart+$j);
					echo " value='' onfocus='javascript:onEnter(this);' onblur='javascript:OnLeave(this.id,3);' ></td>";				
				}
			}
		}
	}
?>

<script type="text/javascript">CalcPrices();</script>

<tr><td colspan="6">&nbsp;</td></tr>
<tr><td colspan="6" class="labels">Other revenues |<a href="javascript:fnHelp('OtherRev');">More Info</a></td></tr>
<?php
	echo $str;
	for($i=0;$i<=count($oCrop)-1;$i++)
	{
		if ($oCrop[$i]->Used == "true")
		{
			echo "<tr><td>".$oCrop[$i]->CName." ($ per ".$oCrop[$i]->NoUnitsUnit.")&nbsp;</td>";
			for($j=$yStart;$j<=$yEnd;$j++)
			{
				echo "\r\n<td><input class=text type=text id=".$oCrop[$i]->Label."_OthRev".$j." name=".$oCrop[$i]->Label."_OthRev".$j;
				echo " onfocus='javascript:onEnter(this);' onblur='javascript:OnLeave(this.id,2);' value='".FormatNum($oCrop[$i]->OthRev[$j-$yStart],2)."' /></td>";
			}
			echo "</tr>";
		}
	}
?>
<tr><td colspan="6">&nbsp;</td></tr>
<tr><td colspan="6" class="labels">Variable costs |<a href="javascript:fnHelp('VarCost');">More Info</a></td></tr>
<?php
	echo $str;
	for($i=0;$i<=count($oCrop)-1;$i++)
	{
		if ($oCrop[$i]->Used == "true")
		{
			//Label Column
			echo "\r\n<tr><td>";
			if( empty($oCrop[$i]->OutUnit) )
			{
				$unitLabel =  $oCrop[$i]->NoUnitsUnit;
			}
			else
				$unitLabel =  $oCrop[$i]->OutUnit;
			echo iif(count($oCrop[$i]->VarCostLbl)>0,"<a href=javascript:ShowHideCost('".$oCrop[$i]->Label."_SubVarCost')>","");//don't hyperlink sub costs if no costs
			echo $oCrop[$i]->CName." ($ per ".$unitLabel;
			echo iif(count($oCrop[$i]->VarCostLbl)>0,"</a>",""); //don't hyperlink sub costs if no costs
			echo ")&nbsp;</td>";
			for ($j=$yStart;$j<=$yEnd;$j++)
			{
				echo "\r\n<td><input class=text type=text id=".$oCrop[$i]->Label."_VarCost".$j." name=".$oCrop[$i]->Label."_VarCost".$j;
				echo " onfocus='javascript:onEnter(this);' onblur='javascript:TotCost(this.id);' /></td>";
			}
			echo "</tr><tbody id=".$oCrop[$i]->Label."_SubVarCost>";

			if(count($oCrop[$i]->VarCostLbl)>0)
			{
				//Create input boxes
				#Upper Bound of 2D Array, *Count($,1) counts 2nd Dimension of 2d Array for PHP
				for ($k=0; $k<=count($oCrop[$i]->VarCost[0],1)-1;$k++)
				{
					echo "\r\n<tr><td class=subvar>".$oCrop[$i]->VarCostLbl[$k]."</td>";
					for ($j=$yStart; $j<=$yEnd; $j++)
					{
						//If crop is hay or peanuts
						if ($oCrop[$i]->Label == 'hay' || $oCrop[$i]->Label == 'peanuts')
						{
							echo "\r\n <td><input class=subtext type=text id=".$oCrop[$i]->Label."_VarCost".$j."_".($k+1)." name=".$oCrop[$i]->Label."_VarCost".$j."_".($k+1)." value=''".
							" onfocus='javascript:onEnter(this);' onblur='javascript:SubCost(this.id);' /></td>";
						}
						else
						{
							echo "\r\n <td><input class=subtext type=text id=".$oCrop[$i]->Label."_VarCost".$j."_".($k+1)." name=".$oCrop[$i]->Label."_VarCost".$j."_".($k+1)." value='".
							FormatNum($oCrop[$i]->VarCost[$j-$yStart][$k],2)."' onfocus='javascript:onEnter(this);' onblur='javascript:SubCost(this.id);' /></td>";
						}
					}
					echo "\r\n</tr>";
				}
				//Count for number of variable cost subcategories
				echo "\r\n<input type=hidden id=".$oCrop[$i]->Label."_VarCostNum name=".$oCrop[$i]->Label."_VarCostNum value=".($k+1)."'>";
				echo "</tbody>";
				
				//Calc total variable costs
				echo "\r\n<script type='text/javascript'>ResizeFirstCol('".$oCrop[$i]->Label."_SubVarCost');";
				for($j=$yStart;$j<=$yEnd;$j++)
				{
					echo "SubCost('".$oCrop[$i]->Label."_VarCost".$j."_1');";
				}
				echo "</script>";
			}
		}
	}
?>
<tr><td colspan="6">&nbsp;</td></tr>
<tr><td colspan="6" class="labels">Fixed costs |<a href="javascript:fnHelp('FixCost');">More Info</a></td></tr>
<?php
	echo $str;
	for($i=0;$i<=count($oCrop)-1;$i++)
	{
		if ($oCrop[$i]->Used == "true")
		{
			//Label Column
			echo "\r\n<tr><td>";
			if( empty($oCrop[$i]->OutUnit) )
			{
				$unitLabel =  $oCrop[$i]->NoUnitsUnit;
			}
			else
				$unitLabel =  $oCrop[$i]->OutUnit;
			echo iif(count($oCrop[$i]->FixCostLbl)>0,"<a href=javascript:ShowHideCost('".$oCrop[$i]->Label."_SubFixCost')>","");//don't hyperlink sub costs if no costs
			echo $oCrop[$i]->CName." ($ per ".$unitLabel;
			echo iif(count($oCrop[$i]->FixCostLbl)>0,"</a>",""); //don't hyperlink sub costs if no costs
			echo ")&nbsp;</td>";
			for ($j=$yStart;$j<=$yEnd;$j++)
			{
				echo "\r\n<td><input class=text type=text id=".$oCrop[$i]->Label."_FixCost".$j." name=".$oCrop[$i]->Label."_FixCost".$j;
				echo " onfocus='javascript:onEnter(this);' onblur='javascript:TotCost(this.id);' /></td>";
			}
			echo "</tr><tbody id=".$oCrop[$i]->Label."_SubFixCost>";

			if(count($oCrop[$i]->FixCostLbl)>0)
			{
				//Create input boxes
				#Upper Bound of 2D Array, *Count($,1) counts 2nd Dimension of 2d Array for PHP
				for ($k=0; $k<= count($oCrop[$i]->FixCost[0],1)-1;$k++)
				{
					echo "\r\n<tr><td class=subvar>".$oCrop[$i]->FixCostLbl[$k]."</td>";
					for ($j=$yStart; $j<=$yEnd; $j++)
					{
						if ($oCrop[$i]->Label == 'hay' || $oCrop[$i]->Label == 'sunflowerseed')
						{
							echo "\r\n <td><input class=subtext type=text id=".$oCrop[$i]->Label."_FixCost".$j."_".($k+1)." name=".$oCrop[$i]->Label."_FixCost".$j."_".($k+1)." value=''".
							" onfocus='javascript:onEnter(this);' onblur='javascript:SubCost(this.id);' /></td>";
						}
						else
						{
							echo "\r\n <td><input class=subtext type=text id=".$oCrop[$i]->Label."_FixCost".$j."_".($k+1)." name=".$oCrop[$i]->Label."_FixCost".$j."_".($k+1)." value='".
							FormatNum($oCrop[$i]->FixCost[$j-$yStart][$k],2)."' onfocus='javascript:onEnter(this);' onblur='javascript:SubCost(this.id);' /></td>";
						}
					}
					echo "\r\n</tr>";
				}
				echo "</tbody>";
				
				//Calc total fixed costs
				echo "\r\n<script type='text/javascript'>ResizeFirstCol('".$oCrop[$i]->Label."_SubFixCost');";
				for($j=$yStart;$j<=$yEnd;$j++)
				{
					echo "SubCost('".$oCrop[$i]->Label."_FixCost".$j."_1');";
				}
				echo "</script>";
			}
		}
	}
?>
</table>

<p><input type="button" onclick="javascript:Validate(); " value="Submit" />
<!--<input type="hidden" name="inpfrm" id="inpfrm" />-->
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
		//Show loading .gif
		showLoading();
		
		//this sets HTML to actual value
		var elems = document.getElementById('inputform').getElementsByTagName("input");
		for(var i = 0; i < elems.length; i++) {
			// set attribute to property value
			elems[i].setAttribute("value", elems[i].value);
		}

//		document.getElementById('inpfrm').value = document.getElementById('inputform').innerHTML;
		check=0; //this forces input to be recalled in firefox;
		document.forms['frm2'].submit();
	}
}

</script>
<script type="text/javascript">
	
window.BeefWedge = function (wt) {

	var Price = new Array();
	var lWeight = new Array();
	var uWeight = new Array();
	wt = wt.toString().replace(',','')*1;
	
<?php
	//Include the beef wedges if cow/calf is chosen
	//if ($oCrop[LookupComm("cowcalf",$oCrop)]->Used == 'true')
	//{
		$j=0;
		$strSQL = "SELECT * FROM tblCalfWedges;";
		$rsData = $db->Execute($strSQL) or die('<br/><font color="red">Query Error on: <b>'.__FILE__.' Line: '.__LINE__.' </b><br/>'.$db->ErrorMsg() );;
		do {
			echo "\r\n  Price[".$j."] = ".$rsData->fields('Price')."; lWeight[".$j."]= ".$rsData->fields('lWeight')."; uWeight[".$j."]= ".$rsData->fields('uWeight').";";
			$j++;
			$rsData->MoveNext();
		} while (!$rsData->EOF);
		$rsData->close();
	//}

?>
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
</script>
<script type="text/javascript">


window.CalcBeefYield = function (yr) {
	document.getElementById('cowcalf_Yield'+yr).value = document.getElementById('cowcalf_Yield_Raw'+yr).value.replace(',','')*document.getElementById('cowcalf_CalvingRate'+yr).value.replace(',','');
}
</script>
<script type="text/javascript">
window.CalcBeefPrice = function () {

	//Get average weight over projection period
	var cwedge=0; var cweight=0;
	for (var i=<?php echo $yStart; ?>; i<=<?php echo $yEnd; ?>; i++)
		cweight += document.getElementById('cowcalf_Yield_Raw'+i).value.replace(',','')*1;
	cweight /= (<?php echo $yEnd; ?>-<?php echo $yStart; ?>+1);
	cwedge = BeefWedge(cweight)*1;
	
	//change historical prices
	for (i=<?php echo $yStart-3; ?>; i<=<?php echo $yStart-1; ?>; i++) {
		document.getElementById('cowcalf_Price'+i).value = document.getElementById('cowcalf_Price'+i+'_St').value.replace(',','')*1 + cwedge;
		OnLeave('cowcalf_Price'+i,3);
	}
	CalcPrices();
}


</script>
<?php
	include('ado_close.php');
?>