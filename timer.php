<?php 
  session_start();

  $inactiviteMax = 300; // 5 minutes = 300 secondes

  if (isset($_SESSION['derniereActivite'])) {
    $inactivite = time() - $_SESSION['derniereActivite'];
    if ($inactivite > $inactiviteMax) {
      $desclog = "Déconnexion automatique après 5 minutes d'inactivité";
      include '/var/www/r2c.uca-project.com/Forms/logout.php';
      setcookie('m1', 1, 0, "/");
      exit();
    } else {
      $_SESSION['derniereActivite'] = time();
    }
  } else {
    $_SESSION['derniereActivite'] = time();
  }
?>
