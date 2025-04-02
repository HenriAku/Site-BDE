<?php
require_once './app/core/Controller.php';
require_once './app/repositories/ProduitRepository.php';

class AchatProduitController extends Controller
{
    public function index($idProduit = null)
    {
        if ($idProduit) {
            $ProduitRepo = new ProduitRepository();
            $produit = $ProduitRepo->findById($idProduit); // Récupère les détails du produit
            $images = $ProduitRepo->getImg();

            $this->view('/boutique/achatProduit.html.twig', ['produit' => $produit, "images" => $images]);
        } else {
            echo "Aucun produit sélectionné.";
        }
    }
}