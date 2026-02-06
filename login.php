<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include 'config.php';

$error = '';

if (isset($_POST['username']) && isset($_POST['password'])) {

    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    $stmt = $conn->prepare("
        SELECT u.*, s.school_code
        FROM users u
        LEFT JOIN schools s ON u.school_id = s.id
        WHERE u.username = ? AND u.status = 'active'
        LIMIT 1
    ");

    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows === 1) {

        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password'])) {

            $_SESSION['user_id']     = $user['id'];
            $_SESSION['username']    = $user['username'];
            $_SESSION['school_id']   = $user['school_id'];
            $_SESSION['branch_id']   = $user['branch_id'];
            $_SESSION['school_code'] = $user['school_code'];
            $_SESSION['branch_name'] = $user['username'];

            header("Location: dashboard.php");
            exit;

        } else {
            $error = "Wrong password";
        }

    } else {
        $error = "User not found";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Login - Student App</title>

<style>
body {
    margin: 0;
    font-family: Arial, sans-serif;
    background: #f5f6fa;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
}
.login-box {
    background: #fff;
    padding: 30px;
    width: 350px;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0,0,0,0.2);
}
h2 {
    text-align: center;
    margin-bottom: 20px;
}
input {
    width: 100%;
    padding: 10px;
    margin: 10px 0;
    border: 1px solid #ccc;
    border-radius: 5px;
}
button {
    width: 100%;
    padding: 12px;
    background: #1abc9c;
    color: #fff;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 16px;
}
button:hover {
    background: #16a085;
}
.error {
    color: red;
    text-align: center;
    margin-top: 10px;
}
</style>
</head>

<body>

<div class="login-box">
    <h2>Login</h2>

    <form method="POST">
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Login</button>
    </form>

    <?php if ($error) echo "<p class='error'>$error</p>"; ?>
</div>

</body>
</html>
