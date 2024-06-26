<?php
session_start();
include '/var/www/r2c.uca-project.com/Forms/checkSession.php';
include '/var/www/r2c.uca-project.com/Accueil/compareLists.php';

// Retrieving best practices
$sql = "SELECT * FROM BONNESPRATIQUES";
$request = $BDD->prepare($sql);
$request->execute();
$bps = $request->fetchAll();
if ($_SESSION['droits'] == 0) {
  $bps = array_filter($bps, function ($bp) {
    return $bp['statut'] == 1;
  });
}

// Retrieving the associations between best practices and programs
$sql = "SELECT * FROM BONNESPRATIQUES_PROGRAMME";
$request = $BDD->prepare($sql);
$request->execute();
$bpsProg = $request->fetchAll();

// Retrieving the associations between best practices and keywords
$sql = "SELECT * FROM BONNESPRATIQUES_MOTSCLEF";
$request = $BDD->prepare($sql);
$request->execute();
$bpsMC = $request->fetchAll();

// Retrieving programs
$sql = "SELECT * FROM PROGRAMME";
$request = $BDD->prepare($sql);
$request->execute();
$progs = $request->fetchAll();
usort($progs, function ($a, $b) {
  return strnatcasecmp($a['nomprog'], $b['nomprog']);
});

// Retrieving keywords
$sql = "SELECT * FROM MOTSCLEF";
$request = $BDD->prepare($sql);
$request->execute();
$motsClefs = $request->fetchAll();
usort($motsClefs, function ($a, $b) {
  return strnatcasecmp($a['motclef'], $b['motclef']);
});

// Retrieving phases
$sql = "SELECT * FROM PHASE";
$request = $BDD->prepare($sql);
$request->execute();
$phases = $request->fetchAll();

// Retrieving filters
$filtres = json_decode($_COOKIE['filtres']);

$filtrePH = [];
$filtrePR = [];
$filtreMC = [];

// Separating filters by type
foreach ($filtres as $filtre) {
  $type = substr($filtre, 0, 2); // Get the first two characters of the string

  switch ($type) {
    case 'PH': // PH for Phase
      $filtrePH[] = substr($filtre, 2);
      break;
    case 'PR': // PR for Program
      $filtrePR[] = substr($filtre, 2);
      break;
    case 'MC': // MC for Keyword
      $filtreMC[] = substr($filtre, 2);
      break;
  }
}

// Filtering best practices
if (!empty($filtrePR) || !empty($filtrePH) || !empty($filtreMC)) {
  $bpsFiltered = compare_lists($filtrePR, $filtrePH, $filtreMC, $bps, $bpsProg, $bpsMC);
} else { // If no filter is applied
  $bpsFiltered = null;
}
?>
<!DOCTYPE html>
<html>

