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
		<?php include("./includes/header.php"); ?>

    <main>
      <div class="form">
        <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
          <h2> Recover your Password </h2>
          <input type="email" name="email" placeholder="Enter your Email" require>
          <input type="submit" name="forgot-password" value="Send Recovery Email">
          <?php
            require "./includes/library.php";
            $pdo = connectDB();

            if (isset($_POST['forgot-password'])) {
              $token = rand(100000, 999999);
              $email = $_POST['email'];
              $query = $pdo->prepare("SELECT COUNT(ID) FROM project_users WHERE email ='$email'");
              $query->execute();
              $row = $query->fetchColumn();
              if ($row > 0) {
                $sql = $pdo->prepare("UPDATE project_users SET token = '$token' WHERE email = '$email'");             
                if ($sql->execute()) {
                $subject = "Your Kick It! Password reset request";
                $headers = "From: Kick It! Password Reset <do-not-reply@kickit.com>\r\n";
                $headers .= "Content-type: text/html\r\n";
                $message = '<h1> You have requested a password Reset for your Kick It! Account </h1> <h2> Your One-time-password is ' . $token . '.</h2>';

                mail($email, $subject, $message, $headers);
                
                header('location:change.php');
              } else {
                echo ("Something Went Wrong!");
              }
            }else{
              echo ("Email not found, Enter the email registered with your account");
            }
          }
          ?>
        </form>
      </div> <!-- end form -->
    </main>
    <?php include "./includes/footer.php"; ?>
  </body>
</html>