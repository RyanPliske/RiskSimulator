<?php
/*****************************************************************************************************************
** File: eigen.php
* Parent: calc_eigen.php
* This file creates a class called Eigen which contains functions particularly:  linear algebra calculations.
*
*****************************************************************************************************************/
class Eigen
{
	/*Constructor*/
	function __construct()
	{
		$this->accuracy = 0.00001;
		$this->Maxlter = 500;
	}
	
	/*Destructor*/
	function __destruct()
	{
		unset($this);
	}
	
	public function Init(array $E)
	{
		$this->matrix = $E;
		$this->n = count($E);

		$this->ValuesDifferent = True; //Used for accuracy checking
		$this->calculate();
		// Transpose to column vector because EigenValues are a row vector before transposing.
		// and sort Eigenvalues Descending in value
		$j=1;
		
		if($this->EigenValues[0] < $this->EigenValues[1]) 
		{
			for($i=0; $i < $this->n; $i++)
			{
				$this->EigenValuesCol[$i] = $this->EigenValues[$j];
				$j--;
			}
		}
		else
		{
			for($i=0; $i < $this->n; $i++)
			{
				$this->EigenValuesCol[$i] = $this->EigenValues[$i];
			}
		}
		
		// Transpose EigenVectors
		for($j=0; $j < $this->n; $j++)
		{
			for($k=0; $k < $this->n; $k++)
			{
				$this->Transposed_Eigen_Vector[$k][$j] = $this->EigenVectors[$j][$k];
			}
		}
	}
	
	/* Calculate the matrix with the diagonals as the EigenValues */
	private function calculate()
	{
		$i = 0;
		do 
		{
			$i = $i +1;
			$this->maxind();
			$this->CalcRho();
			$this->JacobiMatrix($i);
			// Transpose Identity Matrix (to be used below)
			for($j=0; $j < $this->n; $j++)
			{
				for($k=0; $k < $this->n; $k++)
				{
					$this->Transposed_Identity[$j][$k] = $this->j[$k][$j];
				}
			}
			// Original Matrix =  Identity Matrix Transposed * Original Matrix * Identity Matrix
			$this->matrix = $this->matrix_multiplication( $this->matrix_multiplication($this->Transposed_Identity, $this->matrix) , $this->j );

			$this->CheckAccuracy();
		}while ($i <= $this->Maxlter && $this->ValuesDifferent);
	}
	
	/* Get the largest below diagonal component */
	private function maxind()
	{
		$this->tempmax = -1;
		for ($R=1; $R<=$this->n-1; $R++)
		{
			for ($c=0; $c<=$R-1; $c++)
			{
				if ( abs($this->matrix[$R][$c]) > $this->tempmax )
				{
					$this->tempmax = abs($this->matrix[$R][$c]);
					$this->k = $R;
					$this->l = $c;
				}
			}
		}
	}
	/* Calculate the value of rho */
	private function CalcRho()
	{
		if ( $this->matrix[$this->k][$this->k] != $this->matrix[$this->l][$this->l] )
		{
			//ArcTangent
			$this->rho = atan( 2 * $this->matrix[$this->k][$this->l] / ($this->matrix[$this->k][$this->k] - $this->matrix[$this->l][$this->l]) ) / 2;
		}
		else
		{
			$this->rho = pi() * $this->sign($this->matrix[$this->k][$this->l]) / 4;
		}
	}
	
	private function sign( $number ) 
	{ 
		return ( $number > 0 ) ? 1 : ( ( $number < 0 ) ? -1 : 0 ); 
	}	 
	
	private function JacobiMatrix($i)
	{
		//Set $j to identity Matrix
		$this->j = $this->identity($this->n);
		//Assign new values to $j
		$this->j[$this->k][$this->k] = Cos($this->rho);
		$this->j[$this->k][$this->l] = -Sin($this->rho);
		$this->j[$this->l][$this->k] = Sin($this->rho);
		$this->j[$this->l][$this->l] = Cos($this->rho);
		//Calculate EigenVectors
		if ($i >1 )
			$this->EigenVectors = $this->matrix_multiplication($this->EigenVectors, $this->j);
		else
			$this->EigenVectors = $this->j;
	}
	
	public function matrix_multiplication($matrix_A, $matrix_B)
	{
		for ($i=0; $i < $this->n; $i++)
		{
			for ($j=0; $j < $this->n; $j++)
			{
				$matrix_C[$i][$j] = 0; //Loops through rows, then columns to initialize all elements to zero
				for($k=0; $k < $this->n; $k++)
				{
				 $matrix_C[$i][$j] += $matrix_A[$i][$k] * $matrix_B[$k][$j];
				}
			}
		}
		return $matrix_C;
	}
	
	/* Creates an Identity Matrix by filling diagonal with 1's, rest of matrix should be zeroes */
	private function identity($dimension)
	{
		for ($i = 0; $i < $dimension ; $i++)
		{
			for ($j=0; $j < $dimension; $j++)
			{
				$Array[$i][$j] = 0;
				if ($i == $j)
					$Array[$i][$j] = 1;
			}
		}
		return $Array;
	}
	
	/* See if EigenValues are significant */
	private function CheckAccuracy()
	{
		$this->ValuesDifferent = False;

		If (abs($this->tempmax) > $this->accuracy)
		{
			$this->ValuesDifferent = True;
		}
		$this->ExtractEigenValues();
	}
	
	/* Grab diagonal from matrix */
	private function ExtractEigenValues()
	{
		for ($i=0;$i < $this->n; $i++)
		{
			$this->EigenValues[$i] = $this->matrix[$i][$i];
		}
	}
}

?>