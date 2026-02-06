<?php
include 'config.php';

if(isset($_POST['id'], $_POST['reason'])){
    $id = (int)$_POST['id'];
    $reason = $_POST['reason'];

    $stmt = $conn->prepare(
        "UPDATE students SET status=0, delete_reason=? WHERE id=?"
    );
    $stmt->bind_param("si", $reason, $id);

    if($stmt->execute()){
        echo "success";
    }
}
