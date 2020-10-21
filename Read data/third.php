<h1>Connecting db and read data</h1>
<?php
  echo "<pre>";

  require_once "pdo.php";

  $stmt= $pdo->query("SELECT * FROM users");

  while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
    print_r($row);
  }

  echo "<pre>";
 ?>
