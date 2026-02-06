<div class="sidebar">
    <a href="dashboard.php">Dashboard</a>

    <!-- Masters Dropdown -->
    <button class="dropdown-btn" type="button">
        Masters <span class="arrow">▼</span>
    </button>
    <div class="dropdown-container">
        <a href="master.php?page=teacher">Create Teacher</a>
        <a href="master.php?page=timeslot">Create Time Slot</a>
        <a href="master.php?page=gender">Create Gender</a>
    </div>

    <a href="add_student.php">Add Student</a>
    <a href="add_class.php">Add Class</a>
    <a href="assign_class.php">Assign Class</a>
    <a href="student_report.php">Student Report</a>
    <a href="class_report.php">Class Report</a>
    <a href="logout.php">Logout</a>
</div>

<style>
.dropdown-container { 
    display: none; 
    padding-left: 15px; 
}
.dropdown-btn.active .arrow { 
    transform: rotate(180deg); 
}
</style>

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
</script>
