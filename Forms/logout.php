<?php
  session_start();

  include '/home/r2c/R2C/bdd.php';

  //Mise a jours du status de la connexion//
  $sql = "UPDATE USER SET statutcon=0 WHERE login LIKE '$_SESSION[name]'";
  $request = $BDD->prepare($sql);
  $request->execute();
  
  //Déconnexion//
  session_unset();
  session_destroy();
  header('Location: ../index.php');
?>