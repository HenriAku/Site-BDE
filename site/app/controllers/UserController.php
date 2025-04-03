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
        
        $servUser = new AuthService();
        if($servUser->getUser() === null)
        {
            $this->view('/user/index.html.twig', ['users' => $users, 'admin' => null]);

        }else{
            $user = $servUser->getUser();
            $perm = $user->getAdmin();
            
            $this->view('/user/index.html.twig', ['users' => $users, 'admin' => $perm]);
        }
        
    }

    public function admin()
    {
        $repository = new UserRepository();
        $admins = $repository->findAllAdmin();
        $users = $repository->findAll();
        
        $servUser = new AuthService();
        if($servUser->getUser() === null)
        {
            $this->view('/user/gestion.html.twig', ['users' => $users, 'admins' => $admins, 'admin' => null]);

        }else{
            $user = $servUser->getUser();
            $perm = $user->getAdmin();
            
            $this->view('/user/gestion.html.twig', ['users' => $users, 'admins' => $admins, 'admin' => $perm]);
        }
    }

    public function create() 
    {
        echo "<script>console.log('test');</script>";

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

                if (empty($data['conf_password']) || $data['conf_password'] !== $data['password']) {
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
                $user = new User(null, $data['firstname'], $data['lastname'], $data['email'], $hashedPassword, $data['netu'], false, false, true);


                // Sauvegarde dans la base de données
                $userRepo = new UserRepository();
                if (!$userRepo->create($user)) 
                {
                    throw new Exception('Erreur lors de l\'enregistrement de l\'utilisateur.');
                }


                $this->redirectTo('login.php'); // Redirection après création
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
    public function delete($id) 
    {
        $userRepo = new UserRepository();
        return $userRepo->delete($id);
    }

    public function supprAdmin($id) 
    {
        $userRepo = new UserRepository();
        return $userRepo->supprAdmin($id);
    }

    public function ajoutAdmin($id) 
    {
        $userRepo = new UserRepository();
        return $userRepo->ajoutAdmin($id);
    }

    public function profil()
    {

        $authServ = new AuthService();


        if($authServ->isLoggedIn())
        {
            $user = $authServ->getUser();
            //$servUser = new AuthService();
            if($authServ->getUser() === null)
            {
                $this->view('/user/profil.html.twig', ['user' => $user,'admin' => null]);
    
            }else{
                $perm = $user->getAdmin();
                
                $this->view('/user/profil.html.twig', ['user' => $user,'admin' => $perm]);
            }
        }
        else
        {
            $this->redirectTo('login.php');
        }



    }

    public function getUser():?User
    {
        $authServ = new AuthService();

        return $authServ->getUser();

    }

    public function updatePassword(string $newPassword, string $netu): bool
    {
        // Hashage sécurisé
        $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);
        
        try {
            $userRepo = new UserRepository();
            return $userRepo->updatePassword($netu, $hashedPassword);
        } catch (PDOException $e) {
            error_log("Erreur DB: " . $e->getMessage());
            return false;
        }
    }

    public function validate(int $userId): bool
    {
        $userRepo = new UserRepository();
        return $userRepo->validate($userId);
    } 

    
}
