<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'config.php'; // your DB connection

// Set new password for branch2
$new_password = "Branch2NewPass123"; // you can change this
$hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

$username = "branch2"; // branch2 user

$stmt = $conn->prepare("UPDATE users SET password = ? WHERE username = ?");
if(!$stmt){
    die("Prepare failed: " . $conn->error);
}

$stmt->bind_param("ss", $hashed_password, $username);

if($stmt->execute()){
    echo "Branch2 password reset successful!<br>";
    echo "New password: <b>$new_password</b>";
}else{
    echo "Error: " . $stmt->error;
}
$stmt->close();
$conn->close();
?>
