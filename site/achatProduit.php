<?php
//echo("bootique");
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once './app/controllers/AchatProduitController.php';
(new AchatProduitController())->index();

if (isset($_GET['id'])) {
    $idProduit = (int)$_GET['id']; // Récupère l'ID du produit
    echo "ID du produit : " . $idProduit;

    // Vous pouvez maintenant utiliser $idProduit pour récupérer les détails du produit
    require_once './app/controllers/AchatProduitController.php';
    (new AchatProduitController())->index($idProduit);
} else {
    echo "Aucun produit sélectionné.";
}

