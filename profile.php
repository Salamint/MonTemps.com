<?php
include "header.php";

$sort;

if (isset($_GET['sort']) and !empty($_GET['sort']))
{
    $sort = $_GET['sort'];
    $email = get_account_info("email");

    $sql_best = "SELECT C.id, C.name, C.type, T.temps, C.date
    FROM Courses AS C JOIN Temps AS T ON C.id = T.id
    WHERE T.email = \"$email\"
    ORDER BY T.temps";
}
else
{
    $sort = "date";
    $email = get_account_info("email");

    $sql_best = "SELECT C.id, C.name, C.type, T.temps, C.date
    FROM Courses AS C JOIN Temps AS T ON C.id = T.id
    WHERE T.email = \"$email\"
    ORDER BY C.date DESC";
}

if (isset($_POST['delete']) and !empty($_POST['delete']))
{
    $delete = true;

    // Suppression des temps
    $connexion->query("DELETE FROM Temps WHERE email = \"$email\"");

    // Suppression du compte
    $connexion->query("DELETE FROM Utilisateurs WHERE email = \"$email\"");

    // Fin de la session
    end_session();
}
else
    $delete = false;

if (is_logged()):
?>

<?php if ($delete): ?>
    <section>
        <h1>Compte définitivement supprimé !</h1>
        <a href="index.php">Retour à l'accueil</a>
    </section>
<?php else: ?>
    <section>
        <h2>Votre profil <?=NOM_SITE?> :</h2>
        <hr>
        <div class="profile">
            <img src="images/profile.png" width="128" height="128">
            <ul class="right">
                <li><strong>Nom d'utilisateur :</strong> <?=get_account_info("nickname")?></li>
                <li><strong>Adresse e-mail :</strong> <?=get_account_info("email")?></li>
                <li><strong>Âge :</strong> <?=get_account_info("age")?></li>
                <li><strong>Mot de passe :</strong> <?=get_account_info("password")?></li>
            </ul>
        </div>
        <form action="profile.php" method="POST">
            <button class="share" onclick=<?php echo "\"copier('user=".base64_encode($email)."')\""; ?>>Partager</button>
            <input name="delete" id="delete" type="submit" value="Supprimer mon compte">
        </form>
    </section>

<section>
    <div>
        <h2>Vos Temps :</h2>
        <form action="profile.php" method="GET" class="right">
            <?php if (isset($sort) and !empty($sort) and $sort == "time"): ?>
                <input type="hidden" name="sort" value="date">
                <input type="submit" value="Tri par date">
            <?php else: ?>
                <input type="hidden" name="sort" value="time">
                <input type="submit" value="Tri par temps">
            <?php endif; ?>
        </form>
    </div>
    <hr>
    <table>
        <tr>
            <th>ID</th>
            <th>Nom</th>
            <th>Catégorie</th>
            <th>Temps</th>
            <th>Date</th>
        </tr>
        <?php
        $result = $connexion->query($sql_best)->fetchAll();
        foreach($result as $row)
        {
            $id = $row['id'];
            $name = $row['name'];
            $type = $row['type'];
            $time = $row['temps'];
            $date = $row['date'];
            echo "
            <tr>
                <td>$id</td>
                <td>$name</td>
                <td>$type</td>
                <td>$time</td>
                <td>$date</td>
            </tr>";
        }
        ?>
    </table>
</section>

<section>
    <div>
        <h2>Vos Temps :</h2>
        <form action="profile.php" method="GET" class="right">
            <?php if (isset($sort) and !empty($sort) and $sort == "time"): ?>
                <input type="hidden" name="sort" value="date">
                <input type="submit" value="Tri par date">
            <?php else: ?>
                <input type="hidden" name="sort" value="time">
                <input type="submit" value="Tri par temps">
            <?php endif; ?>
        </form>
    </div>
    <hr>
    <table>
        <tr>
            <th>ID</th>
            <th>Nom</th>
            <th>Catégorie</th>
            <th>Temps</th>
            <th>Date</th>
        </tr>
        <?php
        $result = $connexion->query($sql_best)->fetchAll();
        foreach($result as $row)
        {
            $id = $row['id'];
            $name = $row['name'];
            $type = $row['type'];
            $time = $row['temps'];
            $date = $row['date'];
            echo "
            <tr>
                <td>$id</td>
                <td>$name</td>
                <td>$type</td>
                <td>$time</td>
                <td>$date</td>
            </tr>";
        }
        ?>
    </table>
</section>
<?php endif; ?>

<?php else: ?>

<section>
    <h2>Vous devz être connecté pour consulter votr compte <?=NOM_SITE?>.</h2>
    <a href="index.php">Retour à l'accueil</a>
</section>

<?php endif; ?>

<!-- Inclusion du pied de page -->
<?php include 'footer.php' ?>