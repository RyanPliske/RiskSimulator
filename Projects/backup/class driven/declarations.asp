<!-- #include virtual="/Projects/commodity.asp" -->
<%

'*****************************************************************************************************
'declarations
'*****************************************************************************************************

dim oCrop()
i=0
redim preserve oCrop(i+1)
set oCrop(i) = New Commodity
oCrop(i).CName= "Corn"
oCrop(i).YieldUnit = "bu. per acre"
oCrop(i).PriceUnit = "$ per bu."
oCrop(i).NoUnitsUnit = "acre"
oCrop(i).Label = "corn"
oCrop(i).CommCode = "11199199"
oCrop(i).Group = "crop"
oCrop(i).Used = iif(len(request.Form(oCrop(i).label))>0,request.Form(oCrop(i).label),oCrop(i).Used)

i=i+1
redim preserve oCrop(i+1)
set oCrop(i) = New Commodity
oCrop(i).CName= "Soybeans"
oCrop(i).YieldUnit = oCrop(i-1).YieldUnit
oCrop(i).PriceUnit = oCrop(i-1).PriceUnit
oCrop(i).NoUnitsUnit = oCrop(i-1).NoUnitsUnit
oCrop(i).Label = "soybeans"
oCrop(i).CommCode = "15499199"
oCrop(i).Group = "crop"
oCrop(i).Used = iif(len(request.Form(oCrop(i).label))>0,request.Form(oCrop(i).label),oCrop(i).Used)

i=i+1
redim preserve oCrop(i+1)
set oCrop(i) = New Commodity
oCrop(i).CName= "Wheat"
oCrop(i).YieldUnit = oCrop(i-1).YieldUnit
oCrop(i).PriceUnit = oCrop(i-1).PriceUnit
oCrop(i).NoUnitsUnit = oCrop(i-1).NoUnitsUnit
oCrop(i).Label = "wheat"
oCrop(i).CommCode = "10199999"
oCrop(i).Group = "crop"
oCrop(i).Used = iif(len(request.Form(oCrop(i).label))>0,request.Form(oCrop(i).label),oCrop(i).Used)

i=i+1
redim preserve oCrop(i+1)
set oCrop(i) = New Commodity
oCrop(i).CName= "Sorghum"
oCrop(i).YieldUnit = oCrop(i-1).YieldUnit
oCrop(i).PriceUnit = oCrop(i-1).PriceUnit
oCrop(i).NoUnitsUnit = oCrop(i-1).NoUnitsUnit
oCrop(i).Label = "sorghum"
oCrop(i).CommCode = "11499199"
oCrop(i).Group = "crop"
oCrop(i).Used = iif(len(request.Form(oCrop(i).label))>0,request.Form(oCrop(i).label),oCrop(i).Used)

i=i+1
redim preserve oCrop(i+1)
set oCrop(i) = New Commodity
oCrop(i).CName= "Barley"
oCrop(i).YieldUnit = oCrop(i-1).YieldUnit
oCrop(i).PriceUnit = oCrop(i-1).PriceUnit
oCrop(i).NoUnitsUnit = oCrop(i-1).NoUnitsUnit
oCrop(i).Label = "barley"
oCrop(i).CommCode = "11399999"
oCrop(i).Group = "crop"
oCrop(i).Used = iif(len(request.Form(oCrop(i).label))>0,request.Form(oCrop(i).label),oCrop(i).Used)

i=i+1
redim preserve oCrop(i+1)
set oCrop(i) = New Commodity
oCrop(i).CName= "Oats"
oCrop(i).YieldUnit = oCrop(i-1).YieldUnit
oCrop(i).PriceUnit = oCrop(i-1).PriceUnit
oCrop(i).NoUnitsUnit = oCrop(i-1).NoUnitsUnit
oCrop(i).Label = "oats"
oCrop(i).CommCode = "11299999"
oCrop(i).Group = "crop"
oCrop(i).Used = iif(len(request.Form(oCrop(i).label))>0,request.Form(oCrop(i).label),oCrop(i).Used)

i=i+1
redim preserve oCrop(i+1)
set oCrop(i) = New Commodity
oCrop(i).CName= "Hay"
oCrop(i).YieldUnit = "tons per acre"
oCrop(i).PriceUnit = "$ per ton"
oCrop(i).NoUnitsUnit = "acre"
oCrop(i).Label = "hay"
oCrop(i).CommCode = "18999999"
oCrop(i).Group = "crop"
oCrop(i).Used = iif(len(request.Form(oCrop(i).label))>0,request.Form(oCrop(i).label),oCrop(i).Used)

i=i+1
redim preserve oCrop(i+1)
set oCrop(i) = New Commodity
oCrop(i).CName= "Rice"
oCrop(i).YieldUnit = "lbs. per acre"
oCrop(i).PriceUnit = "$ per cwt"
oCrop(i).NoUnitsUnit = "acre"
oCrop(i).UnitAdj = .01
oCrop(i).Label = "rice"
oCrop(i).CommCode = "10619999"
oCrop(i).Group = "crop"
oCrop(i).Used = iif(len(request.Form(oCrop(i).label))>0,request.Form(oCrop(i).label),oCrop(i).Used)

