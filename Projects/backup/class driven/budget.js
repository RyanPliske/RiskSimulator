// JavaScript Document

function ajaxFunction()
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
  xmlHttp.onreadystatechange=function()
    {
    if(xmlHttp.readyState==4) {
		temp.disabled=false;
		if (navigator.appName != "Microsoft Internet Explorer")  /*IE has bug that won't read innerHTML*/
			temp.innerHTML=xmlHttp.responseText;
		else temp.outerHTML=temp.outerHTML.substr(0,temp.outerHTML.indexOf('>')+1) + xmlHttp.responseText + "</select>";
		}
	}
			
	xmlHttp.open("GET","counties.asp?cnty=" + document.forms.frm1.cboSt.value,true);

	
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

function fnGetInputForms() {

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
  	xmlHttp.open("POST","input.asp",true);
  
  //Send the proper header information along with the request
	xmlHttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	xmlHttp.setRequestHeader("Content-length", params.length);
	xmlHttp.setRequestHeader("Connection", "close");
  
  xmlHttp.onreadystatechange=function()
    {
    if(xmlHttp.readyState==4) {
		parseScript('inputform', xmlHttp.responseText);
		}
	}
			
  xmlHttp.send(params);

}

function parseScript(divId, innerHTML) {
		/*var source = _source;
		var ascripts = new Array();
		
		// Strip out tags
		while(source.indexOf("<scrip") > -1 || source.indexOf("</scrip") > -1) {
			var s = source.indexOf("<scrip");
			var s_e = source.indexOf(">", s);
			var e = source.indexOf("</scrip", s);
			var e_e = source.indexOf(">", e);
			
			// Add to scripts array
			ascripts.push(source.substring(s_e+1, e));
			// Strip from source
			source = source.substring(0, s) + source.substring(e_e+1);
		}*/
		var div = document.getElementById(divId);  
		div.innerHTML = innerHTML;  
		var x = div.getElementsByTagName("script");  
		
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
		
		// Return the cleaned source
		//return source;
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
	  thisColumnwidth = thetable.firstChild.firstChild.getElementsByTagName('TD').item(i).scrollWidth; 
      if(thisColumnwidth > widestColumn) { 
        widestColumn = thisColumnwidth; 
      }
    } 
    for(i = 0;i < numberOfcolumns; i++) { 
      // Apply the widest column width to all columns 
      thetable.firstChild.firstChild.getElementsByTagName('TD').item(i).style.width = widestColumn + "px"; 
    } 
  
} 

function isBlank(id) {
//change style of blank cell

	var cell = document.getElementById(id);
	
	if (cell.value=='') cell.className='notext';
	else cell.className='text';
	
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
	if (RawStr2!='') {
		result2 = '.' + RawStr2.toString();
		if (result2.length<dec+1)
			for (i=0; i<dec+1-result2.length; i++)
				result2 += '0';
	}

	return result1 + result2;

}

function OnLeave(id,dec) {

		var cell = document.getElementById(id);
		var tval = cell.value.replace(',','');
		
		try {tval = eval(tval);} //calc cell
		catch(e) {}
		
		//here we will use an if statement to send the id to a function that will use ajax to send
		//the data to vbscript that will update the class and return object id's and values
		//if anything needs to be changed
		//will be of the form: if (function(id)!='') { update input boxes and tval }
		fnUpdateClass(id);
		
		if (!isBlank(id)) cell.value=formatNum(parseFloat(tval),dec);
	
}

function checkState() {
	if (document.forms.frm1.cboSt.value*1 != 0) ajaxFunction();	
}

function checkInput() {

	//see if state is selected
	checkState();
	
	//we don't need to do the rest for firefox
	if (navigator.appName=='Netscape') return;
	
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
	  
	xmlHttp.open("POST","recall_input.asp",true);
	  
	  xmlHttp.onreadystatechange=function()
		{
		if(xmlHttp.readyState==4) {
			//alert(xmlHttp.responseText);
			if (xmlHttp.responseText!='') {
				//document.getElementById('inputform').innerHTML=xmlHttp.responseText;
				document.body.innerHTML=xmlHttp.responseText;
			}
		}
	}
		
	  xmlHttp.send(null);

}

function timeInput() {
	if (document.getElementById('firstrun')) window.setTimeout("checkInput()", 250);	
}



function fnUpdateClass(id) {

var xmlHttp;

//make parameter list
params="label=" + id.substr(0,id.indexOf('_')) + "&";
params+="var=" + id.substring(id.indexOf('_')+1,id.length-4) + "&";
params+="year=" + id.substring(id.length-4,id.length);

/*
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
  	xmlHttp.open("POST","input.asp",true);
  
  //Send the proper header information along with the request
	xmlHttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	xmlHttp.setRequestHeader("Content-length", params.length);
	xmlHttp.setRequestHeader("Connection", "close");
  
  xmlHttp.onreadystatechange=function()
    {
    if(xmlHttp.readyState==4) {
		return xmlHttp.responseText;
		}
	}
			
  xmlHttp.send(params);
*/
}