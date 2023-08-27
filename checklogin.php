<?php

session_start();

require_once "dbconfig.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = $_POST["username"];
    $password = $_POST["password"];

    $query = "SELECT * FROM user WHERE username = '$username'";
    $result = $conn->query($query);
    
    if ($result->num_rows > 0) {
        $userRow = $result->fetch_assoc();
        
        if (password_verify($password, $userRow['password'])) {
            $_SESSION["username"] = $username;
            $_SESSION["name"] = $userRow['name'];
            header("Location: dashboard.php");
            exit;
        } else {
            echo "<script>alert('Incorrect password.'); window.location.href='index.php';</script>";
            exit;
        }
    } else {
        echo "<script>alert('Username not found.'); window.location.href='index.php';</script>";
        exit;
    }

    $conn->close();
}
?>
