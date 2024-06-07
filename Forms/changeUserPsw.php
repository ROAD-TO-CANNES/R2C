<?php
  session_start();
  include '/var/www/r2c.uca-project.com/bdd.php';

  $sql = "SELECT * FROM SPECSPSW";
  $request = $BDD->prepare($sql);
  $request->execute();
  $specspsw = $request->fetch(); 
?>
<link rel="stylesheet" href="../Forms/change_psw.css" />

<form
  id="change-password-form-user"
  class="change-password-form"
  action="../Forms/change_pswScript.php"
  method="post"
>
  <h1>Changer le mot de passe</br>de l'utilisateur <?= $pswname ?></h1>
  <input type="hidden" name="login" value="<?= $pswname ?>">
  <label for="new_psw" class="infomsg">
    <p>Le mot de passe doit faire un minimum de <?=$specspsw['size']?> caracteres 
      et contenir au moins <?=$specspsw['number']?> chiffres, <?=$specspsw['uppercase']?> majuscules 
      et <?=$specspsw['specialchar']?> caratères spéciaux.
    </p>
  </label>
  <input
    type="password"
    name="new_psw"
    placeholder="Nouveau mot de passe"
    required
  />
  <input
    type="password"
    name="new_psw2"
    placeholder="Confirmer le nouveau mot de passe"
    required
  />
  <div id="err2usr" class="error">
    <p>Les mots de passe ne correspondent pas</p>
  </div>
  <div id="err3usr" class="error">
    <p>Veuillez choisir un mot de passe différent de l'actuel</p>
  </div>
  <div id="err4usr" class="error">
    <?php echo '<p>Le mot de passe doit faire aux moins '.$specspsw['size'].' caractères</p>';?>
  </div>
  <div id="err5usr" class="error">
    <?php echo '<p>Le mot de passe doit contenir au moins '.$specspsw['number'].' chiffres</p>';?>
  </div>
  <div id="err6usr" class="error">
    <?php echo '<p>Le mot de passe doit contenir au moins '.$specspsw['specialchar'].' caractères spéciaux</p>';?>
  </div>
  <div id="err7usr" class="error">
    <?php echo '<p>Le mot de passe doit contenir au moins '.$specspsw['uppercase'].' majuscule</p>';?>
  </div>
  <button class="valider" id="validerusr" type="submit" value="Valider">Valider</button>
  <button class="annuler" id="annulerusr" type="button">Annuler</button>
</form>
<div class="valid" id="validusr">
  <p>Mot de passe changé avec succès</p>
  <svg
    xmlns="http://www.w3.org/2000/svg"
    xmlns:xlink="http://www.w3.org/1999/xlink"
    version="1.1"
    id="Layer_1"
    x="0px"
    y="0px"
    viewBox="0 0 32 32"
    enable-background="new 0 0 32 32"
    xml:space="preserve"
  >
    <circle
      class="ok circle"
      fill="none"
      stroke="#28a745"
      stroke-width="1"
      stroke-miterlimit="10"
      cx="16"
      cy="16"
      r="12"
    />
    <polyline
      class="ok check"
      fill="none"
      stroke="#28a745"
      stroke-width="1"
      stroke-miterlimit="10"
      points="23,12 15,20 10,15 "
    />
  </svg>
  <button id="okbtnusr">OK</button>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>document
  .getElementById("change-password-form-user")
  .addEventListener("submit", function (event) {
    event.preventDefault();
    fetch("../Forms/change_pswScript.php", {
      method: "POST",
      body: new FormData(event.target),
    })
      .then((response) => response.json())
      .then((data) => {
        console.log(data);
        switch (data) {
          case 0:
            $("#change-password-form-user").css("display", "none");
            $("#validusr").css("display", "flex");
            break;
          case 2:
            $("#err2usr").addClass("act");
            break;
          case 3:
            $("#err3usr").addClass("act");
            break;
          case 4:
            $("#err4usr").addClass("act");
            break;
          case 5:
            $("#err5usr").addClass("act");
            break;
          case 6:
            $("#err6usr").addClass("act");
            break;
          case 7:
            $("#err7usr").addClass("act");
            break;
          default:
            break;
        }
      });
  });
  </script>