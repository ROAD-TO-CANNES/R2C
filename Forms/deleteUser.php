<?php 
  session_start();
  include '/var/www/r2c.uca-project.com/Forms/checkSession.php';

  if (isset($_POST['usertodelete'])) {
    $login = $_POST['usertodelete'];
    $sql = "DELETE FROM USER WHERE login = ?";
    $request = $BDD->prepare($sql);
    $request->execute([$login]);

    // Logs de suppression d'utilisateur
    $typelog = "Information";
    $desclog = "Utilisateur '".$login."' supprimé avec succés";
    $loginlog = $_SESSION['name'];
    include '/var/www/r2c.uca-project.com/Forms/addLogs.php';
    header('Location: ../Validation/validation.php?message=suppruser');
  } else {
    // Logs d'erreur lors de la suppréssion d'utilisateur
    $typelog = "Warning";
    $desclog = "Erreur lors de la suppression d'un utilisateur certains paramètres sont manquant";
    $loginlog = $_SESSION['name'];
    include '/var/www/r2c.uca-project.com/Forms/addLogs.php';
    header('Location: ../Validation/validation.php?message=ersuppruser');
  }
?>