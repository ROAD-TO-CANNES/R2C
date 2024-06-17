<?php
session_start();
include '/var/www/r2c.uca-project.com/bdd.php';

// Retrieving the password specifications
$sql = "SELECT * FROM SPECSPSW";
$request = $BDD->prepare($sql);
$request->execute();
$specspsw = $request->fetch();
?>
<link rel="stylesheet" href="../Forms/change_psw.css" />
<form id="change-password-form" class="change-password-form" action="../Forms/change_pswScript.php" method="post">
  <h1>Changer le mot de passe</h1>
  <label for="old_psw" class="infomsg">
    <p>Votre nouveau mot de passe doit faire un minimum de <?= $specspsw['size'] ?> caracteres
      et contenir au moins <?= $specspsw['number'] ?> chiffres, <?= $specspsw['uppercase'] ?> majuscules
      et <?= $specspsw['specialchar'] ?> caratères spéciaux. Il ne doit pas non plus contenir de caractères accentués, ni votre nom d'utilisateur.
    </p>
  </label>
  <input type="password" id="old_psw" name="old_psw" placeholder="Mot de passe actuel" autofocus required />
  <div id="err1" class="error">
    <p>Mot de passe incorrecte</p>
  </div>
  <input type="password" id="new_psw" name="new_psw" placeholder="Nouveau mot de passe" required />
  <input type="password" id="new_psw2" name="new_psw2" placeholder="Confirmer le nouveau mot de passe" required />
  <div id="err2" class="error">
    <p>Les mots de passe ne correspondent pas</p>
  </div>
  <div id="err3" class="error">
    <p>Veuillez choisir un mot de passe différent de l'actuel</p>
  </div>
  <div id="err4" class="error">
    <?php echo '<p>Le mot de passe doit faire aux moins ' . $specspsw['size'] . ' caractères</p>'; ?>
  </div>
  <div id="err5" class="error">
    <?php echo '<p>Le mot de passe doit contenir au moins ' . $specspsw['number'] . ' chiffres</p>'; ?>
  </div>
  <div id="err6" class="error">
    <?php echo '<p>Le mot de passe doit contenir au moins ' . $specspsw['specialchar'] . ' caractères spéciaux</p>'; ?>
  </div>
  <div id="err7" class="error">
    <?php echo '<p>Le mot de passe doit contenir au moins ' . $specspsw['uppercase'] . ' majuscule</p>'; ?>
  </div>
  <div id="err8" class="error">
    <p>Le mot de passe ne doit pas contenir le login de l'utilisateur</p>
  </div>
  <div id="err9" class="error">
    <p>Le mot de passe ne doit pas contenir de caractères accentués</p>
  </div>
  <button class="valider" type="submit" value="Valider">Valider</button>
  <button class="annuler" type="button">Annuler</button>
</form>
<div class="valid">
  <p>Mot de passe changé avec succès</p>
  <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Layer_1" x="0px" y="0px" viewBox="0 0 32 32" enable-background="new 0 0 32 32" xml:space="preserve">
    <circle class="ok circle" fill="none" stroke="#28a745" stroke-width="1" stroke-miterlimit="10" cx="16" cy="16" r="12" />
    <polyline class="ok check" fill="none" stroke="#28a745" stroke-width="1" stroke-miterlimit="10" points="23,12 15,20 10,15 " />
  </svg>
  <button id="okbtn">OK</button>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="../Forms/change_psw.js"></script>