<?php

declare(strict_types=1);

require_once __DIR__ . '/controller.php';
require_once __DIR__ . '/../models/entreprisesModel.php';

use App\Models\entreprisesModel;
use App\Models\Database;


class EntreprisesController extends Controller
{
    private entreprisesModel $entreprisesModel;

    public function __construct($twig)
    {
        parent::__construct($twig);
        $database = new Database('localhost', 'root', 'A2#DevWeb!', 'ideastage_BDD');
        $this->entreprisesModel = new entreprisesModel($database);
    }

    public function index()
    {
        $currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $searchQuery = trim((string) ($_GET['q'] ?? ''));
        $selectedVille = trim((string) ($_GET['ville'] ?? ''));

        $entreprises = $this->entreprisesModel->getEntreprises($searchQuery, $selectedVille);
        $villes = $this->entreprisesModel->getVilles();

        return $this->render('Entreprise.twig.html', [
            'page' => 'entreprises',
            'entreprises' => $entreprises,
            'total_entreprises' => count($entreprises),
            'currentPage' => $currentPage,
            'search_query' => $searchQuery,
            'selected_ville' => $selectedVille,
            'villes' => $villes,
            'company_delete_status' => $_GET['company_delete'] ?? null,
        ]);
    }

    public function edit(int $idEntreprise): string
    {
        $this->requireRole(['pilote', 'admin']);

        $entreprise = $this->entreprisesModel->getEntrepriseById($idEntreprise);
        if ($entreprise === null) {
            http_response_code(404);
            return '<h1>404 - Entreprise introuvable</h1>';
        }

        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
            $nom = trim((string) ($_POST['nom_entreprise'] ?? ''));
            $logo = trim((string) ($_POST['logo_entreprise'] ?? ''));
            $description = trim((string) ($_POST['description_entreprise'] ?? ''));
            $noteRaw = trim((string) ($_POST['note_entreprise'] ?? ''));

            if ($nom === '') {
                header('Location: /entreprise/' . $idEntreprise . '/edit?status=missing_fields');
                exit;
            }

            if ($noteRaw !== '' && !is_numeric($noteRaw)) {
                header('Location: /entreprise/' . $idEntreprise . '/edit?status=invalid_note');
                exit;
            }

            $note = $noteRaw === '' ? null : (float) $noteRaw;

            $updated = $this->entreprisesModel->updateEntreprise($idEntreprise, [
                'nom_entreprise' => $nom,
                'logo_entreprise' => $logo,
                'description_entreprise' => $description,
                'note_entreprise' => $note,
            ]);

            $status = $updated ? 'success' : 'error';
            header('Location: /entreprise/' . $idEntreprise . '/edit?status=' . $status);
            exit;
        }

        return $this->render('edit-entreprise.twig.html', [
            'page' => 'entreprises',
            'entreprise' => $entreprise,
            'edit_status' => $_GET['status'] ?? null,
        ]);
    }

    public function delete(int $idEntreprise): string
    {
        $this->requireRole(['pilote', 'admin']);

        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') {
            http_response_code(405);
            return '<h1>405 - Methode non autorisee</h1>';
        }

        try {
            $deleted = $this->entreprisesModel->deleteEntreprise($idEntreprise);
            $status = $deleted ? 'success' : 'error';
        } catch (\Throwable $exception) {
            $status = 'error';
        }

        header('Location: /entreprises?company_delete=' . $status);
        exit;
    }
}