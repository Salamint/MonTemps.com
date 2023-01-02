<?php
include "header.php";


if (isset($_POST['delete_race']) and !empty($_POST['delete_race'] and isset($_POST['race'])))
{
    $connexion->exec("DELETE FROM Temps WHERE Temps.id = \"".$_POST['race']."\"");
    $connexion->exec("DELETE FROM Courses WHERE Courses.id = \"".$_POST['race']."\"");
}


if (isset($_POST['delete_time']) and !empty($_POST['delete_time']) and isset($_POST['time']) and !empty($_POST['time']))
{
    if (isset($_POST['id']) and !empty($_POST['id']) and isset($_POST['email']) and !empty($_POST['email']))
    {
        $connexion->exec("DELETE FROM Temps WHERE Temps.email = \"".$_POST['email']."\" AND Temps.id = \"".$_POST['id']."\" AND Temps.temps = \"".$_POST['time']."\"");
    }
}


if (isset($_GET['sort']) and !empty($_GET['sort']))
{
    $sort = $_GET['sort'];
    if (!in_array($sort, ["id", "name", "type", "date"]))
        $sort = "id";
    if ($sort == "date")
        $sort = "date DESC";
}
else
    $sort = "id";


if (isset($_GET['time']) and !empty($_GET['time'])):
?>

<section>
    <?php
    $elements = explode("\\", base64_decode($_GET['time']));
    $email = htmlspecialchars($elements[0]);
    $id = htmlspecialchars($elements[1]);
    $temps = htmlspecialchars($elements[2]);
    
    $result = $connexion->query(
        "SELECT nickname FROM Utilisateurs WHERE email = \"$email\""
        )->fetchAll();
    $nickname = $result[0]['nickname'];
    ?>
    <h1>Temps réalisé par <a href="browse.php?user=<?=$email?>"><?=$nickname?></a> :</h1>
    <hr>
    <table>
        <tr>
            <th>Course</th>
            <th>Temps</th>
            <th>Auteur</th>
        </tr>
        <tr>
            
            <?php
            $result = $connexion->query(
            "SELECT C.name, T.temps, U.nickname
            FROM Courses AS C
            JOIN Utilisateurs AS U
            JOIN Temps AS T
            ON C.id = T.id
            AND T.email = U.email
            AND T.email = \"$email\"
            AND T.id = \"$id\"
            AND T.temps = $temps"
            )->fetchAll();

            $name = $result[0]['name'];
            $time = $result[0]['temps'];
            $nickname = $result[0]['nickname'];

            echo "<td><a href=\"browse.php?course=$id\">$name</a></td>
            <td><a href=\"browse.php?user=$email\">$time</a></td>
            <td><a href=\"browse.php?user=$email\">$nickname</a></td>";
            ?>
        </tr>
    </table>
    <?php if (is_logged() and $email == $_SESSION['email']): ?>
        <hr>
        <form action="browse.php" method="POST">
            <input name="email" type="hidden" value="<?=$email?>">
            <input name="id" type="hidden" value="<?=$id?>">
            <input name="time" type="hidden" value="<?=$temps?>">
            <input name="delete_time" id="delete" type="submit" value="Supprimer mon temps">
        </form>
    <?php endif; ?>
</section>

<?php elseif (isset($_GET['user']) and !empty($_GET['user'])): ?>

<?php

$user_email = $_GET['user'];

$account = get_account($user_email);
$data = $account->fetchAll()[0];

?>

<section>
    <h1>Profil de l'utilisateur :</h1>
    <hr>
    <ul>
        <li><strong>Nom d'utilisateur :</strong> <?=$data["nickname"]?></li>
        <li><strong>Adresse e-mail :</strong> <?=$data["email"]?></li>
        <li><strong>Âge :</strong> <?=$data["age"]?></li>
    </ul>
</section>

<section>
    <h1>Historique des courses : </h1>
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

            $result = $connexion->query(
            "SELECT C.id, C.name, C.type, T.temps, C.date
            FROM Courses AS C JOIN Temps AS T ON C.id = T.id
            WHERE T.email = \"$user_email\"
            ORDER BY T.temps"
            )->fetchAll();

            foreach($result as $row)
            {
                $time = base64_encode($_GET['user']."\\".$row['id']."\\".$row['temps']);
                echo "<tr>
                    <td><a href=\"browse.php?course=".$row['id']."\">".$row['id']."</a></td>
                    <td><a href=\"browse.php?course=".$row['id']."\">".$row['name']."</a></td>
                    <td><a href=\"browse.php?type=".$row['type']."\">".$row['type']."</a></td>
                    <td><a href=\"browse.php?time=$time\">".$row['temps']."</a></td>
                    <td><a href=\"browse.php?course=".$row['id']."\">".$row['date']."</a></td>
                </tr>";
            }
            ?>
        </table>
