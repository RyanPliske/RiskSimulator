// JavaScript Document

function ResizeAllTables() {

	var x = document.getElementsByTagName("table");  
	var maxtable=0; var temp=0; var maxfirst=0;

	//get widest width
  for ( var i = 2; i < x.length-2; i++) {
	temp = gettablesize(x.item(i));
	maxtable = Math.max(maxtable,temp[1]);
	maxfirst = Math.max(temp[0],maxfirst);
  }

	for ( i = 2; i < x.length-2; i++)
		sizetables(x.item(i),maxfirst,maxtable);

	HideCosts_r1();
	HideCosts_r2();
	HideCosts_r3();

}

function gettablesize(tblId) { 

  // First we will select the table... 
  var thetable = tblId; 
	var therow = thetable.getElementsByTagName("tr").item(0);
	

  // ...and count how many columns it has 
  var numberOfcolumns = therow.getElementsByTagName('TD').length;

    // So we need to find out how wide the widest column is. skip first column
    widestColumn = 0; 
    for(i = 1; i < numberOfcolumns; i++) { 
      // Measure each column... 
	  thisColumnwidth = therow.getElementsByTagName('TD').item(i).clientWidth; 
      if(thisColumnwidth > widestColumn) { 
        widestColumn = thisColumnwidth; 
      }
    } 
	
	var firstColumn = therow.getElementsByTagName('TD').item(0).clientWidth;
    
	return [firstColumn, widestColumn];
  
} 

function sizetables(tblId, firstColumn, widestColumn) {
	
	// First we will select the table... 
  var thetable = tblId; 
  	var therow = thetable.getElementsByTagName("tr").item(0);

  // ...and count how many columns it has 
  var numberOfcolumns = therow.getElementsByTagName('TD').length;
	
	therow.getElementsByTagName('TD').item(0).style.width = firstColumn + "px"; 
	for(i = 1;i < numberOfcolumns; i++) { 
      // Apply the widest column width to all columns 
      therow.getElementsByTagName('TD').item(i).style.width = widestColumn + "px"; 
    } 
	
}

function parseScriptResult(obj) {

		var x = obj.getElementsByTagName("script");  

		// Loop through every script collected and eval it
		for(var i=0; i<x.length; i++) {
			try {
				eval(x[i].text);
			}
			catch(ex) {
				// do what you want here when a script fails
				alert('script failed');
				alert(x[i].text);
			}
		}
		
}

function BudgetType() {

	var frm = document.forms.frm2;

	for(var i=0; i<frm.cboBudget.options.length; i++) {
		if (frm.cboBudget.options[i].value==frm.cboBudget.value) {
			document.getElementById(frm.cboBudget.options[i].value).style.display='block';
		}
		else {
			document.getElementById(frm.cboBudget.options[i].value).style.display="none";
		}
	}
	
}

function printSpecial(id,txt)
{
	if (document.getElementById != null)
	{
		var html = '<HTML>\n<HEAD>\n';
		
		html += '\n\t<meta http-equiv="Content-Type" content="text/html; charset=utf-8">';
		html += '\n\t<script src="results.js" type="text/javascript"></script>';
		html += '\n\t<script language="javascript" src="budget.js" type="text/javascript"></script>';
		html += '\n\t<link href="projects.css" rel="stylesheet" type="text/css">';
		html += '\n\t<link href="print.css" rel="stylesheet" type="text/css" media="print" >';
		html += '\n\t<link href="Help/help.css" rel="stylesheet" type="text/css">';
		
		html += '\n</HEAD>\n<body>';
		html += '<form><center><input type="button" value="Print this page" onClick="window.print()"></center></form>';
		
		html += '<div class="print"><center>'+txt+'</center><br>';
			
		var printReadyElem = document.getElementById(id);
		
		if (printReadyElem != null)
		{
			html += printReadyElem.innerHTML;
		}
		else
		{
			alert("Could not find the printReady function");
			return;
		}
		
		html += '\n</div></BODY>\n</HTML>';

		//var printWin = window.open("","_blank", "height=700,width=900"); //Open in New Window
		var printWin = window.open("","_blank"); //Open in New Tab
		printWin.document.open(); //Create new object in new windows
		printWin.document.write(html); //write to that new object
		printWin.document.close(); //Close that object
		printWin.focus(); //Focus window (for IE and browsers like it)
		
		/*if (gAutoPrint)
		{
			printWin.print();
		
		}*/
		
		
	}
	else
	{
		alert("The print ready feature is only available if you are using an browser. Please update your browser.");
	}
}

function fnHelps() {
	
	var str = 'print.php';

	//get temporary form parameters
	var myfrm = document.forms.frm2;
	var old_action = myfrm.action;
	var new_action = "/Projects/Help/" + str;

	//open window
	window.open("", "popup", "width=750,height=700,scrollbars,location=no");
	
	//set form target to window
	myfrm.target = "popup";
	
	//set the form action
	myfrm.action = new_action;
	
	//submit form
	myfrm.submit();
	
	//reset old parameters
	myfrm.action = old_action;
	myfrm.target = "";
}