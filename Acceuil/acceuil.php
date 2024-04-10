<?php 
  session_start(); 

  include '/home/r2c/R2C/bdd.php';

  // verify if the user is already connected
  $sql = "SELECT statutcon FROM USER WHERE login LIKE '$_SESSION[name]'";
  $request = $BDD->prepare($sql);
  $request->execute();
  $statutcon = $request->fetchColumn();

  // if the user is not connected, redirect to the login page
  if($statutcon == 0) {
    header('Location: ../index.php');
  }

  include '/home/r2c/R2C/timer.php';

  //Récuperation des bonnes pratiques
  $sql = "SELECT * FROM BONNESPRATIQUES";
  $request = $BDD->prepare($sql);
  $request->execute();
  $bps = $request->fetchAll();

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

  // Récupération des filtres
  $filtre = json_decode($_POST['divIds'])
?>

<!DOCTYPE html>
<html>
  <head>
    <title>R2C - Acceuil</title>
    <link rel="stylesheet" type="text/css" href="../Acceuil/acceuil.css">
    <link rel="icon" type="image/png" href="../Img/icon.png">
  </head>
  <header>
    <?php include '/home/r2c/R2C/Header/header.php'; ?>
  </header>
  <body>
    <div class="top">
      <h1>Selectionnez les bonne pratiques souhaitée</h1>
      <div class="btns">
        <?php
          if ($_SESSION['droits'] > 0) {
            echo('
                <a href="../NewProg/newProg.php"><button>Créer un programme</button></a>
                <a href="../Users/users.php"><button>Gérer les utilisateur</button></a>
            ');
          }
        ?>
        <button>Créer une bonne pratique</button>
        <button style="width: 4vw;" id="filtre"><img src="../Img/filter.png" alt="filtrer"></button>
      </div>    
    </div>
    <div class="scroll">
      <?php
        foreach ($bps as $i => $bp) { 
          $date = date('j F Y à g:i', strtotime($bps[$i]['dateprog'])); 
          echo('
            <div class="bp">
              <h2>'.$bps[$i]['descbp'].'</h2>
            </div>
          ');
        }
      ?>
    </div>
    <a><button class="pdf_btn"><img src="../Img/pdf.png" alt="PDF">Générer un fichier PDF</button></a>
    <a><button class="csv_btn"><img src="../Img/csv.png" alt="CSV">Générer un fichier CSV</button></a>
    <div class="select-fond_popup"></div> 
    <div class="select-popup">
      <?php
        echo('
        <div class="divProg">
          <select id="selectProg" name="programme" form="formProg">
            <option class="default_value" value="">Programmes</option>
          ');
          foreach($progs as $i => $prog) {
            echo('<option value="'.$progs[$i]['idprog'].'">'.$progs[$i]['nomprog'].'</option>');
          }
          echo('
          </select>
          <h2>Sélectionnez les programmes</h2>
          <div class="selected-itemsDiv" id="selected-itemsProg"></div>
        </div>
        <div class="divPhase">
          <select id="selectPhase" name="phase" form="formPhase">
            <option class="default_value" value="" disabled selected>Phases</option>
          ');
          foreach($phases as $i => $phase) {
            echo('<option value="'.$phases[$i]['idphase'].'">Phase '.$phases[$i]['idphase'].'</option>');
          }
          echo('
          </select>
          <h2>Sélectionnez les phases</h2>
          <div class="selected-itemsDiv" id="selected-itemsPhase"></div>
        </div>
        <div class="divMotClef">
          <select id="selectMotClef" name="motClef" form="formMotClef">
            <option class="default_value" value="">Mots clefs</option>
          ');
          foreach($motsClefs as $i => $motClef) {
            echo('<option value="'.$motsClefs[$i]['idmotclef'].'">'.$motsClefs[$i]['motclef'].'</option>');
          }
          echo('
          </select>
          <h2>Sélectionnez les mots clefs</h2>
          <div class="selected-itemsDiv" id="selected-itemsMotClef"></div>
        </div>
        ');       
      ?>
      <form id="hiddenForm" action="../Acceuil/acceuil.php" method="post">
        <input type="hidden" id="divIdsInput" name="divIds">
      </form>
      <div class="divBtn">
        <a class="livalidbtn"><button id="validBtn">Valider</button></a>
        <a class="licancelbtn"><button id="cancelBtn">Annuler</button></a>
      </div>
    </div>
  </body>
  <script src="../Acceuil/acceuil.js"></script>
  <script src="../timer.js"></script>
</html>