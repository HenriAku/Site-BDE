<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once './app/controllers/BoutiqueController.php';
(new BoutiqueController())->index();

if (isset($_GET['show_popup_succes'])) {
    echo "<script>alert('Le produit a été ajouter au panier')</script>";
}

if (isset($_GET['show_popup_connexion_err'])) {
    echo "<script>alert('Vous devez être connecter pour acheter'); window.location.href = 'login.php';</script>";
}