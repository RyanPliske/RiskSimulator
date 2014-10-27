<option value="0">Select county...</option>

<?php
	include('ado_open.php');
	//Set Query
	$sql= "SELECT * FROM tblCounties WHERE left(ID,2)='".$_GET['cnty']."' AND right(ID,3)<>'000'";
	//Execute Query
//	$rs = $db->Execute($sql);
	//If query was successful
	$rs = mysqli_query($db, $sql); 
	if(!$rs){
		die("query failed: " . mysqli_connect_error());
	}
	while($row = mysqli_fetch_array($rs)) {
			echo "<option value='".$row[0]."'>".$row[1]."</option>\n\t";
	}
	/*if (isset($rs))
	{
		//PEAR style data retrieval
		while ($array = $rs->FetchRow()) //Note: (=) is NOT a Comparison Operator, this line is actually checking that a value is assigned, if not then it moves on.
		{
			echo "<option value='".$array[0]."'>".$array[1]."</option>\n\t";
		}
	}*/
	//Close connection
	$rs->Close();
	include('ado_close.php');
?>


