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

  if(isset($_POST['specialchar'])) {// Verify if specialchar is set in the POST array
    // Retrieve the specialchar policy
    $sql = "SELECT specialchar FROM SPECSPSW";
    $request = $BDD->prepare($sql);
    $request->execute();
    $result = $request->fetchColumn();

    //sanitize the specialchar variable
    $specialchar = htmlspecialchars($_POST['specialchar']);
    // Verify if the specialchar policy has changed
    if ($result != $specialchar) {
      $specialchar = $specialchar;
    } else { // If the specialchar policy has not changed
      $specialchar = null;
    }
  }

  if(isset($_POST['uppercase'])) {// Verify if uppercase is set in the POST array
    // Retrieve the uppercase policy
    $sql = "SELECT uppercase FROM SPECSPSW";
    $request = $BDD->prepare($sql);
    $request->execute();
    $result = $request->fetchColumn();

    //sanitize the uppercase variable
    $uppercase = htmlspecialchars($_POST['uppercase']);
    // Verify if the uppercase policy has changed
    if ($result != $uppercase) {
      $uppercase = $uppercase;
    } else { // If the uppercase policy has not changed
      $uppercase = null;
    }
  }

  if(isset($_POST['number'])) {// Verify if number is set in the POST array
    // Retrieve the number policy
    $sql = "SELECT number FROM SPECSPSW";
    $request = $BDD->prepare($sql);
    $request->execute();
    $result = $request->fetchColumn();

    //sanitize the number variable
    $number = htmlspecialchars($_POST['number']);
    // Verify if the number policy has changed
    if ($result != $number) {
      $number = $number;
    } else { // If the number policy has not changed
      $number = null;
    }
  }

  if(isset($_POST['size'])) {// Verify if size is set in the POST array
    // Retrieve the size policy
    $sql = "SELECT size FROM SPECSPSW";
    $request = $BDD->prepare($sql);
    $request->execute();
    $result = $request->fetchColumn();

    //sanitize the size variable
    $size = htmlspecialchars($_POST['size']);
    // Verify if the size policy has changed
    if ($result != $size) {
      $size = $size;
    } else { // If the size policy has not changed
      $size = null;
    }
  }

  if ($specialchar != null) {// If the specialchar policy has changed
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
    // Set the message to send to the password policy page
    if (empty($message)) {
      $message = "specialchar=ok";
    } else {
      $message = $message."&specialchar=ok";
    }
  } 
  
  if ($uppercase != null) {// If the uppercase policy has changed
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
    // Set the message to send to the password policy page
    if (empty($message)) {
      $message = "uppercase=ok";
    } else {
      $message = $message."&uppercase=ok";
    }
  } 
  
  if ($number != null) {// If the number policy has changed
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
    // Set the message to send to the password policy page
    if (empty($message)) {
      $message = "number=ok";
    } else {
      $message = $message."&number=ok";
    }
  } 
  
  if ($size != null) {// If the size policy has changed
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
    // Set the message to send to the password policy page
    if (empty($message)) {
      $message = "size=ok";
    } else {
      $message = $message."&size=ok";
    }
  }

  // If the specialchar, uppercase, number and size are not set in the POST array 
  if(!isset($_POST['specialchar']) && !isset($_POST['uppercase']) && !isset($_POST['number']) && !isset($_POST['size'])) {
    // Logs of the error of modifying the password policy
    $typelog = "Warning";
    $desclog = "Erreur lors de la modification de la politique des mots de passe, certains paramètres sont manquants";
    $loginlog = $_SESSION['name'];
    include '/var/www/r2c.uca-project.com/Forms/addLogs.php';
    header('Location: ../Validation/validation.php?message=epolicy');// Redirect to the validation page with the good message
    exit();// Stop the script
  }

  header('Location: ../Forms/policyPassword.php?'.$message);// Redirect to the password policy page with the good message
?>