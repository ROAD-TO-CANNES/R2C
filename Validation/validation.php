<?php 
  session_start(); 

  if(!isset($_SESSION['name'])) {
    header('Location: ../index.php');
  }

  include '/home/r2c/R2C/timer.php';
  include '/home/r2c/R2C/bdd.php';

  if (!$_SESSION['droits'] > 0) {
    header('Location: ../Acceuil/acceuil.php');
  }
?>

<!DOCTYPE html>
<html>
  <head>
    <title>R2C - Valider</title>
    <link rel="stylesheet" type="text/css" href="./validation.css">
    <link rel="icon" type="image/png" href="../Img/icon.png">
  </head>
  <header>
    <?php include '/home/r2c/R2C/Header/header.php'; ?>
  </header>
  <body>
    <div class="validation">
      <?php
        if (isset($_GET['message']) && $_GET['message'] == "cp") {
          echo('<p>Programme créé avec succès</p>');
          include '/home/r2c/R2C/Validation/ok.php';
        } elseif (isset($_GET['message']) && $_GET['message'] == "cbp") {
          echo('<p>Bonne pratique créée avec succès</p>');
          include '/home/r2c/R2C/Validation/ok.php';
        } elseif (isset($_GET['message']) && $_GET['message'] == "ebp") {
          echo('<p style="color:red">Une erreur est survenue lors de la création de la bonne pratique</p>');
          include '/home/r2c/R2C/Validation/error.php';
        } else {
          echo('<p style="color:red">Error</p>');
          include '/home/r2c/R2C/Validation/error.php';
        }
      ?>
    </div>
  </body>
  <script src="../timer.js"></script>
</html>