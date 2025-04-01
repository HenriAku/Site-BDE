<?php

require_once './app/core/Controller.php';
require_once './app/repositories/UserRepository.php';
require_once './app/trait/FormTrait.php';
require_once './app/trait/AuthTrait.php';
require_once './app/services/AuthService.php';

class UserController extends Controller {

    use FormTrait;
    use AuthTrait; 

    public function index() {
        $repository = new UserRepository();
        $users = $repository->findAll();

        // Ensuite, affiche la vue
        $this->view('/user/index.html.twig',  ['users' => $users]);
    }

    public function create() {
        $data = $this->getAllPostParams(); // Récupération des données soumises
        $errors = [];

        if (!empty($data)) {
            try {
                $errors = [];

                // Validation des données
                if (empty($data['firstname'])) {
                    $errors[] = 'Le prénom est requis.';
                }
                // Validation des données
                if (empty($data['lastname'])) {
                    $errors[] = 'Le nom est requis.';
                }
                if (empty($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                    $errors[] = 'Un email valide est requis.';
                }
                if (empty($data['password']) || strlen($data['password']) < 6) {
                    $errors[] = 'Le mot de passe doit contenir au moins 6 caractères.';
                }

                if (empty($data['conf_password']) || !($data['conf_password']) === ($data['password'])) {
                    $errors[] = 'Le mot de passe doit être identique.';
                }

                if (empty($data['netu'])) {
                    $errors[] = 'Le numéro étudiant est requis.';
                }

                if (!empty($errors)) {
                    throw new Exception(implode(', ', $errors));
                }

                // Création de l'objet utilisateur
                $hashedPassword = $this->hash($data['password']);
                $user = new User(null, $data['firstname'], $data['lastname'], $data['email'], $hashedPassword, $data['netu']);


                // Sauvegarde dans la base de données
                $userRepo = new UserRepository();
                if (!$userRepo->create($user)) 
                {
                    throw new Exception('Erreur lors de l\'enregistrement de l\'utilisateur.');
                }


                $this->redirectTo('index.php'); // Redirection après création
            } catch (Exception $e) {
                $errors = explode(', ', $e->getMessage()); // Récupération des erreurs
            }
        }

        // Affichage du formulaire
        $this->view('/user/form.html.twig',  [
            'data' => $data,
            'errors' => $errors,
        ]);
    }

    public function update()
    {
        $id = $this->getQueryParam('id');

        if ($id === null) {
                throw new Exception('User ID is required.');
        }
        $repository = new UserRepository();
        $user = $repository->findById($id);

        if ($user === null) {
            throw new Exception('User not found');
        }

        $data = array_merge([
            'firstname'=>$user->getFirstname(),
            'lastname'=>$user->getLastname(),
            'email'=>$user->getEmail(),
        ],$this->getAllPostParams()); //Get submitted data
        $errors = [];

        if (!empty($this->getAllPostParams())) {
            try {
                $errors = [];

                // Data validation
                if (empty($data['firstname'])) {
                    $errors[] = 'Le prénom est requis.';
                }
                if (empty($data['lastname'])) {
                    $errors[] = 'Le nom est requis.';
                }
                if (empty($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                    $errors[] = 'Un email valide est requis.';
                }
                if (!empty($data['password']) && strlen($data['password']) < 6) {
                    $errors[] = 'Le mot de passe doit contenir au moins 6 caractères.';
                }

                if (!empty($errors)) {
                    throw new Exception(implode(', ', $errors));
                }

                // Update user object
                $user->setFirstname($data['firstname']);
                $user->setLastname($data['lastname']);
                $user->setEmail($data['email']);

                // If password field is not empty, then update the password.
                if (!empty($data['password'])) {
                    $hashedPassword = $this->hash($data['password']);
                    $user->setPassword($hashedPassword);
                }

                // Save to database
                if (!$repository->update($user)) {
                    throw new Exception('Error updating the user.');
                }

                $this->redirectTo('index.php'); // Redirect after update

            } catch (Exception $e) {
                $errors = explode(', ', $e->getMessage()); // Error retrieval
            }
        }

        // Display update form
        $this->view('/user/form.html.twig',  ['data' => $data, 'errors' => $errors, 'id' => $id]);
    }
    public function delete() {
        try {
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
                $repository = new UserRepository(); // Ajoutez cette ligne
                $userId = (int) $_POST['id'];
                $success = $repository->delete($userId); // Utilisez $repository au lieu de $this->userRepository
                if ($success) {
                    http_response_code(200);
                    echo json_encode(['success' => true, 'message' => 'Utilisateur supprimé']);
                } else {
                    http_response_code(404);
                    echo json_encode(['success' => false, 'error' => 'Utilisateur non trouvé']);
                }
            } else {
                http_response_code(400);
                echo json_encode(['success' => false, 'error' => 'Requête invalide']);
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'error' => 'Erreur serveur : ' . $e->getMessage()]);
        }
        exit;
    }

    public function profil()
    {

        $authServ = new AuthService();

        if($authServ->isLoggedIn())
        {
            echo "<script>console.log('sa passe');</script>";
            $this->view('/user/form.html.twig',  ['data' => $data, 'errors' => $errors, 'id' => $id]);
        }
        else
        {
            echo "<script>console.log('sa passe pas');</script>";
        }



    }
}
