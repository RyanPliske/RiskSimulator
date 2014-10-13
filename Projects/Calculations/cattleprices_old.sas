proc sort data=temp out=temp2; by StFips Commodity_Code Year; run;

proc transpose data=temp2 out=temp3; 
	id year;
	by StFips Commodity_Code;
	var Price;
run;

data temp4; set temp3;
	if max(of _2008-_2010) = . then delete;
run;
