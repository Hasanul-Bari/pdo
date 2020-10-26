<?php
    require_once "pdo.php";
    session_start();
    // check if a user is logged in or not
    if ( ! isset($_SESSION['user']) ) {
      die("ACCESS DENIED");
    }


    if ( isset($_POST['make']) && isset($_POST['model']) && isset($_POST['year']) && isset($_POST['mileage']) && isset($_POST['autos_id']) ) {

        // Data validation
        if(strlen($_POST['make']) < 1 || strlen($_POST['model']) < 1 || strlen($_POST['year']) < 1 || strlen($_POST['mileage']) < 1){
            $_SESSION["error"] = "All fields are required";
            header("Location: edit.php?autos_id=".$_POST['autos_id']);
            return;

        }
        else if(is_numeric($_POST['year'])===false){
            $_SESSION["error"] = "Year must be numeric";
            header("Location: edit.php?autos_id=".$_POST['autos_id']);
            return;
        }
        else if(is_numeric($_POST['mileage'])===false){
          $_SESSION["error"] = "Mileage must be numeric";
          header("Location: edit.php?autos_id=".$_POST['autos_id']);
          return;
        }




        $sql = "UPDATE auto SET make = :make,
                model = :model, year = :year, mileage = :mileage
                WHERE autos_id = :autos_id";

        $stmt = $pdo->prepare($sql);
        $stmt->execute(array(
            ':make' => $_POST['make'],
            ':model' => $_POST['model'],
            ':year' => $_POST['year'],
            ':mileage' => $_POST['mileage'],
            ':autos_id' => $_POST['autos_id']));
        $_SESSION['success'] = 'Record edited';
        header( 'Location: index.php' ) ;
        return;
    }

    // Guardian: Make sure that user_id is present
    if ( ! isset($_GET['autos_id']) ) {
      $_SESSION['error'] = "Bad value for id";
      header('Location: index.php');
      return;
    }

    $stmt = $pdo->prepare("SELECT * FROM auto where autos_id = :xyz");
    $stmt->execute(array(":xyz" => $_GET['autos_id']));
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if ( $row === false ) {
        $_SESSION['error'] = "Bad value for id";
        header( 'Location: index.php' ) ;
        return;
    }



    $mk = htmlentities($row['make']);
    $md = htmlentities($row['model']);
    $yr = htmlentities($row['year']);
    $mi = htmlentities($row['mileage']);
    $autos_id = $row['autos_id'];
?>


<!DOCTYPE html>
<html>
  <head>
    <?php require_once "bootstrap.php"; ?>
    <title>Md. Hasanul Bari's Automobile Tracker</title>
  </head>
  <body>
    <div class="container">
      <h1>Editing Automobile</h1>

      <?php
          // Flash pattern
          if ( isset($_SESSION['error']) ) {
              echo '<p style="color:red">'.$_SESSION['error']."</p>\n";
              unset($_SESSION['error']);
          }
       ?>

      <form method="post">
        <p>Make
          <input type="text" name="make" size="40" value="<?= $mk ?>"/></p>
        <p>
          Model<input type="text" name="model" size="40" value="<?= $md ?>"/></p>
        <p>
          Year<input type="text" name="year" size="10" value="<?= $yr ?>"/></p>
        <p>
          Mileage<input type="text" name="mileage" size="10" value="<?= $mi ?>"/></p>
        <input type="hidden" name="autos_id" value="<?= $autos_id ?>">
        <input type="submit" value="Save">
        <input type="submit" name="cancel" value="Cancel">
      </form>

    </div>
  </body>
</html>
