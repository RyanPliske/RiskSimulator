Option Base 1

Public Sub Substitute(KIV As Range, KOV As Range, DATA As Range, out As Range, Optional bolSolver As Boolean)
'Substitute data into the KIV and record the KOV

    Call Initialize

    XL.ScreenUpdating = False

    'Save the formulas in the KIV
    XL.StatusBar = "Simulation: preparing..."
    Dim formulas()
    If KIV.Cells.count > 1 Then
        formulas = KIV.Formula
    Else
        ReDim formulas(1)
        formulas(1) = KIV.Range("A1").Formula
    End If
    
    'Loop through the data and put it into the KIV
    'then record the KOV in the out
    Dim i As Integer, R As Integer, c As Integer, CalcStart As Variant
    CalcStart = XL.Calculation
    XL.Calculation = xlCalculationManual
    Set out = out.Resize(KOV.Rows.count, KOV.Columns.count)
    For i = 0 To DATA.Rows.count / KIV.Rows.count - 1 'iterations
        'Set the data
        KIV.Value = DATA.Rows(1 + i * KIV.Rows.count & ":" & (1 + i) * KIV.Rows.count).Value
        calculate
        If bolSolver Then Application.Run "SolverSolve", True 'solver
        calculate

        'record the data
        out.Offset(i * KOV.Rows.count).Value = KOV.Value
        
        'keep original values if solver runs
        If bolSolver Then Application.Run "SolverFinish", 2

        XL.StatusBar = "Simulation: " & Round((i + 1) / (DATA.Rows.count / KIV.Rows.count) * 100, 0) & "%"
    Next i
    
    'Reset the formulas in the KIV
    XL.StatusBar = "Simulation: ending..."
    KIV.Formula = formulas
    
    calculate
    XL.Calculation = CalcStart
    XL.StatusBar = ""
    XL.ScreenUpdating = True

End Sub

Public Function Cholesky(matrix As Range)
'Calculate the Cholesky factorization of a square matrix

    Dim RCount As Integer, i As Integer, j As Integer
    Dim l() As Double
    RCount = matrix.Rows.count
    ReDim l(RCount, RCount)
    
    'Make sure the matrix is square
    If RCount <> matrix.Columns.count Then
        Cholesky = "Not Square"
        Exit Function
    End If
    
    'Loop through the rows
    For i = 1 To RCount
        On Error Resume Next
        'Loop through columns to the left of the diagnol
        For j = 1 To i - 1
            l(i, j) = (matrix.Cells(i, j) - CSum(l, i, j)) / l(j, j)
        Next j
        
        'Calculate the diagnol of the row
        l(i, i) = Sqr(matrix.Cells(i, i) - CSum(l, i, i))
    Next i
    
    Cholesky = l

End Function

Private Function CSum(l() As Double, i As Integer, j As Integer) As Double
'Calculate the sums in Cholesky

    Dim k As Integer, result As Double
    For k = 1 To j - 1
        result = result + l(i, k) * l(j, k)
    Next k

    CSum = result

End Function

Public Sub Form_Substitute()
    frmSubstitute.Show
End Sub

Public Sub Form_Spectral_Decomposition()
    frmSpectral_Decomposition.Show
End Sub

Public Sub Spectral_Decomposition(matrix As Range, out As Range, Optional asFormula As Boolean, Optional bolSort As Boolean)
    
    Dim objEigen As New Eigen
    Set objEigen = New Eigen
    Dim A As Variant
    A = matrix
    Call objEigen.Init(A, , , bolSort)

	'Probably won't need for PHP
    Dim rng1 As Range, rng2 As Range
    Set rng1 = out.Range("A2").Resize(matrix.Rows.count, 1)
    Set rng2 = rng1.Range("A1").Offset(0, 2).Resize(matrix.Rows.count, matrix.Columns.count)
    
	'Probably won't need for PHP
    rng1.Range("A1").Offset(-1, 0).Value = "Eigenvalues"
    If asFormula = False Then
        rng1 = objEigen.Eigenvalues
    Else
        rng1.FormulaArray = "=Eigenvalues(" & matrix.Address & IIf(bolSort, ",TRUE", "") & ")"
    End If
	
    'Probably won't need for PHP
    rng2.Range("A1").Offset(-1, 0).Value = "Eigenvectors"
    If asFormula = False Then
        rng2 = objEigen.Eigenvectors
    Else
        rng2.FormulaArray = "=Eigenvectors(" & matrix.Address & IIf(bolSort, ",TRUE", "") & ")"
    End If
    calculate
    
    Set objEigen = Nothing
    
End Sub

Public Function Diagonal(vector As Range) As Variant
'Takes a column vector and creates a diagnol matrix
    Dim result As Variant, n As Integer, i As Integer
    n = vector.Rows.count
    ReDim result(1 To n, 1 To n)
    For i = 1 To n
        result(i, i) = vector.Range("a1").Offset(i - 1, 0).Value
    Next i
    
    Diagonal = result
End Function

