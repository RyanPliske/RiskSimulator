<center>
<?php	
/*****************************************************************************************************************
** File: output_eigen.php
* Parent: results.php
* 
* The values used in this are declared in load_draws.php
*
* This file is just for testing purposes!
* This file prints out the results of the Eigenvalues and Eigenvectors
******************************************************************************************************************/
	print "<center>Matrix</center>";
	// Print Table for Selected Matrix
	print "<table border ='1'>";
	for ($i=0; $i < count($matrix); $i++)
	{
		print "<tr>";
		for ($j=0; $j < count($matrix[0]); $j++)
		{
			print "<td>";
			echo $matrix[$i][$j];
			print "</td>";
		}
		print "</tr>";
	}
	print "</table></br>";
	
	// Print Table for values/vectors
	print "<center>EigenVectors</center>";
	print "<table border ='1'>";
	for ($i=0; $i < count($objEigen->EigenVectors); $i++)
	{
		print "<tr>";
		for ($j=0; $j < count($objEigen->EigenVectors[0]); $j++)
		{
			print "<td>";
			echo $objEigen->EigenVectors[$i][$j];
			print "</td>";
		}
		print "</tr>";
	}
	print "</table></br>";
	
	print "<center>EigenValues</center>";
	print "<table border ='1'>";
	for ($i=0; $i < count($objEigen->EigenValuesCol); $i++)
	{
		print "<tr><td>";
		echo $objEigen->EigenValuesCol[$i];
		print "</td></tr>";		
	}
	print "</table>";
	
	
?>
</center>