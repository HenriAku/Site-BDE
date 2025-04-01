<?php
require_once './app/controllers/UserController.php';
require_once './app/services/AuthService.php';

// Activer le debug
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Démarrer la session
session_start();

$authService = new AuthService();

// Vérifier si l'utilisateur est connecté
if (!$authService->isLoggedIn()) {
    die("Erreur : Utilisateur non connecté");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newPassword = $_POST['new_password'] ?? '';

    if (empty($newPassword)) {
        die("Erreur : le mot de passe ne peut pas être vide.");
    }

    if (strlen($newPassword) < 6) {
        die("Erreur : Le mot de passe doit contenir au moins 6 caractères");
    }

    $controller = new UserController();
    $user = $authService->getUser(); // Récupère l'objet User complet
    
    if ($controller->updatePassword($newPassword, $user->getId())) {
        header("Location: user_profil.php?success=1");
        exit;
    } else {
        die("Erreur lors de la mise à jour du mot de passe");
    }
}