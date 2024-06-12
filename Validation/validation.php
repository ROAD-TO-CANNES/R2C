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
      // If a message is sent by the URL
      if (isset($_GET['message'])) {
        // Display the message according to the message sent
        if ($_GET['message'] == "cprog") {
          echo('<p>Programme créé avec succès</p>');
          include '/var/www/r2c.uca-project.com/Validation/ok.php';
        } elseif ($_GET['message'] == "eprog") {
          echo('<p style="color:red">Une erreur est survenue lors de la création du programme</p>');
          include '/var/www/r2c.uca-project.com/Validation/error.php';
        } elseif ($_GET['message'] == "eprogexist") {
          echo('<p style="color:red">Un programme avec ce nom existe déjà</p>');
          include '/var/www/r2c.uca-project.com/Validation/error.php';
        } elseif ($_GET['message'] == "ebpexist") {
          echo('<p style="color:red">Une bonne pratique de ce nom existe déjà</p>');
          include '/var/www/r2c.uca-project.com/Validation/error.php';
        } elseif ($_GET['message'] == "cbp") {
          echo('<p>Bonne pratique créée avec succès</p>');
          include '/var/www/r2c.uca-project.com/Validation/ok.php';
        } elseif ($_GET['message'] == "ebp") {
          echo('<p style="color:red">Une erreur est survenue lors de la création de la bonne pratique</p>');
          include '/var/www/r2c.uca-project.com/Validation/error.php';
        } elseif ($_GET['message'] == "edeletebp") {
          echo('<p style="color:red">Une erreur est survenue lors de la supression de la bonne pratique</p>');
          include '/var/www/r2c.uca-project.com/Validation/error.php';
        } elseif ($_GET['message'] == "deletebp") {
          echo('<p>Bonne pratique supprimée avec succès</p>');
          include '/var/www/r2c.uca-project.com/Validation/ok.php';
        } elseif ($_GET['message'] == "eckeyword") {
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
        } elseif ($_GET['message'] == "epswsadmin") {
          echo('<p style="color:red">Vous ne pouvez pas modifier le mot de passe du Super Administrateur</p>');
          include '/var/www/r2c.uca-project.com/Validation/error.php';
        } elseif ($_GET['message'] == "epswuserinexist") {
          echo('<p style="color:red">L\'utilisateur dont vous tentez de changer le mot de passe n\'existe pas</p>');
          include '/var/www/r2c.uca-project.com/Validation/error.php';
        } elseif ($_GET['message'] == "epolicy") {
          echo('<p style="color:red">Erreur lors de la modification de la politique des mots de passe certains parametres sont manquant</p>');
          include '/var/www/r2c.uca-project.com/Validation/error.php';
        } elseif ($_GET['message'] == "epolicynumber") {
          echo('<p style="color:red">Erreur lors de la modification de la politique des mots de passe les paramètres ne sont pas du bon type</p>');
          include '/var/www/r2c.uca-project.com/Validation/error.php';
        } elseif ($_GET['message'] == "epolicyright") { 
          echo('<p style="color:red">Erreur lors de la modification de la politique des mots de passe vous n\'avez pas les droits</p>');
          include '/var/www/r2c.uca-project.com/Validation/error.php';
        } else {// if the message is not recognized
          echo('<p style="color:red">Error</p>');
          include '/var/www/r2c.uca-project.com/Validation/error.php';
        };
      };
      ?>
    </div>
  </body>
  <script src="../timer.js"></script>
</html>