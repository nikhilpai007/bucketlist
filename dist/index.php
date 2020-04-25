<!DOCTYPE html>
<html lang="en-CA">
  <head>
    <?php
      $PAGE_TITLE = "Kick It! | Home";
      include "./includes/metadata.php";
    ?>
  </head>

  <body id="index-page"> <!-- Create unique ID for index page -->
    <?php include "./includes/header.php"; ?>
    <main>
      <img class="index-logo" src="./img/bucketlist.png" alt="Kick It!"/>

      <!-- self-processing form -->
      <form method="post" action="./list-search.php">
        <div>
          <label for="index-search-string"></label>
          <input id="index-search-string" name="index-search-string" type="text" placeholder="Search bucket lists">
        </div>

        <button id="query-search-button" name="query-search-button" type="submit">Search</button>
      </form>

      <!-- I'm Feeling Lucky Button -->
      <button id="search-lucky-button" name="search-lucky-button" type="submit" onclick="window.location.href='./lucky.php'">I'm Feeling Lucky</button>
  
      <div id="index-information-blurb">
        <p>
          <strong>Welcome to Kick It!</strong>
          Do you have a "bucket list"? What is a bucket list?
          <a href="https://www.merriam-webster.com/dictionary/bucket%20list" target="_blank">Merriam-Webster</a> defines it as <em>"a list of things that one has not done before but wants to do before dying".</em>
          How idelic.
        </p>
        <p>
          Q: Why use our service?
          A: Why not?
          Our service allows users to create their very own bucket lists.
          Should you choose to make your list public, others can see it too, and follow your progress!
          Provide updates as you progress by updating the description, or uploading a photo.
        </p>
        <p>
          <em>Insert more miscellaneous and pointless information here. Maybe include some Harry Potter facts for extra credit?</em>
        </p>
      </div> <!-- end index-information-blurb -->
    </main>
    <?php include "./includes/footer.php"; ?>
  </body>
</html>
