dm 'log;clear;output;clear;';

*import data;
PROC IMPORT OUT= WORK.rawdata 
            DATATABLE= "tblUsage" 
            DBMS=ACCESSCS REPLACE;
     DATABASE="D:\fapri_site\Projects\budget.accdb"; 
     SCANMEMO=YES;
     USEDATE=NO;
     SCANTIME=YES;
RUN;

*sum data by fips;
proc sql noprint; create table sum_rawdata as 
	select fips, count(fips) as count
	from rawdata group by fips;
quit;

*create state and county fips;
data sum_rawdata; set sum_rawdata;
	length State 8. County 8.;
	State = substr(FIPS,1,2);
	County = substr(FIPS,3,3);
	if fips=0 or fips=. then delete;
run;

	* Create an Annotate data set to draw the borders for the states;
	data anno1; set maps.uscounty(where=(state ne stfips('AK') and state ne stfips('HI'))); run;

	proc sort data=anno1; by state segment; run;

	proc gremove data=anno1 out=anno1;
   		by state;
   		id county;
	run;

	data anno1;
		set anno1;
		by state segment;
		retain
			size 2         /* line thickness                 */
		    color 'black'  /* black border                   */
		    xsys '2'       /* Use the map coordinate system. */
		    ysys '2'
		    when 'a';    /* Annotate after the map is drawn. */

		    /* For first point in each polygon or first */
		    /* point of interior polygon, set FUNCTION  */
		    /* to 'POLY'.                               */
		if first.segment or (lag(x)=. and lag(y)=.)
			then function='POLY    ';

		/* For other points, set FUNCTION to 'POLYCONT'. */
		   else function='POLYCONT';

		/* Don't output points with missing values. */
		if x and y then output;
	run;

*create map;
proc format;
value hits_
1 -  5 = '1 - 5'
6 -  10 = '6 - 10'
11 -  15 = '11 - 15'
16 - high = '16+'
;
run;


* fill patterns for the map areas;
%colormac;
pattern1 value=msolid color=%CMYK(18,0,28,0);
pattern2 value=msolid color=%CMYK(59,21,67,2);
pattern3 value=msolid color=%CMYK(71,44,76,34);
pattern4 value=msolid color=%CMYK(74,57,75,71);

* legend location and appearance ;
legend1
offset=(85,5)
label = None
mode=share
across=1
shape=bar(3,4) pct
cborder=black;

*Draw map ;
proc gmap
data=sum_rawdata
map=maps.uscounty(where=(state ne stfips('AK') and state ne stfips('HI')))
all;
id state county;
choro count / discrete legend=legend1 anno=anno1;
format count hits_.;

title h=4 pct 'Farm CART hits by county' ;
run;
