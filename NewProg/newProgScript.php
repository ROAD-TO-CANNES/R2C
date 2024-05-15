<?php
  session_start();
  include '/home/r2c/R2C/Forms/checkSession.php';

  if (isset($_POST['nomprog']) && isset($_POST['descprog'])) {
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
    include '/home/r2c/R2C/Forms/addLogs.php';

    $message = "cp";
    urlencode($message);
    header('Location: ../Validation/validation.php?message='.$message);
  } else {
    //Log d'erreur de création de programme//
    $typelog = "Erreur";
    $desclog = 'Erreur lors de la création du programme certains parametres sont manquants';
    $loginlog = $_SESSION['name'];
    include '/home/r2c/R2C/Forms/addLogs.php';
    header('Location: ../Validation/validation.php?message=ep');
  }
?>