<?php
require_once './app/core/Controller.php';
require_once './app/repositories/ProduitRepository.php';

class BoutiqueController extends Controller
{
    public function index()
    {
        $ProduitRepo = new ProduitRepository();

        $Produits = $ProduitRepo->findAll("libelle_prod", true);
        $images = $ProduitRepo->getImg();

        $this->view('/boutique/boutique.html.twig', ['Produits' => $Produits, 'images' => $images]);
    }

    public function trier_article()
    {
        $repo = new ProduitRepository();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $typeTri = $_POST['ListeTrie'] ?? 'nom';
            
            $ordre = $_POST['listeOrdre'] ?? '▼';
            $ordreCroissant = ($ordre === '▲'); 
            
            // Normalisation du type de tri pour correspondre aux colonnes de la DB
            $typeMap = [
                'Nom' => 'libelle_prod',
                'Catégorie' => 'categorie_prod',
                'Taille' => 'taille_prod',
                'Prix' => 'prix_prod'
            ];
            $typeTri = $typeMap[$typeTri] ?? 'libelle_prod';
        }
        
        $Produits = $repo->findAll($typeTri, $ordreCroissant);
        $images = $repo->getImg();


        $this->view('/boutique/boutique.html.twig', ['Produits' => $Produits, 'images' => $images]);

    }

}