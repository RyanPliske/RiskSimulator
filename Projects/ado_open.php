<?php
$servername = "localhost";
$username = "root";
$password = "fapri";

// Creating a Connection to msql
$db = mysqli_connect($servername, $username, $password, "movedb");

// checks connection
if($db -> connect_error){
	die("Connection failed: " . $db->connect_error);
}

echo "Connected successfully <br>";
?>