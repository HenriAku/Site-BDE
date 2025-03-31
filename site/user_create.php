<!DOCTYPE html>
<html lang="fr">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Inscription</title>
</head>

<?php

require_once './app/controllers/UserController.php';

$controller = new UserController();

$controller->create();
/*

$page = '';
$page .= '<body>';

$page .= '<h1>Inscription</h1>';

$page .= '<div class="Form_Insc">';
$page .= '<div class="NameFirstName" style="display: flex; gap: 10px;">';

$page .= '<div class="field" style="display: flex; flex-direction: column; align-items: start;">';
$page .= '<p>Nom</p>';
$page .= '<input placeholder="Nom" style="width: 150px;"/>';
$page .= '</div>';

$page .= '<div class="field" style="display: flex; flex-direction: column; align-items: start;">';
$page .= '<p>Prénom</p>';
$page .= '<input placeholder="Prénom" style="width: 150px;"/>';
$page .= '</div>';
$page .= '</div>'; // Fin NameFirstName

$page .= '<div class="Email">';
$page .= '<p>Email</p>';
$page .= '<input type="email" placeholder="Email" />';
$page .= '</div>'; //Fin Email

$page .= '<div class="Mdp">';
$page .= '<p>Mot de Passe</p>';
$page .= '<input type="password" placeholder="Password" />';
$page .= '</div>'; //Fin Mdp

$page .= '<div class="Conf_Mdp">';
$page .= '<p>Confirmer Mot De Passe</p>';
$page .= '<input type="password" placeholder="Confirm Password" />';
$page .= '</div>'; //Fin Conf_Mdp

$page .= '<div class="Netu">';
$page .= '<p>Numéro Etudiant</p>';
$page .= '<input type="placeholder" placeholder="Numéro Etudiant" />';
$page .= '</div>'; //Fin Netu

$page .= '</div>'; //Fin Form_insc

$page .= '<pre>Devenez Adhérent pour avoir des avantages 
Prix : 10€</pre>';

$page .= '<button class="Btn_Insc"> Adhérer </button>';



echo $page;*/