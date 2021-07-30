<?php
session_start();
require "database.php";
$db = getConnection();


if(!empty($_POST)){
    $repertoire_file = "img/";
    $chemin_file = $repertoire_file.basename($_FILES['file']['name']);
    //si l'image n'existe pas
    if(!file_exists($_FILES['file']['tmp_name'])){
        echo "l'image n'exixte pas";
    }else{

        $path = $_FILES['file']['name'];
            $ext = pathinfo($path, PATHINFO_EXTENSION);
            $extensions = ['jpeg', 'png'];

       if(in_array($ext,$extensions )){

           //si l'image est bien deplacée dans le repertoire image local
           if(move_uploaded_file($_FILES['file']['tmp_name'], $chemin_file )){
               //on enregistre l'image dans la base de donee
               $query = $db->prepare("
               INSERT INTO articles(titre, contenu, image_article, user_id, categorie_id, date_creation ) 
               VALUES(?, ?, ?, ?, ?, NOW())
               ");
   
               $query->execute([
                   $_POST['titre'],
                   $_POST['contenu'],
                   // file_get_contents($_FILES['file']['tmp_name']),
                   $chemin_file,
                   '1',
                   $_POST['categorie']
               ]);
           
   
               
        //    si l'enregistrement s'est bien passé on redirige vers liste des articles
               header("Location: listes_article.php");
               exit();
           }
       }else{
        //    var_dump('ceest le bon type de fichier');
       }
    }
    
}


    $query = $db->prepare("
    select * from categories
    ");
    $query->execute();
    $categories = $query->fetchAll();


    $template = "add_article";
    require "templates/layout.phtml";

?>

