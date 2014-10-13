<?php
	if (!session_start())
	{
		session_start();
	}
	if ($_SESSION['forms']!="")
	{
		echo $_SESSION['forms'];
		$_SESSION['forms'] = ''; //Enable this to clear on refresh, however multiple f/b will lose data *ENABLED*
	}
?>
