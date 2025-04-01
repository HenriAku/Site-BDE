<?php
require_once '../app/controllers/UserController.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') 
{
    $newPassword = $_POST['new_password'] ?? '';

    if (empty($newPassword)) 
    {
        die("Erreur : le mot de passe ne peut pas être vide.");
    }

    $controller = new UserController();

    $netu = $controller->getUser();

    $controller->updatePassword($newPassword, $netu);

}

