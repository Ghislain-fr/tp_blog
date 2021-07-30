<?php

session_start();

require 'database/connection.php';

$db = getConnection();

// Tester si des données proviennent du formulaire
if (! empty($_POST)) {

	// Si des données sont trouvées, récupérer, ds la DB, l'utilisateur correspondant au nom tapé dans le formulaire
	$query = $db->prepare("
		SELECT id, nom, email, mot_de_passe FROM users WHERE nom = ?
	");

	$query->execute([
		$_POST['username']
	]);

	$user = $query->fetch();

	// Tester le nom d'utilisateur
	if ($user === false) {
		// Si le nom d'utilisateur ne correspond pas
		$_SESSION['error'] = "L'utilisateur n'existe pas.";
		header('Location: login.php');
		exit();
	}

	// Tester le mot de passe hashé
	if (! password_verify($_POST['password'], $user['mot_de_passe'])) {
		// Si le mot de passe ne correspond pas
		$_SESSION['error'] = "Le mot de passe est faux.";
		header('Location: login.php');
		exit();
	}

	
	$_SESSION['auth'] = [
		'id' => $user['id'],
		'username' => $user['username'],
		'email' => $user['email']
	];

	header('Location: index.php');
	exit();
}


$template = 'login';
require 'templates/layout.phtml';