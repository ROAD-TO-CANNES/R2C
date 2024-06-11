<?php 
  session_start();
  include '/var/www/r2c.uca-project.com/Forms/checkSession.php';

  // Verify if usertodelete is set in the POST array
  if (isset($_POST['usertodelete'])) {
    // Sanitize the usertodelete variable
    $login = htmlspecialchars($_POST['usertodelete']);
    // Delete the user
    $sql = "DELETE FROM USER WHERE login = ?";
    $request = $BDD->prepare($sql);
    $request->execute([$login]);

    // Logs of the deletion of a user
    $typelog = "Information";
    $desclog = "Utilisateur '".$login."' supprimé avec succés";
    $loginlog = $_SESSION['name'];
    include '/var/www/r2c.uca-project.com/Forms/addLogs.php';
    header('Location: ../Validation/validation.php?message=suppruser');// Redirect to the validation page with the good message
  } else {
    // Logs of the error of deleting a user
    $typelog = "Warning";
    $desclog = "Erreur lors de la suppression d'un utilisateur certains paramètres sont manquant";
    $loginlog = $_SESSION['name'];
    include '/var/www/r2c.uca-project.com/Forms/addLogs.php';
    header('Location: ../Validation/validation.php?message=ersuppruser');// Redirect to the validation page with the good message
  }
?>