<h1>Connecting db and read data and display in table</h1>
<?php

  $pdo=new PDO("mysql:host=localhost;port=3306;dbname=misc","Hasan","arrow");

  $stmt= $pdo->query("SELECT name, email, password FROM users");

  echo "<table border='1'>\n";
  while($row = $stmt->fetch(PDO::FETCH_ASSOC)){

    echo "<tr><td>";
    echo($row['name']);
    echo("</td><td>");
    echo($row['email']);
    echo("</td><td>");
    echo($row['password']);
    echo("</td></tr>\n");
  }

  echo "</table>\n";


 ?>
