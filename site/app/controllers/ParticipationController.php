<?php

require_once './app/services/AuthService.php';
require_once './app/repositories/ParticipationRepesitory.php';
require_once './app/core/Controller.php';

class ParticipationController extends Controller {

    public function afficherParticipation() {

        $participationRep = new ParticipationRepesitory();
        $participations = $participationRep->findAll();

        $this->view('/evenement/participation.html.twig', ['participations' => $participations]);
    }

    public function payerParticipation() {
        // Debug immédiat
        error_log("Données POST : " . print_r($_POST, true));
        
        $participationRep = new ParticipationRepesitory();
        $idEvent = $_POST['idEvent'] ?? null;
        $idEtu = $_POST['idEtu'] ?? null;
    
        if (!$idEvent || !$idEtu) {
            error_log("IDs manquants: Event=$idEvent, Etu=$idEtu");
            // Gérer l'erreur
            $this->view('/evenement/participation.html.twig', [
                'participations' => $participationRep->findAll(),
                'error' => 'Identifiants manquants'
            ]);
            return;
        }
        
        error_log("Tentative update: Event=$idEvent, Etu=$idEtu");
    $success = $participationRep->payerParticipation($idEvent, $idEtu);
    error_log("Résultat update: ".($success ? "OK" : "ÉCHEC"));
      
    }
}