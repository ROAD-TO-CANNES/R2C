<?php
  session_start();
  include '/var/www/r2c.uca-project.com/Forms/checkSession.php';

  // Verify if user is the SuperAdmin
  if ($_SESSION['droits'] != 2) {
    
  } 

  //Retrieve the password policy
  $sql = "SELECT * FROM SPECSPSW";
  $request = $BDD->prepare($sql);
  $request->execute();
  $policy = $request->fetch();

  //Split the password policy
  $specialchar = $policy['specialchar'];
  $uppercase = $policy['uppercase'];
  $size = $policy['size'];
  $number = $policy['number'];
?>
<!DOCTYPE html>
<html>
  <head>
    <title>R2C - Politique des mots de passe</title>
    <link rel="stylesheet" type="text/css" href="./policyPassword.css">
    <link rel="icon" type="image/png" href="../Img/icon.png">
  </head>
  <header>
    <?php include '/var/www/r2c.uca-project.com/Header/header.php'; ?>
  </header>
  <body>
    <div class="top">
      <h1>Modifier la politique des mots de passe</h1>
      <a href="../Accueil/accueil.php"><button>Retours</button></a>
    </div>
    <form class="formPolicy" action="../Forms/policyPasswordScript.php" method="post">
      <policy>
        <h1>Nombre de caractères spéciaux</h1>
        <input type="number" name="specialchar" value="<?=$specialchar?>">
        <label <?php if(isset($_GET['specialchar']) && $_GET['specialchar'] == "ok") {echo 'style="display:block"';}?> for="specialchar">Nombre de caractères spéciaux modifier a <?=$specialchar?> minimum</label>
        <button type="submit">Enregistrer</button>
      </policy>
      <policy>
        <h1>Nombre de Majuscules</h1>
        <input type="number" name="uppercase" value="<?=$uppercase?>">
        <label <?php if(isset($_GET['uppercase']) && $_GET['uppercase'] == "ok") {echo 'style="display:block"';}?> for="uppercase">Nombre de majuscules modifier a <?=$uppercase?> minimum</label>
        <button type="submit">Enregistrer</button>
      </policy>
      <policy>
        <h1>Longueur</h1>
        <input type="number" name="size" value="<?=$size?>">
        <label <?php if(isset($_GET['size']) && $_GET['size'] == "ok") {echo 'style="display:block"';}?> for="size">Longueur minimum du mot de passe modifier a <?=$size?> caractères</label>
        <button type="submit">Enregistrer</button>
      </policy>
      <policy>
        <h1>Nombre de Chiffres</h1>
        <input type="number" name="number" value="<?=$number?>">
        <label <?php if(isset($_GET['number']) && $_GET['number'] == "ok") {echo 'style="display:block"';}?> for="number">Nombre de chiffres modifier a <?=$number?> minimum</label>
        <button type="submit">Enregistrer</button>
      </policy>
    </form>
  </body>
</html>