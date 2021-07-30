<?php

session_start();

$db = new PDO("mysql:host=localhost;dbname=blog;charset=UTF8", 'root', '', [
	PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
	PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
]);



if (! empty($_POST)) {

	// Tableau (stocké dans la session) contenant toutes les erreurs du formulaire
	$_SESSION['errors'] = [];

	// Vérification Nom et prénom
	$query = $db->prepare("
		SELECT nom, prenom FROM users WHERE nom = ? AND prenom = ?
	");
	$query->execute([
		$_POST['lastname'],
		$_POST['firstname']
	]);
	$userNomEtPrenom = $query->fetchAll();
	if (!empty($userNomEtPrenom)) {
		$_SESSION['errors']['fullname'] = "Il existe déjà un utilisateur avec ce nom et prénom";
	}

	// Est-ce que le mot de passe est assez long ?
	if (strlen($_POST['password']) < 6) {
		$_SESSION['errors']['password'] = "Le mot de passe doit faire au moins 6 caractères";
	}

	// Vérification adresse Email
	$query = $db->prepare("
		SELECT email FROM users WHERE email = ?
	");
    $query->execute([
		$_POST['email']
	]); 
    $email = $query->fetch();

	if ($email) {
		$_SESSION['errors']['email'] = "Email déjà utilisé";
		header('Location: register.php');
		exit();
	}


	// Vérification avatar
	$repertoire_avatar = "avatars/";
	$chemin_avatar = $repertoire_avatar.basename($_FILES['avatar']['name']);
		// Si l'image n'existe pas
		if (!file_exists($_FILES['avatar']['tmp_name'])) {
			$_SESSION['errors']['avatar'] = "L'image n'existe pas";
		} 
	
	// Si il y a au moins 1 erreur :
	if (count($_SESSION['errors']) > 0) {
		header('Location: register.php');
		exit();
	} 
	
	else {
		// Pas d'erreurs dans le formulaire ? On supprime le tableau contenant les erreurs
		unset($_SESSION['errors']);
	}

	// Enregistrement dans la base de données
		if(move_uploaded_file($_FILES['avatar']['tmp_name'], $chemin_avatar)) {
			// on enregistre l'image dans la bdd
			$query = $db->prepare("
			INSERT INTO users(nom, prenom, email, mot_de_passe, image_profil, date_creation) 
			VALUES(?, ?, ?, ?, ?, NOW())
			");

			$query->execute([
				$_POST['lastname'],
				$_POST['firstname'],
				$_POST['email'],
				password_hash($_POST['password'], PASSWORD_BCRYPT),
				$chemin_avatar
			]);
		header('Location: register.php');
		exit();
	}
}


$template ='register';
require 'templates/layout.phtml';