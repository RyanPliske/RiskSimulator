// JavaScript Document

function ajaxFunction(callback)
{ 

var xmlHttp;
var temp = document.forms.frm1.cboCt;
try
  {
  // Firefox, Opera 8.0+, Safari
  xmlHttp=new XMLHttpRequest();
  }
catch (e)
  {
  // Internet Explorer
  try
    {
    xmlHttp=new ActiveXObject("Msxml2.XMLHTTP");
    }
  catch (e)
    {
    try
      {
      xmlHttp=new ActiveXObject("Microsoft.XMLHTTP");
      }
    catch (e)
      {
      alert("Your browser does not support AJAX!");
      return false;
      }
    }
  }
  /*Once user has selected a State...*/
  xmlHttp.onreadystatechange=function()
    {
    if(xmlHttp.readyState==4) {
		temp.disabled=false;
		if (navigator.appName != "Microsoft Internet Explorer")  /*IE has bug that won't read innerHTML*/
			temp.innerHTML=xmlHttp.responseText;
		else temp.outerHTML=temp.outerHTML.substr(0,temp.outerHTML.indexOf('>')+1) + xmlHttp.responseText + "</select>";
				callback(5);
		}
	}

	xmlHttp.open("GET","counties.php?cnty=" + document.forms.frm1.cboSt.value,true);
	//xml not 100% working, works for now but if it breaks, will need to request Header 6/3/2014
	xmlHttp.send(null);
}


function fnValidation() {
	
  var frm = document.forms.frm1;
  var txt = "";
	  
  if (frm.cboSt.value == 0) txt += "No state selected.\n";
  if (frm.cboCt.value == 0) txt += "No county selected.\n";
  
  //For each checkbox see if it has been checked, record the value.
  chk=0;
  for (i = 0; i < frm.chkComm.length; i++)
	if (frm.chkComm[i].checked) chk += 1;
  if (chk ==0) txt += "No crops or livestock selected";
  
  if (txt!="") alert(txt);
  else 
  	if (document.getElementById('firstrun')) 
  		fnGetInputForms();
  	else 
		if (confirm('This will delete current input.'))
  			fnGetInputForms();

}

function fnGetInputForms(callback) {

document.getElementById('inputform').innerHTML = "<center><h1>Input data</h1><br/><p><i>Loading...</i></p><br/><img src='help/ajax-loader.gif' alt='Searching'/></center>";

var xmlHttp;
var frm = document.forms.frm1;

//make parameter list
params="fips=" + frm.cboCt.value + "&";
for (i=0;i<frm.chkComm.length;i++)
	params += frm.chkComm[i].value + "=" + frm.chkComm[i].checked + "&";
params = params.substr(0,params.length-1); //Get rid of last "&"

try
  {
  // Firefox, Opera 8.0+, Safari
  xmlHttp=new XMLHttpRequest();
  }
catch (e)
  {
  // Internet Explorer
  try
    {
    xmlHttp=new ActiveXObject("Msxml2.XMLHTTP");
    }
  catch (e)
    {
    try
      {
      xmlHttp=new ActiveXObject("Microsoft.XMLHTTP");
      }
    catch (e)
      {
      alert("Your browser does not support AJAX!");
      return false;
      }
    }
  }
  	xmlHttp.open("POST","input.php",true);
  
  //Send the proper header information along with the request
	xmlHttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	xmlHttp.setRequestHeader("Content-length", params.length);
	xmlHttp.setRequestHeader("Connection", "close");
  
  xmlHttp.onreadystatechange=function()
    {
    if(xmlHttp.readyState==4) {
		parseScript(document.getElementById('inputform'), xmlHttp.responseText);
				callback(5);
		}
	}
			
  xmlHttp.send(params);

}

