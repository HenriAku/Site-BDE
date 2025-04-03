<?php
require_once './app/repositories/AchatProduitRepository.php';

echo 'test';
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$repo = new AchatProduitRepository();

// Vérifiez que la requête est bien une requête POST
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