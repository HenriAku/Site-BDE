<?php
require_once './app/core/Controller.php';
require_once './app/repositories/PanierRepository.php';
require_once './app/trait/FormTrait.php';
require_once './app/trait/AuthTrait.php';
require_once './app/services/AuthService.php';

class PanierController extends Controller {

use FormTrait;

    public function index() 
    {
        $PanierRepo = new PanierRepository();

        $repo = new PanierRepository();
        $paniers = $repo->findAll();

        $authServ = new AuthService();

        $nomProd = $repo->getProduit($paniers);

        echo('<script> console.log("'.$nomProd[0]->getName().'"); </script>');



        if($authServ->isLoggedIn())
        {
            $user = $authServ->getUser();
            $this->view('/panier/index.html.twig', ['paniers' => $paniers, 'user' => $user, 'produits' => $nomProd]);
        }

    }
}