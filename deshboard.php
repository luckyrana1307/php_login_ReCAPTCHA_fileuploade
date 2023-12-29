<?php
session_start();
if (!isset($_SESSION['user_id'])) {
	header("Location: login.php");
	exit();
}

include 'connection.php';

$sql = "SELECT * FROM users";
$result = $mysqli->query($sql);

if ($result->num_rows > 0) {
    $users = $result->fetch_all(MYSQLI_ASSOC);
} else {
    $users = [];
}

$mysqli->close();
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Deshboard</title>
		<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>
<body>
<div class="row">
	<div class="col-md-12">
		<h2 class="text-center mt-3 mb-3">
	Welcome to DeshBoard, <?php echo $_SESSION['user_name'];?>
</h2>
	</div>
</div>

<button class="btn btn-danger float-end me-5"><a class="text-decoration-none text-light" href="logout.php">Logout</a></button>

<div class="row">

	<div class="col-md-7 mx-auto">
		    <h3>All Users:</h3>
   
   		<table class="table">
			  <thead>
			    <tr>
			      <th scope="col">Name</th>
			      <th scope="col">Email</th>
			      <th scope="col">Password</th>
			      <th scope="col">Gender</th>
			       <th scope="col">Address</th>
			        <th scope="col">Profile Picture:</th>
			        <th scope="col">Action</th>
			    </tr>
			  </thead>
			  <tbody>
        <?php

         foreach ($users as $user) : ?>
         
	
			    <tr>
			     <td><?php echo $user['name']; ?></td>
			      <td><?php echo $user['email']; ?></td>
			       <td><?php echo $user['password']; ?></td>
			        <td><?php echo $user['gender']; ?></td>
			         <td><?php echo $user['address']; ?></td>
			          <td><img class="img-thumbnail" src="<?php echo $user['profile_picture']; ?>" alt="Profile Picture"></td>
			          <td colspan="4" class="d-flex"><button data-bs-toggle="modal" data-bs-target="#update" type="button" class="btn btn-warning mx-2">Update</button>


			           <button type="button" data-bs-toggle="modal" data-bs-target="#delete" class="btn btn-danger">Delete</button></td>
			    </tr>
			   
			


        <?php endforeach; ?>
  </tbody>
			</table>
	</div>
</div>



<div class="modal fade" id="update" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel"><?php echo $user['name']; ?></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
     <div class="modal-body">
	    <form action="update_user.php" method="post" enctype="multipart/form-data">
	    	<input type="hidden" name="id" value="<?php echo $user['id']; ?>">

	        <div class="mb-3">
	            <label for="Name" class="form-label">Name</label>
	            <input type="text" class="form-control" name="name" value="<?php echo isset($user['name']) ? $user['name'] : ''; ?>">
	        </div>
	        <div class="mb-3">
	            <label for="email" class="form-label">Email</label>
	            <input type="email" class="form-control" name="email" value="<?php echo isset($user['email']) ? $user['email'] : ''; ?>">
	        </div>
	        <div class="mb-3">
	            <label for="Password" class="form-label">Password</label>
	            <input type="password" class="form-control" name="password">
	        </div>
	        <div class="mb-3">
	            <label for="Gender" class="form-label">Gender</label>
	            <select name="gender" class="form-select" aria-label="Default select example">
	                <option value="male" <?php echo (isset($user['gender']) && $user['gender'] == 'male') ? 'selected' : ''; ?>>Male</option>
	                <option value="female" <?php echo (isset($user['gender']) && $user['gender'] == 'female') ? 'selected' : ''; ?>>Female</option>
	                <option value="other" <?php echo (isset($user['gender']) && $user['gender'] == 'other') ? 'selected' : ''; ?>>Other</option>
	            </select>
	        </div>
	        <div class="mb-3">
	            <label for="confirm_password" class="form-label">Confirm Password</label>
	            <input type="password" class="form-control" name="confirm_password">
	        </div>
	        <div class="form-floating mb-3">
	            <textarea class="form-control" placeholder="Leave a comment here" name="address"><?php echo isset($user['address']) ? $user['address'] : ''; ?></textarea>
	            <label for="floatingTextarea">Address</label>
	        </div>
	        <div class="mb-3">
	            <label for="file" class="form-label">Profile Picture:</label>
	            <input type="file" class="form-control" name="file" accept="image/*">
	        </div>
	        <button type="submit" class="btn btn-primary">Submit</button>
	    </form>
	</div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
     <button type="submit" class="btn btn-primary" data-bs-dismiss="modal">Submit</button>
      </div>
    </div>
  </div>
</div>


<div class="modal fade" id="delete" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel"><?php echo $user['name']; ?></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="delete_user.php" method="post">
		    <input type="hidden" name="id" value="<?php echo $user['id']; ?>">
		
		        <button type="submit" name="delete_user" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this user?')">Delete</button>

		    	 <button type="button" class="btn btn-secondary w-25 mx-2" data-bs-dismiss="modal">No</button>
       			
		   
		</form>
      </div>
     
       
      
    </div>
  </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>
</html>