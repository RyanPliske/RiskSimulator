<?php
//Get years
$yStart=$_POST["yStart"];
$yEnd=$_POST["yEnd"];
//Declarations utilizes years
include('../declarations.php');
?>
<html>
<head>
<title>Other revenues</title>
<link rel="stylesheet" type="text/css" href="help.css" />
</head>

<body>

<center><h1>Other revenue</h1></center>
<i>Other revenue is any other revenue obtained from the crop or livestock that is not obtained from the primary products.</i>

<?php
	for ($i=0; $i <=count($oCrop)-1; $i++)
	{
		if ($oCrop[$i]->Used == 'true')
		{
			if ($oCrop[$i]->Group == "crop")
			{
				$cropUsed=true;
			}
			if ($oCrop[$i]->Label == 'cowcalf')
			{
				$beefUsed=true;
			}
			if ($oCrop[$i]->Label == 'dairy')
			{
				$dairyUsed=true;
			}
		}
	}
	
	if (isset($cropUsed))
	{
		echo '<p><h2>Crops</h2>
				Other revenue for crops includes crop insurance indemnities, relevant government payments, and the sale of secondary products such as straw. 
				These are set to zero by default.';
	}
	if (isset($beefUsed))
	{
		echo '<p><h2>Cow/calf</h2>
				Revenue from the sale of cull cattle and breeding stock sales.  The default value is based on forecasts of USDA data.';
	}
	if (isset($dairyUsed))
	{
		echo '<p><h2>Dairy</h2>
				Revenue from the sale of cattle.  The default value is based on forecasts of USDA data.';
	}
?>
<p>&nbsp;
<img align="right" src="logo.png" alt="FAPRI logo">
<p><input type="button" value="Close" onClick="javascript:window.close();" />

</body>
</html>
