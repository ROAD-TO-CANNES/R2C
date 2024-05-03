<?php
  include '/home/r2c/R2C/bdd.php';

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

  include '/home/r2c/R2C/timer.php';
?>