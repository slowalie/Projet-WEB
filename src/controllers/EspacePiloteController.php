<?php

declare(strict_types=1);

require_once __DIR__ . '/controller.php';



class EspacePiloteController extends Controller
{
    public function index()
    {
        
        return $this->render('espace-pilote.twig.html', [
            'page' => 'espace-pilote'
        ]);
    }
}
