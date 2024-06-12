<?php
  session_start();
  include '/var/www/r2c.uca-project.com/Forms/checkSession.php';

  // Verify if nombp, descbp and phase are set in POST array
  if (isset($_POST['nombp']) && isset($_POST['descbp']) && isset($_POST['phase'])) {
    // sanitize nombp
    $nombp = htmlspecialchars($_POST['nombp']);
    // Verify if the good practice already exists
    $sql = "SELECT nombp FROM BONNESPRATIQUES WHERE nombp = ?";
    $request = $BDD->prepare($sql);
    $request->execute([$nombp]);
    $result = $request->fetch();

    if ($result) {// if the good practice already exists
      // Log the error of creating a good practice  
      $typelog = "Warning";
      $desclog = 'Erreur lors de la création d\'une bonne pratique "'.$nombp.'" existe déjà';
      $loginlog = $_SESSION['name'];
      include '/var/www/r2c.uca-project.com/Forms/addLogs.php';

      // Redirect to the validation page with the good message
      header('Location: ../Validation/validation.php?message=ebpexist');
      exit();// Stop the script
    }

    // sanitize descbp and phase
    $descbp = htmlspecialchars($_POST['descbp']);
    $phase = htmlspecialchars($_POST['phase']);

    // set switch to 0 or 1
    if(isset($_POST['switch']) && $_POST['switch'] == "on") {
      $switch = 1;
    } else {
      $switch = 0;
    }

    // Verify if divIds is set in POST array
    if(isset($_POST['divIds'])) {
      $divIds = $_POST['divIds'];
      $divIds = json_decode($divIds);
      $prog = [];
      $motclef = [];

      // Split divIds into prog and motclef ids
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
    // encode nombp and descbp
    $nombp_seq = $BDD->quote($nombp);
    $descbp_seq = $BDD->quote($descbp);

    // Insert the good practice into BONNESPRATIQUES
    $sql = "INSERT INTO BONNESPRATIQUES (nombp, descbp, phase, statut) VALUES ($nombp_seq, $descbp_seq, $phase, $switch)";
    $request = $BDD->prepare($sql);
    $request->execute();

    // Retrieve the id of the good practice created
    $sql = "SELECT idbp FROM BONNESPRATIQUES WHERE nombp = $nombp_seq";
    $request = $BDD->prepare($sql);
    $request->execute();
    $idbp = $request->fetchColumn();

    // Log the creation of the good practice
    $typelog = "Information";
    $desclog = 'Création de la bonne pratique "'.$nombp.'" id='.$idbp;
    $loginlog = $_SESSION['name'];
    include '/var/www/r2c.uca-project.com/Forms/addLogs.php';

    if(isset($prog)) {// if prog is set
      // Insert the relations between the good practice and the programs
      foreach ($prog as $idprog) {
        $sql = "INSERT INTO BONNESPRATIQUES_PROGRAMME (idbp, idprog) VALUES ($idbp, $idprog)";
        $request = $BDD->prepare($sql);
        $request->execute();
      }
      // Log the creation of the relations between the good practice and the programs
      $typelog = "Information";
      $desclog = 'Création des relations programme-bonne pratique pour "'.$nombp.'" id='.$idbp;
      $loginlog = $_SESSION['name'];
      include '/var/www/r2c.uca-project.com/Forms/addLogs.php';
    }
    
    if(isset($motclef)){// if motclef is set
      // Insert the relations between the good practice and the keywords
      foreach ($motclef as $idmotclef) {
        $sql = "INSERT INTO BONNESPRATIQUES_MOTSCLEF (idbp, idmotclef) VALUES ($idbp, $idmotclef)";
        $request = $BDD->prepare($sql);
        $request->execute();
      }
      // Log the creation of the relations between the good practice and the keywords
      $typelog = "Information";
      $desclog = 'Création des relations mot clef-bonne pratique pour "'.$nombp.'" id='.$idbp;
      $loginlog = $_SESSION['name'];
      include '/var/www/r2c.uca-project.com/Forms/addLogs.php';
    }

    $message = "cbp";// Success message
  } else { // if nombp, descbp and phase are not set in POST array
    // Log the error of creating a good practice
    $typelog = "Warning";
    $desclog = 'Erreur lors de la création de la bonne pratique certains parametres sont manquants';
    $loginlog = $_SESSION['name'];
    include '/var/www/r2c.uca-project.com/Forms/addLogs.php';

    $message = "ebp"; // Error message
  }
  urlencode($message);
  header('Location: ../Validation/validation.php?message='.$message);// Redirect to the validation page with the good message
?> 