<?php
$servername = "localhost"; // MySQL server
$username   = "root";      // XAMPP default username
$password   = "dev@123";          // XAMPP default password empty
$database   = "student_app"; // unga database name
$port       = 3306;        // MySQL port in XAMPP screenshot-la 3306 irukku

// Connection create pannrathu
$conn = mysqli_connect($servername, $username, $password, $database, $port);

// Connection check
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
