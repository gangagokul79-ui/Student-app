<?php
session_start();
include 'config.php'; // DB connection

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

$branch_id   = $_SESSION['branch_id'];     
$school_code = strtoupper($_SESSION['school_code']);  
$branch_name = $_SESSION['branch_name'];  
$user_id     = $_SESSION['user_id'];   
$username    = $_SESSION['username'] ?? 'User'; // display name
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Dashboard</title>
<link rel="stylesheet" href="css/common.css">

<style>
body { margin:0; font-family: Arial, sans-serif; background:#f0f0f0; }

/* ===== SIDEBAR ===== */
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
.dropdown-container{display:none; background:#34495e;}
.dropdown-container a{padding-left:40px; font-size:14px;}
.dropdown-btn.active .arrow{transform: rotate(180deg);}

/* ===== CONTENT ===== */
.content{
    margin-left:240px; /* match sidebar width */
    min-height:100vh;
    display:flex;
    justify-content:center;
    align-items:flex-start;
    padding:40px 20px;
}

/* ===== DASHBOARD BOX ===== */
.box{
    width:600px;
    background:white;
    padding:40px 30px 30px 30px;
    border-radius:10px;
    box-shadow:0 4px 12px rgba(0,0,0,0.25);
    position:relative;
    text-align:center;
}
h2 { font-size:24px; color:#333; margin-bottom:15px; }
.welcome-msg { font-size:18px; color:#555; margin-bottom:10px; }
.branch-name { font-size:16px; color:grey; }

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
    <!-- Masters Dropdown -->
    <button class="dropdown-btn" type="button">
        Masters <span class="arrow">▼</span>
    </button>
    <div class="dropdown-container">
        <a href="master.php?page=teacher">Create Teacher</a>
        <a href="master.php?page=timeslot">Create Time Slot</a>
        <a href="master.php?page=gender">Create Gender</a>
    </div>
    <a href="logout.php">Logout</a>
</div>

<div class="content">
    <div id="main-content" class="box">
        <h2>Dashboard</h2>
        <p class="welcome-msg">Welcome <?php echo $username; ?>!</p>
        <p class="branch-name"><?php echo $branch_name; ?></p>
    </div>
</div>

<script>
// JS to toggle Masters dropdown
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
</script>

</body>
</html>
