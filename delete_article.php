<?php

require  "database.php";
$db = getConnection();

if(!empty($_GET['id'])){

    $query = $db->prepare("DELETE  FROM articles WHERE id = ? ");
    $query->execute([$_GET['id']]);
    header('Location: listes_article.php');
    exit();
}




?>