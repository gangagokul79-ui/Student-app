<?php
$conn = new mysqli("localhost", "root", "", "student_db");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Invalid Request");
}

$id = intval($_GET['id']);

$sql = "DELETE FROM students WHERE id = $id";

if ($conn->query($sql)) {
    echo "<script>
            alert('Record Deleted Successfully!');
            window.location.href='progress_card.php';
          </script>";
} else {
    echo "Error deleting record!";
}
?>