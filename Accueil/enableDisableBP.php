<?php
  session_start(); 

  include '/var/www/r2c.uca-project.com/Forms/checkSession.php';

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

    $sql = "SELECT nombp FROM BONNESPRATIQUES WHERE idbp = $idBP";
    $request = $BDD->prepare($sql);
    $request->execute();
    $nombp = $request->fetchColumn();

    if ($statut == 1) {
      $sql = "UPDATE BONNESPRATIQUES SET statut = 0 WHERE idbp = $idBP";
      $request = $BDD->prepare($sql);
      $request->execute();

      //Log de désactivation de BP//
      $typelog = "Information";
      $desclog = 'Désactivation de la bonne pratique "'.$nombp.'" id='.$idBP;
      $loginlog = $_SESSION['name'];
      include '/var/www/r2c.uca-project.com/Forms/addLogs.php';
    } elseif ($statut == 0) {
      $sql = "UPDATE BONNESPRATIQUES SET statut = 1 WHERE idbp = $idBP";
      $request = $BDD->prepare($sql);
      $request->execute();

      //Log d'activation de BP//
      $typelog = "Information";
      $desclog = 'Activation de la bonne pratique "'.$nombp.'" id='.$idBP;
      $loginlog = $_SESSION['name'];
      include '/var/www/r2c.uca-project.com/Forms/addLogs.php';
    };

    if(isset($_POST['idbp'])) {
      header('Location: /Accueil/accueil.php');
    };
  } else {
    //Log d'erreur de désactivation/activation de BP//
    $typelog = "Warning";
    $desclog = 'Erreur lors de la désactivation/activation de la bonne pratique certains parametres sont manquants';
    $loginlog = $_SESSION['name'];
    include '/var/www/r2c.uca-project.com/Forms/addLogs.php';
    header('Location: ../Validation/validation.php?message=ed');
  };
?>