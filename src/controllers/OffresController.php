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
        $offres = $this->offresModel->getOffres();
        return $this->render('offres.twig.html', [
            'page' => 'offres',
            'offres' => $offres,
            'total_offres' => count($offres)
        ]);
    }

    
}
