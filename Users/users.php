<?php
  session_start(); 

  include '/home/r2c/R2C/bdd.php';

  // verify if the user is already connected
  $sql = "SELECT statutcon FROM USER WHERE login LIKE '$_SESSION[name]'";
  $request = $BDD->prepare($sql);
  $request->execute();
  $statutcon = $request->fetchColumn();

  // if the user is not connected, redirect to the login page
  if($statutcon == 0) {
    header('Location: ../index.php');
  }

  include '/home/r2c/R2C/timer.php';

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
    <link rel="stylesheet" type="text/css" href="../Acceuil/acceuil.css">
    <link rel="icon" type="image/png" href="../Img/icon.png">
  </head>
  <header>
    <?php include '/home/r2c/R2C/Header/header.php'; ?>
  </header>
  <body>
  <div class="top">
      <h1>Gérer les utilisateurs</h1>
      <div class="btns">
        <button class="btn">Créer un nouvel utilisateur</button>
      </div>
    </div>
    <div class="scroll">
      <?php
        foreach ($users as $i => $user) { 
          $date = date('j F Y à g:i', strtotime($users[$i]['dateprog'])); 
          echo('
            <div class="bp">
              <h2>'.$users[$i]['login'].'</h2>
            </div>
          ');
        }
      ?>
    </div>
  </body>
  <script src="../timer.js"></script>
</html>