<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode([]);
    exit();
}

$branch_id = $_SESSION['branch_id'];

if(isset($_POST['student_name'])){
    $student_name = trim($_POST['student_name']);

    $stmt = $conn->prepare("SELECT gender_id, subject1, subject2, subject3, subject4 FROM students WHERE student_name=? AND branch_id=? LIMIT 1");
    $stmt->bind_param("si", $student_name, $branch_id);
    $stmt->execute();
    $res = $stmt->get_result();

    if($res && $res->num_rows > 0){
        $row = $res->fetch_assoc();
        echo json_encode($row);
    } else {
        echo json_encode([]);
    }
}
?>
