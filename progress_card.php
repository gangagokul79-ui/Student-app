<?php
session_start();
if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit;
}
include 'config.php';

$branch_id = $_SESSION['branch_id'];
$school_id = $_SESSION['school_id'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8" />
<title>Progress Card List</title>
<meta name="viewport" content="width=device-width,initial-scale=1" />
<style>
body{
    font-family: Arial, sans-serif;
    background:#f2f2f2;
    margin:0;
}

/* ================= SIDEBAR ================= */
.sidebar{
    width:220px;
    background:#2c3e50;
    height:100vh;
    position:fixed;
    top:0;
    left:0;
    padding-top:20px;
}
.sidebar a{
    display:block;
    padding:15px;
    color:white;
    text-decoration:none;
}
.sidebar a:hover{
    background:#1abc9c; /* green hover */
}
.dropdown-btn{
    display:block;
    padding:15px;
    width:100%;
    color:white;
    background:none;
    border:none;
    text-align:left;
    cursor:pointer;
    font-size:15px;
}
.dropdown-btn:hover{ background:#1abc9c; }
.dropdown-container{
    display:none;
    background:#34495e;
}
.dropdown-container a{
    padding:10px 30px;
    display:block;
    color:#fff;
    text-decoration:none;
    font-size:13px;
}
.dropdown-container a.active,
.dropdown-container a:hover{ background:#3d566e; }
.arrow{float:right;}

/* ================= CONTENT ================= */
.content{
    margin-left:230px;
    padding:20px;
}
.container{
    max-width:1100px;
    margin:0 auto;
    background:#fff;
    padding:20px;
    border-radius:10px;
    box-shadow:0 0 12px #ccc;
}

/* Table styling */
table{
    width:100%;
    border-collapse:collapse;
    margin-top:10px;
}
th, td{
    padding:10px;
    text-align:center;
    border-bottom:1px solid #e0e0e0;
    font-size:14px;
}
th{
    background:#1abc9c; /* Green header to match sidebar */
    color:#fff;
    font-weight:600;
}
tr:hover td{ background:#fafafa; }
.action{ display:flex; justify-content:flex-start; align-items:center; padding-left:25px; }
.action a{ margin:0 6px; text-decoration:none; font-size:18px; color:#007bff; }
.deleted-row{ background:#ffdfdf!important; }

/* Popup overlay */
.overlay{
    display:none;
    position:fixed;
    inset:0;
    background:rgba(0,0,0,0.45);
    z-index:9999;
    justify-content:center;
    align-items:center;
    padding:20px;
}
.popup{
    width:360px;
    max-width:95%;
    background:#fff;
    padding:18px;
    border-radius:10px;
    position:relative;
    box-shadow:0 10px 30px rgba(0,0,0,0.15);
}
.cross-btn{
    position:absolute;
    top:-12px;
    left:50%;
    transform:translateX(-50%);
    background:#fff;
    border-radius:50%;
    width:30px;
    height:30px;
    display:flex;
    justify-content:center;
    align-items:center;
    box-shadow:0 2px 6px rgba(0,0,0,0.12);
    cursor:pointer;
    font-size:18px;
}
.popup h2{text-align:center;font-size:18px;}
.detail{display:flex;margin-bottom:8px;}
.heading{width:120px;font-weight:700;color:#333;}
.value{flex:1;color:#222;}
.note{font-size:13px;color:#666;margin-top:8px;text-align:center;}
.btn{padding:8px 12px;border-radius:6px;border:none;cursor:pointer;font-weight:600;}
.btn-primary{background:#28a745;color:#fff;}
.btn-secondary{background:#e0e0e0;color:#333;margin-left:8px;}
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
    <div class="dropdown-container"></div>
    <a href="logout.php">Logout</a>
</div>

<div class="content">
<div class="container">
<h2>Progress Card List</h2>

<table>
<thead>
<tr>
<th>No.</th>
<th>Roll No</th>
<th>Name</th>
<th>Gender</th>
<th>Subject 1</th>
<th>Subject 2</th>
<th>Subject 3</th>
<th>Subject 4</th>
<th>Actions</th>
</tr>
</thead>
<tbody>
<?php
$sql = "SELECT * FROM students 
        WHERE branch_id=? AND school_id=? 
        ORDER BY id DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $branch_id, $school_id);
$stmt->execute();
$res = $stmt->get_result();

$i = 1;
while($row = $res->fetch_assoc()){

    $gender = ($row['gender_id']==1) ? "Male" : (($row['gender_id']==2) ? "Female" : "-");
    $id = (int)$row['id'];
    $status = (int)$row['status'];
    $row_class = ($status==0) ? "deleted-row" : "";

    $actions = ($status==0) 
        ? "<a href='javascript:void(0)' onclick='openViewPopup($id)'>👁️</a>" 
        : "<a href='javascript:void(0)' onclick='openViewPopup($id)'>👁️</a>
           <a href='edit_progress.php?id=$id'>✏️</a>
           <a href='javascript:void(0)' onclick='openDeletePopup($id)'>❌</a>";

    echo "<tr id='row-$id' class='$row_class'
        data-roll='{$row['roll_number']}'
        data-name='{$row['student_name']}'
        data-gender='$gender'
        data-sub1='{$row['subject1']}'
        data-sub2='{$row['subject2']}'
        data-sub3='{$row['subject3']}'
        data-sub4='{$row['subject4']}'>
        <td>$i</td>
        <td>{$row['roll_number']}</td>
        <td>{$row['student_name']}</td>
        <td>$gender</td>
        <td>{$row['subject1']}</td>
        <td>{$row['subject2']}</td>
        <td>{$row['subject3']}</td>
        <td>{$row['subject4']}</td>
        <td class='action'>$actions</td>
    </tr>";
    $i++;
}
?>
</tbody>
</table>
<p class="note">👁️ View | ✏️ Edit | ❌ Delete</p>
</div>
</div>

<script>
function openViewPopup(id){
    const r=document.getElementById('row-'+id);
    document.getElementById('viewOverlay').style.display='flex';
    document.getElementById('v-roll').innerText=r.dataset.roll;
    document.getElementById('v-name').innerText=r.dataset.name;
    document.getElementById('v-gender').innerText=r.dataset.gender;
    document.getElementById('v-s1').innerText=r.dataset.sub1;
    document.getElementById('v-s2').innerText=r.dataset.sub2;
    document.getElementById('v-s3').innerText=r.dataset.sub3;
    document.getElementById('v-s4').innerText=r.dataset.sub4;
}
function closeViewPopup(){
    document.getElementById('viewOverlay').style.display='none';
}

function openDeletePopup(id){
    let reason = prompt("Enter reason for deletion:");
    if(reason && reason.trim()!=""){
        fetch('delete_student.php',{
            method:'POST',
            headers:{'Content-Type':'application/x-www-form-urlencoded'},
            body:'id='+id+'&reason='+encodeURIComponent(reason)
        }).then(res=>res.text()).then(data=>{
            alert(data);
            const row = document.getElementById('row-'+id);
            row.classList.add('deleted-row');
            row.querySelector('.action').innerHTML = "<a href='javascript:void(0)' onclick='openViewPopup("+id+")'>👁️</a>";
        });
    }
}
</script>

<div id="viewOverlay" class="overlay">
<div class="popup">
<div class="cross-btn" onclick="closeViewPopup()">×</div>
<h2>Student Details</h2>
<div class="detail"><span class="heading">Roll :</span><span class="value" id="v-roll"></span></div>
<div class="detail"><span class="heading">Name :</span><span class="value" id="v-name"></span></div>
<div class="detail"><span class="heading">Gender :</span><span class="value" id="v-gender"></span></div>
<div class="detail"><span class="heading">Sub 1 :</span><span class="value" id="v-s1"></span></div>
<div class="detail"><span class="heading">Sub 2 :</span><span class="value" id="v-s2"></span></div>
<div class="detail"><span class="heading">Sub 3 :</span><span class="value" id="v-s3"></span></div>
<div class="detail"><span class="heading">Sub 4 :</span><span class="value" id="v-s4"></span></div>
<button class="btn btn-secondary" onclick="closeViewPopup()">Close</button>
</div>
</div>
</body>
</html>
