<?php
include "header.php";

$sort;

if (isset($_GET['sort']) and !empty($_GET['sort']))
{
    $sort = $_GET['sort'];
    $email = get_account_info("email");

    $sql = "SELECT C.id, C.type, T.temps, C.date
    FROM Courses AS C JOIN Temps AS T ON C.id = T.id
    WHERE T.email = \"$email\"
    ORDER BY T.temps";
}
else
{
    $sort = "date";
    $email = get_account_info("email");

    $sql = "SELECT C.id, C.type, T.temps, C.date
    FROM Courses AS C JOIN Temps AS T ON C.id = T.id
    WHERE T.email = \"$email\"
    ORDER BY C.date DESC";
}
?>

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
</section>

<section>
    <div>
        <h2>Vos Courses :</h2>
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
            <th>Catégorie</th>
            <th>Temps</th>
            <th>Date</th>
        </tr>
        <?php
        $result = $connexion->query($sql)->fetchAll();
        foreach($result as $row)
        {
            $id = $row['id'];
            $type = $row['type'];
            $time = $row['temps'];
            $date = $row['date'];
            echo "
            <tr>
                <td>$id</td>
                <td>$type</td>
                <td>$time</td>
                <td>$date</td>
            </tr>";
        }
        ?>
    </table>
</section>

<!-- Inclusion du pied de page -->
<?php include 'footer.php' ?>