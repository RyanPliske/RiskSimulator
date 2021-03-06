<?php

/*
	Load Default Values: 
	File name: load_values.php
	Pulls all the data from DB and saves them into variables
	No output is done here
*/

//Yields*********************************************************************************************************************
for($i=0;$i<=count($oCrop)-1;$i++)
{
	if ($oCrop[$i]->Used == "true")
	{
		$strSQL = "SELECT * FROM tblCountyYields WHERE FIPS='".iif( $oCrop[$i]->Label != 'dairy', $_POST['fips'], substr($_POST['fips'],0,2).'000')."' AND CommCode=".$oCrop[$i]->CommCode;
		$rsData = $db->Execute($strSQL) or die('<br/><font color="red">Query Error on: <b>'.__FILE__.' Line: '.__LINE__.' </b><br/>'.$db->ErrorMsg() );;
		for($j=$yStart; $j<=$yEnd; $j++)
		{
			if (!$rsData->EOF && $rsData->fields($j)!=NULL )
			{
				$oCrop[$i]->Yield[$j-$yStart] = $rsData->fields($j);
			}
		}
		$rsData->close();
	}
}

//Historical Prices********************************************************************************************************************
for($i=0;$i<=count($oCrop)-1;$i++)
{
	if ($oCrop[$i]->Used == "true")
	{
		$strSQL = "SELECT * FROM tblStatePrices WHERE StFips='".substr($_POST["fips"],0,2)."' AND CommCode=".$oCrop[$i]->CommCode;
		$rsData = $db->Execute($strSQL) or die('<br/><font color="red">Query Error on: <b>'.__FILE__.' Line: '.__LINE__.' </b><br/>'.$db->ErrorMsg() );;
		for($j=-3+$yStart; $j<=-1+$yStart; $j++)
		{
			if (!$rsData->EOF && $rsData->fields($j)!=NULL )
			{
				$oCrop[$i]->HPrice[$j-$yStart+3] = $rsData->fields($j);
			}
		}
		$rsData->close();
	}
}

//Forecasted Prices********************************************************************************************************************
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
		$rsData = $db->Execute($strSQL) or die('<br/><font color="red">Query Error on: <b>'.__FILE__.' Line: '.__LINE__.' </b><br/>'.$db->ErrorMsg() );


		for($j=$yStart-3; $j<=$yEnd; $j++)
		{
			if (!$rsData->EOF && $rsData->fields($j)!=NULL )
			{
				$oCrop[$i]->Price[$j-$yStart+3] = $rsData->fields($j);
			}
		}
		$rsData->close();
	}
}

//Other Revenues********************************************************************************************************************
for($i=0;$i<=count($oCrop)-1;$i++)
{
	if ($oCrop[$i]->Used == "true")
	{
		//If Crop is Dairy or cowCalf then get the Region
		if ($oCrop[$i]->CommCode == '40159999' || $oCrop[$i]->CommCode == '40999199')
		{
			//Get the Region
			$strSQL = "SELECT * FROM tblOtherRev AS a INNER JOIN tblRegions AS b ON b.Region = a.Region ";
			$strSQL .= "WHERE b.Fips='".$_POST['fips']."' ";
			$strSQL .= "AND a.CommCode=".$oCrop[$i]->CommCode;
			$rsData = $db->Execute($strSQL) or die('<br/><font color="red">Query Error on: <b>'.__FILE__.' Line: '.__LINE__.' </b><br/>'.$db->ErrorMsg() );
			$Region = $rsData->fields('Region');
			if (!$Region)
				$Region = 0;
		}
		else
			//Else set Region to zero, there is no cost data, Therefore use the national Region
			$Region = 0;
		//Regional Other Revenue
		$strSQL = "SELECT * FROM tblOtherRev WHERE Region=".$Region." AND CommCode=".$oCrop[$i]->CommCode;
		$rsData = $db->Execute($strSQL) or die('<br/><font color="red">Query Error on: <b>'.__FILE__.' Line: '.__LINE__.' </b><br/>'.$db->ErrorMsg() );
		for ($j=$yStart; $j<=$yEnd;$j++)
		{
			if (!$rsData->EOF && $rsData->fields($j)!=NULL )
			{
				$oCrop[$i]->OthRev[$j-$yStart]=$rsData->fields($j);
			}
			else
			{
				$oCrop[$i]->OthRev[$j-$yStart]=0;
			}
		}
		$rsData->close();
	}
}

