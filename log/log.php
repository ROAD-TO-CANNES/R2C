<?php 
  session_start(); 
  include '/var/www/r2c.uca-project.com/Forms/checkSession.php';

  if (!$_SESSION['droits'] > 0) {
    header('Location: ../Accueil/accueil.php');
  }
?>

<!DOCTYPE html>
<html>
  <head>
    <title>R2C - Nouveau programme</title>
    <link rel="stylesheet" type="text/css" href="./newProg.css">
    <link rel="icon" type="image/png" href="../Img/icon.png">
  </head>
  <header>
    <?php include '/var/www/r2c.uca-project.com/Header/header.php'; ?>
  </header>
  <body>
    <div class="top">
      <h1>Créer un nouveau programme</h1>
    </div>
    <form
      id="newProg-form"
      class="newProg-form"
      action="../NewProg/newProgScript.php"
      method="post"
    >
      <input
        type="text"
        id="nomprog"
        name="nomprog"
        placeholder="Nom du programme"
        required
      />
      <textarea
        id="descprog"
        name="descprog"
        placeholder="Décrivez le programme"
        rows="4" 
        cols="50"
        required
      ></textarea>
      <button class="enregistrer" type="submit" value="Enregistrer">Enregistrer</button>
      <a href="../Accueil/accueil.php"><button class="annulerProg" type="button">Annuler</button></a>
    </form>
  </body>
  <script src="../timer.js"></script>
</html>