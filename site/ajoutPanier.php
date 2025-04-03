<?php
require_once './app/repositories/AchatProduitRepository.php';

echo 'test';
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);



// Vérifiez que la requête est bien une requête POST
/*
$repo = new AchatProduitRepository();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérer les données du formulaire
    $taille = $_POST['ListeTaille'] ?? null; // Taille sélectionnée
    $quantite = $_POST['quantie'] ?? null; // Quantité saisie
	$couleur = $_POST['selectedColor'] ?? null;
	$idProd = $_POST['idProduit'] ?? null;

    // Vérifiez que toutes les données nécessaires sont présentes
    if ($taille && $quantite && $couleur && $idProd) {
        echo "Taille : $taille<br>";
        echo "Quantité : $quantite<br>";
		echo "Couleur : $couleur<br>";
		echo "IdProd : $idProd<br>";
		$repo->addPanier($taille, $quantite, $couleur, $idProd);

        // Vous pouvez maintenant traiter ces données (par exemple, les insérer dans la base de données)
    } else {
        echo "Erreur : données manquantes.";
    }
} else {
    echo "Erreur : méthode non autorisée.";
}
*/

header('Content-Type: application/json'); // Indique que la réponse est en JSON

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $taille = $_POST['ListeTaille'] ?? null;
    $quantite = $_POST['quantie'] ?? null;
    $couleur = $_POST['selectedColor'] ?? null;
    $idProd = $_POST['idProduit'] ?? null;
    $redirect = $_POST['redirection'] ?? null;

    if ($taille && $quantite && $couleur && $idProd) {
        try {
            $repo = new AchatProduitRepository();
            $repo->addPanier($taille, (int)$quantite, $couleur, (int)$idProd);
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
