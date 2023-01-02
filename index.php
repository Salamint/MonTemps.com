<?php

// Inclusion du fichier d'entête
include 'header.php';
?>

</header>
<section>

    <?php if(is_logged()): ?>
        <h1>Bienvenue sur notre site <?=get_account_info("nickname")?> !</h1>
        <hr>

    <?php endif; ?>

    <h2>Les Meilleurs Temps :</h2>
    <?php
    $result = $connexion->query("SELECT id, name, type, date FROM Courses AS C");

    echo "<table>
        <tr>
            <th>Identifiant</th>
            <th>Nom</th>
            <th>Catégorie</th>
            <th>Temps</th>
            <th>Date</th>
        </tr>";
    foreach($result as $row)
    {
        $id = $row['id'];
        $result = $connexion->query(
            "SELECT MIN(temps) AS min
        FROM Temps
        WHERE id = \"$id\"");
        $temps = $result->fetchAll()[0]['min'];
        echo "<tr>
            <td>".$id."</td>
            <td>".$row['name']."</td>
            <td>".$row['type']."</td>
            <td>".$temps."</td>
            <td>".$row['date']."</td>
        <tr>";
    }
    echo "</table>";
    ?>
</section>

<!-- Inclusion du pied de page -->
<?php include 'footer.php' ?>