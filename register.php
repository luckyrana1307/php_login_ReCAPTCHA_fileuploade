
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title></title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>
<body>

<div class="container">
	<div class="row">
		<h2 class="text-center mt-5">Registration Form</h2>
		<div class="col-md-7 mx-auto mt-5">
			 <form action="" method="post" enctype="multipart/form-data">
			 	  <div class="mb-3">
				    <label for="Name" class="form-label">Name</label>
				    <input type="text" class="form-control" name="name">
				  </div>
				  <div class="mb-3">
				    <label for="email" class="form-label">Email</label>
				    <input type="email" class="form-control" name="email">
				  </div>
				  <div class="mb-3">
				    <label for="Password" class="form-label">Password</label>
				    <input type="password" class="form-control" name="password">
				  </div>
				  <div class="mb-3">
				    <label for="Gender" class="form-label">Gender</label>
				    <select name="gender" class="form-select" aria-label="Default select example">
					  <option value="male">Male</option>
            		  <option value="female">Female</option>
            		  <option value="other">Other</option>
					</select>
				  </div>
				  <div class="mb-3">
				    <label for="confirm_password" class="form-label">Confirm Password</label>
				    <input type="password" class="form-control" name="confirm_password">
				  </div>
				  <div class="form-floating mb-3">
					  <textarea class="form-control" placeholder="Leave a comment here" name="address"></textarea>
					  <label for="floatingTextarea">Address</label>
				</div>
				<div class="mb-3">
				    <label for="file" class="form-label">Profile Picture:</label>
				    <input type="file" class="form-control" name="file" accept="image/*">
				 </div>
				<div class="mb-3">
				<div class="g-recaptcha" data-sitekey="6LcCsTspAAAAAM5EY7RjTorJvtbALfiNYw5n7ZKa"></div><br>
				 </div>
				 <div class="mb-3">
				 	<button type="submit" class="btn btn-primary">Submit</button>
				 </div>
			</form>
		</div>
	</div>
</div>


    <script src="https://www.google.com/recaptcha/api.js" async defer></script>

</body>
</html>
<?php
ini_set('display_errors', '0');
ini_set('display_startup_errors', '0');
error_reporting(E_ALL);
session_start();
include 'connection.php';

// Include Composer's autoloader
require_once 'vendor/autoload.php';

$recaptcha_secret = "6LcCsTspAAAAABBDV_NaxL1JZr3sJ50LLM8WDPwd";

// Use the correct namespace and class name
$recaptcha = new \ReCaptcha\ReCaptcha($recaptcha_secret);
$recaptcha_response = $_POST['g-recaptcha-response'];

$recaptcha_result = $recaptcha->verify($recaptcha_response);

if (!$recaptcha_result->isSuccess()) {
	// die("Invalid ReCaptcha. Please try again");
}

$name = $_POST['name'];
$email = $_POST['email'];
$password = $_POST['password'];
$confirm_password = $_POST['confirm_password'];
$gender = $_POST['gender'];
$address = $_POST['address'];

//validation
if (empty($name) || empty($email) || empty($password) || empty($confirm_password) || empty($gender) || empty($address)) {
	die("All fields are required. Please fill out the form completely");
}

if(!filter_var($email,FILTER_VALIDATE_EMAIL)){
 die("Invalid email format. Please enter a valid email address");
}
if (strlen($password<6)) {
	die("password must be at least 6 characters long");
}
if ($password !== $confirm_password) {
	die("password do not match. Please confirm your password");
}
// File upload handling
$target_dir = "uploads/";
$original_file_name = basename($_FILES['file']['name']);
$target_file = $target_dir . $original_file_name;

// Validate and sanitize the file name
$target_file = $target_dir . time() . '_' . preg_replace("/[^a-zA-Z0-9.]/", "", $original_file_name);

if (!move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
    die("Error moving the uploaded file");
}

$sql = "INSERT INTO users (name, email, password, gender, address, profile_picture) VALUES (?, ?, ?, ?, ?, ?)";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("ssssss", $name, $email, $password, $gender, $address, $target_file);
$stmt->execute();
$stmt->close();
$mysqli->close();

// Your existing code...

header("Location: deshboard.php");
exit();
?>