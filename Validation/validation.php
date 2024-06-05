<?php 
  session_start(); 

  include '/var/www/r2c.uca-project.com/Forms/checkSession.php';
?>

<!DOCTYPE html>
<html>
  <head>
    <title>R2C - Valider</title>
    <link rel="stylesheet" type="text/css" href="./validation.css">
    <link rel="icon" type="image/png" href="../Img/icon.png">
  </head>
  <header>
    <?php include '/var/www/r2c.uca-project.com/Header/header.php'; ?>
  </header>
  <body>
    <div class="validation">
      <?php
      if (isset($_GET['message'])) {
        if ($_GET['message'] == "cp") {
          echo('<p>Programme créé avec succès</p>');
          include '/var/www/r2c.uca-project.com/Validation/ok.php';
        } elseif ($_GET['message'] == "ep") {
          echo('<p style="color:red">Une erreur est survenue lors de la création du programme</p>');
          include '/var/www/r2c.uca-project.com/Validation/ok.php';
        }  elseif ($_GET['message'] == "cbp") {
          echo('<p>Bonne pratique créée avec succès</p>');
          include '/var/www/r2c.uca-project.com/Validation/ok.php';
        } elseif ($_GET['message'] == "ebp") {
          echo('<p style="color:red">Une erreur est survenue lors de la création de la bonne pratique</p>');
          include '/var/www/r2c.uca-project.com/Validation/error.php';
        } elseif ($_GET['message'] == "ed") {
          echo('<p style="color:red">Une erreur est survenue lors de la supression de la bonne pratique</p>');
          include '/var/www/r2c.uca-project.com/Validation/error.php';
        } elseif ($_GET['message'] == "ecmc") {
          echo('<p style="color:red">Impossible d\'ajouter le mot clef/p>');
          include '/var/www/r2c.uca-project.com/Validation/error.php';
        } elseif ($_GET['message'] == "eccsv") {
          echo('<p style="color:red">Erreure lors de la création du fichier CSV</p>');
          include '/var/www/r2c.uca-project.com/Validation/error.php';
        } elseif ($_GET['message'] == "ecpdf") {
          echo('<p style="color:red">Erreure lors de la création du fichier PDF</p>');
          include '/var/www/r2c.uca-project.com/Validation/error.php';
        } elseif ($_GET['message'] == "suppruser") {
          echo('<p>Utilisateur supprimé avec succès</p>');
          include '/var/www/r2c.uca-project.com/Validation/ok.php';
        } elseif ($_GET['message'] == "ersuppruser") {
          echo('<p style="color:red">Une erreur est survenue lors de la suppression de l\'utilisateur</p>');
          include '/var/www/r2c.uca-project.com/Validation/error.php';
        } else {
          echo('<p style="color:red">Error</p>');
          include '/var/www/r2c.uca-project.com/Validation/error.php';
        };
      };
      ?>
    </div>
  </body>
  <script src="../timer.js"></script>
</html>