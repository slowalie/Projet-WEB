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

	public function getAllCandidates(): array
	{
		$sql = 'SELECT u.id_user, u.nom_user, u.prenom_user, u.mail_user,
					   c.titre_profil, c.disponibilite,
					   COALESCE(app_counts.applications_count, 0) AS applications_count,
					   COALESCE(fav_counts.favorites_count, 0) AS favorites_count
				FROM Candidats c
				INNER JOIN Utilisateurs u ON u.id_user = c.id_user
				LEFT JOIN (
					SELECT p.id_user, COUNT(*) AS applications_count
					FROM Postuler p
					GROUP BY p.id_user
				) app_counts ON app_counts.id_user = c.id_user
				LEFT JOIN (
					SELECT af.id_user, COUNT(*) AS favorites_count
					FROM Ajouter_favoris af
					GROUP BY af.id_user
				) fav_counts ON fav_counts.id_user = c.id_user
				ORDER BY u.nom_user ASC, u.prenom_user ASC';

		$stmt = $this->pdo->query($sql);
		return $stmt->fetchAll(\PDO::FETCH_ASSOC);
	}

	public function getfavorites(int $userId): array
	{
		$sql = 'SELECT o.id_offres, o.nom_offres, o.type_offres, o.tag,
					   e.nom_entreprise, e.logo_entreprise,
					   l.ville
				FROM Ajouter_favoris af
				INNER JOIN Offres o ON af.id_offres = o.id_offres
				INNER JOIN Entreprises e ON o.id_entreprise = e.id_entreprise
				INNER JOIN Localisation l ON o.id_localisation = l.id_localisation
				WHERE af.id_user = :id_user
				ORDER BY o.id_offres DESC';

		$stmt = $this->pdo->prepare($sql);
		$stmt->execute(['id_user' => $userId]);
		return $stmt->fetchAll(\PDO::FETCH_ASSOC);
	}

	public function isFavorite(int $userId, int $offerId): bool
	{
		$sql = 'SELECT 1 FROM Ajouter_favoris WHERE id_user = :id_user AND id_offres = :id_offres LIMIT 1';
		$stmt = $this->pdo->prepare($sql);
		$stmt->execute([
			'id_user' => $userId,
			'id_offres' => $offerId,
		]);

		return $stmt->fetchColumn() !== false;
	}

	public function addFavorite(int $userId, int $offerId): bool
	{
		$this->createIfMissing($userId);

		$sql = 'INSERT INTO Ajouter_favoris (id_offres, id_user, Etat)
				VALUES (:id_offres, :id_user, 1)
				ON DUPLICATE KEY UPDATE Etat = VALUES(Etat)';

		$stmt = $this->pdo->prepare($sql);
		return $stmt->execute([
			'id_offres' => $offerId,
			'id_user' => $userId,
		]);
	}

	public function removeFavorite(int $userId, int $offerId): bool
	{
		$sql = 'DELETE FROM Ajouter_favoris WHERE id_offres = :id_offres AND id_user = :id_user';
		$stmt = $this->pdo->prepare($sql);
		return $stmt->execute([
			'id_offres' => $offerId,
			'id_user' => $userId,
		]);
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

	public function updateCandidateByPilot(
		int $candidateId,
		string $nom,
		string $prenom,
		string $email,
		?string $titreProfil,
		?string $disponibilite
	): bool|string {
		$existsStmt = $this->pdo->prepare('SELECT id_user FROM Candidats WHERE id_user = :id_user LIMIT 1');
		$existsStmt->execute(['id_user' => $candidateId]);
		if ($existsStmt->fetchColumn() === false) {
			return 'not_found';
		}

		$emailStmt = $this->pdo->prepare('SELECT id_user FROM Utilisateurs WHERE mail_user = :mail_user AND id_user <> :id_user LIMIT 1');
		$emailStmt->execute([
			'mail_user' => $email,
			'id_user' => $candidateId,
		]);
		if ($emailStmt->fetchColumn() !== false) {
			return 'email_exists';
		}

		$this->pdo->beginTransaction();

		try {
			$userStmt = $this->pdo->prepare('UPDATE Utilisateurs
				SET nom_user = :nom_user,
					prenom_user = :prenom_user,
					mail_user = :mail_user
				WHERE id_user = :id_user');

			$userStmt->execute([
				'nom_user' => $nom,
				'prenom_user' => $prenom,
				'mail_user' => $email,
				'id_user' => $candidateId,
			]);

			$candidatStmt = $this->pdo->prepare('UPDATE Candidats
				SET titre_profil = :titre_profil,
					disponibilite = :disponibilite
				WHERE id_user = :id_user');

			$candidatStmt->execute([
				'titre_profil' => $titreProfil,
				'disponibilite' => $disponibilite,
				'id_user' => $candidateId,
			]);

			$this->pdo->commit();
			return true;
		} catch (\Throwable $exception) {
			if ($this->pdo->inTransaction()) {
				$this->pdo->rollBack();
			}

			return false;
		}
	}

	public function deleteCandidateByPilot(int $candidateId): bool|string
	{
		$existsStmt = $this->pdo->prepare('SELECT id_user FROM Candidats WHERE id_user = :id_user LIMIT 1');
		$existsStmt->execute(['id_user' => $candidateId]);
		if ($existsStmt->fetchColumn() === false) {
			return 'not_found';
		}

		$this->pdo->beginTransaction();

		try {
			$deleteAjouter = $this->pdo->prepare('DELETE FROM Ajouter_favoris WHERE id_user = :id_user');
			$deleteAjouter->execute(['id_user' => $candidateId]);

			$deletePostuler = $this->pdo->prepare('DELETE FROM Postuler WHERE id_user = :id_user');
			$deletePostuler->execute(['id_user' => $candidateId]);

			$deleteCandidat = $this->pdo->prepare('DELETE FROM Candidats WHERE id_user = :id_user');
			$deleteCandidat->execute(['id_user' => $candidateId]);

			$deleteUser = $this->pdo->prepare('DELETE FROM Utilisateurs WHERE id_user = :id_user');
			$deleteUser->execute(['id_user' => $candidateId]);

			$this->pdo->commit();
			return true;
		} catch (\Throwable $exception) {
			if ($this->pdo->inTransaction()) {
				$this->pdo->rollBack();
			}

			return false;
		}
	}
	

}

