<?php

declare(strict_types=1);
namespace App\Models;
require_once __DIR__ . '/Database.php';


use App\Models\Database;

class LocalisationModel
{
    private \PDO $pdo;

    public function __construct(Database $db)
    {
        $this->pdo = $db->getConnection();
    }

    public function getLocalisations(): array
    {
        $sql = 'SELECT * FROM Localisation';
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function addLocalisation($adresse, $ville, $departement): int
    {
        $sql = 'INSERT INTO Localisation (adresse, ville, departement) 
                VALUES (:adresse, :ville, :departement)';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':adresse' => $adresse,
            ':ville' => $ville,
            ':departement' => $departement
        ]);

        return (int) $this->pdo->lastInsertId();
    }

    
            

    


}