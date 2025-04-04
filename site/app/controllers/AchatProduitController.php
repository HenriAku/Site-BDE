<?php
require_once './app/core/Controller.php';
require_once './app/repositories/ProduitRepository.php';
require_once './app/repositories/AchatProduitRepository.php';
require_once './app/services/AuthService.php';

class AchatProduitController extends Controller
{
    public function index($idProduit = null)
    {
        if ($idProduit) {
            $ProduitRepo = new ProduitRepository();
            $produit = $ProduitRepo->findById($idProduit); // Récupère les détails du produit
            $images = $ProduitRepo->getImg();
            
            $AchatProduitRepo = new AchatProduitRepository();

            $servUser = new AuthService();
            if($servUser->getUser() === null)
            {
                $this->view('/boutique/achatProduit.html.twig', ['produit' => $produit, "images" => $images, 'admin' => null]);
    
            }else{
                $user = $servUser->getUser();
                $perm = $user->getAdmin();
                
                $this->view('/boutique/achatProduit.html.twig', ['produit' => $produit, "images" => $images, 'admin' => $perm]);
            }
           
        } else {
            echo "Aucun produit sélectionné.";
        }

   
    }
}