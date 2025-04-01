<?php
require_once './app/trait/AuthTrait.php';
require_once './app/repositories/UserRepository.php'; 
class AuthService {

    use AuthTrait;

    public function getUser():?User
    {
        if(session_status() == PHP_SESSION_NONE)
            session_start();
        return unserialize($_SESSION['user']);
    }

    public function setUser(User $user): void
    {
        if(session_status() == PHP_SESSION_NONE)
            session_start();
        $_SESSION['user'] = serialize($user);
    }

    public function updatePassword(string $mdp, string $netu)
    {

    }

    public function logout(): void
    {
        echo "<script>console.log('déconnexion');</script>";
        
        session_start();
        session_unset();
        session_destroy();
        header("Location: index.php");
        exit();
    }

    public function isLoggedIn(): bool {
        if(session_status() == PHP_SESSION_NONE)
            session_start();
        return isset($_SESSION['user']);
    }
}
