<?php
		/******************Possible Alternative Ways to Connect to the Db (These aren't quite working)**********************************************
	//A) Connect to MS Access Database USING DSN: ODBC connection (name of DSN, username, password)
	//$conn = odbc_connect('budget', '', '');
	
	//B) Connect to MS Access DSN-less
	//$conn = odbc_connect("Driver={Microsoft Access Driver (*.mdb, *.accdb)};Dbq=budget", 'root', '') or die('Connection to MS Access Failed: '.odbc_errormsg() );
	/***********************************************************************************************************************************************************/
	//C) Connect to MS Access DSN-less via adodb5 (ActiveX Data Object Database 5)
	include('../adodb5/adodb.inc.php'); //First include adodb5 to Configure the Database
	$db = ADONewConnection('access'); //Create new Microsoft Access Connection using adodb5
	//$db->debug = true; //Used for Debugging Issues
    $dsn = "Driver={Microsoft Access Driver (*.mdb, *.accdb)};Dbq=".realpath("budget.accdb").";"; //Realpath dynamical loads the entire path for the MS Access file
	$db->Connect($dsn); //connect to the file
?>