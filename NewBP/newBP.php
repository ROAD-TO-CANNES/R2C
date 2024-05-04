<?php 
  session_start(); 

  include '/home/r2c/R2C/Forms/checkSession.php';

  // Récupération des programmes
  $sql = "SELECT * FROM PROGRAMME";
  $request = $BDD->prepare($sql);
  $request->execute();
  $progs = $request->fetchAll();
  usort($progs, function($a, $b) {
    return strcmp($a['nomprog'], $b['nomprog']);
  });

  // Récupération des mots clefs
  $sql = "SELECT * FROM MOTSCLEF";
  $request = $BDD->prepare($sql);
  $request->execute();
  $motsClefs = $request->fetchAll();
  usort($motsClefs, function($a, $b) {
    return strcmp($a['motclef'], $b['motclef']);
  });

  //Récuperation des phases
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
    <link rel="icon" type="image/png" href="../Img/icon.png">
  </head>
  <header>
    <?php include '/home/r2c/R2C/Header/header.php'; ?>
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
      <input class="switch-case" type="checkbox" id="switch" name="switch" checked />
      <label class="switch" for="switch">Activer/Désactiver la bonne pratique : </label>
      <select id="selectPhase" name="phase">
        <option class="default_value" value="" disabled selected>Phases</option>
        <?php
          foreach($phases as $i => $phase) {
            echo('<option value="'.$phases[$i]['idphase'].'">Phase '.$phases[$i]['idphase'].'</option>');
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
      <div class="select-filtre">
        <?php
          include '/home/r2c/R2C/Forms/selectFiltres.php';          
        ?>
      </div>
      <input type="hidden" id="divIds" name="divIds" />
      <button class="enregistrer" type="submit" value="Enregistrer">Enregistrer</button>
      <a class="aannulerBP" href="../Acceuil/acceuil.php"><button class="annulerBP" type="button">Annuler</button></a>
    </form>
    <script src="../NewBP/selectFiltres.js"></script>
  </body>
</html>