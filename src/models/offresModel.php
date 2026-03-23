<?php

declare(strict_types=1);
namespace App\Models;
require_once __DIR__ . '/Database.php';


use App\models\Database;
use App\Models\EntreprisesModel;
use App\Models\localisationModel;

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

    public function addOffre($nom, $description, $type_contrat, $salaire, $date_debut, $duree, $entreprise, $mission, $note, $secteur, $profil_recherche, $adresse, $ville, $tag, $departement)
{
    // 1. Gérer la Localisation
    $localisationSql = 'SELECT id_localisation FROM Localisation WHERE adresse = :adresse AND ville = :ville AND departement = :departement';
    $localisationStmt = $this->pdo->prepare($localisationSql);
    $localisationStmt->execute([
        ':adresse' => $adresse,
        ':ville' => $ville,
        ':departement' => $departement
    ]);
    $localisationId = $localisationStmt->fetchColumn();

    if (!$localisationId) {
        // Note: Évitez de réinstancier des modèles/DB ici si possible, utilisez des méthodes existantes
        $localisationModel = new localisationModel(new Database('localhost', 'root', 'A2#DevWeb!', 'ideastage_BDD'));
        $localisationId = $localisationModel->addLocalisation($adresse, $ville, $departement);
    }

    // 2. Gérer l'Entreprise
    $entrepriseSql = 'SELECT id_entreprise FROM Entreprises WHERE nom_entreprise = :nom';
    $entrepriseStmt = $this->pdo->prepare($entrepriseSql);
    $entrepriseStmt->execute([':nom' => $entreprise]);
    $entrepriseId = $entrepriseStmt->fetchColumn();

    if (!$entrepriseId) {
        $entreprisesModel = new EntreprisesModel(new Database('localhost', 'root', 'A2#DevWeb!', 'ideastage_BDD'));
        $entrepriseId = $entreprisesModel->addEntreprises($entreprise, '', '', 0);
    }

    // 3. Insérer l'Offre (Correction du nombre de colonnes vs valeurs)
    $sql = 'INSERT INTO Offres (
                nom_offres, description_offres, type_offres, salaire_offres, 
                date_debut, duree_offres, missions, note, 
                secteur_offres, Profil_recherche, id_entreprise, id_localisation
            ) 
            VALUES (
                :nom, :description, :type_contrat, :salaire, 
                :date_debut, :duree, :mission, :note, 
                :secteur, :profil, :id_entreprise, :id_localisation
            )';

    $stmt = $this->pdo->prepare($sql);
    
    return $stmt->execute([
        ':nom'             => $nom,
        ':description'     => $description,
        ':type_contrat'    => $type_contrat,
        ':salaire'         => $salaire,
        ':date_debut'      => $date_debut,
        ':duree'           => $duree,
        ':mission'         => $mission,
        ':note'            => $note,
        ':secteur'         => $secteur,
        ':profil'          => $profil_recherche,
        ':id_entreprise'   => $entrepriseId,
        ':id_localisation' => $localisationId
    ]);
}

    


}