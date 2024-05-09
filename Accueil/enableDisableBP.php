<?php
  session_start(); 

  include '/home/r2c/R2C/Forms/checkSession.php';

  if(isset($_POST['id']) || isset($_POST['idbp'])) {
    if(isset($_POST['id'])) {
      $idBP = substr($_POST['id'], 6);
    } elseif(isset($_POST['idbp'])) {
      $idBP = $_POST['idbp'];
    }

    $sql = "SELECT statut FROM BONNESPRATIQUES WHERE idbp = $idBP";
    $request = $BDD->prepare($sql);
    $request->execute();
    $statut = $request->fetchColumn();

    if ($statut == 1) {
      $sql = "UPDATE BONNESPRATIQUES SET statut = 0 WHERE idbp = $idBP";
      $request = $BDD->prepare($sql);
      $request->execute();
    } elseif ($statut == 0) {
      $sql = "UPDATE BONNESPRATIQUES SET statut = 1 WHERE idbp = $idBP";
      $request = $BDD->prepare($sql);
      $request->execute();
    };

    if(isset($_POST['idbp'])) {
      header('Location: /Accueil/accueil.php');
    };
  } else {
    header('Location: ../Validation/validation.php?message=ed');
  };
?>