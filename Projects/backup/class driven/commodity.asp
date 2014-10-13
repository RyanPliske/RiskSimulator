<%
'class for commodities

Class Commodity

	public Yield(), Price(), NoUnits(), VarCost(), FixCost(), OthRev(), UnitAdj, Revenue(), Costs(), Profit(), CostUnitAdj 'numberical variables
	public YieldUnit, PriceUnit, NoUnitsUnit, CName, Label, CommCode, Group, OutUnit, VarCostLbl(), FixCostLbl() 'labels
	public Used 'binary variable determining if commodity is used

	Private Sub Class_Initialize()
		UnitAdj=1
		Used = "true"
	end sub
	
	Public Sub SetYears(yrs)
		redim Yield(yrs)
		redim Price(yrs)
		redim NoUnits(yrs)
		redim VarCost(yrs,1)
		redim FixCost(yrs,1)
		redim OthRev(yrs)
		redim Revenue(yrs)
		redim Costs(yrs)
		redim Profit(yrs)
		redim CostUnitAdj(yrs)
		redim VarCostLbl(yrs)
		redim FixCostLbl(yrs)
	End Sub
	
	Private sub CalcRevenue()
		dim i
		for i = 0 to ubound(Revenue)
			Revenue(i) = ( Yield(i) * Price(i) * UnitAdj + OthRev(i) ) * NoUnits(i)
		next
	end sub
	
	Private sub CalcCosts()
		dim i
		for i = 0 to ubound(Costs)
			costs(i) = (VarCost(i) + FixCost(i)) * CostUnitAdj(i)
		next
	end sub
	
	Private sub CalcProfit()
		dim i
		for i = 0 to ubound(profit)
			profit(i) = revenue(i) - costs(i)
		next
	end sub
	
	Public sub Calc()
		'default cost unit if there is none
		if IsEmpty(CostUnitAdj(0)) then
			CostUnitAdj=NoUnits
		end if
		
		call CalcRevenue
		call CalcCosts
		call CalcProfit
	end sub
	
	Public Property Get CostUnitLabel
		dim result
		if isempty(OutUnit) then
			result=NoUnitsUnit
		else
			result = OutUnit
		end if
		CostUnitLabel=result
	end property
	
	Public Property Get ResultUnit
		if isempty(OutUnit) then OutUnit = NoUnitsUnit
		ResultUnit=OutUnit
	end property
	
	Public Property Get TotalVarCost(yr)
		dim i, result
		for i = 0 to ubound(VarCost,2)-1
			result = result + VarCost(yr,i)
		next
		TotalVarCost=result
	end property
	
	Public Property Get TotalFixCost(yr)
		dim i, result
		for i = 0 to ubound(FixCost,2)-1
			result = result + FixCost(yr,i)
		next
		TotalFixCost=result
	end property

	Private Sub Class_Terminate()
	End Sub

End Class

%>