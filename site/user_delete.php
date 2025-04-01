<?php
require_once './app/controllers/UserController.php';
require_once './app/services/AuthService.php';

// Activation du debug
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Sécurité
session_start();

$authService = new AuthService();

if (!$authService->isLoggedIn()) {
    http_response_code(401);
    echo json_encode(['success' => false, 'error' => 'Non autorisé']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'Méthode non autorisée']);
    exit;
}


try {
    $controller = new UserController();
    $user = $authService->getUser();
    
    if ($controller->delete($user->getId())) 
    {
        // Déconnexion après suppression
        $authService->logout();
        
        http_response_code(200);
        echo json_encode([
            'success' => true,
            'redirect' => 'login.php?account_deleted=1'
        ]);
    } else {
        http_response_code(500);
        echo json_encode(['success' => false, 'error' => 'Échec de la suppression']);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}