function parseScript(strCode) {

	var scripts = new Array();         // Array which will store the script's code
	  
	  // Strip out tags
	while(strcode.indexOf("<script") > -1 || strcode.indexOf("</script") > -1) {
		var s = strcode.indexOf("<script");
		var s_e = strcode.indexOf(">", s);
		var e = strcode.indexOf("</script", s);
		var e_e = strcode.indexOf(">", e);
		
		// Add to scripts array
		scripts.push(strcode.substring(s_e+1, e));
		// Strip from strcode
		strcode = strcode.substring(0, s) + strcode.substring(e_e+1);
	}
	
  
	for(var i=0; i<scripts.length; i++) {
		try {
			if (navigator.appName != "Microsoft Internet Explorer") eval(scripts[i].text);
			else window.execScript(scripts[i].text);
		}
		catch(ex) {
			// do what you want here when a script fails
			alert('script failed');
			alert(scripts[i].text);
		}
	}
		
}

function parseScript(obj, newHTML) {

		obj.innerHTML = newHTML;  
		var x = obj.getElementsByTagName("script");  

		// Loop through every script collected and eval it
		for(var i=0; i<x.length; i++) {
			try {
				if (navigator.appName != "Microsoft Internet Explorer") eval(x[i].text);
				else window.execScript(x[i].text);
			}
			catch(ex) {
				// do what you want here when a script fails
				alert('script failed');
				alert(x[i].text);
			}
		}
		
}
 
function resizetable(tblId) { 

  // First we will select the table... 
  thetable = document.getElementById(tblId); 
  // ...and count how many columns it has 

  numberOfcolumns = thetable.firstChild.firstChild.getElementsByTagName('TD').length;

    // So we need to find out how wide the widest column is. 
    widestColumn = 0; 
    for(i = 0;i < numberOfcolumns; i++) { 
      // Measure each column... 
	  thisColumnwidth = thetable.firstChild.firstChild.getElementsByTagName('TD').item(i).clientWidth; 
      if(thisColumnwidth > widestColumn) { 
        widestColumn = thisColumnwidth; 
      }
    }
    for(i = 0;i < numberOfcolumns; i++) { 
      // Apply the widest column width to all columns 
      thetable.firstChild.firstChild.getElementsByTagName('TD').item(i).style.width = widestColumn + "px"; 
    } 
  
} 

function onEnter(obj) {
//set class while input box has focus

	var current_class = obj.className;
	obj.className = current_class+'foc';
	obj.select();

}

function isBlank(id,cls) {
//change style of blank cell

	var cell = document.getElementById(id);
	
	if (cell.value=='') cell.className='notext';
	else cell.className=cls;
	
	return cell.value == '';
	
}


function formatNum(RawNum, dec) {

	var result1 = '';
	var result2= '';
	RawNum=Math.round(RawNum*Math.pow(10,dec)).toString();
	if (isNaN(RawNum)) { //make sure input is number
		RawNum='0';
		alert('Not a valid number.');
	}

	var RawStr1 = RawNum.substr(0,RawNum.length-dec);
	var RawStr2 = RawNum.substr(RawNum.length-dec, RawNum.length);
	
	//add commas to numbers left of decimal
	for (var i = RawStr1.length-1; i>=0; i--) {
		result1 = RawStr1.charAt(i) + (((RawStr1.length-i-1)%3==0 && (RawStr1.length-i-1)!=0) ? ',': '') + result1;
	}
	if (result1.length==0) result1=0;
	
	//get decimals
	if (RawStr2!='') 
	{
		if (RawNum.length != 1) //For Special case where Decimal is less than .10 
			result2 = '.' + RawStr2.toString();
		else
			result2 = '.0' + RawStr2.toString();
		if (result2.length<dec+1)
			for (i=0; i<dec+1-result2.length; i++)
				result2 += '0';
	}

	return result1 + result2;

}

function OnLeave(id,dec, cls) {
	
		if (!cls) cls='text';
		var cell = document.getElementById(id);
		var tval = cell.value.replace(',','');
		
		try {tval = eval(tval);} //calc cell
		catch(e) {}
		
		if (!isBlank(id,cls)) cell.value=formatNum(parseFloat(tval),dec);
	
}

function checkState() {
	if (document.forms.frm1.cboSt.value*1 != 0) ajaxFunction();	
}