Public Function Eigenvalues(matrix As Range, Optional bolSort As Boolean)
    
    Dim objEigen As New Eigen
    Set objEigen = New Eigen
    Dim A As Variant
    A = matrix
    Call objEigen.Init(A, , , bolSort)
    
    Eigenvalues = objEigen.Eigenvalues
    
End Function

Public Function Eigenvectors(matrix As Range, Optional bolSort As Boolean)
    
    Dim objEigen As New Eigen
    Set objEigen = New Eigen
    Dim A As Variant
    A = matrix
    Call objEigen.Init(A, , , bolSort)
    
    Eigenvectors = objEigen.Eigenvectors
    
End Function

Public Sub Nearest_Correlation(matrix As Range, out As Range, Optional asFormula As Boolean)
    
    Dim objNC As New Nearest_Corr
    Set objNC = New Nearest_Corr
    Dim A As Variant
    A = matrix
    Call objNC.Init(A)

    Dim rng1 As Range, rng2 As Range
    Set rng1 = out.Range("A2").Resize(matrix.Rows.count, matrix.Columns.count)
    
    rng1.Range("A1").Offset(-1, 0).Value = "Nearest correlation matrix"
    If asFormula = False Then
        rng1 = objNC.result
    Else
        rng1.FormulaArray = "=NearestCorr(" & matrix.Address & ")"
    End If
    
    calculate
    
    Set objNC = Nothing
    
End Sub

Public Sub Form_Nearest_Correlation()
    frmNearCorr.Show
End Sub

Public Function NearestCorr(matrix As Range)

    Dim objNC As New Nearest_Corr
    Set objNC = New Nearest_Corr
    Dim A As Variant
    A = matrix
    Call objNC.Init(A)
    
    NearestCorr = objNC.result

End Function

Public Sub ListPrecedents(sPrecList() As String, Optional rng As Variant)

    Dim rStart As Range
    Dim rPrecCells As Range
    Dim cell As Range, i As Integer
    
    If IsMissing(rng) Then
        Set rStart = ActiveCell
    Else
        Set rStart = Range(rng)
    End If

    'If there are no precedents, an error will occur
    On Error Resume Next
        Set rPrecCells = rStart.DirectPrecedents
    On Error GoTo 0

    'If there are precedents
    If Not rPrecCells Is Nothing Then
        'Loop through the Areas collection and string
        'together the addresses, this only gets those on current sheet
        'For Each cell In rStart.Precedents.Areas
        For Each cell In rStart.DirectPrecedents
            'sPrecList = sPrecList & cell.Address(0, 0) & ","
            i = i + 1
            ReDim Preserve sPrecList(i)
            sPrecList(i) = cell.Address(0, 0)
        Next cell
        
        'Get references on other sheets
        'Do While InStr(1, rng, "!") <> 0
            
        'Loop
    End If
    
End Sub

Public Sub ShowFormulaForm()
    frmFormula.Show
End Sub

Public Sub EnterFormulas()

    Dim cnt As Long
    Dim i As Long

    Application.ScreenUpdating = False
    For Each cell In Selection
        cell.Formula = cell.Value
    Next cell

    Application.ScreenUpdating = True
End Sub

Public Sub FormulaAuditing()
    frmFormulaAudit.Show
End Sub



Public Function Pattern(rng1 As Range, rng2 As Range)
'Does not work if there are duplicate ranges in a function

    Dim strRange1() As String, strRange2() As String
    Dim strTempRange As String
    Call ListPrecedents(strRange1, rng1.Address)
    Call ListPrecedents(strRange2, rng2.Address)
    Dim strFormula1 As String
    strFormula1 = rng1.Formula

    Dim rng As Range, cell As Range, i As Integer, fnd As Integer
    
    'remove absolute references
    For i = 1 To Len(strFormula1)
        If Mid(strFormula1, i, 1) = "$" Then
            strFormula1 = Mid(strFormula1, 1, i - 1) & Mid(strFormula1, i + 1)
        End If
    Next i
    
    'We are going to use rng1 as the base which to manipulate
    On Error Resume Next
    For i = 1 To UBound(strRange1)
        Set rng = Range(strRange1(i))
        strTempRange = ""
        'For Each cell In rng 'get each cell in reference
            strTempRange = ProjectRef(rng, Range(strRange2(i))) 'strTempRange & cell.Value & ","
        'Next cell
        'strTempRange = Mid(strTempRange, 1, Len(strTempRange) - 1) 'remove last comma
        fnd = InStr(strFormula1, strRange1(i))
        Do While fnd > 0 'get all instances
            strFormula1 = Mid(strFormula1, 1, fnd - 1) & strTempRange & _
                Mid(strFormula1, fnd + Len(strRange1(i))) 'replace reference
            fnd = InStr(fnd + 2, strFormula1, strRange1(i))
        Loop
    Next i
    
    Pattern = strFormula1

End Function

Private Function ProjectRef(rng1 As Range, rng2 As Range) As String
    Dim result As Range
    
    Set result = rng2
    
    Set result = result.Range("A1").Offset(rng2.row - rng1.row)
    Set result = result.Range("A1").Offset(0, rng2.Column - rng1.Column)
    
    ProjectRef = result.Address(0, 0)
