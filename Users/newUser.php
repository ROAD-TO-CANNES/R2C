<?php
  session_start();
  include '/var/www/r2c.uca-project.com/Forms/checkSession.php';

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
      <label for="newuserpsw" class="infomsgusr">
        <p>Le mot de passe doit faire un minimum de <?=$specspsw['size']?> caracteres 
          et contenir au moins <?=$specspsw['number']?> chiffres, <?=$specspsw['uppercase']?> majuscules 
          et <?=$specspsw['specialchar']?> caratères spéciaux.
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
      <select id="role" name="role" required>
        <option value="" disabled selected>Choisir un rôle</option>
        <option value="1">Admin</option>
        <option value="0">Utilisateur</option>
      </select>
      <button class="enregistrer" type="submit" value="Enregistrer">Enregistrer</button>
      <a href="../Users/users.php"><button class="annulerUser" type="button">Annuler</button></a>
    </form>
  </body>
  <script>
    document
  .getElementById("newUser-form")
  .addEventListener("submit", function (event) {
    event.preventDefault();
    fetch("../Forms/newUserScript.php", {
      method: "POST",
      body: new FormData(event.target),
    })
      .then((response) => response.json())
      .then((data) => {
        switch (data) {
          case 0:
            $("#errusr0").css("display", "block");
            break;
          case 1:
            $("#errusr1").css("display", "block");
            break;
          case 2:
            $("#errusr2").css("display", "block");
            break;
          case 3:
            $("#errusr3").css("display", "block");
            break;
          case 4:
            $("#errusr4").css("display", "block");
            break;
          case 5:
            document.getElementById("newUser-form").reset();
            window.location.href = "../Users/users.php";
          default:
            break;
        }
      });
  });

// Change password popup
$(".annuler").on("click", function () {
  $("#errusr0").css("display", "none");
  $("#errusr1").css("display", "none");
  $("#errusr2").css("display", "none");
  $("#errusr3").css("display", "none");
  $("#errusr4").css("display", "none");
  document.getElementById("newUser-form").reset();
});

$(".enregistrer").on("click", function () {
  $("#errusr0").css("display", "none");
  $("#errusr1").css("display", "none");
  $("#errusr2").css("display", "none");
  $("#errusr3").css("display", "none");
  $("#errusr4").css("display", "none");
});

  </script>