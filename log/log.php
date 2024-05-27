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
      <form action="" method="GET" >
        <input type="date" name="date" id="filtreInput" value="<?= isset($_GET['date'])==true ? $_GET['date']:'' ?>" placeholder="Filtrer par utilisateur">
        <select name="user">
          <option value="">filter by user</option>
          <option value="admin" <?= isset($_GET['user'])==true ? ($_GET['user']=='admin' ? 'selected':'' ):'' ?> >admin</option>
          <option value="tom" <?= isset($_GET['user'])==true ? ($_GET['user']=='tom' ? 'selected':'' ):'' ?> >tom</option>
          <option value="hugo" <?= isset($_GET['user'])==true ? ($_GET['user']=='hugo' ? 'selected':'' ):'' ?> >hugo</option>
          <option value="elouan" <?= isset($_GET['user'])==true ? ($_GET['user']=='elouan' ? 'selected':'' ):'' ?> >elouan</option>
          <option value="tarik" <?= isset($_GET['user'])==true ? ($_GET['user']=='tarik' ? 'selected':'' ):'' ?> >tarik</option>
          <option value="test" <?= isset($_GET['user'])==true ? ($_GET['user']=='test' ? 'selected':'' ):'' ?> >test</option>
        </select>
        <select name="action">
          <option value="">filter by action</option>
          <option value="Connexion réussie"  <?= isset($_GET['action'])==true ? ($_GET['action']=='Connexion réussie' ? 'selected':'' ):'' ?> >Connexion réussie</option>
          <option value="Déconnexion automatique après 5 minutes d\'inactivité"  <?= isset($_GET['action'])==true ? ($_GET['action']=='Déconnexion automatique après 5 minutes d\'inactivité' ? 'selected':'' ):'' ?> >Déconnexion automatique après 5 minutes d'inactivité</option>
          <option value="Déconnexion manuelle réussie"  <?= isset($_GET['action'])==true ? ($_GET['action']=='Déconnexion manuelle réussie' ? 'selected':'' ):'' ?> >Déconnexion manuelle réussie</option>
          <option value="Connexion échouée l'utilisateur admin est déjà connecté"  <?= isset($_GET['action'])==true ? ($_GET['action']=='Connexion échouée l\'utilisateur admin est déjà connecté' ? 'selected':'' ):'' ?> >Connexion échouée l'utilisateur admin est déjà connecté</option>
          <option value="Connexion échouée mot de passe incorrect"  <?= isset($_GET['action'])==true ? ($_GET['action']=='Connexion échouée mot de passe incorrect' ? 'selected':'' ):'' ?> >Connexion échouée mot de passe incorrect</option>
        </select>
        <button type="submit" id="filtre"><img src="../Img/filter.png" alt="filtrer"><a href="../log/log.php"></a></button>
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
          if (isset($_GET['date']) && isset($_GET['user']) && isset($_GET['action'])) {
            $datea = '%' . $_GET['date'] . '%';
            $login = '%' . $_GET['user'] . '%';
            $type = '%' . $_GET['action'] . '%';
            $sql = "SELECT * FROM LOGS WHERE datea LIKE '$datea' AND login LIKE '$login' AND desca LIKE '$type' ORDER BY datea DESC LIMIT 10;";
            $request = $BDD->prepare($sql);
            $request->execute();
            $logs = $request->fetchAll();
          } else {
            $sql = "SELECT * FROM LOGS WHERE idlog > 0 ORDER BY datea DESC LIMIT 10;";
            $request = $BDD->prepare($sql);
            $request->execute();
            $logs = $request->fetchAll();
          }

          foreach ($logs as $log) {
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