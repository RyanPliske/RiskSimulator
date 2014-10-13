<center><table class="result" id="r3">
<?php
	echo $str; //Years
	
	//Summary for each commodity
	for ($i=0;$i<=count($oCrop)-1;$i++)
	{
		if ($oCrop[$i]->Used=='true')
		{
			echo "\r\n<tr>";
			if( empty($oCrop[$i]->OutUnit) )
			{
				$unitLabel =  $oCrop[$i]->NoUnitsUnit;
			}
			else
				$unitLabel =  $oCrop[$i]->OutUnit;
			//Total
			echo "\r\n<td class='labels'>". $oCrop[$i]->CName." summary ($ per ".$unitLabel.")";
			echo "<tr><td align=left>Revenues</td>";
			for ($j=0; $j<=$yEnd-$yStart; $j++)
			{
				echo "\r\n<td align=right>".FormatNum(Divide($oCrop[$i]->Revenue[$j],$oCrop[$i]->CostUnitAdj[$j],0),2)."</td>";
			}
			echo "\r\n</tr>";
			
			echo "\r\n<tr><td align=left>";
			echo iif(count($oCrop[$i]->VarCostLbl)>0, "<a href=javascript:ShowHideCost('".$oCrop[$i]->Label."_SubVarCost_r3')>", ""); //Don't hyper link sub costs if no costs
			echo "Variable costs";
			echo iif(count($oCrop[$i]->VarCostLbl)>0, "</a>", ""); //Don't hyper link sub costs if no costs
			echo "</td>";
			for ($j=0; $j<=$yEnd-$yStart; $j++)
			{
				echo "<td align=right>".FormatNum(Divide($oCrop[$i]->TotalVarCost($j),$oCrop[$i]->CostUnitAdj[$j],0),2)."</td>";
			}
			echo "</tr><tbody id=".$oCrop[$i]->Label."_SubVarCost_r3>";
			if (count($oCrop[$i]->VarCostLbl)>0)
			{
			//Create input boxes
				for ($k=0; $k<=count($oCrop[$i]->VarCost[0],1)-1; $k++)
				{
					echo "\r\n<tr><td class=subvar>".$tab1.$oCrop[$i]->VarCostLbl[$k]."</td>";
					for ($j=0; $j<=$yEnd-$yStart; $j++)
					{
						echo "\r\n<td align=right class=subvar>".FormatNum($oCrop[$i]->VarCost[$j][$k],2)."</td>";
					}
					echo "\r\n</tr>";
				}
					echo "</tbody>";
			}
			
			echo "\r\n<tr><td align=left>";
			echo iif(count($oCrop[$i]->FixCostLbl)>0, "<a href=javascript:ShowHideCost('".$oCrop[$i]->Label."_SubFixCost_r3')>", ""); //Don't hyper link sub costs if no costs
			echo "Fixed costs";
			echo iif(count($oCrop[$i]->FixCostLbl)>0, "</a>", ""); //Don't hyper link sub costs if no costs
			echo "</td>";
			for ($j=0; $j<=$yEnd-$yStart; $j++)
			{
				echo "<td align=right>".FormatNum(Divide($oCrop[$i]->TotalFixCost($j),$oCrop[$i]->CostUnitAdj[$j],0),2)."</td>";
			}
			echo "</tr><tbody id=".$oCrop[$i]->Label."_SubFixCost_r3>";
			if (count($oCrop[$i]->FixCostLbl)>0)
			{
			//Create input boxes
				for ($k=0; $k<=count($oCrop[$i]->FixCost[0],1)-1; $k++)
				{
					echo "\r\n<tr><td class=subvar>".$tab1.$oCrop[$i]->FixCostLbl[$k]."</td>";
					for ($j=0; $j<=$yEnd-$yStart; $j++)
					{
						echo "\r\n<td align=right class=subvar>".FormatNum($oCrop[$i]->FixCost[$j][$k],2)."</td>";
					}
					echo "\r\n</tr>";
				}
					echo "</tbody>";
			}
			
			echo "<tr><td colspan=".($yEnd-$yStart+2)."><hr/></td>"; //Horizontal Line
			echo "\r\n<tr><td align=left>Net returns</td>";
			for ($j=0; $j<=$yEnd-$yStart; $j++)
			{
				echo "\r\n<td align=right>".FormatNum(Divide($oCrop[$i]->Profit[$j],$oCrop[$i]->CostUnitAdj[$j],0),2)."</td>";
				$SumProfit[$j] = $SumProfit[$j] + $oCrop[$i]->Profit[$j];
			}
			echo "\r\n</tr>";
			echo "\r\n</td>";
			echo "</tr>";
			echo "<tr><td colspan=".($yEnd-$yStart+2).">&nbsp;</td></tr>"; //Space
		}	
	}		
	//Set up hide subcategories function
	echo "\r\n <script type='text/javascript'> function HideCosts_r3() {";
	for($i=0;$i<=count($oCrop)-1;$i++)
	{
		if ($oCrop[$i]->Used == 'true')
		{
			echo " ShowHideCost('".$oCrop[$i]->Label."_SubFixCost_r3'); ShowHideCost('".$oCrop[$i]->Label."_SubVarCost_r3');";
		}
	}
	echo " } </script>\n\t";
?>
</table></center>

<script type="text/javascript">if (!document.getElementById('r3') && document.getElementById('r3')!=null) parseScriptResult(document.getElementById('r3'));</script>