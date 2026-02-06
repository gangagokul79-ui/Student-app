<?php
session_start();
include 'config.php';

// Check admin login
if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

if(isset($_POST['add_user'])){
    $username = $_POST['username'];
    $plain_password = $_POST['password'];
    $created_by = $_SESSION['user_id'];

    // Hash password
    $hashed_password = password_hash($plain_password, PASSWORD_DEFAULT);

    // Insert user
    $sql = "INSERT INTO users (username, password, created_by) 
            VALUES ('$username', '$hashed_password', $created_by)";
    if(mysqli_query($conn, $sql)){
        $success = "User added successfully!";
    } else {
        $error = "Error: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add User</title>
    <style>
        body { font-family: Arial; background: #f5f5f5; display:flex; justify-content:center; align-items:center; height:100vh; }
        form { background: #fff; padding:20px; border-radius:8px; box-shadow:0 0 10px rgba(0,0,0,0.1); width:300px; }
        input { display:block; margin:10px 0; padding:10px; width:100%; }
        .success { color:green; margin-bottom:10px; }
        .error { color:red; margin-bottom:10px; }
        button { padding:10px; width:100%; background:#4CAF50; color:white; border:none; border-radius:5px; cursor:pointer; }
        button:hover { background:#45a049; }
        h2 { text-align:center; }
    </style>
</head>
<body>

<form method="POST">
    <h2>Add User</h2>
    <?php 
    if(isset($success)){ echo "<p class='success'>$success</p>"; }
    if(isset($error)){ echo "<p class='error'>$error</p>"; }
    ?>
    <input type="text" name="username" placeholder="Username" required>
    <input type="password" name="password" placeholder="Password" required>
    <button type="submit" name="add_user">Add User</button>
</form>

<a href="dashboard.php">Back to Dashboard</a>
</body>
</html>
