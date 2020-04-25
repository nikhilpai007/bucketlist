<?php
	require "./includes/library.php"; // Include PDO DB library
	$pdo = connectDB(); // Connect to DB
?>

<!DOCTYPE html>
<html lang="en-CA">
	<head>
		<?php
			$PAGE_TITLE = "Kick It! | Register New Account";
    	include "./includes/metadata.php";
    ?>

    <script src="https://kit.fontawesome.com/2cbf705ee6.js" crossorigin="anonymous"></script>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
	</head>

	<script src="./scripts/register.js"></script> <!-- should this not go in the <head> ? (H)-->

	<body id="register-page">
		<?php include "./includes/header.php"; ?>

		<main>
				<form method="POST" name="form" action="<?php echo $_SERVER['PHP_SELF']; ?>">
					<h2>Register</h2>
					<input class="text" id="username" type="text" name="username" placeholder="Username" required="">
					<div id="user-feedback" class="form-message"></div>
					<input class="text" id="email" type="email" name="email" placeholder="Email" required="">
					<input class="text" id="password" type="password" name="password" placeholder="Password" onKeyUp="checkPasswordStrength();" required="">
					
					<div id="password-strength-status" class="form-message"></div>
					<input class="text" id="confirm_password" type="password" name="confirm_password" placeholder="Confirm Password" required="">

					<div class="form-message">
						<?php
						// Wait for form submission
						if (isset($_POST['submit'])) {

							// If any fields have been left empty
							if (empty($_POST["username"]) || empty($_POST["password"]) || empty($_POST["email"]) || empty($_POST["confirm_password"])) {
								echo ('All Fields should be filled!');
							}

							// All fields have been filled
							else {

								// Gather user supplied form data
								$username = $_POST["username"];
								$email = $_POST["email"];
								$password = $_POST["password"];
								$confirm = $_POST["confirm_password"];

								// Perform a search to determine if a user with that username already exists
								$query = "SELECT * FROM project_users WHERE username='$username'";
								$statement = $pdo->prepare($query);
								$statement->execute([]);
								$result = $statement->fetch();

								// No user exists with username
								if(($result == null)){
									// Both user supplied passwords match
									if (($confirm == $password)){

										// Hash the users password
										$password = password_hash($password, PASSWORD_DEFAULT);
			
										// Insert the user into the database
										$query = "INSERT INTO project_users(username, password, email) VALUES ('$username', '$password', '$email')";
										$statement = $pdo->prepare($query);
			
										// Query successful
										if ($statement->execute([])) {
											// Redirect to login
											echo ('Registration Complete');
											header('Location: login.php');
										}
										else{
											echo('Unexpected Error: Please try again');
										}
									}
									// Passwords do not match
									else {
										echo ('Passwords do not match');
									}
								}
								// Username has been taken
								else{
									echo('Account with that username exists');
								}
							}
						}
						?>
					</div> <!-- end div form-message -->

					<input type="submit" id="submit" name="submit" value="Create a New Account"/>
				</form>
				<p>Already have an Account? <a href="login.php">Login Now!</a></p>

		</main>
		<?php include "./includes/footer.php"; ?>
	</body>
</html>