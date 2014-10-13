// JavaScript Document

function ResizeAllTables() {

	var x = document.getElementsByTagName("table");  
	var maxtable=0; var temp=0; var maxfirst=0;

	//get widest width
  //Skip first table which encloses the others.
  for ( var i = 3; i < x.length-2; i++) {
	temp = gettablesize(x.item(i));
	maxtable = Math.max(maxtable,temp[1]);
	maxfirst = Math.max(temp[0],maxfirst);
  }

	for ( i = 3; i < x.length-2; i++)
		sizetables(x.item(i),maxfirst,maxtable);

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