<?php
session_start();
include 'config.php';

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

$branch_id = $_SESSION['branch_id'];

$classQuery = mysqli_query($conn,"
    SELECT class_name 
    FROM classes 
    WHERE branch_id='$branch_id'
    ORDER BY class_name
");
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Class Report</title>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

<style>
*{box-sizing:border-box;margin:0;padding:0;}
body{
    font-family:Arial,sans-serif;
    background:#f5f6fa;
}

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
.arrow{ float:right; }

.dropdown-container{
    display: none; /* hidden initially */
    background:#34495e;
}
.dropdown-container a{
    padding-left:40px;
    font-size:14px;
}

/* ===== CONTENT ===== */
.content{
    margin-left:240px;
    padding:60px 20px;
    display:flex;
    justify-content:center;
    align-items:flex-start;
}

/* ===== CARD ===== */
.form-container{
    width:620px;
    background:#fff;
    padding:25px;
    border-radius:10px;
    box-shadow:0 6px 18px rgba(0,0,0,.15);
}

/* ===== TITLE ===== */
.title-bar{
    position:relative;
    text-align:center;
    margin-bottom:15px;
}

/* ===== DOWNLOAD ===== */
.download-box{
    position:absolute;
    right:0;
    top:0;
}
.download-btn{
    background:#1abc9c;
    color:#fff;
    border:none;
    padding:6px 16px;
    border-radius:6px;
    cursor:pointer;
}
.download-menu{
    display:none;
    position:absolute;
    right:0;
    top:40px;
    background:#fff;
    border-radius:6px;
    box-shadow:0 4px 10px rgba(0,0,0,.15);
    z-index:10;
}
.download-menu form button{
    width:100%;
    border:none;
    background:#fff;
    padding:10px;
    cursor:pointer;
    text-align:left;
}
.download-menu form button:hover{
    background:#1abc9c;
    color:#fff;
}

/* ===== FORM ===== */
.form-inline{
    display:flex;
    justify-content:center;
    gap:15px;
    margin-bottom:20px;
}
select{
    width:300px;
    height:40px;
    padding:8px;
    border-radius:6px;
    border:1px solid #ccc;
    background:#fff;
}
button{
    height:40px;
    padding:0 22px;
    background:#1abc9c;
    color:#fff;
    border:none;
    border-radius:6px;
    cursor:pointer;
}

/* ===== TABLE ===== */
table{
    width:100%;
    border-collapse:collapse;
}
th,td{
    border:1px solid #ccc;
    padding:8px;
    text-align:center;
}
th{
    background:#2c3e50;
    color:#fff;
}
.error{
    color:red;
    text-align:center;
    margin-top:10px;
}
</style>
</head>

<body>

<!-- ===== SIDEBAR ===== -->
<div class="sidebar">
    <a href="dashboard.php">Dashboard</a>
    <a href="add_student.php">Add Student</a>
    <a href="add_class.php">Add Class</a>
    <a href="assign_class.php">Assign Class</a>
    <a href="student_report.php">Student Report</a>
    <a href="class_report.php">Class Report</a>

    <!-- Masters Dropdown -->
    <button class="dropdown-btn" type="button">Masters <span class="arrow">▼</span></button>
    <div class="dropdown-container">
        <a href="master.php?page=teacher">Create Teacher</a>
        <a href="master.php?page=timeslot">Create Time Slot</a>
        <a href="master.php?page=gender">Create Gender</a>
    </div>

    <a href="logout.php">Logout</a>
</div>

<!-- ===== CONTENT ===== -->
<div class="content">
<div class="form-container">

<?php
$class_name="";
$result=null;

if(isset($_POST['search'])){
    $class_name=$_POST['class_name'];
    $sql="
        SELECT s.roll_number,s.student_name,s.gender_id
        FROM students s
        INNER JOIN class_assignments ca
        ON s.roll_number=ca.roll_number
        WHERE ca.class_name='$class_name'
        AND s.branch_id='$branch_id'
        ORDER BY s.student_name
    ";
    $result=mysqli_query($conn,$sql);
}
?>

<div class="title-bar">
    <h2>Class Report</h2>

<?php if(isset($_POST['search']) && $result && mysqli_num_rows($result)>0){ ?>
    <div class="download-box">
        <button class="download-btn" onclick="toggleDownload()">Download ▼</button>
        <div class="download-menu" id="downloadMenu">
            <form method="POST" action="class_report_download.php">
                <input type="hidden" name="class_name" value="<?=$class_name?>">
                <input type="hidden" name="type" value="excel">
                <button type="submit">📊 Excel</button>
            </form>
            <form method="POST" action="class_report_download.php">
                <input type="hidden" name="class_name" value="<?=$class_name?>">
                <input type="hidden" name="type" value="pdf">
                <button type="submit">📄 PDF</button>
            </form>
        </div>
    </div>
<?php } ?>
</div>

<form method="POST" class="form-inline">
    <select name="class_name" required>
        <option value="">-- Select Class --</option>
        <?php
        while($cls=mysqli_fetch_assoc($classQuery)){
            $sel=($cls['class_name']==$class_name)?'selected':'';
            echo "<option value='{$cls['class_name']}' $sel>{$cls['class_name']}</option>";
        }
        ?>
    </select>
    <button type="submit" name="search">Search</button>
</form>

<?php
if(isset($_POST['search'])){
    if(mysqli_num_rows($result)>0){
        echo "<table>
        <tr>
            <th>S.No</th>
            <th>Roll Number</th>
            <th>Student Name</th>
            <th>Gender</th>
        </tr>";
        $i=1;
        while($r=mysqli_fetch_assoc($result)){
            $gender=$r['gender_id']==1?'Male':($r['gender_id']==2?'Female':'Other');
            echo "<tr>
                <td>$i</td>
                <td>{$r['roll_number']}</td>
                <td>{$r['student_name']}</td>
                <td>$gender</td>
            </tr>";
            $i++;
        }
        echo "</table>";
    }else{
        echo "<p class='error'>No students assigned for this class.</p>";
    }
}
?>

</div>
</div>

<script>
function toggleDownload(){
    let m=document.getElementById("downloadMenu");
    m.style.display=(m.style.display==="block")?"none":"block";
}

document.addEventListener("DOMContentLoaded", function(){
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
});
</script>

</body>
</html>
