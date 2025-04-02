<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once './app/services/AuthService.php';
require_once './app/repositories/ProduitRepository.php';
require_once './app/core/Controller.php';
require_once './app/trait/FormTrait.php';

class ProduitController extends Controller {

    use FormTrait;

    public function create() {
        $ProduitRepo = new ProduitRepository();
        
        // Récupérer toujours les données nécessaires
        $Produits = $ProduitRepo->findAll();
        $images = $ProduitRepo->getImg();
        $data = $this->getAllPostParams();
        $errors = [];
        $selectedProductId = null;
        echo('<script>console.log("test0");</script>');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            echo('<script>console.log("test1");</script>');

            try {
                echo('<script>console.log("test2");</script>');

                $action = $_POST['actions'] ?? '';
                $selectedProductId = $_POST['ListeProduit'] ?? null;

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
                if (empty($data['description'])) {
                    $errors[] = 'La description ne peut pas être vide.';
                }
                if (empty($data['taille'])) {
                    $errors[] = 'La taille ne peut pas être vide.';
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
                    $data['description'],
                    $data['colorPicker'],
                    $data['taille']
                );

                switch ($action) {
                    case 'add':
                        $ProduitRepo->create($Produit);
                        break;
                    case 'update':
                        if (!$selectedProductId) {
                            throw new Exception('Aucun produit sélectionné pour la modification');
                        }
                        $ProduitRepo->updateProduit($Produit, $selectedProductId);
                        break;
                    case 'delete':
                        if (!$selectedProductId) {
                            throw new Exception('Aucun produit sélectionné pour la suppression');
                        }
                        $ProduitRepo->deleteProduit($selectedProductId);
                        break;
                }

                $this->redirectTo('produit.php');
                
            } catch (Exception $e) {
                $errors = explode(', ', $e->getMessage());
            }
        }

        $this->view('/produit/index.html.twig', [
            'Produits' => $Produits,
            'images' => $images,
            'data' => $data,
            'errors' => $errors,
            'selectedProductId' => $selectedProductId
        ]);
    }

    private function checkAuth() {
        $auth = new AuthService();
        if (!$auth->isLoggedIn()) {
            $this->redirectTo('login.php');
        }
    }
}