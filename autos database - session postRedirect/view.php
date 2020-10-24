<?php
    session_start();
    require_once "pdo.php";

    // check if a user is logged in or not
    if ( ! isset($_SESSION['user']) ) {
      die('Not logged in');
    }

    

    $stmt = $pdo->query("SELECT make, year, mileage FROM autos");
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>



<!DOCTYPE html>
<html>
<head>
<title>Md. Hasanul Bari's Automobile Tracker</title>
<?php require_once "bootstrap.php"; ?>
</head>
<body>
<div class="container">
<h1>Tracking Autos for
  <?php
  if ( isset($_SESSION['user']) ) {
      echo htmlentities($_SESSION['user']);
  }

  ?>
</h1>
<p>
  <?php
      if ( isset($_SESSION['success']) ) {
        echo('<p style="color: green;">'.htmlentities($_SESSION['success'])."</p>\n");
        unset($_SESSION['success']);
      }
   ?>
</p>


<h2>Automobiles</h2>
<ul>
  <p>
    <?php
        foreach ( $rows as $row ){
          echo "<li>";
          echo(htmlentities($row['year']." ".$row['make']." / ".$row['mileage']));
          echo("</li>");

        }
     ?>
  </p>
</ul>

<p>
  <a href="add.php">Add New</a> |
  <a href="logout.php">Logout</a>
</p>

</div>
</body>
</html>
