<?php
session_start();
include 'config.php';

$branch_id  = $_SESSION['branch_id'];
$class_name = $_POST['class_name'];
$type       = $_POST['type'];

$sql = "
    SELECT s.roll_number, s.student_name, s.gender_id
    FROM students s
    INNER JOIN class_assignments ca
    ON s.roll_number = ca.roll_number
    WHERE ca.class_name='$class_name'
    AND s.branch_id='$branch_id'
    ORDER BY s.student_name
";

$result = mysqli_query($conn,$sql);

/* ================= EXCEL ================= */
if($type == 'excel'){
    header("Content-Type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=Class_Report_$class_name.xls");

    echo "S.No\tRoll Number\tStudent Name\tGender\n";
    $i=1;
    while($r=mysqli_fetch_assoc($result)){
        $gender = ($r['gender_id']==1)?'Male':(($r['gender_id']==2)?'Female':'Other');
        echo "$i\t{$r['roll_number']}\t{$r['student_name']}\t$gender\n";
        $i++;
    }
    exit;
}

/* ================= PDF ================= */
if($type == 'pdf'){
    require('fpdf/fpdf.php');

    $pdf = new FPDF();
    $pdf->AddPage();
    $pdf->SetFont('Arial','B',14);
    $pdf->Cell(0,10,"Class Report - $class_name",0,1,'C');
    $pdf->Ln(5);

    $pdf->SetFont('Arial','B',10);
    $pdf->Cell(10,8,'#',1);
    $pdf->Cell(35,8,'Roll No',1);
    $pdf->Cell(70,8,'Student Name',1);
    $pdf->Cell(30,8,'Gender',1);
    $pdf->Ln();

    $pdf->SetFont('Arial','',10);
    $i=1;
    while($r=mysqli_fetch_assoc($result)){
        $gender = ($r['gender_id']==1)?'Male':(($r['gender_id']==2)?'Female':'Other');
        $pdf->Cell(10,8,$i,1);
        $pdf->Cell(35,8,$r['roll_number'],1);
        $pdf->Cell(70,8,$r['student_name'],1);
        $pdf->Cell(30,8,$gender,1);
        $pdf->Ln();
        $i++;
    }

    $pdf->Output();
    exit;
}
?>
