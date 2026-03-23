<?php

declare(strict_types=1);
namespace App\Models;
require_once __DIR__ . '/Database.php';


use App\models\Database;

class localisationModel
{
    private \pdo $pdo;

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

    public function addlocalisation( $adresse, $ville, $departement)
    {
        $sql = 'INSERT INTO Localisation (adresse, ville, departement) 
                VALUES (:adresse, :ville, :departement)';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':adresse' => $adresse,
            ':ville' => $ville,
            ':departement' => $departement
        ]);
    }

    
            

    


}