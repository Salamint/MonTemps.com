<?php

// Inclusion du fichier d'entête
include "header.php";

// Constantes pour le calcul du prix
const TVA = 5.5;
const POURCENTS_TVA = 1 + (TVA / 100);

// Suppression des variable de session représentant le pagnier et la commande
unset($_SESSION['cart']);
unset($_SESSION['command']);


// Création du message de bienvenue
if ($nombreDeVisiteurs == 1)
    $bienvenueVisiteur = $nombreDeVisiteurs.'er';
else
    $bienvenueVisiteur = $nombreDeVisiteurs.'ème';


// Définition des champs requis
$requiredFields = [
    'quantité_one_piece',
    'quantité_naruto',
    'civ',
    'nom',
    'prenom',
    'email',
    'telephone',
    'adresse'
];

// Création d'un tableau vide contenant les champs manquants
$missingFields = array();

// Parcours des champs requis
foreach($requiredFields as $requiredField)
{
    // Vérification que chaque champ requis est existant et non vide
    if (!isset($_POST[$requiredField]) or empty($_POST[$requiredField]))
        // Sinon ajoute le nom du champ au champs manquants 
        array_push($missingFields, $requiredField);
}

// Lorqu'au moins un champ requis est manquant, affichage d'un message d'erreur
if (count($missingFields) > 0)
{
    echo "<p>Commande incorrecte ! Les champs suivants sont obligatoires mais n'ont pas été remplis :</p>";

    // Affiche chaque nom de champ manquant
    echo "<ul>";
    foreach($missingFields as $missingField)
        echo "<li>$missingField</li>";
    echo "</ul>";

    echo '<p>Merci de repasser <a href="formulaire.html">commande</a>.</p>';

    // Ferme le script
    exit;
}


// Lorsque le script n'est pas fermé, aucun champ n'est manquant


// Calcul des quantités et des prix
$quantiteOnePiece = intval($_POST['quantité_one_piece']);
$quantiteNaruto = intval($_POST['quantité_naruto']);

// Calcul TVA et prix TTC
$prixOnePiece = 15;
$prixNaruto = 12;

$prixHorsTaxe = ($quantiteOnePiece * $prixOnePiece) + ($quantiteNaruto * $prixNaruto);
$prixTTC = $prixHorsTaxe * POURCENTS_TVA;

// Définition de la civilité
if ($_POST['civ'] == "1")
    $civilite = "Monsieur";
elseif ($_POST['civ'] == "2")
    $civilite = "Madame";
else
{
    echo "</header>";
    echo "<section><p>Merci de rentrer un civilité valide !</p></section>";
    include 'footer.php';
    exit;
}
?>

<?php if(is_logged()): ?>
    <div id="finalisation">
        <h1 class="finalisation">Finalisation de la commande.</h1>
        <hr>
        <p class="finalisation">Commande du : <?=date("d F Y à G:i:s")?>.</p>
        <p class="finalisation">Vous êtes notre <?=$bienvenueVisiteur?> visiteur.</p>
    </div>
</header>
<section>
    <hr>
    <h3>Vos Coordonnées :</h3>
    <hr>
    <br>
    <p><?=$civilite?> <?=$_POST['nom']?> <?=$_POST['prenom']?></p>
    <br>
    <p>Résidant à :</p>
    <p><?=$_POST['adresse']?></p>
    <br>
    <p>Votre numéro de téléphone est le :</p>
    <p><?=$_POST['telephone']?></p>
    <br>
    <p>Et votre email est :</p>
    <p><?=$_POST['email']?></p>
    <br>
    <br>
    <hr>
    <h3>Votre commande : </h3>
    <hr>
    <br>
    <p>Vous avez acheté <?=$quantiteOnePiece?> manga(s) de One Piece, ainsi que <?=$quantiteNaruto?> manga(s) de Naruto.</p>
    <br>
    <p>Le prix hors-taxe des mangas commandés se monte donc à : <?=$prixHorsTaxe?>€.</p>
    <p>Soit un montant (TTC) Toutes Taxes Comprises de : <?=$prixTTC?>€.</p>
    <hr>
    <p>Retour aux <a href="formulaire.html">articles</a>.</p>
</section>

<?php else: ?>
</header>
<section>
    <h1>Merci de vous connecter.</h1>
    <p>Pour passer des commandes, il est nécessaire de se <a href="index.php">connecter</a>.</p>
    <p>Cependant, vos articles ont été sauvegardés dans le pagnier.</p>
    <?php
    $_SESSION['command'] = $_POST;
    $_SESSION['cart'] = [
        "OnePiece" => $quantiteOnePiece,
        "Naruto" => $quantiteNaruto
    ];
    ?>
</section>
<?php endif; ?>

<?php include "footer.php"; ?>