End Function

Sub Run()
    UserForm1.Show
End Sub

Public Function COFACTOR(rng As Variant, R As Long, c As Long) As Variant
    
    Dim A() As Double, i As Double, j As Double, temp_r As Double, temp_c As Double, temp As Variant
    temp = rng
    ReDim A(1 To UBound(temp, 1) - 1, 1 To UBound(temp, 2) - 1)
    
    If UBound(temp, 1) <> UBound(temp, 2) Then
        COFACTOR = "Not square"
        Exit Function
    End If
    
    For i = 1 To UBound(temp, 1)
        If i <> R Then
            temp_r = temp_r + 1
            For j = 1 To UBound(temp, 2)
                If j <> c Then
                    temp_c = temp_c + 1
                    A(temp_r, temp_c) = Val(temp(i, j)) * IIf(temp_r = 1, (-1) ^ (R + c), 1)
                End If
            Next j
            temp_c = 0
        End If
    Next i
    
    COFACTOR = A

End Function

Public Function LOOCV(actual_y As Variant, actual_x As Variant) As Double
'Leave One Out Cross Validation
'Assumes a linear model

    Dim x As Variant, Y As Variant
    x = actual_x
    Y = actual_y

    'Make sure the lengths of x and y are the same
    If UBound(x, 1) <> UBound(Y, 1) Then
        LOOCV = "Unequal lengths"
        Exit Function
    End If

    Dim result As Double, i As Double, temp_x As Variant, temp_y  As Variant

    For i = 1 To UBound(x, 1)
        temp_x = RemoveRow(x, i)
        temp_y = RemoveRow(Y, i)
        result = result + (Y(i, 1) - Application.WorksheetFunction.Trend(temp_y, temp_x, Application.WorksheetFunction.index(x, i, 0))(1)) ^ 2
    Next i
    
    LOOCV = result / UBound(x, 1)

End Function

Private Function RemoveRow(x As Variant, k As Double) As Variant

    Dim R As Double, c As Double, result As Variant
    result = x
    
    For R = k + 1 To UBound(result, 1)
        For c = 1 To UBound(result, 2)
            result(R - 1, c) = result(R, c)
        Next c
    Next R
    
    On Error Resume Next
    result = Application.WorksheetFunction.Transpose(result)
    ReDim Preserve result(UBound(result, 1), UBound(result, 2) - 1)
    If Err <> 0 Then
        ReDim Preserve result(UBound(result, 1) - 1)
    End If
    result = Application.WorksheetFunction.Transpose(result)

    RemoveRow = result

End Function

Public Function ListConcat(delimiter As String, rng As Range) As String
'Create a string from rng seperated by the delimiter

    Dim result As String, i As Long
    For Each cell In rng
        i = i + IIf(cell.Value <> "", 1, 0)
        result = result & IIf(i > 1 And cell.Value <> "", delimiter, "") & cell.Value
    Next cell

    ListConcat = result

End Function

Sub OpenWB()

On Error Resume Next

Dim i0 As Integer, j As Integer, k As Integer
            Dim l As Integer, m As Integer, n As Integer
            Dim i1 As Integer, i2 As Integer, i3 As Integer
            Dim i4 As Integer, i5 As Integer, i6 As Integer
            For i0 = 65 To 66: For j = 65 To 66: For k = 65 To 66
            For l = 65 To 66: For m = 65 To 66: For i1 = 65 To 66
            For i2 = 65 To 66: For i3 = 65 To 66: For i4 = 65 To 66
            For i5 = 65 To 66: For i6 = 65 To 66: For n = 32 To 126
            
            Instance = Chr(i0) & Chr(j) & Chr(k) & _
                Chr(l) & Chr(m) & Chr(i1) & Chr(i2) & Chr(i3) & _
                Chr(i4) & Chr(i5) & Chr(i6) & Chr(n)

            Application.ActiveWorkbook.Unprotect Instance
            'If Application.ActiveWorkbook.ProtectContents = False Then
            '    MsgBox Instance
            '    Exit Sub
            'End If

            Next: Next: Next: Next: Next: Next
            Next: Next: Next: Next: Next: Next

End Sub

Public Function MSubtract(rngMatrix1, rngMatrix2)

    Dim objNC As New Nearest_Corr
    Set objNC = New Nearest_Corr
    Dim matrix1 As Variant, matrix2 As Variant
    matrix1 = rngMatrix1: matrix2 = rngMatrix2

    MSubtract = MSubtract_sub(matrix1, matrix2)
End Function

Private Function MSubtract_sub(matrix1, matrix2)
'subtract matrix2 from matrix1
    n1 = UBound(matrix1, 1)
    n2 = UBound(matrix1, 2)
    m1 = UBound(matrix2, 1)
    m2 = UBound(matrix2, 2)
    If n1 <> m1 Or n2 <> m2 Then
        Exit Function
    End If
    ReDim diff(1 To n1, 1 To n2)
    For j = 1 To n1
        For i = 1 To n2
            diff(j, i) = matrix1(j, i) - matrix2(j, i)
        Next i
    Next j
    MSubtract_sub = diff
End Function
