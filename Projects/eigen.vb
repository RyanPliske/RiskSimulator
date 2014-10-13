Option Base 1


Dim k As Integer, l As Integer, n As Integer
Dim rho As Double, accuracy As Double, MaxIter As Integer
Dim ValuesDifferent As Boolean

Public matrix As Variant
Public Eigenvectors As Variant
Public Eigenvalues As Variant
Public tempmax As Double
Dim j() As Double


Private Sub Class_Initialize()
    accuracy = 0.00001
    MaxIter = 500
End Sub

Public Sub Init(E As Variant, Optional accuracy_ As Variant, Optional MaxIter_ As Variant, Optional bolSort As Boolean)
    matrix = E
    n = UBound(E, 1)
    ReDim j(n, n)
    ReDim Eigenvalues(n)
    If Not IsMissing(accuracy_) Then accuracy = accuracy_
    If Not IsMissing(MaxIter_) Then MaxIter = MaxIter_
    ValuesDifferent = True
    Call calculate
    If bolSort = True Then Call Sort
    Eigenvalues = Application.Transpose(Eigenvalues) 'eigenvalues are a row vector before transposing
End Sub

Private Sub maxind()
'Get the largest below diagonal component

    Dim R As Integer, c As Integer
    tempmax = -1
    
    For R = 2 To n
        For c = 1 To R - 1
            If Abs(matrix(R, c)) > tempmax Then
                tempmax = Abs(matrix(R, c))
                k = R: l = c
            End If
        Next c
    Next R

End Sub

Private Sub CalcRho()
'Calculate the value of rho
    
    If matrix(k, k) <> matrix(l, l) Then
        rho = Atn(2 * matrix(k, l) / (matrix(k, k) - matrix(l, l))) / 2
    Else
        rho = Application.Pi * Sgn(matrix(k, l)) / 4
    End If
    
End Sub

Private Sub JacobiMatrix(i As Integer)
'Determine the matris for the jacobi rotation

    j = identity(n)
    j(k, k) = Cos(rho)
    j(k, l) = -Sin(rho)
    j(l, k) = Sin(rho)
    j(l, l) = Cos(rho)
    If i > 1 Then
        Eigenvectors = Application.MMult(Eigenvectors, j)
    Else
        Eigenvectors = j
    End If
End Sub

Private Sub calculate()
'calculate the matrix with the diagonals as the eigenvalues

    Dim i As Integer
    i = 0
    Do While i <= MaxIter And ValuesDifferent
        i = i + 1
        Call maxind
        Call CalcRho
        Call JacobiMatrix(i)
        matrix = Application.MMult(Application.MMult(Application.Transpose(j), matrix), j)
        Call CheckAccuracy
    Loop
End Sub

Private Function identity(dimension As Integer) As Variant
'Create an identity matrix
    Dim A() As Double
    ReDim A(dimension, dimension)
    Dim index As Integer
    For index = 1 To dimension
        A(index, index) = 1
    Next index
    identity = A
End Function

Private Sub ExtractEigenvalues()
    Dim i As Integer
    For i = 1 To n
        Eigenvalues(i) = matrix(i, i)
    Next i
End Sub

Private Sub CheckAccuracy()
'See if eigenvalues are significant
    Dim i As Integer
    ValuesDifferent = False
    If Abs(tempmax) > accuracy Then ValuesDifferent = True
    ExtractEigenvalues
End Sub

Public Function getAccuracy()
    getAccuracy = accuracy
End Function
Public Sub setAccuracy(accuracy_ As Double)
    accuracy = accuracy_
End Sub
Public Function getMaxIter()
    getMaxIter = MaxIter
End Function
Public Sub setMaxIter(MaxIter_ As Integer)
    MaxIter = MaxIter_
End Sub

Private Sub Sort()
    Dim i As Integer, passNum As Integer
    For passNum = 1 To n - 1
        For i = 1 To n - passNum
            If Eigenvalues(i) < Eigenvalues(i + 1) Then
                Call VectorEntrySwap(Eigenvalues, i, i + 1)
                Call ColSwap(Eigenvectors, i, i + 1)
            End If
        Next i
    Next passNum
End Sub

Private Sub VectorEntrySwap(vector As Variant, entry1 As Integer, entry2 As Integer)
'Swap entries within a vector
    Dim temp As Variant
    temp = vector(entry1)
    vector(entry1) = vector(entry2)
    vector(entry2) = temp
End Sub

Private Sub ColSwap(matrix As Variant, col1 As Integer, col2 As Integer)
'Swap the columns within a vector
    Dim temp As Variant
    Dim i As Integer
    For i = LBound(matrix, 1) To UBound(matrix, 1)
        temp = matrix(i, col1)
        matrix(i, col1) = matrix(i, col2)
        matrix(i, col2) = temp
    Next i
End Sub

