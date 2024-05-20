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
  <header>
    <?php include '/var/www/r2c.uca-project.com/Header/header.php'; ?>
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