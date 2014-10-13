<center><table class="result" id="r2">
<?php
	echo $str; //Years
	
	//Summary for each commodity
	for ($i=0;$i<=count($oCrop)-1;$i++)
	{
		if ($oCrop[$i]->Used == 'true')
		{
			echo "\r\n<tr>";
			
			//Total
			echo "\r\n<td class='labels'>".$oCrop[$i]->CName." summary ($)";
			echo "\r\n<tr><td align=left>Revenues</td>";
			for ($j=0; $j<=$yEnd-$yStart; $j++)
			{
				echo "\r\n<td align=right>".FormatNum($oCrop[$i]->Revenue[$j],0)."</td>";
			}
			echo "\r\n</tr>";
			
			echo "\r\n<tr><td align=left>";
			echo iif(count($oCrop[$i]->VarCostLbl)>0, "<a href=javascript:ShowHideCost('".$oCrop[$i]->Label."_SubVarCost_r2')>", ""); //Don't hyper link sub costs if no costs
			echo "Variable costs";
			echo iif(count($oCrop[$i]->VarCostLbl)>0, "</a>", ""); //Don't hyper link sub costs if no costs
			echo "</td>";
			for ($j=0; $j<=$yEnd-$yStart; $j++)
			{
				echo "<td align=right>".FormatNum($oCrop[$i]->TotalVarCost($j),0)."</td>";
			}
			echo "</tr><tbody id=".$oCrop[$i]->Label."_SubVarCost_r2>";
			if (count($oCrop[$i]->VarCostLbl)>0)
			{
			//Create input boxes
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
			
			echo "\r\n<tr><td align=left>";
			echo iif(count($oCrop[$i]->FixCostLbl)>0, "<a href=javascript:ShowHideCost('".$oCrop[$i]->Label."_SubFixCost_r2')>", ""); //Don't hyper link sub costs if no costs
			echo "Fixed costs";
			echo iif(count($oCrop[$i]->FixCostLbl)>0, "</a>", ""); //Don't hyper link sub costs if no costs
			echo "</td>";
			for ($j=0; $j<=$yEnd-$yStart; $j++)
			{
				echo "<td align=right>".FormatNum($oCrop[$i]->TotalFixCost($j),0)."</td>";
			}
			echo "</tr><tbody id=".$oCrop[$i]->Label."_SubFixCost_r2>";
			if (count($oCrop[$i]->FixCostLbl)>0)
			{
			//Create input boxes
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
			
			echo "<tr><td colspan=".($yEnd-$yStart+2)."><hr/></td>"; //Horizontal Line
			echo "\r\n<tr><td align=left>Net returns</td>";

			for ($j=0; $j<=$yEnd-$yStart; $j++)
			{
				echo "\r\n<td align=right>".FormatNum($oCrop[$i]->Profit[$j],0)."</td>";
				if (!isset($SumProfit[$j]))
					$SumProfit[$j] = $oCrop[$i]->Profit[$j];
				else
					$SumProfit[$j] = $SumProfit[$j] + $oCrop[$i]->Profit[$j];
			}
			echo "\r\n</tr>";
			echo "\r\n</td>";
			echo "</tr>";
			echo "<tr><td colspan=".($yEnd-$yStart+2).">&nbsp;</td></tr>"; //Space
		}
	}
	
	//Show Total Profits	
	echo "<tr><td align=left class='labels'>Total net returns ($)</td>";
	for ($j=0; $j<=$yEnd-$yStart; $j++)
	{
		echo "<td align=right class='labels'>".FormatNum($SumProfit[$j],0)."</td>";
	}
	echo "</tr>";
	echo "<tr><td colspan=".($yEnd-$yStart+2)."><hr/></td>"; //Horizontal Line
	
	//Set up hide subcategories function
	echo "\r\n <script type='text/javascript'> function HideCosts_r2() {";
	for($i=0;$i<=count($oCrop)-1;$i++)
	{
		if ($oCrop[$i]->Used == 'true')
		{
			echo " ShowHideCost('".$oCrop[$i]->Label."_SubFixCost_r2'); ShowHideCost('".$oCrop[$i]->Label."_SubVarCost_r2');";
		}
	}
	echo " } </script>\n\t";
?>
</table></center>

<script type="text/javascript">if (!document.getElementById('r2') && document.getElementById('r2')!=null) parseScriptResult(document.getElementById('r2'));</script>