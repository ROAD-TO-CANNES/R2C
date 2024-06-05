<?php
  session_start(); 
  include '/var/www/r2c.uca-project.com/Forms/checkSession.php';

  if (isset($_GET['changepsw'])) {
    $pswname = $_GET['changepsw'];
  }

  //Récuperation des utilisateurs sauf de l'admin
  $sql = "SELECT * FROM USER WHERE droits != 2";
  $request = $BDD->prepare($sql);
  $request->execute();
  $users = $request->fetchAll();
?>
<!DOCTYPE html>
<html>
  <head>
    <title>R2C - Admin</title>
    <link rel="stylesheet" type="text/css" href="../Users/users.css">
    <link rel="icon" type="image/png" href="../Img/icon.png">
  </head>
  <header>
    <?php include '/var/www/r2c.uca-project.com/Header/header.php'; ?>
  </header>
  <body>
    <div class="fond_delConfirm"></div>
    <div class="top">
      <h1>Gérer les utilisateurs</h1>
      <div class="btns">
        <button class="btn">Créer un nouvel utilisateur</button>
      </div>
    </div>
    <div class="scroll">
      <?php
        foreach ($users as $i => $user) { 
          $name = $users[$i]['login'];
          if ($users[$i]['droits'] == 2) {
            $role = 'Super Admin';
          } elseif ($users[$i]['droits'] == 1) {
            $role = 'Admin';
          } else {
            $role = 'User';
          } 

          if ($users[$i]['tentativedelogin'] >= 3) {
            $status = 'Bloqué';
          } else {
            $status = 'Actif';
          }
      ?>
        <div class="line">
          <h2><?= $name?></h2>
          <p><?= $role?></p>
          <p><?= $status?></p>
          <p>Créé le <?= $users[$i]['dateus']?></p>
          <div class="btns">
            <?php if ($status == 'Bloqué') { ?>
              <form action="../Forms/unlockUser.php" method="POST">
                <input type="hidden" name="userToUnlock" value="<?=$name?>">
                <button class="btn" type="submit">Débloquer</button>
              </form>
            <?php } else { ?>
              <button class="btn inactive">Débloquer</button>
            <?php } ?>
            <form action="" method="get">
              <input type="hidden" name="changepsw" value="<?= $name?>">
              <button class="btn" type="submit">Modifier le mot de passe</button>
            </form>
            <form class="delete" action="../Forms/deleteUser.php" method="post">
              <img id="<?= $name?>" class="corbeille" src="../Img/corbeille.png" alt="corbeille">
              <div id="<?= $name?>" class="delConfirm">
                <p>Êtes-vous sûr de vouloir supprimer <br/> l'utilisateur <?= $name?> ?</p>
                <input type="hidden" name="usertodelete" value="<?= $name?>">
                <button type="submit">Oui</button>
                <button type="button">Non</button>
              </div>
            </form>          
          </div>
        </div>
      <?php } ?>
      <div class="fondpsw" <?php if (isset($_GET['changepsw'])) { echo 'style="display:block"';} ?>></div>
      <div class="popupPsw" <?php if (isset($_GET['changepsw'])) { echo 'style="display:block"';} ?>>
        <?php include '/var/www/r2c.uca-project.com/Forms/changeUserPsw.php'; ?>
      </div>
    </div>
  </body>
  <script src="../timer.js"></script>
  <script src="../Accueil/delConfirm.js"></script>
  <script src="../Users/popup.js"></script>
</html>