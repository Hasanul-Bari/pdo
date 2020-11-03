<?php
    require_once "pdo.php";
    session_start();
    // check if a user is logged in or not
    if ( ! isset($_SESSION['user_id']) ) {
      die("Not logged in");
    }


    if ( isset($_POST['first_name']) && isset($_POST['last_name']) && isset($_POST['email']) && isset($_POST['headline']) && isset($_POST['summary'])  && isset($_POST['profile_id']) ) {

        if(strlen($_POST['first_name']) < 1 || strlen($_POST['last_name']) < 1 || strlen($_POST['email']) < 1 || strlen($_POST['headline']) < 1 || strlen($_POST['summary']) < 1){
            $_SESSION["error"] = "All fields are required";
            header( 'Location: edit.php?profile_id='.$_REQUEST['profile_id'] ) ;
            return;

        }
        else if(strpos($_POST['email'],"@")=== false){
            $_SESSION["error"] = "Email address must contain @";
            header( 'Location: edit.php?profile_id='.$_REQUEST['profile_id'] ) ;
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
              header( 'Location: edit.php?profile_id='.$_REQUEST['profile_id'] ) ;
              return;
            }

            if ( ! is_numeric($year) ) {
              $_SESSION["error"] = "Position year must be numeric";
              header( 'Location: edit.php?profile_id='.$_REQUEST['profile_id'] ) ;
              return;
            }
        }




        $sql = "UPDATE profile SET first_name = :fn,
                last_name = :ln, email = :em, headline = :he, summary=:su
                WHERE profile_id = :profile_id and user_id=:uid";

        $stmt = $pdo->prepare($sql);
        $stmt->execute(array(
          ':profile_id' => $_REQUEST['profile_id'],
          ':uid'=> $_SESSION['user_id'],
          ':fn' => $_POST['first_name'],
          ':ln' => $_POST['last_name'],
          ':em' => $_POST['email'],
          ':he' => $_POST['headline'],
          ':su' => $_POST['summary'])
        );

        // Clear out the old position entries
        $stmt = $pdo->prepare('DELETE FROM Position
            WHERE profile_id=:pid');
        $stmt->execute(array( ':pid' => $_REQUEST['profile_id']));

        // Insert the position entries
        $rank = 1;
        for($i=1; $i<=9; $i++) {
            if ( ! isset($_POST['year'.$i]) ) continue;
            if ( ! isset($_POST['desc'.$i]) ) continue;
            $year = $_POST['year'.$i];
            $desc = $_POST['desc'.$i];

            $stmt = $pdo->prepare('INSERT INTO Position
                (profile_id, rank, year, description)
            VALUES ( :pid, :rank, :year, :desc)');
            $stmt->execute(array(
                ':pid' => $_REQUEST['profile_id'],
                ':rank' => $rank,
                ':year' => $year,
                ':desc' => $desc)
            );
            $rank++;
        }



        $_SESSION['success'] = 'Profile updated';
        header( 'Location: index.php' ) ;
        return;
    }


    if ( ! isset($_GET['profile_id']) ) {
      $_SESSION['error'] = "Missing profile_id";
      header('Location: index.php');
      return;
    }

    $stmt = $pdo->prepare("SELECT * FROM profile where profile_id = :xyz");
    $stmt->execute(array(":xyz" => $_GET['profile_id']));
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if ( $row === false ) {
        $_SESSION['error'] = "Could not load profile";
        header( 'Location: index.php' ) ;
        return;
    }



    $fn = htmlentities($row['first_name']);
    $ln = htmlentities($row['last_name']);
    $em = htmlentities($row['email']);
    $he = htmlentities($row['headline']);
    $su = htmlentities($row['summary']);

    $profile_id = $row['profile_id'];

    $stmt = $pdo->prepare("SELECT * FROM position where profile_id = :xyz ORDER BY rank");
    $stmt->execute(array(":xyz" => $profile_id));
    $positions = $stmt->fetchAll(PDO::FETCH_ASSOC);


?>


<!DOCTYPE html>
<html>
  <head>
    <?php require_once "bootstrap.php"; ?>
    <title>Md. Hasanul Bari's Automobile Tracker</title>
  </head>
  <body>
    <div class="container">
      <h1>Editing Profile for UMSI</h1>

      <?php
          // Flash pattern
          if ( isset($_SESSION['error']) ) {
              echo '<p style="color:red">'.$_SESSION['error']."</p>\n";
              unset($_SESSION['error']);
          }
       ?>

       <form method="post" action="edit.php">
             <p>First Name:
             <input type="text" name="first_name" size="60" value="<?= $fn ?>"/></p>
             <p>Last Name:
             <input type="text" name="last_name" size="60" value="<?= $ln ?>"/></p>
             <p>Email:
             <input type="text" name="email" size="30" value="<?= $em ?>"/></p>
             <p>Headline:<br/>
             <input type="text" name="headline" size="80" value="<?= $he ?>"/></p>
             <p>Summary:<br/>
             <textarea name="summary" rows="8" cols="80"><?= $su ?></textarea>
             <p>
             <input type="hidden" name="profile_id" value="<?= $profile_id ?>"/>

             <?php
                  $pos=0;

                  echo ('<p>Position: <input type="submit" id="addPos" value="+">'."\n");
                  echo ('<div id="position_fields">'."\n");

                  foreach($positions as $position){

                    $pos++;

                    echo ('<div id="position'.$pos.'">'."\n");
                    echo ('<p>Year: <input type="text" name="year'.$pos.'" value="'.htmlentities($position['year']).'" />'."\n");
                    echo ('<input type="button" value="-" ');
                    echo ('onclick="$(\'#position'.$pos.'\').remove();return false;"></p>'."\n");
                    echo (' <textarea name="desc'.$pos.'" rows="8" cols="80">'.htmlentities($position['description']).'</textarea>'."\n");
                  }

                  echo ("</div></p>\n");

              ?>


             <input type="submit" value="Save">
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
