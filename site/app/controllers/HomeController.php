<?php

require_once './app/core/Controller.php';
require_once './app/entities/Purchase.php';
require_once './app/repositories/ProduitRepository.php';
require_once './app/trait/FormTrait.php';
require_once './app/repositories/EvenementRepository.php';
require_once './app/repositories/NewsRepository.php';


class HomeController extends Controller
{
    use FormTrait;
    public function index()
    {
        $evenementRepo = new EvenementRepository();
        $evenements = $evenementRepo->findAll();
    
        $newRepo = new NewsRepository();
        $news = $newRepo->findAll();
        
        $this->view('index.html.twig', [
            'evenements' => $evenements,
            'news' => $news
        ]);
    }

    public function purchase()
    {
        $ProduitRepo = new ProduitRepository();
        $Produit = $ProduitRepo->findById($this->getQueryParam('Produit_id'));

        $authService = new AuthService();
        $purchase = new Purchase(null,$Produit,$authService->getUser(),$this->getPostParam('quantity'));

        if(session_status() == PHP_SESSION_NONE)
            session_start();

        if(!isset($_SESSION['purchases']))
        {
            $_SESSION['purchases']=[];
        }

        $_SESSION['purchases'][] = serialize($purchase);

        $this->redirectTo('index.php');
    }
}