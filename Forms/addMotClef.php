<?php
  session_start();
  include '/home/r2c/R2C/Forms/checkSession.php';

  if(isset($_POST['newMotClef'])) {
    $newMotClef = htmlspecialchars($_POST['newMotClef']);
    $newMotClef_seq = $BDD->quote($newMotClef);

    $sql = "SELECT * FROM MOTSCLEF WHERE motclef LIKE $newMotClef_seq";
    $request = $BDD->prepare($sql);
    $request->execute();
    $result = $request->fetchAll();

    foreach($result as $row) {
      if($row['motclef'] == $newMotClef) {
        $iddiv = 'MC' . $row['idmotclef'];

        // Décoder le tableau JSON
        $selection = json_decode($_COOKIE['selection'], true);
        // Ajouter la nouvelle valeur
        $selection[] = $iddiv;
        // Re-encoder le tableau en JSON
        $updatedCookieValue = json_encode($selection);
        
        setcookie('selection', $updatedCookieValue, 0, "/");
        header('Location: ../NewBP/newBP.php'); 
        exit();
      }
    }

    $sql = "INSERT INTO MOTSCLEF (motclef) VALUES ($newMotClef_seq)";
    $request = $BDD->prepare($sql);
    $request->execute();

    $sql = "SELECT idmotclef FROM MOTSCLEF WHERE motclef LIKE $newMotClef_seq";
    $request = $BDD->prepare($sql);
    $request->execute();
    $idmotclef = $request->fetchColumn();
    
    $iddiv = 'MC' . $idmotclef;

    // Décoder le tableau JSON
    $selection = json_decode($_COOKIE['selection'], true);
    // Ajouter la nouvelle valeur
    $selection[] = $iddiv;
    // Re-encoder le tableau en JSON
    $updatedCookieValue = json_encode($selection);

    setcookie('selection', $updatedCookieValue, 0, "/"); 

    header('Location: ../NewBP/newBP.php');   
  } else {
    header('Location: ../Validation/validation.php?message=ecmc');
  }
?>