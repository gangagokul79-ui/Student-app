<?php
session_start();
include 'config.php';

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

$id = $_GET['id'];

$q = mysqli_query($conn,"
    SELECT s.*, g.gender_name
    FROM students s
    LEFT JOIN gender g ON g.id=s.gender_id
    WHERE s.id='$id'
");
$data = mysqli_fetch_assoc($q);

if(isset($_POST['update'])){
    $name   = $_POST['student_name'];
    $gender = $_POST['gender_id'];
    $sub1   = $_POST['sub1'];
    $sub2   = $_POST['sub2'];
    $sub3   = $_POST['sub3'];
    $sub4   = $_POST['sub4'];

    mysqli_query($conn,"
        UPDATE students SET
        student_name='$name',
        gender_id='$gender',
        subject1='$sub1',
        subject2='$sub2',
        subject3='$sub3',
        subject4='$sub4'
        WHERE id='$id'
    ");

    header("Location: progress_card.php");
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Edit Student</title>

<style>
body{margin:0;font-family:Arial;background:#f0f0f0}

/* SIDEBAR */
.sidebar{
    width:220px;background:#2c3e50;height:100vh;
    position:fixed;padding-top:20px;
}
.sidebar a{display:block;padding:15px;color:white;text-decoration:none}
.sidebar a:hover{background:#1abc9c}

/* CONTENT */
.content{
    margin-left:230px;min-height:100vh;
    display:flex;justify-content:center;
    align-items:flex-start;padding:40px 20px;
}
.form-box{
    width:600px;background:white;
    padding:40px 30px;border-radius:10px;
    box-shadow:0 4px 12px rgba(0,0,0,.25);
}

/* HEADER */
h2{text-align:center}

/* ROLL DISPLAY */
.roll-display{
    color:grey;
    font-weight:bold;
    margin-bottom:20px;
}

/* INPUTS */
.flex-row{display:flex;gap:15px;margin-bottom:15px}
.flex-row input,.flex-row select{
    flex:1;padding:12px;border-radius:5px;
    border:1px solid #bbb;font-size:15px;
    color:black;
}

/* BUTTON */
button{
    width:30%;padding:9px;
    background:#1abc9c;color:white;
    border:none;border-radius:5px;
    font-weight:bold;cursor:pointer
}
button:hover{background:#16a085}
</style>

</head>

<body>

<div class="sidebar">
<a href="dashboard.php">Dashboard</a>
<a href="add_student.php">Add Student</a>
<a href="add_class.php">Add Class</a>
<a href="assign_class.php">Assign Class</a>
<a href="student_report.php">Student Report</a>
<a href="class_report.php">Class Report</a>
<a href="logout.php">Logout</a>
</div>

<div class="content">
<div class="form-box">

<h2>Edit Student</h2>

<!-- 🔒 ROLL NUMBER – NOT EDITABLE -->
<div class="roll-display">
Roll Number : <?php echo $data['roll_number']; ?>
</div>

<form method="POST">

<div class="flex-row">
<input type="text" name="student_name"
value="<?php echo $data['student_name']; ?>" required>

<select name="gender_id" required>
<option value="1" <?php if($data['gender_id']==1) echo "selected"; ?>>Male</option>
<option value="2" <?php if($data['gender_id']==2) echo "selected"; ?>>Female</option>
</select>
</div>

<div class="flex-row">
<input type="text" name="sub1" value="<?php echo $data['subject1']; ?>" required>
<input type="text" name="sub2" value="<?php echo $data['subject2']; ?>" required>
</div>

<div class="flex-row">
<input type="text" name="sub3" value="<?php echo $data['subject3']; ?>" required>
<input type="text" name="sub4" value="<?php echo $data['subject4']; ?>" required>
</div>

<div style="text-align:center;margin-top:25px">
<button type="submit" name="update">Update</button>
</div>

</form>

</div>
</div>

</body>
</html>
