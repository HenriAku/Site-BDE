<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once './app/services/AuthService.php';
require_once './app/repositories/ProduitRepository.php';
require_once './app/core/Controller.php';
require_once './app/trait/FormTrait.php';


class ProduitController extends Controller{

    use FormTrait;

    public function index() {
        $ProduitRepo = new ProduitRepository();
        $Produits = $ProduitRepo->findAll();
        $images = $ProduitRepo->getImg();
    
        // Créer une variable globale Twig
        
        $this->view('/produit/index.html.twig', [
            'Produits' => $Produits,
            'images' => $images
        ]);
    }   

    public function create() {
        //$this->checkAuth();

        $ProduitRepo = new ProduitRepository();
        $data = $this->getAllPostParams();
        $errors = [];

        if (!empty($data)) {
            try {

                $errors = [];

                // Validation des données
                if (empty($data['nom'])) {
                    $errors[] = 'Le nom est requis.';
                }
                if (empty($data['prix']) || $data['prix'] <= 0) {
                    $errors[] = 'Le prix doit être supérieur à 0.';
                }
                if (empty($data['stock']) || $data['stock'] < 0) {
                    $errors[] = 'Le stock ne peut pas être négatif.';
                }
                if (empty($data['description']) || $data['description'] < 0) {
                    $errors[] = 'La description ne peut pas etre vide .';
                }

                if (empty($data['stock']) || $data['stock'] < 0) {
                    $errors[] = 'La description ne peut pas etre vide .';
                }

                
                if (empty($data['taille']) || $data['taille'] < 0) {
                    $errors[] = 'La description ne peut pas etre vide .';
                }

                if (!empty($errors)) {
                    throw new Exception(implode(', ', $errors));
                }

                $Produit = new Produit(
                    null,
                    $data['nom'],
                    (int)$data['stock'],
                    $data['categorie'],
                    (float)$data['prix'],
                    $data['description'] ?? '',
                    $data['colorPicker'],
                    $data['taille']
                );

                // Gérer les soumissions du formulaire
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $action = $_POST['action'] ?? '';
                    $id = $_POST['id'] ?? 0;

                    switch ($action) {
                        case 'add':
                            $this->addEvent($repo,$Produit);
                            break;
                        case 'update':
                            $this->updateEvent($repo, $Produit);
                            break;
                        case 'delete':
                            $this->deleteEvent($repo, $Produit);
                            break;
                    }
                    $this->redirectTo('Produits.php');
                }


            } catch (Exception $e) {
                $errors = explode(', ', $e->getMessage());
            }
        }

        $this->view('/Produit/form', [
            'data' => $data,
            'errors' => $errors
        ]);
    }

    private function checkAuth() {
        $auth = new AuthService();
        if (!$auth->isLoggedIn()) {
            $this->redirectTo('login.php');
        }
    }

    public function update()
    {
        //$this->checkAuth();

        $id = $this->getQueryParam('id');

        if ($id === null) {
            throw new Exception('Produit ID is required.');
        }

        if ($Produit === null) {
            throw new Exception('Produit not found');
        }

        $data = array_merge([
            'name'=>$Produit->getName(),
            'stock'=>$Produit->getStock(),
            'price'=>$Produit->getPrice(),
            'description'=>$Produit->getDescription(),
        ],$this->getAllPostParams()); //Get submitted data


        $errors = [];

        if (!empty($this->getAllPostParams())) {
            try {

                $errors = [];

                // Validation des données
                if (empty($data['name'])) {
                    $errors[] = 'Le nom est requis.';
                }
                if (empty($data['price']) || $data['price'] <= 0) {
                    $errors[] = 'Le prix doit être supérieur à 0.';
                }
                if (empty($data['stock']) || $data['stock'] < 0) {
                    $errors[] = 'Le stock ne peut pas être négatif.';
                }

                if (!empty($errors)) {
                    throw new Exception(implode(', ', $errors));
                }

                $Produit = new Produit(
                    null,
                    $data['name'],
                    (float)$data['price'],
                    $data['description'] ?? '',
                    (int)$data['stock']
                );

                $this->redirectTo('Produits.php');
            } catch (Exception $e) {
                $errors = explode(', ', $e->getMessage());
            }
        }

        $this->view('/produit/form.html.twig', [
            'data' => $data,
            'errors' => $errors
        ]);
    }


}
