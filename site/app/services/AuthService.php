<?php
require_once './app/trait/AuthTrait.php';
require_once './app/repositories/UserRepository.php'; 
class AuthService {

    use AuthTrait;

    public function getUser(): ?User
    {
        if(session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        return isset($_SESSION['user']) ? unserialize($_SESSION['user']) : null;
    }

    public function setUser(User $user): void
    {
        if(session_status() == PHP_SESSION_NONE)
            session_start();
        $_SESSION['user'] = serialize($user);
    }



    public function logout(): void
    {
        session_start();
    
        // Détruit toutes les données de session
        $_SESSION = [];
        
        // Supprime le cookie de session
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params["path"],
                $params["domain"],
                $params["secure"],
                $params["httponly"]
            );
        }
        
        session_destroy();
        header("Location: /");
        exit();
    }

    public function isLoggedIn(): bool {
        if(session_status() == PHP_SESSION_NONE)
            session_start();
        return isset($_SESSION['user']);
    }
}
