<?php
require_once './app/repositories/AchatProduitRepository.php';

echo 'test';
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


header('Content-Type: application/json'); // Indique que la réponse est en JSON

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $taille = $_POST['ListeTaille'] ?? null;
    $quantite = $_POST['quantie'] ?? null;
    $couleur = $_POST['selectedColor'] ?? null;
    $idProd = $_POST['idProduit'] ?? null;
    $redirect = $_POST['redirection'] ?? null;

    if ($taille && $quantite && $couleur && $idProd && $redirect) {
        try {
            $repo = new AchatProduitRepository();
            $repo->addPanier($taille, (int)$quantite, $couleur, (int)$idProd, (bool)$redirect);
            echo json_encode(['success' => true, 'message' => 'Produit ajouté au panier']);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Erreur : ' . $e->getMessage()]);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Données manquantes']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Méthode non autorisée']);
}
