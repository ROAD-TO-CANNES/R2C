<?php
session_start();
include '/var/www/r2c.uca-project.com/Forms/checkSession.php';

// Verify if the user has the rights to access this page
if ($_SESSION['droits'] < 1) {
  header('Location: ../Accueil/accueil.php');
}

// Retrieve all programs
$sql = "SELECT * FROM PROGRAMME";
$request = $BDD->prepare($sql);
$request->execute();
$progs = $request->fetchAll();
// Sort the programs by name (case-insensitive)
usort($progs, function ($a, $b) {
  return strnatcasecmp($a['nomprog'], $b['nomprog']);
});
?>
<!DOCTYPE html>
<html>

<head>
  <title>R2C - Programmes</title>
  <link rel="stylesheet" type="text/css" href="./progs.css">
  <link rel="icon" type="image/png" href="../Img/icon.webp">
</head>
<header>
  <?php include '/var/www/r2c.uca-project.com/Header/header.php'; ?>
</header>

<body>
  <div class="fond_delConfirm"></div>
  <div class="top">
    <h1>Gérer les programmes</h1>
    <div class="btns">
      <a href="../Prog/newProg.php"><button>Créer un programme</button></a>
    </div>
  </div>
  <div class="scroll">
    <?php
    // Display all programs
    foreach ($progs as $i => $prog) {
      $name = $progs[$i]['nomprog'];
      $desc = $progs[$i]['descprog'];
      $id = $progs[$i]['idprog'];
    ?>
      <div class="prog">
        <h2><?= $name ?></h2>
        <div class="wrapper">
          <p><?= $desc ?></p>
        </div>
        <button id="<?= $id ?>" class="corbeille">Supprimer</button>
        <form id="<?= $id ?>" action="../Forms/deleteProg.php" method="get" class="delConfirm">
          <p>Êtes-vous sûr de vouloir supprimer <br /> le programme "<?= $name ?>" ?</p>
          <input type="hidden" name="idprog" value="<?= $id ?>">
          <button type="submit">Oui</button>
          <button type="button">Non</button>
        </form>
      </div>
    <?php } ?>
  </div>
</body>
<script src="../Accueil/delConfirm.js"></script>

</html>