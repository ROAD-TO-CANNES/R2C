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
      <!-- <button id="filtre"><img src="../Img/filter.png" alt="filtrer"></button> -->
      <form action="NULL" method="GET" >
        <input type="date" name="filtre" id="filtreInput" placeholder="Filtrer par utilisateur">
        <select>
          <option value="NULL">filter by user</option>
          <option value="1">admin</option>
          <option value="2">tom</option>
          <option value="3">hugo</option>
          <option value="4">test</option>
        </select>
        <select>
          <option value="NULL">filter by action</option>
          <option value="1">Connexion réussie</option>
          <option value="2">Déconnexion automatique après 5 minutes d'inactivité</option>
          <option value="3">Déconnexion manuelle réussie</option>
          <option value="4">Connexion échouée l'utilisateur "admin" est déjà connecté</option>
          <option value="5">Connexion échouée mot de passe incorrect</option>
        </select>
      </form>
    </div>
    <div class="content">
      <table class="content-table">
        <thead>
          <tr>
            <th>Date</th>
            <th>Utilisateur</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php
            $sql = "SELECT * FROM LOGS WHERE idlog > 0 ORDER BY datea DESC LIMIT 10;";
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
        </tbody>
      </table>
    </div>
  </body>
  <script src="../timer.js"></script>
</html>