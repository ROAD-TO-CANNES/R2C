<?php
  session_start();
  include '/var/www/r2c.uca-project.com/Forms/checkSession.php';

  // Verify if the user is the SuperAdmin
  if ($_SESSION['droits'] != 2) {
    // Logs of the error of modifying the password policy
    $typelog = "Warning";
    $desclog = "Erreur lors de la modification de la politique des mots de passe, l'utilisateur n'a pas les droits nécessaires";
    $loginlog = $_SESSION['name'];
    include '/var/www/r2c.uca-project.com/Forms/addLogs.php';
    header('Location: ../Validation/validation.php?message=epolicyright');// Redirect to the validation page with the good message
    exit();// Stop the script
  } 

  // Verify if specialchar is set in the POST array
  if (isset($_POST['specialchar'])) {
    // Sanitize the specialchar variable
    $specialchar = htmlspecialchars($_POST['specialchar']);

    // Verify if the specialchar variable is a number
    if (!is_numeric($_POST['specialchar'])) {
      // Logs of the error of modifying the password policy
      $typelog = "Warning";
      $desclog = "Erreur lors de la modification de la politique des mots de passe, le nombre de caractères spéciaux n'est pas un nombre";
      $loginlog = $_SESSION['name'];
      include '/var/www/r2c.uca-project.com/Forms/addLogs.php';
      header('Location: ../Validation/validation.php?message=epolicynumber');// Redirect to the validation page with the good message
      exit();// Stop the script
    }

    // Update the specialchar policy
    $sql = "UPDATE SPECSPSW SET specialchar = $specialchar";
    $request = $BDD->prepare($sql);
    $request->execute();

    // Log the modification of the password policy
    $typelog = "Information";
    $desclog = "Modification de la politique des mots de passe, nombre de caractères spéciaux : ".$specialchar;
    $loginlog = $_SESSION['name'];
    include '/var/www/r2c.uca-project.com/Forms/addLogs.php';
    header('Location: ../Forms/policyPassword.php');// Redirect to the password policy page
  } else if (isset($_POST['uppercase'])) {
    // Sanitize the uppercase variable
    $uppercase = htmlspecialchars($_POST['uppercase']);

    // Verify if the uppercase variable is a number
    if (!is_numeric($_POST['uppercase'])) {
      // Logs of the error of modifying the password policy
      $typelog = "Warning";
      $desclog = "Erreur lors de la modification de la politique des mots de passe, le nombre de majuscules n'est pas un nombre";
      $loginlog = $_SESSION['name'];
      include '/var/www/r2c.uca-project.com/Forms/addLogs.php';
      header('Location: ../Validation/validation.php?message=epolicynumber');// Redirect to the validation page with the good message
      exit();// Stop the script
    }

    // Update the uppercase policy
    $sql = "UPDATE SPECSPSW SET uppercase = $uppercase";
    $request = $BDD->prepare($sql);
    $request->execute();

    // Log the modification of the password policy
    $typelog = "Information";
    $desclog = "Modification de la politique des mots de passe, nombre de majuscules : ".$uppercase;
    $loginlog = $_SESSION['name'];
    include '/var/www/r2c.uca-project.com/Forms/addLogs.php';
    header('Location: ../Forms/policyPassword.php');// Redirect to the password policy page
  } elseif (isset($_POST['number'])) {
    // Sanitize the number variable
    $number = htmlspecialchars($_POST['number']);

    // Verify if the number variable is a number
    if (!is_numeric($_POST['number'])) {
      // Logs of the error of modifying the password policy
      $typelog = "Warning";
      $desclog = "Erreur lors de la modification de la politique des mots de passe, le nombre de caractères numériques n'est pas un nombre";
      $loginlog = $_SESSION['name'];
      include '/var/www/r2c.uca-project.com/Forms/addLogs.php';
      header('Location: ../Validation/validation.php?message=epolicynumber');// Redirect to the validation page with the good message
      exit();// Stop the script
    }

    // Update the number policy
    $sql = "UPDATE SPECSPSW SET number = $number";
    $request = $BDD->prepare($sql);
    $request->execute();

    // Log the modification of the password policy
    $typelog = "Information";
    $desclog = "Modification de la politique des mots de passe, nombre de caractères numériques : ".$number;
    $loginlog = $_SESSION['name'];
    include '/var/www/r2c.uca-project.com/Forms/addLogs.php';
    header('Location: ../Forms/policyPassword.php');// Redirect to the password policy page
  } elseif (isset($_POST['size'])) {
    // Sanitize the size variable
    $size = htmlspecialchars($_POST['size']);

    // Verify if the size variable is a number
    if (!is_numeric($_POST['size'])) {
      // Logs of the error of modifying the password policy
      $typelog = "Warning";
      $desclog = "Erreur lors de la modification de la politique des mots de passe, la taille du mot de passe n'est pas un nombre";
      $loginlog = $_SESSION['name'];
      include '/var/www/r2c.uca-project.com/Forms/addLogs.php';
      header('Location: ../Validation/validation.php?message=epolicynumber');// Redirect to the validation page with the good message
      exit();// Stop the script
    }

    // Update the size policy
    $sql = "UPDATE SPECSPSW SET size = $size";
    $request = $BDD->prepare($sql);
    $request->execute();

    // Log the modification of the password policy
    $typelog = "Information";
    $desclog = "Modification de la politique des mots de passe, la taille minimum du mot de passe est de  : ".$size;
    $loginlog = $_SESSION['name'];
    include '/var/www/r2c.uca-project.com/Forms/addLogs.php';
    header('Location: ../Forms/policyPassword.php');// Redirect to the password policy page
  } else { 
    // Logs of the error of modifying the password policy
    $typelog = "Warning";
    $desclog = "Erreur lors de la modification de la politique des mots de passe, certains paramètres sont manquants";
    $loginlog = $_SESSION['name'];
    include '/var/www/r2c.uca-project.com/Forms/addLogs.php';
    header('Location: ../Validation/validation.php?message=epolicy');// Redirect to the validation page with the good message
  }
?>