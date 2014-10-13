dm 'log;clear;output;clear;';

PROC IMPORT OUT= WORK.temp 
            DATAFILE= "D:\fapri_site\Projects\Calculations\raw_prices.
xlsb" 
            DBMS=EXCELCS REPLACE;
     RANGE="sheet1$"; 
     SCANTEXT=YES;
     USEDATE=YES;
     SCANTIME=YES;
RUN;

data temp1; set temp;

	if Commodity="COTTON" then value = value*100;

	if commodity="CORN" THEN commcode=11199199;
	if commodity="SOYBEANS" THEN commcode=15499199;
	if commodity="SORGHUM" THEN commcode=11499199;
	if commodity="BARLEY" THEN commcode=11399999;
	if commodity="OATS" THEN commcode=11299999;
	if commodity="HAY" THEN commcode=18999999;
	if commodity="RICE" THEN commcode=10619999;
	if commodity="COTTON" THEN commcode=12121999;
	if commodity="PEANUTS" THEN commcode=15399199;
	if commodity="SUNFLOWER" THEN commcode=15831999;
	if commodity="SUGARBEETS" THEN commcode=13299199;
	if commodity="WHEAT" THEN commcode=10199999;

	if State_ANSI = . then delete;

	rename State_ANSI=StFips;

	keep year state State_ANSI commodity CommCode value;
run;

proc sort data=temp1 out=temp2; by StFips CommCode Year; run;

proc transpose data=temp2 out=temp3; 
	id year;
	by StFips CommCode;
	var Value;
run;

data temp4; set temp3;
	
	drop _name_ _label_;

	if max(of _2011-_2013) = . then delete;
run;
