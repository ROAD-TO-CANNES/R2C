<?php
  session_start(); 
  include '/home/r2c/R2C/Forms/checkSession.php';

  if(isset($_POST['idbp'])) {
    $bpToDelete = $_POST['idbp'];

    $sql = "SELECT nombp FROM BONNESPRATIQUES WHERE idbp = $bpToDelete";
    $request = $BDD->prepare($sql);
    $request->execute();
    $nombp = $request->fetchColumn();

    $sql = "DELETE FROM BONNESPRATIQUES_PROGRAMME WHERE idbp = $bpToDelete";
    $request = $BDD->prepare($sql);
    $request->execute();

    $sql = "DELETE FROM BONNESPRATIQUES_MOTSCLEF WHERE idbp = $bpToDelete";
    $request = $BDD->prepare($sql);
    $request->execute();

    $sql = "DELETE FROM BONNESPRATIQUES WHERE idbp = $bpToDelete";
    $request = $BDD->prepare($sql);
    $request->execute();

    //Log de suppression de BP//
    $typelog = "Réussite";
    $desclog = 'Suppression de la bonne pratique "'.$nombp.'" id='.$bpToDelete;
    $loginlog = $_SESSION['name'];
    include '/home/r2c/R2C/Forms/addLogs.php';

    header('Location: ../Accueil/accueil.php');
  } else {
    //Log d'erreur de suppression de BP//
    $typelog = "Erreur";
    $desclog = 'Erreur lors de la suppression de la bonne pratique certains parametres sont manquants';
    $loginlog = $_SESSION['name'];
    include '/home/r2c/R2C/Forms/addLogs.php';
    header('Location: ../Validation/validation.php?message=ed');
  }
?>