function OrigVars () {
    this.name = new Array();
	this.value = new Array();
    this.lookupValue = function(name) {
        for (var i=0; i<this.name.length; i++)
			if (this.name[i]==name)
				return this.value[i];
    };
}

function fireOnblur(objID) {
	//function to call onblur;
	var target=document.getElementById(objID);
	if(document.dispatchEvent) { // W3C
		var oEvent = document.createEvent( "MouseEvents" );
		oEvent.initMouseEvent("blur", true, true,window, 1, 1, 1, 1, 1, false, false, false, false, 0, target);
		target.dispatchEvent( oEvent );
		}
	else if(document.fireEvent) { // IE
		target.fireEvent("onblur");
		}    
}

function checkInput() {
//recall input

	//see if state is selected
	//checkState();

	//we don't need to do the rest for firefox
	//if (navigator.appName=='Netscape') return;
	
	var xmlHttp;
	try
	  {
	  // Firefox, Opera 8.0+, Safari
	  xmlHttp=new XMLHttpRequest();
	  }
	catch (e)
	  {
	  // Internet Explorer
	  try
		{
		xmlHttp=new ActiveXObject("Msxml2.XMLHTTP");
		}
	  catch (e)
		{
		try
		  {
		  xmlHttp=new ActiveXObject("Microsoft.XMLHTTP");
		  }
		catch (e)
		  {
		  alert("Your browser does not support AJAX!");
		  return false;
		  }
		}
	  }
	  
	xmlHttp.open("POST","recall_input.php",true);
	
	  xmlHttp.onreadystatechange=function()
		{
		if(xmlHttp.readyState==4) {
			//alert(xmlHttp.responseText);
			if (xmlHttp.responseText!='') {
				if (displayOption() ) {
				//document.getElementById('inputform').innerHTML=xmlHttp.responseText;
				//parseScript(document.body, xmlHttp.responseText);
				//document.getElementById('inputform').innerHTML=xmlHttp.responseText.replace(/</g,"&lt;");
				//parseScript(xmlHttp.responseText);
				try {
					var origVars = new OrigVars();
					//if (navigator.appName != "Microsoft Internet Explorer") eval(xmlHttp.responseText);
					//else window.execScript(xmlHttp.responseText);		
					eval(xmlHttp.responseText);	
					var frm = document.forms.frm1;
					var fips = origVars.lookupValue('fips');
					frm.cboSt.value=fips.substr(0,2); //state
					ajaxFunction( //this forces the county call to wait until the counties are updated
						function (num) {
							document.forms.frm1.cboCt.value=fips; //county
							  //For each checkbox see if it has been checked, record the value.
							  for (i = 0; i < frm.chkComm.length; i++) {
								if (origVars.lookupValue(frm.chkComm[i].value)=='true') frm.chkComm[i].checked=true;
								else frm.chkComm[i].checked=false;
							  }
							
							//get the input forms
							fnGetInputForms(
								function (num) {
							
								  //set the values of the input boxes
								  for (i = 0; i<origVars.name.length; i++) {
									  if (document.getElementsByName(origVars.name[i]).item(0).getAttribute('type')=='text') {
										  document.getElementsByName(origVars.name[i]).item(0).value=origVars.value[i];
										  fireOnblur(origVars.name[i]);
									  }
								  }
								  
								}
							);
								
						}
							   
					);

	 				

				}
				catch (e) {
					alert('Reloading Data has failed');
				}
			}
			}
		}
	}
	  
	  xmlHttp.send(null);

}

function timeInput() {
	if (document.getElementById('firstrun')) window.setTimeout("checkInput()", 5);	
}

function SubCost(id) {
//function to calculate total variable costs

	OnLeave(id,2,'subtext');
	var TotVarCostId = id.substring(0,id.indexOf("_",id.indexOf("_")+1));
	var i = 1;
	var hasBlank=false;

	var result = 0;
	do {
		if (document.getElementById(TotVarCostId+'_'+i).value=='') hasBlank=true;
		result += document.getElementById(TotVarCostId+'_'+i).value*1;
		i++;
	} while (document.getElementById(TotVarCostId+'_'+i));
	if (hasBlank) result='';
	document.getElementById(TotVarCostId).value=result;
	
	//format cell
	OnLeave(TotVarCostId,2);
}

