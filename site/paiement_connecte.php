<?php
require_once './app/controllers/PanierController.php';

// paiement.php
if ($_SERVER['REQUEST_METHOD'] === 'POST') 
{
    $userId = $_POST['user_id'] ?? null;
    $panierId = $_POST['panier_id'] ?? null;
    $produit_id = $_POST['produit_id'] ?? null;
    $panierQte = $_POST['panier_qte'] ?? null;

    if ($userId && $panierId && $panierQte) 
    {
        // Traitement du paiement ici
        // Exemple: enregistrement en base de données
        
        $controller = new PanierController();
        
        $controller->achete($userId, $produit_id, $panierQte, $panierId);

        header('Location: panier.php?success=1');
        exit;
    } else {
        header('Location: panier.php?code=missing_params');
        exit;
    }
} else {
    header('Location: erreur.php?code=invalid_method');
    exit;
}