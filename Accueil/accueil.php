<?php 
  session_start(); 
  include '/var/www/r2c.uca-project.com/Forms/checkSession.php';

  include '/var/www/r2c.uca-project.com/Accueil/compareLists.php';

  //Récuperation des bonnes pratiques
  $sql = "SELECT * FROM BONNESPRATIQUES";
  $request = $BDD->prepare($sql);
  $request->execute();
  $bps = $request->fetchAll();
  if ($_SESSION['droits'] == 0) {
    $bps = array_filter($bps, function($bp) {
      return $bp['statut'] == 1;
    });
  }

  //Récuperation des correspondances entre les bonnes pratiques et les programmes
  $sql = "SELECT * FROM BONNESPRATIQUES_PROGRAMME";
  $request = $BDD->prepare($sql);
  $request->execute();
  $bpsProg = $request->fetchAll();

  //Récuperation des correspondances entre les bonnes pratiques et les mots clefs
  $sql = "SELECT * FROM BONNESPRATIQUES_MOTSCLEF";
  $request = $BDD->prepare($sql);
  $request->execute();
  $bpsMC = $request->fetchAll();

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
  $filtres = json_decode($_COOKIE['filtres']);
  
  $filtrePH = [];
  $filtrePR = [];
  $filtreMC = [];

  foreach($filtres as $filtre) {
    $type = substr($filtre, 0, 2); // Obtient les deux premiers caractères de la chaîne

    switch($type) {
      case 'PH':
        $filtrePH[] = substr($filtre, 2);
        break;
      case 'PR':
        $filtrePR[] = substr($filtre, 2);
        break;
      case 'MC':
        $filtreMC[] = substr($filtre, 2);
        break;
    }
  }

  if(!empty($filtrePR) || !empty($filtrePH) || !empty($filtreMC)) {
    $bpsFiltered = compare_lists($filtrePR, $filtrePH, $filtreMC, $bps, $bpsProg, $bpsMC);
  } else {
    $bpsFiltered = $bps;
  }
?>
<!DOCTYPE html>
<html>
  <head>
    <title>R2C - Accueil</title>
    <link rel="stylesheet" type="text/css" href="../Accueil/accueil.css">
    <link rel="icon" type="image/png" href="../Img/icon.png">

  </head>
  <header>
    <?php include '/var/www/r2c.uca-project.com/Header/header.php'; ?>
  </header>
  <body>
    <div class="fond_delConfirm"></div>
    <div class="top">
      <h1>Selectionnez les bonnes pratiques souhaitée :</h1>
      <div class="btns">
        <?php
          if ($_SESSION['droits'] > 0) {
            echo('
                <a href="../NewProg/newProg.php"><button>Créer un programme</button></a>
                <a href="../Users/users.php"><button>Gérer les utilisateur</button></a>
                <a href="../log/log.php"><button>Consulter les logs</button></a>
            ');
          }
        ?>
        <a href="../NewBP/newBP.php"><button>Créer une bonne pratique</button></a>
        <button style="width: 4vw;" id="filtre"><img src="../Img/filter.png" alt="filtrer"></button>
      </div>    
    </div>
    <div class="scroll">
      <?php
        foreach ($bpsFiltered as $i => $bpFiltered) { 
          $idbp = $bpsFiltered[$i]['idbp'];
          $nombp = $bpsFiltered[$i]['nombp'];
          $statut = $bpsFiltered[$i]['statut'];
          if ($statut == 1) {
            $statutAff = 'Activé';
          } else {
            $statutAff = 'Désactivé';
          }

          $sql = "SELECT idprog FROM BONNESPRATIQUES_PROGRAMME WHERE idbp = $idbp";
          $request = $BDD->prepare($sql);
          $request->execute();
          $progsOfBp = $request->fetchAll();

          $sql = "SELECT idmotclef FROM BONNESPRATIQUES_MOTSCLEF WHERE idbp = $idbp";
          $request = $BDD->prepare($sql);
          $request->execute();
          $motsClefsOfBp = $request->fetchAll();

          echo('
            <div class="bp">
              <h2>'.$nombp.'</h2>
              <input type="checkbox" id="bp'.$i.'" name="idbp" value="'.$idbp.'" checked />
              <label for="bp'.$i.'">Selectionner la bonne pratique</label>  
              <button class="infobtn" id="info'.$idbp.'">Voir la bonne pratique</button>
              <div class="infopopup" id="info'.$idbp.'">');
                include '/var/www/r2c.uca-project.com/Forms/infoBp.php';
              echo ('</div>'); 
              if ($_SESSION['droits'] > 0) {
                echo('
                <form action="../Forms/delBp.php" method="post">');
              }
              elseif ($_SESSION['droits'] == 0) {
                echo('
                <form action="../Accueil/enableDisableBP.php" method="post">');
              }
              echo('
                  <img id="'.$idbp.'" class="corbeille" src="../Img/corbeille.png" alt="corbeille">
                  <div id="'.$idbp.'" class="delConfirm">
                    <p>Êtes-vous sûr de vouloir supprimer <br/> la bonne pratique "'.$nombp.'" ?</p>
                    <input type="hidden" name="idbp" value="'.$idbp.'">
                    <button type="submit">Oui</button>
                    <button type="button">Non</button>
                  </div>
                </form>   
                ');
              if ($_SESSION['droits'] > 0) {
                if ($statut == 1) {
                  echo('
                  <input class="switch-case" type="checkbox" id="switch'.$idbp.'" checked />
                  <label class="switch" for="switch'.$idbp.'">Toggle</label>
                  ');
                } elseif ($statut == 0) {
                  echo('
                  <input class="switch-case" type="checkbox" id="switch'.$idbp.'" />
                  <label class="switch" for="switch'.$idbp.'">Toggle</label>
                  ');
                };
              };
              echo('
            </div>
          ');
        }
        
?>
    </div>
    <a><button class="pdf_btn"><img src="../Img/pdf.png" alt="PDF">Générer un fichier PDF</button></a>
    <a><button class="csv_btn"><img src="../Img/csv.png" alt="CSV">Générer un fichier CSV</button></a>
    <div class="select-fond_popup"></div> 
    <div class="select-popup">
      <button id="rmFiltreBtn">Effacer tous les filtres</button>
      <?php
        include '/var/www/r2c.uca-project.com/Forms/selectFiltres.php';      
      ?>
      <div class="divBtn">
        <button id="validBtn">Valider</button>
        <button id="cancelBtn">Annuler</button>
      </div>
    </div>
  </body>
  <?php 
    // Recuperation de la fenetre ouverte
    if(isset($_GET['info'])) {
    $info = $_GET['info'];
      echo('<script>
        document.querySelector(\'.infopopup#info'.$info.'\').style.display = "flex";
        document.querySelector(\'.fond_delConfirm\').style.display = "block";
      </script>');
    } 
  ?>
  <script src="../Accueil/delConfirm.js"></script>
  <script src="../Accueil/popupFiltres.js"></script>
  <script src="../Accueil/selectFiltres.js"></script>
  <script src="../Accueil/enableDisableBP.js"></script>
  <script src="../Accueil/popupInfo.js"></script>
  <script src="../timer.js"></script>
</html>