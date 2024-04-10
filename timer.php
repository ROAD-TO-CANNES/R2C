<?php 
  session_start();

  $inactiviteMax = 300; // 5 minutes = 300 secondes

  if (isset($_SESSION['derniereActivite'])) {
    $inactivite = time() - $_SESSION['derniereActivite'];
    if ($inactivite > $inactiviteMax) {
      include '/home/r2c/R2C/Forms/logout.php';
      setcookie('m1', 1, 0, "/");
      exit();
    } else {
      $_SESSION['derniereActivite'] = time();
    }
  } else {
    $_SESSION['derniereActivite'] = time();
  }
?>
