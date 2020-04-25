<?php

session_start();

require "./includes/library.php";
$pdo = connectDB();

// Logged in
if ($_SESSION['loggedin']) {
  $username = $_SESSION['username'];
  $userID = $_SESSION['userID'];
}
// Not logged in, redirect to login page
else {
  header("Location:login.php");
}


?>

<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
  <!-- Title with Username -->
  <?php
  $PAGE_TITLE = "Kick It! | $username's Lists";
  include "./includes/metadata.php";
  ?>

  <!-- JS Scipts for all Modals  -->
  <script defer src="scripts/modal-new-list.js"></script>
  <script defer src="scripts/modal-new-list-item.js"></script>
  <script defer src="scripts/modal-visibility.js"></script>
  <script defer src="scripts/modal-view-list-item.js"></script>
  <script defer src="scripts/modal-delete-list.js"></script>
  <script defer src="scripts/modal-view-instructions.js"></script>
  <script defer src="scripts/list-selector.js"></script>
  <script src="https://kit.fontawesome.com/2cbf705ee6.js" crossorigin="anonymous"></script>
</head>

<body>
  <?php include "./includes/header.php"; ?>

  <!-- User Option Navbar -->
  <aside id="user-options">
    <h3>Options:</h3>
    <ul>
      <li id="view-instructions-button"><i class="fas fa-life-ring"></i> View Instructions</li>
      <li id="modal-button-new-list"><i class="fas fa-plus "></i> Create List</li>
      <li id="modal-button-new-list-item"><i class="fas fa-plus "></i> Add item to a list</li>
      <li id="modal-button-visibility"><i class="fas fa-users "></i> Change list visibility</li>
      <li id="modal-button-delete-list"><i class="fas fa-trash-alt "></i> Delete a list </li>
    </ul>
  </aside>

  <!-- Modals -->
  <!-- Modal to create new list -->
  <div id="modal-new-list" class="modal">
    <div class="modal-content">
      <span class="modal-close-new-list modal-close">&times;</span>
      <form method="post">

        <!-- list name -->
        <div>
          <label for="list-name">New list name:</label>
          <input class="text" type="text" name="list-name" placeholder="Kick the Bucket">
        </div>

        <!-- privacy -->
        <div>
          <label for="list-privacy">Select Visibility:</label>
          <div>
            <select id="list-privacy" name="list-privacy">
              <option value="0">Private</option>
              <option value="1">Public</option>
            </select>
          </div>
        </div>

        <input type="submit" name="add-list" value="Create List">
      </form>
    </div>
  </div>

  <!-- PHP for Create List  -->
  <?php
  if (isset($_POST['add-list'])) {
    $listname = $_POST["list-name"];
    $listprivacy = $_POST["list-privacy"];
    $query = "INSERT INTO project_lists(userID, name, visibility) VALUES('$userID','$listname', $listprivacy);";
    $statement = $pdo->prepare($query);

    if ($statement->execute([])) {
      // echo ('New List Added');
    } else {
      // echo ('Invalid Characters');
    }
  }
  ?>

  <!-- Gather bucket lists -->
  <?php
  // Gather lists owned by user
  $query = "SELECT * FROM project_lists WHERE userID = '$userID'";
  $statement = $pdo->prepare($query);
  $statement->execute([]);
  $lists = $statement->fetchAll();
  ?>

  <!-- Modal to add items to the list -->
  <div id="modal-new-list-item" class="modal">
    <div class="modal-content">
      <span class="modal-close modal-close-new-list-item">&times;</span>
      <form method="post" enctype="multipart/form-data">

        <!-- Select which list the user owns and would like to modify -->
        <div>
          <label for="list-name">Select List:</label>
          <div>
            <select id="list-name" name="list-name">
              <?php foreach ($lists as $list) : ?>
                <option value="<?= $list['ID'] ?>"><?= $list['name'] ?></option>
              <?php endforeach ?>
            </select>
          </div>
        </div>

        <!-- new list item name -->
        <div>
          <label for="item-name">New Item name:</label>
          <input class="text" type="text" name="item-name" placeholder="Item Name">
        </div>

        <!-- new list item description -->
        <div>
          <label for="item-desc">Item Description:</label>
          <textarea class="text" name="item-desc" placeholder="Item Description"></textarea>
        </div>

        <!-- new list item image -->
        <div>
          <label for="item-desc">Upload Image:</label>
          <input type="file" name="file" class="file-upload-field" />
        </div>

        <input type="submit" name="add-item" value="Add List Item">
      </form>
    </div> <!-- end modal-content -->
  </div> <!-- end modal-new-list-item -->

  <!-- Php Script to add list item -->
  <?php
  if (isset($_POST['add-item'])) {
    // Gather information from POST array
    $itemname = $_POST["item-name"];
    $list_id = $_POST["list-name"];
    $item_desc = $_POST["item-desc"];

    $file_name = uniqid() . "-" . time();
    $extension  = pathinfo($_FILES["file"]["name"], PATHINFO_EXTENSION);
    $basename   = $file_name . '.' . $extension;
    $file_type = $_FILES['file']['type'];
    $file_size = $_FILES['file']['size'];
    $file_temp_loc = $_FILES['file']['tmp_name'];
    $file_store = "/home/nikhilpaiganesh/public_html/www_data/" . $basename;
    if (move_uploaded_file($file_temp_loc, $file_store)) {
      // echo 'Image Uploaded Sucessfully';
    }


    // Create the date string to be inserted
    date_default_timezone_set("America/Toronto");
    $date = date("d/m/Y") . " at " . date("h:ia");

    $query = "INSERT INTO project_list_items(listID, name, description, date, imagepath) VALUES('$list_id','$itemname','$item_desc','$date','$basename');";
    $statement = $pdo->prepare($query);
    if ($statement->execute([])) {
      // echo 'New Item added';
    } else {
      // echo "No Special Character Allowed";
    }
  }
  ?>


  <!-- Change list visibility modal -->
  <div id="modal-visibility" class="modal">
    <div class="modal-content">
      <span class="modal-close-visibility modal-close">&times;</span>
      <form method="post">
        <label for="list-name1">Select List:</label>
        <div>
          <select id="list-name1" name="list-name1">
            <?php foreach ($lists as $list) : ?>
              <option value="<?= $list['ID'] ?>"><?= $list['name'] ?></option>
            <?php endforeach ?>
          </select>
        </div>

        <!-- update list selection-->
        <div>
          <label for="list-privacy1">Select Visibility:</label>
          <div>
            <select id="list-privacy1" name="list-privacy1">
              <option value="0">Private</option>
              <option value="1">Public</option>
            </select>
          </div>
        </div>

        <!-- update list button -->
        <input type="submit" name="public" value="Update">
      </form>
    </div> <!-- end modal-content -->
  </div> <!-- end modal-visibility -->

  <!-- Php Script to make list public -->
  <?php
  if (isset($_POST['public'])) {
    $list_id1 = $_POST["list-name1"];
    $list_privacy1 = $_POST["list-privacy1"];
    $query = "UPDATE project_lists SET visibility = '$list_privacy1' WHERE ID = '$list_id1';";
    $statement = $pdo->prepare($query);
    if ($statement->execute([])) {
      // echo 'Visibility updated';
    } else {
      echo "Couldn't update visibility";
    }
  }
  ?>

  <!-- Delete List Modal -->
  <div id="modal-delete-list" class="modal">
    <div class="modal-content">
      <span class="modal-close-delete-list modal-close">&times;</span>
      <form method="post" action="<?= $_SERVER['PHP_SELF']; ?>">

        <div>
          <label for="list-name1">Select List:</label>
          <div>
            <select id="list-name1" name="list-name1">
              <?php foreach ($lists as $list) : ?>
                <option value="<?= $list['ID'] ?>"><?= $list['name'] ?></option>
              <?php endforeach ?>
            </select>
          </div>
        </div>

        <!--Delete List Submit -->
        <input type="submit" onclick="return confirm('Are you sure you want to delete this list?');" name="delete-list" value="Delete List">
      </form>
    </div> <!-- end modal-content -->
  </div> <!-- end modal-delete-list -->

  <!-- PHP for Delete List -->
  <?php
  if (isset($_POST['delete-list'])) {
    $listID = $_POST["list-name1"];
    
    //Deletes a list with a given list ID
    $query = "DELETE FROM project_lists WHERE ID = $listID";
    $statement = $pdo->prepare($query);
    
    // Delete the list
    if($statement->execute([])){

      // Deletes all items which belong to that list ID
      $query = "DELETE FROM project_list_items WHERE listID = $listID";
      $statement = $pdo->prepare($query);
      if($statement->execute([])){
        echo("List and items deleted.");
      }
      else{
        echo("No list items to delete");
      }
    }
    else{
      echo("Couldn't delete list");
    }
  }
  ?>

  <main>

  <h2>Welcome to your bucketlist manager, <?= $username ?></h2>
    <p>
      Here on the bucketlist management page, you can create new lists or items, modify some of their variables/inputs, mark an item as complete, remove old items or lists, and more.
    </p>

    <?php
      $query = "SELECT * FROM project_lists WHERE userID = ?";
      $statement = $pdo->prepare($query);
      $statement->execute([$userID]);
      $lists = $statement->fetchAll();
    ?>

    <?php foreach ($lists as $list) : ?>
      <div class="bucket-list">

        <?php
        // Find the owner of the current list
        $query = "SELECT username FROM project_users WHERE ID = ?";
        $statement = $pdo->prepare($query);
        $statement->execute([$list['userID']]);
        $listOwner = $statement->fetch();
        ?>

        <div class="list-header">
          <h2><?= $list['name']; ?> by <?= $listOwner['username'] ?>
            <?php
            if ($list['visibility'] == 0) {
              echo '<i class="fas fa-low-vision"></i></h2>';
            } else {
              echo '<i class="fas fa-eye"></i></h2>';
            }
            ?>
        </div>

        <ul>
          <?php
          $query = "SELECT * FROM project_list_items WHERE listID = ?;";
          $statement = $pdo->prepare($query);
          $statement->execute([$list['ID']]);
          $itemsInList = $statement->fetchAll();
          ?>

          <?php foreach ($itemsInList as $item) : ?>
            <!-- HTML for displaying list item -->
            <li>
              <div class="list-item">
                <?php
                  // Check to see if there is a date for completion.
                  // If there is, show the item has been completed.
                  if ($item['dateCompleted'] != NULL) {
                    echo '<i class="fa fa-check-square"></i> ';
                  } else {
                    echo '<i class="far fa-square"></i> ';
                  }
                ?>

                <a href="" id="list-item-main"> <?= $item['name']; ?></a>

                <div class="list-item-buttons list-item-view-button">
                  View/Edit Details <i class="fas fa-info-circle"></i>
                </div> <!-- end list-item-buttons -->
              </div> <!-- end list-item -->

              <!-- Modal to view list item -->
              <div class="modal-view-list-item modal">
                <div class="modal-content">
                  <span class="close-view-list-item modal-close">&times;</span>
                  <form method="post" enctype="multipart/form-data">
                    <div>
                      <label for="item-name-u">New Item name:</label>
                      <input class="text" type="text" name="item-name-u" value="<?= $item['name']; ?>">
                    </div>

                    <div>
                      <h4>Date Created [Cannot modify]: </h4>
                      <p><?=$item['date']?></p>
                    </div>

                    <div>
                      <label for="dateCompleted">Set Date Completed:</label>
                      <input type="date" name="dateCompleted" value="<?= $item['dateCompleted']; ?>" min="<?= $item['date']; ?>">
                    </div>

                    <div>
                      <label for="item-desc-update">Update Item Description:</label>
                      <textarea class="text" name="item-desc-update"><?= $item['description']; ?></textarea>
                    </div>

                    <div>
                      <label for="item-desc">Update Image:</label>
                      <input type="file" name="file-update" class="file-upload-field" />
                    </div>

                    <div>
                      <h4>Image:</h4>
                      <?php
                        // Check to see if there is a date for completion.
                        // If there is, show the date and time it was marked as being complete.
                        if ($item['imagepath'] != NULL) {
                          echo '<img src="../../../www_data/';
                          echo ($item['imagepath']);
                          echo '" alt="Image uploaded by user">';
                        } else {
                          echo '<p>No image was provided.</p>';
                        }
                      ?>
                    </div>

                    <input type="submit" name="update-item" value="Update">
                    <input type="submit" name="delete-item"  id="delete-account" value="Delete This Item" onclick="return confirm('Are you sure you want to delete this item?');">

                    <input class="number" type="number" name="item-id-u" value="<?= $item['ID']; ?>" readonly>
                  </form>

                </div> <!-- end modal-content -->
              </div> <!-- end list-item -->

              <!-- Script to update item -->
              <?php
              if (isset($_POST['update-item'])) {
                // Gather information from POST array
                $itemnameu = $_POST["item-name-u"];
                $item_descu = $_POST["item-desc-update"];
                $item_dateComp = $_POST["dateCompleted"];
                $id = $_POST['item-id-u'];

                $file_name_u = uniqid() . "-" . time();
                $extension_u  = pathinfo($_FILES["file-update"]["name"], PATHINFO_EXTENSION);
                $basename_u = $file_name_u . '.' . $extension_u;
                $file_type_u = $_FILES['file-update']['type'];
                $file_size_u = $_FILES['file-update']['size'];
                $file_temp_loc_u = $_FILES['file-update']['tmp_name'];
                $file_store_u = "/home/nikhilpaiganesh/public_html/www_data/" . $basename_u;

                $file_upload = $file_name_u . '.' . $extension_u;
                if (move_uploaded_file($file_temp_loc_u, $file_store_u)) {
                  echo 'Image Uploaded Sucessfully';
                }

                if(isset($_POST['file-update'])){
                  $query_uf = "UPDATE project_list_items SET name = imagepath = '$basename_u' WHERE ID = $id;";
                  $statement_uf = $pdo->prepare($query_uf);
                  if ($statement_u->execute([])) {
                    echo 'Image uploaded';
                  } else {
                    echo "Failed to upload image to", $file_store_u;
                  }
                }


                $query_u = "UPDATE project_list_items SET name = '$itemnameu', description = '$item_descu', dateCompleted= '$item_dateComp' WHERE ID = $id;";
                $statement_u = $pdo->prepare($query_u);
                if ($statement_u->execute([])) {
                  // echo 'New Item added';
                } else {
                  // echo "No Special Character Allowed";
                }
              }

              if(isset($_POST['delete-item'])){
                $id = $_POST['item-id-u'];
                $delete = $pdo->prepare("DELETE FROM project_list_items WHERE id = $id");
                 $delete->execute();
              }
              ?>

            </li>
          <?php endforeach ?>
        </ul>
      </div> <!-- end bucket-list -->
    <?php endforeach ?>

    <div id="modal-view-instructions" class="modal">
      <div class="modal-content">
        <span class="close-view-instructions modal-close">&times;</span>
        <div class="mainpage-instructions">
          <h3>Instructions</h3>
            <div>
              <h4>How do I create a new bucket list?</h4>
              <p>
                You can create a new bucket list by navigating to the right side of the screen and clicking on "Create New List", located under the "Options" menu.
                A pop-up window will appear, and you will be able to input the title and set the visibility of your bucket list.
                There are two options for visibility, public (<i class="fas fa-eye"></i>), and private (<i class="fas fa-low-vision"></i>).
                If you choose to create a public bucket list, it will appear on the public-list page, potentially appear on the im-feeling-lucky page, and can be discovered if another user searches for a keyword that is an item of a bucket list.
                If you set the visibility of your bucket list to private, it will only be viewable by you and cannot be seen by anyone else, including by search.
                Regardless of the visibility option you choose, your bucket list will appear on this page.
              </p>
            </div>
            <div>
              <h4>I made a bucket list, how do I add items to it?</h4>
              <p>
                Just like creating a bucket list, you can create a new list item by navigating to the right side of the screen and clicking on "Create New List Item",
                located under the "Options" menu. A pop-up window will appear again, this time with more options.
                You can set the title or name of your list item, set the day it was completed (if you already have completed it and want to add it to give ideas to others),
                add a description,  and upload an image.
                You do not have to fill in the date completed, description, or upload an image. It's entirely optional.
              </p>
            </div>
            <div>
              <h4>I completed an item on my bucket list, how do I mark it as complete?</h4>
              <p>
                You can mark an item as being complete by hovering over the item, and clicking on "View/Edit Details <i class="fas fa-info-circle"></i>" button on the right side.
                In the popup window, enter the date you completed the item on, and click "Update".
                This will automatically update the item, and place a checkmark in the box on the left of the item's title.
                <strong>Please note that you may need to refresh the page for this to take effect!</strong>
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>
  <?php include "./includes/footer.php"; ?>
</body>

</html>