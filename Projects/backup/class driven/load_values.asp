<%

'************************************************************************************************************
'Get default values
'************************************************************************************************************

'yields ********************
for i = 0 to ubound(oCrop)-1
	strSQL = "SELECT tblCountyYields.* FROM tblCountyYields WHERE Fips='" & iif(oCrop(i).label<>"dairy",request.Form("fips"),left(request.Form("fips"),2)&"000") & "' AND CommCode=" & oCrop(i).CommCode & ";"
	Set rsData = adoCon.execute(strSQL)
	if oCrop(i).Used = "true" then
		for j = yStart to yEnd
			if not rsData.eof and not rsData.bof and not isnull(rsData(cstr(j))) then
				oCrop(i).Yield(j-yStart) = rsData(cstr(j))
			'else
			'	oCrop(i).Yield(j-yStart) = empty
			end if
		next
	end if
next
rsData.close
set rsData = nothing

'don't forget to set a session variable equal to oCrop

%>