<?php
  session_start(); 
  include '/var/www/r2c.uca-project.com/Forms/checkSession.php';

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
    $typelog = "Information";
    $desclog = 'Suppression de la bonne pratique "'.$nombp.'" id='.$bpToDelete;
    $loginlog = $_SESSION['name'];
    include '/var/www/r2c.uca-project.com/Forms/addLogs.php';
    $message = "deletebp";
  } else {
    //Log d'erreur de suppression de BP//
    $typelog = "Warning";
    $desclog = 'Erreur lors de la suppression de la bonne pratique certains parametres sont manquants';
    $loginlog = $_SESSION['name'];
    include '/var/www/r2c.uca-project.com/Forms/addLogs.php';
    $message = "edeletebp";
  }
  urlencode($message); 
  header('Location: ../Validation/validation.php?message='.$message);
?>