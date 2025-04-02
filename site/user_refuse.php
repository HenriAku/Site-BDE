<?php
require_once './app/controllers/UserController.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_POST['user_id'] ?? null;

    if ($userId === null) {
        die("Erreur : ID utilisateur manquant.");
    }

    $controller = new UserController();
    // Appelle une méthode pour refuser l'utilisateur
    $success = $controller->delete($userId);

    if ($success) {
        header("Location: users.php?success=" . urlencode("Utilisateur refusé"));
    } else {
        header("Location: users.php?error=" . urlencode("Erreur lors du refus"));
    }
    exit();
}