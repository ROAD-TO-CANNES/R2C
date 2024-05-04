<?php
  session_start();
  include '/home/r2c/R2C/Forms/checkSession.php';

  if (isset($_POST['nombp']) && isset($_POST['descbp']) && isset($_POST['phase'])) {
    $nombp = htmlspecialchars($_POST['nombp']);
    $descbp = htmlspecialchars($_POST['descbp']);
    $phase = htmlspecialchars($_POST['phase']);
    if(isset($_POST['switch']) && $_POST['switch'] == "on") {
      $switch = 1;
    } else {
      $switch = 0;
    }

    if(isset($_POST['divIds'])) {
      $divIds = $_POST['divIds'];
      $divIds = json_decode($divIds);
      $prog = [];
      $motclef = [];

      foreach ($divIds as $id) {
        $type = substr($id, 0, 2);
        $number = substr($id, 2); 

        switch($type) {
          case 'PR':
            $prog[] = $number;
            break;
          case 'MC':
            $motclef[] = $number;
            break;
          default:
            echo 'Erreur';
            break;
        }
      }
    }
    
    include '/home/r2c/R2C/bdd.php';

    $nombp_seq = $BDD->quote($nombp);
    $descbp_seq = $BDD->quote($descbp);

    $sql = "INSERT INTO BONNESPRATIQUES (nombp, descbp, phase, statut) VALUES ($nombp_seq, $descbp_seq, $phase, $switch)";
    $request = $BDD->prepare($sql);
    $request->execute();

    if(isset($prog)) {
      $sql = "SELECT idbp FROM BONNESPRATIQUES WHERE nombp = $nombp_seq";
      $request = $BDD->prepare($sql);
      $request->execute();
      $idbp = $request->fetchColumn();

      foreach ($prog as $idprog) {
        $sql = "INSERT INTO BONNESPRATIQUES_PROGRAMME (idbp, idprog) VALUES ($idbp, $idprog)";
        $request = $BDD->prepare($sql);
        $request->execute();
      }
    }
    
    if(isset($motclef)){
      $sql = "SELECT idbp FROM BONNESPRATIQUES WHERE nombp = $nombp_seq";
      $request = $BDD->prepare($sql);
      $request->execute();
      $idbp = $request->fetchColumn();

      foreach ($motclef as $idmotclef) {
        $sql = "INSERT INTO BONNESPRATIQUES_MOTSCLEF (idbp, idmotclef) VALUES ($idbp, $idmotclef)";
        $request = $BDD->prepare($sql);
        $request->execute();
      }
    }

    $message = "cbp";
    urlencode($message);
    header('Location: ../Validation/validation.php?message='.$message);
  } else {
    $message = "ebp";
    urlencode($message);
    header('Location: ../Validation/validation.php?message='.$message);
  }
?> 