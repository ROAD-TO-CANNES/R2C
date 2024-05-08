<?php
  session_start(); 
  include '/home/r2c/R2C/Forms/checkSession.php';

  $bpToDelete = $_POST['idbp'];

  $sql = "DELETE FROM BONNESPRATIQUES WHERE idbp = $bpToDelete";
  $request = $BDD->prepare($sql);
  $request->execute();

  header('Location: ../Accueil/accueil.php')
?>