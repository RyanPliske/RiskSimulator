<center>
<?php	
	
	for ($i=0;$i<=count($oCrop)-1;$i++)
	{
		if ($oCrop[$i]->Used=='true' && isset($oCrop[$i]->StocYield[0][0]))
		{
			echo '<h1>'.$oCrop[$i]->Label.' stats:</h1>';
			echo '<table border="1">';
			echo '<tr><td></td><td><center>2014</td><td><center>2015</td><td><center>2016</td><td><center>2017</td><td><center>2018</td></tr>';
			
			echo "<tr>";
			echo '<td>Yield avg </td>';
			for ($l=0; $l<=4; $l++)
			{
				echo "<td>";
				echo array_sum($oCrop[$i]->StocYield[$l])/500;
				echo "</td>";
			}
			echo "</tr>";
			
			echo "<tr>";
			echo '<td>Yield sd </td>';
			for ($l=0; $l<=4; $l++)
			{
				echo "<td>";
				echo stats_standard_deviation($oCrop[$i]->StocYield[$l]);
				echo "</td>";
			}
			echo "</tr>";
			
			echo "<tr>";
			echo '<td>Rev avg </td>';
			for ($l=0; $l<=4; $l++)
			{
				echo "<td>";
				echo array_sum($oCrop[$i]->StocRev[$l])/500;
				echo "</td>";
			}
			echo "</tr>";
			
			echo "<tr>";
			echo '<td>Rev sd </td>';
			for ($l=0; $l<=4; $l++)
			{
				echo "<td>";
				echo stats_standard_deviation($oCrop[$i]->StocRev[$l]);
				echo "</td>";
			}
			echo "</tr>";
			
			echo '</table></br>';
			echo '<h1>Revenues:</h1>';
			echo '<table border="1">';
			echo '<tr><td><center>2014</td><td><center>2015</td><td><center>2016</td><td><center>2017</td><td><center>2018</td></tr>';
			for ($k=0; $k<500; $k++)
			{
				echo "<tr>";
				for ($l=0; $l<=4; $l++)
				{
					echo "<td>";
					echo $oCrop[$i]->StocRev[$l][$k]."    ";
					echo "</td>";
				}
				echo "</tr>";
			}
			echo '</table></br>';
		}
	}
?>
</center>