<?php

declare(strict_types=1);

require_once __DIR__ . '/Database.php';

use App\models\Database;

class UserModel
{
    private \PDO $pdo;

    public function __construct(Database $database)
    {
        $this->pdo = $database->getConnection();
    }

    public function existsByEmail(string $email): bool
    {
        $stmt = $this->pdo->prepare('SELECT id_user FROM Utilisateurs WHERE mail_user = :mail_user LIMIT 1');
        $stmt->execute(['mail_user' => $email]);

        return (bool) $stmt->fetch();
    }

    public function createUser(string $nom, string $prenom, string $email, string $passwordHash, string $role): void
    {
        $insertStmt = $this->pdo->prepare('INSERT INTO Utilisateurs (nom_user, prenom_user, mail_user, mdp_user, role_user) VALUES (:nom_user, :prenom_user, :mail_user, :mdp_user, :role_user)');
        $insertStmt->execute([
            'nom_user' => $nom,
            'prenom_user' => $prenom,
            'mail_user' => $email,
            'mdp_user' => $passwordHash,
            'role_user' => $role,
        ]);
    }

    public function findByEmail(string $email): ?array
    {
        $stmt = $this->pdo->prepare('SELECT id_user, mail_user, mdp_user FROM Utilisateurs WHERE mail_user = :mail_user LIMIT 1');
        $stmt->execute(['mail_user' => $email]);
        $user = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($user === false) {
            return null;
        }

        return $user;
    }

    public function getuserbyrole(string $role): array
    {
        $stmt = $this->pdo->prepare('SELECT id_user, nom_user, prenom_user, mail_user FROM Utilisateurs WHERE role_user = :role_user');
        $stmt->execute(['role_user' => $role]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}
