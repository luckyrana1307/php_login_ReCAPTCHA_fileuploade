<?php
include 'connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["delete_user"])) { // Check if the delete button is clicked
        // Validate and sanitize the user_id
        $user_id = filter_var($_POST["id"], FILTER_SANITIZE_NUMBER_INT);

        // Delete the user from the database
        $stmt = $mysqli->prepare("DELETE FROM users WHERE id = ?");
        $stmt->bind_param("i", $user_id);

        if ($stmt->execute()) {
            echo "User deleted successfully";
             header("Location:dashboard.php");
        } else {
            echo "Error deleting user: " . $stmt->error;
        }

        $stmt->close();
    }
}

$mysqli->close();
?>
