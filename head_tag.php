<head>
	<title>FAPRI - Food and Agricultural Policy Research Institute</title>
	<link rel=stylesheet type="text/css" href="fapri_site.css">
	
	<script language="JavaScript1.2" src="../coolmenus4.js">
		/*****************************************************************************
		Copyright (c) 2001 Thomas Brattli (webmaster@dhtmlcentral.com)
		
		DHTML coolMenus - Get it at coolmenus.dhtmlcentral.com
		Version 4.0_beta
		This script can be used freely as long as all copyright messages are
		intact.
		
		Extra info - Coolmenus reference/help - Extra links to help files **** 
		CSS help: http://coolmenus.dhtmlcentral.com/projects/coolmenus/reference.asp?m=37
		General: http://coolmenus.dhtmlcentral.com/reference.asp?m=35
		Menu properties: http://coolmenus.dhtmlcentral.com/properties.asp?m=47
		Level properties: http://coolmenus.dhtmlcentral.com/properties.asp?m=48
		Background bar properties: http://coolmenus.dhtmlcentral.com/properties.asp?m=49
		Item properties: http://coolmenus.dhtmlcentral.com/properties.asp?m=50
		******************************************************************************/
	</script>

	<script language="JavaScript" src="../get_vars.js">
	</script>

<script type="text/javascript">
function MM_CheckFlashVersion(reqVerStr,msg){
  with(navigator){
    var isIE  = (appVersion.indexOf("MSIE") != -1 && userAgent.indexOf("Opera") == -1);
    var isWin = (appVersion.toLowerCase().indexOf("win") != -1);
    if (!isIE || !isWin){  
      var flashVer = -1;
      if (plugins && plugins.length > 0){
        var desc = plugins["Shockwave Flash"] ? plugins["Shockwave Flash"].description : "";
        desc = plugins["Shockwave Flash 2.0"] ? plugins["Shockwave Flash 2.0"].description : desc;
        if (desc == "") flashVer = -1;
        else{
          var descArr = desc.split(" ");
          var tempArrMajor = descArr[2].split(".");
          var verMajor = tempArrMajor[0];
          var tempArrMinor = (descArr[3] != "") ? descArr[3].split("r") : descArr[4].split("r");
          var verMinor = (tempArrMinor[1] > 0) ? tempArrMinor[1] : 0;
          flashVer =  parseFloat(verMajor + "." + verMinor);
        }
      }
      // WebTV has Flash Player 4 or lower -- too low for video
      else if (userAgent.toLowerCase().indexOf("webtv") != -1) flashVer = 4.0;

      var verArr = reqVerStr.split(",");
      var reqVer = parseFloat(verArr[0] + "." + verArr[2]);
  
      if (flashVer < reqVer){
        if (confirm(msg))
          window.location = "http://www.adobe.com/shockwave/download/download.cgi?P1_Prod_Version=ShockwaveFlash";
      }
    }
  } 
}
</script>

<script src="../scripts/AC_RunActiveContent.js" type="text/javascript"></script>


</head>
