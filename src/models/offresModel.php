<?php

declare(strict_types=1);
namespace App\Models;
require_once __DIR__ . '/Database.php';
require_once __DIR__ . '/entreprisesModel.php';
require_once __DIR__ . '/localisationModel.php';


use App\Models\Database;
use App\Models\EntreprisesModel;
use App\Models\LocalisationModel;

class OffresModel
{
    private \PDO $pdo;

    public function __construct(Database $db)
    {
        $this->pdo = $db->getConnection();
    }

    public function getOffres(
        ?string $search = null,
        ?string $ville = null,
        ?string $secteur = null,
        ?string $duree = null,
        ?string $type = null
    ): array
    {
        $sql = 'SELECT * FROM Offres
                INNER JOIN Entreprises ON Offres.id_entreprise = Entreprises.id_entreprise
                INNER JOIN Localisation ON Offres.id_localisation = Localisation.id_localisation';

        $conditions = [];
        $params = [];

        if ($search !== null && $search !== '') {
            $conditions[] = '(Offres.nom_offres LIKE :search OR Entreprises.nom_entreprise LIKE :search)';
            $params[':search'] = '%' . $search . '%';
        }

        if ($ville !== null && $ville !== '' && strtolower($ville) !== 'toutes') {
            $conditions[] = 'Localisation.ville = :ville';
            $params[':ville'] = $ville;
        }

        if ($secteur !== null && $secteur !== '' && strtolower($secteur) !== 'tous') {
            $conditions[] = 'Offres.secteur_offres = :secteur';
            $params[':secteur'] = $secteur;
        }

        if ($duree !== null && $duree !== '' && strtolower($duree) !== 'toutes') {
            $conditions[] = 'Offres.duree_offres = :duree';
            $params[':duree'] = $duree;
        }

        if ($type !== null && $type !== '' && strtolower($type) !== 'tous') {
            $conditions[] = 'Offres.type_offres = :type';
            $params[':type'] = $type;
        }

        if (!empty($conditions)) {
            $sql .= ' WHERE ' . implode(' AND ', $conditions);
        }

        $sql .= ' ORDER BY Offres.id_offres DESC';

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getOffreById(int $id): ?array
    {
        $sql = 'SELECT * FROM Offres
                INNER JOIN Entreprises ON Offres.id_entreprise = Entreprises.id_entreprise
                INNER JOIN Localisation ON Offres.id_localisation = Localisation.id_localisation
                WHERE Offres.id_offres = :id';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $id]);
        $offre = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $offre ?: null;
    }

    public function submitApplication(int $offerId, int $userId, ?string $cvPath, string $lettreMotivationPath): bool
    {
        $this->pdo->beginTransaction();

        try {
            $ensureCandidate = $this->pdo->prepare('INSERT INTO Candidats (id_user)
                SELECT :id_user
                WHERE NOT EXISTS (
                    SELECT 1 FROM Candidats WHERE id_user = :id_user
                )');
            $ensureCandidate->execute(['id_user' => $userId]);

            if ($cvPath !== null && $cvPath !== '') {
                $updateCv = $this->pdo->prepare('UPDATE Candidats SET cv = :cv WHERE id_user = :id_user');
                $updateCv->execute([
                    'id_user' => $userId,
                    'cv' => $cvPath,
                ]);

                $checkCvStmt = $this->pdo->prepare('SELECT cv FROM Candidats WHERE id_user = :id_user LIMIT 1');
                $checkCvStmt->execute(['id_user' => $userId]);
                $savedCvPath = $checkCvStmt->fetchColumn();

                if (!is_string($savedCvPath) || $savedCvPath !== $cvPath) {
                    throw new \RuntimeException('CV path not persisted in Candidats');
                }
            }

            $applyStmt = $this->pdo->prepare('INSERT INTO Postuler (id_offres, id_user, Date_candidature, statut, lettre_motivation)
                VALUES (:id_offres, :id_user, NOW(), :statut, :lettre_motivation)
                ON DUPLICATE KEY UPDATE
                    Date_candidature = NOW(),
                    statut = VALUES(statut),
                    lettre_motivation = VALUES(lettre_motivation)');

            $applyStmt->execute([
                'id_offres' => $offerId,
                'id_user' => $userId,
                'statut' => 'En attente',
                'lettre_motivation' => $lettreMotivationPath,
            ]);

            $checkStmt = $this->pdo->prepare('SELECT 1 FROM Postuler WHERE id_offres = :id_offres AND id_user = :id_user LIMIT 1');
            $checkStmt->execute([
                'id_offres' => $offerId,
                'id_user' => $userId,
            ]);

            if ($checkStmt->fetchColumn() === false) {
                throw new \RuntimeException('Application row not persisted in Postuler');
            }

            $checkLettreStmt = $this->pdo->prepare('SELECT lettre_motivation FROM Postuler WHERE id_offres = :id_offres AND id_user = :id_user LIMIT 1');
            $checkLettreStmt->execute([
                'id_offres' => $offerId,
                'id_user' => $userId,
            ]);
            $savedLettrePath = $checkLettreStmt->fetchColumn();

            if (!is_string($savedLettrePath) || $savedLettrePath !== $lettreMotivationPath) {
                throw new \RuntimeException('Motivation letter path not persisted in Postuler');
            }

            $this->pdo->commit();
            return true;
        } catch (\Throwable $exception) {
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }

            throw $exception;
        }
    }
    public function deleteOffre(int $id): bool
    {
        $sql = 'DELETE FROM Offres WHERE id_offres = :id';
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([':id' => $id]);
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
        $localisationModel = new LocalisationModel(new Database('localhost', 'root', 'A2#DevWeb!', 'ideastage_BDD'));
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