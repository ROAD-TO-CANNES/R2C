<?php
session_start();
include '/var/www/r2c.uca-project.com/Forms/checkSession.php';

// Verify if userToUnlock is set in POST array
if (isset($_POST['userToUnlock'])) {
  // sanitize userToUnlock
  $userToUnlock = htmlspecialchars($_POST['userToUnlock']);
  // Reset tentativedelogin to 0
  $sql = "UPDATE USER SET tentativedelogin = 0 WHERE login = ?";
  $request = $BDD->prepare($sql);
  $request->execute([$userToUnlock]);
  header('Location: ../Users/users.php'); // Redirect to users.php
}
