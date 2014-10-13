<html>
<head>
<title>Print Help</title>
<link rel="stylesheet" type="text/css" href="help.css" />
</head>

<body> 

<?php
	echo "<center><h1>Save to File</h1><i>Saving to PDF (or another filetype), will depend upon your web browser.</i></center>";
	echo "\n\t<h4>Internet Explorer: ";
	if ( strpos($_SERVER['HTTP_USER_AGENT'], "MSIE") )
		echo "\r<font color=purple>(You)</font>";
	echo '</h4><i>I. Click on the "Print preview" link. <br/>II. Click "Print this page" button <br/>III. Check the "Print to file" checkbox <br/>IV. Click "Print".</i>';
	echo '<h4>Google Chrome: ';
	if ( strpos($_SERVER['HTTP_USER_AGENT'], "Chrome") )
		echo "\r<font color=purple>(You)</font>";
	echo '</h4><i>I. Click on the "Print preview" link. <br/>II. Click "Print this page" button <br/>III. Located underneath Destination, Click "Change..." button<br/>IV. Click "Save as PDF"<br/>V. Click "Save"</i>';
	echo '<h4>Firefox: ';
if ( strpos($_SERVER['HTTP_USER_AGENT'], "Firefox") )
		echo "\r<font color=purple>(You)</font>";
	echo '</h4>Not Supported.';

?>
<p>&nbsp;
<img align="right" src="logo.png" alt="FAPRI logo">
<p><input type="button" value="Close" onClick="javascript:window.close();" />

</body>
</html>