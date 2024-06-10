<?php
  session_start();
  include '/var/www/r2c.uca-project.com/Forms/checkSession.php';

  if (isset($_POST['nomprog']) && isset($_POST['descprog'])) {
    $sql = "SELECT nomprog FROM PROGRAMME WHERE nomprog = ?";
    $request = $BDD->prepare($sql);
    $request->execute([$_POST['nomprog']]);
    $result = $request->fetch();
    if ($result) {
      //Log d'erreur de création de programme//
      $typelog = "Warning";
      $desclog = 'Erreur lors de la création d\'un programme "'.$_POST['nomprog'].'" existe déjà';
      $loginlog = $_SESSION['name'];
      include '/var/www/r2c.uca-project.com/Forms/addLogs.php';
      header('Location: ../Validation/validation.php?message=eprogexist');
      exit();
    }

    $nomprog = htmlspecialchars($_POST['nomprog']);
    $descprog = htmlspecialchars($_POST['descprog']);

    $nomprog_seq = $BDD->quote($nomprog);
    $descprog_seq = $BDD->quote($descprog);
    $dateprog_seq = $BDD->quote(date('Y-m-d h:i:s'));

    $sql = "INSERT INTO PROGRAMME (dateprog, nomprog, descprog) VALUES ($dateprog_seq, $nomprog_seq, $descprog_seq)";
    $request = $BDD->prepare($sql);
    $request->execute();

    $sql = "SELECT idprog FROM PROGRAMME WHERE nomprog = $nomprog_seq";
    $request = $BDD->prepare($sql);
    $request->execute();
    $idprog = $request->fetchColumn();

    //Log de création de programme//
    $typelog = "Réussite";
    $desclog = 'Création du programme "'.$nomprog.'" id='.$idprog;
    $loginlog = $_SESSION['name'];
    include '/var/www/r2c.uca-project.com/Forms/addLogs.php';

    $message = "cp";
    urlencode($message);
    header('Location: ../Validation/validation.php?message='.$message);
  } else {
    //Log d'erreur de création de programme//
    $typelog = "Erreur";
    $desclog = 'Erreur lors de la création du programme certains parametres sont manquants';
    $loginlog = $_SESSION['name'];
    include '/var/www/r2c.uca-project.com/Forms/addLogs.php';
    header('Location: ../Validation/validation.php?message=ep');
  }
?>