<center>
<?php	
	
	for ($i=0;$i<=count($oCrop)-1;$i++)
	{
		if ($oCrop[$i]->Used=='true' && isset($oCrop[$i]->RandomLatinHyperCube[0][0]))
		{
			echo '<h1>'.$oCrop[$i]->Label.' stats:</h1>';
			echo '<table border="1">';
			echo '<tr><td></td><td><center>2014</td><td><center>2015</td><td><center>2016</td><td><center>2017</td><td><center>2018</td></tr>';
			
			echo "<tr>";
			echo '<td>Avg </td>';
			for ($l=0; $l<=4; $l++)
			{
				echo "<td>";
				echo array_sum($oCrop[$i]->RandomLatinHyperCube[$l])/500;
				echo "</td>";
			}
			echo "</tr>";
			
			echo "<tr>";
			echo '<td>St Dev </td>';
			for ($l=0; $l<=4; $l++)
			{
				echo "<td>";
				echo stats_standard_deviation($oCrop[$i]->RandomLatinHyperCube[$l]);
				echo "</td>";
			}
			echo "</tr>";
			
			echo '</table></br>';
			echo '<h1>Latin HyperCube:</h1>';
			echo '<table border="1">';
			echo '<tr><td><center>2014</td><td><center>2015</td><td><center>2016</td><td><center>2017</td><td><center>2018</td></tr>';
			for ($k=0; $k<500; $k++)
			{
				echo "<tr>";
				for ($l=0; $l<=4; $l++)
				{
					echo "<td>";
					echo $oCrop[$i]->RandomLatinHyperCube[$l][$k]."    ";
					echo "</td>";
				}
				echo "</tr>";
			}
			echo '</table></br>';
		}
	}
?>
</center>