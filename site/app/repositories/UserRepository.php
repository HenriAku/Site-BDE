<?php
require_once './app/core/Repository.php';
require_once './app/entities/User.php';

class UserRepository {
    private $pdo;

    public function __construct() {
        $this->pdo = Repository::getInstance()->getPDO();
    }

    public function findAll(): array {
        $stmt = $this->pdo->query('SELECT * FROM "adherent" where estconnecte = true');
        $users = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $users[] = $this->createUserFromRow($row);
        }
        return $users;
    }

    public function findAllAdmin(): array {
        $stmt = $this->pdo->query('SELECT * FROM "adherent" where estconnecte = true AND admin = true ');
        $admins = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $admins[] = $this->createUserFromRow($row);
        }
        return $admins;
    }

    private function createUserFromRow(array $row): User 
    {
        // Conversion robuste pour estadmin
        $estAdmin = false;
        if (isset($row['admin'])) {
            $estAdmin = ($row['admin'] === true || $row['admin'] === 1 || $row['admin'] === '1' || strtolower($row['admin']) === 'true');
        }

        return new User(
            $row['n_etu'],
            $row['prenom_etu'],
            $row['nom_etu'],
            $row['mail_etu'],
            $row['mdp_etu'],
            $row['num_etu'],
            (bool)($row['estvalide'] ?? false),
            $estAdmin,
            (bool)($row['estconnecte'] ?? true)

        );
    }

    public function create(User $user): bool 
    {
        $stmt = $this->pdo->prepare('INSERT INTO adherent (num_etu, nom_etu, prenom_etu, admin, mdp_etu, mail_etu, estvalide) 
                                    VALUES (:num_etu, :lastname, :firstname, :admin, :password, :email, :estvalide)');
        return $stmt->execute([
            'num_etu' => $user->getNetu(),
            'firstname' => $user->getFirstname(),
            'lastname' => $user->getLastname(),
            'admin' => $user->getAdmin() ? 1 : 0, // Convertit booléen en 1/0
            'password' => $user->getPassword(),
            'email' => $user->getEmail(),
            'estvalide' => $user->getValide() ? 1 : 0
        ]);
    }
    
    

    public function update(User $user): bool
    {
        $stmt = $this->pdo->prepare('UPDATE "User" SET firstname = :newFirstname, lastname = :newLastname, email = :newEmail, password = :newPassword WHERE id = :id');
        return $stmt->execute([
            'newFirstname' => $user->getFirstname(),
            'newLastname' => $user->getLastname(),
            'newEmail' => $user->getEmail(),
            'newPassword' => $user->getPassword(),
            'id' => $user->getId(),
        ]);
    }

    public function updatePassword(int $userId, string $hashedPassword): bool
    {
        $stmt = $this->pdo->prepare('UPDATE adherent SET mdp_etu = ? WHERE n_etu = ?');
        return $stmt->execute([$hashedPassword, $userId]);
    } 

    public function validate(int $userId): bool
    {
        $stmt = $this->pdo->prepare('UPDATE adherent SET estvalide = ? WHERE n_etu = ?');
        return $stmt->execute([true, $userId]);
    } 

    public function delete(int $id): bool {
        if (!$this->pdo) {
            throw new Exception('Connexion PDO non initialisée');
        }
        $stmt = $this->pdo->prepare('DELETE FROM adherent WHERE n_etu = :id');
        $stmt->execute(['id' => $id]);
        $rowsAffected = $stmt->rowCount();
        return $rowsAffected > 0;
    }

    
    public function supprAdmin($id) : bool
    {
        $stmt = $this->pdo->prepare('UPDATE adherent SET admin = false WHERE n_etu = :id');
        return $stmt->execute(['id' => $id,]);
    }

    public function ajoutAdmin($id) 
    {
        $stmt = $this->pdo->prepare('UPDATE adherent SET admin = true WHERE n_etu = :id');
        return $stmt->execute(['id' => $id,]);
    }

    public function findByEmail(string $email): ?User {
        $stmt = $this->pdo->prepare('SELECT * FROM "adherent" WHERE mail_etu = :email');
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if($user)
            return $this->createUserFromRow($user);
        return null;
    }

    public function findById(int $id): ?User {
        $stmt = $this->pdo->prepare('SELECT * FROM "adherent" WHERE n_etu = :id');
        $stmt->execute(['id' => $id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($user) {
            return $this->createUserFromRow($user);
        }
        return null;
    }
}
