<?php
require_once './app/core/Controller.php';
require_once './app/repositories/PanierRepository.php';
require_once './app/repositories/UserRepository.php';
require_once './app/trait/FormTrait.php';
require_once './app/trait/AuthTrait.php';
require_once './app/services/AuthService.php';
require_once './vendor/autoload.php'; 

class PanierController extends Controller {

    use FormTrait;

    public function index() 
    {
        $PanierRepo = new PanierRepository();

        $repo = new PanierRepository();
        $paniers = $repo->findAll();

        $authServ = new AuthService();

        $nomProd = $repo->getProduit($paniers);

        if($authServ->isLoggedIn())
        {
            $user = $authServ->getUser();
            $this->view('/panier/index.html.twig', ['paniers' => $paniers, 'user' => $user, 'produits' => $nomProd]);
        }

    }

    public function achete($userId, $produit_id, $panierQte, $panierId) {
        $repo = new PanierRepository();
        $userRepo = new UserRepository();
        
        $produit = $repo->getProduitById($produit_id);
        $user = $userRepo->findById($userId);
        
        $repo->achete($userId, $produit_id, $panierQte);
        $repo->delete($panierId);
        
        $this->envoyerMailConfirmation($user, $produit, $panierQte);
    }


    public function acheteToutPanier($userId): bool {
        $repo = new PanierRepository();
        $userRepo = new UserRepository();
        $paniers = $repo->findAll();
        $user = $userRepo->findById($userId);
        
        $total = 0;
        $detailsAchats = [];
        
        foreach($paniers as $panier) {
            if($panier->getn_user() == $userId) {
                $produit = $repo->getProduitById($panier->getn_produit());
                $repo->achete($userId, $panier->getn_produit(), $panier->getqte());
                $repo->delete($panier->getn_panier());
                
                $total += $produit->getPrice() * $panier->getqte();
                $detailsAchats[] = [
                    'nom' => $produit->getName(),
                    'quantite' => $panier->getqte(),
                    'prix_unitaire' => $produit->getPrice(),
                    'total' => $produit->getPrice() * $panier->getqte()
                ];
            }
        }
        
        $this->envoyerMailConfirmationGlobal($user, $detailsAchats, $total);
        
        return true;
    }

    public function delete($panier_id)
    {
        $repo = new PanierRepository();
        $repo->delete($panier_id);
    }
}