</section>

<?php elseif (isset($_GET['course'])): ?>

<section>
    <?php
    $result = $connexion->query("SELECT name FROM Courses WHERE id = ".$_GET['course']);
    $name = $result->fetchAll()[0]['name'];
    ?>
    <h1>Toutes les temps de la course <a href="browse.php?course=<?=$_GET['course']?>"><?=$name?></a> :</h1>
    <hr>
    <table>
        <form class="browse" action="browse.php" method="GET">
            <tr>
                <th><a href="browse.php?sort=nickname">Nom d'utilisateur</a></th>
                <th><a href="browse.php?sort=email">E-mail</a></th>
                <th><a href="browse.php?sort=temps">Temps</a></th>
            </tr>
            <?php
            $result = $connexion->query(
                "SELECT U.nickname, U.email, T.temps
            FROM Temps AS T
            JOIN Utilisateurs as U
            ON U.email = T.email
            AND T.id = ".$_GET['course']."
            ORDER BY $sort"
            );

            foreach($result->fetchAll() as $row)
            {
                $user_mail = $row['email'];
                $time = base64_encode($row['email']."\\".$_GET['course']."\\".$row['temps']);
                echo "<tr>
                    <td><a href=\"browse.php?user=$user_mail\">".$row['nickname']."</a></td>
                    <td><a href=\"browse.php?user=$user_mail\">$user_mail</a></td>
                    <td><a href=\"browse.php?time=$time\">".$row['temps']."</a></td>
                </tr>";
            }
            ?>
        </form>
    </table>
    <?php
    $owned = false;
    if (is_logged())
    {
        $result = $connexion->query(
            "SELECT C.id
        FROM Courses as C
        JOIN Proprietes as P
        ON C.id = P.id
        AND C.id = ".$_GET['course']."
        AND P.email = \"".$_SESSION['email']."\""
        )->fetchAll();

        $owned = !empty($result);
    }

    if ($owned):
    ?>
        <hr>
        <form action="browse.php" method="POST">
            <input name="race" type="hidden" value="<?=$result[0]['id']?>">
            <input name="delete_race" id="delete" type="submit" value="Supprimer la course">
        </form>
    <?php endif; ?>
</section>

<?php elseif (isset($_GET['type']) and !empty($_GET['type'])): ?>

<section>
    <h1>Toutes les courses dans la catégorie <a href="browse.php?browse=type"><?=$_GET['type']?></a> :</h1>
    <hr>
    <table>
        <tr>
            <th>ID</th>
            <th>Nom</th>
            <th>Date</th>
        </tr>
        <?php

        $result = $connexion->query(
        "SELECT C.id, C.name, C.date
        FROM Courses AS C
        WHERE C.type = \"".$_GET['type']."\""
        );

        foreach($result->fetchAll() as $row)
        {
            echo "<tr>
                <td><a href=\"browse.php?course=".$row['id']."\">".$row['id']."</a></td>
                <td><a href=\"browse.php?course=".$row['id']."\">".$row['name']."</a></td>
                <td><a href=\"browse.php?course=".$row['id']."\">".$row['date']."</a></td>
            </tr>";
        }
        ?>
    </table>
</section>

<?php else: ?>

<section>
    <h1>Toutes les courses :</h1>
    <hr>
    <table>
        <form class="browse" action="browse.php" method="GET">
            <tr>
                <th><a href="browse.php?sort=id">Identifiant</a></th>
                <th><a href="browse.php?sort=name">Nom</a></th>
                <th><a href="browse.php?sort=type">Catégorie</a></th>
                <th><a href="browse.php?sort=date">Date</a></th>
            </tr>
            <?php
            $result = $connexion->query("SELECT id, name, type, date FROM Courses ORDER BY $sort");

            foreach($result->fetchAll() as $row)
            {
                echo "<tr>
                    <td><a href=\"browse.php?course=".$row['id']."\">".$row['id']."</a></td>
                    <td><a href=\"browse.php?course=".$row['id']."\">".$row['name']."</a></td>
                    <td><a href=\"browse.php?type=".$row['type']."\">".$row['type']."</a></td>
                    <td><a href=\"browse.php?course=".$row['id']."\">".$row['date']."</a></td>
                </tr>";
            }
            ?>
        </form>
    </table>
</section>

<?php endif; ?>

<?php include "footer.php"; ?>