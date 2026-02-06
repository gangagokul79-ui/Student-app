<?php
include 'config.php';

if(isset($_POST['submit'])){
    $school_name = $_POST['school_name'];
    $school_code = $_POST['school_code'];
    $address = $_POST['address'];

    $sql = "INSERT INTO schools (school_name, school_code, address) VALUES ('$school_name', '$school_code', '$address')";
    if($conn->query($sql)){
        $msg = "School added successfully!";
    } else {
        $msg = "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add School</title>
    <style>
        body{ font-family: Arial; padding: 30px; }
        form{ max-width: 400px; margin: auto; }
        input, textarea{ width: 100%; padding: 8px; margin: 8px 0; }
        input[type=submit]{ width: auto; padding: 10px 20px; background-color: #4CAF50; color: white; border: none; cursor: pointer; }
        input[type=submit]:hover{ background-color: #45a049; }
        .msg{ color: green; text-align: center; }
    </style>
</head>
<body>

<h2 style="text-align:center;">Add School</h2>

<?php if(isset($msg)) { echo "<p class='msg'>$msg</p>"; } ?>

<form method="POST">
    <label>School Name</label>
    <input type="text" name="school_name" required>

    <label>School Code</label>
    <input type="text" name="school_code" required>

    <label>Address</label>
    <textarea name="address" required></textarea>

    <input type="submit" name="submit" value="Add School">
</form>

</body>
</html>
