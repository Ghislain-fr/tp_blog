<?php

session_start();
require "database.php"; // a remplacer par      require "database/connection.php"

$db = getConnection();


//on recupere la liste de tous les articles de la base de données//
//------------------debut---------------------//
$query = $db->prepare(" SELECT * FROM  articles");
$query->execute();
$others_article = $query->fetchAll();
//------------------fin---------------------//
$etat = false;
$content = 200;

if(!empty($_GET['contenu']) && $_GET['contenu'] == 1){
    $etat = true;
    $idSelected = $_GET['id'];
}
// if($_GET['contenu'] == 1){
//     $contenu = true;
// }

$template = 'index';
require 'templates/layout.phtml';

?>