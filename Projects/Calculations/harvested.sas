dm 'log;clear;output;clear;';
*temp is all data, wheat is wheat by type;

****************************************************;
*wheat;
****************************************************;
*sum wheat by categories;
proc sql; create table wheat0 as select Year, State, County, StFips, CoFips, District, Sum(Planted_All_Purposes) as Planted_All_Purposes, Sum(Harvested) as Harvested, 
	Sum(Production) as Production, FIPS
	from wheat
	where (Commodity = 'Wheat Durum' or Commodity = 'Wheat Other Spring' or Commodity = 'Wheat Winter All')
	Group By FIPS, Year, State, County, StFips, CoFips, District;
run;

*add some labels for the wheat by categories;
data wheat0; set wheat0;
	Commodity = 'Wheat All';
	Planted_unit = 'acres';
	Harvested_unit = 'acres';
	Yield_unit = 'bushel';
	Production_unit = 'bushel';
	Yield=Production/Harvested;
	CommCode=10119999;
run;

*add the all wheat to the wheat sum dataset;
proc sort data=wheat0; by CommCode FIPS Year; run;
proc sort data=temp; by CommCode FIPS Year; run;
data temp0; merge temp wheat0; by CommCode FIPS Year;
run;

***************************************************;

data temp1; set temp0;
	if CoFips = 999 or CoFips = 888 then delete;
run;

proc sort data=temp1 out=temp2; by Fips CommCode Year; run;

proc transpose data=temp2 out=temp3; 
	id year;
	by Fips CommCode;
	var Harvested;
run;

data temp4; set temp3;
	if max(of _1982-_2011) = . then delete;
run;
