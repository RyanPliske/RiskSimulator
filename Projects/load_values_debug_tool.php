<?php
/****************************************************************************************************
Get Default Values
*****************************************************************************************************/

//Yields*****************
for($i=0;$i<=count($oCrop)-1;$i++)
{
	if ($oCrop[$i]->Used == "true")
	{
		echo "<h2>Yields:</h2>";
		$strSQL = "SELECT * FROM tblCountyYields WHERE FIPS='".iif( $oCrop[$i]->Label != 'dairy', $_POST['fips'], substr($_POST['fips'],0,2).'000')."' AND CommCode=".$oCrop[$i]->CommCode;
		echo $strSQL;
		$rsData = $db->Execute($strSQL);
		for($j=$yStart; $j<=$yEnd; $j++)
		{
			echo "<br/>".$j;
			if (!$rsData->EOF && !$rsData->BOF && $rsData->fields($j)!=NULL )
			{
				$oCrop[$i]->Yield[$j-$yStart] = $rsData->fields($j);
				echo "<br/>".$oCrop[$i]->Yield[$j-$yStart];
			}
		}
		$rsData->close();
	}
}

//Historical Prices************
for($i=0;$i<=count($oCrop)-1;$i++)
{
	if ($oCrop[$i]->Used == "true")
	{
		echo "<h2>Historical Prices:</h2>";
		$strSQL = "SELECT * FROM tblStatePrices WHERE StFips='".substr($_POST["fips"],0,2)."' AND CommCode=".$oCrop[$i]->CommCode;
		echo $strSQL;
		$rsData = $db->Execute($strSQL);
		for($j=-3+$yStart; $j<=-1+$yStart; $j++)
		{
			echo "<br/>".$j;
			if (!$rsData->EOF && !$rsData->BOF && $rsData->fields($j)!=NULL )
			{
				$oCrop[$i]->HPrice[$j-$yStart+3] = $rsData->fields($j);
				echo "<br/>".$oCrop[$i]->HPrice[$j-$yStart+3] ;
			}
		}
		$rsData->close();
	}
}

//Forecasted Prices************
for($i=0;$i<=count($oCrop)-1;$i++)
{
	if ($oCrop[$i]->Used == "true")
	{
		if ($oCrop[$i]->Label != "dairy")
		{
			$strSQL = "SELECT * FROM tblNationalPrices WHERE CommCode=".$oCrop[$i]->CommCode;
		}
		else
			$strSQL = "SELECT * FROM tblStatePrices WHERE StFips='".substr($_POST["fips"],0,2)."' AND CommCode=".$oCrop[$i]->CommCode;
		echo "<h2>Forecasted Prices</h2>";
		echo "<br/>".$strSQL;
		$rsData = $db->Execute($strSQL);
		//Add spaces for historical years, this does not carry through for results
		$oCrop[$i]->RedimPrice(count($oCrop[$i]->Price)+3);
		for($j=$yStart-3; $j<=$yEnd; $j++)
		{
			echo "<br/>".$j;
			if (!$rsData->EOF && !$rsData->BOF && $rsData->fields($j)!=NULL )
			{
				$oCrop[$i]->Price[$j-$yStart+3] = $rsData->fields($j);
				echo "<br/>".$oCrop[$i]->Price[$j-$yStart+3];
			}
		}
		$rsData->close();
	}
}

