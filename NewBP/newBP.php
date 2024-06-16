<?php 
  session_start(); 
  include '/var/www/r2c.uca-project.com/Forms/checkSession.php';

  // Retrieve the programs
  $sql = "SELECT * FROM PROGRAMME";
  $request = $BDD->prepare($sql);
  $request->execute();
  $progs = $request->fetchAll();
  usort($progs, function($a, $b) {
    return strnatcasecmp($a['nomprog'], $b['nomprog']);
  });

  // Retrieve the keywords
  $sql = "SELECT * FROM MOTSCLEF";
  $request = $BDD->prepare($sql);
  $request->execute();
  $motsClefs = $request->fetchAll();
  usort($motsClefs, function($a, $b) {
    return strnatcasecmp($a['motclef'], $b['motclef']);
  });

  //Retrieve the phases
  $sql = "SELECT * FROM PHASE";
  $request = $BDD->prepare($sql);
  $request->execute();
  $phases = $request->fetchAll();
?>

<!DOCTYPE html>
<html>
  <head>
    <title>R2C - Nouvelle bonne pratique</title>
    <link rel="stylesheet" type="text/css" href="./newBP.css">
    <link rel="icon" type="image/png" href="../Img/icon.webp">
  </head>
  <header>
    <?php include '/var/www/r2c.uca-project.com/Header/header.php'; ?>
  </header>
  <body>
    <div class="top">
      <h1>Créer une nouvelle bonne pratique</h1>
    </div>
    <form
      id="newBP-form"
      class="newBP-form"
      action="../NewBP/newBPScript.php"
      method="post"
    >
      <input
        type="text"
        id="nombp"
        name="nombp"
        placeholder="Nom de la bonne pratique"
        required
      />
      <?php
        if($_SESSION['droits'] > 0) {// if user is admin or super admin
          // Display the switch to activate or deactivate the good practice
          echo '
            <input class="switch-case" type="checkbox" id="switch" name="switch" checked />
            <label class="switch" for="switch">Activer/Désactiver la bonne pratique : </label> 
          ';
        } elseif($_SESSION['droits'] == 0) {// if user is a user
          // Hide the switch to activate the good practice
          echo '
            <input class="switch-case" type="hidden" id="switch" name="switch" value="on" />
          ';
        }
      ?>
        <select id="selectPhase" name="phase">
        <option class="default_value" value="" disabled selected>Phases</option>
        <?php
          foreach($phases as $i => $phase) {// Display all phases in the select
            echo('<option value="'.$phases[$i]['idphase'].'">'.$phases[$i]['descript'].'</option>');
          }
        ?>
      </select>
      <textarea
        id="descbp"
        name="descbp"
        placeholder="Décrivez la bonne pratique"
        rows="4" 
        cols="50"
        required
      ></textarea>
      <input type="hidden" id="divIds" name="divIds" />
      <button class="enregistrer" type="submit" value="Enregistrer">Enregistrer</button>
      <a href="../Accueil/accueil.php"><button class="annulerBP" type="button">Annuler</button></a>
    </form>
    <div class="select-filtre">
      <?php
        include '/var/www/r2c.uca-project.com/Forms/selectFiltres.php';          
      ?>
    </div>
    <script src="../NewBP/selectFiltres.js"></script>
    <script src="../NewBP/addMc.js"></script>
  </body>
</html>