<?php
include 'config.php';
session_start();

// Example: logged-in user
$_SESSION['username'] = 'admin';

// Fetch all time slots
$result = mysqli_query($conn, "SELECT * FROM time_slot ORDER BY id ASC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Time Slot List</title>
    <style>
        body { font-family: Arial; }
        table { border-collapse: collapse; width: 80%; margin: 20px auto; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: center; }
        th { background-color: #f2f2f2; }
        a.button { padding: 5px 10px; background-color: #4CAF50; color: white; text-decoration: none; border-radius: 4px; }
        a.button:hover { background-color: #45a049; }
    </style>
</head>
<body>

<h2 style="text-align:center;">Time Slot List</h2>
<div style="width:80%; margin:auto; text-align:right;">
    <a class="button" href="add_time_slot.php">Add New Time Slot</a>
</div>

<table>
    <tr>
        <th>ID</th>
        <th>Slot Name</th>
        <th>Start Time</th>
        <th>End Time</th>
        <th>Created On</th>
        <th>Created By</th>
        <th>Actions</th>
    </tr>

    <?php while($row = mysqli_fetch_assoc($result)) { ?>
    <tr>
        <td><?= $row['id']; ?></td>
        <td><?= $row['slot_name']; ?></td>
        <td><?= $row['start_time']; ?></td>
        <td><?= $row['end_time']; ?></td>
        <td><?= $row['created_on']; ?></td>
        <td><?= $row['created_by']; ?></td>
        <td>
            <a href="edit_time_slot.php?id=<?= $row['id']; ?>">Edit</a> | 
            <a href="delete_time_slot.php?id=<?= $row['id']; ?>" onclick="return confirm('Are you sure?')">Delete</a>
        </td>
    </tr>
    <?php } ?>
</table>

</body>
</html>