function TotCost(id) {
//function to calculate individual variable costs from total

	var NewCost = document.getElementById(id).value*1;
	var TotVarCostId = id.substring(0,id.indexOf("_",id.indexOf("_")+1));
	var i = 1;
	var hasBlank=false;

	//format cell
	OnLeave(id,2);

	//get old total variable costs
	var OldCost = 0;
	while (document.getElementById(id+'_'+i)) {
		OldCost += document.getElementById(id+'_'+i).value*1;
		if (document.getElementById(id).value=='') hasBlank=true;
		i++;
	}
	if (OldCost==0) OldCostF=i-1;
	else OldCostF=OldCost;

	//enter new variable costs
	i=1;
	while (document.getElementById(id+'_'+i)) {
		document.getElementById(id+'_'+i).value = (OldCost!=0) ? document.getElementById(id+'_'+i).value * NewCost / OldCostF : 1 * NewCost / OldCostF;
		if (hasBlank) document.getElementById(id+'_'+i).value='';
		OnLeave(id+'_'+i,2,'subtext');
		i++;
	}
	
}

function ShowHideCost(id, bol) {
//show or hide sub costs

	var obj = document.getElementById(id);

	if (!bol)
		if (obj.style.display!="none")
			obj.style.display="none";
		else
			obj.style.display="";
	else obj.style.display="none";
		
}

function ShowHideDraws(id, bol) {
//show or hide Stochastic Draws
	ShowHideCost(id, true);
	//ShowHideCost(id, bol);
}

function ResizeFirstCol(id) { 

  // Get the group
  var thetable = document.getElementById(id).parentNode; 
  var colmin='320px'; //minimum width for first column;

  //var thecol = thetable.childNodes.item(1).childNodes.item(2).getElementsByTagName('TD').item(0);
  //if (navigator.appName == "Microsoft Internet Explorer") var thecol = thetable.firstChild.getElementsByTagName('TD').item(1);
  if (navigator.appName == "Microsoft Internet Explorer") var thecol = thetable.getElementsByTagName('tr').item(1).getElementsByTagName('td').item(0);
  else var thecol = thetable.childNodes.item(1).childNodes.item(2).getElementsByTagName('TD').item(0);

  if (!thecol.style.width) thecol.style.width=thecol.clientWidth+'px'; //set width if there is none
  if (thecol.scrollWidth>thecol.style.width) thecol.style.width=thecol.clientWidth;
  if (thecol.style.width<colmin) thecol.style.width=colmin;

	ShowHideCost(id,true);
} 

function fnHelp(sbj) {
	
	var str = '';
	switch(sbj) {
		case 'OtherRev' :
			str = 'OtherRev.php';
			break;
		case 'VarCost' :
			str = 'VarCost.php';
			break;
		case 'FixCost' :
			str = 'FixCost.php';
			break;
		case 'Draws' :
			str = 'Draws.php';
			break;
		default :
			str = 'Incorrect category';
	}

	//get temporary form parameters
	var myfrm = document.forms.frm2;
	var old_action = myfrm.action;
	var new_action = "/Projects/Help/" + str;

	//open window
	window.open("", "popup", "width=500,height=400,scrollbars,location=no");
	
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

var check=0;

window.onload = function() {
	if (window.location.pathname.indexOf('budget.php')!=-1) {
		resizetable('tbl1'); 
		checkState();
		timeInput();
		//checkInput();
	}
}

window.onfocus = function() {
	if (check==0 && window.location.pathname.indexOf('budget.php')!=-1) {
		check=1;
		//checkInput();
		//timeInput();
	}
}

function displayOption() 
{
    if (confirm("Welcome Back! Would you like to load data from your previous session?") == true) 
	{
        return true;
    } else 
	{
       return false;
    }
}

function showLoading() {
    document.getElementById('loadingmsg').style.display = 'block';
}