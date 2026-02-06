<?php
include 'config.php';
session_start();
if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit;
}

// Fetch schools
$result = mysqli_query($conn, "SELECT * FROM schools");
?>

<!DOCTYPE html>
<html>
<head>
    <title>School List</title>
    <style>
        body { font-family: Arial; padding: 20px; }
        table { width: 50%; border-collapse: collapse; margin-top: 20px; }
        table, th, td { border: 1px solid black; }
        th, td { padding: 10px; text-align: center; }
        a.button { padding: 10px 20px; background: #4CAF50; color: #fff; text-decoration: none; }
    </style>
</head>
<body>

<h2>School List</h2>

<a class="button" href="add_school.php">+ Add School</a>

<table>
    <tr>
        <th>ID</th>
        <th>School Name</th>
        <th>Code</th>
        <th>Address</th>
    </tr>

    <?php while($row = mysqli_fetch_assoc($result)) { ?>
    <tr>
        <td><?= $row['id'] ?></td>
        <td><?= $row['school_name'] ?></td>
        <td><?= $row['school_code'] ?></td>
        <td><?= $row['address'] ?></td>
    </tr>
    <?php } ?>

</table>

</body>
</html>