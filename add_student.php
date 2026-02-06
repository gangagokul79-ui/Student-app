<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$branch_id   = $_SESSION['branch_id'];
$school_id   = $_SESSION['school_id'];
$school_code = strtoupper($_SESSION['school_code']); 
$created_by  = $_SESSION['user_id'];

$success = "";
$error   = "";

function getNextRollNumber($conn, $school_code, $branch_id) {
    $stmt = $conn->prepare("
        SELECT roll_number 
        FROM students 
        WHERE branch_id = ? 
        ORDER BY id DESC 
        LIMIT 1
    ");
    $stmt->bind_param("i", $branch_id);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res && $res->num_rows > 0) {
        $row = $res->fetch_assoc();
        preg_match('/^(\d+)/', $row['roll_number'], $m);
        $next = intval($m[1]) + 1;
    } else {
        $next = 1;
    }
    return $next . $school_code;
}

$nextRoll = getNextRollNumber($conn, $school_code, $branch_id);

if (isset($_POST['submit'])) {

    $student_name = trim($_POST['student_name']);
    $gender_id    = $_POST['gender_id'];
    $sub1         = $_POST['sub1'];
    $sub2         = $_POST['sub2'];
    $sub3         = $_POST['sub3'];
    $sub4         = $_POST['sub4'];

    $roll_no = $nextRoll;

    // **Fetch branch info for backend only**
    $branch_sql = "SELECT branch_code, branch_name FROM branches WHERE id='$branch_id'";
    $branch_res = mysqli_query($conn, $branch_sql);
    $branch_data = mysqli_fetch_assoc($branch_res);
    $branch_code = $branch_data['branch_code'];
    $branch_name = $branch_data['branch_name'];

    $sql = "INSERT INTO students
            (student_name, gender_id, roll_number,
             branch_id, school_id,
             subject1, subject2, subject3, subject4,
             branch_code, branch_name,
             created_by, created_on)
            VALUES
            ('$student_name', '$gender_id', '$roll_no',
             '$branch_id', '$school_id',
             '$sub1', '$sub2', '$sub3', '$sub4',
             '$branch_code', '$branch_name',
             '$created_by', NOW())";

    if (mysqli_query($conn, $sql)) {
        $success = "Student added successfully";
    } else {
        $error = "Error : " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Student</title>
    <link rel="stylesheet" href="css/common.css">
    <style>
        *{box-sizing:border-box;margin:0;padding:0;}
        body{font-family:Arial,sans-serif;background:#f5f6fa;}
        .sidebar{width:240px;height:100vh;background:#2c3e50;position:fixed;top:0;left:0;padding-top:20px;}
        .sidebar a, .sidebar button{display:block;width:100%;padding:14px 20px;color:#ecf0f1;text-decoration:none;background:none;border:none;text-align:left;font-size:15px;cursor:pointer;}
        .sidebar a:hover, .sidebar button:hover{background:#1abc9c;color:#fff;}
        .arrow{float:right;}
        .dropdown-container{background:#34495e;}
        .dropdown-container a{padding-left:40px;font-size:14px;}
        .content{margin-left:240px;padding-top:60px;display:flex;justify-content:center;align-items:flex-start;min-height:100vh;}
        .form-box{position:relative;width:520px;background:#fff;padding:40px 30px 28px;border-radius:12px;box-shadow:0 6px 18px rgba(0,0,0,0.15);}
        .form-box h2{text-align:center;margin-bottom:25px;}
        input, select{width:100%;height:40px;margin-bottom:15px;padding:5px 10px;border-radius:6px;border:1px solid #ccc;}
        .form-row{display:grid;grid-template-columns: 1fr 1fr;gap:15px;margin-bottom:15px;}
        button{width:100%;height:42px;background:#1abc9c;color:#fff;border:none;border-radius:6px;cursor:pointer;}
        button:hover{background:#16a085;}
        .toast-success{position:absolute;top:20px;right:20px;background-color:#28a745;color:#fff;padding:12px 20px;border-radius:6px;font-size:14px;font-weight:bold;z-index:999;box-shadow:0 4px 12px rgba(0,0,0,0.15);}
        .error{color:red;text-align:center;margin-bottom:10px;}
        .dropdown-container{display:none;}
    </style>
</head>

<body class="page add-student">
   
<div class="sidebar">
    <a href="dashboard.php">Dashboard</a>
    <a href="add_student.php">Add Student</a>
    <a href="add_class.php">Add Class</a>
    <a href="assign_class.php">Assign Class</a>
    <a href="student_report.php">Student Report</a>
    <a href="class_report.php">Class Report</a>
    <button class="dropdown-btn" type="button">
        Masters <span class="arrow">▼</span>
    </button>

    <div class="dropdown-container">
        <a href="master.php?type=teacher">Create Teacher</a>
        <a href="master.php?type=slot">Create Time Slot</a>
        <a href="master.php?type=gender">Create Gender</a>
    </div>

    <a href="logout.php">Logout</a>
</div>

<div class="content">
    <div class="form-box add-student">
        <div style="position:relative; margin-bottom:25px;">
            <h2>Add Student</h2>
            <a href="progress_card.php" style="position:absolute;right:0;top:0;background:#1abc9c;color:#fff;padding:6px 14px;border-radius:6px;font-size:13px;font-weight:bold;text-decoration:none;">
               View Students
            </a>
            <div style="font-size:13px; color:#888; margin-top:5px;">
                Roll Number : <?php echo $nextRoll; ?>
            </div>
        </div>

        <?php
        if ($success) echo "<div class='toast-success'>$success</div>";
        if ($error) echo "<p class='error'>$error</p>";
        ?>

        <form method="POST">

            <div class="form-row">
                <input list="students_list" id="student_name" name="student_name" placeholder="Student Name" autocomplete="off" required>
                <datalist id="students_list">
                    <?php
                    $result = mysqli_query($conn, "SELECT student_name FROM students WHERE branch_id='$branch_id'");
                    while($row = mysqli_fetch_assoc($result)){
                        echo "<option value='".$row['student_name']."'>";
                    }
                    ?>
                </datalist>

                <select name="gender_id" required>
                    <option value="">Select Gender</option>
                    <option value="1">Male</option>
                    <option value="2">Female</option>
                </select>
            </div>

            <div class="form-row">
                <input type="text" name="sub1" placeholder="Subject 1" required>
                <input type="text" name="sub2" placeholder="Subject 2" required>
            </div>

            <div class="form-row">
                <input type="text" name="sub3" placeholder="Subject 3" required>
                <input type="text" name="sub4" placeholder="Subject 4" required>
            </div>

            <button type="submit" name="submit">Add Student</button>

        </form>

    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function(){
    $('#student_name').on('input change', function(){
        var studentName = $(this).val().trim();
        if(studentName != ''){
            $.ajax({
                url: 'fetch_student.php',
                type: 'POST',
                data: {student_name: studentName},
                success: function(response){
                    if(response){
                        var data = JSON.parse(response);
                        if(Object.keys(data).length > 0){
                            $('select[name="gender_id"]').val(data.gender_id);
                            $('input[name="sub1"]').val(data.subject1);
                            $('input[name="sub2"]').val(data.subject2);
                            $('input[name="sub3"]').val(data.subject3);
                            $('input[name="sub4"]').val(data.subject4);
                        } else {
                            $('select[name="gender_id"]').val('');
                            $('input[name="sub1"]').val('');
                            $('input[name="sub2"]').val('');
                            $('input[name="sub3"]').val('');
                            $('input[name="sub4"]').val('');
                        }
                    }
                }
            });
        } else {
            $('select[name="gender_id"]').val('');
            $('input[name="sub1"]').val('');
            $('input[name="sub2"]').val('');
            $('input[name="sub3"]').val('');
            $('input[name="sub4"]').val('');
        }
    });
});
</script>
<script>
document.addEventListener("DOMContentLoaded", function () {
    var btn = document.querySelector(".dropdown-btn");
    var container = document.querySelector(".dropdown-container");

    btn.addEventListener("click", function () {
        container.style.display =
            container.style.display === "block" ? "none" : "block";
    });
});
</script>

</body>
</html>
