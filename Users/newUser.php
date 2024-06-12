<?php
  session_start();
  include '/var/www/r2c.uca-project.com/Forms/checkSession.php';

  // Verify if the user has the rights to access this page
  if ($_SESSION['droits'] < 1) {
    header('Location: ../Accueil/accueil.php');
  }
?>
<!DOCTYPE html>
<html>
  <head>
    <title>R2C - Nouvel utilisateur</title>
    <link rel="stylesheet" type="text/css" href="../Users/newUser.css">
    <link rel="icon" type="image/png" href="../Img/icon.png">
  </head>
  <header>
    <?php include '/var/www/r2c.uca-project.com/Header/header.php'; ?>
  </header>
  <body>
    <div class="top">
      <h1>Créer un nouvel utilisateur</h1>
    </div>
    <form
      id="newUser-form"
      class="newUser-form"
      action="../Forms/newUserScript.php"
      method="post"
    >
      <input
        type="text"
        id="username"
        name="username"
        placeholder="Nom d'utilisateur"
        required
      />
      <div id="errusr7" class="errorusr">
        <p>Ce nom d'utilisateur est déja utilisé</p>
      </div>
      <div id="errusr8" class="errorusr">
        <p>Le nom d'utilisateur ne doit pas contenir de caractères spéciaux ni d'espaces</p>
      </div>
      <label for="newuserpsw" class="infomsgusr">
        <p>Le mot de passe doit faire un minimum de <?=$specspsw['size']?> caracteres 
          et contenir au moins <?=$specspsw['number']?> chiffres, <?=$specspsw['uppercase']?> majuscules 
          et <?=$specspsw['specialchar']?> caratères spéciaux. Il ne doit pas non plus contenir de caractères accentués, ni le nom de l'utilisateur.
        </p>
      </label>
      <input
        type="password"
        id="newuserpsw"
        name="newuserpsw"
        placeholder="Mot de passe"
        required
      />
      <input
        type="password"
        id="newuserpsw2"
        name="newuserpsw2"
        placeholder="Confirmer le mot de passe"
        required
      />
      <div id="errusr0" class="errorusr">
        <p>Les mots de passe ne correspondent pas</p>
      </div>
      <div id="errusr1" class="errorusr">
        <?php echo '<p>Le mot de passe doit faire aux moins '.$specspsw['size'].' caractères</p>';?>
      </div>
      <div id="errusr2" class="errorusr">
        <?php echo '<p>Le mot de passe doit contenir au moins '.$specspsw['number'].' chiffres</p>';?>
      </div>
      <div id="errusr3" class="errorusr">
        <?php echo '<p>Le mot de passe doit contenir au moins '.$specspsw['specialchar'].' caractères spéciaux</p>';?>
      </div>
      <div id="errusr4" class="errorusr">
        <?php echo '<p>Le mot de passe doit contenir au moins '.$specspsw['uppercase'].' majuscule</p>';?>
      </div>
      <div id="errusr5" class="errorusr">
        <p>Le mot de passe ne doit pas contenir le login</p>
      </div>
      <div id="errusr6" class="errorusr">
        <p>Le mot de passe ne doit pas contenir de caractères accentués</p>
      </div>
      <select id="role" name="role" required>
        <option value="" disabled selected>Choisir un rôle</option>
        <option value="1">Admin</option>
        <option value="0">Utilisateur</option>
      </select>
      <button class="enregistrer" id="enregistrerUser" type="submit" value="Enregistrer">Enregistrer</button>
      <a href="../Users/users.php"><button class="annulerUser" type="button">Annuler</button></a>
    </form>
  </body>
  <script src="../Users/newUser.js"></script>