<?php
session_start();
include 'config.php';

$branch_id   = $_SESSION['branch_id'] ?? 1;
$branch_name = $_SESSION['branch_name'] ?? '';
$msg = "";
$class_name = "";

if(isset($_GET['q'])){
    $q = mysqli_real_escape_string($conn, $_GET['q']);
    $res = mysqli_query($conn,"
        SELECT student_name
        FROM students
        WHERE branch_id='$branch_id'
        AND student_name LIKE '$q%'
        LIMIT 10
    ");
    $names = [];
    while($r=mysqli_fetch_assoc($res)){
        $names[] = $r['student_name'];
    }
    echo json_encode($names);
    exit;
}

if(!isset($_SESSION['assigned_students'])){
    $_SESSION['assigned_students'] = [];
}

if(isset($_POST['remove_id'])){
    foreach($_SESSION['assigned_students'] as $k=>$s){
        if($s['id'] == $_POST['remove_id']){
            unset($_SESSION['assigned_students'][$k]);
            $_SESSION['assigned_students'] = array_values($_SESSION['assigned_students']);
            break;
        }
    }
}

if(isset($_POST['search_btn'])){
    $search     = mysqli_real_escape_string($conn, $_POST['student']);
    $class_name = mysqli_real_escape_string($conn, $_POST['class_name']);
    $_SESSION['selected_class_name'] = $class_name;

    $res = mysqli_query($conn,"
        SELECT id, roll_number, student_name, gender_id
        FROM students
        WHERE branch_id='$branch_id'
        AND student_name LIKE '%$search%'
    ");

    while($r=mysqli_fetch_assoc($res)){
        $exists = false;
        foreach($_SESSION['assigned_students'] as $as){
            if($as['id'] == $r['id']){
                $exists = true;
                break;
            }
        }
        if(!$exists){
            $_SESSION['assigned_students'][] = $r;
        }
    }
}

if(isset($_POST['assign_all_btn'])){
    $class_name = $_SESSION['selected_class_name'] ?? '';

    if($class_name == ''){
        $msg = "<div class='error'>Class name missing!</div>";
    } else {
        $cnt = 0;
        foreach($_SESSION['assigned_students'] as $s){

            $check = mysqli_query($conn,"
                SELECT COUNT(*) total
                FROM class_assignments
                WHERE student_name='{$s['student_name']}'
            ");
            if(mysqli_fetch_assoc($check)['total'] >= 7) continue;

            $dup = mysqli_query($conn,"
                SELECT COUNT(*) c
                FROM class_assignments
                WHERE student_name='{$s['student_name']}'
                AND class_name='$class_name'
            ");
            if(mysqli_fetch_assoc($dup)['c'] > 0) continue;

            mysqli_query($conn,"
                INSERT INTO class_assignments
                (roll_number, student_name, branch_id, branch_name, class_name, assigned_on)
                VALUES(
                    '{$s['roll_number']}',
                    '{$s['student_name']}',
                    '$branch_id',
                    '$branch_name',
                    '$class_name',
                    NOW()
                )
            ");
            if(mysqli_affected_rows($conn) > 0) $cnt++;
        }

        if($cnt > 0){
            $msg = "<div class='toast-success'>Assigned $cnt student(s) successfully!</div>";
        }

        $_SESSION['assigned_students'] = [];
        unset($_SESSION['selected_class_name']);
    }
}

$class_q = mysqli_query($conn,"
    SELECT class_name
    FROM classes
    WHERE branch_id='$branch_id'
");
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Assign Class</title>

<style>
*{box-sizing:border-box;margin:0;padding:0;}
body{
    font-family:Arial,sans-serif;
    background:#f5f6fa;
}

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
.dropdown-container{background:#34495e;}
.dropdown-container a{padding-left:40px;font-size:14px;}

.content{
    margin-left:240px;
    min-height:100vh;
    display:flex;
    justify-content:center;
    align-items:flex-start;
    padding:60px 20px;
}

.form-box{
    width:520px;
    background:#fff;
    padding:40px 30px 28px;
    border-radius:12px;
    box-shadow:0 6px 18px rgba(0,0,0,0.15);
    position:relative;
}

.form-box h2{
    text-align:center;
    margin-bottom:40px;  
}

input,select{
    width:100%;
    height:40px;
    padding:8px 12px;
    border-radius:6px;
    border:1px solid #ccc;
}

select[name="class_name"] {
    color: #888888;  /* default grey */
    background: #ffffff;
}

/* When user selects a class, text becomes black */
select[name="class_name"]:valid {
    color: #000000;
}

.flex-row{
    display:grid;
    grid-template-columns:1fr 1.2fr auto;
    gap:16px;
}

button{
    padding:10px 28px;
    background:#1abc9c;
    color:#fff;
    border:none;
    border-radius:6px;
    cursor:pointer;
}
button.remove-btn{
    background:#ff5c5c;
}

.toast-success{
    position:absolute;
    top:10px;
    right:10px;
    background:#28a745;
    color:#fff;
    padding:12px 20px;
    border-radius:6px;
    font-weight:bold;
}

.error{
    text-align:center;
    font-weight:bold;
    margin-bottom:10px;
}

table{
    width:100%;
    border-collapse:collapse;
    margin-top:15px;
}
table th,table td{
    border:1px solid #ccc;
    padding:8px;
    text-align:center;
}
table th{
    background:#f2f2f2;
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

<h2>Assign Class</h2>
<?php if($msg) echo $msg; ?>

<form method="post">
<div class="flex-row">
<select name="class_name" required>
<option value="" disabled selected>Select Class</option>
<?php
while($r=mysqli_fetch_assoc($class_q)){
$sel = (($_SESSION['selected_class_name'] ?? '')==$r['class_name'])?'selected':''; 
echo "<option value='{$r['class_name']}' $sel>{$r['class_name']}</option>";
}
?>
</select>

<div style="position:relative;">
<input type="text" name="student" id="student_search"
       placeholder="Search student" autocomplete="off" required>
<div id="suggestions"
style="position:absolute;top:42px;width:100%;
background:#fff;border:1px solid #ccc;
max-height:150px;overflow-y:auto;display:none;z-index:1000;"></div>
</div>

<button name="search_btn">Search</button>
</div>
</form>

<?php if(!empty($_SESSION['assigned_students'])){ ?>
<table>
<tr>
<th>S.No</th>
<th>Roll No</th>
<th>Student Name</th>
<th>Gender</th>
<th>Action</th>
</tr>

<?php
$i=1;
foreach($_SESSION['assigned_students'] as $s){
$gender = ($s['gender_id']==1)?"Male":(($s['gender_id']==2)?"Female":"Other");
?>
<tr>
<td><?= $i++ ?></td>
<td><?= $s['roll_number'] ?></td>
<td><?= $s['student_name'] ?></td>
<td><?= $gender ?></td>
<td>
<form method="post">
<input type="hidden" name="remove_id" value="<?= $s['id'] ?>">
<button class="remove-btn">Remove</button>
</form>
</td>
</tr>
<?php } ?>
</table>

<form method="post" style="text-align:center;margin-top:12px;">
<button name="assign_all_btn">Assign All</button>
</form>
<?php } ?>

</div>
</div>

<script>
const input=document.getElementById("student_search");
const box=document.getElementById("suggestions");

input.addEventListener("keyup",()=>{
let val=input.value.trim();
if(val.length<1){box.style.display="none";return;}
fetch("?q="+encodeURIComponent(val))
.then(r=>r.json())
.then(d=>{
box.innerHTML="";
if(d.length==0){box.style.display="none";return;}
d.forEach(n=>{
let div=document.createElement("div");
div.textContent=n;
div.style.padding="8px";
div.style.cursor="pointer";
div.onclick=()=>{input.value=n;box.style.display="none";}
box.appendChild(div);
});
box.style.display="block";
});
});

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
