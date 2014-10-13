<?php
//Create class for commodities
class Commodity{
	//Numerical Variables
	  public $Yield=array();
	  public $Price=array();
	  public $HPrice=array();
	  public $NoUnits=array();
	  public $VarCost=array();
	  public $FixCost=array();
	  public $OthRev=array();
	  public $UnitAdj=1;
	  public $Revenue=array();
	  public $Costs=array();
	  public $Profit=array();
	  public $CostUnitAdj;
	//Labels	
	  public $YieldUnit;
	  public $PriceUnit;
	  public $NoUnitsUnit;
	  public $CName;
	  public $Label;
	  public $CommCode;
	  public $Group;
	  public $OutUnit;
	  public $VarCostLbl=array();
	  public $FixCostLbl=array();
	  public $VarCostHelp;
	  public $FixCostHelp;
	  public $Used; //Binary variable used to determine if commodity is used
	  
	  private function Class_Initialize()
	  {
		$this->UnitAdj=1;
		$this->Used="false";
	  }
	  
	  //Function to set size of php Arrays (Not really needed for php)
	  public function SetYears($yrs)
	  {
		$Yield=new SplFixedArray($yrs);
		$Price=new SplFixedArray($yrs);
		$NoUnits=new SplFixedArray($yrs);
		$OthRev=new SplFixedArray($yrs);
		$Revenue=new SplFixedArray($yrs);
		$Costs=new SplFixedArray($yrs);
		$Profit=new SplFixedArray($yrs);
		$CostUnitAdj=new SplFixedArray($yrs);
		$HPrice=new splFixedArray(3);
		$VarCostLbl=new splFixedArray(0);
		$FixCostLbl=new splFixedArray(7);
	  }
	  
	 private function CalcRevenue()
	{
		for ($i=0; $i<=4; $i++)
		{
			$this->Revenue[$i]=($this->Yield[$i] * $this->Price[$i] * $this->UnitAdj + $this->OthRev[$i]) * $this->NoUnits[$i];
		}
	}
	
	private function CalcCosts()
	{
		for ($yr=0; $yr<=4; $yr++)
		{
			$this->Costs[$yr] = $this->TotalVarCost($yr) + $this->TotalFixCost($yr);
		}
	}
  
  private function CalcProfit()
  {
		for ($i=0; $i<=4; $i++)
		{
			$this->Profit[$i]=$this->Revenue[$i] - $this->Costs[$i];
		}
  }
  
  public function Calc()
  {
		//Default Cost Unit if there is none
		if (empty($this->CostUnitAdj[0]) )
		{
			$this->CostUnitAdj=$this->NoUnits;
		}

		$this->CalcRevenue();
		$this->CalcCosts();
		$this->CalcProfit();
  }
  
  public function ResultUnit()
  {
	if( empty($this->OutUnit) )
	{
		$this->OutUnit = $this->NoUnitsUnit;
	}
	else
		return $this->OutUnit;
  }
  
  public function TotalVarCost($yr)
  {
	$result = 0;
	for ($i=0; $i<=count($this->VarCost[0],1)-1; $i++)
	{
		$result = $result + $this->VarCost[$yr][$i] * $this->CostUnitAdj[$yr];
	}
		return $result;
  }
  
    public function TotalFixCost($yr)
  {
	$result = 0;
	for ($i=0; $i<=count($this->FixCost[0],1)-1; $i++)
	{
		$result = $result + $this->FixCost[$yr][$i] * $this->CostUnitAdj[$yr];
	}
		return $result;
  }
  
}
 
?>