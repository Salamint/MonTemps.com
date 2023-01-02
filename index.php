<?php

// Inclusion du fichier d'entête
include 'header.php';
?>

</header>
<section>

    <!-- Lorsque l'utilisateur est connecté, afficher : -->
    <?php if(is_logged()): ?>
        <h1>Bienvenue sur notre site marchand <?=$_SESSION['username']?> !</h1>
        <p><a href="formulaire.html">Voir les articles en vente.</a></p>

        <!-- Si un pagnier et rempli et qu'une commande a été lancée avant connection -->
        <?php
        if (
            isset($_SESSION['cart']) and !empty($_SESSION['cart'])
            and isset($_SESSION['command']) and !empty($_SESSION['command'])
        ):
        ?>
            <p>Vos articles dans le panier sont :</p>
            <ul>

                <!-- Affiche ou non le nombre d'article en fonction de s'il est nul ou pas -->
                <?php if (isset($_SESSION['cart']['OnePiece']) and !empty($_SESSION['cart']['OnePiece'])): ?>
                    <li><?=$_SESSION['cart']['OnePiece']?> manga(s) de One Piece</li>
                <?php endif; ?>
                <?php if (isset($_SESSION['cart']['Naruto']) and !empty($_SESSION['cart']['Naruto'])): ?>
                    <li><?=$_SESSION['cart']['Naruto']?> manga(s) de Naruto</li>
                <?php endif; ?>
            </ul>
            <form action="recapitulatif.php" method="POST">

                <!-- Si une commande à été passée, pré-rempli des champs invisible pour pouvoir repasser la commande en étant connecté -->
                <?php
                foreach(array_keys($_SESSION['command']) as $fieldName)
                    echo '<input name="'.$fieldName.'" type="hidden" value="'.$_SESSION['command'][$fieldName].'">';
                ?>
                <input name="valid_cart" type="submit" value="Finaliser la commande">
            </form>
        <?php endif; ?>

    <!-- Si l'utilisateur n'est pas connecté, afficher : -->
    <?php endif; ?>
</section>

<!-- Inclusion du pied de page -->
<?php include 'footer.php' ?>