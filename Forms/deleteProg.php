<?php
session_start();
include '/var/www/r2c.uca-project.com/Forms/checkSession.php';

// Verify if the user has the rights to delete a program
if ($_SESSION['droits'] >= 1) {
  // Verify if the program to delete is set in the GET array
  if (isset($_GET['idprog'])) {
    $idprog = $_GET['idprog'];

    // Verify idprog is a number
    if (!is_numeric($idprog)) {
      header('Location: ../Validation/validation.php?message=esupprprognotnb'); // Redirect to the validation page with the good message
      exit(); // Stop the script
    }

    //Retrieve the name of the program to delete
    $sql = "SELECT nomprog FROM PROGRAMME WHERE idprog = $idprog";
    $request = $BDD->prepare($sql);
    $request->execute();
    $nomprog = $request->fetchColumn();

    // Delete the program
    $sql = "DELETE FROM PROGRAMME WHERE idprog = $idprog";
    $request = $BDD->prepare($sql);
    $request->execute();

    // Log the deletion of the program
    $typelog = "Information";
    $desclog = "Suppression du programme '" . $nomprog . "' id=" . $idprog;
    $loginlog = $_SESSION['name'];
    include '/var/www/r2c.uca-project.com/Forms/addLogs.php';
    header('Location: ../Prog/progs.php'); // Redirect to the programs page
  } else { // If the program to delete is not set in the GET array
    // Log the error of deleting a program
    $typelog = "Warning";
    $desclog = "Erreur lors de la suppression d'un programme certains parametres sont manquants";
    $loginlog = $_SESSION['name'];
    include '/var/www/r2c.uca-project.com/Forms/addLogs.php';
    header('Location: ../Validation/validation.php?message=esupprprognotset'); // Redirect to the validation page with the good message
  }
} else { // If the user does not have the rights to delete a program
  // Log the error of deleting a program
  $typelog = "Warning";
  $desclog = "Erreur lors de la suppression d'un programme l'utilisateur n'a pas les droits";
  $loginlog = $_SESSION['name'];
  include '/var/www/r2c.uca-project.com/Forms/addLogs.php';
  header('Location: ../Validation/validation.php?message=esupprprognorights'); // Redirect to the validation page with the good message
}
