dm 'log;clear;output;clear;';
*hay and wheat are by type;

*old data;
PROC IMPORT OUT= WORK.temp 
            DATAFILE= "D:\fapri_site\Projects\Calculations\raw_yields.x
lsb" 
            DBMS=EXCELCS REPLACE;
     RANGE="County1968$"; 
     SCANTEXT=YES;
     USEDATE=YES;
     SCANTIME=YES;
RUN;

data temp; set temp;
	if CoFips = 999 or CoFips = 888 then delete;
run;

****************************************************;
*wheat;
****************************************************;

PROC IMPORT OUT= WORK.WHEAT 
            DATAFILE= "D:\fapri_site\Projects\Calculations\wheat_yields.
xlsb" 
            DBMS=EXCELCS REPLACE;
     RANGE="County2632$"; 
     SCANTEXT=YES;
     USEDATE=YES;
     SCANTIME=YES;
RUN;

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
	if harvested>0 then Yield=Production/Harvested; else Yield=0;
	CommCode=10119999;
	if CoFips = 999 or CoFips = 888 then delete;
run;

*add the all wheat to the wheat sum dataset;
proc sort data=wheat0; by CommCode FIPS Year; run;
proc sort data=temp; by CommCode FIPS Year; run;
data OldData; merge temp wheat0; by CommCode FIPS Year;
run;

*new data;
PROC IMPORT OUT= WORK.RawData 
            DATAFILE= "D:\fapri_site\Projects\Calculations\data_update.x
lsx" 
            DBMS=EXCELCS REPLACE;
     RANGE="sheet1$"; 
     SCANTEXT=YES;
     USEDATE=YES;
     SCANTIME=YES;
RUN;

***********************************************************;
* import 2012 and other recent data;

data rawdata; set rawdata;
	if county_ansi=. then delete;
	rename State_ANSI=StFips
		County_ANSI=CoFips;
	if data_item='WHEAT, SPRING, (EXCL DURUM) - ACRES HARVESTED' then data_item='WHEAT, SPRING, (EXCL DURUM) - HARVESTED';
	if data_item='WHEAT, SPRING, (EXCL DURUM) - ACRES PLANTED' then data_item='WHEAT, SPRING, (EXCL DURUM) - PLANTED';
run;
proc sort data=rawdata out=rawdata; by year state county stfips cofips commodity; run;
proc transpose data=rawdata out=rawdataupdate;
	by year state county StFips CoFips commodity;
	var value;
	id data_item;
run;

data rawdataupdate; set rawdataupdate;
	array x(*) _numeric_;
	do i = 1 to dim(x);
		if x(i) = . then x(i)=0;
	end;
	planted_all_purposes=COTTON__UPLAND___ACRES_PLANTED+PEANUTS___ACRES_PLANTED+SOYBEANS___ACRES_PLANTED+CORN___ACRES_PLANTED+BARLEY___ACRES_PLANTED+
		RICE___ACRES_PLANTED+SORGHUM___ACRES_PLANTED+SUGARBEETS___ACRES_PLANTED+OATS___ACRES_PLANTED+WHEAT__SPRING__DURUM___ACRES_PLA+WHEAT__WINTER___ACRES_PLANTED+
		WHEAT__SPRING___EXCL_DURUM____PL;
	harvested=COTTON__UPLAND___ACRES_HARVESTED+HAY___EXCL_ALFALFA____ACRES_HARV+PEANUTS___ACRES_HARVESTED+SOYBEANS___ACRES_HARVESTED+CORN__GRAIN___ACRES_HARVESTED+
		HAY__ALFALFA___ACRES_HARVESTED+BARLEY___ACRES_HARVESTED+RICE___ACRES_HARVESTED+SORGHUM__GRAIN___ACRES_HARVESTED+SUGARBEETS___ACRES_HARVESTED+OATS___ACRES_HARVESTED+
		WHEAT__SPRING__DURUM___ACRES_HAR+WHEAT__WINTER___ACRES_HARVESTED+WHEAT__SPRING___EXCL_DURUM____HA;
	production=COTTON__UPLAND___PRODUCTION__MEA+HAY___EXCL_ALFALFA____PRODUCTION+PEANUTS___PRODUCTION__MEASURED_I+SOYBEANS___PRODUCTION__MEASURED+CORN__GRAIN___PRODUCTION__MEASUR+
		HAY__ALFALFA___PRODUCTION__MEASU+BARLEY___PRODUCTION__MEASURED_IN+RICE___PRODUCTION__MEASURED_IN_C+SORGHUM__GRAIN___PRODUCTION__MEA+SUGARBEETS___PRODUCTION__MEASURE+
		OATS___PRODUCTION__MEASURED_IN_B+WHEAT__SPRING__DURUM___PRODUCTIO+WHEAT__WINTER___PRODUCTION__MEAS+WHEAT__SPRING___EXCL_DURUM____PR;
	drop i;
run;

data rawdataupdate; set rawdataupdate;
	length Temp $3.;
	Temp = CoFips;
	if length(compress(Temp)) = 1 then Temp = compress('00'||Temp) ;
	if length(compress(Temp)) = 2 then Temp = compress('0'||Temp) ;
	if length(compress(Temp)) = 3 then Temp = Temp ;
	FIPS = input(compress(StFips || Temp ),6.0) ;

	if commodity ne "COTTON" and commodity ne "RICE" then yield=production/harvested;
	else if commodity="COTTON" then yield=production/harvested*480;
	else if commodity="RICE" then yield=production/harvested*100;

	if commodity="CORN" THEN commcode=11199199;
	if commodity="SOYBEANS" THEN commcode=15499199;
	if commodity="SORGHUM" THEN commcode=11499199;
	if commodity="BARLEY" THEN commcode=11399999;
	if commodity="OATS" THEN commcode=11299999;
	if commodity="HAY" THEN commcode=18999999;
	if commodity="RICE" THEN commcode=10619999;
	if commodity="COTTON" THEN commcode=12121999;
	if commodity="PEANUTS" THEN commcode=15399199;
	if commodity="SUNFLOWERSEED" THEN commcode=15831999;
	if commodity="SUGARBEETS" THEN commcode=13299199;
	if commodity="WHEAT" THEN commcode=10199999;

	keep fips year state county stfips cofips commodity planted_all_purposes harvested production yield CommCode;
run;
proc sort data=rawdataupdate; by CommCode FIPS Year; run;
proc sort data=olddata; by CommCode FIPS Year; run;
data temp; merge olddata rawdataupdate; by CommCode FIPS Year; run;



/*
*harvested;*********************************************************;
proc sort data=temp out=temp2; by Fips CommCode Year; run;

proc transpose data=temp2 out=temp3; 
	id year;
	by Fips CommCode;
	var Harvested;
run;

data temp4; set temp3;
	if max(of _1982-_2011) = . then delete;
run;
*/


*yields;*********************************************************;
proc sort data=temp out=temp2; by Fips CommCode Year; run;

proc transpose data=temp2 out=temp3; 
	id year;
	by Fips CommCode;
	var Yield;
run;

data temp4; set temp3;
	if max(of _1982-_2012) = . then delete;
run;

*don't forget dairy yields!;
