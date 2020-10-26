<?php
    session_start();
    require_once "pdo.php";
 ?>

<!DOCTYPE html>
<html>
<head>
<title>Md. Hasanul Bari - Index Page</title>
<?php require_once "bootstrap.php"; ?>
</head>
<body>
  <div class="container">
    <h2>Welcome to the Automobiles Database</h2>

    <?php
        if ( isset($_SESSION['error']) ) {
            echo '<p style="color:red">'.$_SESSION['error']."</p>\n";
            unset($_SESSION['error']);
        }
        if ( isset($_SESSION['success']) ) {
            echo '<p style="color:green">'.$_SESSION['success']."</p>\n";
            unset($_SESSION['success']);
        }

        if ( isset($_SESSION['user']) ){

          $stmt = $pdo->query("SELECT make, model, year, mileage, autos_id FROM auto");
          $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

          if ($rows == false) {
	             echo "<p>No rows found</p>";
          }
          else{


     ?>
         <table border="1">
              <thead><tr>
              <th>Make</th>
              <th>Model</th>
              <th>Year</th>
              <th>Mileage</th>
              <th>Action</th>
              </tr></thead>

              <?php
                    foreach ( $rows as $row ){
                      echo "<tr><td>";
                      echo(htmlentities($row['make']));
                      echo("</td><td>");
                      echo(htmlentities($row['model']));
                      echo("</td><td>");
                      echo(htmlentities($row['year']));
                      echo("</td><td>");
                      echo(htmlentities($row['mileage']));
                      echo("</td><td>");
                      echo('<a href="edit.php?autos_id='.$row['autos_id'].'">Edit</a> / ');
                      echo('<a href="delete.php?autos_id='.$row['autos_id'].'">Delete</a>');
                      echo("</td></tr>\n");
                    }
               ?>

        </table>
      <?php
          }
       ?>

           <p><a href="add.php">Add New Entry</a></p>
           <p><a href="logout.php">Logout</a></p>

     <?php
        }
        else{
      ?>
          <p><a href="login.php">Please log in</a></p>
          <p>Attempt to <a href="add.php">add data</a> without logging in</p>
      <?php
            }
       ?>


  </div>
</body>
</html>
