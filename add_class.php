<?php
session_start();
include 'config.php';

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

$branch_id = $_SESSION['branch_id'];
$msg = "";

if(isset($_POST['submit'])){
    $class_name   = mysqli_real_escape_string($conn, $_POST['class_name']);
    $teacher_id   = $_POST['teacher_id'];
    $time_slot_id = $_POST['time_slot_id']; // MUST be ID

    if($class_name != "" && $teacher_id != "" && $time_slot_id != ""){
        $insert = "INSERT INTO classes (class_name, teacher_id, time_slot_id, branch_id)
                   VALUES ('$class_name','$teacher_id','$time_slot_id','$branch_id')";
        if(mysqli_query($conn,$insert)){
            $msg = "✔ Class added successfully!";
        }else{
            $msg = "Error occurred: " . mysqli_error($conn);
        }
    }else{
        $msg = "Please fill all fields";
    }
}

$teachers = mysqli_query($conn,"SELECT id, teacher_name FROM teachers ORDER BY teacher_name");

$slots = mysqli_query($conn,"SELECT id, slot_name FROM time_slots ORDER BY slot_name");
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Add Class</title>
<link rel="stylesheet" href="css/common.css">

<style>
*{box-sizing:border-box;margin:0;padding:0;}
body{font-family:Arial,sans-serif;background:#f5f6fa;}

.sidebar{
    width:240px;
    height:100vh;
    background:#2c3e50;
    position:fixed;
    top:0;
    left:0;
    padding-top:20px;
}
.sidebar a,
.sidebar button{
    display:block;
    width:100%;
    padding:14px 20px;
    color:#ecf0f1;
    text-decoration:none;
    background:none;
    border:none;
    text-align:left;
    font-size:15px;
    cursor:pointer;
}
.sidebar a:hover,
.sidebar button:hover{
    background:#1abc9c;
    color:#fff;
}
.arrow{float:right;}
.dropdown-container{background:#34495e; display:none;} /* default hide */

.content{
    margin-left:240px;
    padding-top:60px;
    display:flex;
    justify-content:center;
    align-items:flex-start;
    min-height:100vh;
}

.form-box{
    position:relative;
    width:520px;
    background:#fff;
    padding:40px 30px 28px;
    border-radius:12px;
    box-shadow:0 6px 18px rgba(0,0,0,0.15);
}
.form-box h2{
    text-align:center;
    margin-bottom:40px;
}
input, select{
    width:100%;
    height:40px;
    margin-bottom:15px;
    padding:5px 10px;
    border-radius:6px;
    border:1px solid #ccc;
}
.flex-row{
    display:grid;
    grid-template-columns:1fr 1fr;
    gap:20px;
}
button{
    width:100%;
    height:42px;
    background:#1abc9c;
    color:#fff;
    border:none;
    border-radius:6px;
    cursor:pointer;
}
button:hover{ background:#16a085; }

.toast-success{
    position:absolute;
    top:20px;
    right:20px;
    background-color:#28a745;
    color:#fff;
    padding:12px 20px;
    border-radius:6px;
    font-size:14px;
    font-weight:bold;
}
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
    <div class="form-box">
        <h2>Add Class</h2>

        <?php if($msg!=""){ ?>
            <div class="toast-success"><?php echo $msg; ?></div>
        <?php } ?>

        <form method="POST">
            <input type="text" name="class_name" placeholder="Class Name" required>

            <div class="flex-row">
                <select name="teacher_id" required>
                    <option value="">Select Teacher</option>
                    <?php
                    while($t = mysqli_fetch_assoc($teachers)){
                        echo "<option value='{$t['id']}'>{$t['teacher_name']}</option>";
                    }
                    ?>
                </select>

                <select name="time_slot_id" required>
                    <option value="">Select Time Slot</option>
                    <?php
                    while($s = mysqli_fetch_assoc($slots)){
                        echo "<option value='{$s['id']}'>{$s['slot_name']}</option>";
                    }
                    ?>
                </select>
            </div>

            <button type="submit" name="submit">Add Class</button>
        </form>
    </div>
</div>

<script>
var dropdowns = document.getElementsByClassName("dropdown-btn");
for (var i = 0; i < dropdowns.length; i++) {
    dropdowns[i].addEventListener("click", function() {
        this.classList.toggle("active");
        var dropdownContent = this.nextElementSibling;
        if(dropdownContent.style.display === "block"){
            dropdownContent.style.display = "none";
        } else {
            dropdownContent.style.display = "block";
        }
    });
}

window.onload = function(){
    var masterDropdown = document.querySelector(".dropdown-btn");
    var dropdownContent = masterDropdown.nextElementSibling;
    dropdownContent.style.display = "none"; 
};
</script>

</body>
</html>
