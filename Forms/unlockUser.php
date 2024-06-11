<?php
  session_start();
  include '/var/www/r2c.uca-project.com/Forms/checkSession.php';

  if (isset($_POST['userToUnlock'])) {
    $userToUnlock = htmlspecialchars($_POST['userToUnlock']);
    $sql = "UPDATE USER SET tentativedelogin = 0 WHERE login = ?";
    $request = $BDD->prepare($sql);
    $request->execute([$userToUnlock]);
    header('Location: ../Users/users.php');
  }
?>