<?php 
session_start();

require "database.php";
$db = getConnection();

$query = $db->prepare(" select id, titre, contenu, image_article, date_creation from articles where user_id = ?");
$query->execute([$_SESSION['auth']['id']]);

$listes = $query->fetchAll();

$template = "listes_article";
require "templates/layout.phtml";


?>