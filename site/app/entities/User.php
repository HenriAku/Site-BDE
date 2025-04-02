<?php
class User {
   
    public function __construct(
        private ?int $id, 
        private string $firstname,
        private string $lastname,
        private string $email, 
        private ?string $password, 
        private ?string $netu, 
        private bool $estvalide = false, 
        private bool $estadmin = false
    ) {}

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstname(): string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): void
    {
        $this->firstname = $firstname;
    }

    public function getLastname(): string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): void
    {
        $this->lastname = $lastname;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(?string $password): void
    {
        $this->password = $password;
    }

    public function getNetu(): ?string
    {
        return $this->netu;
    }

    public function setNetu(?string $netu): void
    {
        $this->netu = $netu;
    }

    public function getValide(): bool
    {
        return $this->estvalide;
    }

    public function setValide(bool $estvalide): void
    {
        $this->estvalide = $estvalide;
    }

    public function getAdmin(): bool
    {
        return $this->estadmin;
    }

    public function setAdmin(bool $estadmin): void
    {
        $this->estadmin = $estadmin;
    }

    public function __serialize(): array
    {
        return [
            'id' => $this->id,
            'firstname' => $this->firstname,
            'lastname' => $this->lastname,
            'email' => $this->email,
            'password' => $this->password,
            'netu' => $this->netu,
            'estvalide' => $this->estvalide,
            'estadmin' => $this->estadmin
        ];
    }

    public function __unserialize(array $data): void
    {
        $this->id = $data['id'] ?? null;
        $this->firstname = $data['firstname'] ?? '';
        $this->lastname = $data['lastname'] ?? '';
        $this->email = $data['email'] ?? '';
        $this->password = $data['password'] ?? null;
        $this->netu = $data['netu'] ?? null;
        $this->estvalide = $data['estvalide'] ?? false;
        $this->estadmin = $data['estadmin'] ?? false;
    }
}
?>
