<?php
  session_start();
  require_once "pdo.php";


  $salt = 'XyZzy12*_';

  $failure = false;  // If we have no POST data

  // Check to see if we have some POST data, if we do process it
  if ( isset($_POST['email']) && isset($_POST['pass']) ) {

      if ( strlen($_POST['email']) < 1 || strlen($_POST['pass']) < 1 ) {
          $_SESSION["error"] = "User name and password are required";
          header( 'Location: login.php' ) ;
          return;
      }
      else if(strpos($_POST['email'],"@")=== false){
          $_SESSION["error"] = "Email must have an at-sign (@)";
          header( 'Location: login.php' ) ;
          return;
      }
      else {
        $check = hash('md5', $salt.$_POST['pass']);
        $stmt = $pdo->prepare('SELECT user_id, name FROM users
                              WHERE email = :em AND password = :pw');
        $stmt->execute(array( ':em' => $_POST['email'], ':pw' => $check));
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ( $row !== false ) {
            $_SESSION['name'] = $row['name'];
            $_SESSION['user_id'] = $row['user_id'];
            // Redirect the browser to index.php
            header("Location: index.php");
            return;
        }
        else{
          $_SESSION["error"] = "Incorrect password";
          header( 'Location: login.php' ) ;
          return;
        }
      }
  }

// Fall through into the View
?>


<!DOCTYPE html>
<html>
  <head>
    <?php require_once "bootstrap.php"; ?>
    <title>Md. Hasanul Bari's Login Page</title>
  </head>
  <body>
    <div class="container">
      <h1>Please Log In</h1>
      <?php

        if ( isset($_SESSION["error"]) ) {
            // Look closely at the use of single and double quotes
            echo('<p style="color: red;">'.htmlentities($_SESSION["error"])."</p>\n");
            unset($_SESSION['error']);
        }
      ?>
      <form method="POST" action="login.php">
          <label for="nam">User Name</label>
          <input type="text" name="email" id="email"><br/>
          <label for="id_1723">Password</label>
          <input type="password" name="pass" id="id_1723"><br/>
          <input type="submit" onclick="return doValidate();" value="Log In">
          <a href="index.php">Cancel</a></p>
      </form>
      <p>
      For a password hint, view source and find a password hint
      in the HTML comments.
      <!-- Hint: The password is the three character the name of the language you are
      learning (all lower case) followed by 123. -->
      </p>


      <script>
            function doValidate() {
                console.log('Validating...');
                try {
                     addr = document.getElementById('email').value;
                     pw = document.getElementById('id_1723').value;
                    console.log("Validating addr="+addr+" pw="+pw);
                    //console.log("Enter2");
                    if (addr == null || addr == "" || pw == null || pw == "") {
                        console.log("Both fields must be filled out");
                        alert("Both fields must be filled out");
                        return false;
                    }
                    if ( addr.indexOf('@') == -1 ) {
                      console.log("Invalid email address");

                        alert("Invalid email address");
                        return false;
                    }
                    return true;
                } catch(e) {
                    return false;
                }
                return false;
            }
      </script>

    </div>
  </body>
</html>
