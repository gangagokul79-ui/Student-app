<?php
include 'config.php';

// Fetch all classes with teacher name and time slot
$sql = "SELECT c.id, c.class_name, t.teacher_name, ts.slot_name
        FROM classes c
        JOIN teachers t ON c.teacher_id = t.id
        JOIN time_slot ts ON c.time_slot_id = ts.id
        ORDER BY c.id ASC";

$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Class List</title>
    <style>
        body { font-family: Arial; background-color: #f9f9f9; }
        h2 { text-align: center; margin-top: 30px; }
        .container { width: 80%; margin: 20px auto; }
        table { border-collapse: collapse; width: 100%; margin-top: 20px; }
        th, td { border: 1px solid #ccc; padding: 10px; text-align: center; }
        th { background-color: #f2f2f2; }
        a { text-decoration: none; color: #007BFF; }
        a:hover { text-decoration: underline; }
        .add-class { margin-bottom: 10px; display: inline-block; }
    </style>
</head>
<body>

<div class="container">
    <h2>Class List</h2>
    <a href="add_class.php" class="add-class">+ Add New Class</a>
    
    <table>
        <tr>
            <th>Class Name</th>
            <th>Teacher</th>
            <th>Time Slot</th>
        </tr>
        <?php if(mysqli_num_rows($result) > 0) { ?>
            <?php while($row = mysqli_fetch_assoc($result)) { ?>
                <tr>
                    <td><?= htmlspecialchars($row['class_name']); ?></td>
                    <td><?= htmlspecialchars($row['teacher_name']); ?></td>
                    <td><?= htmlspecialchars($row['slot_name']); ?></td>
                </tr>
            <?php } ?>
        <?php } else { ?>
            <tr>
                <td colspan="3">No classes found.</td>
            </tr>
        <?php } ?>
    </table>
</div>

</body>
</html>