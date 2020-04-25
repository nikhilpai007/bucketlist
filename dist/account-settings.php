<?php
require "./includes/library.php"; // Include DB library
$pdo = connectDB();
session_start();

// If user has not logged in
if (!$_SESSION['loggedin']) {
	// Redirect them to the login page
	header("location:login.php");
	exit();
}
?>


<!DOCTYPE html>
<html lang="en-CA">

<head>
	<?php
	$PAGE_TITLE = "Kick It! | Account Settings";
	include "./includes/metadata.php";
	?>

	<script src="https://kit.fontawesome.com/2cbf705ee6.js" crossorigin="anonymous"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
</head>

<body id="account-settings-page">
	<?php include("./includes/header.php"); ?>

	<?php
	// Grab the username and email using the session store variable
	$query = "SELECT username, email FROM project_users WHERE username = ?";
	$statement = $pdo->prepare($query);
	$statement->execute([$_SESSION['username']]);
	$result = $statement->fetch();
	?>

	<!-- Change username / email -->
	<main>

		<h2> Account Settings </h2>

		<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
			<label for="user">Update Username</label>
			<input class="text" type="text" name="user" value="<?= $result['username']; ?>">
			<label for="email">Update Email</label>
			<input class="text" type="email" name="email" value="<?= $result['email']; ?>">
			<input type="submit" name="edit" value="Submit">

			<div class="form-message">
				<?php
				// User requests change of information
				if (isset($_POST['edit'])) {
					// Gather information from form
					$username = $_POST['user'];
					$email = $_POST['email'];

					// Update DB information
					$query = "UPDATE project_users SET username = ?, email = ? WHERE username = ?";
					$statement = $pdo->prepare($query);

					if ($statement->execute([$username, $email, $_SESSION['username']])) {
						// Update cookie and session variables
						setcookie('cookie', $username);
						$_SESSION['username'] = $username;

						// Log the user out so they can log back in with new information
						echo ("Information updated, please log back in");
						header("refresh:2;url=logout.php");
					} else {
						echo ("Couldn't update information");
					}
				}
				?>
			</div> <!-- end form-message -->
		</form> <!-- end change-username/email -->

		<!-- Change password -->
		<h2>Change Password</h2>

		<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
			<input class="text" type="password" name="current" placeholder="Current Password" required="">
			<input class="text" type="password" name="new" placeholder="New Password" required="">
			<input class="text" type="password" name="confirm" placeholder="Re-enter New Password" required="">


			<div class="form-message">
				<?php
				// Gather password hash for current user
				$query = "SELECT password FROM project_users WHERE username=?";
				$statement = $pdo->prepare($query);
				$statement->execute([$_SESSION['username']]);
				$result = $statement->fetch();

				// When change of password from is submitted
				if (isset($_POST['change'])) {
					$current = $_POST['current'];

					// Verify current password matches user supplied current password
					if (password_verify($current, $result['password'])) {
						$password = $_POST['new'];
						$confirm = $_POST['confirm'];

						// If both new passwords match
						if ($password == $confirm) {
							// Hash the new password
							$password = password_hash($password, PASSWORD_DEFAULT);

							// Prepare password update query
							$query = "UPDATE project_users SET password = ? WHERE username = ?";
							$statement = $pdo->prepare($query);

							// If query was successful
							if ($statement->execute([$password, $_SESSION['username']])) {
								echo ('Password Changed, please log back in');
								header("Refresh:2;URL=logout.php");
							} else
								echo ('Could not update password');
						} else
							echo ('Passwords do not match');
					} else
						echo ("Current password was incorrect");
				}

				?>
			</div> <!-- end form-message -->

			<input type="submit" name="change" value="Submit">
		</form> <!-- end change-password -->

		<!-- Delete Account -->
		<h2>Delete Account</h2>

		<form action="./delete.php" method="post">
			<button name="delete-account" id="delete-account" 
					onclick="return confirm('Are you sure you want to delete your account? You will not be able to recover your bucket list(s)!');">
				Delete Account
			</button>
		</form>

	</main>
	<?php include "./includes/footer.php"; ?>

</body>

</html>