<?php
require_once './app/controllers/EvenementController.php';

$controller = new EvenementController();

// Si on a un paramètre id, on affiche le détail, sinon la liste
if (isset($_GET['id'])) {
    $controller->show();
} else {
    $controller->index();
}