<?php
require "./includes/library.php"; // Include database connection library
$pdo = connectDB(); // Connect to database

session_start(); // Start $_SESSION
?>

<!DOCTYPE html>
<html lang="en-CA">

  <head>
    <?php
    $PAGE_TITLE = "Kick It! | Public Lists";
    include "./includes/metadata.php";
    ?>

    <script defer src="scripts/modal-view-list-item.js"></script>
    <script src="https://kit.fontawesome.com/2cbf705ee6.js" crossorigin="anonymous"></script>
  </head>

  <body id="public-lists-page">
    <?php include "./includes/header.php"; ?>

    <main>
       <?php
        $query = "SELECT * FROM project_lists WHERE visibility = 1";
        $statement = $pdo->prepare($query);
        $statement->execute([]);
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
          <h2><?= $list['name']; ?> by <?= $listOwner['username'] ?></h2>
        </div>

          <ul>
            <?php
              // Find all items in that list
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
                    View Details <i class="fas fa-info-circle"></i>
                  </div> <!-- end list-item-buttons -->
                </div> <!-- end list-item -->

                <!-- Modal to view list item -->
                <div class="modal-view-list-item modal">
                  <div class="modal-content">
                    <span class="close-view-list-item modal-close">&times;</span>
                    <h3><?=$item['name'];?> by: <?=$listOwner['username'];?></h3>

                    <div>
                      <h4>Date Created: </h4>
                      <p><?=$item['date']?></p>
                    </div>

                    <div>
                      <h4>Date Completed: </h4>
                      <?php
                        // Check to see if there is a date for completion.
                        // If there is, show the date and time it was marked as being complete.
                        if ($item['dateCompleted'] != NULL) {
                          echo '<p>';
                          echo ($item['dateCompleted']);
                          echo '</p>';
                        } else {
                          echo '<p>This item is not yet complete.</p>';
                        }
                      ?>
                    </div>

                    <div>
                      <h4>Description:</h4>
                      <p><?=$item['description'];?></p>
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
                  </div> <!-- end modal-content -->
                </div> <!-- end modal-view-list-item modal -->

              </li>
            <?php endforeach ?>
          </ul>
        </div> <!-- end bucket-list -->
      <?php endforeach ?>
    </main>
    <?php include "./includes/footer.php"; ?>
  </body>
</html>