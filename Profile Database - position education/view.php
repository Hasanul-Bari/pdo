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

    $stmt = $pdo->prepare("SELECT * FROM position where profile_id = :xyz ORDER BY rank");
    $stmt->execute(array(":xyz" => $_GET['profile_id']));
    $positions = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $stmt = $pdo->prepare("SELECT year, name FROM education join institution on education.institution_id= institution.institution_id
                           where profile_id = :xyz ORDER BY rank");
    $stmt->execute(array(":xyz" => $_GET['profile_id']));
    $schools = $stmt->fetchAll(PDO::FETCH_ASSOC);



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

      <?php
          if($schools!=false){
            echo "<p>Education</p><ul>\n";

            foreach($schools as $school){

              echo "<li>".$school['year'].": ".$school['name']."</li>\n";

            }
            echo "</ul>";
          }


          if($positions!=false){
            echo "<p>Position</p><ul>\n";

            foreach($positions as $position){

              echo "<li>".$position['year'].": ".$position['description']."</li>\n";

            }
            echo "</ul>";
          }
       ?>

      </p><a href="index.php">Done</a>

    </div>
  </body>
</html>
