<?php

declare(strict_types=1);
namespace App\Models;
require_once __DIR__ . '/Database.php';


use App\models\Database;

class OffresModel
{
    private \pdo $pdo;

    public function __construct(Database $db)
    {
        $this->pdo = $db->getConnection();
    }

    public function getOffres(): array
    {
        $sql = 'SELECT * FROM Offres
                INNER JOIN Entreprises ON Offres.id_entreprise = Entreprises.id_entreprise
                INNER JOIN Localisation ON Offres.id_localisation = Localisation.id_localisation';             
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function searchOffres(string $keyword): array
    {
        $sql = 'SELECT * FROM Offres WHERE nom_offres LIKE :keyword OR titre_offre LIKE :keyword OR description_offre LIKE :keyword';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['keyword' => '%' . $keyword . '%']);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }


}