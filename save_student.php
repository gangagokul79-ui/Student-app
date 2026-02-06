<?php
session_start();
include 'config.php';

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit;
}

if(isset($_POST['submit'])) {

    $school_id = $_POST['school_id'];
    $roll_no   = $_POST['roll_no']; 
    $name      = trim($_POST['name']);
    $gender    = $_POST['gender'];
    $sub1      = $_POST['subject1'];
    $sub2      = $_POST['subject2'];
    $sub3      = $_POST['subject3'];
    $sub4      = $_POST['subject4'];

    $created_on = date('Y-m-d H:i:s');
    $created_by = $_SESSION['username'];

    if($school_id == "" || $roll_no == "" || $name == "" || $gender == ""){
        header("Location: add_student.php?error=Please fill all fields");
        exit;
    }

    $sql = "INSERT INTO students (school_id, roll_no, name, gender, subject1, subject2, subject3, subject4, created_on, created_by)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isssiiiiss", $school_id, $roll_no, $name, $gender, $sub1, $sub2, $sub3, $sub4, $created_on, $created_by);

    if($stmt->execute()){
        header("Location: add_student.php?success=1");
    } else {
        header("Location: add_student.php?error=Database Error");
    }
}
?>