<?php 
  session_start(); 
  include '/var/www/r2c.uca-project.com/Forms/checkSession.php';

  if (!$_SESSION['droits'] > 1) {
    header('Location: ../Accueil/accueil.php');
  }
?>

<!DOCTYPE html>
<html>
  <head>
    <title>R2C - logs</title>
    <link rel="stylesheet" type="text/css" href="./log.css">
    <link rel="icon" type="image/png" href="../Img/icon.png">
  </head>
  <header>
    <?php include '/var/www/r2c.uca-project.com/Header/header.php'; ?>
  </header>
  <body>
    <div class="top">
      <h1>Consulter les logs</h1>
    </div>
    <div class="content">
      <table>
        <tr>
          <th>Date</th>
          <th>Utilisateur</th>
          <th>Page</th>
          <th>Action</th>
        </tr>
        <?php
          $sql = "SELECT * FROM LOGS";
          $request = $BDD->prepare($sql);
          $request->execute();
          $logs = $request->fetchAll();

          foreach($logs as $log) {
            echo '<tr>';
            echo '<td>' . $log['datea'] . '</td>';
            echo '<td>' . $log['login'] . '</td>';
            echo '<td>' . $log['desca'] . '</td>';
            echo '</tr>';
          }
        ?>
      </table>
    </div>
  </body>
  <script src="../timer.js"></script>
</html>