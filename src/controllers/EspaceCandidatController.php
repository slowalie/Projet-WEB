<?php

declare(strict_types=1);

require_once __DIR__ . '/controller.php';

class EspaceCandidatController extends Controller
{
    public function index()
    {
        $this->requireRole(['etudiant', 'pilote', 'admin']);

        return $this->render('espace-candidat.twig.html', [
            'page' => 'espace-candidat'
        ]);
    }
}
