<?php
session_start();
include 'config.php';

$type = $_GET['type'] ?? 'teacher';
$showToast = false;
$toastMsg = "";

// ===== Create Teacher =====
if(isset($_POST['save_teacher'])){
    $school_id  = $_SESSION['school_id'] ?? 0;   // session school_id
    $branch_id  = $_SESSION['branch_id'] ?? 0;   // session branch_id
    $created_by = $_SESSION['user_id'] ?? 0;     // session user_id

    mysqli_query($conn,"
        INSERT INTO teachers 
        (teacher_name, gender_id, phone_number, school_id, branch_id, created_by, created_on)
        VALUES (
            '".mysqli_real_escape_string($conn,$_POST['teacher_name'])."',
            '".mysqli_real_escape_string($conn,$_POST['gender_id'])."',
            '".mysqli_real_escape_string($conn,$_POST['phone_number'])."',
            '$school_id',
            '$branch_id',
            '$created_by',
            NOW()
        )
    ");
    $showToast = true;
    $toastMsg = "Teacher Created Successfully";
}

// ===== Create Gender =====
if(isset($_POST['save_gender'])){
    mysqli_query($conn,"
        INSERT INTO gender (gender_name, status)
        VALUES (
            '".mysqli_real_escape_string($conn,$_POST['gender_name'])."',
            '".mysqli_real_escape_string($conn,$_POST['status'])."'
        )
    ");
    $showToast = true;
    $toastMsg = "Gender Created Successfully";
}

// ===== Create Time Slot =====
if(isset($_POST['save_slot'])){
    $branch_id   = $_SESSION['branch_id'] ?? 0;   // get branch_id from session
    $created_by  = $_SESSION['user_id'] ?? 0;     // get user_id from session

    // Fetch branch_code from database
    $branch_code = '';
    if($branch_id > 0){
        $res = mysqli_query($conn,"SELECT branch_code FROM branches WHERE id='$branch_id'");
        if($row = mysqli_fetch_assoc($res)){
            $branch_code = $row['branch_code'];
        }
    }

    mysqli_query($conn,"
        INSERT INTO time_slots 
        (slot_name, start_time, end_time, branch_id, branch_code, created_by, created_on)
        VALUES (
            '".mysqli_real_escape_string($conn,$_POST['slot_name'])."',
            '".mysqli_real_escape_string($conn,$_POST['start_time'])."',
            '".mysqli_real_escape_string($conn,$_POST['end_time'])."',
            '$branch_id',
            '$branch_code',
            '$created_by',
            NOW()
        )
    ");
    $showToast = true;
    $toastMsg = "Time Slot Created Successfully";
}

// ===== Fetch active genders =====
$genderQuery = mysqli_query($conn,"
    SELECT id, gender_name 
    FROM gender 
    WHERE status = 1
");

// ===== Fetch time slots =====
$slotQuery = mysqli_query($conn,"
    SELECT slot_name 
    FROM time_slots
");
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Masters</title>
<link rel="stylesheet" href="css/common.css">
<style>
body{margin:0;font-family:Arial,sans-serif;background:#f0f0f0;}
.content{margin-left:240px;padding:60px 20px;min-height:100vh;display:flex;justify-content:center;align-items:flex-start;}
.form-box{width:520px;background:#fff;padding:40px 30px 28px;border-radius:12px;box-shadow:0 6px 18px rgba(0,0,0,0.15);position:relative;margin:auto;}
input,select{width:100%;height:40px;padding:8px 12px;margin-bottom:18px;border-radius:6px;border:1px solid #ccc;font-size:14px;}
button{padding:10px 28px;font-weight:bold;background:#1abc9c;color:#fff;border:none;border-radius:6px;cursor:pointer;}
button:hover{background:#16a085;}
label{font-weight:bold;margin-bottom:5px;display:block;}
.toast-success{position:absolute;top:10px;right:10px;background:#28a745;color:#fff;padding:12px 20px;border-radius:6px;font-weight:bold;z-index:999;box-shadow:0 4px 12px rgba(0,0,0,0.15);}

.sidebar{width:240px;height:100vh;background:#2c3e50;position:fixed;top:0;left:0;padding-top:20px;}
.sidebar a,.sidebar button{display:block;width:100%;padding:14px 20px;color:#ecf0f1;text-decoration:none;background:none;border:none;text-align:left;font-size:15px;cursor:pointer;}
.sidebar a:hover,.sidebar button:hover{background:#1abc9c;color:#fff;}
.dropdown-container{display:none;background:#34495e;}
.dropdown-container a{padding:10px 30px;display:block;color:#fff;text-decoration:none;font-size:13px;}
.dropdown-container a.active,.dropdown-container a:hover{background:#3d566e;}
.arrow{float:right;}
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

    <button class="dropdown-btn">Masters <span class="arrow">▼</span></button>
    <div class="dropdown-container">
        <a href="master.php?type=teacher" class="<?= ($type=='teacher')?'active':'' ?>">Create Teacher</a>
        <a href="master.php?type=slot" class="<?= ($type=='slot')?'active':'' ?>">Create Time Slot</a>
        <a href="master.php?type=gender" class="<?= ($type=='gender')?'active':'' ?>">Create Gender</a>
    </div>

    <a href="logout.php">Logout</a>
</div>

<div class="content">
<div class="form-box">

<?php if($showToast){ ?>
    <div class="toast-success">✅ <?php echo $toastMsg; ?></div>
<?php } ?>

<!-- ===== Teacher Form ===== -->
<?php if($type=='teacher'){ ?>
<h3 style="text-align:center;margin-bottom:20px;">Create Teacher</h3>
<form method="post">
    <input type="text" name="teacher_name" placeholder="Enter Teacher Name" required>
    <select name="gender_id" required>
        <option value="">Select Gender</option>
        <?php while($g=mysqli_fetch_assoc($genderQuery)){ ?>
            <option value="<?php echo $g['id']; ?>">
                <?php echo $g['gender_name']; ?>
            </option>
        <?php } ?>
    </select>
    <input type="text" name="phone_number" placeholder="Enter Phone Number" required>
    <button name="save_teacher">Save Teacher</button>
</form>
<?php } ?>

<!-- ===== Gender Form ===== -->
<?php if($type=='gender'){ ?>
<h3 style="text-align:center;margin-bottom:20px;">Create Gender</h3>
<form method="post">
    <input type="text" name="gender_name" placeholder="Enter Gender Name" required>
    <select name="status">
        <option value="1">Active</option>
        <option value="0">Inactive</option>
    </select>
    <button name="save_gender">Save Gender</button>
</form>
<?php } ?>

<!-- ===== Time Slot Form ===== -->
<?php if($type=='slot'){ ?>
<h3 style="text-align:center;margin-bottom:20px;">Create Time Slot</h3>
<form method="post">
    <label>Slot Name</label>
    <input type="text" name="slot_name" required>
    <label>From Time</label>
    <input type="time" name="start_time" required>
    <label>To Time</label>
    <input type="time" name="end_time" required>
    <button name="save_slot">Save Time Slot</button>
</form>
<?php } ?>

</div>
</div>

<script>
setTimeout(()=>{
    let t=document.querySelector('.toast-success');
    if(t) t.style.display='none';
},3000);

var dropdowns=document.getElementsByClassName("dropdown-btn");
for(var i=0;i<dropdowns.length;i++){
    dropdowns[i].addEventListener("click", function(){
        this.classList.toggle("active");
        var container=this.nextElementSibling;
        container.style.display=(container.style.display==='block')?'none':'block';
    });
}

window.addEventListener("DOMContentLoaded", ()=>{
    var activeLink=document.querySelector(".dropdown-container a.active");
    if(activeLink){
        activeLink.parentElement.style.display="block";
        activeLink.parentElement.previousElementSibling.classList.add("active");
    }
});
</script>

</body>
</html>
