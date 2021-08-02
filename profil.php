<?php
session_start();

require 'database/connection.php';

$db = getConnection();
if($_SESSION['auth']){


//$query = $db->prepare('
//SELECT *
//FROM articles
//WHERE id = ?

//');
$query = $db->prepare('
SELECT nom, prenom, email, image_profil
FROM users
WHERE id = ?
'); 

$query->execute([
    $_SESSION['auth']['id']
]);
$infosUser = $query->fetch();
};


$template = 'profil';

require 'templates/layout.phtml';