<?php

declare(strict_types=1);

require_once __DIR__ . '/controller.php';

use App\Models\OffresModel;
use App\Models\EntreprisesModel;
use App\Models\LocalisationModel;
use App\Models\Database;    



class EspacePiloteController extends Controller
{
    public function index()
    {
        $this->requireRole(['pilote', 'admin']);

        $entreprisesModel = new EntreprisesModel(new Database('localhost', 'root', 'A2#DevWeb!', 'ideastage_BDD'));
        $entreprises = $entreprisesModel->getEntreprises();
        return $this->render('espace-pilote.twig.html', [
            'page' => 'espace-pilote',
            'entreprises' => $entreprises
        ]);
    }
}
