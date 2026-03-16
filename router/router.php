<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../src/controllers/HomeController.php';
require_once __DIR__ . '/../src/controllers/EspaceCandidatController.php';

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

// Configurer Twig
$loader = new FilesystemLoader(__DIR__ . '/../templates');
$twig = new Environment($loader);

// Parser l'URL pour déterminer la page
$requestUri = $_SERVER['REQUEST_URI'];
$basePath = '/';

// Enlever le basePath pour obtenir la route relative
$route = str_replace($basePath, '', $requestUri);

$route = trim($route, '/');

// Router simple avec contrôleurs
if ($route == '' || $route == 'home') {
    $controller = new HomeController($twig);
    echo $controller->index();
} elseif ($route == 'espace-candidat') {
    $controller = new EspaceCandidatController($twig);
    echo $controller->index();
} else {
    // 404
    http_response_code(404);
    echo "Page non trouvée";
}

