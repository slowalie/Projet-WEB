<?php

declare(strict_types=1);

require_once __DIR__ . '/controller.php';
require_once __DIR__ . '/../models/offresModel.php';

use App\Models\OffresModel;
use App\Models\Database;


class detailOffresController extends Controller
{
    private OffresModel $offresModel;

    public function __construct($twig)
    {
        parent::__construct($twig);
        $database = new Database('localhost', 'root', 'A2#DevWeb!', 'ideastage_BDD');
        $this->offresModel = new OffresModel($database);
    }

    public function index(int $id_offres): string
    {
        $offre = $this->offresModel->getOffreById($id_offres);
        
        return $this->render('detail-offre.twig.html', [
            'page' => 'offres',
            'offre' => $offre,
            
        ]);
    }
}