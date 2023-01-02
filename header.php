<?php

const NOM_SITE = "MonTemps.com";

// Modifie le fuseau horaire par défaut
date_default_timezone_set('Europe/Paris');

// nom du fichier contenant le nombre d'utilisateur ayant visité le site
$viewerNumberFileName = "nombre_de_visiteur.log";

// Au démarrage, si le fichier existe,
if (file_exists($viewerNumberFileName))
{
	// Lire le nombre de visiteurs
	$file = fopen($viewerNumberFileName, "r");
	$nombreDeVisiteurs = intval(fread($file, filesize($viewerNumberFileName)));
	fclose($file);
}
else
{
	// Sinon, créer le fichier du nombre de visiteur, et initialiser la variable à 0
	$file = fopen($viewerNumberFileName, "w");
	$nombreDeVisiteurs = 0;
	fwrite($file, (string) $nombreDeVisiteurs);
	fclose($file);
}


/**
 * Fonction utilisée pour rafraîchir le nombre de visiteur du site,
 * c'est à dire incrémenter la variable de comptage de 1 et réécrire
 * la nouvelle valeur dans le fichier du nombre de visiteur.
 */
function refresh_viewer_number()
{
	global $viewerNumberFileName, $nombreDeVisiteurs;
	$nombreDeVisiteurs += 1;
	$file = fopen($viewerNumberFileName, "w");
	fwrite($file, (string) $nombreDeVisiteurs);
}


/**
 * Met fin à la session en cours en la vidant de ses attributs de session.
 */
function end_session()
{
	if (session_id())
	{
		session_unset();
		session_destroy();
	}
}


/**
 * Vérifie que l'utilisateur est connecté, en vérifiant si les attributs de session
 * 'username' et 'password' existent.
 */
function is_logged() : bool
{
	return isset($_SESSION['username']) and isset($_SESSION['password']);
}


// Crée la session vide, afin de commencer à travailler
if (!session_id())
{
	session_start();
	session_regenerate_id();
}


// Si la requête POST indique de se déconnecter
if (isset($_POST['disconnect']))
	// Mettre fin à la session
    end_session();
?>

<!DOCTYPE html>

<html lang="fr">
	<head>
		<meta charset="UTF-8">
		<title>Site marchand</title>
		<link rel="stylesheet" type="text/css" href="styles/style.css">
	</head>

	<body>
		<header>
			<nav>
				<a class="button" href="index.php"><button id="icon"><?=NOM_SITE?></button></a>

				<div>
					<!-- Si l'utillisateur est connecté, afficher un bouton de déconnexion -->
					<?php if(is_logged()): ?>
						<a class="button" href="profile.php"><img src="images/profile.png"></a>
						<form action="index.php" method="POST">
							<input name="disconnect" type="submit" value="Déconnexion">
						</form>
					<?php else: ?>
						<a class="button" href="connection.php"><button id="icon">Connexion</button></a>
					<?php endif; ?>
				</div>
			</nav>
