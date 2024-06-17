<?php
session_start();
include '/var/www/r2c.uca-project.com/Forms/checkSession.php';

// Verify if the user has the rights to access this page
if ($_SESSION['droits'] < 1) {
  header('Location: ../Accueil/accueil.php');
}

// Verify if the change password form is open
if (isset($_GET['changepsw'])) {
  $pswname = $_GET['changepsw'];
}

// Recover all users except super admin
$sql = "SELECT * FROM USER WHERE droits != 2";
$request = $BDD->prepare($sql);
$request->execute();
$users = $request->fetchAll();
?>
<!DOCTYPE html>
<html>

<head>
  <title>R2C - Users</title>
  <link rel="stylesheet" type="text/css" href="../Users/users.css">
  <link rel="icon" type="image/png" href="../Img/icon.webp">
</head>
<header>
  <?php include '/var/www/r2c.uca-project.com/Header/header.php'; ?>
</header>

<body>
  <div class="fond_delConfirm"></div>
  <div class="top">
    <h1>Gérer les utilisateurs</h1>
    <div class="btns">
      <a href="../Users/newUser.php"><button class="btn">Créer un nouvel utilisateur</button></a>
    </div>
  </div>
  <div class="scroll">
    <?php
    // Set the months in french
    $months = array(
      1 => 'janvier',
      2 => 'février',
      3 => 'mars',
      4 => 'avril',
      5 => 'mai',
      6 => 'juin',
      7 => 'juillet',
      8 => 'août',
      9 => 'septembre',
      10 => 'octobre',
      11 => 'novembre',
      12 => 'décembre'
    );

    // Display all users
    foreach ($users as $i => $user) {
      $name = $users[$i]['login'];
      // Set the role
      if ($users[$i]['droits'] == 2) {
        $role = 'Super Admin';
      } elseif ($users[$i]['droits'] == 1) {
        $role = 'Admin';
      } else {
        $role = 'User';
      }

      // Set the status
      if ($users[$i]['tentativedelogin'] >= 3) {
        $affstatus = '<p style="font-weight: 700; color: #951e1e; width: 50px;">Bloqué</p>';
        $status = 0;
      } else {
        $affstatus = '<p style="font-weight: 700; color: #4ed34e; width: 50px;">Actif</p>';
        $status = 1;
      }

      // Format the date
      $date = date_create_from_format('Y-m-d', $users[$i]['dateus']);
      $formattedDate = date_format($date, 'd') . ' ' . $months[date_format($date, 'n')] . ' ' . date_format($date, 'Y');
    ?>
      <div class="line">
        <h2><?= $name ?></h2>
        <p style="width: 80px;"><?= $role ?></p>
        <?= $affstatus ?>
        <p style="width: 12vw;">Créé le <?= $formattedDate ?></p>
        <div class="btns">
          <?php if ($status == 0) { // If the user is blocked display the unlock button
          ?>
            <form action="../Forms/unlockUser.php" method="POST">
              <input type="hidden" name="userToUnlock" value="<?= $name ?>">
              <button class="btn" type="submit">Débloquer</button>
            </form>
          <?php } else { // If the user is active hide the unlock button
          ?>
            <button class="btn inactive">Débloquer</button>
          <?php } ?>
          <form action="" method="get">
            <input type="hidden" name="changepsw" value="<?= $name ?>">
            <button class="btn" type="submit">Modifier le mot de passe</button>
          </form>
          <form class="delete" action="../Forms/deleteUser.php" method="post">
            <img id="<?= $name ?>" class="corbeille" src="../Img/corbeille.webp" alt="corbeille">
            <div id="<?= $name ?>" class="delConfirm">
              <p>Êtes-vous sûr de vouloir supprimer <br /> l'utilisateur "<?= $name ?>" ?</p>
              <input type="hidden" name="usertodelete" value="<?= $name ?>">
              <button type="submit">Oui</button>
              <button type="button">Non</button>
            </div>
          </form>
        </div>
      </div>
    <?php } ?>
    <!-- Popup to change the password -->
    <div class="fondpsw" <?php if (isset($_GET['changepsw'])) {
                            echo 'style="display:block"';
                          } ?>></div>
    <div class="popupPsw" <?php if (isset($_GET['changepsw'])) {
                            echo 'style="display:block"';
                          } ?>>
      <?php include '/var/www/r2c.uca-project.com/Forms/changeUserPsw.php'; ?>
    </div>
  </div>
</body>
<script src="../timer.js"></script>
<script src="../Accueil/delConfirm.js"></script>
<script src="../Users/popup.js"></script>

</html>