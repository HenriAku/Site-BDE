<?php
require_once './app/core/Controller.php';
require_once './app/repositories/ProduitRepository.php';

class BoutiqueController extends Controller
{
    public function index()
    {
        $ProduitRepo = new ProduitRepository();

        $Produits = $ProduitRepo->findAll();
        $images = $ProduitRepo->getImg();

        $this->view('/boutique/boutique.html.twig', ['Produits' => $Produits, 'images' => $images]);
    }
}