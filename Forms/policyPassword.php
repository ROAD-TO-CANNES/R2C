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
    <content>
      <form class="policy" action="../Forms/policyPasswordScript.php" method="post">
        <h1>Nombre de caractères spéciaux</h1>
        <input type="number" name="specialchar" value="<?=$specialchar?>">
        <button type="submit">Enregistrer</button>
      </form>
      <form class="policy" action="../Forms/policyPasswordScript.php" method="post">
        <h1>Nombre de Majuscules</h1>
        <input type="number" name="uppercase" value="<?=$uppercase?>">
        <button type="submit">Enregistrer</button>
      </form>
      <form class="policy" action="../Forms/policyPasswordScript.php" method="post">
        <h1>Longueur</h1>
        <input type="number" name="size" value="<?=$size?>">
        <button type="submit">Enregistrer</button>
      </form>
      <form class="policy" action="../Forms/policyPasswordScript.php" method="post">
        <h1>Nombre de Chiffres</h1>
        <input type="number" name="number" value="<?=$number?>">
        <button type="submit">Enregistrer</button>
      </form>
    </content>
  </body>
</html>