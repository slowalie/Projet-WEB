<?php

declare(strict_types=1);
namespace App\Models;
require_once __DIR__ . '/Database.php';


use App\Models\Database;

class EntreprisesModel
{
    private \PDO $pdo;

    public function __construct(Database $db)
    {
        $this->pdo = $db->getConnection();
    }

    public function getEntreprises(?string $search = null, ?string $ville = null): array
    {
        $sql = 'SELECT DISTINCT e.*
                FROM Entreprises e
                LEFT JOIN Offres o ON o.id_entreprise = e.id_entreprise
                LEFT JOIN Localisation l ON l.id_localisation = o.id_localisation';

        $conditions = [];
        $params = [];

        if ($search !== null && $search !== '') {
            $conditions[] = '(e.nom_entreprise LIKE :search OR e.description_entreprise LIKE :search)';
            $params[':search'] = '%' . $search . '%';
        }

        if ($ville !== null && $ville !== '' && strtolower($ville) !== 'toutes') {
            $conditions[] = 'l.ville = :ville';
            $params[':ville'] = $ville;
        }

        if (!empty($conditions)) {
            $sql .= ' WHERE ' . implode(' AND ', $conditions);
        }

        $sql .= ' ORDER BY e.nom_entreprise ASC';

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getVilles(): array
    {
        $sql = 'SELECT DISTINCT l.ville
                FROM Localisation l
                INNER JOIN Offres o ON o.id_localisation = l.id_localisation
                ORDER BY l.ville ASC';

        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(\PDO::FETCH_COLUMN) ?: [];
    }

    public function addEntreprises($nom, $logo, $description, $note): int
    {
        $sql = 'INSERT INTO Entreprises (nom_entreprise, logo_entreprise, description_entreprise, note_entreprise) 
                VALUES (:nom, :logo, :description, :note)';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':nom' => $nom,
            ':logo' => $logo,
            ':description' => $description,
            ':note' => $note
        ]);

        return (int) $this->pdo->lastInsertId();
    }

    public function getEntrepriseById(int $idEntreprise): ?array
    {
        $sql = 'SELECT * FROM Entreprises WHERE id_entreprise = :id_entreprise LIMIT 1';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id_entreprise' => $idEntreprise]);
        $entreprise = $stmt->fetch(\PDO::FETCH_ASSOC);

        return $entreprise ?: null;
    }

    public function updateEntreprise(int $idEntreprise, array $data): bool
    {
        $sql = 'UPDATE Entreprises
                SET nom_entreprise = :nom_entreprise,
                    logo_entreprise = :logo_entreprise,
                    description_entreprise = :description_entreprise,
                    note_entreprise = :note_entreprise
                WHERE id_entreprise = :id_entreprise';

        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':id_entreprise' => $idEntreprise,
            ':nom_entreprise' => $data['nom_entreprise'],
            ':logo_entreprise' => $data['logo_entreprise'],
            ':description_entreprise' => $data['description_entreprise'],
            ':note_entreprise' => $data['note_entreprise'],
        ]);
    }

    public function deleteEntreprise(int $idEntreprise): bool
    {
        $sql = 'DELETE FROM Entreprises WHERE id_entreprise = :id_entreprise';
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([':id_entreprise' => $idEntreprise]);
    }

}

