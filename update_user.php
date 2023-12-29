<?php
session_start();
include 'connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_POST["id"];
    $name = $_POST["name"];
    $email = $_POST["email"];
    $password = $_POST['password'];
    $gender = $_POST['gender'];
    $address = $_POST['address'];
    // Add other fields as needed

    $stmt = $mysqli->prepare("UPDATE users SET name=?, email=?, password=?, gender=?, address=? WHERE id=?");
    $stmt->bind_param("sssssi", $name, $email, $password,  $gender,$address, $user_id);

    if ($stmt->execute()) {
        echo "User data updated successfully";
         header("Location:dashboard.php");
    } else {
        echo "Error updating user data: " . $stmt->error;
    }

    $stmt->close();
    $mysqli->close();
}
?>
