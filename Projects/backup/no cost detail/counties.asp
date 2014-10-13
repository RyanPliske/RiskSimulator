<option value="0">Select county...</option>

<!-- #include virtual="Projects/ado_open.asp" -->
<%
    strSQL = "SELECT tblCounties.* FROM tblCounties WHERE left(ID,2)='" & request.querystring("cnty") & "' AND right(ID,3)<>'000';"
    Set rsArea = adoCon.execute(strSQL)
    Do while not rsArea.eof
        response.Write(vbcrlf & "<option value='" & rsArea("ID") & "'>" & rsArea("fldCounty") & "</option>")
        rsArea.movenext
    loop
    
    adoCon.close
    set rsArea = nothing
%>
<!-- #include virtual="Projects/ado_close.asp" -->