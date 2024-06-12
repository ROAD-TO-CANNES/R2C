<?php 
  session_start();

  $inactiviteMax = 300; // 5 minutes = 300 secondes

  // If derniereActivite is set in the session, set inactivite to the difference between the current time and the last activity
  if (isset($_SESSION['derniereActivite'])) {
    $inactivite = time() - $_SESSION['derniereActivite'];
    if ($inactivite > $inactiviteMax) {// If the inactivity is greater than the maximum inactivity
      // Disconnect the user
      $desclog = "Déconnexion automatique après 5 minutes d'inactivité";
      include '/var/www/r2c.uca-project.com/Forms/logout.php';
      // Set the cookie of inactivity to 1
      setcookie('m1', 1, 0, "/");
      exit();// Stop the script
    } else {// If the inactivity is less than the maximum inactivity
      $_SESSION['derniereActivite'] = time(); // Set the last activity to the current time
    }
  } else {// If the last activity is not set
    $_SESSION['derniereActivite'] = time();// Set the last activity to the current time
  }
?>
