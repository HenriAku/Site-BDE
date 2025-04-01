<?php
require_once './app/core/Repository.php';
require_once './app/entities/User.php';

class UserRepository {
    private $pdo;

    public function __construct() {
        $this->pdo = Repository::getInstance()->getPDO();
    }

    public function findAll(): array {
        $stmt = $this->pdo->query('SELECT * FROM "adherent"');
        $users = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $users[] = $this->createUserFromRow($row);
        }
        return $users;
    }

    private function createUserFromRow(array $row): User
    {
        return new User($row['n_etu'],  $row['nom_etu'], $row['prenom_etu'], $row['mail_etu'], $row['mdp_etu'], $row['num_etu']);
    }

    public function create(User $user): bool 
    {
        $stmt = $this->pdo->prepare('INSERT INTO adherent (num_etu, nom_etu, prenom_etu, admin, mdp_etu, mail_etu) 
                                     VALUES (:num_etu, :firstname, :lastname, false, :password, :email)');
        return $stmt->execute([
            'num_etu' => $user->getNetu(),
            'firstname' => $user->getFirstname(),
            'lastname' => $user->getLastname(),
            'email' => $user->getEmail(),
            'password' => $user->getPassword(),
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
    public function delete(int $id): bool {
        if (!$this->pdo) {
            throw new Exception('Connexion PDO non initialisée');
        }
        $stmt = $this->pdo->prepare('DELETE FROM adherent WHERE n_etu = :id');
        $stmt->execute(['id' => $id]);
        $rowsAffected = $stmt->rowCount();
        return $rowsAffected > 0;
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
        $stmt = $this->pdo->prepare('SELECT * FROM "User" WHERE id = :id');
        $stmt->execute(['id' => $id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if($user)
            return $this->createUserFromRow($user);
        return null;
    }
}
