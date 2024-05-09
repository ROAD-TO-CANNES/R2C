<?php
  session_start(); 
  include '/home/r2c/R2C/Forms/checkSession.php';

  if(isset($_POST['idbp'])) {
    $bpToDelete = $_POST['idbp'];

    $sql = "DELETE FROM BONNESPRATIQUES_PROGRAMME WHERE idbp = $bpToDelete";
    $request = $BDD->prepare($sql);
    $request->execute();

    $sql = "DELETE FROM BONNESPRATIQUES_MOTSCLEF WHERE idbp = $bpToDelete";
    $request = $BDD->prepare($sql);
    $request->execute();

    $sql = "DELETE FROM BONNESPRATIQUES WHERE idbp = $bpToDelete";
    $request = $BDD->prepare($sql);
    $request->execute();

    header('Location: ../Accueil/accueil.php');
  } else {
    header('Location: ../Validation/validation.php?message=ed');
  }
?>