<?php
session_start();
require "database.php";
$db = getConnection();

//verification du formulaire de modification
if(isset($_POST['submit'])){
    $repertoire_file = "img/";
    $chemin_file = $repertoire_file.basename($_FILES['file']['name']);

     //si l'image existe deja dans le repertoire img on le remplace
     if(file_exists($_FILES['file']['tmp_name'])){
        // on poura le supprimer ici mais pour l'instant je gere pas ce cas 
     }

     $path = $_FILES['file']['name'];
     $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
     $extensions = ['jpeg', 'jpg', 'png'];

    if(in_array($ext,$extensions )){
     //si l'image est bien deplacée dans le repertoire image local
        if(move_uploaded_file($_FILES['file']['tmp_name'], $chemin_file )){
            
            //on met à jour les informations de l'article dans la bd 
            $query = $db->prepare("UPDATE articles SET titre = ?, contenu = ?, categorie_id = ?, image_article = ?, date_creation = NOW() WHERE id = ?");
            $query->execute([
                $_POST['titre'],
                $_POST['contenu'],
                $_POST['categorie'],
                $chemin_file ,
                $_POST['id'],
            ]);

            header('Location: listes_article.php');
            exit();
        }
    }
}
//si l'utilisateur appuie sur le bouton editer 
if(!empty($_GET['id'])){
    //on recupere toutes les informations de l'article à editer
    $query = $db->prepare(" select id, titre, contenu, image_article, date_creation, categorie_id from articles where id = ?");
    $query->execute([$_GET['id']]);
    $edit = $query->fetch();



    //on recupere le id et nom de la categorie de l'article à editer
    $categorie_selection = $db->prepare(" select id, nom from categories where id = ?");
    $categorie_selection->execute([$edit['categorie_id']]);
    $default_categorie = $categorie_selection->fetch();



    //on recupere la liste des categorie 
    $query = $db->prepare("select * from categories");
    $query->execute();
    $categories = $query->fetchAll();


    $template = "edit_article";
    require "templates/layout.phtml";

}







?>