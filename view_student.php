<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$branch_id = $_SESSION['branch_id'];

// Students list of same branch
$sql = "SELECT * FROM students WHERE branch_id='$branch_id' ORDER BY id ASC";
$result = mysqli_query($conn, $sql);

?>

<!DOCTYPE html>
<html>
<head>
    <title>View Students</title>
    <style>
        body { font-family: Arial; background-color: #f7f7f7; padding: 40px; }
        .container { width: 900px; margin: auto; background: #fff; padding: 25px; border-radius: 10px; box-shadow: 0 0 10px #ccc; }

        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 10px; border: 1px solid #ccc; text-align: center; }
        th { background-color: #007bff; color: white; }

        h2 { text-align: center; margin-bottom: 10px; }

        .btn { display: inline-block; background: #28a745; color: white; padding: 8px 18px; border-radius: 5px; text-decoration: none; }
        .btn:hover { background: #218838; }

        .view-btn {
            background: #0066ff;
            padding: 6px 12px;
            border-radius: 4px;
            color: white;
            font-size: 14px;
            text-decoration: none;
        }
        .view-btn:hover { background: #004ecc; }

    </style>
</head>
<body>

<div class="container">
    <h2>Branch <?php echo $branch_id; ?> - Students</h2>

    <a class="btn" href="add_student.php">Add Student</a>

    <table>
        <thead>
            <tr>
                <th>No.</th>
                <th>Roll No</th>
                <th>Name</th>
                <th>Gender</th>
                <th>View Progress</th>
            </tr>
        </thead>

        <tbody>
        <?php
        if (mysqli_num_rows($result) > 0) {
            $i = 1;
            while ($row = mysqli_fetch_assoc($result)) {
                echo "
                <tr>
                    <td>$i</td>
                    <td>{$row['roll_no']}</td>
                    <td>{$row['name']}</td>
                    <td>{$row['gender']}</td>

                    <td>
                        <a class='view-btn' href='progress_card.php?sid={$row['id']}'>
                            View
                        </a>
                    </td>
                </tr>
                ";
                $i++;
            }
        } else {
            echo "<tr><td colspan='5'>No students found</td></tr>";
        }
        ?>
        </tbody>
    </table>

</div>

</body>
</html>
