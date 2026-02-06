<?php
session_start();
include 'config.php';

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

$branch_id = $_SESSION['branch_id'];

$studentQuery = mysqli_query($conn,"
    SELECT roll_number, student_name
    FROM students
    WHERE branch_id='$branch_id'
    ORDER BY student_name
");
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Student Report</title>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

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
.dropdown-container{background:#34495e;}
.dropdown-container a{
    padding-left:40px;
    font-size:14px;
}

.content{
    margin-left:240px;
    padding-top:80px;
    display:flex;
    justify-content:center;
    align-items:flex-start;
    min-height:100vh;
}

.form-container{
    width:520px;
    padding:25px;
    background:#fff;
    border-radius:8px;
    box-shadow:0 0 10px rgba(0,0,0,.1);
}

.title-bar{
    position:relative;
    text-align:center;
    margin-bottom:20px;
}
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
    font-weight:bold;
}
.download-btn:hover{background:#16a085;}

.download-menu{
    display:none;
    position:absolute;
    right:0;
    top:40px;
    background:#fff;
    border:1px solid #ccc;
    border-radius:6px;
    min-width:130px;
    box-shadow:0 4px 10px rgba(0,0,0,.1);
    z-index:10;
}
.download-menu button{
    width:100%;
    background:#fff;
    border:none;
    padding:10px;
    text-align:left;
    cursor:pointer;
}
.download-menu button:hover{
    background:#1abc9c;
    color:#fff;
}

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
}
button{
    height:40px;
    padding:0 26px;
    background:#1abc9c;
    color:#fff;
    border:none;
    border-radius:6px;
    cursor:pointer;
    font-weight:bold;
}

table{
    width:100%;
    border-collapse:collapse;
    margin-top:20px;
}
table th,table td{
    border:1px solid #ccc;
    padding:8px;
    text-align:center;
}
table th{
    background:#2c3e50;
    color:#fff;
}
.error{color:red;margin-top:10px;}
</style>

<script>
function toggleDownload(){
    let m=document.getElementById("downloadMenu");
    m.style.display=(m.style.display==="block")?"none":"block";
}
</script>
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
<div class="form-container">

<div class="title-bar">
    <h2>Student Report</h2>

<?php if(isset($_POST['search'])){ ?>
    <div class="download-box">
        <button class="download-btn" onclick="toggleDownload()">Download ▼</button>

        <div class="download-menu" id="downloadMenu">
            <form method="POST" action="student_report_download.php">
                <input type="hidden" name="roll_number" value="<?=$_POST['roll_number']?>">
                <input type="hidden" name="type" value="excel">
                <button type="submit">
                    <i class="fa-solid fa-file-excel" style="color:green;margin-right:6px;"></i> Excel
                </button>
            </form>

            <form method="POST" action="student_report_download.php">
                <input type="hidden" name="roll_number" value="<?=$_POST['roll_number']?>">
                <input type="hidden" name="type" value="pdf">
                <button type="submit">
                    <i class="fa-solid fa-file-pdf" style="color:red;margin-right:6px;"></i> PDF
                </button>
            </form>
        </div>
    </div>
<?php } ?>
</div>

<form method="POST" class="form-inline">
    <select name="roll_number" required>
        <option value="">-- Select Student --</option>
        <?php
        while($stu=mysqli_fetch_assoc($studentQuery)){
            echo "<option value='{$stu['roll_number']}'>
                    {$stu['student_name']} ({$stu['roll_number']})
                  </option>";
        }
        ?>
    </select>
    <button type="submit" name="search">Search</button>
</form>

<?php
if(isset($_POST['search'])){
    $roll=$_POST['roll_number'];

    $sql="
        SELECT s.roll_number,s.student_name,s.gender_id,ca.class_name
        FROM students s
        INNER JOIN class_assignments ca
            ON s.roll_number=ca.roll_number
        WHERE s.roll_number='$roll'
          AND s.branch_id='$branch_id'
    ";
    $result=mysqli_query($conn,$sql);

    if(mysqli_num_rows($result)>0){
        echo "<table>
            <tr>
                <th>S.No</th>
                <th>Roll No</th>
                <th>Name</th>
                <th>Gender</th>
                <th>Class</th>
            </tr>";
        $i=1;
        while($r=mysqli_fetch_assoc($result)){
            $gender=($r['gender_id']==1)?'Male':(($r['gender_id']==2)?'Female':'Other');
            echo "<tr>
                <td>$i</td>
                <td>{$r['roll_number']}</td>
                <td>{$r['student_name']}</td>
                <td>$gender</td>
                <td>{$r['class_name']}</td>
            </tr>";
            $i++;
        }
        echo "</table>";
    }else{
        echo "<p class='error'>No class assigned.</p>";
    }
}
?>

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
