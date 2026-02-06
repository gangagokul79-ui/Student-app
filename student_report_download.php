<?php
session_start();
include 'config.php';

if(!isset($_SESSION['user_id'])){
    exit();
}

$branch_id = $_SESSION['branch_id'];
$roll = $_POST['roll_number'];
$type = $_POST['type'];

/* ===== FETCH DATA ===== */
$sql = "
    SELECT s.roll_number, s.student_name, s.gender_id, ca.class_name
    FROM students s
    INNER JOIN class_assignments ca
        ON s.roll_number = ca.roll_number
    WHERE s.roll_number='$roll'
      AND s.branch_id='$branch_id'
";
$result = mysqli_query($conn,$sql);

/* ================= EXCEL ================= */
if($type=="excel"){

    header("Content-Type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=student_report.xls");

    echo "Roll Number\tStudent Name\tGender\tClass Name\n";

    while($r=mysqli_fetch_assoc($result)){
        $gender = ($r['gender_id']==1)?'Male':(($r['gender_id']==2)?'Female':'Other');
        echo "{$r['roll_number']}\t{$r['student_name']}\t$gender\t{$r['class_name']}\n";
    }
    exit();
}

/* ================= PDF ================= */
if($type=="pdf"){

    // 🔥 IMPORTANT PATH
    require('fpdf/fpdf.php');

    $pdf = new FPDF();
    $pdf->AddPage();
    $pdf->SetFont('Arial','B',14);

    $pdf->Cell(0,10,'Student Report',0,1,'C');
    $pdf->Ln(5);

    $pdf->SetFont('Arial','B',10);
    $pdf->Cell(35,8,'Roll No',1);
    $pdf->Cell(50,8,'Student Name',1);
    $pdf->Cell(35,8,'Gender',1);
    $pdf->Cell(50,8,'Class',1);
    $pdf->Ln();

    $pdf->SetFont('Arial','',10);

    while($r=mysqli_fetch_assoc($result)){
        $gender = ($r['gender_id']==1)?'Male':(($r['gender_id']==2)?'Female':'Other');

        $pdf->Cell(35,8,$r['roll_number'],1);
        $pdf->Cell(50,8,$r['student_name'],1);
        $pdf->Cell(35,8,$gender,1);
        $pdf->Cell(50,8,$r['class_name'],1);
        $pdf->Ln();
    }

    $pdf->Output();
    exit();
}
?>
