<center><table class="result" id="r1">
<?php	
	echo $str; //Years
	
	//Sum variable Costs for each commodity
	for ($i=0; $i<=count($oCrop)-1;$i++)
	{
		if ($oCrop[$i]->Used == 'true')
		{
			for ($j=0; $j<=count($oCrop[$i]->VarCostLbl)-1;$j++)
			{
				//If var cost is already in the VarCostsLbl array
				$loc = -1; //Location Flag (Of positon in 2d Array VarCosts 
				if(isset($VarCostsLbl))
				{
					for ($vc=0; $vc<=count($VarCostsLbl)-1; $vc++)
					{
						if ($VarCostsLbl[$vc]==$oCrop[$i]->VarCostLbl[$j])
						{
							$loc = $vc;
							break;
						}
					}
				}
				if ($loc >= 0)//Then add to current category using location Flag
				{
					for ($yr=0; $yr<=$yEnd-$yStart; $yr++)
					{
						if (!isset($VarCosts[$yr][$loc]))
							$VarCosts[$yr][$loc] = $oCrop[$i]->VarCost[$yr][$j] * $oCrop[$i]->CostUnitAdj[$yr];
						else
							$VarCosts[$yr][$loc] = $VarCosts[$yr][$loc] + $oCrop[$i]->VarCost[$yr][$j] * $oCrop[$i]->CostUnitAdj[$yr];
					}
				}
				else //make NEW category and add it
				{
					for ($yr=0; $yr<=$yEnd-$yStart; $yr++)
					{
						if (!isset($VarCosts[$yr]))
							$VarCosts[$yr] = array();
						$VarCosts[$yr][count($VarCosts[$yr],1)] = $oCrop[$i]->VarCost[$yr][$j] * $oCrop[$i]->CostUnitAdj[$yr];
					}
					if (!isset($VarCostsLbl))
						$VarCostsLbl[0] = $oCrop[$i]->VarCostLbl[$j];
					else
						$VarCostsLbl[count($VarCostsLbl)] = $oCrop[$i]->VarCostLbl[$j];
				}
			}
		}
	} 
	

	//Sum Fixed Costs for Each Commodity
	for ($i=0; $i<=count($oCrop)-1;$i++)
	{
		if ($oCrop[$i]->Used == 'true')
		{
			for ($j=0; $j<=count($oCrop[$i]->FixCostLbl)-1;$j++)
			{
				//If fix cost is in array, add to category
				$loc = -1;
				if(!empty($FixCostsLbl))
				{
					for ($vc=0; $vc<=count($FixCostsLbl)-1; $vc++)
					{
						if ($FixCostsLbl[$vc]==$oCrop[$i]->FixCostLbl[$j])
						{
							$loc = $vc;
							break;
						}
					}
				}

				if ($loc >= 0)
				{
					for ($yr=0; $yr<=$yEnd-$yStart; $yr++)
					{
						if (!isset($FixCosts[$yr][$loc]))
							$FixCosts[$yr][$loc] = $oCrop[$i]->FixCost[$yr][$j] * $oCrop[$i]->CostUnitAdj[$yr];
						else
							$FixCosts[$yr][$loc] = $FixCosts[$yr][$loc] + $oCrop[$i]->FixCost[$yr][$j] * $oCrop[$i]->CostUnitAdj[$yr];
					}
				}
				else //make new category and add it
				{
					for ($yr=0; $yr<=$yEnd-$yStart; $yr++)
					{
						if (!isset($FixCosts[$yr]))
							$FixCosts[$yr] = array();
						$FixCosts[$yr][count($FixCosts[$yr],1)] = $oCrop[$i]->FixCost[$yr][$j] * $oCrop[$i]->CostUnitAdj[$yr];
					}
						if (!isset($FixCostsLbl))
							$FixCostsLbl[0] = $oCrop[$i]->FixCostLbl[$j];
						else
							$FixCostsLbl[count($FixCostsLbl)] = $oCrop[$i]->FixCostLbl[$j];
				}
			}
		}
	}
	
	//Revenues
	echo "<tr><td colspan=".($yEnd-$yStart+2)." class='labels'>Revenue ($)</td></tr>";
	
	//Show Total Revenue
	echo "<tr><td align=left>Total revenue</td>";
	for ($j=0; $j<=$yEnd-$yStart; $j++)
	{
		echo "<td align=right>".FormatNum($TotalRev[$j],0)."</td>";
	}
	echo "</tr>";
	echo "<tr><td colspan=".($yEnd-$yStart+2)."><hr /></td>"; //Horizontal Line
	echo "<tr><td colspan=".($yEnd-$yStart+2).">&nbsp;</td></tr>"; //Space
	
	//Display variable costs each commodity
	echo "<tr><td colspan=".($yEnd-$yStart+2)." class='labels'>Variable costs ($)</td></tr>";
	for ($i=0; $i<=count($VarCostsLbl)-1; $i++)
	{
		echo "\r\n<tr><td align=left>".$tab1.$VarCostsLbl[$i]."</td>";
		for ($j=0; $j<=$yEnd-$yStart; $j++)
		{
			echo "<td align=right>".FormatNum($VarCosts[$j][$i],0)."</td>";
			if ($i!=0)
			{
				$SumVarCost[$j] = $SumVarCost[$j] + $VarCosts[$j][$i];
			}
			else
			{
				$SumVarCost[$j] = $VarCosts[$j][$i]; 
			}
		}
		echo "</tr>";
	}
	
	//Show total variable costs
	echo "<tr><td align=left>Total variable costs</td>";
	for ($j=0; $j<=$yEnd-$yStart; $j++)
	{
		echo "<td align=right>".FormatNum($SumVarCost[$j],0)."</td>";
	} 
	echo "</tr>";
	echo "<tr><td colspan=".($yEnd-$yStart+2).">&nbsp;</td></tr>"; //Space
	
	//Display Fixed Costs Each Commodity
	echo "<tr><td colspan=".($yEnd-$yStart+2)." class='labels'>Fixed costs ($)</td></tr>";
	for ($i=0; $i<=count($FixCostsLbl)-1; $i++)
	{
		echo "\r\n<tr><td align=left>".$tab1.$FixCostsLbl[$i]."</td>";
		for ($j=0; $j<=$yEnd-$yStart; $j++)
		{
			echo "<td align=right>".FormatNum($FixCosts[$j][$i],0)."</td>";
			if ($i!=0)
			{
				$SumFixCost[$j] = $SumFixCost[$j] + $FixCosts[$j][$i];
			}
			else
				$SumFixCost[$j] = $FixCosts[$j][$i];
		}
		echo "</tr>";
	}
	
	//Show total fixed costs
	echo "<tr><td align=left>Total fixed costs</td>";
	for ($j=0; $j<=$yEnd-$yStart; $j++)
	{
		echo "<td align=right>".FormatNum($SumFixCost[$j],0)."</td>";
	} 
	echo "</tr>";
	
	//show total costs
	echo "<tr><td colspan=".($yEnd-$yStart+2).">&nbsp;</td></tr>"; //Space	
	echo "<tr><td align=left class='labels'>Total costs</td>";
	for ($j=0; $j<=$yEnd-$yStart; $j++)
	{
		echo "<td align=right class='labels'>".FormatNum($SumVarCost[$j]+$SumFixCost[$j],0)."</td>";
	}
	echo "</tr>";
	echo "<tr><td colspan=".($yEnd-$yStart+2)."><hr /></td>"; //Horizontal Line
	
	//show total profits.
	echo "<tr><td colspan=".($yEnd-$yStart+2).">&nbsp;</td></tr>"; //Space	
	echo "<tr><td align=left class='labels'>Net returns ($)</td>";
	for ($j=0; $j<=$yEnd-$yStart; $j++)
	{
		echo "<td align=right class='labels'>".FormatNum($TotalRev[$j]-$SumVarCost[$j]-$SumFixCost[$j],0)."</td>";
	}
	echo "</tr>";
	echo "<tr><td colspan=".($yEnd-$yStart+2)."><hr /></td>"; //Horizontal Line
?>
</table></center>