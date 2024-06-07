<?php 
  session_start(); 
  include '/var/www/r2c.uca-project.com/Forms/checkSession.php';

  if ($_SESSION['droits'] < 1) {
    header('Location: ../Accueil/accueil.php');
  }

  //recuperation des utilisateurs
  $sql = "SELECT login FROM USER";
  $request = $BDD->prepare($sql);
  $request->execute();
  $users = $request->fetchAll();
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
          <?php
            foreach ($users as $user) {
              echo '<option value="'.$user['login'].'" '.(isset($_GET['user'])==true ? ($_GET['user']==$user['login'] ? 'selected':'' ):'' ).' >'.$user['login'].'</option>';
            }
          ?> 
        </select>
        <select name="action">
          <option value="">filter by type</option>
          <option value="Information"  <?= isset($_GET['action'])==true ? ($_GET['action']=='Information' ? 'selected':'' ):'' ?> >Information</option>
          <option value="Warning"  <?= isset($_GET['action'])==true ? ($_GET['action']=='Warning' ? 'selected':'' ):'' ?> >Warning</option>
          <option value="Alert"  <?= isset($_GET['action'])==true ? ($_GET['action']=='Alert' ? 'selected':'' ):'' ?> >Alert</option>
        </select>
        <button type="submit" id="filtre"><img src="../Img/filter.png" alt="filtrer"><a href="../log/log.php"></a></button>
      </form>
    </div>
    <div class="content">
      <table class="content-table">
        <thead>
          <tr>
          <th>Date</th>
          <th>Type</th>
          <th>Description</th>
          <th>Utilisateur</th>
          </tr>
        </thead>
        <tbody>
          <?php
            $conditions = [];
            $params = [];

            if (isset($_GET['date']) && !empty($_GET['date'])) {
              $conditions[] = "datea LIKE ?";
              $params[] = '%' . $_GET['date'] . '%';
            }

            if (isset($_GET['user']) && !empty($_GET['user'])) {
              $conditions[] = "login LIKE ?";
              $params[] = '%' . $_GET['user'] . '%';
            }

            if (isset($_GET['action']) && !empty($_GET['action'])) {
              $conditions[] = "type LIKE ?";
              $params[] = '%' . $_GET['action'] . '%';
            }

            $sql = "SELECT * FROM LOGS";

            if (!empty($conditions)) {
              $sql .= " WHERE " . implode(" AND ", $conditions);
            }

            $sql .= " ORDER BY datea DESC";

            $request = $BDD->prepare($sql);
            $request->execute($params);
            $logsAll = $request->fetchAll();

            if (isset($_GET['page'])) {
              $page = $_GET['page'];
            } else {
              $page = 1;
            }

            $size = 20;

            $start = ($page - 1) * $size;
            $end = $start + $size;
            $logs = array_slice($logsAll, $start, $size);

            foreach ($logs as $log) {
              $date = date('d/m/Y \à H\hi, s\s', strtotime($log['datea']));

              if ($log['type'] == 'Information') {
                $type = '<td>Information</td>';
              } elseif ($log['type'] == 'Warning') {
                $type = '<td style="color: orange;">Warning</td>';
              } elseif ($log['type'] == 'Alert') {
                $type = '<td style="color: red;">Alert</td>';
              }

              echo '<tr>';
                echo '<td>' . $date . '</td>';
                echo $type;
                echo '<td>' . $log['desca'] . '</td>';
                echo '<td>' . $log['login'] . '</td>';
              echo '</tr>';
            }
          ?>
        </tbody>
      </table>
    </div>
    <div class="bottom">
      <?php
        // Récupérer le nombre total de logs correspondant aux critères de filtrage
        $totalLogs = count($logsAll);
        
        if ($totalLogs > $end) {
          $aff = $end - $start;
          echo '<p>'.$aff.' résultats affichés</p>';
          echo 'Page-'.$page.' Logs '.$start.' à '.$end;
        } else {
          $aff = $totalLogs - $start;
          echo '<p>'.$aff.' résultats affichés</p>';
          echo 'Page-'.$page.' Logs '.$start.' à '.$totalLogs;
        }
      ?>
      <div class="nav">
        <?php 
          if ($page == 1 && $totalLogs > $end)  { ?>
            <a id="precedente" class="nav-btn inactive">Page précédente</a> 
            <a id="suivant" class="nav-btn" href="?page=<?= $page + 1 ?>">Page suivante</a>
        <?php 
          } elseif ($page > 1 && $totalLogs > $end) { ?>
            <a id="precedente" class="nav-btn" href="?page=<?= $page - 1 ?>">Page précédente</a>
            <a id="suivant" class="nav-btn" href="?page=<?= $page + 1 ?>">Page suivante</a>
        <?php 
          } elseif ($page > 1 && $totalLogs <= $end) { ?>
            <a id="precedente" class="nav-btn" href="?page=<?= $page - 1 ?>">Page précédente</a>
            <a id="suivant" class="nav-btn inactive">Page suivante</a>
        <?php 
          }?>
      </div>
    </div>
  </body>
  <script src="../timer.js"></script>
</html>