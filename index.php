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
    $sql = "SELECT C.id AS ID, MIN(DISTINCT T.temps) AS min
    FROM Temps AS T JOIN Courses AS C ON T.id = C.id
    ORDER BY C.id";

    $result = $connexion->query($sql);
    echo "<table><tr><th>Course</th><th>Temps</th></tr>";
    foreach($result as $row)
    {
        echo "<tr><td>".$row['ID']."</td><td>".$row['min']."</td></tr>";
    }
    echo "</table>";
    ?>
</section>

<!-- Inclusion du pied de page -->
<?php include 'footer.php' ?>