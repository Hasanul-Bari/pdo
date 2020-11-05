<?php
  session_start();
  require_once "pdo.php";

  $stmt = $pdo->query("SELECT * FROM profile");
  $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>


<!DOCTYPE html>
<html>
<head>
<title>Md. Hasanul Bari's Resume Registry</title>
<?php require_once "bootstrap.php"; ?>
</head>
<body>
  <div class="container">
    <h1>Md. Hasanul Bari's Resume Registry</h1>

    <?php
        if ( isset($_SESSION['error']) ) {
            echo '<p style="color:red">'.$_SESSION['error']."</p>\n";
            unset($_SESSION['error']);
        }
        if ( isset($_SESSION['success']) ) {
            echo '<p style="color:green">'.$_SESSION['success']."</p>\n";
            unset($_SESSION['success']);
        }

        if ( !isset($_SESSION['user_id']) ){

              echo '<p><a href="login.php">Please log in</a></p>';

              if($rows!=false){

                  echo '<table border="1">';
                  echo '<thead><tr>';
                  echo '<th>Name</th>';
                  echo '<th>Headline</th>';
                  echo '</tr></thead>';

                  foreach ( $rows as $row ){
                    echo "<tr><td>";
                    echo('<a href="view.php?profile_id='.$row['profile_id'].'">'.htmlentities($row['first_name']).' '.htmlentities($row['last_name']).'</a> ');
                    echo("</td><td>");
                    echo(htmlentities($row['summary']));
                    echo("</td></tr>\n");
                  }

                  echo '</table>';

            }

        }
        else{

            echo '<p><a href="logout.php">Logout</a></p>';

            if($rows!=false){

              echo '<table border="1">';
              echo '<thead><tr>';
              echo '<th>Name</th>';
              echo '<th>Headline</th>';
              echo '<th>Action</th>';
              echo '</tr></thead>';

              foreach ( $rows as $row ){
                echo "<tr><td>";
                echo('<a href="view.php?profile_id='.$row['profile_id'].'">'.htmlentities($row['first_name']).' '.htmlentities($row['last_name']).'</a>');
                echo("</td><td>");
                echo(htmlentities($row['headline']));
                echo("</td><td>");
                echo('<a href="edit.php?profile_id='.$row['profile_id'].'">Edit</a> ');
                echo('<a href="delete.php?profile_id='.$row['profile_id'].'">Delete</a>');
                echo("</td></tr>\n");
              }

              echo '</table>';
            }


            echo '<p><a href="add.php">Add New Entry</a></p>';

        }

    ?>






  </div>
</body>
</html>
