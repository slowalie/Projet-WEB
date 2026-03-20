<?php
declare(strict_types=1);

require_once __DIR__ . '/../src/controllers/controller.php';
require_once __DIR__ . '/../src/controllers/HomeController.php';
require_once __DIR__ . '/../src/controllers/EspaceCandidatController.php';
require_once __DIR__ . '/../src/controllers/TaskController.php';
require_once __DIR__ . '/../src/controllers/OffresController.php';
require_once __DIR__ . '/../src/controllers/EspacePiloteController.php';


use Twig\Environment;
use Twig\Loader\FilesystemLoader;

function createTwigEnvironment(): Environment
{
    $loader = new FilesystemLoader(__DIR__ . '/../templates');
    return new Environment($loader);
}

function normalizeRoute(string $requestUri): string
{
    $path = parse_url($requestUri, PHP_URL_PATH) ?? '/';
    $route = trim($path, '/');
    return $route === '' ? '/' : '/' . $route;
}

function dispatchRoute(string $requestUri, Environment $twig): string
{
    $route = normalizeRoute($requestUri);

    if ($route === '/' || $route === '/home') {
        $controller = new HomeController($twig);
        return $controller->index();
    }

    if ($route === '/espace-candidat') {
        $controller = new EspaceCandidatController($twig);
        return $controller->index();
    }

    if ($route === '/tasks') {
        $controller = new TaskController($twig);
        return $controller->index();
    }

    if ($route === '/offres') {
        $controller = new OffresController($twig);
        return $controller->index();
    }

    if ($route === '/espace-pilote') {
        $controller = new EspacePiloteController($twig);
        return $controller->index();
    }

    http_response_code(404);
    return '<h1>404 - Page non trouvee</h1>';
}

