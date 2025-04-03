<?php

require_once './app/services/AuthService.php';
require_once './app/repositories/ParticipationRepesitory.php';
require_once './app/core/Controller.php';

class ParticipationController extends Controller {

    public function afficherParticipation() {

        $participationRep = new ParticipationRepesitory();
        $participations = $participationRep->findAll();

        $servUser = new AuthService();
        if($servUser->getUser() === null)
        {
            $this->view('/evenement/participation.html.twig', ['participations' => $participations,'admin' => $perm]);
        }else{
            $user = $servUser->getUser();
            $perm = $user->getAdmin();
            $this->view('/evenement/participation.html.twig', ['participations' => $participations,'admin' => $perm]);
        }
        
    }

    public function payerParticipation() {
        
        $participationRep = new ParticipationRepesitory();
        $idEvent = $_POST['idEvent'] ?? null;
        $idEtu = $_POST['idEtu'] ?? null;
    
        if (!$idEvent || !$idEtu) {
            $this->view('/evenement/participation.html.twig', [
                'participations' => $participationRep->findAll(),
                'error' => 'Identifiants manquants'
            ]);
            return;
        }
        
        $success = $participationRep->payerParticipation($idEvent, $idEtu);

        $servUser = new AuthService();
        if($servUser->getUser() === null)
        {
            $this->view('/evenement/participation.html.twig', ['participations' => $participationRep->findAll(),'admin' => null]);
        }else{
            $user = $servUser->getUser();
            $perm = $user->getAdmin();
            $this->view('/evenement/participation.html.twig', ['participations' => $participationRep->findAll(),'admin' => $perm]);
        } 
    }  
}