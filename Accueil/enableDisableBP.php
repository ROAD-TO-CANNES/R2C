<?php
  session_start(); 
  include '/var/www/r2c.uca-project.com/Forms/checkSession.php';

  //Check existence of POST variables
  if(isset($_POST['id']) || isset($_POST['idbp'])) {
    if(isset($_POST['id'])) { //If the id is sent from an administrator
      $idBP = substr($_POST['id'], 6);
    } elseif(isset($_POST['idbp'])) { //If the id is sent from a user
      $idBP = $_POST['idbp'];
    }

    //Recovering BP status
    $sql = "SELECT statut FROM BONNESPRATIQUES WHERE idbp = $idBP";
    $request = $BDD->prepare($sql);
    $request->execute();
    $statut = $request->fetchColumn();

    //Recovering of the BP name
    $sql = "SELECT nombp FROM BONNESPRATIQUES WHERE idbp = $idBP";
    $request = $BDD->prepare($sql);
    $request->execute();
    $nombp = $request->fetchColumn();


    if ($statut == 1) {//If BP is activated, deactivate it
      $sql = "UPDATE BONNESPRATIQUES SET statut = 0 WHERE idbp = $idBP";
      $request = $BDD->prepare($sql);
      $request->execute();

      //BP deactivation log
      $typelog = "Information";
      $desclog = 'Désactivation de la bonne pratique "'.$nombp.'" id='.$idBP;
      $loginlog = $_SESSION['name'];
      include '/var/www/r2c.uca-project.com/Forms/addLogs.php';
    } elseif ($statut == 0) {//If BP is disabled, enable it
      $sql = "UPDATE BONNESPRATIQUES SET statut = 1 WHERE idbp = $idBP";
      $request = $BDD->prepare($sql);
      $request->execute();

      //BP activation log
      $typelog = "Information";
      $desclog = 'Activation de la bonne pratique "'.$nombp.'" id='.$idBP;
      $loginlog = $_SESSION['name'];
      include '/var/www/r2c.uca-project.com/Forms/addLogs.php';
    };

    // If the request comes from a user, redirect to the home page
    if(isset($_POST['idbp'])) {
      header('Location: /Accueil/accueil.php');
    };
  } else { //If the parameters are missing
    //BP deactivation/activation error log
    $typelog = "Warning";
    $desclog = 'Erreur lors de la désactivation/activation de la bonne pratique certains parametres sont manquants';
    $loginlog = $_SESSION['name'];
    include '/var/www/r2c.uca-project.com/Forms/addLogs.php';
    //Redirect to the validation page with an error message
    header('Location: ../Validation/validation.php?message=ed');
  };
?>