//Other Revenues************************
for($i=0;$i<=count($oCrop)-1;$i++)
{
	if ($oCrop[$i]->Used == "true")
	{
		echo "<h2>Other Revenues:</h2>";
		//If Crop is Dairy or cowCalf then get the Region
		if ($oCrop[$i]->CommCode == '40159999' || $oCrop[$i]->CommCode == '40999199')
		{
			//Get the Region
			$strSQL = "SELECT * FROM tblOtherRev AS a INNER JOIN tblRegions AS b ON b.Region = a.Region ";
			$strSQL .= "WHERE b.Fips='".$_POST['fips']."' ";
			$strSQL .= "AND a.CommCode=".$oCrop[$i]->CommCode;
			echo "<br/>".$strSQL;
			$rsData = $db->Execute($strSQL) or die('<br/><b><font color="red">Query Error: '.$db->ErrorMsg() );
			$Region = $rsData->fields('Region');
			echo "<br/>Region: ".$Region;
		}
		else
			//Else set Region to zero, there is no cost data, Therefore use the national Region
			$Region = 0;
		//Regional Other Revenue
		$strSQL = "SELECT * FROM tblOtherRev WHERE Region=".$Region." AND CommCode=".$oCrop[$i]->CommCode;
		echo "<br/>".$strSQL;
		$rsData = $db->Execute($strSQL) or die('<br/><b><font color="red">Query Error: '.$db->ErrorMsg() );
		for ($j=$yStart; $j<=$yEnd;$j++)
		{
			echo "<br/>".$j;
			if (!$rsData->EOF && !$rsData->BOF && $rsData->fields($j)!=NULL )
			{
				$oCrop[$i]->OthRev[$j-$yStart]=$rsData->fields($j);
				echo "<br/>".$oCrop[$i]->OthRev[$j-$yStart];
			}
			else
			{
				$oCrop[$i]->OthRev[$j-$yStart]=0;
				echo "<br/>".$oCrop[$i]->OthRev[$j-$yStart];
			}
		}
		$rsData->close();
	}
}

