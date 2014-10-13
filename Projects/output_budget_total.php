<center><table class="result" id="r1">
<?php
	echo $str; //Years

	//Revenues
	echo "<tr><td colspan=". ($yEnd-$yStart+2)." class='labels'>Revenue ($)</td></tr>";

	for ($i=0;$i<=count($oCrop)-1;$i++)
	{
		if ($oCrop[$i]->Used=='true')
		{
			echo "\r\n<tr><td align=left>".$tab1.$oCrop[$i]->CName."</td>";
			for ($j=0;$j<=$yEnd-$yStart;$j++)
			{
				echo "<td align=right>".FormatNum($oCrop[$i]->Revenue[$j],0)."</td>";
				if (!isset($TotalRev[$j]))
					$TotalRev[$j] = 0;
				$TotalRev[$j]= $TotalRev[$j]+$oCrop[$i]->Revenue[$j];
			}
			echo"</tr>";
		}
	}
	
	//Show Total Revenue
	echo "<tr><td align=left class='labels'>Total revenue</td>";
	for ($j=0; $j<=$yEnd-$yStart; $j++)
	{
		echo "<td align=right class='labels'>".FormatNum($TotalRev[$j],0)."</td>";
	}
	echo "</tr>";
	echo "<tr><td colspan=".($yEnd-$yStart+2)."><hr/></td>"; //Horizontal Line
	
	//Variable Costs
	echo "<tr><td colspan=".($yEnd-$yStart+2).">&nbsp;</td></tr>"; //Space
	echo "<tr><td colspan=".($yEnd-$yStart+2)." class='labels'>Variable costs ($)</td></tr>";
	for ($i=0;$i<=count($oCrop)-1;$i++)
	{
		if ($oCrop[$i]->Used == 'true')
		{
			echo "\r\n<tr><td align=left>".$tab1;
			echo iif(count($oCrop[$i]->VarCostLbl)>0, "<a href=javascript:ShowHideCost('".$oCrop[$i]->Label."_SubVarCost_r1')>", ""); //Don't hyper link sub costs if no costs
			echo $oCrop[$i]->CName;
			echo iif(count($oCrop[$i]->VarCostLbl)>0, "</a>", ""); //Don't hyper link sub costs if no costs
			echo "</td>";

			for ($j=0;$j<=$yEnd-$yStart; $j++)
			{
				echo "<td align=right>".FormatNum($oCrop[$i]->TotalVarCost($j),0)."</td>";
				if (!isset($SumVarCost[$j]))
					$SumVarCost[$j] = 0;
				$SumVarCost[$j] = $SumVarCost[$j]+$oCrop[$i]->TotalVarCost($j);
			}
			echo "</tr><tbody id=".$oCrop[$i]->Label."_SubVarCost_r1>";
			
			if (count($oCrop[$i]->VarCostLbl)>0)
			{
				for ($k=0; $k<=count($oCrop[$i]->VarCost[0],1)-1; $k++)
				{
					echo "\r\n<tr><td class=subvar>".$tab1.$oCrop[$i]->VarCostLbl[$k]."</td>";
					for ($j=0; $j<=$yEnd-$yStart; $j++)
					{
						echo "\r\n<td align=right class=subvar>".FormatNum($oCrop[$i]->VarCost[$j][$k]*$oCrop[$i]->CostUnitAdj[$j],0)."</td>";
					}
					echo "\r\n</tr>";
				}
					echo "</tbody>";
			}
		}
	}
	//Show Total Variable Costs
	echo "<tr><td align=left>Total variable costs</td>";
	for ($j=0;$j<=$yEnd-$yStart; $j++)
	{
		echo "<td align=right>".FormatNum($SumVarCost[$j],0)."</td>";
	}
	echo "</tr>";
	echo "<tr><td colspan=".($yEnd-$yStart+2).">&nbsp;</td></tr>"; //Space
	
	//Fixed Costs
	echo "<tr><td colspan=".($yEnd-$yStart+2)." class='labels'>Fixed costs ($)</td></tr>";
	for ($i=0;$i<=count($oCrop)-1;$i++)
	{
		if ($oCrop[$i]->Used == 'true')
		{
			echo "\r\n<tr><td align=left>".$tab1;
			echo iif(count($oCrop[$i]->VarCostLbl)>0, "<a href=javascript:ShowHideCost('".$oCrop[$i]->Label."_SubFixCost_r1')>", ""); //Don't hyper link sub costs if no costs
			echo $oCrop[$i]->CName;
			echo iif(count($oCrop[$i]->VarCostLbl)>0, "</a>", ""); //Don't hyper link sub costs if no costs
			echo "</td>";

			for ($j=0;$j<=$yEnd-$yStart; $j++)
			{
				echo "<td align=right>".FormatNum($oCrop[$i]->TotalFixCost($j),0)."</td>";
				if (!isset($SumFixCost[$j]))
					$SumFixCost[$j] = 0;
				$SumFixCost[$j] = $SumFixCost[$j]+$oCrop[$i]->TotalFixCost($j);
			}
			echo "</tr><tbody id=".$oCrop[$i]->Label."_SubFixCost_r1>";
			
			if (count($oCrop[$i]->FixCostLbl)>0)
			{
				for ($k=0; $k<=count($oCrop[$i]->FixCost[0],1)-1; $k++)
				{
					echo "\r\n<tr><td class=subvar>".$tab1.$oCrop[$i]->FixCostLbl[$k]."</td>";
					for ($j=0; $j<=$yEnd-$yStart; $j++)
					{
						echo "\r\n<td align=right class=subvar>".FormatNum($oCrop[$i]->FixCost[$j][$k]*$oCrop[$i]->CostUnitAdj[$j],0)."</td>";
					}
					echo "\r\n</tr>";
				}
					echo "</tbody>";
			}
		}
	}
	//Show Total Fixed Costs
	echo "<tr><td align=left>Total fixed costs</td>";
	for ($j=0;$j<=$yEnd-$yStart; $j++)
	{
		echo "<td align=right>".FormatNum($SumFixCost[$j],0)."</td>";
	}
	echo "</tr>";
	echo "<tr><td colspan=".($yEnd-$yStart+2).">&nbsp;</td></tr>"; //Space
	
	//Show Total Costs
	echo "<tr><td align=left class='labels'>Total Costs</td>";
	for ($j=0; $j<=$yEnd-$yStart; $j++)
	{
		echo "<td align=right class='labels'>".FormatNum($SumVarCost[$j]+$SumFixCost[$j],0)."</td>";
	}
	echo "</tr>";
	echo "<tr><td colspan=".($yEnd-$yStart+2)."><hr/></td>"; //Horizontal Line
	
	//Show Total Profits
	echo "<tr><td colspan=".($yEnd-$yStart+2).">&nbsp;</td></tr>"; //Space
	echo "<tr><td align=left class='labels'>Net returns ($)</td>";
	for ($j=0; $j<=$yEnd-$yStart; $j++)
	{
		echo "<td align=right class='labels'>".FormatNum($TotalRev[$j]-$SumVarCost[$j]-$SumFixCost[$j],0)."</td>";
	}
	echo "</tr>";
	echo "<tr><td colspan=".($yEnd-$yStart+2)."><hr/></td>"; //Horizontal Line
	
?>
</table></center>
<?php
	//Set up hide subcategories function
	echo "\r\n <script type='text/javascript'> function HideCosts_r1() {";
	for($i=0;$i<=count($oCrop)-1;$i++)
	{
		if ($oCrop[$i]->Used == 'true')
		{
			echo " ShowHideCost('".$oCrop[$i]->Label."_SubFixCost_r1'); ShowHideCost('".$oCrop[$i]->Label."_SubVarCost_r1');";
		}
	}
	echo " } </script>\n\t";
?>
<script type="text/javascript">if (!document.getElementById('r1') && document.getElementById('r1')!=null) parseScriptResult(document.getElementById('r1'));</script>