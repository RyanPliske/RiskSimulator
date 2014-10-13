<?php
	require 'commodity.php';
	//Declare oCrop object and place Corn,Soybeans,Wheat,Sorghum, Barley, Oats, Hay, Rice...
	//Upland Cotton, Peanuts, Sunflower seed, Sugar beets, Cow/Calf, and Dairy into the object
	
	$yEnd = 2018;
	$yStart = 2014;
	
	//Corn********************************************************
	$i=0;
	$oCrop[$i] = new Commodity();
	$oCrop[$i]->CName = "Corn";
	$oCrop[$i]->YieldUnit = "bu. per acre";
	$oCrop[$i]->PriceUnit = "$ per bu.";
	$oCrop[$i]->NoUnitsUnit = "acre";
	$oCrop[$i]->Label = "corn";
	$oCrop[$i]->CommCode = "11199199";
	$oCrop[$i]->Group = "crop";
	$oCrop[$i]->Code = "CRPFRM";
	//If Crop is selected
	if( isset($_POST[$oCrop[$i]->Label]) )
	{
		$oCrop[$i]->Used = $_POST[$oCrop[$i]->Label];
	}
	else{}//Do Nothing
	$oCrop[$i]->VarCostHelp  = "Variable costs include seed, fertilizer (commercial fertilizers, soil conditioners and manure), chemicals, custom operations, technical services, commercial drying, fuel, lube, electricity, repairs, purchased irrigation water and interest on operating capital. The default value is based on FAPRI forecasts of USDA data and is adjusted based upon the county yield to regional yield.";
	$oCrop[$i]->FixCostHelp = "Fixed costs include hired labor, capital recovery of machinery and equipment, opportunity cost of land (rental rate), taxes and insurance and general farm overhead.";

	//Soybeans********************************************************
	$i=1;
	$oCrop[$i] = new Commodity();
	$oCrop[$i]->CName = "Soybeans";
	$oCrop[$i]->YieldUnit = "bu. per acre";
	$oCrop[$i]->PriceUnit = "$ per bu.";
	$oCrop[$i]->NoUnitsUnit = "acre";
	$oCrop[$i]->Label = "soybeans";
	$oCrop[$i]->CommCode = "15499199";
	$oCrop[$i]->Group = "crop";
	$oCrop[$i]->Code = "SBPFRM";
	//If Crop is selected
	if( isset($_POST[$oCrop[$i]->Label]) )
	{
		$oCrop[$i]->Used = $_POST[$oCrop[$i]->Label];
	}
	else{}//Do Nothing
	$oCrop[$i]->VarCostHelp  = "Variable costs include seed, fertilizer (commercial fertilizers, soil conditioners and manure), chemicals, custom operations, fuel, lube, electricity, repairs, purchased irrigation water and interest on operating capital. The default value is based on FAPRI forecasts of USDA data and is adjusted based upon the county yield to regional yield.";
	$oCrop[$i]->FixCostHelp = "Fixed costs include hired labor, capital recovery of machinery and equipment, opportunity cost of land (rental rate), taxes and insurance and general farm overhead.";

	//Wheat********************************************************
	$i=2;
	$oCrop[$i] = new Commodity();
	$oCrop[$i]->CName = "Wheat";
	$oCrop[$i]->YieldUnit = "bu. per acre";
	$oCrop[$i]->PriceUnit = "$ per bu.";
	$oCrop[$i]->NoUnitsUnit = "acre";
	$oCrop[$i]->Label = "wheat";
	$oCrop[$i]->CommCode = "10199999";
	$oCrop[$i]->Group = "crop";
	$oCrop[$i]->Code = "WHPFRM";
	//If Crop is selected
	if( isset($_POST[$oCrop[$i]->Label]) )
	{
		$oCrop[$i]->Used = $_POST[$oCrop[$i]->Label];
	}
	else{}//Do Nothing
	$oCrop[$i]->VarCostHelp  ="Variable costs include seed, fertilizer (commercial fertilizers, soil conditioners and manure), chemicals, custom operations, fuel, lube, electricity, repairs, purchased irrigation water, straw bailing and interest on operating capital. The default value is based on FAPRI forecasts of USDA data and is adjusted based upon the county yield to regional yield.";
	$oCrop[$i]->FixCostHelp = "Fixed costs include hired labor, capital recovery of machinery and equipment, opportunity cost of land (rental rate), taxes and insurance and general farm overhead.";
	
	//Sorghum********************************************************
	$i=3;
	$oCrop[$i] = new Commodity();
	$oCrop[$i]->CName = "Sorghum";
	$oCrop[$i]->YieldUnit = "bu. per acre";
	$oCrop[$i]->PriceUnit = "$ per bu.";
	$oCrop[$i]->NoUnitsUnit = "acre";
	$oCrop[$i]->Label = "sorghum";
	$oCrop[$i]->CommCode = "11499199";
	$oCrop[$i]->Group = "crop";
	$oCrop[$i]->Code = "SGPFRM";
	//If Crop is selected
	if( isset($_POST[$oCrop[$i]->Label]) )
	{
		$oCrop[$i]->Used = $_POST[$oCrop[$i]->Label];
	}
	else{}//Do Nothing
	$oCrop[$i]->VarCostHelp  = "Variable costs include seed, fertilizer (commercial fertilizers and soil conditioners), chemicals, custom operations, fuel, lube, electricity, repairs, purchased irrigation water and interest on operating capital. The default value is based on FAPRI forecasts of USDA data and is adjusted based upon the county yield to regional yield.";
	$oCrop[$i]->FixCostHelp = "Fixed costs include hired labor, capital recovery of machinery and equipment, opportunity cost of land, taxes and insurance and general farm overhead.";
	
	//Barley********************************************************
	$i=4;
	$oCrop[$i] = new Commodity();
	$oCrop[$i]->CName = "Barley";
	$oCrop[$i]->YieldUnit = "bu. per acre";
	$oCrop[$i]->PriceUnit = "$ per bu.";
	$oCrop[$i]->NoUnitsUnit = "acre";
	$oCrop[$i]->Label = "barley";
	$oCrop[$i]->CommCode = "11399999";
	$oCrop[$i]->Group = "crop";
	$oCrop[$i]->Code = "BRPFRM";
	//If Crop is selected
	if( isset($_POST[$oCrop[$i]->Label]) )
	{
		$oCrop[$i]->Used = $_POST[$oCrop[$i]->Label];
	}
	else{}//Do Nothing
	$oCrop[$i]->VarCostHelp  = "Variable costs include seed, fertilizer (commercial fertilizers, soil conditioners and manure), chemicals, custom operations, fuel, lube, electricity, repairs, purchased irrigation water, straw bailing and interest on operating capital. The default value is based on FAPRI forecasts of USDA data and is adjusted based upon the county yield to US yield.";
	$oCrop[$i]->FixCostHelp = "Fixed costs include hired labor, capital recovery of machinery and equipment, opportunity cost of land (rental rate), taxes and insurance and general farm overhead.";
	
	//Oats********************************************************
	$i=5;
	$oCrop[$i] = new Commodity();
	$oCrop[$i]->CName = "Oats";
	$oCrop[$i]->YieldUnit = "bu. per acre";
	$oCrop[$i]->PriceUnit = "$ per bu.";
	$oCrop[$i]->NoUnitsUnit = "acre";
	$oCrop[$i]->Label = "oats";
	$oCrop[$i]->CommCode = "11299999";
	$oCrop[$i]->Group = "crop";
	$oCrop[$i]->Code = "OTPFRM";
	//If Crop is selected
	if( isset($_POST[$oCrop[$i]->Label]) )
	{
		$oCrop[$i]->Used = $_POST[$oCrop[$i]->Label];
	}
	else{}//Do Nothing
	$oCrop[$i]->VarCostHelp  = "Variable costs include seed, fertilizer (commercial fertilizers, soil conditioners and manure), chemicals, custom operations, fuel, lube, electricity, repairs, straw bailing and interest on operating capital. The default value is based on FAPRI forecasts of USDA data and is adjusted based upon the county yield to US yield.";
	$oCrop[$i]->FixCostHelp =  "Fixed costs include hired labor, capital recovery of machinery and equipment, opportunity cost of land, taxes and insurance and general farm overhead.";
	
	//Hay********************************************************
	$i=6;
	$oCrop[$i] = new Commodity();
	$oCrop[$i]->CName = "Hay";
	$oCrop[$i]->YieldUnit = "tons per acre";
	$oCrop[$i]->PriceUnit = "$ per ton";
	$oCrop[$i]->NoUnitsUnit = "acre";
	$oCrop[$i]->Label = "hay";
	$oCrop[$i]->CommCode = "18999999";
	$oCrop[$i]->Group = "crop";
	$oCrop[$i]->Code = "HAPFRM";
	//If Crop is selected
	if( isset($_POST[$oCrop[$i]->Label]) )
	{
		$oCrop[$i]->Used = $_POST[$oCrop[$i]->Label];
	}
	else{}//Do Nothing
	$oCrop[$i]->VarCostHelp  = "No default hay costs are provided.";
	$oCrop[$i]->FixCostHelp =  "No default hay costs are provided.";
	
	//Rice********************************************************
	$i=7;
	$oCrop[$i] = new Commodity();
	$oCrop[$i]->CName = "Rice";
	$oCrop[$i]->YieldUnit = "lbs. per acre";
	$oCrop[$i]->PriceUnit = "$ per cwt";
	$oCrop[$i]->NoUnitsUnit = "acre";
	$oCrop[$i]->UnitAdj = .01;
	$oCrop[$i]->Label = "rice";
	$oCrop[$i]->CommCode = "10619999";
	$oCrop[$i]->Group = "crop";
	$oCrop[$i]->Code = "RCPFRM";
	//If Crop is selected
	if( isset($_POST[$oCrop[$i]->Label]) )
	{
		$oCrop[$i]->Used = $_POST[$oCrop[$i]->Label];
	}
	else{}//Do Nothing
	$oCrop[$i]->VarCostHelp  =  "Variable costs include seed, fertilizer (commercial fertilizers and soil conditioners), chemicals, custom operations, fuel, lube, electricity, repairs, purchased irrigation water, commercial drying and interest on operating capital. The default value is based on FAPRI forecasts of USDA data and is adjusted based upon the county yield to regional yield.";
	$oCrop[$i]->FixCostHelp =  "Fixed costs include hired labor, capital recovery of machinery and equipment, opportunity cost of land (rental rate), taxes and insurance and general farm overhead.";
	
	//Upland Cotton********************************************************
	$i=8;
	$oCrop[$i] = new Commodity();
	$oCrop[$i]->CName = "Upland Cotton";
	$oCrop[$i]->YieldUnit = "lbs. per acre";
	$oCrop[$i]->PriceUnit = "$ per cwt";
	$oCrop[$i]->NoUnitsUnit = "acre";
	$oCrop[$i]->UnitAdj = .01;
	$oCrop[$i]->Label = "cotton";
	$oCrop[$i]->CommCode = "12121999";
	$oCrop[$i]->Group = "crop";
	$oCrop[$i]->Code = "CTPFRM";
	//If Crop is selected
	if( isset($_POST[$oCrop[$i]->Label]) )
	{
		$oCrop[$i]->Used = $_POST[$oCrop[$i]->Label];
	}
	else{}//Do Nothing
	$oCrop[$i]->VarCostHelp  = "Variable costs include seed, fertilizer (commercial fertilizers, soil conditioners and manure), chemicals, custom operations, fuel, lube, electricity, repairs, ginning, purchased irrigation water and interest on operating capital. The default value is based on FAPRI forecasts of USDA data and is adjusted based upon the county yield to regional yield.";
	$oCrop[$i]->FixCostHelp =  "Fixed costs include hired labor, capital recovery of machinery and equipment, opportunity cost of land, taxes and insurance and general farm overhead.";
	
	//Peanuts********************************************************
	$i=9;
	$oCrop[$i] = new Commodity();
	$oCrop[$i]->CName = "Peanuts";
	$oCrop[$i]->YieldUnit = "lbs. per acre";
	$oCrop[$i]->PriceUnit = "$ per pound";
	$oCrop[$i]->NoUnitsUnit = "acre";
	$oCrop[$i]->Label = "peanuts";
	$oCrop[$i]->CommCode = "15399199";
	$oCrop[$i]->Group = "crop";
	$oCrop[$i]->Code = "PNPFRM";

	//If Crop is selected
	if( isset($_POST[$oCrop[$i]->Label]) )
	{
		$oCrop[$i]->Used = $_POST[$oCrop[$i]->Label];
	}
	else{}//Do Nothing
	$oCrop[$i]->VarCostHelp  = "Variable cost include seed, fertilizer (commercial fertilizers, soil conditioners and manure), chemicals, custom operations, fuel, lube, electricity, repairs, purchased irrigation water, hay bailing, commercial drying and interest on operating capital. The default value is based on FAPRI forecasts of USDA data and is adjusted based upon the county yield to regional yield.";
	$oCrop[$i]->FixCostHelp =  "Fixed costs include hired labor, capital recovery of machinery and equipment, opportunity cost of land, taxes and insurance and general farm overhead.";
	
	//Sunflowerseed********************************************************
	$i=10;
	$oCrop[$i] = new Commodity();
	$oCrop[$i]->CName = "Sunflowerseed";
	$oCrop[$i]->YieldUnit = "lbs. per acre";
	$oCrop[$i]->PriceUnit = "$ per pound";
	$oCrop[$i]->NoUnitsUnit = "acre";
	$oCrop[$i]->Label = "sunflowerseed";
	$oCrop[$i]->CommCode = "15831999";
	$oCrop[$i]->Group = "crop";
	$oCrop[$i]->Code = "SFPFRM";
	//If Crop is selected
	if( isset($_POST[$oCrop[$i]->Label]) )
	{
		$oCrop[$i]->Used = $_POST[$oCrop[$i]->Label];
	}
	else{}//Do Nothing
	$oCrop[$i]->VarCostHelp  ="The default value is based on FAPRI forecasts is adjusted based upon the county yield to national yield.";
	$oCrop[$i]->FixCostHelp =   "No default fixed costs for sunflowers are provided.";
	
	//Sugar Beets********************************************************
	$i=11;
	$oCrop[$i] = new Commodity();
	$oCrop[$i]->CName = "Sugar beets";
	$oCrop[$i]->YieldUnit = "tons per acre";
	$oCrop[$i]->PriceUnit = "$ per cwt";
	$oCrop[$i]->NoUnitsUnit = "acre";
	$oCrop[$i]->UnitAdj = 20;
	$oCrop[$i]->Label = "sugarbeets";
	$oCrop[$i]->CommCode = "13299199";
	$oCrop[$i]->Group = "crop";
	$oCrop[$i]->Code = "";
	//If Crop is selected
	if( isset($_POST[$oCrop[$i]->Label]) )
	{
		$oCrop[$i]->Used = $_POST[$oCrop[$i]->Label];
	}
	else{}//Do Nothing
	$oCrop[$i]->VarCostHelp  ="Variable costs include seed, fertilizer (commercial fertilizers, soil conditioners and manure), chemicals, custom operations, fuel, lube, electricity, repairs, purchased irrigation water, freight and dirt hauling, miscellaneous, hauling allowance and interest on operating capital. The default value is based on FAPRI forecasts of USDA data and is adjusted based upon the county yield to US yield.";
	$oCrop[$i]->FixCostHelp =   "Fixed costs include hired labor, capital recovery of machinery and equipment, opportunity cost of land (rental rate), taxes and insurance, general farm overhead and coop share.";
	
	//Cow/Calf********************************************************
	$i=12;
	$oCrop[$i] = new Commodity();
	$oCrop[$i]->CName = "Cow/calf";
	$oCrop[$i]->YieldUnit = "lbs./calf sold";
	$oCrop[$i]->PriceUnit = "$ per cwt";
	$oCrop[$i]->NoUnitsUnit = "cow";
	$oCrop[$i]->UnitAdj = .01;
	$oCrop[$i]->Label = "cowcalf";
	$oCrop[$i]->CommCode = "40159999";
	$oCrop[$i]->Group = "livestock";
	$oCrop[$i]->Code = "BFCT67P";
	//If Crop is selected
	if( isset($_POST[$oCrop[$i]->Label]) )
	{
		$oCrop[$i]->Used = $_POST[$oCrop[$i]->Label];
	}
	else{}//Do Nothing
	$oCrop[$i]->VarCostHelp  ="Variable costs include protein supplements, hay, pasture, veterinary and medicince, marketing, fuel, lube, electricity, machinery and building repairs, salt, miscellaneous and interest on operating costs. The default value is based on AMAP forecasts of Livestock Marketing Information Center data and is adjusted for the region.";
	$oCrop[$i]->FixCostHelp =  "Fixed costs include taxes and capital (herd) replacement.";
	
	//Dairy********************************************************
	$i=13;
	$oCrop[$i] = new Commodity();
	$oCrop[$i]->CName = "Dairy";
	$oCrop[$i]->YieldUnit = "lbs. milk/cow";
	$oCrop[$i]->PriceUnit = "$ per cwt";
	$oCrop[$i]->NoUnitsUnit = "cow";
	$oCrop[$i]->OutUnit = "cwt milk";
	$oCrop[$i]->UnitAdj = .01;
	$oCrop[$i]->Label = "dairy";
	$oCrop[$i]->CommCode = "40999199";
	$oCrop[$i]->Group = "livestock";
	$oCrop[$i]->Code = "DYMKPAMW";
	//If Crop is selected
	if( isset($_POST[$oCrop[$i]->Label]) )
	{
		$oCrop[$i]->Used = $_POST[$oCrop[$i]->Label];
	}
	else{}//Do Nothing
	$oCrop[$i]->VarCostHelp  = "Variable costs include purchased feed, homegrown harvested feed, grazed feed, veterinary and medicine, bedding and litter, marketing, custom services, fuel, lube, electricity, repairs and interest on operating capital. The default value is based on AMAP forecasts of USDA data for the region.";
	$oCrop[$i]->FixCostHelp =  "Fixed costs include hired labor, capital recovery of machinery and equipment, opportunity cost of land (rental rate), taxes and insurance and general farm overhead.";
	
	//Set Number of Years
	for ($j=0; $j<=$i; $j++)
	{
		$oCrop[$j]->SetYears($yEnd-$yStart+1);
	}

	/*********************************************************************************************************
	'necessary functions
	'*********************************************************************************************************/
	function iif($condition, $return_true, $return_false)
	{
		if($condition)
		{
			return $return_true;
		}
		else
		{
			return $return_false;
		}
	}
	
	function LookupComm($lbl, $oCrop)
	{
		for ($j=0; $j<=count($oCrop)-1;$j++)
		{
			if ($oCrop[$j]->Label==$lbl)
			{
				break;
			}
		}
		$return_value = iif($oCrop[$j]->Label==$lbl,$j,"error");
		return $return_value;
	}
	
	function FormatNum($num, $dec)
	{
		if ($num)
		{
			$result=number_format($num, $dec);
		}
		else
		{
			$result=number_format(0, $dec);
		}
		return $result;
	}
	
	function Divide($numerator, $denominator, $return_fail)
	{
		//On Error Resume Next will be dealt with later when I find where Divide is called. (@)
		$result = $numerator/$denominator;
		/*
		if ($Err->Number != 0)
		{
			return $return_fail;
		}
		*/
		return $result;
	}
	
?>