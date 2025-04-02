<?php
require_once './app/controllers/HomeController.php';
ini_set('error_log', '/error.log');
error_log("Test de log");
(new HomeController())->index();
 