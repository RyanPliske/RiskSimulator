<?php
	if (!isset($_SESSION))
	{
		session_start();
		$_SESSION['current_page']= 'contact_us'; //Using SESSIONS, setting the current page into global variable
	}
	echo '<html>';
    require "head_tag.php"; //Checks for Flash and sets up Header
	echo '<body><center>';
	require "header.php"; //Header of every page
?>
			<p>
			<table width=800 border=0 cellspacing=0 cellpadding=0>
				<tr>
					<td width=8 valign="top"><img src="/images/spacers_curves/left_top_curve.gif"></td>
					<td width=12 rowspan=3 valign="top">&nbsp;</td>
					<td width=784 rowspan=3 align="left" valign="top">
						<p><center><font class="title">Contact Information</font></center>

						<p><font class="title">T</font>o learn more about FAPRI, contact either the specific person(s) 
						who might be best able to address your interests ( <a href="/about_fapri/staff_directory.asp?current_page=about_fapri" target="_blank">Staff Directory</a> ) or use our generic 
						address and your questions will be sent to the appropriate person(s):

						<p><font class="subtitle2">Food and Agricultural Policy Research Institute</font>
						<br>101 Park DeVille Drive, Suite E
						<br>Columbia, Missouri 65203
						<br>Phone: (573) 882-3576
						<br>Fax: (573) 884-4688
						
						<p><font class="subtitle2">Web Site Questions or Comments </font>
						<br>FAPRI Web Administrator
						<br>101 Park DeVille Drive, Suite E
						<br>Columbia, Missouri 65203
						<br>Phone: (573) 882-3576
						<br>E-mail: <a href="mailto:umcfapriwebmaster@missouri.edu">umcfapriwebmaster@missouri.edu</a>
					</td>
					<td width=12 rowspan=3 valign="top">&nbsp;</td>
					<td width=8 valign="top"><img src="/images/spacers_curves/right_top_curve.gif"></td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td valign="bottom"><img src="/images/spacers_curves/left_bottom_curve.gif"></td>
					<td valign="bottom"><img src="/images/spacers_curves/right_bottom_curve.gif"></td>
				</tr>
			</table>
			<p>
			<?php
			require 'footer.php';
			?>
		</center>
	</body>
	
</html>