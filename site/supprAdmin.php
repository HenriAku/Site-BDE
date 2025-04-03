<?php
require_once './app/controllers/UserController.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_POST['user_id'] ?? null;

    if ($userId === null) {
        die("Erreur : ID utilisateur manquant.");
    }

    $controller = new UserController();
    $success = $controller->supprAdmin($userId);

    if ($success) {
        header("Location: gestion.php?success=" . urlencode("Utilisateur refusé"));
    } else {
        header("Location: gestion.php?error=" . urlencode("Erreur lors du refus"));
    }
    exit();
}