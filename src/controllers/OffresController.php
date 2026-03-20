<?php

declare(strict_types=1);

require_once __DIR__ . '/controller.php';
require_once __DIR__ . '/../models/offresModel.php';

class OffresController extends Controller
{   
    private OffresModel $offresModel;

    public function __construct(Environment $twig)
    {
        parent::__construct($twig);
        $this->offresModel = new OffresModel();
    }

    

    public function index(): string
    {
        return $this->render('offres.twig.html', [
            'page' => 'offres'
        ]);
    }
}
