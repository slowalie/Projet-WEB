<?php

declare(strict_types=1);

namespace App\Models;

require_once __DIR__ . '/Database.php';

class CandidatModel
{
	private \PDO $pdo;

	public function __construct(Database $database)
	{
		$this->pdo = $database->getConnection();
	}

	public function getByUserId(int $userId): ?array
	{
		$sql = 'SELECT u.id_user, u.nom_user, u.prenom_user, u.mail_user,
					   c.titre_profil, c.cv, c.photo, c.add_doc, c.disponibilite
				FROM Utilisateurs u
				LEFT JOIN Candidats c ON c.id_user = u.id_user
				WHERE u.id_user = :id_user
				LIMIT 1';

		$stmt = $this->pdo->prepare($sql);
		$stmt->execute(['id_user' => $userId]);

		$result = $stmt->fetch(\PDO::FETCH_ASSOC);
		if ($result === false) {
			return null;
		}

		return $result;
	}

	public function getApplicationsByUserId(int $userId): array
	{
		$sql = 'SELECT p.id_offres, p.Date_candidature, p.statut,
					   o.nom_offres,
					   e.nom_entreprise, e.logo_entreprise,
					   l.ville
				FROM Postuler p
				INNER JOIN Offres o ON p.id_offres = o.id_offres
				INNER JOIN Entreprises e ON o.id_entreprise = e.id_entreprise
				INNER JOIN Localisation l ON o.id_localisation = l.id_localisation
				WHERE p.id_user = :id_user
				ORDER BY p.Date_candidature DESC';

		$stmt = $this->pdo->prepare($sql);
		$stmt->execute(['id_user' => $userId]);

		return $stmt->fetchAll(\PDO::FETCH_ASSOC);
	}

	public function createIfMissing(int $userId): void
	{
		$sql = 'INSERT INTO Candidats (id_user)
				SELECT :id_user
				WHERE NOT EXISTS (
					SELECT 1 FROM Candidats WHERE id_user = :id_user
				)';

		$stmt = $this->pdo->prepare($sql);
		$stmt->execute(['id_user' => $userId]);
	}

	public function updateProfile(int $userId, array $data): bool
	{
		$this->createIfMissing($userId);

		$stmt = $this->pdo->prepare('UPDATE Candidats
			SET titre_profil = :titre_profil,
				disponibilite = :disponibilite,
				cv = :cv,
				add_doc = :add_doc,
				photo = :photo
			WHERE id_user = :id_user');

		return $stmt->execute([
			'id_user' => $userId,
			'titre_profil' => $data['titre_profil'],
			'disponibilite' => $data['disponibilite'],
			'cv' => $data['cv'],
			'add_doc' => $data['add_doc'],
			'photo' => $data['photo'],
		]);
	}
	

}

