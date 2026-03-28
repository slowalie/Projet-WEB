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

    
}
