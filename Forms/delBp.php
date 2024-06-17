<?php
session_start();
include '/var/www/r2c.uca-project.com/Forms/checkSession.php';

// Verify if idbp is set in the POST array
if (isset($_POST['idbp'])) {
  $bpToDelete = $_POST['idbp'];

  // Retrieve the name of the good practice to delete
  $sql = "SELECT nombp FROM BONNESPRATIQUES WHERE idbp = $bpToDelete";
  $request = $BDD->prepare($sql);
  $request->execute();
  $nombp = $request->fetchColumn();

  // Delete the good practice and its associated keywords and programs
  $sql = "DELETE FROM BONNESPRATIQUES_PROGRAMME WHERE idbp = $bpToDelete";
  $request = $BDD->prepare($sql);
  $request->execute();

  $sql = "DELETE FROM BONNESPRATIQUES_MOTSCLEF WHERE idbp = $bpToDelete";
  $request = $BDD->prepare($sql);
  $request->execute();

  $sql = "DELETE FROM BONNESPRATIQUES WHERE idbp = $bpToDelete";
  $request = $BDD->prepare($sql);
  $request->execute();

  // Log the deletion of the good practice
  $typelog = "Information";
  $desclog = 'Suppression de la bonne pratique "' . $nombp . '" id=' . $bpToDelete;
  $loginlog = $_SESSION['name'];
  include '/var/www/r2c.uca-project.com/Forms/addLogs.php';
  $message = "deletebp";
} else {
  // Log the error of deleting the good practice
  $typelog = "Warning";
  $desclog = 'Erreur lors de la suppression de la bonne pratique certains parametres sont manquants';
  $loginlog = $_SESSION['name'];
  include '/var/www/r2c.uca-project.com/Forms/addLogs.php';
  $message = "edeletebp";
}
urlencode($message);
header('Location: ../Validation/validation.php?message=' . $message); // Redirect to the validation page with the good message
