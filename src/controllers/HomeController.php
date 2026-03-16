<?php

class HomeController
{
    private $twig;

    public function __construct($twig)
    {
        $this->twig = $twig;
    }

    public function index()
    {
        return $this->twig->render('home.twig.html', [
            'page' => 'home'
        ]);
    }
}
