<?php 
  session_start(); 

  include '/home/r2c/R2C/bdd.php';
  // verify if the user is already connected
  $sql = "SELECT statutcon FROM USER WHERE login LIKE '$_SESSION[name]'";
  $request = $BDD->prepare($sql);
  $request->execute();
  $statutcon = $request->fetchColumn();

  // if the user is already connected, redirect to the home page
  if($statutcon == 1) {
    header('Location: ./Accueil/accueil.php');
  }
?>

<!DOCTYPE html>
<html>
  <head>
      <title>R2C - Login</title>
      <link rel="stylesheet" type="text/css" href="index.css">
      <link rel="icon" type="image/png" href="./Img/icon.png">
  </head>
  <header>
    <?php include '/home/r2c/R2C/Header/header.php'; ?>
  </header>
  <body>
    <?php include '/home/r2c/R2C/Forms/login_form.php'; ?>
  </body>
</html>