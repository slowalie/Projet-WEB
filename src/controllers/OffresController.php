<?php

declare(strict_types=1);

require_once __DIR__ . '/controller.php';
require_once __DIR__ . '/../models/offresModel.php';

use App\Models\OffresModel;
use App\Models\Database;


class OffresController extends Controller
{
    private OffresModel $offresModel;

    public function __construct($twig)
    {
        parent::__construct($twig);
        $database = new Database('localhost', 'root', 'A2#DevWeb!', 'ideastage_BDD');
        $this->offresModel = new OffresModel($database);
    }

    public function index()
    {
        $currentPage = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
        $searchQuery = trim((string) ($_GET['q'] ?? ''));
        $selectedVille = trim((string) ($_GET['ville'] ?? ''));
        $selectedSecteur = trim((string) ($_GET['secteur'] ?? ''));
        $selectedDuree = trim((string) ($_GET['duree'] ?? ''));
        $selectedType = trim((string) ($_GET['type'] ?? ''));

        $allOffres = $this->offresModel->getOffres();
        $offres = $this->offresModel->getOffres($searchQuery, $selectedVille, $selectedSecteur, $selectedDuree, $selectedType);

        $secteurs = array_unique(array_column($allOffres, 'secteur_offres'));
        $villes = array_unique(array_column($allOffres, 'ville'));

        sort($secteurs);
        sort($villes);

        return $this->render('offres.twig.html', [
            'page' => 'offres',
            'offres' => $offres,
            'total_offres' => count($offres),
            'currentPage' => $currentPage,
            'secteurs' => $secteurs,
            'villes' => $villes,
            'search_query' => $searchQuery,
            'selected_ville' => $selectedVille,
            'selected_secteur' => $selectedSecteur,
            'selected_duree' => $selectedDuree,
            'selected_type' => $selectedType

        ]);
    }
    public function publish()
    {
        $this->requireRole(['pilote', 'admin']);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->offresModel->addOffre(
                $_POST['nomoffrein'],
                $_POST['descriptionoffrein'],
                $_POST['typeoffrein'],
                $_POST['salaireoffrein'],
                $_POST['datedebutoffrein'],
                $_POST['dureeoffrein'],
                $_POST['entreprisoffrein'],
                $_POST['missionsoffrein'],
                $_POST['noteoffrein'],
                $_POST['secteuroffrein'],
                $_POST['profiloffrein'],
                $_POST['adresseoffrein'],
                $_POST['villeoffrein'],
                $_POST['tagoffrein'],
                $_POST['departementoffrein']
            );
            header('Location: /espace-pilote');
            exit();
        }
        
    }

    public function edit(int $idOffre): string
    {
        $this->requireRole(['pilote', 'admin']);

        $offre = $this->offresModel->getOffreById($idOffre);
        if ($offre === null) {
            http_response_code(404);
            return '<h1>404 - Offre introuvable</h1>';
        }

        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
            $payload = [
                'nom_offres' => trim((string) ($_POST['nom_offres'] ?? '')),
                'description_offres' => trim((string) ($_POST['description_offres'] ?? '')),
                'type_offres' => trim((string) ($_POST['type_offres'] ?? '')),
                'salaire_offres' => trim((string) ($_POST['salaire_offres'] ?? '')),
                'date_debut' => trim((string) ($_POST['date_debut'] ?? '')),
                'duree_offres' => trim((string) ($_POST['duree_offres'] ?? '')),
                'missions' => trim((string) ($_POST['missions'] ?? '')),
                'note' => trim((string) ($_POST['note'] ?? '')),
                'secteur_offres' => trim((string) ($_POST['secteur_offres'] ?? '')),
                'Profil_recherche' => trim((string) ($_POST['Profil_recherche'] ?? '')),
                'tag' => trim((string) ($_POST['tag'] ?? '')),
                'skils' => trim((string) ($_POST['skils'] ?? '')),
            ];

            if (
                $payload['nom_offres'] === ''
                || $payload['description_offres'] === ''
                || $payload['type_offres'] === ''
                || $payload['secteur_offres'] === ''
            ) {
                header('Location: /offre/' . $idOffre . '/edit?status=missing_fields');
                exit;
            }

            $success = $this->offresModel->updateOffre($idOffre, $payload);
            $status = $success ? 'success' : 'error';
            header('Location: /offre/' . $idOffre . '/edit?status=' . $status);
            exit;
        }

        return $this->render('edit-offre.twig.html', [
            'page' => 'offres',
            'offre' => $offre,
            'edit_status' => $_GET['status'] ?? null,
        ]);
    }

    
}
