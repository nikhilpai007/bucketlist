<?php
	require "./includes/library.php"; // Include database connection library
	$pdo = connectDB(); // Connect to database

	session_start(); // Start $_SESSION

    // Prepare query
    $query = "DELETE FROM project_users WHERE username = ?";
    $statement = $pdo->prepare($query);

    //Fetches id from username 
    $username = $_SESSION['username'];
    $query1 = "SELECT * FROM project_users WHERE username = '$username'";
    $statement1 = $pdo->prepare($query1);
    $statement1->execute([]);
    $lists = $statement1->fetch(PDO::FETCH_ASSOC);
    $userID = $lists['ID'];

    // If query was successful
    if($statement->execute([$_SESSION['username']])){
        session_destroy();
        setcookie('cookie', '', time()-1000); // Erase user cookie
    } else {
        header("location:settings.php");
    }

    //Fetches listid
    $query2 = "SELECT * FROM project_lists WHERE userID = $userID";
    $statement3 = $pdo->prepare($query2);
    $statement3->execute([]);
    $list_ID = $statement3->fetch(PDO::FETCH_ASSOC);
    $listID = $list_ID['ID'];

    //Deletes list items associated with the list
    $sql1 = "DELETE FROM project_list_items WHERE listID = $listID";
    $statement4 = $pdo->prepare($sql1);
    $statement4->execute([]);

    //Deletes Lists associated with the username
    $sql = "DELETE FROM project_lists WHERE userID = $userID";
    $statement2 = $pdo->prepare($sql);
    $statement2->execute([]);
    
    header("location:login.php");
?>