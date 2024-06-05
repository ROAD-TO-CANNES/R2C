<?php 
  session_start();
  include '/var/www/r2c.uca-project.com/Forms/checkSession.php';

  if (isset($_POST['usertodelete'])) {
    $login = $_POST['usertodelete'];
    $sql = "DELETE FROM USER WHERE login = ?";
    $request = $BDD->prepare($sql);
    $request->execute([$login]);
    header('Location: ../Validation/validation.php?message=suppruser');
  } else {
    header('Location: ../Validation/validation.php?message=ersuppruser');
  }
?>