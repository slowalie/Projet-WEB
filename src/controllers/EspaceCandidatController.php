<?php

class EspaceCandidatController
{
    private $twig;

    public function __construct($twig)
    {
        $this->twig = $twig;
    }

    public function index()
    {
        return $this->twig->render('espace-candidat.twig.html', [
            'page' => 'espace-candidat'
        ]);
    }
}
