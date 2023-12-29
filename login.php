<?php
session_start();

// Check if the user is already logged in, redirect to dashboard if true
if (isset($_SESSION['user_id'])) {
    header("Location: deshboard.php");
    exit();
}

// Include the database connection code (assuming it's in a separate file)
include 'connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
      $password = $_POST['password'];

    // Validate user input (add more validation as needed)
    if (empty($email) || empty($password)) {
        $error = "Both email and password are required.";
    } else {
        // Check user credentials in the database
        $sql = "SELECT * FROM users WHERE email = ? AND password = ?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("ss", $email, $password);
        $stmt->execute();
        $result = $stmt->get_result();

        // If a user is found, log them in
        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();

            // Store user information in the session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['email'];

            // Redirect to the dashboard
            header("Location:dashboard.php");
            exit();
        } else {
            $error = "Invalid email or password. Please try again.";
        }

        $stmt->close();
    }
}

$mysqli->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>
<body>
    <div class="container">
    <div class="row">
    <h2 class="text-center">Login</h2>
</div>
</div>
    <?php if (isset($error)) : ?>
        <p style="color: red;"><?php echo $error; ?></p>
    <?php endif; ?>
    <div class="container">
<div class="row">
    <div class="col-md-5 mt-4 mb-4 mx-auto ">
        <div class="card p-4">
        <form action="" method="post">
        

                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" name="email">
                  </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                  </div>
        <button class="btn btn-success" type="submit">Login</button>
       <a href="register.php" class="float-end">registration</a>
        </form>
    </div>
    </div>
</div>
</div>
</body>
</html>
