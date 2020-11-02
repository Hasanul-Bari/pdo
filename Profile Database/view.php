<?php
    require_once "pdo.php";
    session_start();

    if ( ! isset($_GET['profile_id']) ) {
      $_SESSION['error'] = "Missing profile_id";
      header('Location: index.php');
      return;
    }

    $stmt = $pdo->prepare("SELECT * FROM profile where profile_id = :xyz");
    $stmt->execute(array(":xyz" => $_GET['profile_id']));
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if ( $row === false ) {
        $_SESSION['error'] = "Could not load profile";
        header( 'Location: index.php' ) ;
        return;
    }



    $fn = htmlentities($row['first_name']);
    $ln = htmlentities($row['last_name']);
    $em = htmlentities($row['email']);
    $he = htmlentities($row['headline']);
    $su = htmlentities($row['summary']);
 ?>

<!DOCTYPE html>
<html>
  <head>
    <?php require_once "bootstrap.php"; ?>
    <title>Md. Hasanul Bari's Profile View</title>
  </head>
  <body>
    <div class="container">
      <h1>Profile information</h1>


      <p>First Name: <?= $fn ?></p>
      <p>Last Name: <?= $ln ?></p>
      <p>Email: <?= $em ?></p>
      <p>Headline:<br/><?= $he ?></p>
      <p>Summary:<br/><?= $su ?><p>

      </p><a href="index.php">Done</a>

    </div>
  </body>
</html>
