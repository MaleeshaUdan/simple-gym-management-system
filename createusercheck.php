<?php

require_once "dbconfig.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form inputs
    $name = $_POST["name"];
    $username = $_POST["username"];
    $password = $_POST["password"];
    $confirmPassword = $_POST["confirm_password"];

    if ($password !== $confirmPassword) {
        echo "<script>alert('Passwords do not match.'); window.location.href='createuser.php';</script>";
    } else {
        $query = "SELECT * FROM user WHERE username = '$username'";
        $result = $conn->query($query);
        if ($result->num_rows > 0) {
            echo "<script>alert('Username already exists.'); window.location.href='createuser.php';</script>";
        } else {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $insertQuery = "INSERT INTO user (name, username, password) VALUES ('$name', '$username', '$hashedPassword')";
            if ($conn->query($insertQuery) === TRUE) {
                echo "<script>alert('User created successfully.'); window.location.href='index.php';</script>";
            } else {
                echo "Error: " . $insertQuery . "<br>" . $conn->error;
            }
        }
    }
}
$conn->close();
?>
