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
            header( 'Location: add.php' ) ;
            return;

        }
        else if(strpos($_POST['email'],"@")=== false){
            $_SESSION["error"] = "Email address must contain @";
            header( 'Location: add.php' ) ;
            return;
        }




        $sql = "UPDATE profile SET first_name = :fn,
                last_name = :ln, email = :em, headline = :he, summary=:su
                WHERE profile_id = :profile_id";

        $stmt = $pdo->prepare($sql);
        $stmt->execute(array(
          ':profile_id' => $_POST['profile_id'],
          ':fn' => $_POST['first_name'],
          ':ln' => $_POST['last_name'],
          ':em' => $_POST['email'],
          ':he' => $_POST['headline'],
          ':su' => $_POST['summary'])
        );

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
           <input type="submit" value="Save">
           <input type="submit" name="cancel" value="Cancel">
           </p>
 </form>

    </div>
  </body>
</html>
