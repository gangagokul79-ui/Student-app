<?php
include 'config.php';
session_start();

// Logged-in user
$created_by = $_SESSION['username'] ?? 'admin'; // fallback

// Form data
$slot_name = $_POST['slot_name'];
$start_time = $_POST['start_time'];
$end_time = $_POST['end_time'];

// Insert query
$sql = "INSERT INTO time_slot (slot_name, start_time, end_time, created_by, updated_by) 
        VALUES (?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("sssss", $slot_name, $start_time, $end_time, $created_by, $created_by);

if($stmt->execute()){
    echo "Time slot added successfully!";
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>