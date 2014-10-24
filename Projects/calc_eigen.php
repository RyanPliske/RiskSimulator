<?php
/*****************************************************************************************************************
** File: calc_eigen.php
* Parent: load_draws.php
*
* This file takes in a matrix (Double Array) and computes the Eigen Vector and Eigen Values 
* for that particular matrix.
*
*****************************************************************************************************************/
include('eigen.php');
$objEigen = new Eigen();
//Grab the crops Price-Yield Correlation Value

//Build the correlation matrix
$matrix = array(
					array(1, -0.35),
					array(-0.35, 1),
					);

//Initialize Eigen Object: This calculates the Eigenvalues and Eigenvectors of the given matrix
$objEigen->Init($matrix);
// The Eigenvalues and Eigenvectors are now calculated! Now let's edit them a bit.
// Using the calculated eigenvalues and eigenvectors, let's find the nearest correlation matrix
$oldMatrix = $matrix; //Save the initial matrix

// Find nearest Decomposed Correlation Matrix
$x=0;
// The purpose of this loop is to Calculate Eigenvalues and Eigenvectors until eigenvalues are at least zero
do{
		$x++;
		if($x!=1) // Skip calculating Eigen first time around because we just did that when we used : init()
		{
			// Calculate Eigenvalues and Eigenvectors
			$objEigen->Init($oldMatrix);
		}
		//Make sure all eigenvalues are at least 0
		for ($i=0; $i < count($objEigen->EigenValuesCol); $i++)
		{
			if($objEigen->EigenValuesCol[$i] < 0)
				$objEigen->EigenValuesCol[$i] = 0;
		}
		
		// Create Identity Matrix with EigenValues as Diagonals
		for ($i = 0; $i < count($objEigen->EigenValuesCol); $i++)
		{
			for ($j=0; $j < count($objEigen->EigenValuesCol); $j++)
			{
				$diagonalized_Identity[$i][$j] = (float)0;
				if ($i == $j)
					$diagonalized_Identity[$i][$j] = $objEigen->EigenValuesCol[$j];
			}
		}

		// CorrelationMatrix = EigenvectorMatrix * Identity Matrix with EigenValues as Diagonals * EigenvectorMatrix Transposed

		$corrMatrix = $objEigen->matrix_multiplication($objEigen->EigenVectors, $diagonalized_Identity);
		$corrMatrix = $objEigen->matrix_multiplication($corrMatrix, $objEigen->Transposed_Eigen_Vector);

		// Set Diagonals to 1
		for ($i = 0; $i < count($corrMatrix); $i++)
		{
			for ($j=0; $j < count($corrMatrix[0]); $j++)
			{
				if ($i == $j)
					$corrMatrix[$i][$j] = (float)1;
			}
		}
		
		// Subtract Matrices: $temp = $corrMatrix - $oldMatrix;
		for ($i = 0; $i < count($corrMatrix); $i++)
		{
			for ($j=0; $j < count($corrMatrix[0]); $j++)
			{
					$temp[$i][$j] = $corrMatrix[$i][$j] - $oldMatrix[$i][$j];
			}
		}

		$check = sqrt(abs(ColSumsOfSquares($temp) / ColSumsOfSquares($oldMatrix)));

		$oldMatrix = $corrMatrix;

	} 
	while($check < pow(10,-20));
	// Before we leave set non-diagonals back to original pos/neg it began with
	for ($i = 0; $i < count($corrMatrix); $i++)
	{
		for ($j=0; $j < count($corrMatrix[0]); $j++)
		{
			if ($i != $j)
				$corrMatrix[$i][$j] = $corrMatrix[$i][$j] * -1;
		}
	}
	$oldMatrix = $corrMatrix;
	var_dump($oldMatrix);

//Destroy object
$objEigen->__destruct();

function ColSumsOfSquares($a)
{
	$sum = 0;
	for ($i = 0; $i < count($a); $i++)
	{
		for ($j=0; $j < count($a[0]); $j++)
		{
			$sum = $sum + ($a[$i][$j] * $a[$i][$j]) ;
		}
	}
	return $sum;
}
?>