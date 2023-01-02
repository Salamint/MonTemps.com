<?php

const NOM_SITE = "MonTemps.com";

// Modifie le fuseau horaire par défaut
date_default_timezone_set('Europe/Paris');


// Connexion à la base de données
try {
	$connexion = new PDO("sqlite:database/database.db");
} catch(PDOException $e) {
	echo "Connection failed: " . $e->getMessage();
	$connexion = null;
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
	return isset($_SESSION['email']);
}


// Crée la session vide, afin de commencer à travailler
if (!session_id())
{
	session_start();
	session_regenerate_id();
}

function get_account(string $email) : PDOStatement | null
{
    global $connexion;
    try
    {
        $result = $connexion->query("SELECT * FROM Utilisateurs WHERE email = \"$email\"");
		if ($result)
			return $result;
        return null;
    }
    catch (PDOException)
    {
        return null;
    }
}

function get_account_info(string $info)
{
	$account = get_account($_SESSION['email']);
	if (isset($account))
		return $account->fetchAll()[0][$info];
	return null;
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
		<title><?=NOM_SITE?></title>
		<link rel="stylesheet" type="text/css" href="assets/style.css">
	</head>

	<body>
		<header>
			<nav>
				<a class="button title" href="index.php"><button id="icon"><?=NOM_SITE?></button></a>

				<div>
					<a class="button" href="browse.php"><button>Toutes les courses</button></a>
					<!-- Si l'utillisateur est connecté, afficher un bouton de déconnexion -->
					<?php if(is_logged()): ?>
						<a class="button" href="create.php?type=temps"><button>Soumettre un temps</button></a>
						<a class="button" href="create.php?type=course"><button>Créer une course</button></a>
						<a class="button" href="profile.php"><img src="images/profile.png"></a>
						<form action="index.php" method="POST">
							<input name="disconnect" type="submit" value="Déconnexion">
						</form>
					<?php else: ?>
						<a class="button" href="connexion.php"><button id="icon">Connexion</button></a>
					<?php endif; ?>
				</div>
			</nav>
