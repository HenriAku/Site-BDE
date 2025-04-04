<?php
//echo("bootique");
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);



require_once './app/controllers/AchatProduitController.php';
(new AchatProduitController())->index();

if (isset($_GET['id'])) {
    $idProduit = (int)$_GET['id']; // Récupère l'ID du produit

    // Vous pouvez maintenant utiliser $idProduit pour récupérer les détails du produit
    require_once './app/controllers/AchatProduitController.php';
    (new AchatProduitController())->index($idProduit);
} else {
    echo "Aucun produit sélectionné.";
}

if (isset($_GET['show_popup_connexion_err'])) {
    echo "<script>alert('Vous devez être connecter pour acheter'); window.location.href = 'login.php';</script>";
}

if (isset($_GET['show_popup_succes'])) {
    echo "<script>alert('Le produit a été ajouter au panier')</script>";
}


