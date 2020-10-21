<h1>Connecting db and read data</h1>
<?php
  echo "<pre>";

  $pdo=new PDO("mysql:host=localhost;port=3306;dbname=misc","Hasan","arrow");

  $stmt= $pdo->query("SELECT * FROM users");

  while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
    print_r($row);
  }

  /*
  $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
  print_r($rows);
  */

  echo "<pre>";
 ?>
