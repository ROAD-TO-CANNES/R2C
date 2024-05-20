<?php
  session_start(); 

  include '/var/www/r2c.uca-project.com/Forms/checkSession.php';

  //Récuperation des utilisateurs
  $sql = "SELECT * FROM USER";
  $request = $BDD->prepare($sql);
  $request->execute();
  $users = $request->fetchAll();
?>
<!DOCTYPE html>
<html>
  <head>
    <title>R2C - Admin</title>
    <link rel="stylesheet" type="text/css" href="../Accueil/accueil.css">
    <link rel="icon" type="image/png" href="../Img/icon.png">
  </head>
  <body>
  </body>
</html>