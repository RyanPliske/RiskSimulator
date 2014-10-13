<?php
/*****************************************************************************************************************
** File: load_draws.php
* Parent: results.php
*
* This file checks to see which crops have been selected by the user from input.php/results.php  (Upwards of 13 crops can be selected)
* Crops include Corn, Rice, Soybeans, Wheat, Sorghum, Barley, Oats, Hay, Cotton, Peanuts, Sunflowerseed, Sugar beets, Cows, and Dairy
* Note: Not all crops have functioning data in the database. These data values are calculated by FAPRI staff and will soon hopefully be up to date.
* The values that do not produce results are checked by conditional: if  (isset($oCrop[$i]->AvgDraw[0]) )
*
* If a crop is selected then...
*
* 1) stochastic draws are calculated.
* Each Crop  pulls 500 rows of stochastic data from database. Each row contains 5 data entries (2014,2015...2018)
* Therefore there could potentially be 32,500 data entries (if all 13 crops are selected) which are pulled from the DB and stored in php arrays.
* Draws are calculated for each Row based on the following equation: Draw[i] = Row[i]* User Input / Avg. Draw for particular Crop 
*
* 2) a Random Latin Hypercube is created for that crop.
* A Monte Carlo method is used which simply randomizes a number between 1 and 0
* A Latin Hypercube is then created from the Monte Carlo Array (Which contains 500 randomized numbers)
* Beginning with the zero-eth element in the array (which is zero), a number is assigned to the Latin Hypercube Array 
* based on the following formula: Latin Hypercube[i] = 1/500*Rand()+MonteCarlo[i-1]/500
* After which, the Latin Hypercube is then randomized by ranking the elements in the Monte Carlo array (which is random)
* and resorting the Latin Hypercube based on those ranks therefore "randomizing" the order of the array. 
*
* 3) Stochastic Yields are calculated for each 500 Rows so that 
*  Revenues can be calculated for each 500 rows. (per crop)
*
* 4) calc_eigen.php is called 
*****************************************************************************************************************/ 

for($i=0;$i<=count($oCrop)-1;$i++)
{
	if ($oCrop[$i]->Used == "true" && $oCrop[$i]->Label != 'cowcalf')
	{	
		//Grab all 500 Rows and place into array
		$strSQL = "SELECT \"2014\" , \"2015\", \"2016\", \"2017\", \"2018\" FROM tblStochasticDraws WHERE ZTIME = '".$oCrop[$i]->Code."'";
		$rsAll = $db->Execute($strSQL) or die('<br/><font color="red">Query Error on: <b>'.__FILE__.' Line: '.__LINE__.' </b><br/>'.$db->ErrorMsg() );
		if (isset($rsAll->fields[0])) //If FetchRow returns a row, then load values for display boxes
		{
			$rowCount = 0;
			do
			{
				$yrCount = 2014;
				for ($j=0; $j<=4; $j++) //For 2014-2018 input values into 2d Array
				{
					$oCrop[$i]->Draws[$j][$rowCount] = $rsAll->fields($yrCount);
					$yrCount++;
				}
				$rowCount++;
				$rsAll->MoveNext();
			} while(!$rsAll->EOF); 
		}
		//Grab Standard Deviations for the Yield of this Crop
		$queryCount = 0;
		$query_fips_value = iif( $oCrop[$i]->Label != 'dairy', $fips, substr($fips,0,2).'000');

		while (1)
		{
			if ($queryCount > 0)
			{
				$query_fips_value += 2;
				if ($query_fips_value < 10000)
					$query_fips_value = "0".$query_fips_value;
			}
			//echo "<br/>".$query_fips_value;
			$strSQL = "SELECT \"St dev*\" FROM tblStochasticYields WHERE FIPS='".$query_fips_value."' AND CommCode=".$oCrop[$i]->CommCode;
			$rsDev = $db->Execute($strSQL) or die('<br/><font color="red">Query Error on: <b>'.__FILE__.' Line: '.__LINE__.' </b><br/>'.$db->ErrorMsg() );
			if (isset($rsDev->fields[0]))
			{
				$oCrop[$i]->StdDev = floatval($rsDev->fields[0]);
				break;
			}
			$queryCount++;
		}

		$rsDev->Close();
		$rsAll->Close();
	}
	$total_random = 0;
	if  (isset($oCrop[$i]->AvgDraw[0]) && $oCrop[$i]->Label != 'cowcalf')
	{
		//Calculate Draws
		for ($k=0; $k<500; $k++)
		{
			for ($l=0; $l<=4; $l++)
			{
				//Calculated Draw = Row * User Input / Avg. Draw for particular Crop
				$oCrop[$i]->CalcDraws[$l][$k] = $oCrop[$i]->Draws[$l][$k] * ($oCrop[$i]->UserDraws[$l] / $oCrop[$i]->AvgDraw[$l]);
				
				//Calc Monte Carlo: Random Number between 1 and 0
				while(1)
				{
					$_randNum = (mt_rand(0,9)/10 + mt_rand(0,9)/100 + mt_rand(0,9)/1000 + mt_rand(0,9)/10000 + mt_rand(0,9)/100000 + 
											mt_rand(0,9)/1000000  + mt_rand(0,9)/10000000 + mt_rand(0,9)/100000000  + mt_rand(0,9)/1000000000 );
					if ($_randNum > 1 || $_randNum == 0)
						continue;
					else
						break;
				}
				$oCrop[$i]->MonteCarlo[$l][$k] = $_randNum;
				
				//Create Sorted Latin Hypercube Array (Values will increase with each increment)
				//=1/500*RAND()+k/500
				$oCrop[$i]->LatinHyperCube[$l][$k] = 1/500*$_randNum+$k/500;
			}
		}
		//Set this array to a new one called RandomMonteCarlo
		$oCrop[$i]->RandomMonte = $oCrop[$i]->MonteCarlo;
		//Create Random Latin Hypercube Array
		//First sort array (QuickSort seems to be the fastest method)
		for ($l=0; $l<=4; $l++)
		{
			sort($oCrop[$i]->MonteCarlo[$l]); //Sorted Monte Carlo 
		}
		for ($k=0; $k<500; $k++)
		{
			for ($l=0; $l<=4; $l++)
			{
				$oCrop[$i]->RandomLatinHyperCube[$l][$k] = $oCrop[$i]->LatinHyperCube[$l][rank($oCrop[$i]->RandomMonte[0][$k], $oCrop[$i]->MonteCarlo[0])]; //Randomize Latin HyperCube
				
				//Calc Stochastic Yield
				$oCrop[$i]->StocYield[$l][$k] = inverse_ncdf($oCrop[$i]->RandomLatinHyperCube[$l][$k] ) * $oCrop[$i]->StdDev + $oCrop[$i]->Yield[$l];
				
				//Calc Stochastic Revenue
				$oCrop[$i]->StocRev[$l][$k] = ($oCrop[$i]->StocYield[$l][$k] * $oCrop[$i]->Price[$l] * $oCrop[$i]->UnitAdj + $oCrop[$i]->OthRev[$l]) * $oCrop[$i]->NoUnits[$l];
			}
		}
	}
}
//Calculate Eigenvalues and Eigenvectors for a correlated Matrix
include 'calc_eigen.php';



