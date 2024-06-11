<?php
  session_start();
  include '/var/www/r2c.uca-project.com/Forms/checkSession.php';

  if ($_SESSION['droits'] < 1) {
    header('Location: ../Accueil/accueil.php');
  }

  // Get the password requirements
  $sql = "SELECT * FROM SPECSPSW";
  $request = $BDD->prepare($sql);
  $request->execute();
  $specspsw = $request->fetch();

  // Check if the required parameters are present
  if (isset($_POST['username']) && isset($_POST['newuserpsw']) && isset($_POST['newuserpsw2']) && isset($_POST['role'])) {
    if (!preg_match('/^[a-zA-Z0-9]+$/', $_POST['username'])) {// Check if the username contains special characters or spaces
      // Log the error
      $typelog = "Warning";
      $desclog = "Erreur lors de la création d'un utilisateur le nom d'utilisateur contient des caractères spéciaux ou des espaces";
      $loginlog = $_SESSION['name'];
      include '/var/www/r2c.uca-project.com/Forms/addLogs.php';

      $response = 9; // The username contains special characters or spaces
      echo json_encode($response);
      exit();// Stop the script
    }
    // Sanitize the username
    $username = htmlspecialchars($_POST['username']);

    // Check if the username already exists in the database
    $sql = "SELECT login FROM USER WHERE login LIKE '".$username."'";
    $request = $BDD->prepare($sql);
    $request->execute();
    $result = $request->fetch();
    if ($result) {// If the username already exists
      // Log the error
      $typelog = "Warning";
      $desclog = "Erreur lors de la création d'un utilisateur, le login '".$username."' est déjà utilisé";
      $loginlog = $_SESSION['name'];
      include '/var/www/r2c.uca-project.com/Forms/addLogs.php';

      $response = 8;// The username already exists
      echo json_encode($response);
      exit();
    }

    $newuserpsw = $_POST['newuserpsw'];
    $newuserpsw2 = $_POST['newuserpsw2'];
    $role = $_POST['role'];
    $date = date('Y-m-d H:i:s');// Get the current date

    // Check if the passwords match
    if ($newuserpsw == $newuserpsw2) {
      $loginInPassword = strpos($newuserpsw, $username); // Check if the password contains the username
      if ($loginInPassword === false) {
        $size = strlen($newuserpsw);// Password size
        if ($size >= $specspsw['size']) { // Check if the password is long enough
          $nbnumber = preg_match_all("/[0-9]/", $newuserpsw);// Number of numbers
          if ($nbnumber >= $specspsw['number']) {// Check if the password contains enough numbers
            $nbspecial = preg_match_all("/[^a-zA-Z0-9]/", $newuserpsw);// Number of special characters
            if ($nbspecial >= $specspsw['specialchar']) {// Check if the password contains enough special characters
              $nbupper = preg_match_all("/[A-Z]/", $newuserpsw);// Number of uppercase letters
              if ($nbupper >= $specspsw['uppercase']) {// Check if the password contains enough uppercase letters
                $hasAccent = preg_match('/[àáâãäåçèéêëìíîïðòóôõöùúûüýÿ]/i', $newuserpsw); // Check if the password contains accents
                if ($hasAccent === 0) {
                  $newuserpsw_hash = password_hash($newuserpsw, PASSWORD_DEFAULT);// Hash the password

                  // Insert the new user into the database
                  $sql = "INSERT INTO USER (login, mdp, droits, dateus) VALUES ('$username', '$newuserpsw_hash', $role, '$date')";
                  $request = $BDD->prepare($sql);
                  $request->execute();

                  // Log the creation of the new user
                  $typelog = "Information";
                  $desclog = "Création du nouvel utilisateur '".$username."' réussie";
                  $loginlog = $_SESSION['name'];
                  include '/var/www/r2c.uca-project.com/Forms/addLogs.php';

                  $response = 7;// Success
                } else {
                  // Log the error
                  $typelog = "Warning";
                  $desclog = "Erreur lors de la création de l'utilisateur '".$username."' le mot de passe contient des accents";
                  $loginlog = $_SESSION['name'];
                  include '/var/www/r2c.uca-project.com/Forms/addLogs.php';

                  $response = 6;// The password contains accents
                }
              } else {
                // Log the error
                $typelog = "Warning";
                $desclog = "Erreur lors de la création de l'utilisateur '".$username."' le mot de passe ne contien pas suffisament de majuscules";
                $loginlog = $_SESSION['name'];
                include '/var/www/r2c.uca-project.com/Forms/addLogs.php';

                $response = 4;// Number of uppercase letters insufficient
              }
            } else {
              // Log the error
              $typelog = "Warning";
              $desclog = "Erreur lors de la création de l'utilisateur '".$username."' le mot de passe ne contien pas suffisament de caractères spéciaux";
              $loginlog = $_SESSION['name'];
              include '/var/www/r2c.uca-project.com/Forms/addLogs.php';
              
              $response = 3;// Number of special characters insufficient
            }
          } else {
            // Log the error
            $typelog = "Warning";
            $desclog = "Erreur lors de la création de l'utilisateur '".$username."' le mot de passe ne contien pas suffisament de chiffres";
            $loginlog = $_SESSION['name'];
            include '/var/www/r2c.uca-project.com/Forms/addLogs.php';
            
            $response = 2;// Number of numbers insufficient
          }
        } else {
          // Log the error
          $typelog = "Warning";
          $desclog = "Erreur lors de la création de l'utilisateur '".$username."' le mot de passe est trop court";
          $loginlog = $_SESSION['name'];
          include '/var/www/r2c.uca-project.com/Forms/addLogs.php';
          
          $response = 1;// Password too short
        }
      } else {
        //  Log the error
        $typelog = "Warning";
        $desclog = "Erreur lors de la création de l'utilisateur '".$username."' le mot de passe contient le login";
        $loginlog = $_SESSION['name'];
        include '/var/www/r2c.uca-project.com/Forms/addLogs.php';
        
        $response = 5;// The password contains the username
      }
    } else {
      // Log the error
      $typelog = "Warning";
      $desclog = "Erreur lors de la création de l'utilisateur '".$username."' les mots de passe ne correspondent pas";
      $loginlog = $_SESSION['name'];
      include '/var/www/r2c.uca-project.com/Forms/addLogs.php';
      
      $response = 0;// The passwords do not match
    }
  } else {
    // Log the error
    $typelog = "Warning";
    $desclog = "Erreur lors de la création d'un utilisateur certain paramètres sont manquant";
    $loginlog = $_SESSION['name'];
    include '/var/www/r2c.uca-project.com/Forms/addLogs.php';
  }
  echo json_encode($response);
  exit();
?>