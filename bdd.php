<?php
  //Connexion a la base de donnée//
    try {
      $BDD = new PDO(
        'mysql:host=localhost;dbname=project;charset=utf8',
        'tomh', 
        'MopAssant03$'
      );
    }
    catch (Exception $e) {
      echo('Erreur : ' . $e->getMessage());
    }
  //-----------Connexion a la base de donnée ------------//
?>