<?php
    session_start();
    require_once "pdo.php";


    // check if a user is logged in or not
    if ( ! isset($_SESSION['user_id']) ) {
      die("Not logged in");
    }

    // If the user requested cancel go back to view.php
    if ( isset($_POST['cancel']) ) {
        header('Location: index.php');
        return;
    }


    if ( isset($_POST['first_name']) && isset($_POST['last_name']) && isset($_POST['email']) && isset($_POST['headline']) && isset($_POST['summary']) ) {

        //validate profile
        if(strlen($_POST['first_name']) < 1 || strlen($_POST['last_name']) < 1 || strlen($_POST['email']) < 1 || strlen($_POST['headline']) < 1 || strlen($_POST['summary']) < 1){
            $_SESSION["error"] = "All fields are required";
            header( 'Location: add.php' ) ;
            return;

        }
        else if(strpos($_POST['email'],"@")=== false){
            $_SESSION["error"] = "Email address must contain @";
            header( 'Location: add.php' ) ;
            return;
        }

        //validate position

        for($i=1; $i<=9; $i++) {
            if ( ! isset($_POST['year'.$i]) ) continue;
            if ( ! isset($_POST['desc'.$i]) ) continue;
            $year = $_POST['year'.$i];
            $desc = $_POST['desc'.$i];
            if ( strlen($year) == 0 || strlen($desc) == 0 ) {
              $_SESSION["error"] = "All fields are required";
              header( 'Location: add.php' ) ;
              return;
            }

            if ( ! is_numeric($year) ) {
              $_SESSION["error"] = "Position year must be numeric";
              header( 'Location: add.php' ) ;
              return;
            }
        }



          $stmt = $pdo->prepare('INSERT INTO Profile  (user_id, first_name, last_name, email, headline, summary)
                                VALUES ( :uid, :fn, :ln, :em, :he, :su)');
          $stmt->execute(array(
                  ':uid' => $_SESSION['user_id'],
                  ':fn' => $_POST['first_name'],
                  ':ln' => $_POST['last_name'],
                  ':em' => $_POST['email'],
                  ':he' => $_POST['headline'],
                  ':su' => $_POST['summary'])
          );

          $profile_id = $pdo->lastInsertId();

          //insert the position entries
          $rank=1;
          for($i=1; $i<=9; $i++) {
              if ( ! isset($_POST['year'.$i]) ) continue;
              if ( ! isset($_POST['desc'.$i]) ) continue;
              $year = $_POST['year'.$i];
              $desc = $_POST['desc'.$i];

              $stmt = $pdo->prepare('INSERT INTO Position (profile_id, rank, year, description)
                                    VALUES ( :pid, :rank, :year, :desc)');
              $stmt->execute(array(
                  ':pid' => $profile_id,
                  ':rank' => $rank,
                  ':year' => $year,
                  ':desc' => $desc)
              );
              $rank++;

          }


            $_SESSION['success'] = "Profile added";
            header("Location: index.php");
            return;



    }


?>



<!DOCTYPE html>
<html>
  <head>
  <title>Md. Hasanul Bari's Profile Add</title>
  <?php require_once "bootstrap.php"; ?>
  </head>
  <body>
    <div class="container">
    <h1>Adding Profile for UMSI</h1>
    <p>
      <?php
          if ( isset($_SESSION["error"]) ) {
              echo('<p style="color: red;">'.htmlentities($_SESSION["error"])."</p>\n");
              unset($_SESSION['error']);
          }
       ?>
    </p>

    <form method="post">
        <p>First Name:
        <input type="text" name="first_name" size="60"/></p>
        <p>Last Name:
        <input type="text" name="last_name" size="60"/></p>
        <p>Email:
        <input type="text" name="email" size="30"/></p>
        <p>Headline:<br/>
        <input type="text" name="headline" size="80"/></p>
        <p>Summary:<br/>
        <textarea name="summary" rows="8" cols="80"></textarea></p>
        <p>Position:
        <input type="submit" id="addPos" value="+">
        <div id="position_fields"></div>
        </p>
        <p>
        <input type="submit" value="Add">
        <input type="submit" name="cancel" value="Cancel">
        </p>
    </form>



    <script>
          countPos = 0;


          $(document).ready(function(){

              window.console && console.log('Document ready called');
              $('#addPos').click(function(event){

                  event.preventDefault();
                  if ( countPos >= 9 ) {
                      alert("Maximum of nine position entries exceeded");
                      return;
                  }
                  countPos++;
                  window.console && console.log("Adding position "+countPos);
                  $('#position_fields').append(
                      '<div id="position'+countPos+'"> \
                      <p>Year: <input type="text" name="year'+countPos+'" value="" /> \
                      <input type="button" value="-" \
                          onclick="$(\'#position'+countPos+'\').remove();return false;"></p> \
                      <textarea name="desc'+countPos+'" rows="8" cols="80"></textarea>\
                      </div>');
              });
          });
      </script>



    </div>
  </body>
</html>
