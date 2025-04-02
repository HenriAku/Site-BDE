<?php
require_once './app/controllers/UserController.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_POST['user_id'] ?? null;

    if ($userId === null) {
        die("Erreur : ID utilisateur manquant.");
    }

    $controller = new UserController();
    // Appelle une méthode pour accepter l'utilisateur
    $success = $controller->validate($userId);

    if ($success) {
        header("Location: users.php?success=" . urlencode("Utilisateur accepté"));
    } else {
        header("Location: users.php?error=" . urlencode("Erreur lors de l'acceptation"));
    }
    exit();
}