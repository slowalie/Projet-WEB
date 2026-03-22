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

    public function filtreoffres($filtres){
        $sql = 'SELECT * FROM Offres
                INNER JOIN Entreprises ON Offres.id_entreprise = Entreprises.id_entreprise
                INNER JOIN Localisation ON Offres.id_localisation = Localisation.id_localisation
                WHERE 1=1';
        
        if (!empty($filtres['type_offres'])) {
            $sql .= ' AND Offres.type_offres = :type_offres';
        }
        if (!empty($filtres['secteur_offres'])) {
            $sql .= ' AND Offres.secteur_offres = :secteur_offres';
        }
        if (!empty($filtres['ville'])) {
            $sql .= ' AND Offres.ville = :ville';
        }

        $stmt = $this->pdo->prepare($sql);
        
        if (!empty($filtres['type_offres'])) {
            $stmt->bindValue(':type_offres', $filtres['type_offres']);
        }
        if (!empty($filtres['secteur_offres'])) {
            $stmt->bindValue(':secteur_offres', $filtres['secteur_offres']);
        }
        if (!empty($filtres['ville'])) {
            $stmt->bindValue(':ville', $filtres['ville']);
        }

        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }


}