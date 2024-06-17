<?php
session_start();
include '/var/www/r2c.uca-project.com/Forms/checkSession.php';

// Retrieving the password specifications
$sql = "SELECT * FROM SPECSPSW";
$request = $BDD->prepare($sql);
$request->execute();
$specspsw = $request->fetch();

if (isset($_POST['new_psw']) && isset($_POST['new_psw2'])) {
  // Verify if the user is changing his own password
  if (isset($_POST['old_psw'])) {
    $old_psw = $_POST['old_psw'];
    $username = $_SESSION['name'];

    // Retrieve the rights of the user
    $sql = "SELECT droits FROM USER WHERE login LIKE '" . $_SESSION['name'] . "'";
    $request = $BDD->prepare($sql);
    $request->execute();
    $droits = $request->fetchColumn();
    // Verify if the user is an admin changing another user's password
  } elseif (isset($_POST['login'])) {
    $login = htmlspecialchars($_POST['login']);
    $username = $login;

    // Verify if the user exists
    $sql = "SELECT login FROM USER WHERE login LIKE '" . $login . "'";
    $request = $BDD->prepare($sql);
    $request->execute();
    $result = $request->fetch();
    if (!$result) { // If the user does not exist
      $typelog = "Alert";
      $desclog = "Tentative de changement de mot de passe fauduleuse détectée l'utilisateur concerné n'existe pas";
      $loginlog = $_SESSION['name'];
      include '/var/www/r2c.uca-project.com/Forms/addLogs.php';

      $response = 11; //The user does not exist
      echo json_encode($response);
      exit(); //Exit the script
    }

    // Retrieve the rights of the user
    $sql = "SELECT droits FROM USER WHERE login LIKE '" . $login . "'";
    $request = $BDD->prepare($sql);
    $request->execute();
    $droits = $request->fetchColumn();
  }
  // If the user is trying to change the password of the SuperAdmin
  if ($droits == 2) {
    $typelog = "Alert";
    $desclog = "Tentative de changement de mot de passe fauduleuse détectée le mot de passe du SuperAdmin ne peut pas être changé";
    $loginlog = $_SESSION['name'];
    include '/var/www/r2c.uca-project.com/Forms/addLogs.php';

    $response = 10; //The password of the SuperAdmin cannot be changed
    echo json_encode($response);
    exit(); //Exit the script
  }
  $new_psw = $_POST['new_psw'];
  $new_psw_hash = password_hash($new_psw, PASSWORD_DEFAULT); //Hash the new password with password_hash
  $new_psw2 = $_POST['new_psw2'];

  if ($new_psw == $new_psw2) { // Check if the two passwords match
    $upperUsername = strtoupper($username); // Uppercase the username
    $upperLoginInPassword = strpos($new_psw, $upperUsername);
    $firstUpperUsername = ucfirst(strtolower($username)); // First letter uppercase of the username
    $firstUpperLoginInPassword = strpos($new_psw, $firstUpperUsername);
    $loginInPassword = strpos($new_psw, $username);
    // Check if the password contains the login in lowercase or uppercase or the first letter in uppercase
    if ($loginInPassword === false && $upperLoginInPassword === false && $firstUpperLoginInPassword === false) {
      $size = strlen($new_psw); // Password size
      if ($size >= $specspsw['size']) { // Check the size of the password
        $nbnumber = preg_match_all("/[0-9]/", $new_psw); //Number of numbers
        if ($nbnumber >= $specspsw['number']) { // Check the number of numbers
          $nbspecial = preg_match_all("/[^a-zA-Z0-9]/", $new_psw); //Number of special characters
          if ($nbspecial >= $specspsw['specialchar']) { // Check the number of special characters
            $nbupper = preg_match_all("/[A-Z]/", $new_psw); //Number of uppercase letters
            if ($nbupper >= $specspsw['uppercase']) { // Check the number of uppercase letters
              $hasAccent = preg_match('/[àáâãäåçèéêëìíîïðòóôõöùúûüýÿ]/i', $new_psw); //Check for accents
              if ($hasAccent === 0) {
                // encode the new password             
                $new_psw_seq = $BDD->quote($new_psw_hash);
                if (isset($login)) { // If the user is an admin changing another user's password
                  // Retrieve the old password
                  $sql = "SELECT mdp FROM USER WHERE login LIKE '" . $login . "'";
                  $request = $BDD->prepare($sql);
                  $request->execute();
                  $result = $request->fetch();

                  // Check if the new password is different from the old one
                  if (password_verify($new_psw, $result['mdp'])) {
                    // Log the failed password change attempt
                    $typelog = "Warning";
                    $desclog = "Tentative de changement de mot de passe échouée pour le compte " . $login . " le nouveau mot de passe doit être différent de l'ancien";
                    $loginlog = $_SESSION['name'];
                    include '/var/www/r2c.uca-project.com/Forms/addLogs.php';

                    $response = 3; //The new password must be different from the old one
                    echo json_encode($response);
                    exit(); //Exit the script
                  } else { // If the new password is different from the old one
                    // Update the password
                    $sql = "UPDATE USER SET mdp=$new_psw_seq WHERE login LIKE '" . $login . "'";
                    $request = $BDD->prepare($sql);
                    $request->execute();
                    // Log the password change
                    $typelog = "Information";
                    $desclog = "Changement de mot de passe du compte " . $login . " réussi";
                    $loginlog = $_SESSION['name'];
                    include '/var/www/r2c.uca-project.com/Forms/addLogs.php';

                    $response = 0; // Success
                    echo json_encode($response);
                    exit(); // Exit the script
                  }
                } elseif ($new_psw != $old_psw) { // If the user is changing his own password
                  // Retrieve the old password
                  $sql = "SELECT mdp FROM USER WHERE login LIKE '" . $_SESSION['name'] . "'";
                  $request = $BDD->prepare($sql);
                  $request->execute();
                  $result = $request->fetch();

                  // Check if the old password is correct
                  if (password_verify($old_psw, $result['mdp'])) {
                    // Update the password
                    $sql = "UPDATE USER SET mdp=$new_psw_seq WHERE login LIKE '" . $_SESSION['name'] . "'";
                    $request = $BDD->prepare($sql);
                    $request->execute();
                    // Log the password change
                    $typelog = "Information";
                    $desclog = "Changement de mot de passe réussi";
                    $loginlog = $_SESSION['name'];
                    include '/var/www/r2c.uca-project.com/Forms/addLogs.php';

                    $response = 0; // Success
                  } else {
                    // Log the failed password change attempt
                    $typelog = "Warning";
                    $desclog = "Tentative de changement de mot de passe échouée mot de passe incorrect";
                    $loginlog = $_SESSION['name'];
                    include '/var/www/r2c.uca-project.com/Forms/addLogs.php';

                    $response = 1; // Incorrect password
                  };
                } else {
                  // Log the failed password change attempt
                  $typelog = "Warning";
                  $desclog = "Tentative de changement de mot de passe échouée le nouveau mot de passe doit être différent de l'ancien";
                  $loginlog = $_SESSION['name'];
                  include '/var/www/r2c.uca-project.com/Forms/addLogs.php';

                  $response = 3; // The new password must be different from the old one
                }
              } else {
                // Log the failed password change attempt
                $typelog = "Warning";
                $desclog = "Tentative de changement de mot de passe échouée le mot de passe contient des caractères accentués";
                $loginlog = $_SESSION['name'];
                include '/var/www/r2c.uca-project.com/Forms/addLogs.php';

                $response = 9; // The password contains accented characters
              }
            } else {
              // Log the failed password change attempt
              $typelog = "Warning";
              $desclog = "Tentative de changement de mot de passe échouée le mot de passe ne contient pas assez de majuscules";
              $loginlog = $_SESSION['name'];
              include '/var/www/r2c.uca-project.com/Forms/addLogs.php';

              $response = 7; // The password must contain at least $specspsw['uppercase'] uppercase letters
            }
          } else {
            // Log the failed password change attempt
            $typelog = "Warning";
            $desclog = "Tentative de changement de mot de passe échouée le mot de passe ne contient pas assez de caractères spéciaux";
            $loginlog = $_SESSION['name'];
            include '/var/www/r2c.uca-project.com/Forms/addLogs.php';

            $response = 6; // The password must contain at least $specspsw['specialchar'] special characters
          }
        } else {
          // Log the failed password change attempt
          $typelog = "Warning";
          $desclog = "Tentative de changement de mot de passe échouée le mot de passe ne contient pas assez de chiffres";
          $loginlog = $_SESSION['name'];
          include '/var/www/r2c.uca-project.com/Forms/addLogs.php';

          $response = 5; // The password must contain at least $specspsw['number'] numbers
        }
      } else {
        // Log the failed password change attempt
        $typelog = "Warning";
        $desclog = "Tentative de changement de mot de passe échouée le mot de passe est trop court";
        $loginlog = $_SESSION['name'];
        include '/var/www/r2c.uca-project.com/Forms/addLogs.php';

        $response = 4; // The password is too short
      }
    } else {
      // Log the failed password change attempt
      $typelog = "Warning";
      $desclog = "Tentative de changement de mot de passe échouée le mot de passe contient le login";
      $loginlog = $_SESSION['name'];
      include '/var/www/r2c.uca-project.com/Forms/addLogs.php';

      $response = 8; //The password contains the login
    }
  } else {
    // Log the failed password change attempt
    $typelog = "Warning";
    $desclog = "Tentative de changement de mot de passe échouée les mots de passe ne correspondent pas";
    $loginlog = $_SESSION['name'];
    include '/var/www/r2c.uca-project.com/Forms/addLogs.php';

    $response = 2; // The passwords do not match
  };
  echo json_encode($response); // Send the response to the client
};
