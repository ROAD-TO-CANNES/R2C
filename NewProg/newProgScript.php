<?php
  if (isset($_POST['nomprog']) && isset($_POST['descprog'])) {
    $nomprog = htmlspecialchars($_POST['nomprog']);
    $descprog = htmlspecialchars($_POST['descprog']);

    include '/home/r2c/R2C/bdd.php';

    $nomprog_seq = $BDD->quote($nomprog);
    $descprog_seq = $BDD->quote($descprog);
    $dateprog_seq = $BDD->quote(date('Y-m-d h:i:s'));

    $sql = "INSERT INTO PROGRAMME (dateprog, nomprog, descprog) VALUES ($dateprog_seq, $nomprog_seq, $descprog_seq)";
    $request = $BDD->prepare($sql);
    $request->execute();

    $message = "cp";
    urlencode($message);
    header('Location: ../Validation/validation.php?message='.$message);
  }
?>