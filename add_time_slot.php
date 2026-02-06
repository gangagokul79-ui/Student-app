<?php
include 'config.php';
session_start();

// Example: logged-in user
$_SESSION['username'] = 'admin'; // neenga login username use pannalam
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Time Slot</title>
    <style>
        body { font-family: Arial; }
        .container { width: 400px; margin: 40px auto; }
        input { width: 100%; padding: 8px; margin: 10px 0; }
    </style>
</head>
<body>

<div class="container">
    <h2>Add Time Slot</h2>

    <form action="save_time_slot.php" method="POST">
        <label>Slot Name:</label>
        <input type="text" name="slot_name" required>

        <label>Start Time:</label>
        <input type="time" name="start_time" required>

        <label>End Time:</label>
        <input type="time" name="end_time" required>

        <button type="submit">Save Time Slot</button>
    </form>
</div>

</body>
</html>