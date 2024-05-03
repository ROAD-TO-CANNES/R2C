<?php
  //Connexion a la base de donnée//
    try {
      $BDD = new PDO(
        'mysql:host=localhost;dbname=project;charset=utf8',
        'tomh', 
        'HJ34!5r&*'
      );
    }
    catch (Exception $e) {
      echo('Erreur : ' . $e->getMessage());
    }
  //-----------Connexion a la base de donnée ------------//
?>