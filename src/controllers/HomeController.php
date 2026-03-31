<?php

declare(strict_types=1);

require_once __DIR__ . '/controller.php';
require_once __DIR__ . '/../models/offresModel.php';

use App\Models\OffresModel;
use App\Models\Database;


class HomeController extends Controller
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
        $villes = array_unique(array_column($offres, 'ville'));
        $featuredOffres = array_values(array_filter($offres, static function (array $offre): bool {
            $tag = (string) ($offre['tag'] ?? '');
            return $tag === 'une' || $tag === 'new';
        }));

        return $this->render('home.twig.html', [
            'page' => 'home',
            'offres' => $offres,
            'villes' => $villes,
            'featured_offres' => array_slice($featuredOffres, 0, 6),
        ]);
       
    }
}
