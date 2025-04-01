<?php
require_once './app/controllers/EvenementController.php';

$controller = new EvenementController();

if (isset($_GET['id'])) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['note'])) {
        $controller->addComment();
    } else {
        $controller->show();
    }
} else {
    $controller->index();
}