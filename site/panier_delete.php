<?php
require_once './app/controllers/PanierController.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $panierId = $_POST['panier_id'] ?? null;

    if ($panierId === null) {
        die("Erreur : ID du panier manquant.");
    }

    $controller = new PanierController();
    // Appelle une méthode pour supprimer l'élément du panier
    $success = $controller->delete($panierId);

    if ($success) {
        header("Location: panier.php?success=" . urlencode("Article supprimé du panier"));
    } else {
        header("Location: panier.php?error=" . urlencode("Erreur lors de la suppression"));
    }
    exit();
}