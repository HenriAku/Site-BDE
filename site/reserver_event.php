<?php
require_once './app/services/AuthService.php';
require_once './app/repositories/EvenementRepository.php';

$auth = new AuthService();
if (!$auth->isLoggedIn()) {
    header("Location: /login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $eventId = (int)$_POST['event_id'];
    $userId = $auth->getUser()->getId();

    try {
        $repo = new EvenementRepository();
        
        // Vérifier si l'utilisateur n'a pas déjà réservé
        if ($repo->isAlreadyRegistered($userId, $eventId)) {
            $_SESSION['reservation_error'] = "Vous avez déjà réservé cet événement";
            header("Location: /event.php?id=$eventId");
            exit();
        }

        // Enregistrer la réservation
        if ($repo->registerForEvent($userId, $eventId)) {
            $_SESSION['reservation_success'] = "Réservation confirmée!";
        } else {
            $_SESSION['reservation_error'] = "Erreur lors de la réservation";
        }
        
        header("Location: /event.php?id=$eventId");
        exit();
        
    } catch (Exception $e) {
        $_SESSION['reservation_error'] = "Erreur: " . $e->getMessage();
        header("Location: /event.php?id=$eventId");
        exit();
    }
}

header("Location: /");
exit();