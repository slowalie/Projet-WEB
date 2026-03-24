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

    public function getEntreprises(): array
    {
        $sql = 'SELECT * FROM Entreprises
                INNER JOIN Offres ON Entreprises.id_entreprise = Offres.id_entreprise
                INNER JOIN Localisation ON Offres.id_localisation = Localisation.id_localisation';
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
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

}

