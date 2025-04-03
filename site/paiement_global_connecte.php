<?php
require_once './app/controllers/PanierController.php';

// paiement_global.php
if ($_SERVER['REQUEST_METHOD'] === 'POST') 
{
    $userId = $_POST['user_id'] ?? null;

    error_log("test debug");
    echo '<script>console.log("test");</script>';

    if ($userId) 
    {
        // Traitement du paiement global
        $controller = new PanierController();
        
        // Supposons que votre controller a une méthode pour acheter tout le panier
        $result = $controller->acheteToutPanier($userId);


        if ($result) 
        {
            header('Location: panier.php?success=all');
        } 
        else 
        {
            header('Location: panier.php?error=payment_failed');
        }
        exit;
    } 
    else {
        header('Location: panier.php?error=missing_user_id');
        exit;
    }
} else {
    header('Location: erreur.php?code=invalid_method');
    exit;
}