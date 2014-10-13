<%

if not session("forms")="" then
	response.Write(session("forms"))
	'session("forms")="" 'turn this on if you want to clear on refresh, however multiple f/b will lose data
end if

%>