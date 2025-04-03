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



        echo('<script>console.log("'.$paniers[10]->gettaile().'");</script>');

        if($authServ->isLoggedIn())
        {
            $user = $authServ->getUser();
            $this->view('/panier/index.html.twig', ['paniers' => $paniers, 'user' => $user, 'produits' => $nomProd]);
        }

    }

    public function achete($userId, $produit_id, $panierQte, $panierId)
    {
        $repo = new PanierRepository();

        $repo->achete($userId, $produit_id, $panierQte);

        $repo->delete($panierId);
    }

    public function acheteToutPanier($userId):bool
    {
        $repo = new PanierRepository();

        $paniers = $repo->findAll();

        foreach($paniers as $panier)
        {
            if($panier->getn_user() == $userId)
            {
                $repo->achete($userId, $panier->getn_produit(), $panier->getqte());
                $repo->delete($panier->getn_panier());
            }
        }

        return true;
    }

}