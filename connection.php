<?php

include "header.php";

// Si une demande de connection est envoyée
if (isset($_POST['valid_connection']))
{

    // Déclare le nom d'utilisateur et le mot de passe (NULL)
    $username;
    $password;

    // Récupère le nom d'utilisateur et le mot de passe si existant et non vide
    if (isset($_POST['username']) and !empty($_POST['username']))
        $username = $_POST['username'];
    if (isset($_POST['password']) and !empty($_POST['password']))
        $password = $_POST['password'];
    
    //Si le nom d'utilisateur et le mot de passe son correct et égaux tous les deux à "fb"
    if (
        isset($username) and $username == "fb"
        and isset($password) and $password == "fb"
        )
    {
        // Affectation des variables de session
        $_SESSION['username'] = htmlspecialchars($username);
        $_SESSION['password'] = htmlspecialchars($password);
    }
}

?>

<?php if (is_logged()): ?>
    <section>
        <h2>Vous êtes connecté à votre compte en ligne <?=NOM_SITE?>.</h2>
		<a href="index.php">Retour à l'accueil</a>
    </section>
<?php else: ?>
    <section>
        <h2>Connectez-vous à votre compte en ligne <?=NOM_SITE?>.</h2>
        <hr>
        <form action="connection.php" id="connection-form" method="POST">
            <h3>Connexion :</h3>
            <input name="username" placeholder="Identifiant" required type="text">
            <input name="password" placeholder="Mot de passe" required type="password">
            <input name="valid_connection" type="submit" value="Connexion">
        </form>
    </section>
<?php endif; ?>