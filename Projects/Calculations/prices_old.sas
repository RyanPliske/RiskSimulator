data temp1; set temp;
	if Commodity_Code=12121999 then Price_per_unit = Price_per_unit*100;
run;

proc sort data=temp1 out=temp2; by StFips Commodity_Code Year; run;

proc transpose data=temp2 out=temp3; 
	id year;
	by StFips Commodity_Code;
	var Price_per_unit;
run;

data temp4; set temp3;
	if max(of _2009-_2011) = . then delete;
run;
