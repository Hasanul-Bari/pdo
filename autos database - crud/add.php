<?php
    session_start();
    require_once "pdo.php";


    // check if a user is logged in or not
    if ( ! isset($_SESSION['user']) ) {
      die("ACCESS DENIED");
    }

    // If the user requested cancel go back to view.php
    if ( isset($_POST['cancel']) ) {
        header('Location: index.php');
        return;
    }


    if ( isset($_POST['make']) && isset($_POST['model']) && isset($_POST['year']) && isset($_POST['mileage'])) {

        if(strlen($_POST['make']) < 1 || strlen($_POST['model']) < 1 || strlen($_POST['year']) < 1 || strlen($_POST['mileage']) < 1){
            $_SESSION["error"] = "All fields are required";
            header( 'Location: add.php' ) ;
            return;

        }
        else if(is_numeric($_POST['year'])===false){
            $_SESSION["error"] = "Year must be numeric";
            header( 'Location: add.php' ) ;
            return;
        }
        else if(is_numeric($_POST['mileage'])===false){
          $_SESSION["error"] = "Mileage must be numeric";
          header( 'Location: add.php' ) ;
          return;
        }
        else {
            $sql = "INSERT INTO auto (make, model, year, mileage) VALUES ( :mk, :md, :yr, :mi)";

            //echo("<pre>\n".$sql."\n</pre>\n");

            $stmt = $pdo->prepare($sql);
            $stmt->execute(array(
                  ':mk' => $_POST['make'],
                  ':md' => $_POST['model'],
                  ':yr' => $_POST['year'],
                  ':mi' => $_POST['mileage'])
            );

            $_SESSION['success'] = "Record added";
            header("Location: index.php");
            return;
        }

    }


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
          if ( isset($_SESSION["error"]) ) {
              // Look closely at the use of single and double quotes
              echo('<p style="color: red;">'.htmlentities($_SESSION["error"])."</p>\n");
              unset($_SESSION['error']);
          }
       ?>
    </p>

    <form method="post">
        <p>Make:

        <input type="text" name="make" size="40"/></p>
        <p>Model:

        <input type="text" name="model" size="40"/></p>
        <p>Year:

        <input type="text" name="year" size="10"/></p>
        <p>Mileage:

        <input type="text" name="mileage" size="10"/></p>
        <input type="submit" name='add' value="Add">
        <input type="submit" name="cancel" value="Cancel">
    </form>



    </div>
  </body>
</html>
