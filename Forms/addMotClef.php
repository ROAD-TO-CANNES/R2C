<?php
session_start();
include '/var/www/r2c.uca-project.com/Forms/checkSession.php';

// Verify that the newMotClef variable is set
if (isset($_POST['newMotClef'])) {
  // Sanitize the newMotClef variable
  $newMotClef = htmlspecialchars($_POST['newMotClef']);
  $newMotClef_seq = $BDD->quote($newMotClef);

  // Check if the newMotClef already exists in the database
  $sql = "SELECT * FROM MOTSCLEF WHERE motclef LIKE $newMotClef_seq";
  $request = $BDD->prepare($sql);
  $request->execute();
  $result = $request->fetchAll();

  // If the newMotClef already exists, add it to the selection cookie
  foreach ($result as $row) {
    if ($row['motclef'] == $newMotClef) {
      $iddiv = 'MC' . $row['idmotclef'];

      // Decode the JSON array
      $selection = json_decode($_COOKIE['selection'], true);
      // Add the new value
      $selection[] = $iddiv;
      // Re-encode the array to JSON
      $updatedCookieValue = json_encode($selection);

      setcookie('selection', $updatedCookieValue, 0, "/");
      header('Location: ../NewBP/newBP.php');
      // Exit the script
      exit();
    }
  }

  // If the newMotClef does not exist in the database, add it
  $sql = "INSERT INTO MOTSCLEF (motclef) VALUES ($newMotClef_seq)";
  $request = $BDD->prepare($sql);
  $request->execute();

  // Log the creation of the new keyword
  $typelog = "Information";
  $desclog = 'Création du mot clef "' . $newMotClef . '"';
  $loginlog = $_SESSION['name'];
  include '/var/www/r2c.uca-project.com/Forms/addLogs.php';

  // Get the id of the newly created keyword
  $sql = "SELECT idmotclef FROM MOTSCLEF WHERE motclef LIKE $newMotClef_seq";
  $request = $BDD->prepare($sql);
  $request->execute();
  $idmotclef = $request->fetchColumn();

  $iddiv = 'MC' . $idmotclef;

  // Decode the JSON array
  $selection = json_decode($_COOKIE['selection'], true);
  // Add the new value
  $selection[] = $iddiv;
  // Re-encode the array to JSON
  $updatedCookieValue = json_encode($selection);

  // Update the selection cookie
  setcookie('selection', $updatedCookieValue, 0, "/");
  header('Location: ../NewBP/newBP.php');
} else { // If the newMotClef variable is not set
  // Log the error
  $typelog = "Warning";
  $desclog = 'Erreur lors de la création du mot clef certains parametres sont manquants';
  $loginlog = $_SESSION['name'];
  include '/var/www/r2c.uca-project.com/Forms/addLogs.php';
  // Redirect to the validation page with the error message
  header('Location: ../Validation/validation.php?message=eckeyword');
}
