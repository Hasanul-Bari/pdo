<?php
    require_once "pdo.php";
    $message=false;
    $hp;
    // Demand a GET parameter
    if ( ! isset($_GET['name']) || strlen($_GET['name']) < 1  ) {
        die('Name parameter missing');
    }

    // If the user requested logout go back to index.php
    if ( isset($_POST['logout']) ) {
        header('Location: index.php');
        return;
    }

    if ( isset($_POST['make']) && isset($_POST['year']) && isset($_POST['mileage'])) {

        if(is_numeric($_POST['mileage'])===false || is_numeric($_POST['year'])===false){
            $message="Mileage and year must be numeric";
            $hp=false;
        }
        else if(strlen($_POST['make']) < 1){
            $message="Make is required";
            $hp=false;
        }
        else {
            $sql = "INSERT INTO autos (make, year, mileage) VALUES ( :mk, :yr, :mi)";

            //echo("<pre>\n".$sql."\n</pre>\n");

            $stmt = $pdo->prepare($sql);
            $stmt->execute(array(
                  ':mk' => $_POST['make'],
                  ':yr' => $_POST['year'],
                  ':mi' => $_POST['mileage'])
            );

            $message="Record inserted";
            $hp=true;
        }

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
  if ( isset($_REQUEST['name']) ) {
      echo htmlentities($_REQUEST['name']);
  }

  ?>
</h1>
<p>
  <?php
  if ( $message !== false ) {
      // Look closely at the use of single and double quotes
      if($hp===false){
          echo('<p style="color: red;">'.htmlentities($message)."</p>\n");
      }
      else {
          echo('<p style="color: green;">'.htmlentities($message)."</p>\n");
      }

  }
   ?>
</p>

<form method="post">
  <p>Make:
  <input type="text" name="make" size="60"/></p>
  <p>Year:
  <input type="text" name="year"/></p>
  <p>Mileage:
  <input type="text" name="mileage"/></p>
  <input type="submit" value="Add">
  <input type="submit" name="logout" value="Logout">
</form>

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

</div>
</body>
</html>
