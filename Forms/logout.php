<?php
  session_start();
  include '/home/r2c/R2C/Forms/checkSession.php';

  setcookie('filtres', '', time() - 3600, "/");

  //Mise a jours du status de la connexion//
  $sql = "UPDATE USER SET statutcon=0 WHERE login LIKE '$_SESSION[name]'";
  $request = $BDD->prepare($sql);
  $request->execute();

  //Log de déconnexion//
  $typelog = "Déconnexion";
  if(!isset($desclog)){
    $desclog = "Déconnexion manuelle réussie";
  }
  $loginlog = $_SESSION['name'];
  include '/home/r2c/R2C/Forms/addLogs.php';
  
  //Déconnexion//
  session_unset();
  session_destroy();
  header('Location: ../index.php');
?>