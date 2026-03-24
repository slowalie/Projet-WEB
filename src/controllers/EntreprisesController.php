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
        $entreprises = $this->entreprisesModel->getEntreprises();
        return $this->render('Entreprise.twig.html', [
            'page' => 'entreprises',
            'entreprises' => $entreprises,
            'total_entreprises' => count($entreprises),
            'currentPage' => $currentPage
        ]);
    }
}