i=i+1
redim preserve oCrop(i+1)
set oCrop(i) = New Commodity
oCrop(i).CName= "Upland cotton"
oCrop(i).YieldUnit = "lbs. per acre"
oCrop(i).PriceUnit = "$ per cwt"
oCrop(i).NoUnitsUnit = "acre"
oCrop(i).UnitAdj = .01
oCrop(i).Label = "cotton"
oCrop(i).CommCode = "12121999"
oCrop(i).Group = "crop"
oCrop(i).Used = iif(len(request.Form(oCrop(i).label))>0,request.Form(oCrop(i).label),oCrop(i).Used)

i=i+1
redim preserve oCrop(i+1)
set oCrop(i) = New Commodity
oCrop(i).CName= "Peanuts"
oCrop(i).YieldUnit = "lbs. per acre"
oCrop(i).PriceUnit = "$ per pound"
oCrop(i).NoUnitsUnit = "acre"
oCrop(i).Label = "peanuts"
oCrop(i).CommCode = "15399199"
oCrop(i).Group = "crop"
oCrop(i).Used = iif(len(request.Form(oCrop(i).label))>0,request.Form(oCrop(i).label),oCrop(i).Used)

i=i+1
redim preserve oCrop(i+1)
set oCrop(i) = New Commodity
oCrop(i).CName= "Sunflowerseed"
oCrop(i).YieldUnit = "lbs. per acre"
oCrop(i).PriceUnit = "$ per pound"
oCrop(i).NoUnitsUnit = "acre"
oCrop(i).Label = "sunflowerseed"
oCrop(i).CommCode = "15831999"
oCrop(i).Group = "crop"
oCrop(i).Used = iif(len(request.Form(oCrop(i).label))>0,request.Form(oCrop(i).label),oCrop(i).Used)

i=i+1
redim preserve oCrop(i+1)
set oCrop(i) = New Commodity
oCrop(i).CName= "Sugar beets"
oCrop(i).YieldUnit = "tons per acre"
oCrop(i).PriceUnit = "$ per cwt"
oCrop(i).NoUnitsUnit = "acre"
oCrop(i).UnitAdj = 20
oCrop(i).Label = "sugarbeets"
oCrop(i).CommCode = "13299199"
oCrop(i).Group = "crop"
oCrop(i).Used = iif(len(request.Form(oCrop(i).label))>0,request.Form(oCrop(i).label),oCrop(i).Used)

i=i+1
redim preserve oCrop(i+1)
set oCrop(i) = New Commodity
oCrop(i).CName= "Cow/calf"
oCrop(i).YieldUnit = "lbs./calf sold"
oCrop(i).PriceUnit = "$ per cwt"
oCrop(i).NoUnitsUnit = "cow"
oCrop(i).UnitAdj = .01
oCrop(i).Label = "cowcalf"
oCrop(i).CommCode = "40159999"
oCrop(i).Group = "livestock"
oCrop(i).Used = iif(len(request.Form(oCrop(i).label))>0,request.Form(oCrop(i).label),oCrop(i).Used)

i=i+1
redim preserve oCrop(i+1)
set oCrop(i) = New Commodity
oCrop(i).CName= "Dairy"
oCrop(i).YieldUnit = "lbs. milk/cow"
oCrop(i).PriceUnit = "$ per cwt"
oCrop(i).NoUnitsUnit = "cow"
oCrop(i).OutUnit = "cwt milk"
oCrop(i).UnitAdj = .01
oCrop(i).Label = "dairy"
oCrop(i).CommCode = "40999199"
oCrop(i).Group = "livestock"
oCrop(i).Used = iif(len(request.Form(oCrop(i).label))>0,request.Form(oCrop(i).label),oCrop(i).Used)

'i=i+1
'redim preserve oCrop(i+1)
'set oCrop(i) = New Commodity
'oCrop(i).CName= "Hogs (farrow)"
'oCrop(i).YieldUnit = "lbs./feeder pig sold"
'oCrop(i).PriceUnit = "$ per cwt"
'oCrop(i).NoUnitsUnit = "Feeder pigs sold"
'oCrop(i).UnitAdj = .01
'oCrop(i).Label = "farrow"
'oCrop(i).CommCode = ""
'oCrop(i).Group = "livestock"
'oCrop(i).Used = iif(len(request.Form(oCrop(i).label))>0,request.Form(oCrop(i).label),oCrop(i).Used)
'
'i=i+1
'redim preserve oCrop(i+1)
'set oCrop(i) = New Commodity
'oCrop(i).CName= "Hogs (finish)"
'oCrop(i).YieldUnit = "lbs./pig sold"
'oCrop(i).PriceUnit = "$ per cwt"
'oCrop(i).NoUnitsUnit = "Market pigs sold"
'oCrop(i).UnitAdj = .01
'oCrop(i).Label = "finish"
'oCrop(i).CommCode = ""
'oCrop(i).Group = "livestock"
'oCrop(i).Used = iif(len(request.Form(oCrop(i).label))>0,request.Form(oCrop(i).label),oCrop(i).Used)

'Set the number of years
for j = 0 to i
	oCrop(j).SetYears(yEnd-yStart+1)
next


'*********************************************************************************************************
'necessary functions
'*********************************************************************************************************
function iif(condition, return_true, return_false)
	if condition then
		iif = return_true
	else
		iif = return_false
	end if
end function

function LookupComm(lbl)

	for j = 0 to ubound(oCrop)-1
		if oCrop(j).Label=lbl then exit for
	next
	
	LookupComm = iif(oCrop(j).Label=lbl,j,"error")

end function

%>