//Variable Costs*************************
for($i=0;$i<=count($oCrop)-1;$i++)
{
	if ($oCrop[$i]->Used == "true")
	{
		echo "<h2>Variable Costs:</h2>";
		//Get the Region
		$strSQL = "SELECT * FROM tblRegions AS a INNER JOIN tblRegionVarCosts AS b ON a.Region=b.Region ";
		$strSQL .= "WHERE a.Fips='".$_POST['fips']."' ";
		$strSQL .= "AND b.CommCode=".$oCrop[$i]->CommCode;
		echo "<br/>".$strSQL;
		$rsData = $db->Execute($strSQL) or die('<br/><b><font color="red">Query Error: '.$db->ErrorMsg() );

		if (!$rsData->FetchRow()) //If FetchRow doesn't return a row, then we know Region is zero
		{
			$Region = 0;
		}
		else
		{
			$Region = $rsData->fields('Region');
		}
		echo "<br/><font color='red'>Region: ".$Region."</font>";
		//Regional Costs
		$strSQL = "SELECT * FROM tblRegionVarCosts AS a ";
		$strSQL .="WHERE a.Region=".$Region." AND a.CommCode=".$oCrop[$i]->CommCode;
		echo "<br/>".$strSQL;
		$rsData = $db->Execute($strSQL) or die('<br/><b><font color="red">Query Error: '.$db->ErrorMsg() );
		//Regional Yields
		$strSQL = "SELECT tblRegions.Region, tblCountyYields.CommCode"; //These are the weighted regional yields
		for ($k=$yStart-12;$k<=$yStart-3;$k++)
		{
			$strSQL .= ", Sum(tblCountyYields.[".$k."]*tblCountyHarvested.[".$k."])/Sum(tblCountyHarvested.[".$k."]) AS ".$k." ";
		}
		$strSQL .= "FROM (tblRegions INNER JOIN tblCountyHarvested ON tblRegions.Fips = tblCountyHarvested.Fips) INNER JOIN tblCountyYields ON (tblCountyHarvested.Fips = tblCountyYields.Fips) AND ";
		$strSQL .= "(tblCountyHarvested.CommCode = tblCountyYields.CommCode) ";
		$strSQL .= "WHERE ((tblCountyYields.CommCode)=".$oCrop[$i]->CommCode.")".iif($Region!=0, " AND ((tblRegions.Region)=".$Region.")", "");
		$strSQL .= " GROUP BY tblRegions.Region, tblCountyYields.CommCode";
		echo "<br/>".$strSQL;
		$rsRgYield = $db->Execute($strSQL) or die('<br/><b><font color="red">Query Error: '.$db->ErrorMsg() );
		$AvgRgYield = 1;
		if (!$rsRgYield->EOF && !$rsData->BOF) //If not end of file, make sure there is a regional yield
		{
			for($k=$yStart-12; $k<=$yStart-3; $k++)
			{
				if (is_numeric($rsRgYield->fields($k)))
				{
					$AvgRgYield += $rsRgYield->fields($k);
					echo "<br/><font color='blue'>AvgRgYield: ".$AvgRgYield."</font>";
				}
			}
		}
		//County Yields
		$strSQL = "SELECT tblCountyYields.* FROM tblCountyYields WHERE Fips='".$_POST['fips']."' AND CommCode=".$oCrop[$i]->CommCode;
		$rsCoYield = $db->Execute($strSQL) or die('<br/><b><font color="red">Query Error: '.$db->ErrorMsg() );
		$AvgCoYield = 1;
		for($k=$yStart-12; $k<=$yStart-3; $k++)
		{
			if(!$rsCoYield->EOF && !$rsCoYield->BOF  && $rsCoYield->fields($k)!=NULL ) //Make sure all yields exist
			{
				if(is_numeric($rsCoYield->fields($k)))
				{
					$AvgCoYield = $avgCoYield + $rsCoYield->fields($k);
					echo "<br/><font color='green'>AvgCoYield: ".$AvgCoYield."</font>";
				}
			}
			else //If they don't, set county yield equal to regional yield
			{
				$AvgCoYield = $AvgRgYield;
				echo "<br/><font color='green'>AvgCoYield: ".$AvgCoYield."</font>";
				break;
			}
		}
		//Save the Variable Costs
		$n=1;
		do
		{
			//$oCrop[$i]->VarCostLbl($n);
			for($j=$yStart; $j<=$yEnd; $j++)
			{
				if (!$rsData->EOF && !$rsData->BOF && $rsData->fields($j)!=NULL )
				{
					$oCrop[$i]->VarCost[$j-$yStart][$n-1] = $rsData->fields($j)*(($AvgCoYield/$AvgRgYield-1)*.5+1);
					echo "<br/><font color = 'purple'>VarCost: ".$oCrop[$i]->VarCost[$j-$yStart][$n-1]."</font>";
					//$oCrop[$i]->VarCostLbl[$n];
					$oCrop[$i]->VarCostLbl[$n-1] = $rsData->fields('Label');
					echo "<br/>VarCostLbl: ".$oCrop[$i]->VarCostLbl[$n-1];
				}
			}
			$n=$n+1;
			//Move to the next Row
			$rsData->MoveNext();
		} while(!$rsData->EOF);
		if ($rsData->EOF && $rsData->BOF) //If there are no variable costs create blank category
		{
			//$oCrop[$i]->VarCost[$n];
			//$oCrop[$i]->VarCostLbl[$n];
			$oCrop[$i]->VarCostLbl[0] = $oCrop[$i]->CName." variable costs";
		}
		$rsData->Close();
		$rsCoYield->Close();
		$rsRgYield->Close();
	}
}
//Fixed Costs*************************
for($i=0;$i<=count($oCrop)-1;$i++)
{
	if ($oCrop[$i]->Used == "true")
	{
		echo "<h2>Fixed Costs:</h2>";
		//Get the Region
		$strSQL = "SELECT * FROM tblRegions AS a INNER JOIN tblRegionFixedCosts AS b ON a.Region=b.Region ";
		$strSQL .= "WHERE a.Fips='".$_POST['fips']."' ";
		$strSQL .= "AND b.CommCode=".$oCrop[$i]->CommCode;
		echo "<br/>".$strSQL;
		$rsData = $db->Execute($strSQL) or die('<br/><b><font color="red">Query Error: '.$db->ErrorMsg() );
		if (!$rsData->FetchRow()) //If FetchRow doesn't return a row, then we know Region is zero
		{
			$Region = 0;
		}
		else
		{
			$Region = $rsData->fields('Region');
		}
		echo "<br/><font color='red'>Region: ".$Region."</font>";
		//Regional Costs
		$strSQL = "SELECT * FROM tblRegionFixedCosts AS a ";
		$strSQL .="WHERE a.Region=".$Region." AND a.CommCode=".$oCrop[$i]->CommCode;
		echo "<br/>".$strSQL;
		$rsData = $db->Execute($strSQL) or die('<br/><b><font color="red">Query Error: '.$db->ErrorMsg() );
		//Regional Yields
		$strSQL = "SELECT tblRegions.Region, tblCountyYields.CommCode"; //These are the weighted regional yields
		for ($k=$yStart-12;$k<=$yStart-3;$k++)
		{
			$strSQL .= ", Sum(tblCountyYields.[".$k."]*tblCountyHarvested.[".$k."])/Sum(tblCountyHarvested.[".$k."]) AS ".$k." ";
		}
		$strSQL .= "FROM (tblRegions INNER JOIN tblCountyHarvested ON tblRegions.Fips = tblCountyHarvested.Fips) INNER JOIN tblCountyYields ON (tblCountyHarvested.Fips = tblCountyYields.Fips) AND ";
		$strSQL .= "(tblCountyHarvested.CommCode = tblCountyYields.CommCode) ";
		$strSQL .= "WHERE ((tblCountyYields.CommCode)=".$oCrop[$i]->CommCode.")".iif($Region!=0, " AND ((tblRegions.Region)=".$Region.")", "");
		$strSQL .= "GROUP BY tblRegions.Region, tblCountyYields.CommCode";
		echo "<br/>".$strSQL;
		$rsRgYield = $db->Execute($strSQL) or die('<br/><b><font color="red">Query Error: '.$db->ErrorMsg() );
		$AvgRgYield = 1;
		if (!$rsRgYield->EOF && !$rsData->BOF) //If not end of file, make sure there is a regional yield
		{
			for($k=$yStart-12; $k<=$yStart-3; $k++)
			{
				if (is_numeric($rsRgYield->fields($k)))
				{
					$AvgRgYield += $rsRgYield->fields($k);
					echo "<br/><font color='blue'>AvgRgYield: ".$AvgRgYield."</font>";
				}
			}
		}
		//County Yields
		$strSQL = "SELECT tblCountyYields.* FROM tblCountyYields WHERE Fips='".$_POST['fips']."' AND CommCode=".$oCrop[$i]->CommCode;
		$rsCoYield = $db->Execute($strSQL) or die('<br/><b><font color="red">Query Error: '.$db->ErrorMsg() );
		$AvgCoYield = 1;
		for($k=$yStart-12; $k<=$yStart-3; $k++)
		{
			if(!$rsCoYield->EOF && !$rsCoYield->BOF  && $rsCoYield->fields($k)!=NULL ) //Make sure all yields exist
			{
				if(is_numeric($rsCoYield->fields($k)))
				{
					$AvgCoYield += $rsCoYield->fields($k);
					echo "<br/><font color='green'>AvgCoYield: ".$AvgCoYield."</font>";
				}
			}
			else //If they don't, set county yield equal to regional yield
			{
				$AvgCoYield = $AvgRgYield;
				echo "<br/><font color='green'>AvgCoYield: ".$AvgCoYield."</font>";
				break;
			}
		}
		//Regional Costs
		$n=1;
		do
		{
			//$oCrop[$i]->VarCostLbl($n);
			for($j=$yStart; $j<=$yEnd; $j++)
			{
				if (!$rsData->EOF && !$rsData->BOF && $rsData->fields($j)!=NULL )
				{
					$oCrop[$i]->FixCost[$j-$yStart][$n-1] = $rsData->fields($j)*(($AvgCoYield/$AvgRgYield-1)*.5+1);
					echo "<br/><font color = 'purple'>VarCost: ".$oCrop[$i]->FixCost[$j-$yStart][$n-1]."</font>";
					//$oCrop[$i]->FixCostLbl[$n];
					$oCrop[$i]->FixCostLbl[$n-1] = $rsData->fields('Label');
					echo "<br/>VarCostLbl: ".$oCrop[$i]->FixCostLbl[$n-1];
				}
			}
			$n=$n+1;
			//Move to the next Row
			$rsData->MoveNext();
		} while(!$rsData->EOF);
		if ($rsData->EOF && $rsData->BOF) //If there are no variable costs create blank category
		{
			$oCrop[$i]->FixCost[$n];
			//$oCrop[$i]->FixCostLbl[$n];
			$oCrop[$i]->FixCostLbl[0] = $oCrop[$i]->CName." fixed costs";
		}
		$rsData->Close();
		$rsCoYield->Close();
		$rsRgYield->Close();
	}
}	
	
?>