/***************************************************************************
	//Inverse ncdf approximation by Peter John Acklam: 
	//His Facebook: https://www.facebook.com/pjacklam
	//His Site: http://home.online.no/~pjacklam/notes/invnorm/
	//implementation adapted to PHP by Michael Nickerson
	//Input paramater is $p - probability - where 0 < p < 1.
 ***************************************************************************/

function inverse_ncdf($p) 
{

  //Coefficients in rational approximations
  $a = array(1 => -3.969683028665376e+01, 2 => 2.209460984245205e+02,
    			 3 => -2.759285104469687e+02, 4 => 1.383577518672690e+02,
    			 5 => -3.066479806614716e+01, 6 => 2.506628277459239e+00);

  $b = array(1 => -5.447609879822406e+01, 2 => 1.615858368580409e+02,
          		 3 => -1.556989798598866e+02, 4 => 6.680131188771972e+01,
    			 5 => -1.328068155288572e+01);

  $c = array(1 => -7.784894002430293e-03, 2 => -3.223964580411365e-01,
    	 			 3 => -2.400758277161838e+00, 4 => -2.549732539343734e+00,
    			 5 => 4.374664141464968e+00, 6 => 2.938163982698783e+00);

  $d = array(1 => 7.784695709041462e-03, 2 => 3.224671290700398e-01,
    	 			 3 => 2.445134137142996e+00, 4 => 3.754408661907416e+00);

  //Define break-points.
  $p_low =  0.02425;								 //Use lower region approx. below this
  $p_high = 1 - $p_low;								 //Use upper region approx. above this

  //Define/list variables (doesn't really need a definition)
  //$p (probability), $sigma (std. deviation), and $mu (mean) are user inputs
  $q = NULL; $x = NULL; $y = NULL; $r = NULL;

  //Rational approximation for lower region.
  if (0 < $p && $p < $p_low) 
  {
    $q = sqrt(-2 * log($p));
    $x = ((((($c[1] * $q + $c[2]) * $q + $c[3]) * $q + $c[4]) * $q + $c[5]) *
   	 	 	 $q + $c[6]) / (((($d[1] * $q + $d[2]) * $q + $d[3]) * $q + $d[4]) *
  	 		 $q + 1);
  }

  //Rational approximation for central region.
  elseif ($p_low <= $p && $p <= $p_high) 
  {
    $q = $p - 0.5;
    $r = $q * $q;
    $x = ((((($a[1] * $r + $a[2]) * $r + $a[3]) * $r + $a[4]) * $r + $a[5]) *
   	 	 	 $r + $a[6]) * $q / ((((($b[1] * $r + $b[2]) * $r + $b[3]) * $r +
  	 		 $b[4]) * $r + $b[5]) * $r + 1);
  }

  //Rational approximation for upper region.
  elseif ($p_high < $p && $p < 1)
  {
    $q = sqrt(-2 * log(1 - $p));
    $x = -((((($c[1] * $q + $c[2]) * $q + $c[3]) * $q + $c[4]) * $q +
   	 	 	 $c[5]) * $q + $c[6]) / (((($d[1] * $q + $d[2]) * $q + $d[3]) *
  	 		 $q + $d[4]) * $q + 1);
  }

  //If 0 < p < 1, return a null value
  else 
  {
  	$x = NULL;
  }

  return $x;
  //END inverse ncdf implementation.
}


function stats_standard_deviation(array $a, $sample = false) 
{
	$n = count($a);
	$mean = array_sum($a) / $n;
	$carry = 0.0;
	foreach ($a as $val) {
		$d = ((double) $val) - $mean;
		$carry += $d * $d;
	};
	if ($sample) {
	   --$n;
	}
	return sqrt($carry / $n);
}



function rank($lookUp, array $b)
{
	$result = 0;
	foreach($b as $key => $value)
	{
	    if($lookUp == $value)
        {
			$result = $key;
        }
	}
	return $result;
}
?>