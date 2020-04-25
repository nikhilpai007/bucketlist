<?php
    //require_once "config.php"; // Obsolete.

    require "./includes/library.php";
    $pdo = connectDB();

    error_reporting(0);

    $value = false;
    $username = $_POST['username'];

    $query = "SELECT * FROM project_users WHERE username = ?";
    $statement = $pdo->prepare($query);
    $statement->execute([$username]);
    $results = $statement->fetch();

    if($results == TRUE){
        echo('Username Taken');
    } else {
        echo('Username Available');
    }
?>