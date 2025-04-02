<?php
require_once './app/core/Controller.php';
require_once './app/controllers/UserController.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$controller = new UserController();

$controller->index(); // Afficher la liste par défaut
