<?php
  session_start();
  include '/var/www/r2c.uca-project.com/bdd.php';

  // verify if the user is already connected
  $sql = "SELECT statutcon FROM USER WHERE login LIKE '$_SESSION[name]'";
  $request = $BDD->prepare($sql);
  $request->execute();
  $statutcon = $request->fetchColumn();

  // if the user is not connected, redirect to the login page
  if($statutcon == 0) {
    header('Location: ../index.php');
    exit;
  }

  setcookie('filtres', '', time() - 3600, "/");

  //Mise a jours du status de la connexion//
  $sql = "UPDATE USER SET statutcon=0 WHERE login LIKE '$_SESSION[name]'";
  $request = $BDD->prepare($sql);
  $request->execute();

  //Log de déconnexion//
  $typelog = "Information";
  if(!isset($desclog)){
    $desclog = "Déconnexion manuelle réussie";
  }
  $loginlog = $_SESSION['name'];
  include '/var/www/r2c.uca-project.com/Forms/addLogs.php';
  
  //Déconnexion//
  session_unset();
  session_destroy();
  header('Location: ../index.php');
?>