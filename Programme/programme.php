<?php 
  session_start(); 

  include '/home/r2c/R2C/bdd.php';

  // verify if the user is already connected
  $sql = "SELECT statutcon FROM USER WHERE login LIKE '$_SESSION[name]'";
  $request = $BDD->prepare($sql);
  $request->execute();
  $statutcon = $request->fetchColumn();

  // if the user is not connected, redirect to the login page
  if($statutcon == 0) {
    header('Location: ../index.php');
  }

  include '/home/r2c/R2C/timer.php';

  $currentProg = $_GET['prog'];

  //Récuperation du programme
  $sql = "SELECT * FROM PROGRAMME WHERE idprog=$currentProg";
  $request = $BDD->prepare($sql);
  $request->execute();
  $infoProg = $request->fetch();

  //Récuperation des bonnes pratiques associées
  $sql = "SELECT * FROM BONNESPRATIQUES WHERE prog=$currentProg";
  $request = $BDD->prepare($sql);
  $request->execute();
  $bp = $request->fetchAll();
?>

<!DOCTYPE html>
<html>
  <head>
    <title>R2C - <?php echo($infoProg['nomprog'])?></title>
    <link rel="stylesheet" type="text/css" href="./programme.css">
    <link rel="icon" type="image/png" href="../Img/icon.png">
  </head>
  <header>
    <?php include '/home/r2c/R2C/Header/header.php'; ?>
  </header>
  <body>
    <?php
      echo('
        <h1>'.$infoProg['nomprog'].'</h1>
        <p>'.$infoProg['descprog'].'</p>
      ');
    ?>
    <pre>
        <?php
          print_r($bp);
        ?>
    </pre>
  </body>
  <script src="../timer.js"></script>
</html>