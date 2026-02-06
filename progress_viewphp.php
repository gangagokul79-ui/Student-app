<?php
include 'config.php';
$id = $_GET['id'];

$sql = "SELECT * FROM progress_cards WHERE id=$id";
$res = mysqli_query($conn,$sql);
$row = mysqli_fetch_assoc($res);
?>

<table class="table table-bordered">
    <tr><th>ID</th><td><?php echo $row['id']; ?></td></tr>
    <tr><th>Student Name</th><td><?php echo $row['student_name']; ?></td></tr>
    <tr><th>Gender</th><td><?php echo $row['gender']; ?></td></tr>
    <tr><th>Subject 1</th><td><?php echo $row['sub1']; ?></td></tr>
    <tr><th>Subject 2</th><td><?php echo $row['sub2']; ?></td></tr>
    <tr><th>Subject 3</th><td><?php echo $row['sub3']; ?></td></tr>
    <tr><th>Subject 4</th><td><?php echo $row['sub4']; ?></td></tr>
    <tr><th>Created On</th><td><?php echo $row['created_on']; ?></td></tr>
    <tr><th>Created By</th><td><?php echo $row['created_by']; ?></td></tr>
    <tr><th>Updated On</th><td><?php echo $row['updated_on']; ?></td></tr>
    <tr><th>Updated By</th><td><?php echo $row['updated_by']; ?></td></tr>
    <?php if($row['delete_status']==1): ?>
    <tr><th>Deleted Reason</th><td><?php echo $row['delete_reason']; ?></td></tr>
    <?php endif; ?>
</table>
