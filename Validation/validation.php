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
        } else {
          echo('<p style="color:red">Error</p>');
        }
      ?>
      <svg
        xmlns="http://www.w3.org/2000/svg"
        xmlns:xlink="http://www.w3.org/1999/xlink"
        version="1.1"
        id="Layer_1"
        x="0px"
        y="0px"
        viewBox="0 0 32 32"
        enable-background="new 0 0 32 32"
        xml:space="preserve"
      >
        <circle
          class="ok circle"
          fill="none"
          stroke="#28a745"
          stroke-width="1"
          stroke-miterlimit="10"
          cx="16"
          cy="16"
          r="12"
        />
        <polyline
          class="ok check"
          fill="none"
          stroke="#28a745"
          stroke-width="1"
          stroke-miterlimit="10"
          points="23,12 15,20 10,15 "
        />
      </svg>
      <a class="liokbtn" href="../Acceuil/acceuil.php"><button id="okbtn">OK</button></a>
    </div>
  </body>
  <script src="../timer.js"></script>
</html>