<head>
  <title>R2C - Accueil</title>
  <link rel="stylesheet" type="text/css" href="../Accueil/accueil.css">
  <link rel="icon" type="image/png" href="../Img/icon.webp">

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
        echo ('
                <a href="../log/log.php"><button>Consulter les logs</button></a>
                <a href="../Users/users.php"><button>Gérer les utilisateurs</button></a>
                <a href="../Prog/progs.php"><button>Gérer les programmes</button></a>
            ');
      }
      ?>
      <a href="../NewBP/newBP.php"><button>Créer une bonne pratique</button></a>
      <button style="width: 4vw;" class="filtre"><img src="../Img/filter.webp" alt="filtrer"></button>
    </div>
  </div>
  <?php
  if ($bpsFiltered == null) { // If no filter is applied or if no results are found show the filter button
    echo '
        <div style="overflow: hidden; justify-content: center; align-items: center" class="scroll">
          <p style="font-size: x-large;">Veulliez appliquer des filtres pour afficher les bonnes pratiques correspondantes</p>
          <button class="filtre" id="bigFiltre">Filtrer<img src="../Img/filter.webp" alt="filtrer"></button>
          <style>
            .pdf_btn,
            .csv_btn {
              display: none;
            }
          </style>';
  } else { // Otherwise display filtered best practices
    echo '<div class="scroll">';
    foreach ($bpsFiltered as $i => $bpFiltered) {  // New element for each good practice
      $idbp = $bpsFiltered[$i]['idbp'];
      $nombp = $bpsFiltered[$i]['nombp'];
      $statut = $bpsFiltered[$i]['statut'];
      if ($statut == 1) {
        $statutAff = 'Activé';
      } else {
        $statutAff = 'Désactivé';
      }
      $idphase = $bpsFiltered[$i]['phase'];
      foreach ($phases as $phase) {
        if ($phase['idphase'] == $idphase) {
          $nomphase = $phase['descript'];
        }
      }
      $descbp = $bpsFiltered[$i]['descbp'];

      // Retrieving programs and keywords associated with the best practice
      $sql = "SELECT idprog FROM BONNESPRATIQUES_PROGRAMME WHERE idbp = $idbp";
      $request = $BDD->prepare($sql);
      $request->execute();
      $progsOfBp = $request->fetchAll();

      $sql = "SELECT idmotclef FROM BONNESPRATIQUES_MOTSCLEF WHERE idbp = $idbp";
      $request = $BDD->prepare($sql);
      $request->execute();
      $motsClefsOfBp = $request->fetchAll();

      echo ('
            <div class="bp">
              <h2>' . $nombp . '</h2>
              <div class="selectionbp">
                <label for="bp' . $i . '">Selectionner la bonne pratique :</label>');
      if ($statut == 1) {
        echo ('<input type="checkbox" id="bp' . $i . '" name="checkedBp" value="' . $idbp . '" checked />');
      } elseif ($statut == 0) {
        echo ('<input type="checkbox" id="bp' . $i . '" name="checkedBp" value="' . $idbp . '" />');
      };
      echo ('
              </div>
              <button class="infobtn" id="info' . $idbp . '">Voir la bonne pratique</button>
              <div class="infopopup" id="info' . $idbp . '">');
      include '/var/www/r2c.uca-project.com/Forms/infoBp.php';
      echo ('</div>');
      if ($_SESSION['droits'] > 0) { // If the user has rights, display the delete button
        echo ('
                <form action="../Forms/delBp.php" method="post">');
      } elseif ($_SESSION['droits'] == 0) { // If the user has no rights, display the disable button
        echo ('
                <form action="../Accueil/enableDisableBP.php" method="post">');
      }
      echo ('
                  <img ');
      if ($_SESSION['droits'] == 0) {
        echo 'style="margin-right: 1vw"';
      };
      echo 'id="' . $idbp . '" class="corbeille" src="../Img/corbeille.webp" alt="corbeille">
                  <div id="' . $idbp . '" class="delConfirm">
                    <p>Êtes-vous sûr de vouloir supprimer <br/> la bonne pratique "' . $nombp . '" ?</p>
                    <input type="hidden" name="idbp" value="' . $idbp . '">
                    <button type="submit">Oui</button>
                    <button type="button">Non</button>
                  </div>
                </form>   
                ';
      if ($_SESSION['droits'] > 0) {  // If the user has rights, display the enable/disable button
        if ($statut == 1) {
          echo ('
                  <input class="switch-case" type="checkbox" id="switch' . $idbp . '" checked />
                  <label class="switch" for="switch' . $idbp . '">Toggle</label>
                  ');
        } elseif ($statut == 0) {
          echo ('
                  <input class="switch-case" type="checkbox" id="switch' . $idbp . '" />
                  <label class="switch" for="switch' . $idbp . '">Toggle</label>
                  ');
        };
      };
      echo ('
            </div>
          ');
    }
  }
  ?>
  </div>
  <form id="pythonPDF" action="../Python/generatePDF.php" method="post">
    <input type="hidden" id="generate_pdf" name="generate_pdf">
    <input type="hidden" id="generate_pdf-phase" name="generate_pdf-phase">
    <input type="hidden" id="generate_pdf-keyword" name="generate_pdf-keyword">
    <input type="hidden" id="generate_pdf-prog" name="generate_pdf-prog">
    <button type="submit" class="pdf_btn"><img src="../Img/pdf.webp" alt="PDF">Générer un fichier PDF</button>
  </form>
  <form id="pythonCSV" action="../Python/generateCSV.php" method="post">
    <input type="hidden" id="generate_csv" name="generate_csv">
    <input type="hidden" id="generate_csv-phase" name="generate_csv-phase">
    <input type="hidden" id="generate_csv-keyword" name="generate_csv-keyword">
    <input type="hidden" id="generate_csv-prog" name="generate_csv-prog">
    <button type="submit" class="csv_btn"><img src="../Img/csv.webp" alt="CSV">Générer un fichier CSV</button>
  </form>
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
// Displaying the popup for the good practice creation
if (isset($_GET['info'])) {
  $info = $_GET['info'];
  echo ('<script>
        document.querySelector(\'.infopopup#info' . $info . '\').style.display = "flex";
        document.querySelector(\'.fond_delConfirm\').style.display = "block";
      </script>');
}
?>
<script src="../Accueil/delConfirm.js"></script>
<script src="../Accueil/popupFiltres.js"></script>
<script src="../Accueil/selectFiltres.js"></script>
<script src="../Accueil/enableDisableBP.js"></script>
<script src="../Accueil/popupInfo.js"></script>
<script src="../Accueil/updateInputs.js"></script>
<script src="../timer.js"></script>

</html>