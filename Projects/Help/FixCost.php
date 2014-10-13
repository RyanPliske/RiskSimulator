<?php
//Get years
$yStart=$_POST["yStart"];
$yEnd=$_POST["yEnd"];
//Declarations utilizes years
include('../declarations.php');
?>
<html>
<head>
<title>Fixed costs</title>
<link rel="stylesheet" type="text/css" href="help.css" />
</head>

<body>

<center><h1>Fixed costs</h1></center>

<?php
	for($i=0; $i<=count($oCrop)-1; $i++)
	{
		if ($oCrop[$i]->Used == 'true')
		{
			echo "\r\n<p><h2>".$oCrop[$i]->CName."</h2>";
			echo "\r\n".$oCrop[$i]->FixCostHelp;
		}
	}
?>

<p>&nbsp;
<img align="right" src="logo.png" alt="FAPRI logo">
<p><input type="button" value="Close" onClick="javascript:window.close();" />

</body>
</html>
