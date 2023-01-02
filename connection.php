<?php

include "header.php";

$error;

// Si une demande de connexion est envoyée
if (isset($_POST['validate_sign_in']))
{

    // Déclare le nom d'utilisateur et le mot de passe (NULL)
    $email;
    $password;

    // Récupère le nom d'utilisateur et le mot de passe si existant et non vide
    if (isset($_POST['email']) and !empty($_POST['email']))
        $email = $_POST['email'];
    if (isset($_POST['password']) and !empty($_POST['password']))
        $password = $_POST['password'];
    
    //Si le nom d'utilisateur et le mot de passe son correct et le mot de passe correspond
    if (isset($email) and isset($password))
    {
        $sql = "SELECT password FROM Utilisateurs WHERE email = \"$email\"";
        try
        {
            $result = $connexion->query($sql);
            $account = $result->fetchAll();
            if (!empty($account))
            {
                if ($account[0]["password"] == $password)
                    $_SESSION['email'] = htmlspecialchars($email);
                else
                    $error = "Mot de passe incorrect.";
            }
            else
                $error = "Aucun compte lié à cette adresse.";
        }
        catch (PDOException)
        {
            $error = "Impossible de joindre votre compte.";
        }
    }
}

// Si une demande d'inscription est envoyée
if (isset($_POST['validate_sign_up']))
{

    // Récupère les données
    $email = htmlspecialchars($_POST['email']);
    $password = htmlspecialchars($_POST['password']);
    $nickname = htmlspecialchars($_POST['nickname']);
    $age = (int) htmlspecialchars($_POST['age']);
    
    $account = get_account($email);
    if (empty($account->fetchAll()))
    {
        if (isset($email) and !empty($email))
        {
            if (isset($password) and !empty($password))
            {
                if (strlen($password) >= 8)
                {
                    if (isset($nickname) and !empty($nickname))
                    {
                        if (strlen($nickname) >= 5)
                        {
                            if (isset($age) and !empty($age))
                            {
                                if ($age >= 16)
                                {
                                    $sql = "INSERT INTO Utilisateurs VALUES (\"$email\", \"$password\", \"$nickname\", $age)";
                                    $result = $connexion->exec($sql);
                                    echo $result;
                                    $_SESSION['email'] = $email;
                                }
                                else
                                    $error = "Vous devez avoir au minimum 16 ans pour pouvoir créer un compte.";
                            }
                            else
                                $error = "Aucun âge fourni.";
                        }
                        else
                            $error = "Nom d'utilisateur trop court.";
                    }
                    else
                        $error = "Aucun nom d'utilisateur fourni.";
                }
                else
                    $error = "Mot de passe trop court.";
            }
            else
                $error = "Aucun mot de passe fourni";
        }
        else
            $error = "Aucun email fourni.";
    }
    else
        $error = "Un compte existe déjà à cette adresse.";
}

if (is_logged()):
?>

    <section>
        <h2>Vous êtes connecté à votre compte en ligne <?=NOM_SITE?>.</h2>
		<a href="index.php">Retour à l'accueil</a>
    </section>

<?php elseif (isset($_POST['sign_up'])): ?>

    <section>
        <h2>Créez votre compte en ligne <?=NOM_SITE?>.</h2>
        <hr>
        <form action="connection.php" id="connection-form" method="POST">
            <h3>Inscription :</h3>
            <input name="email" placeholder="Adresse e-mail" required type="email">
            <input name="password" placeholder="Mot de passe" minlength="8" maxlength="32" required type="password">
            <input name="nickname" placeholder="Nom d'utilisateur" minlength="5" maxlength="32" required type="text">
            <input name="age" placeholder="Âge" required type="number">
            <input name="validate_sign_up" type="submit" value="Inscription">
        </form>
        <hr>
        <form action="connection.php" method="POST">
            <label for="sign_in">Déjà un compte ?</label>
            <input name="sign_in" id="sign_in" type="submit" value="Se connecter">
        </form>
    </section>

<?php else: ?>

    <section>
        <h2>Connectez-vous à votre compte en ligne <?=NOM_SITE?>.</h2>
        <hr>
        <form action="connection.php" id="connection-form" method="POST">
            <h3>Connexion :</h3>
            <input name="email" placeholder="Adresse e-mail" required type="email">
            <input name="password" placeholder="Mot de passe" minlength="8" required type="password">
            <input name="validate_sign_in" type="submit" value="Connexion">
        </form>
        <?php if (isset($error)): ?>
            <p><?=$error?></p>
        <?php endif; ?>
        <hr>
        <form action="connection.php" method="POST">
            <label for="sign_up">Pas de compte ?</label>
            <input name="sign_up" id="sign_up" type="submit" value="En créer un">
        </form>
    </section>

<?php endif; ?>

<!-- Inclusion du pied de page -->
<?php include 'footer.php' ?>