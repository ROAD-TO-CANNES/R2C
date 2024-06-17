<?php
session_start();
include '/var/www/r2c.uca-project.com/Forms/checkSession.php';

echo 'here';
// Verify if nomprog and descprog are set in POST array
if (isset($_POST['nomprog']) && isset($_POST['descprog'])) {

  // sanitize nomprog
  $nomprog = htmlspecialchars($_POST['nomprog']);

  // Verify if the programme already exists
  $sql = "SELECT nomprog FROM PROGRAMME";
  $request = $BDD->prepare($sql);
  $request->execute();
  $prog = $request->fetchAll();

  foreach ($prog as $row) {
    if ($row['nomprog'] == $nomprog) {
      $result = true;
      break;
    }
  }

  if ($result) { // if the programme already exists
    // Log the error of creating a programme
    $typelog = "Warning";
    $desclog = 'Erreur lors de la création d\'un programme "' . $nomprog . '" existe déjà';
    $loginlog = $_SESSION['name'];
    include '/var/www/r2c.uca-project.com/Forms/addLogs.php';

    // Redirect to the validation page with the good message
    header('Location: ../Validation/validation.php?message=eprogexist');
    exit(); // Stop the script
  }

  // sanitize descprog
  $descprog = htmlspecialchars($_POST['descprog']);


  // encode nomprog, descprog and dateprog
  $nomprog_seq = $BDD->quote($nomprog);
  $descprog_seq = $BDD->quote($descprog);
  $dateprog_seq = $BDD->quote(date('Y-m-d h:i:s'));

  // Insert the programme into the database
  $sql = "INSERT INTO PROGRAMME (dateprog, nomprog, descprog) VALUES ($dateprog_seq, $nomprog_seq, $descprog_seq)";
  $request = $BDD->prepare($sql);
  $request->execute();

  // Retrieve the id of the programme created
  $sql = "SELECT idprog FROM PROGRAMME WHERE nomprog = $nomprog_seq";
  $request = $BDD->prepare($sql);
  $request->execute();
  $idprog = $request->fetchColumn();

  // Log the creation of the programme
  $typelog = "Information";
  $desclog = 'Création du programme "' . $nomprog . '" id=' . $idprog;
  $loginlog = $_SESSION['name'];
  include '/var/www/r2c.uca-project.com/Forms/addLogs.php';

  $message = "cprog"; // Success message
} else { // if nomprog or descprog are not set in POST array
  // Log the error of creating a programme
  $typelog = "Warning";
  $desclog = 'Erreur lors de la création du programme certains parametres sont manquants';
  $loginlog = $_SESSION['name'];
  include '/var/www/r2c.uca-project.com/Forms/addLogs.php';

  $message = "eprog"; // Error message
}
// Redirect to the validation page with the good message
urlencode($message);
header('Location: ../Validation/validation.php?message=' . $message);
