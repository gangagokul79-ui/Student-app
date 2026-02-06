<?php
include 'config.php';
$id = $_POST['id'];
$reason = $_POST['reason'];

$sql = "UPDATE progress_cards SET delete_status=1, delete_reason='$reason' WHERE id=$id";
mysqli_query($conn,$sql);
echo "success";
?>
