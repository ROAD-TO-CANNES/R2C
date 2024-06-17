<?php
session_start();
include '/var/www/r2c.uca-project.com/bdd.php';

// verify if the user is already connected
$sql = "SELECT statutcon FROM USER WHERE login LIKE '$_SESSION[name]'";
$request = $BDD->prepare($sql);
$request->execute();
$statutcon = $request->fetchColumn();

// if the user is already connected, redirect to the home page
if ($statutcon == 1) {
  header('Location: ./Accueil/accueil.php');
}
?>

<!DOCTYPE html>
<html>

<head>
  <title>R2C - Login</title>
  <link rel="stylesheet" type="text/css" href="index.css">
  <link rel="icon" type="image/png" href="./Img/icon.webp">
</head>
<header>
  <?php include '/var/www/r2c.uca-project.com/Header/header.php'; ?>
</header>

<body>
  <?php include '/var/www/r2c.uca-project.com/Forms/login_form.php'; ?>
  <img src="./Img/thales.webp" alt="Thales" class="thales">
</body>

</html>