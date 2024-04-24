<?php session_start(); ?> 

<meta charset="UTF-8">
<link rel="stylesheet" type="text/css" href="../Header/header.css">

<?php
  if ($_SESSION['droits'] == 0) {
    $role = 'Utilisateur';
  } elseif ($_SESSION['droits'] == 1) {
    $role = 'Administrateur';
  } elseif ($_SESSION['droits'] == 2) {
    $role = 'Super Administrateur';
  } else {
    $role = 'Erreur de droits';
  };
?>
<div class="fond_popup"></div>
<div class="fond_psw"></div>
<div class="banner">
  <img src="../Img/logo.png" alt="R2C" class="logo">
  <div class="title_header">
    <h1>Bienvenue sur R2C</h1>
    <h2>Votre plateforme de bonne pratique de validation des avioniques</h2>
  </div>
</div>

<div class="auth">
  <?php
    if(isset($_SESSION['name'])){
      echo('
        <div class="parent">
          <div class="auth-popup">
            <span class="close">✘</span>
            <div class="auth-popup-content">
              <img src="../Img/default_pp.png" alt="photo de profile" class="ppa">
              <div class="p">
                <h1>'.ucfirst(strtolower($_SESSION['name'])).'</h1>'.
                $role.'<br>
              </div>
            </div>');
            if($_SESSION['droits'] == 1) {
              echo('<span class="changepsw">Modifier votre mot de passe</span>');
            };
            echo('
            <form method="post" action="../Forms/logout.php">
              <button type="submit">Déconnexion</button>
            </form>
          </div>
          <img src="../Img/default_pp.png" alt="photo de profile" style="background-color:#5893c7" class="pp connected_pp">
        </div>
        <p>Bonjours </br>'. ucfirst(strtolower($_SESSION['name'])) .'</p>'
      );
    } 
    else {
      echo('
        <img src="../Img/default_pp.png" alt="photo de profile" class="pp">
        <p>
          Vous n\'etes pas 
          <br> 
          connecté
        </p>'
      );
    };
  ?>
</div>

<div class="psw-popup">
  <?php include '../Forms/change_psw.html' ?>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="../Header/header.js"></script>
