<?php

declare(strict_types=1);

require_once __DIR__ . '/controller.php';

class EspaceEntrepriseController extends Controller
{
    public function index(): string
    {
        return $this->render('Entreprise.twig.html', [
            'page' => 'Entreprise'
        ]);
    }
}
