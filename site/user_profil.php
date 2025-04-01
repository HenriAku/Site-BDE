<!DOCTYPE html>
<html lang="fr">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Profil</title>
</head>

<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once './app/controllers/UserController.php';

$controller = new UserController();

$controller->profil();
