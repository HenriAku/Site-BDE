<?php
require_once './app/controllers/PanierController.php';


$controller = new PanierController();

$authServ = new AuthService();

if($authServ->isLoggedIn())
{
    $controller->index();
}
else
{
    header("Location: login.php");
}