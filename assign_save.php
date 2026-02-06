<?php
session_start();
include 'config.php';

if(!isset($_POST['class_id'])){
    $_SESSION['msg'] = "<p style='color:red;text-align:center;'>Class not selected.</p>";
    header("Location: assign_class.php");
    exit;
}

$class_id = (int)$_POST['class_id'];

// Class name fetch
$getClass = mysqli_query($conn, "SELECT class_name FROM classes WHERE id='$class_id'");
if(mysqli_num_rows($getClass) == 0){
    $_SESSION['msg'] = "<p style='color:red;text-align:center;'>Invalid class selected.</p>";
    header("Location: assign_class.php");
    exit;
}

$class_name = mysqli_fetch_assoc($getClass)['class_name'];

// Student selection check
if(!isset($_POST['student_ids']) || count($_POST['student_ids']) == 0){
    $_SESSION['msg'] = "<p style='color:red;text-align:center;'>No students selected.</p>";
    header("Location: assign_class.php?class_id=$class_id");
    exit;
}

foreach($_POST['student_ids'] as $sid){

    $sid = (int)$sid;

    // Fetch student details
    $s = mysqli_query($conn, "SELECT * FROM students WHERE id='$sid'");
    if(mysqli_num_rows($s) == 0){ continue; }

    $row = mysqli_fetch_assoc($s);

    // Insert into class_assignments
    mysqli_query($conn, "
        INSERT INTO class_assignments
        (roll_number, student_name, branch_id, branch_name, class_name, status, created_by, created_on, student_id, assigned_on)
        VALUES
        (
            '{$row['roll_number']}',
            '{$row['student_name']}',
            '{$row['branch_id']}',
            '{$row['branch_name']}',
            '$class_name',
            'active',
            'admin',
            NOW(),
            '{$row['id']}',
            NOW()
        )
    ");
}

$_SESSION['msg'] = "<p style='color:green;text-align:center;'>Assigned Successfully!</p>";
header("Location: assign_class.php?class_id=$class_id");
exit;

?>
