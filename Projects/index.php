<?php
	if (!isset($_SESSION))
	{
		session_start();
		$_SESSION['current_page']= 'projects_index'; //Using SESSIONS, setting the current page into global variable
	}
	echo '<html>';
    include "../head_tag.php"; //Checks for Flash and sets up Header
	echo '<body><center>';
	include "../header.php"; //Header of every page
?>
			<p>
			<table width=800 border=0 cellspacing=0 cellpadding=0>
				<tr>
					<td width=8 valign="top"><img src="/images/spacers_curves/left_top_curve.gif"></td>
					<td width=12 rowspan=3 valign="top">&nbsp;</td>
					<td width=784 rowspan=3 align="left" valign="top">
					</br>
					<p><center><font class="title">Beginning Farmer &amp; Rancher<br>
                    <br>Farm Cost and Return Tool (CART)</font></center>
	<br>

  <p>Welcome to the FAPRI Beginning Farmer and Rancher online Farm Cost and Return Tool (CART) page. This tool, developed as part  of the beginning farmer and rancher project, was created to assist beginning farmers and ranchers.	It can be used by those who are either thinking about farming or are actively engaged in a farming operation.</p>

<p>The tool includes data from the FAPRI baseline and the USDA. It includes a specified set of  commodities (see below), historical production and costs, and five years of  projected production and costs estimates. The user can select the commodities on the farm, enter the number of  acres farmed for each crop selected and number of head of livestock, and edit  yields, prices and costs. Once the farm  is constructed and the data entered, a five-year estimate of costs and returns  are available to the user. This allows  the user to estimate the profitability of different farming operations  online. These are just ESTIMATES and  there are many factors that would come into play if someone actually operated a  farm that looked like the one simulated online.</p>
  <p>The farm cost and return tool includes the following:</p>
  
  <ul>
    <li><b>Commodities</b></li>
    <ul>
      <li>Corn, Soybeans, Wheat, Sorghum, Barley, Oats,  Hay, Rice, Upland Cotton, Peanuts, Sunflower Seed, Sugar Beets, Cow/Calf, Dairy</li>
      </ul>
    <li><b>County-level yield estimates</b></li>
    <li><b>Scale of Operation</b></li>
    <ul>
      <li>Number of acres or head of livestock entered by  the user</li>
      </ul>
    <li><b>Historical and estimated future prices</b></li>
    <ul>
      <li>Historical prices from USDA and FAPRI baseline  estimates for the future</li>
      <li>Users have the ability to change</li>
      </ul>
    <li><b>Other Revenues</b></li>
    <ul>
      <li>Crops: Includes crop insurance indemnities,  government payments, sale of secondary products</li>
      <li>Livestock</li>
      <ul>
        <li>Sale of cull cattle and breeding stock</li>
        </ul>
      </ul>
    <li><b>Variable Costs</b></li>
    <ul>
      <li>FAPRI projections based on USDA data</li>
      </ul>
    <li><b>Fixed Costs</b></li>
    <ul>
      <li>FAPRI projections based on USDA data</li>
      </ul>
  </ul>

  <p>After defining the structure of the farm, the user can look  at estimated returns over the next five years for the whole farm or by  commodity.</p>
  
  &nbsp;<br>
  <center><input type="button" value="Enter tool" onClick="javascript:parent.location='budget.php'"></center>

	<center>
  <br><p>For a more detailed crop budgeting tool, see the <a href="/farmers_corner/tools/index.asp?current_page=farmers_corner">Crop Budget Generator</a>.</p>
  
 <!-- <p>For questions or help concerning the CART Tool, please email the <a href="mailto:umcfapriwebmaster@missouri.edu">FAPRI Web Administrator</a>.</p>-->
 
  &nbsp;<i><b>Updated March 2014 with March 2014 baseline</b></i></center>
  
  &nbsp;<br>

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
			require '../footer.php';
			?>
	</center>
	</body>
	
</html>

