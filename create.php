<?php
include "header.php";

$error;

if (isset($_GET['type']) and !empty($_GET['type']))
    $type = $_GET['type'];
else
    $type = "temps";


function id_is_used(int $id) : bool
{
    global $connexion;
    $result = $connexion->query("SELECT id FROM Courses")->fetchAll();
    foreach ($result as $row)
    {
        if ($row['id'] == (string) $id)
            return true;
    }
    return false;
} 



if (isset($_POST['submit_race']))
{
    if (isset($_POST['name']) and !empty($_POST['name']) and isset($_POST['type']) and !empty($_POST['type']))
    {
        $number = 0;
        while (id_is_used($number)) {
            $number += 1;
        }
        $name = htmlspecialchars($_POST['name']);
        $type = htmlspecialchars($_POST['type']);
        $date = date_format(date_create(),"Y-m-d");

        try {
            $connexion->exec("INSERT INTO Courses VALUES (\"$number\", \"$name\", \"$type\", \"$date\")");
        } catch (PDOException)
        {
            $error = "Une erreur s'est produite lors de la création de la course.";
        }

        try {
            $connexion->exec("INSERT INTO Proprietes VALUES (\"".$_SESSION['email']."\", \"$number\")");
        } catch (PDOException)
        {
            $error = "Une erreur s'est produite lors de la création de la propriété.";
        }
    }
}
elseif (isset($_POST['submit_time']))
{
    if (isset($_POST['id']) and !empty($_POST['id']) and isset($_POST['temps']) and !empty($_POST['temps']))
    {
        $id = htmlspecialchars($_POST['id']);
        $temps = (int) htmlspecialchars($_POST['temps']);
        $email = $_SESSION['email'];

        try {
            $connexion->exec("INSERT INTO Temps VALUES (\"$email\", \"$id\", \"$temps\")");
        } catch (PDOException)
        {
            $error = "Ce temps existe déjà sur cette course à votre nom.";
        }
    }
}

if (is_logged()):
?>

<?php if (isset($_POST['submit_race'])): ?>

<section>
    <h1>Course créée !</h1>
    <ul>
        <li><strong>Identifiant : </strong><?=$number?></li>
        <li><strong>Nom : </strong><?=$name?></li>
        <li><strong>Catégorie : </strong><?=$type?></li>
        <li><strong>Date : </strong><?=$date?></li>
    </ul>
    <button class="share" onclick="copier('course=<?=$number?>')">Partager</button>
</section>

<?php elseif (isset($_POST['submit_time'])): ?>

<section>
    <h1>Temps soumis !</h1>
    <ul>
        <li><strong>Identifiant de la course : </strong><?=$id?></li>
        <li><strong>Temps réalisé : </strong><?=$temps?> (s)</li>
    </ul>
    <button class="share" onclick=<?php
    $string = base64_encode("$id\\$email\\$temps");
    echo "\"copier('time=$string')\"";
    ?>>Partager</button>
</section>

<?php elseif ($type == "course"): ?>

<section>
    <h1>Création d'une course :</h1>
    <hr>
    <form action="create.php" id="centered-form" method="POST">
        <input name="name" type="text" minlength="4" maxlength="32" placeholder="Nom" required>
        <input name="type" type="text" minlength="4" maxlength="32" placeholder="Catégorie" required>
        <input name="submit_race" type="submit" value="Créer">
    </form>
    <?php if (isset($error)): ?>
        <p><?=$error?></p>
    <?php endif; ?>
</section>

<?php elseif ($type == "temps"): ?>

<section>
    <h1>Soumission d'un temps :</h1>
    <hr>
    <form action="create.php" id="centered-form" method="POST">
        <input name="id" type="number" placeholder="Identifiant de course" required>
        <input name="temps" type="number" placeholder="Temps (secondes)" required>
        <input name="submit_time" type="submit" value="Soumettre">
    </form>
    <?php if (isset($error)): ?>
        <p><?=$error?></p>
    <?php endif; ?>
</section>

<?php else: ?>

<section>
    <h1>Erreur 404.</h1>
    <a href="index.php">Retour à l'accueil</a>
</section>

<?php endif; ?>

<?php else: ?>

<section>
    <h2>Vous devz être connecté pour créer un nouveau temps ou une nouvelle course.</h2>
    <a href="index.php">Retour à l'accueil</a>
</section>

<?php endif; ?>

<?php include "footer.php"; ?>