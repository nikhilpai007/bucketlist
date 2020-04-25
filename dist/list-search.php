<?php
require "./includes/library.php"; // Include database connection library
$pdo = connectDB(); // Connect to database

session_start(); // Start $_SESSION

/* This is to determine which input form the user has used
   * Without it, an undefined index error is thrown, or one is overwritten by the other.
   */
if (isset($_POST['index-search-string'])) {
  $search = $_POST['index-search-string'] ?? NULL;
} else {
  $search = $_POST['nav-search-string'] ?? NULL;
}
?>

<!DOCTYPE html>
<html lang="en-CA">

<head>
  <?php
  $PAGE_TITLE = "Kick It! | Search Results";
  include "./includes/metadata.php";
  ?>
  <script defer src="scripts/modal-view-list-item.js"></script>
</head>

<body id="seach-results-page">
  <!-- Create unique ID for index page -->
  <?php include "./includes/header.php"; ?>
  <main>
    <h2>Searching for: "<?php echo $search; ?>"</h2>
    <?php
    // Select all public lists
    $query = "SELECT * FROM `project_lists` WHERE visibility = 1";
    $statement = $pdo->prepare($query);
    $statement->execute([]);
    $publicLists = $statement->fetchAll();
    ?>

    <!-- For each potential list -->
    <?php foreach ($publicLists as $potentialMatch) : ?>

      <!-- Select items in the list where the name matches the search query -->
      <?php
      $query = "SELECT * FROM `project_list_items` WHERE (name LIKE '%" . $search . "%') AND listID = ?";
      $statement = $pdo->prepare($query);
      $statement->execute([$potentialMatch['ID']]);
      $matchingListItems = $statement->fetchAll();
      ?>

      <!-- For every matching list item -->
      <?php foreach ($matchingListItems as $matchingItem) : ?>
        <div>
          <!-- Output the list containing the item -->
          <?php
          $query = "SELECT * FROM project_lists WHERE ID = ?;";
          $statement = $pdo->prepare($query);
          $statement->execute([$matchingItem['listID']]);
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
              
              <!-- Display list title -->
              <div class="list-header">
                <h2><?= $list['name']; ?> by <?= $listOwner['username'] ?></h2>
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
                    <div class="list-item <?php if(stripos($item['name'], $search) !== false){echo("selected");}?>">
                      <?php
                      if ($item['itemCompletion'] == 0) {
                        echo '<i class="far fa-square"></i> ';
                      } else {
                        echo '<i class="fa fa-check-square"></i> ';
                      }
                      ?>
                      <span id="list-item-main"> <?= $item['name']; ?></span>

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
            </div>
          <?php endforeach ?>


        </div>
      <?php endforeach //($matchingListItems as $matchingItem)?>
    <?php endforeach //($publicLists as $potentialMatch)?>
  </main>
  <?php include "./includes/footer.php"; ?>
</body>

</html>