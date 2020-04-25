<?php

  // If a session has not started yet, do so.
  // Code sourced from https://stackoverflow.com/questions/6249707/check-if-php-session-has-already-started
  // Date: April 22, 2020
  if (session_status() == PHP_SESSION_NONE) {
    session_start();
  }

  // If the loggedin variable has not been set yet, let's assume it is false.
  // Otherwise, the header is broken if the user has not logged in during this session.
  if(!isset($_SESSION["loggedin"])){
    $_SESSION["loggedin"] = false;
  }

?>

<header>
  <link rel="stylesheet" href="./css/header.css" /> <!-- should be moved to styles.css - will do once other pages are styled, so not as much scrolling needed (Harrison) -->
  
  <!-- Favicon fix sourced from: https://stackoverflow.com/questions/13827325/correct-mime-type-for-favicon-ico (April 23, 2020) -->
  <link rel="shortcut icon" type="image/x-icon" href="favicon.ico" /> <!-- IE -->
  <link rel="icon" type="image/x-icon" href="favicon.ico" /> <!-- other browsers -->

  <!-- Navigation bar -->
  <a href="./index.php"><img class="nav-logo" src="./img/bucketlist.png" width="150" height="50" alt="insert logo here"/></a>

  <nav>

    <!-- If the user IS logged in, then set the home menu option direct to the mainpage.php page -->
    <!-- If the user NOT logged in, then set the home menu option to the index.php page -->
    <?php
      if ($_SESSION["loggedin"] == true){
        // Echo out html code to be picked up by a screen reader
        echo '<a class="nav-button-home" href="./mainpage.php"><i class="fas fa-home"></i> Home</a>';
      } else {
        echo '<a class="nav-button-home" href="./index.php"><i class="fas fa-home"></i> Home</a>';
      }
    ?>
    
    <!-- Search Bar -->
    <div class="nav-search-bar">
      <form method="post" action="./list-search.php">
        <div>
          <label for="nav-search-string"></label>
          <input id="nav-search-string" name="nav-search-string" type="text" placeholder="Search">
        </div>
      </form>
    </div> <!-- end nav-search-bar -->

    <!-- Lists dropdown menu -->
    <div class="nav-dropdown-lists">
      <button class="nav-button-lists"><i class="fas fa-list"> </i> Lists <i class="fas fa-angle-down"></i></button>

      <div class="nav-dropdown-lists-content">
        <a class="nav-button-public-lists" href="./public-lists.php"><i class="fas fa-globe"></i> Public Bucket Lists</a>

        <!-- If the user IS logged in, then display an option to view their personal lists -->
        <?php if ($_SESSION["loggedin"] == true){
          // Echo out html code to be picked up by a screen reader
          echo '<a class="nav-button-personal-lists" href="./mainpage.php"><i class="fas fa-star"></i> Your Bucket List</a>';
        }
        ?>
        <a class="nav-button-lucky" href="./lucky.php"><i class="fas fa-dice-six"></i> I'm Feeling Lucky</a> <!-- if not on index page -->
      </div> <!-- end nav-dropdown-lists-content -->
    </div> <!-- end nav-dropdown-lists -->

    <!-- Account dropdown menu -->
    <!-- If the user IS logged in, then display the account management button -->
    <?php if ($_SESSION["loggedin"] == true){
      // Echo out html code to be picked up by a screen reader
      echo'<div class="nav-dropdown-account">
            <button class="nav-button-account"><i class="fas fa-toolbox"></i> Account Management <i class="fas fa-angle-down"></i></button>

            <div class="nav-dropdown-account-content">
              <a class="nav-button-account-settings" href="./account-settings.php"><i class="fas fa-user-cog"></i> Settings</a>
              <a class="nav-button-account-logout" href="./logout.php"><i class="fas fa-sign-out-alt"></i> Sign Out</a> <!-- if logged in -->
            </div> <!-- end nav-dropdown-account-content -->
           </div> <!-- end nav-dropdown-account -->';
    }
    ?>

    <!-- If the user is NOT logged in, then display the login/register button -->
    <?php if ($_SESSION["loggedin"] == false){
      // Echo out html code to be picked up by a screen reader
      echo '<a class="nav-button-login" href="./login.php"><i class="fas fa-user"></i> Login/Register</a>';
    }
    ?>
  </nav>
</header>