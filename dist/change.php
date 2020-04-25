<!DOCTYPE html>
<html lang="en-CA">
	<head>
		<?php
			$PAGE_TITLE = "Bucketlist | Password Recovery";
    	include "./includes/metadata.php";
    ?>

    <script src="https://kit.fontawesome.com/2cbf705ee6.js" crossorigin="anonymous"></script>
	</head>

  <body id="password-recovery-page">

    <?php include "./includes/header.php"; ?>
    <main>
      <div class="form">
        <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
          <h2>Recover your Password</h2>
          
          <input class="text" type="text" name="email" placeholder="Enter your email" require>
          <input class="text" type="text" name="otp" placeholder="Enter your One Time Password" require>
          <input class="text" type="password" name="password" placeholder="Enter your new Password" require>
          <input class="text" type="password" name="confirm_password" placeholder="Confirm your new Password" require>
          <input type="submit" name="reset" value="Reset Password">

          <?php
            require "./includes/library.php";
            $pdo = connectDB();
            
            // If reset submit button has been pressed
            if (isset($_POST['reset'])) {
              // Retrieve variables from POST array
              $otp = $_POST['otp'];
              $email = $_POST['email'];
              $password = $_POST['password'];
              $confirm = $_POST['confirm_password'];

              // Verify that the email and one time password exist
              $check = $pdo->prepare("SELECT COUNT(ID) FROM project_users WHERE email ='$email' AND token = '$otp'");
              $check->execute();
              $row = $check->fetchColumn();
              if ($row > 0) {
                // If the supplied passwords match
                if ($confirm == $password) {
                  // Hash the password and update the database
                  $password = password_hash($password, PASSWORD_DEFAULT);
                  $query = "UPDATE project_users SET password = '$password' WHERE email = '$email'";
                  $statement = $pdo->prepare($query);
                  if ($statement->execute([])){
                    $tokenDel = $pdo->prepare("UPDATE project_users SET token = NULL WHERE email = '$email'");
                    $tokenDel->execute();
                    echo ('Password Changed');
                    header('Location: login.php');
                  }
                } else {
                  echo ('Passwords do not match');
                }
              } else {
                echo "Email and One Time Password do not match";
              }
            }
          ?>
          </form>
        </div> <!-- end form -->
    </main>
    <?php include "./includes/footer.php"; ?>
  </body>
</html>