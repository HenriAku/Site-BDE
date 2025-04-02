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
        //$this->checkAuth();
        $ProduitRepo = new ProduitRepository();
        
        $Produits = $ProduitRepo->findAll();
        $images = $ProduitRepo->getImg();
        $data = $this->getAllPostParams();
        $errors = [];
        $index = null;
        $selectedProduct = null;
    

        if (!empty($data)) {
            try {
                $action = $_POST['action'] ?? '';
                // Déplacer la récupération de l'index ici, après la vérification de la méthode POST
                $index = $_POST['prodId'];
    
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
    
                // Gestion de l'image
                $imagePath = null;
                if (!empty($_FILES['image']['name'])) {
                    $imagePath = $this->handleImageUpload($_FILES['image']);
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
                        $newId = $ProduitRepo->create($Produit);
                        if ($imagePath) {
                            $ProduitRepo->saveImage($newId, basename($imagePath));
                        }
                        break;
                    case 'update':
                        if ($index <= 0) {
                            throw new Exception('Aucun produit sélectionné pour la mise à jour');
                        }
                        $success = $ProduitRepo->updateProduit($Produit, $index);
                        if ($imagePath) {
                            $ProduitRepo->saveImage($index, basename($imagePath));
                        }
                        if (!$success) {
                            throw new Exception('Échec de la mise à jour du produit');
                        }
                        break;
                    case 'delete':
                        if ($index <= 0) {
                            throw new Exception('Aucun produit sélectionné pour la suppression');
                        }
                        $success = $ProduitRepo->deleteProduit($index);
                        if (!$success) {
                            throw new Exception('Échec de la suppression du produit');
                        }
                        break;
                }
    
                // Ajouter un message de succès
                $_SESSION['message'] = 'Opération effectuée avec succès';
                $this->redirectTo('produit.php');
                
            } catch (Exception $e) {
                $errors = explode(', ', $e->getMessage());
            }
        }
    
        // Charger le produit sélectionné si un index est défini
        // Même si c'est une requête GET, pour pré-remplir le formulaire
        $index = isset($_GET['index']) ? (int)$_GET['index'] : 
                 (isset($index) ? $index : null);
        
        if ($index) {
            $selectedProduct = $ProduitRepo->findById($index);
        }
    
        $this->view('/produit/index.html.twig', [
            'Produits' => $Produits,
            'images' => $images,
            'index' => $index,
            'selectedProduct' => $selectedProduct,
            'nom' => $nom ?? ($selectedProduct->name ?? ''),
            'description' => $description ?? ($selectedProduct->description ?? ''),
            'prix' => $prix ?? ($selectedProduct->price ?? ''),
            'stock' => $stock ?? ($selectedProduct->stock ?? ''),
            'categorie' => $categorie ?? ($selectedProduct->categorie ?? ''),
            'taille' => $taille ?? ($selectedProduct->taille ?? ''),
            'colorPicker' => $colorPicker ?? ($selectedProduct->color ?? '#ff0000'),
            'errors' => $errors,
            'message' => $_SESSION['message'] ?? null
        ]);
        
        // Effacer le message après l'avoir affiché
        unset($_SESSION['message']);
    }

    private function handleImageUpload($file) {
        $targetDir = "../asset/images/produit/";
        $targetFile = $targetDir . basename($file["name"]);
        $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

        // Vérifications de sécurité
        $check = getimagesize($file["tmp_name"]);
        if ($check === false) {
            throw new Exception("Le fichier n'est pas une image.");
        }

        if (file_exists($targetFile)) {
            throw new Exception("Un fichier avec ce nom existe déjà.");
        }

        if ($file["size"] > 500000) {
            throw new Exception("Le fichier est trop volumineux.");
        }

        if (!in_array($imageFileType, ["jpg", "png", "jpeg", "gif"])) {
            throw new Exception("Seuls les fichiers JPG, JPEG, PNG & GIF sont autorisés.");
        }

        if (!move_uploaded_file($file["tmp_name"], $targetFile)) {
            throw new Exception("Une erreur est survenue lors du téléchargement de l'image.");
        }

        return $targetFile;
    }

    private function checkAuth() {
        $auth = new AuthService();
        if (!$auth->isLoggedIn()) {
            $this->redirectTo('login.php');
        }
    }
}