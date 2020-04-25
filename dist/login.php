<?php
	require "./includes/library.php"; // Include database connection library
	$pdo = connectDB(); // Connect to database

	session_start(); // Start $_SESSION

	// This is to catch any possible errors when the user logs out.
	// If this statement is not present, it throws an undefined index error.
	if(!isset($_SESSION["loggedin"])){
    $_SESSION["loggedin"] = false;
  }

	// If user is already logged in, redirect them to user settings
	if ($_SESSION["loggedin"] == true){
		header("Location:account-settings.php");
	}
?>

<!DOCTYPE html>
<html lang="en-CA">
	<head>
		<?php
			$PAGE_TITLE = "Kick It! | Login";
    	include "./includes/metadata.php";
    ?>

    <script src="https://kit.fontawesome.com/2cbf705ee6.js" crossorigin="anonymous"></script>
	</head>
	
	<body id="login-page">
		<?php include "./includes/header.php"; ?>
		
    <main>
		<!--div class="form"-->
			<!-- self-processing form -->
			<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
				<h2>Login to your account</h2>
				<input class="text" type="text" name="username" placeholder="Username" <?php if (isset($_COOKIE['cookie'])) {
																							echo "value='{$_COOKIE['cookie']}'";
																						} ?> require>
				<input class="text" type="password" name="password" placeholder="Password" require/>
				<input type="checkbox" id="remember" name="remember" value="remember"/>
				<label for="remember">Remember me</label>

				<!-- Contains output for form validation -->
				<div class="form-message">
					<?php
					// User has submitted the login form
					if (isset($_POST["login"])) {

						// Either the username or password was left blank
						if (empty($_POST["username"]) || empty($_POST["password"])) {
							echo "You must enter a username AND password";
						} 
						
						else {
							// Gather user supplied username and password
							$username = $_POST["username"];
							$password = $_POST["password"];

							// Select the user's profile from the DB
							$query = "SELECT * FROM project_users WHERE username = '$username'";
							$statement = $pdo->prepare($query);
							$statement->execute([]);
							$result = $statement->fetch();

							// If the result returned a user
							if ($result != null) {
								// If provided password matches the selected users hashed password
								if(password_verify($password, $result['password'])){
									session_start();
									$_SESSION["loggedin"] = true;
									$_SESSION["username"] = $username;
									$_SESSION["userID"] = $result['ID'];
									header("location:mainpage.php");
								}
								else{
									echo('Incorrect Password!');
								}
							}
							else{
								echo "User", "$username", "does not exist!";
							}
						}
					}
					?>
				</div>
				<input type="submit" name="login" value="Log in"/>
			</form>
			<p>Forgot your password? <a href="reset.php">Reset</a></p>
			<p>Don't have an account? <a href="register.php">Register now!</a></p>
		</main>
		<?php include "./includes/footer.php"; ?>
	</body>
</html>