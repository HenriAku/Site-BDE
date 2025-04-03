<?php
require_once './app/core/Controller.php';
require_once './app/repositories/ProduitRepository.php';
require_once './app/services/AuthService.php';


class BoutiqueController extends Controller
{
    public function index()
    {
        $ProduitRepo = new ProduitRepository();

        $Produits = $ProduitRepo->findAll();
        $images = $ProduitRepo->getImg();

        $servUser = new AuthService();
        if($servUser->getUser() === null)
        {
            $this->view('/boutique/boutique.html.twig', ['Produits' => $Produits, 'images' => $images, 'admin' => null]);

        }else{
            $user = $servUser->getUser();
            $perm = $user->getAdmin();
            
            $this->view('/boutique/boutique.html.twig', ['Produits' => $Produits, 'images' => $images, 'admin' => $perm]);
        }

        
    }
}