//Variable Costs********************************************************************************************************************
for($i=0;$i<=count($oCrop)-1;$i++)
{
	if ($oCrop[$i]->Used == "true")
	{
		//Get the Region
		$strSQL = "SELECT * FROM tblRegions AS a INNER JOIN tblRegionVarCosts AS b ON a.Region=b.Region ";
		$strSQL .= "WHERE a.Fips='".$_POST['fips']."' ";
		$strSQL .= "AND b.CommCode=".$oCrop[$i]->CommCode;
		$rsData = $db->Execute($strSQL) or die('<br/><font color="red">Query Error on: <b>'.__FILE__.' Line: '.__LINE__.' </b><br/>'.$db->ErrorMsg() );

		if (!$rsData->FetchRow()) //If FetchRow doesn't return a row, then we know Region is zero
		{
			$Region = 0;
		}
		else
		{
			$Region = $rsData->fields('Region');
		}
		//Regional Costs
		$strSQL_repeat = "SELECT * FROM tblRegionVarCosts AS a ";
		$strSQL_repeat .="WHERE a.Region=".$Region." AND a.CommCode=".$oCrop[$i]->CommCode;
		$rsData = $db->Execute($strSQL_repeat) or die('<br/><font color="red">Query Error on: <b>'.__FILE__.' Line: '.__LINE__.' </b><br/>'.$db->ErrorMsg() );
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
		$rsRgYield = $db->Execute($strSQL) or die('<br/><font color="red">Query Error on: <b>'.__FILE__.' Line: '.__LINE__.' </b><br/>'.$db->ErrorMsg() );
		$AvgRgYield = 1;
		if (!$rsRgYield->EOF) //If not end of file, make sure there is a regional yield
		{
			for($k=$yStart-12; $k<=$yStart-3; $k++)
			{
				if (is_numeric($rsRgYield->fields($k)))
				{
					$AvgRgYield += $rsRgYield->fields($k);
				}
			}
		}
		//County Yields
		$strSQL = "SELECT tblCountyYields.* FROM tblCountyYields WHERE Fips='".$_POST['fips']."' AND CommCode=".$oCrop[$i]->CommCode;
		$rsCoYield = $db->Execute($strSQL) or die('<br/><font color="red">Query Error on: <b>'.__FILE__.' Line: '.__LINE__.' </b><br/>'.$db->ErrorMsg() );
		$AvgCoYield = 1;
		for($k=$yStart-12; $k<=$yStart-3; $k++)
		{
			if(!$rsCoYield->EOF && $rsCoYield->fields($k)!=NULL ) //Make sure all yields exist
			{
				if(is_numeric($rsCoYield->fields($k)))
				{
					$AvgCoYield += $rsCoYield->fields($k);
				}
			}
			else //If they don't, set county yield equal to regional yield
			{
				$AvgCoYield = $AvgRgYield;
				break;
			}
		}
		//Save the Variable Costs
		$n=1;
		do
		{
			for($j=$yStart; $j<=$yEnd; $j++)
			{
				if (!$rsData->EOF && $rsData->fields($j)!=NULL )
				{
					//$oCrop[$i]->VarCost[$j-$yStart][$n-1] = round($rsData->fields($j)*(($AvgCoYield/$AvgRgYield-1)*.500+1),4);
					$oCrop[$i]->VarCost[$j-$yStart][$n-1] = round($rsData->fields($j),2, PHP_ROUND_HALF_UP);
					$oCrop[$i]->VarCostLbl[$n-1] = $rsData->fields('Label');
				}
			}
			$n=$n+1;
			//Move to the next Row
			$rsData->MoveNext();
		} while(!$rsData->EOF);
		//Loop back to beginning of FetchRow
		$rsData = $db->Execute($strSQL_repeat) or die('<br/><font color="red">Query Error on: <b>'.__FILE__.' Line: '.__LINE__.' </b><br/>'.$db->ErrorMsg() );

		if (!$rsData->FetchRow()) //If there are no variable costs, then create blank category
		{
			$oCrop[$i]->VarCostLbl[0] = $oCrop[$i]->CName." variable costs";
			$oCrop[$i]->VarCost[0][0] = 0;
		}

		$rsData->Close();
		$rsCoYield->Close();
		$rsRgYield->Close();
	}
}
//Fixed Costs********************************************************************************************************************
for($i=0;$i<=count($oCrop)-1;$i++)
{
	if ($oCrop[$i]->Used == "true")
	{
		//Get the Region
		$strSQL = "SELECT * FROM tblRegions AS a INNER JOIN tblRegionFixedCosts AS b ON a.Region=b.Region ";
		$strSQL .= "WHERE a.Fips='".$_POST['fips']."' ";
		$strSQL .= "AND b.CommCode=".$oCrop[$i]->CommCode;
		$rsData = $db->Execute($strSQL) or die('<br/><font color="red">Query Error on: <b>'.__FILE__.' Line: '.__LINE__.' </b><br/>'.$db->ErrorMsg() );
		if (!$rsData->FetchRow()) //If FetchRow doesn't return a row, then we know Region is zero
		{
			$Region = 0;
		}
		else
		{
			$Region = $rsData->fields('Region');
		}
		//Regional Costs
		$strSQL_repeat = "SELECT * FROM tblRegionFixedCosts AS a ";
		$strSQL_repeat .="WHERE a.Region=".$Region." AND a.CommCode=".$oCrop[$i]->CommCode;
		$rsData = $db->Execute($strSQL_repeat) or die('<br/><font color="red">Query Error on: <b>'.__FILE__.' Line: '.__LINE__.' </b><br/>'.$db->ErrorMsg() );
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
		$rsRgYield = $db->Execute($strSQL) or die('<br/><font color="red">Query Error on: <b>'.__FILE__.' Line: '.__LINE__.' </b><br/>'.$db->ErrorMsg() );
		$AvgRgYield = 1;
		if (!$rsRgYield->EOF ) //If not end of file, make sure there is a regional yield
		{
			for($k=$yStart-12; $k<=$yStart-3; $k++)
			{
				if (is_numeric($rsRgYield->fields($k)))
				{
					$AvgRgYield += $rsRgYield->fields($k);
				}
			}
		}
		$rsRgYield->Close();
		//County Yields
		$strSQL = "SELECT tblCountyYields.* FROM tblCountyYields WHERE Fips='".$_POST['fips']."' AND CommCode=".$oCrop[$i]->CommCode;
		$rsCoYield = $db->Execute($strSQL) or die('<br/><font color="red">Query Error on: <b>'.__FILE__.' Line: '.__LINE__.' </b><br/>'.$db->ErrorMsg() );
		$AvgCoYield = 1;
		for($k=$yStart-12; $k<=$yStart-3; $k++)
		{
			if(!$rsCoYield->EOF && $rsCoYield->fields($k)!=NULL ) //Make sure all yields exist
			{
				if(is_numeric($rsCoYield->fields($k)))
				{
					$AvgCoYield += $rsCoYield->fields($k);
				}
			}
			else //If they don't, set county yield equal to regional yield
			{
				$AvgCoYield = $AvgRgYield;
				break;
			}
		}
		$rsCoYield->Close();
		//Regional Costs
		$n=1;
		do
		{
			for($j=$yStart; $j<=$yEnd; $j++)
			{
				if (!$rsData->EOF  && $rsData->fields($j)!=NULL )
				{
					$oCrop[$i]->FixCost[$j-$yStart][$n-1] = $rsData->fields($j)*(($AvgCoYield/$AvgRgYield-1)*.5+1);
					$oCrop[$i]->FixCostLbl[$n-1] = $rsData->fields('Label');
				}
			}
			$n=$n+1;
			//Move to the next Row
			$rsData->MoveNext();
		} while(!$rsData->EOF);
		//Loop back to beginning of FetchRow
		$rsData = $db->Execute($strSQL_repeat) or die('<br/><font color="red">Query Error on: <b>'.__FILE__.' Line: '.__LINE__.' </b><br/>'.$db->ErrorMsg() );
	
		if (!$rsData->FetchRow()) //If there are no variable costs, then create blank category
		{
			$oCrop[$i]->FixCost[0][0] = 0;
			$oCrop[$i]->FixCostLbl[0] = $oCrop[$i]->CName." fixed costs";
		}
		$rsData->Close();
	}
}	
//Stochastic Draws********************************************************************************************************************
for($i=0;$i<=count($oCrop)-1;$i++)
{
	if ($oCrop[$i]->Used == "true")
	{	
		$strSQL = "SELECT AVG(\"2014\") AS yr2014,  AVG(\"2015\") AS yr2015, AVG(\"2016\") AS yr2016, AVG(\"2017\") AS yr2017, AVG(\"2018\") AS yr2018 FROM tblStochasticDraws WHERE ZTIME = '".$oCrop[$i]->Code."'";
		$rsAvg = $db->Execute($strSQL) or die('<br/><font color="red">Query Error on: <b>'.__FILE__.' Line: '.__LINE__.' </b><br/>'.$db->ErrorMsg() );
	
		if ($rsAvg->fields('yr2014')!=NULL) //If FetchRow returns a row, then load values for display boxes
		{
			$oCrop[$i]->AvgDraw[0] = $rsAvg->fields('yr2014');
			$oCrop[$i]->AvgDraw[1] = $rsAvg->fields('yr2015');
			$oCrop[$i]->AvgDraw[2] = $rsAvg->fields('yr2016');
			$oCrop[$i]->AvgDraw[3] = $rsAvg->fields('yr2017');
			$oCrop[$i]->AvgDraw[4] = $rsAvg->fields('yr2018');
		}
		$rsAvg->Close();
	} 
}
?>