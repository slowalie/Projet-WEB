<?php

declare(strict_types=1);

require_once __DIR__ . '/controller.php';

class OffresController extends Controller
{
    public function index(): string
    {
        return $this->render('offres.twig.html', [
            'page' => 'offres'
        ]);
    }
}
