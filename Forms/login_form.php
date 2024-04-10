<?php session_start(); ?>

<link rel="stylesheet" type="text/css" href="./Forms/login.css">

<div class="login-wrap">
  <h2>Connectez vous a <br> R2C</h2>
  <?php
    if(isset($_GET['error']) && $_GET['error'] == "e1"){
      echo("
        <div class=\"error\">
          <p>Nom d'utilisateur ou mot de passe incorrect</p>
        </div>"
      );
    };
    if(isset($_GET['error']) && $_GET['error'] == "e2"){
      echo("
        <div class=\"error\">
          <p>Votre compte est bloqué veuillez contacter votre administrateur</p>
        </div>"
      );
    };
    if(isset($_GET['error']) && $_GET['error'] == "e3"){
      echo("
        <div class=\"error\">
          <p>Désolé, un utilisateur est déjà connecté. Une seule connexion à la fois est autorisée</p>
        </div>"
      );
    };
    if(isset($_COOKIE['m1']) && $_COOKIE['m1'] == "1") {
      echo("
        <div class=\"message\">
          <p>Vous avez été déconnecté pour cause d'innactivitée</p>
        </div>"
      );
    };
  ?>
    <form method="post" action="./Forms/login.php">
      <div class="mb-3">
        <input type="userName" class="form-control" id="userName" name="userName" placeholder="Nom d'utilisateur" required autofocus>
      </div>
      <div class="mb-3">
        <input type="password" class="form-control" id="password" name="password" placeholder="Mot de passe" required>
      </div>
      <button type="submit" class="btn btn-primary">Se connecter</button>
    </form>
</div>