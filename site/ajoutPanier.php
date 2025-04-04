<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

require_once './app/repositories/AchatProduitRepository.php';
require_once './app/services/AuthService.php';

$service = new AuthService();
//idProduit=${productId}&selectedColor=${firstColor}&ListeTaille=${selectedTaille}&quantie=1`;
if (isset($_GET['idProduit']) && isset($_GET['selectedColor']) && isset($_GET['ListeTaille']) && isset($_GET['quantite'])) {
    $taille = $_GET['ListeTaille'];
    $quantite = $_GET['quantite'];
    $couleur = $_GET['selectedColor'];
    $idProd = $_GET['idProduit'];
}
else{
//if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $taille = $_POST['ListeTaille'] ?? null;
    $quantite = $_POST['quantie'] ?? null;
    $couleur = $_POST['selectedColor'] ?? null;
    $idProd = $_POST['idProduit'] ?? null;
}

// Vérification de la connexion
if (!$service->isLoggedIn()) {
    if (isset($_GET['idProduit']))
        header('Location: boutique.php?&show_popup_connexion_err=1');
    else
        header('Location: achatProduit.php?id=' . $idProd . '&show_popup_connexion_err=1');
    exit();
}


if ($taille && $quantite && $couleur && $idProd) {
    try {
        $repo = new AchatProduitRepository();
        $repo->addPanier($taille, (int)$quantite, $couleur, (int)$idProd);
        $response = ['success' => true, 'message' => 'Produit ajouté au panier'];
        // Redirection vers la page produit après succès
        if (isset($_GET['idProduit']))
            header('Location: boutique.php?&show_popup_succes=1');
        else
            header('Location: achatProduit.php?id=' . $idProd. '&show_popup_succes=1');
        exit();
    } catch (Exception $e) {
        header('Location: achatProduit.php?id=' . $idProd . '&show_popup_coul_err=1');
    }
} 


